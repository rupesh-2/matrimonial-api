<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class MatchController extends Controller
{
    /**
     * Create a match with another user
     */
    public function createMatch(Request $request, User $user)
    {
        $currentUser = $request->user();

        // Check if already matched
        if ($currentUser->isMatchedWith($user)) {
            return response()->json([
                'message' => 'Already matched with this user',
            ], 400);
        }

        // Check if both users have liked each other
        if (!$currentUser->hasLiked($user) || !$user->hasLiked($currentUser)) {
            return response()->json([
                'message' => 'Both users must like each other to create a match',
            ], 400);
        }

        // Create match in both directions
        $currentUser->matches()->attach($user->id);
        $user->matches()->attach($currentUser->id);

        return response()->json([
            'message' => 'Match created successfully',
            'matched_user' => $user,
        ], 201);
    }

    /**
     * Get all mutual matches for the authenticated user
     */
    public function getMatches(Request $request)
    {
        $request->validate([
            'limit' => 'sometimes|integer|min:1|max:50',
            'page' => 'sometimes|integer|min:1',
        ]);

        $limit = $request->get('limit', 10);
        $page = $request->get('page', 1);
        $user = $request->user();
        
        // Get mutual matches (users who liked each other)
        // Get users that the current user has liked AND who have liked the current user back
        $likedUserIds = $user->likes()->pluck('liked_user_id');
        $likedByUserIds = $user->likedBy()->pluck('user_id');
        
        // Find intersection (mutual likes)
        $mutualUserIds = $likedUserIds->intersect($likedByUserIds);
        
        $mutualMatches = User::whereIn('id', $mutualUserIds)
                             ->with('preferences')
                             ->paginate($limit, ['*'], 'page', $page);

        return response()->json([
            'matches' => $mutualMatches->items(),
            'total' => $mutualMatches->total(),
            'current_page' => $mutualMatches->currentPage(),
            'per_page' => $mutualMatches->perPage(),
            'has_more' => $mutualMatches->hasMorePages(),
        ]);
    }

    /**
     * Remove a match with another user
     */
    public function unmatch(Request $request, User $user)
    {
        $currentUser = $request->user();

        // Remove match from both directions
        $currentUser->matches()->detach($user->id);
        $user->matches()->detach($currentUser->id);

        return response()->json([
            'message' => 'Match removed successfully',
        ]);
    }

    /**
     * Get match statistics for the authenticated user
     */
    public function getMatchStats(Request $request)
    {
        $user = $request->user();
        
        $totalMatches = $user->matches()->count();
        $totalLikes = $user->likes()->count();
        $totalLikedBy = $user->likedBy()->count();
        
        // Get recent matches (last 30 days)
        $recentMatches = $user->matches()
                             ->where('matches.created_at', '>=', now()->subDays(30))
                             ->count();
        
        return response()->json([
            'stats' => [
                'total_matches' => $totalMatches,
                'total_likes_given' => $totalLikes,
                'total_likes_received' => $totalLikedBy,
                'recent_matches_30_days' => $recentMatches,
                'match_rate' => $totalLikedBy > 0 ? round(($totalMatches / $totalLikedBy) * 100, 2) : 0,
            ]
        ]);
    }

    /**
     * Get a specific match by user ID
     */
    public function getMatch(Request $request, User $user)
    {
        $currentUser = $request->user();
        
        // Check if they are actually matched
        if (!$currentUser->isMatchedWith($user)) {
            return response()->json([
                'message' => 'No match found with this user',
            ], 404);
        }
        
        // Get the match with additional information
        $match = $currentUser->matches()
                           ->with(['preferences'])
                           ->where('users.id', $user->id)
                           ->select('users.*', 'matches.created_at as matched_at')
                           ->first();
        
        if (!$match) {
            return response()->json([
                'message' => 'Match not found',
            ], 404);
        }
        
        return response()->json([
            'match' => [
                'id' => $match->id,
                'name' => $match->name,
                'email' => $match->email,
                'age' => $match->age,
                'gender' => $match->gender,
                'religion' => $match->religion,
                'caste' => $match->caste,
                'income' => $match->income,
                'education' => $match->education,
                'location' => $match->location,
                'occupation' => $match->occupation,
                'bio' => $match->bio,
                'profile_picture' => $match->profile_picture,
                'preferences' => $match->preferences,
                'matched_at' => $match->matched_at,
                'user_created_at' => $match->created_at,
                'user_updated_at' => $match->updated_at,
            ]
        ]);
    }
} 