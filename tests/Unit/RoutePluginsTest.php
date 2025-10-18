<?php

declare(strict_types=1);

namespace CloudCastle\Http\Router\Tests\Unit;

use CloudCastle\Http\Router\Plugin\AnalyticsPlugin;
use CloudCastle\Http\Router\Plugin\LoggerPlugin;
use CloudCastle\Http\Router\Router;
use PHPUnit\Framework\TestCase;

class RoutePluginsTest extends TestCase
{
    private Router $router;

    protected function setUp(): void
    {
        $this->router = new Router();
    }

    public function testRouteCanHavePlugin(): void
    {
        $plugin = new AnalyticsPlugin();
        
        $route = $this->router->get('/test', fn() => 'test')
            ->plugin($plugin);

        $this->assertCount(1, $route->getPlugins());
        $this->assertTrue($route->hasPlugin('analytics'));
        $this->assertSame($plugin, $route->getPlugin('analytics'));
    }

    public function testRouteCanHaveMultiplePlugins(): void
    {
        $analytics = new AnalyticsPlugin();
        $logger = new LoggerPlugin(sys_get_temp_dir() . '/test.log');
        
        $route = $this->router->get('/test', fn() => 'test')
            ->plugins([$analytics, $logger]);

        $this->assertCount(2, $route->getPlugins());
        $this->assertTrue($route->hasPlugin('analytics'));
        $this->assertTrue($route->hasPlugin('logger'));
    }

    public function testRouteCanRemovePlugin(): void
    {
        $plugin = new AnalyticsPlugin();
        
        $route = $this->router->get('/test', fn() => 'test')
            ->plugin($plugin);

        $this->assertTrue($route->hasPlugin('analytics'));

        $route->removePlugin('analytics');

        $this->assertFalse($route->hasPlugin('analytics'));
        $this->assertNull($route->getPlugin('analytics'));
    }

    public function testRoutePluginsWorkDuringExecution(): void
    {
        $analytics = new AnalyticsPlugin();
        
        $route = $this->router->get('/test', fn() => ['data' => 'test'])
            ->name('test.route')
            ->plugin($analytics);

        $this->router->dispatch('/test', 'GET');
        $result = $this->router->executeRoute($route);

        $this->assertEquals(['data' => 'test'], $result);
        
        $stats = $analytics->getStatistics();
        $this->assertEquals(1, $stats['total_dispatches']);
        $this->assertArrayHasKey('test.route', $stats['route_hits']);
    }

    public function testGroupPluginsApplyToRoutes(): void
    {
        $analytics = new AnalyticsPlugin();
        
        $this->router->group(['plugins' => [$analytics]], function ($router) {
            $router->get('/users', fn() => 'users')->name('users.index');
            $router->get('/posts', fn() => 'posts')->name('posts.index');
        });

        $usersRoute = $this->router->getRouteByName('users.index');
        $postsRoute = $this->router->getRouteByName('posts.index');

        $this->assertCount(1, $usersRoute->getPlugins());
        $this->assertCount(1, $postsRoute->getPlugins());
        $this->assertTrue($usersRoute->hasPlugin('analytics'));
        $this->assertTrue($postsRoute->hasPlugin('analytics'));
    }

    public function testGroupPluginsAndRoutePluginsBothWork(): void
    {
        $groupAnalytics = new AnalyticsPlugin();
        $routeLogger = new LoggerPlugin(sys_get_temp_dir() . '/route.log');
        
        $this->router->group(['plugins' => [$groupAnalytics]], function ($router) use ($routeLogger) {
            $router->get('/test', fn() => 'test')
                ->name('test.route')
                ->plugin($routeLogger);
        });

        $route = $this->router->getRouteByName('test.route');

        $this->assertCount(2, $route->getPlugins());
        $this->assertTrue($route->hasPlugin('analytics'));
        $this->assertTrue($route->hasPlugin('logger'));
    }

    public function testNestedGroupsInheritPlugins(): void
    {
        $plugin1 = new AnalyticsPlugin();
        $plugin2 = new LoggerPlugin(sys_get_temp_dir() . '/nested.log');
        
        $this->router->group(['plugins' => [$plugin1]], function ($router) use ($plugin2) {
            $router->group(['plugins' => [$plugin2]], function ($router) {
                $router->get('/nested', fn() => 'nested')->name('nested.route');
            });
        });

        $route = $this->router->getRouteByName('nested.route');

        // Should have both plugins from outer and inner groups
        $this->assertCount(2, $route->getPlugins());
        $this->assertTrue($route->hasPlugin('analytics'));
        $this->assertTrue($route->hasPlugin('logger'));
    }

