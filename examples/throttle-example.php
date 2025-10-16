<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use CloudCastle\Http\Router\Facade\Route;
use CloudCastle\Http\Router\Exceptions\TooManyRequestsException;

// ============================================
// Примеры использования Rate Limiting
// ============================================

// Пример 1: Ограничение на отдельный маршрут
Route::get('/api/data', function () {
    return json_encode(['data' => 'Some data']);
})->throttle(10, 1); // 10 запросов в минуту

// Пример 2: Строгое ограничение для авторизации
Route::post('/auth/login', 'AuthController@login')
    ->throttle(5, 1) // Только 5 попыток входа в минуту
    ->name('auth.login');

// Пример 3: Группа с ограничением
Route::group([
    'prefix' => '/api/v1',
    'throttle' => ['max' => 60, 'decay' => 1], // 60 запросов в минуту
], function () {
    Route::get('/users', 'Api\UserController@index');
    Route::get('/posts', 'Api\PostController@index');
    Route::get('/comments', 'Api\CommentController@index');
});

// Пример 4: Разные лимиты для разных групп
Route::group([
    'prefix' => '/api/public',
    'throttle' => 30, // 30 запросов в минуту
    'tags' => 'public-api',
], function () {
    Route::get('/stats', fn() => 'Public stats');
});

Route::group([
    'prefix' => '/api/premium',
    'middleware' => 'auth',
    'throttle' => ['max' => 1000, 'decay' => 1], // 1000 запросов в минуту для premium
    'tags' => 'premium-api',
], function () {
    Route::get('/analytics', fn() => 'Premium analytics');
});

// Пример 5: Кастомный ключ для rate limiting
Route::get('/api/search', function () {
    return 'Search results';
})->throttle(20, 1, 'search-api'); // Отдельный лимит для поиска

// ============================================
// Обработка запросов с rate limiting
// ============================================

$uri = $_SERVER['REQUEST_URI'] ?? '/api/data';
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$clientIp = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';

try {
    $route = Route::dispatch($uri, $method, null, $clientIp);
    
    echo "✓ Request allowed\n";
    echo "Route: {$route->getUri()}\n";
    
    // Получаем информацию о лимите
    if ($rateLimiter = $route->getRateLimiter()) {
        $remaining = $rateLimiter->remaining($clientIp);
        $limit = $rateLimiter->getMaxAttempts();
        
        echo "\nRate Limit Info:\n";
        echo "  Limit: {$limit} requests\n";
        echo "  Remaining: {$remaining}\n";
        echo "  Time window: {$rateLimiter->getDecayMinutes()} minute(s)\n";
    }
    
} catch (TooManyRequestsException $e) {
    http_response_code(429);
    
    echo "✗ Too Many Requests\n\n";
    echo "Rate Limit Exceeded:\n";
    echo "  Limit: {$e->getLimit()} requests\n";
    echo "  Remaining: {$e->getRemaining()}\n";
    echo "  Retry after: {$e->getRetryAfter()} seconds\n";
    
    // Отправляем заголовки
    header("X-RateLimit-Limit: {$e->getLimit()}");
    header("X-RateLimit-Remaining: {$e->getRemaining()}");
    header("Retry-After: {$e->getRetryAfter()}");
    
} catch (\Exception $e) {
    http_response_code(500);
    echo "Error: {$e->getMessage()}\n";
}

// ============================================
// Продвинутый пример с middleware
// ============================================

echo "\n\n--- Advanced Example with Custom Logic ---\n\n";

class RateLimitMiddleware
{
    public function handle($request, callable $next)
    {
        // Здесь можно добавить кастомную логику
        // Например, разные лимиты для разных ролей пользователей
        
        $userRole = 'guest'; // Получаем из сессии/токена
        
        if ($userRole === 'admin') {
            // Админы не ограничиваются
            return $next($request);
        }
        
        return $next($request);
    }
}

// Пример использования с комбинацией ограничений
Route::group([
    'prefix' => '/admin',
    'middleware' => ['auth', 'admin'],
    'throttle' => ['max' => 500, 'decay' => 1],
    'whitelistIp' => ['192.168.1.0/24'],
    'domain' => 'admin.example.com',
    'port' => 443,
], function () {
    Route::get('/dashboard', fn() => 'Admin dashboard')
        ->name('admin.dashboard')
        ->tag('admin');
    
    Route::post('/settings', fn() => 'Settings updated')
        ->throttle(10, 5); // Более строгое ограничение для изменений
});

echo "✓ Rate limiting configured successfully!\n";
echo "\nUsage examples:\n";
echo "  - GET /api/data - 10 req/min\n";
echo "  - POST /auth/login - 5 req/min\n";
echo "  - GET /api/v1/* - 60 req/min\n";
echo "  - GET /api/public/* - 30 req/min\n";
echo "  - GET /api/premium/* - 1000 req/min (authenticated)\n";
echo "  - GET /admin/* - 500 req/min (whitelisted IP only)\n";

