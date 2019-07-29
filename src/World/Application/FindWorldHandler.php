<?php declare(strict_types = 1);

namespace App\World\Application;

use App\World\Contracts\WorldsQueryRepository;

class FindWorldHandler
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
     * @param \App\World\Application\FindWorld $command
     * @return \App\World\Application\Query\WorldView|null
     */
    public function execute(FindWorld $command)
    {
        return $this->worlds->find($command->worldId());
    }
}
