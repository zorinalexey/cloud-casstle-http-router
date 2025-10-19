<?php

declare(strict_types=1);

namespace CloudCastle\Http\Router;

/**
 * Manager for IP bans due to rate limit violations.
 */
class BanManager
{
    /** @var array<string, int> Banned IPs with expiration timestamps */
    private array $bans = [];

    /** @var array<string, int> Violation counts per IP */
    private array $violations = []; // in seconds

    /**
     * @param int $maxViolations Number of violations before ban (default: 3)
     * @param int $banDuration Ban duration in seconds (default: 3600 = 1 hour)
     */
    public function __construct(private readonly int $maxViolations = 3, private readonly int $banDuration = 3600)
    {
    }

    /**
     * Get ban expiration time.
     */
    public function getBanExpiration(string $ipAddress): ?int
    {
        return $this->bans[$ipAddress] ?? null;
    }

    /**
     * Get remaining ban time in seconds.
     */
    public function getBanTimeRemaining(string $ipAddress): int
    {
        if (!$this->isBanned($ipAddress)) {
            return 0;
        }

        return max(0, $this->bans[$ipAddress] - time());
    }

    /**
     * Check if IP is currently banned.
     */
    public function isBanned(string $ipAddress): bool
    {
        if (!isset($this->bans[$ipAddress])) {
            return false;
        }

        // Check if ban expired
        if (time() >= $this->bans[$ipAddress]) {
            $this->unban($ipAddress);

            return false;
        }

        return true;
    }

    /**
     * Manually unban an IP address.
     */
    public function unban(string $ipAddress): void
    {
        unset($this->bans[$ipAddress]);
        unset($this->violations[$ipAddress]);
    }

    /**
     * Record a rate limit violation.
     *
     * @return bool True if IP should be banned
     */
    public function recordViolation(string $ipAddress): bool
    {
        // If already banned, don't record more violations
        if ($this->isBanned($ipAddress)) {
            return true;
        }

        // Increment violation count
        $this->violations[$ipAddress] = ($this->violations[$ipAddress] ?? 0) + 1;

        // Check if should ban
        if ($this->violations[$ipAddress] >= $this->maxViolations) {
            $this->ban($ipAddress);

            return true;
        }

        return false;
    }

    /**
     * Ban an IP address.
     */
    public function ban(string $ipAddress, ?int $duration = null): void
    {
        $duration ??= $this->banDuration;
        $this->bans[$ipAddress] = time() + $duration;
    }

    /**
     * Clear violation count for IP.
     */
    public function clearViolations(string $ipAddress): void
    {
        unset($this->violations[$ipAddress]);
    }

    /**
     * Get violation count for IP.
     */
    public function getViolationCount(string $ipAddress): int
    {
        return $this->violations[$ipAddress] ?? 0;
    }

    /**
     * Get all banned IPs.
     *
     * @return array<string, int> IP => expiration timestamp
     */
    public function getBannedIps(): array
    {
        // Remove expired bans
        foreach ($this->bans as $ipAddress => $expiration) {
            if (time() >= $expiration) {
                $this->unban($ipAddress);
            }
        }

        return $this->bans;
    }

    /**
     * Clear all bans.
     */
    public function clearAllBans(): void
    {
        $this->bans = [];
        $this->violations = [];
    }

    /**
     * Get ban statistics.
     *
     * @return array<string, mixed>
     */
    public function getStatistics(): array
    {
        return [
            'total_banned' => count($this->bans),
            'total_violations' => array_sum($this->violations),
            'unique_ips_with_violations' => count($this->violations),
            'max_violations' => $this->maxViolations,
            'ban_duration' => $this->banDuration,
        ];
    }
}
