<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use CloudCastle\Http\Router\Facade\Route;

// ============================================
// Настройка маршрутов для демонстрации фильтрации
// ============================================

// Публичные маршруты
Route::get('/', fn() => 'home')->name('home')->tag('public');
Route::get('/about', fn() => 'about')->name('about')->tag('public');

// API маршруты
Route::group(['prefix' => '/api/v1', 'tags' => 'api'], function() {
    Route::get('/users', fn() => 'users')
        ->name('api.users')
        ->throttle(100, 1);
    
    Route::get('/posts', fn() => 'posts')
        ->name('api.posts')
        ->tag('public')
        ->throttle(100, 1);
    
    Route::post('/admin/users', fn() => 'admin users')
        ->middleware('auth')
        ->whitelistIp(['192.168.1.1']);
});

// Админ панель
Route::group([
    'prefix' => '/admin',
    'domain' => 'admin.example.com',
    'middleware' => ['auth', 'admin'],
], function() {
    Route::get('/dashboard', fn() => 'dashboard')
        ->name('admin.dashboard')
        ->tag('admin');
    
    Route::get('/settings', fn() => 'settings')
        ->name('admin.settings')
        ->tag('admin')
        ->throttle(10, 1);
});

// Метрики на отдельном порту
Route::get('/metrics', fn() => 'metrics')
    ->port(9090)
    ->whitelistIp(['127.0.0.1', '::1']);

echo "===============================================\n";
echo "Route Filtering Examples\n";
echo "===============================================\n\n";

// ============================================
// 1. Получение текущего маршрута
// ============================================
echo "1. Current Route:\n";
echo str_repeat("-", 50) . "\n";

$route = Route::dispatch('/', 'GET');
echo "Current route name: " . Route::currentRouteName() . "\n";
echo "Is 'home' route? " . (Route::currentRouteNamed('home') ? 'Yes' : 'No') . "\n";
echo "Current route URI: " . Route::current()?->getUri() . "\n\n";

// ============================================
// 2. Фильтрация по методу HTTP
// ============================================
echo "2. Routes by HTTP Method:\n";
echo str_repeat("-", 50) . "\n";

$getRoutes = Route::router()->getRoutesByMethod('GET');
$postRoutes = Route::router()->getRoutesByMethod('POST');

echo "GET routes: " . count($getRoutes) . "\n";
echo "POST routes: " . count($postRoutes) . "\n\n";

// ============================================
// 3. Фильтрация по тегам
// ============================================
echo "3. Routes by Tags:\n";
echo str_repeat("-", 50) . "\n";

$apiRoutes = Route::getRoutesByTag('api');
$publicRoutes = Route::getRoutesByTag('public');

echo "API routes: " . count($apiRoutes) . "\n";
echo "Public routes: " . count($publicRoutes) . "\n";
echo "All tags: " . implode(', ', Route::router()->getAllTags()) . "\n\n";

// ============================================
// 4. Фильтрация по домену
// ============================================
echo "4. Routes by Domain:\n";
echo str_repeat("-", 50) . "\n";

$adminDomainRoutes = Route::router()->getRoutesByDomain('admin.example.com');
$allDomains = Route::router()->getAllDomains();

echo "Admin domain routes: " . count($adminDomainRoutes) . "\n";
echo "All domains: " . implode(', ', $allDomains) . "\n\n";

// ============================================
// 5. Фильтрация по порту
// ============================================
echo "5. Routes by Port:\n";
echo str_repeat("-", 50) . "\n";

$portRoutes = Route::router()->getRoutesByPort(9090);
$allPorts = Route::router()->getAllPorts();

echo "Port 9090 routes: " . count($portRoutes) . "\n";
echo "All ports: " . implode(', ', $allPorts) . "\n\n";

// ============================================
// 6. Фильтрация по IP ограничениям
// ============================================
echo "6. Routes by IP Restrictions:\n";
echo str_repeat("-", 50) . "\n";

$whitelistedRoutes = Route::router()->getRoutesByWhitelistedIp('192.168.1.1');
$restrictedRoutes = Route::router()->getRoutesWithIpRestrictions();

echo "Routes allowing 192.168.1.1: " . count($whitelistedRoutes) . "\n";
echo "Routes with IP restrictions: " . count($restrictedRoutes) . "\n\n";

