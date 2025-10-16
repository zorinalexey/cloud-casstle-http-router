# Rate Limiting

**CloudCastle HTTP Router v1.1.0**  
**Language**: English

**Translations**: [Русский](../../ru/documentation/rate-limiting.md) | [Deutsch](../../de/documentation/rate-limiting.md) | [Français](../../fr/documentation/rate-limiting.md)

---

## 🎯 What is Rate Limiting?

Rate limiting is a mechanism to limit request frequency from clients.

## ⏱️ Time Windows (v1.1.0)

```php
// Per second
Route::get('/api/realtime', fn() => 'data')->perSecond(10);

// Per minute
Route::post('/api/submit', fn() => 'ok')->perMinute(60);

// Per hour
Route::post('/api/heavy', fn() => 'done')->perHour(50);

// Per day
Route::post('/email/send', fn() => 'sent')->perDay(100);

// Per week
Route::post('/newsletter', fn() => 'sent')->perWeek(1);

// Per month
Route::post('/billing', fn() => 'ok')->perMonth(1);
```

## 🚫 With Auto-Ban

```php
Route::post('/login', 'Auth@login')
    ->throttleWithBan(5, 60, 3, 7200);
```

---

**Translations**: [Русский](../../ru/documentation/rate-limiting.md) | [Deutsch](../../de/documentation/rate-limiting.md) | [Français](../../fr/documentation/rate-limiting.md)
