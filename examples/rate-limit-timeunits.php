<?php

declare(strict_types = 1);

require __DIR__ . '/../vendor/autoload.php';

use CloudCastle\Http\Router\Facade\Route;
use CloudCastle\Http\Router\RateLimiter;
use CloudCastle\Http\Router\TimeUnit;

echo "===============================================\n";
echo "Rate Limiting - Различные временные окна\n";
echo "===============================================\n\n";

// ============================================
// 1. Per Second - Ограничение по секундам
// ============================================
echo "1. Ограничение по секундам:\n";
echo str_repeat("-", 50) . "\n";

Route::get('/api/realtime', fn () => 'realtime data')
    ->perSecond(10); // 10 запросов в секунду

echo "• 10 запросов в секунду\n";
echo "• Идеально для realtime API\n";
echo "• WebSocket endpoints\n\n";

// Или через throttle
Route::get('/api/fast', fn () => 'fast')
    ->throttle(5, 1); // 5 запросов в 1 секунду

echo "• 5 запросов в 1 секунду\n\n";

// Несколько секунд
Route::get('/api/burst', fn () => 'burst')
    ->perSecond(100, 5); // 100 запросов в 5 секунд

echo "• 100 запросов в 5 секунд\n";
echo "• Burst protection\n\n";

// ============================================
// 2. Per Minute - Ограничение по минутам
// ============================================
echo "2. Ограничение по минутам:\n";
echo str_repeat("-", 50) . "\n";

Route::post('/api/submit', fn () => 'submitted')
    ->perMinute(60); // 60 запросов в минуту

echo "• 60 запросов в минуту\n";
echo "• Стандартное API ограничение\n\n";

// Несколько минут
Route::post('/api/upload', fn () => 'uploaded')
    ->perMinute(10, 5); // 10 запросов в 5 минут

echo "• 10 запросов в 5 минут\n";
echo "• Для тяжелых операций\n\n";

// ============================================
// 3. Per Hour - Ограничение по часам
// ============================================
echo "3. Ограничение по часам:\n";
echo str_repeat("-", 50) . "\n";

Route::get('/api/reports', fn () => 'report')
    ->perHour(1000); // 1000 запросов в час

echo "• 1000 запросов в час\n";
echo "• Для аналитики и отчетов\n\n";

Route::post('/api/export', fn () => 'export')
    ->perHour(5, 2); // 5 запросов в 2 часа

echo "• 5 запросов в 2 часа\n";
echo "• Для экспорта данных\n\n";

// ============================================
// 4. Per Day - Ограничение по дням
// ============================================
echo "4. Ограничение по дням:\n";
echo str_repeat("-", 50) . "\n";

Route::post('/api/email', fn () => 'sent')
    ->perDay(100); // 100 запросов в день

echo "• 100 запросов в день\n";
echo "• Email рассылки\n\n";

Route::get('/api/downloads', fn () => 'file')
    ->perDay(50, 7); // 50 запросов в 7 дней (неделю)

echo "• 50 запросов в 7 дней\n";
echo "• Для загрузок файлов\n\n";

// ============================================
// 5. Per Week - Ограничение по неделям
// ============================================
echo "5. Ограничение по неделям:\n";
echo str_repeat("-", 50) . "\n";

Route::post('/api/backup', fn () => 'backed up')
    ->perWeek(7); // 7 запросов в неделю

echo "• 7 запросов в неделю\n";
echo "• Для резервного копирования\n\n";

Route::post('/api/billing', fn () => 'billed')
    ->perWeek(1, 4); // 1 запрос в 4 недели

echo "• 1 запрос в 4 недели\n";
echo "• Для billing операций\n\n";

// ============================================
// 6. Per Month - Ограничение по месяцам
// ============================================
echo "6. Ограничение по месяцам:\n";
echo str_repeat("-", 50) . "\n";

Route::post('/api/subscription', fn () => 'subscribed')
    ->perMonth(1); // 1 запрос в месяц

echo "• 1 запрос в месяц\n";
echo "• Для подписок\n\n";

Route::get('/api/analytics/monthly', fn () => 'stats')
    ->perMonth(30, 3); // 30 запросов в 3 месяца

echo "• 30 запросов в 3 месяца\n";
echo "• Для квартальной аналитики\n\n";

// ============================================
// 7. С использованием TimeUnit enum
// ============================================
echo "7. Использование TimeUnit enum:\n";
echo str_repeat("-", 50) . "\n";

// Создание через RateLimiter::make()
$limiterSecond = RateLimiter::make(100, 1, TimeUnit::SECOND);
$limiterMinute = RateLimiter::make(1000, 1, TimeUnit::MINUTE);
$limiterHour = RateLimiter::make(10000, 1, TimeUnit::HOUR);
$limiterDay = RateLimiter::make(50000, 1, TimeUnit::DAY);
$limiterWeek = RateLimiter::make(100000, 1, TimeUnit::WEEK);
$limiterMonth = RateLimiter::make(1000000, 1, TimeUnit::MONTH);

