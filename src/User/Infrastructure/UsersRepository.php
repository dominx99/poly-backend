<?php declare (strict_types = 1);

namespace Wallet\User\Infrastructure;

use Doctrine\ORM\EntityManager;
use Ramsey\Uuid\Uuid;
use Wallet\User\Domain\User;
use Wallet\User\Domain\User\Email;
use Wallet\User\Domain\User\Password;

class UsersRepository
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $entityManager;

    /**
     * @param \Doctrine\ORM\EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param  array $params
     * @return void
     */
    public function add(array $params): void
    {
        $user = new User(
            Uuid::uuid4(),
            new Email($params['email']),
            new Password($params['password'])
        );

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
