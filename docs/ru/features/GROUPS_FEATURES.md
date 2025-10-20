# Groups Features - Детальное описание групп маршрутов

[English](../../en/features/GROUPS_FEATURES.md) | **Русский** | [Deutsch](../../de/features/GROUPS_FEATURES.md) | [Français](../../fr/features/GROUPS_FEATURES.md) | [中文](../../zh/features/GROUPS_FEATURES.md)

---

## Содержание

- [Базовые группы](#базовые-группы)
- [Префиксы](#префиксы)
- [Middleware в группе](#middleware-в-группе)
- [Вложенные группы](#вложенные-группы)
- [Атрибуты групп](#атрибуты-групп)
- [Сравнение с аналогами](#сравнение-с-аналогами)

---

## Базовые группы

### Описание

Группы позволяют применять общие атрибуты к нескольким маршрутам одновременно.

### Использование

```php
$router->group(['prefix' => '/api'], function() use ($router) {
    $router->get('/users', $action);    // /api/users
    $router->get('/posts', $action);    // /api/posts
    $router->get('/comments', $action); // /api/comments
});
```

### Сравнение

| Роутер | Группы | Атрибутов | API | Оценка |
|--------|--------|-----------|-----|--------|
| **CloudCastle** | ✅ | **12+** | Простой | **⭐⭐⭐⭐⭐** |
| Laravel | ✅ | 10+ | Простой | ⭐⭐⭐⭐⭐ |
| Symfony | ✅ | 8+ | Сложный | ⭐⭐⭐⭐ |
| FastRoute | ✅ | 2 | Базовый | ⭐⭐⭐ |
| Slim | ✅ | 3 | Средний | ⭐⭐⭐⭐ |

---

## Префиксы

```php
// Простой префикс
$router->group(['prefix' => '/admin'], function() {
    $router->get('/dashboard', $action); // /admin/dashboard
    $router->get('/users', $action);     // /admin/users
});

// Версионирование API
$router->group(['prefix' => '/api/v1'], function() {
    $router->get('/users', $action); // /api/v1/users
});

$router->group(['prefix' => '/api/v2'], function() {
    $router->get('/users', $action); // /api/v2/users
});
```

---

## Middleware в группе

```php
$router->group(['middleware' => [AuthMiddleware::class]], function() {
    $router->get('/dashboard', $action);
    $router->get('/profile', $action);
    $router->get('/settings', $action);
    // Все с AuthMiddleware
});
```

---

## Вложенные группы

CloudCastle поддерживает до **50 уровней вложенности**!

```php
$router->group(['prefix' => '/api'], function() {
    $router->group(['prefix' => '/v1'], function() {
        $router->group(['middleware' => ['auth']], function() {
            $router->get('/users', $action);
            // /api/v1/users с auth middleware
        });
    });
});
```

### Сравнение

| Роутер | Max вложенность | Оценка |
|--------|-----------------|--------|
| **CloudCastle** | **Неограничено** | **⭐⭐⭐⭐⭐** |
| Laravel | Неограничено | ⭐⭐⭐⭐⭐ |
| Symfony | Неограничено | ⭐⭐⭐⭐⭐ |
| FastRoute | ~10 | ⭐⭐⭐ |
| Slim | ~20 | ⭐⭐⭐⭐ |

---

## Атрибуты групп

### Все доступные атрибуты

```php
$router->group([
    'prefix' => '/admin',               // Префикс URI
    'middleware' => ['auth', 'admin'],  // Middleware
    'domain' => 'admin.example.com',    // Домен
    'port' => 8080,                     // Порт
    'https' => true,                    // Только HTTPS
    'throttle' => 100,                  // Rate limiting
    'whitelistIp' => ['192.168.1.0/24'],// IP Whitelist
    'blacklistIp' => ['1.2.3.4'],       // IP Blacklist
    'namespace' => 'App\\Admin',        // Namespace контроллеров
    'tags' => ['admin', 'secure'],      // Теги
    'protocols' => ['https'],           // Протоколы
    'plugins' => [$plugin],             // Плагины
], function() {
    // Маршруты
});
```

### Сравнение атрибутов

| Атрибут | CloudCastle | Laravel | Symfony | FastRoute | Slim |
|---------|-------------|---------|---------|-----------|------|
| prefix | ✅ | ✅ | ✅ | ✅ | ✅ |
| middleware | ✅ | ✅ | ⚠️ | ❌ | ✅ |
| domain | ✅ | ✅ | ✅ | ❌ | ❌ |
| port | ✅ | ❌ | ❌ | ❌ | ❌ |
| https | ✅ | ⚠️ | ✅ | ❌ | ❌ |
| throttle | ✅ | ✅ | ❌ | ❌ | ❌ |
| whitelistIp | ✅ | ❌ | ❌ | ❌ | ❌ |
| namespace | ✅ | ✅ | ✅ | ❌ | ❌ |
| tags | ✅ | ❌ | ❌ | ❌ | ❌ |
| protocols | ✅ | ❌ | ✅ | ❌ | ❌ |
| plugins | ✅ | ❌ | ❌ | ❌ | ❌ |

**Уникальность:** CloudCastle имеет больше всего атрибутов групп!

---

## Примеры реального использования

### API с версионированием

```php
$router->group(['prefix' => '/api'], function() {
    // v1
    $router->group(['prefix' => '/v1', 'tags' => ['api-v1']], function() {
        $router->get('/users', UserV1Controller::class);
    });
    
    // v2  
    $router->group(['prefix' => '/v2', 'tags' => ['api-v2']], function() {
        $router->get('/users', UserV2Controller::class);
    });
});
```

### Микросервисы

```php
// User Service (port 8081)
$router->group(['port' => 8081, 'prefix' => '/users'], function() {
    $router->get('/', $action);
    $router->get('/{id}', $action);
});

// Product Service (port 8082)
$router->group(['port' => 8082, 'prefix' => '/products'], function() {
    $router->get('/', $action);
    $router->get('/{id}', $action);
});
```

---

## Заключение

**CloudCastle предлагает самые гибкие группы:**

✅ 12+ атрибутов (больше всех)  
✅ До 50 уровней вложенности  
✅ Уникальные возможности (port, tags, plugins)  
✅ Простой API  

---

[⬆ Наверх](#groups-features---детальное-описание-групп-маршрутов) | [📚 Все фичи](../ALL_FEATURES.md) | [🏠 Главная](../../../README.md)

© 2024 CloudCastle HTTP Router
