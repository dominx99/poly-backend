<?php declare(strict_types=1);

namespace App\Map\Application\Handlers;

use App\Map\Application\Commands\PutMapObject;
use App\Map\Domain\Map\Field;
use App\Map\Domain\Map;
use App\User\Domain\User;
use App\Map\Domain\Map\Unit;
use Doctrine\ORM\EntityManagerInterface;
use App\Map\Application\Factories\MapObjectFactory;
use App\System\Infrastructure\Event\EventDispatcherInterface;
use App\Map\Application\Events\PlacedMapObject;
use Ramsey\Uuid\Uuid;

final class PutMapObjectHandler
{
    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    private $entityManager;

    /** @var \App\System\Infrastructure\Event\EventDispatcherInterface */
    private $events;

    /**
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager
     * @param \App\System\Infrastructure\Event\EventDispatcherInterface $events
     */
    public function __construct(EntityManagerInterface $entityManager, EventDispatcherInterface $events)
    {
        $this->entityManager   = $entityManager;
        $this->events          = $events;
    }

    /**
     * @param \App\Map\Application\Commands\PutMapObject $command
     * @return void
     */
    public function handle(PutMapObject $command): void
    {
        $mapObjectId = (string) Uuid::uuid4();
        $mapObject   = MapObjectFactory::create($mapObjectId, $command->type());

        $mapObject->setField(
            $this->entityManager->getRepository(Field::class)->find($command->fieldId())
        );

        $mapObject->setUser(
            $this->entityManager->getRepository(User::class)->find($command->userId())
        );

        $mapObject->setMap(
            $this->entityManager->getRepository(Map::class)->find($command->mapId())
        );

        $mapObject->setUnit(
            $this->entityManager->getRepository(Unit::class)->find($command->unitId())
        );

        $mapObject->updateEarnedAt();

        $this->entityManager->persist($mapObject);
        $this->entityManager->flush();

        $this->events->dispatch(new PlacedMapObject($mapObjectId));
    }
}
