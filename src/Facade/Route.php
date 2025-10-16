<?php

declare(strict_types=1);

namespace CloudCastle\Http\Router\Facade;

use CloudCastle\Http\Router\Route as RouteClass;
use CloudCastle\Http\Router\Router;
use CloudCastle\Http\Router\RouteMacros;
use Closure;

/**
 * Static facade for Router
 *
 * @method static RouteClass get(string $uri, mixed $action)
 * @method static RouteClass post(string $uri, mixed $action)
 * @method static RouteClass put(string $uri, mixed $action)
 * @method static RouteClass patch(string $uri, mixed $action)
 * @method static RouteClass delete(string $uri, mixed $action)
 * @method static RouteClass view(string $uri, mixed $action)
 * @method static RouteClass custom(string $method, string $uri, mixed $action)
 * @method static RouteClass match(array $methods, string $uri, mixed $action)
 * @method static RouteClass any(string $uri, mixed $action)
 * @method static void group(array $attributes, Closure $callback)
 * @method static Router middleware(array|string|callable $middleware)
 * @method static RouteClass dispatch(string $uri, string $method, ?string $domain = null, ?string $clientIp = null)
 * @method static Router enableCache(?string $cacheDir = null)
 * @method static Router disableCache()
 * @method static bool loadFromCache()
 * @method static bool compile(bool $force = false)
 * @method static bool clearCache()
 * @method static RouteClass|null getRouteByName(string $name)
 * @method static array getRoutesByTag(string $tag)
 * @method static array getRoutes()
 * @method static void autoCompile()
 */
class Route
{
    /**
     * Get the router instance
     */
    public static function router(): Router
    {
        return Router::getInstance();
    }

    /**
     * Add a GET route
     */
    public static function get(string $uri, mixed $action): RouteClass
    {
        return self::router()->get($uri, $action);
    }

    /**
     * Add a POST route
     */
    public static function post(string $uri, mixed $action): RouteClass
    {
        return self::router()->post($uri, $action);
    }

    /**
     * Add a PUT route
     */
    public static function put(string $uri, mixed $action): RouteClass
    {
        return self::router()->put($uri, $action);
    }

    /**
     * Add a PATCH route
     */
    public static function patch(string $uri, mixed $action): RouteClass
    {
        return self::router()->patch($uri, $action);
    }

    /**
     * Add a DELETE route
     */
    public static function delete(string $uri, mixed $action): RouteClass
    {
        return self::router()->delete($uri, $action);
    }

    /**
     * Add a VIEW route
     */
    public static function view(string $uri, mixed $action): RouteClass
    {
        return self::router()->view($uri, $action);
    }

    /**
     * Add a route with custom HTTP method
     *
     * @param string $method Custom HTTP method (e.g., 'PURGE', 'TRACE', 'CONNECT')
     */
    public static function custom(string $method, string $uri, mixed $action): RouteClass
    {
        return self::router()->custom($method, $uri, $action);
    }

    /**
     * Add a route with multiple HTTP methods
     *
     * @param array<string> $methods
     */
    public static function match(array $methods, string $uri, mixed $action): RouteClass
    {
        return self::router()->match($methods, $uri, $action);
    }

    /**
     * Add a route for all HTTP methods
     */
    public static function any(string $uri, mixed $action): RouteClass
    {
        return self::router()->any($uri, $action);
    }

    /**
     * Create a route group
     *
     * @param array<string, mixed> $attributes
     */
    public static function group(array $attributes, Closure $callback): void
    {
        self::router()->group($attributes, $callback);
    }

    /**
     * Add global middleware
     *
     * @param array<class-string|callable>|class-string|callable $middleware
     */
    public static function middleware(array|string|callable $middleware): Router
    {
        return self::router()->middleware($middleware);
    }

    /**
     * Dispatch request
     */
    public static function dispatch(string $uri, string $method, ?string $domain = null, ?string $clientIp = null, ?int $port = null): RouteClass
    {
        return self::router()->dispatch($uri, $method, $domain, $clientIp, $port);
    }

    /**
     * Enable cache
     */
    public static function enableCache(?string $cacheDir = null): Router
    {
        return self::router()->enableCache($cacheDir);
    }

    /**
     * Disable cache
     */
    public static function disableCache(): Router
    {
        return self::router()->disableCache();
    }

    /**
     * Load from cache
     */
    public static function loadFromCache(): bool
    {
        return self::router()->loadFromCache();
    }

    /**
     * Compile routes
     */
    public static function compile(bool $force = false): bool
    {
        return self::router()->compile($force);
    }

    /**
     * Clear cache
     */
    public static function clearCache(): bool
    {
        return self::router()->clearCache();
    }

