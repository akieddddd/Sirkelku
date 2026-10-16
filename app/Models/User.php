<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'school_id',
        'bio',
        'avatar_path',
        'is_private',
        'last_seen_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_private' => 'boolean',
            'last_seen_at' => 'datetime',
        ];
    }

    public function isOnline(): bool
    {
        return $this->last_seen_at && $this->last_seen_at->diffInMinutes(now()) <= 5;
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function hobbies(): BelongsToMany
    {
        return $this->belongsToMany(Hobby::class, 'user_hobbies')->withTimestamps();
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function communities(): BelongsToMany
    {
        return $this->belongsToMany(Community::class, 'community_members')
            ->withPivot('role', 'joined_at')
            ->withTimestamps();
    }

    public function threads(): HasMany
    {
        return $this->hasMany(Thread::class);
    }

    public function postLikes(): HasMany
    {
        return $this->hasMany(PostLike::class);
    }

    public function sentMatchRequests(): HasMany
    {
        return $this->hasMany(MatchRequest::class, 'sender_id');
    }

    public function receivedMatchRequests(): HasMany
    {
        return $this->hasMany(MatchRequest::class, 'receiver_id');
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(AppNotification::class, 'user_id')->latest();
    }

    public function postReports(): HasMany
    {
        return $this->hasMany(PostReport::class);
    }

    public function unreadNotificationsCount(): int
    {
        return $this->notifications()->where('is_read', false)->count();
    }

    public function unreadMessagesCount(): int
    {
        return $this->receivedMessages()->whereNull('read_at')->count();
    }

    public function isOnboarded(): bool
    {
        return !is_null($this->school_id) && $this->hobbies()->exists();
    }

    public function isMemberOf($communityId): bool
    {
        return $this->communities()->where('communities.id', is_object($communityId) ? $communityId->id : $communityId)->exists();
    }

    public function isAdminOf($communityId): bool
    {
        return $this->communities()
            ->where('communities.id', is_object($communityId) ? $communityId->id : $communityId)
            ->wherePivot('role', 'admin')
            ->exists();
    }

    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar_path && file_exists(public_path('storage/' . $this->avatar_path))) {
            return asset('storage/' . $this->avatar_path);
        }
        if ($this->avatar_path && filter_var($this->avatar_path, FILTER_VALIDATE_URL)) {
            return $this->avatar_path;
        }
        return 'https://api.dicebear.com/7.x/bottts/svg?seed=' . urlencode($this->username ?? $this->name ?? 'user');
    }

    public function friendIds(): array
    {
        $sent = MatchRequest::where('sender_id', $this->id)
            ->where('status', 'accepted')
            ->pluck('receiver_id')
            ->all();

        $received = MatchRequest::where('receiver_id', $this->id)
            ->where('status', 'accepted')
            ->pluck('sender_id')
            ->all();

        return array_values(array_unique(array_merge($sent, $received)));
    }

    public function isFriendsWith($userId): bool
    {
        $targetId = $userId instanceof User ? $userId->id : (int) $userId;

        if (!$targetId) {
            return false;
        }

        if ($this->id === $targetId) {
            return true;
        }

        return MatchRequest::where('status', 'accepted')
            ->where(function ($q) use ($targetId) {
                $q->where(function ($sub) use ($targetId) {
                    $sub->where('sender_id', $this->id)->where('receiver_id', $targetId);
                })->orWhere(function ($sub) use ($targetId) {
                    $sub->where('sender_id', $targetId)->where('receiver_id', $this->id);
                });
            })
            ->exists();
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }
}
