<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContentFilterRequest;
use App\Http\Resources\ContentResource;
use App\Repositories\ContentRepository;
use App\Services\Media\MediaResolver;
use Illuminate\Http\JsonResponse;

class ContentController extends Controller
{
    public function __construct(
        private ContentRepository $contentRepository,
        private MediaResolver $mediaResolver
    ) {}

    public function index(ContentFilterRequest $request): JsonResponse
    {
        $filters = $request->validated();
        $contents = $this->contentRepository->search($filters);

        $tmdbFallback = [];
        if ($contents->isEmpty() && !empty($filters['search']) && config('services.tmdb.key')) {
            $tmdbFallback = $this->mediaResolver
                ->resolve('tmdb')
                ->search($filters['search']);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Daftar konten berhasil diambil.',
            'data' => ContentResource::collection($contents),
            'external_results' => $tmdbFallback,
        ]);
    }

    public function show(string $slug): JsonResponse
    {
        $content = $this->contentRepository->findBySlug($slug);

        if (!$content) {
            return response()->json([
                'status' => 'error',
                'message' => 'Konten tidak ditemukan.',
                'data' => null,
            ], 404);
        }

        $suggested = $this->contentRepository->getSuggested($content);

        return response()->json([
            'status' => 'success',
            'message' => 'Detail konten berhasil diambil.',
            'data' => [
                'detail' => new ContentResource($content),
                'suggested' => ContentResource::collection($suggested),
            ],
        ]);
    }
}