# PSR支持

[English](../../en/features/17_PSR_SUPPORT.md) | [Русский](../../ru/features/17_PSR_SUPPORT.md) | [Deutsch](../../de/features/17_PSR_SUPPORT.md) | [Français](../../fr/features/17_PSR_SUPPORT.md) | [**中文**](17_PSR_SUPPORT.md)

---

## 📚 文档导航

[README](../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [API_REFERENCE](../API_REFERENCE.md) | [ALL_FEATURES](../ALL_FEATURES.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [PERFORMANCE](../PERFORMANCE_ANALYSIS.md) | [SECURITY](../SECURITY_REPORT.md) | [COMPARISON](../COMPARISON.md) | [FAQ](../FAQ.md)

**详细文档:** [01](01_BASIC_ROUTING.md) | [02](02_ROUTE_PARAMETERS.md) | [03](03_ROUTE_GROUPS.md) | [04](04_RATE_LIMITING.md) | [05](05_IP_FILTERING.md) | [06](06_MIDDLEWARE.md) | [07](07_NAMED_ROUTES.md) | [08](08_TAGS.md) | [09](09_HELPER_FUNCTIONS.md) | [10](10_ROUTE_SHORTCUTS.md) | [11](11_ROUTE_MACROS.md) | [12](12_URL_GENERATION.md) | [13](13_EXPRESSION_LANGUAGE.md) | [14](14_CACHING.md) | [15](15_PLUGINS.md) | [16](16_LOADERS.md) | [17](17_PSR_SUPPORT.md) | [18](18_ACTION_RESOLVER.md) | [19](19_STATISTICS.md) | [20](20_SECURITY.md) | [21](21_EXCEPTIONS.md) | [22](22_CLI_TOOLS.md)

---

**类别:** 标准  
**标准数量:** 3  
**复杂度:** ⭐⭐⭐ 高级

---

## 支持的PSR标准

### PSR-7: HTTP消息接口
```php
use Psr\Http\Message\ServerRequestInterface;
$request = ServerRequestFactory::fromGlobals();
```

### PSR-15: HTTP服务器请求处理器
```php
$adapter = new Psr15MiddlewareAdapter($router);
$response = $adapter->process($request, $handler);
```

## 另请参阅

- [中间件](06_MIDDLEWARE.md) - 中间件系统
- [API参考](../API_REFERENCE.md) - 完整API参考

---

© 2024 CloudCastle HTTP Router  
[⬆ 返回顶部](#psr支持)