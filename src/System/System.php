<?php declare (strict_types = 1);

namespace Wallet\System;

use DI\Container;
use Wallet\System\Contracts\Command;
use Wallet\System\Contracts\Query;

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
     * @param  \Wallet\System\Contracts\Command $command
     * @return void
     */
    public function handle(Command $command): void
    {
        $handler = $this->resolveHandler($command);

        $handler->handle($command);
    }

    /**
     * @param  \Wallet\System\Contracts\Query $query
     * @return mixed
     */
    public function execute(Command $command)
    {
        $handler = $this->resolveHandler($command);

        return $handler->execute($command);
    }

    /**
     * @param  object $command
     * @return \Wallet\System\Contracts\Handler|\Wallet\System\Contracts\Query
     */
    public function resolveHandler(object $command)
    {
        return $this->container->get(get_class($command));
    }
}
