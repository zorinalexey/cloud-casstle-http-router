<?php

declare(strict_types=1);

namespace CloudCastle\Http\Router\Tests\Unit;

use CloudCastle\Http\Router\RateLimiter;
use CloudCastle\Http\Router\TimeUnit;
use PHPUnit\Framework\TestCase;

class RateLimiterTimeUnitsTest extends TestCase
{
    public function testPerSecond(): void
    {
        $limiter = RateLimiter::perSecond(5);

        $this->assertEquals(5, $limiter->getMaxAttempts());
        $this->assertEquals(1, $limiter->getDecaySeconds());
    }

    public function testPerSecondMultiple(): void
    {
        $limiter = RateLimiter::perSecond(10, 5);

        $this->assertEquals(10, $limiter->getMaxAttempts());
        $this->assertEquals(5, $limiter->getDecaySeconds());
    }

    public function testPerMinute(): void
    {
        $limiter = RateLimiter::perMinute(60);

        $this->assertEquals(60, $limiter->getMaxAttempts());
        $this->assertEquals(60, $limiter->getDecaySeconds());
    }

    public function testPerMinuteMultiple(): void
    {
        $limiter = RateLimiter::perMinute(100, 5);

        $this->assertEquals(100, $limiter->getMaxAttempts());
        $this->assertEquals(300, $limiter->getDecaySeconds());
    }

    public function testPerHour(): void
    {
        $limiter = RateLimiter::perHour(1000);

        $this->assertEquals(1000, $limiter->getMaxAttempts());
        $this->assertEquals(3600, $limiter->getDecaySeconds());
    }

    public function testPerDay(): void
    {
        $limiter = RateLimiter::perDay(10000);

        $this->assertEquals(10000, $limiter->getMaxAttempts());
        $this->assertEquals(86400, $limiter->getDecaySeconds());
    }

    public function testPerWeek(): void
    {
        $limiter = RateLimiter::perWeek(50000);

        $this->assertEquals(50000, $limiter->getMaxAttempts());
        $this->assertEquals(604800, $limiter->getDecaySeconds());
    }

    public function testPerMonth(): void
    {
        $limiter = RateLimiter::perMonth(100000);

        $this->assertEquals(100000, $limiter->getMaxAttempts());
        $this->assertEquals(2592000, $limiter->getDecaySeconds());
    }

    public function testMakeWithTimeUnit(): void
    {
        $limiterSecond = RateLimiter::make(10, 1, TimeUnit::SECOND);
        $limiterMinute = RateLimiter::make(100, 1, TimeUnit::MINUTE);
        $limiterHour = RateLimiter::make(1000, 1, TimeUnit::HOUR);
        $limiterDay = RateLimiter::make(10000, 1, TimeUnit::DAY);
        $limiterWeek = RateLimiter::make(50000, 1, TimeUnit::WEEK);
        $limiterMonth = RateLimiter::make(100000, 1, TimeUnit::MONTH);

        $this->assertEquals(1, $limiterSecond->getDecaySeconds());
        $this->assertEquals(60, $limiterMinute->getDecaySeconds());
        $this->assertEquals(3600, $limiterHour->getDecaySeconds());
        $this->assertEquals(86400, $limiterDay->getDecaySeconds());
        $this->assertEquals(604800, $limiterWeek->getDecaySeconds());
        $this->assertEquals(2592000, $limiterMonth->getDecaySeconds());
    }

    public function testBackwardCompatibilityGetDecayMinutes(): void
    {
        $limiter = RateLimiter::perMinute(60, 5);

        $this->assertEquals(5, $limiter->getDecayMinutes());
    }

    protected function setUp(): void
    {
        RateLimiter::resetAll();
    }
}
