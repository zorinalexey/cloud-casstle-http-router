# Route Macros

[**English**](11_ROUTE_MACROS.md) | [Русский](../../ru/features/11_ROUTE_MACROS.md) | [Deutsch](../../de/features/11_ROUTE_MACROS.md) | [Français](../../fr/features/11_ROUTE_MACROS.md) | [中文](../../zh/features/11_ROUTE_MACROS.md)

---

## 📚 Documentation Navigation

[README](../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [API_REFERENCE](../API_REFERENCE.md) | [ALL_FEATURES](../ALL_FEATURES.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [PERFORMANCE](../PERFORMANCE_ANALYSIS.md) | [SECURITY](../SECURITY_REPORT.md) | [COMPARISON](../COMPARISON.md) | [FAQ](../FAQ.md)

**Detailed Documentation:** [01](01_BASIC_ROUTING.md) | [02](02_ROUTE_PARAMETERS.md) | [03](03_ROUTE_GROUPS.md) | [04](04_RATE_LIMITING.md) | [05](05_IP_FILTERING.md) | [06](06_MIDDLEWARE.md) | [07](07_NAMED_ROUTES.md) | [08](08_TAGS.md) | [09](09_HELPER_FUNCTIONS.md) | [10](10_ROUTE_SHORTCUTS.md) | [11](11_ROUTE_MACROS.md) | [12](12_URL_GENERATION.md) | [13](13_EXPRESSION_LANGUAGE.md) | [14](14_CACHING.md) | [15](15_PLUGINS.md) | [16](16_LOADERS.md) | [17](17_PSR_SUPPORT.md) | [18](18_ACTION_RESOLVER.md) | [19](19_STATISTICS.md) | [20](20_SECURITY.md) | [21](21_EXCEPTIONS.md) | [22](22_CLI_TOOLS.md)

---

**Category:** Automation  
**Number of Macros:** 7  
**Complexity:** ⭐⭐ Intermediate Level

---

## Description

Route Macros are predefined route templates for quickly creating standard route sets (RESTful CRUD, authentication, admin, etc.). One macro creates multiple related routes.

## Macros

### 1. resource() - RESTful Resource

```php
Route::resource('users', UserController::class);
```

Creates 7 routes:

| Method | URI | Action | Name |
|--------|-----|--------|------|
| GET | /users | index | users.index |
| GET | /users/create | create | users.create |
| POST | /users | store | users.store |
| GET | /users/{id} | show | users.show |
| GET | /users/{id}/edit | edit | users.edit |
| PUT | /users/{id} | update | users.update |
| DELETE | /users/{id} | destroy | users.destroy |

### 2. apiResource() - API Resource

```php
Route::apiResource('posts', PostController::class);
```

Creates 5 routes (no create/edit forms):

| Method | URI | Action | Name |
|--------|-----|--------|------|
| GET | /posts | index | posts.index |
| POST | /posts | store | posts.store |
| GET | /posts/{id} | show | posts.show |
| PUT | /posts/{id} | update | posts.update |
| DELETE | /posts/{id} | destroy | posts.destroy |

### 3. auth() - Authentication Routes

```php
Route::auth();
```

Creates authentication routes:
- GET /login
- POST /login
- POST /logout
- GET /register
- POST /register
- Password reset routes

### 4. admin() - Admin Panel Routes

```php
Route::admin('admin', AdminController::class);
```

Creates admin routes with prefix and middleware.

### 5. api() - API Routes

```php
Route::api('v1', ApiController::class);
```

Creates versioned API routes.

### 6. crud() - Simple CRUD

```php
Route::crud('products', ProductController::class);
```

Creates basic CRUD routes.

### 7. Custom Macro

```php
Route::macro('myMacro', function($name, $controller) {
    Route::get("/$name", [$controller, 'index']);
    Route::post("/$name", [$controller, 'store']);
});

Route::myMacro('items', ItemController::class);
```

## Examples

```php
// RESTful resources
Route::resource('users', UserController::class);
Route::resource('posts', PostController::class);
Route::resource('comments', CommentController::class);

// API resources
Route::group(['prefix' => 'api/v1'], function() {
    Route::apiResource('users', ApiUserController::class);
    Route::apiResource('posts', ApiPostController::class);
});

// Custom macro usage
Route::macro('adminResource', function($name, $controller) {
    Route::group([
        'prefix' => 'admin',
        'middleware' => ['auth', 'admin']
    ], function() use ($name, $controller) {
        Route::resource($name, $controller);
    });
});

Route::adminResource('users', AdminUserController::class);
```

## See Also

- [Route Shortcuts](10_ROUTE_SHORTCUTS.md) - Quick configuration
- [Basic Routing](01_BASIC_ROUTING.md) - Route registration
- [API Reference](../API_REFERENCE.md) - Complete API reference

---

© 2024 CloudCastle HTTP Router  
[⬆ Back to top](#route-macros)