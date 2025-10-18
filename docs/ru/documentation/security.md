# Безопасность

**CloudCastle HTTP Router v1.1.1**  
**Язык**: Русский

---

## 🌍 Переводы

- **[Русский](security.md)** (текущий)
- [English](../../en/documentation/security.md)
- [Deutsch](../../de/documentation/security.md)
- [Français](../../fr/documentation/security.md)

---

## 🛡️ Обзор безопасности

CloudCastle Router предоставляет комплексную защиту приложений с встроенными механизмами безопасности.

**Результаты тестов**: 13/13 тестов безопасности пройдено ✅  
**Соответствие**: OWASP Top 10 ✅

---

## 🔒 IP фильтрация

### Whitelist (белый список)

```php
Route::get('/admin', 'AdminController@index')
    ->whitelistIp(['192.168.1.0/24', '10.0.0.1']);
```

**Поддерживаемые форматы**:
- Одиночный IP: `192.168.1.100`
- CIDR notation: `192.168.1.0/24`
- Диапазон: `10.0.0.1-10.0.0.255`
- Массив IP: `['10.0.0.1', '10.0.0.2']`

### Blacklist (черный список)

```php
Route::get('/public', 'PublicController@index')
    ->blacklistIp(['1.2.3.4', '5.6.7.8']);
```

### С группами

```php
Route::group(['whitelistIp' => ['192.168.0.0/16']], function() {
    Route::get('/internal-api', 'InternalController@api');
    Route::get('/metrics', 'MetricsController@index');
});
```

---

## 🔐 HTTPS Enforcement

### Принудительный HTTPS

```php
Route::post('/login', 'AuthController@login')
    ->https();

Route::post('/payment', 'PaymentController@process')
    ->https();
```

### HTTPS Middleware

```php
use CloudCastle\Http\Router\Middleware\HttpsEnforcement;

Route::post('/secure', 'Controller@secure')
    ->middleware(new HttpsEnforcement(redirectToHttps: true));
```

**Возможности**:
- Проверка HTTPS соединения
- Автоматическая переадресация
- Поддержка reverse proxy headers
- X-Forwarded-Proto detection

---

## 🌐 Доменные ограничения

### Привязка к домену

```php
Route::get('/admin', 'AdminController@index')
    ->domain('admin.example.com');

Route::get('/api', 'ApiController@index')
    ->domain('api.example.com');
```

### С группами

```php
Route::group(['domain' => 'admin.example.com'], function() {
    Route::get('/dashboard', 'AdminController@dashboard');
    Route::get('/users', 'AdminController@users');
});
```

---

## 🔌 Портовые ограничения

```php
// Метрики только на порту 9090
Route::get('/metrics', 'MetricsController@index')
    ->port(9090);

// Админка на нестандартном порту
Route::get('/admin', 'AdminController@index')
    ->port(8443);
```

---

## 🔐 Протокольные ограничения

### HTTPS only

```php
Route::post('/payment', 'PaymentController@process')
    ->protocol('https');
```

### HTTP или HTTPS

```php
Route::get('/public', 'PublicController@index')
    ->protocol(['http', 'https']);
```

### WebSocket

```php
Route::get('/ws/chat', 'ChatController@connect')
    ->protocol('ws');

Route::get('/wss/secure-chat', 'ChatController@secureConnect')
    ->protocol('wss');
```

---

## 🛡️ Встроенные Middleware

### 1. HTTPS Enforcement

```php
use CloudCastle\Http\Router\Middleware\HttpsEnforcement;

Route::post('/login', 'AuthController@login')
    ->middleware(new HttpsEnforcement(redirectToHttps: true));
```

**Защита**:
- ✅ Проверка HTTPS
- ✅ Автоматический redirect
- ✅ Поддержка proxy headers

### 2. SSRF Protection

```php
use CloudCastle\Http\Router\Middleware\SsrfProtection;

Route::post('/fetch-url', 'Controller@fetchUrl')
    ->middleware(new SsrfProtection());
```

**Защита от**:
- ✅ Запросов к localhost
- ✅ Запросов к приватным IP
- ✅ Запросов к metadata endpoints (AWS, GCP)
- ✅ File:// протокола

