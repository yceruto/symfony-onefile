<?php

require_once __DIR__.'/vendor/autoload_runtime.php';

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;
use Twig\Environment;
use function Symfony\Component\DependencyInjection\Loader\Configurator\env;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    #[Route('/{name}', methods: 'GET')]
    public function index(Environment $twig, string $name = 'world'): Response
    {
        $content = $twig->createTemplate($this->getTemplate('index.html.twig'))
            ->render(['name' => $name]);

        return new Response($content);
    }

    public function getTemplate(string $name): string
    {
        return [
            'index.html.twig' => <<<'TWIG'
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Symfony One File Challenge</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
</head>
<body class="bg-indigo-700">
    <div class="px-6 py-24 sm:px-6 sm:py-32 lg:px-8">
        <div class="mx-auto max-w-2xl text-center">
            <svg class="mx-auto" xmlns="http://www.w3.org/2000/svg" xml:space="preserve" width="128" height="128" fill="#fff" stroke="#fff" viewBox="-25.6 -25.6 563.2 563.2"><rect width="563.2" height="563.2" x="-25.6" y="-25.6" fill="#4338ca" stroke="none" stroke-width="0" rx="281.6"/><path d="M255.991.5C114.889.5.5 114.882.5 255.985.5 397.105 114.889 511.5 255.991 511.5 397.11 511.5 511.5 397.105 511.5 255.985 511.5 114.882 397.11.5 255.991.5zm137.797 147.873c-11.83.416-19.993-6.649-20.376-17.391-.121-3.941.89-7.368 3.597-11.402 2.633-5.16 3.202-5.759 3.136-8.013-.245-6.758-10.463-7.012-13.257-6.883-38.354 1.272-48.464 53.028-56.656 95.12l-4.009 22.193c22.082 3.231 37.759-.752 46.509-6.412 12.31-7.988-3.452-16.205-1.473-25.296 2.029-9.265 10.451-13.739 17.143-13.918 9.377-.245 16.072 9.489 15.86 19.357-.329 16.322-21.981 38.74-65.293 37.821a148.213 148.213 0 0 1-14.646-1.044l-8.176 45.102c-7.311 34.133-17.024 80.79-51.795 121.493-29.87 35.529-60.178 41.031-73.747 41.492-25.4.874-42.229-12.675-42.841-30.747-.582-17.507 14.891-27.071 25.051-27.388 13.549-.449 22.93 9.373 23.292 20.692.345 9.564-4.653 12.559-7.972 14.363-2.204 1.784-5.527 3.605-5.402 7.544.079 1.68 1.884 5.563 7.522 5.381 10.741-.366 17.874-5.677 22.852-9.231 24.739-20.602 34.258-56.53 46.725-121.926l2.611-15.839c4.259-21.271 8.967-44.974 16.161-68.602-17.434-13.128-27.892-29.4-51.342-35.767-16.077-4.37-25.883-.661-32.77 8.055-8.162 10.321-5.455 23.753 2.429 31.629l13.029 14.405c15.96 18.455 24.705 32.813 21.408 52.113-5.211 30.847-41.951 54.491-85.379 41.143-37.073-11.419-44.001-37.667-39.544-52.138 3.926-12.721 14.035-15.124 23.925-12.102 10.587 3.285 14.741 16.156 11.71 26.023-.346 1.057-.886 2.845-1.988 5.198-1.234 2.729-3.505 5.119-4.495 8.292-2.379 7.768 8.259 13.282 15.67 15.561 16.588 5.106 32.777-3.567 36.878-16.991 3.813-12.338-3.988-20.945-7.224-24.243L145.204 259.2c-7.182-8.009-22.98-30.311-15.282-55.364 2.973-9.656 9.24-19.902 18.318-26.689 19.179-14.288 40.034-16.642 59.896-10.924 25.687 7.386 38.038 24.381 54.048 37.496 8.953-26.269 21.375-51.992 40.047-73.703 16.867-19.778 39.522-34.096 65.477-34.985 25.936-.856 45.539 10.899 46.184 29.504.261 7.92-4.288 23.311-20.104 23.838z"/></svg>
            <h2 class="text-3xl font-bold tracking-tight text-white sm:text-4xl mt-2">Hello {{ name|title }}</h2>
            <p class="mx-auto mt-6 max-w-xl text-lg leading-8 text-indigo-200">Welcome to Symfony One File Challenge 2024<br>Packing a Symfony full-stack Framework Application in one File.</p>
        </div>
    </div>
</body>
</html>
TWIG,
        ][$name];
    }

    public function registerBundles(): iterable
    {
        yield new FrameworkBundle();
        yield new TwigBundle();
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
