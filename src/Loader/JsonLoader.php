<?php

declare(strict_types=1);

namespace CloudCastle\Http\Router\Loader;

use CloudCastle\Http\Router\Router;
use InvalidArgumentException;
use RuntimeException;

/**
 * JSON Route Loader.
 *
 * Loads routes from JSON configuration files.
 *
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 * @SuppressWarnings(PHPMD.NPathComplexity)
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 * @SuppressWarnings(PHPMD.TooManyMethods)
 */
class JsonLoader
{
    public function __construct(private readonly Router $router)
    {
    }

    /**
     * Load routes from JSON file.
     */
    public function load(string $filePath): void
    {
        if (!file_exists($filePath)) {
            throw new InvalidArgumentException('JSON file not found: ' . $filePath);
        }

        $content = file_get_contents($filePath);
        if ($content === false) {
            throw new RuntimeException('Failed to read JSON file: ' . $filePath);
        }

        $data = json_decode($content, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new RuntimeException('Invalid JSON: ' . json_last_error_msg());
        }

        if (!is_array($data)) {
            throw new InvalidArgumentException('JSON must contain an object or array');
        }

        $this->processRoutes($data);
    }

    /**
     * Process routes from JSON data.
     *
     * @param array<string, mixed> $data
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    private function processRoutes(array $data): void
    {
        // Process single routes
        if (isset($data['routes']) && is_array($data['routes'])) {
            foreach ($data['routes'] as $routeConfig) {
                $this->addRoute($routeConfig);
            }
        }

        // Process route groups
        if (isset($data['groups']) && is_array($data['groups'])) {
            foreach ($data['groups'] as $groupConfig) {
                $this->addGroup($groupConfig);
            }
        }
    }

    /**
     * Add a single route.
     *
     * @param array<string, mixed> $config
     */
    private function addRoute(array $config): void
    {
        $route = $this->createRouteFromConfig($this->router, $config);
        $this->configureRoute($route, $config);
    }

    /**
     * Create route from config.
     *
     * @param array<string, mixed> $config
     */
    private function createRouteFromConfig(Router $router, array $config): \CloudCastle\Http\Router\Route
    {
        $method = is_string($config['method'] ?? null) ? strtoupper($config['method']) : 'GET';
        $uri = is_string($config['uri'] ?? null) ? $config['uri'] : (is_string($config['path'] ?? null) ? $config['path'] : '/');
        $action = $config['action'] ?? $config['handler'] ?? fn (): string => 'OK';

        return match ($method) {
            'GET' => $router->get($uri, $action),
            'POST' => $router->post($uri, $action),
            'PUT' => $router->put($uri, $action),
            'DELETE' => $router->delete($uri, $action),
            'PATCH' => $router->patch($uri, $action),
            'VIEW' => $router->view($uri, $action),
            default => $router->any($uri, $action),
        };
    }

    /**
     * Configure route with settings.
     *
     * @param array<string, mixed> $config
     */
    private function configureRoute(\CloudCastle\Http\Router\Route $route, array $config): void
    {
        $this->setRouteName($route, $config);
        $this->setRouteMiddleware($route, $config);
        $this->setRouteDefaults($route, $config);
        $this->setRouteCondition($route, $config);
        $this->setRouteRequirements($route, $config);
        $this->setRouteTags($route, $config);
        $this->setRouteThrottle($route, $config);
        $this->setRouteIpFilters($route, $config);
    }

    /**
     * Set route name.
     *
     * @param array<string, mixed> $config
     */
    private function setRouteName(\CloudCastle\Http\Router\Route $route, array $config): void
    {
        if (isset($config['name']) && is_string($config['name'])) {
            $route->name($config['name']);
        }
    }

    /**
     * Set route middleware.
     *
     * @param array<string, mixed> $config
     */
    private function setRouteMiddleware(\CloudCastle\Http\Router\Route $route, array $config): void
    {
        if (isset($config['middleware'])) {
            $middlewares = is_array($config['middleware']) ? $config['middleware'] : [$config['middleware']];
            foreach ($middlewares as $middleware) {
                $route->middleware($middleware);
            }
        }
    }

    /**
     * Set route defaults.
     *
     * @param array<string, mixed> $config
     */
    private function setRouteDefaults(\CloudCastle\Http\Router\Route $route, array $config): void
    {
        if (isset($config['defaults']) && is_array($config['defaults'])) {
            $route->defaults($config['defaults']);
        }
    }

    /**
     * Set route condition.
     *
     * @param array<string, mixed> $config
     */
    private function setRouteCondition(\CloudCastle\Http\Router\Route $route, array $config): void
    {
        if (isset($config['condition']) && is_string($config['condition'])) {
            $route->condition($config['condition']);
        }
    }

    /**
     * Set route requirements.
     *
     * @param array<string, mixed> $config
     */
    private function setRouteRequirements(\CloudCastle\Http\Router\Route $route, array $config): void
    {
        if (isset($config['requirements']) && is_array($config['requirements'])) {
            foreach ($config['requirements'] as $param => $pattern) {
                if (is_string($param) && is_string($pattern)) {
                    $route->where($param, $pattern);
                }
            }
        }
    }

