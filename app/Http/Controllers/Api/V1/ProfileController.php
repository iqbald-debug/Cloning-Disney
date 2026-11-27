<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProfileResource;
use App\Models\Profile;
use Illuminate\Http\JsonResponse;

class ProfileController extends Controller
{
    public function index(): JsonResponse
    {
        $profiles = Profile::all();

        return response()->json([
            'status' => 'success',
            'message' => 'Daftar profil berhasil diambil.',
            'data' => ProfileResource::collection($profiles),
        ]);
    }
}