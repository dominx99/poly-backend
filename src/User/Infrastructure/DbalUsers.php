<?php declare(strict_types = 1);

namespace App\User\Infrastructure;

use App\User\Application\Query\UserView;
use App\User\Contracts\UserQueryRepository;
use App\User\Application\Query\ResourcesView;
use Doctrine\ORM\EntityManagerInterface;
use App\System\Infrastructure\Exceptions\UnexpectedException;
use App\System\Infrastructure\Exceptions\UserNotFoundException;

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
     * @param \Doctrine\ORM\EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->connection   = $em->getConnection();
        $this->queryBuilder = $this->connection->createQueryBuilder();
    }

    /**
     * @param string $id
     * @return \App\User\Application\Query\UserView
     */
    public function find(string $id): UserView
    {
        $qb = $this->connection->createQueryBuilder();
        $qb
            ->select('*')
            ->from('users', 'u')
            ->where('u.id = :id')
            ->setParameter('id', $id);

        $user = $this->connection->fetchAssoc($qb->getSQL(), $qb->getParameters());

        if (! $user) {
            throw new UserNotFoundException();
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
     * @param null|string $email
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

    /**
     * @param string $userId
     * @return \App\User\Application\Query\ResourcesView
     */
    public function getResources(string $userId): ResourcesView
    {
        $qb = $this->connection
            ->createQueryBuilder()
            ->select('r.gold')
            ->from('resources', 'r')
            ->leftJoin('r', 'users', 'u', 'r.user_id = u.id')
            ->leftJoin('u', 'maps', 'm', 'm.world_id = u.world_id')
            ->where('u.id = :id')
            ->setParameter('id', $userId);

        if (! $resources = $this->connection->fetchAssoc($qb->getSQL(), $qb->getParameters())) {
            throw new UnexpectedException('Resources not found');
        }

        return ResourcesView::createFromDatabase($resources);
    }
}
