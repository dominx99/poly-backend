<?php declare(strict_types=1);

namespace App\Army\Application\Handlers;

use App\Army\Application\Commands\AssignBaseArmies;
use App\Army\Contracts\BaseArmyWriteRepository;

final class AssignBaseArmiesHandler
{
    /**
     * @var \App\Army\Contracts\BaseArmyWriteRepository
     */
    private $baseArmies;

    /**
     * @param \App\Army\Contracts\BaseArmyWriteRepository $baseArmies
     */
    public function __construct(BaseArmyWriteRepository $baseArmies)
    {
        $this->baseArmies = $baseArmies;
    }

    /**
     * @param \App\Army\Application\Commands\AssignBaseArmies $command
     * @return void
     */
    public function handle(AssignBaseArmies $command): void
    {
        $this->baseArmies->addMany($command->mapId(), $command->armies());
    }
}
