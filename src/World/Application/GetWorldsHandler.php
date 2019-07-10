<?php declare(strict_types = 1);

namespace App\World\Application;

use App\World\Contracts\WorldsQueryRepository;

class GetWorldsHandler
{
    /**
     * @var \App\World\Contracts\WorldsQueryRepository
     */
    private $worlds;

    /**
     * @param \App\World\Contracts\WorldsQueryRepository $worlds
     */
    public function __construct(WorldsQueryRepository $worlds)
    {
        $this->worlds = $worlds;
    }

    /**
     * @param \App\World\Application\GetWorlds $command
     * @return array|null
     */
    public function execute(GetWorlds $command)
    {
        return $this->worlds->findBy($command->filters());
    }
}
