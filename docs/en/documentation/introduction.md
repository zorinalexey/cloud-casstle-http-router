# Introduction to HttpRouter

**CloudCastle HttpRouter** is a modern, high-performance routing library for PHP 8.2+ designed with emphasis on security, performance, and developer experience.

## 📊 Statistics

- **308 tests** / **748 assertions** ✅
- **Code Coverage:** >95%
- **PHPStan:** Level Max (3 non-critical warnings)
- **PHPCS:** PSR-12 compliant
- **PHPMD:** No critical issues
- **PHP versions:** 8.2, 8.3, 8.4

## ✨ Key Features

### 🔐 Security
- **Rate Limiting** — DDoS protection with flexible limits
- **Auto-ban system** — automatic blocking of malicious actors
- **Protocol enforcement** — forced HTTPS/WSS usage
- **Path traversal protection** — defense against directory traversal
- **Mass assignment protection** — protection against unwanted parameter assignment
- **OWASP Top 10** — full compliance with security recommendations

### ⚡ Performance
- **Route Caching** — route caching for instant access
- **Lazy Loading** — lazy middleware loading
- **Optimized Matching** — optimized matching algorithm
- **Memory Efficient** — efficient memory usage (30MB for 308 tests)
- **Fast Dispatch** — ~0.001ms per dispatch with cache

### 🎯 Developer Experience
- **Fluent API** — expressive chainable interface
- **Route Groups** — route grouping with shared settings
- **Named Routes** — named routes for convenient URL generation
- **Middleware Support** — full middleware support
- **Tag System** — tag system for route organization
- **Static Facade** — static facade `Route::` for quick access

### 🛠️ Extensibility
- **Custom Methods** — support for custom HTTP methods
- **WebSocket Support** — full WebSocket support (WS/WSS)
- **Middleware Chains** — middleware chains with priorities
- **Event System** — event system for hooks
- **Dependency Injection** — integration with DI containers

## ⚖️ Pros and Cons

### ✅ Advantages

1. **Comprehensive security out of the box**
   - Rate limiting and auto-ban built-in natively
   - Protection against all major attack vectors (OWASP Top 10)
   - Protocol enforcement for secure connections

2. **High performance**
   - Advanced route caching system
   - Optimized matching algorithms
   - Minimal memory consumption

3. **Modern PHP**
   - Full support for PHP 8.2+ features
   - Strict typing
   - Return types and named arguments

4. **Rich functionality**
   - WebSocket support (rare for PHP routers)
   - Tag system for organization
   - Flexible time units (seconds, minutes, hours, days)

5. **Excellent documentation**
   - Detailed usage examples
   - Documentation in 4 languages
   - Detailed testing reports

6. **100% test coverage**
   - 308 unit, integration and functional tests
   - Security tests (OWASP)
   - Performance tests
   - Load & Stress testing

### ⚠️ Limitations

1. **Requires PHP 8.2+**
   - Doesn't work on older PHP versions
   - Requires modern hosting

2. **Young library**
   - Fewer production use cases compared to competitors
   - Fewer community plugins

3. **Complexity for simple tasks**
   - Overkill for simple projects
   - May be excessive for landing pages

4. **Architectural features**
   - Uses static Facade (not everyone likes it)
   - Superglobals access ($_SERVER) — justified for HTTP router
   - High cyclomatic complexity in Router.php — due to rich API

## 🔄 Comparison with Competitors

| Feature | HttpRouter | Symfony | Laravel | FastRoute | Slim |
|---------|-----------|---------|---------|-----------|------|
| **PHP Version** | 8.2+ | 8.1+ | 8.2+ | 7.1+ | 8.1+ |
| **Rate Limiting** | ✅ Built-in | ⚠️ Bundle | ✅ Built-in | ❌ | ❌ |
| **Auto-ban** | ✅ Built-in | ❌ | ❌ | ❌ | ❌ |
| **WebSocket** | ✅ WS/WSS | ❌ | ⚠️ Echo | ❌ | ❌ |
| **Route Caching** | ✅ | ✅ | ✅ | ⚠️ Manual | ❌ |
| **Middleware** | ✅ | ✅ | ✅ | ❌ | ✅ |
| **Named Routes** | ✅ | ✅ | ✅ | ✅ | ✅ |
| **Route Groups** | ✅ | ✅ | ✅ | ✅ | ✅ |
| **Protocol Enforcement** | ✅ HTTPS/WSS | ❌ | ❌ | ❌ | ❌ |
| **Tag System** | ✅ | ❌ | ❌ | ❌ | ❌ |
| **Static Facade** | ✅ Route:: | ❌ | ✅ Route:: | ❌ | ❌ |
| **Performance** | ⚡⚡⚡ | ⚡⚡ | ⚡⚡ | ⚡⚡⚡ | ⚡⚡⚡ |
| **Size** | Small | Large | Large | Tiny | Medium |
| **Dependencies** | Minimal | Many | Many | None | Few |
| **Documentation** | 4 languages | EN | EN | EN | EN |

