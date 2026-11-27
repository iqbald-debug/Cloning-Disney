<?php

namespace App\Contracts;

use App\Models\Profile;
use App\Models\Content;
use Illuminate\Database\Eloquent\Collection;

interface WatchlistRepositoryInterface
{
    public function getByProfile(Profile $profile): Collection;

    public function add(
        Profile $profile,
        Content $content
    ): bool;

    public function remove(
        Profile $profile,
        Content $content
    ): bool;

    public function exists(
        Profile $profile,
        Content $content
    ): bool;
}
