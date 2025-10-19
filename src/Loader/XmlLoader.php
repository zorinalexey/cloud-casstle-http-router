<?php

declare(strict_types=1);

namespace CloudCastle\Http\Router\Loader;

use CloudCastle\Http\Router\Router;
use RuntimeException;
use SimpleXMLElement;

/**
 * Load routes from XML files.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
class XmlLoader
{
    public function __construct(private readonly Router $router)
    {
    }

    /**
     * Load routes from XML file.
     *
     * @throws RuntimeException
     */
    public function load(string $filePath): void
    {
        if (!file_exists($filePath)) {
            throw new RuntimeException('XML file not found: ' . $filePath);
        }

        $xml = simplexml_load_file($filePath);

        if ($xml === false) {
            throw new RuntimeException('Failed to parse XML file: ' . $filePath);
        }

        $this->processRoutes($xml);
    }

    /**
     * Process routes from XML.
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    private function processRoutes(SimpleXMLElement $xml): void
    {
        foreach ($xml->route as $routeXml) {
            $path = (string) ($routeXml['path'] ?? throw new RuntimeException('Route missing path attribute'));
            $name = (string) ($routeXml['name'] ?? '');
            $methods = (string) ($routeXml['methods'] ?? 'GET');
            $controller = (string) ($routeXml['controller'] ?? '');

            $methodsArray = array_map('trim', explode(',', $methods));

            $action = $controller !== '' ? $controller : fn (): string => $name;

            $route = $this->router->match($methodsArray, $path, $action);

            if ($name !== '') {
                $route->name($name);
            }

            // Middleware
            if (isset($routeXml->middleware)) {
                $middlewareString = (string) $routeXml->middleware;
                /** @var array<string> $middleware */
                $middleware = array_map('trim', explode(',', $middlewareString));
                $route->middleware($middleware);
            }

            // Domain
            if (isset($routeXml['domain'])) {
                $route->domain((string) $routeXml['domain']);
            }

            // Requirements
            if (isset($routeXml->requirements)) {
                foreach ($routeXml->requirements->requirement as $req) {
                    $param = (string) $req['param'];
                    $pattern = (string) $req['pattern'];
                    $route->where($param, $pattern);
                }
            }

            // Defaults
            if (isset($routeXml->defaults)) {
                foreach ($routeXml->defaults->default as $def) {
                    $param = (string) $def['param'];
                    $value = (string) $def['value'];
                    $route->default($param, $value);
                }
            }
        }
    }
}
