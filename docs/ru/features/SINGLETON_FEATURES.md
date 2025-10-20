# Singleton Pattern - Паттерн одиночка

[English](../../en/features/SINGLETON_FEATURES.md) | **Русский** | [Deutsch](../../de/features/SINGLETON_FEATURES.md) | [Français](../../fr/features/SINGLETON_FEATURES.md) | [中文](../../zh/features/SINGLETON_FEATURES.md)

---

## Содержание

- [Введение](#введение)
- [getInstance()](#getinstance)
- [setInstance()](#setinstance)
- [reset()](#reset)
- [Использование](#использование)
- [Сравнение с аналогами](#сравнение-с-аналогами)

---

## Введение

CloudCastle Router реализует паттерн Singleton для удобного глобального доступа к экземпляру роутера.

### Зачем Singleton?

✅ Глобальный доступ из любой точки приложения  
✅ Один экземпляр для всего приложения  
✅ Facade API работает через singleton  
✅ Упрощает интеграцию  

---

## getInstance()

### Описание

Получить singleton экземпляр роутера.

### Использование

```php
use CloudCastle\Http\Router\Router;

// Получить экземпляр
$router = Router::getInstance();

// Использование
$router->get('/users', $action);
$router->post('/posts', $action);
```

### Автоматическое создание

Если экземпляра нет, создастся автоматически:

```php
// Первый вызов - создаст новый экземпляр
$router1 = Router::getInstance();

// Второй вызов - вернёт тот же экземпляр
$router2 = Router::getInstance();

var_dump($router1 === $router2); // true
```

### С Facade

Facade автоматически использует getInstance():

```php
use CloudCastle\Http\Router\Facade\Route;

// Внутри использует Router::getInstance()
Route::get('/users', $action);
Route::post('/posts', $action);
```

---

## setInstance()

### Описание

Установить кастомный экземпляр роутера.

### Использование

```php
// Создать кастомный экземпляр
$customRouter = new Router();
$customRouter->enableCache('/custom/cache');
$customRouter->enableAutoNaming();

// Установить как singleton
Router::setInstance($customRouter);

// Теперь getInstance() вернёт кастомный экземпляр
$router = Router::getInstance();
var_dump($router === $customRouter); // true
```

### Dependency Injection Container

```php
// В контейнере
$container->singleton(Router::class, function() {
    $router = new Router();
    $router->enableCache('/var/cache/routes');
    return $router;
});

// Установить в Router
$router = $container->make(Router::class);
Router::setInstance($router);

// Теперь доступен глобально
$router = Router::getInstance();
```

---

## reset()

### Описание

Сбросить singleton экземпляр (полезно для тестирования).

### Использование

```php
// Получить экземпляр
$router1 = Router::getInstance();
$router1->get('/users', $action);

// Сбросить
Router::reset();

// Следующий getInstance() создаст новый экземпляр
$router2 = Router::getInstance();

var_dump($router1 === $router2); // false
```

### В тестах

```php
use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase
{
    protected function setUp(): void
    {
        // Сбросить перед каждым тестом
        Router::reset();
    }
    
    public function testRouteRegistration()
    {
        $router = Router::getInstance();
        $router->get('/test', $action);
        
        $this->assertTrue($router->hasRoute('test'));
    }
    
    public function testAnotherFeature()
    {
        // Новый чистый экземпляр благодаря reset() в setUp()
        $router = Router::getInstance();
        
        $this->assertEquals(0, $router->count());
    }
}
```

---

## Использование

### Глобальная конфигурация

```php
// bootstrap.php

// Настроить singleton
$router = Router::getInstance();
$router->enableCache(__DIR__ . '/cache/routes');
$router->enableAutoNaming();
$router->middleware([
    CorsMiddleware::class,
    SecurityLogger::class,
]);

// Теперь доступно везде в приложении
```

### В разных файлах

**routes/web.php:**
```php
use CloudCastle\Http\Router\Router;

$router = Router::getInstance();

$router->get('/', HomeController::class);
$router->get('/about', AboutController::class);
```

**routes/api.php:**
```php
use CloudCastle\Http\Router\Router;

$router = Router::getInstance(); // Тот же экземпляр!

$router->group(['prefix' => '/api'], function() {
    Route::get('/users', UserController::class);
    Route::get('/posts', PostController::class);
});
```

### С helper функциями

```php
// helpers.php
function router(): Router
{
    return Router::getInstance();
}

// Использование
router()->get('/users', $action);
router()->post('/posts', $action);

$route = router()->current();
```

---

## Примеры

### Централизованная конфигурация

```php
// config/router.php
return function() {
    $router = Router::getInstance();
    
    // Global middleware
    $router->middleware([
        CorsMiddleware::class,
        SecurityLogger::class,
    ]);
    
    // Cache
    if (env('APP_ENV') === 'production') {
        $router->enableCache(storage_path('cache/routes'));
    }
    
    // Auto-naming
    if (env('AUTO_NAMING', false)) {
        $router->enableAutoNaming();
    }
    
    // Plugins
    $router->registerPlugin(new LoggerPlugin());
    $router->registerPlugin(new AnalyticsPlugin());
    
    return $router;
};

// bootstrap.php
$routerConfig = require __DIR__ . '/config/router.php';
$router = $routerConfig();
```

### Multi-tenant приложение

```php
// Для каждого tenant - свой роутер
class TenantManager
{
    public function switchTenant(string $tenantId): void
    {
        // Сброс текущего
        Router::reset();
        
        // Создание нового для tenant
        $router = new Router();
        $router->enableCache("/cache/$tenantId/routes");
        
        // Загрузка маршрутов tenant
        require __DIR__ . "/tenants/$tenantId/routes.php";
        
        // Установка как singleton
        Router::setInstance($router);
    }
}

// Использование
$tenantManager->switchTenant('tenant-1');
$route = Router::getInstance()->dispatch($uri, $method);
```

---

## Альтернатива Singleton

### Instance API

Если не хотите использовать Singleton:

```php
// Создать свой экземпляр
$router = new Router();

// НЕ использовать getInstance()
$router->get('/users', $action);
$router->post('/posts', $action);

// Передавать явно
function handleRequest(Router $router)
{
    $route = $router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
    return $route->run();
}

handleRequest($router);
```

### Dependency Injection

```php
class UserController
{
    public function __construct(
        private Router $router
    ) {}
    
    public function index()
    {
        $routes = $this->router->getRoutesByTag('api');
        return view('users.index', compact('routes'));
    }
}

// В контейнере
$container->bind(Router::class, function() {
    return Router::getInstance(); // Или new Router()
});
```

---

## Сравнение с аналогами

| Роутер | Singleton | getInstance() | setInstance() | reset() | Оценка |
|--------|-----------|---------------|---------------|---------|--------|
| **CloudCastle** | ✅ | ✅ | ✅ | ✅ | **⭐⭐⭐⭐⭐** |
| Laravel | ⚠️ Container | ⚠️ app() | ⚠️ | ⚠️ | ⭐⭐⭐⭐ |
| Symfony | ❌ | ❌ | ❌ | ❌ | ⭐⭐ |
| FastRoute | ❌ | ❌ | ❌ | ❌ | ⭐ |
| Slim | ⚠️ Container | ⚠️ | ⚠️ | ❌ | ⭐⭐⭐ |

### Преимущества CloudCastle

✅ **Простой Singleton** - классический паттерн  
✅ **getInstance()** - стандартный метод  
✅ **setInstance()** - кастомизация  
✅ **reset()** - для тестов  
✅ **Facade поддержка** - статический API  

---

## Best Practices

### 1. Конфигурация в одном месте

```php
// bootstrap.php - настройка один раз
$router = Router::getInstance();
$router->enableCache(__DIR__ . '/cache');
$router->middleware($globalMiddleware);

// Везде дальше - просто используем
```

### 2. reset() в тестах

```php
class TestCase
{
    protected function setUp(): void
    {
        Router::reset(); // Чистый state
    }
}
```

### 3. DI когда нужна изоляция

```php
// Вместо singleton - используйте DI
public function __construct(Router $router)
{
    $this->router = $router;
}
```

---

## Заключение

**CloudCastle Singleton Pattern:**

✅ getInstance() - получение экземпляра  
✅ setInstance() - установка кастомного  
✅ reset() - сброс (для тестов)  
✅ Facade API через singleton  
✅ Глобальный доступ  
✅ Поддержка DI  

**Рекомендация:** Используйте Singleton для удобства, DI - для тестируемости!

---

[⬆ Наверх](#singleton-pattern---паттерн-одиночка) | [📚 Все фичи](../ALL_FEATURES.md) | [🏠 Главная](../../../README.md)

© 2024 CloudCastle HTTP Router

