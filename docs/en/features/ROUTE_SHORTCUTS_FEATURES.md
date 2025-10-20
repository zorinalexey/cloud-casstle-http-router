# Route Shortcuts Features

**English** | [Русский](../../ru/features/ROUTE_SHORTCUTS_FEATURES.md) | [Deutsch](../../de/features/ROUTE_SHORTCUTS_FEATURES.md) | [Français](../../fr/features/ROUTE_SHORTCUTS_FEATURES.md) | [中文](../../zh/features/ROUTE_SHORTCUTS_FEATURES.md)

---









## Overview

16 convenient shortcut methods for common route configurations.

---

## Middleware Shortcuts

### auth()
```php
Route::get('/profile', $action)->auth();
// Equivalent to: ->middleware(['auth'])
```

### guest()
```php
Route::get('/login', $action)->guest();
// Equivalent to: ->middleware(['guest'])
```

### api()
```php
Route::get('/api/users', $action)->api();
// Equivalent to: ->middleware(['api'])
```

### web()
```php
Route::get('/home', $action)->web();
// Equivalent to: ->middleware(['web'])
```

### cors()
```php
Route::post('/api/external', $action)->cors();
// Equivalent to: ->middleware(['cors'])
```

---

## Security Shortcuts

### localhost()
```php
Route::get('/debug', $action)->localhost();
// Equivalent to: ->whitelistIp(['127.0.0.1', '::1'])
```

### secure() / https()
```php
Route::post('/payment', $action)->secure();
Route::post('/payment', $action)->https();
// Equivalent to: ->https(true)
```

---

## Rate Limiting Shortcuts

### throttleStandard()
```php
Route::post('/api', $action)->throttleStandard();
// 60 requests per minute
```

### throttleStrict()
```php
Route::post('/login', $action)->throttleStrict();
// 10 requests per minute
```

### throttleGenerous()
```php
Route::get('/api/public', $action)->throttleGenerous();
// 120 requests per minute
```

---

## Tagging Shortcuts

### public()
```php
Route::get('/home', $action)->public();
// Tags: ['public']
```

### private()
```php
Route::get('/admin', $action)->private();
// Tags: ['private']
```

### admin()
```php
Route::get('/admin/panel', $action)->admin();
// Tags: ['admin']
```

---

## Combined Shortcuts

### apiEndpoint()
```php
Route::get('/api/users', $action)->apiEndpoint();
// Equivalent to: ->api()->throttleStandard()->tags(['api'])
```

### protected()
```php
Route::get('/secure', $action)->protected();
// Equivalent to: ->auth()->https()
```

---

## Comparison

| Shortcut | CloudCastle | Laravel | Symfony | FastRoute | Slim |
|----------|-------------|---------|---------|-----------|------|
| **Total shortcuts** | **16** | ~5 | 0 | 0 | 0 |
| **auth()** | ✅ | ⚠️ | ❌ | ❌ | ❌ |
| **localhost()** | ✅ | ❌ | ❌ | ❌ | ❌ |
| **throttle shortcuts** | 3 | 0 | 0 | 0 | 0 |
| **Combined shortcuts** | ✅ | ❌ | ❌ | ❌ | ❌ |

**CloudCastle has the MOST route shortcuts!**

---

[⬆ Back](../FEATURES_INDEX.md) | [🏠 Home](../../../README.md)

---

© 2024 CloudCastle HTTP Router. All Rights Reserved.


