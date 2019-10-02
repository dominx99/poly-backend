<?php declare(strict_types=1);

namespace App\Map\Application\Queries;

use App\Map\Application\Commands\FindMapObject;
use App\Map\Application\Query\MapObjectView;
use App\System\Infrastructure\Exceptions\UnexpectedException;
use App\Unit\Application\Views\UnitView;
use Doctrine\ORM\EntityManagerInterface;

final class FindMapObjectQuery
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
     * @param \App\Map\Application\Commands\FindMapObject $command
     * @return \App\Map\Application\Query\MapObjectView
     * @throws \App\System\Infrastructure\Exceptions\UnexpectedException
     */
    public function execute(FindMapObject $command): MapObjectView
    {
        $qb = $this->connection
            ->createQueryBuilder()
            ->select('mo.*, u.*')
            ->from('map_objects', 'mo')
            ->where('mo.id = :mapObjectId')
            ->innerJoin('mo', 'units', 'u', 'u.id = mo.unit_id')
            ->setParameter('mapObjectId', $command->id());

        if (! $row = $this->connection->fetchAssoc($qb->getSQL(), $qb->getParameters())) {
            throw new UnexpectedException('Unit not found');
        }

        $mapObject = MapObjectView::createFromDatabase($row);
        $mapObject->setUnit(UnitView::createFromDatabase($row));

        return $mapObject;
    }
}
