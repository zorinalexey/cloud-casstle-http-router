# Helper Functions - Детальное описание вспомогательных функций

[English](../../en/features/HELPER_FUNCTIONS_FEATURES.md) | **Русский** | [Deutsch](../../de/features/HELPER_FUNCTIONS_FEATURES.md) | [Français](../../fr/features/HELPER_FUNCTIONS_FEATURES.md) | [中文](../../zh/features/HELPER_FUNCTIONS_FEATURES.md)

---

## Содержание

- [route()](#route)
- [router()](#router)
- [current_route()](#current_route)
- [route_url()](#route_url)
- [dispatch_route()](#dispatch_route)
- [Все функции](#все-функции)
- [Сравнение с аналогами](#сравнение-с-аналогами)

---

## route()

### Описание

Получить маршрут по имени или текущий маршрут.

### Использование

```php
// Получить по имени
$route = route('users.show');

// Без аргументов = текущий маршрут
$currentRoute = route();

// С параметрами
$url = route('users.show', ['id' => 123]);
// /users/123
```

---

## router()

### Описание

Получить экземпляр роутера.

```php
$router = router();

// Использование
$router->get('/new-route', $action);
$router->dispatch($uri, $method);
```

---

## current_route()

### Описание

Получить текущий выполняемый маршрут.

```php
$route = current_route();

echo $route->getName();      // users.show
echo $route->getUri();       // /users/{id}
print_r($route->getParameters()); // ['id' => 123]
```

---

## route_url()

### Описание

Генерация URL для именованного маршрута.

```php
// Простой URL
$url = route_url('users.index');
// /users

// С параметрами
$url = route_url('users.show', ['id' => 123]);
// /users/123

// Абсолютный URL
$url = route_url('users.show', ['id' => 123], true);
// https://example.com/users/123

// С query параметрами
$url = route_url('users.index', ['page' => 2, 'sort' => 'name']);
// /users?page=2&sort=name
```

---

## dispatch_route()

### Описание

Выполнить текущий HTTP запрос.

```php
// Автоматически использует $_SERVER
$route = dispatch_route();

// Выполнить действие
$result = $route->run();
```

---

## Все функции

| Функция | Описание | Пример |
|---------|----------|--------|
| `route($name)` | Получить маршрут | `route('users.show')` |
| `router()` | Экземпляр роутера | `router()->get()` |
| `current_route()` | Текущий маршрут | `current_route()` |
| `previous_route()` | Предыдущий маршрут | `previous_route()` |
| `route_url($name, $params)` | URL маршрута | `route_url('users.show', [1])` |
| `route_is($name)` | Проверка имени | `route_is('users.*')` |
| `route_name()` | Имя текущего | `route_name()` |
| `route_has($name)` | Есть ли маршрут | `route_has('admin')` |
| `routes_by_tag($tag)` | По тегу | `routes_by_tag('api')` |
| `route_stats()` | Статистика | `route_stats()` |
| `route_back()` | Назад | `route_back()` |
| `dispatch_route()` | Выполнить | `dispatch_route()` |

---

## Примеры использования

### Навигация

```php
// В шаблоне
<a href="<?= route_url('users.index') ?>">All Users</a>
<a href="<?= route_url('users.show', ['id' => $user->id]) ?>">Profile</a>
```

### Проверки

```php
// Проверить текущий маршрут
if (route_is('admin.*')) {
    echo "Admin area";
}

// Проверить существование
if (route_has('api.v2.users')) {
    $url = route_url('api.v2.users');
} else {
    $url = route_url('api.v1.users');
}
```

### Статистика

```php
$stats = route_stats();

echo "Total routes: " . $stats['total'];
echo "GET routes: " . $stats['methods']['GET'];
echo "Tagged 'api': " . count(routes_by_tag('api'));
```

---

## Сравнение с аналогами

| Роутер | Helper Functions | Количество | Оценка |
|--------|------------------|------------|--------|
| **CloudCastle** | ✅ | **12+** | **⭐⭐⭐⭐⭐** |
| Laravel | ✅ | 10+ | ⭐⭐⭐⭐⭐ |
| Symfony | ⚠️ Частично | 5 | ⭐⭐⭐ |
| FastRoute | ❌ | 0 | ⭐ |
| Slim | ❌ | 0 | ⭐ |

### Детальное сравнение

**CloudCastle:**
```php
route('users.show')          // ✅
route_url('users.show', [1]) // ✅
route_is('admin.*')          // ✅
routes_by_tag('api')         // ✅ Уникально!
```

**Laravel:**
```php
route('users.show')          // ✅
route('users.show', [1])     // ✅
Route::is('admin.*')         // ✅
// Нет routes_by_tag
```

**Symfony:**
```php
$this->generateUrl('users.show') // ⚠️ Только в контроллере
// Нет глобальных функций
```

**FastRoute / Slim:**
```php
// Нет helper функций вообще
```

---

## Заключение

**CloudCastle предлагает полный набор helpers:**

✅ 12+ полезных функций  
✅ Удобный API  
✅ Уникальные возможности (tags, stats)  
✅ Laravel-подобный синтаксис  

**Рекомендация:** Используйте helpers для упрощения кода!

---

[⬆ Наверх](#helper-functions---детальное-описание-вспомогательных-функций) | [📚 Все фичи](../ALL_FEATURES.md) | [🏠 Главная](../../../README.md)

© 2024 CloudCastle HTTP Router
