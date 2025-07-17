<?php

namespace App\Services;

use App\Models\User;
use App\Models\Preference;
use Illuminate\Support\Facades\DB;

class MatchmakingService
{
    /**
     * Get recommendations for a user using collaborative filtering
     */
    public function getRecommendations(User $user, int $limit = 10): array
    {
        // Get user preferences
        $preferences = $user->preferences;
        
        if (!$preferences) {
            // If no preferences, return random users
            return User::where('id', '!=', $user->id)
                      ->where('gender', '!=', $user->gender)
                      ->inRandomOrder()
                      ->limit($limit)
                      ->get()
                      ->toArray();
        }

        // Get all potential matches (excluding self and existing matches)
        $potentialMatches = User::where('id', '!=', $user->id)
                               ->where('gender', '!=', $user->gender)
                               ->whereNotIn('id', $user->matches()->pluck('matched_user_id'))
                               ->whereNotIn('id', $user->matchedBy()->pluck('user_id'))
                               ->get();

        $recommendations = [];

        foreach ($potentialMatches as $match) {
            $score = $this->calculateMatchScore($user, $match, $preferences);
            
            if ($score > 0) {
                $recommendations[] = [
                    'user' => $match,
                    'score' => $score,
                    'compatibility_percentage' => min(100, $score * 100)
                ];
            }
        }

        // Sort by score (highest first) and take top N
        usort($recommendations, function ($a, $b) {
            return $b['score'] <=> $a['score'];
        });

        return array_slice($recommendations, 0, $limit);
    }

    /**
     * Calculate match score using weighted collaborative filtering
     */
    private function calculateMatchScore(User $user, User $match, Preference $preferences): float
    {
        $totalScore = 0;
        $totalWeight = 0;

        // Age compatibility
        if ($preferences->age_weight > 0 && $match->age && $preferences->preferred_age_min && $preferences->preferred_age_max) {
            $ageScore = $this->calculateAgeScore($match->age, $preferences->preferred_age_min, $preferences->preferred_age_max);
            $totalScore += $ageScore * $preferences->age_weight;
            $totalWeight += $preferences->age_weight;
        }

        // Gender compatibility
        if ($preferences->gender_weight > 0 && $preferences->preferred_gender) {
            $genderScore = $match->gender === $preferences->preferred_gender ? 1.0 : 0.0;
            $totalScore += $genderScore * $preferences->gender_weight;
            $totalWeight += $preferences->gender_weight;
        }

        // Religion compatibility
        if ($preferences->religion_weight > 0 && $match->religion && $preferences->preferred_religion) {
            $religionScore = $match->religion === $preferences->preferred_religion ? 1.0 : 0.0;
            $totalScore += $religionScore * $preferences->religion_weight;
            $totalWeight += $preferences->religion_weight;
        }

        // Caste compatibility
        if ($preferences->caste_weight > 0 && $match->caste && $preferences->preferred_caste) {
            $casteScore = $match->caste === $preferences->preferred_caste ? 1.0 : 0.0;
            $totalScore += $casteScore * $preferences->caste_weight;
            $totalWeight += $preferences->caste_weight;
        }

        // Income compatibility
        if ($preferences->income_weight > 0 && $match->income && $preferences->preferred_income_min && $preferences->preferred_income_max) {
            $incomeScore = $this->calculateIncomeScore($match->income, $preferences->preferred_income_min, $preferences->preferred_income_max);
            $totalScore += $incomeScore * $preferences->income_weight;
            $totalWeight += $preferences->income_weight;
        }

        // Education compatibility
        if ($preferences->education_weight > 0 && $match->education && $preferences->preferred_education) {
            $educationScore = $match->education === $preferences->preferred_education ? 1.0 : 0.0;
            $totalScore += $educationScore * $preferences->education_weight;
            $totalWeight += $preferences->education_weight;
        }

        // Location compatibility
        if ($preferences->location_weight > 0 && $match->location && $preferences->preferred_location) {
            $locationScore = $match->location === $preferences->preferred_location ? 1.0 : 0.0;
            $totalScore += $locationScore * $preferences->location_weight;
            $totalWeight += $preferences->location_weight;
        }

        // Occupation compatibility
        if ($preferences->occupation_weight > 0 && $match->occupation && $preferences->preferred_occupation) {
            $occupationScore = $match->occupation === $preferences->preferred_occupation ? 1.0 : 0.0;
            $totalScore += $occupationScore * $preferences->occupation_weight;
            $totalWeight += $preferences->occupation_weight;
        }

        // Collaborative filtering bonus based on likes
        $collaborativeBonus = $this->calculateCollaborativeBonus($user, $match);
        $totalScore += $collaborativeBonus * 0.5; // 50% weight for collaborative filtering
        $totalWeight += 0.5;

        return $totalWeight > 0 ? $totalScore / $totalWeight : 0;
    }

    /**
     * Calculate age compatibility score
     */
    private function calculateAgeScore(int $age, int $minAge, int $maxAge): float
    {
        if ($age >= $minAge && $age <= $maxAge) {
            return 1.0;
        }
        
        // Gradual decrease outside preferred range
        $distance = min(abs($age - $minAge), abs($age - $maxAge));
        return max(0, 1 - ($distance * 0.1)); // 10% decrease per year outside range
    }

    /**
     * Calculate income compatibility score
     */
    private function calculateIncomeScore(int $income, int $minIncome, int $maxIncome): float
    {
        if ($income >= $minIncome && $income <= $maxIncome) {
            return 1.0;
        }
        
        // Gradual decrease outside preferred range
        $distance = min(abs($income - $minIncome), abs($income - $maxIncome));
        return max(0, 1 - ($distance / max($maxIncome, 1)) * 0.5); // 50% decrease based on percentage difference
    }

    /**
     * Calculate collaborative filtering bonus based on user behavior
     */
    private function calculateCollaborativeBonus(User $user, User $match): float
    {
        // Get users who liked the match
        $usersWhoLikedMatch = $match->likedBy()->pluck('users.id');
        
        // Get users who the current user has liked
        $usersLikedByCurrentUser = $user->likes()->pluck('users.id');
        
        // Find intersection (users who both liked the match and were liked by current user)
        $intersection = $usersWhoLikedMatch->intersect($usersLikedByCurrentUser);
        
        if ($intersection->count() === 0) {
            return 0.0;
        }
        
        // Calculate similarity based on common likes
        $similarity = $intersection->count() / max($usersLikedByCurrentUser->count(), 1);
        
        return min(1.0, $similarity * 2); // Cap at 1.0, boost by factor of 2
    }

    /**
     * Get mutual matches for a user
     */
    public function getMutualMatches(User $user): array
    {
        $matches = $user->matches()->with('preferences')->get();
        $matchedBy = $user->matchedBy()->with('preferences')->get();
        
        return $matches->merge($matchedBy)->unique('id')->values()->toArray();
    }
} 