<?php declare (strict_types = 1);

namespace Wallet\User\Infrastructure;

use Doctrine\ORM\EntityManager;
use Ramsey\Uuid\Uuid;
use Wallet\User\Domain\SocialProvider;
use Wallet\User\Domain\SocialProvider\Name;
use Wallet\User\Domain\User;
use Wallet\User\Domain\User\Email;
use Wallet\User\Domain\User\Password;

class ORMUsers
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
        $id = isset($params['id']) ? $params['id'] : Uuid::uuid4();

        $user = new User(
            $id,
            new Email($params['email']),
            new Password($params['password'])
        );

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    /**
     * @param array $params
     * @return void
     */
    public function addWithProvider(array $params): void
    {
        $user = new User(
            Uuid::uuid4(),
            new Email($params['email'])
        );

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $socialProvider = new SocialProvider(
            Uuid::uuid4(),
            $user,
            new Name($params['provider_name'])
        );

        $this->entityManager->persist($socialProvider);
        $this->entityManager->flush();
    }
}
