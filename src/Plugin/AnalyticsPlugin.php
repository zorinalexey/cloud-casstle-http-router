<?php

declare(strict_types=1);

namespace CloudCastle\Http\Router\Plugin;

use CloudCastle\Http\Router\Route;

/**
 * Analytics plugin for collecting router statistics.
 */
class AnalyticsPlugin extends AbstractPlugin
{
    /** @var array<string, int> */
    private array $routeHits = [];

    /** @var array<string, int> */
    private array $methodStats = [];

    /** @var array<string, float> */
    private array $executionTimes = [];

    private int $totalDispatches = 0;

    private int $totalExceptions = 0;

    private int $routesRegistered = 0;

    /**
     * Get plugin name.
     */
    public function getName(): string
    {
        return 'analytics';
    }

    /**
     * Called when route is registered.
     */
    public function onRouteRegistered(Route $route): void
    {
        $this->routesRegistered++;
    }

    /**
     * Called before route dispatch.
     */
    public function beforeDispatch(Route $route, string $uri, string $method): void
    {
        $this->totalDispatches++;

        // Count route hits
        $routeName = $route->getName() ?? $route->getUri();
        $this->routeHits[$routeName] = ($this->routeHits[$routeName] ?? 0) + 1;

        // Count method stats
        $this->methodStats[$method] = ($this->methodStats[$method] ?? 0) + 1;

        // Store start time for execution time tracking
        $this->executionTimes[$routeName . '_start'] = microtime(true);
    }

    /**
     * Called after route dispatch.
     */
    public function afterDispatch(Route $route, mixed $result): mixed
    {
        // Calculate execution time
        $routeName = $route->getName() ?? $route->getUri();
        $startKey = $routeName . '_start';

        if (isset($this->executionTimes[$startKey])) {
            $executionTime = microtime(true) - $this->executionTimes[$startKey];
            $this->executionTimes[$routeName] = $executionTime;
            unset($this->executionTimes[$startKey]);
        }

        return $result;
    }

    /**
     * Called when exception occurs.
     */
    public function onException(\Exception $exception): void
    {
        $this->totalExceptions++;
    }

    /**
     * Get statistics.
     *
     * @return array<string, mixed>
     */
    public function getStatistics(): array
    {
        return [
            'total_dispatches' => $this->totalDispatches,
            'total_exceptions' => $this->totalExceptions,
            'total_routes_registered' => $this->routesRegistered,
            'route_hits' => $this->routeHits,
            'method_stats' => $this->methodStats,
            'execution_times' => $this->getExecutionTimes(),
            'most_popular_route' => $this->getMostPopularRoute(),
            'most_used_method' => $this->getMostUsedMethod(),
            'average_execution_time' => $this->getAverageExecutionTime(),
        ];
    }

    /**
     * Get route hits.
     *
     * @return array<string, int>
     */
    public function getRouteHits(): array
    {
        return $this->routeHits;
    }

    /**
     * Get method statistics.
     *
     * @return array<string, int>
     */
    public function getMethodStats(): array
    {
        return $this->methodStats;
    }

    /**
     * Get execution times (without _start entries).
     *
     * @return array<string, float>
     */
    public function getExecutionTimes(): array
    {
        return array_filter(
            $this->executionTimes,
            fn ($key): bool => !str_ends_with($key, '_start'),
            ARRAY_FILTER_USE_KEY
        );
    }

    /**
     * Get most popular route.
     */
    public function getMostPopularRoute(): ?string
    {
        if ($this->routeHits === []) {
            return null;
        }

        arsort($this->routeHits);

        return array_key_first($this->routeHits);
    }

    /**
     * Get most used HTTP method.
     */
    public function getMostUsedMethod(): ?string
    {
        if ($this->methodStats === []) {
            return null;
        }

        arsort($this->methodStats);

        return array_key_first($this->methodStats);
    }

    /**
     * Get average execution time.
     */
    public function getAverageExecutionTime(): float
    {
        $times = $this->getExecutionTimes();

        if ($times === []) {
            return 0.0;
        }

        return array_sum($times) / count($times);
    }

    /**
     * Reset all statistics.
     */
    public function reset(): void
    {
        $this->routeHits = [];
        $this->methodStats = [];
        $this->executionTimes = [];
        $this->totalDispatches = 0;
        $this->totalExceptions = 0;
        $this->routesRegistered = 0;
    }
}
