<?php declare(strict_types=1);

namespace App\Army\Infrastructure;

use App\Army\Contracts\ArmyUnitWriteRepository;
use App\Army\Domain\ArmyUnit;
use App\Gold\Domain\EarningPoints;
use App\Map\Domain\Map\Unit\Name;
use App\Map\Domain\Map\Unit\DisplayName;
use App\Map\Domain\Map\Unit\Cost;
use App\Map\Domain\Map;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;
use App\Map\Domain\Map\Unit\Power;
use App\Map\Domain\Map\Unit\Defense;
use App\System\Infrastructure\Exceptions\BusinessException;

final class DoctrineArmyUnits implements ArmyUnitWriteRepository
{
    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var \Doctrine\DBAL\Connection
     */
    private $connection;

    /**
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->connection    = $entityManager->getConnection();
    }

    /**
     * @param string $mapId
     * @param array $armies
     * @return void
     */
    public function addMany(string $mapId, array $armies): void
    {
        if (! $map = $this->entityManager->getRepository(Map::class)->find($mapId)) {
            throw new BusinessException('Map not found.');
        }

        foreach ($armies as $army) {
            $armyUnit = new ArmyUnit(
                (string) Uuid::uuid4(),
                new Name($army['name']),
                new DisplayName($army['display_name']),
                new Cost($army['cost']),
                new Power($army['power']),
                new Defense($army['defense']),
                new EarningPoints($army['earning_points'])
            );

            $armyUnit->addMap($map);
            $this->entityManager->persist($armyUnit);
        }

        $this->entityManager->flush();
    }
}
