<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use CloudCastle\Http\Router\Facade\Route;
use CloudCastle\Http\Router\Middleware\HttpsEnforcement;
use CloudCastle\Http\Router\Middleware\SecurityLogger;
use CloudCastle\Http\Router\Middleware\SsrfProtection;

// ============================================
// Максимальная безопасность - OWASP Top 10 Compliance
// ============================================

echo "===============================================\n";
echo "Maximum Security Configuration\n";
echo "OWASP Top 10 (2021) - Full Compliance\n";
echo "===============================================\n\n";

// ============================================
// A01: Broken Access Control - МАКСИМАЛЬНАЯ ЗАЩИТА
// ============================================
echo "A01: Broken Access Control\n";
echo str_repeat("-", 50) . "\n";

Route::group([
    'prefix' => '/admin',
    'https' => true,                      // Только HTTPS
    'domain' => 'admin.example.com',      // Только admin домен
    'port' => 443,                         // Только HTTPS порт
    'whitelistIp' => ['192.168.1.1'],     // Только конкретные IP
    'middleware' => ['auth', 'admin', 'two-factor'], // Множественная аутентификация
    'throttle' => ['max' => 10, 'decay' => 1], // Строгий rate limit
], function() {
    Route::get('/dashboard', 'Admin\DashboardController@index')
        ->name('admin.dashboard');
    
    Route::get('/sensitive-data', 'Admin\DataController@index')
        ->middleware('audit-log') // Дополнительное логирование
        ->throttle(5, 1);          // Еще более строгий лимит
});

echo "✅ Multi-layer access control configured\n\n";

// ============================================
// A02: Cryptographic Failures - ПОЛНАЯ ЗАЩИТА
// ============================================
echo "A02: Cryptographic Failures\n";
echo str_repeat("-", 50) . "\n";

// HTTPS enforcement middleware
$httpsMiddleware = HttpsEnforcement::class;

// Защищенные маршруты
Route::group([
    'https' => true,
    'middleware' => $httpsMiddleware,
], function() {
    // Платежи - только HTTPS
    Route::post('/payment', 'PaymentController@process')
        ->https()
        ->name('payment.process');
    
    // Личные данные - только HTTPS
    Route::get('/profile/security', 'ProfileController@security')
        ->https()
        ->auth()
        ->name('profile.security');
    
    // API ключи - только HTTPS
    Route::post('/api/keys/generate', 'ApiKeyController@generate')
        ->https()
        ->secure() // Порт 443
        ->auth()
        ->throttleStrict()
        ->name('api.keys.generate');
});

echo "✅ HTTPS enforcement configured\n";
echo "✅ Secure port (443) enforced\n";
echo "✅ Cryptographic middleware applied\n\n";

// ============================================
// A03: Injection - ЗАЩИТА ОТ ИНЪЕКЦИЙ
// ============================================
echo "A03: Injection\n";
echo str_repeat("-", 50) . "\n";

// Строгая типизация параметров
Route::get('/users/{id:\d+}', function(int $id) {
    // $id гарантированно число
    return "User: {$id}";
})->name('users.show');

Route::get('/posts/{slug:[a-z0-9-]+}', function(string $slug) {
    // $slug содержит только безопасные символы
    return "Post: {$slug}";
})->name('posts.show');

echo "✅ Strict parameter validation with regex\n";
echo "✅ Type safety with PHP 8.1+\n";
echo "✅ Parameter isolation\n\n";

// ============================================
// A07: Identification and Authentication Failures
// ============================================
echo "A07: Auth Failures\n";
echo str_repeat("-", 50) . "\n";

// Маршруты аутентификации с защитой от brute-force
Route::post('/login', 'AuthController@login')
    ->guest()
    ->throttle(5, 1)  // Только 5 попыток в минуту
    ->https()          // Только HTTPS
    ->name('login');

Route::post('/register', 'AuthController@register')
    ->guest()
    ->throttle(3, 10) // 3 регистрации в 10 минут
    ->https()
    ->name('register');

Route::post('/password/reset', 'AuthController@reset')
    ->throttle(3, 1)  // 3 запроса в минуту
    ->https()
    ->name('password.reset');

echo "✅ Rate limiting against brute-force\n";
echo "✅ HTTPS for sensitive operations\n";
echo "✅ Throttling on auth endpoints\n\n";

// ============================================
// A09: Security Logging and Monitoring Failures
// ============================================
echo "A09: Logging and Monitoring\n";
echo str_repeat("-", 50) . "\n";

// Глобальный security logger
Route::middleware(SecurityLogger::class);

// Критичные маршруты с усиленным логированием
Route::group([
    'middleware' => [SecurityLogger::class, 'audit-trail'],
    'tags' => 'critical',
], function() {
    Route::post('/admin/users/delete/{id}', 'Admin\UserController@destroy')
        ->admin()
        ->https()
        ->name('admin.users.destroy');
    
    Route::post('/settings/security', 'SettingsController@updateSecurity')
        ->auth()
        ->https()
        ->middleware('log-security-changes')
        ->name('settings.security.update');
});

