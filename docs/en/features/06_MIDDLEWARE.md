# Middleware

[**English**](06_MIDDLEWARE.md) | [Русский](../../ru/features/06_MIDDLEWARE.md) | [Deutsch](../../de/features/06_MIDDLEWARE.md) | [Français](../../fr/features/06_MIDDLEWARE.md) | [中文](../../zh/features/06_MIDDLEWARE.md)

---

## 📚 Documentation Navigation

[README](../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [API_REFERENCE](../API_REFERENCE.md) | [ALL_FEATURES](../ALL_FEATURES.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [PERFORMANCE](../PERFORMANCE_ANALYSIS.md) | [SECURITY](../SECURITY_REPORT.md) | [COMPARISON](../COMPARISON.md) | [FAQ](../FAQ.md)

**Detailed Documentation:** [01](01_BASIC_ROUTING.md) | [02](02_ROUTE_PARAMETERS.md) | [03](03_ROUTE_GROUPS.md) | [04](04_RATE_LIMITING.md) | [05](05_IP_FILTERING.md) | [06](06_MIDDLEWARE.md) | [07](07_NAMED_ROUTES.md) | [08](08_TAGS.md) | [09](09_HELPER_FUNCTIONS.md) | [10](10_ROUTE_SHORTCUTS.md) | [11](11_ROUTE_MACROS.md) | [12](12_URL_GENERATION.md) | [13](13_EXPRESSION_LANGUAGE.md) | [14](14_CACHING.md) | [15](15_PLUGINS.md) | [16](16_LOADERS.md) | [17](17_PSR_SUPPORT.md) | [18](18_ACTION_RESOLVER.md) | [19](19_STATISTICS.md) | [20](20_SECURITY.md) | [21](21_EXCEPTIONS.md) | [22](22_CLI_TOOLS.md)

---

**Category:** Request Processing  
**Number of Types:** 6  
**Complexity:** ⭐⭐ Intermediate Level

---

## Description

Middleware are intermediate handlers that execute before or after the main route action. They are used for authentication, logging, CORS, validation, and other tasks.

## Applying Middleware

### 1. Global Middleware

```php
// Applied to ALL routes
Route::middleware([CorsMiddleware::class, LoggerMiddleware::class]);
```

### 2. On Specific Route

```php
Route::get('/dashboard', $action)
    ->middleware([AuthMiddleware::class]);
```

### 3. In Group

```php
Route::group(['middleware' => [AuthMiddleware::class]], function() {
    Route::get('/dashboard', $action);
    Route::get('/profile', $action);
});
```

## Built-in Middleware

### AuthMiddleware

```php
use CloudCastle\Http\Router\Middleware\AuthMiddleware;

Route::get('/dashboard', $action)
    ->middleware([AuthMiddleware::class]);

// Or via shortcut
Route::get('/dashboard', $action)->auth();
```

### CorsMiddleware

```php
use CloudCastle\Http\Router\Middleware\CorsMiddleware;

Route::get('/api/data', $action)
    ->middleware([CorsMiddleware::class]);

// Or via shortcut
Route::get('/api/data', $action)->cors();
```

### HttpsEnforcement

```php
use CloudCastle\Http\Router\Middleware\HttpsEnforcement;

Route::post('/payment', $action)
    ->middleware([HttpsEnforcement::class]);

// Or via shortcut
Route::post('/payment', $action)->secure();
```

### SecurityLogger

```php
use CloudCastle\Http\Router\Middleware\SecurityLogger;

Route::post('/admin/action', $action)
    ->middleware([SecurityLogger::class]);
```

## Custom Middleware

### Creating Middleware

```php
namespace App\Middleware;

class CustomMiddleware
{
    public function handle($request, $next)
    {
        // Before route action
        
        // Execute route action
        $response = $next($request);
        
        // After route action
        
        return $response;
    }
}
```

### Using Custom Middleware

```php
use App\Middleware\CustomMiddleware;

Route::get('/test', $action)
    ->middleware([CustomMiddleware::class]);
```

## Middleware Patterns

### 1. Authentication

```php
class AuthMiddleware
{
    public function handle($request, $next)
    {
        if (!isset($_SESSION['user_id'])) {
            return response()->redirect('/login');
        }
        
        return $next($request);
    }
}
```

### 2. Role Check

```php
class AdminMiddleware
{
    public function handle($request, $next)
    {
        if (!$request->user()->isAdmin()) {
            return response()->json(['error' => 'Forbidden'], 403);
        }
        
        return $next($request);
    }
}
```

### 3. Request Logging

```php
class LoggerMiddleware
{
    public function handle($request, $next)
    {
        $start = microtime(true);
        
        $response = $next($request);
        
        $duration = microtime(true) - $start;
        Log::info("Request processed in {$duration}s");
        
        return $response;
    }
}
```

### 4. CORS Headers

```php
class CorsMiddleware
{
    public function handle($request, $next)
    {
        $response = $next($request);
        
        $response->header('Access-Control-Allow-Origin', '*');
        $response->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE');
        
        return $response;
    }
}
```

### 5. Rate Limiting

```php
class RateLimitMiddleware
{
    public function handle($request, $next)
    {
        $ip = $request->ip();
        
        if ($this->exceedsLimit($ip)) {
            return response()->json(['error' => 'Too many requests'], 429);
        }
        
        return $next($request);
    }
}
```

### 6. Request Validation

```php
class ValidateRequestMiddleware
{
    public function handle($request, $next)
    {
        $errors = $this->validate($request);
        
        if (!empty($errors)) {
            return response()->json(['errors' => $errors], 422);
        }
        
        return $next($request);
    }
}
```

## Middleware Order

Middleware executes in the order they are registered:

```php
Route::get('/test', $action)
    ->middleware([
        FirstMiddleware::class,   // Executes first
        SecondMiddleware::class,  // Executes second
        ThirdMiddleware::class    // Executes third
    ]);
```

## Best Practices

### 1. Single Responsibility

```php
// Good: Each middleware has one purpose
Route::get('/admin', $action)
    ->middleware([
        AuthMiddleware::class,
        AdminMiddleware::class,
        LoggerMiddleware::class
    ]);
```

### 2. Reusable Middleware

```php
// Create reusable middleware for common tasks
class CacheMiddleware
{
    public function handle($request, $next)
    {
        $key = $this->getCacheKey($request);
        
        if ($cached = cache()->get($key)) {
            return $cached;
        }
        
        $response = $next($request);
        cache()->put($key, $response, 3600);
        
        return $response;
    }
}
```

### 3. Middleware Groups

```php
// Define middleware groups
Route::group([
    'middleware' => [
        AuthMiddleware::class,
        AdminMiddleware::class,
        SecurityLogger::class
    ]
], function() {
    // All admin routes
});
```

## See Also

- [Route Groups](03_ROUTE_GROUPS.md) - Organizing routes with middleware
- [Security](20_SECURITY.md) - Security features overview
- [Rate Limiting](04_RATE_LIMITING.md) - Rate limiting middleware
- [API Reference](../API_REFERENCE.md) - Complete API reference

---

© 2024 CloudCastle HTTP Router  
[⬆ Back to top](#middleware)