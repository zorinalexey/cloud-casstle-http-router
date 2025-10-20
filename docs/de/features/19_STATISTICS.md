# Стат und  mit т und ка  und  Anfrageы

[English](../../en/features/19_STATISTICS.md) | [Русский](../../ru/features/19_STATISTICS.md) | **Deutsch** | [Français](../../fr/features/19_STATISTICS.md) | [中文](../../zh/features/19_STATISTICS.md)

---







---

## 📚 Dokumentationsnavigation

[README](../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [API_REFERENCE](../API_REFERENCE.md) | [ALL_FEATURES](../ALL_FEATURES.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [PERFORMANCE](../PERFORMANCE_ANALYSIS.md) | [SECURITY](../SECURITY_REPORT.md) | [COMPARISON](../COMPARISON.md) | [FAQ](../FAQ.md)

**Detaillierte Dokumentation:** [01](01_BASIC_ROUTING.md) | [02](02_ROUTE_PARAMETERS.md) | [03](03_ROUTE_GROUPS.md) | [04](04_RATE_LIMITING.md) | [05](05_IP_FILTERING.md) | [06](06_MIDDLEWARE.md) | [07](07_NAMED_ROUTES.md) | [08](08_TAGS.md) | [09](09_HELPER_FUNCTIONS.md) | [10](10_ROUTE_SHORTCUTS.md) | [11](11_ROUTE_MACROS.md) | [12](12_URL_GENERATION.md) | [13](13_EXPRESSION_LANGUAGE.md) | [14](14_CACHING.md) | [15](15_PLUGINS.md) | [16](16_LOADERS.md) | [17](17_PSR_SUPPORT.md) | [18](18_ACTION_RESOLVER.md) | [19](19_STATISTICS.md) | [20](20_SECURITY.md) | [21](21_EXCEPTIONS.md) | [22](22_CLI_TOOLS.md)

---


**Kategorie:** А auf л und з Routeо in   
**Anzahl der Methoden:** 24  
**Komplexität:** ⭐⭐ Mittel уро in ень

---

## Оп und  mit ан und е

Methoden  für   nach лучен und я  und нформац und  und  о зарег und  mit тр und ро in анных Routeах,  und х групп und ро in к und ,  nach  und  mit ка  und   mit тат und  mit т und к und .

## Hauptmethoden

### Общая  mit тат und  mit т und ка

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

### Ф und льтрац und я

```php
// По Methodeу
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

### По und  mit к

```php
// Поиск по URI или имени
$results = Route::router()->searchRoutes('user');
// Все маршруты содержащие 'user'
```

### Групп und ро in ка

```php
// По Methodeам
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

### Эк mit  nach рт

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

**Version:** 1.1.1  
**Стату mit :** ✅ Стаб und ль auf я функц und о auf льно mit ть


---

## 📚 Dokumentationsnavigation

[README](../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [API_REFERENCE](../API_REFERENCE.md) | [ALL_FEATURES](../ALL_FEATURES.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [FAQ](../FAQ.md)

**Detaillierte Dokumentation:** [01](01_BASIC_ROUTING.md) | [02](02_ROUTE_PARAMETERS.md) | [03](03_ROUTE_GROUPS.md) | [04](04_RATE_LIMITING.md) | [05](05_IP_FILTERING.md) | [06](06_MIDDLEWARE.md) | [07](07_NAMED_ROUTES.md) | [08](08_TAGS.md) | [09](09_HELPER_FUNCTIONS.md) | [10](10_ROUTE_SHORTCUTS.md) | [11](11_ROUTE_MACROS.md) | [12](12_URL_GENERATION.md) | [13](13_EXPRESSION_LANGUAGE.md) | [14](14_CACHING.md) | [15](15_PLUGINS.md) | [16](16_LOADERS.md) | [17](17_PSR_SUPPORT.md) | [18](18_ACTION_RESOLVER.md) | [19](19_STATISTICS.md) | [20](20_SECURITY.md) | [21](21_EXCEPTIONS.md) | [22](22_CLI_TOOLS.md)

**© 2024 CloudCastle HTTP Router**
