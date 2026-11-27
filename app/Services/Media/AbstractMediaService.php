<?php

namespace App\Services\Media;

use App\Contracts\MediaProviderInterface;
use App\Contracts\PlayableInterface;
use App\Models\Content;
use Illuminate\Support\Facades\Cache;

abstract class AbstractMediaService implements MediaProviderInterface, PlayableInterface
{
    protected string $baseUrl = '';

    protected int $cacheTtl = 3600;

    protected array $payload = [];

    private string $apiKey = '';

    public function __construct()
    {
        $this->apiKey = (string) config('services.tmdb.key', '');
    }

    public function search(string $query): array
    {
        $cacheKey = 'media_search_' . md5($query);

        return Cache::remember(
            $cacheKey,
            $this->cacheTtl,
            fn () => $this->fetchSearchResults($query)
        );
    }

    public function findBySlug(string $slug): ?array
    {
        $content = Content::with('brand')
            ->where('slug', $slug)
            ->first();

        return $content?->toArray();
    }

    protected function fetchSearchResults(string $query): array
    {
        return Content::with('brand')
            ->where('title', 'LIKE', "%{$query}%")
            ->get()
            ->toArray();
    }

    protected function formatMetadata(Content $content): array
    {
        return [
            'id' => $content->id,
            'title' => $content->title,
            'type' => $content->type,
            'year' => $content->release_year,
            'rating' => $content->age_rating,
            'quality' => $content->video_quality,
            'backdrop' => $content->backdrop_url,
            'trailer' => $content->trailer_url,
        ];
    }

    protected function getApiKey(): string
    {
        return $this->apiKey;
    }

    protected function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    public function formatStreamMetadata(): array
    {
        return [
            'provider' => static::class,
            'quality' => '4K Ultra HD',
            'format' => 'Video',
        ];
    }

    public function calculateDurationLabel(): string
    {
        return 'Duration unavailable';
    }
}