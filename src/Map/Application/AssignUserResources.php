<?php declare(strict_types = 1);

namespace App\Map\Application;

use App\System\Contracts\Command;

class AssignUserResources implements Command
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
     * @param string $mapId
     * @param array $userIds
     */
    public function __construct(string $mapId, array $userIds)
    {
        $this->mapId   = $mapId;
        $this->userIds = $userIds;
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
}
