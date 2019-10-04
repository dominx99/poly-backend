<?php declare(strict_types=1);

namespace App\User\Application\Handlers;

use App\User\Application\Commands\UpdateUserResources;
use App\User\Domain\Resource;
use App\System\System;
use App\System\Infrastructure\Event\EventDispatcherInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Map\Application\Events\CollectedMoneyFromMapObjectsByUser;
use App\System\Contracts\GoldIncomeCalculator;
use App\User\Application\Commands\GetUserMapObjects;

final class UpdateUserResourcesHandler
{
    /** @var \App\System\System */
    private $system;

    /** @var \App\System\Infrastructure\Event\EventDispatcherInterface */
    private $events;

    /** @var \Doctrine\ORM\EntityManagerInterface */
    private $entityManager;

    /** @var \App\System\Contracts\GoldIncomeCalculator */
    private $goldCalculator;

    /**
     * @param \App\System\System $system
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager
     * @param \App\System\Infrastructure\Event\EventDispatcherInterface $events
     * @param \App\System\Contracts\GoldIncomeCalculator
     */
    public function __construct(
        System $system,
        EntityManagerInterface $entityManager,
        EventDispatcherInterface $events,
        GoldIncomeCalculator $goldCalculator
    ) {
        $this->system         = $system;
        $this->events         = $events;
        $this->entityManager  = $entityManager;
        $this->goldCalculator = $goldCalculator;
    }

    /**
     * @param \App\User\Application\Commands\UpdateUserResources $command
     * @return void
     */
    public function handle(UpdateUserResources $command): void
    {
        $resource = $this->entityManager->getRepository(Resource::class)->findOneBy([
            'user' => $command->userId(),
        ]);

        $amount = $this->goldCalculator
            ->from($this->system->execute(new GetUserMapObjects($command->mapId(), $command->userId())))
            ->withConversion(1)
            ->calculate();

        $resource->updateGold($amount);

        $this->events->dispatch(new CollectedMoneyFromMapObjectsByUser($command->mapId(), $command->userId()));
    }
}
