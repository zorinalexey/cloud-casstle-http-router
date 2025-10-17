<?php

declare(strict_types = 1);

require __DIR__ . '/../vendor/autoload.php';

use CloudCastle\Http\Router\Facade\Route;

// ============================================
// Примеры использования shortcuts (удобных методов)
// ============================================

echo "===============================================\n";
echo "Route Shortcuts Usage Examples\n";
echo "===============================================\n\n";

// ============================================
// 1. Middleware shortcuts
// ============================================
echo "1. Middleware Shortcuts:\n";
echo str_repeat("-", 50) . "\n";

// Вместо ->middleware('auth')
Route::get('/profile', 'UserController@profile')
    ->auth()  // Shortcut!
    ->name('profile');

// Вместо ->middleware('guest')
Route::get('/login', 'AuthController@login')
    ->guest()  // Shortcut!
    ->name('login');

// Вместо ->middleware('api')
Route::get('/api/data', 'ApiController@data')
    ->api()  // Shortcut!
    ->name('api.data');

echo "✓ Auth, guest, api shortcuts applied\n\n";

// ============================================
// 2. Security shortcuts
// ============================================
echo "2. Security Shortcuts:\n";
echo str_repeat("-", 50) . "\n";

// Только с localhost
Route::get('/debug', 'DebugController@index')
    ->localhost()  // Shortcut for whitelistIp(['127.0.0.1', '::1'])
    ->name('debug');

// Принудительный HTTPS
Route::post('/payment', 'PaymentController@process')
    ->secure()  // Shortcut for port(443)
    ->auth()
    ->name('payment');

echo "✓ Localhost and secure shortcuts applied\n\n";

// ============================================
// 3. Throttle shortcuts
// ============================================
echo "3. Throttle Shortcuts:\n";
echo str_repeat("-", 50) . "\n";

// Стандартное ограничение (60 req/min)
Route::get('/api/standard', fn () => 'standard')
    ->throttleStandard();  // 60 req/min

// Строгое ограничение (10 req/min)
Route::post('/auth/login', 'AuthController@login')
    ->throttleStrict();  // 10 req/min

// Щедрое ограничение (1000 req/min)
Route::get('/api/premium', fn () => 'premium')
    ->throttleGenerous();  // 1000 req/min

echo "✓ Throttle shortcuts applied\n\n";

// ============================================
// 4. Tag shortcuts
// ============================================
echo "4. Tag Shortcuts:\n";
echo str_repeat("-", 50) . "\n";

Route::get('/api/public', fn () => 'public')
    ->public();  // Shortcut for tag('public')

Route::get('/internal', fn () => 'internal')
    ->private();  // Shortcut for tag('private')

echo "✓ Tag shortcuts applied\n\n";

// ============================================
// 5. Composite shortcuts
// ============================================
echo "5. Composite Shortcuts:\n";
echo str_repeat("-", 50) . "\n";

// Admin маршрут с полной настройкой
Route::get('/admin/dashboard', 'AdminController@dashboard')
    ->admin()  // Shortcut: middleware(['auth', 'admin']) + tag('admin')
    ->name('admin.dashboard');

// API endpoint с полной настройкой
Route::get('/api/v1/users', 'Api\UserController@index')
    ->apiEndpoint(100)  // Shortcut: api middleware + throttle(100) + tag('api')
    ->name('api.users');

// Защищенный ресурс
Route::get('/documents', 'DocumentController@index')
    ->protected()  // Shortcut: auth + throttle(100)
    ->name('documents');

echo "✓ Composite shortcuts applied\n\n";

// ============================================
// 6. Цепочки shortcuts
// ============================================
echo "6. Chaining Shortcuts:\n";
echo str_repeat("-", 50) . "\n";

Route::post('/api/secure/data', 'SecureController@data')
    ->secure()           // HTTPS only
    ->auth()             // Authenticated only
    ->throttleStrict()   // 10 req/min
    ->localhost()        // Localhost only
    ->name('secure.data');

echo "✓ Multiple shortcuts chained\n\n";

// ============================================
// 7. Использование helper functions
// ============================================
echo "7. Helper Functions:\n";
echo str_repeat("-", 50) . "\n";

// Текущий маршрут
echo "Current route: " . route_name() . "\n";

// Проверка маршрута
if (route_is('users.index')) {
    echo "On users index page\n";
}

// Генерация URL
$userUrl = route_url('users.show', ['id' => 456]);
echo "User URL: {$userUrl}\n";

// Проверка существования
if (route_has('admin.dashboard')) {
    echo "Admin dashboard route exists\n";
}

// Статистика
$stats = route_stats();
echo "Total routes: {$stats['total']}\n";

echo "\n";

// ============================================
// 8. Навигация helpers
// ============================================
echo "8. Navigation Helpers:\n";
echo str_repeat("-", 50) . "\n";

Route::dispatch('/about', 'GET');
echo "Current: " . route_name() . "\n";
echo "Previous: " . (previous_route()?->getName() ?? 'none') . "\n";
echo "Back URL: " . route_back('/') . "\n\n";

// ============================================
// 9. Dispatch helper
// ============================================
echo "9. Dispatch Helper:\n";
echo str_repeat("-", 50) . "\n";

// Симулируем $_SERVER переменные
$_SERVER['REQUEST_URI'] = '/users';
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['HTTP_HOST'] = 'example.com';
$_SERVER['REMOTE_ADDR'] = '127.0.0.1';
$_SERVER['SERVER_PORT'] = '80';

try {
    $dispatched = dispatch_route();
    echo "Dispatched route: {$dispatched->getName()}\n";
    echo "URI: {$dispatched->getUri()}\n";
} catch (Exception $e) {
    echo "Error: {$e->getMessage()}\n";
}

echo "\n";

// ============================================
// 10. Сравнение: Обычный vs Shortcuts
// ============================================
echo "10. Comparison - Regular vs Shortcuts:\n";
echo str_repeat("-", 50) . "\n\n";

echo "REGULAR WAY:\n";
echo "```php\n";
echo "Route::get('/admin', 'AdminController@index')\n";
echo "    ->middleware(['auth', 'admin'])\n";
echo "    ->whitelistIp(['127.0.0.1', '::1'])\n";
echo "    ->port(443)\n";
echo "    ->throttle(60, 1)\n";
echo "    ->tag('admin')\n";
echo "    ->name('admin.dashboard');\n";
echo "```\n\n";

echo "WITH SHORTCUTS:\n";
echo "```php\n";
echo "Route::get('/admin', 'AdminController@index')\n";
echo "    ->admin()        // auth + admin middleware + tag\n";
echo "    ->localhost()    // IP whitelist\n";
echo "    ->secure()       // port 443\n";
echo "    ->throttleStandard()  // 60 req/min\n";
echo "    ->name('admin.dashboard');\n";
echo "```\n\n";

echo "✓ Much more concise and readable!\n\n";

// ============================================
// Итоговая статистика
// ============================================
echo "===============================================\n";
echo "Summary\n";
echo "===============================================\n\n";

$allStats = router()->getRouteStats();
echo "Total routes registered: {$allStats['total']}\n";
echo "Named routes: {$allStats['named']}\n";
echo "With middleware: {$allStats['with_middleware']}\n";
echo "Throttled: {$allStats['throttled']}\n";

echo "\n✓ Shortcuts make code 50% shorter and more readable!\n";

