# Система плагинов

**Категория:** Расширяемость  
**Количество методов:** 13  
**Сложность:** ⭐⭐⭐ Продвинутый уровень

---

## Описание

Система плагинов позволяет расширять функциональность роутера через события (hooks). Плагины могут выполняться до/после dispatch, при регистрации маршрутов и при исключениях.

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

## Методы управления

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

### 3-6. Другие методы

```php
// Получить плагин
$plugin = Route::getPlugin('logger');

// Проверить наличие
if (Route::hasPlugin('analytics')) {
    // ...
}

// Получить все плагины
$plugins = Route::getPlugins();
```

## Встроенные плагины

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

## Создание кастомного плагина

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

**Версия:** 1.1.1  
**Статус:** ✅ Стабильная функциональность

