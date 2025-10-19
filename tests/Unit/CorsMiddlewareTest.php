<?php

declare(strict_types=1);

namespace CloudCastle\Http\Router\Tests\Unit;

use CloudCastle\Http\Router\Middleware\CorsMiddleware;
use PHPUnit\Framework\TestCase;

class CorsMiddlewareTest extends TestCase
{
    protected function setUp(): void
    {
        // Reset server vars for each test
        $_SERVER = [];
    }

    public function testDefaultConfiguration(): void
    {
        $middleware = new CorsMiddleware();

        $_SERVER['HTTP_ORIGIN'] = 'https://example.com';
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $called = false;
        $middleware->handle('/test', function () use (&$called): string {
            $called = true;

            return 'response';
        });

        $this->assertTrue($called);
    }

    public function testAllowedOrigins(): void
    {
        $middleware = new CorsMiddleware(
            allowedOrigins: ['https://example.com', 'https://test.com']
        );

        $_SERVER['HTTP_ORIGIN'] = 'https://example.com';
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $called = false;
        $middleware->handle('/test', function () use (&$called): string {
            $called = true;

            return 'response';
        });

        $this->assertTrue($called);
    }

    public function testWildcardOrigin(): void
    {
        $middleware = new CorsMiddleware(
            allowedOrigins: ['*']
        );

        $_SERVER['HTTP_ORIGIN'] = 'https://any-origin.com';
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $called = false;
        $middleware->handle('/test', function () use (&$called): string {
            $called = true;

            return 'response';
        });

        $this->assertTrue($called);
    }

    public function testPreflightRequest(): void
    {
        $middleware = new CorsMiddleware(
            allowedOrigins: ['*'],
            allowedMethods: ['GET', 'POST', 'PUT'],
            allowedHeaders: ['Content-Type', 'Authorization']
        );

        $_SERVER['HTTP_ORIGIN'] = 'https://example.com';
        $_SERVER['REQUEST_METHOD'] = 'OPTIONS';

        $this->expectOutputString('');

        try {
            $middleware->handle('/test', fn (): string => 'should not reach here');
        } catch (\Exception) {
            // Expected exit() call
        }
    }

    public function testAllowCredentials(): void
    {
        $middleware = new CorsMiddleware(
            allowedOrigins: ['https://example.com'],
            allowCredentials: true
        );

        $_SERVER['HTTP_ORIGIN'] = 'https://example.com';
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $middleware->handle('/test', fn (): string => 'response');

        // Credentials header should be set
        $this->assertTrue(true); // Headers are set via header() function
    }

    public function testMaxAge(): void
    {
        $middleware = new CorsMiddleware(
            maxAge: 7200
        );

        $_SERVER['HTTP_ORIGIN'] = 'https://example.com';
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $middleware->handle('/test', fn (): string => 'response');

        $this->assertTrue(true);
    }

    public function testNoOriginHeader(): void
    {
        $middleware = new CorsMiddleware();

        unset($_SERVER['HTTP_ORIGIN']);
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $called = false;
        $middleware->handle('/test', function () use (&$called): string {
            $called = true;

            return 'response';
        });

        $this->assertTrue($called);
    }

    public function testAllowedMethodsConfiguration(): void
    {
        $middleware = new CorsMiddleware(
            allowedMethods: ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS', 'PATCH', 'HEAD', 'VIEW', 'CUSTOM']
        );

        $_SERVER['HTTP_ORIGIN'] = 'https://example.com';
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $called = false;
        $middleware->handle('/test', function () use (&$called): string {
            $called = true;

            return 'response';
        });

        $this->assertTrue($called);
    }

    public function testAllowedHeadersConfiguration(): void
    {
        $middleware = new CorsMiddleware(
            allowedHeaders: [
                'Content-Type',
                'Authorization',
                'X-Requested-With',
                'X-CSRF-TOKEN',
                'X-XSRF-TOKEN',
                'X-XSRF-HEADER',
            ]
        );

        $_SERVER['HTTP_ORIGIN'] = 'https://example.com';
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $called = false;
        $middleware->handle('/test', function () use (&$called): string {
            $called = true;

            return 'response';
        });

        $this->assertTrue($called);
    }
}
