<?php declare(strict_types = 1);

namespace App\User\Infrastructure;

use Doctrine\ORM\EntityManager;
use App\User\Application\Query\UserView;
use App\User\Contracts\UserQueryRepository;

class DbalUsers implements UserQueryRepository
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
     * @param string $id
     * @return \App\User\Application\Query\UserView
     */
    public function find(string $id)
    {
        $qb = $this->connection->createQueryBuilder();
        $qb
            ->select('*')
            ->from('users', 'u')
            ->where('u.id = :id')
            ->setParameter('id', $id);

        $user = $this->connection->fetchAssoc($qb->getSQL(), $qb->getParameters());

        if (! $user) {
            throw new \Exception('User not found.');
        }

        return UserView::createFromDatabase($user);
    }

    /**
     * @param \App\User\Infrastructure\UserFilters $filters
     * @return void
     */
    public function findBy(UserFilters $filters)
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

    /**
     * @param string $userId
     * @return bool
     */
    public function isInGame(string $userId): bool
    {
        $result = $this->connection->createQueryBuilder()
            ->select('u.world_id')
            ->from('users', 'u')
            ->where('u.id = :id')
            ->setParameter('id', $userId)
            ->execute()
            ->fetch();

        return $result['world_id'] !== null;
    }

    /**
     * @param mixed $email
     * @return bool
     */
    public function emailExist($email): bool
    {
        $result = $this->connection->createQueryBuilder()
            ->select('count(u.id) as exist')
            ->from('users', 'u')
            ->where('u.email = :email')
            ->setParameter('email', $email)
            ->execute()
            ->fetch();

        return $result['exist'] >= 1;
    }
}
