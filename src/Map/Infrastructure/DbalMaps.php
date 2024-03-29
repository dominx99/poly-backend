<?php declare(strict_types = 1);

namespace App\Map\Infrastructure;

use App\Map\Contracts\MapQueryRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Map\Application\Query\MapView;
use App\Map\Application\Query\FieldView;
use App\Map\Application\Query\MapObjectView;
use App\System\Infrastructure\Exceptions\UnexpectedException;
use App\Unit\Application\Views\UnitView;

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
     * @param \Doctrine\ORM\EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->connection = $em->getConnection();
    }

    /**
     * @param string $worldId
     * @return \App\Map\Application\Query\MapView|null
     */
    public function findByWorld(string $worldId)
    {
        $qb = $this->connection->createQueryBuilder()
            ->select('m.*')
            ->from('maps', 'm')
            ->where('m.world_id = :worldId')
            ->setParameter('worldId', $worldId);

        if (! $map = $this->connection->fetchAssoc($qb->getSQL(), $qb->getParameters())) {
            throw new UnexpectedException('Map not found.');
        }

        $map = MapView::createFromDatabase($map);

        $map->setFields($this->getFieldsByWorld($worldId) ?? []);
        $map->setMapObjects($this->getMapObjectsByWorld($worldId) ?? []);

        return $map;
    }

    /**
     * @param string $worldId
     * @return array
     */
    private function getFieldsByWorld(string $worldId): array
    {
        $qb = $this->connection->createQueryBuilder()
            ->select('f.*')
            ->from('maps', 'm')
            ->leftJoin('m', 'worlds', 'w', 'w.id = m.world_id')
            ->innerJoin('m', 'fields', 'f', 'm.id = f.map_id')
            ->where('w.id = :worldId')
            ->setParameter('worldId', $worldId);

        return array_map(function ($field) {
            return FieldView::createFromDatabase($field);
        }, $this->connection->fetchAll($qb->getSQL(), $qb->getParameters()));
    }

    /**
     * @param string $worldId
     * @return array
     */
    private function getMapObjectsByWorld(string $worldId): array
    {
        $qb = $this->connection->createQueryBuilder()
            ->select('mo.*', 'u.*')
            ->from('maps', 'm')
            ->leftJoin('m', 'worlds', 'w', 'w.id = m.world_id')
            ->innerJoin('m', 'map_objects', 'mo', 'mo.map_id = m.id')
            ->innerJoin('mo', 'units', 'u', 'mo.unit_id = u.id')
            ->where('w.id = :worldId')
            ->setParameter('worldId', $worldId);

        return array_map(function ($row) {
            $mapObject = MapObjectView::createFromDatabase($row);
            $mapObject->setUnit(UnitView::createFromDatabase($row));

            return $mapObject;
        }, $this->connection->fetchAll($qb->getSQL(), $qb->getParameters()));
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
            ->from('maps', 'm')
            ->join('m', 'worlds', 'w', 'm.world_id = w.id')
            ->join('w', 'users', 'u', 'm.world_id = u.world_id')
            ->where('m.id = :id')
            ->setParameter('id', $mapId)
            ->execute()
            ->fetchAll();

        return array_column($userIds, 'id');
    }
}
