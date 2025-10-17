<?php

declare(strict_types=1);

namespace CloudCastle\Http\Router\Tests\Stress;

use CloudCastle\Http\Router\Router;

/**
 * Stress testing to find breaking points
 * Run with: php tests/Stress/StressTest.php.
 */
class StressTest
{
    private const MAX_ROUTES = 100000;

    // Увеличено в 2 раза
    private const MAX_REQUESTS = 200000;  // Увеличено в 2 раза

    public function run(): void
    {
        echo "===============================================\n";
        echo "HTTP Router Stress Testing\n";
        echo "===============================================\n\n";

        $this->testMaximumRoutes();
        $this->testDeepNesting();
        $this->testLongUriPatterns();
        $this->testExtremeConcurrency();
        $this->testMemoryLimits();
    }

    private function testMaximumRoutes(): void
    {
        echo "Test 1: Maximum Routes Capacity\n";
        echo str_repeat('-', 50) . "\n";

        $router = new Router();
        $memoryStart = memory_get_usage(true);

        $maxRoutes = 0;
        $start = microtime(true);

        try {
            for ($i = 0; $i < self::MAX_ROUTES; $i++) {
                $router->get(sprintf('/route%d/users/{id}/posts/{postId}', $i), function (): void {
                });
                $maxRoutes++;

                if ($i % 10000 === 0 && $i > 0) {
                    $currentMemory = memory_get_usage(true);
                    $memoryUsed = $currentMemory - $memoryStart;
                    echo sprintf('  %d routes registered, memory: ', $i) . $this->formatBytes($memoryUsed) . "\n";
                }
            }
        } catch (\Throwable $throwable) {
            echo sprintf('  Failed at %d routes: ', $maxRoutes) . $throwable->getMessage() . "\n";
        }

        $duration = microtime(true) - $start;
        $memoryEnd = memory_get_usage(true);
        $memoryUsed = $memoryEnd - $memoryStart;

        echo "\n  Maximum routes handled: " . number_format($maxRoutes) . "\n";
        echo '  Registration time: ' . number_format($duration, 2) . "s\n";
        echo '  Memory used: ' . $this->formatBytes($memoryUsed) . "\n";
        echo '  Per route: ' . $this->formatBytes($memoryUsed / $maxRoutes) . "\n\n";
    }

    private function testDeepNesting(): void
    {
        echo "Test 2: Deep Group Nesting\n";
        echo str_repeat('-', 50) . "\n";

        $router = new Router();
        $maxDepth = 0;

        try {
            $this->createNestedGroups($router, 50);
            $maxDepth = 50;
        } catch (\Throwable $throwable) {
            echo sprintf('  Failed at depth %d: ', $maxDepth) . $throwable->getMessage() . "\n";
        }

        echo sprintf('  Maximum nesting depth: %s%s', $maxDepth, PHP_EOL);
        echo '  Routes created: ' . count($router->getRoutes()) . "\n\n";
    }

    private function createNestedGroups(Router $router, int $depth, int $current = 0): void
    {
        if ($current >= $depth) {
            $router->get('/endpoint', function (): void {
            });

            return;
        }

        $router->group(['prefix' => '/level' . $current], function ($router) use ($depth, $current): void {
            $this->createNestedGroups($router, $depth, $current + 1);
        });
    }

    private function testLongUriPatterns(): void
    {
        echo "Test 3: Long URI Patterns\n";
        echo str_repeat('-', 50) . "\n";

        $router = new Router();

        // Create very long URI pattern
        $segments = [];
        for ($i = 0; $i < 100; $i++) {
            $segments[] = 'segment' . $i;
            $segments[] = sprintf('{param%d}', $i);
        }

        $longUri = '/' . implode('/', $segments);

        $start = microtime(true);
        $router->get($longUri, function (): void {
        });
        $registrationTime = microtime(true) - $start;

        echo '  URI length: ' . strlen($longUri) . " characters\n";
        echo '  Segments: ' . count($segments) . "\n";
        echo '  Registration time: ' . number_format($registrationTime * 1000, 2) . "ms\n";

        // Test matching
        $testUri = str_replace(
            array_map(fn ($i): string => sprintf('{param%s}', $i), range(0, 99)),
            array_map(fn ($i): string => 'value' . $i, range(0, 99)),
            $longUri
        );

        $start = microtime(true);

        try {
            $router->dispatch($testUri, 'GET');
            $matchTime = microtime(true) - $start;
            echo '  Match time: ' . number_format($matchTime * 1000, 2) . "ms\n\n";
        } catch (\Exception $exception) {
            echo '  Match failed: ' . $exception->getMessage() . "\n\n";
        }
    }