### 📈 Detailed Comparison

#### vs Symfony Routing
**Advantages:**
- Built-in rate limiting and auto-ban
- WebSocket support
- Protocol enforcement
- Simpler API
- Fewer dependencies

**Disadvantages:**
- Fewer integrations with other components
- Fewer community plugins
- Not part of a large framework

#### vs Laravel Router
**Advantages:**
- Works standalone (without Laravel)
- Built-in auto-ban system
- Native WebSocket support
- Protocol enforcement
- Tag system

**Disadvantages:**
- No Eloquent ORM integration
- No route model binding
- Fewer ecosystem tools

#### vs FastRoute
**Advantages:**
- Rate limiting out of the box
- Auto-ban system
- Middleware support
- Route caching
- Named routes
- Route groups

**Disadvantages:**
- Slightly slower in pure dispatch speed (~1-2%)
- More memory due to additional features

#### vs Slim Router
**Advantages:**
- Richer security functionality
- Auto-ban system
- WebSocket support
- Protocol enforcement
- Tag system

**Disadvantages:**
- Not integrated into a microframework
- Requires separate installation

## 🎯 When to Use HttpRouter

### ✅ Perfect for:

1. **API servers with high security requirements**
   - Built-in rate limiting
   - Auto-ban protection
   - Protocol enforcement

2. **WebSocket applications**
   - Native WS/WSS support
   - Unified routing for HTTP and WebSocket

3. **Microservices**
   - Lightweight and fast
   - Minimal dependencies
   - Excellent performance

4. **Modern PHP projects**
   - PHP 8.2+ features
   - Strict typing
   - Modern best practices

### ⚠️ Not recommended for:

1. **Legacy projects on PHP < 8.2**
2. **Simple static sites** (overkill)
3. **Projects requiring Laravel/Symfony ecosystem integration**

## 📦 Installation

```bash
composer require cloud-castle/http-router
```

## 🚀 Quick Start

```php
<?php

use CloudCastle\Http\Router\Route;

// Simple route
Route::get('/users', fn() => ['users' => User::all()]);

// Route with parameters
Route::get('/users/{id}', function($id) {
    return User::find($id);
});

// Rate limiting
Route::get('/api/data', fn() => getData())
    ->rateLimit(requests: 100, per: '1 minute');

// Route group
Route::group('/api/v1', function() {
    Route::get('/users', 'UserController@index');
    Route::post('/users', 'UserController@store');
})->middleware('auth')->rateLimit(1000, '1 hour');

// WebSocket
Route::websocket('/chat', 'ChatController@handle')
    ->protocol('wss'); // Only secure WebSocket

// Dispatch
$route = Route::dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
echo $route->call();
```

## 📚 Further Reading

- [Quick Start](quickstart.md)
- [Routes](routes.md)
- [Route Groups](route-groups.md)
- [Middleware](middleware.md)
- [Security](security.md)
- [Rate Limiting](rate-limiting.md)
- [Auto-ban](auto-ban.md)
- [Performance](performance.md)
- [API Reference](api-reference.md)

## 📊 Reports

- [Unit Tests](../reports/unit-tests.md)
- [Static Analysis](../reports/static-analysis.md)
- [Performance Benchmarks](../reports/performance.md)
- [Load Testing](../reports/load-testing.md)
- [Security Testing](../reports/security.md)
- [Competitor Comparison](../reports/comparison.md)

## 🤝 Contributing

We welcome contributions to the library! See [CONTRIBUTING.md](../CONTRIBUTING.md)

## 📄 License

MIT License. See [LICENSE](../../LICENSE)

---

**CloudCastle HttpRouter** — the choice for modern PHP applications with high security and performance requirements.