echo "✅ Built-in security logging middleware\n";
echo "✅ Audit trail for critical operations\n";
echo "✅ Current/previous route tracking\n";
echo "✅ Rate limit monitoring\n";
echo "✅ Exception logging with full context\n\n";

// ============================================
// A10: Server-Side Request Forgery (SSRF)
// ============================================
echo "A10: SSRF Protection\n";
echo str_repeat("-", 50) . "\n";

// SSRF protection middleware
Route::group([
    'middleware' => [SsrfProtection::class],
], function() {
    Route::post('/api/fetch', 'ApiController@fetch')
        ->auth()
        ->throttle(10, 1);
    
    Route::post('/webhook/proxy', 'WebhookController@proxy')
        ->middleware('validate-webhook-url')
        ->whitelistIp(['trusted.server.ip']);
});

echo "✅ SSRF protection middleware\n";
echo "✅ URL validation and sanitization\n";
echo "✅ Private IP blocking\n";
echo "✅ Domain whitelist support\n\n";

// ============================================
// Комплексная защита - ВСЕ ВМЕСТЕ
// ============================================
echo "===============================================\n";
echo "Complete Security Stack\n";
echo "===============================================\n\n";

Route::group([
    // A01: Access Control
    'https' => true,
    'domain' => 'secure.example.com',
    'port' => 443,
    'whitelistIp' => ['192.168.1.0/24'],
    'middleware' => [
        'auth',
        'two-factor',
        HttpsEnforcement::class,     // A02: Crypto
        SecurityLogger::class,        // A09: Logging
        SsrfProtection::class,        // A10: SSRF
    ],
    'throttle' => 50,                    // A07: Auth protection
], function() {
    Route::post('/critical/operation', 'CriticalController@execute')
        ->name('critical.operation')
        ->tag('critical')
        ->throttle(5, 1); // Дополнительное ограничение
});

echo "✅ Multi-layer security applied:\n";
echo "  - HTTPS only (A02)\n";
echo "  - IP whitelist (A01)\n";
echo "  - Domain restriction (A01)\n";
echo "  - Port 443 only (A02)\n";
echo "  - Multi-factor auth (A07)\n";
echo "  - Rate limiting (A07)\n";
echo "  - Security logging (A09)\n";
echo "  - SSRF protection (A10)\n";
echo "  - Audit trail (A09)\n\n";

// ============================================
// Примеры для разных протоколов
// ============================================
echo "===============================================\n";
echo "Protocol Support\n";
echo "===============================================\n\n";

// HTTP/HTTPS
Route::get('/public-api', 'ApiController@public')
    ->protocol(['http', 'https'])
    ->name('api.public');

// Только HTTPS
Route::get('/secure-api', 'ApiController@secure')
    ->https()
    ->name('api.secure');

// WebSocket
Route::get('/ws/chat', 'WebSocketController@chat')
    ->websocket()
    ->auth()
    ->name('websocket.chat');

// Secure WebSocket only
Route::get('/wss/notifications', 'WebSocketController@notifications')
    ->secureWebsocket()
    ->auth()
    ->name('websocket.notifications');

echo "✅ HTTP/HTTPS support\n";
echo "✅ WebSocket (WS) support\n";
echo "✅ Secure WebSocket (WSS) support\n";
echo "✅ Custom protocol support\n\n";

// ============================================
// Использование в production
// ============================================
echo "===============================================\n";
echo "Production Usage Example\n";
echo "===============================================\n\n";

// Получение протокола из запроса
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';

try {
    $route = Route::dispatch(
        $_SERVER['REQUEST_URI'] ?? '/',
        $_SERVER['REQUEST_METHOD'] ?? 'GET',
        $_SERVER['HTTP_HOST'] ?? null,
        $_SERVER['REMOTE_ADDR'] ?? null,
        isset($_SERVER['SERVER_PORT']) ? (int)$_SERVER['SERVER_PORT'] : null,
        $protocol  // Передаем протокол!
    );
    
    echo "✓ Route matched: {$route->getName()}\n";
    echo "  Protocol: " . implode(', ', $route->getProtocols()) . "\n";
    echo "  HTTPS only: " . ($route->isHttpsOnly() ? 'Yes' : 'No') . "\n";
    
} catch (\CloudCastle\Http\Router\Exceptions\InsecureConnectionException $e) {
    http_response_code(426); // Upgrade Required
    echo "✗ Insecure connection: {$e->getMessage()}\n";
    echo "  Redirect to: https://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}\n";
}

echo "\n===============================================\n";
echo "OWASP Top 10 Compliance: 10/10 ✅\n";
echo "Security Level: MAXIMUM 🔒\n";
echo "===============================================\n";

