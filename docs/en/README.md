[🇷🇺 Русский](ru/README.md) | [🇺🇸 English](en/README.md) | [🇩🇪 Deutsch](de/README.md) | [🇫🇷 Français](fr/README.md) | [🇨🇳 中文](zh/README.md)

[📚 Table of Contents](en/_table-of-contents.md) | [🏠 Home](en/README.md)

---

# CloudCastle HTTP Router Documentation

**Languages:** 🇷🇺 Russian | [🇬🇧 English](../en/README.md) | [🇩🇪 Deutsch](../de/README.md) | [🇫🇷 Français](../fr/README.md) | [🇨🇳中文](../zh/README.md)

[📚 Table of Contents](_table-of-contents.md) | [🏠 Home](README.md)

---

Welcome to the documentation for CloudCastle HTTP Router - a modern, fast and secure router for PHP 8.2+.

## 📚 Contents

### Getting started

- [Main page](../../README.md) - quick start and basic information
- [Getting Started](getting-started.md) - guide for beginners
- [Best Practices](best-practices.md) - best development practices

### Testing

- [Summary of all tests](test-summary.md) - results of all tests and benchmarks
- [Unit tests](unit-tests.md) - detailed results of 419 tests
- [Security tests](security-tests.md) - analysis of 13 security checks
- [Performance tests](performance-tests.md) - performance benchmarks
- [Load tests](load-tests.md) - load testing (50K+ req/sec)
- [Stress tests](stress-tests.md) - extreme conditions (1M+ routes)

### Possibilities

- [All features](features.md) - complete list of 30+ features
- [Auto-Naming](auto-naming.md) - automatic naming of routes (a unique feature!)
- [Route Shortcuts](shortcuts.md) - 13+ shortcuts for quick setup
- [Route Macros](macros.md) - 7+ macros (code reduction by 80-97%)
- [Helper Functions](helpers.md) - 15+ global functions
- [ThrottleWithBan](throttle-with-ban.md) - rate limiting + auto-ban (unique feature!)
- [Tags System](tags.md) - tag system for filtering routes
- [Route Loaders](loaders.md) - YAML/XML/JSON/Attributes configuration
- [Middleware](middleware.md) - middleware and PSR-15 system
- [Facade](facade.md) - static use (Laravel-style)
- [Code Quality](code-quality.md) - PHPStan, PHPMD, PHPCS reports

### Comparison

- [Detailed comparison with competitors](comparison-detailed.md) - full analysis of 6 routers

## 🎯 About the project

CloudCastle HTTP Router is a high-performance router with a unique set of security features and configuration flexibility.

### Key indicators

- **Performance**: 50,946 requests/sec (average)
- **Scalability**: 1,095,000+ routes
- **Safety**: 13 safety mechanisms
- **Tests**: 447 tests, 1043+ assertions
- **Coverage**: 100% success rate

## 📊 Test results

### Performance

| Category | Result | Status |
|:---|:---:|:---:|
| Light Load | 52,488 req/sec | ✅ |
| Medium Load | 45,260 req/sec | ✅ |
| Heavy Load | 55,089 req/sec | ✅ |
| Concurrent Access | 8,316 req/sec | ✅ |

### Scalability

| Parameter | Meaning |
|:---|:---:|
| Maximum routes | 1,095,000 |
| Memory/route | 1.39 KB |
| Nesting depth | 50 levels |
| URI length | 1,980 characters |

### Safety

✅ All 13 security tests passed successfully:
- Path Traversal Protection
- SQL Injection Prevention
- XSS Protection
- IP Whitelist/Blacklist
- IP Spoofing Protection
- Domain Security
- ReDoS Protection
- Method Override Protection
- Mass Assignment Protection
- Cache Injection Prevention
- Resource Exhaustion Prevention
- Unicode Security

## 🆚 Comparison with competitors

### Performance (requests/sec)

