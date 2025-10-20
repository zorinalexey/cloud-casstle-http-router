# Middleware Features

**English** | [Русский](../../ru/features/MIDDLEWARE_FEATURES.md) | [Deutsch](../../de/features/MIDDLEWARE_FEATURES.md) | [Français](../../fr/features/MIDDLEWARE_FEATURES.md) | [中文](../../zh/features/MIDDLEWARE_FEATURES.md)

---









## Overview

CloudCastle HTTP Router provides a powerful middleware system for processing requests and responses.

### Main Features:

- ✅ Global middleware (applied to all routes)
- ✅ Route-specific middleware
- ✅ Group middleware
- ✅ PSR-15 support
- ✅ 5 built-in middleware
- ✅ MiddlewareDispatcher for chaining

---

## Global Middleware

Applied to ALL routes:

```php
use CloudCastle\Http\Router\Router;

$router = Router::getInstance();

$router->middleware([
    'cors',
    'security',
    'logging',
]);
```

**Retrieval:**
```php
$global = $router->getGlobalMiddleware();
```

---

## Route Middleware

Applied to specific routes:

```php
Route::get('/admin', $action)->middleware(['auth', 'admin']);
Route::post('/api/data', $action)->middleware(['api', 'throttle']);
```

---

## Group Middleware

Applied to route groups:

```php
Route::group(['middleware' => ['auth']], function() {
    Route::get('/profile', $action);
    Route::get('/settings', $action);
});
```

---

## Built-in Middleware

### 1. AuthMiddleware
Authentication check.

### 2. CorsMiddleware
CORS headers:
```php
Access-Control-Allow-Origin: *
Access-Control-Allow-Methods: GET, POST, ...
```

### 3. HttpsMiddleware
Forces HTTPS, redirects HTTP → HTTPS.

### 4. SecurityLoggerMiddleware
Logs security events.

### 5. SsrfProtectionMiddleware
Protection against Server-Side Request Forgery.

---

## PSR-15 Support

```php
use Psr\Http\Server\MiddlewareInterface;

class CustomMiddleware implements MiddlewareInterface
{
    public function process($request, $handler): ResponseInterface
    {
        // Before
        $request = $request->withAttribute('user', $user);
        
        // Process
        $response = $handler->handle($request);
        
        // After
        return $response->withHeader('X-Custom', 'value');
    }
}
```

---

## Comparison with Alternatives

| Feature | CloudCastle | Laravel | Symfony | FastRoute | Slim |
|---------|-------------|---------|---------|-----------|------|
| **Global middleware** | ✅ | ✅ | ✅ | ❌ | ✅ |
| **Route middleware** | ✅ | ✅ | ✅ | ❌ | ✅ |
| **Group middleware** | ✅ | ✅ | ✅ | ❌ | ✅ |
| **PSR-15 support** | ✅ | ⚠️ | ⚠️ | ❌ | ✅ |
| **Built-in middleware** | 5 | 10+ | 5+ | 0 | 2 |

**CloudCastle advantages:**
- ✅ Full PSR-15 compliance
- ✅ Simple API
- ✅ Security-focused middleware

---

[⬆ Back](../FEATURES_INDEX.md) | [🏠 Home](../../../README.md)

---

© 2024 CloudCastle HTTP Router. All Rights Reserved.


