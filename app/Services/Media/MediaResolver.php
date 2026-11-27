<?php

namespace App\Services\Media;

use App\Contracts\MediaProviderInterface;
use InvalidArgumentException;

class MediaResolver
{
    private array $providers = [];

    public function registerProvider(
        string $name,
        MediaProviderInterface $provider
    ): void {
        $this->providers[$name] = $provider;
    }

    public function resolve(string $name): MediaProviderInterface
    {
        if (!isset($this->providers[$name])) {
            throw new InvalidArgumentException(
                "Media provider [{$name}] tidak ditemukan."
            );
        }

        return $this->providers[$name];
    }

    public function getAvailableProviders(): array
    {
        return array_keys($this->providers);
    }
}
