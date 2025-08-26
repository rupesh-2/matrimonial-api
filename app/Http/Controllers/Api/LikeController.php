<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    /**
     * Like a user (legacy method - use discover/like instead)
     */
    public function like(Request $request, User $user)
    {
        return response()->json([
            'message' => 'This endpoint is deprecated. Please use /api/discover/like/{user_id} instead.',
        ], 410);
    }

    /**
     * Unlike a user (legacy method - use discover/unlike instead)
     */
    public function unlike(Request $request, User $user)
    {
        return response()->json([
            'message' => 'This endpoint is deprecated. Please use /api/discover/unlike/{user_id} instead.',
        ], 410);
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