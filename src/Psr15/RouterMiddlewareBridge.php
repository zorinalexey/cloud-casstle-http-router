<?php

declare(strict_types=1);

namespace CloudCastle\Http\Router\Psr15;

use CloudCastle\Http\Router\Contracts\MiddlewareInterface;
use Psr\Http\Server\MiddlewareInterface as PsrMiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Bridge to use our middleware as PSR-15 middleware.
 */
class RouterMiddlewareBridge implements PsrMiddlewareInterface
{
    public function __construct(private readonly MiddlewareInterface $middleware)
    {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $uri = $request->getUri()->getPath();

        $this->middleware->handle($uri, fn (): ResponseInterface => $handler->handle($request));

        return $handler->handle($request);
    }
}
