# CloudCastle HTTP Router

[English](**README.md**) | [Русский](../../README.md) | [Deutsch](../de/README.md) | [Français](../fr/README.md) | [中文](../zh/README.md)

---

[![PHP Version](https://img.shields.io/badge/PHP-8.2%2B-blue.svg)](https://php.net)
[![License](https://img.shields.io/badge/license-MIT-green.svg)](../../LICENSE)
[![Tests](https://img.shields.io/badge/tests-501%2F501-success.svg)](../ru/TESTS_DETAILED.md)
[![PHPStan](https://img.shields.io/badge/PHPStan-Level%20Max-brightgreen.svg)](../../reports/phpstan.txt)
[![Performance](https://img.shields.io/badge/performance-54k%20req%2Fsec-brightgreen.svg)](../ru/PERFORMANCE_ANALYSIS.md)
[![Features](https://img.shields.io/badge/features-209%2B-blue.svg)](../../FEATURES_LIST.md)

**Powerful, flexible, and secure HTTP routing library for PHP 8.2+** with a focus on performance, security, and ease of use.

## ⚡ Why CloudCastle HTTP Router?

### 🎯 Key Advantages

- ⚡ **Highest Performance** - **54,891 req/sec**, faster than most competitors
- 🔒 **Comprehensive Security** - 12+ built-in protection mechanisms (OWASP Top 10)
- 💎 **209+ Features** - richest functionality on the market
- 💾 **Minimal Memory Footprint** - only **1.32 KB per route**
- 📊 **Extreme Scalability** - tested on **1,160,000 routes**
- 🔌 **Extensibility** - plugin system, middleware, macros
- 📦 **Full Autonomy** - framework-independent
- ✅ **100% Reliability** - 501 tests, 0 errors, 95%+ coverage

---

## 🚀 Quick Start

### Installation

```bash
composer require cloud-castle/http-router
```

### Basic Example

```php
<?php

use CloudCastle\Http\Router\Facade\Route;

// Simple routes
Route::get('/users', fn() => 'Users list');
Route::post('/users', fn() => 'Create user');
Route::get('/users/{id}', fn($id) => "User: $id")
    ->where('id', '[0-9]+');

// Dispatch
$route = Route::dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
echo $route->run();
```

### Advanced Example

```php
// Protected API
Route::group(['prefix' => '/api/v1'], function() {
    Route::get('/users', [UserController::class, 'index'])
        ->name('api.users')
        ->throttle(100, 1)  // 100 requests per minute
        ->middleware([AuthMiddleware::class])
        ->tag('api');
    
    Route::post('/users', [UserController::class, 'store'])
        ->throttle(20, 1)
        ->whitelistIp(['192.168.1.0/24'])
        ->middleware([AuthMiddleware::class, AdminMiddleware::class]);
});
```

---

## 💡 Core Features

### 1️⃣ HTTP Methods (7 ways)

```php
Route::get('/users', $action);
Route::post('/users', $action);
Route::put('/users/{id}', $action);
Route::patch('/users/{id}', $action);
Route::delete('/users/{id}', $action);
Route::any('/page', $action);              // Any method
Route::match(['GET', 'POST'], '/form', $action);  // Multiple methods
Route::custom('VIEW', '/preview', $action);       // Custom method
```

### 2️⃣ Smart Parameters

```php
// Basic parameters
Route::get('/users/{id}', $action);

// With validation
Route::get('/users/{id}', $action)->where('id', '[0-9]+');
Route::get('/posts/{slug}', $action)->where('slug', '[a-z0-9-]+');

// Optional
Route::get('/blog/{category?}', $action);

// Default values
Route::get('/posts/{page}', $action)->defaults(['page' => 1]);

// Inline patterns
Route::get('/users/{id:[0-9]+}', $action);
```

### 3️⃣ Route Groups

```php
Route::group([
    'prefix' => '/api/v1',
    'middleware' => [AuthMiddleware::class],
    'domain' => 'api.example.com',
    'port' => 8080,
    'namespace' => 'App\\Controllers\\Api',
], function() {
    Route::get('/users', 'UserController@index');
    Route::get('/posts', 'PostController@index');
});
```

### 4️⃣ Rate Limiting & Auto-Ban

```php
// Rate limiting
Route::post('/api/login', $action)
    ->throttle(5, 1);  // 5 attempts per minute

// With TimeUnit enum
use CloudCastle\Http\Router\TimeUnit;

Route::post('/api/submit', $action)
    ->throttle(100, TimeUnit::HOUR->value);

// Auto-ban system
use CloudCastle\Http\Router\BanManager;

$banManager = new BanManager(
    maxViolations: 5,      // 5 violations
    banDuration: 3600      // 1 hour ban
);

Route::post('/login', $action)
    ->throttle(3, 1)
    ->getRateLimiter()
    ?->setBanManager($banManager);
```

### 5️⃣ IP Filtering

```php
// Whitelist (only allowed IPs)
Route::get('/admin', $action)
    ->whitelistIp(['192.168.1.1', '10.0.0.0/8']);

// Blacklist (blocked IPs)
Route::get('/public', $action)
    ->blacklistIp(['1.2.3.4', '5.6.7.0/24']);

// In group
Route::group(['whitelistIp' => ['192.168.1.0/24']], function() {
    Route::get('/admin/users', $action);
    Route::get('/admin/settings', $action);
});
```

### 6️⃣ Middleware

```php
// Global
Route::middleware([CorsMiddleware::class, LoggerMiddleware::class]);

// On route
Route::get('/admin', $action)
    ->middleware([AuthMiddleware::class, AdminMiddleware::class]);

// Built-in middleware
Route::get('/api/data', $action)->auth();        // AuthMiddleware
Route::get('/api/public', $action)->cors();      // CorsMiddleware
Route::get('/secure', $action)->secure();        // HTTPS enforcement
```

### 7️⃣ Named Routes and URL Generation

```php
// Naming
Route::get('/users/{id}', $action)->name('users.show');

// Auto-naming
Route::enableAutoNaming();

// URL generation
$url = route_url('users.show', ['id' => 5]);  // /users/5

// With domain
use CloudCastle\Http\Router\UrlGenerator;

$generator = new UrlGenerator();
$url = $generator->generate('users.show', ['id' => 5])
    ->toDomain('api.example.com')
    ->toProtocol('https')
    ->absolute();  // https://api.example.com/users/5

// Signed URLs
$signedUrl = $generator->signed('verify.email', ['user' => 123], 3600);
```

### 8️⃣ Route Shortcuts (14 methods)

```php
Route::get('/api/data', $action)->apiEndpoint();  // API + CORS + JSON
Route::get('/admin', $action)->admin();           // Auth + Admin + Whitelist
Route::get('/page', $action)->public();           // Public tag
Route::get('/dashboard', $action)->protected();   // Auth + HTTPS
Route::get('/localhost', $action)->localhost();   // Only localhost

// Throttle shortcuts
Route::post('/api/submit', $action)->throttleStandard();   // 60/min
Route::post('/api/strict', $action)->throttleStrict();     // 10/min
Route::post('/api/bulk', $action)->throttleGenerous();     // 1000/min
```

### 9️⃣ Route Macros (7 macros)

```php
// RESTful resource
Route::resource('/users', UserController::class);
// Creates: index, create, store, show, edit, update, destroy

// API resource (without create/edit)
Route::apiResource('/posts', PostController::class);

// CRUD (simple)
Route::crud('/products', ProductController::class);

// Authentication
Route::auth();
// Creates: login, logout, register, password.request, password.reset

// Admin panel
Route::adminPanel('/admin');

// API versioning
Route::apiVersion('v1', function() {
    Route::get('/users', $action);
});

// Webhooks
Route::webhooks('/webhooks', WebhookController::class);
```

### 🔟 Helper Functions (18 functions)

```php
route('users.show');              // Get route by name
current_route();                  // Current route
previous_route();                 // Previous route
route_is('users.*');              // Check route name
route_name();                     // Current route name
router();                         // Router instance
dispatch_route($uri, $method);    // Dispatch
route_url('users.show', ['id' => 5]);  // Generate URL
route_has('users.show');          // Check existence
route_stats();                    // Route statistics
routes_by_tag('api');             // Routes by tag
route_back();                     // Go back
```

---

## 📊 Performance

### Benchmarks (PHPBench)

| Operation | Time | Performance |
|-----------|------|-------------|
| **Add 1000 routes** | 3.435ms | 0.0034ms/route |
| **Match first route** | 123μs | 8,130 req/sec |
| **Match middle route** | 1.746ms | 573 req/sec |
| **Match last route** | 3.472ms | 288 req/sec |
| **Named lookup** | 3.858ms | 259 req/sec |
| **Route groups** | 2.577ms | 388 req/sec |
| **With middleware** | 2.030ms | 493 req/sec |
| **With parameters** | 73μs | 13,699 req/sec |

### Load Tests

| Scenario | Routes | Requests | Result | Memory |
|----------|--------|----------|--------|--------|
| **Light Load** | 100 | 1,000 | **53,975 req/sec** | 6 MB |
| **Medium Load** | 500 | 5,000 | **54,135 req/sec** | 6 MB |
| **Heavy Load** | 1,000 | 10,000 | **54,891 req/sec** | 6 MB |

### Stress Tests

- ✅ **1,160,000 routes** processed
- ✅ **1.46 GB memory** (1.32 KB/route)
- ✅ **200,000 requests** in 3.8 sec
- ✅ **0 errors** under extreme load

📖 More: [Performance Analysis](../ru/PERFORMANCE_ANALYSIS.md)

---

## 🔒 Security

### Built-in Protection Mechanisms

CloudCastle HTTP Router includes **12+ security layers**:

✅ **Rate Limiting** - DDoS prevention  
✅ **Auto-Ban System** - automatic blocking  
✅ **IP Filtering** - whitelist/blacklist with CIDR  
✅ **HTTPS Enforcement** - force HTTPS usage  
✅ **Path Traversal Protection** - protection against ../../../  
✅ **SQL Injection Protection** - parameter validation  
✅ **XSS Protection** - escaping  
✅ **ReDoS Protection** - regex DoS protection  
✅ **Method Override Protection** - method spoofing protection  
✅ **Cache Injection Protection** - secure caching  
✅ **IP Spoofing Protection** - X-Forwarded-For validation  
✅ **Protocol Restrictions** - HTTP/HTTPS/WS/WSS

### Security Tests

**13/13 OWASP Top 10 tests passed** ✅

```
✓ Path Traversal Protection
✓ SQL Injection Protection
✓ XSS Protection
✓ Rate Limiting (A07:2021)
✓ IP Filtering & Spoofing
✓ Method Override Attacks
✓ Cache Injection
✓ ReDoS Protection
✓ Unicode Security
✓ Resource Exhaustion
✓ HTTPS Enforcement
✓ Domain/Port Restrictions
✓ Auto-Ban System
```

📖 More: [Security Report](../ru/SECURITY_REPORT.md)

---

## 🧩 Advanced Features

### Plugin System

```php
use CloudCastle\Http\Router\Contracts\PluginInterface;

class LoggerPlugin implements PluginInterface {
    public function beforeDispatch(Route $route, string $uri, string $method): void {
        log("Request: $method $uri");
    }
    
    public function afterDispatch(Route $route, mixed $result): mixed {
        log("Response generated");
        return $result;
    }
    
    public function onRouteRegistered(Route $route): void {
        log("Route registered: {$route->getUri()}");
    }
    
    public function onException(Route $route, \Exception $e): void {
        log("Error: " . $e->getMessage());
    }
}

Route::registerPlugin(new LoggerPlugin());
```

### Route Loaders (5 types)

```php
use CloudCastle\Http\Router\Loader\*;

// JSON
$loader = new JsonLoader($router);
$loader->load('routes.json');

// YAML
$loader = new YamlLoader($router);
$loader->load('routes.yaml');

// XML
$loader = new XmlLoader($router);
$loader->load('routes.xml');

// PHP Attributes
$loader = new AttributeLoader($router);
$loader->loadFromDirectory('app/Controllers');

// PHP files
require 'routes/web.php';
require 'routes/api.php';
```

### Expression Language

```php
Route::get('/admin', $action)
    ->condition('request.ip == "192.168.1.1" and request.time > 9');

Route::get('/api/data', $action)
    ->condition('request.header["X-API-Key"] == "secret"');
```

### Route Caching

```php
// Enable cache
$router->enableCache('var/cache/routes');

// Compile
$router->compile();

// Auto-load from cache
if ($router->loadFromCache()) {
    // Cache loaded - instant start
} else {
    // Register routes
    require 'routes/web.php';
    $router->compile();
}

// Clear
$router->clearCache();
```

### PSR Support

```php
// PSR-7
use Psr\Http\Message\ServerRequestInterface;
$request = ServerRequestFactory::fromGlobals();

// PSR-15
use CloudCastle\Http\Router\Psr15\Psr15MiddlewareAdapter;
$psrMiddleware = new Psr15MiddlewareAdapter($router);
```

---

## 📚 Documentation

### Main Documentation

- 📖 [User Guide](USER_GUIDE.md) - Complete guide to all features
- 🔍 [API Reference](API_REFERENCE.md) - Detailed API documentation
- 💡 [Examples](../../examples/) - 20+ ready-to-use examples
- ❓ [FAQ](FAQ.md) - Frequently asked questions
- 🎯 [Features List](../../FEATURES_LIST.md) - All 209+ features

### Reports and Analysis

- 📊 [Test Summary](../ru/SUMMARY.md)
- 🧪 [Detailed Tests](../ru/TESTS_DETAILED.md)
- ⚡ [Performance Analysis](PERFORMANCE_ANALYSIS.md)
- 🔒 [Security Report](SECURITY_REPORT.md)
- ⚖️ [Comparison with Alternatives](COMPARISON.md)

---

## 🧪 Code Quality

### Test Statistics

```
Total tests:      501
Passed:           501 ✅
Failed:           0
Coverage:         ~95%
Assertions:       1,200+
```

### Static Analysis

- **PHPStan:** Level MAX - 0 critical errors ✅
- **PHPMD:** 0 issues ✅
- **PHPCS:** PSR-12 - 0 violations ✅
- **PHP-CS-Fixer:** 0 files need fixes ✅
- **Rector:** 0 changes required ✅

### Running Tests

```bash
# All tests
composer test

# By category
composer test:unit          # Unit tests
composer test:security      # Security tests
composer test:performance   # Performance tests
composer test:load          # Load tests
composer test:stress        # Stress tests

# Static analysis
composer phpstan            # PHPStan
composer phpcs              # PHP_CodeSniffer
composer phpmd              # PHP Mess Detector
composer analyse            # All analyzers

# Benchmarks
composer benchmark          # PHPBench
```

---

## ⚖️ Comparison with Alternatives

| Feature | CloudCastle | Laravel | Symfony | FastRoute | Slim |
|---------|-------------|---------|---------|-----------|------|
| **Performance** | **54k req/sec** | 35k | 40k | 60k | 45k |
| **Memory (1k routes)** | **6 MB** | 12 MB | 10 MB | 4 MB | 5 MB |
| **Features** | **209+** | 150+ | 180+ | 20+ | 50+ |
| **Rate Limiting** | ✅ Built-in | ✅ | ❌ | ❌ | ⚠️ Package |
| **Auto-Ban** | ✅ | ❌ | ❌ | ❌ | ❌ |
| **IP Filtering** | ✅ Built-in | ⚠️ Middleware | ❌ | ❌ | ⚠️ Middleware |
| **Expression Lang** | ✅ | ❌ | ⚠️ Limited | ❌ | ❌ |
| **Plugins** | ✅ 4 built-in | ✅ | ⚠️ Events | ❌ | ❌ |
| **Loaders** | ✅ 5 types | ⚠️ PHP only | ⚠️ XML/YAML | ❌ | ❌ |
| **Macros** | ✅ 7 macros | ✅ | ❌ | ❌ | ❌ |
| **Shortcuts** | ✅ 14 methods | ⚠️ Some | ❌ | ❌ | ❌ |
| **Helpers** | ✅ 18 functions | ✅ 10+ | ⚠️ Few | ❌ | ⚠️ Few |
| **PSR-15** | ✅ | ✅ | ✅ | ❌ | ✅ |
| **Standalone** | ✅ | ❌ Framework | ⚠️ Complex | ✅ | ✅ |
| **Tests** | **501** | 300+ | 500+ | 100+ | 200+ |
| **Coverage** | **95%+** | 90%+ | 95%+ | 80%+ | 85%+ |

### Conclusion

**CloudCastle HTTP Router** - optimal balance between **performance**, **functionality**, and **security**. 

✅ **Best choice for:**
- API servers with high security requirements
- Microservice architecture
- High-load systems (50k+ req/sec)
- Projects requiring maximum routing control

📖 More: [Comparison with Alternatives](COMPARISON.md)

---

## 🤝 Contributing

We welcome contributions to CloudCastle HTTP Router development!

### How to Help

1. ⭐ Star the project
2. 🐛 Report bugs
3. 💡 Suggest new features
4. 📝 Improve documentation
5. 🔧 Submit Pull Requests

### Process

```bash
# 1. Fork the project
git clone https://github.com/YOUR_USERNAME/cloud-casstle-http-router.git

# 2. Create feature branch
git checkout -b feature/AmazingFeature

# 3. Commit changes
git commit -m 'Add some AmazingFeature'

# 4. Push to branch
git push origin feature/AmazingFeature

# 5. Open Pull Request
```

### Requirements

- ✅ Follow PSR-12
- ✅ Write tests (PHPUnit)
- ✅ Update documentation
- ✅ Check PHPStan/PHPCS
- ✅ One PR = one feature

📖 More: [CONTRIBUTING.md](../../CONTRIBUTING.md)

---

## 📄 License

This project is licensed under the **MIT License**. See [LICENSE](../../LICENSE) for details.

```
MIT License

Copyright (c) 2024 CloudCastle

Permission is hereby granted, free of charge, to any person obtaining a copy...
```

---

## 💬 Support

### Contacts

- 📧 **Email:** zorinalexey59292@gmail.com
- 💬 **Telegram:** [@CloudCastle85](https://t.me/CloudCastle85)
- 📢 **Telegram Channel:** [@cloud_castle_news](https://t.me/cloud_castle_news)
- 🐛 **GitHub Issues:** [Report an issue](https://github.com/zorinalexey/cloud-casstle-http-router/issues)
- 💡 **GitHub Discussions:** [Discussions](https://github.com/zorinalexey/cloud-casstle-http-router/discussions)

### Useful Links

- [📚 Documentation](../ru/)
- [💡 Usage Examples](../../examples/)
- [📋 Changelog](../../CHANGELOG.md)
- [🗺️ Roadmap](../../ROADMAP.md)
- [🔒 Security Policy](../../SECURITY.md)
- [📜 Code of Conduct](../../CODE_OF_CONDUCT.md)
- [🤝 Contributors](../../CONTRIBUTORS.md)

---

## 🌟 Acknowledgments

Huge thanks to all [contributors](../../CONTRIBUTORS.md) for their contribution to the project!

### Technologies Used

- [PHPUnit](https://phpunit.de/) - Testing
- [PHPStan](https://phpstan.org/) - Static Analysis
- [PHPCS](https://github.com/squizlabs/PHP_CodeSniffer) - Code Style
- [PHPBench](https://phpbench.readthedocs.io/) - Benchmarks
- [Rector](https://getrector.org/) - Refactoring

---

## 📈 Project Statistics

![GitHub Stars](https://img.shields.io/github/stars/zorinalexey/cloud-casstle-http-router?style=social)
![GitHub Forks](https://img.shields.io/github/forks/zorinalexey/cloud-casstle-http-router?style=social)
![GitHub Watchers](https://img.shields.io/github/watchers/zorinalexey/cloud-casstle-http-router?style=social)

![GitHub Issues](https://img.shields.io/github/issues/zorinalexey/cloud-casstle-http-router)
![GitHub Pull Requests](https://img.shields.io/github/issues-pr/zorinalexey/cloud-casstle-http-router)
![GitHub Last Commit](https://img.shields.io/github/last-commit/zorinalexey/cloud-casstle-http-router)

---

**Made with ❤️ by [CloudCastle](https://github.com/zorinalexey)**

---

[⬆ Back to Top](#cloudcastle-http-router)
