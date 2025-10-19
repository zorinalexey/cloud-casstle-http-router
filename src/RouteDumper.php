<?php

declare(strict_types=1);

namespace CloudCastle\Http\Router;

/**
 * Route dumper for debugging and documentation.
 */
class RouteDumper
{
    public function __construct(private readonly Router $router)
    {
    }

    /**
     * Dump all routes as array.
     *
     * @return array<int, array<string, mixed>>
     */
    public function dump(): array
    {
        $routes = [];

        foreach ($this->router->getAllRoutes() as $route) {
            $routes[] = [
                'methods' => $route->getMethods(),
                'uri' => $route->getUri(),
                'name' => $route->getName(),
                'action' => $this->formatAction($route->getAction()),
                'middleware' => $route->getMiddleware(),
                'domain' => $route->getDomain(),
                'port' => $route->getPort(),
                'protocols' => $route->getProtocols(),
                'defaults' => $route->getDefaults(),
                'condition' => $route->getCondition(),
            ];
        }

        return $routes;
    }

    /**
     * Dump routes as JSON.
     */
    public function dumpJson(): string
    {
        $json = json_encode($this->dump(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

        return $json !== false ? $json : '[]';
    }

    /**
     * Dump routes as table (CLI output).
     */
    public function dumpTable(): string
    {
        $output = '';
        $output .= str_repeat('-', 120) . "\n";
        $output .= sprintf("| %-10s | %-30s | %-20s | %-40s |\n", 'METHOD', 'URI', 'NAME', 'ACTION');
        $output .= str_repeat('-', 120) . "\n";

        foreach ($this->router->getAllRoutes() as $route) {
            $methods = implode(',', $route->getMethods());
            $uri = $route->getUri();
            $name = $route->getName() ?? '-';
            $action = $this->formatAction($route->getAction());

            $output .= sprintf("| %-10s | %-30s | %-20s | %-40s |\n", $methods, $uri, $name, $action);
        }

        return $output . (str_repeat('-', 120) . "\n");
    }

    /**
     * Format action for display.
     */
    private function formatAction(mixed $action): string
    {
        if (is_string($action)) {
            return $action;
        }

        if (is_array($action) && count($action) === 2) {
            [$class, $method] = $action;
            $className = is_string($class) ? $class : $class::class;

            return sprintf('%s@%s', $className, $method);
        }

        if ($action instanceof \Closure) {
            return 'Closure';
        }

        if (is_callable($action)) {
            return 'Callable';
        }

        return 'Unknown';
    }
}
