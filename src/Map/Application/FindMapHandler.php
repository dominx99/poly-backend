<?php declare(strict_types = 1);

namespace App\Map\Application;

use App\Map\Contracts\MapQueryRepository;
use App\System\Contracts\Responsable;
use App\Map\Responses\MapSuccess;
use App\Map\Application\FindMap;

class FindMapHandler
{
    /**
     * @var \App\Map\Contracts\MapQueryRepository
     */
    private $maps;

    /**
     * @param \App\Map\Contracts\MapQueryRepository $maps
     */
    public function __construct(MapQueryRepository $maps)
    {
        $this->maps = $maps;
    }

    /**
     * @param \App\Map\Application\FindMap $command
     * @return void
     */
    public function execute(FindMap $command): Responsable
    {
        $map = $this->maps->findByWorld($command->worldId());

        return new MapSuccess($map);
    }
}
