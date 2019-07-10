<?php declare(strict_types = 1);

namespace App\Map\Contracts;

interface MapQueryRepository
{
    public function getUserIds(string $mapId): array;
}