### 3. Security Logger

```php
use CloudCastle\Http\Router\Middleware\SecurityLogger;

Route::post('/sensitive', 'Controller@sensitive')
    ->middleware(new SecurityLogger('/var/log/security.log'));
```

**Логирует**:
- Информацию о запросе
- IP адрес
- Параметры
- Исключения

---

## 🎯 Комплексная защита

### Максимальная безопасность

```php
Route::post('/admin/critical', 'AdminController@critical')
    ->https()                                    // Только HTTPS
    ->domain('admin.example.com')                // Только с admin домена
    ->port(443)                                  // Только стандартный HTTPS порт
    ->whitelistIp(['192.168.1.0/24'])           // Только офисная сеть
    ->middleware(['auth', 'admin', '2fa'])       // Множественная аутентификация
    ->throttleWithBan(3, 60, 1, 86400)          // Строгий автобан
    ->tag(['admin', 'critical']);
```

### API endpoint защита

```php
Route::post('/api/v1/data', 'ApiController@store')
    ->https()
    ->middleware(['auth', 'api-key'])
    ->perMinute(100)
    ->middleware(new SsrfProtection())
    ->middleware(new SecurityLogger('/var/log/api.log'));
```

---

## ⚠️ Обработка исключений

### IpNotAllowedException

```php
use CloudCastle\Http\Router\Exceptions\IpNotAllowedException;

try {
    Route::dispatch($uri, $method);
} catch (IpNotAllowedException $e) {
    http_response_code(403);
    error_log("Blocked IP: " . $_SERVER['REMOTE_ADDR']);
    echo json_encode(['error' => 'Access denied']);
}
```

### BannedException

```php
use CloudCastle\Http\Router\Exceptions\BannedException;

try {
    Route::dispatch($uri, $method);
} catch (BannedException $e) {
    http_response_code(403);
    echo json_encode([
        'error' => 'IP banned',
        'retry_after' => $e->getRetryAfter()
    ]);
}
```

### InsecureConnectionException

```php
use CloudCastle\Http\Router\Exceptions\InsecureConnectionException;

try {
    Route::dispatch($uri, $method);
} catch (InsecureConnectionException $e) {
    http_response_code(403);
    echo 'HTTPS required';
}
```

---

## 📊 Тесты безопасности

Пройдено **13 тестов безопасности**:

✅ Path Traversal Protection  
✅ SQL Injection Protection  
✅ XSS Protection  
✅ IP Whitelist Security  
✅ IP Blacklist Security  
✅ IP Spoofing Protection  
✅ Domain Security  
✅ ReDoS Protection  
✅ Method Override Attack  
✅ Mass Assignment Protection  
✅ Cache Injection Protection  
✅ Resource Exhaustion Protection  
✅ Unicode Security

[Подробный отчет →](../../reports/security.md)

---

## 🎓 Лучшие практики

### ✅ Рекомендуется

```php
// 1. Всегда используйте HTTPS для чувствительных данных
Route::post('/login', 'AuthController@login')->https();

// 2. Комбинируйте несколько уровней защиты
Route::post('/admin/delete', 'AdminController@delete')
    ->https()
    ->whitelistIp(['офисная сеть'])
    ->middleware(['auth', 'admin'])
    ->perHour(10);

// 3. Используйте автобан для публичных endpoint
Route::post('/api/public', 'ApiController@public')
    ->throttleWithBan(100, 60, 5, 3600);
```

### ❌ Не рекомендуется

```php
// Плохо: Чувствительные данные без HTTPS
Route::post('/login', 'AuthController@login'); // Нет https()!

// Плохо: Админка без IP фильтрации
Route::get('/admin', 'AdminController@index'); // Доступна всем!

// Плохо: Нет rate limiting на публичном API
Route::post('/api/public', 'ApiController@data'); // Уязвимо к DDoS!
```

---

## 🔗 См. также

- [Rate Limiting](rate-limiting.md)
- [Автобан](auto-ban.md)
- [Middleware](middleware.md)
- [Отчет по безопасности](../../reports/security.md)

---

**[← Назад к оглавлению](README.md)**

