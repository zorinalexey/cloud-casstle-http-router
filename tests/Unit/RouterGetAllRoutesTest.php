<?php

declare(strict_types=1);

namespace CloudCastle\Http\Router\Tests\Unit;

use CloudCastle\Http\Router\Facade\Route;
use CloudCastle\Http\Router\Router;
use PHPUnit\Framework\TestCase;

class RouterGetAllRoutesTest extends TestCase
{
    protected function setUp(): void
    {
        Router::reset();
    }

    public function testGetRoutes(): void
    {
        $router = new Router();

        $router->get('/users', fn (): string => 'users');
        $router->post('/posts', fn (): string => 'posts');
        $router->put('/comments', fn (): string => 'comments');

        $routes = $router->getRoutes();

        $this->assertCount(3, $routes);
        $this->assertContainsOnlyInstancesOf(\CloudCastle\Http\Router\Route::class, $routes);
    }

    public function testGetRoutesAsArray(): void
    {
        $router = new Router();

        $router->get('/users', 'UserController@index')
            ->name('users.index')
            ->tag('api')
            ->middleware('auth');

        $routesArray = $router->getRoutesAsArray();

        $this->assertIsArray($routesArray);
        $this->assertCount(1, $routesArray);
        $this->assertArrayHasKey('uri', $routesArray[0]);
        $this->assertArrayHasKey('methods', $routesArray[0]);
        $this->assertArrayHasKey('name', $routesArray[0]);
        $this->assertArrayHasKey('tags', $routesArray[0]);
        $this->assertArrayHasKey('middleware', $routesArray[0]);
        $this->assertArrayHasKey('action', $routesArray[0]);

        $this->assertEquals('/users', $routesArray[0]['uri']);
        $this->assertEquals(['GET'], $routesArray[0]['methods']);
        $this->assertEquals('users.index', $routesArray[0]['name']);
    }

    public function testCount(): void
    {
        $router = new Router();

        $this->assertEquals(0, $router->count());

        $router->get('/one', fn (): string => '1');
        $this->assertEquals(1, $router->count());

        $router->post('/two', fn (): string => '2');
        $this->assertEquals(2, $router->count());

        $router->group(['prefix' => '/api'], function ($router): void {
            $router->get('/three', fn (): string => '3');
            $router->get('/four', fn (): string => '4');
        });

        $this->assertEquals(4, $router->count());
    }

    public function testGetRoutesAsJson(): void
    {
        $router = new Router();

        $router->get('/users', fn (): string => 'users')->name('users');
        $router->post('/posts', fn (): string => 'posts')->name('posts');

        $json = $router->getRoutesAsJson();

        $this->assertJson($json);

        $decoded = json_decode($json, true);
        $this->assertCount(2, $decoded);
    }

    public function testGetRoutesGroupedByMethod(): void
    {
        $router = new Router();

        $router->get('/users', fn (): string => 'users');
        $router->get('/posts', fn (): string => 'posts');
        $router->post('/users', fn (): string => 'create user');
        $router->put('/users/{id}', fn (): string => 'update user');

        $grouped = $router->getRoutesGroupedByMethod();

        $this->assertArrayHasKey('GET', $grouped);
        $this->assertArrayHasKey('POST', $grouped);
        $this->assertArrayHasKey('PUT', $grouped);

        $this->assertCount(2, $grouped['GET']);
        $this->assertCount(1, $grouped['POST']);
        $this->assertCount(1, $grouped['PUT']);
    }

    public function testGetRoutesGroupedByPrefix(): void
    {
        $router = new Router();

        $router->get('/users', fn (): string => 'users');
        $router->get('/users/profile', fn (): string => 'profile');
        $router->get('/posts', fn (): string => 'posts');
        $router->get('/posts/latest', fn (): string => 'latest');
        $router->get('/about', fn (): string => 'about');

        $grouped = $router->getRoutesGroupedByPrefix();

        $this->assertArrayHasKey('/users', $grouped);
        $this->assertArrayHasKey('/posts', $grouped);
        $this->assertArrayHasKey('/about', $grouped);

        $this->assertCount(2, $grouped['/users']);
        $this->assertCount(2, $grouped['/posts']);
        $this->assertCount(1, $grouped['/about']);
    }

    public function testGetRoutesGroupedByDomain(): void
    {
        $router = new Router();

        $router->get('/main', fn (): string => 'main');
        $router->get('/api', fn (): string => 'api')->domain('api.example.com');
        $router->get('/admin', fn (): string => 'admin')->domain('admin.example.com');
        $router->get('/another', fn (): string => 'another')->domain('api.example.com');

        $grouped = $router->getRoutesGroupedByDomain();

        $this->assertArrayHasKey('default', $grouped);
        $this->assertArrayHasKey('api.example.com', $grouped);
        $this->assertArrayHasKey('admin.example.com', $grouped);

        $this->assertCount(1, $grouped['default']);
        $this->assertCount(2, $grouped['api.example.com']);
        $this->assertCount(1, $grouped['admin.example.com']);
    }

    public function testStaticFacadeGetRoutes(): void
    {
        Route::get('/test1', fn (): string => 'test1');
        Route::get('/test2', fn (): string => 'test2');
        Route::post('/test3', fn (): string => 'test3');

        $routes = Route::getRoutes();
        $this->assertCount(3, $routes);

        $count = Route::count();
        $this->assertEquals(3, $count);
    }

    public function testStaticFacadeGetRoutesAsArray(): void
    {
        Route::get('/users', 'UserController@index')
            ->name('users')
            ->tag('web');

        $routesArray = Route::getRoutesAsArray();

        $this->assertIsArray($routesArray);
        $this->assertCount(1, $routesArray);
        $this->assertEquals('/users', $routesArray[0]['uri']);
        $this->assertEquals('users', $routesArray[0]['name']);
        $this->assertContains('web', $routesArray[0]['tags']);
    }

    public function testStaticFacadeGetRoutesAsJson(): void
    {
        Route::get('/api/users', fn (): string => 'users')->tag('api');

        $json = Route::getRoutesAsJson(JSON_PRETTY_PRINT);

        $this->assertJson($json);
        // Check that JSON contains the URI (may have or not have leading slash)
        $decoded = json_decode($json, true);
        $this->assertNotEmpty($decoded);
        $this->assertStringContainsString('users', $decoded[0]['uri']);
    }

    public function testEmptyRouter(): void
    {
        $router = new Router();

        $this->assertEmpty($router->getRoutes());
        $this->assertEmpty($router->getRoutesAsArray());
        $this->assertEquals(0, $router->count());
        $this->assertEquals('[]', $router->getRoutesAsJson());
    }
}
