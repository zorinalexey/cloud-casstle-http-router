<?php

declare(strict_types=1);

namespace CloudCastle\Http\Router;

/**
 * Rate limiter for controlling request frequency.
 *
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 */
class RateLimiter
{
    /** @var array<string, array{count: int, reset: int}> */
    private static array $requests = [];

    private readonly string $key; // Changed from decayMinutes to decaySeconds

    private ?BanManager $banManager = null;

    /**
     * @param int $maxAttempts Maximum number of attempts
     * @param int $decaySeconds Time window in seconds
     * @param string|null $key Custom key for rate limiting
     */
    public function __construct(
        private readonly int $maxAttempts = 60,
        private readonly int $decaySeconds = 60,
        ?string $key = null
    ) {
        $this->key = $key ?? 'default';
    }

    /**
     * Create rate limiter with time unit.
     *
     * @param int $maxAttempts Maximum attempts
     * @param int $decay Time value
     * @param TimeUnit $unit Time unit
     * @param string|null $key Custom key
     */
    public static function make(int $maxAttempts, int $decay, TimeUnit $unit, ?string $key = null): self
    {
        return new self($maxAttempts, $unit->toSeconds($decay), $key);
    }

    /**
     * Create rate limiter per second.
     */
    public static function perSecond(int $maxAttempts, int $seconds = 1, ?string $key = null): self
    {
        return new self($maxAttempts, $seconds, $key);
    }

    /**
     * Create rate limiter per minute.
     */
    public static function perMinute(int $maxAttempts, int $minutes = 1, ?string $key = null): self
    {
        return new self($maxAttempts, $minutes * 60, $key);
    }

    /**
     * Create rate limiter per hour.
     */
    public static function perHour(int $maxAttempts, int $hours = 1, ?string $key = null): self
    {
        return new self($maxAttempts, $hours * 3600, $key);
    }

    /**
     * Create rate limiter per day.
     */
    public static function perDay(int $maxAttempts, int $days = 1, ?string $key = null): self
    {
        return new self($maxAttempts, $days * 86400, $key);
    }

    /**
     * Create rate limiter per week.
     */
    public static function perWeek(int $maxAttempts, int $weeks = 1, ?string $key = null): self
    {
        return new self($maxAttempts, $weeks * 604800, $key);
    }

    /**
     * Create rate limiter per month.
     */
    public static function perMonth(int $maxAttempts, int $months = 1, ?string $key = null): self
    {
        return new self($maxAttempts, $months * 2592000, $key);
    }

    /**
     * Alias for clearAll (backward compatibility).
     */
    public static function resetAll(): void
    {
        self::clearAll();
    }

    /**
     * Clear all rate limit data.
     */
    public static function clearAll(): void
    {
        self::$requests = [];
    }

    /**
     * Enable auto-ban on rate limit violations.
     *
     * @param int $maxViolations Number of violations before ban (default: 3)
     * @param int $banDuration Ban duration in seconds (default: 3600 = 1 hour)
     */
    public function enableAutoBan(int $maxViolations = 3, int $banDuration = 3600): self
    {
        $this->banManager = new BanManager($maxViolations, $banDuration);

        return $this;
    }

    /**
     * Get ban manager.
     */
    public function getBanManager(): ?BanManager
    {
        return $this->banManager;
    }

    /**
     * Set custom ban manager.
     */
    public function setBanManager(BanManager $banManager): self
    {
        $this->banManager = $banManager;

        return $this;
    }

    /**
     * Attempt to perform an action.
     *
     * @param string $identifier Unique identifier (e.g., IP address, user ID)
     *
     * @return bool True if allowed, false if rate limit exceeded
     */
    public function attempt(string $identifier): bool
    {
        // Check if IP is banned first
        if ($this->banManager instanceof BanManager && $this->banManager->isBanned($identifier)) {
            return false;
        }

        $key = $this->resolveKey($identifier);
        $this->cleanupExpired();

        if (!isset(self::$requests[$key])) {
            self::$requests[$key] = [
                'count' => 1,
                'reset' => time() + $this->decaySeconds,
            ];

            return true;
        }

        $data = self::$requests[$key];

        // Check if window has expired
        if (time() >= $data['reset']) {
            self::$requests[$key] = [
                'count' => 1,
                'reset' => time() + $this->decaySeconds,
            ];

            return true;
        }

        // Check if limit exceeded
        if ($data['count'] >= $this->maxAttempts) {
            // Record violation for auto-ban
            if ($this->banManager instanceof BanManager) {
                $this->banManager->recordViolation($identifier);
            }

            return false;
        }

        // Increment counter
        self::$requests[$key]['count']++;

        return true;
    }

