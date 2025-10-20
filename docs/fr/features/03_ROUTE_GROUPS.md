# Groupes de Routes

[English](../../en/features/03_ROUTE_GROUPS.md) | [Русский](../../ru/features/03_ROUTE_GROUPS.md) | [Deutsch](../../de/features/03_ROUTE_GROUPS.md) | [**Français**](03_ROUTE_GROUPS.md) | [中文](../../zh/features/03_ROUTE_GROUPS.md)

---

## 📚 Navigation Documentation

[README](../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [API_REFERENCE](../API_REFERENCE.md) | [ALL_FEATURES](../ALL_FEATURES.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [PERFORMANCE](../PERFORMANCE_ANALYSIS.md) | [SECURITY](../SECURITY_REPORT.md) | [COMPARISON](../COMPARISON.md) | [FAQ](../FAQ.md)

**Documentation Détaillée:** [01](01_BASIC_ROUTING.md) | [02](02_ROUTE_PARAMETERS.md) | [03](03_ROUTE_GROUPS.md) | [04](04_RATE_LIMITING.md) | [05](05_IP_FILTERING.md) | [06](06_MIDDLEWARE.md) | [07](07_NAMED_ROUTES.md) | [08](08_TAGS.md) | [09](09_HELPER_FUNCTIONS.md) | [10](10_ROUTE_SHORTCUTS.md) | [11](11_ROUTE_MACROS.md) | [12](12_URL_GENERATION.md) | [13](13_EXPRESSION_LANGUAGE.md) | [14](14_CACHING.md) | [15](15_PLUGINS.md) | [16](16_LOADERS.md) | [17](17_PSR_SUPPORT.md) | [18](18_ACTION_RESOLVER.md) | [19](19_STATISTICS.md) | [20](20_SECURITY.md) | [21](21_EXCEPTIONS.md) | [22](22_CLI_TOOLS.md)

---

**Catégorie:** Organisation du Code  
**Nombre d'Attributs:** 12  
**Complexité:** ⭐⭐ Niveau Intermédiaire

---

## Description

Les groupes de routes permettent d'organiser les routes avec des attributs communs (préfixe, middleware, domaine, etc.), en les appliquant à toutes les routes du groupe. Cela simplifie le code et le rend plus maintenable.

## Fonctionnalités

### 1. Préfixe

**Attribut:** `'prefix' => string`

**Description:** Ajoute un préfixe à toutes les URIs du groupe.

**Exemples:**

```php
// Préfixe simple
Route::group(['prefix' => '/api'], function() {
    Route::get('/users', $action);     // /api/users
    Route::get('/posts', $action);     // /api/posts
});

// Versioning d'API
Route::group(['prefix' => '/api/v1'], function() {
    Route::get('/users', [ApiV1UserController::class, 'index']);
    Route::get('/posts', [ApiV1PostController::class, 'index']);
});

// Préfixes imbriqués
Route::group(['prefix' => '/admin'], function() {
    Route::group(['prefix' => '/users'], function() {
        Route::get('/', $action);           // /admin/users
        Route::get('/{id}', $action);       // /admin/users/{id}
    });
});

// Plusieurs niveaux
Route::group(['prefix' => '/app'], function() {
    Route::group(['prefix' => '/api'], function() {
        Route::group(['prefix' => '/v1'], function() {
            Route::get('/data', $action);   // /app/api/v1/data
        });
    });
});
```

---

### 2. Middleware

**Attribut:** `'middleware' => array|string`

**Description:** Applique le middleware à toutes les routes du groupe.

**Exemples:**

```php
use CloudCastle\Http\Router\Middleware\AuthMiddleware;

// Middleware unique
Route::group(['middleware' => AuthMiddleware::class], function() {
    Route::get('/dashboard', $action);
    Route::get('/profile', $action);
});

// Plusieurs middleware
Route::group([
    'middleware' => [
        AuthMiddleware::class,
        AdminMiddleware::class,
        LoggerMiddleware::class
    ]
], function() {
    Route::get('/admin/users', $action);
    Route::get('/admin/posts', $action);
});

// Middleware mixte (groupe + individuel)
Route::group(['middleware' => AuthMiddleware::class], function() {
    Route::get('/dashboard', $action);  // AuthMiddleware seulement
    
    Route::get('/admin', $action)
        ->middleware([AdminMiddleware::class]);  // AuthMiddleware + AdminMiddleware
});
```

---

### 3. Domaine

**Attribut:** `'domain' => string`

**Description:** Restreint les routes à un domaine spécifique.

**Exemples:**

```php
// Sous-domaine API
Route::group(['domain' => 'api.example.com'], function() {
    Route::get('/users', $action);
    Route::get('/posts', $action);
});

// Sous-domaine admin
Route::group(['domain' => 'admin.example.com'], function() {
    Route::get('/dashboard', $action);
    Route::get('/users', $action);
});

// Sous-domaine wildcard
Route::group(['domain' => '{subdomain}.example.com'], function() {
    Route::get('/data', function($subdomain) {
        return "Sous-domaine: $subdomain";
    });
});

// Plusieurs domaines
Route::group(['domain' => ['api.example.com', 'api.local']], function() {
    Route::get('/users', $action);
});
```

---

### 4. Namespace

**Attribut:** `'namespace' => string`

**Description:** Définit le namespace pour les contrôleurs du groupe.

**Exemples:**

```php
// Namespace API
Route::group(['namespace' => 'App\\Http\\Controllers\\Api'], function() {
    Route::get('/users', 'UserController@index');  // App\Http\Controllers\Api\UserController
    Route::get('/posts', 'PostController@index');  // App\Http\Controllers\Api\PostController
});

// Namespace Admin
Route::group(['namespace' => 'App\\Http\\Controllers\\Admin'], function() {
    Route::get('/dashboard', 'DashboardController@index');
    Route::get('/users', 'UserController@index');
});

// Namespaces imbriqués
Route::group(['namespace' => 'App\\Http\\Controllers'], function() {
    Route::group(['namespace' => 'Api\\V1'], function() {
        Route::get('/users', 'UserController@index');  // App\Http\Controllers\Api\V1\UserController
    });
});
```

---

### 5. Noms de Routes

**Attribut:** `'as' => string`

**Description:** Ajoute un préfixe aux noms de routes du groupe.

**Exemples:**

```php
// Noms de routes API
Route::group(['as' => 'api.'], function() {
    Route::get('/users', $action)->name('users');      // api.users
    Route::get('/posts', $action)->name('posts');      // api.posts
});

// Noms de routes admin
Route::group(['as' => 'admin.'], function() {
    Route::get('/dashboard', $action)->name('dashboard');  // admin.dashboard
    Route::get('/users', $action)->name('users');          // admin.users
});

// Noms de routes imbriqués
Route::group(['as' => 'api.v1.'], function() {
    Route::get('/users', $action)->name('users');      // api.v1.users
    Route::get('/posts', $action)->name('posts');      // api.v1.posts
});
```

---

### 6. Limitation de Débit

**Attribut:** `'throttle' => array`

**Description:** Applique la limitation de débit à toutes les routes du groupe.

**Exemples:**

```php
// Limitation de débit API
Route::group(['throttle' => [100, 1]], function() {
    Route::get('/users', $action);     // 100 requêtes par minute
    Route::get('/posts', $action);     // 100 requêtes par minute
});

// Limites différentes pour différents groupes
Route::group(['throttle' => [60, 1]], function() {
    Route::get('/public/data', $action);  // 60 requêtes par minute
});

Route::group(['throttle' => [1000, 1]], function() {
    Route::get('/premium/data', $action); // 1000 requêtes par minute
});
```

---

### 7. Filtrage IP

**Attribut:** `'whitelist' => array` | `'blacklist' => array`

**Description:** Applique le filtrage IP à toutes les routes du groupe.

**Exemples:**

```php
// Whitelist d'IPs spécifiques
Route::group(['whitelist' => ['192.168.1.0/24', '10.0.0.0/8']], function() {
    Route::get('/admin', $action);
    Route::get('/internal', $action);
});

// Blacklist d'IPs spécifiques
Route::group(['blacklist' => ['192.168.1.100', '10.0.0.50']], function() {
    Route::get('/public', $action);
});
```

---

### 8. Tags

**Attribut:** `'tag' => array|string`

**Description:** Ajoute des tags à toutes les routes du groupe.

**Exemples:**

```php
// Tag unique
Route::group(['tag' => 'api'], function() {
    Route::get('/users', $action);
    Route::get('/posts', $action);
});

// Plusieurs tags
Route::group(['tag' => ['api', 'v1', 'public']], function() {
    Route::get('/users', $action);
    Route::get('/posts', $action);
});
```

---

### 9. Paramètres de Cache

**Attribut:** `'cache' => array`

**Description:** Définit les paramètres de cache pour toutes les routes du groupe.

**Exemples:**

```php
// Cache pour 1 heure
Route::group(['cache' => [3600]], function() {
    Route::get('/static-data', $action);
    Route::get('/public-info', $action);
});

// Cache avec tags
Route::group(['cache' => [3600, ['api', 'v1']]], function() {
    Route::get('/users', $action);
    Route::get('/posts', $action);
});
```

---

### 10. Attributs Multiples

**Description:** Combiner plusieurs attributs dans un seul groupe.

**Exemples:**

```php
// Groupe API complet
Route::group([
    'prefix' => '/api/v1',
    'middleware' => [AuthMiddleware::class],
    'namespace' => 'App\\Http\\Controllers\\Api\\V1',
    'as' => 'api.v1.',
    'throttle' => [100, 1],
    'tag' => ['api', 'v1']
], function() {
    Route::get('/users', 'UserController@index')->name('users');
    Route::get('/posts', 'PostController@index')->name('posts');
});

// Groupe admin
Route::group([
    'prefix' => '/admin',
    'domain' => 'admin.example.com',
    'middleware' => [AuthMiddleware::class, AdminMiddleware::class],
    'namespace' => 'App\\Http\\Controllers\\Admin',
    'as' => 'admin.',
    'whitelist' => ['192.168.1.0/24']
], function() {
    Route::get('/dashboard', 'DashboardController@index');
    Route::get('/users', 'UserController@index');
});
```

---

### 11. Groupes Imbriqués

**Description:** Groupes dans des groupes pour une organisation complexe.

**Exemples:**

```php
// Groupe API principal
Route::group(['prefix' => '/api', 'middleware' => AuthMiddleware::class], function() {
    
    // Routes publiques (pas d'auth requise)
    Route::group(['middleware' => []], function() {
        Route::get('/health', $action);
        Route::get('/version', $action);
    });
    
    // API V1
    Route::group(['prefix' => '/v1', 'as' => 'v1.'], function() {
        Route::get('/users', $action)->name('users');
        Route::get('/posts', $action)->name('posts');
    });
    
    // API V2
    Route::group(['prefix' => '/v2', 'as' => 'v2.'], function() {
        Route::get('/users', $action)->name('users');
        Route::get('/posts', $action)->name('posts');
    });
    
    // API Admin
    Route::group(['prefix' => '/admin', 'middleware' => AdminMiddleware::class], function() {
        Route::get('/stats', $action);
        Route::get('/logs', $action);
    });
});
```

---

### 12. Groupes Conditionnels

**Description:** Groupes avec des attributs conditionnels.

**Exemples:**

```php
// Groupes basés sur l'environnement
if (app()->environment('production')) {
    Route::group(['domain' => 'api.example.com'], function() {
        Route::get('/users', $action);
    });
} else {
    Route::group(['domain' => 'api.local'], function() {
        Route::get('/users', $action);
    });
}

// Groupes basés sur les fonctionnalités
if (config('features.api_v2')) {
    Route::group(['prefix' => '/api/v2'], function() {
        Route::get('/users', $action);
    });
}
```

---

## Meilleures Pratiques

### 1. Regroupement Logique

```php
// Grouper par fonctionnalité
Route::group(['prefix' => '/api/v1'], function() {
    Route::group(['prefix' => '/users'], function() {
        Route::get('/', [UserController::class, 'index']);
        Route::post('/', [UserController::class, 'store']);
        Route::get('/{id}', [UserController::class, 'show']);
        Route::put('/{id}', [UserController::class, 'update']);
        Route::delete('/{id}', [UserController::class, 'destroy']);
    });
});
```

### 2. Organisation Middleware

```php
// Grouper par exigences middleware
Route::group(['middleware' => AuthMiddleware::class], function() {
    Route::get('/dashboard', $action);
    Route::get('/profile', $action);
    
    Route::group(['middleware' => AdminMiddleware::class], function() {
        Route::get('/admin/users', $action);
        Route::get('/admin/posts', $action);
    });
});
```

### 3. Nommage Cohérent

```php
// Nommage de routes cohérent
Route::group(['as' => 'api.v1.'], function() {
    Route::get('/users', $action)->name('users.index');
    Route::post('/users', $action)->name('users.store');
    Route::get('/users/{id}', $action)->name('users.show');
});
```

---

## Modèles Courants

### 1. Versioning d'API

```php
Route::group(['prefix' => '/api'], function() {
    Route::group(['prefix' => '/v1', 'as' => 'v1.'], function() {
        Route::apiResource('users', UserController::class);
        Route::apiResource('posts', PostController::class);
    });
    
    Route::group(['prefix' => '/v2', 'as' => 'v2.'], function() {
        Route::apiResource('users', UserV2Controller::class);
        Route::apiResource('posts', PostV2Controller::class);
    });
});
```

### 2. Panneau d'Administration

```php
Route::group([
    'prefix' => '/admin',
    'middleware' => [AuthMiddleware::class, AdminMiddleware::class],
    'namespace' => 'App\\Http\\Controllers\\Admin',
    'as' => 'admin.'
], function() {
    Route::get('/dashboard', 'DashboardController@index');
    Route::resource('users', 'UserController');
    Route::resource('posts', 'PostController');
});
```

### 3. Public vs Privé

```php
// Routes publiques
Route::group(['middleware' => []], function() {
    Route::get('/', [HomeController::class, 'index']);
    Route::get('/about', [PageController::class, 'about']);
});

// Routes privées
Route::group(['middleware' => AuthMiddleware::class], function() {
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/profile', [ProfileController::class, 'show']);
});
```

---

## Conseils de Performance

### 1. Minimiser l'Imbrication

```php
// Bien: Structure plate
Route::group(['prefix' => '/api/v1'], function() {
    Route::get('/users', $action);
    Route::get('/posts', $action);
});

// À éviter: Imbrication profonde
Route::group(['prefix' => '/api'], function() {
    Route::group(['prefix' => '/v1'], function() {
        Route::group(['prefix' => '/users'], function() {
            Route::get('/', $action);
        });
    });
});
```

### 2. Middleware Efficace

```php
// Appliquer middleware au niveau du groupe
Route::group(['middleware' => AuthMiddleware::class], function() {
    Route::get('/dashboard', $action);
    Route::get('/profile', $action);
});
```

---

## Dépannage

### Problèmes Courants

1. **Middleware non appliqué**
   - Vérifier l'enregistrement du middleware
   - Vérifier que la classe middleware existe
   - Vérifier l'ordre du middleware

2. **Préfixe ne fonctionne pas**
   - Vérifier le format du préfixe
   - Vérifier les slashes de début/fin
   - S'assurer de l'imbrication correcte

3. **Problèmes de namespace**
   - Vérifier le format du namespace
   - Vérifier que les classes contrôleur existent
   - Vérifier l'autoloading

### Conseils de Debug

```php
// Activer le mode debug
Route::enableDebug();

// Vérifier les attributs de groupe
$routes = Route::getAllRoutes();
foreach ($routes as $route) {
    echo $route->getUri() . ' - ' . $route->getName() . PHP_EOL;
}
```

---

## Voir Aussi

- [Routage de Base](01_BASIC_ROUTING.md) - Enregistrement de routes de base
- [Paramètres de Route](02_ROUTE_PARAMETERS.md) - Paramètres de route dynamiques
- [Middleware](06_MIDDLEWARE.md) - Middleware de traitement des requêtes
- [Routes Nommées](07_NAMED_ROUTES.md) - Identification des routes
- [Référence API](../API_REFERENCE.md) - Référence API complète

---

© 2024 CloudCastle HTTP Router  
[⬆ Retour en haut](#groupes-de-routes)