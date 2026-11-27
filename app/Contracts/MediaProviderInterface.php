<?php

namespace App\Contracts;

interface MediaProviderInterface extends SearchableInterface
{
    public function findBySlug(string $slug): ?array;
}
