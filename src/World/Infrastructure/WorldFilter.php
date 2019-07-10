<?php declare(strict_types = 1);

namespace App\World\Infrastructure;

class WorldFilter
{
    private $filters;

    public function __construct(array $filters)
    {
        $this->filters = $filters;
    }

    public function getFilters(): array
    {
        return $this->filters;
    }
}
