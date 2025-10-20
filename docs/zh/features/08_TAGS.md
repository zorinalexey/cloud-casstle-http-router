# 路由标签

[English](../../en/features/08_TAGS.md) | [Русский](../../ru/features/08_TAGS.md) | [Deutsch](../../de/features/08_TAGS.md) | [Français](../../fr/features/08_TAGS.md) | [**中文**](08_TAGS.md)

---

## 📚 文档导航

[README](../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [API_REFERENCE](../API_REFERENCE.md) | [ALL_FEATURES](../ALL_FEATURES.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [PERFORMANCE](../PERFORMANCE_ANALYSIS.md) | [SECURITY](../SECURITY_REPORT.md) | [COMPARISON](../COMPARISON.md) | [FAQ](../FAQ.md)

**详细文档:** [01](01_BASIC_ROUTING.md) | [02](02_ROUTE_PARAMETERS.md) | [03](03_ROUTE_GROUPS.md) | [04](04_RATE_LIMITING.md) | [05](05_IP_FILTERING.md) | [06](06_MIDDLEWARE.md) | [07](07_NAMED_ROUTES.md) | [08](08_TAGS.md) | [09](09_HELPER_FUNCTIONS.md) | [10](10_ROUTE_SHORTCUTS.md) | [11](11_ROUTE_MACROS.md) | [12](12_URL_GENERATION.md) | [13](13_EXPRESSION_LANGUAGE.md) | [14](14_CACHING.md) | [15](15_PLUGINS.md) | [16](16_LOADERS.md) | [17](17_PSR_SUPPORT.md) | [18](18_ACTION_RESOLVER.md) | [19](19_STATISTICS.md) | [20](20_SECURITY.md) | [21](21_EXCEPTIONS.md) | [22](22_CLI_TOOLS.md)

---

**类别:** 代码组织  
**方法数量:** 5  
**复杂度:** ⭐ 初学者级别

---

## 方法

### 1. tag()

```php
// 单个标签
Route::get('/api/users', $action)->tag('api');

// 多个标签
Route::get('/api/public/posts', $action)->tag(['api', 'public']);
```

### 2. getRoutesByTag()

```php
$apiRoutes = Route::getRoutesByTag('api');

foreach ($apiRoutes as $route) {
    echo $route->getUri() . "\n";
}
```

### 3. hasTag()

```php
if (Route::router()->hasTag('api')) {
    echo "有API路由";
}
```

### 4. getAllTags()

```php
$allTags = Route::router()->getAllTags();
// ['api', 'public', 'admin', 'protected']
```

### 5. getTags() (在Route上)

```php
$route = Route::current();
$tags = $route->getTags();
// ['api', 'public']
```

## 使用

### 路由组织

```php
Route::group(['tag' => 'api'], function() {
    Route::get('/users', $action);
    Route::get('/posts', $action);
});

// 多个标签
Route::group(['tag' => ['api', 'v1', 'public']], function() {
    Route::get('/data', $action);
});
```

### 文档生成

```php
$apiRoutes = Route::getRoutesByTag('api');

foreach ($apiRoutes as $route) {
    echo "端点: " . $route->getUri() . "\n";
    echo "方法: " . implode(', ', $route->getMethods()) . "\n";
}
```

### 缓存管理

```php
// 清除标记路由的缓存
$apiRoutes = Route::getRoutesByTag('api');
foreach ($apiRoutes as $route) {
    Cache::forget($route->getName());
}
```

## 最佳实践

```php
// 按功能组织
Route::get('/api/users', $action)->tag(['api', 'users']);
Route::get('/api/posts', $action)->tag(['api', 'posts']);
Route::get('/admin/users', $action)->tag(['admin', 'users']);

// 与其他功能结合
Route::get('/api/data', $action)
    ->tag('api')
    ->name('api.data')
    ->throttle(100, 1);
```

## 另请参阅

- [命名路由](07_NAMED_ROUTES.md) - 路由命名
- [路由组](03_ROUTE_GROUPS.md) - 路由组织
- [API参考](../API_REFERENCE.md) - 完整API参考

---

© 2024 CloudCastle HTTP Router  
[⬆ 返回顶部](#路由标签)