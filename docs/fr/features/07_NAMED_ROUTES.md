# Routes Nommées

[English](../../en/features/07_NAMED_ROUTES.md) | [Русский](../../ru/features/07_NAMED_ROUTES.md) | [Deutsch](../../de/features/07_NAMED_ROUTES.md) | [**Français**](07_NAMED_ROUTES.md) | [中文](../../zh/features/07_NAMED_ROUTES.md)

---

## 📚 Navigation Documentation

[README](../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [API_REFERENCE](../API_REFERENCE.md) | [ALL_FEATURES](../ALL_FEATURES.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [PERFORMANCE](../PERFORMANCE_ANALYSIS.md) | [SECURITY](../SECURITY_REPORT.md) | [COMPARISON](../COMPARISON.md) | [FAQ](../FAQ.md)

**Documentation Détaillée:** [01](01_BASIC_ROUTING.md) | [02](02_ROUTE_PARAMETERS.md) | [03](03_ROUTE_GROUPS.md) | [04](04_RATE_LIMITING.md) | [05](05_IP_FILTERING.md) | [06](06_MIDDLEWARE.md) | [07](07_NAMED_ROUTES.md) | [08](08_TAGS.md) | [09](09_HELPER_FUNCTIONS.md) | [10](10_ROUTE_SHORTCUTS.md) | [11](11_ROUTE_MACROS.md) | [12](12_URL_GENERATION.md) | [13](13_EXPRESSION_LANGUAGE.md) | [14](14_CACHING.md) | [15](15_PLUGINS.md) | [16](16_LOADERS.md) | [17](17_PSR_SUPPORT.md) | [18](18_ACTION_RESOLVER.md) | [19](19_STATISTICS.md) | [20](20_SECURITY.md) | [21](21_EXCEPTIONS.md) | [22](22_CLI_TOOLS.md)

---

**Catégorie:** Organisation du Code  
**Nombre de Méthodes:** 6  
**Complexité:** ⭐ Niveau Débutant

---

## Méthodes

### 1. name()

```php
Route::get('/users/{id}', $action)->name('users.show');
Route::post('/users', $action)->name('users.store');
```

### 2. getRouteByName()

```php
$route = Route::getRouteByName('users.show');
```

### 3. currentRouteName()

```php
$name = Route::currentRouteName();
// 'users.show'
```

### 4. currentRouteNamed()

```php
if (Route::currentRouteNamed('users.show')) {
    echo "Affichage utilisateur";
}
```

### 5. enableAutoNaming()

```php
Route::enableAutoNaming();

Route::get('/users', $action);       // auto: users.get
Route::post('/users', $action);      // auto: users.post
Route::get('/users/{id}', $action);  // auto: users.id.get
```

### 6. getNamedRoutes()

```php
$namedRoutes = Route::getNamedRoutes();
// ['users.show' => Route, 'users.store' => Route, ...]
```

## Nommage Automatique

Format: `{uri}.{method}`

Exemples:
- `/users` + GET → `users.get`
- `/users/{id}` + GET → `users.id.get`
- `/api/posts` + POST → `api.posts.post`

## Meilleures Pratiques

```php
// Convention de nommage RESTful
Route::get('/users', $action)->name('users.index');
Route::post('/users', $action)->name('users.store');
Route::get('/users/{id}', $action)->name('users.show');
Route::put('/users/{id}', $action)->name('users.update');
Route::delete('/users/{id}', $action)->name('users.destroy');

// Préfixe de groupe
Route::group(['as' => 'admin.'], function() {
    Route::get('/dashboard', $action)->name('dashboard'); // admin.dashboard
    Route::get('/users', $action)->name('users');         // admin.users
});
```

## Voir Aussi

- [Génération d'URL](12_URL_GENERATION.md) - Générer des URLs depuis routes nommées
- [Fonctions Helper](09_HELPER_FUNCTIONS.md) - Fonction helper route()
- [Référence API](../API_REFERENCE.md) - Référence API complète

---

© 2024 CloudCastle HTTP Router  
[⬆ Retour en haut](#routes-nommées)