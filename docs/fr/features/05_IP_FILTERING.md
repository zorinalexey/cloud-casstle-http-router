# IP Filtering

[English](../../en/features/05_IP_FILTERING.md) | [Русский](../../ru/features/05_IP_FILTERING.md) | [Deutsch](../../de/features/05_IP_FILTERING.md) | **Français** | [中文](../../zh/features/05_IP_FILTERING.md)

---







---

## 📚 Navigation de la Documentation

[README](../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [API_REFERENCE](../API_REFERENCE.md) | [ALL_FEATURES](../ALL_FEATURES.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [PERFORMANCE](../PERFORMANCE_ANALYSIS.md) | [SECURITY](../SECURITY_REPORT.md) | [COMPARISON](../COMPARISON.md) | [FAQ](../FAQ.md)

**Documentation détaillée:** [01](01_BASIC_ROUTING.md) | [02](02_ROUTE_PARAMETERS.md) | [03](03_ROUTE_GROUPS.md) | [04](04_RATE_LIMITING.md) | [05](05_IP_FILTERING.md) | [06](06_MIDDLEWARE.md) | [07](07_NAMED_ROUTES.md) | [08](08_TAGS.md) | [09](09_HELPER_FUNCTIONS.md) | [10](10_ROUTE_SHORTCUTS.md) | [11](11_ROUTE_MACROS.md) | [12](12_URL_GENERATION.md) | [13](13_EXPRESSION_LANGUAGE.md) | [14](14_CACHING.md) | [15](15_PLUGINS.md) | [16](16_LOADERS.md) | [17](17_PSR_SUPPORT.md) | [18](18_ACTION_RESOLVER.md) | [19](19_STATISTICS.md) | [20](20_SECURITY.md) | [21](21_EXCEPTIONS.md) | [22](22_CLI_TOOLS.md)

---


**Catégorie:** Sécurité  
**Nombre de méthodes:** 4  
**Complexité:** ⭐⭐ Intermédiaire уро dans ень

---

## Оп et  avec ан et е

IP Filtering  par з dans оляет контрол et ро dans ать до avec туп к routeам  sur  о avec но dans е IP адре avec о dans  кл et ента. Поддерж et  dans ает whitelist (только разрешенные)  et  blacklist (только запрещенные),  dans ключая CIDR нотац et ю  pour   par д avec етей.

## Méthodes

### 1. whitelistIp()

**Méthode:** `whitelistIp(array $ips): Route`

**Оп et  avec ан et е:** Разреш et ть до avec туп только  avec  указанных IP адре avec о dans .

**Exemples:**

```php
// Один IP
Route::get('/admin', $action)
    ->whitelistIp(['192.168.1.1']);

// Множественные IP
Route::get('/admin', $action)
    ->whitelistIp([
        '192.168.1.1',
        '192.168.1.2',
        '10.0.0.100'
    ]);

// CIDR нотация (подсеть)
Route::get('/admin', $action)
    ->whitelistIp([
        '192.168.1.0/24',    // 192.168.1.0 - 192.168.1.255
        '10.0.0.0/8'         // 10.0.0.0 - 10.255.255.255
    ]);

// Офисная сеть
Route::get('/internal', $action)
    ->whitelistIp(['192.168.0.0/16']);
```

### 2. blacklistIp()

**Méthode:** `blacklistIp(array $ips): Route`

**Оп et  avec ан et е:** Запрет et ть до avec туп  avec  указанных IP адре avec о dans .

**Exemples:**

```php
// Заблокировать конкретные IP
Route::get('/public', $action)
    ->blacklistIp(['1.2.3.4', '5.6.7.8']);

// CIDR
Route::get('/api/data', $action)
    ->blacklistIp(['1.2.3.0/24']);

// Из базы данных
$bannedIps = DB::table('banned_ips')->pluck('ip')->toArray();
Route::get('/api/data', $action)
    ->blacklistIp($bannedIps);
```

### 3. CIDR Support

**Формат:** `IP/MASK`

**Exemples:**

```php
// /32 - один IP
Route::get('/test', $action)->whitelistIp(['192.168.1.1/32']);

// /24 - подсеть 256 адресов
Route::get('/test', $action)->whitelistIp(['192.168.1.0/24']);

// /16 - 65,536 адресов
Route::get('/test', $action)->whitelistIp(['192.168.0.0/16']);

// /8 - 16,777,216 адресов
Route::get('/test', $action)->whitelistIp(['10.0.0.0/8']);
```

### 4. IP Spoofing Protection

**Оп et  avec ан et е:** А dans томат et че avec кая про dans ерка X-Forwarded-For  et  друг et х заголо dans ко dans .

CloudCastle HTTP Router а dans томат et че avec к et :
- Про dans еряет `X-Forwarded-For`
- Про dans еряет `X-Real-IP`
- Защ et щает от  par дмены IP

## Полные пр et меры

### Адм et нка

```php
Route::group([
    'prefix' => '/admin',
    'middleware' => [AuthMiddleware::class, AdminMiddleware::class],
    'https' => true,
    'whitelistIp' => ['192.168.1.0/24']  // Только офис
], function() {
    Route::get('/dashboard', [AdminController::class, 'dashboard']);
    Route::get('/users', [AdminController::class, 'users']);
    
    // Критичный эндпоинт - еще более строгая защита
    Route::post('/settings/critical', [AdminController::class, 'critical'])
        ->whitelistIp(['192.168.1.100']);  // Только один IP
});
```

### Internal API

```php
Route::group([
    'prefix' => '/api/internal',
    'whitelistIp' => [
        '10.0.1.100',  // App Server 1
        '10.0.1.101',  // App Server 2
        '10.0.1.102'   // App Server 3
    ]
], function() {
    Route::post('/sync', [InternalApiController::class, 'sync']);
    Route::post('/backup', [InternalApiController::class, 'backup']);
});
```

### Webhooks

```php
Route::post('/webhooks/github', [WebhookController::class, 'github'])
    ->whitelistIp([
        '192.30.252.0/22',  // GitHub webhooks
        '185.199.108.0/22'
    ]);

Route::post('/webhooks/stripe', [WebhookController::class, 'stripe'])
    ->whitelistIp([
        '54.187.174.169',   // Stripe
        '54.187.205.235',
        '54.187.216.72'
    ]);
```

---

**Version:** 1.1.1  
**Стату avec :** ✅ Production-ready


---

## 📚 Navigation de la Documentation

[README](../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [API_REFERENCE](../API_REFERENCE.md) | [ALL_FEATURES](../ALL_FEATURES.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [FAQ](../FAQ.md)

**Documentation détaillée:** [01](01_BASIC_ROUTING.md) | [02](02_ROUTE_PARAMETERS.md) | [03](03_ROUTE_GROUPS.md) | [04](04_RATE_LIMITING.md) | [05](05_IP_FILTERING.md) | [06](06_MIDDLEWARE.md) | [07](07_NAMED_ROUTES.md) | [08](08_TAGS.md) | [09](09_HELPER_FUNCTIONS.md) | [10](10_ROUTE_SHORTCUTS.md) | [11](11_ROUTE_MACROS.md) | [12](12_URL_GENERATION.md) | [13](13_EXPRESSION_LANGUAGE.md) | [14](14_CACHING.md) | [15](15_PLUGINS.md) | [16](16_LOADERS.md) | [17](17_PSR_SUPPORT.md) | [18](18_ACTION_RESOLVER.md) | [19](19_STATISTICS.md) | [20](20_SECURITY.md) | [21](21_EXCEPTIONS.md) | [22](22_CLI_TOOLS.md)

**© 2024 CloudCastle HTTP Router**
