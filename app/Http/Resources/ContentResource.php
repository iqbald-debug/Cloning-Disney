<?php

namespace App\Http\Resources;

use App\Services\Media\MovieContentService;
use App\Services\Media\SeriesContentService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $metadata = $this->type === 'series'
            ? app(SeriesContentService::class)->getSeriesMetadata($this->resource)
            : app(MovieContentService::class)->getMovieMetadata($this->resource);

        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'type' => $this->type,
            'synopsis' => $this->synopsis,
            'backdrop_url' => $this->backdrop_url,
            'title_logo_url' => $this->title_logo_url,
            'trailer_url' => $this->trailer_url,
            'release_year' => $this->release_year,
            'duration_minutes' => $this->duration_minutes,
            'total_seasons' => $this->total_seasons,
            'duration_label' => $metadata['duration'],
            'stream_metadata' => [
                'title' => $metadata['title'],
                'type' => $metadata['type'],
                'quality' => $metadata['quality'],
                'duration' => $metadata['duration'],
            ],
            'age_rating' => $this->age_rating,
            'video_quality' => $this->video_quality,
            'is_featured' => (bool) $this->is_featured,
            'category_row' => $this->category_row,
            'brand' => new BrandResource($this->whenLoaded('brand')),
        ];
    }
}