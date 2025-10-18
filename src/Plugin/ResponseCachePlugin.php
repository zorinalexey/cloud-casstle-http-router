<?php

declare(strict_types=1);

namespace CloudCastle\Http\Router\Plugin;

use CloudCastle\Http\Router\Route;

/**
 * Response cache plugin for caching route responses.
 */
class ResponseCachePlugin extends AbstractPlugin
{
    /** @var array<string, mixed> */
    private array $cache = [];

    /** @var array<string, int> */
    private array $cacheExpiry = [];

    private int $defaultTtl = 3600; // 1 hour

    /** @var array<string> Routes that should be cached */
    private array $cacheableRoutes = [];

    private bool $cacheAllRoutes = false;

    /**
     * @param int $defaultTtl Default cache TTL in seconds
     * @param bool $cacheAllRoutes Cache all routes by default
     */
    public function __construct(int $defaultTtl = 3600, bool $cacheAllRoutes = false)
    {
        $this->defaultTtl = $defaultTtl;
        $this->cacheAllRoutes = $cacheAllRoutes;
    }

    /**
     * Get plugin name.
     */
    public function getName(): string
    {
        return 'response_cache';
    }

    /**
     * Called after route dispatch - return cached response if available.
     */
    public function afterDispatch(Route $route, mixed $result): mixed
    {
        $cacheKey = $this->getCacheKey($route);

        // Check if this route should be cached
        if (!$this->shouldCache($route)) {
            return $result;
        }

        // Store in cache
        $this->cache[$cacheKey] = $result;
        $this->cacheExpiry[$cacheKey] = time() + $this->defaultTtl;

        return $result;
    }

    /**
     * Called before dispatch - check if cached response exists.
     * Note: This is called from beforeDispatch but returns early with cached result.
     */
    public function beforeDispatch(Route $route, string $uri, string $method): void
    {
        // This hook is used for checking, actual cache return happens in custom method
    }

    /**
     * Get cached response if exists.
     */
    public function getCachedResponse(Route $route): mixed
    {
        $cacheKey = $this->getCacheKey($route);

        if (!isset($this->cache[$cacheKey])) {
            return null;
        }

        // Check if expired
        if (isset($this->cacheExpiry[$cacheKey]) && $this->cacheExpiry[$cacheKey] < time()) {
            unset($this->cache[$cacheKey], $this->cacheExpiry[$cacheKey]);

            return null;
        }

        return $this->cache[$cacheKey];
    }

    /**
     * Check if route response is cached.
     */
    public function isCached(Route $route): bool
    {
        $cacheKey = $this->getCacheKey($route);

        if (!isset($this->cache[$cacheKey])) {
            return false;
        }

        // Check expiry
        if (isset($this->cacheExpiry[$cacheKey]) && $this->cacheExpiry[$cacheKey] < time()) {
            return false;
        }

        return true;
    }

    /**
     * Add route to cacheable list.
     */
    public function addCacheableRoute(string $routeName): self
    {
        $this->cacheableRoutes[] = $routeName;

        return $this;
    }

    /**
     * Set routes that should be cached.
     *
     * @param array<string> $routes Route names
     */
    public function setCacheableRoutes(array $routes): self
    {
        $this->cacheableRoutes = $routes;

        return $this;
    }

    /**
     * Enable caching for all routes.
     */
    public function cacheAllRoutes(bool $enabled = true): self
    {
        $this->cacheAllRoutes = $enabled;

        return $this;
    }

    /**
     * Set default TTL.
     */
    public function setDefaultTtl(int $ttl): self
    {
        $this->defaultTtl = $ttl;

        return $this;
    }

    /**
     * Clear all cache.
     */
    public function clearCache(): void
    {
        $this->cache = [];
        $this->cacheExpiry = [];
    }

    /**
     * Clear cache for specific route.
     */
    public function clearRouteCache(Route $route): void
    {
        $cacheKey = $this->getCacheKey($route);
        unset($this->cache[$cacheKey], $this->cacheExpiry[$cacheKey]);
    }

    /**
     * Get cache statistics.
     *
     * @return array<string, mixed>
     */
    public function getCacheStats(): array
    {
        $hitCount = count($this->cache);
        $expiredCount = 0;

        foreach ($this->cacheExpiry as $key => $expiry) {
            if ($expiry < time()) {
                $expiredCount++;
            }
        }

        return [
            'total_cached' => $hitCount,
            'expired' => $expiredCount,
            'active' => $hitCount - $expiredCount,
            'cache_keys' => array_keys($this->cache),
        ];
    }

    /**
     * Generate cache key for route.
     */
    private function getCacheKey(Route $route): string
    {
        return md5(
            implode('|', $route->getMethods()) .
            '|' . $route->getUri() .
            '|' . ($route->getName() ?? '')
        );
    }

    /**
     * Check if route should be cached.
     */
    private function shouldCache(Route $route): bool
    {
        if ($this->cacheAllRoutes) {
            return true;
        }

        $routeName = $route->getName();

        return $routeName !== null && in_array($routeName, $this->cacheableRoutes, true);
    }
}
