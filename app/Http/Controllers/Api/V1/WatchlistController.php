<?php

namespace App\Http\Controllers\Api\V1;

use App\Contracts\WatchlistRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\ToggleWatchlistRequest;
use App\Http\Resources\ContentResource;
use App\Models\Content;
use App\Models\Profile;
use Illuminate\Http\JsonResponse;

class WatchlistController extends Controller
{
    public function __construct(
        private WatchlistRepositoryInterface $watchlistRepository
    ) {}

    public function index(Profile $profile): JsonResponse
    {
        $contents = $this->watchlistRepository->getByProfile($profile);

        return response()->json([
            'status' => 'success',
            'message' => 'Daftar watchlist berhasil diambil.',
            'data' => ContentResource::collection($contents),
        ]);
    }

    public function toggle(ToggleWatchlistRequest $request, Profile $profile): JsonResponse
    {
        $content = Content::findOrFail($request->validated('content_id'));

        if ($this->watchlistRepository->exists($profile, $content)) {
            $this->watchlistRepository->remove($profile, $content);
            $action = 'removed';
        } else {
            $this->watchlistRepository->add($profile, $content);
            $action = 'added';
        }

        return response()->json([
            'status' => 'success',
            'message' => $action === 'added'
                ? 'Berhasil ditambahkan ke Watchlist.'
                : 'Dihapus dari Watchlist.',
            'data' => [
                'action' => $action,
                'in_watchlist' => $action === 'added',
            ],
        ]);
    }
}