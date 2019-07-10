<?php declare(strict_types = 1);

namespace App\Map\Application;

use App\System\Contracts\Command;

class AssignUserPositions implements Command
{
    /**
     * @var string
     */
    private $worldId;

    /**
     * @var string
     */
    private $mapId;

    /**
     * @param string $worldId
     */
    public function __construct(string $worldId, string $mapId)
    {
        $this->worldId = $worldId;
        $this->mapId   = $mapId;
    }

    /**
     * @return string
     */
    public function worldId(): string
    {
        return $this->worldId;
    }

    /**
     * @return string
     */
    public function mapId(): string
    {
        return $this->mapId;
    }
}
