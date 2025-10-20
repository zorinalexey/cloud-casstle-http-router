<?php

declare(strict_types=1);

namespace CloudCastle\Http\Router\Middleware;

use CloudCastle\Http\Router\Contracts\MiddlewareInterface;

/**
 * CORS (Cross-Origin Resource Sharing) middleware.
 *
 * @SuppressWarnings(PHPMD.Superglobals)
 * @SuppressWarnings(PHPMD.ExitExpression)
 * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
 */
class CorsMiddleware implements MiddlewareInterface
{
    /**
     * @param array<string> $allowedOrigins
     * @param array<string> $allowedMethods
     * @param array<string> $allowedHeaders
     */
    public function __construct(
        private readonly array $allowedOrigins = ['*'],
        private readonly array $allowedMethods = [
            'GET', 'POST', 'PUT', 'DELETE', 'OPTIONS', 'PATCH', 'HEAD', 'VIEW', 'CUSTOM',
        ],
        private readonly array $allowedHeaders = [
            'Content-Type', 'Authorization', 'X-Requested-With',
            'X-CSRF-TOKEN', 'X-XSRF-TOKEN', 'X-XSRF-HEADER',
            'X-XSRF-HEADER-NAME', 'X-XSRF-HEADER-VALUE',
            'X-XSRF-HEADER-NAME-VALUE',
        ],
        private readonly int $maxAge = 86400,
        private readonly bool $allowCredentials = false
    ) {
    }

    /**
     * @param array<string, mixed> $parameters
     */
    public function handle(mixed $uri, callable $next, array $parameters = []): mixed
    {
        // Get origin from request
        $origin = $_SERVER['HTTP_ORIGIN'] ?? '';

        // Check if origin is allowed
        if ($this->isOriginAllowed($origin)) {
            header('Access-Control-Allow-Origin: ' . $origin);
        } elseif (in_array('*', $this->allowedOrigins, true)) {
            header('Access-Control-Allow-Origin: *');
        }

        // Allow credentials if enabled
        if ($this->allowCredentials) {
            header('Access-Control-Allow-Credentials: true');
        }

        // Handle preflight request
        if (($_SERVER['REQUEST_METHOD'] ?? '') === 'OPTIONS') {
            header('Access-Control-Allow-Methods: ' . implode(', ', $this->allowedMethods));
            header('Access-Control-Allow-Headers: ' . implode(', ', $this->allowedHeaders));
            header('Access-Control-Max-Age: ' . $this->maxAge);
            http_response_code(200);

            return ''; // Return empty response for OPTIONS request
        }

        // Expose headers for actual request
        header('Access-Control-Allow-Methods: ' . implode(', ', $this->allowedMethods));
        header('Access-Control-Allow-Headers: ' . implode(', ', $this->allowedHeaders));

        return $next($uri);
    }

    /**
     * Check if origin is allowed.
     */
    private function isOriginAllowed(string $origin): bool
    {
        if ($origin === '') {
            return false;
        }

        return in_array($origin, $this->allowedOrigins, true);
    }
}
