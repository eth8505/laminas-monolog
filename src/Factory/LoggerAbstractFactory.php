<?php declare(strict_types=1);

namespace LaminasMonolog\Factory;

use LaminasMonolog\Exception\InvalidArgumentException;
use LaminasMonolog\Exception\OutOfBoundsException;
use LaminasMonolog\Exception\RuntimeException;
use LaminasMonolog\MonologOptions;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\AbstractFactoryInterface;

/**
 * Abstract logger factory to create arbitrary loggers
 */
class LoggerAbstractFactory implements AbstractFactoryInterface
{

    /**
     * @var int
     */
    private const INHERITANCE_LEVEL_LIMIT = 10;

    /**
     * @inheritDoc
     */
    public function canCreate(ContainerInterface $container, $requestedName)
    {
        return $this->getLoggerConfig($container->get('Config')['monolog'], $requestedName) !== null;
    }

    /**
     * Get logger config from config
     *
     * @param array $config
     * @param string $requestedName
     * @return MonologOptions|null
     */
    private function getLoggerConfig(array $config, string $requestedName): ?MonologOptions
    {

        if (!isset($config['loggers']) || !is_array($config['loggers'])) {
            return null;
        }

        $loggers = $config['loggers'];

        if (!isset($loggers[$requestedName]) || !is_array($loggers[$requestedName])) {
            return null;
        }

        $loggerConfig = $loggers[$requestedName];

        if (isset($loggerConfig['@extends'])) {

            $recursionDepth = 0;

            do {

                if (($recursionDepth + 1) > self::INHERITANCE_LEVEL_LIMIT) {
                    throw new RuntimeException(sprintf('Maximum inheritance level of %u reached', self::INHERITANCE_LEVEL_LIMIT));
                } elseif (!is_string($loggerConfig['@extends'])) {
                    throw new InvalidArgumentException('@extends must be string');
                } elseif (!isset($loggers[$loggerConfig['@extends']])) {
                    throw new OutOfBoundsException("Offset {$loggerConfig['@extends']} does not exist");
                }

                $nextConfig = $loggers[$loggerConfig['@extends']];
                unset($loggerConfig['@extends']);
                $loggerConfig = array_replace_recursive($nextConfig, $loggerConfig);
                $recursionDepth++;

            } while (isset($loggerConfig['@extends']));

        }

        return new MonologOptions($this->applyConfigDefaults($loggerConfig, $config));

    }

    private function applyConfigDefaults(array $loggerConfig, array $monologConfig): array
    {

        if (!isset($monologConfig['defaults'])) {
            return $loggerConfig;
        }

        $defaults = $monologConfig['defaults'];

        if (!empty($defaults['processors'])) {
            $loggerConfig['processors'] = array_merge($loggerConfig['processors'] ?? [], $defaults['processors']);
        }

        if ((!empty($defaults['level']) || !empty($default['formatter'])) && !empty($loggerConfig['handlers'])) {

            array_walk($loggerConfig['handlers'], function(array &$handler) use ($defaults) {

                if (!empty($defaults['level'])) {
                    $handler['options']['level'] = $handler['options']['level'] ?? $defaults['level'];
                }

                if (!empty($defaults['formatter']) && empty($handler['formatter'])) {
                    $handler['formatter'] = $defaults['formatter'];
                }

            });

        }

        return $loggerConfig;

    }

    /**
     * @inheritDoc
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {

        $loggerFactory = $container->get(LoggerFactory::class);
        $loggerConfig = $this->getLoggerConfig($container->get('Config')['monolog'], $requestedName);

        if ($loggerConfig === null) {
            throw new RuntimeException("Logger config for \"{$requestedName}\" not found");
        }

        return $loggerFactory($loggerConfig);

    }

}
