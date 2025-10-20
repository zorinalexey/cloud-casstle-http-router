# Routen-Tags

[English](../../en/features/08_TAGS.md) | [Русский](../../ru/features/08_TAGS.md) | [**Deutsch**](08_TAGS.md) | [Français](../../fr/features/08_TAGS.md) | [中文](../../zh/features/08_TAGS.md)

---

## 📚 Dokumentationsnavigation

[README](../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [API_REFERENCE](../API_REFERENCE.md) | [ALL_FEATURES](../ALL_FEATURES.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [PERFORMANCE](../PERFORMANCE_ANALYSIS.md) | [SECURITY](../SECURITY_REPORT.md) | [COMPARISON](../COMPARISON.md) | [FAQ](../FAQ.md)

**Detaillierte Dokumentation:** [01](01_BASIC_ROUTING.md) | [02](02_ROUTE_PARAMETERS.md) | [03](03_ROUTE_GROUPS.md) | [04](04_RATE_LIMITING.md) | [05](05_IP_FILTERING.md) | [06](06_MIDDLEWARE.md) | [07](07_NAMED_ROUTES.md) | [08](08_TAGS.md) | [09](09_HELPER_FUNCTIONS.md) | [10](10_ROUTE_SHORTCUTS.md) | [11](11_ROUTE_MACROS.md) | [12](12_URL_GENERATION.md) | [13](13_EXPRESSION_LANGUAGE.md) | [14](14_CACHING.md) | [15](15_PLUGINS.md) | [16](16_LOADERS.md) | [17](17_PSR_SUPPORT.md) | [18](18_ACTION_RESOLVER.md) | [19](19_STATISTICS.md) | [20](20_SECURITY.md) | [21](21_EXCEPTIONS.md) | [22](22_CLI_TOOLS.md)

---

**Kategorie:** Code-Organisation  
**Anzahl Methoden:** 5  
**Komplexität:** ⭐ Anfänger-Level

---

## Methoden

### 1. tag()

```php
// Einzelner Tag
Route::get('/api/users', $action)->tag('api');

// Mehrere Tags
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
    echo "Hat API-Routen";
}
```

### 4. getAllTags()

```php
$allTags = Route::router()->getAllTags();
// ['api', 'public', 'admin', 'protected']
```

### 5. getTags() (auf Route)

```php
$route = Route::current();
$tags = $route->getTags();
// ['api', 'public']
```

## Verwendung

### Routen-Organisation

```php
Route::group(['tag' => 'api'], function() {
    Route::get('/users', $action);
    Route::get('/posts', $action);
});

// Mehrere Tags
Route::group(['tag' => ['api', 'v1', 'public']], function() {
    Route::get('/data', $action);
});
```

### Dokumentations-Generierung

```php
$apiRoutes = Route::getRoutesByTag('api');

foreach ($apiRoutes as $route) {
    echo "Endpunkt: " . $route->getUri() . "\n";
    echo "Methoden: " . implode(', ', $route->getMethods()) . "\n";
}
```

### Cache-Verwaltung

```php
// Cache für getaggte Routen löschen
$apiRoutes = Route::getRoutesByTag('api');
foreach ($apiRoutes as $route) {
    Cache::forget($route->getName());
}
```

## Best Practices

```php
// Nach Funktionalität organisieren
Route::get('/api/users', $action)->tag(['api', 'users']);
Route::get('/api/posts', $action)->tag(['api', 'posts']);
Route::get('/admin/users', $action)->tag(['admin', 'users']);

// Mit anderen Features kombinieren
Route::get('/api/data', $action)
    ->tag('api')
    ->name('api.data')
    ->throttle(100, 1);
```

## Siehe auch

- [Benannte Routen](07_NAMED_ROUTES.md) - Routen-Benennung
- [Routen-Gruppen](03_ROUTE_GROUPS.md) - Routen-Organisation
- [API-Referenz](../API_REFERENCE.md) - Vollständige API-Referenz

---

© 2024 CloudCastle HTTP Router  
[⬆ Nach oben](#routen-tags)