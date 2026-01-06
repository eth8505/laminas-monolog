<?php declare(strict_types=1);

namespace LaminasMonolog\Formatter\Factory;

use LaminasMonolog\Formatter\FormatterPluginManager;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class FormatterPluginManagerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        return new FormatterPluginManager(
            $container,
            $container->get('Config')['monolog']['formatters'] ?? []
        );
    }

}
