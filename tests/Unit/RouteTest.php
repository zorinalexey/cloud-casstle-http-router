<?php

declare(strict_types=1);

namespace CloudCastle\Http\Router\Tests\Unit;

use CloudCastle\Http\Router\Route;
use CloudCastle\Http\Router\Router;
use PHPUnit\Framework\TestCase;

class RouteTest extends TestCase
{
    public function testRouteCreation(): void
    {
        $route = new Route(['GET'], '/users', function () {
            return 'users';
        });

        $this->assertEquals('/users', $route->getUri());
        $this->assertEquals(['GET'], $route->getMethods());
        $this->assertIsCallable($route->getAction());
    }

    public function testRouteWithMultipleMethods(): void
    {
        $route = new Route(['GET', 'POST'], '/form', 'FormController@handle');

        $this->assertEquals(['GET', 'POST'], $route->getMethods());
    }

    public function testRouteMatching(): void
    {
        $route = new Route(['GET'], '/users/{id}', function ($id) {
            return "User: $id";
        });

        $this->assertTrue($route->matches('/users/123', 'GET'));
        $this->assertFalse($route->matches('/users/123', 'POST'));
        $this->assertFalse($route->matches('/posts/123', 'GET'));
    }

    public function testRouteParameterExtraction(): void
    {
        $route = new Route(['GET'], '/users/{id}', function () {
        });
        $route->matches('/users/123', 'GET');

        $params = $route->getParameters();
        $this->assertArrayHasKey('id', $params);
        $this->assertEquals('123', $params['id']);
    }

    public function testRouteWithRegexConstraint(): void
    {
        $route = new Route(['GET'], '/posts/{id:\d+}', function () {
        });

        $this->assertTrue($route->matches('/posts/123', 'GET'));
        $this->assertFalse($route->matches('/posts/abc', 'GET'));
    }

    public function testMultipleParametersExtraction(): void
    {
        $route = new Route(['GET'], '/posts/{year:\d{4}}/{month:\d{2}}/{slug}', function () {
        });
        $route->matches('/posts/2024/01/hello-world', 'GET');

        $params = $route->getParameters();
        $this->assertEquals('2024', $params['year']);
        $this->assertEquals('01', $params['month']);
        $this->assertEquals('hello-world', $params['slug']);
    }

    public function testRouteNaming(): void
    {
        $route = new Route(['GET'], '/users', function () {
        });
        $route->name('users.index');

        $this->assertEquals('users.index', $route->getName());
    }

    public function testRouteTagging(): void
    {
        $route = new Route(['GET'], '/api/users', function () {
        });
        $route->tag('api')->tag('public');

        $tags = $route->getTags();
        $this->assertContains('api', $tags);
        $this->assertContains('public', $tags);
    }

    public function testRouteTaggingWithArray(): void
    {
        $route = new Route(['GET'], '/api/users', function () {
        });
        $route->tag(['api', 'public', 'v1']);

        $tags = $route->getTags();
        $this->assertCount(3, $tags);
        $this->assertContains('api', $tags);
        $this->assertContains('public', $tags);
        $this->assertContains('v1', $tags);
    }

    public function testRouteMiddleware(): void
    {
        $route = new Route(['GET'], '/admin', function () {
        });
        $route->middleware('auth')->middleware('admin');

        $middleware = $route->getMiddleware();
        $this->assertCount(2, $middleware);
        $this->assertContains('auth', $middleware);
        $this->assertContains('admin', $middleware);
    }

    public function testRouteDomain(): void
    {
        $route = new Route(['GET'], '/dashboard', function () {
        });
        $route->domain('admin.example.com');

        $this->assertEquals('admin.example.com', $route->getDomain());
        $this->assertTrue($route->isDomainAllowed('admin.example.com'));
        $this->assertFalse($route->isDomainAllowed('api.example.com'));
    }

    public function testRoutePort(): void
    {
        $route = new Route(['GET'], '/metrics', function () {
        });
        $route->port(8080);

        $this->assertEquals(8080, $route->getPort());
        $this->assertTrue($route->isPortAllowed(8080));
        $this->assertFalse($route->isPortAllowed(80));
    }

    public function testRouteWhitelistIp(): void
    {
        $route = new Route(['GET'], '/admin', function () {
        });
        $route->whitelistIp(['192.168.1.1', '10.0.0.1']);

        $this->assertTrue($route->isIpAllowed('192.168.1.1'));
        $this->assertTrue($route->isIpAllowed('10.0.0.1'));
        $this->assertFalse($route->isIpAllowed('1.2.3.4'));
    }

    public function testRouteBlacklistIp(): void
    {
        $route = new Route(['GET'], '/api', function () {
        });
        $route->blacklistIp(['1.2.3.4', '5.6.7.8']);

        $this->assertFalse($route->isIpAllowed('1.2.3.4'));
        $this->assertFalse($route->isIpAllowed('5.6.7.8'));
        $this->assertTrue($route->isIpAllowed('192.168.1.1'));
    }

    public function testRouteWithoutConstraints(): void
    {
        $route = new Route(['GET'], '/users', function () {
        });

        // Without domain constraint, all domains allowed
        $this->assertTrue($route->isDomainAllowed('any.domain.com'));

        // Without port constraint, all ports allowed
        $this->assertTrue($route->isPortAllowed(8080));
        $this->assertTrue($route->isPortAllowed(80));

        // Without IP constraints, all IPs allowed
        $this->assertTrue($route->isIpAllowed('1.2.3.4'));
    }

    public function testFluentInterface(): void
    {
        $route = new Route(['GET'], '/api/users', function () {
        });

        $result = $route
            ->name('api.users')
            ->tag('api')
            ->middleware('auth')
            ->domain('api.example.com')
            ->port(443)
            ->whitelistIp('192.168.1.1');

        $this->assertInstanceOf(Route::class, $result);
        $this->assertEquals('api.users', $route->getName());
    }
}
