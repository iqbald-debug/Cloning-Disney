<?php

namespace App\Contracts;

use App\Models\Profile;
use App\Models\Content;

interface WatchlistableInterface
{
    public function addToWatchlist(
        Profile $profile,
        Content $content
    ): bool;

    public function removeFromWatchlist(
        Profile $profile,
        Content $content
    ): bool;

    public function isInWatchlist(
        Profile $profile,
        Content $content
    ): bool;
}
