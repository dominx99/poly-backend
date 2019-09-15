<?php declare(strict_types=1);

namespace App\Army\Infrastructure;

use Doctrine\ORM\EntityManager;
use App\Army\Contracts\BaseArmyQueryRepository;
use App\Army\Application\Views\BaseArmyView;

final class DbalBaseArmies implements BaseArmyQueryRepository
{
    /**
     * @var \Doctrine\DBAL\Connection
     */
    private $connection;

    /**
     * @param \Doctrine\ORM\EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->connection = $entityManager->getConnection();
    }

    /**
     * @param string $mapId
     * @return array
     */
    public function getBaseArmiesFromMap(string $mapId): array
    {
        $query = $this->connection
            ->createQueryBuilder()
            ->select('a.*')
            ->from('base_armies', 'a')
            ->join('a', 'maps', 'm', 'm.id = a.map_id')
            ->where('a.map_id = :mapId')
            ->orderBy('a.cost')
            ->setParameter('mapId', $mapId);

        $armies = $this->connection->fetchAll($query->getSQL(), $query->getParameters());

        return array_map(function ($army) {
            return BaseArmyView::createFromDatabase($army);
        }, $armies);
    }
}
