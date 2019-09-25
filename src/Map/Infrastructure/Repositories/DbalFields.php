<?php declare(strict_types=1);

namespace App\Map\Infrastructure\Repositories;

use App\Map\Application\Query\FieldView;
use App\Map\Contracts\FieldQueryRepository;
use App\System\Infrastructure\Exceptions\UnexpectedException;
use Doctrine\ORM\EntityManagerInterface;

final class DbalFields implements FieldQueryRepository
{
    /**
     * @var \Doctrine\DBAL\Connection
     */
    protected $connection;

    /**
     * @param \Doctrine\ORM\EntityManager $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->connection = $em->getConnection();
    }

    /**
     * @param string $id
     * @throws \Exception
     * @return \App\Map\Application\Query\FieldView
     */
    public function find(string $id): FieldView
    {
        $qb = $this->connection
            ->createQueryBuilder()
            ->select('*')
            ->from('fields', 'f')
            ->where('f.id = :id')
            ->setParameter('id', $id);

        if (! $field = $this->connection->fetchAssoc($qb->getSQL(), $qb->getParameters())) {
            throw new UnexpectedException('Field not found.');
        }

        return $field ? FieldView::createFromDatabase($field) : null;
    }

    /**
     * @param string $mapId
     * @return array|\App\Map\Application\Query\FieldView[]
     */
    public function findByMap(string $mapId, string $userId): array
    {
        $qb = $this->connection
            ->createQueryBuilder()
            ->select('f.*')
            ->from('fields', 'f')
            ->where('f.map_id = :mapId')
            ->andWhere('f.user_id = :userId')
            ->setParameter('mapId', $mapId)
            ->setParameter('userId', $userId);

        return array_map(function ($field) {
            return FieldView::createFromDatabase($field);
        }, $this->connection->fetchAll($qb->getSQL(), $qb->getParameters()));
    }
}
