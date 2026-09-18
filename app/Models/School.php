<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class School extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_name',
        'city',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function communities(): HasMany
    {
        return $this->hasMany(Community::class);
    }
}
