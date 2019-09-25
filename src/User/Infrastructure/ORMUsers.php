<?php declare(strict_types = 1);

namespace App\User\Infrastructure;

use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;
use App\User\Domain\SocialProvider;
use App\User\Domain\SocialProvider\Name;
use App\User\Domain\User;
use App\User\Domain\User\Email;
use App\User\Domain\User\Password;

class ORMUsers
{
    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param  array $params
     * @return void
     */
    public function add(array $params): void
    {
        $id = $params['id'] ?? Uuid::uuid4();

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

        $socialProvider = new SocialProvider(
            Uuid::uuid4(),
            new Name($params['provider_name'])
        );

        $user->addSocialProvider($socialProvider);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
