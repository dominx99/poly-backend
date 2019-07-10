<?php declare(strict_types = 1);

namespace App\Map\Infrastructure;

use App\Map\Contracts\MapQueryRepository;
use Doctrine\ORM\EntityManager;

class DbalMaps implements MapQueryRepository
{
    /**
     * @var \Doctrine\DBAL\Connection
     */
    protected $connection;

    /**
     * @var \Doctrine\DBAL\Query\QueryBuilder
     */
    protected $queryBuilder;

    /**
     * @param \Doctrine\ORM\EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->connection = $em->getConnection();
    }

    /**
     * @param string $mapId
     * @param int $numberOfPositions
     * @return array
     */
    public function getRandomPositions(string $mapId, int $numberOfPositions): array
    {
        $fieldIds = $this->connection->createQueryBuilder()
            ->select('f.id', 'f.map_id')
            ->from('fields', 'f')
            ->addSelect('RAND() AS random')
            ->orderBy('random')
            ->where('f.map_id = :id')
            ->setMaxResults($numberOfPositions)
            ->setParameter('id', $mapId)
            ->execute()
            ->fetchAll();

        return array_column($fieldIds, 'id');
    }

    /**
     * @param string $mapId
     * @return array
     */
    public function getUserIds(string $mapId): array
    {
        $userIds = $this->connection->createQueryBuilder()
            ->select('u.id', 'm.id as map_id')
            ->from('users', 'u')
            ->join('u', 'worlds', 'w', 'u.world_id = w.id')
            ->join('w', 'maps', 'm', 'm.world_id = w.id')
            ->where('m.id = :id')
            ->setParameter('id', $mapId)
            ->execute()
            ->fetchAll();

        return array_column($userIds, 'id');
    }
}
