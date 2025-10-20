# Plugin System - Детальное описание системы плагинов

[English](../../en/features/PLUGIN_FEATURES.md) | **Русский** | [Deutsch](../../de/features/PLUGIN_FEATURES.md) | [Français](../../fr/features/PLUGIN_FEATURES.md) | [中文](../../zh/features/PLUGIN_FEATURES.md)

---

## Содержание

- [Введение](#введение)
- [Создание плагина](#создание-плагина)
- [Хуки плагинов](#хуки-плагинов)
- [Встроенные плагины](#встроенные-плагины)
- [Управление плагинами](#управление-плагинами)
- [Сравнение с аналогами](#сравнение-с-аналогами)

---

## Введение

Plugin System позволяет расширять функциональность роутера без изменения основного кода.

---

## Создание плагина

```php
use CloudCastle\Http\Router\Contracts\PluginInterface;
use CloudCastle\Http\Router\Route;
use CloudCastle\Http\Router\Router;

class MyPlugin implements PluginInterface
{
    public function getName(): string {
        return 'my-plugin';
    }
    
    public function boot(Router $router): void {
        // Инициализация
    }
    
    public function beforeDispatch(Route $route, string $uri, string $method): void {
        // До выполнения маршрута
        error_log("Request: $method $uri");
    }
    
    public function afterDispatch(Route $route, mixed $result): mixed {
        // После выполнения
        return $result;
    }
    
    public function onRouteRegistered(Route $route): void {
        // При регистрации маршрута
    }
    
    public function onException(\Exception $exception): void {
        // При исключении
        error_log("Exception: " . $exception->getMessage());
    }
    
    public function isEnabled(): bool {
        return true;
    }
}
```

---

## Хуки плагинов

### 1. boot()

Вызывается при регистрации плагина:

```php
public function boot(Router $router): void {
    // Настройка плагина
    $this->router = $router;
    $this->initializeStorage();
}
```

### 2. beforeDispatch()

Вызывается перед выполнением маршрута:

```php
public function beforeDispatch(Route $route, string $uri, string $method): void {
    // Логирование запроса
    Log::info("Request", [
        'uri' => $uri,
        'method' => $method,
        'route' => $route->getName(),
    ]);
    
    // Метрики
    $this->startTimer();
}
```

### 3. afterDispatch()

Вызывается после выполнения:

```php
public function afterDispatch(Route $route, mixed $result): mixed {
    // Модификация результата
    if (is_array($result)) {
        $result['meta'] = [
            'route' => $route->getName(),
            'time' => $this->getElapsed(),
        ];
    }
    
    return $result;
}
```

### 4. onRouteRegistered()

При регистрации каждого маршрута:

```php
public function onRouteRegistered(Route $route): void {
    // Индексация для поиска
    $this->routeIndex[$route->getUri()] = $route;
}
```

### 5. onException()

При любом исключении:

```php
public function onException(\Exception $exception): void {
    // Логирование ошибок
    ErrorTracker::capture($exception);
}
```

---

## Встроенные плагины

### 1. LoggerPlugin

```php
use CloudCastle\Http\Router\Plugin\LoggerPlugin;

$plugin = new LoggerPlugin('/path/to/log');
$router->registerPlugin($plugin);

// Логирует все запросы
```

### 2. AnalyticsPlugin

```php
use CloudCastle\Http\Router\Plugin\AnalyticsPlugin;

$plugin = new AnalyticsPlugin();
$router->registerPlugin($plugin);

// Собирает аналитику
```

### 3. ResponseCachePlugin

```php
use CloudCastle\Http\Router\Plugin\ResponseCachePlugin;

$plugin = new ResponseCachePlugin(3600); // Кеш на 1 час
$router->registerPlugin($plugin);

// Кеширует ответы
```

---

## Управление плагинами

### На уровне роутера

```php
// Регистрация
$router->registerPlugin(new MyPlugin());

// Получение
$plugin = $router->getPlugin('my-plugin');

// Проверка
if ($router->hasPlugin('logger')) {
    // ...
}

// Удаление
$router->unregisterPlugin('my-plugin');

// Все плагины
$plugins = $router->getPlugins();
```

### На уровне маршрута

**Уникальная возможность** - плагины можно добавлять к конкретным маршрутам!

```php
// Один плагин
Route::get('/special', $action)
    ->plugin(new SpecialPlugin());

// Множественные
Route::post('/api/data', $action)
    ->plugins([
        new ValidationPlugin(),
        new CachingPlugin(),
        new MetricsPlugin(),
    ]);

// Проверка наличия
if ($route->hasPlugin('validation')) {
    // ...
}

// Получение
$plugin = $route->getPlugin('caching');

// Удаление
$route->removePlugin('metrics');

// Все плагины маршрута
$plugins = $route->getPlugins();
```

### Примеры применения

**Валидация для конкретного маршрута:**
```php
Route::post('/users', $action)
    ->plugin(new ValidationPlugin([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users',
    ]));
```

**Кеширование результата:**
```php
Route::get('/expensive-data', $action)
    ->plugin(new ResponseCachePlugin(3600)); // 1 час
```

**Метрики:**
```php
Route::post('/api/payment', $action)
    ->plugin(new MetricsPlugin('payments'));
```

---

## Сравнение с аналогами

| Роутер | Plugin System | Хуков | Встроенных | Оценка |
|--------|---------------|-------|------------|--------|
| **CloudCastle** | ✅ | **5** | **3** | **⭐⭐⭐⭐⭐** |
| Laravel | ✅ Service Providers | Много | Много | ⭐⭐⭐⭐⭐ |
| Symfony | ✅ Events | Много | Много | ⭐⭐⭐⭐⭐ |
| FastRoute | ❌ | - | - | ⭐ |
| Slim | ⚠️ Middleware | - | - | ⭐⭐ |

**Плюсы CloudCastle:**
- ✅ Простой интерфейс
- ✅ 5 полезных хуков
- ✅ Легко создавать свои

**Примеры:**

```php
// Метрики
class MetricsPlugin implements PluginInterface {
    public function beforeDispatch($route, $uri, $method): void {
        $this->startTime = microtime(true);
    }
    
    public function afterDispatch($route, $result): mixed {
        $duration = microtime(true) - $this->startTime;
        Metrics::record($route->getName(), $duration);
        return $result;
    }
}

// Security
class SecurityPlugin implements PluginInterface {
    public function beforeDispatch($route, $uri, $method): void {
        if ($this->isSuspicious($uri)) {
            throw new SecurityException();
        }
    }
}
```

---

[⬆ Наверх](#plugin-system---детальное-описание-системы-плагинов) | [📚 Все фичи](../ALL_FEATURES.md) | [🏠 Главная](../../../README.md)

© 2024 CloudCastle HTTP Router
