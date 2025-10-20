# Plugin System

[**English**](15_PLUGINS.md) | [Русский](../../ru/features/15_PLUGINS.md) | [Deutsch](../../de/features/15_PLUGINS.md) | [Français](../../fr/features/15_PLUGINS.md) | [中文](../../zh/features/15_PLUGINS.md)

---

## 📚 Documentation Navigation

[README](../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [API_REFERENCE](../API_REFERENCE.md) | [ALL_FEATURES](../ALL_FEATURES.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [PERFORMANCE](../PERFORMANCE_ANALYSIS.md) | [SECURITY](../SECURITY_REPORT.md) | [COMPARISON](../COMPARISON.md) | [FAQ](../FAQ.md)

**Detailed Documentation:** [01](01_BASIC_ROUTING.md) | [02](02_ROUTE_PARAMETERS.md) | [03](03_ROUTE_GROUPS.md) | [04](04_RATE_LIMITING.md) | [05](05_IP_FILTERING.md) | [06](06_MIDDLEWARE.md) | [07](07_NAMED_ROUTES.md) | [08](08_TAGS.md) | [09](09_HELPER_FUNCTIONS.md) | [10](10_ROUTE_SHORTCUTS.md) | [11](11_ROUTE_MACROS.md) | [12](12_URL_GENERATION.md) | [13](13_EXPRESSION_LANGUAGE.md) | [14](14_CACHING.md) | [15](15_PLUGINS.md) | [16](16_LOADERS.md) | [17](17_PSR_SUPPORT.md) | [18](18_ACTION_RESOLVER.md) | [19](19_STATISTICS.md) | [20](20_SECURITY.md) | [21](21_EXCEPTIONS.md) | [22](22_CLI_TOOLS.md)

---

**Category:** Extensibility  
**Number of Methods:** 13  
**Complexity:** ⭐⭐⭐ Advanced Level

---

## Description

Plugin system allows extending router functionality through events (hooks). Plugins can execute before/after dispatch, on route registration, and on exceptions.

## PluginInterface

```php
interface PluginInterface
{
    public function beforeDispatch(Route $route, string $uri, string $method): void;
    public function afterDispatch(Route $route, mixed $result): mixed;
    public function onRouteRegistered(Route $route): void;
    public function onException(Route $route, \Exception $e): void;
}
```

## Methods

### 1. registerPlugin()
```php
$plugin = new LoggerPlugin('/var/log/routes.log');
$router->registerPlugin($plugin);
```

### 2-13. Other Methods
- `removePlugin()` - Remove plugin
- `getPlugins()` - Get all plugins
- Built-in plugins: LoggerPlugin, CachePlugin, SecurityPlugin, etc.

## See Also

- [Middleware](06_MIDDLEWARE.md) - Middleware system
- [API Reference](../API_REFERENCE.md) - Complete API reference

---

© 2024 CloudCastle HTTP Router  
[⬆ Back to top](#plugin-system)