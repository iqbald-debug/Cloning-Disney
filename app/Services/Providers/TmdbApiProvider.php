<?php

namespace App\Services\Providers;

use App\Services\Media\AbstractMediaService;
use Illuminate\Support\Facades\Http;

class TmdbApiProvider extends AbstractMediaService
{
    protected string $baseUrl = 'https://api.themoviedb.org/3';

    protected function fetchSearchResults(string $query): array
    {
        if ($this->getApiKey() === '') {
            return [];
        }

        $response = Http::timeout(10)
            ->get($this->baseUrl . '/search/multi', [
                'api_key' => $this->getApiKey(),
                'query' => $query,
            ]);

        if (!$response->successful()) {
            return [];
        }

        return $response->json('results', []);
    }

    public function findBySlug(string $slug): ?array
    {
        return null;
    }
}
