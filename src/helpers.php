<?php

declare(strict_types=1);

use CloudCastle\Http\Router\Facade\Route;
use CloudCastle\Http\Router\Route as RouteClass;

if (!function_exists('route')) {
    /**
     * Get route by name or dispatch current request
     *
     * @param array<string, mixed> $parameters
     */
    function route(?string $name = null, array $parameters = []): ?RouteClass
    {
        // TODO: Implement parameter substitution for dynamic routes
        unset($parameters); // Reserved for future use

        if ($name === null) {
            return Route::current();
        }

        return Route::getRouteByName($name);
    }
}

if (!function_exists('current_route')) {
    /**
     * Get current route
     */
    function current_route(): ?RouteClass
    {
        return Route::current();
    }
}

if (!function_exists('previous_route')) {
    /**
     * Get previous route
     */
    function previous_route(): ?RouteClass
    {
        return Route::previous();
    }
}

if (!function_exists('route_is')) {
    /**
     * Check if current route matches name
     */
    function route_is(string $name): bool
    {
        return Route::currentRouteNamed($name);
    }
}

if (!function_exists('route_name')) {
    /**
     * Get current route name
     */
    function route_name(): ?string
    {
        return Route::currentRouteName();
    }
}

if (!function_exists('router')) {
    /**
     * Get router instance
     */
    function router(): \CloudCastle\Http\Router\Router
    {
        return Route::router();
    }
}

if (!function_exists('dispatch_route')) {
    /**
     * Dispatch current HTTP request
     */
    function dispatch_route(): RouteClass
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $domain = $_SERVER['HTTP_HOST'] ?? null;
        $clientIp = $_SERVER['REMOTE_ADDR'] ?? null;

        // Remove query string
        $uri = strtok($uri, '?') ?: '/';

        return Route::dispatch($uri, $method, $domain, $clientIp);
    }
}

if (!function_exists('route_url')) {
    /**
     * Generate URL for named route
     *
     * @param array<string, mixed> $parameters
     */
    function route_url(string $name, array $parameters = []): string
    {
        $route = Route::getRouteByName($name);

        if ($route === null) {
            return '';
        }

        $uri = $route->getUri();

        // Replace parameters
        foreach ($parameters as $key => $value) {
            $uri = preg_replace('/\{' . $key . '(?::[^}]+)?\}/', (string)$value, (string) $uri);
        }

        return $uri;
    }
}

if (!function_exists('route_has')) {
    /**
     * Check if route exists
     */
    function route_has(string $name): bool
    {
        return Route::router()->hasRoute($name);
    }
}

if (!function_exists('route_stats')) {
    /**
     * Get route statistics
     *
     * @return array<string, mixed>
     */
    function route_stats(): array
    {
        return Route::getRouteStats();
    }
}

if (!function_exists('routes_by_tag')) {
    /**
     * Get routes by tag
     *
     * @return array<RouteClass>
     */
    function routes_by_tag(string $tag): array
    {
        return Route::getRoutesByTag($tag);
    }
}

if (!function_exists('route_back')) {
    /**
     * Get URL to go back to previous route
     */
    function route_back(string $default = '/'): string
    {
        return Route::router()->previousRouteUri() ?? $default;
    }
}
