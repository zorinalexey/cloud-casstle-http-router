# Route Loaders

[**English**](16_LOADERS.md) | [Русский](../../ru/features/16_LOADERS.md) | [Deutsch](../../de/features/16_LOADERS.md) | [Français](../../fr/features/16_LOADERS.md) | [中文](../../zh/features/16_LOADERS.md)

---

## 📚 Documentation Navigation

[README](../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [API_REFERENCE](../API_REFERENCE.md) | [ALL_FEATURES](../ALL_FEATURES.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [PERFORMANCE](../PERFORMANCE_ANALYSIS.md) | [SECURITY](../SECURITY_REPORT.md) | [COMPARISON](../COMPARISON.md) | [FAQ](../FAQ.md)

**Detailed Documentation:** [01](01_BASIC_ROUTING.md) | [02](02_ROUTE_PARAMETERS.md) | [03](03_ROUTE_GROUPS.md) | [04](04_RATE_LIMITING.md) | [05](05_IP_FILTERING.md) | [06](06_MIDDLEWARE.md) | [07](07_NAMED_ROUTES.md) | [08](08_TAGS.md) | [09](09_HELPER_FUNCTIONS.md) | [10](10_ROUTE_SHORTCUTS.md) | [11](11_ROUTE_MACROS.md) | [12](12_URL_GENERATION.md) | [13](13_EXPRESSION_LANGUAGE.md) | [14](14_CACHING.md) | [15](15_PLUGINS.md) | [16](16_LOADERS.md) | [17](17_PSR_SUPPORT.md) | [18](18_ACTION_RESOLVER.md) | [19](19_STATISTICS.md) | [20](20_SECURITY.md) | [21](21_EXCEPTIONS.md) | [22](22_CLI_TOOLS.md)

---

**Category:** Configuration  
**Number of Types:** 5  
**Complexity:** ⭐⭐ Intermediate Level

---

## Description

Loaders allow defining routes in various formats (JSON, YAML, XML, PHP Attributes, PHP files) instead of programmatic registration.

## Loader Types

### 1. JsonLoader
```php
use CloudCastle\Http\Router\Loader\JsonLoader;

$loader = new JsonLoader($router);
$loader->load(__DIR__ . '/routes.json');
```

### 2. YamlLoader
```php
$loader = new YamlLoader($router);
$loader->load(__DIR__ . '/routes.yaml');
```

### 3. XmlLoader
```php
$loader = new XmlLoader($router);
$loader->load(__DIR__ . '/routes.xml');
```

### 4. AttributeLoader
```php
$loader = new AttributeLoader($router);
$loader->loadFromDirectory(__DIR__ . '/Controllers');
```

### 5. PhpLoader
```php
$loader = new PhpLoader($router);
$loader->load(__DIR__ . '/routes.php');
```

## See Also

- [Basic Routing](01_BASIC_ROUTING.md) - Route registration
- [API Reference](../API_REFERENCE.md) - Complete API reference

---

© 2024 CloudCastle HTTP Router  
[⬆ Back to top](#route-loaders)