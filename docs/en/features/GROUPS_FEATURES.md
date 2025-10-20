# Route Groups Features

**English** | [Русский](../ru/features/GROUPS_FEATURES.md) | [Deutsch](../de/features/GROUPS_FEATURES.md) | [Français](../fr/features/GROUPS_FEATURES.md) | [中文](../zh/features/GROUPS_FEATURES.md)

---





## Overview

Route groups allow combining routes with common attributes.

### Main Features:

- ✅ Common prefix for all group routes
- ✅ Common middleware
- ✅ Unlimited nested groups
- ✅ Domain/Port/Protocol restrictions
- ✅ IP filtering at group level
- ✅ Rate limiting for entire group
- ✅ Namespace for controllers
- ✅ Tags for all group routes

---

## Group Attributes

### 1. Prefix

```php
Route::group(['prefix' => '/api'], function() {
    Route::get('/users', $action);  // /api/users
    Route::get('/posts', $action);  // /api/posts
});
```

### 2. Middleware

```php
Route::group(['middleware' => ['auth']], function() {
    Route::get('/profile', $action);
    Route::get('/settings', $action);
});
```

### 3. Domain

```php
Route::group(['domain' => 'api.example.com'], function() {
    Route::get('/users', $action);
});
```

### 4. Port

```php
Route::group(['port' => 8080], function() {
    Route::get('/admin', $action);
});
```

### 5. HTTPS

```php
Route::group(['https' => true], function() {
    Route::post('/payment', $action);
});
```

### 6. IP Filtering

```php
Route::group(['whitelistIp' => ['192.168.1.0/24']], function() {
    Route::get('/admin', $action);
});
```

### 7. Rate Limiting

```php
Route::group(['throttle' => [60, 1]], function() {
    // Max 60 requests per minute for all routes
});
```

### 8. Namespace

```php
Route::group(['namespace' => 'App\\Controllers\\Admin'], function() {
    Route::get('/users', 'UserController@index');
    // Resolves to: App\Controllers\Admin\UserController
});
```

### 9. Tags

```php
Route::group(['tags' => ['api', 'v1']], function() {
    Route::get('/users', $action);  // Tags: api, v1
});
```

---

## Nested Groups

Unlimited nesting depth!

```php
Route::group(['prefix' => '/api'], function() {
    Route::group(['middleware' => ['auth']], function() {
        Route::group(['prefix' => '/admin'], function() {
            Route::get('/users', $action);
            // Path: /api/admin/users
            // Middleware: auth
        });
    });
});
```

---

## Comparison with Alternatives

| Feature | CloudCastle | Laravel | Symfony | FastRoute | Slim |
|---------|-------------|---------|---------|-----------|------|
| **Prefix** | ✅ | ✅ | ✅ | ✅ | ✅ |
| **Middleware** | ✅ | ✅ | ⚠️ | ❌ | ✅ |
| **Nesting** | ✅ Unlimited | ✅ Unlimited | ✅ Unlimited | ⚠️ | ✅ |
| **Domain** | ✅ | ✅ | ✅ | ❌ | ❌ |
| **Port** | ✅ | ❌ | ❌ | ❌ | ❌ |
| **HTTPS** | ✅ | ⚠️ | ⚠️ | ❌ | ❌ |
| **IP filtering** | ✅ | ❌ | ❌ | ❌ | ❌ |
| **Tags** | ✅ | ❌ | ❌ | ❌ | ❌ |

**CloudCastle has the MOST group attributes!**

---

[⬆ Back](../FEATURES_INDEX.md) | [🏠 Home](../../../README.md)

---

© 2024 CloudCastle HTTP Router. All Rights Reserved.


