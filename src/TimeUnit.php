<?php

declare(strict_types=1);

namespace CloudCastle\Http\Router;

/**
 * Time unit constants for rate limiting.
 */
enum TimeUnit: int
{
    case SECOND = 1;
    case MINUTE = 60;
    case HOUR = 3600;
    case DAY = 86400;
    case WEEK = 604800;
    case MONTH = 2592000; // 30 days

    /**
     * Convert value to seconds.
     */
    public function toSeconds(int $value): int
    {
        return $value * $this->value;
    }

    /**
     * Get plural unit name.
     */
    public function getPlural(): string
    {
        return $this->getName() . 's';
    }

    /**
     * Get unit name.
     */
    public function getName(): string
    {
        return match ($this) {
            self::SECOND => 'second',
            self::MINUTE => 'minute',
            self::HOUR => 'hour',
            self::DAY => 'day',
            self::WEEK => 'week',
            self::MONTH => 'month',
        };
    }
}
