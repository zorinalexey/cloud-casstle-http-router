# Configuration - Конфигурация роутера

[English](../../en/features/CONFIGURATION_FEATURES.md) | **Русский** | [Deutsch](../../de/features/CONFIGURATION_FEATURES.md) | [Français](../../fr/features/CONFIGURATION_FEATURES.md) | [中文](../../zh/features/CONFIGURATION_FEATURES.md)

---

## Содержание

- [Введение](#введение)
- [Глобальный middleware](#глобальный-middleware)
- [Кеширование](#кеширование)
- [Auto-naming](#auto-naming)
- [Плагины](#плагины)
- [Singleton](#singleton)
- [Полная конфигурация](#полная-конфигурация)
- [Примеры конфигураций](#примеры-конфигураций)
- [Сравнение с аналогами](#сравнение-с-аналогами)

---

## Введение

CloudCastle Router предлагает гибкую систему конфигурации для настройки поведения роутера.

### Основные методы конфигурации

```php
$router = new Router();

// Или через singleton
$router = Router::getInstance();

// Конфигурация
$router->middleware([...])           // Глобальные middleware
       ->enableCache('/path')        // Кеширование
       ->enableAutoNaming()          // Автоименование
       ->registerPlugin($plugin);    // Плагины
```

---

## Глобальный middleware

### middleware()

Добавить глобальные middleware, которые применяются ко ВСЕМ маршрутам.

```php
use CloudCastle\Http\Router\Router;
use App\Middleware\CorsMiddleware;
use App\Middleware\SecurityLogger;
use App\Middleware\RateLimiter;

$router = Router::getInstance();

// Один middleware
$router->middleware(CorsMiddleware::class);

// Множественные
$router->middleware([
    CorsMiddleware::class,
    SecurityLogger::class,
    RateLimiter::class,
]);
```

### Порядок выполнения

Глобальные middleware выполняются **ДО** middleware маршрута:

```php
// Глобальные
$router->middleware([
    'cors',      // 1
    'security',  // 2
]);

// На маршруте
Route::get('/users', $action)->middleware([
    'auth',      // 3
    'throttle',  // 4
]);

// Порядок: cors → security → auth → throttle → action
```

### Получение списка

```php
$globalMiddleware = $router->getGlobalMiddleware();
// Array: ['cors', 'security', ...]
```

---

## Кеширование

### enableCache()

Включает кеширование скомпилированных маршрутов.

```php
// С путём к директории
$router->enableCache('/var/cache/routes');

// Или с путём к файлу
$router->enableCache('/var/cache/routes/routes.cache');

// Chainable
$router->enableCache('/cache')
       ->compile();  // Скомпилировать сразу
```

### disableCache()

Отключает кеширование.

```php
$router->disableCache();
```

### Проверка статуса

```php
if ($router->isCacheEnabled()) {
    echo "Cache enabled";
}

$cachePath = $router->getCachePath();
// /var/cache/routes
```

### autoCompile()

Автоматическая компиляция при необходимости.

```php
$router->enableCache('/cache')
       ->autoCompile();  // Компилирует, если кеша нет
```

### Методы компиляции

```php
// Принудительная компиляция
$router->compile();

// Загрузка из кеша
$router->loadFromCache();

// Очистка кеша
$router->clearCache();
```

---

## Auto-naming

### enableAutoNaming()

Включает автоматическую генерацию имён для маршрутов без явных имён.

```php
$router->enableAutoNaming();

// Теперь все маршруты без name() получат автоимена
Route::get('/users', $action);
// Автоимя: "users.get"
```

### disableAutoNaming()

Отключает автогенерацию.

```php
$router->disableAutoNaming();
```

### Проверка статуса

```php
if ($router->isAutoNamingEnabled()) {
    echo "Auto-naming включен";
}
```

---

## Плагины

### registerPlugin()

Регистрирует плагин в роутере.

```php
use CloudCastle\Http\Router\Plugin\LoggerPlugin;
use CloudCastle\Http\Router\Plugin\AnalyticsPlugin;

// Один плагин
$router->registerPlugin(new LoggerPlugin('/logs/router.log'));

// Множественные
$router->registerPlugin(new LoggerPlugin())
       ->registerPlugin(new AnalyticsPlugin())
       ->registerPlugin(new CustomPlugin());
```

### unregisterPlugin()

Удаляет плагин.

```php
$router->unregisterPlugin('logger');
```

### Получение плагинов

```php
// Все плагины
$plugins = $router->getPlugins();

// Конкретный плагин
$logger = $router->getPlugin('logger');

// Проверка наличия
if ($router->hasPlugin('analytics')) {
    // ...
}
```

---

## Singleton

### getInstance()

Получить singleton экземпляр.

```php
$router = Router::getInstance();
```

### setInstance()

Установить кастомный экземпляр как singleton.

```php
$customRouter = new Router();
$customRouter->enableCache('/cache');

Router::setInstance($customRouter);
```

### reset()

Сбросить singleton (полезно для тестов).

```php
Router::reset();
```

---

## Полная конфигурация

### Пример полной настройки

```php
use CloudCastle\Http\Router\Router;
use App\Middleware\{CorsMiddleware, SecurityLogger, AuthMiddleware};
use App\Plugins\{LoggerPlugin, MetricsPlugin};

// Получить экземпляр
$router = Router::getInstance();

// 1. Глобальные middleware
$router->middleware([
    CorsMiddleware::class,
    SecurityLogger::class,
]);

// 2. Кеширование
if (env('APP_ENV') === 'production') {
    $router->enableCache(storage_path('cache/routes'))
           ->loadFromCache();
} else {
    $router->disableCache();
}

// 3. Auto-naming
if (env('AUTO_NAMING', false)) {
    $router->enableAutoNaming();
}

// 4. Плагины
$router->registerPlugin(new LoggerPlugin(storage_path('logs/router.log')))
       ->registerPlugin(new MetricsPlugin());

// 5. Теперь регистрируем маршруты
require __DIR__ . '/routes/web.php';
require __DIR__ . '/routes/api.php';

return $router;
```

---

## Примеры конфигураций

### Development

```php
// config/router.dev.php

$router = Router::getInstance();

$router
    // Без кеша для автоперезагрузки
    ->disableCache()
    
    // С автонеймингом для удобства
    ->enableAutoNaming()
    
    // Логирование всех запросов
    ->registerPlugin(new VerboseLoggerPlugin())
    
    // Debug middleware
    ->middleware([
        DebugMiddleware::class,
        CorsMiddleware::class,
    ]);

return $router;
```

### Production

```php
// config/router.prod.php

$router = Router::getInstance();

$router
    // Кеширование для производительности
    ->enableCache('/var/cache/routes')
    ->loadFromCache()
    
    // Без автонейминга (явные имена)
    ->disableAutoNaming()
    
    // Production плагины
    ->registerPlugin(new ErrorTrackingPlugin())
    ->registerPlugin(new MetricsPlugin())
    
    // Security middleware
    ->middleware([
        SecurityHeadersMiddleware::class,
        CorsMiddleware::class,
        RateLimitMiddleware::class,
    ]);

return $router;
```

### Testing

```php
// tests/bootstrap.php

// Сброс для изоляции
Router::reset();

$router = Router::getInstance();

$router
    // Без кеша
    ->disableCache()
    
    // Без плагинов
    // (не регистрируем)
    
    // Минимум middleware
    ->middleware([
        TestMiddleware::class,
    ]);

return $router;
```

### API Application

```php
// config/router.api.php

$router = Router::getInstance();

$router
    // Кеширование
    ->enableCache('/cache/api-routes')
    
    // API middleware
    ->middleware([
        CorsMiddleware::class,
        ApiAuthMiddleware::class,
        RateLimitMiddleware::class,
        JsonResponseMiddleware::class,
    ])
    
    // API плагины
    ->registerPlugin(new ApiAnalyticsPlugin())
    ->registerPlugin(new RequestValidationPlugin())
    
    // Автонейминг для версионированных API
    ->enableAutoNaming();

return $router;
```

### Microservices

```php
// config/router.microservice.php

$router = Router::getInstance();

$router
    // Быстрое кеширование
    ->enableCache(env('CACHE_PATH'))
    ->loadFromCache()
    
    // Service mesh middleware
    ->middleware([
        ServiceMeshMiddleware::class,
        TracingMiddleware::class,
        CircuitBreakerMiddleware::class,
    ])
    
    // Мониторинг
    ->registerPlugin(new PrometheusPlugin())
    ->registerPlugin(new DistributedTracingPlugin());

return $router;
```

---

## Конфигурация через Environment

### .env файл

```env
# Router configuration
ROUTER_CACHE_ENABLED=true
ROUTER_CACHE_PATH=/var/cache/routes
ROUTER_AUTO_NAMING=false
ROUTER_DEBUG=false
```

### Использование

```php
$router = Router::getInstance();

// Кеш
if (env('ROUTER_CACHE_ENABLED', false)) {
    $router->enableCache(env('ROUTER_CACHE_PATH'));
    $router->loadFromCache();
}

// Auto-naming
if (env('ROUTER_AUTO_NAMING', false)) {
    $router->enableAutoNaming();
}

// Debug
if (env('ROUTER_DEBUG', false)) {
    $router->registerPlugin(new DebugPlugin());
}
```

---

## Конфигурация через Config файл

### config/router.php

```php
return [
    'cache' => [
        'enabled' => env('APP_ENV') === 'production',
        'path' => storage_path('cache/routes'),
    ],
    
    'auto_naming' => [
        'enabled' => env('ROUTER_AUTO_NAMING', false),
    ],
    
    'middleware' => [
        'global' => [
            \App\Middleware\CorsMiddleware::class,
            \App\Middleware\SecurityLogger::class,
        ],
    ],
    
    'plugins' => [
        \App\Plugins\LoggerPlugin::class,
        \App\Plugins\MetricsPlugin::class,
    ],
];
```

### Применение конфигурации

```php
$config = require __DIR__ . '/config/router.php';
$router = Router::getInstance();

// Кеш
if ($config['cache']['enabled']) {
    $router->enableCache($config['cache']['path'])
           ->loadFromCache();
}

// Auto-naming
if ($config['auto_naming']['enabled']) {
    $router->enableAutoNaming();
}

// Middleware
$router->middleware($config['middleware']['global']);

// Плагины
foreach ($config['plugins'] as $pluginClass) {
    $router->registerPlugin(new $pluginClass());
}
```

---

## Bootstrap паттерн

### bootstrap/router.php

```php
<?php

use CloudCastle\Http\Router\Router;

// 1. Получить роутер
$router = Router::getInstance();

// 2. Загрузить конфигурацию
$config = require __DIR__ . '/../config/router.php';

// 3. Применить конфигурацию
if ($config['cache']['enabled']) {
    $router->enableCache($config['cache']['path']);
    
    if (file_exists($config['cache']['path'])) {
        $router->loadFromCache();
    } else {
        // Первый запуск - регистрируем маршруты и компилируем
        require __DIR__ . '/../routes/web.php';
        require __DIR__ . '/../routes/api.php';
        $router->compile();
    }
} else {
    // Development - без кеша
    require __DIR__ . '/../routes/web.php';
    require __DIR__ . '/../routes/api.php';
}

// 4. Middleware
$router->middleware($config['middleware']['global']);

// 5. Плагины
foreach ($config['plugins'] as $plugin) {
    $router->registerPlugin(new $plugin());
}

return $router;
```

### public/index.php

```php
<?php

require __DIR__ . '/../vendor/autoload.php';

// Bootstrap роутер
$router = require __DIR__ . '/../bootstrap/router.php';

// Dispatch
try {
    $route = $router->dispatch(
        $_SERVER['REQUEST_URI'],
        $_SERVER['REQUEST_METHOD'],
        $_SERVER['HTTP_HOST'] ?? null,
        $_SERVER['REMOTE_ADDR'] ?? null
    );
    
    $result = $route->run();
    echo $result;
    
} catch (\Exception $e) {
    http_response_code(500);
    echo "Error: " . $e->getMessage();
}
```

---

## Сравнение с аналогами

| Функция | CloudCastle | Laravel | Symfony | FastRoute | Slim |
|---------|-------------|---------|---------|-----------|------|
| **Глобальный middleware** | ✅ middleware() | ✅ | ✅ | ❌ | ✅ |
| **Кеширование** | ✅ enableCache() | ✅ route:cache | ✅ cache:warmup | ✅ | ⚠️ |
| **Auto-naming** | ✅ enableAutoNaming() | ❌ | ❌ | ❌ | ❌ |
| **Плагины** | ✅ registerPlugin() | ⚠️ Service Providers | ⚠️ Events | ❌ | ❌ |
| **Singleton** | ✅ getInstance() | ⚠️ Container | ⚠️ | ❌ | ⚠️ |
| **Chainable API** | ✅ | ✅ | ⚠️ | ❌ | ⚠️ |

### Преимущества CloudCastle

✅ **Простая конфигурация** - все в одном месте  
✅ **Chainable API** - fluent интерфейс  
✅ **Гибкость** - множество опций  
✅ **Auto-naming** - уникальная возможность  
✅ **Plugin система** - расширяемость  

---

## Best Practices

### 1. Разделение конфигураций по окружениям

```php
// config/router.php
return require __DIR__ . '/router.' . env('APP_ENV', 'production') . '.php';
```

### 2. Кеширование в production

```php
if (env('APP_ENV') === 'production') {
    $router->enableCache('/cache')->loadFromCache();
}
```

### 3. Централизованная конфигурация

```php
// Один файл для всех настроек
$router = require __DIR__ . '/bootstrap/router.php';
```

### 4. Использование DI контейнера

```php
$container->singleton(Router::class, function() {
    $router = Router::getInstance();
    // Конфигурация...
    return $router;
});
```

---

## Заключение

**CloudCastle Configuration - мощная и гибкая:**

✅ middleware() - глобальные middleware  
✅ enableCache() - кеширование  
✅ enableAutoNaming() - автоименование  
✅ registerPlugin() - плагины  
✅ Singleton pattern  
✅ Chainable API  
✅ Environment конфигурация  
✅ Bootstrap паттерн  

**Рекомендация:** Используйте bootstrap файл для централизованной конфигурации!

---

[⬆ Наверх](#configuration---конфигурация-роутера) | [📚 Все фичи](../ALL_FEATURES.md) | [🏠 Главная](../../../README.md)

© 2024 CloudCastle HTTP Router

