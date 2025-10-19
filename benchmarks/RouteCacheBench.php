<?php

declare(strict_types=1);

namespace CloudCastle\Http\Router\Benchmarks;

use CloudCastle\Http\Router\Router;

/**
 * Benchmarks for Route caching performance.
 */
final class RouteCacheBench
{
    /**
     * Benchmark compiling routes to cache.
     */
    public function benchCompileRoutes(): void
    {
        $cacheDir = sys_get_temp_dir() . '/router_bench_cache_' . uniqid();
        $router = new Router();
        $router->enableCache($cacheDir);

        for ($i = 0; $i < 100; $i++) {
            $router->get("/route{$i}", fn () => "Route {$i}")->name("route.{$i}");
        }

        $router->compile();

        // Cleanup
        if (is_dir($cacheDir)) {
            array_map('unlink', glob($cacheDir . '/*') ?: []);
            rmdir($cacheDir);
        }
    }

    /**
     * Benchmark loading routes from cache.
     */
    public function benchLoadFromCache(): void
    {
        $cacheDir = sys_get_temp_dir() . '/router_bench_cache_' . uniqid();
        $router = new Router();
        $router->enableCache($cacheDir);

        // Setup: compile routes
        for ($i = 0; $i < 100; $i++) {
            $router->get("/route{$i}", fn () => "Route {$i}")->name("route.{$i}");
        }
        $router->compile();

        // Create new router and load from cache
        $newRouter = new Router();
        $newRouter->enableCache($cacheDir);
        $newRouter->loadFromCache();

        // Cleanup
        if (is_dir($cacheDir)) {
            array_map('unlink', glob($cacheDir . '/*') ?: []);
            rmdir($cacheDir);
        }
    }
}
