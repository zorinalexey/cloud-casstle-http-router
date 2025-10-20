# 中间件

[English](../../en/features/06_MIDDLEWARE.md) | [Русский](../../ru/features/06_MIDDLEWARE.md) | [Deutsch](../../de/features/06_MIDDLEWARE.md) | [Français](../../fr/features/06_MIDDLEWARE.md) | [**中文**](06_MIDDLEWARE.md)

---

## 📚 文档导航

[README](../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [API_REFERENCE](../API_REFERENCE.md) | [ALL_FEATURES](../ALL_FEATURES.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [PERFORMANCE](../PERFORMANCE_ANALYSIS.md) | [SECURITY](../SECURITY_REPORT.md) | [COMPARISON](../COMPARISON.md) | [FAQ](../FAQ.md)

**详细文档:** [01](01_BASIC_ROUTING.md) | [02](02_ROUTE_PARAMETERS.md) | [03](03_ROUTE_GROUPS.md) | [04](04_RATE_LIMITING.md) | [05](05_IP_FILTERING.md) | [06](06_MIDDLEWARE.md) | [07](07_NAMED_ROUTES.md) | [08](08_TAGS.md) | [09](09_HELPER_FUNCTIONS.md) | [10](10_ROUTE_SHORTCUTS.md) | [11](11_ROUTE_MACROS.md) | [12](12_URL_GENERATION.md) | [13](13_EXPRESSION_LANGUAGE.md) | [14](14_CACHING.md) | [15](15_PLUGINS.md) | [16](16_LOADERS.md) | [17](17_PSR_SUPPORT.md) | [18](18_ACTION_RESOLVER.md) | [19](19_STATISTICS.md) | [20](20_SECURITY.md) | [21](21_EXCEPTIONS.md) | [22](22_CLI_TOOLS.md)

---

**类别:** 请求处理  
**类型数量:** 6  
**复杂度:** ⭐⭐ 中级

---

## 描述

中间件是在主路由动作之前或之后执行的中间处理器。它们用于身份验证、日志记录、CORS、验证和其他任务。

## 应用中间件

### 1. 全局中间件

```php
// 应用到所有路由
Route::middleware([CorsMiddleware::class, LoggerMiddleware::class]);
```

### 2. 在特定路由上

```php
Route::get('/dashboard', $action)
    ->middleware([AuthMiddleware::class]);
```

### 3. 在组中

```php
Route::group(['middleware' => [AuthMiddleware::class]], function() {
    Route::get('/dashboard', $action);
    Route::get('/profile', $action);
});
```

## 内置中间件

### AuthMiddleware

```php
use CloudCastle\Http\Router\Middleware\AuthMiddleware;

Route::get('/dashboard', $action)
    ->middleware([AuthMiddleware::class]);

// 或通过快捷方式
Route::get('/dashboard', $action)->auth();
```

### CorsMiddleware

```php
use CloudCastle\Http\Router\Middleware\CorsMiddleware;

Route::get('/api/data', $action)
    ->middleware([CorsMiddleware::class]);

// 或通过快捷方式
Route::get('/api/data', $action)->cors();
```

### HttpsEnforcement

```php
use CloudCastle\Http\Router\Middleware\HttpsEnforcement;

Route::post('/payment', $action)
    ->middleware([HttpsEnforcement::class]);

// 或通过快捷方式
Route::post('/payment', $action)->secure();
```

### SecurityLogger

```php
use CloudCastle\Http\Router\Middleware\SecurityLogger;

Route::post('/admin/action', $action)
    ->middleware([SecurityLogger::class]);
```

## 自定义中间件

### 创建中间件

```php
namespace App\Middleware;

class CustomMiddleware
{
    public function handle($request, $next)
    {
        // 路由动作之前
        
        // 执行路由动作
        $response = $next($request);
        
        // 路由动作之后
        
        return $response;
    }
}
```

### 使用自定义中间件

```php
use App\Middleware\CustomMiddleware;

Route::get('/test', $action)
    ->middleware([CustomMiddleware::class]);
```

## 中间件模式

### 1. 身份验证

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

### 2. 角色检查

```php
class AdminMiddleware
{
    public function handle($request, $next)
    {
        if (!$request->user()->isAdmin()) {
            return response()->json(['error' => '禁止访问'], 403);
        }
        
        return $next($request);
    }
}
```

### 3. 请求日志

```php
class LoggerMiddleware
{
    public function handle($request, $next)
    {
        $start = microtime(true);
        
        $response = $next($request);
        
        $duration = microtime(true) - $start;
        Log::info("请求处理时间 {$duration}s");
        
        return $response;
    }
}
```

### 4. CORS头部

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

### 5. 速率限制

```php
class RateLimitMiddleware
{
    public function handle($request, $next)
    {
        $ip = $request->ip();
        
        if ($this->exceedsLimit($ip)) {
            return response()->json(['error' => '请求过多'], 429);
        }
        
        return $next($request);
    }
}
```

### 6. 请求验证

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

## 中间件顺序

中间件按注册顺序执行:

```php
Route::get('/test', $action)
    ->middleware([
        FirstMiddleware::class,   // 首先执行
        SecondMiddleware::class,  // 第二执行
        ThirdMiddleware::class    // 第三执行
    ]);
```

## 最佳实践

### 1. 单一职责

```php
// 好: 每个中间件有一个目的
Route::get('/admin', $action)
    ->middleware([
        AuthMiddleware::class,
        AdminMiddleware::class,
        LoggerMiddleware::class
    ]);
```

### 2. 可重用中间件

```php
// 为常见任务创建可重用中间件
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

### 3. 中间件组

```php
// 定义中间件组
Route::group([
    'middleware' => [
        AuthMiddleware::class,
        AdminMiddleware::class,
        SecurityLogger::class
    ]
], function() {
    // 所有管理路由
});
```

## 另请参阅

- [路由组](03_ROUTE_GROUPS.md) - 使用中间件组织路由
- [安全](20_SECURITY.md) - 安全功能概述
- [速率限制](04_RATE_LIMITING.md) - 速率限制中间件
- [API参考](../API_REFERENCE.md) - 完整API参考

---

© 2024 CloudCastle HTTP Router  
[⬆ 返回顶部](#中间件)