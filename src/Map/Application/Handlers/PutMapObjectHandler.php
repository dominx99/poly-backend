<?php declare(strict_types=1);

namespace App\Map\Application\Handlers;

use App\Map\Application\Commands\PutMapObject;
use App\Map\Domain\Map\Field;
use App\Map\Domain\Map;
use App\User\Domain\User;
use App\Map\Domain\Map\Unit;
use Doctrine\ORM\EntityManagerInterface;
use App\Map\Application\Factories\MapObjectFactory;

final class PutMapObjectHandler
{
    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    private $entityManager;

    /**
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param \App\Map\Application\Commands\PutMapObject $command
     * @return void
     */
    public function handle(PutMapObject $command): void
    {
        $mapObject = MapObjectFactory::create($command->type());

        $mapObject->setField(
            $this->entityManager->getRepository(Field::class)->find($command->fieldId())
        );

        $mapObject->setUser(
            $this->entityManager->getRepository(User::class)->find($command->userId())
        );

        $mapObject->setMap(
            $this->entityManager->getRepository(Map::class)->find($command->mapId())
        );

        $unit = $this->entityManager->getRepository(Unit::class)->find($command->unitId());

        $mapObject->setUnit(
            $unit
        );

        $this->entityManager->persist($mapObject);
        $this->entityManager->flush();
    }
}
