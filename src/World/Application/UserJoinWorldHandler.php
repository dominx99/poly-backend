<?php declare(strict_types = 1);

namespace App\World\Application;

use App\World\Contracts\WorldsWriteRepository;

class UserJoinWorldHandler
{
    /**
     * @var \App\World\Contracts\WorldsWriteRepository
     */
    private $worlds;

    /**
     * @param \App\World\Contracts\WorldsWriteRepository $worlds
     */
    public function __construct(WorldsWriteRepository $worlds)
    {
        $this->worlds = $worlds;
    }

    /**
     * @param \App\World\Application\UserJoinWorld $command
     */
    public function handle(UserJoinWorld $command): void
    {
        $this->worlds->addUser($command->worldId(), $command->userId());
    }
}
