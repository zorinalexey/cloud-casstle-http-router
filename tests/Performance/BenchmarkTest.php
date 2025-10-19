<?php

declare(strict_types=1);

namespace CloudCastle\Http\Router\Tests\Performance;

use CloudCastle\Http\Router\Router;
use Exception;
use PHPUnit\Framework\TestCase;

class BenchmarkTest extends TestCase
{
    private const ITERATIONS = 5000;

    private Router $router;

    public function testRouteRegistrationPerformance(): void
    {
        $start = microtime(true);

        for ($i = 0; $i < self::ITERATIONS; $i++) {
            $this->router->get('/users/' . $i, function (): void {
            });
        }

        $duration = microtime(true) - $start;
        $perSecond = self::ITERATIONS / $duration;

        $this->assertLessThan(5, $duration, sprintf(
            'Route registration should complete in less than 5 seconds (actual: %.4fs, %d routes/sec)',
            $duration,
            (int) $perSecond
        ));
    }

    public function testRouteMatchingPerformance(): void
    {
        // Register routes
        for ($i = 0; $i < 1000; $i++) {
            $this->router->get(sprintf('/route%d/users/{id}', $i), function (): void {
            });
        }

        $start = microtime(true);

        for ($i = 0; $i < self::ITERATIONS; $i++) {
            try {
                $routeIndex = $i % 1000;
                $this->router->dispatch(sprintf('/route%d/users/123', $routeIndex), 'GET');
            } catch (Exception) {
                // Ignore
            }
        }

        $duration = microtime(true) - $start;
        $perSecond = self::ITERATIONS / $duration;

        $this->assertLessThan(30, $duration, sprintf(
            'Route matching should complete in less than 30 seconds (actual: %.4fs, %d matches/sec)',
            $duration,
            (int) $perSecond
        ));
    }

    public function testCachedRoutePerformance(): void
    {
        $cacheDir = sys_get_temp_dir() . '/router-benchmark-' . uniqid();
        $this->router->enableCache($cacheDir);

        // Register and compile routes
        for ($i = 0; $i < 1000; $i++) {
            $this->router->get('/route' . $i, function (): void {
            });
        }

        $this->router->compile(true);

        // Create new router and load from cache
        $cachedRouter = new Router();
        $cachedRouter->enableCache($cacheDir);

        $start = microtime(true);
        $cachedRouter->loadFromCache();
        $loadDuration = microtime(true) - $start;

        // Test dispatch performance with cached routes
        $start = microtime(true);
        for ($i = 0; $i < self::ITERATIONS; $i++) {
            try {
                $routeIndex = $i % 1000;
                $cachedRouter->dispatch('/route' . $routeIndex, 'GET');
            } catch (Exception) {
                // Ignore
            }
        }

        $dispatchDuration = microtime(true) - $start;
        $rps = self::ITERATIONS / $dispatchDuration;

        // Cleanup
        $cachedRouter->clearCache();
        @rmdir($cacheDir);

        $this->assertLessThan(0.1, $loadDuration, sprintf(
            'Cache loading should complete in less than 100ms (actual: %.2fms, %d req/sec dispatch)',
            $loadDuration * 1000,
            (int) $rps
        ));
    }

    public function testMemoryUsage(): void
    {
        $memoryBefore = memory_get_usage(true);

        for ($i = 0; $i < 1000; $i++) {
            $this->router->get(sprintf('/route%d/users/{id}/posts/{postId}', $i), function (): void {
            });
        }

        $memoryAfter = memory_get_usage(true);
        $memoryUsed = $memoryAfter - $memoryBefore;
        $perRoute = $memoryUsed / 1000;

        $this->assertLessThan(10 * 1024 * 1024, $memoryUsed, sprintf(
            'Memory usage should be less than 10MB for 1000 routes (actual: %.2f MB, %.2f KB per route)',
            $memoryUsed / 1024 / 1024,
            $perRoute / 1024
        ));
    }

    public function testGroupPerformance(): void
    {
        $start = microtime(true);

        for ($i = 0; $i < 100; $i++) {
            $this->router->group(['prefix' => '/group' . $i], function ($router): void {
                for ($j = 0; $j < 100; $j++) {
                    $router->get('/route' . $j, function (): void {
                    });
                }
            });
        }

        $duration = microtime(true) - $start;
        $totalRoutes = 10000;
        $rps = $totalRoutes / $duration;

        $this->assertLessThan(5, $duration, sprintf(
            'Should create 10000 routes in under 5 seconds (actual: %.4fs, %d routes/sec)',
            $duration,
            (int) $rps
        ));
    }

    protected function setUp(): void
    {
        $this->router = new Router();
    }
}
