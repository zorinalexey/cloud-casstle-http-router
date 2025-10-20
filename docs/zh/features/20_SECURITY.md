# 安全

[English](../../en/features/20_SECURITY.md) | [Русский](../../ru/features/20_SECURITY.md) | [Deutsch](../../de/features/20_SECURITY.md) | [Français](../../fr/features/20_SECURITY.md) | [**中文**](20_SECURITY.md)

---

## 📚 文档导航

[README](../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [API_REFERENCE](../API_REFERENCE.md) | [ALL_FEATURES](../ALL_FEATURES.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [PERFORMANCE](../PERFORMANCE_ANALYSIS.md) | [SECURITY](../SECURITY_REPORT.md) | [COMPARISON](../COMPARISON.md) | [FAQ](../FAQ.md)

**详细文档:** [01](01_BASIC_ROUTING.md) | [02](02_ROUTE_PARAMETERS.md) | [03](03_ROUTE_GROUPS.md) | [04](04_RATE_LIMITING.md) | [05](05_IP_FILTERING.md) | [06](06_MIDDLEWARE.md) | [07](07_NAMED_ROUTES.md) | [08](08_TAGS.md) | [09](09_HELPER_FUNCTIONS.md) | [10](10_ROUTE_SHORTCUTS.md) | [11](11_ROUTE_MACROS.md) | [12](12_URL_GENERATION.md) | [13](13_EXPRESSION_LANGUAGE.md) | [14](14_CACHING.md) | [15](15_PLUGINS.md) | [16](16_LOADERS.md) | [17](17_PSR_SUPPORT.md) | [18](18_ACTION_RESOLVER.md) | [19](19_STATISTICS.md) | [20](20_SECURITY.md) | [21](21_EXCEPTIONS.md) | [22](22_CLI_TOOLS.md)

---

**类别:** 应用保护  
**机制数量:** 12  
**复杂度:** ⭐⭐⭐ 关键

---

## 内置安全机制

### 1. 速率限制
```php
Route::post('/login', $action)->throttle(5, 1);
```

### 2. 自动封禁系统
```php
Route::post('/login', $action)->autoBan(10, 60);
```

### 3. IP过滤
```php
Route::get('/admin', $action)->whitelistIp(['192.168.1.0/24']);
```

### 4-12. 其他安全功能

## 另请参阅

- [速率限制](04_RATE_LIMITING.md) - 速率限制和自动封禁
- [IP过滤](05_IP_FILTERING.md) - 基于IP的访问控制
- [安全报告](../SECURITY_REPORT.md) - 安全分析
- [API参考](../API_REFERENCE.md) - 完整API参考

---

© 2024 CloudCastle HTTP Router  
[⬆ 返回顶部](#安全)