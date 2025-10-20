# Статистика и запросы

**Категория:** Анализ маршрутов  
**Количество методов:** 24  
**Сложность:** ⭐⭐ Средний уровень

---

## Описание

Методы для получения информации о зарегистрированных маршрутах, их группировки, поиска и статистики.

## Основные методы

### Общая статистика

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

### Фильтрация

```php
// По методу
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

### Поиск

```php
// Поиск по URI или имени
$results = Route::router()->searchRoutes('user');
// Все маршруты содержащие 'user'
```

### Группировка

```php
// По методам
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

### Экспорт

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

**Версия:** 1.1.1  
**Статус:** ✅ Стабильная функциональность

