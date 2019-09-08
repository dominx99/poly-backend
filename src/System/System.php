<?php declare(strict_types = 1);

namespace App\System;

use DI\Container;
use App\System\Contracts\Command;
use Psr\Log\LoggerInterface;

class System
{
    /**
     * @var \DI\Container
     */
    private $container;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $log;

    /**
     * @param \DI\Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->log       = $container->get(LoggerInterface::class);
    }

    /**
     * @param \App\System\Contracts\Command $command
     * @return void
     */
    public function handle(Command $command): void
    {
        try {
            $handler = $this->resolveHandler($command);

            $handler->handle($command);
        } catch (\Exception $e) {
            $this->log->error($e->getMessage());
        }
    }

    /**
     * @param \App\System\Contracts\Command $command
     * @return \App\System\Contracts\Command
     */
    public function execute(Command $command)
    {
        try {
            $handler = $this->resolveHandler($command);

            return $handler->execute($command);
        } catch (\Exception $e) {
            $this->log->error($e->getMessage());
        }
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
