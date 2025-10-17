<?php

declare(strict_types=1);

namespace CloudCastle\Http\Router\Exceptions;

/**
 * Exception thrown when rate limit is exceeded.
 */
class TooManyRequestsException extends RouterException
{
    private int $retryAfter = 60;

    private int $limit = 0;

    private int $remaining = 0;

    /**
     * Set retry after seconds.
     */
    public function setRetryAfter(int $seconds): self
    {
        $this->retryAfter = $seconds;

        return $this;
    }

    /**
     * Get retry after seconds.
     */
    public function getRetryAfter(): int
    {
        return $this->retryAfter;
    }

    /**
     * Set rate limit.
     */
    public function setLimit(int $limit): self
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * Get rate limit.
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * Set remaining requests.
     */
    public function setRemaining(int $remaining): self
    {
        $this->remaining = $remaining;

        return $this;
    }

    /**
     * Get remaining requests.
     */
    public function getRemaining(): int
    {
        return $this->remaining;
    }
}
