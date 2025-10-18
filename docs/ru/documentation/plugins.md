# Система плагинов

**CloudCastle HTTP Router v1.1.1**  
**Дата**: Сентябрь 2025  
**Язык**: Русский

---

## 🌍 Переводы

- **[Русский](../../ru/documentation/plugins.md)** (текущий)
- **[English](../../en/documentation/plugins.md)**
- **[Deutsch](../../de/documentation/plugins.md)**
- **[Français](../../fr/documentation/plugins.md)**

---

## Содержание

1. [Введение](#введение)
2. [Базовые концепции](#базовые-концепции)
3. [Встроенные плагины](#встроенные-плагины)
4. [Создание кастомного плагина](#создание-кастомного-плагина)
5. [Жизненный цикл](#жизненный-цикл)
6. [API Reference](#api-reference)
7. [Примеры использования](#примеры-использования)

---

## Введение

Система плагинов CloudCastle Router предоставляет мощный механизм расширения функциональности роутера без изменения его исходного кода. Плагины могут перехватывать события жизненного цикла маршрутизации и добавлять свою логику.

### Преимущества

- 🔌 **Расширяемость** - добавляйте новый функционал без изменения core
- 🎯 **Модульность** - включайте только нужные плагины
- 🚀 **Производительность** - плагины выполняются только когда нужно
- 🔧 **Гибкость** - создавайте свои плагины под задачи
- 📊 **Мониторинг** - отслеживайте работу роутера в реальном времени

---

## Базовые концепции

### PluginInterface

Все плагины реализуют интерфейс `PluginInterface`:

```php
interface PluginInterface
{
    public function getName(): string;
    public function getVersion(): string;
    public function boot(Router $router): void;
    public function isEnabled(): bool;
    public function enable(): void;
    public function disable(): void;
    
    // Lifecycle hooks
    public function onRouteRegistered(Route $route): void;
    public function beforeDispatch(Route $route, string $uri, string $method): void;
    public function afterDispatch(Route $route, mixed $result): mixed;
    public function onException(\Exception $exception): void;
}
```

### AbstractPlugin

Базовый класс для создания плагинов:

```php
use CloudCastle\Http\Router\Plugin\AbstractPlugin;

class MyPlugin extends AbstractPlugin
{
    public function getName(): string
    {
        return 'my_plugin';
    }
    
    // Переопределяйте только нужные методы
    public function beforeDispatch(Route $route, string $uri, string $method): void
    {
        // Ваша логика
    }
}
```

---

## Встроенные плагины

### LoggerPlugin

Логирование событий роутера.

**Возможности:**
- Логирование регистрации маршрутов
- Логирование диспетчеризации
- Логирование исключений
- Настраиваемый формат логов
- Выбор событий для логирования

**Пример:**

```php
use CloudCastle\Http\Router\Plugin\LoggerPlugin;

$logger = new LoggerPlugin('/var/log/router.log');

// Настройка
$logger->setLogRouteRegistration(true);   // Логировать регистрацию маршрутов
$logger->setLogDispatches(true);          // Логировать диспетчеризацию
$logger->setLogExceptions(true);          // Логировать исключения

$router->registerPlugin($logger);
```

**Формат логов:**

```
[2025-09-01 12:00:00] [ROUTE REGISTERED] GET|POST /api/users -> users.api
[2025-09-01 12:00:01] [BEFORE DISPATCH] GET /api/users -> Route: users.api
[2025-09-01 12:00:01] [AFTER DISPATCH] Route: users.api, Result Type: array
[2025-09-01 12:00:05] [EXCEPTION] RouteNotFoundException: Route not found for URI: /invalid
```

---

### AnalyticsPlugin

Сбор статистики и метрик работы роутера.

**Собираемые метрики:**
- Количество диспетчеризаций
- Хиты по маршрутам
- Статистика HTTP методов
- Время выполнения маршрутов
- Количество исключений
- Самый популярный маршрут
- Самый используемый метод
- Среднее время выполнения

**Пример:**

```php
use CloudCastle\Http\Router\Plugin\AnalyticsPlugin;

$analytics = new AnalyticsPlugin();
$router->registerPlugin($analytics);

// ... работа с роутером ...

// Получение статистики
$stats = $analytics->getStatistics();

echo "Всего диспетчеризаций: {$stats['total_dispatches']}\n";
echo "Всего исключений: {$stats['total_exceptions']}\n";
echo "Самый популярный маршрут: {$stats['most_popular_route']}\n";
echo "Самый используемый метод: {$stats['most_used_method']}\n";
echo "Среднее время выполнения: {$stats['average_execution_time']}s\n";

// Хиты по маршрутам
foreach ($stats['route_hits'] as $route => $hits) {
    echo "$route: $hits хитов\n";
}

// Статистика методов
foreach ($stats['method_stats'] as $method => $count) {
    echo "$method: $count запросов\n";
}

// Сброс статистики
$analytics->reset();
```

---

### ResponseCachePlugin

Кеширование ответов маршрутов.

**Возможности:**
- Кеширование всех маршрутов или выборочно
- Настраиваемое время жизни (TTL)
- Автоматическое удаление устаревших записей
- Статистика кеша
- Очистка кеша по маршруту

**Пример:**

```php
use CloudCastle\Http\Router\Plugin\ResponseCachePlugin;

$cache = new ResponseCachePlugin(
    defaultTtl: 300,      // 5 минут
    cacheAllRoutes: false  // Не кешировать все
);

// Указать какие маршруты кешировать
$cache->setCacheableRoutes(['users.list', 'posts.index', 'api.data']);

// Или кешировать все
$cache->cacheAllRoutes(true);

$router->registerPlugin($cache);

// Проверка кеша
$route = $router->getRoute('users.list');
if ($cache->isCached($route)) {
    $result = $cache->getCachedResponse($route);
}

// Очистка кеша
$cache->clearCache();                  // Очистить весь кеш
$cache->clearRouteCache($route);       // Очистить для конкретного маршрута

// Статистика
$stats = $cache->getCacheStats();
echo "Всего в кеше: {$stats['total_cached']}\n";
echo "Активных: {$stats['active']}\n";
echo "Устаревших: {$stats['expired']}\n";
```

---

## Создание кастомного плагина

### Минимальный плагин

```php
use CloudCastle\Http\Router\Plugin\AbstractPlugin;
use CloudCastle\Http\Router\Route;

class RequestCounterPlugin extends AbstractPlugin
{
    private int $count = 0;
    
    public function getName(): string
    {
        return 'request_counter';
    }
    
    public function beforeDispatch(Route $route, string $uri, string $method): void
    {
        $this->count++;
    }
    
    public function getCount(): int
    {
        return $this->count;
    }
}

// Использование
$counter = new RequestCounterPlugin();
$router->registerPlugin($counter);

// Позже
echo "Обработано запросов: " . $counter->getCount();
```

### Продвинутый плагин

```php
class PerformanceMonitorPlugin extends AbstractPlugin
{
    private array $timings = [];
    private array $slowRoutes = [];
    private float $threshold = 0.5; // 500ms
    
    public function getName(): string
    {
        return 'performance_monitor';
    }
    
    public function getVersion(): string
    {
        return '1.0.0';
    }
    
    public function beforeDispatch(Route $route, string $uri, string $method): void
    {
        $key = $this->getRouteKey($route);
        $this->timings[$key] = microtime(true);
    }
    
    public function afterDispatch(Route $route, mixed $result): mixed
    {
        $key = $this->getRouteKey($route);
        
        if (isset($this->timings[$key])) {
            $duration = microtime(true) - $this->timings[$key];
            
            if ($duration > $this->threshold) {
                $this->slowRoutes[$key] = [
                    'route' => $route->getName() ?? $route->getUri(),
                    'duration' => $duration,
                    'threshold' => $this->threshold,
                    'timestamp' => time(),
                ];
                
                // Отправить уведомление
                $this->notifySlow($route, $duration);
            }
            
            unset($this->timings[$key]);
        }
        
        return $result;
    }
    
    public function getSlowRoutes(): array
    {
        return $this->slowRoutes;
    }
    
    public function setThreshold(float $threshold): self
    {
        $this->threshold = $threshold;
        return $this;
    }
    
    private function getRouteKey(Route $route): string
    {
        return md5($route->getUri() . implode(',', $route->getMethods()));
    }
    
    private function notifySlow(Route $route, float $duration): void
    {
        // Отправить в мониторинг, лог, etc.
        error_log(sprintf(
            "Slow route detected: %s took %.2fs (threshold: %.2fs)",
            $route->getName() ?? $route->getUri(),
            $duration,
            $this->threshold
        ));
    }
}
```

### Плагин с конфигурацией

```php
class SecurityAuditPlugin extends AbstractPlugin
{
    private array $config;
    private array $events = [];
    
    public function __construct(array $config = [])
    {
        $this->config = array_merge([
            'log_file' => '/var/log/security.log',
            'alert_email' => null,
            'track_ips' => true,
            'track_user_agents' => false,
        ], $config);
    }
    
    public function getName(): string
    {
        return 'security_audit';
    }
    
    public function beforeDispatch(Route $route, string $uri, string $method): void
    {
        $event = [
            'timestamp' => time(),
            'uri' => $uri,
            'method' => $method,
            'route' => $route->getName(),
        ];
        
        if ($this->config['track_ips']) {
            $event['ip'] = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        }
        
        if ($this->config['track_user_agents']) {
            $event['user_agent'] = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
        }
        
        $this->events[] = $event;
        $this->writeToLog($event);
    }
    
    public function onException(\Exception $exception): void
    {
        $this->writeToLog([
            'type' => 'exception',
            'class' => get_class($exception),
            'message' => $exception->getMessage(),
            'timestamp' => time(),
        ]);
        
        if ($this->config['alert_email']) {
            $this->sendAlert($exception);
        }
    }
    
    private function writeToLog(array $event): void
    {
        file_put_contents(
            $this->config['log_file'],
            json_encode($event) . "\n",
            FILE_APPEND
        );
    }
    
    private function sendAlert(\Exception $exception): void
    {
        mail(
            $this->config['alert_email'],
            'Security Alert: ' . get_class($exception),
            $exception->getMessage()
        );
    }
}
```

---

## Жизненный цикл

### Порядок вызова хуков

1. **Регистрация маршрута**
   ```
   Router::get() → Plugin::onRouteRegistered()
   ```

2. **Диспетчеризация**
   ```
   Router::dispatch() → Plugin::beforeDispatch()
   ```

3. **Выполнение**
   ```
   Router::executeRoute() → Middleware → Action
   ```

4. **После выполнения**
   ```
   Action result → Plugin::afterDispatch() → return
   ```

5. **При исключении**
   ```
   Exception → Plugin::onException() → throw
   ```

### Диаграмма

```
┌─────────────────────────────────────┐
│      Router::get('/users')          │
└────────────┬────────────────────────┘
             │
             ▼
    ┌────────────────────┐
    │ onRouteRegistered  │ ◄── All enabled plugins
    └────────────────────┘
             │
             ▼
┌─────────────────────────────────────┐
│   Router::dispatch('/users', 'GET') │
└────────────┬────────────────────────┘
             │
             ▼
    ┌────────────────────┐
    │  beforeDispatch    │ ◄── All enabled plugins
    └────────────────────┘
             │
             ▼
┌─────────────────────────────────────┐
│    Router::executeRoute($route)     │
└────────────┬────────────────────────┘
             │
             ▼
    ┌────────────────────┐
    │    Middleware      │
    └────────┬───────────┘
             │
             ▼
    ┌────────────────────┐
    │      Action        │
    └────────┬───────────┘
             │
             ▼
    ┌────────────────────┐
    │  afterDispatch     │ ◄── All enabled plugins
    └────────────────────┘
             │
             ▼
┌─────────────────────────────────────┐
│         Return result               │
└─────────────────────────────────────┘

     (On exception at any point)
             │
             ▼
    ┌────────────────────┐
    │   onException      │ ◄── All enabled plugins
    └────────────────────┘
```

---

## API Reference

### Router методы

```php
// Регистрация плагина
$router->registerPlugin(PluginInterface $plugin): self

// Удаление плагина
$router->unregisterPlugin(string $name): self

// Проверка наличия плагина
$router->hasPlugin(string $name): bool

// Получение плагина по имени
$router->getPlugin(string $name): ?PluginInterface

// Получение всех плагинов
$router->getPlugins(): array
```

### Plugin методы

```php
// Базовые
getName(): string                    // Уникальное имя плагина
getVersion(): string                 // Версия плагина
boot(Router $router): void          // Инициализация при регистрации
isEnabled(): bool                   // Проверка активности
enable(): void                      // Включить плагин
disable(): void                     // Выключить плагин

// Хуки жизненного цикла
onRouteRegistered(Route $route): void
beforeDispatch(Route $route, string $uri, string $method): void
afterDispatch(Route $route, mixed $result): mixed
onException(\Exception $exception): void
```

---

## Примеры использования

### Базовое использование

```php
use CloudCastle\Http\Router\Router;
use CloudCastle\Http\Router\Plugin\LoggerPlugin;
use CloudCastle\Http\Router\Plugin\AnalyticsPlugin;

$router = Router::getInstance();

// Регистрация плагинов
$router->registerPlugin(new LoggerPlugin('/tmp/router.log'));
$router->registerPlugin(new AnalyticsPlugin());

// Определение маршрутов
$router->get('/users', 'UserController@index')->name('users.index');
$router->get('/posts', 'PostController@index')->name('posts.index');

// Использование
$route = $router->dispatch('/users', 'GET');
$result = $router->executeRoute($route);

// Получение статистики
$analytics = $router->getPlugin('analytics');
$stats = $analytics->getStatistics();
```

### Множественные плагины

```php
$logger = new LoggerPlugin('/var/log/router.log');
$analytics = new AnalyticsPlugin();
$cache = new ResponseCachePlugin(300);
$monitor = new PerformanceMonitorPlugin();

$router
    ->registerPlugin($logger)
    ->registerPlugin($analytics)
    ->registerPlugin($cache)
    ->registerPlugin($monitor);

// Все плагины работают параллельно
```

### Условная активация

```php
$analytics = new AnalyticsPlugin();
$router->registerPlugin($analytics);

// В production - включить
if (ENV === 'production') {
    $analytics->enable();
} else {
    $analytics->disable();
}

// Или динамически
if ($request->header('X-Debug')) {
    $router->getPlugin('analytics')->enable();
}
```

### Создание плагина для интеграции

```php
class SentryPlugin extends AbstractPlugin
{
    private \Sentry\Client $sentry;
    
    public function __construct(\Sentry\Client $sentry)
    {
        $this->sentry = $sentry;
    }
    
    public function getName(): string
    {
        return 'sentry';
    }
    
    public function onException(\Exception $exception): void
    {
        $this->sentry->captureException($exception);
    }
    
    public function beforeDispatch(Route $route, string $uri, string $method): void
    {
        $this->sentry->addBreadcrumb([
            'category' => 'router',
            'message' => "Dispatching $method $uri",
            'level' => 'info',
        ]);
    }
}

// Использование
$sentry = \Sentry\init(['dsn' => 'your-dsn']);
$router->registerPlugin(new SentryPlugin($sentry));
```

### Цепочка плагинов

```php
// Плагины выполняются в порядке регистрации
$router
    ->registerPlugin(new AuthPlugin())      // 1. Проверка auth
    ->registerPlugin(new RateLimitPlugin()) // 2. Rate limiting
    ->registerPlugin(new LoggerPlugin())    // 3. Логирование
    ->registerPlugin(new AnalyticsPlugin()) // 4. Статистика
    ->registerPlugin(new CachePlugin());    // 5. Кеширование
```

---

## Best Practices

### 1. Именование плагинов

✅ **Хорошо:**
```php
public function getName(): string {
    return 'my_company_plugin';  // Уникально, snake_case
}
```

❌ **Плохо:**
```php
public function getName(): string {
    return 'plugin';  // Слишком общее
}
```

### 2. Производительность

✅ **Хорошо:**
```php
public function beforeDispatch(Route $route, string $uri, string $method): void
{
    if (!$this->shouldProcess($route)) {
        return;  // Ранний выход
    }
    
    // Легковесная обработка
}
```

❌ **Плохо:**
```php
public function beforeDispatch(Route $route, string $uri, string $method): void
{
    // Тяжелые операции на каждом запросе
    $this->database->query(...);
    $this->api->call(...);
}
```

### 3. Обработка ошибок

✅ **Хорошо:**
```php
public function afterDispatch(Route $route, mixed $result): mixed
{
    try {
        // Ваша логика
        return $result;
    } catch (\Exception $e) {
        error_log($e->getMessage());
        return $result;  // Не ломать основной flow
    }
}
```

### 4. Конфигурация

✅ **Хорошо:**
```php
class MyPlugin extends AbstractPlugin
{
    public function __construct(
        private array $config = []
    ) {
        // Значения по умолчанию
        $this->config = array_merge([
            'enabled' => true,
            'log_level' => 'info',
        ], $config);
    }
}
```

---

## FAQ

**Q: Могут ли плагины изменять результат выполнения?**

A: Да, метод `afterDispatch()` может модифицировать и возвращать измененный результат.

```php
public function afterDispatch(Route $route, mixed $result): mixed
{
    return array_merge($result, ['plugin' => 'added_data']);
}
```

**Q: Как отключить плагин временно?**

A: Используйте методы `disable()` и `enable()`:

```php
$plugin = $router->getPlugin('analytics');
$plugin->disable();

// ... запросы не отслеживаются ...

$plugin->enable();
```

**Q: Можно ли использовать плагины с Facade?**

A: Да, плагины регистрируются в singleton Router, который использует Facade:

```php
use CloudCastle\Http\Router\Facade\Route;

Route::getInstance()->registerPlugin(new MyPlugin());
```

**Q: В каком порядке выполняются плагины?**

A: В порядке регистрации. Если важен порядок, регистрируйте плагины в нужной последовательности.

---

## См. также

- [API Reference](api-reference.md)
- [Middleware](middleware.md)
- [Примеры](../../../examples/)
- [Тесты плагинов](../../../tests/Unit/PluginSystemTest.php)

---

**Дата создания**: Сентябрь 2025  
**Последнее обновление**: Октябрь 2025

