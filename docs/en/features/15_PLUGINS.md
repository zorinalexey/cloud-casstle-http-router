# С and  with тема плаг and но in 

**English** | [Русский](../../ru/features/15_PLUGINS.md) | [Deutsch](../../de/features/15_PLUGINS.md) | [Français](../../fr/features/15_PLUGINS.md) | [中文](../../zh/features/15_PLUGINS.md)

---







---

## 📚 Documentation Navigation

[README](../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [API_REFERENCE](../API_REFERENCE.md) | [ALL_FEATURES](../ALL_FEATURES.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [PERFORMANCE](../PERFORMANCE_ANALYSIS.md) | [SECURITY](../SECURITY_REPORT.md) | [COMPARISON](../COMPARISON.md) | [FAQ](../FAQ.md)

**Detailed documentation:** [01](01_BASIC_ROUTING.md) | [02](02_ROUTE_PARAMETERS.md) | [03](03_ROUTE_GROUPS.md) | [04](04_RATE_LIMITING.md) | [05](05_IP_FILTERING.md) | [06](06_MIDDLEWARE.md) | [07](07_NAMED_ROUTES.md) | [08](08_TAGS.md) | [09](09_HELPER_FUNCTIONS.md) | [10](10_ROUTE_SHORTCUTS.md) | [11](11_ROUTE_MACROS.md) | [12](12_URL_GENERATION.md) | [13](13_EXPRESSION_LANGUAGE.md) | [14](14_CACHING.md) | [15](15_PLUGINS.md) | [16](16_LOADERS.md) | [17](17_PSR_SUPPORT.md) | [18](18_ACTION_RESOLVER.md) | [19](19_STATISTICS.md) | [20](20_SECURITY.md) | [21](21_EXCEPTIONS.md) | [22](22_CLI_TOOLS.md)

---


**Category:** Ра with ш and ряемо with ть  
**Number of methods:** 13  
**Complexity:** ⭐⭐⭐ Advanced уро in ень

---

## Оп and  with ан and е

С and  with тема плаг and но in   by з in оляет ра with ш and рять функц and о on льно with ть роутера через  with обыт and я (hooks). Плаг and ны могут  in ы by лнять with я до/ by  with ле dispatch, пр and  рег and  with трац and  and  routeо in   and  пр and   and  with ключен and ях.

## PluginInterface

```php
interface PluginInterface
{
    // До dispatch
    public function beforeDispatch(Route $route, string $uri, string $method): void;
    
    // После dispatch
    public function afterDispatch(Route $route, mixed $result): mixed;
    
    // При регистрации маршрута
    public function onRouteRegistered(Route $route): void;
    
    // При исключении
    public function onException(Route $route, \Exception $e): void;
}
```

## Methods упра in лен and я

### 1. registerPlugin()

```php
use CloudCastle\Http\Router\Plugin\LoggerPlugin;

$plugin = new LoggerPlugin('/var/log/routes.log');
Route::registerPlugin($plugin);
```

### 2. unregisterPlugin()

```php
Route::unregisterPlugin('logger');
```

### 3-6. Друг and е methods

```php
// Get плагин
$plugin = Route::getPlugin('logger');

// Проверить наличие
if (Route::hasPlugin('analytics')) {
    // ...
}

// Get все плагины
$plugins = Route::getPlugins();
```

## В with троенные плаг and ны

### LoggerPlugin

```php
use CloudCastle\Http\Router\Plugin\LoggerPlugin;

$logger = new LoggerPlugin('/var/log/routes.log');
Route::registerPlugin($logger);

// Логирует:
// - Все запросы
// - Регистрацию маршрутов
// - Исключения
```

### AnalyticsPlugin

```php
use CloudCastle\Http\Router\Plugin\AnalyticsPlugin;

$analytics = new AnalyticsPlugin();
Route::registerPlugin($analytics);

// Собирает статистику:
$stats = $analytics->getStats();
// Количество запросов, время выполнения, и т.д.
```

### ResponseCachePlugin

```php
use CloudCastle\Http\Router\Plugin\ResponseCachePlugin;

$cache = new ResponseCachePlugin('/var/cache/responses', 3600);
Route::registerPlugin($cache);

// Кеширует ответы GET запросов на 1 час
```

## Создан and е ка with томного плаг and  on 

```php
use CloudCastle\Http\Router\Contracts\PluginInterface;
use CloudCastle\Http\Router\Route;

class MyPlugin implements PluginInterface
{
    public function beforeDispatch(Route $route, string $uri, string $method): void
    {
        echo "Before: $method $uri\n";
    }
    
    public function afterDispatch(Route $route, mixed $result): mixed
    {
        echo "After dispatch\n";
        return $result;
    }
    
    public function onRouteRegistered(Route $route): void
    {
        echo "Route registered: {$route->getUri()}\n";
    }
    
    public function onException(Route $route, \Exception $e): void
    {
        echo "Error: {$e->getMessage()}\n";
    }
}

Route::registerPlugin(new MyPlugin());
```

---

**Version:** 1.1.1  
**Стату with :** ✅ Стаб and ль on я функц and о on льно with ть


---

## 📚 Documentation Navigation

[README](../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [API_REFERENCE](../API_REFERENCE.md) | [ALL_FEATURES](../ALL_FEATURES.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [FAQ](../FAQ.md)

**Detailed documentation:** [01](01_BASIC_ROUTING.md) | [02](02_ROUTE_PARAMETERS.md) | [03](03_ROUTE_GROUPS.md) | [04](04_RATE_LIMITING.md) | [05](05_IP_FILTERING.md) | [06](06_MIDDLEWARE.md) | [07](07_NAMED_ROUTES.md) | [08](08_TAGS.md) | [09](09_HELPER_FUNCTIONS.md) | [10](10_ROUTE_SHORTCUTS.md) | [11](11_ROUTE_MACROS.md) | [12](12_URL_GENERATION.md) | [13](13_EXPRESSION_LANGUAGE.md) | [14](14_CACHING.md) | [15](15_PLUGINS.md) | [16](16_LOADERS.md) | [17](17_PSR_SUPPORT.md) | [18](18_ACTION_RESOLVER.md) | [19](19_STATISTICS.md) | [20](20_SECURITY.md) | [21](21_EXCEPTIONS.md) | [22](22_CLI_TOOLS.md)

**© 2024 CloudCastle HTTP Router**
