<?php

declare(strict_types=1);

namespace CloudCastle\Http\Router\Tests\Unit;

use CloudCastle\Http\Router\Route;
use CloudCastle\Http\Router\RouteCompiler;
use PHPUnit\Framework\TestCase;

class RouteCompilerTest extends TestCase
{
    private RouteCompiler $compiler;

    protected function setUp(): void
    {
        $this->compiler = new RouteCompiler();
    }

    public function testCompileSimpleRoute(): void
    {
        $routes = [
            new Route(['GET'], '/users', 'UserController@index'),
        ];

        $compiled = $this->compiler->compile($routes);

        $this->assertIsArray($compiled);
        $this->assertArrayHasKey('routes', $compiled);
        $this->assertArrayHasKey('named', $compiled);
        $this->assertArrayHasKey('tagged', $compiled);
        $this->assertArrayHasKey('metadata', $compiled);
        $this->assertCount(1, $compiled['routes']);
    }

    public function testCompileNamedRoute(): void
    {
        $route = new Route(['GET'], '/users', 'UserController@index');
        $route->name('users.index');

        $compiled = $this->compiler->compile([$route]);

        $this->assertArrayHasKey('users.index', $compiled['named']);
        $this->assertEquals(0, $compiled['named']['users.index']);
    }

    public function testCompileTaggedRoute(): void
    {
        $route = new Route(['GET'], '/api/users', 'Api\UserController@index');
        $route->tag(['api', 'public']);

        $compiled = $this->compiler->compile([$route]);

        $this->assertArrayHasKey('api', $compiled['tagged']);
        $this->assertArrayHasKey('public', $compiled['tagged']);
        $this->assertContains(0, $compiled['tagged']['api']);
        $this->assertContains(0, $compiled['tagged']['public']);
    }

    public function testRestoreRoutes(): void
    {
        $originalRoute = new Route(['GET'], '/users/{id}', 'UserController@show');
        $originalRoute->name('users.show')->tag('web');

        $compiled = $this->compiler->compile([$originalRoute]);
        $restored = $this->compiler->restore($compiled);

        $this->assertCount(1, $restored);
        $this->assertEquals('/users/{id}', $restored[0]->getUri());
        $this->assertEquals(['GET'], $restored[0]->getMethods());
        $this->assertEquals('users.show', $restored[0]->getName());
        $this->assertContains('web', $restored[0]->getTags());
    }

    public function testCompileClosureRoute(): void
    {
        $route = new Route(['GET'], '/test', fn (): string => 'test');

        $compiled = $this->compiler->compile([$route]);

        $this->assertEquals('closure', $compiled['routes'][0]['action']['type']);
        $this->assertFalse($compiled['routes'][0]['action']['serialized']);
    }

    public function testCompileArrayAction(): void
    {
        $route = new Route(['POST'], '/users', ['UserController', 'store']);

        $compiled = $this->compiler->compile([$route]);

        $this->assertEquals('array', $compiled['routes'][0]['action']['type']);
        $this->assertEquals('UserController', $compiled['routes'][0]['action']['controller']);
        $this->assertEquals('store', $compiled['routes'][0]['action']['method']);
    }

    public function testCompileWithMiddleware(): void
    {
        $route = new Route(['GET'], '/protected', 'Controller@method');
        $route->middleware(['auth', 'admin']);

        $compiled = $this->compiler->compile([$route]);

        $this->assertCount(2, $compiled['routes'][0]['middleware']);
        $this->assertEquals('class', $compiled['routes'][0]['middleware'][0]['type']);
        $this->assertEquals('auth', $compiled['routes'][0]['middleware'][0]['class']);
    }

    public function testCompileMetadata(): void
    {
        $routes = [
            new Route(['GET'], '/one', 'Controller@one'),
            new Route(['POST'], '/two', 'Controller@two'),
        ];

        $compiled = $this->compiler->compile($routes);

        $this->assertArrayHasKey('compiled_at', $compiled['metadata']);
        $this->assertEquals(2, $compiled['metadata']['routes_count']);
    }
}
