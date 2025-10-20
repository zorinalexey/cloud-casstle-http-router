# Plugin Features

**English** | [Русский](../../ru/features/PLUGIN_FEATURES.md) | [Deutsch](../../de/features/PLUGIN_FEATURES.md) | [Français](../../fr/features/PLUGIN_FEATURES.md) | [中文](../../zh/features/PLUGIN_FEATURES.md)

---









## Overview

Extensible plugin system for adding custom functionality.

---

## Plugin Interface

```php
use CloudCastle\Http\Router\Contracts\PluginInterface;

interface PluginInterface
{
    public function boot(Router $router): void;
    public function beforeDispatch($uri, $method): void;
    public function afterDispatch(Route $route): void;
    public function onRouteRegistered(Route $route): void;
    public function onException(\Exception $e): void;
}
```

---

## Register Plugins

```php
// Register plugin
$router->registerPlugin(new LoggerPlugin());
$router->registerPlugin(new AnalyticsPlugin());

// Unregister
$router->unregisterPlugin('logger');

// Get plugin
$plugin = $router->getPlugin('analytics');
```

---

## Built-in Plugins

### 1. LoggerPlugin
Logs route dispatching.

### 2. AnalyticsPlugin
Collects route statistics.

### 3. ResponseCachePlugin
Caches route responses.

---

## Route-level Plugins

```php
Route::get('/users', $action)
    ->plugin(new CustomPlugin());
```

---

## Comparison

| Feature | CloudCastle | Laravel | Symfony | FastRoute | Slim |
|---------|-------------|---------|---------|-----------|------|
| **Plugin system** | ✅ | ⚠️ Service providers | ⚠️ Bundles | ❌ | ⚠️ |
| **Route-level** | ✅ | ❌ | ❌ | ❌ | ❌ |
| **5 hooks** | ✅ | ⚠️ | ⚠️ | ❌ | ⚠️ |

---

[⬆ Back](../FEATURES_INDEX.md) | [🏠 Home](../../../README.md)

---

© 2024 CloudCastle HTTP Router. All Rights Reserved.


