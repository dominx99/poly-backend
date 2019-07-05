<?php declare(strict_types = 1);

namespace App\User\Infrastructure;

use Doctrine\ORM\EntityManager;
use App\User\Application\Query\UserView;

class DbalUsers
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
        $this->connection   = $em->getConnection();
        $this->queryBuilder = $this->connection->createQueryBuilder();
    }

    /**
     * @param \App\User\Infrastructure\UserFilters $filters
     * @return void
     */
    public function find(UserFilters $filters)
    {
        $this->queryBuilder
            ->select('*')
            ->from('users', 'u');

        foreach ($filters->getFilters() as $column => $value) {
            $this->queryBuilder
                ->where("u.{$column} = :{$column}")
                ->setParameter($column, $value);
        }

        $user = $this->connection->fetchAssoc($this->queryBuilder->getSQL(), $this->queryBuilder->getParameters());

        return $user ? UserView::createFromDatabase($user) : null;
    }

    /**
     * @param  string $email
     * @return null|\App\User\Application\Query\UserView
     */
    public function findByEmail(string $email)
    {
        $this->queryBuilder
            ->select('*')
            ->from('users', 'u')
            ->where('u.email = :email')
            ->setParameter('email', $email);

        $user = $this->connection->fetchAssoc($this->queryBuilder->getSQL(), $this->queryBuilder->getParameters());

        return $user ? UserView::createFromDatabase($user) : null;
    }
}
