<?php

declare(strict_types=1);

namespace CloudCastle\Http\Router\Contracts;

use CloudCastle\Http\Router\Router;
use CloudCastle\Http\Router\Route;

/**
 * Plugin interface for extending router functionality.
 */
interface PluginInterface
{
    /**
     * Get plugin name.
     */
    public function getName(): string;

    /**
     * Get plugin version.
     */
    public function getVersion(): string;

    /**
     * Plugin initialization (called when plugin is registered).
     *
     * @param Router $router Router instance
     */
    public function boot(Router $router): void;

    /**
     * Called before route is dispatched.
     *
     * @param Route $route Matched route
     * @param string $uri Request URI
     * @param string $method HTTP method
     *
     * @return void
     */
    public function beforeDispatch(Route $route, string $uri, string $method): void;

    /**
     * Called after route is dispatched.
     *
     * @param Route $route Matched route
     * @param mixed $result Route action result
     *
     * @return mixed Modified result or original
     */
    public function afterDispatch(Route $route, mixed $result): mixed;

    /**
     * Called when route is registered.
     *
     * @param Route $route Registered route
     */
    public function onRouteRegistered(Route $route): void;

    /**
     * Called when exception occurs during dispatch.
     *
     * @param \Exception $exception Exception instance
     *
     * @return void
     */
    public function onException(\Exception $exception): void;

    /**
     * Check if plugin is enabled.
     */
    public function isEnabled(): bool;

    /**
     * Enable plugin.
     */
    public function enable(): void;

    /**
     * Disable plugin.
     */
    public function disable(): void;
}
