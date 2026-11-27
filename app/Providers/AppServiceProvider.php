<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Contracts\MediaProviderInterface;
use App\Contracts\WatchlistRepositoryInterface;

use App\Repositories\WatchlistRepository;

use App\Services\Media\MediaResolver;
use App\Services\Providers\LocalDatabaseProvider;
use App\Services\Providers\TmdbApiProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Binding Media Provider
        $this->app->bind(
            MediaProviderInterface::class,
            LocalDatabaseProvider::class
        );

        // Binding Watchlist Repository
        $this->app->bind(
            WatchlistRepositoryInterface::class,
            WatchlistRepository::class
        );

        // Register Media Resolver
        $this->app->singleton(
            MediaResolver::class,
            function ($app) {
                $resolver = new MediaResolver();

                $resolver->registerProvider(
                    'local',
                    $app->make(LocalDatabaseProvider::class)
                );

                $resolver->registerProvider(
                    'tmdb',
                    $app->make(TmdbApiProvider::class)
                );

                return $resolver;
            }
        );
    }

    public function boot(): void
    {
        //
    }
}
