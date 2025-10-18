<?php

declare(strict_types=1);

namespace CloudCastle\Http\Router\Plugin;

use CloudCastle\Http\Router\Contracts\PluginInterface;
use CloudCastle\Http\Router\Router;
use CloudCastle\Http\Router\Route;

/**
 * Base abstract plugin class with default implementations.
 */
abstract class AbstractPlugin implements PluginInterface
{
    protected bool $enabled = true;

    protected ?Router $router = null;

    /**
     * Get plugin name.
     */
    abstract public function getName(): string;

    /**
     * Get plugin version.
     */
    public function getVersion(): string
    {
        return '1.0.0';
    }

    /**
     * Plugin initialization.
     */
    public function boot(Router $router): void
    {
        $this->router = $router;
    }

    /**
     * Called before route dispatch.
     */
    public function beforeDispatch(Route $route, string $uri, string $method): void
    {
        // Default: no action
    }

    /**
     * Called after route dispatch.
     */
    public function afterDispatch(Route $route, mixed $result): mixed
    {
        // Default: return result unchanged
        return $result;
    }

    /**
     * Called when route is registered.
     */
    public function onRouteRegistered(Route $route): void
    {
        // Default: no action
    }

    /**
     * Called when exception occurs.
     */
    public function onException(\Exception $exception): void
    {
        // Default: no action
    }

    /**
     * Check if plugin is enabled.
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * Enable plugin.
     */
    public function enable(): void
    {
        $this->enabled = true;
    }

    /**
     * Disable plugin.
     */
    public function disable(): void
    {
        $this->enabled = false;
    }

    /**
     * Get router instance.
     */
    protected function getRouter(): ?Router
    {
        return $this->router;
    }
}
