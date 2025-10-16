# Rate Limiting

**CloudCastle HTTP Router v1.1.0**  
**Sprache**: Deutsch

**Übersetzungen**: [Русский](../../ru/documentation/rate-limiting.md) | [English](../../en/documentation/rate-limiting.md) | [Français](../../fr/documentation/rate-limiting.md)

---

## ⏱️ Zeitfenster

```php
Route::get('/api', fn() => 'data')->perSecond(10);
Route::post('/api', fn() => 'ok')->perMinute(60);
Route::post('/api', fn() => 'done')->perHour(50);
```

---

**Übersetzungen**: [Русский](../../ru/documentation/rate-limiting.md) | [English](../../en/documentation/rate-limiting.md) | [Français](../../fr/documentation/rate-limiting.md)
