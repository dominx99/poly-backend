<?php declare(strict_types=1);

namespace App\Army\Contracts;

interface BaseArmyWriteRepository
{
    public function addMany(string $mapId, array $armies): void;
}
