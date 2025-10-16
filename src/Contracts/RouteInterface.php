<?php

declare(strict_types=1);

namespace CloudCastle\Http\Router\Contracts;

/**
 * Interface for routes
 */
interface RouteInterface
{
    /**
     * Get the route URI pattern
     */
    public function getUri(): string;

    /**
     * Get allowed HTTP methods
     *
     * @return array<string>
     */
    public function getMethods(): array;

    /**
     * Get the route action
     */
    public function getAction(): mixed;

    /**
     * Get route parameters
     *
     * @return array<string, mixed>
     */
    public function getParameters(): array;

    /**
     * Set route parameters
     *
     * @param array<string, mixed> $parameters
     */
    public function setParameters(array $parameters): self;

    /**
     * Get route name
     */
    public function getName(): ?string;
}
