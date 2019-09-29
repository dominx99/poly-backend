<?php declare(strict_types=1);

namespace App\Army\Infrastructure;

use Doctrine\ORM\EntityManagerInterface;
use App\Army\Contracts\ArmyUnitQueryRepository;
use App\Army\Application\Views\ArmyUnitView;

final class DbalArmyUnits implements ArmyUnitQueryRepository
{
    /**
     * @var \Doctrine\DBAL\Connection
     */
    private $connection;

    /**
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->connection = $entityManager->getConnection();
    }

    /**
     * @param string $mapId
     * @return array
     */
    public function getArmyUnitsFromMap(string $mapId): array
    {
        $query = $this->connection
            ->createQueryBuilder()
            ->select('u.*')
            ->from('units', 'u')
            ->join('u', 'maps', 'm', 'm.id = u.map_id')
            ->where('u.map_id = :mapId')
            ->andWhere('type = "army"')
            ->orderBy('u.cost')
            ->setParameter('mapId', $mapId);

        $armies = $this->connection->fetchAll($query->getSQL(), $query->getParameters());

        return array_map(function ($army) {
            return ArmyUnitView::createFromDatabase($army);
        }, $armies);
    }
}
