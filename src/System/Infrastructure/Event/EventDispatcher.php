<?php declare(strict_types = 1);

namespace App\System\Infrastructure\Event;

use Psr\Container\ContainerInterface;

class EventDispatcher implements EventDispatcherInterface
{
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
        }

        throw new \Exception('Given event does not have any listeners');
    }
}
