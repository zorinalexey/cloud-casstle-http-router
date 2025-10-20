<?php

declare(strict_types=1);

namespace CloudCastle\Http\Router\Middleware;

use CloudCastle\Http\Router\Contracts\MiddlewareInterface;
use CloudCastle\Http\Router\Exceptions\InsecureConnectionException;

/**
 * Middleware to enforce HTTPS connections.
 *
 * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
 */
class HttpsEnforcement implements MiddlewareInterface
{
    /**
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     */
    public function __construct(private readonly bool $redirectToHttps = false)
    {
    }

    public function handle(mixed $request, callable $next): mixed
    {
        $protocol = $this->getProtocol();

        if ($protocol !== 'https') {
            if ($this->redirectToHttps) {
                $this->redirectToHttps();
            }

            throw new InsecureConnectionException('HTTPS connection required');
        }

        return $next($request);
    }

    /**
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    private function getProtocol(): string
    {
        if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
            return 'https';
        }

        if (isset($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
            return strtolower((string) $_SERVER['HTTP_X_FORWARDED_PROTO']);
        }

        if (isset($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] === 'on') {
            return 'https';
        }

        return 'http';
    }

    /**
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    private function redirectToHttps(): void
    {
        $host = $_SERVER['HTTP_HOST'] ?? '';
        $uri = $_SERVER['REQUEST_URI'] ?? '/';

        header(sprintf('Location: https://%s%s', $host, $uri), true, 301);
        // Redirect header set, exception will be thrown to stop execution
    }
}