1. **CloudCastle** - 50,946 🥇
2. FastRoute - 47,033 🥈
3. AltoRouter - 39,967 🥉
4. Slim - 37,167
5. Laravel - 16,233
6. Symfony - 15,633

### Functionality (number of features)

1. **CloudCastle** - 25/25 (100%) 🥇
2. Symfony - 10/25 (40%) 🥈
3. Laravel - 9/25 (36%) 🥉
4. Slim - 7/25 (28%)
5. AltoRouter - 4/25 (16%)
6. FastRoute - 3/25 (12%)

### Scalability (maximum routes)

1. **CloudCastle** - 1,095,000 🥇
2. FastRoute - 500,000 🥈
3. Slim - 200,000 🥉
4. AltoRouter - 150,000
5. Symfony - 100,000
6. Laravel - 80,000

## 🚀 Quick start

### Installation

```bash
composer require cloud-castle/http-router
```

### Basic use

```php
use CloudCastle\Http\Router\Router;

$router = new Router();

$router->get('/', function() {
    return 'Hello, World!';
});

$router->get('/users/{id}', function($id) {
    return "User: {$id}";
});

$result = $router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
```

###Advanced features

```php
// Middleware
$router->get('/admin', 'AdminController@index')
    ->middleware(['auth', 'admin']);

// Rate Limiting
$router->get('/api/data', 'ApiController@data')
    ->perMinute(60);

// Conditions
$router->get('/premium', 'PremiumController@index')
    ->condition('user.subscription == "premium"');

// Groups
$router->group(['prefix' => '/api/v1'], function($router) {
    $router->get('/users', 'UserController@index');
    $router->get('/posts', 'PostController@index');
});
```

## 💡 Recommendations

### When to use CloudCastle

✅ **Ideal for:**
- High-load API services
- Microservice architecture
- Projects with security requirements
- Enterprise applications
- Multi-tenant systems

✅ **Advantages:**
- Maximum performance
- Better scalability
- Comprehensive security
- Rich functionality
- Modern code (PHP 8.2+)

### Best Practices

1. **Use caching** in production
2. **Group routes** by functionality
3. **Use named routes** for URL generation
4. **Use rate limiting** for public APIs
5. **Customize YAML/XML/JSON** for large configurations

## 📖 Additional resources

### Documentation

- [Test summary](test-summary.md) - detailed results of all tests
- [Comparison of routers](comparison-detailed.md) - complete analysis of alternatives

### Examples

Examples of use are in the `examples/` directory:
- `basic-usage.php` - basic routing
- `yaml-routes.yaml` - YAML configuration
- `xml-routes.xml` - XML ​​configuration
- `json-routes.json` - JSON configuration ⭐
- `attributes-usage.php` - PHP 8 Attributes
- `middleware-advanced.php` - advanced middleware
- `expression-usage.php` - Expression Language

### Reports

Test results in the `reports/` directory:
- `phpunit.txt` - PHPUnit results
- `security-tests.txt` - security tests
- `performance-tests.txt` - benchmarks
- `load-tests.txt` - load tests
- `stress-tests.txt` - stress tests
- `phpstan.txt` - static analysis
- `phpcs.txt` - code style
- `phpmd.txt` - code quality

## 🤝 Support

- **Issues**: [GitHub Issues](https://github.com/zorinalexey/cloud-casstle-http-router/issues)
- **Email**: zorinalexey59292@gmail.com
- **Telegram**: [@CloudCastle85](https://t.me/CloudCastle85)

## 📄 License

MIT License - see [LICENSE](../../LICENSE) file.

---

**CloudCastle HTTP Router** - Maximum performance. Complete security. Richest functionality.

*Last update: October 18, 2025*

---

[📚 Table of Contents](_table-of-contents.md) | [🏠 Home](README.md)

---

[📚 Table of Contents](en/_table-of-contents.md) | [🏠 Home](en/README.md)
