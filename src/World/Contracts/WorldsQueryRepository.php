<?php declare(strict_types = 1);

namespace App\World\Contracts;

use App\World\Infrastructure\WorldFilter;

interface WorldsQueryRepository
{
    public function findBy(WorldFilter $filters);
}
