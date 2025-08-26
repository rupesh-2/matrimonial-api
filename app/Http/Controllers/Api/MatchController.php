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

        // Create match
        $currentUser->matches()->attach($user->id);

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
} 