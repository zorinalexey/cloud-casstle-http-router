# Security

---

## 📚 Documentation Navigation

[README](../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [API_REFERENCE](../API_REFERENCE.md) | [ALL_FEATURES](../ALL_FEATURES.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [PERFORMANCE](../PERFORMANCE_ANALYSIS.md) | [SECURITY](../SECURITY_REPORT.md) | [COMPARISON](../COMPARISON.md) | [FAQ](../FAQ.md)

**Детальная документация:** [01](01_BASIC_ROUTING.md) | [02](02_ROUTE_PARAMETERS.md) | [03](03_ROUTE_GROUPS.md) | [04](04_RATE_LIMITING.md) | [05](05_IP_FILTERING.md) | [06](06_MIDDLEWARE.md) | [07](07_NAMED_ROUTES.md) | [08](08_TAGS.md) | [09](09_HELPER_FUNCTIONS.md) | [10](10_ROUTE_SHORTCUTS.md) | [11](11_ROUTE_MACROS.md) | [12](12_URL_GENERATION.md) | [13](13_EXPRESSION_LANGUAGE.md) | [14](14_CACHING.md) | [15](15_PLUGINS.md) | [16](16_LOADERS.md) | [17](17_PSR_SUPPORT.md) | [18](18_ACTION_RESOLVER.md) | [19](19_STATISTICS.md) | [20](20_SECURITY.md) | [21](21_EXCEPTIONS.md) | [22](22_CLI_TOOLS.md)

---


**Категория:** Защита приложения  
**Количество механизмов:** 12  
**Сложность:** ⭐⭐⭐ Критически важно

---

## Встроенные механизмы защиты

### 1. Rate Limiting

Защита от DDoS и брут-форса.

```php
Route::post('/login', $action)->throttle(5, 1);
```

### 2. Auto-Ban System

Автоматическая блокировка атакующих IP.

```php
$banManager = new BanManager(5, 3600);
Route::post('/login', $action)
    ->throttle(3, 1)
    ->getRateLimiter()
    ?->setBanManager($banManager);
```

### 3. IP Filtering

Whitelist/Blacklist IP адресов.

```php
Route::get('/admin', $action)->whitelistIp(['192.168.1.0/24']);
Route::get('/public', $action)->blacklistIp(['1.2.3.4']);
```

### 4. HTTPS Enforcement

Принудительное использование HTTPS.

```php
Route::post('/payment', $action)->https();
Route::get('/secure', $action)->secure();
```

### 5. Protocol Restrictions

Ограничение протоколов (HTTP/HTTPS/WS/WSS).

```php
Route::get('/ws/chat', $action)->protocol(['wss']);
```

### 6. Path Traversal Protection

Автоматическая защита от `../` атак.

```php
// Роутер НЕ позволит:
// /files/../../../etc/passwd
// Параметр будет безопасно извлечен
```

### 7. SQL Injection Protection

Валидация parameterов через `where()`.

```php
Route::get('/users/{id}', $action)
    ->where('id', '[0-9]+');  // Только цифры
```

### 8. XSS Protection

Экранирование parameterов (рекомендуется в action).

```php
Route::get('/search/{query}', function($query) {
    return htmlspecialchars($query);  // XSS защита
});
```

### 9. ReDoS Protection

Защита от Regex DoS - безопасные паттерны.

```php
Route::get('/users/{id}', $action)
    ->where('id', '[0-9]+');  // Безопасный паттерн
```

### 10. Method Override Protection

Проверка реального HTTP methodа.

### 11. Cache Injection Protection

Безопасное кеширование с подписью.

### 12. IP Spoofing Protection

Проверка X-Forwarded-For и защита от подмены IP.

## OWASP Top 10

✅ A01:2021 – Broken Access Control - IP filtering, Auth middleware  
✅ A02:2021 – Cryptographic Failures - HTTPS enforcement  
✅ A03:2021 – Injection - Parameter validation (where)  
✅ A04:2021 – Insecure Design - Secure by default  
✅ A05:2021 – Security Misconfiguration - Defaults secure  
✅ A06:2021 – Vulnerable Components - Updated dependencies  
✅ A07:2021 – Identification Failures - Rate limiting, Auto-ban  
✅ A08:2021 – Software Integrity Failures - Signed URLs  
✅ A09:2021 – Logging Failures - SecurityLogger  
✅ A10:2021 – SSRF - SsrfProtection middleware

## Рекомендации

```php
// ✅ ВСЕГДА валидируйте параметры
Route::get('/users/{id}', $action)->where('id', '[0-9]+');

// ✅ ВСЕГДА используйте HTTPS для sensitive data
Route::post('/payment', $action)->https();

// ✅ ВСЕГДА ограничивайте rate
Route::post('/login', $action)->throttle(5, 1);

// ✅ ВСЕГДА защищайте админку
Route::group([
    'prefix' => '/admin',
    'middleware' => [AuthMiddleware::class, AdminMiddleware::class],
    'https' => true,
    'whitelistIp' => ['офисная сеть']
], function() {
    // Админские маршруты
});
```

---

**Version:** 1.1.1  
**Статус:** ✅ OWASP Top 10 Compliant


---

## 📚 Documentation Navigation

[README](../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [API_REFERENCE](../API_REFERENCE.md) | [ALL_FEATURES](../ALL_FEATURES.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [FAQ](../FAQ.md)

**Детальная документация:** [01](01_BASIC_ROUTING.md) | [02](02_ROUTE_PARAMETERS.md) | [03](03_ROUTE_GROUPS.md) | [04](04_RATE_LIMITING.md) | [05](05_IP_FILTERING.md) | [06](06_MIDDLEWARE.md) | [07](07_NAMED_ROUTES.md) | [08](08_TAGS.md) | [09](09_HELPER_FUNCTIONS.md) | [10](10_ROUTE_SHORTCUTS.md) | [11](11_ROUTE_MACROS.md) | [12](12_URL_GENERATION.md) | [13](13_EXPRESSION_LANGUAGE.md) | [14](14_CACHING.md) | [15](15_PLUGINS.md) | [16](16_LOADERS.md) | [17](17_PSR_SUPPORT.md) | [18](18_ACTION_RESOLVER.md) | [19](19_STATISTICS.md) | [20](20_SECURITY.md) | [21](21_EXCEPTIONS.md) | [22](22_CLI_TOOLS.md)

**© 2024 CloudCastle HTTP Router**
