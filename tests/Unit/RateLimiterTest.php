<?php

declare(strict_types=1);

namespace CloudCastle\Http\Router\Tests\Unit;

use CloudCastle\Http\Router\RateLimiter;
use PHPUnit\Framework\TestCase;

class RateLimiterTest extends TestCase
{
    public function testBasicRateLimiting(): void
    {
        $limiter = new RateLimiter(3, 1);

        $this->assertTrue($limiter->attempt('user1'));
        $this->assertEquals(2, $limiter->remaining('user1'));
        
        $this->assertTrue($limiter->attempt('user1'));
        $this->assertEquals(1, $limiter->remaining('user1'));
        
        $this->assertTrue($limiter->attempt('user1'));
        $this->assertEquals(0, $limiter->remaining('user1'));
        
        $this->assertFalse($limiter->attempt('user1'));
        $this->assertTrue($limiter->tooManyAttempts('user1'));
        $this->assertEquals(3, $limiter->attempts('user1'));
    }

    public function testRemainingAttempts(): void
    {
        $limiter = new RateLimiter(5, 1);

        $this->assertEquals(5, $limiter->remaining('user1'));

        $limiter->attempt('user1');
        $this->assertEquals(4, $limiter->remaining('user1'));

        $limiter->attempt('user1');
        $this->assertEquals(3, $limiter->remaining('user1'));
    }

    public function testTooManyAttempts(): void
    {
        $limiter = new RateLimiter(2, 1);

        $this->assertFalse($limiter->tooManyAttempts('user1'));
        $this->assertEquals(2, $limiter->remaining('user1'));

        $limiter->attempt('user1');
        $this->assertFalse($limiter->tooManyAttempts('user1'));
        $this->assertEquals(1, $limiter->remaining('user1'));
        
        $limiter->attempt('user1');
        $this->assertTrue($limiter->tooManyAttempts('user1'));
        $this->assertEquals(0, $limiter->remaining('user1'));
        $this->assertEquals(2, $limiter->attempts('user1'));
    }

    public function testClearAttempts(): void
    {
        $limiter = new RateLimiter(2, 1);

        $limiter->attempt('user1');
        $limiter->attempt('user1');
        $this->assertTrue($limiter->tooManyAttempts('user1'));

        $limiter->clear('user1');
        $this->assertFalse($limiter->tooManyAttempts('user1'));
        $this->assertEquals(2, $limiter->remaining('user1'));
    }

    public function testAvailableIn(): void
    {
        $limiter = new RateLimiter(1, 1);

        $limiter->attempt('user1');
        $limiter->attempt('user1'); // Exceeds limit

        $availableIn = $limiter->availableIn('user1');
        $this->assertGreaterThan(0, $availableIn);
        $this->assertLessThanOrEqual(60, $availableIn);
    }

    public function testMultipleUsers(): void
    {
        $limiter = new RateLimiter(2, 1);

        $limiter->attempt('user1');
        $limiter->attempt('user1');
        $limiter->attempt('user2');

        $this->assertTrue($limiter->tooManyAttempts('user1'));
        $this->assertFalse($limiter->tooManyAttempts('user2'));
        $this->assertEquals(0, $limiter->remaining('user1'));
        $this->assertEquals(1, $limiter->remaining('user2'));
    }

    public function testCustomKey(): void
    {
        $limiter1 = new RateLimiter(2, 1, 'api');
        $limiter2 = new RateLimiter(5, 1, 'web');

        $limiter1->attempt('user1');
        $limiter1->attempt('user1');

        // Same user, different limiter - should not be limited
        $this->assertTrue($limiter2->attempt('user1'));
        $this->assertEquals(4, $limiter2->remaining('user1'));
    }

    public function testAttemptsCount(): void
    {
        $limiter = new RateLimiter(10, 1);

        $this->assertEquals(0, $limiter->attempts('user1'));

        $limiter->attempt('user1');
        $this->assertEquals(1, $limiter->attempts('user1'));

        $limiter->attempt('user1');
        $limiter->attempt('user1');
        $this->assertEquals(3, $limiter->attempts('user1'));
    }

    public function testResetAll(): void
    {
        $limiter1 = new RateLimiter(2, 1, 'key1');
        $limiter2 = new RateLimiter(2, 1, 'key2');

        $limiter1->attempt('user1');
        $limiter2->attempt('user2');

        RateLimiter::resetAll();

        $this->assertEquals(0, $limiter1->attempts('user1'));
        $this->assertEquals(0, $limiter2->attempts('user2'));
        $this->assertEquals(2, $limiter1->remaining('user1'));
        $this->assertEquals(2, $limiter2->remaining('user2'));
    }

    public function testGetMaxAttempts(): void
    {
        $limiter = new RateLimiter(10, 1);
        
        $this->assertEquals(10, $limiter->getMaxAttempts());
    }

    public function testGetDecayMinutes(): void
    {
        $limiter = new RateLimiter(10, 300); // 300 seconds = 5 minutes
        
        $this->assertEquals(5, $limiter->getDecayMinutes());
        $this->assertEquals(300, $limiter->getDecaySeconds());
    }

    public function testZeroRemainingWhenExceeded(): void
    {
        $limiter = new RateLimiter(1, 1);
        
        $limiter->attempt('user1');
        $limiter->attempt('user1'); // Exceed
        
        $this->assertEquals(0, $limiter->remaining('user1'));
        $this->assertGreaterThan(0, $limiter->attempts('user1'));
    }

    public function testAvailableInForNonLimitedUser(): void
    {
        $limiter = new RateLimiter(5, 1);
        
        // User hasn't made any attempts
        $this->assertEquals(0, $limiter->availableIn('newuser'));
    }

    protected function setUp(): void
    {
        RateLimiter::resetAll();
    }

    protected function tearDown(): void
    {
        RateLimiter::resetAll();
    }
}
