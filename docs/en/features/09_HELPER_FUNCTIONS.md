# Helper Functions

[**English**](09_HELPER_FUNCTIONS.md) | [Русский](../../ru/features/09_HELPER_FUNCTIONS.md) | [Deutsch](../../de/features/09_HELPER_FUNCTIONS.md) | [Français](../../fr/features/09_HELPER_FUNCTIONS.md) | [中文](../../zh/features/09_HELPER_FUNCTIONS.md)

---

## 📚 Documentation Navigation

[README](../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [API_REFERENCE](../API_REFERENCE.md) | [ALL_FEATURES](../ALL_FEATURES.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [PERFORMANCE](../PERFORMANCE_ANALYSIS.md) | [SECURITY](../SECURITY_REPORT.md) | [COMPARISON](../COMPARISON.md) | [FAQ](../FAQ.md)

**Detailed Documentation:** [01](01_BASIC_ROUTING.md) | [02](02_ROUTE_PARAMETERS.md) | [03](03_ROUTE_GROUPS.md) | [04](04_RATE_LIMITING.md) | [05](05_IP_FILTERING.md) | [06](06_MIDDLEWARE.md) | [07](07_NAMED_ROUTES.md) | [08](08_TAGS.md) | [09](09_HELPER_FUNCTIONS.md) | [10](10_ROUTE_SHORTCUTS.md) | [11](11_ROUTE_MACROS.md) | [12](12_URL_GENERATION.md) | [13](13_EXPRESSION_LANGUAGE.md) | [14](14_CACHING.md) | [15](15_PLUGINS.md) | [16](16_LOADERS.md) | [17](17_PSR_SUPPORT.md) | [18](18_ACTION_RESOLVER.md) | [19](19_STATISTICS.md) | [20](20_SECURITY.md) | [21](21_EXCEPTIONS.md) | [22](22_CLI_TOOLS.md)

---

**Category:** Helper Functions  
**Number of Functions:** 18  
**Complexity:** ⭐ Beginner Level

---

## Description

Helper Functions are global PHP functions that simplify working with the router, providing a short and convenient API without the need to use full class names.

## All Functions

### 1. route()

**Signature:** `route(?string $name = null, array $parameters = []): ?Route`

**Description:** Get route by name or return current route.

**Parameters:**
- `$name` - Route name (null = current route)
- `$parameters` - Parameters for substitution (reserved)

**Examples:**

```php
// Get route by name
$route = route('users.show');

// Get current route
$current = route();

// Check existence
if ($route = route('users.index')) {
    echo "Route exists: " . $route->getUri();
}

// Get route information
$route = route('api.users.show');
if ($route) {
    echo "URI: " . $route->getUri();
    echo "Name: " . $route->getName();
    echo "Methods: " . implode(', ', $route->getMethods());
}
```

### 2. current_route()

**Signature:** `current_route(): ?Route`

**Description:** Get currently executing route.

**Examples:**

```php
$route = current_route();
echo "Current: " . $route->getUri();
```

### 3. previous_route()

**Signature:** `previous_route(): ?Route`

**Description:** Get previously executed route.

**Examples:**

```php
$previous = previous_route();
if ($previous) {
    echo "Previous: " . $previous->getUri();
}
```

### 4. route_is()

**Signature:** `route_is(string $name): bool`

**Description:** Check if current route matches name.

**Examples:**

```php
if (route_is('users.show')) {
    echo "Viewing user";
}

if (route_is('admin.*')) {
    echo "Admin section";
}
```

### 5. route_name()

**Signature:** `route_name(): ?string`

**Description:** Get current route name.

**Examples:**

```php
$name = route_name();
// 'users.show'
```

### 6. router()

**Signature:** `router(): Router`

**Description:** Get router instance.

**Examples:**

```php
$router = router();
$allRoutes = $router->getAllRoutes();
```

### 7. dispatch_route()

**Signature:** `dispatch_route(string $uri, string $method = 'GET'): ?Route`

**Description:** Dispatch and execute route.

**Examples:**

```php
$route = dispatch_route('/users/123', 'GET');
```

## Quick Reference

```php
// Get routes
route('users.show')          // Get by name
current_route()              // Current route
previous_route()             // Previous route

// Check routes
route_is('users.show')       // Check name
route_name()                 // Get name

// Router access
router()                     // Get router
dispatch_route('/users')     // Dispatch route
```

## See Also

- [Named Routes](07_NAMED_ROUTES.md) - Route naming
- [URL Generation](12_URL_GENERATION.md) - URL generation
- [API Reference](../API_REFERENCE.md) - Complete API reference

---

© 2024 CloudCastle HTTP Router  
[⬆ Back to top](#helper-functions)