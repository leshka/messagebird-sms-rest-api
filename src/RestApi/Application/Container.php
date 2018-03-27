<?php
declare(strict_types=1);

namespace RestApi\Application;

use Psr\Container\ContainerInterface;
use ReflectionClass;
use ReflectionException;
use RestApi\Exception\ContainerException;
use RestApi\Exception\ContainerNotFoundException;
use RestApi\ParameterBag;

/**
 * Simple DI container implementation.
 * It can create objects by request as well as get configuration parameters in it.
 * Configuration can be added using addDefinitions method.
 *
 * @package RestApi\Application
 */
class Container implements ContainerInterface
{

    /**
     * @var ParameterBag|null
     */
    private $params;

    /**
     * Container constructor.
     */
    public function __construct()
    {
        $this->params = new ParameterBag();
    }

    /**
     * @param array $definitions an assoc array having key as an entry identifier and mixed value as value.
     */
    public function addDefinitions(array $definitions): void
    {
        foreach ($definitions as $key => $value) {
            $this->params->set($key, $value);
        }
    }

    /**
     * @param string $id an entry identifier
     * @return mixed|null
     *
     * @throws ContainerException if impossible to instantiate the dependency
     * @throws ContainerNotFoundException if there is no entry saved at definitions
     */
    public function get($id)
    {
        if (!$this->has($id)) {
            if (!class_exists($id)) {
                throw new ContainerNotFoundException(sprintf('No entry was found for %s identifier', $id));
            }
            return $this->resolve($id);
        }

        $value = $this->params->get($id);
        if (\is_callable($value)) {
            return $value($this);
        }

        return $value;
    }

    /**
     * Initializing objects using it's default values or pre-defined definitions for container.
     *
     * @param string $className
     * @return mixed|null|object
     *
     * @throws ContainerException
     * @throws ContainerNotFoundException
     */
    private function resolve(string $className)
    {
        if ($this->has($className)) {
            return $this->get($className);
        }

        try {
            $reflector = new ReflectionClass($className);
        } catch (ReflectionException $exception) {
            throw new ContainerException($exception->getMessage());
        }

        if (!$reflector->isInstantiable()) {
            throw new ContainerException(sprintf('%s is not instantiable', $className));
        }

        if (null === $reflector->getConstructor()) {
            return new $className;
        }

        $parameters = $reflector->getConstructor()->getParameters();
        $dependencies = array();
        foreach ($parameters as $parameter) {
            if (null === $parameter->getClass()) { // if it's not a class
                if (!$parameter->isDefaultValueAvailable()) {
                    throw new ContainerException(sprintf('Cannot resolve parameter %s for class %s', $parameter->getName(), $className));
                }
                $dependencies[] = $parameter->getDefaultValue();
            } else {
                $dependencies[] = $this->resolve($parameter->getClass()->name);
            }
        }

        return $reflector->newInstanceArgs($dependencies);
    }

    /**
     * Checks if an entry identifier is known to the container
     *
     * @param string $id an entry identifier
     * @return bool
     */
    public function has($id): bool
    {
        return $this->params->has($id);
    }

}
