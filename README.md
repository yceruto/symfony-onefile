# Symfony One File Challenge

> [!NOTE]
> Inspired by [Fabien Potencier Blog in 2013](http://fabien.potencier.org/packing-a-symfony-full-stack-framework-application-in-one-file-introduction.html)

```php
<?php

require_once __DIR__.'/vendor/autoload_runtime.php';

// ...

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    #[Route('/{name}', methods: 'GET')]
    public function index(string $name = 'world'): Response
    {
        return new Response("Hello $name!");
    }

    public function registerBundles(): iterable
    {
        yield new FrameworkBundle();
    }

    private function configureContainer(ContainerConfigurator $container, LoaderInterface $loader, ContainerBuilder $builder): void
    {
        $container->extension('framework', [
            'secret' => env('APP_SECRET'),
        ]);
    }

    private function configureRoutes(RoutingConfigurator $routes): void
    {
        $routes->import(self::class, 'attribute');
    }
}

return static function (array $context) {
    $kernel = new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);

    return \in_array(\PHP_SAPI, ['cli', 'phpdbg', 'embed'], true) ? new Application($kernel) : $kernel;
};
```

Use the same `app.php` as console and web application entry point.

```bash
php app.php cache:clear
```

or using the [Symfony CLI](https://symfony.com/download) server:

```bash
symfony serve
```

## LICENSE

This software is published under the [MIT License](LICENSE).
