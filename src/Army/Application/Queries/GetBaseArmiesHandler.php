<?php declare(strict_types=1);

namespace App\Army\Application\Queries;

use App\Army\Application\Commands\GetBaseArmies;
use App\System\Responses\Success;
use App\System\Contracts\Responsable;
use App\Army\Contracts\BaseArmyQueryRepository;

final class GetBaseArmiesHandler
{
    /**
     * @var \App\Army\Contracts\BaseArmyQueryRepository
     */
    private $armies;

    /**
     * @param \App\Army\Contracts\BaseArmyQueryRepository $armies
     */
    public function __construct(BaseArmyQueryRepository $armies)
    {
        $this->armies = $armies;
    }

    /**
     * @param \App\Army\Application\Commands\GetBaseArmies $command
     * @return \App\System\Contracts\Responsable
     */
    public function execute(GetBaseArmies $command): Responsable
    {
        $armies = $this->armies->getBaseArmiesFromMap($command->mapId());

        $data = array_map(function ($army) {
            return [
                'name'         => $army->name(),
                'display_name' => $army->displayName(),
                'cost'         => $army->cost(),
            ];
        }, $armies);

        return new Success($data);
    }
}
