<?php

namespace LaminasMonologTest\Processor;

use LaminasMonolog\Processor\ProcessorPluginManager;
use Monolog\Processor\ProcessorInterface;
use PHPUnit\Framework\TestCase;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Exception\ServiceNotFoundException;

class ProcessorPluginManagerTest extends TestCase
{

    public function testGetPlugin(): void
    {

        $handler = $this->createMock(ProcessorInterface::class);

        $handlerPluginManager = new ProcessorPluginManager($this->createMock(ContainerInterface::class), [
            'services' => [
                ProcessorInterface::class => $handler,
            ],
        ]);
        $handlerReturned = $handlerPluginManager->get(ProcessorInterface::class);
        $this->assertSame($handler, $handlerReturned);

    }

    public function testValidateInvalidPlugin()
    {

        $this->expectException(ServiceNotFoundException::class);

        $handlerPluginManager = new ProcessorPluginManager($this->createMock(ContainerInterface::class));
        $handlerPluginManager->get('invalidpluginname');

    }

}
