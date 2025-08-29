<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserBlock;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class UserBlockController extends Controller
{
    /**
     * Block a user
     */
    public function blockUser(Request $request, User $user)
    {
        $request->validate([
            'reason' => 'nullable|string|max:500',
        ]);

        $currentUser = $request->user();

        // Check if trying to block yourself
        if ($currentUser->id === $user->id) {
            throw ValidationException::withMessages([
                'user' => ['You cannot block yourself.'],
            ]);
        }

        // Check if already blocked
        if ($currentUser->hasBlocked($user)) {
            return response()->json([
                'message' => 'User is already blocked.',
            ], 400);
        }

        // Block the user
        $blocked = $currentUser->blockUser($user, $request->reason);

        if ($blocked) {
            return response()->json([
                'message' => 'User blocked successfully.',
                'blocked_user' => $user->only(['id', 'name', 'email']),
                'reason' => $request->reason,
            ], 201);
        }

        return response()->json([
            'message' => 'Failed to block user.',
        ], 500);
    }

    /**
     * Unblock a user
     */
    public function unblockUser(Request $request, User $user)
    {
        $currentUser = $request->user();

        // Check if user is actually blocked
        if (!$currentUser->hasBlocked($user)) {
            return response()->json([
                'message' => 'User is not blocked.',
            ], 400);
        }

        // Unblock the user
        $unblocked = $currentUser->unblockUser($user);

        if ($unblocked) {
            return response()->json([
                'message' => 'User unblocked successfully.',
                'unblocked_user' => $user->only(['id', 'name', 'email']),
            ]);
        }

        return response()->json([
            'message' => 'Failed to unblock user.',
        ], 500);
    }

    /**
     * Get list of blocked users
     */
    public function getBlockedUsers(Request $request)
    {
        $currentUser = $request->user();
        $blockedUsers = $currentUser->blockedUsers()
                                   ->with('preferences')
                                   ->paginate(20);

        return response()->json([
            'blocked_users' => $blockedUsers,
        ]);
    }

    /**
     * Get list of users who blocked current user
     */
    public function getBlockedByUsers(Request $request)
    {
        $currentUser = $request->user();
        $blockedByUsers = $currentUser->blockedByUsers()
                                     ->with('preferences')
                                     ->paginate(20);

        return response()->json([
            'blocked_by_users' => $blockedByUsers,
        ]);
    }

    /**
     * Check if a user is blocked
     */
    public function checkBlockStatus(Request $request, User $user)
    {
        $currentUser = $request->user();

        $isBlocked = $currentUser->hasBlocked($user);
        $isBlockedBy = $currentUser->isBlockedBy($user);

        return response()->json([
            'user_id' => $user->id,
            'is_blocked' => $isBlocked,
            'is_blocked_by' => $isBlockedBy,
            'can_interact' => !$isBlocked && !$isBlockedBy,
        ]);
    }

    /**
     * Get block statistics
     */
    public function getBlockStats(Request $request)
    {
        $currentUser = $request->user();

        $blockedCount = $currentUser->blockedUsers()->count();
        $blockedByCount = $currentUser->blockedByUsers()->count();

        return response()->json([
            'blocked_count' => $blockedCount,
            'blocked_by_count' => $blockedByCount,
        ]);
    }
}
