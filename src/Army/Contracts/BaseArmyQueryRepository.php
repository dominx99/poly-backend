<?php declare(strict_types=1);

namespace App\Army\Contracts;

interface BaseArmyQueryRepository
{
    public function getBaseArmiesFromMap(string $mapId): array;
}
