<?php

declare(strict_types=1);

namespace CloudCastle\Http\Router;

use CloudCastle\Http\Router\Contracts\RouteInterface;
use CloudCastle\Http\Router\Exceptions\RouteNotFoundException;

/**
 * URL Matcher for finding routes by URL and method.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
class UrlMatcher
{
    public function __construct(private readonly Router $router)
    {
    }

    /**
     * Match URL to a route.
     *
     *
     * @throws RouteNotFoundException
     *
     * @return array{route: RouteInterface, parameters: array<string, string>}
     */
    public function match(string $url, string $method = 'GET'): array
    {
        $url = trim($url, '/');
        $method = strtoupper($method);

        foreach ($this->router->getAllRoutes() as $route) {
            if (!in_array($method, $route->getMethods(), true)) {
                continue;
            }

            $pattern = $this->compilePattern($route->getUri());

            if (preg_match($pattern, $url, $matches) === 1) {
                $parameters = array_filter(
                    $matches,
                    fn ($key): bool => !is_int($key),
                    ARRAY_FILTER_USE_KEY
                );

                return [
                    'route' => $route,
                    'parameters' => $parameters,
                ];
            }
        }

        throw new RouteNotFoundException('No route matches URL: /' . $url);
    }

    /**
     * Compile route pattern to regex.
     */
    private function compilePattern(string $uri): string
    {
        // Normalize URI (remove leading/trailing slashes)
        $uri = trim($uri, '/');

        $pattern = preg_replace_callback(
            '/\{([a-zA-Z_]\w*)\}/',
            fn ($matches): string => '(?P<' . $matches[1] . '>[^/]+)',
            $uri
        );

        return '#^' . $pattern . '$#';
    }

    /**
     * Check if URL matches any route.
     */
    public function matches(string $url, string $method = 'GET'): bool
    {
        try {
            $this->match($url, $method);

            return true;
        } catch (RouteNotFoundException) {
            return false;
        }
    }
}
