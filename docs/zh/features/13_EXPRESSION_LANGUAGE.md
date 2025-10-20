# 表达式语言

[English](../../en/features/13_EXPRESSION_LANGUAGE.md) | [Русский](../../ru/features/13_EXPRESSION_LANGUAGE.md) | [Deutsch](../../de/features/13_EXPRESSION_LANGUAGE.md) | [Français](../../fr/features/13_EXPRESSION_LANGUAGE.md) | [**中文**](13_EXPRESSION_LANGUAGE.md)

---

## 📚 文档导航

[README](../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [API_REFERENCE](../API_REFERENCE.md) | [ALL_FEATURES](../ALL_FEATURES.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [PERFORMANCE](../PERFORMANCE_ANALYSIS.md) | [SECURITY](../SECURITY_REPORT.md) | [COMPARISON](../COMPARISON.md) | [FAQ](../FAQ.md)

**详细文档:** [01](01_BASIC_ROUTING.md) | [02](02_ROUTE_PARAMETERS.md) | [03](03_ROUTE_GROUPS.md) | [04](04_RATE_LIMITING.md) | [05](05_IP_FILTERING.md) | [06](06_MIDDLEWARE.md) | [07](07_NAMED_ROUTES.md) | [08](08_TAGS.md) | [09](09_HELPER_FUNCTIONS.md) | [10](10_ROUTE_SHORTCUTS.md) | [11](11_ROUTE_MACROS.md) | [12](12_URL_GENERATION.md) | [13](13_EXPRESSION_LANGUAGE.md) | [14](14_CACHING.md) | [15](15_PLUGINS.md) | [16](16_LOADERS.md) | [17](17_PSR_SUPPORT.md) | [18](18_ACTION_RESOLVER.md) | [19](19_STATISTICS.md) | [20](20_SECURITY.md) | [21](21_EXCEPTIONS.md) | [22](22_CLI_TOOLS.md)

---

**类别:** 高级功能  
**操作符数量:** 5  
**复杂度:** ⭐⭐⭐ 高级

---

## 描述

表达式语言允许基于计算表达式（IP、时间、头部等）创建路由条件。

## 使用

```php
// 按IP
Route::get('/admin', $action)
    ->condition('request.ip == "192.168.1.1"');

// 按时间
Route::get('/api/data', $action)
    ->condition('request.time > 9 and request.time < 18');

// 按头部
Route::get('/api/secure', $action)
    ->condition('request.header["X-API-Key"] == "secret"');
```

## 操作符

- 比较: `==`, `!=`, `>`, `<`, `>=`, `<=`
- 逻辑: `and`, `or`, `not`
- 字符串: `matches`, `contains`, `starts_with`, `ends_with`

## 另请参阅

- [路由参数](02_ROUTE_PARAMETERS.md) - 参数验证
- [IP过滤](05_IP_FILTERING.md) - 基于IP的访问控制
- [API参考](../API_REFERENCE.md) - 完整API参考

---

© 2024 CloudCastle HTTP Router  
[⬆ 返回顶部](#表达式语言)