<?php declare(strict_types=1);

namespace App\User\Application\Handlers;

use App\System\Infrastructure\Exceptions\UserNotFoundException;
use App\User\Application\Commands\ReduceUserGoldForUnit;
use Doctrine\ORM\EntityManagerInterface;
use App\User\Domain\User;
use App\Map\Domain\Map\Unit;
use App\System\Infrastructure\Exceptions\UnexpectedException;

final class ReduceUserGoldForUnitHandler
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
     * @param \App\User\Application\Commands\ReduceUserGoldForUnit $command
     * @return void
     */
    public function handle(ReduceUserGoldForUnit $command): void
    {
        if (! $user = $this->entityManager->getRepository(User::class)->find($command->userId())) {
            throw new UserNotFoundException();
        }

        if (! $unit = $this->entityManager->getRepository(Unit::class)->find($command->unitId())) {
            throw new UnexpectedException('Unit not found.');
        }

        $user->buy($unit);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
