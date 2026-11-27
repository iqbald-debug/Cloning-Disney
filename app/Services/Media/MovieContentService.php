<?php

namespace App\Services\Media;

use App\Models\Content;

class MovieContentService extends AbstractMediaService
{
    public function formatStreamMetadata(): array
    {
        return [
            'title' => 'Movie Streaming',
            'type' => 'movie',
            'quality' => '4K Ultra HD • Dolby Vision • 5.1',
            'duration' => $this->calculateDurationLabel(),
        ];
    }

    public function calculateDurationLabel(): string
    {
        $minutes = $this->payload['duration_minutes'] ?? 0;

        if ($minutes <= 0) {
            return 'Duration unavailable';
        }

        $hours = intdiv($minutes, 60);
        $remainingMinutes = $minutes % 60;

        return $hours > 0
            ? "{$hours}h {$remainingMinutes}m"
            : "{$remainingMinutes}m";
    }

    public function getMovieMetadata(Content $content): array
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
            ->where('type', 'movie')
            ->where('title', 'LIKE', "%{$query}%")
            ->get()
            ->toArray();
    }
}
