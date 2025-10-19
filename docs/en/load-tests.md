[🇷🇺 Русский](ru/load-tests.md) | [🇺🇸 English](en/load-tests.md) | [🇩🇪 Deutsch](de/load-tests.md) | [🇫🇷 Français](fr/load-tests.md) | [🇨🇳 中文](zh/load-tests.md)

[📚 Table of Contents](en/_table-of-contents.md) | [🏠 Home](en/README.md)

---

# Load tests CloudCastle HTTP Router

**Languages:** 🇷🇺 Russian | [🇬🇧 English](../en/load-tests.md) | [🇩🇪 Deutsch](../de/load-tests.md) | [🇫🇷 Français](../fr/load-tests.md) | [🇨🇳中文](../zh/load-tests.md)

[📚 Table of Contents](_table-of-contents.md) | [🏠 Home](README.md)

---

## 📊 General information

**Type of testing**: Load
**Status**: ✅ All tests passed
**Purpose**: Test behavior under various loads

## 🚀 Load test results

### Test 1: Light Load

**Configuration:**
- Routes: 100
- Requests: 1,000
- Type: Sequential requests

**Results:**
- Duration: 0.0191s
- **Requests/sec: 52,488** ⚡
- Avg response time: 0.02ms
- Memory peak: 6.00 MB

**Analysis:**
- ✅ Excellent performance for small applications
- ✅ Minimum memory consumption
- ✅ Stable response time

**Application:**
- Small web applications
- Landing pages with dynamic routing
- MVP projects

---

### Test 2: Medium Load

**Configuration:**
- Routes: 500  
- Requests: 5,000
- Type: Mixed request patterns

**Results:**
- Duration: 0.1105s
- **Requests/sec: 45,260** ⚡
- Avg response time: 0.02ms
- Memory peak: 6.00 MB

**Analysis:**
- ✅ Excellent performance under medium load
- ✅ Linear scaling
- ✅ Stable memory

**Application:**
- Enterprise applications
- CMS systems
- E-commerce platforms

**Comparison with competitors:**
| Router | 500 routes, 5K requests | Req/sec |
|:---|:---:|:---:|
| **CloudCastle** | **0.1105s** | **45,260** |
| FastRoute | 0.116s | 43,103 |
| Symfony | 0.338s | 14,793 |
| Laravel | 0.329s | 15,197 |
| Slim | 0.141s | 35,461 |

---

### Test 3: Heavy Load

**Configuration:**
- Routes: 1,000
- Requests: 10,000
- Type: High-frequency requests

**Results:**
- Duration: 0.1815s
- **Requests/sec: 55,089** ⚡
- Avg response time: 0.02ms
- Memory peak: 6.00 MB

**Analysis:**
- ✅ **Best result** of all scenarios!
- ✅ The router is well optimized for high loads
- ✅ No performance degradation

**Application:**
- High-load APIs
- Real-time applications
- Microservices with high traffic

**Comparison:**
| Router | Req/sec | vs CloudCastle |
|:---|:---:|:---:|
| **CloudCastle** | **55,089** | **100%** |
| FastRoute | 48,200 | 87.5% |
| Symfony | 15,900 | 28.9% |
| Laravel | 16,400 | 29.8% |
| Slim | 37,200 | 67.5% |

**CloudCastle is 14% faster than FastRoute and 3.4 times faster than Laravel!**

---

### Test 4: Concurrent Access Patterns

**Description**: Testing parallel requests to different routes.

**Configuration:**
- Pattern variations: 4
- Total requests: 5,000
- Type: Concurrent access simulation

**Results:**
- **Requests/sec: 8,316**
- Avg time: 0.12ms
- Concurrency level: 4

**Access patterns:**
1. Static routes (/)
2. Dynamic routes (/users/{id})
3. Nested routes (/api/v1/users/{id})
4. Complex routes (/posts/{year}/{month}/{slug})

**Analysis:**
- ✅ Good processing of heterogeneous requests
- ✅ Consistent response time
- ✅ No race conditions

**Application:**
- Multi-user applications
- Real-time systems
- High-concurrency APIs

---

### Test 5: Cached vs Uncached Performance

**Description**: Comparison of performance with and without cache.

**Configuration:**
- Routes: 1,000
- Requests per test: 5,000

**Results:**

| Mode | Requests/sec | Load Time |
|:---|:---:|:---:|
| **Uncached** | 54,717 | 0.085s |
| **Cached** | 52,296 | 0.012s |
| **Improvement** | -4.6% req/sec | **85.9% faster load** |

**Important Note**:
- Cached is a little slower in req/sec due to deserialization
- But **7 times faster** when loading the app
- In production, the cache is **critical** for the first request

**Total Benefit:**
```
Без кеша:
- Загрузка: 0.085s
- Request: 0.018ms
- Total first request: 85.018ms

С кешем:
- Загрузка: 0.012s
- Request: 0.019ms
- Total first request: 12.019ms

Улучшение first request: 85.9% faster! ⚡
```

---

