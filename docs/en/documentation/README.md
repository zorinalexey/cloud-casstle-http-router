# CloudCastle HTTP Router - Documentation

**Version**: 1.1.1  
**Language**: English

---

## 🌍 Translations

- [Русский](../../ru/documentation/README.md) (Full documentation)
- **[English](README.md)** (current)
- [Deutsch](../../de/documentation/README.md)
- [Français](../../fr/documentation/README.md)

---

## 📚 Documentation

**Note**: Full detailed documentation is currently available in Russian. English translation is in progress.

For complete documentation, please refer to:
- **[Russian Documentation](../../ru/documentation/README.md)** (Complete)

---

## 🚀 Quick Start

```php
<?php

use CloudCastle\Http\Router\Facade\Route;

// Simple route
Route::get('/users', fn() => 'User list');

// With parameters
Route::get('/user/{id}', fn($id) => "User: $id");

// With rate limiting and auto-ban
Route::post('/login', 'AuthController@login')
    ->throttleWithBan(
        maxAttempts: 5,
        decaySeconds: 60,
        maxViolations: 3,
        banDurationSeconds: 7200
    );

// Dispatch
$result = Route::dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
```

---

## ✨ Key Features

- ✅ All HTTP methods (GET, POST, PUT, PATCH, DELETE, OPTIONS, HEAD)
- ✅ Dynamic parameters with constraints
- ✅ Route groups with shared attributes
- ✅ Named and tagged routes
- ✅ **Automatic route naming** 🆕
- ✅ Regular expressions
- ✅ Route caching
- 🛡️ **Auto-ban** - protection from brute-force and DDoS
- ⏱️ **Flexible time windows** - from seconds to months
- 🔒 IP filtering (white/black lists)
- 🚀 **60,000+ req/s** performance
- 📊 **740,000+ routes** supported

---

## 📊 Test Results

### Unit Tests
- **263 tests** - all passed ✅
- **611 assertions**
- **Coverage**: ~95%

### Performance
- **Light Load**: 60,095 req/s
- **Heavy Load**: 59,599 req/s
- **Memory**: 1.47 KB per route

### Static Analysis
- **PHPStan**: Level MAX - 0 errors ✅
- **PHPCS**: PSR-12 - 0 errors ✅

---

## 📖 Available Documentation

### Russian (Complete)
- [Introduction](../../ru/documentation/introduction.md)
- [Quick Start](../../ru/documentation/quickstart.md)
- [Routes](../../ru/documentation/routes.md)
- [Auto-naming](../../ru/documentation/auto-naming.md) 🆕
- [Route Groups](../../ru/documentation/route-groups.md)
- [Middleware](../../ru/documentation/middleware.md)
- [Rate Limiting](../../ru/documentation/rate-limiting.md)
- [Auto-ban](../../ru/documentation/auto-ban.md)
- [Security](../../ru/documentation/security.md)
- [Performance](../../ru/documentation/performance.md)
- [API Reference](../../ru/documentation/api-reference.md)

### Reports
- [📊 Test Report](../../ru/reports/tests.md)
- [⚡ Performance](../../ru/reports/performance.md)
- [🔒 Security](../../ru/reports/security.md)
- [📈 Static Analysis](../../ru/reports/static-analysis.md)
- [⚖️ Comparison](../../ru/reports/comparison.md)

---

## 📦 Installation

```bash
composer require cloudcastle/http-router
```

**Requirements**:
- PHP 8.2, 8.3 or 8.4
- Composer 2.x

---

## 🆚 Comparison with Alternatives

| Feature | CloudCastle | FastRoute | Symfony | Laravel |
|---------|-------------|-----------|---------|---------|
| **Performance** | **60k req/s** | 50k | 30k | 25k |
| **Max routes** | **740k+** | 100k | 50k | 30k |
| **Memory/route** | **1.47 KB** | 2.5 KB | 3.8 KB | 4.2 KB |
| **Rate Limiting** | ✅ Built-in | ❌ | ❌ | ✅ Package |
| **Auto-ban** | ✅ | ❌ | ❌ | ❌ |
| **Auto-naming** | ✅ | ❌ | ❌ | ❌ |

---

## 🔗 Links

- **GitHub**: https://github.com/zorinalexey/cloud-casstle-http-router
- **Packagist**: https://packagist.org/packages/cloudcastle/http-router
- **Support Chat**: [Telegram](https://t.me/cloud_castle_news)
- **Email**: zorinalexey59292@gmail.com

---

**CloudCastle HTTP Router** - Performance. Security. Simplicity.

**Language**: English | [Русский](../../ru/documentation/README.md) | [Deutsch](../../de/documentation/README.md) | [Français](../../fr/documentation/README.md)

