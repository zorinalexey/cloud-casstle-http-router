# Introduction

**CloudCastle HTTP Router v1.1.1**  
**Language**: English

---

## 🌍 Translations

- [Русский](../../ru/documentation/introduction.md)
- **[English](introduction.md)** (current)
- [Deutsch](../../de/documentation/introduction.md)
- [Français](../../fr/documentation/introduction.md)

---

## About the Project

**CloudCastle HTTP Router** is a high-performance HTTP router for PHP 8.2+, designed with a focus on performance, security, and ease of use.

### Project Philosophy

We created a router that combines:
- **Speed** - 60,000+ requests per second
- **Scalability** - support for 740,000+ routes
- **Security** - built-in attack protection
- **Convenience** - intuitive API and automation

---

## ✨ Key Features

### Routing
- ✅ All HTTP methods (GET, POST, PUT, PATCH, DELETE, OPTIONS, HEAD)
- ✅ Dynamic parameters with regex constraints
- ✅ Route groups with prefixes
- ✅ Named routes
- ✅ Tagged routes
- ✅ **Automatic route naming** 🆕
- ✅ Nested groups
- ✅ Route caching

### Security
- 🛡️ **Rate Limiting** with flexible time windows
- 🚫 **Auto-ban** when limits exceeded
- 🔒 **IP filtering** (white/black lists)
- 🌐 **Domain restrictions**
- 🔐 **Protocol restrictions** (HTTP/HTTPS/WS/WSS)
- 🛡️ **HTTPS Enforcement** middleware
- 🛡️ **SSRF Protection** middleware
- 📝 **Security Logging** middleware
- ✅ **OWASP Top 10** compliance

### Performance
- 🚀 **60,000+ req/s** on light load
- 📊 **O(1)** route lookup complexity
- 💾 **1.47 KB** memory per route
- ⚡ Compilation and caching
- 🎯 Indexing for fast lookup

### Middleware
- ✅ Global middleware
- ✅ Route middleware
- ✅ Group middleware
- ✅ Middleware priorities
- ✅ Processing chains

---

## 🎯 Who is this router for?

### Perfect for:

✅ **High-load applications** - when performance matters  
✅ **API services** - with built-in rate limiting and protection  
✅ **Microservices** - lightweight and standalone  
✅ **Enterprise projects** - with high quality requirements  
✅ **Projects with many routes** - scalability  

### Excellent choice if you need:

- Maximum performance
- Built-in security
- Scalability
- Standalone router without framework
- High code quality (PHPStan Level MAX)

---

## 📦 Installation

### Requirements

- **PHP**: 8.2, 8.3 or 8.4
- **Composer**: 2.x
- **Extensions**: mbstring, json

### Install via Composer

```bash
composer require cloudcastle/http-router
```

### Verify Installation

```php
<?php

require 'vendor/autoload.php';

use CloudCastle\Http\Router\Facade\Route;

// Create a simple route
Route::get('/', fn() => 'CloudCastle Router is working!');

// Dispatch
echo Route::dispatch('/', 'GET');
```

---

## 🚀 Quick Start

### Example 1: Basic Routing

```php
use CloudCastle\Http\Router\Facade\Route;

// GET request
Route::get('/users', function() {
    return 'User list';
});

// POST request
Route::post('/users', function() {
    return 'Create user';
});

// With parameters
Route::get('/user/{id}', function($id) {
    return "User #$id";
});
```

### Example 2: With Security

```php
// Rate limiting
Route::post('/api/data', 'ApiController@store')
    ->perMinute(60);

// With auto-ban
Route::post('/login', 'AuthController@login')
    ->throttleWithBan(
        maxAttempts: 5,
        decaySeconds: 60,
        maxViolations: 3,
        banDurationSeconds: 7200
    );
```

### Example 3: Route Groups

```php
Route::group(['prefix' => 'api/v1', 'middleware' => 'auth'], function() {
    Route::get('/users', 'UserController@index');
    Route::get('/posts', 'PostController@index');
});
```

---

## 📊 Performance

### Benchmarks

| Load | Req/sec | Memory |
|------|---------|--------|
| Light (100 routes) | 60,095 | 4 MB |
| Medium (500 routes) | 58,905 | 4 MB |
| Heavy (1,000 routes) | 59,599 | 6 MB |
| Extreme (200k req) | 55,609 | 14 MB |

### Comparison with Competitors

```
CloudCastle   ████████████████████ 60,095 req/s
FastRoute     ████████████████░░░░ 50,000 req/s
Symfony       ████████████░░░░░░░░ 30,000 req/s
Laravel       ██████████░░░░░░░░░░ 25,000 req/s
```

[More about performance →](performance.md)

---

## 🛡️ Security

### Built-in Protection

- **Rate Limiting**: From seconds to months
- **Auto-Ban**: Automatic blocking on violations
- **IP Filtering**: White and black lists
- **HTTPS Enforcement**: Force HTTPS usage
- **SSRF Protection**: Protection from SSRF attacks
- **Security Logging**: Event logging

### Security Tests

✅ 13/13 security tests passed  
✅ OWASP Top 10 compliance  
✅ Protection: XSS, SQL Injection, Path Traversal, ReDoS  

[More about security →](security.md)

---

## 📚 Documentation

### Main Topics

1. [Quick Start](quickstart.md) - First steps
2. [Routes](routes.md) - Creation and configuration
3. [Auto-naming](auto-naming.md) - Automatic naming 🆕
4. [Groups](route-groups.md) - Route organization
5. [Middleware](middleware.md) - Request handlers
6. [Rate Limiting](rate-limiting.md) - Request limiting
7. [Auto-ban](auto-ban.md) - Abuse protection
8. [Security](security.md) - Application protection
9. [Performance](performance.md) - Optimization
10. [API Reference](api-reference.md) - Complete reference

---

## 🆚 Why CloudCastle?

### vs FastRoute

| Feature | CloudCastle | FastRoute |
|---------|-------------|-----------|
| Performance | **60k req/s** | 50k req/s |
| Rate Limiting | ✅ | ❌ |
| Middleware | ✅ | ❌ |
| Auto-ban | ✅ | ❌ |
| IP Filtering | ✅ | ❌ |

### vs Symfony Router

| Feature | CloudCastle | Symfony |
|---------|-------------|---------|
| Performance | **60k req/s** | 30k req/s |
| Standalone | ✅ | ❌ |
| Rate Limiting | ✅ | ❌ |
| Memory/route | **1.47 KB** | 3.8 KB |

[Full comparison →](../../reports/comparison.md)

---

## 🤝 Community

### Support

- **GitHub Issues**: https://github.com/zorinalexey/cloud-casstle-http-router/issues
- **Telegram**: https://t.me/cloud_castle_news
- **Email**: zorinalexey59292@gmail.com

---

## 📄 License

MIT License - use freely in commercial and personal projects.

---

**CloudCastle HTTP Router** - Your choice for high-performance routing! 🚀