    /**
     * Get route by name
     */
    public static function getRouteByName(string $name): ?RouteClass
    {
        return self::router()->getRouteByName($name);
    }

    /**
     * Get routes by tag
     *
     * @return array<RouteClass>
     */
    public static function getRoutesByTag(string $tag): array
    {
        return self::router()->getRoutesByTag($tag);
    }

    /**
     * Get all routes
     *
     * @return array<RouteClass>
     */
    public static function getRoutes(): array
    {
        return self::router()->getRoutes();
    }

    /**
     * Auto compile
     */
    public static function autoCompile(): void
    {
        self::router()->autoCompile();
    }

    /**
     * Reset router instance (useful for testing)
     */
    public static function reset(): void
    {
        Router::reset();
    }

    /**
     * Check if cache is loaded
     */
    public static function isCacheLoaded(): bool
    {
        return self::router()->isCacheLoaded();
    }

    /**
     * Get named routes
     *
     * @return array<string, RouteClass>
     */
    public static function getNamedRoutes(): array
    {
        return self::router()->getNamedRoutes();
    }

    /**
     * Get all routes as array
     *
     * @return array<array<string, mixed>>
     */
    public static function getRoutesAsArray(): array
    {
        return self::router()->getRoutesAsArray();
    }

    /**
     * Get routes count
     */
    public static function count(): int
    {
        return self::router()->count();
    }

    /**
     * Get routes as JSON
     */
    public static function getRoutesAsJson(int $flags = 0): string
    {
        return self::router()->getRoutesAsJson($flags);
    }

    /**
     * Get current route
     */
    public static function current(): ?RouteClass
    {
        return self::router()->current();
    }

    /**
     * Get previous route
     */
    public static function previous(): ?RouteClass
    {
        return self::router()->previous();
    }

    /**
     * Get current route name
     */
    public static function currentRouteName(): ?string
    {
        return self::router()->currentRouteName();
    }

    /**
     * Get previous route name
     */
    public static function previousRouteName(): ?string
    {
        return self::router()->previousRouteName();
    }

    /**
     * Check if current route is named
     */
    public static function currentRouteNamed(string $name): bool
    {
        return self::router()->currentRouteNamed($name);
    }

    /**
     * Check if previous route is named
     */
    public static function previousRouteNamed(string $name): bool
    {
        return self::router()->previousRouteNamed($name);
    }

    /**
     * Get routes grouped by method
     *
     * @return array<string, array<RouteClass>>
     */
    public static function getRoutesGroupedByMethod(): array
    {
        return self::router()->getRoutesGroupedByMethod();
    }

    /**
     * Get routes grouped by prefix
     *
     * @return array<string, array<RouteClass>>
     */
    public static function getRoutesGroupedByPrefix(): array
    {
        return self::router()->getRoutesGroupedByPrefix();
    }

    /**
     * Get routes grouped by domain
     *
     * @return array<string, array<RouteClass>>
     */
    public static function getRoutesGroupedByDomain(): array
    {
        return self::router()->getRoutesGroupedByDomain();
    }

    /**
     * Get route statistics
     *
     * @return array<string, mixed>
     */
    public static function getRouteStats(): array
    {
        return self::router()->getRouteStats();
    }

    // ==================== Macros ====================

    /**
     * Register RESTful resource routes
     *
     * @param string $name Resource name
     * @param string $controller Controller class
     */
    public static function resource(string $name, string $controller): void
    {
        RouteMacros::resource($name, $controller);
    }

    /**
     * Register API resource routes
     *
     * @param string $name Resource name
     * @param string $controller Controller class
     * @param int $rateLimit Rate limit
     */
    public static function apiResource(string $name, string $controller, int $rateLimit = 100): void
    {
        RouteMacros::apiResource($name, $controller, $rateLimit);
    }

    /**
     * Register CRUD routes
     *
     * @param string $name Resource name
     * @param string $controller Controller class
     */
    public static function crud(string $name, string $controller): void
    {
        RouteMacros::crud($name, $controller);
    }

    /**
     * Register authentication routes
     */
    public static function auth(): void
    {
        RouteMacros::auth();
    }

    /**
     * Register admin panel routes
     *
     * @param array<string> $allowedIps
     */
    public static function adminPanel(array $allowedIps = []): void
    {
        RouteMacros::adminPanel($allowedIps);
    }

    /**
     * Register API version routes
     */
    public static function apiVersion(string $version, callable $callback): void
    {
        RouteMacros::apiVersion($version, $callback);
    }

    /**
     * Register webhook routes
     *
     * @param array<string> $allowedIps
     */
    public static function webhooks(array $allowedIps = []): void
    {
        RouteMacros::webhooks($allowedIps);
    }
}
