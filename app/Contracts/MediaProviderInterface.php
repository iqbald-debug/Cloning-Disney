<?php

namespace App\Contracts;

//media
interface MediaProviderInterface extends SearchableInterface
{
    public function findBySlug(string $slug): ?array;
}
