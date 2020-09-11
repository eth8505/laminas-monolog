<?php declare(strict_types=1);

namespace LaminasMonolog\Factory;

use LaminasMonolog\Exception\ClassNotFoundException;
use LaminasMonolog\Exception\InvalidArgumentException;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\AbstractFactoryInterface;

/**
 * Factory to generically create instances via named parameters using reflection
 */
class ReflectionAbstractFactory implements AbstractFactoryInterface
{

    /**
     * @inheritDoc
     */
    public function canCreate(ContainerInterface $container, $requestedName)
    {
        return true;
    }

    /**
     * @inheritDoc
     * @throws \ReflectionException
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {

        if (!class_exists($requestedName)) {
            throw new ClassNotFoundException($requestedName);
        }

        $reflectionClass = new \ReflectionClass($requestedName);
        $constructor = $reflectionClass->getConstructor();

        if ($constructor === null) {
            return $reflectionClass->newInstance();
        }

        $requiredArgsCount = $constructor->getNumberOfRequiredParameters();
        $argumentCount = $options === null ? 0 : count($options);

        if ($requiredArgsCount > $argumentCount) {

            throw new InvalidArgumentException(sprintf(
                '%s::__construct() requires at least %d arguments; %d given',
                $requestedName,
                $requiredArgsCount,
                $argumentCount
            ));

        }

        $parameters = [];
        foreach ($constructor->getParameters() as $parameter) {

            $parameterName = $parameter->getName();

            if ($options !== null && array_key_exists($parameterName, $options)) {
                $parameters[$parameter->getPosition()] = $options[$parameterName];
                continue;
            }

            if (!$parameter->isOptional()) {
                throw new InvalidArgumentException("Missing at least one required parameters \"{$parameterName}\"");
            }

            $parameters[$parameter->getPosition()] = $parameter->getDefaultValue();

        }

        return $reflectionClass->newInstanceArgs($parameters);

    }

}
