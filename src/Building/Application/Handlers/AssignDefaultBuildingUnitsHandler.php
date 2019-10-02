<?php declare(strict_types=1);

namespace App\Building\Application\Handlers;

use App\Building\Domain\BuildingUnit;
use App\Map\Application\Events\MapGenerated;
use App\System\System;
use App\Building\Application\Commands\AssignBuildingUnits;

final class AssignDefaultBuildingUnitsHandler
{
    /** @var \App\System\System */
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
        $this->system->handle(new AssignBuildingUnits($event->mapId(), BuildingUnit::DEFAULT_BUILDINGS));
    }
}
