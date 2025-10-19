<?php

declare(strict_types=1);

namespace CloudCastle\Http\Router;

use RuntimeException;

/**
 * URL Generator for creating URLs from route names and parameters.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
class UrlGenerator
{
    public function __construct(private readonly Router $router, private string $baseUrl = '')
    {
    }

    /**
     * Set base URL for generated URLs.
     */
    public function setBaseUrl(string $baseUrl): self
    {
        $this->baseUrl = rtrim($baseUrl, '/');

        return $this;
    }

    /**
     * Generate URL for named route.
     *
     * @param array<string, string|int> $parameters
     * @param array<string, string|int> $queryParams
     *
     * @throws RuntimeException
     */
    public function generate(string $routeName, array $parameters = [], array $queryParams = []): string
    {
        $route = $this->router->getRoute($routeName);

        if (!$route instanceof \CloudCastle\Http\Router\Route) {
            throw new RuntimeException('Route not found: ' . $routeName);
        }

        $uri = $route->getUri();

        // Replace parameters
        $url = preg_replace_callback('/\{([a-zA-Z_]\w*)\}/', function ($matches) use ($parameters): string {
            $param = $matches[1];

            if (!isset($parameters[$param])) {
                throw new RuntimeException('Missing parameter: ' . $param);
            }

            return (string) $parameters[$param];
        }, $uri);

        // Add base URL
        $urlTrimmed = $url !== null ? ltrim($url, '/') : '';
        $fullUrl = $this->baseUrl !== '' ? $this->baseUrl . '/' . $urlTrimmed : '/' . $urlTrimmed;

        // Add query parameters
        if ($queryParams !== []) {
            $fullUrl .= '?' . http_build_query($queryParams);
        }

        return $fullUrl;
    }

    /**
     * Generate absolute URL.
     *
     * @param array<string, string|int> $parameters
     * @param array<string, string|int> $queryParams
     */
    public function absolute(string $routeName, array $parameters = [], array $queryParams = []): string
    {
        if ($this->baseUrl === '') {
            $this->setBaseUrl($this->detectBaseUrl());
        }

        return $this->generate($routeName, $parameters, $queryParams);
    }

    /**
     * Detect base URL from server variables.
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    private function detectBaseUrl(): string
    {
        $https = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
        $protocol = $https ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';

        return sprintf('%s://%s', $protocol, $host);
    }
}
