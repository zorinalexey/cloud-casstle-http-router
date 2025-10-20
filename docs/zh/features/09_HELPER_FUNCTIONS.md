# 辅助函数

[English](../../en/features/09_HELPER_FUNCTIONS.md) | [Русский](../../ru/features/09_HELPER_FUNCTIONS.md) | [Deutsch](../../de/features/09_HELPER_FUNCTIONS.md) | [Français](../../fr/features/09_HELPER_FUNCTIONS.md) | [**中文**](09_HELPER_FUNCTIONS.md)

---

## 📚 文档导航

[README](../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [API_REFERENCE](../API_REFERENCE.md) | [ALL_FEATURES](../ALL_FEATURES.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [PERFORMANCE](../PERFORMANCE_ANALYSIS.md) | [SECURITY](../SECURITY_REPORT.md) | [COMPARISON](../COMPARISON.md) | [FAQ](../FAQ.md)

**详细文档:** [01](01_BASIC_ROUTING.md) | [02](02_ROUTE_PARAMETERS.md) | [03](03_ROUTE_GROUPS.md) | [04](04_RATE_LIMITING.md) | [05](05_IP_FILTERING.md) | [06](06_MIDDLEWARE.md) | [07](07_NAMED_ROUTES.md) | [08](08_TAGS.md) | [09](09_HELPER_FUNCTIONS.md) | [10](10_ROUTE_SHORTCUTS.md) | [11](11_ROUTE_MACROS.md) | [12](12_URL_GENERATION.md) | [13](13_EXPRESSION_LANGUAGE.md) | [14](14_CACHING.md) | [15](15_PLUGINS.md) | [16](16_LOADERS.md) | [17](17_PSR_SUPPORT.md) | [18](18_ACTION_RESOLVER.md) | [19](19_STATISTICS.md) | [20](20_SECURITY.md) | [21](21_EXCEPTIONS.md) | [22](22_CLI_TOOLS.md)

---

**类别:** 辅助函数  
**函数数量:** 18  
**复杂度:** ⭐ 初学者级别

---

## 描述

辅助函数是全局PHP函数，简化了与路由器的工作，提供简短方便的API，无需使用完整的类名。

## 所有函数

### 1. route()

**签名:** `route(?string $name = null, array $parameters = []): ?Route`

**描述:** 按名称获取路由或返回当前路由。

**参数:**
- `$name` - 路由名称（null = 当前路由）
- `$parameters` - 替换参数（保留）

**示例:**

```php
// 按名称获取路由
$route = route('users.show');

// 获取当前路由
$current = route();

// 检查存在
if ($route = route('users.index')) {
    echo "路由存在: " . $route->getUri();
}

// 获取路由信息
$route = route('api.users.show');
if ($route) {
    echo "URI: " . $route->getUri();
    echo "名称: " . $route->getName();
    echo "方法: " . implode(', ', $route->getMethods());
}
```

### 2. current_route()

**签名:** `current_route(): ?Route`

**描述:** 获取当前执行的路由。

**示例:**

```php
$route = current_route();
echo "当前: " . $route->getUri();
```

### 3. previous_route()

**签名:** `previous_route(): ?Route`

**描述:** 获取之前执行的路由。

**示例:**

```php
$previous = previous_route();
if ($previous) {
    echo "之前: " . $previous->getUri();
}
```

### 4. route_is()

**签名:** `route_is(string $name): bool`

**描述:** 检查当前路由是否匹配名称。

**示例:**

```php
if (route_is('users.show')) {
    echo "查看用户";
}

if (route_is('admin.*')) {
    echo "管理部分";
}
```

### 5. route_name()

**签名:** `route_name(): ?string`

**描述:** 获取当前路由名称。

**示例:**

```php
$name = route_name();
// 'users.show'
```

### 6. router()

**签名:** `router(): Router`

**描述:** 获取路由器实例。

**示例:**

```php
$router = router();
$allRoutes = $router->getAllRoutes();
```

### 7. dispatch_route()

**签名:** `dispatch_route(string $uri, string $method = 'GET'): ?Route`

**描述:** 调度和执行路由。

**示例:**

```php
$route = dispatch_route('/users/123', 'GET');
```

## 快速参考

```php
// 获取路由
route('users.show')          // 按名称获取
current_route()              // 当前路由
previous_route()             // 之前路由

// 检查路由
route_is('users.show')       // 检查名称
route_name()                 // 获取名称

// 路由器访问
router()                     // 获取路由器
dispatch_route('/users')     // 调度路由
```

## 另请参阅

- [命名路由](07_NAMED_ROUTES.md) - 路由命名
- [URL生成](12_URL_GENERATION.md) - URL生成
- [API参考](../API_REFERENCE.md) - 完整API参考

---

© 2024 CloudCastle HTTP Router  
[⬆ 返回顶部](#辅助函数)