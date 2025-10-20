# FAQ - Frequently Asked Questions

**English** | [Русский](../ru/FAQ.md)

---

## Table of Contents

- [General Questions](#general-questions)
- [Installation and Setup](#installation-and-setup)
- [Performance](#performance)
- [Security](#security)
- [Troubleshooting](#troubleshooting)

---

## General Questions

### What is CloudCastle HTTP Router?

CloudCastle HTTP Router is a high-performance HTTP routing library for PHP 8.2+, providing rich functionality out of the box including Rate Limiting, IP filtering, Auto-Ban system and much more.

### How is it different from Laravel/Symfony Router?

**Advantages:**
- Standalone (no framework required)
- Higher performance (54k+ req/sec)
- Built-in Rate Limiting and IP filtering
- Auto-Ban system out of the box
- Lower memory usage

**When to use:**
- Standalone projects
- API servers
- Microservices
- Projects where performance matters

### What is the minimum PHP version?

PHP 8.2 or higher.

### Can it be used with frameworks?

Yes, the library is standalone and can be used with any framework or without one.

### Is there PSR support?

Yes, supports PSR-7 (HTTP Message) and PSR-15 (HTTP Server Handler).

---

## Installation and Setup

### How to install?

```bash
composer require cloud-castle/http-router
```

### Are additional dependencies needed?

Minimal:
- psr/http-message
- psr/http-server-handler
- psr/http-server-middleware

All are installed automatically via Composer.

### How to set up route autoloading?

**Option 1 - PHP file:**
```php
require 'routes.php';
```

**Option 2 - JSON Loader:**
```php
use CloudCastle\Http\Router\Loader\JsonLoader;
$loader = new JsonLoader($router);
$loader->load('routes.json');
```

**Option 3 - Attributes:**
```php
use CloudCastle\Http\Router\Loader\AttributeLoader;
$loader = new AttributeLoader($router);
$loader->loadFromDirectory('app/Controllers');
```

### How to enable caching?

```php
$router->enableCache('/path/to/cache');

if (!$router->loadFromCache()) {
    // Register routes
    include 'routes.php';
    $router->compile();
}
```

---

## Performance

### How fast is the router?

Without cache: ~10,000 req/sec  
With cache: ~100,000 req/sec (10x improvement)

### Does caching really speed up by 10x?

Yes! Benchmarks show:
- Without cache: ~100μs per dispatch
- With cache: ~10μs per dispatch

### How much memory does it use?

For 1000 routes: ~6 MB  
For 10,000 routes: ~60 MB  
For 100,000 routes: ~600 MB

Very efficient memory usage!

### Is it faster than FastRoute?

FastRoute: ~60,000 req/sec (fastest)  
CloudCastle: ~54,000 req/sec

CloudCastle is slightly slower but offers MUCH more functionality.

---

## Security

### What security features are built-in?

- ✅ Path Traversal protection
- ✅ SQL Injection protection
- ✅ XSS protection
- ✅ IP Spoofing protection
- ✅ ReDoS protection
- ✅ Auto-Ban system
- ✅ Rate Limiting
- ✅ IP Whitelist/Blacklist
- ✅ HTTPS enforcement
- ✅ Domain/Port restrictions

### How does Auto-Ban work?

The system automatically bans IPs that exceed rate limits multiple times:

```php
$banManager = new BanManager();
$banManager->enableAutoBan(5); // Ban after 5 violations

// IP will be banned automatically if it exceeds rate limit 5 times
```

### How to protect admin routes?

```php
Route::group([
    'prefix' => '/admin',
    'middleware' => ['auth', 'admin'],
    'whitelistIp' => ['192.168.1.0/24'], // Office network only
    'https' => true, // HTTPS only
], function() {
    Route::get('/dashboard', $action);
    Route::get('/users', $action);
});
```

### How to prevent brute force attacks?

```php
Route::post('/login', $action)
    ->throttleWithBan(
        maxAttempts: 5,
        decaySeconds: 60,
        maxViolations: 3,
        banDurationSeconds: 3600 // 1 hour ban
    );
```

---

## Troubleshooting

### Route not found error?

Check:
1. Route is registered correctly
2. URI matches exactly (case-sensitive)
3. HTTP method is correct
4. Domain/port/protocol match (if set)

### Too Many Requests error?

Your IP exceeded the rate limit. Solutions:
1. Wait for rate limit to reset
2. Increase rate limit if you control the server
3. Use caching to reduce requests

### IP not allowed error?

Your IP is not in the whitelist or is blacklisted.

Check route IP restrictions:
```php
$route->getWhitelistIps();
$route->getBlacklistIps();
```

### Cache not working?

Verify:
1. Cache directory is writable
2. Cache is enabled: `$router->isCacheEnabled()`
3. Routes are compiled: `$router->compile()`
4. Loading from cache: `$router->loadFromCache()`

### How to debug routes?

```bash
# List all routes
composer routes-list

# Show specific route
php vendor/bin/router show route.name

# Test URI matching
php vendor/bin/router match GET /users/123

# Get statistics
php vendor/bin/router stats
```

---

## See Also

- [User Guide](USER_GUIDE.md) - Complete guide
- [API Reference](API_REFERENCE.md) - Full API
- [All Features](ALL_FEATURES.md) - All 209+ features

---

[⬆ Back to top](#faq---frequently-asked-questions) | [🏠 Home](../../README.md)

---

© 2024 CloudCastle HTTP Router. All Rights Reserved.

