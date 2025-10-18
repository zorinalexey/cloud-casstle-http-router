# Middleware

**CloudCastle HTTP Router v1.1.1**  
**Язык**: Русский

---

## 🌍 Переводы

- **[Русский](middleware.md)** (текущий)
- [English](../../en/documentation/middleware.md)
- [Deutsch](../../de/documentation/middleware.md)
- [Français](../../fr/documentation/middleware.md)

---

## 📋 Введение

Middleware - это обработчики, которые выполняются до или после основного действия маршрута. Они используются для аутентификации, логирования, валидации и других cross-cutting concerns.

---

## 🎯 Создание Middleware

### Интерфейс MiddlewareInterface

```php
<?php

namespace CloudCastle\Http\Router\Contracts;

interface MiddlewareInterface
{
    /**
     * Handle request
     *
     * @param mixed $request
     * @param callable $next
     * @return mixed
     */
    public function handle(mixed $request, callable $next): mixed;
}
```

### Пример middleware

```php
<?php

namespace App\Middleware;

use CloudCastle\Http\Router\Contracts\MiddlewareInterface;

class AuthMiddleware implements MiddlewareInterface
{
    public function handle(mixed $request, callable $next): mixed
    {
        // Проверка аутентификации
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            return 'Unauthorized';
        }
        
        // Передача управления дальше
        return $next($request);
    }
}
```

---

## 🔧 Применение Middleware

### К маршруту

```php
use App\Middleware\AuthMiddleware;

Route::get('/dashboard', 'DashboardController@index')
    ->middleware(AuthMiddleware::class);

// Или строкой
Route::get('/dashboard', 'DashboardController@index')
    ->middleware('auth');
```

### Несколько middleware

```php
Route::get('/admin', 'AdminController@index')
    ->middleware(['auth', 'admin', 'verified']);
```

### К группе

```php
Route::group(['middleware' => 'auth'], function() {
    Route::get('/dashboard', 'DashboardController@index');
    Route::get('/profile', 'ProfileController@show');
});
```

### Глобальные middleware

```php
use CloudCastle\Http\Router\Router;

$router = Router::getInstance();
$router->middleware('cors');
$router->middleware(['logging', 'security']);

// Будут применены ко всем маршрутам
```

---

## 🛡️ Встроенные Middleware

### 1. HTTPS Enforcement

Принудительное использование HTTPS

```php
use CloudCastle\Http\Router\Middleware\HttpsEnforcement;

Route::post('/login', 'AuthController@login')
    ->middleware(new HttpsEnforcement(redirectToHttps: true));
    
// Или через метод https()
Route::post('/login', 'AuthController@login')
    ->https();
```

**Возможности**:
- Проверка HTTPS соединения
- Автоматическая переадресация на HTTPS
- Поддержка X-Forwarded-Proto
- Поддержка X-Forwarded-SSL

---

### 2. SSRF Protection

Защита от Server-Side Request Forgery

```php
use CloudCastle\Http\Router\Middleware\SsrfProtection;

Route::post('/fetch-url', 'Controller@fetchUrl')
    ->middleware(new SsrfProtection());

// С whitelist доменов
Route::post('/fetch-url', 'Controller@fetchUrl')
    ->middleware(new SsrfProtection(['example.com', 'api.example.com']));
```

**Защита от**:
- Запросов к localhost/127.0.0.1
- Запросов к приватным IP (10.x.x.x, 192.168.x.x, etc.)
- Запросов к metadata endpoints (169.254.169.254)
- File:// протокола

---

### 3. Security Logger

Логирование событий безопасности

```php
use CloudCastle\Http\Router\Middleware\SecurityLogger;

Route::post('/api', 'ApiController@handle')
    ->middleware(new SecurityLogger('/var/log/security.log'));
```

**Логирует**:
- Информацию о запросе (IP, method, URI)
- Параметры маршрута
- Исключения безопасности
- Timestamp

---

## 🔄 Порядок выполнения

### Цепочка middleware

```php
Route::get('/protected', 'Controller@index')
    ->middleware(['logging', 'auth', 'admin']);

// Порядок выполнения:
// 1. logging
// 2. auth
// 3. admin
// 4. Controller action
// 5. admin (обратно)
// 6. auth (обратно)
// 7. logging (обратно)
```

### Пример с логированием

```php
class LoggingMiddleware implements MiddlewareInterface
{
    public function handle(mixed $request, callable $next): mixed
    {
        $start = microtime(true);
        
        // До выполнения action
        error_log("Request started");
        
        $response = $next($request);
        
        // После выполнения action
        $time = microtime(true) - $start;
        error_log("Request completed in {$time}s");
        
        return $response;
    }
}
```

---

## 💡 Практические примеры

### Пример 1: Authentication

```php
class AuthMiddleware implements MiddlewareInterface
{
    public function handle(mixed $request, callable $next): mixed
    {
        session_start();
        
        if (!isset($_SESSION['authenticated'])) {
            http_response_code(401);
            return json_encode(['error' => 'Unauthorized']);
        }
        
        return $next($request);
    }
}

// Использование
Route::get('/dashboard', 'DashboardController@index')
    ->middleware(AuthMiddleware::class);
```

### Пример 2: CORS

```php
class CorsMiddleware implements MiddlewareInterface
{
    public function handle(mixed $request, callable $next): mixed
    {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');
        
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(200);
            return '';
        }
        
        return $next($request);
    }
}

Route::group(['middleware' => CorsMiddleware::class], function() {
    Route::get('/api/data', 'ApiController@data');
});
```

### Пример 3: Request Validation

```php
class ValidateJsonMiddleware implements MiddlewareInterface
{
    public function handle(mixed $request, callable $next): mixed
    {
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
        
        if (!str_contains($contentType, 'application/json')) {
            http_response_code(415);
            return json_encode(['error' => 'Content-Type must be application/json']);
        }
        
        return $next($request);
    }
}

Route::post('/api/data', 'ApiController@store')
    ->middleware(ValidateJsonMiddleware::class);
```

### Пример 4: Rate Limit Middleware

```php
class RateLimitMiddleware implements MiddlewareInterface
{
    public function __construct(private int $maxAttempts = 60)
    {
    }
    
    public function handle(mixed $request, callable $next): mixed
    {
        $ip = $_SERVER['REMOTE_ADDR'];
        
        // Проверка лимита (упрощенно)
        if ($this->exceedsLimit($ip)) {
            http_response_code(429);
            return 'Too Many Requests';
        }
        
        return $next($request);
    }
    
    private function exceedsLimit(string $ip): bool
    {
        // Логика проверки лимита
        return false;
    }
}
```

---

## 🎨 Продвинутое использование

### Middleware с зависимостями

```php
class DatabaseMiddleware implements MiddlewareInterface
{
    public function __construct(
        private DatabaseConnection $db
    ) {
    }
    
    public function handle(mixed $request, callable $next): mixed
    {
        $this->db->connect();
        $response = $next($request);
        $this->db->disconnect();
        
        return $response;
    }
}

// Использование
Route::get('/data', 'Controller@data')
    ->middleware(new DatabaseMiddleware($dbConnection));
```

### Условный middleware

```php
Route::get('/resource', 'Controller@index')
    ->middleware(function($request, $next) {
        if (someCondition()) {
            return 'Condition not met';
        }
        return $next($request);
    });
```

---

## 🔗 См. также

- [Безопасность](security.md)
- [Rate Limiting](rate-limiting.md)
- [Группы маршрутов](route-groups.md)

---

**[← Назад к оглавлению](README.md)**

