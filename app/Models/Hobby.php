<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Hobby extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'icon',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_hobbies')->withTimestamps();
    }

    public function communities(): HasMany
    {
        return $this->hasMany(Community::class);
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function threads(): HasMany
    {
        return $this->hasMany(Thread::class);
    }
}
