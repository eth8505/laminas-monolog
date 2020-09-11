<?php declare(strict_types=1);

namespace LaminasMonolog\Processor;

use Monolog\Processor\ProcessorInterface;
use Laminas\ServiceManager\AbstractPluginManager;

/**
 * Plugin manager for processors
 */
class ProcessorPluginManager extends AbstractPluginManager
{

    /**
     * @var string
     */
    protected $instanceOf = ProcessorInterface::class;

}
