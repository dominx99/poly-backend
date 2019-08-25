<?php declare(strict_types = 1);

namespace App\Map\Contracts;

interface MapWriteRepository
{
    public function assignPositions(string $mapId, array $userIds, array $fieldIds): void;

    public function assignResources(string $mapId, array $userIds): void;
}
