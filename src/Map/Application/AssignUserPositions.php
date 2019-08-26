<?php declare(strict_types = 1);

namespace App\Map\Application;

use App\System\Contracts\Command;

class AssignUserPositions implements Command
{
    /**
     * @var string
     */
    private $mapId;

    /**
     * @var array
     */
    private $userIds;

    /**
     * @var array
     */
    private $positions;

    /**
     * @param string $mapId
     * @param array $userIds
     * @param array $positions
     */
    public function __construct(string $mapId, array $userIds, array $positions)
    {
        $this->mapId     = $mapId;
        $this->userIds   = $userIds;
        $this->positions = $positions;
    }

    /**
     * @return string
     */
    public function mapId(): string
    {
        return $this->mapId;
    }

    /**
     * @return array
     */
    public function userIds(): array
    {
        return $this->userIds;
    }

    /**
     * @return array
     */
    public function positions(): array
    {
        return $this->positions;
    }
}
