<?php declare (strict_types = 1);

namespace Wallet\Wallet\Infrastructure;

use Doctrine\ORM\EntityManager;
use Ramsey\Uuid\Uuid;
use Wallet\User\Domain\User;
use Wallet\Wallet\Domain\Wallet;
use Wallet\Wallet\Domain\Wallet\Name;
use Wallet\Wallet\Domain\Wallet\Slug;

class ORMWallets
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /**
     * @param \Doctrine\ORM\EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param array $params
     * @return void
     */
    public function add(array $params): void
    {
        $owner = $this->entityManager->find(User::class, $params['owner_id']);

        $wallet = new Wallet(
            Uuid::uuid4(),
            new Name($params['name']),
            new Slug($params['slug'])
        );

        $wallet->addOwner($owner);
        $wallet->addMember($owner);

        $this->entityManager->persist($wallet);
        $this->entityManager->flush();
    }
}
