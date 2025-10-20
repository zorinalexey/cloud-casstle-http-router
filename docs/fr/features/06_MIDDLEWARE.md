# Middleware

---

## 📚 Navigation de la Documentation

[README](../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [API_REFERENCE](../API_REFERENCE.md) | [ALL_FEATURES](../ALL_FEATURES.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [PERFORMANCE](../PERFORMANCE_ANALYSIS.md) | [SECURITY](../SECURITY_REPORT.md) | [COMPARISON](../COMPARISON.md) | [FAQ](../FAQ.md)

**Детальная документация:** [01](01_BASIC_ROUTING.md) | [02](02_ROUTE_PARAMETERS.md) | [03](03_ROUTE_GROUPS.md) | [04](04_RATE_LIMITING.md) | [05](05_IP_FILTERING.md) | [06](06_MIDDLEWARE.md) | [07](07_NAMED_ROUTES.md) | [08](08_TAGS.md) | [09](09_HELPER_FUNCTIONS.md) | [10](10_ROUTE_SHORTCUTS.md) | [11](11_ROUTE_MACROS.md) | [12](12_URL_GENERATION.md) | [13](13_EXPRESSION_LANGUAGE.md) | [14](14_CACHING.md) | [15](15_PLUGINS.md) | [16](16_LOADERS.md) | [17](17_PSR_SUPPORT.md) | [18](18_ACTION_RESOLVER.md) | [19](19_STATISTICS.md) | [20](20_SECURITY.md) | [21](21_EXCEPTIONS.md) | [22](22_CLI_TOOLS.md)

---


**Категория:** Обработка requêteов  
**Количество типов:** 6  
**Сложность:** ⭐⭐ Средний уровень

---

## Описание

Middleware - это промежуточные обработчики, которые выполняются до или после основного действия routeа. Они используются для аутентификации, логирования, CORS, валидации и других задач.

## Применение middleware

### 1. Глобальный middleware

```php
// Применяется ко ВСЕМ маршрутам
Route::middleware([CorsMiddleware::class, LoggerMiddleware::class]);
```

### 2. На конкретном routeе

```php
Route::get('/dashboard', $action)
    ->middleware([AuthMiddleware::class]);
```

### 3. В группе

```php
Route::group(['middleware' => [AuthMiddleware::class]], function() {
    Route::get('/dashboard', $action);
    Route::get('/profile', $action);
});
```

## Встроенные middleware

### AuthMiddleware

```php
use CloudCastle\Http\Router\Middleware\AuthMiddleware;

Route::get('/dashboard', $action)
    ->middleware([AuthMiddleware::class]);

// Или через shortcut
Route::get('/dashboard', $action)->auth();
```

### CorsMiddleware

```php
use CloudCastle\Http\Router\Middleware\CorsMiddleware;

Route::get('/api/data', $action)
    ->middleware([CorsMiddleware::class]);

// Или через shortcut
Route::get('/api/data', $action)->cors();
```

### HttpsEnforcement

```php
use CloudCastle\Http\Router\Middleware\HttpsEnforcement;

Route::post('/payment', $action)
    ->middleware([HttpsEnforcement::class]);

// Или через shortcut
Route::post('/payment', $action)->secure();
```

### SecurityLogger

```php
use CloudCastle\Http\Router\Middleware\SecurityLogger;

Route::post('/api/sensitive', $action)
    ->middleware([SecurityLogger::class]);
```

### SsrfProtection

```php
use CloudCastle\Http\Router\Middleware\SsrfProtection;

Route::post('/webhook', $action)
    ->middleware([SsrfProtection::class]);
```

### MiddlewareDispatcher

```php
use CloudCastle\Http\Router\MiddlewareDispatcher;

$dispatcher = new MiddlewareDispatcher();
$dispatcher->add(AuthMiddleware::class);
$dispatcher->add(LoggerMiddleware::class);

$response = $dispatcher->dispatch($route, fn($r) => $r->run());
```

## Создание кастомного middleware

```php
use CloudCastle\Http\Router\Contracts\MiddlewareInterface;
use CloudCastle\Http\Router\Route;

class CustomMiddleware implements MiddlewareInterface
{
    public function handle(Route $route, callable $next): mixed
    {
        // Before logic
        echo "Before route\n";
        
        // Execute route
        $response = $next($route);
        
        // After logic
        echo "After route\n";
        
        return $response;
    }
}

Route::get('/test', $action)
    ->middleware([CustomMiddleware::class]);
```

## Порядок выполнения

```php
Route::get('/test', $action)
    ->middleware([
        FirstMiddleware::class,   // 1. Before
        SecondMiddleware::class,  // 2. Before
        ThirdMiddleware::class,   // 3. Before
    ]);

// Порядок:
// 1. FirstMiddleware::before
// 2. SecondMiddleware::before
// 3. ThirdMiddleware::before
// 4. Route Action
// 5. ThirdMiddleware::after
// 6. SecondMiddleware::after
// 7. FirstMiddleware::after
```

---

**Version:** 1.1.1  
**Статус:** ✅ Стабильная функциональность


---

## 📚 Navigation de la Documentation

[README](../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [API_REFERENCE](../API_REFERENCE.md) | [ALL_FEATURES](../ALL_FEATURES.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [FAQ](../FAQ.md)

**Детальная документация:** [01](01_BASIC_ROUTING.md) | [02](02_ROUTE_PARAMETERS.md) | [03](03_ROUTE_GROUPS.md) | [04](04_RATE_LIMITING.md) | [05](05_IP_FILTERING.md) | [06](06_MIDDLEWARE.md) | [07](07_NAMED_ROUTES.md) | [08](08_TAGS.md) | [09](09_HELPER_FUNCTIONS.md) | [10](10_ROUTE_SHORTCUTS.md) | [11](11_ROUTE_MACROS.md) | [12](12_URL_GENERATION.md) | [13](13_EXPRESSION_LANGUAGE.md) | [14](14_CACHING.md) | [15](15_PLUGINS.md) | [16](16_LOADERS.md) | [17](17_PSR_SUPPORT.md) | [18](18_ACTION_RESOLVER.md) | [19](19_STATISTICS.md) | [20](20_SECURITY.md) | [21](21_EXCEPTIONS.md) | [22](22_CLI_TOOLS.md)

**© 2024 CloudCastle HTTP Router**
