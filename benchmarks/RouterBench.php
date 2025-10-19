<?php

declare(strict_types=1);

namespace CloudCastle\Http\Router\Benchmarks;

use CloudCastle\Http\Router\Router;

/**
 * Benchmarks for Router performance.
 */
final class RouterBench
{
    /**
     * Benchmark adding routes.
     */
    public function benchAddRoutes(): void
    {
        $router = new Router();

        for ($i = 0; $i < 100; $i++) {
            $router->get("/user/{$i}", fn () => "User {$i}");
        }
    }

    /**
     * Benchmark route matching - best case (first route).
     */
    public function benchMatchFirstRoute(): void
    {
        $router = new Router();
        $router->get('/home', fn () => 'Home');
        $router->get('/about', fn () => 'About');
        $router->get('/contact', fn () => 'Contact');

        $router->dispatch('/home', 'GET');
    }

    /**
     * Benchmark route matching - average case (middle route).
     */
    public function benchMatchMiddleRoute(): void
    {
        $router = new Router();

        for ($i = 0; $i < 50; $i++) {
            $router->get("/route{$i}", fn () => "Route {$i}");
        }

        $router->dispatch('/route25', 'GET');
    }

    /**
     * Benchmark route matching - worst case (last route).
     */
    public function benchMatchLastRoute(): void
    {
        $router = new Router();

        for ($i = 0; $i < 100; $i++) {
            $router->get("/route{$i}", fn () => "Route {$i}");
        }

        $router->dispatch('/route99', 'GET');
    }

    /**
     * Benchmark named route lookup.
     */
    public function benchNamedRouteLookup(): void
    {
        $router = new Router();

        for ($i = 0; $i < 100; $i++) {
            $router->get("/user/{$i}", fn () => "User {$i}")->name("user.{$i}");
        }

        $router->getRouteByName('user.50');
    }

    /**
     * Benchmark route groups.
     */
    public function benchRouteGroups(): void
    {
        $router = new Router();

        $router->group(['prefix' => 'api/v1'], function (Router $router): void {
            for ($i = 0; $i < 50; $i++) {
                $router->get("/endpoint{$i}", fn () => "Endpoint {$i}");
            }
        });
    }

    /**
     * Benchmark route with middleware.
     */
    public function benchRouteWithMiddleware(): void
    {
        $router = new Router();
        $middleware = fn ($request, $next) => $next($request);

        for ($i = 0; $i < 50; $i++) {
            $router->get("/secure{$i}", fn () => "Secure {$i}")
                ->middleware($middleware);
        }
    }

    /**
     * Benchmark route with parameters.
     */
    public function benchRouteWithParameters(): void
    {
        $router = new Router();
        $router->get('/user/{id}/posts/{postId}', fn ($id, $postId) => "User {$id}, Post {$postId}");

        $router->dispatch('/user/123/posts/456', 'GET');
    }
}
