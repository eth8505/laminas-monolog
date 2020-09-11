<?php declare(strict_types=1);

namespace LaminasMonolog\Handler;

use Monolog\Handler\HandlerInterface;
use Laminas\ServiceManager\AbstractPluginManager;

/**
 * Plugin manager for handlers
 */
class HandlerPluginManager extends AbstractPluginManager
{

    /**
     * @var string
     */
    protected $instanceOf = HandlerInterface::class;

}
