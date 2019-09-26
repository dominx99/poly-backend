<?php declare(strict_types=1);

namespace App\User\Application\Queries;

use App\System\Infrastructure\Exceptions\UnexpectedException;
use App\User\Application\Commands\CanUserAffordUnit;
use Doctrine\DBAL\FetchMode;
use Doctrine\ORM\EntityManagerInterface;

final class CanUserAffordUnitQuery
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
     * @param \App\User\Application\Commands\CanUserAffordUnit $command
     * @return bool
     */
    public function execute(CanUserAffordUnit $command): bool
    {
        return $this->getUserGold($command->userId()) >= $this->getUnitCost($command->unitId());
    }

    /**
     * @param string $userId
     * @throws \App\System\Infrastructure\Exceptions\UnexpectedException
     * @return int
     */
    private function getUserGold(string $userId): int
    {
        $qb = $this->connection
            ->createQueryBuilder()
            ->select('r.gold')
            ->from('users', 'u')
            ->innerJoin('u', 'resources', 'r', 'r.user_id = u.id')
            ->where('u.id = :userId')
            ->setParameter('userId', $userId)
            ->execute();

        if (! $gold = $qb->fetch(FetchMode::COLUMN)) {
            throw new UnexpectedException('Could not fetch user gold.');
        }

        return (int) $gold;
    }

    /**
     * @param string $unitId
     * @throws \App\System\Infrastructure\Exceptions\UnexpectedException
     * @return int
     */
    private function getUnitCost(string $unitId): int
    {
        $qb = $this->connection
            ->createQueryBuilder()
            ->select('u.cost')
            ->from('units', 'u')
            ->where('u.id = :unitId')
            ->setParameter('unitId', $unitId)
            ->execute();

        if (! $cost = $qb->fetch(FetchMode::COLUMN)) {
            throw new UnexpectedException('Could not fetch unit cost.');
        }

        return (int) $cost;
    }
}
