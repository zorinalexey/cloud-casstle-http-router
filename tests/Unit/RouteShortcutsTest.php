<?php

declare(strict_types=1);

namespace CloudCastle\Http\Router\Tests\Unit;

use CloudCastle\Http\Router\Route;
use CloudCastle\Http\Router\Router;
use PHPUnit\Framework\TestCase;

class RouteShortcutsTest extends TestCase
{
    protected function setUp(): void
    {
        Router::reset();
    }

    public function testAuthShortcut(): void
    {
        $route = new Route(['GET'], '/profile', fn (): string => 'profile');
        $route->auth();

        $this->assertContains('auth', $route->getMiddleware());
    }

    public function testGuestShortcut(): void
    {
        $route = new Route(['GET'], '/login', fn (): string => 'login');
        $route->guest();

        $this->assertContains('guest', $route->getMiddleware());
    }

    public function testApiShortcut(): void
    {
        $route = new Route(['GET'], '/api/data', fn (): string => 'data');
        $route->api();

        $this->assertContains('api', $route->getMiddleware());
    }

    public function testWebShortcut(): void
    {
        $route = new Route(['GET'], '/page', fn (): string => 'page');
        $route->web();

        $this->assertContains('web', $route->getMiddleware());
    }

    public function testCorsShortcut(): void
    {
        $route = new Route(['POST'], '/api/endpoint', fn (): string => 'data');
        $route->cors();

        $this->assertContains('cors', $route->getMiddleware());
    }

    public function testLocalhostShortcut(): void
    {
        $route = new Route(['GET'], '/debug', fn (): string => 'debug');
        $route->localhost();

        $whitelist = $route->getWhitelistIps();
        $this->assertContains('127.0.0.1', $whitelist);
        $this->assertContains('::1', $whitelist);
    }

    public function testSecureShortcut(): void
    {
        $route = new Route(['POST'], '/payment', fn (): string => 'payment');
        $route->secure();

        $this->assertTrue($route->isHttpsOnly());
        $this->assertEquals(443, $route->getPort());
    }

    public function testThrottleStandardShortcut(): void
    {
        $route = new Route(['GET'], '/api', fn (): string => 'api');
        $route->throttleStandard();

        $limiter = $route->getRateLimiter();
        $this->assertNotNull($limiter);
        $this->assertEquals(60, $limiter->getMaxAttempts());
        $this->assertEquals(60, $limiter->getDecaySeconds());
        $this->assertEquals(1, $limiter->getDecayMinutes());
    }

    public function testThrottleStrictShortcut(): void
    {
        $route = new Route(['POST'], '/login', fn (): string => 'login');
        $route->throttleStrict();

        $limiter = $route->getRateLimiter();
        $this->assertNotNull($limiter);
        $this->assertEquals(10, $limiter->getMaxAttempts());
    }

    public function testThrottleGenerousShortcut(): void
    {
        $route = new Route(['GET'], '/api/premium', fn (): string => 'premium');
        $route->throttleGenerous();

        $limiter = $route->getRateLimiter();
        $this->assertNotNull($limiter);
        $this->assertEquals(1000, $limiter->getMaxAttempts());
    }

    public function testPublicShortcut(): void
    {
        $route = new Route(['GET'], '/api/public', fn (): string => 'public');
        $route->public();

        $this->assertContains('public', $route->getTags());
    }

    public function testPrivateShortcut(): void
    {
        $route = new Route(['GET'], '/internal', fn (): string => 'internal');
        $route->private();

        $this->assertContains('private', $route->getTags());
    }

    public function testAdminShortcut(): void
    {
        $route = new Route(['GET'], '/admin', fn (): string => 'admin');
        $route->admin();

        $middleware = $route->getMiddleware();
        $this->assertContains('auth', $middleware);
        $this->assertContains('admin', $middleware);
        $this->assertContains('admin', $route->getTags());
    }

    public function testApiEndpointShortcut(): void
    {
        $route = new Route(['GET'], '/api/endpoint', fn (): string => 'endpoint');
        $route->apiEndpoint(200);

        $middleware = $route->getMiddleware();
        $this->assertContains('api', $middleware);
        $this->assertContains('api', $route->getTags());

        $limiter = $route->getRateLimiter();
        $this->assertNotNull($limiter);
        $this->assertEquals(200, $limiter->getMaxAttempts());
    }

    public function testProtectedShortcut(): void
    {
        $route = new Route(['GET'], '/protected', fn (): string => 'protected');
        $route->protected();

        $middleware = $route->getMiddleware();
        $this->assertContains('auth', $middleware);

        $limiter = $route->getRateLimiter();
        $this->assertNotNull($limiter);
        $this->assertEquals(100, $limiter->getMaxAttempts());
    }

    public function testChainedShortcuts(): void
    {
        $route = new Route(['POST'], '/secure/api', fn (): string => 'data');
        $route->auth()
            ->api()
            ->secure()
            ->throttleStrict()
            ->localhost();

        $middleware = $route->getMiddleware();
        $this->assertContains('auth', $middleware);
        $this->assertContains('api', $middleware);
        $this->assertTrue($route->isHttpsOnly());
        $this->assertEquals(10, $route->getRateLimiter()?->getMaxAttempts());
        $this->assertContains('127.0.0.1', $route->getWhitelistIps());
    }
}
