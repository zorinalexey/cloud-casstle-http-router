<?php

declare(strict_types=1);

namespace CloudCastle\Http\Router\Benchmarks;

use CloudCastle\Http\Router\RateLimiter;

/**
 * Benchmarks for RateLimiter performance.
 */
final class RateLimiterBench
{
    /**
     * Benchmark rate limiter creation.
     */
    public function benchCreateRateLimiter(): void
    {
        RateLimiter::perSecond(60);
    }

    /**
     * Benchmark rate limiter attempts tracking.
     */
    public function benchTrackAttempts(): void
    {
        $limiter = RateLimiter::perMinute(100);
        $identifier = 'test_user';

        for ($i = 0; $i < 50; $i++) {
            $limiter->attempt($identifier);
        }
    }

    /**
     * Benchmark checking rate limit status.
     */
    public function benchCheckRateLimit(): void
    {
        $limiter = RateLimiter::perMinute(100);
        $identifier = 'test_user';

        // Add some attempts
        for ($i = 0; $i < 10; $i++) {
            $limiter->attempt($identifier);
        }

        // Check limit multiple times
        for ($i = 0; $i < 50; $i++) {
            $limiter->tooManyAttempts($identifier);
        }
    }

    /**
     * Benchmark multiple identifiers.
     */
    public function benchMultipleIdentifiers(): void
    {
        $limiter = RateLimiter::perMinute(100);

        for ($i = 0; $i < 10; $i++) {
            $identifier = "user_{$i}";
            for ($j = 0; $j < 5; $j++) {
                $limiter->attempt($identifier);
            }
        }
    }
}
