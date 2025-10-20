#   请求

[English](../../en/features/19_STATISTICS.md) | [Русский](../../ru/features/19_STATISTICS.md) | [Deutsch](../../de/features/19_STATISTICS.md) | [Français](../../fr/features/19_STATISTICS.md) | **中文**

---







---

## 📚 文档导航

[README](../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [API_REFERENCE](../API_REFERENCE.md) | [ALL_FEATURES](../ALL_FEATURES.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [PERFORMANCE](../PERFORMANCE_ANALYSIS.md) | [SECURITY](../SECURITY_REPORT.md) | [COMPARISON](../COMPARISON.md) | [FAQ](../FAQ.md)

**详细文档：** [01](01_BASIC_ROUTING.md) | [02](02_ROUTE_PARAMETERS.md) | [03](03_ROUTE_GROUPS.md) | [04](04_RATE_LIMITING.md) | [05](05_IP_FILTERING.md) | [06](06_MIDDLEWARE.md) | [07](07_NAMED_ROUTES.md) | [08](08_TAGS.md) | [09](09_HELPER_FUNCTIONS.md) | [10](10_ROUTE_SHORTCUTS.md) | [11](11_ROUTE_MACROS.md) | [12](12_URL_GENERATION.md) | [13](13_EXPRESSION_LANGUAGE.md) | [14](14_CACHING.md) | [15](15_PLUGINS.md) | [16](16_LOADERS.md) | [17](17_PSR_SUPPORT.md) | [18](18_ACTION_RESOLVER.md) | [19](19_STATISTICS.md) | [20](20_SECURITY.md) | [21](21_EXCEPTIONS.md) | [22](22_CLI_TOOLS.md)

---


**类别:**  路由  
**数量 方法:** 24  
**复杂度：** ⭐⭐ 中级 

---

## 

方法      路由,  ,   .

## 主要方法

###  

```php
// Полная статистика
$stats = Route::getRouteStats();
/*
[
    'total' => 150,
    'named' => 120,
    'with_middleware' => 60,
    'with_tags' => 80,
    'methods' => ['GET' => 80, 'POST' => 40, ...],
    'domains' => ['api.example.com' => 30],
    'ports' => [8080 => 20]
]
*/

// Количество маршрутов
$count = Route::count();

// Все маршруты
$routes = Route::getRoutes();

// Именованные маршруты
$named = Route::getNamedRoutes();
```

### 

```php
// По 方法у
$getRoutes = Route::router()->getRoutesByMethod('GET');
$postRoutes = Route::router()->getRoutesByMethod('POST');

// По домену
$apiRoutes = Route::router()->getRoutesByDomain('api.example.com');

// По порту
$routes = Route::router()->getRoutesByPort(8080);

// По префиксу
$adminRoutes = Route::router()->getRoutesByPrefix('/admin');

// По тегу
$publicRoutes = Route::getRoutesByTag('public');

// По middleware
$authRoutes = Route::router()->getRoutesByMiddleware(AuthMiddleware::class);

// По контроллеру
$userRoutes = Route::router()->getRoutesByController(UserController::class);

// С IP ограничениями
$restricted = Route::router()->getRoutesWithIpRestrictions();

// С throttle
$throttled = Route::router()->getThrottledRoutes();

// С доменом
$withDomain = Route::router()->getRoutesWithDomain();

// С портом
$withPort = Route::router()->getRoutesWithPort();
```

### 

```php
// Поиск по URI или имени
$results = Route::router()->searchRoutes('user');
// Все маршруты содержащие 'user'
```

### 

```php
// По 方法ам
$grouped = Route::getRoutesGroupedByMethod();
/*
[
    'GET' => [Route, Route, ...],
    'POST' => [Route, Route, ...],
]
*/

// По префиксу
$grouped = Route::getRoutesGroupedByPrefix();
/*
[
    '/api' => [Route, Route, ...],
    '/admin' => [Route, Route, ...]
]
*/

// По домену
$grouped = Route::getRoutesGroupedByDomain();
```

### 

```php
// В JSON
$json = Route::getRoutesAsJson(JSON_PRETTY_PRINT);

// В массив
$array = Route::getRoutesAsArray();

// Все домены
$domains = Route::router()->getAllDomains();

// Все порты
$ports = Route::router()->getAllPorts();

// Все теги
$tags = Route::router()->getAllTags();
```

---

**版本：** 1.1.1  
**:** ✅  


---

## 📚 文档导航

[README](../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [API_REFERENCE](../API_REFERENCE.md) | [ALL_FEATURES](../ALL_FEATURES.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [FAQ](../FAQ.md)

**详细文档：** [01](01_BASIC_ROUTING.md) | [02](02_ROUTE_PARAMETERS.md) | [03](03_ROUTE_GROUPS.md) | [04](04_RATE_LIMITING.md) | [05](05_IP_FILTERING.md) | [06](06_MIDDLEWARE.md) | [07](07_NAMED_ROUTES.md) | [08](08_TAGS.md) | [09](09_HELPER_FUNCTIONS.md) | [10](10_ROUTE_SHORTCUTS.md) | [11](11_ROUTE_MACROS.md) | [12](12_URL_GENERATION.md) | [13](13_EXPRESSION_LANGUAGE.md) | [14](14_CACHING.md) | [15](15_PLUGINS.md) | [16](16_LOADERS.md) | [17](17_PSR_SUPPORT.md) | [18](18_ACTION_RESOLVER.md) | [19](19_STATISTICS.md) | [20](20_SECURITY.md) | [21](21_EXCEPTIONS.md) | [22](22_CLI_TOOLS.md)

**© 2024 CloudCastle HTTP Router**
