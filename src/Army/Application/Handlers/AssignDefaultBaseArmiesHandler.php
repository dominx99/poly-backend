<?php declare(strict_types=1);

namespace App\Army\Application\Handlers;

use App\Map\Application\Events\MapGenerated;
use App\Army\Domain\BaseArmy;
use App\System\System;
use App\Army\Application\Commands\AssignBaseArmies;

final class AssignDefaultBaseArmiesHandler
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
     * @param \App\Map\Application\Events\MapGenerated $event
     * @return void
     */
    public function handle(MapGenerated $event): void
    {
        $this->system->handle(new AssignBaseArmies($event->mapId(), BaseArmy::DEFAULT_ARMIES));
    }
}
