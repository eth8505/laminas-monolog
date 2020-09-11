<?php declare(strict_types=1);

namespace LaminasMonolog\Formatter;

use Monolog\Formatter\FormatterInterface;
use Laminas\ServiceManager\AbstractPluginManager;

/**
 * Plugin manager for formatters
 */
class FormatterPluginManager extends AbstractPluginManager
{

    /**
     * @var string
     */
    protected $instanceOf = FormatterInterface::class;

}
