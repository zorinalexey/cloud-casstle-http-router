<?php

declare(strict_types=1);

namespace CloudCastle\Http\Router\Tests\Unit;

use CloudCastle\Http\Router\Route;
use CloudCastle\Http\Router\RouteGroup;
use PHPUnit\Framework\TestCase;

class RouteGroupTest extends TestCase
{
    public function testGroupCreation(): void
    {
        $group = new RouteGroup(['prefix' => '/api']);

        $this->assertEquals('/api', $group->getPrefix());
    }

    public function testGroupWithMiddleware(): void
    {
        $group = new RouteGroup(['middleware' => 'auth']);

        $middleware = $group->getMiddleware();
        $this->assertContains('auth', $middleware);
    }

    public function testGroupWithDomain(): void
    {
        $group = new RouteGroup(['domain' => 'api.example.com']);

        $this->assertEquals('api.example.com', $group->getDomain());
    }

    public function testGroupWithPort(): void
    {
        $group = new RouteGroup(['port' => 8080]);

        $this->assertInstanceOf(RouteGroup::class, $group);
    }

    public function testGroupWithThrottle(): void
    {
        $group = new RouteGroup(['throttle' => ['max' => 100, 'decay' => 1]]);

        $rateLimiter = $group->getRateLimiter();
        $this->assertNotNull($rateLimiter);
        $this->assertEquals(100, $rateLimiter->getMaxAttempts());
    }

    public function testGroupWithTags(): void
    {
        $group = new RouteGroup(['tags' => ['api', 'v1']]);

        $tags = $group->getTags();
        $this->assertContains('api', $tags);
        $this->assertContains('v1', $tags);
    }

    public function testGroupWithIpWhitelist(): void
    {
        $group = new RouteGroup(['whitelistIp' => ['192.168.1.1']]);

        $ips = $group->getWhitelistIps();
        $this->assertContains('192.168.1.1', $ips);
    }

    public function testGroupWithIpBlacklist(): void
    {
        $group = new RouteGroup(['blacklistIp' => ['1.2.3.4']]);

        $ips = $group->getBlacklistIps();
        $this->assertContains('1.2.3.4', $ips);
    }

    public function testFluentInterface(): void
    {
        $group = new RouteGroup();

        $result = $group
            ->prefix('/api')
            ->middleware('auth')
            ->domain('api.example.com')
            ->port(8080)
            ->throttle(100, 1)
            ->tag('api')
            ->whitelistIp('192.168.1.1')
            ->blacklistIp('1.2.3.4');

        $this->assertInstanceOf(RouteGroup::class, $result);
        $this->assertEquals('/api', $group->getPrefix());
    }

    public function testApplyToRoute(): void
    {
        $group = new RouteGroup([
            'prefix' => '/api',
            'middleware' => 'auth',
            'domain' => 'api.example.com',
        ]);

        $route = new Route(['GET'], '/users', function () {
        });
        $group->applyToRoute($route);

        $this->assertContains('auth', $route->getMiddleware());
        $this->assertEquals('api.example.com', $route->getDomain());
    }
}
