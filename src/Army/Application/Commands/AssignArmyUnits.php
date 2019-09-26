<?php declare(strict_types=1);

namespace App\Army\Application\Commands;

use App\System\Contracts\Command;

final class AssignArmyUnits implements Command
{
    /**
     * @var string
     */
    private $mapId;

    /**
     * @var array
     */
    private $armies;

    /**
     * @param string $mapId
     * @param array $armies
     */
    public function __construct(string $mapId, array $armies)
    {
        $this->mapId  = $mapId;
        $this->armies = $armies;
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
    public function armies(): array
    {
        return $this->armies;
    }
}
