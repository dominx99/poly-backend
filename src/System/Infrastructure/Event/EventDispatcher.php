<?php declare(strict_types = 1);

namespace App\System\Infrastructure\Event;

use Psr\Container\ContainerInterface;
use App\Map\Application\MapGenerateHandler;
use App\World\Application\StartWorldHandler;
use App\User\Application\AssignUserBaseKitHandler;
use App\World\Application\Events\WorldReady;
use App\Map\Application\Events\MapGenerated;
use App\Army\Application\Handlers\AssignDefaultArmyUnitsHandler;
use App\Map\Application\Events\PushPlacedMapObjectNotificationHandler;
use App\System\Infrastructure\Exceptions\UnexpectedException;
use Psr\Log\LoggerInterface;
use App\Map\Application\Events\PlacedMapObject;
use App\Map\Application\Handlers\UpdateEarnedAtOfMapObjectsByUserHandler;
use App\Map\Application\Events\CollectedMoneyFromMapObjectsByUser;
use App\Building\Application\Handlers\AssignDefaultBuildingUnitsHandler;

class EventDispatcher implements EventDispatcherInterface
{
    /**
     * @var \Psr\Container\ContainerInterface
     */
    private $container;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $log;

    /**
     * @var array
     */
    private $listeners = [
        WorldReady::class => [
            MapGenerateHandler::class,
            StartWorldHandler::class,
        ],
        MapGenerated::class => [
            AssignUserBaseKitHandler::class,
            AssignDefaultArmyUnitsHandler::class,
            AssignDefaultBuildingUnitsHandler::class,
        ],
        PlacedMapObject::class => [
            PushPlacedMapObjectNotificationHandler::class,
        ],
        CollectedMoneyFromMapObjectsByUser::class => [
            UpdateEarnedAtOfMapObjectsByUserHandler::class,
        ],
    ];

    /**
     * @param \Psr\Container\ContainerInterface $container
     * @param \Psr\Log\LoggerInterface $log
     */
    public function __construct(ContainerInterface $container, LoggerInterface $log)
    {
        $this->container = $container;
        $this->log       = $log;
    }

    /**
     * @param \App\System\Infrastructure\Event\Event $event
     * @return void
     */
    public function dispatch(Event $event): void
    {
        $className = get_class($event);

        if (array_key_exists($className, $this->listeners)) {
            foreach ($this->listeners[$className] as $object) {
                $handler = $this->container->get($object);

                try {
                    $handler->handle($event);
                } catch (\Throwable $t) {
                    $this->log->error($t->getFile() . ' ' . $t->getLine() . ' ' . $t->getMessage());

                    throw $t;
                }
            }

            return;
        }

        throw new UnexpectedException("{$className} event does not have any listeners");
    }
}
