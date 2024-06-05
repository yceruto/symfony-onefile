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
        return new Response($twig->render('index.html.twig', ['name' => $name]));
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
