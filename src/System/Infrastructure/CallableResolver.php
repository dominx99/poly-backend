<?php declare(strict_types = 1);

namespace App\System\Infrastructure;

use Slim\Interfaces\CallableResolverInterface;
use Invoker\CallableResolver as InvokerCallableResolver;

class CallableResolver implements CallableResolverInterface
{
    /**
     * @var \Invoker\CallableResolver
     */
    private $callableResolver;

    /**
     * @param \Invoker\CallableResolver $callableResolver
     */
    public function __construct(InvokerCallableResolver $callableResolver)
    {
        $this->callableResolver = $callableResolver;
    }

    /**
     * {@inheritdoc}
     */
    public function resolve($toResolve): callable
    {
        return $this->callableResolver->resolve($toResolve);
    }
}
