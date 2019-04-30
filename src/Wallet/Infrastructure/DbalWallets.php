<?php declare (strict_types = 1);

namespace Wallet\Wallet\Infrastructure;

use Doctrine\ORM\EntityManager;
use Wallet\Wallet\Application\Query\WalletView;

class DbalWallets
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
     * @param string $slug
     * @return Wallet\Wallet\Application\Query\WalletView|null
     */
    public function findBySlug(string $slug)
    {
        $this->queryBuilder
            ->select('*')
            ->from('wallets', 'w')
            ->where('w.slug = :slug')
            ->setParameter('slug', $slug);

        $wallet = $this->connection->fetchAssoc($this->queryBuilder->getSQL(), $this->queryBuilder->getParameters());

        return $wallet ? WalletView::createFromDatabase($wallet) : null;
    }
}
