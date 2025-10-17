<?php

declare(strict_types=1);

namespace CloudCastle\Http\Router\Contracts;

/**
 * Interface for route middleware.
 */
interface MiddlewareInterface
{
    /**
     * Handle the request and optionally pass it to the next middleware.
     */
    public function handle(mixed $request, callable $next): mixed;
}
