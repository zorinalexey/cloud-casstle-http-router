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

        echo "\n";
        echo "Route Registration Performance:\n";
        echo "  Total routes: " . self::ITERATIONS . "\n";
        echo "  Duration: " . number_format($duration, 4) . "s\n";
        echo "  Routes/sec: " . number_format($perSecond, 0) . "\n";
        echo "  Time per route: " . number_format(($duration / self::ITERATIONS) * 1000, 4) . "ms\n";

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

        echo "\n";
        echo "Route Matching Performance:\n";
        echo "  Total matches: " . self::ITERATIONS . "\n";
        echo "  Duration: " . number_format($duration, 4) . "s\n";
        echo "  Matches/sec: " . number_format($perSecond, 0) . "\n";
        echo "  Time per match: " . number_format(($duration / self::ITERATIONS) * 1000, 4) . "ms\n";

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

        echo "\n";
        echo "Cached Route Loading Performance:\n";
        echo "  Total routes: 1000\n";
        echo "  Load duration: " . number_format($loadDuration * 1000, 2) . "ms\n";

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

        echo "  Dispatch performance: " . number_format(self::ITERATIONS / $dispatchDuration, 0) . " req/sec\n";

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

        echo "\n";
        echo "Memory Usage:\n";
        echo "  Total routes: 1000\n";
        echo "  Memory used: " . number_format($memoryUsed / 1024 / 1024, 2) . " MB\n";
        echo "  Per route: " . number_format($perRoute / 1024, 2) . " KB\n";

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

        echo "\n";
        echo "Group Performance:\n";
        echo "  Total groups: 100\n";
        echo "  Routes per group: 100\n";
        echo "  Total routes: {$totalRoutes}\n";
        echo "  Duration: " . number_format($duration, 4) . "s\n";
        echo "  Routes/sec: " . number_format($totalRoutes / $duration, 0) . "\n";

        $this->assertLessThan(5, $duration);
    }
}

