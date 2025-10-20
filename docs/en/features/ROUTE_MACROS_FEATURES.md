# Route Macros Features

[**English**](../../en/features/ROUTE_MACROS_FEATURES.md) | [Русский](../../ru/features/ROUTE_MACROS_FEATURES.md)

---

## Overview

Predefined route patterns for common use cases - 6+ ready templates.

---

## resource()

RESTful resource routing:

```php
RouteMacros::resource('users', UserController::class);

// Creates 7 routes:
// GET    /users           -> index
// GET    /users/create    -> create
// POST   /users           -> store
// GET    /users/{id}      -> show
// GET    /users/{id}/edit -> edit
// PUT    /users/{id}      -> update
// DELETE /users/{id}      -> destroy
```

---

## apiResource()

API resource with rate limiting:

```php
RouteMacros::apiResource('posts', PostController::class);

// Creates 6 routes (no create/edit):
// GET    /posts
// POST   /posts
// GET    /posts/{id}
// PUT    /posts/{id}
// PATCH  /posts/{id}
// DELETE /posts/{id}
```

---

## auth()

Authentication routes with throttling:

```php
RouteMacros::auth();

// Creates:
// GET  /login
// POST /login (throttled: 5 attempts/min)
// GET  /register
// POST /register (throttled: 3 attempts/min)
// POST /logout
// GET  /password/reset
// POST /password/reset
```

---

## adminPanel() (Unique!)

Admin panel routes with security:

```php
RouteMacros::adminPanel('AdminController', [
    'whitelistIp' => ['192.168.1.0/24']
]);

// Creates admin routes with:
// - /admin prefix
// - auth + admin middleware
// - HTTPS enforcement
// - IP whitelist
// - Rate limiting
```

---

## apiVersion()

API versioning:

```php
RouteMacros::apiVersion('v1', function() {
    Route::get('/users', $action);
    Route::get('/posts', $action);
});

// Creates /api/v1/users, /api/v1/posts
```

---

## webhooks() (Unique!)

Webhook endpoints with security:

```php
RouteMacros::webhooks('github', WebhookController::class, [
    'whitelistIp' => ['192.30.252.0/22'] // GitHub IPs
]);

// Creates secure webhook endpoints
```

---

## Comparison

| Macro | CloudCastle | Laravel | Symfony | FastRoute | Slim |
|-------|-------------|---------|---------|-----------|------|
| **resource()** | ✅ | ✅ | ⚠️ | ❌ | ❌ |
| **apiResource()** | ✅ | ✅ | ❌ | ❌ | ❌ |
| **auth()** | ✅ | ✅ | ⚠️ | ❌ | ❌ |
| **adminPanel()** | ✅ | ❌ | ❌ | ❌ | ❌ |
| **webhooks()** | ✅ | ❌ | ❌ | ❌ | ❌ |
| **Total macros** | **6+** | 3 | 0 | 0 | 0 |

**CloudCastle has unique macros not found elsewhere!**

---

[⬆ Back](../FEATURES_INDEX.md) | [🏠 Home](../../../README.md)

---

© 2024 CloudCastle HTTP Router. All Rights Reserved.


