# Временные окна (Time Units)

**CloudCastle HTTP Router v1.1.0**  
**Язык**: Русский

**Переводы
**: [English](../../en/documentation/time-units.md) | [Deutsch](../../de/documentation/time-units.md) | [Français](../../fr/documentation/time-units.md)

---

## ⏱️ Что такое временные окна?

Временные окна позволяют настраивать rate limiting с разной детализацией - от **секунд** до **месяцев**.

## 🚀 Быстрый старт

```php
use CloudCastle\Http\Router\Facade\Route;

// По секундам - для realtime API
Route::get('/api/stream', 'StreamController@data')
    ->perSecond(10);  // 10 запросов в секунду

// По минутам - стандартный API
Route::post('/api/submit', 'ApiController@submit')
    ->perMinute(60);  // 60 запросов в минуту

// По часам - тяжелые операции
Route::post('/api/process', 'ApiController@process')
    ->perHour(50);  // 50 запросов в час

// По дням - email рассылка
Route::post('/send-email', 'EmailController@send')
    ->perDay(100);  // 100 запросов в день

// По неделям - backup
Route::post('/backup', 'BackupController@create')
    ->perWeek(7);  // 7 запросов в неделю

// По месяцам - billing
Route::post('/billing/renew', 'BillingController@renew')
    ->perMonth(1);  // 1 операция в месяц
```

## 📋 Доступные методы

### Route методы

| Метод                           | Описание          | Пример            |
|---------------------------------|-------------------|-------------------|
| `perSecond($max, $seconds = 1)` | Лимит по секундам | `->perSecond(10)` |
| `perMinute($max, $minutes = 1)` | Лимит по минутам  | `->perMinute(60)` |
| `perHour($max, $hours = 1)`     | Лимит по часам    | `->perHour(100)`  |
| `perDay($max, $days = 1)`       | Лимит по дням     | `->perDay(1000)`  |
| `perWeek($max, $weeks = 1)`     | Лимит по неделям  | `->perWeek(7)`    |
| `perMonth($max, $months = 1)`   | Лимит по месяцам  | `->perMonth(1)`   |

### RateLimiter статические методы

```php
use CloudCastle\Http\Router\RateLimiter;

$limiter = RateLimiter::perSecond(10);
$limiter = RateLimiter::perMinute(60);
$limiter = RateLimiter::perHour(100);
$limiter = RateLimiter::perDay(1000);
$limiter = RateLimiter::perWeek(7);
$limiter = RateLimiter::perMonth(1);
```

## 🎯 TimeUnit Enum

```php
use CloudCastle\Http\Router\TimeUnit;
use CloudCastle\Http\Router\RateLimiter;

// Создание через enum
$limiter = RateLimiter::make(100, 1, TimeUnit::SECOND);
$limiter = RateLimiter::make(1000, 1, TimeUnit::MINUTE);
$limiter = RateLimiter::make(10000, 1, TimeUnit::HOUR);
$limiter = RateLimiter::make(50000, 1, TimeUnit::DAY);
$limiter = RateLimiter::make(100000, 1, TimeUnit::WEEK);
$limiter = RateLimiter::make(1000000, 1, TimeUnit::MONTH);

// Значения в секундах
TimeUnit::SECOND->value;  // 1
TimeUnit::MINUTE->value;  // 60
TimeUnit::HOUR->value;    // 3600
TimeUnit::DAY->value;     // 86400
TimeUnit::WEEK->value;    // 604800
TimeUnit::MONTH->value;   // 2592000

// Конвертация
TimeUnit::MINUTE->toSeconds(5);  // 300 (5 минут = 300 секунд)
TimeUnit::HOUR->toSeconds(2);    // 7200 (2 часа = 7200 секунд)
```

## 💡 Примеры по типам операций

### WebSocket / Realtime

```php
// 100 подключений в секунду
Route::get('/ws/connect', 'WebSocketController@connect')
    ->perSecond(100);

// 10 сообщений в секунду
Route::post('/ws/message', 'WebSocketController@message')
    ->perSecond(10);
```

### Standard API

```php
// 1000 запросов в минуту
Route::group(['prefix' => 'api/v1'], function() {
    Route::get('/users', 'UserController@index')
        ->perMinute(1000);
    
    Route::post('/users', 'UserController@store')
        ->perMinute(100);
});
```

### Тяжелые операции

```php
// 10 экспортов в час
Route::post('/export/data', 'ExportController@export')
    ->perHour(10);

// 5 импортов в 2 часа
Route::post('/import/data', 'ImportController@import')
    ->perHour(5, 2);
```

