[🇷🇺 Русский](ru/helpers.md) | [🇺🇸 English](en/helpers.md) | [🇩🇪 Deutsch](de/helpers.md) | [🇫🇷 Français](fr/helpers.md) | [🇨🇳 中文](zh/helpers.md)

[📚 Table of Contents](en/_table-of-contents.md) | [🏠 Home](en/README.md)

---

# Helper Functions - Global helper functions

**Languages:** 🇷🇺 Russian | [🇬🇧 English](../en/helpers.md) | [🇩🇪 Deutsch](../de/helpers.md) | [🇫🇷 Français](../fr/helpers.md) | [🇨🇳中文](../zh/helpers.md)

[📚 Table of Contents](_table-of-contents.md) | [🏠 Home](README.md)

---

## 📚 Review

CloudCastle HTTP Router provides **15+ global helper functions** to make working with routes easier.

## 🎯 Basic Helpers

### 1. route() - Getting a route

```php
// Получить маршрут по имени
$route = route('users.show');

// Получить текущий маршрут (без параметров)
$current = route();
```

**Returns:** `Route|null`

---

### 2. current_route() - Current route

```php
$current = current_route();

if ($current) {
    echo "Current: " . $current->getName();
    echo "URI: " . $current->getUri();
}
```

**Returns:** `Route|null`

---

### 3. previous_route() - Previous route

```php
$previous = previous_route();

if ($previous) {
    echo "Previous: " . $previous->getName();
}
```

**Application:**
- Breadcrumbs
- Navigation
- "Back" buttons

---

### 4. route_is() - Checking the current route

```php
if (route_is('users.index')) {
    echo "On users listing page";
}

if (route_is(['users.index', 'users.show'])) {
    echo "On any users page";
}
```

**Parameters:** `string|array`
**Returns:** `bool`

**Use in views:**
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

### 5. route_name() - Name of the current route

```php
$name = route_name();
// "users.index"

echo "Current page: " . route_name();
```

**Returns:** `string|null`

---

### 6. route_url() - URL generation

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

**Parameters:**
1. `$name` - route name
2. `$parameters` - route parameters
3. `$queryParams` - query parameters

---

### 7. route_has() - Existence check

```php
if (route_has('admin.dashboard')) {
    echo "Admin route exists";
}

if (!route_has('non.existent')) {
    echo "Route doesn't exist";
}
```

**Returns:** `bool`

**Application:**
- Feature detection
- Conditional navigation
- Module checks

---

### 8. router() - Router instance

```php
$router = router();

// Получить статистику
$stats = router()->getRouteStats();

// Добавить middleware
router()->middleware('global');

// Получить все маршруты
$all = router()->getRoutes();
```

**Returns:** `Router`

---

### 9. dispatch_route() - Dispatch the current request

```php
// Автоматически использует $_SERVER
$route = dispatch_route();

// С кастомными параметрами
$route = dispatch_route('/custom/uri', 'POST');

// С IP
$route = dispatch_route('/api/data', 'GET', '192.168.1.1');
```

**Parameters:**
1. `$uri` - URI (default: $_SERVER['REQUEST_URI'])
2. `$method` - HTTP method (default: $_SERVER['REQUEST_METHOD'])
3. `$clientIp` - Client IP (default: $_SERVER['REMOTE_ADDR'])

---

### 10. route_stats() - Route statistics

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

**Returns:** `array` with statistics

---

### 11. route_back() - URL back

```php
// Вернуться на предыдущую страницу
$backUrl = route_back();

// С fallback
$backUrl = route_back('/default');

// В HTML
<a href="<?= route_back('/') ?>">← Back</a>
```

---

### 12. routes_by_tag() - Routes by tag

```php
// Получить все API маршруты
$apiRoutes = routes_by_tag('api');

foreach ($apiRoutes as $route) {
    echo $route->getUri() . "\n";
}
```

---

## 📊 Examples of use

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

## 🆚 Comparison with competitors

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
| **TOTAL** | **15+** | **8** | **4** | **1-2** |

**CloudCastle provides almost 2 times more helpers than Laravel!**

## 💡 Best Practices

### 1. Use helpers in views

```php
<!-- Вместо -->
<a href="/users/<?= $id ?>">User</a>

<!-- Используйте -->
<a href="<?= route_url('users.show', ['id' => $id]) ?>">User</a>
```

### 2. Check existence before use

```php
if (route_has('admin.panel')) {
    $url = route_url('admin.panel');
}
```

### 3. Use route_is() for active links

```php
<li class="<?= route_is('dashboard') ? 'active' : '' ?>">
    <a href="<?= route_url('dashboard') ?>">Dashboard</a>
</li>
```

### 4. route_stats() for monitoring

```php
// В админке
$stats = route_stats();

echo "Application Statistics:\n";
echo "Total routes: {$stats['total']}\n";
echo "API endpoints: " . count(routes_by_tag('api')) . "\n";
```

## ✅ Conclusion

Helper functions make working with the router **5-10 times more convenient**:

- ✅ **15+ functions** - the largest number
- ✅ **Global** - available everywhere
- ✅ **Convenient** - simplify the code
- ✅ **Type-safe** - return the correct types

**Unique helpers** (only in CloudCastle):
- `previous_route()`
- `dispatch_route()`
- `route_stats()`
- `routes_by_tag()`

---

*Last update: October 18, 2025*

---

[📚 Table of Contents](_table-of-contents.md) | [🏠 Home](README.md)

---

[📚 Table of Contents](en/_table-of-contents.md) | [🏠 Home](en/README.md)