    /**
     * Resolve full key with prefix.
     */
    private function resolveKey(string $identifier): string
    {
        return $this->key . ':' . $identifier;
    }

    /**
     * Clean up expired entries.
     */
    private function cleanupExpired(): void
    {
        $now = time();
        foreach (self::$requests as $key => $data) {
            if ($now >= $data['reset']) {
                unset(self::$requests[$key]);
            }
        }
    }

    /**
     * Hit (increment) the rate limiter.
     */
    public function hit(string $identifier): void
    {
        // Don't record hits for banned IPs
        if ($this->banManager instanceof BanManager && $this->banManager->isBanned($identifier)) {
            return;
        }

        $key = $this->resolveKey($identifier);
        $this->cleanupExpired();

        if (!isset(self::$requests[$key])) {
            self::$requests[$key] = [
                'count' => 1,
                'reset' => time() + $this->decaySeconds,
            ];

            return;
        }

        if (time() >= self::$requests[$key]['reset']) {
            self::$requests[$key] = [
                'count' => 1,
                'reset' => time() + $this->decaySeconds,
            ];

            return;
        }

        self::$requests[$key]['count']++;
    }

    /**
     * Check if too many attempts have been made.
     */
    public function tooManyAttempts(string $identifier): bool
    {
        // Check if IP is banned first
        if ($this->banManager instanceof BanManager && $this->banManager->isBanned($identifier)) {
            return true;
        }

        $key = $this->resolveKey($identifier);
        $this->cleanupExpired();

        if (!isset(self::$requests[$key])) {
            return false;
        }

        $data = self::$requests[$key];

        // Check if window has expired
        if (time() >= $data['reset']) {
            return false;
        }

        $tooMany = $data['count'] >= $this->maxAttempts;

        // Record violation for auto-ban
        if ($tooMany && $this->banManager instanceof BanManager) {
            $shouldBan = $this->banManager->recordViolation($identifier);
            if ($shouldBan) {
                // Clear rate limit data for banned IP
                unset(self::$requests[$key]);
            }
        }

        return $tooMany;
    }

    /**
     * Get remaining attempts.
     */
    public function remaining(string $identifier): int
    {
        if ($this->banManager instanceof BanManager && $this->banManager->isBanned($identifier)) {
            return 0;
        }

        $key = $this->resolveKey($identifier);
        $this->cleanupExpired();

        if (!isset(self::$requests[$key])) {
            return $this->maxAttempts;
        }

        $data = self::$requests[$key];

        if (time() >= $data['reset']) {
            return $this->maxAttempts;
        }

        return max(0, $this->maxAttempts - $data['count']);
    }

    /**
     * Reset rate limiter for identifier.
     */
    public function reset(string $identifier): void
    {
        $key = $this->resolveKey($identifier);
        unset(self::$requests[$key]);
    }

    /**
     * Get max attempts.
     */
    public function getMaxAttempts(): int
    {
        return $this->maxAttempts;
    }

    /**
     * Get decay seconds.
     */
    public function getDecaySeconds(): int
    {
        return $this->decaySeconds;
    }

    /**
     * Get decay minutes (backward compatibility).
     */
    public function getDecayMinutes(): int
    {
        return (int) ($this->decaySeconds / 60);
    }

    /**
     * Get current request count for identifier.
     */
    public function attempts(string $identifier): int
    {
        $key = $this->resolveKey($identifier);

        if (!isset(self::$requests[$key])) {
            return 0;
        }

        $data = self::$requests[$key];

        // Check if window has expired
        if (time() >= $data['reset']) {
            return 0;
        }

        return $data['count'];
    }

    /**
     * Get seconds until reset.
     */
    public function availableIn(string $identifier): int
    {
        $key = $this->resolveKey($identifier);

        if (!isset(self::$requests[$key])) {
            return 0;
        }

        $data = self::$requests[$key];

        return max(0, $data['reset'] - time());
    }

    /**
     * Clear rate limit for identifier.
     */
    public function clear(string $identifier): void
    {
        $key = $this->resolveKey($identifier);
        unset(self::$requests[$key]);
    }
}
