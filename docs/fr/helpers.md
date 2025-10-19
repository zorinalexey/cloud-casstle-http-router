[🇷🇺 Русский](ru/helpers.md) | [🇺🇸 English](en/helpers.md) | [🇩🇪 Deutsch](de/helpers.md) | [🇫🇷 Français](fr/helpers.md) | [🇨🇳 中文](zh/helpers.md)

[📚 Table of Contents](fr/_table-of-contents.md) | [🏠 Home](fr/README.md)

---

# Fonctions d'assistance - Fonctions d'assistance globales

**Langues :** 🇷🇺 Russe | [🇫🇷 Anglais](../en/helpers.md) | [🇩🇪 Deutsch](../de/helpers.md) | [🇫🇷 Français](../fr/helpers.md) | [🇨🇳中文](../zh/helpers.md)

[📚 Table des matières](_table-of-contents.md) | [🏠 Accueil](LISEZMOI.md)

---

## 📚 Bilan

CloudCastle HTTP Router fournit **plus de 15 fonctions d'assistance globales** pour faciliter l'utilisation des routes.

## 🎯 Aides de base

### 1. route() - Obtenir un itinéraire

```php
// Получить маршрут по имени
$route = route('users.show');

// Получить текущий маршрут (без параметров)
$current = route();
```

**Renvoie :** `Route|null`

---

### 2. current_route() - Itinéraire actuel

```php
$current = current_route();

if ($current) {
    echo "Current: " . $current->getName();
    echo "URI: " . $current->getUri();
}
```

**Renvoie :** `Route|null`

---

### 3. previous_route() - Itinéraire précédent

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

### 4. route_is() - Vérification de l'itinéraire actuel

```php
if (route_is('users.index')) {
    echo "On users listing page";
}

if (route_is(['users.index', 'users.show'])) {
    echo "On any users page";
}
```

**Paramètres :** `string|array`
**Renvoie :** `bool`

**Utilisation dans les vues :**
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

### 5. route_name() - Nom de l'itinéraire actuel

```php
$name = route_name();
// "users.index"

echo "Current page: " . route_name();
```

**Renvoie :** `string|null`

---

### 6. route_url() - Génération d'URL

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

**Paramètres :**
1. `$name` - nom de la route
2. `$parameters` - paramètres de route
3. `$queryParams` - paramètres de requête

---

### 7. route_has() - Vérification de l'existence

```php
if (route_has('admin.dashboard')) {
    echo "Admin route exists";
}

if (!route_has('non.existent')) {
    echo "Route doesn't exist";
}
```

**Renvoie :** `bool`

**Application:**
- Feature detection
- Conditional navigation
- Module checks

---

### 8. router() - Instance de routeur

```php
$router = router();

// Получить статистику
$stats = router()->getRouteStats();

// Добавить middleware
router()->middleware('global');

// Получить все маршруты
$all = router()->getRoutes();
```

**Renvoie :** « Routeur »

---

### 9. dispatch_route() - Distribue la requête en cours

```php
// Автоматически использует $_SERVER
$route = dispatch_route();

// С кастомными параметрами
$route = dispatch_route('/custom/uri', 'POST');

// С IP
$route = dispatch_route('/api/data', 'GET', '192.168.1.1');
```

**Paramètres :**
1. `$uri` - URI (default: $_SERVER['REQUEST_URI'])
2. `$method` - HTTP method (default: $_SERVER['REQUEST_METHOD'])
3. `$clientIp` - Client IP (default: $_SERVER['REMOTE_ADDR'])

---

### 10. route_stats() - Statistiques d'itinéraire

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

**Renvoie :** `tableau` avec statistiques

---

### 11. route_back() - URL de retour

```php
// Вернуться на предыдущую страницу
$backUrl = route_back();

// С fallback
$backUrl = route_back('/default');

// В HTML
<a href="<?= route_back('/') ?>">← Back</a>
```

---

### 12. routes_by_tag() - Itinéraires par tag

```php
// Получить все API маршруты
$apiRoutes = routes_by_tag('api');

foreach ($apiRoutes as $route) {
    echo $route->getUri() . "\n";
}
```

---

## 📊 Exemples d'utilisation

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

## 🆚 Comparaison avec les concurrents

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

**CloudCastle fournit presque 2 fois plus d'aides que Laravel !**

## 💡 Best Practices

### 1. Utiliser des assistants dans les vues

```php
<!-- Вместо -->
<a href="/users/<?= $id ?>">User</a>

<!-- Используйте -->
<a href="<?= route_url('users.show', ['id' => $id]) ?>">User</a>
```

### 2. Vérifiez l'existence avant utilisation

```php
if (route_has('admin.panel')) {
    $url = route_url('admin.panel');
}
```

### 3. Utilisez route_is() pour les liens actifs

```php
<li class="<?= route_is('dashboard') ? 'active' : '' ?>">
    <a href="<?= route_url('dashboard') ?>">Dashboard</a>
</li>
```

### 4. route_stats() pour la surveillance

```php
// В админке
$stats = route_stats();

echo "Application Statistics:\n";
echo "Total routes: {$stats['total']}\n";
echo "API endpoints: " . count(routes_by_tag('api')) . "\n";
```

## ✅Conclusion

Les fonctions d'assistance rendent le travail avec le routeur **5 à 10 fois plus pratique** :

- ✅ **15+ fonctions** - le plus grand nombre
- ✅ **Global** - disponible partout
- ✅ **Pratique** - simplifiez le code
- ✅ **Type-safe** - renvoie les types corrects

**Aide uniques** (uniquement dans CloudCastle) :
- `previous_route()`
- `dispatch_route()`
- `route_stats()`
- `routes_by_tag()`

---

*Dernière mise à jour : 18 octobre 2025*

---

[📚 Table des matières](_table-of-contents.md) | [🏠 Accueil](LISEZMOI.md)

---

[📚 Table of Contents](fr/_table-of-contents.md) | [🏠 Home](fr/README.md)
