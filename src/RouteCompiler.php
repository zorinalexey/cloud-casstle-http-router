<?php

declare(strict_types=1);

namespace CloudCastle\Http\Router;

/**
 * Compiles routes for caching
 */
class RouteCompiler
{
    /**
     * Compile routes to cacheable array
     *
     * @param array<Route> $routes
     * @return array<string, mixed>
     */
    public function compile(array $routes): array
    {
        $compiled = [
            'routes' => [],
            'named' => [],
            'tagged' => [],
            'metadata' => [
                'compiled_at' => time(),
                'routes_count' => count($routes),
            ],
        ];

        foreach ($routes as $index => $route) {
            $routeData = $this->compileRoute($route);
            $compiled['routes'][] = $routeData;

            // Store named routes mapping
            if ($route->getName() !== null) {
                $compiled['named'][$route->getName()] = $index;
            }

            // Store tagged routes mapping
            foreach ($route->getTags() as $tag) {
                if (!isset($compiled['tagged'][$tag])) {
                    $compiled['tagged'][$tag] = [];
                }

                $compiled['tagged'][$tag][] = $index;
            }
        }

        return $compiled;
    }

    /**
     * Compile a single route
     *
     * @return array<string, mixed>
     */
    private function compileRoute(Route $route): array
    {
        return [
            'methods' => $route->getMethods(),
            'uri' => $route->getUri(),
            'action' => $this->serializeAction($route->getAction()),
            'name' => $route->getName(),
            'tags' => $route->getTags(),
            'middleware' => $this->serializeMiddleware($route->getMiddleware()),
            'domain' => $route->getDomain(),
            'port' => $route->getPort(),
            'whitelist_ips' => $route->getWhitelistIps(),
            'blacklist_ips' => $route->getBlacklistIps(),
            'namespace' => $route->namespace ?? null,
        ];
    }

    /**
     * Serialize action for caching
     *
     * @return array<string, mixed>|string
     */
    private function serializeAction(mixed $action): array|string
    {
        // Closure cannot be serialized directly, store as indicator
        if ($action instanceof \Closure) {
            return [
                'type' => 'closure',
                'serialized' => false,
                'warning' => 'Closures cannot be cached. This route will need to be re-registered.',
            ];
        }

        // Array action [Controller, method]
        if (is_array($action)) {
            return [
                'type' => 'array',
                'controller' => is_object($action[0]) ? $action[0]::class : $action[0],
                'method' => $action[1],
            ];
        }

        // String action
        if (is_string($action)) {
            return [
                'type' => 'string',
                'action' => $action,
            ];
        }

        return [
            'type' => 'unknown',
            'value' => serialize($action),
        ];
    }

    /**
     * Serialize middleware for caching
     *
     * @param array<class-string|callable> $middleware
     * @return array<array<string, mixed>>
     */
    private function serializeMiddleware(array $middleware): array
    {
        $serialized = [];

        foreach ($middleware as $m) {
            if (is_string($m)) {
                $serialized[] = [
                    'type' => 'class',
                    'class' => $m,
                ];
            } elseif (is_callable($m)) {
                $serialized[] = [
                    'type' => 'callable',
                    'warning' => 'Callables cannot be fully cached',
                ];
            } else {
                $serialized[] = [
                    'type' => 'unknown',
                ];
            }
        }

        return $serialized;
    }

    /**
     * Restore routes from compiled cache
     *
     * @param array<string, mixed> $compiled
     * @return array<Route>
     */
    public function restore(array $compiled): array
    {
        $routes = [];

        foreach ($compiled['routes'] as $routeData) {
            $route = $this->restoreRoute($routeData);
            if ($route instanceof \CloudCastle\Http\Router\Route) {
                $routes[] = $route;
            }
        }

        return $routes;
    }

    /**
     * Restore a single route from compiled data
     *
     * @param array<string, mixed> $routeData
     */
    private function restoreRoute(array $routeData): ?Route
    {
        $action = $this->unserializeAction($routeData['action']);

        // Skip routes with closures (they cannot be restored)
        if ($action === null) {
            return null;
        }

        $route = new Route(
            $routeData['methods'],
            $routeData['uri'],
            $action
        );

        // Restore route properties
        if ($routeData['name'] !== null) {
            $route->name($routeData['name']);
        }

        if (!empty($routeData['tags'])) {
            $route->tag($routeData['tags']);
        }

        $middleware = $this->unserializeMiddleware($routeData['middleware']);
        if ($middleware !== []) {
            $route->middleware($middleware);
        }

        if ($routeData['domain'] !== null) {
            $route->domain($routeData['domain']);
        }

        if (isset($routeData['port']) && $routeData['port'] !== null) {
            $route->port($routeData['port']);
        }

        if (!empty($routeData['whitelist_ips'])) {
            $route->whitelistIp($routeData['whitelist_ips']);
        }

        if (!empty($routeData['blacklist_ips'])) {
            $route->blacklistIp($routeData['blacklist_ips']);
        }

        if ($routeData['namespace'] !== null) {
            $route->namespace = $routeData['namespace'];
        }

        return $route;
    }

    /**
     * Unserialize action from cached data
     *
     * @param array<string, mixed>|string $actionData
     */
    private function unserializeAction(array|string $actionData): mixed
    {
        if (is_string($actionData)) {
            return $actionData;
        }

        $type = $actionData['type'] ?? 'unknown';

        return match ($type) {
            'array' => [$actionData['controller'], $actionData['method']],
            'string' => $actionData['action'],
            'closure' => null, // Closures cannot be restored
            default => null,
        };
    }

    /**
     * Unserialize middleware from cached data
     *
     * @param array<array<string, mixed>> $middlewareData
     * @return array<class-string>
     */
    private function unserializeMiddleware(array $middlewareData): array
    {
        $middleware = [];

        foreach ($middlewareData as $m) {
            if ($m['type'] === 'class') {
                $middleware[] = $m['class'];
            }

            // Callables are skipped as they cannot be restored
        }

        return $middleware;
    }
}
