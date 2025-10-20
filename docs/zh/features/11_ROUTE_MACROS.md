# 路由宏

[English](../../en/features/11_ROUTE_MACROS.md) | [Русский](../../ru/features/11_ROUTE_MACROS.md) | [Deutsch](../../de/features/11_ROUTE_MACROS.md) | [Français](../../fr/features/11_ROUTE_MACROS.md) | [**中文**](11_ROUTE_MACROS.md)

---

## 📚 文档导航

[README](../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [API_REFERENCE](../API_REFERENCE.md) | [ALL_FEATURES](../ALL_FEATURES.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [PERFORMANCE](../PERFORMANCE_ANALYSIS.md) | [SECURITY](../SECURITY_REPORT.md) | [COMPARISON](../COMPARISON.md) | [FAQ](../FAQ.md)

**详细文档:** [01](01_BASIC_ROUTING.md) | [02](02_ROUTE_PARAMETERS.md) | [03](03_ROUTE_GROUPS.md) | [04](04_RATE_LIMITING.md) | [05](05_IP_FILTERING.md) | [06](06_MIDDLEWARE.md) | [07](07_NAMED_ROUTES.md) | [08](08_TAGS.md) | [09](09_HELPER_FUNCTIONS.md) | [10](10_ROUTE_SHORTCUTS.md) | [11](11_ROUTE_MACROS.md) | [12](12_URL_GENERATION.md) | [13](13_EXPRESSION_LANGUAGE.md) | [14](14_CACHING.md) | [15](15_PLUGINS.md) | [16](16_LOADERS.md) | [17](17_PSR_SUPPORT.md) | [18](18_ACTION_RESOLVER.md) | [19](19_STATISTICS.md) | [20](20_SECURITY.md) | [21](21_EXCEPTIONS.md) | [22](22_CLI_TOOLS.md)

---

**类别:** 自动化  
**宏数量:** 7  
**复杂度:** ⭐⭐ 中级

---

## 描述

路由宏是预定义的路由模板，用于快速创建标准路由集（RESTful CRUD、认证、管理等）。

## 宏

### 1. resource() - RESTful资源

```php
Route::resource('users', UserController::class);
```

创建7个路由: index, create, store, show, edit, update, destroy

### 2. apiResource() - API资源

```php
Route::apiResource('posts', PostController::class);
```

创建5个路由（无create/edit表单）

### 3-7. 其他宏
- `auth()` - 认证路由
- `admin()` - 管理面板路由
- `api()` - API路由
- `crud()` - 简单CRUD
- Custom Macro - 自定义宏

## 另请参阅

- [路由快捷方式](10_ROUTE_SHORTCUTS.md) - 快速配置
- [基本路由](01_BASIC_ROUTING.md) - 路由注册
- [API参考](../API_REFERENCE.md) - 完整API参考

---

© 2024 CloudCastle HTTP Router  
[⬆ 返回顶部](#路由宏)