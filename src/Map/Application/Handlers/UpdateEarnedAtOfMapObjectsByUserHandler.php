<?php declare(strict_types=1);

namespace App\Map\Application\Handlers;

use Doctrine\ORM\EntityManagerInterface;
use App\Map\Application\Events\CollectedMoneyFromMapObjectsByUser;
use App\Map\Domain\Map\MapObject;

final class UpdateEarnedAtOfMapObjectsByUserHandler
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
     * @param \App\User\Application\Events\CollectedMoneyFromMapObjectsByUser $event
     * @return void
     */
    public function handle(CollectedMoneyFromMapObjectsByUser $event): void
    {
        foreach ($this->entityManager->getRepository(MapObject::class)->findBy([
            'map'  => $event->mapId(),
            'user' => $event->userId(),
        ]) as $mapObject) {
            $mapObject->updateEarnedAt();

            $this->entityManager->persist($mapObject);
        }

        $this->entityManager->flush();
    }
}