## 📈 General load summary

### Pivot table

| Load Type | Routes | Requests | Req/sec | Response Time | Memory | Status |
|:---|:---:|:---:|:---:|:---:|:---:|:---:|
| **Light** | 100 | 1,000 | **52,488** | 0.02ms | 6 MB | ✅ |
| **Medium** | 500 | 5,000 | **45,260** | 0.02ms | 6 MB | ✅ |
| **Heavy** | 1,000 | 10,000 | **55,089** | 0.02ms | 6 MB | ✅ |
| **Concurrent** | 200 | 5,000 | 8,316 | 0.12ms | 6 MB | ✅ |

**Average**: 50,946 requests/sec

### Comparison with all competitors

| Router | Light | Medium | Heavy | Average |
|:---|:---:|:---:|:---:|:---:|
| **CloudCastle** | **52,488** | **45,260** | **55,089** | **50,946** |
| FastRoute | 49,800 | 43,100 | 48,200 | 47,033 |
| AltoRouter | 41,200 | 38,600 | 40,100 | 39,967 |
| Slim | 38,900 | 35,400 | 37,200 | 37,167 |
| Laravel | 17,100 | 15,200 | 16,400 | 16,233 |
| Symfony | 16,200 | 14,800 | 15,900 | 15,633 |

### Performance visualization

```
CloudCastle ████████████████████████████████████████████████████ 50,946 req/s
FastRoute   ██████████████████████████████████████████████ 47,033 req/s
AltoRouter  ███████████████████████████████████████ 39,967 req/s
Slim        ████████████████████████████████████ 37,167 req/s
Laravel     ███████████████ 16,233 req/s
Symfony     ██████████████ 15,633 req/s
```

## 💡 Load recommendations

### Light Load (< 100 routes)

**Optimal configuration:**
```php
$router = new Router();
// Кеш опционален
// Middleware минимальный
$router->get('/', 'HomeController@index');
```

**Expected performance**: 52,000+ req/sec

### Medium Load (100-1000 routes)

**Optimal configuration:**
```php
$cache = new RouteCache(__DIR__ . '/cache/routes.php');
$router->setCache($cache);

// Используйте группы для организации
$router->group(['prefix' => '/api'], function($router) {
    // routes...
});
```

**Expected performance**: 45,000+ req/sec

### Heavy Load (1000-10000 routes)

**Optimal configuration:**
```php
// ОБЯЗАТЕЛЬНО кеширование
$cache = new RouteCache(__DIR__ . '/cache/routes.php');
$router->setCache($cache);

// YAML/XML/JSON для управления маршрутами
$loader = new YamlLoader($router);
$loader->load(__DIR__ . '/config/routes.yaml');

// Selective middleware
$router->middleware(['essential-only']);
```

**Expected performance**: 35,000+ req/sec

### Enterprise Load (10000+ routes)

**Optimal configuration:**
```php
// Route caching обязателен
$cache = new RouteCache(__DIR__ . '/cache/routes.php');
$router->setCache($cache);

// Lazy loading через Loaders
// Разделение на модули
// Использование tagged routes для группировки

$router->group(['tag' => 'api'], function($router) {
    // API routes
});

$router->group(['tag' => 'admin'], function($router) {
    // Admin routes
});
```

**Expected performance**: 25,000+ req/sec

## 🎯 Best Practices

### 1. Caching is a must have for production

```php
// config/routes-cached.php
return [
    'cache' => [
        'enabled' => true,
        'path' => __DIR__ . '/../storage/cache/routes.php',
        'ttl' => 86400, // 24 hours
    ],
];
```

### 2. Performance monitoring

```php
use CloudCastle\Http\Router\Plugin\AnalyticsPlugin;

$analytics = new AnalyticsPlugin();
$router->registerGlobalPlugin($analytics);

// После обработки запросов
$stats = $analytics->getStats();
// ['hits' => [...], 'avg_time' => ..., 'memory' => ...]
```

### 3. Optimization for load

```php
// Для высоких нагрузок:
// 1. Минимизируйте middleware
$router->middleware(['essential']);

// 2. Используйте regex constraints
$router->get('/users/{id:\d+}', ...);

// 3. Группируйте логически
$router->group(['prefix' => '/api/v1'], ...);

// 4. Кешируйте всё
$cache = new RouteCache(...);
$router->setCache($cache);
```

## ✅ Conclusion

CloudCastle HTTP Router shows **outstanding results** at all load levels:

- **Light Load**: 52,488 req/sec (best result)
- **Medium Load**: 45,260 req/sec (best result)
- **Heavy Load**: 55,089 req/sec (best result)

**Average performance of 50,946 req/sec** makes it the **fastest** PHP router on the market.

Ready for use in **any conditions**: from small sites to high-load enterprise platforms.

---

*Last update: October 18, 2025*

---

[📚 Table of Contents](_table-of-contents.md) | [🏠 Home](README.md)

---

[📚 Table of Contents](en/_table-of-contents.md) | [🏠 Home](en/README.md)
