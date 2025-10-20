# IP Filtering

**Категория:** Безопасность  
**Количество методов:** 4  
**Сложность:** ⭐⭐ Средний уровень

---

## Описание

IP Filtering позволяет контролировать доступ к маршрутам на основе IP адресов клиента. Поддерживает whitelist (только разрешенные) и blacklist (только запрещенные), включая CIDR нотацию для подсетей.

## Методы

### 1. whitelistIp()

**Метод:** `whitelistIp(array $ips): Route`

**Описание:** Разрешить доступ только с указанных IP адресов.

**Примеры:**

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

**Метод:** `blacklistIp(array $ips): Route`

**Описание:** Запретить доступ с указанных IP адресов.

**Примеры:**

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

**Примеры:**

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

**Описание:** Автоматическая проверка X-Forwarded-For и других заголовков.

CloudCastle HTTP Router автоматически:
- Проверяет `X-Forwarded-For`
- Проверяет `X-Real-IP`
- Защищает от подмены IP

## Полные примеры

### Админка

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

**Версия:** 1.1.1  
**Статус:** ✅ Production-ready

