<?php declare(strict_types=1);

namespace App\Army\Application\Queries;

use App\Army\Application\Commands\GetArmyUnits;
use App\System\Responses\Success;
use App\System\Contracts\Responsable;
use App\Army\Contracts\ArmyUnitQueryRepository;

final class GetArmyUnitsHandler
{
    /**
     * @var \App\Army\Contracts\ArmyUnitQueryRepository
     */
    private $armies;

    /**
     * @param \App\Army\Contracts\ArmyUnitQueryRepository $armies
     */
    public function __construct(ArmyUnitQueryRepository $armies)
    {
        $this->armies = $armies;
    }

    /**
     * @param \App\Army\Application\Commands\GetArmyUnits $command
     * @return \App\System\Contracts\Responsable
     */
    public function execute(GetArmyUnits $command): Responsable
    {
        $armies = $this->armies->getArmyUnitsFromMap($command->mapId());

        $data = array_map(function ($army) {
            return $army->toArray();
        }, $armies);

        return new Success($data);
    }
}
