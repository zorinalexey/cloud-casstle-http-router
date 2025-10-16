<?php

declare(strict_types=1);

namespace CloudCastle\Http\Router\Exceptions;

/**
 * Exception thrown when HTTP method is not allowed for a route
 */
class MethodNotAllowedException extends RouterException
{
    /** @var array<string> */
    private array $allowedMethods = [];

    /**
     * @param array<string> $allowedMethods
     */
    public function setAllowedMethods(array $allowedMethods): self
    {
        $this->allowedMethods = $allowedMethods;
        return $this;
    }

    /**
     * @return array<string>
     */
    public function getAllowedMethods(): array
    {
        return $this->allowedMethods;
    }
}
