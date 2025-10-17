<?php

declare(strict_types=1);

namespace CloudCastle\Http\Router\Traits;

use CloudCastle\Http\Router\Route;

/**
 * Convenient shortcuts for route configuration.
 */
trait RouteShortcuts
{
    /**
     * Quick middleware setup with common presets.
     */
    public function auth(): Route
    {
        return $this->middleware('auth');
    }

    /**
     * Quick guest middleware.
     */
    public function guest(): Route
    {
        return $this->middleware('guest');
    }

    /**
     * Quick API middleware.
     */
    public function api(): Route
    {
        return $this->middleware('api');
    }

    /**
     * Quick web middleware.
     */
    public function web(): Route
    {
        return $this->middleware('web');
    }

    /**
     * Quick CORS middleware.
     */
    public function cors(): Route
    {
        return $this->middleware('cors');
    }

    /**
     * Only allow localhost.
     */
    public function localhost(): Route
    {
        return $this->whitelistIp(['127.0.0.1', '::1']);
    }

    /**
     * Force HTTPS (port 443) - alias for https().
     */
    public function secure(): Route
    {
        return $this->https();
    }

    /**
     * Standard throttle (60 req/min).
     */
    public function throttleStandard(): Route
    {
        return $this->throttle(60, 60); // 60 requests per 60 seconds
    }

    /**
     * Strict throttle (10 req/min).
     */
    public function throttleStrict(): Route
    {
        return $this->throttle(10, 1);
    }

    /**
     * Generous throttle (1000 req/min).
     */
    public function throttleGenerous(): Route
    {
        return $this->throttle(1000, 1);
    }

    /**
     * Mark as public route.
     */
    public function public(): Route
    {
        return $this->tag('public');
    }

    /**
     * Mark as private route.
     */
    public function private(): Route
    {
        return $this->tag('private');
    }

    /**
     * Mark as admin route with security.
     */
    public function admin(): Route
    {
        return $this->middleware(['auth', 'admin'])->tag('admin');
    }

    /**
     * Quick setup for API endpoint.
     */
    public function apiEndpoint(int $rateLimit = 100): Route
    {
        return $this->middleware('api')
            ->throttle($rateLimit, 1)
            ->tag('api');
    }

    /**
     * Quick setup for protected resource.
     */
    public function protected(): Route
    {
        return $this->middleware('auth')
            ->throttle(100, 1);
    }
}
