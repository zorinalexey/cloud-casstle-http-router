# Action Resolver

[**English**](18_ACTION_RESOLVER.md) | [Русский](../../ru/features/18_ACTION_RESOLVER.md) | [Deutsch](../../de/features/18_ACTION_RESOLVER.md) | [Français](../../fr/features/18_ACTION_RESOLVER.md) | [中文](../../zh/features/18_ACTION_RESOLVER.md)

---

## 📚 Documentation Navigation

[README](../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [API_REFERENCE](../API_REFERENCE.md) | [ALL_FEATURES](../ALL_FEATURES.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [PERFORMANCE](../PERFORMANCE_ANALYSIS.md) | [SECURITY](../SECURITY_REPORT.md) | [COMPARISON](../COMPARISON.md) | [FAQ](../FAQ.md)

**Detailed Documentation:** [01](01_BASIC_ROUTING.md) | [02](02_ROUTE_PARAMETERS.md) | [03](03_ROUTE_GROUPS.md) | [04](04_RATE_LIMITING.md) | [05](05_IP_FILTERING.md) | [06](06_MIDDLEWARE.md) | [07](07_NAMED_ROUTES.md) | [08](08_TAGS.md) | [09](09_HELPER_FUNCTIONS.md) | [10](10_ROUTE_SHORTCUTS.md) | [11](11_ROUTE_MACROS.md) | [12](12_URL_GENERATION.md) | [13](13_EXPRESSION_LANGUAGE.md) | [14](14_CACHING.md) | [15](15_PLUGINS.md) | [16](16_LOADERS.md) | [17](17_PSR_SUPPORT.md) | [18](18_ACTION_RESOLVER.md) | [19](19_STATISTICS.md) | [20](20_SECURITY.md) | [21](21_EXCEPTIONS.md) | [22](22_CLI_TOOLS.md)

---

**Category:** Action Processing  
**Number of Formats:** 6  
**Complexity:** ⭐⭐ Intermediate Level

---

## Supported Formats

### 1. Closure
```php
Route::get('/users', function() {
    return 'Users list';
});
```

### 2. Array [Controller::class, 'method']
```php
Route::get('/users', [UserController::class, 'index']);
```

### 3. String 'Controller@method'
```php
Route::get('/users', 'UserController@index');
```

### 4. Callable
```php
Route::get('/users', [$service, 'handle']);
```

### 5. Invokable Class
```php
Route::get('/users', UserAction::class);
```

### 6. Custom Resolver
```php
$router->setActionResolver(new CustomResolver());
```

## See Also

- [Basic Routing](01_BASIC_ROUTING.md) - Route registration
- [API Reference](../API_REFERENCE.md) - Complete API reference

---

© 2024 CloudCastle HTTP Router  
[⬆ Back to top](#action-resolver)