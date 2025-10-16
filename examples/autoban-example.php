<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use CloudCastle\Http\Router\Facade\Route;
use CloudCastle\Http\Router\Exceptions\TooManyRequestsException;
use CloudCastle\Http\Router\Exceptions\BannedException;

echo "===============================================\n";
echo "Auto-Ban System Examples\n";
echo "===============================================\n\n";

// ============================================
// 1. Базовый автобан
// ============================================
echo "1. Базовый автобан:\n";
echo str_repeat("-", 50) . "\n";

Route::post('/api/data', fn() => 'data')
    ->throttleWithBan(
        maxAttempts: 5,           // 5 запросов в минуту
        decayMinutes: 1,           // Окно 1 минута
        maxViolations: 3,          // 3 нарушения до бана
        banDurationMinutes: 60     // Бан на 1 час
    );

echo "Маршрут настроен:\n";
echo "  • Лимит: 5 запросов/мин\n";
echo "  • Макс нарушений: 3\n";
echo "  • Длительность бана: 60 минут\n\n";

// ============================================
// 2. Строгий автобан для логина
// ============================================
echo "2. Строгий автобан для логина:\n";
echo str_repeat("-", 50) . "\n";

Route::post('/login', fn() => 'login')
    ->throttleWithBan(
        maxAttempts: 3,            // 3 попытки в минуту
        decayMinutes: 1,           // Окно 1 минута
        maxViolations: 2,          // 2 нарушения до бана
        banDurationMinutes: 120    // Бан на 2 часа
    );

echo "Маршрут настроен:\n";
echo "  • Лимит: 3 попытки/мин\n";
echo "  • Макс нарушений: 2\n";
echo "  • Длительность бана: 120 минут\n";
echo "  • Защита от brute-force атак!\n\n";

// ============================================
// 3. Мгновенный бан для критичных операций
// ============================================
echo "3. Мгновенный бан для критичных операций:\n";
echo str_repeat("-", 50) . "\n";

Route::delete('/admin/critical', fn() => 'deleted')
    ->middleware(['auth', 'admin'])
    ->throttleWithBan(
        maxAttempts: 1,            // 1 запрос в минуту
        decayMinutes: 1,           // Окно 1 минута
        maxViolations: 1,          // Бан сразу при первом нарушении
        banDurationMinutes: 1440   // Бан на 24 часа
    );

echo "Маршрут настроен:\n";
echo "  • Лимит: 1 запрос/мин\n";
echo "  • Макс нарушений: 1 (мгновенный бан!)\n";
echo "  • Длительность бана: 1440 минут (24 часа)\n\n";

// ============================================
// 4. Разные уровни для разных эндпоинтов
// ============================================
echo "4. Разные уровни для разных эндпоинтов:\n";
echo str_repeat("-", 50) . "\n";

// Мягкий для публичного API
Route::get('/api/public/data', fn() => 'public')
    ->throttleWithBan(100, 1, 5, 30); // 100/min, 5 нарушений, 30 мин бан

// Средний для authenticated API
Route::get('/api/protected/data', fn() => 'protected')
    ->auth()
    ->throttleWithBan(50, 1, 3, 60); // 50/min, 3 нарушения, 1 час бан

// Строгий для admin API
Route::post('/api/admin/action', fn() => 'admin')
    ->admin()
    ->throttleWithBan(10, 1, 2, 240); // 10/min, 2 нарушения, 4 часа бан

echo "Настроены 3 уровня защиты:\n";
echo "  • Public: 100/min, бан 30 мин\n";
echo "  • Protected: 50/min, бан 1 час\n";
echo "  • Admin: 10/min, бан 4 часа\n\n";

// ============================================
// 5. Симуляция работы автобана
// ============================================
echo "5. Симуляция работы автобана:\n";
echo str_repeat("-", 50) . "\n";

Route::get('/test/autoban', fn() => 'test')
    ->throttleWithBan(2, 1, 2, 5); // 2/min, 2 нарушения, 5 мин бан

$testIp = '192.168.1.100';

try {
    echo "Попытка 1: ";
    Route::dispatch('/test/autoban', 'GET', null, $testIp);
    echo "✓ OK\n";
    
    echo "Попытка 2: ";
    Route::dispatch('/test/autoban', 'GET', null, $testIp);
    echo "✓ OK\n";
    
    echo "Попытка 3 (превышение лимита): ";
    Route::dispatch('/test/autoban', 'GET', null, $testIp);
    echo "✗ Не должно быть здесь\n";
} catch (TooManyRequestsException $e) {
    echo "✓ Rate limit exceeded (violation 1)\n";
}

