<?php
namespace App;

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Bundle\SecurityBundle\SecurityBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollectionBuilder;

/**
 * Class AppKernel
 *
 * @package App
 * @author  Maarten Bicknese <maarten.bicknese@devmob.com>
 */
class AppKernel extends Kernel
{
    use MicroKernelTrait;

    /**
     * @return array
     */
    public function registerBundles(): array
    {
        $bundles = [
            new FrameworkBundle(),
            new SecurityBundle(),
            new DoctrineBundle(),
        ];

        return $bundles;
    }

    /**
     * @return string
     */
    public function getCacheDir(): string
    {
        return '/dev/shm/cache/' . $this->getEnvironment();
    }

    /**
     * @return string
     */
    public function getLogDir(): string
    {
        return '/dev/shm/logs';
    }

    /**
     * @param ContainerBuilder $c
     * @param LoaderInterface  $loader
     */
    protected function configureContainer(ContainerBuilder $c, LoaderInterface $loader): void
    {
        $loader->load(__DIR__ . '/Resources/config/config_' . $this->getEnvironment() . '.yml');
        $loader->load(__DIR__ . '/Resources/config/services.yml');
        $loader->load(__DIR__ . '/Resources/config/security.yml');
    }

    /**
     * @param RouteCollectionBuilder $routes
     */
    protected function configureRoutes(RouteCollectionBuilder $routes): void
    {
        $routes->addRoute(new Route(
            '/match',
            ['_controller' => 'app.controller.match:joinMatch'],
            [],
            [],
            '',
            [],
            ['POST']
        ), 'join');
        $routes->addRoute(new Route(
            '/match/{matchId}',
            ['_controller' => 'app.controller.match:displayMatch'],
            [],
            [],
            '',
            [],
            ['GET']
        ), 'display');
        $routes->addRoute(new Route(
            '/ship',
            ['_controller' => 'app.controller.ship:place'],
            [],
            [],
            '',
            [],
            ['POST']
        ), 'placeShip');
        $routes->addRoute(new Route(
            '/shot',
            ['_controller' => 'app.controller.shot:fire'],
            [],
            [],
            '',
            [],
            ['POST']
        ), 'fireShot');
    }
}
