<?php

namespace App\Contracts;

//tampilan media
interface MediaProviderInterface extends SearchableInterface
{
    public function findBySlug(string $slug): ?array;
}
