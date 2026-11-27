<?php

namespace App\Contracts;

interface SearchableInterface
{
    public function search(string $query): array;
}
