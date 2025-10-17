# Безопасность

**CloudCastle HTTP Router v1.1.0**  
**Язык**: Русский

---

**Переводы**: [English](../../en/documentation/security.md) | [Deutsch](../../de/documentation/security.md) | [Français](../../fr/documentation/security.md)

---

## 🛡️ Обзор безопасности

CloudCastle HTTP Router предоставляет комплексную защиту от распространённых веб-атак и соответствует стандартам OWASP Top 10.

## Система автобана

Защита от brute-force и DDoS атак:

```php
Route::post('/login', 'AuthController@login')
    ->throttleWithBan(
        maxAttempts: 5,           // 5 попыток
        decaySeconds: 60,          // за минуту
        maxViolations: 3,          // 3 нарушения до бана
        banDurationSeconds: 7200   // бан на 2 часа
    );
```

## IP фильтрация

### Whitelist (Белый список)
```php
Route::get('/admin', 'AdminController@index')
    ->whitelistIp(['192.168.1.1', '10.0.0.1']);
```

### Blacklist (Чёрный список)
```php
Route::get('/api', 'ApiController@data')
    ->blacklistIp(['1.2.3.4', '5.6.7.8']);
```

## HTTPS enforcement

```php
Route::post('/payment', 'PaymentController@process')
    ->https(); // Требует HTTPS
```

## Протокольные ограничения

```php
// Только WebSocket
Route::get('/ws/chat', 'ChatController@handle')
    ->protocol(['ws', 'wss']);

// Только безопасный WebSocket
Route::get('/ws/secure', 'SecureController@handle')
    ->protocol(['wss']);
```

## Rate Limiting

### По секундам (защита от DDoS)
```php
Route::get('/api/realtime', fn() => 'data')
    ->perSecond(100);
```

### По минутам (стандартная защита)
```php
Route::post('/api/submit', fn() => 'ok')
    ->perMinute(60);
```

### По дням (защита email)
```php
Route::post('/send-email', fn() => 'sent')
    ->perDay(100);
```

## Security Middleware

### HTTPS Enforcement
```php
Route::middleware(HttpsEnforcement::class)
    ->get('/secure', fn() => 'secure data');
```

### Security Logging
```php
Route::middleware(SecurityLogger::class)
    ->post('/critical', fn() => 'ok');
```

### SSRF Protection
```php
Route::middleware(SsrfProtection::class)
    ->post('/fetch', fn() => 'data');
```

## OWASP Top 10 Compliance

| Категория | Защита | Статус |
|-----------|--------|--------|
| A01 - Access Control | IP фильтрация, middleware | ✅ |
| A02 - Cryptographic Failures | HTTPS enforcement | ✅ |
| A03 - Injection | Parameter validation | ✅ |
| A04 - Insecure Design | Security by default | ✅ |
| A05 - Security Misconfiguration | Secure defaults | ✅ |
| A07 - Authentication Failures | Rate limiting, auto-ban | ✅ |
| A09 - Logging Failures | Security logging | ✅ |
| A10 - SSRF | SSRF protection middleware | ✅ |

## Лучшие практики

### 1. Всегда используйте HTTPS для критичных операций
```php
Route::post('/payment', 'PaymentController@process')
    ->https()
    ->middleware('auth');
```

### 2. Применяйте rate limiting
```php
Route::post('/api/expensive', fn() => 'result')
    ->perHour(10);
```

### 3. Используйте IP фильтрацию для админки
```php
Route::group([
    'prefix' => '/admin',
    'whitelistIp' => ['192.168.1.0/24'],
    'middleware' => 'admin',
], function() {
    // Admin routes
});
```

### 4. Защита от brute-force
```php
Route::post('/login', 'AuthController@login')
    ->throttleWithBan(5, 60, 3, 7200);
```

## Дополнительно

См. также:
- [Отчёт по безопасности](../reports/security.md)
- [Rate Limiting](rate-limiting.md)
- [Автобан](auto-ban.md)

---

**[◀ Middleware](middleware.md)** | **[Rate Limiting ▶](rate-limiting.md)**

