<?php

declare(strict_types=1);

namespace CloudCastle\Http\Router\Tests\Unit;

use CloudCastle\Http\Router\Contracts\MiddlewareInterface;
use CloudCastle\Http\Router\MiddlewareDispatcher;
use PHPUnit\Framework\TestCase;

class TestMiddleware implements MiddlewareInterface
{
    public function __construct(private readonly string $name)
    {
    }

    public function handle(mixed $request, callable $next): mixed
    {
        $request['middleware'][] = $this->name;

        return $next($request);
    }
}

class MiddlewareDispatcherTest extends TestCase
{
    public function testDispatchWithoutMiddleware(): void
    {
        $dispatcher = new MiddlewareDispatcher([]);

        $result = $dispatcher->dispatch(['data' => 'test'], fn ($request) => $request);

        $this->assertEquals(['data' => 'test'], $result);
    }

    public function testDispatchWithSingleMiddleware(): void
    {
        $middleware = function (array $request, $next) {
            $request['modified'] = true;

            return $next($request);
        };

        $dispatcher = new MiddlewareDispatcher([$middleware]);

        $result = $dispatcher->dispatch(['data' => 'test'], fn ($request) => $request);

        $this->assertTrue($result['modified']);
    }

    public function testMiddlewareChain(): void
    {
        $middleware1 = function ($request, $next) {
            $request['count'] = ($request['count'] ?? 0) + 1;

            return $next($request);
        };

        $middleware2 = function ($request, $next) {
            $request['count'] = ($request['count'] ?? 0) + 1;

            return $next($request);
        };

        $middleware3 = function ($request, $next) {
            $request['count'] = ($request['count'] ?? 0) + 1;

            return $next($request);
        };

        $dispatcher = new MiddlewareDispatcher([$middleware1, $middleware2, $middleware3]);

        $result = $dispatcher->dispatch([], fn ($request) => $request);

        $this->assertEquals(3, $result['count']);
    }

    public function testMiddlewareOrder(): void
    {
        $middleware1 = function (array $request, $next) {
            $request['order'][] = '1-before';
            $response = $next($request);
            $response['order'][] = '1-after';

            return $response;
        };

        $middleware2 = function (array $request, $next) {
            $request['order'][] = '2-before';
            $response = $next($request);
            $response['order'][] = '2-after';

            return $response;
        };

        $dispatcher = new MiddlewareDispatcher([$middleware1, $middleware2]);

        $result = $dispatcher->dispatch(['order' => []], function (array $request) {
            $request['order'][] = 'handler';

            return $request;
        });

        $expected = ['1-before', '2-before', 'handler', '2-after', '1-after'];
        $this->assertEquals($expected, $result['order']);
    }

    public function testAddMiddleware(): void
    {
        $dispatcher = new MiddlewareDispatcher();

        $this->assertEmpty($dispatcher->getMiddleware());

        $dispatcher->add(fn ($req, $next) => $next($req));

        $this->assertCount(1, $dispatcher->getMiddleware());
    }
}
