<?php

declare(strict_types=1);

namespace CloudCastle\Http\Router;

use ArrayAccess;
use Countable;
use Iterator;

/**
 * Optimized route collection with fast lookups.
 */
class RouteCollection implements Iterator, Countable, ArrayAccess
{
    /** @var array<Route> */
    private array $routes = [];

    /** @var array<string, Route> */
    private array $namedRoutes = [];

    /** @var array<string, Route> */
    private array $exactMatches = [];

    private int $position = 0;

    /**
     * Add route to collection.
     */
    public function add(Route $route): void
    {
        $this->routes[] = $route;

        // Index by name
        $name = $route->getName();
        if ($name !== null && $name !== '' && $name !== '0') {
            $this->namedRoutes[$name] = $route;
        }

        // Index exact matches for O(1) lookup
        $uri = $route->getUri();
        if (!str_contains($uri, '{') && !str_contains($uri, '(')) {
            foreach ($route->getMethods() as $method) {
                $key = $method . ':' . $uri;
                $this->exactMatches[$key] = $route;
            }
        }
    }

    /**
     * Fast lookup for exact matches.
     */
    public function matchExact(string $uri, string $method): ?Route
    {
        $key = strtoupper($method) . ':' . $uri;

        return $this->exactMatches[$key] ?? null;
    }

    /**
     * Get route by name.
     */
    public function getByName(string $name): ?Route
    {
        return $this->namedRoutes[$name] ?? null;
    }

    /**
     * Get all routes.
     *
     * @return array<Route>
     */
    public function all(): array
    {
        return $this->routes;
    }

    // Iterator implementation
    public function current(): Route
    {
        return $this->routes[$this->position];
    }

    public function key(): int
    {
        return $this->position;
    }

    public function next(): void
    {
        ++$this->position;
    }

    public function rewind(): void
    {
        $this->position = 0;
    }

    public function valid(): bool
    {
        return isset($this->routes[$this->position]);
    }

    // Countable implementation
    public function count(): int
    {
        return count($this->routes);
    }

    // ArrayAccess implementation
    public function offsetExists(mixed $offset): bool
    {
        return isset($this->routes[$offset]);
    }

    public function offsetGet(mixed $offset): ?Route
    {
        return $this->routes[$offset] ?? null;
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        if ($offset === null) {
            $this->add($value);
        } else {
            $this->routes[$offset] = $value;
        }
    }

    public function offsetUnset(mixed $offset): void
    {
        unset($this->routes[$offset]);
    }
}
