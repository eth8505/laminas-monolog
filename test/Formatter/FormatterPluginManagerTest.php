<?php

namespace LaminasMonologTest\Formatter;

use LaminasMonolog\Formatter\FormatterPluginManager;
use Interop\Container\ContainerInterface;
use Monolog\Formatter\FormatterInterface;
use PHPUnit\Framework\TestCase;
use Laminas\ServiceManager\Exception\ServiceNotFoundException;

class FormatterPluginManagerTest extends TestCase
{

    public function testGetPlugin(): void
    {

        $handler = $this->createMock(FormatterInterface::class);

        $handlerPluginManager = new FormatterPluginManager($this->createMock(ContainerInterface::class), [
            'services' => [
                FormatterInterface::class => $handler,
            ],
        ]);
        $handlerReturned = $handlerPluginManager->get(FormatterInterface::class);
        $this->assertSame($handler, $handlerReturned);

    }

    public function testValidateInvalidPlugin()
    {

        $this->expectException(ServiceNotFoundException::class);

        $handlerPluginManager = new FormatterPluginManager($this->createMock(ContainerInterface::class));
        $handlerPluginManager->get('invalidpluginname');

    }

}
