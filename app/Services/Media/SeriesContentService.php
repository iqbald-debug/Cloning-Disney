<?php

namespace App\Services\Media;

use App\Models\Content;

class SeriesContentService extends AbstractMediaService
{
    public function formatStreamMetadata(): array
    {
        return [
            'title' => 'Series Streaming',
            'type' => 'series',
            'quality' => '4K Ultra HD • Dolby Vision • 5.1',
            'duration' => $this->calculateDurationLabel(),
        ];
    }

    public function calculateDurationLabel(): string
    {
        $seasons = (int) ($this->payload['total_seasons'] ?? 0);
        $episodes = (int) ($this->payload['total_episodes'] ?? 0);

        if ($seasons <= 0) {
            return 'Season information unavailable';
        }

        $seasonText = $seasons > 1 ? "{$seasons} Seasons" : "{$seasons} Season";

        return $episodes > 0
            ? "{$seasonText} • {$episodes} Episodes"
            : $seasonText;
    }

    public function getSeriesMetadata(Content $content): array
    {
        $this->payload = $content->toArray();

        return array_merge(
            $this->formatStreamMetadata(),
            [
                'content' => $this->formatMetadata($content),
            ]
        );
    }

    protected function fetchSearchResults(string $query): array
    {
        return Content::with('brand')
            ->where('type', 'series')
            ->where('title', 'LIKE', "%{$query}%")
            ->get()
            ->toArray();
    }
}