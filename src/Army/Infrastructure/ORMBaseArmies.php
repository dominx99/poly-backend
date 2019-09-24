<?php declare(strict_types=1);

namespace App\Army\Infrastructure;

use App\Army\Contracts\BaseArmyWriteRepository;
use App\Army\Domain\BaseArmy;
use App\Army\Domain\BaseArmy\Name;
use App\Army\Domain\BaseArmy\Cost;
use App\Army\Domain\BaseArmy\DisplayName;
use App\Map\Domain\Map;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;

final class ORMBaseArmies implements BaseArmyWriteRepository
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
        $map = $this->entityManager->getRepository(Map::class)->find($mapId);

        foreach ($armies as $army) {
            $baseArmy = new BaseArmy(
                (string) Uuid::uuid4(),
                new Name($army['name']),
                new DisplayName($army['display_name']),
                new Cost($army['cost'])
            );

            $baseArmy->addMap($map);
            $this->entityManager->persist($baseArmy);
        }

        $this->entityManager->flush();
    }
}
