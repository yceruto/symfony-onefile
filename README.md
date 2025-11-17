# Symfony One File Challenge

> [!NOTE]
> Inspired by [Fabien Potencier Blog in 2013](http://fabien.potencier.org/packing-a-symfony-full-stack-framework-application-in-one-file-introduction.html)

```php
<?php

require_once __DIR__.'/vendor/autoload.php';

use Symfony\Bundle\FrameworkBundle\Console\Application as ConsoleApplication;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Runtime\SymfonyRuntime;

class SymfonyOneFileApp extends Kernel
{
    use MicroKernelTrait;

    #[Route('/{name}', methods: 'GET')]
    public function __invoke(string $name = 'World'): Response
    {
        return new Response("Hello $name!");
    }
}

$app = new SymfonyOneFileApp('dev', true);

new SymfonyRuntime()
    ->getRunner(\PHP_SAPI === 'cli' ? new ConsoleApplication($app) : $app)
    ->run();
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
