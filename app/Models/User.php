<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'age',
        'gender',
        'religion',
        'caste',
        'income',
        'education',
        'location',
        'occupation',
        'bio',
        'profile_picture',
        'remember_login',
        'last_login_at',
        'last_login_ip',
        'last_login_user_agent',
        'status',
        'deletion_reason',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'age' => 'integer',
            'income' => 'integer',
            'remember_login' => 'boolean',
            'last_login_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    /**
     * Get the user's preferences
     */
    public function preferences()
    {
        return $this->hasOne(Preference::class);
    }

    /**
     * Get the user's matches
     */
    public function matches()
    {
        return $this->belongsToMany(User::class, 'matches', 'user_id', 'matched_user_id')
                    ->withTimestamps();
    }

    /**
     * Get the user's matched users (reverse relationship)
     */
    public function matchedBy()
    {
        return $this->belongsToMany(User::class, 'matches', 'matched_user_id', 'user_id')
                    ->withTimestamps();
    }

    /**
     * Get all matches (both directions)
     */
    public function allMatches()
    {
        return $this->matches()->union($this->matchedBy());
    }

    /**
     * Get all matches with pagination (both directions)
     */
    public function getAllMatchesPaginated($limit = 10, $page = 1)
    {
        $matches = $this->matches()->with('preferences');
        $matchedBy = $this->matchedBy()->with('preferences');
        
        // Combine both queries
        $allMatches = $matches->union($matchedBy);
        
        // Since union doesn't support pagination directly, we'll use a different approach
        $matchIds = $this->matches()->pluck('users.id');
        $matchedByIds = $this->matchedBy()->pluck('users.id');
        $allMatchIds = $matchIds->merge($matchedByIds)->unique();
        
        return User::whereIn('id', $allMatchIds)
                  ->with('preferences')
                  ->paginate($limit, ['*'], 'page', $page);
    }

    /**
     * Get the user's likes
     */
    public function likes()
    {
        return $this->belongsToMany(User::class, 'likes', 'user_id', 'liked_user_id')
                    ->withTimestamps();
    }

    /**
     * Get users who liked this user
     */
    public function likedBy()
    {
        return $this->belongsToMany(User::class, 'likes', 'liked_user_id', 'user_id')
                    ->withTimestamps();
    }

    /**
     * Get sent messages
     */
    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'from_user_id');
    }

    /**
     * Get received messages
     */
    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'to_user_id');
    }

    /**
     * Check if user is matched with another user
     */
    public function isMatchedWith(User $user): bool
    {
        return $this->matches()->where('matched_user_id', $user->id)->exists() ||
               $this->matchedBy()->where('user_id', $user->id)->exists();
    }

    /**
     * Check if user has liked another user
     */
    public function hasLiked(User $user): bool
    {
        return $this->likes()->where('liked_user_id', $user->id)->exists();
    }

    /**
     * Get users blocked by this user
     */
    public function blockedUsers()
    {
        return $this->belongsToMany(User::class, 'user_blocks', 'blocker_id', 'blocked_user_id')
                    ->withPivot('reason', 'blocked_at')
                    ->withTimestamps();
    }

    /**
     * Get users who blocked this user
     */
    public function blockedByUsers()
    {
        return $this->belongsToMany(User::class, 'user_blocks', 'blocked_user_id', 'blocker_id')
                    ->withPivot('reason', 'blocked_at')
                    ->withTimestamps();
    }

    /**
     * Check if user is blocked by another user
     */
    public function isBlockedBy(User $user): bool
    {
        return UserBlock::isBlocked($user->id, $this->id);
    }

    /**
     * Check if user has blocked another user
     */
    public function hasBlocked(User $user): bool
    {
        return UserBlock::isBlocked($this->id, $user->id);
    }

    /**
     * Block a user
     */
    public function blockUser(User $user, string $reason = null): bool
    {
        if ($this->id === $user->id) {
            return false; // Can't block yourself
        }

        if ($this->hasBlocked($user)) {
            return false; // Already blocked
        }

        return UserBlock::create([
            'blocker_id' => $this->id,
            'blocked_user_id' => $user->id,
            'reason' => $reason,
        ]) ? true : false;
    }

    /**
     * Unblock a user
     */
    public function unblockUser(User $user): bool
    {
        return UserBlock::where('blocker_id', $this->id)
                        ->where('blocked_user_id', $user->id)
                        ->delete() > 0;
    }

    /**
     * Update last login information
     */
    public function updateLastLogin(Request $request = null): void
    {
        $this->update([
            'last_login_at' => now(),
            'last_login_ip' => $request ? $request->ip() : null,
            'last_login_user_agent' => $request ? $request->userAgent() : null,
        ]);
    }

    /**
     * Check if user account is active
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check if user account is suspended
     */
    public function isSuspended(): bool
    {
        return $this->status === 'suspended';
    }

    /**
     * Check if user account is deleted
     */
    public function isDeleted(): bool
    {
        return $this->status === 'deleted' || $this->deleted_at !== null;
    }

    /**
     * Soft delete user account
     */
    public function softDeleteAccount(string $reason = null): bool
    {
        // Delete all tokens
        $this->tokens()->delete();
        
        // Update status and reason
        $this->update([
            'status' => 'deleted',
            'deletion_reason' => $reason,
        ]);
        
        // Soft delete the user
        return $this->delete();
    }

    /**
     * Restore user account
     */
    public function restoreAccount(): bool
    {
        $this->update(['status' => 'active']);
        return $this->restore();
    }
}
