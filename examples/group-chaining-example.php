<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use CloudCastle\Http\Router\Router;

$router = new Router();

// Пример 1: Создание группы с цепочкой методов
echo "Пример 1: Группа с цепочкой методов\n";
echo str_repeat('=', 50) . "\n\n";

$adminGroup = $router->group(['prefix' => '/admin'], function (Router $router): void {
    $router->get('/dashboard', fn (): string => 'Admin Dashboard');
    $router->get('/users', fn (): string => 'User Management');
    $router->get('/settings', fn (): string => 'Settings');
})
    ->middleware(['auth', 'admin'])
    ->throttle(100, 1)
    ->whitelistIp(['192.168.1.1', '10.0.0.1'])
    ->tag(['admin', 'protected'])
    ->name('admin');

echo "Группа создана с префиксом: " . $adminGroup->getPrefix() . "\n";
echo "Middleware: " . implode(', ', $adminGroup->getMiddleware()) . "\n";
echo "Whitelist IPs: " . implode(', ', $adminGroup->getWhitelistIps()) . "\n";
echo "Tags: " . implode(', ', $adminGroup->getTags()) . "\n";
echo "Rate limit: " . $adminGroup->getRateLimiter()?->getMaxAttempts() . " запросов в минуту\n";
echo "Маршрутов в группе: " . count($adminGroup->getRoutes()) . "\n\n";

// Пример 2: API группа с версионированием
echo "Пример 2: API v1 группа\n";
echo str_repeat('=', 50) . "\n\n";

$apiV1 = $router->group(['prefix' => '/api/v1'], function (Router $router): void {
    $router->get('/users', fn (): string => 'Get Users');
    $router->get('/posts', fn (): string => 'Get Posts');
    $router->get('/comments', fn (): string => 'Get Comments');
})
    ->throttle(60, 1)
    ->tag(['api', 'v1', 'public'])
    ->middleware('api');

echo "API v1 префикс: " . $apiV1->getPrefix() . "\n";
echo "API v1 tags: " . implode(', ', $apiV1->getTags()) . "\n";
echo "Маршрутов: " . count($apiV1->getRoutes()) . "\n\n";

// Пример 3: API v2 с более высокими лимитами
echo "Пример 3: API v2 группа (премиум)\n";
echo str_repeat('=', 50) . "\n\n";

$apiV2 = $router->group(['prefix' => '/api/v2'], function (Router $router): void {
    $router->get('/users', fn (): string => 'Get Users v2');
    $router->get('/analytics', fn (): string => 'Analytics');
})
    ->throttle(1000, 1)
    ->tag(['api', 'v2', 'premium'])
    ->middleware(['api', 'auth']);

echo "API v2 префикс: " . $apiV2->getPrefix() . "\n";
echo "API v2 rate limit: " . $apiV2->getRateLimiter()?->getMaxAttempts() . " req/min\n";
echo "Маршрутов: " . count($apiV2->getRoutes()) . "\n\n";

// Пример 4: Мультитенантность с доменами
echo "Пример 4: Мультитенантные группы\n";
echo str_repeat('=', 50) . "\n\n";

$tenant1 = $router->group(['prefix' => '/app'], function (Router $router): void {
    $router->get('/dashboard', fn (): string => 'Tenant 1 Dashboard');
    $router->get('/profile', fn (): string => 'Tenant 1 Profile');
})
    ->domain('tenant1.example.com')
    ->tag('tenant-1');

$tenant2 = $router->group(['prefix' => '/app'], function (Router $router): void {
    $router->get('/dashboard', fn (): string => 'Tenant 2 Dashboard');
    $router->get('/profile', fn (): string => 'Tenant 2 Profile');
})
    ->domain('tenant2.example.com')
    ->tag('tenant-2');

echo "Tenant 1 домен: " . $tenant1->getDomain() . "\n";
echo "Tenant 2 домен: " . $tenant2->getDomain() . "\n";
echo "Tenant 1 маршрутов: " . count($tenant1->getRoutes()) . "\n";
echo "Tenant 2 маршрутов: " . count($tenant2->getRoutes()) . "\n\n";

// Пример 5: Микросервисная архитектура с портами
echo "Пример 5: Микросервисы с изоляцией по портам\n";
echo str_repeat('=', 50) . "\n\n";

$userService = $router->group(['prefix' => '/users'], function (Router $router): void {
    $router->get('/', fn (): string => 'List Users');
    $router->get('/{id}', fn (string $id): string => "User $id");
})
    ->port(8081)
    ->tag('user-service');

$productService = $router->group(['prefix' => '/products'], function (Router $router): void {
    $router->get('/', fn (): string => 'List Products');
    $router->get('/{id}', fn (string $id): string => "Product $id");
})
    ->port(8082)
    ->tag('product-service');

echo "User Service порт: " . ($userService->getPort() ?? 'не указан') . "\n";
echo "Product Service порт: " . ($productService->getPort() ?? 'не указан') . "\n\n";

// Пример 6: HTTPS only группа
echo "Пример 6: Безопасная группа (HTTPS only)\n";
echo str_repeat('=', 50) . "\n\n";

$secureGroup = $router->group(['prefix' => '/secure'], function (Router $router): void {
    $router->get('/payments', fn (): string => 'Payments');
    $router->get('/transactions', fn (): string => 'Transactions');
})
    ->https()
    ->middleware('encryption')
    ->tag('secure');

echo "Secure группа требует HTTPS\n";
echo "Маршрутов: " . count($secureGroup->getRoutes()) . "\n\n";

// Статистика
echo "\nОбщая статистика:\n";
echo str_repeat('=', 50) . "\n";
echo "Всего маршрутов в роутере: " . count($router->getRoutes()) . "\n";
echo "Всего групп создано: 7\n";

// Тестирование dispatch
echo "\nТестирование dispatch:\n";
echo str_repeat('=', 50) . "\n";

try {
    $route = $router->dispatch('/admin/dashboard', 'GET');
    echo "✓ Маршрут найден: " . $route->getUri() . "\n";
} catch (\Exception $e) {
    echo "✗ Ошибка: " . $e->getMessage() . "\n";
}

try {
    $route = $router->dispatch('/api/v1/users', 'GET');
    echo "✓ Маршрут найден: " . $route->getUri() . "\n";
    echo "  Rate limit: " . ($route->getRateLimiter()?->getMaxAttempts() ?? 'не установлен') . " req/min\n";
} catch (\Exception $e) {
    echo "✗ Ошибка: " . $e->getMessage() . "\n";
}

echo "\n✅ Пример завершен!\n";

