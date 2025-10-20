# 路由快捷方式

[English](../../en/features/10_ROUTE_SHORTCUTS.md) | [Русский](../../ru/features/10_ROUTE_SHORTCUTS.md) | [Deutsch](../../de/features/10_ROUTE_SHORTCUTS.md) | [Français](../../fr/features/10_ROUTE_SHORTCUTS.md) | [**中文**](10_ROUTE_SHORTCUTS.md)

---

## 📚 文档导航

[README](../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [API_REFERENCE](../API_REFERENCE.md) | [ALL_FEATURES](../ALL_FEATURES.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [PERFORMANCE](../PERFORMANCE_ANALYSIS.md) | [SECURITY](../SECURITY_REPORT.md) | [COMPARISON](../COMPARISON.md) | [FAQ](../FAQ.md)

**详细文档:** [01](01_BASIC_ROUTING.md) | [02](02_ROUTE_PARAMETERS.md) | [03](03_ROUTE_GROUPS.md) | [04](04_RATE_LIMITING.md) | [05](05_IP_FILTERING.md) | [06](06_MIDDLEWARE.md) | [07](07_NAMED_ROUTES.md) | [08](08_TAGS.md) | [09](09_HELPER_FUNCTIONS.md) | [10](10_ROUTE_SHORTCUTS.md) | [11](11_ROUTE_MACROS.md) | [12](12_URL_GENERATION.md) | [13](13_EXPRESSION_LANGUAGE.md) | [14](14_CACHING.md) | [15](15_PLUGINS.md) | [16](16_LOADERS.md) | [17](17_PSR_SUPPORT.md) | [18](18_ACTION_RESOLVER.md) | [19](19_STATISTICS.md) | [20](20_SECURITY.md) | [21](21_EXCEPTIONS.md) | [22](22_CLI_TOOLS.md)

---

**类别:** 易用性  
**方法数量:** 14  
**复杂度:** ⭐ 初学者级别

---

## 描述

路由快捷方式是用于快速设置典型路由配置的快捷方法。

## 所有快捷方式

### 1. auth()
```php
Route::get('/dashboard', $action)->auth();
```

### 2-14. 其他快捷方式
- `guest()` - 访客
- `cors()` - CORS中间件
- `secure()` - 强制HTTPS
- `apiResource()` - API资源
- `resource()` - RESTful资源
- `redirect()` - 重定向
- `view()` - 视图渲染
- `cached()` - 缓存
- `throttled()` - 速率限制
- `tagged()` - 标签
- `named()` - 名称

## 另请参阅

- [中间件](06_MIDDLEWARE.md) - 中间件系统
- [路由宏](11_ROUTE_MACROS.md) - 路由模板
- [API参考](../API_REFERENCE.md) - 完整API参考

---

© 2024 CloudCastle HTTP Router  
[⬆ 返回顶部](#路由快捷方式)