<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'community_id',
        'hobby_id',
        'content',
        'image_path',
    ];

    protected function casts(): array
    {
        return [
            'is_flagged' => 'boolean',
            'reports_count' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function community(): BelongsTo
    {
        return $this->belongsTo(Community::class);
    }

    public function hobby(): BelongsTo
    {
        return $this->belongsTo(Hobby::class);
    }

    public function likes(): HasMany
    {
        return $this->hasMany(PostLike::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(PostComment::class)->latest();
    }

    public function reports(): HasMany
    {
        return $this->hasMany(PostReport::class);
    }

    public function isReportedBy($user): bool
    {
        if (!$user) return false;
        $userId = is_object($user) ? $user->id : $user;
        return $this->reports()->where('user_id', $userId)->exists();
    }

    public function isLikedBy($user): bool
    {
        if (!$user) return false;
        $userId = is_object($user) ? $user->id : $user;
        return $this->likes()->where('user_id', $userId)->exists();
    }

    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image_path) {
            return null;
        }
        if (filter_var($this->image_path, FILTER_VALIDATE_URL)) {
            return $this->image_path;
        }
        if (app()->environment('testing')) {
            return asset('storage/' . $this->image_path);
        }
        if (file_exists(public_path('storage/' . $this->image_path)) || file_exists(storage_path('app/public/' . $this->image_path))) {
            return asset('storage/' . $this->image_path);
        }
        return null;
    }

    public function scopeVisibleTo($query, ?User $viewer)
    {
        if (! $viewer) {
            return $query->whereHas('user', function ($q) {
                $q->where('is_private', false);
            });
        }

        $friendIds = $viewer->friendIds();

        return $query->where(function ($q) use ($viewer, $friendIds) {
            $q->where('posts.user_id', $viewer->id)
              ->orWhereHas('user', function ($uq) {
                  $uq->where('is_private', false);
              });

            if (!empty($friendIds)) {
                $q->orWhereIn('posts.user_id', $friendIds);
            }
        });
    }

    public function isVisibleTo(?User $viewer): bool
    {
        $author = $this->relationLoaded('user') ? $this->user : $this->user()->first();
        if (!$author) {
            return false;
        }

        if (!$author->is_private) {
            return true;
        }

        if (!$viewer) {
            return false;
        }

        if ($viewer->id === $author->id) {
            return true;
        }

        return $viewer->isFriendsWith($author->id);
    }
}
