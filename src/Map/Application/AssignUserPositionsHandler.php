<?php declare(strict_types = 1);

namespace App\Map\Application;

use App\Map\Contracts\MapWriteRepository;

class AssignUserPositionsHandler
{
    /**
     * @var \App\Map\Contracts\MapWriteRepository
     */
    private $maps;

    /**
     * @param \App\Map\Contracts\MapWriteRepository $maps
     */
    public function __construct(MapWriteRepository $maps)
    {
        $this->maps = $maps;
    }

    /**
     * @param \App\Map\Application\AssignUserPositions $command
     * @return void
     */
    public function handle(AssignUserPositions $command): void
    {
        $this->maps->assignPositions($command->mapId(), $command->userIds(), $command->positions());
    }
}
