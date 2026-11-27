<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Content extends Model
{
    protected $fillable = [
        'brand_id',
        'tmdb_id',
        'title',
        'slug',
        'type',
        'synopsis',
        'backdrop_url',
        'title_logo_url',
        'trailer_url',
        'release_year',
        'duration_minutes',
        'total_seasons',
        'age_rating',
        'video_quality',
        'is_featured',
        'category_row',
    ];

    protected function casts(): array
    {
        return [
            'tmdb_id' => 'integer',
            'release_year' => 'integer',
            'duration_minutes' => 'integer',
            'total_seasons' => 'integer',
            'is_featured' => 'boolean',
        ];
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function watchlists(): HasMany
    {
        return $this->hasMany(Watchlist::class);
    }

    public function profiles(): BelongsToMany
    {
        return $this->belongsToMany(
            Profile::class,
            'watchlists'
        )->withTimestamps();
    }
}