    public function testGlobalAndRoutePluginsBothExecute(): void
    {
        $globalAnalytics = new AnalyticsPlugin();
        $routeLogger = new LoggerPlugin(sys_get_temp_dir() . '/combined.log');
        
        $this->router->registerPlugin($globalAnalytics);
        
        $route = $this->router->get('/test', fn() => ['result' => 'ok'])
            ->name('combined.route')
            ->plugin($routeLogger);

        $this->router->dispatch('/test', 'GET');
        $result = $this->router->executeRoute($route);

        $this->assertEquals(['result' => 'ok'], $result);
        
        $stats = $globalAnalytics->getStatistics();
        $this->assertEquals(1, $stats['total_dispatches']);
        $this->assertGreaterThan(0, $stats['total_routes_registered']);
    }

    public function testRoutePluginCanModifyResult(): void
    {
        $modifierPlugin = new class extends \CloudCastle\Http\Router\Plugin\AbstractPlugin {
            public function getName(): string
            {
                return 'modifier';
            }

            public function afterDispatch(\CloudCastle\Http\Router\Route $route, mixed $result): mixed
            {
                if (is_array($result)) {
                    $result['modified'] = true;
                }
                return $result;
            }
        };
        
        $route = $this->router->get('/test', fn() => ['data' => 'test'])
            ->plugin($modifierPlugin);

        $this->router->dispatch('/test', 'GET');
        $result = $this->router->executeRoute($route);

        $this->assertEquals(['data' => 'test', 'modified' => true], $result);
    }

    public function testSinglePluginInGroupAttribute(): void
    {
        $plugin = new AnalyticsPlugin();
        
        // Test single plugin (not array)
        $this->router->group(['plugins' => $plugin], function ($router) {
            $router->get('/single', fn() => 'test')->name('single.route');
        });

        $route = $this->router->getRouteByName('single.route');

        $this->assertCount(1, $route->getPlugins());
        $this->assertTrue($route->hasPlugin('analytics'));
    }

    public function testFluentInterfaceForPlugins(): void
    {
        $analytics = new AnalyticsPlugin();
        $logger = new LoggerPlugin(sys_get_temp_dir() . '/fluent.log');
        
        $route = $this->router->get('/fluent', fn() => 'test')
            ->plugin($analytics)
            ->plugin($logger)
            ->name('fluent.route');

        $this->assertInstanceOf(\CloudCastle\Http\Router\Route::class, $route);
        $this->assertCount(2, $route->getPlugins());
    }

    public function testDisabledRoutePluginDoesNotExecute(): void
    {
        $plugin = new class extends \CloudCastle\Http\Router\Plugin\AbstractPlugin {
            public int $callCount = 0;

            public function getName(): string
            {
                return 'counter';
            }

            public function afterDispatch(\CloudCastle\Http\Router\Route $route, mixed $result): mixed
            {
                $this->callCount++;
                return $result;
            }
        };

        $route = $this->router->get('/test', fn() => 'test')->plugin($plugin);

        $plugin->disable();

        $this->router->dispatch('/test', 'GET');
        $this->router->executeRoute($route);

        $this->assertEquals(0, $plugin->callCount);
    }

    public function testMultipleRoutesCanSharePlugin(): void
    {
        $analytics = new AnalyticsPlugin();
        
        $route1 = $this->router->get('/route1', fn() => 'r1')
            ->name('route1')
            ->plugin($analytics);
            
        $route2 = $this->router->get('/route2', fn() => 'r2')
            ->name('route2')
            ->plugin($analytics);

        $this->router->dispatch('/route1', 'GET');
        $this->router->executeRoute($route1);

        $this->router->dispatch('/route2', 'GET');
        $this->router->executeRoute($route2);

        $stats = $analytics->getStatistics();
        $this->assertEquals(2, $stats['total_dispatches']);
        $this->assertArrayHasKey('route1', $stats['route_hits']);
        $this->assertArrayHasKey('route2', $stats['route_hits']);
    }

    public function testRoutePluginExceptionHandling(): void
    {
        $exceptionLogger = new class extends \CloudCastle\Http\Router\Plugin\AbstractPlugin {
            public ?\Throwable $caughtException = null;

            public function getName(): string
            {
                return 'exception_logger';
            }

            public function onException(\Throwable $exception): void
            {
                $this->caughtException = $exception;
            }
        };

        $route = $this->router->get('/error', function () {
            throw new \RuntimeException('Test error');
        })->plugin($exceptionLogger);

        try {
            $this->router->dispatch('/error', 'GET');
            $this->router->executeRoute($route);
            $this->fail('Exception should have been thrown');
        } catch (\RuntimeException $e) {
            $this->assertEquals('Test error', $e->getMessage());
            $this->assertNotNull($exceptionLogger->caughtException);
            $this->assertEquals('Test error', $exceptionLogger->caughtException->getMessage());
        }
    }
}

