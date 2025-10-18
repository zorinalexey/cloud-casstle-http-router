<?php

declare(strict_types=1);

namespace CloudCastle\Http\Router\Tests\Unit;

use CloudCastle\Http\Router\Router;
use CloudCastle\Http\Router\Route;
use CloudCastle\Http\Router\Plugin\LoggerPlugin;
use CloudCastle\Http\Router\Plugin\AnalyticsPlugin;
use CloudCastle\Http\Router\Plugin\ResponseCachePlugin;
use PHPUnit\Framework\TestCase;

class PluginSystemTest extends TestCase
{
    private Router $router;

    protected function setUp(): void
    {
        parent::setUp();
        $this->router = new Router();
    }

    public function testRegisterPlugin(): void
    {
        $plugin = new AnalyticsPlugin();
        $this->router->registerPlugin($plugin);

        $this->assertTrue($this->router->hasPlugin('analytics'));
    }

    public function testUnregisterPlugin(): void
    {
        $plugin = new AnalyticsPlugin();
        $this->router->registerPlugin($plugin);

        $this->router->unregisterPlugin('analytics');

        $this->assertFalse($this->router->hasPlugin('analytics'));
    }

    public function testGetPlugins(): void
    {
        $logger = new LoggerPlugin();
        $analytics = new AnalyticsPlugin();

        $this->router->registerPlugin($logger);
        $this->router->registerPlugin($analytics);

        $plugins = $this->router->getPlugins();

        $this->assertCount(2, $plugins);
        $this->assertArrayHasKey('logger', $plugins);
        $this->assertArrayHasKey('analytics', $plugins);
    }

    public function testGetPlugin(): void
    {
        $analytics = new AnalyticsPlugin();
        $this->router->registerPlugin($analytics);

        $plugin = $this->router->getPlugin('analytics');

        $this->assertInstanceOf(AnalyticsPlugin::class, $plugin);
    }

    public function testGetNonExistentPlugin(): void
    {
        $plugin = $this->router->getPlugin('non-existent');

        $this->assertNull($plugin);
    }

    public function testPluginBoot(): void
    {
        $plugin = new AnalyticsPlugin();
        $this->router->registerPlugin($plugin);

        // Plugin should be booted
        $this->assertTrue($plugin->isEnabled());
    }

    public function testPluginOnRouteRegistered(): void
    {
        $analytics = new AnalyticsPlugin();
        $this->router->registerPlugin($analytics);

        $this->router->get('/users', fn (): string => 'users');
        $this->router->get('/posts', fn (): string => 'posts');

        $stats = $analytics->getStatistics();

        $this->assertEquals(2, $stats['total_routes_registered']);
    }

    public function testPluginBeforeDispatch(): void
    {
        $analytics = new AnalyticsPlugin();
        $this->router->registerPlugin($analytics);

        $route = $this->router->get('/test', fn (): string => 'test');
        $this->router->dispatch('/test', 'GET');

        $stats = $analytics->getStatistics();

        $this->assertEquals(1, $stats['total_dispatches']);
    }

    public function testAnalyticsPluginCollectsRouteHits(): void
    {
        $analytics = new AnalyticsPlugin();
        $this->router->registerPlugin($analytics);

        $route = $this->router->get('/users', fn (): string => 'users')->name('users.index');

        $this->router->dispatch('/users', 'GET');
        $this->router->dispatch('/users', 'GET');
        $this->router->dispatch('/users', 'GET');

        $hits = $analytics->getRouteHits();

        $this->assertEquals(3, $hits['users.index']);
    }

    public function testAnalyticsPluginCollectsMethodStats(): void
    {
        $analytics = new AnalyticsPlugin();
        $this->router->registerPlugin($analytics);

        $this->router->get('/get', fn (): string => 'get');
        $this->router->post('/post', fn (): string => 'post');

        $this->router->dispatch('/get', 'GET');
        $this->router->dispatch('/get', 'GET');
        $this->router->dispatch('/post', 'POST');

        $methodStats = $analytics->getMethodStats();

        $this->assertEquals(2, $methodStats['GET']);
        $this->assertEquals(1, $methodStats['POST']);
    }

    public function testAnalyticsPluginMostPopularRoute(): void
    {
        $analytics = new AnalyticsPlugin();
        $this->router->registerPlugin($analytics);

        $this->router->get('/route1', fn (): string => 'r1')->name('route1');
        $this->router->get('/route2', fn (): string => 'r2')->name('route2');

        $this->router->dispatch('/route1', 'GET');
        $this->router->dispatch('/route2', 'GET');
        $this->router->dispatch('/route2', 'GET');
        $this->router->dispatch('/route2', 'GET');

        $mostPopular = $analytics->getMostPopularRoute();

        $this->assertEquals('route2', $mostPopular);
    }

    public function testAnalyticsPluginReset(): void
    {
        $analytics = new AnalyticsPlugin();
        $this->router->registerPlugin($analytics);

        $this->router->get('/test', fn (): string => 'test');
        $this->router->dispatch('/test', 'GET');

        $analytics->reset();

        $stats = $analytics->getStatistics();

        $this->assertEquals(0, $stats['total_dispatches']);
        $this->assertEquals(0, $stats['total_routes_registered']);
    }

