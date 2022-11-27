# Slim Illuminate autowire

Autowire class dependencies using the Laravel/Illuminate [service container](https://laravel.com/docs/9.x/container) by proxying Slim's [callable resolver](https://www.slimframework.com/docs/v4/objects/routing.html#container-resolution).

## Usage

```php
use Illuminate\Container\Container;
use Illuminate\Contracts\Container\Container as ContainerContract;
use Kekalainen\SlimIlluminateAutowire\AdvancedCallableResolverProxy;
use Slim\CallableResolver;
use Slim\Factory\AppFactory;
use Slim\Interfaces\AdvancedCallableResolverInterface;

// Instantiate the Illuminate container.
$container = new Container();

// Bind the container instance into the container.
$container->instance(ContainerContract::class, $container);

// Bind a concrete advanced callable resolver to be proxied.
$container->bind(AdvancedCallableResolverInterface::class, CallableResolver::class);

// Resolve the proxied callable resolver.
$callableResolver = $container->make(AdvancedCallableResolverProxy::class);

// Set the static properties of the Slim App factory.
AppFactory::setContainer($container);
AppFactory::setCallableResolver($callableResolver);

// Instantiate the Slim App.
$app = AppFactory::create();
```
