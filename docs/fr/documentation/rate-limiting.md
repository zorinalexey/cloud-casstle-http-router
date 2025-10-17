# Rate Limiting

**CloudCastle HTTP Router v1.1.0**  
**Langue**: Français

**Traductions
**: [Русский](../../ru/documentation/rate-limiting.md) | [English](../../en/documentation/rate-limiting.md) | [Deutsch](../../de/documentation/rate-limiting.md)

---

## ⏱️ Fenêtres temporelles

```php
Route::get('/api', fn() => 'data')->perSecond(10);
Route::post('/api', fn() => 'ok')->perMinute(60);
Route::post('/api', fn() => 'done')->perHour(50);
```

---

**Traductions
**: [Русский](../../ru/documentation/rate-limiting.md) | [English](../../en/documentation/rate-limiting.md) | [Deutsch](../../de/documentation/rate-limiting.md)
