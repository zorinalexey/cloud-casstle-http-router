<?php

declare(strict_types=1);

namespace CloudCastle\Http\Router;

use CloudCastle\Http\Router\Contracts\MiddlewareInterface;
use CloudCastle\Http\Router\Exceptions\InvalidActionException;

/**
 * Dispatches middleware chain.
 */
class MiddlewareDispatcher
{
    private int $index = 0;

    /**
     * @param array<class-string|callable> $middleware
     */
    public function __construct(private array $middleware = [])
    {
    }

    /**
     * Process middleware chain.
     */
    public function dispatch(mixed $request, callable $finalHandler): mixed
    {
        $this->index = 0;

        return $this->next($request, $finalHandler);
    }

    /**
     * Execute next middleware in chain.
     */
    private function next(mixed $request, callable $finalHandler): mixed
    {
        // If no more middleware, execute final handler
        if (!isset($this->middleware[$this->index])) {
            return $finalHandler($request);
        }

        $middleware = $this->middleware[$this->index++];

        // Resolve middleware
        $middlewareInstance = $this->resolveMiddleware($middleware);

        // Execute middleware
        return $middlewareInstance->handle($request, fn ($req): mixed => $this->next($req, $finalHandler));
    }

    /**
     * Resolve middleware to instance.
     *
     * @param class-string|callable $middleware
     *
     * @throws InvalidActionException
     */
    private function resolveMiddleware(string|callable $middleware): MiddlewareInterface
    {
        // If it's a callable, wrap it in an anonymous middleware
        if (is_callable($middleware) && !is_string($middleware)) {
            return new class ($middleware) implements MiddlewareInterface {
                private $callable;

                public function __construct(callable $callable)
                {
                    $this->callable = $callable;
                }

                public function handle(mixed $request, callable $next): mixed
                {
                    return ($this->callable)($request, $next);
                }
            };
        }

        // If it's a class name, instantiate it
        if (class_exists($middleware)) {
            $instance = new $middleware();

            if (!$instance instanceof MiddlewareInterface) {
                throw new InvalidActionException(
                    sprintf('Middleware %s must implement MiddlewareInterface', $middleware)
                );
            }

            return $instance;
        }

        throw new InvalidActionException('Invalid middleware: ' . print_r($middleware, true));
    }

    /**
     * Add middleware to the stack.
     *
     * @param class-string|callable $middleware
     */
    public function add(string|callable $middleware): self
    {
        $this->middleware[] = $middleware;

        return $this;
    }

    /**
     * Get all middleware.
     *
     * @return array<class-string|callable>
     */
    public function getMiddleware(): array
    {
        return $this->middleware;
    }
}
