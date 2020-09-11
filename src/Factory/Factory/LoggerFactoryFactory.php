<?php declare(strict_types=1);

namespace LaminasMonolog\Factory\Factory;

use LaminasMonolog\Factory\LoggerFactory;
use LaminasMonolog\Formatter\FormatterPluginManager;
use LaminasMonolog\Handler\HandlerPluginManager;
use LaminasMonolog\Processor\ProcessorPluginManager;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class LoggerFactoryFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new LoggerFactory(
            $container,
            $container->get(HandlerPluginManager::class),
            $container->get(ProcessorPluginManager::class),
            $container->get(FormatterPluginManager::class)
        );
    }

}
