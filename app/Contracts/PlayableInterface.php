<?php

namespace App\Contracts;

interface PlayableInterface
{
    public function formatStreamMetadata(): array;

    public function calculateDurationLabel(): string;
}
