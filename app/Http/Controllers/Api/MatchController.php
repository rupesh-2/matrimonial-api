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
     * Get all matches for the authenticated user
     */
    public function getMatches(Request $request)
    {
        $user = $request->user();
        
        $matches = $user->matches()->with('preferences')->get();
        $matchedBy = $user->matchedBy()->with('preferences')->get();
        
        $allMatches = $matches->merge($matchedBy)->unique('id')->values();

        return response()->json([
            'matches' => $allMatches,
            'total' => $allMatches->count(),
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