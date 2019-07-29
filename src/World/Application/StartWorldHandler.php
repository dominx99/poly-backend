<?php declare(strict_types = 1);

namespace App\World\Application;

use App\World\Domain\World\Status;
use App\System\System;
use App\Map\Application\MapGenerate;
use Monolog\Logger;
use App\Map\Application\AssignUserPositions;
use Ramsey\Uuid\Uuid;
use App\Map\Application\AssignUserResources;

class StartWorldHandler
{
    /**
     * @var \App\System\System
     */
    private $system;

    /**
     * @param \App\System\System $system
     */
    public function __construct(System $system, Logger $log)
    {
        $this->system = $system;

        $this->log = $log;
    }

    /**
     * @param \App\World\Application\StartWorld $command
     * @return void
     */
    public function handle(StartWorld $command): void
    {
        $mapId = (string) Uuid::uuid4();

        $this->system->handle(new MapGenerate($command->id(), $mapId));
        $this->system->handle(new AssignUserPositions($command->id(), $mapId));
        $this->system->handle(new AssignUserResources($mapId));
        $this->system->handle(new UpdateWorldStatus($command->id(), Status::STARTED));
    }
}
