<?php declare(strict_types=1);

namespace App\Army\Contracts;

interface ArmyUnitQueryRepository
{
    public function getArmyUnitsFromMap(string $mapId): array;
}
