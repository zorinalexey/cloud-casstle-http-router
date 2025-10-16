<?php

declare(strict_types=1);

namespace CloudCastle\Http\Router\Exceptions;

/**
 * Exception thrown when IP is banned due to rate limit violations
 */
class BannedException extends RouterException
{
    private int $banExpiresAt = 0;

    private string $bannedIp = '';

    private string $reason = '';

    public function setBanExpiresAt(int $timestamp): self
    {
        $this->banExpiresAt = $timestamp;
        return $this;
    }

    public function getBanExpiresAt(): int
    {
        return $this->banExpiresAt;
    }

    public function getTimeRemaining(): int
    {
        return max(0, $this->banExpiresAt - time());
    }

    public function setBannedIp(string $ip): self
    {
        $this->bannedIp = $ip;
        return $this;
    }

    public function getBannedIp(): string
    {
        return $this->bannedIp;
    }

    public function setReason(string $reason): self
    {
        $this->reason = $reason;
        return $this;
    }

    public function getReason(): string
    {
        return $this->reason;
    }
}
