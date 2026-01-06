<?php declare(strict_types=1);

namespace LaminasMonolog\Processor\Factory;

use LaminasMonolog\Processor\ProcessorPluginManager;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class ProcessorPluginManagerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        return new ProcessorPluginManager(
            $container,
            $container->get('Config')['monolog']['processors'] ?? []
        );
    }

}
