<?php declare(strict_types=1);

namespace App\Building\Application\Handlers;

use App\Building\Application\Commands\AssignBuildingUnits;
use App\Building\Domain\BuildingUnit;
use Doctrine\ORM\EntityManagerInterface;
use App\Map\Domain\Map\Unit\Name;
use App\Map\Domain\Map\Unit\DisplayName;
use App\Map\Domain\Map\Unit\Cost;
use App\Map\Domain\Map\Unit\Power;
use App\Map\Domain\Map\Unit\Defense;
use App\Gold\Domain\EarningPoints;
use App\Map\Domain\Map;
use App\System\Infrastructure\Exceptions\BusinessException;
use Ramsey\Uuid\Uuid;

final class AssignBuildingUnitsHandler
{
    /** @var \Doctrine\ORM\EntityManagerInterface */
    private $entityManager;

    /**
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param \App\Building\Application\Commands\AssignBuildingUnits $command
     * @return void
     */
    public function handle(AssignBuildingUnits $command): void
    {
        if (! $map = $this->entityManager->getRepository(Map::class)->find($command->mapId())) {
            throw new BusinessException('Map not found.');
        }

        foreach ($command->buildings() as $building) {
            $buildingUnit = new BuildingUnit(
                (string) Uuid::uuid4(),
                new Name($building['name']),
                new DisplayName($building['display_name']),
                new Cost($building['cost']),
                new Power($building['power']),
                new Defense($building['defense']),
                new EarningPoints($building['earning_points'])
            );

            $buildingUnit->addMap($map);
            $this->entityManager->persist($buildingUnit);
        }

        $this->entityManager->flush();
    }
}
