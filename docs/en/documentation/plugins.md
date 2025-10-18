# Plugin System

**CloudCastle HTTP Router v1.1.1**  
**Date**: September 2025  
**Language**: English

---

## 🌍 Translations

- **[Русский](../../ru/documentation/plugins.md)**
- **[English](../../en/documentation/plugins.md)** (current)
- **[Deutsch](../../de/documentation/plugins.md)**
- **[Français](../../fr/documentation/plugins.md)**

---

## Table of Contents

1. [Introduction](#introduction)
2. [Basic Concepts](#basic-concepts)
3. [Built-in Plugins](#built-in-plugins)
4. [Creating Custom Plugins](#creating-custom-plugins)
5. [Lifecycle](#lifecycle)
6. [API Reference](#api-reference)
7. [Usage Examples](#usage-examples)

---

## Introduction

CloudCastle Router's plugin system provides a powerful mechanism for extending router functionality without modifying its source code. Plugins can intercept routing lifecycle events and add custom logic.

### Benefits

- 🔌 **Extensibility** - add new features without changing core code
- 🎯 **Modularity** - enable only the plugins you need
- 🚀 **Performance** - plugins execute only when needed
- 🔧 **Flexibility** - create custom plugins for your needs
- 📊 **Monitoring** - track router performance in real-time

---

## Basic Concepts

### PluginInterface

All plugins implement the `PluginInterface`:

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

Base class for creating plugins:

```php
use CloudCastle\Http\Router\Plugin\AbstractPlugin;

class MyPlugin extends AbstractPlugin
{
    public function getName(): string
    {
        return 'my_plugin';
    }
    
    // Override only the methods you need
    public function beforeDispatch(Route $route, string $uri, string $method): void
    {
        // Your logic here
    }
}
```

---

## Built-in Plugins

### LoggerPlugin

Logging router events.

**Features:**
- Log route registrations
- Log dispatches
- Log exceptions
- Customizable log format
- Selective event logging

**Example:**

```php
use CloudCastle\Http\Router\Plugin\LoggerPlugin;

$logger = new LoggerPlugin('/var/log/router.log');

// Configuration
$logger->setLogRouteRegistration(true);   // Log route registrations
$logger->setLogDispatches(true);          // Log dispatches
$logger->setLogExceptions(true);          // Log exceptions

$router->registerPlugin($logger);
```

---

### AnalyticsPlugin

Collect router statistics and metrics.

**Collected Metrics:**
- Number of dispatches
- Route hits
- HTTP method statistics
- Route execution times
- Exception count
- Most popular route
- Most used method
- Average execution time

**Example:**

```php
use CloudCastle\Http\Router\Plugin\AnalyticsPlugin;

$analytics = new AnalyticsPlugin();
$router->registerPlugin($analytics);

// ... use router ...

// Get statistics
$stats = $analytics->getStatistics();

echo "Total dispatches: {$stats['total_dispatches']}\n";
echo "Most popular route: {$stats['most_popular_route']}\n";
echo "Average execution time: {$stats['average_execution_time']}s\n";

// Reset statistics
$analytics->reset();
```

---

### ResponseCachePlugin

Cache route responses.

**Features:**
- Cache all routes or selectively
- Configurable TTL (Time To Live)
- Automatic cleanup of expired entries
- Cache statistics
- Clear cache by route

**Example:**

```php
use CloudCastle\Http\Router\Plugin\ResponseCachePlugin;

$cache = new ResponseCachePlugin(
    300,      // 5 minutes TTL
    false     // Don't cache all routes
);

// Specify which routes to cache
$cache->setCacheableRoutes(['users.list', 'posts.index', 'api.data']);

$router->registerPlugin($cache);

// Clear cache
$cache->clearCache();                  // Clear all cache
$cache->clearRouteCache($route);       // Clear specific route

// Statistics
$stats = $cache->getCacheStats();
echo "Total cached: {$stats['total_cached']}\n";
```

---

## Creating Custom Plugins

### Minimal Plugin

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

// Usage
$counter = new RequestCounterPlugin();
$router->registerPlugin($counter);

// Later
echo "Processed requests: " . $counter->getCount();
```

### Advanced Plugin

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
                    'timestamp' => time(),
                ];
            }
        }
        
        return $result;
    }
    
    public function getSlowRoutes(): array
    {
        return $this->slowRoutes;
    }
}
```

---

## Lifecycle

### Hook Execution Order

1. **Route Registration**
   ```
   Router::get() → Plugin::onRouteRegistered()
   ```

2. **Dispatch**
   ```
   Router::dispatch() → Plugin::beforeDispatch()
   ```

3. **Execution**
   ```
   Router::executeRoute() → Middleware → Action
   ```

4. **After Execution**
   ```
   Action result → Plugin::afterDispatch() → return
   ```

5. **On Exception**
   ```
   Exception → Plugin::onException() → throw
   ```

---

## API Reference

### Router Methods

```php
// Register plugin
$router->registerPlugin(PluginInterface $plugin): self

// Unregister plugin
$router->unregisterPlugin(string $name): self

// Check if plugin exists
$router->hasPlugin(string $name): bool

// Get plugin by name
$router->getPlugin(string $name): ?PluginInterface

// Get all plugins
$router->getPlugins(): array
```

### Plugin Methods

```php
// Basic
getName(): string                    // Unique plugin name
getVersion(): string                 // Plugin version
boot(Router $router): void          // Initialize on registration
isEnabled(): bool                   // Check if enabled
enable(): void                      // Enable plugin
disable(): void                     // Disable plugin

// Lifecycle hooks
onRouteRegistered(Route $route): void
beforeDispatch(Route $route, string $uri, string $method): void
afterDispatch(Route $route, mixed $result): mixed
onException(\Exception $exception): void
```

---

## Usage Examples

### Basic Usage

```php
use CloudCastle\Http\Router\Router;
use CloudCastle\Http\Router\Plugin\LoggerPlugin;
use CloudCastle\Http\Router\Plugin\AnalyticsPlugin;

$router = Router::getInstance();

// Register plugins
$router->registerPlugin(new LoggerPlugin('/tmp/router.log'));
$router->registerPlugin(new AnalyticsPlugin());

// Define routes
$router->get('/users', 'UserController@index')->name('users.index');

// Use router
$route = $router->dispatch('/users', 'GET');
$result = $router->executeRoute($route);

// Get statistics
$analytics = $router->getPlugin('analytics');
$stats = $analytics->getStatistics();
```

### Multiple Plugins

```php
$router
    ->registerPlugin(new LoggerPlugin('/var/log/router.log'))
    ->registerPlugin(new AnalyticsPlugin())
    ->registerPlugin(new ResponseCachePlugin(300))
    ->registerPlugin(new PerformanceMonitorPlugin());

// All plugins work in parallel
```

### Integration Plugin Example

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
}

// Usage
$sentry = \Sentry\init(['dsn' => 'your-dsn']);
$router->registerPlugin(new SentryPlugin($sentry));
```

---

## Best Practices

### 1. Plugin Naming

✅ **Good:**
```php
public function getName(): string {
    return 'my_company_plugin';  // Unique, snake_case
}
```

❌ **Bad:**
```php
public function getName(): string {
    return 'plugin';  // Too generic
}
```

### 2. Performance

✅ **Good:**
```php
public function beforeDispatch(Route $route, string $uri, string $method): void
{
    if (!$this->shouldProcess($route)) {
        return;  // Early exit
    }
    
    // Lightweight processing
}
```

### 3. Error Handling

✅ **Good:**
```php
public function afterDispatch(Route $route, mixed $result): mixed
{
    try {
        // Your logic
        return $result;
    } catch (\Exception $e) {
        error_log($e->getMessage());
        return $result;  // Don't break the main flow
    }
}
```

---

## See Also

- [API Reference](api-reference.md)
- [Middleware](middleware.md)
- [Examples](../../../examples/)

---

**Created**: September 2025  
**Last Updated**: October 2025

