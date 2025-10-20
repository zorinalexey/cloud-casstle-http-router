# Advanced Routing Features - Расширенные возможности маршрутизации

[English](../../en/features/ADVANCED_ROUTING_FEATURES.md) | **Русский** | [Deutsch](../../de/features/ADVANCED_ROUTING_FEATURES.md) | [Français](../../fr/features/ADVANCED_ROUTING_FEATURES.md) | [中文](../../zh/features/ADVANCED_ROUTING_FEATURES.md)

---

## Содержание

- [Parameter Constraints (where)](#parameter-constraints-where)
- [Default Parameter Values](#default-parameter-values)
- [Time-based Rate Limiting](#time-based-rate-limiting)
- [WebSocket Support](#websocket-support)
- [Route Search & Filtering](#route-search--filtering)
- [Route Statistics](#route-statistics)
- [Route Export](#route-export)
- [Current & Previous Routes](#current--previous-routes)
- [Сравнение с аналогами](#сравнение-с-аналогами)

---

## Parameter Constraints (where)

### Описание

Наложение regex ограничений на параметры маршрута.

### Использование

```php
// Только цифры
Route::get('/users/{id}', $action)
    ->where('id', '[0-9]+');

// Буквы и дефисы
Route::get('/posts/{slug}', $action)
    ->where('slug', '[a-z\-]+');

// Множественные ограничения
Route::get('/archive/{year}/{month}', $action)
    ->where('year', '[0-9]{4}')
    ->where('month', '[0-9]{2}');

// UUID
Route::get('/items/{uuid}', $action)
    ->where('uuid', '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}');
```

### Глобальные паттерны

```php
// В RouteServiceProvider или bootstrap
Router::pattern('id', '[0-9]+');
Router::pattern('uuid', '[0-9a-f-]+');
Router::pattern('slug', '[a-z0-9\-]+');

// Теперь все {id} автоматически проверяются
Route::get('/users/{id}', $action); // auto: where('id', '[0-9]+')
Route::get('/posts/{id}', $action); // auto: where('id', '[0-9]+')
```

---

## Default Parameter Values

### Описание

Значения по умолчанию для опциональных параметров.

### Использование

```php
// Один параметр
Route::get('/users/{role?}', $action)
    ->default('role', 'guest');

// Множественные
Route::get('/search/{query?}/{sort?}/{page?}', $action)
    ->defaults([
        'query' => '',
        'sort' => 'date',
        'page' => 1
    ]);

// С проверкой
Route::get('/catalog/{category?}/{limit?}', $action)
    ->where('limit', '[0-9]+')
    ->defaults([
        'category' => 'all',
        'limit' => 20
    ]);
```

---

## Time-based Rate Limiting

### Описание

Удобные методы для rate limiting по времени.

### perSecond()

```php
// 10 запросов в секунду
Route::post('/api/track', $action)
    ->perSecond(10);

// 5 запросов за 3 секунды
Route::post('/api/upload', $action)
    ->perSecond(5, 3);
```

### perMinute()

```php
// 60 запросов в минуту
Route::get('/api/data', $action)
    ->perMinute(60);

// 20 запросов за 5 минут
Route::post('/api/search', $action)
    ->perMinute(20, 5);
```

### perHour()

```php
// 1000 запросов в час
Route::get('/api/export', $action)
    ->perHour(1000);

// 500 запросов за 6 часов
Route::post('/api/batch', $action)
    ->perHour(500, 6);
```

### perDay()

```php
// 10000 запросов в день
Route::get('/api/reports', $action)
    ->perDay(10000);

// 1000 запросов за 7 дней
Route::post('/api/heavy', $action)
    ->perDay(1000, 7);
```

### perWeek()

```php
// 50000 запросов в неделю
Route::get('/api/analytics', $action)
    ->perWeek(50000);
```

### perMonth()

```php
// 1000000 запросов в месяц
Route::get('/api/stats', $action)
    ->perMonth(1000000);
```

### throttleWithBan()

**Автоматический бан после превышения лимита!**

```php
// После 3 нарушений - бан на 1 час
Route::post('/login', $action)
    ->throttleWithBan(
        maxAttempts: 5,
        decaySeconds: 60,
        maxViolations: 3,
        banDurationSeconds: 3600
    );

// Защита от брутфорса
Route::post('/api/auth', $action)
    ->throttleWithBan(
        maxAttempts: 10,
        decaySeconds: 60,
        maxViolations: 5,
        banDurationSeconds: 86400 // 24 часа
    );
```

---

## WebSocket Support

### Описание

Поддержка WebSocket маршрутов.

### websocket()

```php
// WebSocket (ws:// и wss://)
Route::custom('WEBSOCKET', '/chat', $action)
    ->websocket();

// Только ws://
Route::custom('WEBSOCKET', '/stream', $action)
    ->protocol('ws');
```

### secureWebsocket()

```php
// Только wss:// (secure)
Route::custom('WEBSOCKET', '/secure-chat', $action)
    ->secureWebsocket();
```

### httpOrHttps()

```php
// HTTP и HTTPS
Route::get('/api/public', $action)
    ->httpOrHttps();
```

---

## Route Search & Filtering

### searchRoutes()

Мощный поиск по множественным критериям:

```php
// Поиск по критериям
$routes = $router->searchRoutes([
    'method' => 'GET',
    'domain' => 'api.example.com',
    'has_middleware' => 'auth',
    'has_tag' => 'public',
]);

// Комплексный поиск
$routes = $router->searchRoutes([
    'uri_pattern' => '/api/',
    'methods' => ['GET', 'POST'],
    'has_throttle' => true,
    'has_domain' => true,
]);
```

### Фильтрация по различным критериям

```php
// По домену
$routes = $router->getRoutesByDomain('api.example.com');

// По порту
$routes = $router->getRoutesByPort(8080);

// По IP whitelist
$routes = $router->getRoutesByWhitelistedIp('192.168.1.100');

// По middleware
$routes = $router->getRoutesByMiddleware('auth');

// По URI паттерну
$routes = $router->getRoutesByUriPattern('/api/v1/');

// По префиксу
$routes = $router->getRoutesByPrefix('/admin');

// По имени (паттерн)
$routes = $router->getNamedRoutesMatching('users.*');

// По типу action
$routes = $router->getRoutesByActionType('closure');
$routes = $router->getRoutesByActionType('controller');

// По контроллеру
$routes = $router->getRoutesByController(UserController::class);

// С ограничениями
$routes = $router->getRoutesWithDomain();
$routes = $router->getRoutesWithPort();
$routes = $router->getRoutesWithIpRestrictions();
$routes = $router->getThrottledRoutes();
```

---

## Route Statistics

```php
$stats = $router->getRouteStats();

/*
Array:
[
    'total' => 150,
    'methods' => [
        'GET' => 80,
        'POST' => 40,
        'PUT' => 15,
        'DELETE' => 10,
        'PATCH' => 5
    ],
    'named' => 120,
    'unnamed' => 30,
    'with_middleware' => 90,
    'with_domain' => 20,
    'with_port' => 5,
    'with_throttle' => 50,
    'with_ip_restrictions' => 10,
    'with_tags' => 60,
    'grouped' => 100,
    'cached' => true
]
*/

// Использование
echo "Total routes: " . $stats['total'];
echo "GET routes: " . $stats['methods']['GET'];
echo "Protected: " . $stats['with_middleware'];
```

### Подсчет

```php
// Общее количество
$count = $router->count();

// Или через Countable
$count = count($router);
```

---

## Route Export

### getRoutesAsJson()

```php
// JSON экспорт
$json = $router->getRoutesAsJson();

// С pretty print
$json = $router->getRoutesAsJson(JSON_PRETTY_PRINT);

// С дополнительными флагами
$json = $router->getRoutesAsJson(
    JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
);
```

### getRoutesAsArray()

```php
// Массив для API
$routes = $router->getRoutesAsArray();

/*
[
    [
        'methods' => ['GET'],
        'uri' => '/users',
        'name' => 'users.index',
        'action' => 'UserController@index',
        'middleware' => ['auth'],
        'domain' => null,
        'port' => null,
    ],
    // ...
]
*/
```

### Группировка

```php
// По методам
$grouped = $router->getRoutesGroupedByMethod();
/*
[
    'GET' => [...],
    'POST' => [...],
]
*/

// По префиксам
$grouped = $router->getRoutesGroupedByPrefix();
/*
[
    '/api' => [...],
    '/admin' => [...],
]
*/

// По доменам
$grouped = $router->getRoutesGroupedByDomain();
/*
[
    'api.example.com' => [...],
    'admin.example.com' => [...],
]
*/
```

---

## Current & Previous Routes

### Текущий маршрут

```php
// Получить текущий Route объект
$current = $router->current();

// Имя текущего маршрута
$name = $router->currentRouteName();

// Проверка имени
if ($router->currentRouteNamed('users.show')) {
    // ...
}
```

### Предыдущий маршрут

```php
// Предыдущий Route объект
$previous = $router->previous();

// Имя
$name = $router->previousRouteName();

// URI
$uri = $router->previousRouteUri();

// Проверка
if ($router->previousRouteNamed('users.index')) {
    // Пришли со списка пользователей
}
```

### Helper функции

```php
// Текущий маршрут
$route = current_route();
$name = route_name();

// Предыдущий
$prev = previous_route();

// Назад
return route_back(); // Redirect на предыдущий маршрут
```

---

## Сравнение с аналогами

| Фича | CloudCastle | Laravel | Symfony | FastRoute | Slim |
|------|-------------|---------|---------|-----------|------|
| **where()** | ✅ | ✅ | ✅ | ❌ | ❌ |
| **defaults()** | ✅ | ✅ | ✅ | ❌ | ❌ |
| **perSecond/Minute/Hour** | ✅ | ⚠️ Partial | ❌ | ❌ | ❌ |
| **throttleWithBan()** | ✅ | ❌ | ❌ | ❌ | ❌ |
| **websocket()** | ✅ | ❌ | ⚠️ | ❌ | ❌ |
| **searchRoutes()** | ✅ | ⚠️ Limited | ❌ | ❌ | ❌ |
| **getRouteStats()** | ✅ | ⚠️ | ⚠️ | ❌ | ❌ |
| **getRoutesAsJson()** | ✅ | ⚠️ | ✅ | ❌ | ❌ |
| **current()/previous()** | ✅ | ✅ | ✅ | ❌ | ❌ |

### Уникальные возможности CloudCastle

✅ **throttleWithBan()** - автобан после превышения  
✅ **perDay/Week/Month()** - длительные периоды  
✅ **searchRoutes()** - мощный поиск  
✅ **getRouteStats()** - детальная статистика  
✅ **websocket()/secureWebsocket()** - WebSocket из коробки  

---

## Заключение

**CloudCastle предлагает 30+ расширенных возможностей:**

✅ Parameter constraints  
✅ Default values  
✅ Time-based throttling (6 методов!)  
✅ Auto-ban throttling  
✅ WebSocket support  
✅ Мощный поиск и фильтрация  
✅ Детальная статистика  
✅ Множественные форматы экспорта  
✅ Current/Previous routes  

**Рекомендация:** Используйте расширенные возможности для максимального контроля!

---

[⬆ Наверх](#advanced-routing-features---расширенные-возможности-маршрутизации) | [📚 Все фичи](../ALL_FEATURES.md) | [🏠 Главная](../../../README.md)

© 2024 CloudCastle HTTP Router

