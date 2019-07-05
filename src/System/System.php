<?php declare(strict_types = 1);

namespace App\System;

use DI\Container;
use App\System\Contracts\Command;

class System
{
    /**
     * @var \DI\Container
     */
    private $container;

    /**
     * @param \DI\Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param \App\System\Contracts\Command $command
     * @return void
     */
    public function handle(Command $command): void
    {
        $handler = $this->resolveHandler($command);

        $handler->handle($command);
    }

    /**
     * @param \App\System\Contracts\Command $command
     * @return mixed
     */
    public function execute(Command $command)
    {
        $handler = $this->resolveHandler($command);

        return $handler->execute($command);
    }

    /**
     * @param  object $command
     * @return object
     */
    public function resolveHandler(object $command)
    {
        return $this->container->get(get_class($command));
    }
}