### Email/SMS рассылки

```php
// 100 писем в день
Route::post('/email/send', 'EmailController@send')
    ->perDay(100);

// 50 SMS за неделю
Route::post('/sms/send', 'SmsController@send')
    ->perWeek(50);
```

### Backup и аналитика

```php
// 1 backup в день
Route::post('/backup/create', 'BackupController@create')
    ->perDay(1);

// 30 отчетов в неделю
Route::get('/reports/generate', 'ReportController@generate')
    ->perWeek(30);
```

### Billing операции

```php
// 1 продление в месяц
Route::post('/subscription/renew', 'BillingController@renew')
    ->perMonth(1);

// 12 invoice за год (1 в месяц)
Route::post('/invoice/generate', 'BillingController@invoice')
    ->perMonth(1);
```

## 🔢 Множественные временные окна

```php
// 100 запросов в 5 секунд
Route::get('/api/burst', 'ApiController@burst')
    ->perSecond(100, 5);

// 1000 запросов в 10 минут
Route::post('/api/batch', 'ApiController@batch')
    ->perMinute(1000, 10);

// 50 запросов в 6 часов
Route::post('/api/heavy', 'ApiController@heavy')
    ->perHour(50, 6);

// 500 запросов за неделю
Route::get('/api/analytics', 'ApiController@analytics')
    ->perDay(500, 7);
```

## 🎨 Комбинации

### С автобаном

```php
// 10/сек, бан после 5 нарушений
Route::get('/api/fast', 'ApiController@fast')
    ->perSecond(10)
    ->getRateLimiter()
    ->enableAutoBan(5, 300); // 5 нарушений, бан 5 минут
```

### Разные окна для группы

```php
Route::group(['prefix' => 'api/v2'], function() {
    // Быстрые endpoint'ы
    Route::get('/status', 'StatusController@check')
        ->perSecond(100);
    
    // Обычные запросы
    Route::get('/data', 'DataController@index')
        ->perMinute(1000);
    
    // Медленные операции
    Route::post('/process', 'ProcessController@run')
        ->perHour(50);
    
    // Редкие операции
    Route::post('/migrate', 'MigrateController@run')
        ->perDay(1);
});
```

## 📊 Таблица рекомендаций

| Тип операции     | Метод         | Лимит    | Пример                |
|------------------|---------------|----------|-----------------------|
| Realtime API     | `perSecond()` | 10-100   | `/ws/stream`          |
| GraphQL API      | `perSecond()` | 50-100   | `/graphql`            |
| REST API (read)  | `perMinute()` | 100-1000 | `/api/users`          |
| REST API (write) | `perMinute()` | 50-100   | `/api/users` POST     |
| File Upload      | `perHour()`   | 10-50    | `/upload/file`        |
| Data Export      | `perHour()`   | 5-20     | `/export/data`        |
| Email Send       | `perDay()`    | 50-200   | `/email/send`         |
| SMS Send         | `perDay()`    | 10-100   | `/sms/send`           |
| Backup           | `perWeek()`   | 1-7      | `/backup/create`      |
| Newsletter       | `perWeek()`   | 1-4      | `/newsletter/send`    |
| Billing          | `perMonth()`  | 1-5      | `/billing/charge`     |
| Subscription     | `perMonth()`  | 1        | `/subscription/renew` |

## ⚠️ Важные замечания

### Обратная совместимость

Старый метод `throttle()` теперь работает с секундами:

```php
// Раньше (v1.0)
->throttle(60, 1)  // 60 запросов, 1 МИНУТА

// Теперь (v1.1)
->throttle(60, 60)  // 60 запросов, 60 СЕКУНД

// Используйте новые методы для ясности
->perMinute(60)  // Однозначно понятно
```

### Точность временных окон

- ✅ Секунды: точность ±0.1 сек
- ✅ Минуты: точность ±1 сек
- ✅ Часы/дни: точность ±1 мин
- ✅ Недели/месяцы: точность ±1 час

## 📚 См. также

- [Rate Limiting](rate-limiting.md) - основы rate limiting
- [Автобан](auto-ban.md) - система автобана
- [API Reference](api-reference.md) - полный справочник API

---

**CloudCastle HTTP Router** - гибкость от секунд до месяцев! ⏱️

---

**Переводы
**: [English](../../en/documentation/time-units.md) | [Deutsch](../../de/documentation/time-units.md) | [Français](../../fr/documentation/time-units.md)

