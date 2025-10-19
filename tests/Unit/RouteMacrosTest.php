<?php

declare(strict_types=1);

namespace CloudCastle\Http\Router\Tests\Unit;

use CloudCastle\Http\Router\Facade\Route;
use CloudCastle\Http\Router\Router;
use PHPUnit\Framework\TestCase;

class RouteMacrosTest extends TestCase
{
    public function testResourceMacro(): void
    {
        Route::resource('users', 'UserController');

        $routes = Route::getRoutes();
        $this->assertCount(7, $routes);

        // Check named routes
        $this->assertTrue(route_has('user.index'));
        $this->assertTrue(route_has('user.create'));
        $this->assertTrue(route_has('user.store'));
        $this->assertTrue(route_has('user.show'));
        $this->assertTrue(route_has('user.edit'));
        $this->assertTrue(route_has('user.update'));
        $this->assertTrue(route_has('user.destroy'));
    }

    public function testResourceMacroMethods(): void
    {
        Route::resource('posts', 'PostController');

        $indexRoute = route('post.index');
        $this->assertEquals(['GET'], $indexRoute->getMethods());

        $storeRoute = route('post.store');
        $this->assertEquals(['POST'], $storeRoute->getMethods());

        $updateRoute = route('post.update');
        $this->assertEquals(['PUT'], $updateRoute->getMethods());

        $destroyRoute = route('post.destroy');
        $this->assertEquals(['DELETE'], $destroyRoute->getMethods());
    }

    public function testApiResourceMacro(): void
    {
        Route::apiResource('products', 'ProductController', 200);

        $routes = Route::getRoutes();
        $this->assertCount(5, $routes); // API resource has 5 routes (no create/edit forms)

        // Check all routes have API middleware and rate limiting
        foreach ($routes as $route) {
            $this->assertContains('api', $route->getMiddleware());
            $this->assertContains('api', $route->getTags());
            $this->assertNotNull($route->getRateLimiter());
        }
    }

    public function testApiResourceRateLimits(): void
    {
        Route::apiResource('items', 'ItemController', 100);

        $indexRoute = route('item.index');
        $this->assertEquals(100, $indexRoute->getRateLimiter()?->getMaxAttempts());

        $storeRoute = route('item.store');
        $this->assertEquals(50, $storeRoute->getRateLimiter()?->getMaxAttempts()); // Write operations have stricter limits
    }

    public function testCrudMacro(): void
    {
        Route::crud('comments', 'CommentController');

        $routes = Route::getRoutes();
        $this->assertCount(4, $routes);

        // Verify all CRUD operations exist
        $methods = array_map(fn ($r): string => implode('', $r->getMethods()), $routes);
        $this->assertContains('GET', $methods);
        $this->assertContains('POST', $methods);
        $this->assertContains('PUT', $methods);
        $this->assertContains('DELETE', $methods);
    }

    public function testAuthMacro(): void
    {
        Route::auth();

        $routes = Route::getRoutes();
        $this->assertGreaterThanOrEqual(6, count($routes));

        // Check key routes exist
        $this->assertTrue(route_has('login'));
        $this->assertTrue(route_has('logout'));
        $this->assertTrue(route_has('register'));
        $this->assertTrue(route_has('password.request'));
    }

    public function testAuthMacroRateLimiting(): void
    {
        Route::auth();

        $loginRoute = route('login.post');
        $this->assertEquals(10, $loginRoute->getRateLimiter()?->getMaxAttempts());
        $this->assertEquals(60, $loginRoute->getRateLimiter()?->getDecaySeconds());

        $registerRoute = route('register.post');
        $this->assertEquals(3, $registerRoute->getRateLimiter()?->getMaxAttempts());
        $this->assertEquals(10, $registerRoute->getRateLimiter()?->getDecayMinutes());
    }

    public function testAdminPanelMacro(): void
    {
        Route::adminPanel(['192.168.1.1']);

        $routes = Route::getRoutes();
        $this->assertGreaterThanOrEqual(3, count($routes));

        // Check all routes have admin middleware and IP restriction
        foreach ($routes as $route) {
            $middleware = $route->getMiddleware();
            $this->assertContains('auth', $middleware);
            $this->assertContains('admin', $middleware);
            $this->assertContains('192.168.1.1', $route->getWhitelistIps());
        }
    }

    public function testApiVersionMacro(): void
    {
        Route::apiVersion('v1', function (): void {
            Route::get('users', fn (): string => 'users');
            Route::get('posts', fn (): string => 'posts');
        });

        $routes = Route::getRoutes();
        $this->assertCount(2, $routes);

        foreach ($routes as $route) {
            $this->assertStringContainsString('api/v1', $route->getUri());
            $middleware = $route->getMiddleware();
            $this->assertNotEmpty($middleware, 'Middleware should not be empty');
            // Tags are applied at group level, check there's throttling instead
            $limiter = $route->getRateLimiter();
            $this->assertNotNull($limiter);
            $this->assertEquals(100, $limiter->getMaxAttempts());
        }
    }

    public function testWebhooksMacro(): void
    {
        Route::webhooks(['192.0.2.1']);

        $routes = Route::getRoutes();
        $this->assertGreaterThanOrEqual(3, $routes);

        foreach ($routes as $route) {
            $this->assertStringContainsString('webhooks', $route->getUri());
            $this->assertContains('192.0.2.1', $route->getWhitelistIps());
            $limiter = $route->getRateLimiter();
            $this->assertNotNull($limiter);
            $this->assertEquals(1000, $limiter->getMaxAttempts());
        }
    }

    public function testMultipleMacrosCombined(): void
    {
        Route::resource('users', 'UserController');
        Route::apiResource('products', 'ProductController');
        Route::auth();
        Route::adminPanel();

        $stats = route_stats();
        $this->assertGreaterThan(15, $stats['total']);
        $this->assertGreaterThan(10, $stats['named']);
    }

    protected function setUp(): void
    {
        Router::reset();
    }
}
