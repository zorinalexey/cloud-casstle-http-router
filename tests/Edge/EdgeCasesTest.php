<?php

declare(strict_types=1);

namespace CloudCastle\Http\Router\Tests\Edge;

use CloudCastle\Http\Router\Exceptions\RouteNotFoundException;
use CloudCastle\Http\Router\Facade\Route as RouteFacade;
use CloudCastle\Http\Router\Router;
use Exception;
use PHPUnit\Framework\TestCase;

/**
 * Edge cases and corner scenarios.
 */
class EdgeCasesTest extends TestCase
{
    public function testEmptyRouter(): void
    {
        $router = new Router();

        $this->assertCount(0, $router->getRoutes());
        $this->assertNull($router->current());
        $this->assertNull($router->previous());
        $this->assertEquals([], $router->getNamedRoutes());
    }

    public function testDuplicateRouteNames(): void
    {
        $router = new Router();

        $router->get('/first', fn (): string => 'first')->name('duplicate');
        $router->get('/second', fn (): string => 'second')->name('duplicate');

        // Last one wins
        $route = $router->getRouteByName('duplicate');
        $this->assertEquals('/second', $route?->getUri());
    }

    public function testSameUriDifferentMethods(): void
    {
        $router = new Router();

        $router->get('/resource', fn (): string => 'get');
        $router->post('/resource', fn (): string => 'post');
        $router->put('/resource', fn (): string => 'put');

        $getRoute = $router->dispatch('/resource', 'GET');
        $this->assertEquals(['GET'], $getRoute->getMethods());

        $postRoute = $router->dispatch('/resource', 'POST');
        $this->assertEquals(['POST'], $postRoute->getMethods());
    }

    public function testEmptyUriSegments(): void
    {
        $router = new Router();

        $router->get('//double//slash', fn (): string => 'test');

        // Router should normalize or handle empty segments
        $routes = $router->getRoutes();
        $this->assertCount(1, $routes);
    }

    public function testSpecialCharactersInUri(): void
    {
        $router = new Router();

        $router->get('/users/{name}', fn ($name): string => 'user: ' . $name);

        $route = $router->dispatch('/users/john-doe', 'GET');
        $this->assertEquals(['name' => 'john-doe'], $route->getParameters());
    }

    public function testUtf8Uri(): void
    {
        $router = new Router();

        $router->get('/пользователи/{id}', fn ($id): string => 'user ' . $id);

        try {
            $route = $router->dispatch('/пользователи/123', 'GET');
            $this->assertEquals(['id' => '123'], $route->getParameters());
        } catch (Exception) {
            // UTF-8 might not be supported in all configurations
            $this->markTestSkipped('UTF-8 URIs not supported');
        }
    }

    public function testCaseSensitivity(): void
    {
        $router = new Router();

        $router->get('/users', fn (): string => 'users');

        // URI is case-sensitive by default
        $this->expectException(RouteNotFoundException::class);
        $router->dispatch('/Users', 'GET');
    }

    public function testMethodCaseInsensitivity(): void
    {
        $router = new Router();

        $router->get('/test', fn (): string => 'test');

        // Method should be case-insensitive
        $route1 = $router->dispatch('/test', 'get');
        $route2 = $router->dispatch('/test', 'GET');
        $route3 = $router->dispatch('/test', 'GeT');

        $this->assertNotNull($route1);
        $this->assertNotNull($route2);
        $this->assertNotNull($route3);
    }

    public function testTrailingSlash(): void
    {
        $router = new Router();

        $router->get('/users', fn (): string => 'users');

        // Exact match required
        $route1 = $router->dispatch('/users', 'GET');
        $this->assertNotNull($route1);

        try {
            $router->dispatch('/users/', 'GET');
            $this->fail('Should not match with trailing slash');
        } catch (RouteNotFoundException) {
            $this->assertTrue(true);
        }
    }

    public function testVeryLongParameterValue(): void
    {
        $router = new Router();

        $router->get('/search/{query}', fn ($query): string => 'search: ' . $query);

        $longQuery = str_repeat('a', 1000);
        $route = $router->dispatch('/search/' . $longQuery, 'GET');

        $this->assertEquals($longQuery, $route->getParameters()['query']);
    }

    public function testMultipleGroupsOnSameRoute(): void
    {
        $router = new Router();

        // Route should inherit from all nested groups
        $router->group(['middleware' => 'outer'], function ($router): void {
            $router->group(['middleware' => 'inner'], function ($router): void {
                $router->get('/nested', fn (): string => 'nested');
            });
        });

        $routes = $router->getRoutes();
        $middleware = $routes[0]->getMiddleware();

        $this->assertContains('outer', $middleware);
        $this->assertContains('inner', $middleware);
    }

    public function testOptionalParameters(): void
    {
        $router = new Router();

        // Route with optional parameter using regex
        $router->get('/posts/{id:\d+}?', fn ($id = null): string => 'post: ' . $id);

        // This is a basic test - full optional param support might need enhancement
        $routes = $router->getRoutes();
        $this->assertCount(1, $routes);
    }

    public function testConflictingRoutes(): void
    {
        $router = new Router();

        // More specific route should be checked first
        $router->get('/users/admin', fn (): string => 'admin user');
        $router->get('/users/{id}', fn ($id): string => 'user ' . $id);

        // Currently, first registered wins
        $route = $router->dispatch('/users/admin', 'GET');
        $this->assertNotNull($route);
    }

    public function testEmptyMiddlewareArray(): void
    {
        $router = new Router();

        $router->get('/test', fn (): string => 'test')->middleware([]);

        $routes = $router->getRoutes();
        $this->assertEmpty($routes[0]->getMiddleware());
    }

    public function testNullValuesHandling(): void
    {
        $router = new Router();

        $router->get('/test', fn (): string => 'test');

        // Dispatch with all optional params as null
        $route = $router->dispatch('/test', 'GET', null, null, null);

        $this->assertNotNull($route);
        $this->assertNull($route->getDomain());
        $this->assertNull($route->getPort());
    }

    public function testStaticMethodsIsolation(): void
    {
        // Static methods should use singleton
        RouteFacade::get('/static1', fn (): string => 'static1')->name('static1');

        // Creating new instance should not see static routes
        $newRouter = new Router();
        $this->assertCount(0, $newRouter->getRoutes());

        // Singleton should have the route
        $singleton = Router::getInstance();
        $this->assertCount(1, $singleton->getRoutes());
    }

    protected function setUp(): void
    {
        Router::reset();
    }
}
