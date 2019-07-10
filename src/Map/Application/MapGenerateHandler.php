<?php declare(strict_types = 1);

namespace App\Map\Application;

use App\Map\Infrastructure\MapGenerator;
use App\Map\Infrastructure\MapSize;
use App\World\Contracts\WorldsWriteRepository;
use App\World\Domain\World\Status;

class MapGenerateHandler
{
    /**
     * @var \App\Map\Infrastructure\MapGenerator
     */
    private $generator;

    /**
     * @var \App\World\Contracts\WorldsWriteRepository
     */
    private $worlds;

    /**
     * @param \App\Map\Infrastructure\MapGenerator $generator
     */
    public function __construct(MapGenerator $generator, WorldsWriteRepository $worlds)
    {
        $this->generator = $generator;
        $this->worlds    = $worlds;
    }

    /**
     * @param \App\Map\Application\MapGenerate $command
     * @return void
     */
    public function handle(MapGenerate $command): void
    {
        $this->worlds->updateStatus($command->worldId(), Status::MAP_GENERATION);

        $map = $this->generator->generate(new MapSize(16, 16));

        $this->worlds->addMap($command->worldId(), $command->mapId(), $map->toArray());
    }
}
