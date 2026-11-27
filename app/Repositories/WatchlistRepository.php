<?php

namespace App\Repositories;

use App\Contracts\WatchlistRepositoryInterface;
use App\Models\Profile;
use App\Models\Content;
use Illuminate\Database\Eloquent\Collection;

class WatchlistRepository implements WatchlistRepositoryInterface
{
    private string $table = 'watchlists';

    public function getByProfile(Profile $profile): Collection
    {
        return $profile->contents()
            ->with('brand')
            ->get();
    }

    public function add(
        Profile $profile,
        Content $content
    ): bool {
        $watchlist = $profile->watchlists()->firstOrCreate([
            'content_id' => $content->id,
        ]);

        return $watchlist->wasRecentlyCreated;
    }

    public function remove(
        Profile $profile,
        Content $content
    ): bool {
        return $profile->watchlists()
            ->where('content_id', $content->id)
            ->delete() > 0;
    }

    public function exists(
        Profile $profile,
        Content $content
    ): bool {
        return $profile->watchlists()
            ->where('content_id', $content->id)
            ->exists();
    }
}
