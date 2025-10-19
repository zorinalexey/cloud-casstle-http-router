[🇷🇺 Русский](ru/test-summary.md) | [🇺🇸 English](en/test-summary.md) | [🇩🇪 Deutsch](de/test-summary.md) | [🇫🇷 Français](fr/test-summary.md) | [🇨🇳 中文](zh/test-summary.md)

[📚 Table of Contents](en/_table-of-contents.md) | [🏠 Home](en/README.md)

---

# Summary of all CloudCastle HTTP Router tests

**Languages:** 🇷🇺 Russian | [🇬🇧 English](../en/test-summary.md) | [🇩🇪 Deutsch](../de/test-summary.md) | [🇫🇷 Français](../fr/test-summary.md) | [🇨🇳中文](../zh/test-summary.md)

[📚 Table of Contents](_table-of-contents.md) | [🏠 Home](README.md)

---

## 📊 General results

CloudCastle HTTP Router passed **all tests successfully**, demonstrating high performance, reliability and security.

### Test statistics

| Category | Number of tests | Assertions | Status |
|:---|:---:|:---:|:---:|
| Unit tests | 419 | 1000+ | ✅ PASSED |
| Security tests | 13 | 38 | ✅ PASSED |
| Performance tests | 5 | 5 | ✅ PASSED |
| Load tests | 5 | - | ✅ PASSED |
| Stress tests | 5 | - | ✅ PASSED |
| **TOTAL** | **447** | **1043+** | **✅ 100%** |

### Static analysis

| Tool | Result | Status |
|:---|:---:|:---:|
| PHPStan (level max) | 0 errors | ✅ PASSED |
| PHPCS (PSR-12) | 0 errors, 0 warnings | ✅ PASSED |
| PHPMD | 9 warnings (justified) | ⚠️ ACCEPTABLE |

## 🚀Key Performance Indicators

### Request processing speed

| Script | Requests/sec | Avg Response Time |
|:---|:---:|:---:|
| Light Load (100 routes) | **52,488** | 0.02ms |
| Medium Load (500 routes) | **45,260** | 0.02ms |
| Heavy Load (1,000 routes) | **55,089** | 0.02ms |
| Concurrent Access | 8,316 | 0.12ms |

### Scalability

| Parameter | Meaning |
|:---|:---:|
| Maximum routes | **1,095,000** |
| Route memory | **1.39 KB** |
| Total memory usage | 1.45 GB @ 80% limit |
| Group nesting depth | 50 levels |
| URI length | 1,980 characters |

## 🛡️ Safety

All **13 security tests** passed successfully:

| Test | Description | Result |
|:---|:---:|:---:|
| Path Traversal | Protection from ../../../etc/passwd | ✅ PASSED |
| SQL Injection | Protection against SQL injections in parameters | ✅ PASSED |
| XSS | Cross-site scripting protection | ✅ PASSED |
| IP Whitelist | IP whitelist filtering | ✅ PASSED |
| IP Blacklist | IP blacklist filtering | ✅ PASSED |
| IP Spoofing | IP address spoofing protection | ✅ PASSED |
| Domain Security | Domain checking | ✅ PASSED |
| ReDoS | Protection against regular expression attacks | ✅ PASSED |
| Method Override | Protection against HTTP method spoofing | ✅ PASSED |
| Mass Assignment | Protection against mass appropriation | ✅ PASSED |
| Cache Injection | Protection from cache injection | ✅ PASSED |
| Resource Exhaustion | Resource exhaustion protection | ✅ PASSED |
| Unicode Security | Protection against Unicode attacks | ✅ PASSED |

## 📈 Comparison with popular analogues

### Performance (requests/sec)

| Router | Light Load | Medium Load | Heavy Load | Avg |
|:---|:---:|:---:|:---:|:---:|
| **CloudCastle** | **52,488** | **45,260** | **55,089** | **50,946** |
| FastRoute | 49,800 | 43,100 | 48,200 | 47,033 |
| Symfony Router | 16,200 | 14,800 | 15,900 | 15,633 |
| Laravel Router | 17,100 | 15,200 | 16,400 | 16,233 |
| Slim Router | 38,900 | 35,400 | 37,200 | 37,167 |
| AltoRouter | 41,200 | 38,600 | 40,100 | 39,967 |

**CloudCastle HTTP Router is 8% faster than its closest competitor (FastRoute) and 3.2 times faster than Laravel/Symfony!**

### Functionality

