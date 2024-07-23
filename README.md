# Symfony One File Challenge

> [!NOTE]
> Inspired by [Fabien Potencier Blog in 2013](http://fabien.potencier.org/packing-a-symfony-full-stack-framework-application-in-one-file-introduction.html)

```php
<?php

require_once __DIR__.'/vendor/autoload_runtime.php';

use Symfony\Bundle\FrameworkBundle\Console\Application as ConsoleApplication;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Routing\Attribute\Route;

class SymfonyOneFileApp extends Kernel
{
    use MicroKernelTrait;

    #[Route('/{name}', methods: 'GET')]
    public function __invoke(string $name = 'World'): Response
    {
        return new Response("Hello $name!");
    }
}

return static function (array $context) {
    $app = new SymfonyOneFileApp($context['APP_ENV'], (bool) $context['APP_DEBUG']);

    return \in_array(\PHP_SAPI, ['cli', 'phpdbg', 'embed'], true) ? new ConsoleApplication($app) : $app;
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

> [!NOTE]
> Since 7.2, we are further improving the default configuration for this Kernel by making the `config/` 
> directory optional by default, as well as eliminating the requirement to configure the `framework.secret` 
> value.

## LICENSE

This software is published under the [MIT License](LICENSE).
