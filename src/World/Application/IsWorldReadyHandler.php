<?php declare(strict_types = 1);

namespace App\World\Application;

use App\World\Contracts\WorldsQueryRepository;

class IsWorldReadyHandler
{
    private $worlds;

    public function __construct(WorldsQueryRepository $worlds)
    {
        $this->worlds = $worlds;
    }

    public function execute(IsWorldReady $command): bool
    {
        $count = $this->worlds->countUsers($command->id());

        return $count === $command->trigger();
    }
}
