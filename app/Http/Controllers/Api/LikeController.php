<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    /**
     * Like a user
     */
    public function like(Request $request, User $user)
    {
        $currentUser = $request->user();

        // Check if already liked
        if ($currentUser->hasLiked($user)) {
            return response()->json([
                'message' => 'Already liked this user',
            ], 400);
        }

        // Create like
        $currentUser->likes()->attach($user->id);

        // Check if it's a mutual like (match)
        $isMatch = $user->hasLiked($currentUser);
        
        if ($isMatch) {
            // Create mutual match
            $currentUser->matches()->attach($user->id);
        }

        return response()->json([
            'message' => 'User liked successfully',
            'is_match' => $isMatch,
            'liked_user' => $user,
        ], 201);
    }

    /**
     * Unlike a user
     */
    public function unlike(Request $request, User $user)
    {
        $currentUser = $request->user();

        // Remove like
        $currentUser->likes()->detach($user->id);

        return response()->json([
            'message' => 'User unliked successfully',
        ]);
    }

    /**
     * Get all likes for the authenticated user
     */
    public function getLikes(Request $request)
    {
        $user = $request->user();
        
        $likes = $user->likes()->with('preferences')->get();
        $likedBy = $user->likedBy()->with('preferences')->get();

        return response()->json([
            'likes' => $likes,
            'liked_by' => $likedBy,
            'total_likes' => $likes->count(),
            'total_liked_by' => $likedBy->count(),
        ]);
    }
} 