<?php declare(strict_types = 1);

namespace App\World\Application;

use App\World\Contracts\WorldsQueryRepository;
use App\World\Application\GetWorldPossibleToJoin;

class GetWorldPossibleToJoinHandler
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
     * @param \App\World\Application\GetWorldPossibleToJoin $command
     */
    public function execute(GetWorldPossibleToJoin $command)
    {
        return $this->worlds->getWorldPossibleToJoin();
    }
}
