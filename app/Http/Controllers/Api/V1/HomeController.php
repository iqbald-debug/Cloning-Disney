<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\BrandResource;
use App\Http\Resources\ContentResource;
use App\Models\Brand;
use App\Repositories\ContentRepository;
use Illuminate\Http\JsonResponse;

class HomeController extends Controller
{
    public function __construct(
        private ContentRepository $contentRepository
    ) {}

    public function index(): JsonResponse
    {
        $brands = Brand::orderBy('sort_order')->get();
        $featured = $this->contentRepository->getFeatured();
        $rows = $this->contentRepository->getByCategory()->map(
            fn ($items) => ContentResource::collection($items)
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Data halaman utama Disney+ berhasil dimuat.',
            'data' => [
                'featured_banners' => ContentResource::collection($featured),
                'brands' => BrandResource::collection($brands),
                'rows' => $rows,
            ],
        ]);
    }
}