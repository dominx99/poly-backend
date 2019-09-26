<?php declare(strict_types=1);

namespace App\Army\Application\Handlers;

use App\Army\Application\Commands\AssignArmyUnits;
use App\Army\Contracts\ArmyUnitWriteRepository;

final class AssignArmyUnitsHandler
{
    /**
     * @var \App\Army\Contracts\ArmyUnitWriteRepository
     */
    private $armyUnits;

    /**
     * @param \App\Army\Contracts\ArmyUnitWriteRepository $armyUnits
     */
    public function __construct(ArmyUnitWriteRepository $armyUnits)
    {
        $this->armyUnits = $armyUnits;
    }

    /**
     * @param \App\Army\Application\Commands\AssignArmyUnits $command
     * @return void
     */
    public function handle(AssignArmyUnits $command): void
    {
        $this->armyUnits->addMany($command->mapId(), $command->armies());
    }
}
