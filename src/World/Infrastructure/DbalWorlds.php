<?php declare(strict_types = 1);

namespace App\World\Infrastructure;

use Doctrine\ORM\EntityManagerInterface;
use App\World\Contracts\WorldsQueryRepository;
use App\World\Application\Query\WorldView;

class DbalWorlds implements WorldsQueryRepository
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
        $this->connection   = $em->getConnection();
        $this->queryBuilder = $this->connection->createQueryBuilder();
    }

    /**
     * @param string $id
     * @return null|\App\World\Application\Query\WorldView
     */
    public function find(string $id)
    {
        $qb = $this->connection->createQueryBuilder();
        $qb
            ->select('*')
            ->from('worlds', 'w')
            ->where('w.id = :id')
            ->setParameter('id', $id);

        $world = $this->connection->fetchAssoc($qb->getSQL(), $qb->getParameters());

        return $world ? WorldView::createFromDatabase($world) : null;
    }

    /**
     * @param string $id
     * @return int
     */
    public function countUsers(string $id): int
    {
        $qb = $this->connection->createQueryBuilder();
        $qb
            ->select('count(u.id) as number')
            ->from('users', 'u')
            ->where('u.world_id = :id')
            ->setParameter('id', $id);

        $result = $this->connection->fetchAssoc($qb->getSQL(), $qb->getParameters());

        return (int) $result['number'];
    }

    /**
     * @param \App\World\Infrastructure\WorldFilter $filters
     * @return array|null
     */
    public function findBy(WorldFilter $filters)
    {
        $this->queryBuilder
            ->select('*')
            ->from('worlds', 'w');

        foreach ($filters->getFilters() as $column => $value) {
            $this->queryBuilder
                ->where("w.{$column} = :{$column}")
                ->setParameter($column, $value);
        }

        if (! $worlds = $this->connection->fetchAll(
            $this->queryBuilder->getSQL(),
            $this->queryBuilder->getParameters()
        )) {
            return [];
        }

        return array_map(function ($world) {
            return WorldView::createFromDatabase($world);
        }, $worlds);
    }

    /**
     * @return \App\World\Application\Query\WorldView|null
     */
    public function getWorldPossibleToJoin()
    {
        $world = $this->connection->createQueryBuilder()
            ->select('w.*')
            ->from('worlds', 'w')
            ->leftJoin('w', 'users', 'u', 'w.id = u.world_id')
            ->groupBy('w.id')
            ->having('count(u.id) < 3')
            ->execute()
            ->fetch();

        return $world ? WorldView::createFromDatabase($world) : null;
    }
}
