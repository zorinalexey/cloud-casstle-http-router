<?php

declare(strict_types=1);

namespace CloudCastle\Http\Router\Tests\Unit;

use CloudCastle\Http\Router\Facade\Route;
use CloudCastle\Http\Router\Router;
use PHPUnit\Framework\TestCase;

class HelpersTest extends TestCase
{
    protected function setUp(): void
    {
        Router::reset();
        
        // Setup test routes
        Route::get('/', fn() => 'home')->name('home');
        Route::get('/users', fn() => 'users')->name('users.index');
        Route::get('/users/{id}', fn($id) => "user {$id}")->name('users.show');
        Route::get('/posts', fn() => 'posts')->name('posts.index')->tag('blog');
    }

    public function testRouteHelper(): void
    {
        $route = route('users.show');
        
        $this->assertNotNull($route);
        $this->assertEquals('/users/{id}', $route->getUri());
        $this->assertEquals('users.show', $route->getName());
    }

    public function testRouteHelperWithoutName(): void
    {
        Route::dispatch('/', 'GET');
        $current = route();
        
        $this->assertNotNull($current);
        $this->assertEquals('home', $current->getName());
    }

    public function testCurrentRouteHelper(): void
    {
        Route::dispatch('/users', 'GET');
        $current = current_route();
        
        $this->assertNotNull($current);
        $this->assertEquals('users.index', $current->getName());
    }

    public function testPreviousRouteHelper(): void
    {
        Route::dispatch('/', 'GET');
        $this->assertNull(previous_route());
        
        Route::dispatch('/users', 'GET');
        $previous = previous_route();
        
        $this->assertNotNull($previous);
        $this->assertEquals('home', $previous->getName());
    }

    public function testRouteIsHelper(): void
    {
        Route::dispatch('/users', 'GET');
        
        $this->assertTrue(route_is('users.index'));
        $this->assertFalse(route_is('home'));
        $this->assertFalse(route_is('nonexistent'));
    }

    public function testRouteNameHelper(): void
    {
        Route::dispatch('/posts', 'GET');
        
        $this->assertEquals('posts.index', route_name());
    }

    public function testRouterHelper(): void
    {
        $router = router();
        
        $this->assertInstanceOf(Router::class, $router);
        $this->assertSame(Router::getInstance(), $router);
    }

    public function testRouteUrlHelper(): void
    {
        $url = route_url('users.show', ['id' => 123]);
        
        $this->assertEquals('/users/123', $url);
    }

    public function testRouteUrlWithMultipleParameters(): void
    {
        Route::get('/posts/{year}/{month}/{slug}', fn() => 'post')
            ->name('posts.show');
        
        $url = route_url('posts.show', [
            'year' => 2024,
            'month' => 10,
            'slug' => 'hello-world',
        ]);
        
        $this->assertEquals('/posts/2024/10/hello-world', $url);
    }

    public function testRouteHasHelper(): void
    {
        $this->assertTrue(route_has('home'));
        $this->assertTrue(route_has('users.index'));
        $this->assertFalse(route_has('nonexistent'));
    }

    public function testRouteStatsHelper(): void
    {
        $stats = route_stats();
        
        $this->assertIsArray($stats);
        $this->assertArrayHasKey('total', $stats);
        $this->assertArrayHasKey('named', $stats);
        $this->assertGreaterThan(0, $stats['total']);
    }

    public function testRoutesByTagHelper(): void
    {
        $routes = routes_by_tag('blog');
        
        $this->assertIsArray($routes);
        $this->assertCount(1, $routes);
    }

    public function testRouteBackHelper(): void
    {
        Route::dispatch('/', 'GET');
        Route::dispatch('/users', 'GET');
        $backUrl = route_back('/default');
        
        $this->assertEquals('/', $backUrl);
    }

    public function testRouteBackWithDefault(): void
    {
        $backUrl = route_back('/default');
        
        $this->assertEquals('/default', $backUrl);
    }

    public function testDispatchRouteHelper(): void
    {
        $_SERVER['REQUEST_URI'] = '/users';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['HTTP_HOST'] = 'example.com';
        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
        $_SERVER['SERVER_PORT'] = '80';

        $route = dispatch_route();
        
        $this->assertNotNull($route);
        $this->assertEquals('users.index', $route->getName());
    }

    public function testDispatchRouteHelperWithQueryString(): void
    {
        $_SERVER['REQUEST_URI'] = '/users?page=2&limit=10';
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $route = dispatch_route();
        
        $this->assertEquals('users.index', $route->getName());
    }
}

