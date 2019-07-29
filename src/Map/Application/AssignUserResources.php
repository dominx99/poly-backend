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
     * @param string $mapId
     */
    public function __construct(string $mapId)
    {
        $this->mapId = $mapId;
    }

    /**
     * @return string
     */
    public function mapId(): string
    {
        return $this->mapId;
    }
}
