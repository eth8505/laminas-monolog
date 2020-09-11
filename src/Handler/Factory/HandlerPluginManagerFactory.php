<?php declare(strict_types=1);

namespace LaminasMonolog\Handler\Factory;

use LaminasMonolog\Handler\HandlerPluginManager;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class HandlerPluginManagerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new HandlerPluginManager(
            $container,
            $container->get('Config')['monolog']['handlers'] ?? []
        );
    }

}