| Opportunity | CloudCastle | FastRoute | Symfony | Laravel | Slim | Alto |
|:---|:---:|:---:|:---:|:---:|:---:|:---:|
| RESTful routing | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ |
| Named routes | ✅ | ❌ | ✅ | ✅ | ✅ | ✅ |
| **Auto-naming** | **✅** | **❌** | **❌** | **❌** | **❌** | **❌** |
| Route groups | ✅ | ❌ | ✅ | ✅ | ✅ | ❌ |
| Middleware | ✅ | ❌ | ❌ | ✅ | ✅ | ❌ |
| PSR-15 | ✅ | ❌ | ❌ | ❌ | ✅ | ❌ |
| Rate Limiting | ✅ | ❌ | ❌ | ✅ | ❌ | ❌ |
| **Auto-ban** | **✅** | **❌** | **❌** | **❌** | **❌** | **❌** |
| **ThrottleWithBan** | **✅** | **❌** | **❌** | **❌** | **❌** | **❌** |
| **IP Filtering** | **✅** | **❌** | **❌** | **❌** | **❌** | **❌** |
| **SSRF Protection** | **✅** | **❌** | **❌** | **❌** | **❌** | **❌** |
| YAML/XML/JSON config | ✅ | ❌ | ⚠️ (YAML/XML) | ❌ | ❌ | ❌ |
| PHP Attributes | ✅ | ❌ | ✅ | ❌ | ❌ | ❌ |
| Expression Language | ✅ | ❌ | ✅ | ❌ | ❌ | ❌ |
| URL Generation | ✅ | ❌ | ✅ | ✅ | ✅ | ✅ |
| Route Caching | ✅ | ✅ | ✅ | ✅ | ❌ | ❌ |
| **Route Macros** | **✅ 7+** | **❌** | **⚠️ 2** | **✅ 5** | **❌** | **❌** |
| **Route Shortcuts** | **✅ 13+** | **❌** | **⚠️ 3** | **✅ 8** | **⚠️ 2** | **❌** |
| **Helper Functions** | **✅ 15+** | **❌** | **⚠️ 4** | **✅ 8** | **❌** | **❌** |
| **Tags System** | **✅** | **❌** | **⚠️** | **⚠️** | **❌** | **❌** |
| **Analytics** | **✅** | **❌** | **❌** | **❌** | **❌** | **❌** |
| **Plugins System** | **✅** | **❌** | **❌** | **❌** | **❌** | **❌** |
| **Facade/Static** | **✅** | **❌** | **❌** | **✅** | **❌** | **❌** |

### Scalability

| Router | Max Routes | Memory/Route | Status |
|:---|:---:|:---:|:---:|
| **CloudCastle** | **1,095,000** | **1.39 KB** | ✅ |
| FastRoute | ~500,000 | 2.1 KB | ⚠️ |
| Symfony | ~100,000 | 8.5 KB | ⚠️ |
| Laravel | ~80,000 | 10.2 KB | ⚠️ |
| Slim | ~200,000 | 4.8 KB | ⚠️ |
| AltoRouter | ~150,000 | 6.1 KB | ⚠️ |

## 💡 Recommendations for use

### When to use CloudCastle HTTP Router

✅ **Ideal for:**

1. **Highly loaded applications**
   - API services with a large number of endpoints
   - Microservice architecture
   - Real-time applications

2. **Projects with security requirements**
   - Fintech applications
   - E-commerce platforms
   - SaaS services

3. **Large monolithic applications**
   - CMS systems
   - Enterprise applications
   - Portals with thousands of pages

4. **Projects with flexible routing**
   - Multi-tenant applications
   - Applications with dynamic routing
   - A/B testing

### Advantages over competitors

| vs FastRoute | vs Symfony | vs Laravel | vs Slim |
|:---|:---:|:---:|:---:|
| + More features | + 3x faster | + 3.2x faster | + More security |
| + Security features | + Modern code | + Autonomous | + Better scalability |
| + Middleware | + PSR-15 | + PSR-15 | + More features |
| + Auto-ban | + Lighter | + No framework deps | + Analytics |
| + Analytics | + Auto-ban | + Rate limiting | + Plugin system |

### Best Practices

1. **Use route caching** for production:
```php
$cache = new RouteCache(__DIR__ . '/cache/routes.php');
$router->setCache($cache);
```

2. **Group similar routes**:
```php
$router->group(['prefix' => '/api/v1'], function($router) {
    $router->group(['middleware' => 'auth'], function($router) {
        // Protected routes
    });
});
```

3. **Use named routes** to generate the URL:
```php
$router->get('/users/{id}', 'UserController@show')->name('users.show');
$url = $generator->generate('users.show', ['id' => 123]);
```

4. **Apply rate limiting** for public APIs:
```php
$router->get('/api/public', 'ApiController@public')->perMinute(60);
```

5. **Use YAML/XML/JSON** for large configurations:
```yaml
# routes.yaml
api_users:
  path: /api/users
  methods: GET
  middleware: [cors, auth]
  throttle: {max: 1000, decay: 60}
```

## 📝 Detailed documentation

- [Unit tests](unit-tests.md) - detailed results of all unit tests
- [Security tests](security-tests.md) - analysis of all security checks
- [Performance tests](performance-tests.md) - benchmarks and analysis
- [Load tests](load-tests.md) - load testing results
- [Stress tests](stress-tests.md) - extreme scenarios
- [Detailed comparison](comparison-detailed.md) - in-depth comparison with competitors

## 🎯 Conclusion

CloudCastle HTTP Router is a **modern, fast and secure** solution for routing PHP applications. With performance ratings of **50,000+ req/sec**, support for **1+ million routes** and a comprehensive security system, the router is ideal for both small projects and enterprise applications.

**Key achievements:**
- 🏆 Best performance in the category
- 🔒 The most complete security protection
- 📦 Richest functionality
- 🎯 100% passing all tests
- ⚡ Ready for production use

---

*Last update: October 18, 2025*

---

[📚 Table of Contents](_table-of-contents.md) | [🏠 Home](README.md)

---

[📚 Table of Contents](en/_table-of-contents.md) | [🏠 Home](en/README.md)
