# Route Shortcuts

[**English**](10_ROUTE_SHORTCUTS.md) | [Русский](../../ru/features/10_ROUTE_SHORTCUTS.md) | [Deutsch](../../de/features/10_ROUTE_SHORTCUTS.md) | [Français](../../fr/features/10_ROUTE_SHORTCUTS.md) | [中文](../../zh/features/10_ROUTE_SHORTCUTS.md)

---

## 📚 Documentation Navigation

[README](../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [API_REFERENCE](../API_REFERENCE.md) | [ALL_FEATURES](../ALL_FEATURES.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [PERFORMANCE](../PERFORMANCE_ANALYSIS.md) | [SECURITY](../SECURITY_REPORT.md) | [COMPARISON](../COMPARISON.md) | [FAQ](../FAQ.md)

**Detailed Documentation:** [01](01_BASIC_ROUTING.md) | [02](02_ROUTE_PARAMETERS.md) | [03](03_ROUTE_GROUPS.md) | [04](04_RATE_LIMITING.md) | [05](05_IP_FILTERING.md) | [06](06_MIDDLEWARE.md) | [07](07_NAMED_ROUTES.md) | [08](08_TAGS.md) | [09](09_HELPER_FUNCTIONS.md) | [10](10_ROUTE_SHORTCUTS.md) | [11](11_ROUTE_MACROS.md) | [12](12_URL_GENERATION.md) | [13](13_EXPRESSION_LANGUAGE.md) | [14](14_CACHING.md) | [15](15_PLUGINS.md) | [16](16_LOADERS.md) | [17](17_PSR_SUPPORT.md) | [18](18_ACTION_RESOLVER.md) | [19](19_STATISTICS.md) | [20](20_SECURITY.md) | [21](21_EXCEPTIONS.md) | [22](22_CLI_TOOLS.md)

---

**Category:** Ease of Use  
**Number of Methods:** 14  
**Complexity:** ⭐ Beginner Level

---

## Description

Route Shortcuts are shortcut methods for quickly setting up typical route configurations (middleware, throttle, tags, etc.). One method call replaces multiple configuration lines.

## All Shortcuts

### 1. auth()
```php
Route::get('/dashboard', $action)->auth();
// Equivalent to: ->middleware([AuthMiddleware::class])
```

### 2. guest()
```php
Route::get('/login', $action)->guest();
// Only accessible to guests (not authenticated)
```

### 3. cors()
```php
Route::get('/api/data', $action)->cors();
// Adds CORS middleware
```

### 4. secure()
```php
Route::post('/payment', $action)->secure();
// Enforces HTTPS
```

### 5. apiResource()
```php
Route::apiResource('users', UserController::class);
// Creates 5 RESTful API routes (no create/edit forms)
```

### 6. resource()
```php
Route::resource('posts', PostController::class);
// Creates 7 RESTful routes (full CRUD)
```

### 7. redirect()
```php
Route::redirect('/old-url', '/new-url', 301);
// Permanent redirect
```

### 8. view()
```php
Route::view('/about', 'about.html');
// Render view directly
```

### 9. permanent()
```php
Route::redirect('/old', '/new')->permanent();
// 301 redirect
```

### 10. temporary()
```php
Route::redirect('/old', '/new')->temporary();
// 302 redirect
```

### 11. cached()
```php
Route::get('/data', $action)->cached(3600);
// Cache response for 1 hour
```

### 12. throttled()
```php
Route::post('/api/action', $action)->throttled(60, 1);
// 60 requests per minute
```

### 13. tagged()
```php
Route::get('/api/users', $action)->tagged('api');
// Add tags quickly
```

### 14. named()
```php
Route::get('/users/{id}', $action)->named('users.show');
// Set name
```

## Quick Examples

```php
// API endpoint with common settings
Route::get('/api/users', [UserController::class, 'index'])
    ->auth()
    ->cors()
    ->throttled(100, 1)
    ->tagged('api')
    ->cached(300);

// Secure admin route
Route::post('/admin/action', $action)
    ->auth()
    ->secure()
    ->throttled(10, 1);

// Public cached route
Route::get('/public/data', $action)
    ->cors()
    ->cached(3600)
    ->tagged('public');
```

## See Also

- [Middleware](06_MIDDLEWARE.md) - Middleware system
- [Route Macros](11_ROUTE_MACROS.md) - Route templates
- [API Reference](../API_REFERENCE.md) - Complete API reference

---

© 2024 CloudCastle HTTP Router  
[⬆ Back to top](#route-shortcuts)