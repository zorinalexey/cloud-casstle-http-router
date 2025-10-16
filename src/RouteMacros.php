<?php

declare(strict_types=1);

namespace CloudCastle\Http\Router;

/**
 * Macros for common route patterns
 */
class RouteMacros
{
    /**
     * Resource routing (RESTful)
     *
     * @param string $name Resource name (e.g., 'users')
     * @param string $controller Controller class
     */
    public static function resource(string $name, string $controller): void
    {
        $router = Router::getInstance();
        $singular = rtrim($name, 's');

        $router->get('/' . $name, $controller . '@index')
            ->name($singular . '.index');

        $router->get(sprintf('/%s/create', $name), $controller . '@create')
            ->name($singular . '.create');

        $router->post('/' . $name, $controller . '@store')
            ->name($singular . '.store');

        $router->get(sprintf('/%s/{%sId}', $name, $singular), $controller . '@show')
            ->name($singular . '.show');

        $router->get(sprintf('/%s/{%sId}/edit', $name, $singular), $controller . '@edit')
            ->name($singular . '.edit');

        $router->put(sprintf('/%s/{%sId}', $name, $singular), $controller . '@update')
            ->name($singular . '.update');

        $router->delete(sprintf('/%s/{%sId}', $name, $singular), $controller . '@destroy')
            ->name($singular . '.destroy');
    }

    /**
     * API resource routing
     *
     * @param string $name Resource name
     * @param string $controller Controller class
     * @param int $rateLimit Rate limit (default 100 req/min)
     */
    public static function apiResource(string $name, string $controller, int $rateLimit = 100): void
    {
        $router = Router::getInstance();
        $singular = rtrim($name, 's');

        $router->get('/' . $name, $controller . '@index')
            ->name($singular . '.index')
            ->apiEndpoint($rateLimit);

        $router->post('/' . $name, $controller . '@store')
            ->name($singular . '.store')
            ->apiEndpoint($rateLimit / 2); // Stricter for writes

        $router->get(sprintf('/%s/{%sId}', $name, $singular), $controller . '@show')
            ->name($singular . '.show')
            ->apiEndpoint($rateLimit);

        $router->put(sprintf('/%s/{%sId}', $name, $singular), $controller . '@update')
            ->name($singular . '.update')
            ->apiEndpoint($rateLimit / 2);

        $router->delete(sprintf('/%s/{%sId}', $name, $singular), $controller . '@destroy')
            ->name($singular . '.destroy')
            ->apiEndpoint($rateLimit / 2);
    }

    /**
     * CRUD routes (simplified resource)
     *
     * @param string $name Resource name
     * @param string $controller Controller class
     */
    public static function crud(string $name, string $controller): void
    {
        $router = Router::getInstance();

        $router->get('/' . $name, $controller . '@index');
        $router->post('/' . $name, $controller . '@create');
        $router->put(sprintf('/%s/{id}', $name), $controller . '@update');
        $router->delete(sprintf('/%s/{id}', $name), $controller . '@delete');
    }

    /**
     * Auth routes
     */
    public static function auth(): void
    {
        $router = Router::getInstance();

        $router->get('/login', 'AuthController@showLogin')
            ->name('login')
            ->guest();

        $router->post('/login', 'AuthController@login')
            ->name('login.post')
            ->guest()
            ->throttle(10, 60); // 10 attempts per 60 seconds (1 minute)

        $router->post('/logout', 'AuthController@logout')
            ->name('logout')
            ->auth();

        $router->get('/register', 'AuthController@showRegister')
            ->name('register')
            ->guest();

        $router->post('/register', 'AuthController@register')
            ->name('register.post')
            ->guest()
            ->throttle(3, 600); // 3 attempts per 600 seconds (10 minutes) // 3 registrations per 10 minutes

        $router->get('/password/reset', 'AuthController@showReset')
            ->name('password.request')
            ->guest();

        $router->post('/password/email', 'AuthController@sendResetLink')
            ->name('password.email')
            ->guest()
            ->throttle(3, 1);
    }

    /**
     * Admin panel routes
     *
     * @param array<string> $allowedIps
     */
    public static function adminPanel(array $allowedIps = []): void
    {
        $router = Router::getInstance();

        $attributes = [
            'prefix' => 'admin',
            'middleware' => ['auth', 'admin'],
            'throttle' => 100,
        ];

        if ($allowedIps !== []) {
            $attributes['whitelistIp'] = $allowedIps;
        }

        $router->group($attributes, function ($router): void {
            $router->get('/dashboard', 'Admin\DashboardController@index')
                ->name('admin.dashboard');

            $router->get('/users', 'Admin\UserController@index')
                ->name('admin.users');

            $router->get('/settings', 'Admin\SettingsController@index')
                ->name('admin.settings');
        });
    }

    /**
     * API versioning routes
     *
     * @param string $version Version (e.g., 'v1', 'v2')
     */
    public static function apiVersion(string $version, callable $callback): void
    {
        $router = Router::getInstance();

        $router->group([
            'prefix' => 'api/' . $version,
            'middleware' => ['api'],
            'throttle' => 100,
            'tags' => ['api', $version],
        ], $callback);
    }

    /**
     * Webhooks routes with strict security
     *
     * @param array<string> $allowedIps
     */
    public static function webhooks(array $allowedIps = []): void
    {
        $router = Router::getInstance();

        $attributes = [
            'prefix' => 'webhooks',
            'middleware' => 'verify_webhook_signature',
            'throttle' => 1000,
        ];

        if ($allowedIps !== []) {
            $attributes['whitelistIp'] = $allowedIps;
        }

        $router->group($attributes, function ($router): void {
            $router->post('/github', 'WebhookController@github')
                ->name('webhook.github');

            $router->post('/stripe', 'WebhookController@stripe')
                ->name('webhook.stripe');

            $router->post('/paypal', 'WebhookController@paypal')
                ->name('webhook.paypal');
        });
    }
}