    /**
     * Set route tags.
     *
     * @param array<string, mixed> $config
     */
    private function setRouteTags(\CloudCastle\Http\Router\Route $route, array $config): void
    {
        if (isset($config['tags'])) {
            $tags = is_array($config['tags']) ? $config['tags'] : [$config['tags']];
            foreach ($tags as $tag) {
                $route->tag((string) $tag);
            }
        }
    }

    /**
     * Set route throttle.
     *
     * @param array<string, mixed> $config
     */
    private function setRouteThrottle(\CloudCastle\Http\Router\Route $route, array $config): void
    {
        if (isset($config['throttle']) && is_array($config['throttle'])) {
            $limit = $config['throttle']['limit'] ?? 60;
            $perMinutes = $config['throttle']['per_minutes'] ?? 1;
            $route->throttle((int) $limit, (int) $perMinutes);
        }
    }

    /**
     * Set route IP filters.
     *
     * @param array<string, mixed> $config
     */
    private function setRouteIpFilters(\CloudCastle\Http\Router\Route $route, array $config): void
    {
        if (isset($config['whitelist']) && is_array($config['whitelist'])) {
            $route->whitelistIp($config['whitelist']);
        }

        if (isset($config['blacklist']) && is_array($config['blacklist'])) {
            $route->blacklistIp($config['blacklist']);
        }
    }

    /**
     * Add a route group.
     *
     * @param array<string, mixed> $config
     */
    private function addGroup(array $config): void
    {
        $attributes = $this->getGroupAttributes($config);

        $group = $this->router->group($attributes, function (Router $router) use ($config): void {
            $this->addGroupRoutes($router, $config);
            $this->addGroupNestedGroups($router, $config);
        });

        // Apply group-level configurations
        $this->configureGroup($group, $config);
    }

    /**
     * Configure group with settings.
     *
     * @param array<string, mixed> $config
     */
    private function configureGroup(\CloudCastle\Http\Router\RouteGroup $group, array $config): void
    {
        // Add middleware
        if (isset($config['middleware'])) {
            $middlewares = is_array($config['middleware']) ? $config['middleware'] : [$config['middleware']];
            $group->middleware($middlewares);
        }

        // Set domain
        if (isset($config['domain']) && is_string($config['domain'])) {
            $group->domain($config['domain']);
        }

        // Set port
        if (isset($config['port']) && is_int($config['port'])) {
            $group->port($config['port']);
        }

        // Set protocol
        if (isset($config['protocol'])) {
            $protocol = is_array($config['protocol']) ? $config['protocol'] : [$config['protocol']];
            $group->protocol($protocol);
        }

        // Set name
        if (isset($config['name']) && is_string($config['name'])) {
            $group->name($config['name']);
        }

        // Set tags
        if (isset($config['tags'])) {
            $tags = is_array($config['tags']) ? $config['tags'] : [$config['tags']];
            $group->tag($tags);
        }

        // Set throttle
        if (isset($config['throttle']) && is_array($config['throttle'])) {
            $limit = $config['throttle']['limit'] ?? 60;
            $perMinutes = $config['throttle']['per_minutes'] ?? 1;
            $group->throttle((int) $limit, (int) $perMinutes);
        }

        // Set whitelist
        if (isset($config['whitelist']) && is_array($config['whitelist'])) {
            $group->whitelistIp($config['whitelist']);
        }

        // Set blacklist
        if (isset($config['blacklist']) && is_array($config['blacklist'])) {
            $group->blacklistIp($config['blacklist']);
        }
    }

    /**
     * Get group attributes.
     *
     * @param array<string, mixed> $config
     * @return array<string, mixed>
     */
    private function getGroupAttributes(array $config): array
    {
        $attributes = [];

        if (isset($config['prefix'])) {
            $attributes['prefix'] = $config['prefix'];
        }

        return $attributes;
    }

    /**
     * Add routes to group.
     *
     * @param array<string, mixed> $config
     */
    private function addGroupRoutes(Router $router, array $config): void
    {
        if (isset($config['routes']) && is_array($config['routes'])) {
            foreach ($config['routes'] as $routeConfig) {
                $this->addRouteToRouter($router, $routeConfig);
            }
        }
    }

    /**
     * Add nested groups to group.
     *
     * @param array<string, mixed> $config
     */
    private function addGroupNestedGroups(Router $router, array $config): void
    {
        if (isset($config['groups']) && is_array($config['groups'])) {
            foreach ($config['groups'] as $nestedConfig) {
                $this->addNestedGroupToRouter($router, $nestedConfig);
            }
        }
    }

    /**
     * Add route to router (within a group).
     *
     * @param array<string, mixed> $config
     */
    private function addRouteToRouter(Router $router, array $config): void
    {
        $route = $this->createRouteFromConfig($router, $config);
        $this->configureRoute($route, $config);
    }

    /**
     * Add nested group to router.
     *
     * @param array<string, mixed> $config
     */
    private function addNestedGroupToRouter(Router $router, array $config): void
    {
        $attributes = $this->getGroupAttributes($config);

        $group = $router->group($attributes, function (Router $nestedRouter) use ($config): void {
            $this->addGroupRoutes($nestedRouter, $config);
        });

        // Apply group-level configurations
        $this->configureGroup($group, $config);
    }
}
