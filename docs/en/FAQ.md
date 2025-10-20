# FAQ - Frequently Asked Questions

[**English**](FAQ.md) | [Русский](../ru/FAQ.md) | [Deutsch](../de/FAQ.md) | [Français](../fr/FAQ.md) | [中文](../zh/FAQ.md)

---

**Version:** 1.1.1  
**Date:** October 2025

---

## 📚 Documentation Navigation

### Main Documents
- [README](../../README.md) - Home
- [USER_GUIDE](USER_GUIDE.md) - Complete user guide
- [FEATURES_INDEX](FEATURES_INDEX.md) - Catalogue of all features
- [API_REFERENCE](API_REFERENCE.md) - API reference

### Features
- [Detailed feature docs](features/) - 22 categories
- [ALL_FEATURES](ALL_FEATURES.md) - Full feature list

### Tests and Reports
- [TESTS_SUMMARY](TESTS_SUMMARY.md) - Test summary
- [Detailed test reports](tests/) - 7 reports
- [PERFORMANCE_ANALYSIS](PERFORMANCE_ANALYSIS.md) - Performance analysis
- [SECURITY_REPORT](SECURITY_REPORT.md) - Security report

### Additional
- **[FAQ](FAQ.md) - Frequently Asked Questions** ← You are here
- [COMPARISON](COMPARISON.md) - Comparison with alternatives
- [DOCUMENTATION_SUMMARY](DOCUMENTATION_SUMMARY.md) - Documentation summary

---

## Contents

