<?php

namespace App\Repositories;

use App\Models\Content;
use Illuminate\Database\Eloquent\Collection;

class ContentRepository
{
    private string $model = Content::class;

    public function getAll(): Collection
    {
        return Content::with('brand')
            ->latest()
            ->get();
    }

    public function findBySlug(string $slug): ?Content
    {
        return Content::with('brand')
            ->where('slug', $slug)
            ->first();
    }

    public function search(array $filters): Collection
    {
        $query = Content::with('brand');

        if (!empty($filters['search'])) {
            $query->where(
                'title',
                'LIKE',
                '%' . $filters['search'] . '%'
            );
        }

        if (!empty($filters['brand'])) {
            $query->whereHas('brand', function ($builder) use ($filters) {
                $builder->where('slug', $filters['brand']);
            });
        }

        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        return $query->latest()->get();
    }

    public function getFeatured(): Collection
    {
        return Content::with('brand')
            ->where('is_featured', true)
            ->latest()
            ->get();
    }

    public function getByCategory(): Collection
    {
        return Content::with('brand')
            ->whereNotNull('category_row')
            ->get()
            ->groupBy('category_row');
    }

    public function getSuggested(Content $content): Collection
    {
        return Content::with('brand')
            ->where('brand_id', $content->brand_id)
            ->where('id', '!=', $content->id)
            ->limit(6)
            ->get();
    }
}
