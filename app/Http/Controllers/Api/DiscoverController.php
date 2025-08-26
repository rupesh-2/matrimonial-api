<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\MatchmakingService;
use Illuminate\Http\Request;

class DiscoverController extends Controller
{
    protected $matchmakingService;

    public function __construct(MatchmakingService $matchmakingService)
    {
        $this->matchmakingService = $matchmakingService;
    }

    /**
     * Get discover recommendations for the authenticated user
     */
    public function getDiscoverProfiles(Request $request)
    {
        $request->validate([
            'limit' => 'sometimes|integer|min:1|max:50',
            'page' => 'sometimes|integer|min:1',
        ]);

        $limit = $request->get('limit', 10);
        $page = $request->get('page', 1);
        $user = $request->user();
        
        $recommendations = $this->matchmakingService->getDiscoverRecommendations($user, $limit, $page);

        return response()->json([
            'discover_profiles' => $recommendations['profiles'],
            'total' => $recommendations['total'],
            'current_page' => $page,
            'per_page' => $limit,
            'has_more' => $recommendations['has_more'],
        ]);
    }

    /**
     * Like a profile from discover section
     */
    public function likeProfile(Request $request, $userId)
    {
        $currentUser = $request->user();
        $targetUser = \App\Models\User::findOrFail($userId);

        // Check if trying to like self
        if ($currentUser->id === $targetUser->id) {
            return response()->json([
                'message' => 'Cannot like your own profile',
            ], 400);
        }

        // Check if already liked
        if ($currentUser->hasLiked($targetUser)) {
            return response()->json([
                'message' => 'Already liked this profile',
            ], 400);
        }

        // Check if already matched
        if ($currentUser->isMatchedWith($targetUser)) {
            return response()->json([
                'message' => 'Already matched with this user',
            ], 400);
        }

        // Create like
        $currentUser->likes()->attach($targetUser->id);

        // Check if it's a mutual like (match)
        $isMatch = $targetUser->hasLiked($currentUser);
        
        if ($isMatch) {
            // Create mutual match
            $currentUser->matches()->attach($targetUser->id);
            
            return response()->json([
                'message' => 'It\'s a match! 🎉',
                'is_match' => true,
                'matched_user' => $targetUser,
            ], 201);
        }

        return response()->json([
            'message' => 'Profile liked successfully',
            'is_match' => false,
            'liked_user' => $targetUser,
        ], 201);
    }

    /**
     * Unlike a profile from discover section
     */
    public function unlikeProfile(Request $request, $userId)
    {
        $currentUser = $request->user();
        $targetUser = \App\Models\User::findOrFail($userId);

        // Remove like
        $currentUser->likes()->detach($targetUser->id);

        // If they were matched, remove the match from both directions
        if ($currentUser->isMatchedWith($targetUser)) {
            $currentUser->matches()->detach($targetUser->id);
            $targetUser->matches()->detach($currentUser->id);
        }

        return response()->json([
            'message' => 'Profile unliked successfully',
        ]);
    }

    /**
     * Get profiles that liked the current user
     */
    public function getLikedByProfiles(Request $request)
    {
        $request->validate([
            'limit' => 'sometimes|integer|min:1|max:50',
            'page' => 'sometimes|integer|min:1',
        ]);

        $limit = $request->get('limit', 10);
        $page = $request->get('page', 1);
        $user = $request->user();

        $likedBy = $user->likedBy()
                       ->with('preferences')
                       ->whereNotIn('users.id', $user->likes()->pluck('users.id')) // Exclude mutual likes
                       ->paginate($limit, ['*'], 'page', $page);

        return response()->json([
            'liked_by_profiles' => $likedBy->items(),
            'total' => $likedBy->total(),
            'current_page' => $likedBy->currentPage(),
            'per_page' => $likedBy->perPage(),
            'has_more' => $likedBy->hasMorePages(),
        ]);
    }
}
