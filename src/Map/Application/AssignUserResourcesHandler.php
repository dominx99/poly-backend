<?php declare(strict_types = 1);

namespace App\Map\Application;

use App\Map\Contracts\MapWriteRepository;

class AssignUserResourcesHandler
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
     * @param \App\Map\Application\AssignUserResources $command
     * @return void
     */
    public function handle(AssignUserResources $command): void
    {
        $this->maps->assignResources($command->mapId(), $command->userIds());
    }
}
