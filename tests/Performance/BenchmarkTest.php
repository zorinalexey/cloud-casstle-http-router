<?php

declare(strict_types=1);

namespace CloudCastle\Http\Router\Tests\Performance;

use CloudCastle\Http\Router\Router;
use PHPUnit\Framework\TestCase;

class BenchmarkTest extends TestCase
{
    private const ITERATIONS = 10000;
    private Router $router;

    protected function setUp(): void
    {
        $this->router = new Router();
    }

    public function testRouteRegistrationPerformance(): void
    {
        $start = microtime(true);

        for ($i = 0; $i < self::ITERATIONS; $i++) {
            $this->router->get("/users/{$i}", function () {});
        }

        $duration = microtime(true) - $start;
        $perSecond = self::ITERATIONS / $duration;

        
        // Performance test
        // Stats: removed echo for clean output
        // Stats: removed echo for clean output
        // Stats: removed echo for clean output
        // Stats: removed echo for clean output

        $this->assertLessThan(5, $duration, "Route registration should complete in less than 5 seconds");
    }

    public function testRouteMatchingPerformance(): void
    {
        // Register routes
        for ($i = 0; $i < 1000; $i++) {
            $this->router->get("/route{$i}/users/{id}", function () {});
        }

        $start = microtime(true);

        for ($i = 0; $i < self::ITERATIONS; $i++) {
            try {
                $routeIndex = $i % 1000;
                $this->router->dispatch("/route{$routeIndex}/users/123", 'GET');
            } catch (\Exception $e) {
                // Ignore
            }
        }

        $duration = microtime(true) - $start;
        $perSecond = self::ITERATIONS / $duration;

        
        // Performance test
        // Stats: removed echo for clean output
        // Stats: removed echo for clean output
        // Stats: removed echo for clean output
        // Stats: removed echo for clean output

        $this->assertLessThan(2, $duration, "Route matching should complete in less than 2 seconds");
    }

    public function testCachedRoutePerformance(): void
    {
        $cacheDir = sys_get_temp_dir() . '/router-benchmark-' . uniqid();
        $this->router->enableCache($cacheDir);

        // Register and compile routes
        for ($i = 0; $i < 1000; $i++) {
            $this->router->get("/route{$i}", function () {});
        }
        $this->router->compile(true);

        // Create new router and load from cache
        $cachedRouter = new Router();
        $cachedRouter->enableCache($cacheDir);

        $start = microtime(true);
        $cachedRouter->loadFromCache();
        $loadDuration = microtime(true) - $start;

        
        // Performance test
        // Stats: removed echo for clean output
        // Stats: removed echo for clean output

        // Test dispatch performance with cached routes
        $start = microtime(true);
        for ($i = 0; $i < self::ITERATIONS; $i++) {
            try {
                $routeIndex = $i % 1000;
                $cachedRouter->dispatch("/route{$routeIndex}", 'GET');
            } catch (\Exception $e) {
                // Ignore
            }
        }
        $dispatchDuration = microtime(true) - $start;

        // Stats: removed echo for clean output

        // Cleanup
        $cachedRouter->clearCache();
        @rmdir($cacheDir);

        $this->assertLessThan(0.1, $loadDuration, "Cache loading should complete in less than 100ms");
    }

    public function testMemoryUsage(): void
    {
        $memoryBefore = memory_get_usage(true);

        for ($i = 0; $i < 1000; $i++) {
            $this->router->get("/route{$i}/users/{id}/posts/{postId}", function () {});
        }

        $memoryAfter = memory_get_usage(true);
        $memoryUsed = $memoryAfter - $memoryBefore;
        $perRoute = $memoryUsed / 1000;

        

        // Stats: removed echo for clean output
        // Stats: removed echo for clean output
        // Stats: removed echo for clean output

        $this->assertLessThan(10 * 1024 * 1024, $memoryUsed, "Memory usage should be less than 10MB for 1000 routes");
    }

    public function testGroupPerformance(): void
    {
        $start = microtime(true);

        for ($i = 0; $i < 100; $i++) {
            $this->router->group(['prefix' => "/group{$i}"], function ($router) {
                for ($j = 0; $j < 100; $j++) {
                    $router->get("/route{$j}", function () {});
                }
            });
        }

        $duration = microtime(true) - $start;
        $totalRoutes = 10000;

        
        // Performance test
        // Stats: removed echo for clean output
        // Stats: removed echo for clean output
        // Stats: removed echo for clean output
        // Stats: removed echo for clean output
        // Stats: removed echo for clean output

        $this->assertLessThan(5, $duration);
    }
}

