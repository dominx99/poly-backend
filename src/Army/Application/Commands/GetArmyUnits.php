<?php declare(strict_types=1);

namespace App\Army\Application\Commands;

use App\System\Contracts\Command;

final class GetArmyUnits implements Command
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