echo "TimeUnit::SECOND  = " . TimeUnit::SECOND->value . " сек\n";
echo "TimeUnit::MINUTE  = " . TimeUnit::MINUTE->value . " сек (60)\n";
echo "TimeUnit::HOUR    = " . TimeUnit::HOUR->value . " сек (3600)\n";
echo "TimeUnit::DAY     = " . TimeUnit::DAY->value . " сек (86400)\n";
echo "TimeUnit::WEEK    = " . TimeUnit::WEEK->value . " сек (604800)\n";
echo "TimeUnit::MONTH   = " . TimeUnit::MONTH->value . " сек (2592000)\n\n";

// ============================================
// 8. Автобан с разными временными окнами
// ============================================
echo "8. Автобан с временными окнами:\n";
echo str_repeat("-", 50) . "\n";

// Бан за секунды
Route::post('/api/critical', fn () => 'done')
    ->throttleWithBan(
        maxAttempts : 1,
        decaySeconds : 1,        // 1 секунда
        maxViolations : 1,
        banDurationSeconds : 300 // Бан на 5 минут
    );
echo "• 1 запрос/сек, бан на 5 минут\n\n";

// Бан за минуты
Route::post('/login', fn () => 'logged in')
    ->throttleWithBan(
        maxAttempts : 5,
        decaySeconds : 60,       // 1 минута
        maxViolations : 3,
        banDurationSeconds : 7200 // Бан на 2 часа
    );
echo "• 5 запросов/мин, бан на 2 часа\n\n";

// Бан за часы
Route::post('/api/heavy', fn () => 'processed')
    ->throttleWithBan(
        maxAttempts : 100,
        decaySeconds : 3600,      // 1 час
        maxViolations : 2,
        banDurationSeconds : 86400 // Бан на 1 день
    );
echo "• 100 запросов/час, бан на 1 день\n\n";

// ============================================
// 9. Комбинированные ограничения
// ============================================
echo "9. Комбинированные ограничения:\n";
echo str_repeat("-", 50) . "\n";

Route::group([
    'prefix' => 'api/v2',
], function (){
    // Быстрые endpoints
    Route::get('/status', fn () => 'ok')
        ->perSecond(100);
    
    // Средние endpoints
    Route::get('/data', fn () => 'data')
        ->perMinute(1000);
    
    // Медленные endpoints
    Route::post('/process', fn () => 'processing')
        ->perHour(50);
    
    // Очень редкие операции
    Route::post('/migrate', fn () => 'migrated')
        ->perDay(1);
});

echo "• Группа с разными лимитами\n";
echo "• status: 100/сек\n";
echo "• data: 1000/мин\n";
echo "• process: 50/час\n";
echo "• migrate: 1/день\n\n";

// ============================================
// 10. Практические сценарии
// ============================================
echo "10. Практические сценарии:\n";
echo str_repeat("-", 50) . "\n";

// WebSocket / SSE
Route::get('/ws/connect', fn () => 'connected')
    ->perSecond(10, 1); // 10 подключений в секунду
echo "✓ WebSocket: 10 подключений/сек\n";

// GraphQL API
Route::post('/graphql', fn () => 'query result')
    ->perMinute(100, 1); // 100 запросов в минуту
echo "✓ GraphQL: 100 запросов/мин\n";

// File Upload
Route::post('/upload/large', fn () => 'uploaded')
    ->perHour(10, 1); // 10 загрузок в час
echo "✓ Upload: 10 загрузок/час\n";

// Batch Processing
Route::post('/batch/import', fn () => 'imported')
    ->perDay(5, 1); // 5 импортов в день
echo "✓ Batch: 5 импортов/день\n";

// Newsletter
Route::post('/newsletter/send', fn () => 'sent')
    ->perWeek(1, 1); // 1 рассылка в неделю
echo "✓ Newsletter: 1 рассылка/нед\n";

// Subscription Management
Route::post('/subscription/renew', fn () => 'renewed')
    ->perMonth(1, 1); // 1 продление в месяц
echo "✓ Subscription: 1 продление/мес\n";

echo "\n";

// ============================================
// Таблица рекомендаций
// ============================================
echo "===============================================\n";
echo "Рекомендации по временным окнам\n";
echo "===============================================\n\n";

echo "┌─────────────────┬─────────────┬──────────────────┐\n";
echo "│ Тип операции    │ Лимит       │ Временное окно   │\n";
echo "├─────────────────┼─────────────┼──────────────────┤\n";
echo "│ Realtime        │ 10-100      │ Per Second       │\n";
echo "│ API Standard    │ 60-1000     │ Per Minute       │\n";
echo "│ Heavy Tasks     │ 10-100      │ Per Hour         │\n";
echo "│ Email/SMS       │ 10-100      │ Per Day          │\n";
echo "│ Backup/Reports  │ 1-10        │ Per Week         │\n";
echo "│ Billing/Subs    │ 1-5         │ Per Month        │\n";
echo "└─────────────────┴─────────────┴──────────────────┘\n\n";

echo "✓ Поддержка всех временных единиц!\n";
echo "✓ Гибкая настройка лимитов!\n";
echo "✓ Автобан для всех окон!\n";

