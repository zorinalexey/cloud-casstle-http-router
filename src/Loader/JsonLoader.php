<?php

declare(strict_types=1);

namespace CloudCastle\Http\Router\Loader;

use CloudCastle\Http\Router\Router;
use CloudCastle\Http\Router\RouteGroup;
use InvalidArgumentException;
use RuntimeException;

/**
 * JSON Route Loader
 *
 * Loads routes from JSON configuration files.
 *
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 * @SuppressWarnings(PHPMD.NPathComplexity)
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
            throw new InvalidArgumentException("JSON file not found: {$filePath}");
        }

        $content = file_get_contents($filePath);
        if ($content === false) {
            throw new RuntimeException("Failed to read JSON file: {$filePath}");
        }

        $data = json_decode($content, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new RuntimeException("Invalid JSON: " . json_last_error_msg());
        }

        if (!is_array($data)) {
            throw new InvalidArgumentException("JSON must contain an object or array");
        }

        $this->processRoutes($data);
    }

    /**
     * Process routes from JSON data.
     *
     * @param array<string, mixed> $data
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
        $method = strtoupper($config['method'] ?? 'GET');
        $uri = $config['uri'] ?? $config['path'] ?? '/';
        $action = $config['action'] ?? $config['handler'] ?? function () {
            return 'OK';
        };

        // Call appropriate method based on HTTP method
        $route = match ($method) {
            'GET' => $this->router->get($uri, $action),
            'POST' => $this->router->post($uri, $action),
            'PUT' => $this->router->put($uri, $action),
            'DELETE' => $this->router->delete($uri, $action),
            'PATCH' => $this->router->patch($uri, $action),
            'OPTIONS' => $this->router->options($uri, $action),
            'HEAD' => $this->router->head($uri, $action),
            'VIEW' => $this->router->view($uri, $action),
            default => $this->router->any($uri, $action),
        };

        // Set route name
        if (isset($config['name'])) {
            $route->name((string) $config['name']);
        }

        // Add middleware
        if (isset($config['middleware'])) {
            $middlewares = is_array($config['middleware']) ? $config['middleware'] : [$config['middleware']];
            foreach ($middlewares as $middleware) {
                $route->middleware($middleware);
            }
        }

        // Set defaults
        if (isset($config['defaults']) && is_array($config['defaults'])) {
            $route->defaults($config['defaults']);
        }

        // Set condition
        if (isset($config['condition'])) {
            $route->condition((string) $config['condition']);
        }

        // Set requirements (where)
        if (isset($config['requirements']) && is_array($config['requirements'])) {
            foreach ($config['requirements'] as $param => $pattern) {
                $route->where((string) $param, (string) $pattern);
            }
        }

        // Set domain
        if (isset($config['domain'])) {
            $route->domain((string) $config['domain']);
        }

        // Set port
        if (isset($config['port'])) {
            $route->port((int) $config['port']);
        }

        // Set protocol
        if (isset($config['protocol'])) {
            $route->protocol((string) $config['protocol']);
        }

        // Add tags
        if (isset($config['tags'])) {
            $tags = is_array($config['tags']) ? $config['tags'] : [$config['tags']];
            foreach ($tags as $tag) {
                $route->tag((string) $tag);
            }
        }

        // Set rate limiting
        if (isset($config['throttle'])) {
            if (is_array($config['throttle'])) {
                $limit = $config['throttle']['limit'] ?? 60;
                $perMinutes = $config['throttle']['per_minutes'] ?? 1;
                $route->throttle((int) $limit, (int) $perMinutes);
            }
        }

        // Set IP whitelist
        if (isset($config['whitelist']) && is_array($config['whitelist'])) {
            $route->whitelistIp($config['whitelist']);
        }

        // Set IP blacklist
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
        $attributes = [];

        if (isset($config['prefix'])) {
            $attributes['prefix'] = $config['prefix'];
        }

        $this->router->group($attributes, function (Router $router) use ($config) {
            // Add group middleware
            if (isset($config['middleware'])) {
                $middlewares = is_array($config['middleware']) ? $config['middleware'] : [$config['middleware']];
                foreach ($middlewares as $middleware) {
                    $router->middleware($middleware);
                }
            }

            // Set group domain
            if (isset($config['domain'])) {
                $router->domain((string) $config['domain']);
            }

            // Set group port
            if (isset($config['port'])) {
                $router->port((int) $config['port']);
            }

            // Set group protocol
            if (isset($config['protocol'])) {
                $router->protocol((string) $config['protocol']);
            }

            // Add routes to group
            if (isset($config['routes']) && is_array($config['routes'])) {
                foreach ($config['routes'] as $routeConfig) {
                    $this->addRouteToRouter($router, $routeConfig);
                }
            }

            // Add nested groups
            if (isset($config['groups']) && is_array($config['groups'])) {
                foreach ($config['groups'] as $nestedConfig) {
                    $this->addNestedGroupToRouter($router, $nestedConfig);
                }
            }
        });
    }

    /**
     * Add route to router (within a group).
     *
     * @param array<string, mixed> $config
     */
    private function addRouteToRouter(Router $router, array $config): void
    {
        $method = strtoupper($config['method'] ?? 'GET');
        $uri = $config['uri'] ?? $config['path'] ?? '/';
        $action = $config['action'] ?? $config['handler'] ?? function () {
            return 'OK';
        };

        // Call appropriate method based on HTTP method
        $route = match ($method) {
            'GET' => $router->get($uri, $action),
            'POST' => $router->post($uri, $action),
            'PUT' => $router->put($uri, $action),
            'DELETE' => $router->delete($uri, $action),
            'PATCH' => $router->patch($uri, $action),
            'OPTIONS' => $router->options($uri, $action),
            'HEAD' => $router->head($uri, $action),
            default => $router->any($uri, $action),
        };

        // Apply route configuration (same as addRoute)
        if (isset($config['name'])) {
            $route->name((string) $config['name']);
        }

        if (isset($config['middleware'])) {
            $middlewares = is_array($config['middleware']) ? $config['middleware'] : [$config['middleware']];
            foreach ($middlewares as $middleware) {
                $route->middleware($middleware);
            }
        }

        if (isset($config['defaults']) && is_array($config['defaults'])) {
            $route->defaults($config['defaults']);
        }

        if (isset($config['condition'])) {
            $route->condition((string) $config['condition']);
        }

        if (isset($config['requirements']) && is_array($config['requirements'])) {
            foreach ($config['requirements'] as $param => $pattern) {
                $route->where((string) $param, (string) $pattern);
            }
        }
    }

    /**
     * Add nested group to router.
     *
     * @param array<string, mixed> $config
     */
    private function addNestedGroupToRouter(Router $router, array $config): void
    {
        $attributes = [];

        if (isset($config['prefix'])) {
            $attributes['prefix'] = $config['prefix'];
        }

        $router->group($attributes, function (Router $nestedRouter) use ($config) {
            if (isset($config['middleware'])) {
                $middlewares = is_array($config['middleware']) ? $config['middleware'] : [$config['middleware']];
                foreach ($middlewares as $middleware) {
                    $nestedRouter->middleware($middleware);
                }
            }

            if (isset($config['routes']) && is_array($config['routes'])) {
                foreach ($config['routes'] as $routeConfig) {
                    $this->addRouteToRouter($nestedRouter, $routeConfig);
                }
            }
        });
    }
}
