<?php declare(strict_types=1);

namespace LaminasMonolog\Factory;

use LaminasMonolog\Formatter\FormatterPluginManager;
use LaminasMonolog\Handler\HandlerPluginManager;
use LaminasMonolog\MonologOptions;
use LaminasMonolog\Processor\ProcessorPluginManager;
use Interop\Container\ContainerInterface;
use Monolog\Handler\HandlerInterface;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

class LoggerFactory
{

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var HandlerPluginManager
     */
    private $handlerPluginManager;

    /**
     * @var ProcessorPluginManager
     */
    private $processorPluginManager;

    /**
     * @var FormatterPluginManager
     */
    private $formatterPluginManager;

    /**
     * Constructor
     * @param ContainerInterface $container
     * @param HandlerPluginManager $handlerPluginManager
     * @param ProcessorPluginManager $processorPluginManager
     * @param FormatterPluginManager $formatterPluginManager
     */
    public function __construct(
        ContainerInterface $container,
        HandlerPluginManager $handlerPluginManager,
        ProcessorPluginManager $processorPluginManager,
        FormatterPluginManager $formatterPluginManager
    ) {
        $this->container = $container;
        $this->handlerPluginManager = $handlerPluginManager;
        $this->processorPluginManager = $processorPluginManager;
        $this->formatterPluginManager = $formatterPluginManager;
    }

    /**
     * Create logger
     *
     * @param MonologOptions $options
     * @return LoggerInterface
     */
    public function __invoke(MonologOptions $options): LoggerInterface
    {

        $logger = new Logger($options->getName());

        foreach ($options->getHandlers() as $handlerConfig) {

            if (!is_array($handlerConfig)) {
                $handlerConfig = ['name' => $handlerConfig];
            }

            $logger->pushHandler($this->createHandler($handlerConfig));
        }

        foreach ($options->getProcessors() as $processorConfig) {

            if (!is_array($processorConfig)) {
                $processorConfig = ['name' => $processorConfig];
            }

            $logger->pushProcessor(
                $this->processorPluginManager->get($processorConfig['name'], $processorConfig['options'] ?? [])
            );

        }

        return $logger;


    }

    /**
     * Create handler from config
     *
     * @param array $handlerConfig
     * @return HandlerInterface
     */
    private function createHandler(array $handlerConfig): HandlerInterface
    {

        $handler = $this->handlerPluginManager->get($handlerConfig['name'], $handlerConfig['options'] ?? []);

        if (isset($handlerConfig['formatter'])) {

            $formatterConfig = is_array($handlerConfig['formatter'])
                ? $handlerConfig['formatter']
                : ['name' => $handlerConfig['formatter']];

            $formatter = $this->formatterPluginManager->get(
                $formatterConfig['name'],
                $formatterConfig['options'] ?? []
            );

            $handler->setFormatter($formatter);

        }

        return $handler;

    }

}
