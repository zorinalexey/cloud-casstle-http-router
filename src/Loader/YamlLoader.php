<?php

declare(strict_types=1);

namespace CloudCastle\Http\Router\Loader;

use CloudCastle\Http\Router\Router;
use RuntimeException;

/**
 * Load routes from YAML files.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
class YamlLoader
{
    public function __construct(private readonly Router $router)
    {
    }

    /**
     * Load routes from YAML file.
     *
     * @throws RuntimeException
     */
    public function load(string $filePath): void
    {
        if (!file_exists($filePath)) {
            throw new RuntimeException('YAML file not found: ' . $filePath);
        }

        if (!function_exists('yaml_parse_file')) {
            throw new RuntimeException('YAML extension not installed. Install php-yaml extension.');
        }

        $data = yaml_parse_file($filePath);

        if ($data === false || !is_array($data)) {
            throw new RuntimeException('Failed to parse YAML file: ' . $filePath);
        }

        $this->processRoutes($data);
    }

    /**
     * Process routes from parsed YAML data.
     *
     * @param array<string, mixed> $data
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    private function processRoutes(array $data): void
    {
        foreach ($data as $name => $config) {
            if (!is_array($config)) {
                continue;
            }

            if (!isset($config['path']) || !is_string($config['path'])) {
                throw new RuntimeException(sprintf("Route %s missing 'path'", $name));
            }

            $path = $config['path'];
            $methods = $config['methods'] ?? ['GET'];
            $action = $config['controller'] ?? fn (): int|string => $name;

            if (is_string($methods)) {
                $methods = [$methods];
            }

            if (!is_array($methods)) {
                $methods = ['GET'];
            }

            $routeName = is_string($name) ? $name : (string) $name;
            $route = $this->router->match($methods, $path, $action)->name($routeName);

            // Apply optional config
            if (isset($config['middleware'])) {
                $middleware = $config['middleware'];
                if (is_string($middleware)) {
                    $middleware = [$middleware];
                }

                if (is_array($middleware)) {
                    $route->middleware($middleware);
                }
            }

            if (isset($config['domain']) && is_string($config['domain'])) {
                $route->domain($config['domain']);
            }

            if (isset($config['throttle']) && is_array($config['throttle'])) {
                $max = $config['throttle']['max'] ?? 60;
                $decay = $config['throttle']['decay'] ?? 60;
                if (is_int($max) && is_int($decay)) {
                    $route->throttle($max, $decay);
                }
            }

            if (isset($config['requirements']) && is_array($config['requirements'])) {
                foreach ($config['requirements'] as $param => $regex) {
                    if (is_string($param) && is_string($regex)) {
                        $route->where($param, $regex);
                    }
                }
            }

            if (isset($config['defaults']) && is_array($config['defaults'])) {
                foreach ($config['defaults'] as $param => $value) {
                    if (is_string($param)) {
                        $route->default($param, $value);
                    }
                }
            }
        }
    }
}