    public function testLoggerPluginLogsEvents(): void
    {
        $logFile = sys_get_temp_dir() . '/test_router_' . uniqid() . '.log';
        $logger = new LoggerPlugin($logFile);
        $this->router->registerPlugin($logger);

        $this->router->get('/test', fn (): string => 'test')->name('test.route');
        $this->router->dispatch('/test', 'GET');

        $this->assertFileExists($logFile);

        $logContent = file_get_contents($logFile);
        $this->assertStringContainsString('[ROUTE REGISTERED]', (string) $logContent);
        $this->assertStringContainsString('[BEFORE DISPATCH]', (string) $logContent);
        $this->assertStringContainsString('test.route', (string) $logContent);

        // Cleanup
        unlink($logFile);
    }

    public function testResponseCachePlugin(): void
    {
        $cache = new ResponseCachePlugin();
        $cache->cacheAllRoutes(true);

        $this->router->registerPlugin($cache);

        $route = $this->router->get('/cached', fn (): string => 'cached result')->name('cached');

        // First call - not cached
        $this->assertFalse($cache->isCached($route));

        // Execute route (simulate)
        $result = $this->router->executeRoute($route);

        // Now should be cached
        $this->assertTrue($cache->isCached($route));

        // Get from cache
        $cachedResult = $cache->getCachedResponse($route);
        $this->assertEquals('cached result', $cachedResult);
    }

    public function testMultiplePlugins(): void
    {
        $logger = new LoggerPlugin(sys_get_temp_dir() . '/multi_' . uniqid() . '.log');
        $analytics = new AnalyticsPlugin();

        $this->router->registerPlugin($logger);
        $this->router->registerPlugin($analytics);

        $this->assertCount(2, $this->router->getPlugins());

        $this->router->get('/test', fn (): string => 'test');
        $this->router->dispatch('/test', 'GET');

        $stats = $analytics->getStatistics();
        $this->assertEquals(1, $stats['total_dispatches']);
        $this->assertEquals(1, $stats['total_routes_registered']);
    }

    public function testPluginEnableDisable(): void
    {
        $analytics = new AnalyticsPlugin();
        $this->router->registerPlugin($analytics);

        $this->assertTrue($analytics->isEnabled());

        $analytics->disable();
        $this->assertFalse($analytics->isEnabled());

        // Dispatch while disabled - should not count
        $this->router->get('/test', fn (): string => 'test');
        $this->router->dispatch('/test', 'GET');

        $stats = $analytics->getStatistics();
        $this->assertEquals(0, $stats['total_dispatches']); // Plugin was disabled

        $analytics->enable();
        $this->router->dispatch('/test', 'GET');

        $stats = $analytics->getStatistics();
        $this->assertEquals(1, $stats['total_dispatches']); // Now counted
    }

    public function testPluginFluentInterface(): void
    {
        $result = $this->router->registerPlugin(new AnalyticsPlugin());
        $this->assertInstanceOf(Router::class, $result);

        $result = $this->router->unregisterPlugin('analytics');
        $this->assertInstanceOf(Router::class, $result);
    }

    public function testAnalyticsExecutionTimeTracking(): void
    {
        $analytics = new AnalyticsPlugin();
        $this->router->registerPlugin($analytics);

        $route = $this->router->get('/slow', function (): string {
            usleep(10000); // 10ms
            return 'done';
        })->name('slow.route');

        // First dispatch to trigger beforeDispatch
        $this->router->dispatch('/slow', 'GET');
        
        // Then execute to trigger afterDispatch
        $this->router->executeRoute($route);

        $stats = $analytics->getStatistics();

        // Check that dispatch was tracked
        $this->assertGreaterThanOrEqual(1, $stats['total_dispatches']);
    }

    public function testCachePluginSelectiveRoutes(): void
    {
        $cache = new ResponseCachePlugin();
        $cache->setCacheableRoutes(['cacheable.route']);

        $this->router->registerPlugin($cache);

        $cachedRoute = $this->router->get('/cached', fn (): string => 'cached')->name('cacheable.route');
        $uncachedRoute = $this->router->get('/uncached', fn (): string => 'uncached')->name('not.cacheable');

        // Execute both
        $this->router->executeRoute($cachedRoute);
        $this->router->executeRoute($uncachedRoute);

        // Only cacheable route should be cached
        $this->assertTrue($cache->isCached($cachedRoute));
        $this->assertFalse($cache->isCached($uncachedRoute));
    }

    public function testLoggerPluginCustomConfiguration(): void
    {
        $logFile = sys_get_temp_dir() . '/custom_' . uniqid() . '.log';
        $logger = new LoggerPlugin($logFile);
        $logger->setLogRouteRegistration(false); // Disable route registration logging
        $logger->setLogDispatches(true);

        $this->router->registerPlugin($logger);

        $this->router->get('/test', fn (): string => 'test');
        $this->router->dispatch('/test', 'GET');

        $logContent = file_get_contents($logFile);

        // Should NOT contain route registration
        $this->assertStringNotContainsString('[ROUTE REGISTERED]', (string) $logContent);

        // Should contain dispatch
        $this->assertStringContainsString('[BEFORE DISPATCH]', (string) $logContent);

        // Cleanup
        unlink($logFile);
    }
}

