# Expression Language

[**English**](13_EXPRESSION_LANGUAGE.md) | [Русский](../../ru/features/13_EXPRESSION_LANGUAGE.md) | [Deutsch](../../de/features/13_EXPRESSION_LANGUAGE.md) | [Français](../../fr/features/13_EXPRESSION_LANGUAGE.md) | [中文](../../zh/features/13_EXPRESSION_LANGUAGE.md)

---

## 📚 Documentation Navigation

[README](../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [API_REFERENCE](../API_REFERENCE.md) | [ALL_FEATURES](../ALL_FEATURES.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [PERFORMANCE](../PERFORMANCE_ANALYSIS.md) | [SECURITY](../SECURITY_REPORT.md) | [COMPARISON](../COMPARISON.md) | [FAQ](../FAQ.md)

**Detailed Documentation:** [01](01_BASIC_ROUTING.md) | [02](02_ROUTE_PARAMETERS.md) | [03](03_ROUTE_GROUPS.md) | [04](04_RATE_LIMITING.md) | [05](05_IP_FILTERING.md) | [06](06_MIDDLEWARE.md) | [07](07_NAMED_ROUTES.md) | [08](08_TAGS.md) | [09](09_HELPER_FUNCTIONS.md) | [10](10_ROUTE_SHORTCUTS.md) | [11](11_ROUTE_MACROS.md) | [12](12_URL_GENERATION.md) | [13](13_EXPRESSION_LANGUAGE.md) | [14](14_CACHING.md) | [15](15_PLUGINS.md) | [16](16_LOADERS.md) | [17](17_PSR_SUPPORT.md) | [18](18_ACTION_RESOLVER.md) | [19](19_STATISTICS.md) | [20](20_SECURITY.md) | [21](21_EXCEPTIONS.md) | [22](22_CLI_TOOLS.md)

---

**Category:** Advanced Features  
**Number of Operators:** 5  
**Complexity:** ⭐⭐⭐ Advanced Level

---

## Description

Expression Language allows creating conditions for routes based on computed expressions (IP, time, headers, etc.).

## Usage

### condition()

```php
// By IP
Route::get('/admin', $action)
    ->condition('request.ip == "192.168.1.1"');

// By time
Route::get('/api/data', $action)
    ->condition('request.time > 9 and request.time < 18');

// By headers
Route::get('/api/secure', $action)
    ->condition('request.header["X-API-Key"] == "secret"');
```

## Operators

### Comparison
- `==` - Equal
- `!=` - Not equal
- `>` - Greater than
- `<` - Less than
- `>=` - Greater or equal
- `<=` - Less or equal

### Logical
- `and` - Logical AND
- `or` - Logical OR
- `not` - Logical NOT

### String
- `matches` - Regex match
- `contains` - String contains
- `starts_with` - Starts with
- `ends_with` - Ends with

## Variables

- `request.ip` - Client IP
- `request.method` - HTTP method
- `request.uri` - Request URI
- `request.time` - Current hour (0-23)
- `request.header[name]` - Request header
- `request.query[name]` - Query parameter

## Examples

```php
// IP-based access
Route::get('/internal', $action)
    ->condition('request.ip matches "^192\\.168\\."');

// Time-based access (business hours only)
Route::get('/office-only', $action)
    ->condition('request.time >= 9 and request.time <= 17');

// Header-based access
Route::get('/api/v2', $action)
    ->condition('request.header["X-API-Version"] == "2.0"');

// Complex condition
Route::get('/special', $action)
    ->condition('
        (request.ip == "192.168.1.1" or request.ip == "10.0.0.1")
        and request.header["X-Auth-Token"] != ""
        and request.time >= 9
    ');

// Method-based
Route::match(['GET', 'POST'], '/data', $action)
    ->condition('request.method == "POST" and request.header["Content-Type"] contains "json"');
```

## See Also

- [Route Parameters](02_ROUTE_PARAMETERS.md) - Parameter validation
- [IP Filtering](05_IP_FILTERING.md) - IP-based access control
- [API Reference](../API_REFERENCE.md) - Complete API reference

---

© 2024 CloudCastle HTTP Router  
[⬆ Back to top](#expression-language)