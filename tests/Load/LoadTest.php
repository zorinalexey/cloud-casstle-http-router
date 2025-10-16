<?php

declare(strict_types=1);

namespace CloudCastle\Http\Router\Tests\Load;

use CloudCastle\Http\Router\Router;

/**
 * Load testing for the router
 * Run with: php tests/Load/LoadTest.php
 */
class LoadTest
{
    private Router $router;
    private array $stats = [];

    public function run(): void
    {
        echo "===============================================\n";
        echo "HTTP Router Load Testing\n";
        echo "===============================================\n\n";

        $this->testLightLoad();
        $this->testMediumLoad();
        $this->testHeavyLoad();
        $this->testConcurrentPatterns();
        $this->testCachedVsUncached();

        $this->printSummary();
    }

    private function testLightLoad(): void
    {
        echo "Test 1: Light Load (100 routes, 1,000 requests)\n";
        echo str_repeat("-", 50) . "\n";

        $this->router = new Router();
        $this->registerRoutes(100);

        $requests = 1000;
        $duration = $this->simulateRequests($requests);

        $requestsPerSecond = $requests / $duration;
        $avgResponseTime = ($duration / $requests) * 1000;

        echo "  Routes registered: 100\n";
        echo "  Total requests: {$requests}\n";
        echo "  Duration: " . number_format($duration, 4) . "s\n";
        echo "  Requests/sec: " . number_format($requestsPerSecond, 0) . "\n";
        echo "  Avg response time: " . number_format($avgResponseTime, 2) . "ms\n";
        echo "  Memory peak: " . $this->formatBytes(memory_get_peak_usage(true)) . "\n\n";

        $this->stats['light'] = [
            'rps' => $requestsPerSecond,
            'avg_time' => $avgResponseTime,
        ];
    }

    private function testMediumLoad(): void
    {
        echo "Test 2: Medium Load (500 routes, 5,000 requests)\n";
        echo str_repeat("-", 50) . "\n";

        $this->router = new Router();
        $this->registerRoutes(500);

        $requests = 5000;
        $duration = $this->simulateRequests($requests);

        $requestsPerSecond = $requests / $duration;
        $avgResponseTime = ($duration / $requests) * 1000;

        echo "  Routes registered: 500\n";
        echo "  Total requests: {$requests}\n";
        echo "  Duration: " . number_format($duration, 4) . "s\n";
        echo "  Requests/sec: " . number_format($requestsPerSecond, 0) . "\n";
        echo "  Avg response time: " . number_format($avgResponseTime, 2) . "ms\n";
        echo "  Memory peak: " . $this->formatBytes(memory_get_peak_usage(true)) . "\n\n";

        $this->stats['medium'] = [
            'rps' => $requestsPerSecond,
            'avg_time' => $avgResponseTime,
        ];
    }

    private function testHeavyLoad(): void
    {
        echo "Test 3: Heavy Load (1,000 routes, 10,000 requests)\n";
        echo str_repeat("-", 50) . "\n";

        $this->router = new Router();
        $this->registerRoutes(1000);

        $requests = 10000;
        $duration = $this->simulateRequests($requests);

        $requestsPerSecond = $requests / $duration;
        $avgResponseTime = ($duration / $requests) * 1000;

        echo "  Routes registered: 1,000\n";
        echo "  Total requests: {$requests}\n";
        echo "  Duration: " . number_format($duration, 4) . "s\n";
        echo "  Requests/sec: " . number_format($requestsPerSecond, 0) . "\n";
        echo "  Avg response time: " . number_format($avgResponseTime, 2) . "ms\n";
        echo "  Memory peak: " . $this->formatBytes(memory_get_peak_usage(true)) . "\n\n";

        $this->stats['heavy'] = [
            'rps' => $requestsPerSecond,
            'avg_time' => $avgResponseTime,
        ];
    }