// ============================================
// 7. Фильтрация по middleware
// ============================================
echo "7. Routes by Middleware:\n";
echo str_repeat("-", 50) . "\n";

$authRoutes = Route::router()->getRoutesByMiddleware('auth');
$adminMiddlewareRoutes = Route::router()->getRoutesByMiddleware('admin');

echo "Routes with 'auth' middleware: " . count($authRoutes) . "\n";
echo "Routes with 'admin' middleware: " . count($adminMiddlewareRoutes) . "\n\n";

// ============================================
// 8. Маршруты с ограничениями
// ============================================
echo "8. Routes with Throttling:\n";
echo str_repeat("-", 50) . "\n";

$throttledRoutes = Route::router()->getThrottledRoutes();

echo "Throttled routes: " . count($throttledRoutes) . "\n";
foreach ($throttledRoutes as $route) {
    $limiter = $route->getRateLimiter();
    if ($limiter) {
        echo "  - {$route->getUri()}: {$limiter->getMaxAttempts()} req/{$limiter->getDecayMinutes()}min\n";
    }
}
echo "\n";

// ============================================
// 9. Фильтрация по префиксу URI
// ============================================
echo "9. Routes by Prefix:\n";
echo str_repeat("-", 50) . "\n";

$apiPrefixRoutes = Route::router()->getRoutesByPrefix('/api');
$adminPrefixRoutes = Route::router()->getRoutesByPrefix('/admin');

echo "Routes with /api prefix: " . count($apiPrefixRoutes) . "\n";
echo "Routes with /admin prefix: " . count($adminPrefixRoutes) . "\n\n";

// ============================================
// 10. Комплексный поиск
// ============================================
echo "10. Complex Search:\n";
echo str_repeat("-", 50) . "\n";

$complexSearch = Route::router()->searchRoutes([
    'tag' => 'api',
    'method' => 'GET',
    'has_throttle' => true,
]);

echo "GET API routes with throttling: " . count($complexSearch) . "\n";

$adminSearch = Route::router()->searchRoutes([
    'prefix' => '/admin',
    'has_domain' => true,
]);

echo "Admin routes with domain: " . count($adminSearch) . "\n\n";

// ============================================
// 11. Статистика маршрутов
// ============================================
echo "11. Route Statistics:\n";
echo str_repeat("-", 50) . "\n";

$stats = Route::router()->getRouteStats();

echo "Total routes: {$stats['total']}\n";
echo "Named routes: {$stats['named']}\n";
echo "Tagged categories: {$stats['tagged']}\n";
echo "With middleware: {$stats['with_middleware']}\n";
echo "With domain: {$stats['with_domain']}\n";
echo "With port: {$stats['with_port']}\n";
echo "With IP restrictions: {$stats['with_ip_restrictions']}\n";
echo "Throttled: {$stats['throttled']}\n\n";

echo "By Method:\n";
foreach ($stats['by_method'] as $method => $count) {
    echo "  {$method}: {$count}\n";
}
echo "\n";

// ============================================
// 12. Проверка существования
// ============================================
echo "12. Existence Checks:\n";
echo str_repeat("-", 50) . "\n";

echo "Has route 'home'? " . (Route::router()->hasRoute('home') ? 'Yes' : 'No') . "\n";
echo "Has route 'nonexistent'? " . (Route::router()->hasRoute('nonexistent') ? 'Yes' : 'No') . "\n";
echo "Has tag 'api'? " . (Route::router()->hasTag('api') ? 'Yes' : 'No') . "\n";
echo "Has tag 'nonexistent'? " . (Route::router()->hasTag('nonexistent') ? 'Yes' : 'No') . "\n\n";

// ============================================
// 13. Получение маршрутов по контроллеру
// ============================================
echo "13. Named Routes Matching Pattern:\n";
echo str_repeat("-", 50) . "\n";

$adminNamedRoutes = Route::router()->getNamedRoutesMatching('admin');
echo "Routes with 'admin' in name: " . count($adminNamedRoutes) . "\n";
foreach ($adminNamedRoutes as $name => $route) {
    echo "  - {$name}: {$route->getUri()}\n";
}

echo "\n✓ All filtering examples completed!\n";

