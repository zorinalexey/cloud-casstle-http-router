<?php

declare(strict_types=1);

namespace CloudCastle\Http\Router\Exceptions;

/**
 * Exception thrown when insecure connection is used for HTTPS-only route
 */
class InsecureConnectionException extends RouterException
{
    private string $requiredProtocol = 'https';

    public function setRequiredProtocol(string $protocol): self
    {
        $this->requiredProtocol = $protocol;
        return $this;
    }

    public function getRequiredProtocol(): string
    {
        return $this->requiredProtocol;
    }
}
