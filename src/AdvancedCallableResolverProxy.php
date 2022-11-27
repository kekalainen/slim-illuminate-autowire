<?php

namespace Kekalainen\SlimIlluminateAutowire;

use Illuminate\Contracts\Container\Container;
use Slim\Interfaces\AdvancedCallableResolverInterface;

/**
 * Proxies an advanced callable resolver to provide (factoryless) automatic dependency injection based on type declarations.
 * 
 * https://www.slimframework.com/docs/v4/objects/routing.html#registering-a-controller-with-the-container
 * https://laravel.com/docs/9.x/container#automatic-injection
 */
class AdvancedCallableResolverProxy implements AdvancedCallableResolverInterface
{
    protected Container $container;

    protected AdvancedCallableResolverInterface $callableResolver;

    public function __construct(Container $container, AdvancedCallableResolverInterface $callableResolver)
    {
        $this->container = $container;
        $this->callableResolver = $callableResolver;
    }

    /**
     * Prepare $toResolve for container resolution.
     */
    protected function prepareToResolve($toResolve): void
    {
        if (!is_string($toResolve))
            return;

        // Extract the class name from Slim notation.
        [$class] = explode(':', $toResolve);

        if (
            $this->container->has($class) ||
            !class_exists($class)
        )
            return;

        // Resolve the class instance (including any type-hinted dependencies) and bind it into the container.
        $this->container->instance($class, $this->container->make($class));
    }

    public function resolve($toResolve): callable
    {
        $this->prepareToResolve($toResolve);
        return $this->callableResolver->resolve($toResolve);
    }

    public function resolveRoute($toResolve): callable
    {
        $this->prepareToResolve($toResolve);
        return $this->callableResolver->resolveRoute($toResolve);
    }

    public function resolveMiddleware($toResolve): callable
    {
        $this->prepareToResolve($toResolve);
        return $this->callableResolver->resolveMiddleware($toResolve);
    }
}
