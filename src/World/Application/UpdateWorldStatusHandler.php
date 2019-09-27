<?php declare(strict_types = 1);

namespace App\World\Application;

use App\System\Contracts\Socket;
use App\World\Contracts\WorldsWriteRepository;
use App\World\Events\ChangeWorldStatusEvent;

class UpdateWorldStatusHandler
{
    /**
     * @var \App\World\Contracts\WorldsWriteRepository
     */
    private $worlds;

    /** @var \App\System\Contracts\Socket */
    private $socket;

    /**
     * @param \App\World\Contracts\WorldsWriteRepository $worlds
     * @param \App\System\Contracts\Socket $socket
     */
    public function __construct(WorldsWriteRepository $worlds, Socket $socket)
    {
        $this->worlds = $worlds;
        $this->socket = $socket;
    }

    /**
     * @param \App\World\Application\UpdateWorldStatus $command
     * @return void
     */
    public function handle(UpdateWorldStatus $command): void
    {
        $this->worlds->updateStatus($command->id(), $command->status());
        $this->socket->trigger(new ChangeWorldStatusEvent($command->id(), $command->status()));
    }
}
