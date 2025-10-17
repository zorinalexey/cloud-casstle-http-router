<?php

declare(strict_types=1);

namespace CloudCastle\Http\Router;

use Closure;
use CloudCastle\Http\Router\Exceptions\InvalidActionException;
use ReflectionClass;
use ReflectionException;

/**
 * Resolves and executes route actions.
 */
class ActionResolver
{
    /**
     * Resolve and execute action.
     *
     * @param array<string, mixed> $parameters
     *
     * @throws InvalidActionException
     */
    public function resolve(mixed $action, array $parameters = []): mixed
    {
        // Closure
        if ($action instanceof Closure) {
            return $this->resolveClosure($action, $parameters);
        }

        // Array [Controller, method]
        if (is_array($action) && count($action) === 2) {
            return $this->resolveArray($action, $parameters);
        }

        // String with @ or :: separator
        if (is_string($action)) {
            return $this->resolveString($action, $parameters);
        }

        throw new InvalidActionException(
            'Invalid action type. Expected Closure, array [Controller, method], or string "Controller@method"'
        );
    }

    /**
     * Resolve closure action.
     *
     * @param array<string, mixed> $parameters
     */
    private function resolveClosure(Closure $closure, array $parameters): mixed
    {
        return call_user_func_array($closure, $parameters);
    }

    /**
     * Resolve array action [Controller, method].
     *
     * @param array<int, mixed> $action
     * @param array<string, mixed> $parameters
     *
     * @throws InvalidActionException
     */
    private function resolveArray(array $action, array $parameters): mixed
    {
        [$controller, $method] = $action;

        // If controller is a string, instantiate it
        if (is_string($controller)) {
            try {
                $controller = $this->instantiateController($controller);
            } catch (ReflectionException $e) {
                throw new InvalidActionException(
                    sprintf('Cannot instantiate controller: %s. ', $controller) . $e->getMessage()
                );
            }
        }

        // Check if method exists
        if (!method_exists($controller, $method)) {
            $controllerClass = $controller::class;

            throw new InvalidActionException(
                sprintf('Method %s does not exist on controller %s', $method, $controllerClass)
            );
        }

        return call_user_func_array([$controller, $method], $parameters);
    }

    /**
     * Resolve string action "Controller@method" or "Controller::method".
     *
     * @param array<string, mixed> $parameters
     *
     * @throws InvalidActionException
     */
    private function resolveString(string $action, array $parameters): mixed
    {
        // Check for @ separator
        if (str_contains($action, '@')) {
            [$controller, $method] = explode('@', $action, 2);

            return $this->resolveArray([$controller, $method], $parameters);
        }

        // Check for :: separator
        if (str_contains($action, '::')) {
            [$controller, $method] = explode('::', $action, 2);

            return $this->resolveArray([$controller, $method], $parameters);
        }

        throw new InvalidActionException(
            sprintf("Invalid string action format: %s. Expected 'Controller@method' or 'Controller::method'", $action)
        );
    }

    /**
     * Instantiate a controller class.
     *
     * @throws ReflectionException
     */
    private function instantiateController(string $controller): object
    {
        $reflection = new ReflectionClass($controller);

        // Get constructor parameters
        $constructor = $reflection->getConstructor();

        if ($constructor === null || $constructor->getNumberOfRequiredParameters() === 0) {
            return $reflection->newInstance();
        }

        // Try to resolve constructor dependencies (basic implementation)
        $dependencies = [];
        foreach ($constructor->getParameters() as $parameter) {
            $type = $parameter->getType();

            // If parameter has a class type, try to instantiate it
            if ($type && !$type->isBuiltin()) {
                $className = $type->getName();
                $dependencies[] = new $className();
            } elseif ($parameter->isDefaultValueAvailable()) {
                $dependencies[] = $parameter->getDefaultValue();
            } else {
                throw new InvalidActionException(
                    sprintf('Cannot resolve parameter $%s for controller %s', $parameter->getName(), $controller)
                );
            }
        }

        return $reflection->newInstanceArgs($dependencies);
    }

    /**
     * Set container for dependency injection (optional enhancement).
     */
    public function setContainer(?object $container): self
    {
        // This can be enhanced to use a DI container
        // For now, we keep it simple
        return $this;
    }
}
