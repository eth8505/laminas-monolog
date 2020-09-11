<?php

namespace LaminasMonologTest\Factory;

use LaminasMonolog\Factory\ReflectionAbstractFactory;
use LaminasMonologTest\Asset\DummyClassWithConstructorArgs;
use Interop\Container\ContainerInterface;
use PHPUnit\Framework\TestCase;
use Laminas\ServiceManager\AbstractPluginManager;
use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Laminas\ServiceManager\ServiceManager;

class ReflectionAbstractFactoryTest extends TestCase
{

    public function testCanCreateReturnsTrue(): void
    {
        $factory = new ReflectionAbstractFactory();
        $this->assertTrue($factory->canCreate($this->createMock(ContainerInterface::class), 'dummy'));
    }

    public function testCreateWithoutOptions(): void
    {

        $container = new ServiceManager([
            'abstract_factories' => [ReflectionAbstractFactory::class]
        ]);

        $this->assertInstanceOf(\stdClass::class, $container->get(\stdClass::class));

    }

    public function testCreateWithMissingRequiredOptionFails(): void
    {

        $container = new ServiceManager([
            'abstract_factories' => [ReflectionAbstractFactory::class]
        ]);

        $this->expectException(ServiceNotCreatedException::class);
        $this->expectExceptionMessageMatches('/::__construct\(\) requires at least 1 arguments; 0 given/');
        $container->get(DummyClassWithConstructorArgs::class);

    }



    public function testCreateWithOptions(): void
    {

        $container = new class($this->createMock(ContainerInterface::class)) extends AbstractPluginManager {

            /**
             * @inheritDoc
             */
            public function __construct($configInstanceOrParentLocator = null, array $config = [])
            {
                parent::__construct($configInstanceOrParentLocator, [
                    'abstract_factories' => [ReflectionAbstractFactory::class]
                ]);
            }


        };

        $this->assertInstanceOf(DummyClassWithConstructorArgs::class, $container->get(DummyClassWithConstructorArgs::class, [
            'b' => true
        ]));

    }

}
