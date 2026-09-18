<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Community extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'hobby_id',
        'school_id',
        'banner_path',
        'avatar_path',
        'created_by',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function hobby(): BelongsTo
    {
        return $this->belongsTo(Hobby::class);
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'community_members')
            ->withPivot('role', 'joined_at')
            ->withTimestamps();
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class)->latest();
    }

    public function threads(): HasMany
    {
        return $this->hasMany(Thread::class)->latest();
    }

    public function getBannerUrlAttribute(): string
    {
        if ($this->banner_path && file_exists(public_path('storage/' . $this->banner_path))) {
            return asset('storage/' . $this->banner_path);
        }
        if ($this->banner_path && filter_var($this->banner_path, FILTER_VALIDATE_URL)) {
            return $this->banner_path;
        }
        return 'https://images.unsplash.com/photo-1511632765486-a01980e01a18?w=1200&auto=format&fit=crop&q=80';
    }

    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar_path && file_exists(public_path('storage/' . $this->avatar_path))) {
            return asset('storage/' . $this->avatar_path);
        }
        if ($this->avatar_path && filter_var($this->avatar_path, FILTER_VALIDATE_URL)) {
            return $this->avatar_path;
        }
        return 'https://api.dicebear.com/7.x/identicon/svg?seed=' . urlencode($this->slug);
    }
}
