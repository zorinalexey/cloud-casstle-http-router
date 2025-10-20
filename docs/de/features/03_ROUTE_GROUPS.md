# Routen-Gruppen

[English](../../en/features/03_ROUTE_GROUPS.md) | [Русский](../../ru/features/03_ROUTE_GROUPS.md) | [**Deutsch**](03_ROUTE_GROUPS.md) | [Français](../../fr/features/03_ROUTE_GROUPS.md) | [中文](../../zh/features/03_ROUTE_GROUPS.md)

---

## 📚 Dokumentationsnavigation

[README](../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [API_REFERENCE](../API_REFERENCE.md) | [ALL_FEATURES](../ALL_FEATURES.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [PERFORMANCE](../PERFORMANCE_ANALYSIS.md) | [SECURITY](../SECURITY_REPORT.md) | [COMPARISON](../COMPARISON.md) | [FAQ](../FAQ.md)

**Detaillierte Dokumentation:** [01](01_BASIC_ROUTING.md) | [02](02_ROUTE_PARAMETERS.md) | [03](03_ROUTE_GROUPS.md) | [04](04_RATE_LIMITING.md) | [05](05_IP_FILTERING.md) | [06](06_MIDDLEWARE.md) | [07](07_NAMED_ROUTES.md) | [08](08_TAGS.md) | [09](09_HELPER_FUNCTIONS.md) | [10](10_ROUTE_SHORTCUTS.md) | [11](11_ROUTE_MACROS.md) | [12](12_URL_GENERATION.md) | [13](13_EXPRESSION_LANGUAGE.md) | [14](14_CACHING.md) | [15](15_PLUGINS.md) | [16](16_LOADERS.md) | [17](17_PSR_SUPPORT.md) | [18](18_ACTION_RESOLVER.md) | [19](19_STATISTICS.md) | [20](20_SECURITY.md) | [21](21_EXCEPTIONS.md) | [22](22_CLI_TOOLS.md)

---

**Kategorie:** Code-Organisation  
**Anzahl Attribute:** 12  
**Komplexität:** ⭐⭐ Mittelstufe

---

## Beschreibung

Routen-Gruppen ermöglichen es, Routen mit gemeinsamen Attributen (Präfix, Middleware, Domain, etc.) zu organisieren und sie auf alle Routen in der Gruppe anzuwenden. Dies vereinfacht den Code und macht ihn wartbarer.

## Funktionen

### 1. Präfix

**Attribut:** `'prefix' => string`

**Beschreibung:** Fügt allen URIs in der Gruppe ein Präfix hinzu.

**Beispiele:**

```php
// Einfaches Präfix
Route::group(['prefix' => '/api'], function() {
    Route::get('/users', $action);     // /api/users
    Route::get('/posts', $action);     // /api/posts
});

// API-Versionierung
Route::group(['prefix' => '/api/v1'], function() {
    Route::get('/users', [ApiV1UserController::class, 'index']);
    Route::get('/posts', [ApiV1PostController::class, 'index']);
});

// Verschachtelte Präfixe
Route::group(['prefix' => '/admin'], function() {
    Route::group(['prefix' => '/users'], function() {
        Route::get('/', $action);           // /admin/users
        Route::get('/{id}', $action);       // /admin/users/{id}
    });
});

// Mehrere Ebenen
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

**Beschreibung:** Wendet Middleware auf alle Routen in der Gruppe an.

**Beispiele:**

```php
use CloudCastle\Http\Router\Middleware\AuthMiddleware;

// Einzelne Middleware
Route::group(['middleware' => AuthMiddleware::class], function() {
    Route::get('/dashboard', $action);
    Route::get('/profile', $action);
});

// Mehrere Middleware
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

// Gemischte Middleware (Gruppe + individuell)
Route::group(['middleware' => AuthMiddleware::class], function() {
    Route::get('/dashboard', $action);  // Nur AuthMiddleware
    
    Route::get('/admin', $action)
        ->middleware([AdminMiddleware::class]);  // AuthMiddleware + AdminMiddleware
});
```

---

### 3. Domain

**Attribut:** `'domain' => string`

**Beschreibung:** Beschränkt Routen auf eine bestimmte Domain.

**Beispiele:**

```php
// API-Subdomain
Route::group(['domain' => 'api.example.com'], function() {
    Route::get('/users', $action);
    Route::get('/posts', $action);
});

// Admin-Subdomain
Route::group(['domain' => 'admin.example.com'], function() {
    Route::get('/dashboard', $action);
    Route::get('/users', $action);
});

// Wildcard-Subdomain
Route::group(['domain' => '{subdomain}.example.com'], function() {
    Route::get('/data', function($subdomain) {
        return "Subdomain: $subdomain";
    });
});

// Mehrere Domains
Route::group(['domain' => ['api.example.com', 'api.local']], function() {
    Route::get('/users', $action);
});
```

---

### 4. Namespace

**Attribut:** `'namespace' => string`

**Beschreibung:** Setzt den Namespace für Controller in der Gruppe.

**Beispiele:**

```php
// API-Namespace
Route::group(['namespace' => 'App\\Http\\Controllers\\Api'], function() {
    Route::get('/users', 'UserController@index');  // App\Http\Controllers\Api\UserController
    Route::get('/posts', 'PostController@index');  // App\Http\Controllers\Api\PostController
});

// Admin-Namespace
Route::group(['namespace' => 'App\\Http\\Controllers\\Admin'], function() {
    Route::get('/dashboard', 'DashboardController@index');
    Route::get('/users', 'UserController@index');
});

// Verschachtelte Namespaces
Route::group(['namespace' => 'App\\Http\\Controllers'], function() {
    Route::group(['namespace' => 'Api\\V1'], function() {
        Route::get('/users', 'UserController@index');  // App\Http\Controllers\Api\V1\UserController
    });
});
```

---

### 5. Routen-Namen

**Attribut:** `'as' => string`

**Beschreibung:** Fügt allen Routen-Namen in der Gruppe ein Präfix hinzu.

**Beispiele:**

```php
// API-Routen-Namen
Route::group(['as' => 'api.'], function() {
    Route::get('/users', $action)->name('users');      // api.users
    Route::get('/posts', $action)->name('posts');      // api.posts
});

// Admin-Routen-Namen
Route::group(['as' => 'admin.'], function() {
    Route::get('/dashboard', $action)->name('dashboard');  // admin.dashboard
    Route::get('/users', $action)->name('users');          // admin.users
});

// Verschachtelte Routen-Namen
Route::group(['as' => 'api.v1.'], function() {
    Route::get('/users', $action)->name('users');      // api.v1.users
    Route::get('/posts', $action)->name('posts');      // api.v1.posts
});
```

---

### 6. Rate Limiting

**Attribut:** `'throttle' => array`

**Beschreibung:** Wendet Rate Limiting auf alle Routen in der Gruppe an.

**Beispiele:**

```php
// API Rate Limiting
Route::group(['throttle' => [100, 1]], function() {
    Route::get('/users', $action);     // 100 Anfragen pro Minute
    Route::get('/posts', $action);     // 100 Anfragen pro Minute
});

// Verschiedene Limits für verschiedene Gruppen
Route::group(['throttle' => [60, 1]], function() {
    Route::get('/public/data', $action);  // 60 Anfragen pro Minute
});

Route::group(['throttle' => [1000, 1]], function() {
    Route::get('/premium/data', $action); // 1000 Anfragen pro Minute
});
```

---

### 7. IP-Filterung

**Attribut:** `'whitelist' => array` | `'blacklist' => array`

**Beschreibung:** Wendet IP-Filterung auf alle Routen in der Gruppe an.

**Beispiele:**

```php
// Bestimmte IPs auf Whitelist
Route::group(['whitelist' => ['192.168.1.0/24', '10.0.0.0/8']], function() {
    Route::get('/admin', $action);
    Route::get('/internal', $action);
});

// Bestimmte IPs auf Blacklist
Route::group(['blacklist' => ['192.168.1.100', '10.0.0.50']], function() {
    Route::get('/public', $action);
});
```

---

### 8. Tags

**Attribut:** `'tag' => array|string`

**Beschreibung:** Fügt allen Routen in der Gruppe Tags hinzu.

**Beispiele:**

```php
// Einzelner Tag
Route::group(['tag' => 'api'], function() {
    Route::get('/users', $action);
    Route::get('/posts', $action);
});

// Mehrere Tags
Route::group(['tag' => ['api', 'v1', 'public']], function() {
    Route::get('/users', $action);
    Route::get('/posts', $action);
});
```

---

### 9. Cache-Einstellungen

**Attribut:** `'cache' => array`

**Beschreibung:** Setzt Cache-Einstellungen für alle Routen in der Gruppe.

**Beispiele:**

```php
// Cache für 1 Stunde
Route::group(['cache' => [3600]], function() {
    Route::get('/static-data', $action);
    Route::get('/public-info', $action);
});

// Cache mit Tags
Route::group(['cache' => [3600, ['api', 'v1']]], function() {
    Route::get('/users', $action);
    Route::get('/posts', $action);
});
```

---

### 10. Mehrere Attribute

**Beschreibung:** Kombinieren mehrerer Attribute in einer einzigen Gruppe.

**Beispiele:**

```php
// Vollständige API-Gruppe
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

// Admin-Gruppe
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

### 11. Verschachtelte Gruppen

**Beschreibung:** Gruppen innerhalb von Gruppen für komplexe Organisation.

**Beispiele:**

```php
// Haupt-API-Gruppe
Route::group(['prefix' => '/api', 'middleware' => AuthMiddleware::class], function() {
    
    // Öffentliche Routen (keine Auth erforderlich)
    Route::group(['middleware' => []], function() {
        Route::get('/health', $action);
        Route::get('/version', $action);
    });
    
    // V1 API
    Route::group(['prefix' => '/v1', 'as' => 'v1.'], function() {
        Route::get('/users', $action)->name('users');
        Route::get('/posts', $action)->name('posts');
    });
    
    // V2 API
    Route::group(['prefix' => '/v2', 'as' => 'v2.'], function() {
        Route::get('/users', $action)->name('users');
        Route::get('/posts', $action)->name('posts');
    });
    
    // Admin API
    Route::group(['prefix' => '/admin', 'middleware' => AdminMiddleware::class], function() {
        Route::get('/stats', $action);
        Route::get('/logs', $action);
    });
});
```

---

### 12. Bedingte Gruppen

**Beschreibung:** Gruppen mit bedingten Attributen.

**Beispiele:**

```php
// Umgebungsbasierte Gruppen
if (app()->environment('production')) {
    Route::group(['domain' => 'api.example.com'], function() {
        Route::get('/users', $action);
    });
} else {
    Route::group(['domain' => 'api.local'], function() {
        Route::get('/users', $action);
    });
}

// Feature-basierte Gruppen
if (config('features.api_v2')) {
    Route::group(['prefix' => '/api/v2'], function() {
        Route::get('/users', $action);
    });
}
```

---

## Best Practices

### 1. Logische Gruppierung

```php
// Nach Funktionalität gruppieren
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

### 2. Middleware-Organisation

```php
// Nach Middleware-Anforderungen gruppieren
Route::group(['middleware' => AuthMiddleware::class], function() {
    Route::get('/dashboard', $action);
    Route::get('/profile', $action);
    
    Route::group(['middleware' => AdminMiddleware::class], function() {
        Route::get('/admin/users', $action);
        Route::get('/admin/posts', $action);
    });
});
```

### 3. Konsistente Benennung

```php
// Konsistente Routen-Benennung
Route::group(['as' => 'api.v1.'], function() {
    Route::get('/users', $action)->name('users.index');
    Route::post('/users', $action)->name('users.store');
    Route::get('/users/{id}', $action)->name('users.show');
});
```

---

## Häufige Muster

### 1. API-Versionierung

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

### 2. Admin-Panel

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

### 3. Öffentlich vs Privat

```php
// Öffentliche Routen
Route::group(['middleware' => []], function() {
    Route::get('/', [HomeController::class, 'index']);
    Route::get('/about', [PageController::class, 'about']);
});

// Private Routen
Route::group(['middleware' => AuthMiddleware::class], function() {
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/profile', [ProfileController::class, 'show']);
});
```

---

## Performance-Tipps

### 1. Verschachtelung minimieren

```php
// Gut: Flache Struktur
Route::group(['prefix' => '/api/v1'], function() {
    Route::get('/users', $action);
    Route::get('/posts', $action);
});

// Vermeiden: Tiefe Verschachtelung
Route::group(['prefix' => '/api'], function() {
    Route::group(['prefix' => '/v1'], function() {
        Route::group(['prefix' => '/users'], function() {
            Route::get('/', $action);
        });
    });
});
```

### 2. Effiziente Middleware

```php
// Middleware auf Gruppenebene anwenden
Route::group(['middleware' => AuthMiddleware::class], function() {
    Route::get('/dashboard', $action);
    Route::get('/profile', $action);
});
```

---

## Fehlerbehebung

### Häufige Probleme

1. **Middleware nicht angewendet**
   - Middleware-Registrierung prüfen
   - Middleware-Klasse verifizieren
   - Middleware-Reihenfolge prüfen

2. **Präfix funktioniert nicht**
   - Präfix-Format verifizieren
   - Führende/nachfolgende Schrägstriche prüfen
   - Richtige Verschachtelung sicherstellen

3. **Namespace-Probleme**
   - Namespace-Format prüfen
   - Controller-Klassen verifizieren
   - Autoloading prüfen

### Debug-Tipps

```php
// Debug-Modus aktivieren
Route::enableDebug();

// Gruppen-Attribute prüfen
$routes = Route::getAllRoutes();
foreach ($routes as $route) {
    echo $route->getUri() . ' - ' . $route->getName() . PHP_EOL;
}
```

---

## Siehe auch

- [Grundlegendes Routing](01_BASIC_ROUTING.md) - Grundlegende Routen-Registrierung
- [Routen-Parameter](02_ROUTE_PARAMETERS.md) - Dynamische Routen-Parameter
- [Middleware](06_MIDDLEWARE.md) - Anfrage-Verarbeitungs-Middleware
- [Benannte Routen](07_NAMED_ROUTES.md) - Routen-Identifikation
- [API-Referenz](../API_REFERENCE.md) - Vollständige API-Referenz

---

© 2024 CloudCastle HTTP Router  
[⬆ Nach oben](#routen-gruppen)