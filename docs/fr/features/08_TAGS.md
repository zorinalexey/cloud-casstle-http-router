# Tags de Routes

[English](../../en/features/08_TAGS.md) | [Русский](../../ru/features/08_TAGS.md) | [Deutsch](../../de/features/08_TAGS.md) | [**Français**](08_TAGS.md) | [中文](../../zh/features/08_TAGS.md)

---

## 📚 Navigation Documentation

[README](../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [API_REFERENCE](../API_REFERENCE.md) | [ALL_FEATURES](../ALL_FEATURES.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [PERFORMANCE](../PERFORMANCE_ANALYSIS.md) | [SECURITY](../SECURITY_REPORT.md) | [COMPARISON](../COMPARISON.md) | [FAQ](../FAQ.md)

**Documentation Détaillée:** [01](01_BASIC_ROUTING.md) | [02](02_ROUTE_PARAMETERS.md) | [03](03_ROUTE_GROUPS.md) | [04](04_RATE_LIMITING.md) | [05](05_IP_FILTERING.md) | [06](06_MIDDLEWARE.md) | [07](07_NAMED_ROUTES.md) | [08](08_TAGS.md) | [09](09_HELPER_FUNCTIONS.md) | [10](10_ROUTE_SHORTCUTS.md) | [11](11_ROUTE_MACROS.md) | [12](12_URL_GENERATION.md) | [13](13_EXPRESSION_LANGUAGE.md) | [14](14_CACHING.md) | [15](15_PLUGINS.md) | [16](16_LOADERS.md) | [17](17_PSR_SUPPORT.md) | [18](18_ACTION_RESOLVER.md) | [19](19_STATISTICS.md) | [20](20_SECURITY.md) | [21](21_EXCEPTIONS.md) | [22](22_CLI_TOOLS.md)

---

**Catégorie:** Organisation du Code  
**Nombre de Méthodes:** 5  
**Complexité:** ⭐ Niveau Débutant

---

## Méthodes

### 1. tag()

```php
// Tag unique
Route::get('/api/users', $action)->tag('api');

// Tags multiples
Route::get('/api/public/posts', $action)->tag(['api', 'public']);
```

### 2. getRoutesByTag()

```php
$apiRoutes = Route::getRoutesByTag('api');

foreach ($apiRoutes as $route) {
    echo $route->getUri() . "\n";
}
```

### 3. hasTag()

```php
if (Route::router()->hasTag('api')) {
    echo "A des routes API";
}
```

### 4. getAllTags()

```php
$allTags = Route::router()->getAllTags();
// ['api', 'public', 'admin', 'protected']
```

### 5. getTags() (sur Route)

```php
$route = Route::current();
$tags = $route->getTags();
// ['api', 'public']
```

## Utilisation

### Organisation des Routes

```php
Route::group(['tag' => 'api'], function() {
    Route::get('/users', $action);
    Route::get('/posts', $action);
});

// Tags multiples
Route::group(['tag' => ['api', 'v1', 'public']], function() {
    Route::get('/data', $action);
});
```

### Génération de Documentation

```php
$apiRoutes = Route::getRoutesByTag('api');

foreach ($apiRoutes as $route) {
    echo "Point de terminaison: " . $route->getUri() . "\n";
    echo "Méthodes: " . implode(', ', $route->getMethods()) . "\n";
}
```

### Gestion du Cache

```php
// Effacer le cache pour routes taguées
$apiRoutes = Route::getRoutesByTag('api');
foreach ($apiRoutes as $route) {
    Cache::forget($route->getName());
}
```

## Meilleures Pratiques

```php
// Organiser par fonctionnalité
Route::get('/api/users', $action)->tag(['api', 'users']);
Route::get('/api/posts', $action)->tag(['api', 'posts']);
Route::get('/admin/users', $action)->tag(['admin', 'users']);

// Combiner avec autres fonctionnalités
Route::get('/api/data', $action)
    ->tag('api')
    ->name('api.data')
    ->throttle(100, 1);
```

## Voir Aussi

- [Routes Nommées](07_NAMED_ROUTES.md) - Nommage des routes
- [Groupes de Routes](03_ROUTE_GROUPS.md) - Organisation des routes
- [Référence API](../API_REFERENCE.md) - Référence API complète

---

© 2024 CloudCastle HTTP Router  
[⬆ Retour en haut](#tags-de-routes)