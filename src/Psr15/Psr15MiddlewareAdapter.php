<?php

declare(strict_types=1);

namespace CloudCastle\Http\Router\Psr15;

use Closure;
use CloudCastle\Http\Router\Contracts\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface as PsrMiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Adapter for PSR-15 middleware to work with our router.
 *
 * @SuppressWarnings(PHPMD.LongVariable)
 * @SuppressWarnings(PHPMD.StaticAccess)
 *
 * @phpcs:disable PSR1.Classes.ClassDeclaration.MultipleClasses
 */
class Psr15MiddlewareAdapter implements MiddlewareInterface
{
    public function __construct(
        private readonly PsrMiddlewareInterface $psrMiddleware,
        private readonly ServerRequestInterface $request,
        private readonly ResponseInterface $response
    ) {
    }

    /**
     * @param array<string, mixed> $parameters
     */
    public function handle(mixed $uri, callable $next, array $parameters = []): mixed
    {
        $nextClosure = $next instanceof Closure ? $next : Closure::fromCallable($next);
        $handler = new class ($nextClosure, $this->response) implements RequestHandlerInterface {
            public function __construct(private readonly Closure $next, private readonly ResponseInterface $response)
            {
            }

            public function handle(ServerRequestInterface $request): ResponseInterface
            {
                ($this->next)($request->getUri()->getPath());

                return $this->response;
            }
        };

        return $this->psrMiddleware->process($this->request, $handler);
    }
}
