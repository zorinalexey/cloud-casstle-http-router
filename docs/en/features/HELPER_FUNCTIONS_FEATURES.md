# Helper Functions Features

**English** | [Русский](../ru/features/HELPER_FUNCTIONS_FEATURES.md) | [Deutsch](../de/features/HELPER_FUNCTIONS_FEATURES.md) | [Français](../fr/features/HELPER_FUNCTIONS_FEATURES.md) | [中文](../zh/features/HELPER_FUNCTIONS_FEATURES.md)

---





## Overview

12+ global helper functions for convenient router interaction.

---

## Functions List

### route()
Get route by name or current route:
```php
$route = route('users.show');
$currentRoute = route(); // Current route
```

### router()
Get router instance:
```php
$router = router();
```

### current_route()
Get current route:
```php
$route = current_route();
```

### previous_route()
Get previous route:
```php
$prevRoute = previous_route();
```

### route_url()
Generate URL:
```php
$url = route_url('users.show', ['id' => 123]); // /users/123
```

### route_is()
Check route name:
```php
if (route_is('admin.*')) {
    echo 'Admin area';
}
```

### route_name()
Get current route name:
```php
$name = route_name(); // 'users.show'
```

### route_has()
Check if route exists:
```php
if (route_has('users.show')) {
    // Route exists
}
```

### route_stats()
Get route statistics:
```php
$stats = route_stats();
// ['total' => 150, 'named' => 120, ...]
```

### routes_by_tag()
Get routes by tag:
```php
$apiRoutes = routes_by_tag('api');
```

### route_back()
URL to previous route:
```php
$backUrl = route_back();
```

### dispatch_route()
Dispatch current request:
```php
$route = dispatch_route();
```

---

## Comparison

| Function | CloudCastle | Laravel | Symfony | FastRoute | Slim |
|----------|-------------|---------|---------|-----------|------|
| **route()** | ✅ | ✅ | ✅ | ❌ | ⚠️ |
| **route_url()** | ✅ | ✅ | ✅ | ❌ | ⚠️ |
| **current_route()** | ✅ | ✅ | ✅ | ❌ | ⚠️ |
| **routes_by_tag()** | ✅ | ❌ | ❌ | ❌ | ❌ |
| **route_stats()** | ✅ | ⚠️ | ⚠️ | ❌ | ❌ |

**CloudCastle has the MOST helper functions!**

---

[⬆ Back](../FEATURES_INDEX.md) | [🏠 Home](../../../README.md)

---

© 2024 CloudCastle HTTP Router. All Rights Reserved.


