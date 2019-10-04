<?php declare(strict_types=1);

namespace App\Building\Application\Commands;

use App\System\Contracts\Command;

final class AssignBuildingUnits implements Command
{
    /** @var string */
    private $mapId;

    /** @var array */
    private $buildings;

    /**
     * @param string $mapId
     * @param array $buildings
     */
    public function __construct(string $mapId, array $buildings)
    {
        $this->mapId     = $mapId;
        $this->buildings = $buildings;
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
    public function buildings(): array
    {
        return $this->buildings;
    }
}
