<?php declare(strict_types = 1);

namespace App\World\Application;

use App\System\Contracts\Command;
use App\World\Infrastructure\WorldFilter;

class GetWorlds implements Command
{
    /**
     * @var \App\World\Infrastructure\WorldFilter
     */
    private $filters;

    /**
     * @param \App\World\Infrastructure\WorldFilter $filters
     */
    public function __construct(WorldFilter $filters = null)
    {
        $this->filters = $filters ?? new WorldFilter([]);
    }

    /**
     * @return \App\World\Infrastructure\WorldFilter
     */
    public function filters(): WorldFilter
    {
        return $this->filters;
    }
}