    private function testConcurrentPatterns(): void
    {
        echo "Test 4: Concurrent Access Patterns\n";
        echo str_repeat("-", 50) . "\n";

        $this->router = new Router();
        $this->registerComplexRoutes();

        $patterns = [
            '/api/v1/users/123',
            '/api/v1/posts/456/comments/789',
            '/admin/dashboard',
            '/public/assets/image.jpg',
        ];

        $requests = 5000;
        $start = microtime(true);

        for ($i = 0; $i < $requests; $i++) {
            $pattern = $patterns[$i % count($patterns)];
            try {
                $this->router->dispatch($pattern, 'GET');
            } catch (\Exception $e) {
                // Continue
            }
        }

        $duration = microtime(true) - $start;
        $requestsPerSecond = $requests / $duration;

        echo "  Pattern variations: " . count($patterns) . "\n";
        echo "  Total requests: {$requests}\n";
        echo "  Requests/sec: " . number_format($requestsPerSecond, 0) . "\n";
        echo "  Avg time: " . number_format(($duration / $requests) * 1000, 2) . "ms\n\n";
    }

    private function testCachedVsUncached(): void
    {
        echo "Test 5: Cached vs Uncached Performance\n";
        echo str_repeat("-", 50) . "\n";

        $cacheDir = sys_get_temp_dir() . '/router-load-test-' . uniqid();

        // Test without cache
        $uncachedRouter = new Router();
        $this->registerRoutes(500, $uncachedRouter);

        $start = microtime(true);
        for ($i = 0; $i < 1000; $i++) {
            try {
                $uncachedRouter->dispatch('/route' . ($i % 500), 'GET');
            } catch (\Exception $e) {
            }
        }
        $uncachedDuration = microtime(true) - $start;

        // Test with cache
        $cachedRouter = new Router();
        $cachedRouter->enableCache($cacheDir);
        $this->registerRoutes(500, $cachedRouter);
        $cachedRouter->compile(true);

        $loadRouter = new Router();
        $loadRouter->enableCache($cacheDir);
        $loadRouter->loadFromCache();

        $start = microtime(true);
        for ($i = 0; $i < 1000; $i++) {
            try {
                $loadRouter->dispatch('/route' . ($i % 500), 'GET');
            } catch (\Exception $e) {
            }
        }
        $cachedDuration = microtime(true) - $start;

        $improvement = (($uncachedDuration - $cachedDuration) / $uncachedDuration) * 100;

        echo "  Uncached: " . number_format(1000 / $uncachedDuration, 0) . " req/sec\n";
        echo "  Cached: " . number_format(1000 / $cachedDuration, 0) . " req/sec\n";
        echo "  Improvement: " . number_format($improvement, 1) . "%\n\n";

        $loadRouter->clearCache();
        @rmdir($cacheDir);
    }

    private function registerRoutes(int $count, ?Router $router = null): void
    {
        $router = $router ?? $this->router;

        for ($i = 0; $i < $count; $i++) {
            $router->get("/route{$i}", function () {
                return 'response';
            });
        }
    }

    private function registerComplexRoutes(): void
    {
        $this->router->group(['prefix' => '/api/v1'], function ($router) {
            $router->get('/users/{id}', fn() => 'user');
            $router->get('/posts/{postId}/comments/{commentId}', fn() => 'comment');
        });

        $this->router->group(['prefix' => '/admin'], function ($router) {
            $router->get('/dashboard', fn() => 'dashboard');
        });

        $this->router->get('/public/assets/{file}', fn() => 'asset');
    }

    private function simulateRequests(int $count): float
    {
        $routes = $this->router->getRoutes();
        $routeCount = count($routes);

        $start = microtime(true);

        for ($i = 0; $i < $count; $i++) {
            $routeIndex = $i % $routeCount;
            try {
                $this->router->dispatch("/route{$routeIndex}", 'GET');
            } catch (\Exception $e) {
                // Continue
            }
        }

        return microtime(true) - $start;
    }

    private function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;
        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }
        return number_format($bytes, 2) . ' ' . $units[$i];
    }

    private function printSummary(): void
    {
        echo "===============================================\n";
        echo "Summary\n";
        echo "===============================================\n\n";

        foreach ($this->stats as $test => $data) {
            echo ucfirst($test) . " Load:\n";
            echo "  " . number_format($data['rps'], 0) . " requests/sec\n";
            echo "  " . number_format($data['avg_time'], 2) . "ms avg response time\n\n";
        }

        echo "Test completed successfully!\n";
    }
}

// Run if executed directly
if (php_sapi_name() === 'cli' && isset($argv[0]) && realpath($argv[0]) === realpath(__FILE__)) {
    require_once __DIR__ . '/../../vendor/autoload.php';
    $test = new LoadTest();
    $test->run();
}
