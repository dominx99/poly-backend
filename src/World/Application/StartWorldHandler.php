<?php declare(strict_types = 1);

namespace App\World\Application;

use App\World\Domain\World\Status;
use App\System\System;
use App\World\Application\Events\WorldReady;

class StartWorldHandler
{
    /**
     * @var \App\System\System
     */
    private $system;

    /**
     * @param \App\System\System $system
     */
    public function __construct(System $system)
    {
        $this->system = $system;
    }

    /**
     * @param \App\World\Application\WorldReady $event
     * @return void
     */
    public function handle(WorldReady $event): void
    {
        $this->system->handle(new UpdateWorldStatus($event->worldId(), Status::STARTED));
    }
}
