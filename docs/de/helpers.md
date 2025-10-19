[🇷🇺 Русский](ru/helpers.md) | [🇺🇸 English](en/helpers.md) | [🇩🇪 Deutsch](de/helpers.md) | [🇫🇷 Français](fr/helpers.md) | [🇨🇳 中文](zh/helpers.md)

[📚 Table of Contents](de/_table-of-contents.md) | [🏠 Home](de/README.md)

---

# Hilfsfunktionen – Globale Hilfsfunktionen

**Sprachen:** 🇷🇺 Russisch | [🇬🇧 Englisch](../en/helpers.md) | [🇩🇪 Deutsch](../de/helpers.md) | [🇫🇷 Français](../fr/helpers.md) | [🇨🇳中文](../zh/helpers.md)

[📚 Inhaltsverzeichnis](_table-of-contents.md) | [🏠 Startseite](README.md)

---

## 📚 Rezension

Der CloudCastle HTTP Router bietet **15+ globale Hilfsfunktionen**, um die Arbeit mit Routen zu erleichtern.

## 🎯 Grundlegende Helfer

### 1. route() – Eine Route abrufen

```php
// Получить маршрут по имени
$route = route('users.show');

// Получить текущий маршрут (без параметров)
$current = route();
```

**Rückgabe:** `Route|null`

---

### 2. current_route() – Aktuelle Route

```php
$current = current_route();

if ($current) {
    echo "Current: " . $current->getName();
    echo "URI: " . $current->getUri();
}
```

**Rückgabe:** `Route|null`

---

### 3. previous_route() – Vorherige Route

```php
$previous = previous_route();

if ($previous) {
    echo "Previous: " . $previous->getName();
}
```

**Anwendung:**
- Breadcrumbs
- Navigation
- "Back" buttons

---

### 4. route_is() – Überprüfen der aktuellen Route

```php
if (route_is('users.index')) {
    echo "On users listing page";
}

if (route_is(['users.index', 'users.show'])) {
    echo "On any users page";
}
```

**Parameter:** `string|array`
**Rückgabe:** `bool`

**Verwendung in Ansichten:**
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

### 5. route_name() – Name der aktuellen Route

```php
$name = route_name();
// "users.index"

echo "Current page: " . route_name();
```

**Rückgabe:** `string|null`

---

### 6. route_url() – URL-Generierung

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

**Parameter:**
1. „$name“ – Routenname
2. „$parameters“ – Routenparameter
3. „$queryParams“ – Abfrageparameter

---

### 7. route_has() – Existenzprüfung

```php
if (route_has('admin.dashboard')) {
    echo "Admin route exists";
}

if (!route_has('non.existent')) {
    echo "Route doesn't exist";
}
```

**Rückgabe:** `bool`

**Anwendung:**
- Feature detection
- Conditional navigation
- Module checks

---

### 8. router() – Router-Instanz

```php
$router = router();

// Получить статистику
$stats = router()->getRouteStats();

// Добавить middleware
router()->middleware('global');

// Получить все маршруты
$all = router()->getRoutes();
```

**Rückgabe:** `Router`

---

### 9. Dispatch_route() – Versenden Sie die aktuelle Anfrage

```php
// Автоматически использует $_SERVER
$route = dispatch_route();

// С кастомными параметрами
$route = dispatch_route('/custom/uri', 'POST');

// С IP
$route = dispatch_route('/api/data', 'GET', '192.168.1.1');
```

**Parameter:**
1. `$uri` - URI (default: $_SERVER['REQUEST_URI'])
2. `$method` - HTTP method (default: $_SERVER['REQUEST_METHOD'])
3. `$clientIp` - Client IP (default: $_SERVER['REMOTE_ADDR'])

---

### 10. route_stats() – Routenstatistik

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

**Rückgabe:** „Array“ mit Statistiken

---

### 11. route_back() – URL zurück

```php
// Вернуться на предыдущую страницу
$backUrl = route_back();

// С fallback
$backUrl = route_back('/default');

// В HTML
<a href="<?= route_back('/') ?>">← Back</a>
```

---

### 12. Routen_by_tag() – Routen nach Tag

```php
// Получить все API маршруты
$apiRoutes = routes_by_tag('api');

foreach ($apiRoutes as $route) {
    echo $route->getUri() . "\n";
}
```

---

## 📊 Anwendungsbeispiele

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

## 🆚 Vergleich mit Mitbewerbern

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
| **GESAMT** | **15+** | **8** | **4** | **1-2** |

**CloudCastle bietet fast zweimal mehr Helfer als Laravel!**

## 💡 Best Practices

### 1. Verwenden Sie Helfer in Ansichten

```php
<!-- Вместо -->
<a href="/users/<?= $id ?>">User</a>

<!-- Используйте -->
<a href="<?= route_url('users.show', ['id' => $id]) ?>">User</a>
```

### 2. Überprüfen Sie vor der Verwendung die Existenz

```php
if (route_has('admin.panel')) {
    $url = route_url('admin.panel');
}
```

### 3. Verwenden Sie route_is() für aktive Links

```php
<li class="<?= route_is('dashboard') ? 'active' : '' ?>">
    <a href="<?= route_url('dashboard') ?>">Dashboard</a>
</li>
```

### 4. route_stats() zur Überwachung

```php
// В админке
$stats = route_stats();

echo "Application Statistics:\n";
echo "Total routes: {$stats['total']}\n";
echo "API endpoints: " . count(routes_by_tag('api')) . "\n";
```

## ✅ Fazit

Hilfsfunktionen machen die Arbeit mit dem Router **5-10-mal komfortabler**:

- ✅ **15+ Funktionen** – die größte Anzahl
- ✅ **Global** – überall verfügbar
- ✅ **Praktisch** – Vereinfachen Sie den Code
- ✅ **Typsicher** – die richtigen Typen zurückgeben

**Einzigartige Helfer** (nur in CloudCastle):
- `previous_route()`
- `dispatch_route()`
- `route_stats()`
- `routes_by_tag()`

---

*Letzte Aktualisierung: 18. Oktober 2025*

---

[📚 Inhaltsverzeichnis](_table-of-contents.md) | [🏠 Startseite](README.md)

---

[📚 Table of Contents](de/_table-of-contents.md) | [🏠 Home](de/README.md)