### General
1. [What is CloudCastle HTTP Router?](#what-is-cloudcastle-http-router)
2. [Why choose CloudCastle over others?](#why-choose-cloudcastle)
3. [What are the requirements?](#requirements)
4. [How to install CloudCastle?](#installation)

### Performance
5. [How fast is CloudCastle?](#performance)
6. [How to improve performance?](#optimization)
7. [What is route caching?](#caching)
8. [How many routes can it handle?](#scalability)

### Security
9. [How secure is CloudCastle?](#security)
10. [What is Rate Limiting?](#rate-limiting)
11. [What is the Auto-Ban system?](#auto-ban)
12. [How to protect the admin panel?](#protecting-admin)

### Usage
13. [How to register routes?](#registering-routes)
14. [What are route groups?](#route-groups)
15. [How to use middleware?](#middleware)
16. [How to build a RESTful API?](#restful-api)

### Advanced
17. [What are Route Macros?](#macros)
18. [How to use plugins?](#plugins)
19. [PSR support?](#psr-support)
20. [Can it be used with frameworks?](#frameworks)

---

## General

### What is CloudCastle HTTP Router?

CloudCastle HTTP Router is a modern routing library for PHP 8.2+ that provides **209+ features** to build secure and high-performance web applications.

Key highlights:
- ⚡ 53,637 req/sec performance
- 🔒 13/13 OWASP Top 10 compliance
- 💎 209+ features
- ✅ 501 tests (100% pass)

---

### Why choose CloudCastle?

CloudCastle is the only router with:

1. Built-in Rate Limiting
```php
Route::post('/api', $action)->throttle(60, 1);
```

2. Auto-Ban System
```php
$banManager = new BanManager(5, 3600);
Route::post('/login', $action)
    ->throttle(3, 1)
    ->getRateLimiter()?->setBanManager($banManager);
```

3. Built-in IP Filtering
```php
Route::get('/admin', $action)->whitelistIp(['192.168.1.0/24']);
```

4. 209+ features — more than competitors.

Comparison:
- Symfony: 180+ features, no built-in rate limiting
- Laravel: 150+ features, framework-only
- FastRoute: ~20 features, pure speed
- Slim: ~50 features, basic functionality

CloudCastle = best balance of speed, security, and functionality.

---

### Requirements

Minimum:
- PHP 8.2+
- Composer
- ~2 MB disk space

Recommended:
- PHP 8.3+
- Opcache enabled
- 128 MB+ memory_limit

Supported PHP versions: 8.2/8.3/8.4

---

### Installation

```bash
composer require cloud-castle/http-router
```

Quick start:
```php
<?php
require 'vendor/autoload.php';
use CloudCastle\Http\Router\Facade\Route;
Route::get('/users', fn() => 'Users list');
$route = Route::dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
echo $route->run();
```

---

## Performance

### How fast is CloudCastle?

Load tests:
- Light (100 routes): 55,923 req/sec
- Medium (500 routes): 54,680 req/sec
- Heavy (1000 routes): 53,637 req/sec

Comparison (1000 routes):
1. FastRoute: 60,000 req/sec
2. CloudCastle: 53,637 req/sec (with 209+ features)
3. Slim: 45,000 req/sec
4. Symfony: 40,000 req/sec
5. Laravel: 35,000 req/sec

---

### Optimization

1) Route caching
```php
$router->enableCache('cache/routes');
if (!$router->loadFromCache()) {
    require 'routes/web.php';
    $router->compile();
}
```

2) Inline parameters
```php
// Faster
Route::get('/users/{id:[0-9]+}', $action);
// Slower
Route::get('/users/{id}', $action)->where('id', '[0-9]+');
```

3) Grouping
```php
Route::group(['prefix' => '/api', 'middleware' => [...]], function() {
    // 100 routes
});
```

---

### Caching

Compiles routes into an optimized format for instant loading.

Without cache: ~10–50 ms init  
With cache: ~0.1–1 ms init  
Speed-up: 10–50x

---

### Scalability

Tested up to 1,095,000 routes; ~1.39 KB/route.

---

## Security

### How secure is CloudCastle?

Built-in protections (13/13 OWASP): Path Traversal, SQL Injection, XSS, IP Filtering, IP Spoofing, ReDoS, Rate Limiting, Auto-Ban, HTTPS, Protocol, Domain/Port, Cache Injection.

---

### Rate Limiting
```php
Route::post('/api/submit', $action)->throttle(60, 1);
// On exceed → TooManyRequestsException (HTTP 429)
```

### Auto-Ban
```php
$banManager = new BanManager(5, 3600);
Route::post('/login', $action)
    ->throttle(3, 1)
    ->getRateLimiter()?->setBanManager($banManager);
```

### Protecting Admin
```php
Route::group([
  'prefix' => '/admin',
  'middleware' => [AuthMiddleware::class, AdminMiddleware::class],
  'https' => true,
  'whitelistIp' => ['192.168.1.0/24'],
  'throttle' => [30, 1]
], function() {
  Route::get('/dashboard', [AdminController::class, 'dashboard']);
});
```

---

## Usage

### Registering Routes
```php
use CloudCastle\Http\Router\Facade\Route;
Route::get('/users', [UserController::class, 'index']);
Route::post('/users', [UserController::class, 'store']);
```

### Route Groups
```php
Route::group([
  'prefix' => '/api/v1',
  'middleware' => [AuthMiddleware::class],
  'throttle' => [100, 1],
  'tags' => 'api'
], function() {
  Route::get('/users', $action);
  Route::get('/posts', $action);
});
```

### Middleware
- Global: `Route::middleware([...])`
- Route: `->middleware([...])`
- Group: `Route::group(['middleware'=>[...]])`

### RESTful API
```php
Route::apiResource('users', ApiUserController::class, 100);
```

---

## Advanced

### Macros
- `resource()`, `apiResource()`, `crud()`, `auth()`, `adminPanel()`, `apiVersion()`, `webhooks()`

### Plugins
Implement `PluginInterface`; built-ins: LoggerPlugin, AnalyticsPlugin, ResponseCachePlugin.

### PSR Support
PSR-1, PSR-4, PSR-7, PSR-12, PSR-15

### Frameworks
Works standalone; integrable with Laravel/Symfony.

---

## 📚 See Also
- [USER_GUIDE.md](USER_GUIDE.md)
- [FEATURES_INDEX.md](FEATURES_INDEX.md)
- [TESTS_SUMMARY.md](TESTS_SUMMARY.md)
- [COMPARISON.md](COMPARISON.md)

---

© 2024 CloudCastle HTTP Router  
[⬆ Back to top](#faq---frequently-asked-questions)