    private function testExtremeConcurrency(): void
    {
        echo "Test 4: Extreme Request Volume\n";
        echo str_repeat('-', 50) . "\n";

        $router = new Router();

        // Register routes
        for ($i = 0; $i < 1000; $i++) {
            $router->get('/route' . $i, function (): void {
            });
        }

        $requests = self::MAX_REQUESTS;
        $successCount = 0;
        $errorCount = 0;

        $start = microtime(true);

        for ($i = 0; $i < $requests; $i++) {
            try {
                $router->dispatch('/route' . ($i % 1000), 'GET');
                $successCount++;
            } catch (\Exception) {
                $errorCount++;
            }

            if ($i % 10000 === 0 && $i > 0) {
                $elapsed = microtime(true) - $start;
                $rps = $i / $elapsed;
                echo sprintf('  %d requests processed, ', $i) . number_format($rps, 0) . " req/sec\n";
            }
        }

        $duration = microtime(true) - $start;
        $requestsPerSecond = $requests / $duration;

        echo "\n  Total requests: " . number_format($requests) . "\n";
        echo '  Successful: ' . number_format($successCount) . "\n";
        echo sprintf('  Errors: %d%s', $errorCount, PHP_EOL);
        echo '  Duration: ' . number_format($duration, 2) . "s\n";
        echo '  Requests/sec: ' . number_format($requestsPerSecond, 0) . "\n";
        echo '  Avg time: ' . number_format(($duration / $requests) * 1000, 4) . "ms\n\n";
    }

    private function testMemoryLimits(): void
    {
        $stopPercent = 80;
        $maxLimit = 1024 * 2;
        echo "Test 5: Memory Limit Stress\n";
        echo str_repeat('-', 50) . "\n";
        
        ini_set('memory_limit', "{$maxLimit}M");
        $memoryLimit = ini_get('memory_limit');
        echo sprintf('  PHP memory limit: %s%s', $memoryLimit, PHP_EOL);

        $router = new Router();
        $routeCount = 0;
        $memoryStart = memory_get_usage(true);

        try {
            while (true) {
                // Create routes with complex patterns
                $router->get("/complex/{id:\d+}/data/{uuid:[a-f0-9-]+}/file/{name}", function (): void {
                })
                    ->name('route.' . $routeCount)
                    ->tag(['tag1', 'tag2', 'tag3'])
                    ->middleware(['auth', 'throttle', 'cors']);

                $routeCount++;

                if ($routeCount % 5000 === 0) {
                    $currentMemory = memory_get_usage(true);
                    $used = $currentMemory - $memoryStart;
                    $percent = ($currentMemory / $this->parseMemoryLimit($memoryLimit)) * 100;

                    echo sprintf('  Routes: %d, Memory: ', $routeCount) . $this->formatBytes($used);
                    echo ' (' . number_format($percent, 1) . "%)\n";

                    if ($percent > $stopPercent) {
                        echo "  Stopping at $stopPercent% memory usage\n";
                        break;
                    }
                }
            }
        } catch (\Throwable $throwable) {
            echo sprintf('  Error at %d routes: ', $routeCount) . $throwable->getMessage() . "\n";
        }

        $memoryEnd = memory_get_usage(true);
        $memoryUsed = $memoryEnd - $memoryStart;

        echo "\n  Final route count: " . number_format($routeCount) . "\n";
        echo '  Memory used: ' . $this->formatBytes($memoryUsed) . "\n";
        echo '  Per route: ' . $this->formatBytes($memoryUsed / $routeCount) . "\n\n";
    }

    private function formatBytes(int|float $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;
        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }

        return number_format($bytes, 2) . ' ' . $units[$i];
    }

    private function parseMemoryLimit(string $limit): int
    {
        $limit = trim($limit);
        $last = strtolower($limit[strlen($limit) - 1]);
        $value = (int) $limit;

        switch ($last) {
            case 'g':
                $value *= 1024;
                // fall through
                // no break
            case 'm':
                $value *= 1024;
                // fall through
                // no break
            case 'k':
                $value *= 1024;
        }

        return $value;
    }
}

// Run if executed directly
if (PHP_SAPI === 'cli' && isset($argv[0]) && realpath($argv[0]) === realpath(__FILE__)) {
    require_once __DIR__ . '/../../vendor/autoload.php';
    $test = new StressTest();
    $test->run();
}
