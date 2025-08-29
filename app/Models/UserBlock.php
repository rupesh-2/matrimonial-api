<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserBlock extends Model
{
    use HasFactory;

    protected $fillable = [
        'blocker_id',
        'blocked_user_id',
        'reason',
        'blocked_at'
    ];

    protected $casts = [
        'blocked_at' => 'datetime',
    ];

    /**
     * Get the user who blocked
     */
    public function blocker()
    {
        return $this->belongsTo(User::class, 'blocker_id');
    }

    /**
     * Get the user who was blocked
     */
    public function blockedUser()
    {
        return $this->belongsTo(User::class, 'blocked_user_id');
    }

    /**
     * Check if a user is blocked by another user
     */
    public static function isBlocked($blockerId, $blockedUserId): bool
    {
        return static::where('blocker_id', $blockerId)
                    ->where('blocked_user_id', $blockedUserId)
                    ->exists();
    }

    /**
     * Get all users blocked by a specific user
     */
    public static function getBlockedUsers($userId)
    {
        return static::where('blocker_id', $userId)
                    ->with('blockedUser')
                    ->get()
                    ->pluck('blockedUser');
    }

    /**
     * Get all users who blocked a specific user
     */
    public static function getBlockedByUsers($userId)
    {
        return static::where('blocked_user_id', $userId)
                    ->with('blocker')
                    ->get()
                    ->pluck('blocker');
    }
}
