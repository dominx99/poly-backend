<?php declare(strict_types=1);

namespace App\Building\Application\Queries;

use App\Building\Application\Commands\GetBuildingUnitsByMap;
use App\System\Contracts\Responsable;
use App\System\Infrastructure\Exceptions\BusinessException;
use App\System\Responses\Success;
use Doctrine\ORM\EntityManagerInterface;

final class GetBuildingUnitsByMapQuery
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
     * @param \App\Building\Application\Commands\GetBuildingUnitsByMap $command
     * @return \App\System\Contracts\Responsable
     */
    public function execute(GetBuildingUnitsByMap $command): Responsable
    {
        $qb = $this->connection
            ->createQueryBuilder()
            ->select('u.*')
            ->from('units', 'u')
            ->where('type = :type')
            ->andWhere('map_id = :mapId')
            ->setParameters([
                'mapId' => $command->mapId(),
                'type'  => 'building',
            ]);

        if (! $buildings = $this->connection->fetchAll($qb->getSQL(), $qb->getParameters())) {
            throw new BusinessException('Building units not found.');
        }

        return Success::create($buildings);
    }
}
