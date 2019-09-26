<?php declare(strict_types=1);

namespace App\User\Application\Handlers;

use App\System\Infrastructure\Exceptions\UnexpectedException;
use App\System\Infrastructure\Exceptions\UserNotFoundException;
use App\User\Application\Commands\UserGainField;
use App\User\Domain\User;
use Doctrine\ORM\EntityManagerInterface;
use App\Map\Domain\Map\Field;

final class UserGainFieldHandler
{
    /** @var \Doctrine\ORM\EntityManagerInterface */
    private $entityManager;

    /**
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param \App\User\Application\Commands\UserGainField $command
     * @return void
     * @throws \App\System\Infrastructure\Exceptions\UserNotFoundException
     */
    public function handle(UserGainField $command): void
    {
        if (! $user = $this->entityManager->getRepository(User::class)->find($command->userId())) {
            throw new UserNotFoundException();
        }

        if (! $field = $this->entityManager->getRepository(Field::class)->find($command->fieldId())) {
            throw new UnexpectedException('Field not found.');
        }

        $user->gainField($field);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
