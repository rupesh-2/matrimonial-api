<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

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
}
