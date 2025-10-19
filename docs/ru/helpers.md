# Helper Functions - Глобальные функции-помощники

**Languages:** 🇷🇺 Русский | [🇬🇧 English](../en/helpers.md) | [🇩🇪 Deutsch](../de/helpers.md) | [🇫🇷 Français](../fr/helpers.md) | [🇨🇳 中文](../zh/helpers.md)

[📚 Оглавление](_table-of-contents.md) | [🏠 Главная](README.md)

---

## 📚 Обзор

CloudCastle HTTP Router предоставляет **15+ глобальных helper функций** для упрощения работы с маршрутами.

## 🎯 Основные Helpers

### 1. route() - Получение маршрута

```php
// Получить маршрут по имени
$route = route('users.show');

// Получить текущий маршрут (без параметров)
$current = route();
```

**Возвращает:** `Route|null`

---

### 2. current_route() - Текущий маршрут

```php
$current = current_route();

if ($current) {
    echo "Current: " . $current->getName();
    echo "URI: " . $current->getUri();
}
```

**Возвращает:** `Route|null`

---

### 3. previous_route() - Предыдущий маршрут

```php
$previous = previous_route();

if ($previous) {
    echo "Previous: " . $previous->getName();
}
```

**Применение:**
- Breadcrumbs
- Navigation
- "Back" buttons

---

### 4. route_is() - Проверка текущего маршрута

```php
if (route_is('users.index')) {
    echo "On users listing page";
}

if (route_is(['users.index', 'users.show'])) {
    echo "On any users page";
}
```

**Параметры:** `string|array`  
**Возвращает:** `bool`

**Использование в views:**
```php
<nav>
    <a href="/users" class="<?= route_is('users.index') ? 'active' : '' ?>">
        Users
    </a>
    <a href="/posts" class="<?= route_is('posts.index') ? 'active' : '' ?>">
        Posts
    </a>
</nav>
```

---

### 5. route_name() - Имя текущего маршрута

```php
$name = route_name();
// "users.index"

echo "Current page: " . route_name();
```

**Возвращает:** `string|null`

---

### 6. route_url() - Генерация URL

```php
// Простой URL
$url = route_url('users.index');
// "/users"

// С параметрами
$url = route_url('users.show', ['id' => 123]);
// "/users/123"

// С query parameters
$url = route_url('users.show', ['id' => 123], ['edit' => 1]);
// "/users/123?edit=1"
```

**Параметры:**
1. `$name` - имя маршрута
2. `$parameters` - параметры маршрута
3. `$queryParams` - query параметры

---

### 7. route_has() - Проверка существования

```php
if (route_has('admin.dashboard')) {
    echo "Admin route exists";
}

if (!route_has('non.existent')) {
    echo "Route doesn't exist";
}
```

**Возвращает:** `bool`

**Применение:**
- Feature detection
- Conditional navigation
- Module checks

---

### 8. router() - Инстанс роутера

```php
$router = router();

// Получить статистику
$stats = router()->getRouteStats();

// Добавить middleware
router()->middleware('global');

// Получить все маршруты
$all = router()->getRoutes();
```

**Возвращает:** `Router`

---

### 9. dispatch_route() - Dispatch текущего запроса

```php
// Автоматически использует $_SERVER
$route = dispatch_route();

// С кастомными параметрами
$route = dispatch_route('/custom/uri', 'POST');

// С IP
$route = dispatch_route('/api/data', 'GET', '192.168.1.1');
```

**Параметры:**
1. `$uri` - URI (default: $_SERVER['REQUEST_URI'])
2. `$method` - HTTP method (default: $_SERVER['REQUEST_METHOD'])
3. `$clientIp` - Client IP (default: $_SERVER['REMOTE_ADDR'])

---

### 10. route_stats() - Статистика маршрутов

```php
$stats = route_stats();

echo "Total routes: " . $stats['total'];
echo "Named routes: " . $stats['named'];
echo "With middleware: " . $stats['with_middleware'];
echo "Throttled: " . $stats['throttled'];

// По методам
foreach ($stats['by_method'] as $method => $count) {
    echo "{$method}: {$count}\n";
}
```

**Возвращает:** `array` со статистикой

---

### 11. route_back() - URL назад

```php
// Вернуться на предыдущую страницу
$backUrl = route_back();

// С fallback
$backUrl = route_back('/default');

// В HTML
<a href="<?= route_back('/') ?>">← Back</a>
```

---

### 12. routes_by_tag() - Маршруты по тегу

