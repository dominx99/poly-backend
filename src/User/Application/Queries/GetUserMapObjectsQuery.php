<?php declare(strict_types=1);

namespace App\User\Application\Queries;

use App\Map\Application\Query\MapObjectView;
use App\Unit\Application\Views\UnitView;
use App\User\Application\Commands\GetUserMapObjects;
use Doctrine\ORM\EntityManagerInterface;

final class GetUserMapObjectsQuery
{
    /** @var \Doctrine\DBAL\Connection */
    private $connection;

    /**
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->connection = $entityManager->getConnection();
    }

    /**
     * @param \App\User\Application\Commands\GetUserMapObjects $command
     * @return array
     */
    public function execute(GetUserMapObjects $command): array
    {
        $qb = $this->connection
            ->createQueryBuilder()
            ->select('mo.*', 'u.*')
            ->from('map_objects', 'mo')
            ->innerJoin('mo', 'units', 'u', 'u.id = mo.unit_id')
            ->where('mo.user_id = :userId')
            ->andWhere('mo.map_id = :mapId')
            ->setParameter('userId', $command->userId())
            ->setParameter('mapId', $command->mapId());

        return array_map(function ($row) {
            $mapObject = MapObjectView::createFromDatabase($row);
            $mapObject->setUnit(UnitView::createFromDatabase($row));

            return $mapObject;
        }, $this->connection->fetchAll($qb->getSQL(), $qb->getParameters()));
    }
}
