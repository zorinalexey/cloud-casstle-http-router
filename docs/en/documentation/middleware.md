# Middleware

**CloudCastle HTTP Router v1.1.1**  
**Language**: English

---

## 🌍 Translations

- [Русский](../../ru/documentation/middleware.md)
- **[English](middleware.md)** (current)
- [Deutsch](../../de/documentation/middleware.md)
- [Français](../../fr/documentation/middleware.md)

---

## 📋 Introduction

Middleware are handlers that execute before or after the main route action. They're used for authentication, logging, validation, and other cross-cutting concerns.

---

## 🎯 Creating Middleware

### MiddlewareInterface

```php
<?php

namespace CloudCastle\Http\Router\Contracts;

interface MiddlewareInterface
{
    public function handle(mixed $request, callable $next): mixed;
}
```

### Example Middleware

```php
<?php

namespace App\Middleware;

use CloudCastle\Http\Router\Contracts\MiddlewareInterface;

class AuthMiddleware implements MiddlewareInterface
{
    public function handle(mixed $request, callable $next): mixed
    {
        // Check authentication
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            return 'Unauthorized';
        }
        
        // Pass control forward
        return $next($request);
    }
}
```

---

## 🔧 Applying Middleware

### To Route

```php
Route::get('/dashboard', 'DashboardController@index')
    ->middleware(AuthMiddleware::class);
```

### Multiple Middleware

```php
Route::get('/admin', 'AdminController@index')
    ->middleware(['auth', 'admin', 'verified']);
```

### To Group

```php
Route::group(['middleware' => 'auth'], function() {
    Route::get('/dashboard', 'DashboardController@index');
});
```

### Global Middleware

```php
$router = Router::getInstance();
$router->middleware(['cors', 'logging']);
```

---

## 🛡️ Built-in Middleware

### 1. HTTPS Enforcement

```php
use CloudCastle\Http\Router\Middleware\HttpsEnforcement;

Route::post('/login', 'AuthController@login')
    ->middleware(new HttpsEnforcement(redirectToHttps: true));
```

### 2. SSRF Protection

```php
use CloudCastle\Http\Router\Middleware\SsrfProtection;

Route::post('/fetch-url', 'Controller@fetchUrl')
    ->middleware(new SsrfProtection());
```

### 3. Security Logger

```php
use CloudCastle\Http\Router\Middleware\SecurityLogger;

Route::post('/api', 'ApiController@handle')
    ->middleware(new SecurityLogger('/var/log/security.log'));
```

---

## 🔗 See Also

- [Security](security.md)
- [Rate Limiting](rate-limiting.md)

---

**[← Back to contents](README.md)**

