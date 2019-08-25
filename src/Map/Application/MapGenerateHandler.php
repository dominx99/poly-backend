<?php declare(strict_types = 1);

namespace App\Map\Application;

use App\Map\Infrastructure\MapGenerator;
use App\Map\Infrastructure\MapSize;
use App\World\Contracts\WorldsWriteRepository;
use App\World\Domain\World\Status;
use App\World\Application\UpdateWorldStatus;
use App\System\System;
use App\Map\Application\Events\MapGenerated;
use App\System\Infrastructure\Event\EventDispatcher;
use App\World\Application\Events\WorldReady;
use Ramsey\Uuid\Uuid;

class MapGenerateHandler
{
    /**
     * @var \App\System\System
     */
    private $system;

    /**
     * @var \App\System\Infrastructure\Event\EventDispatcher
     */
    private $events;

    /**
     * @var \App\Map\Infrastructure\MapGenerator
     */
    private $generator;

    /**
     * @var \App\World\Contracts\WorldsWriteRepository
     */
    private $worlds;

    /**
     * @param \App\System\System $system
     * @param \App\System\Infrastructure\Event\EventDispatcher $events
     * @param \App\Map\Infrastructure\MapGenerator $generator
     * @param \App\World\Contracts\WorldsWriteRepository $worlds
     */
    public function __construct(
        System $system,
        EventDispatcher $events,
        MapGenerator $generator,
        WorldsWriteRepository $worlds
    ) {
        $this->system    = $system;
        $this->events    = $events;
        $this->generator = $generator;
        $this->worlds    = $worlds;
    }

    /**
     * @param \App\Map\Application\MapGenerate $command
     * @return void
     */
    public function handle(WorldReady $command): void
    {
        $this->system->handle(new UpdateWorldStatus($command->worldId(), Status::MAP_GENERATION));

        $map   = $this->generator->generate(MapSize::createDefault());
        $mapId = (string) Uuid::uuid4();

        $this->worlds->addMap($command->worldId(), $mapId, $map->toArray());

        $this->events->dispatch(new MapGenerated($mapId));
    }
}
