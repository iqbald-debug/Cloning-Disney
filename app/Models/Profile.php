<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Profile extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'avatar_url',
        'is_kids',
    ];

    protected function casts(): array
    {
        return [
            'is_kids' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function watchlists(): HasMany
    {
        return $this->hasMany(Watchlist::class);
    }

    public function contents(): BelongsToMany
    {
        return $this->belongsToMany(
            Content::class,
            'watchlists'
        )->withTimestamps();
    }
}
