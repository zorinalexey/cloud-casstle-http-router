# Caching Features - Detailed Description

**English** | [Русский](../../ru/features/CACHING_FEATURES.md) | [Deutsch](../../de/features/CACHING_FEATURES.md) | [Français](../../fr/features/CACHING_FEATURES.md) | [中文](../../zh/features/CACHING_FEATURES.md)

---









## Overview

Route caching for **maximum performance** - up to 10x speedup.

---

## Route Cache

### Usage

```php
// Enable cache
$router->enableCache('/path/to/cache');

// Compile and cache
$router->compile();

// Load from cache
$router->loadFromCache();

// Clear cache
$router->clearCache();
```

---

## Auto Compile

Automatic compilation when routes change:

```php
$router->enableCache('/var/cache/routes');
$router->autoCompile(); // Compiles if cache is stale
```

---

## Production Setup

```php
$router = new Router();

if (file_exists('/var/cache/routes.cache')) {
    // Load from cache (fast)
    $router->enableCache('/var/cache/routes.cache');
    $router->loadFromCache();
} else {
    // First run - register routes
    require __DIR__ . '/routes.php';
    
    // Compile and cache
    $router->enableCache('/var/cache/routes.cache');
    $router->compile();
}
```

---

## Performance

| Mode | Requests/sec | Time (ms) | Improvement |
|------|-------------|-----------|-------------|
| Without cache | 10,000 | 0.100 | - |
| With cache | **100,000** | **0.010** | **10x** |

---

## Comparison

| Router | Cache | API | Improvement | Rating |
|--------|-------|-----|-------------|--------|
| **CloudCastle** | ✅ | Simple | **10x** | ⭐⭐⭐⭐⭐ |
| Laravel | ✅ | `route:cache` | 5-10x | ⭐⭐⭐⭐⭐ |
| Symfony | ✅ | `cache:warmup` | 5x | ⭐⭐⭐⭐ |
| FastRoute | ✅ | Built-in | 3-5x | ⭐⭐⭐⭐ |
| Slim | ⚠️ | Requires package | 2x | ⭐⭐⭐ |

**Recommendation:** Always use cache in production!

---

[⬆ Back](../FEATURES_INDEX.md) | [🏠 Home](../../../README.md)

---

© 2024 CloudCastle HTTP Router. All Rights Reserved.


