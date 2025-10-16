<?php

declare(strict_types=1);

namespace CloudCastle\Http\Router;

/**
 * Manager for IP bans due to rate limit violations
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
     * Check if IP is currently banned
     */
    public function isBanned(string $ip): bool
    {
        if (!isset($this->bans[$ip])) {
            return false;
        }

        // Check if ban expired
        if (time() >= $this->bans[$ip]) {
            $this->unban($ip);
            return false;
        }

        return true;
    }

    /**
     * Get ban expiration time
     */
    public function getBanExpiration(string $ip): ?int
    {
        return $this->bans[$ip] ?? null;
    }

    /**
     * Get remaining ban time in seconds
     */
    public function getBanTimeRemaining(string $ip): int
    {
        if (!$this->isBanned($ip)) {
            return 0;
        }

        return max(0, $this->bans[$ip] - time());
    }

    /**
     * Record a rate limit violation
     * 
     * @return bool True if IP should be banned
     */
    public function recordViolation(string $ip): bool
    {
        // If already banned, don't record more violations
        if ($this->isBanned($ip)) {
            return true;
        }

        // Increment violation count
        $this->violations[$ip] = ($this->violations[$ip] ?? 0) + 1;

        // Check if should ban
        if ($this->violations[$ip] >= $this->maxViolations) {
            $this->ban($ip);
            return true;
        }

        return false;
    }

    /**
     * Ban an IP address
     */
    public function ban(string $ip, ?int $duration = null): void
    {
        $duration ??= $this->banDuration;
        $this->bans[$ip] = time() + $duration;
    }

    /**
     * Manually unban an IP address
     */
    public function unban(string $ip): void
    {
        unset($this->bans[$ip]);
        unset($this->violations[$ip]);
    }

    /**
     * Clear violation count for IP
     */
    public function clearViolations(string $ip): void
    {
        unset($this->violations[$ip]);
    }

    /**
     * Get violation count for IP
     */
    public function getViolationCount(string $ip): int
    {
        return $this->violations[$ip] ?? 0;
    }

    /**
     * Get all banned IPs
     * 
     * @return array<string, int> IP => expiration timestamp
     */
    public function getBannedIps(): array
    {
        // Remove expired bans
        foreach ($this->bans as $ip => $expiration) {
            if (time() >= $expiration) {
                $this->unban($ip);
            }
        }

        return $this->bans;
    }

    /**
     * Clear all bans
     */
    public function clearAllBans(): void
    {
        $this->bans = [];
        $this->violations = [];
    }

    /**
     * Get ban statistics
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