echo "\nПосле сброса окна...\n";
sleep(61);

try {
    echo "Попытка 1 (новое окно): ";
    Route::dispatch('/test/autoban', 'GET', null, $testIp);
    echo "✓ OK\n";
    
    echo "Попытка 2: ";
    Route::dispatch('/test/autoban', 'GET', null, $testIp);
    echo "✓ OK\n";
    
    echo "Попытка 3 (превышение лимита снова): ";
    Route::dispatch('/test/autoban', 'GET', null, $testIp);
    echo "✗ Не должно быть здесь\n";
} catch (TooManyRequestsException $e) {
    echo "✓ Rate limit exceeded (violation 2 - BANNED!)\n";
}

try {
    echo "\nПопытка доступа после бана: ";
    Route::dispatch('/test/autoban', 'GET', null, $testIp);
    echo "✗ Не должно быть здесь\n";
} catch (BannedException $e) {
    echo "✗ BANNED!\n";
    echo "  IP: " . $e->getBannedIp() . "\n";
    echo "  Reason: " . $e->getReason() . "\n";
    echo "  Time remaining: " . gmdate('i:s', $e->getTimeRemaining()) . "\n";
    echo "  Expires at: " . date('Y-m-d H:i:s', $e->getBanExpiresAt()) . "\n";
}

echo "\n";

// ============================================
// 6. Проверка статистики банов
// ============================================
echo "6. Статистика банов:\n";
echo str_repeat("-", 50) . "\n";

$route = Route::getRouteByName(null); // Get any route with ban manager
if ($route && $rateLimiter = $route->getRateLimiter()) {
    if ($banManager = $rateLimiter->getBanManager()) {
        $stats = $banManager->getStatistics();
        
        echo "Всего забанено: " . $stats['total_banned'] . "\n";
        echo "Всего нарушений: " . $stats['total_violations'] . "\n";
        echo "Уникальных IP с нарушениями: " . $stats['unique_ips_with_violations'] . "\n";
        echo "Макс нарушений до бана: " . $stats['max_violations'] . "\n";
        echo "Длительность бана: " . ($stats['ban_duration'] / 60) . " минут\n";
        
        echo "\nЗабаненные IP:\n";
        foreach ($banManager->getBannedIps() as $ip => $expiration) {
            $remaining = $expiration - time();
            echo "  • {$ip}: еще " . gmdate('H:i:s', $remaining) . "\n";
        }
    }
}

echo "\n";

// ============================================
// 7. Группа с автобаном
// ============================================
echo "7. Группа маршрутов с автобаном:\n";
echo str_repeat("-", 50) . "\n";

Route::group([
    'prefix' => 'api/v1',
    'middleware' => 'api',
], function() {
    // Разные лимиты для разных эндпоинтов в группе
    
    Route::get('/users', fn() => 'users')
        ->throttleWithBan(100, 1, 3, 30);
    
    Route::post('/users', fn() => 'create user')
        ->throttleWithBan(20, 1, 2, 60); // Строже для записи
    
    Route::delete('/users/{id}', fn() => 'delete user')
        ->throttleWithBan(5, 1, 1, 120); // Очень строго для удаления
});

echo "Настроено 3 маршрута с разными уровнями автобана\n\n";

// ============================================
// 8. Практические рекомендации
// ============================================
echo "===============================================\n";
echo "Рекомендации по настройке\n";
echo "===============================================\n\n";

echo "Публичные API:\n";
echo "  throttleWithBan(100, 1, 5, 30)\n";
echo "  • 100 запросов/мин\n";
echo "  • 5 нарушений\n";
echo "  • Бан 30 минут\n\n";

echo "Аутентификация:\n";
echo "  throttleWithBan(5, 1, 2, 120)\n";
echo "  • 5 попыток/мин\n";
echo "  • 2 нарушения\n";
echo "  • Бан 2 часа\n\n";

echo "Критичные операции:\n";
echo "  throttleWithBan(1, 1, 1, 1440)\n";
echo "  • 1 запрос/мин\n";
echo "  • 1 нарушение = мгновенный бан\n";
echo "  • Бан 24 часа\n\n";

echo "Admin панель:\n";
echo "  throttleWithBan(10, 1, 1, 480)\n";
echo "  • 10 запросов/мин\n";
echo "  • 1 нарушение = бан\n";
echo "  • Бан 8 часов\n\n";

echo "✓ Автобан защищает от:\n";
echo "  • Brute-force атак\n";
echo "  • DDoS атак\n";
echo "  • Abuse API\n";
echo "  • Сканирования\n";
echo "  • Повторных нарушений\n";

