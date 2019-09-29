<?php declare(strict_types = 1);

namespace App\User\Application;

use App\System\System;
use App\Map\Contracts\MapQueryRepository;
use App\Map\Application\AssignUserPositions;
use App\Map\Application\AssignUserResources;
use App\Map\Application\Events\MapGenerated;
use App\User\Application\Commands\AssignUserColors;

class AssignUserBaseKitHandler
{
    /**
     * @var \App\System\System
     */
    private $system;

    /**
     * @var \App\Map\Contracts\MapQueryRepository
     */
    private $maps;

    /**
     * @param \App\System\System $system
     * @param \App\Map\Contracts\MapQueryRepository $maps
     */
    public function __construct(System $system, MapQueryRepository $maps)
    {
        $this->system = $system;
        $this->maps   = $maps;
    }

    /**
     * @param \App\Map\Application\Events\MapGenerated $event
     * @return void
     */
    public function handle(MapGenerated $event): void
    {
        $userIds   = $this->maps->getUserIds($event->mapId());
        $positions = $this->maps->getRandomPositions($event->mapId(), count($userIds));

        $this->system->handle(new AssignUserPositions($event->mapId(), $userIds, $positions));
        $this->system->handle(new AssignUserResources($event->mapId(), $userIds));
        $this->system->handle(new AssignUserColors($userIds));
    }
}