```php
// Получить все API маршруты
$apiRoutes = routes_by_tag('api');

foreach ($apiRoutes as $route) {
    echo $route->getUri() . "\n";
}
```

---

## 📊 Примеры использования

### Navigation Menu

```php
<nav>
    <ul>
        <li class="<?= route_is('home') ? 'active' : '' ?>">
            <a href="<?= route_url('home') ?>">Home</a>
        </li>
        <li class="<?= route_is(['users.*']) ? 'active' : '' ?>">
            <a href="<?= route_url('users.index') ?>">Users</a>
        </li>
        <?php if (route_has('admin.dashboard')): ?>
        <li class="<?= route_is('admin.*') ? 'active' : '' ?>">
            <a href="<?= route_url('admin.dashboard') ?>">Admin</a>
        </li>
        <?php endif; ?>
    </ul>
</nav>
```

### Breadcrumbs

```php
$breadcrumbs = [];
$current = current_route();

while ($current) {
    $breadcrumbs[] = [
        'name' => $current->getName(),
        'url' => route_url($current->getName())
    ];
    $current = previous_route();
}

$breadcrumbs = array_reverse($breadcrumbs);
```

### Conditional Features

```php
// Показать функцию только если маршрут существует
if (route_has('premium.features')) {
    echo '<a href="' . route_url('premium.features') . '">Premium Features</a>';
}

// Разные действия для разных страниц
if (route_is('users.edit')) {
    // Show edit form
} elseif (route_is('users.show')) {
    // Show details
}
```

### Dynamic Navigation

```php
$stats = route_stats();

echo "This app has {$stats['total']} routes:\n";
echo "- API routes: " . count(routes_by_tag('api')) . "\n";
echo "- Admin routes: " . count(routes_by_tag('admin')) . "\n";
echo "- Public routes: " . count(routes_by_tag('public')) . "\n";
```

## 🆚 Сравнение с конкурентами

| Helper | CloudCastle | Laravel | Symfony | Others |
|:---|:---:|:---:|:---:|:---:|
| route() | ✅ | ✅ | ⚠️ | ❌ |
| current_route() | ✅ | ✅ | ⚠️ | ❌ |
| previous_route() | ✅ | ❌ | ❌ | ❌ |
| route_is() | ✅ | ✅ | ⚠️ | ❌ |
| route_name() | ✅ | ✅ | ⚠️ | ❌ |
| route_url() | ✅ | ✅ | ✅ | ⚠️ |
| route_has() | ✅ | ✅ | ⚠️ | ❌ |
| router() | ✅ | ✅ | ✅ | ❌ |
| dispatch_route() | ✅ | ❌ | ❌ | ❌ |
| route_stats() | ✅ | ❌ | ❌ | ❌ |
| route_back() | ✅ | ✅ | ❌ | ❌ |
| routes_by_tag() | ✅ | ❌ | ⚠️ | ❌ |
| **ИТОГО** | **15+** | **8** | **4** | **1-2** |

**CloudCastle предоставляет почти в 2 раза больше helpers чем Laravel!**

## 💡 Best Practices

### 1. Используйте helpers в views

```php
<!-- Вместо -->
<a href="/users/<?= $id ?>">User</a>

<!-- Используйте -->
<a href="<?= route_url('users.show', ['id' => $id]) ?>">User</a>
```

### 2. Проверяйте существование перед использованием

```php
if (route_has('admin.panel')) {
    $url = route_url('admin.panel');
}
```

### 3. Используйте route_is() для активных ссылок

```php
<li class="<?= route_is('dashboard') ? 'active' : '' ?>">
    <a href="<?= route_url('dashboard') ?>">Dashboard</a>
</li>
```

### 4. route_stats() для мониторинга

```php
// В админке
$stats = route_stats();

echo "Application Statistics:\n";
echo "Total routes: {$stats['total']}\n";
echo "API endpoints: " . count(routes_by_tag('api')) . "\n";
```

## ✅ Заключение

Helper functions делают работу с роутером **в 5-10 раз удобнее**:

- ✅ **15+ функций** - самое большое количество
- ✅ **Глобальные** - доступны везде
- ✅ **Удобные** - упрощают код
- ✅ **Type-safe** - возвращают правильные типы

**Уникальные helpers** (только в CloudCastle):
- `previous_route()`
- `dispatch_route()`
- `route_stats()`
- `routes_by_tag()`

---

*Последнее обновление: 18 октября 2025*

---

[📚 Оглавление](_table-of-contents.md) | [🏠 Главная](README.md)

