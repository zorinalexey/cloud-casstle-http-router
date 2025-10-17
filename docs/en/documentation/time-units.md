# Time Units

**CloudCastle HTTP Router v1.1.0**  
**Language**: English

**Translations
**: [Русский](../../ru/documentation/time-units.md) | [Deutsch](../../de/documentation/time-units.md) | [Français](../../fr/documentation/time-units.md)

---

## ⏱️ What are Time Units?

Flexible rate limiting from **seconds** to **months**.

## 🚀 Quick Start

```php
// Per second
Route::get('/api/stream', fn() => 'data')->perSecond(10);

// Per minute
Route::post('/api/submit', fn() => 'ok')->perMinute(60);

// Per hour
Route::post('/api/heavy', fn() => 'done')->perHour(50);

// Per day
Route::post('/email', fn() => 'sent')->perDay(100);

// Per week
Route::post('/newsletter', fn() => 'sent')->perWeek(1);

// Per month
Route::post('/billing', fn() => 'ok')->perMonth(1);
```

## 📋 Available Methods

| Method                    | Description           |
|---------------------------|-----------------------|
| perSecond($max, $seconds) | Rate limit per second |
| perMinute($max, $minutes) | Rate limit per minute |
| perHour($max, $hours)     | Rate limit per hour   |
| perDay($max, $days)       | Rate limit per day    |
| perWeek($max, $weeks)     | Rate limit per week   |
| perMonth($max, $months)   | Rate limit per month  |

---

**Translations
**: [Русский](../../ru/documentation/time-units.md) | [Deutsch](../../de/documentation/time-units.md) | [Français](../../fr/documentation/time-units.md)
