<?php declare(strict_types=1);

namespace App\User\Application\Handlers;

use App\User\Application\Commands\AssignUserColors;
use Doctrine\ORM\EntityManagerInterface;
use App\User\Domain\User;

final class AssignUserColorsHandler
{
    const COLORS = [
        'red',
        'green',
        'purple',
        'indigo',
    ];

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
     * @param \App\User\Application\Commands\AssignUserColors $command
     * @return void
     */
    public function handle(AssignUserColors $command): void
    {
        $colors = self::COLORS;
        shuffle($colors);

        $index = 0;
        foreach ($this->entityManager->getRepository(User::class)->findBy(['id' => $command->userIds()]) as $user) {
            $user->setColor($colors[++$index]);
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
