<?php

use App\Http\Controllers\Api\V1\ContentController;
use App\Http\Controllers\Api\V1\HomeController;
use App\Http\Controllers\Api\V1\ProfileController;
use App\Http\Controllers\Api\V1\WatchlistController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->group(function () {
    Route::get('/profiles', [ProfileController::class, 'index']);
    Route::get('/home', [HomeController::class, 'index']);
    Route::get('/contents', [ContentController::class, 'index']);
    Route::get('/contents/{slug}', [ContentController::class, 'show']);
    Route::get('/profiles/{profile}/watchlist', [WatchlistController::class, 'index']);
    Route::post('/profiles/{profile}/watchlist/toggle', [WatchlistController::class, 'toggle']);
});