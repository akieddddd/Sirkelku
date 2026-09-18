<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Thread extends Model
{
    use HasFactory;

    protected $fillable = [
        'community_id',
        'user_id',
        'hobby_id',
        'title',
        'body',
        'is_pinned',
    ];

    protected $casts = [
        'is_pinned' => 'boolean',
    ];

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

    public function comments(): HasMany
    {
        return $this->hasMany(ThreadComment::class);
    }

    public function rootComments(): HasMany
    {
        return $this->hasMany(ThreadComment::class)
            ->whereNull('parent_id')
            ->with(['user.school', 'replies.user.school', 'replies.replies.user.school'])
            ->oldest();
    }
}
