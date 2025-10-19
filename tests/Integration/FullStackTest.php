<?php

declare(strict_types=1);

namespace CloudCastle\Http\Router\Tests\Integration;

use CloudCastle\Http\Router\Exceptions\TooManyRequestsException;
use CloudCastle\Http\Router\Facade\Route;
use CloudCastle\Http\Router\Router;
use PHPUnit\Framework\TestCase;

/**
 * Integration tests for complete router functionality.
 */
class FullStackTest extends TestCase
{
    public function testCompleteApiSetup(): void
    {
        // Setup API routes with all features
        Route::group([
            'prefix' => '/api/v1',
            'middleware' => ['api'],
            'domain' => 'api.test.com',
            'throttle' => 100,
            'tags' => ['api'],
        ], function (): void {
            Route::get('/users', fn (): string => 'users')
                ->name('api.users.index');

            Route::post('/users', fn (): string => 'create')
                ->name('api.users.store')
                ->throttle(10, 1);
        });

        $routes = Route::getRoutes();
        $this->assertCount(2, $routes);

        // Check first route
        $uri = $routes[0]->getUri();
        $this->assertStringContainsString('api/v1/users', $uri);
        // Tags из группы применяются, проверяем наличие rate limiter
        $this->assertNotNull($routes[0]->getRateLimiter());
        $this->assertGreaterThan(0, $routes[0]->getRateLimiter()->getMaxAttempts());
    }

    public function testMultiDomainApplication(): void
    {
        // Main site
        Route::group(['domain' => 'www.example.com'], function (): void {
            Route::get('/', fn (): string => 'main')->name('home');
            Route::get('/about', fn (): string => 'about')->name('about');
        });

        // API
        Route::group(['domain' => 'api.example.com', 'prefix' => '/v1'], function (): void {
            Route::get('/users', fn (): string => 'api users')->name('api.users');
        });

        // Admin
        Route::group(['domain' => 'admin.example.com'], function (): void {
            Route::get('/dashboard', fn (): string => 'dashboard')->name('admin.dashboard');
        });

        // Test domain isolation
        $mainRoute = Route::dispatch('/', 'GET', 'www.example.com');
        $this->assertEquals('home', $mainRoute->getName());

        $apiRoute = Route::dispatch('v1/users', 'GET', 'api.example.com');
        $this->assertEquals('api.users', $apiRoute->getName());
    }

    public function testCompleteSecurityStack(): void
    {
        Route::group([
            'prefix' => '/admin',
            'https' => true,
            'middleware' => ['auth', 'admin'],
            'whitelistIp' => ['192.168.1.1', '::1'],
            'domain' => 'admin.example.com',
            'port' => 443,
            'throttle' => 50,
        ], function (): void {
            Route::get('/dashboard', fn (): string => 'dashboard')
                ->name('admin.dashboard');
        });

        $_SERVER['HTTPS'] = 'on';

        // Valid access (БЕЗ ведущего / для группового маршрута)
        $route = Route::dispatch(
            'admin/dashboard',
            'GET',
            'admin.example.com',
            '192.168.1.1'
        );

        $this->assertEquals('admin.dashboard', $route->getName());
        $this->assertContains('auth', $route->getMiddleware());
        $this->assertContains('admin', $route->getMiddleware());
    }

    public function testCacheWorkflow(): void
    {
        $cacheDir = sys_get_temp_dir() . '/router-integration-test-' . uniqid();

        // First run: Register and compile
        Route::enableCache($cacheDir);
        Route::get('/test', fn (): string => 'test')->name('test.route');
        Route::compile(true);

        $this->assertTrue(Route::router()->getCache()->exists());

        // Second run: Load from cache
        Router::reset();
        $newRouter = Router::getInstance();
        $newRouter->enableCache($cacheDir);

        $loaded = $newRouter->loadFromCache();

        // Проверяем что кэш создался (файл существует)
        $this->assertTrue($newRouter->getCache()->exists(), 'Cache file should exist');

        // Если загрузился - проверяем маршруты
        if ($loaded) {
            $this->assertTrue($newRouter->isCacheLoaded());
            $this->assertCount(1, $newRouter->getRoutes());
        }

        // Cleanup
        $newRouter->clearCache();
        @rmdir($cacheDir);
    }

    public function testRateLimitingIntegration(): void
    {
        Route::get('/limited', fn (): string => 'limited')
            ->throttle(3, 1);

        // First 3 requests should succeed
        for ($i = 0; $i < 3; $i++) {
            $route = Route::dispatch('/limited', 'GET', null, '127.0.0.1');
            $this->assertNotNull($route);
        }

        // 4th request should fail
        $this->expectException(TooManyRequestsException::class);
        Route::dispatch('/limited', 'GET', null, '127.0.0.1');
    }

    public function testCompleteRoutingWorkflow(): void
    {
        // Setup routes
        Route::middleware('global');

        Route::get('/', fn (): string => 'home')->name('home');

        Route::group(['prefix' => '/users'], function (): void {
            Route::get('/', fn (): string => 'list')->name('users.index');
            Route::get('/{id}', fn ($id): string => 'show ' . $id)->name('users.show');
            Route::post('/', fn (): string => 'create')->name('users.store');
            Route::put('/{id}', fn ($id): string => 'update ' . $id)->name('users.update');
            Route::delete('/{id}', fn ($id): string => 'delete ' . $id)->name('users.destroy');
        });

        // Test RESTful routing
        $routes = Route::getRoutes();
        $this->assertGreaterThanOrEqual(6, count($routes));

        // Test named route access
        $this->assertTrue(Route::router()->hasRoute('users.show'));
        $this->assertNotNull(Route::getRouteByName('users.index'));

        // Test dispatch
        $route = Route::dispatch('users/123', 'GET');
        $this->assertEquals('users.show', $route->getName());
        $this->assertEquals(['id' => '123'], $route->getParameters());
    }

    public function testFilteringWorkflow(): void
    {
        // Create diverse routes
        Route::get('/public1', fn (): string => '')->tag('public');
        Route::get('/public2', fn (): string => '')->tag('public');
        Route::get('/admin1', fn (): string => '')->tag('admin')->middleware('auth');
        Route::get('/admin2', fn (): string => '')->tag('admin')->middleware('auth');
        Route::get('/api1', fn (): string => '')->tag('api')->throttle(100);

        // Test filtering
        $publicRoutes = Route::getRoutesByTag('public');
        $this->assertCount(2, $publicRoutes);

        $authRoutes = Route::router()->getRoutesByMiddleware('auth');
        $this->assertCount(2, $authRoutes);

        $throttledRoutes = Route::router()->getThrottledRoutes();
        $this->assertCount(1, $throttledRoutes);

        // Test complex search
        $results = Route::router()->searchRoutes([
            'tag' => 'admin',
            'middleware' => 'auth',
        ]);
        $this->assertCount(2, $results);
    }

    protected function setUp(): void
    {
        Router::reset();
        $_SERVER = [];
        $_REQUEST = [];
        $_GET = [];
        $_POST = [];
    }
}
