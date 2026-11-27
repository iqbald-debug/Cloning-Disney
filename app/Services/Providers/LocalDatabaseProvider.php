<?php

namespace App\Services\Providers;

use App\Services\Media\AbstractMediaService;
use App\Models\Content;

class LocalDatabaseProvider extends AbstractMediaService
{
    protected function fetchSearchResults(string $query): array
    {
        return Content::with('brand')
            ->where('title', 'LIKE', "%{$query}%")
            ->orWhereHas('brand', function ($builder) use ($query) {
                $builder->where('name', 'LIKE', "%{$query}%");
            })
            ->get()
            ->toArray();
    }

    public function findBySlug(string $slug): ?array
    {
        return Content::with('brand')
            ->where('slug', $slug)
            ->first()
            ?->toArray();
    }
}
