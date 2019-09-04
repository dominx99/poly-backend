<?php declare(strict_types = 1);

namespace App\System\Infrastructure\Event;

use Psr\Container\ContainerInterface;
use App\Map\Application\MapGenerateHandler;
use App\World\Application\StartWorldHandler;
use App\User\Application\AssignUserBaseKitHandler;
use App\World\Application\Events\WorldReady;
use App\Map\Application\Events\MapGenerated;
use App\Army\Application\Handlers\AssignDefaultBaseArmiesHandler;

class EventDispatcher implements EventDispatcherInterface
{
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
            AssignDefaultBaseArmiesHandler::class,
        ],
    ];

    /**
     * @param \Psr\Container\ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
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
                $handler->handle($event);
            }

            return;
        }

        throw new \Exception("{$className} event does not have any listeners");
    }
}
