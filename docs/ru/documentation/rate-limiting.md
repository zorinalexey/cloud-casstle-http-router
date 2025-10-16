# Rate Limiting (Ограничение частоты запросов)

**CloudCastle HTTP Router v1.1.0**  
**Язык**: Русский

**Переводы**: [English](../../en/documentation/rate-limiting.md) | [Deutsch](../../de/documentation/rate-limiting.md) | [Français](../../fr/documentation/rate-limiting.md)

---

## 🎯 Что такое Rate Limiting?

Rate Limiting - это механизм ограничения частоты запросов от клиента для защиты от:
- Перегрузки сервера
- DDoS атак
- Злоупотребления API
- Brute-force атак

## 🚀 Основное использование

```php
use CloudCastle\Http\Router\Facade\Route;

// Базовое ограничение
Route::get('/api/data', 'ApiController@index')
    ->throttle(60, 60);  // 60 запросов за 60 секунд
```

## ⏱️ Временные окна (v1.1.0)

```php
// По секундам
Route::get('/api/realtime', fn() => 'data')
    ->perSecond(10);

// По минутам
Route::post('/api/submit', fn() => 'ok')
    ->perMinute(60);

// По часам
Route::post('/api/heavy', fn() => 'done')
    ->perHour(50);

// По дням
Route::post('/email/send', fn() => 'sent')
    ->perDay(100);

// По неделям
Route::post('/newsletter', fn() => 'sent')
    ->perWeek(1);

// По месяцам
Route::post('/billing', fn() => 'ok')
    ->perMonth(1);
```

## 🚫 С автобаном (v1.1.0)

```php
Route::post('/login', 'Auth@login')
    ->throttleWithBan(
        maxAttempts: 5,           // лимит
        decaySeconds: 60,          // окно
        maxViolations: 3,          // нарушений до бана
        banDurationSeconds: 7200   // длительность бана
    );
```

## 📊 Shortcuts

```php
// Стандартный (60/мин)
Route::get('/api', fn() => 'data')
    ->throttleStandard();

// Строгий (10/мин)
Route::post('/api/write', fn() => 'ok')
    ->throttleStrict();
```

## 🔧 RateLimiter API

```php
use CloudCastle\Http\Router\RateLimiter;

$limiter = RateLimiter::perMinute(60);

// Проверка
if ($limiter->tooManyAttempts('user-123')) {
    throw new Exception('Too many requests');
}

// Регистрация попытки
$limiter->hit('user-123');

// Оставшиеся попытки
$remaining = $limiter->remaining('user-123');

// Время до сброса
$availableIn = $limiter->availableIn('user-123');
```

## 💡 Примеры

### API endpoint
```php
Route::post('/api/data', 'ApiController@store')
    ->perMinute(100)
    ->middleware('api');
```

### Login защита
```php
Route::post('/login', 'AuthController@login')
    ->throttleWithBan(5, 60, 3, 7200);
```

### Группа с лимитом
```php
Route::group(['prefix' => 'api'], function() {
    // Применится ко всем маршрутам в группе
})->perMinute(1000);
```

---

**Переводы**: [English](../../en/documentation/rate-limiting.md) | [Deutsch](../../de/documentation/rate-limiting.md) | [Français](../../fr/documentation/rate-limiting.md)
