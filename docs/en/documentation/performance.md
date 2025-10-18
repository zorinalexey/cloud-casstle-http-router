# Performance

**CloudCastle HTTP Router v1.1.1**  
**Language**: English

---

## 🌍 Translations

- [Русский](../../ru/documentation/performance.md)
- **[English](performance.md)** (current)
- [Deutsch](../../de/documentation/performance.md)
- [Français](../../fr/documentation/performance.md)

---

## ⚡ Key Metrics

| Metric | Value |
|--------|-------|
| **Speed** | 60,095 req/s |
| **Max Routes** | 740,000+ |
| **Memory/route** | 1.47 KB |
| **Lookup** | O(1) |
| **Latency** | 0.02 ms |

---

## 🚀 Optimization

### 1. Route Caching

```php
use CloudCastle\Http\Router\Router;

$router = Router::getInstance();
$router->enableCache('cache/routes.php');

// Compile to cache
$router->compile();
```

**Effect**: 50% faster loading for > 100 routes

### 2. Indexing

CloudCastle automatically indexes routes for O(1) lookup

### 3. Regex Compilation

Regex patterns are compiled once during registration

---

## 📊 Benchmarks

### Route Registration

| Quantity | Time | Routes/sec |
|----------|------|------------|
| 1,000 | 0.05s | 20,000 |
| 10,000 | 0.50s | 20,000 |
| 100,000 | 4.35s | 22,988 |

### Route Matching

| Type | Time |
|------|------|
| Exact match | 0.001ms |
| With parameters | 0.002ms |
| Regex pattern | 0.005ms |
| From cache | 0.0005ms |

---

## 🔗 See Also

- [Performance Report](../../reports/performance.md)
- [Load Testing](../../reports/load-testing.md)

---

**[← Back to contents](README.md)**

