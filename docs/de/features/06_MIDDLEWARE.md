# Middleware

[English](../../en/features/06_MIDDLEWARE.md) | [Русский](../../ru/features/06_MIDDLEWARE.md) | [**Deutsch**](06_MIDDLEWARE.md) | [Français](../../fr/features/06_MIDDLEWARE.md) | [中文](../../zh/features/06_MIDDLEWARE.md)

---

## 📚 Dokumentationsnavigation

[README](../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [API_REFERENCE](../API_REFERENCE.md) | [ALL_FEATURES](../ALL_FEATURES.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [PERFORMANCE](../PERFORMANCE_ANALYSIS.md) | [SECURITY](../SECURITY_REPORT.md) | [COMPARISON](../COMPARISON.md) | [FAQ](../FAQ.md)

**Detaillierte Dokumentation:** [01](01_BASIC_ROUTING.md) | [02](02_ROUTE_PARAMETERS.md) | [03](03_ROUTE_GROUPS.md) | [04](04_RATE_LIMITING.md) | [05](05_IP_FILTERING.md) | [06](06_MIDDLEWARE.md) | [07](07_NAMED_ROUTES.md) | [08](08_TAGS.md) | [09](09_HELPER_FUNCTIONS.md) | [10](10_ROUTE_SHORTCUTS.md) | [11](11_ROUTE_MACROS.md) | [12](12_URL_GENERATION.md) | [13](13_EXPRESSION_LANGUAGE.md) | [14](14_CACHING.md) | [15](15_PLUGINS.md) | [16](16_LOADERS.md) | [17](17_PSR_SUPPORT.md) | [18](18_ACTION_RESOLVER.md) | [19](19_STATISTICS.md) | [20](20_SECURITY.md) | [21](21_EXCEPTIONS.md) | [22](22_CLI_TOOLS.md)

---

**Kategorie:** Anfrage-Verarbeitung  
**Anzahl Typen:** 6  
**Komplexität:** ⭐⭐ Mittelstufe

---

## Beschreibung

Middleware sind Zwischenhandler, die vor oder nach der Hauptrouten-Aktion ausgeführt werden. Sie werden für Authentifizierung, Protokollierung, CORS, Validierung und andere Aufgaben verwendet.

## Middleware anwenden

### 1. Globale Middleware

```php
// Wird auf ALLE Routen angewendet
Route::middleware([CorsMiddleware::class, LoggerMiddleware::class]);
```

### 2. Auf spezifischer Route

```php
Route::get('/dashboard', $action)
    ->middleware([AuthMiddleware::class]);
```

### 3. In Gruppe

```php
Route::group(['middleware' => [AuthMiddleware::class]], function() {
    Route::get('/dashboard', $action);
    Route::get('/profile', $action);
});
```

## Eingebaute Middleware

### AuthMiddleware

```php
use CloudCastle\Http\Router\Middleware\AuthMiddleware;

Route::get('/dashboard', $action)
    ->middleware([AuthMiddleware::class]);

// Oder über Shortcut
Route::get('/dashboard', $action)->auth();
```

### CorsMiddleware

```php
use CloudCastle\Http\Router\Middleware\CorsMiddleware;

Route::get('/api/data', $action)
    ->middleware([CorsMiddleware::class]);

// Oder über Shortcut
Route::get('/api/data', $action)->cors();
```

### HttpsEnforcement

```php
use CloudCastle\Http\Router\Middleware\HttpsEnforcement;

Route::post('/payment', $action)
    ->middleware([HttpsEnforcement::class]);

// Oder über Shortcut
Route::post('/payment', $action)->secure();
```

### SecurityLogger

```php
use CloudCastle\Http\Router\Middleware\SecurityLogger;

Route::post('/admin/action', $action)
    ->middleware([SecurityLogger::class]);
```

## Benutzerdefinierte Middleware

### Middleware erstellen

```php
namespace App\Middleware;

class CustomMiddleware
{
    public function handle($request, $next)
    {
        // Vor Routen-Aktion
        
        // Routen-Aktion ausführen
        $response = $next($request);
        
        // Nach Routen-Aktion
        
        return $response;
    }
}
```

### Benutzerdefinierte Middleware verwenden

```php
use App\Middleware\CustomMiddleware;

Route::get('/test', $action)
    ->middleware([CustomMiddleware::class]);
```

## Middleware-Muster

### 1. Authentifizierung

```php
class AuthMiddleware
{
    public function handle($request, $next)
    {
        if (!isset($_SESSION['user_id'])) {
            return response()->redirect('/login');
        }
        
        return $next($request);
    }
}
```

### 2. Rollenprüfung

```php
class AdminMiddleware
{
    public function handle($request, $next)
    {
        if (!$request->user()->isAdmin()) {
            return response()->json(['error' => 'Verboten'], 403);
        }
        
        return $next($request);
    }
}
```

### 3. Anfrage-Protokollierung

```php
class LoggerMiddleware
{
    public function handle($request, $next)
    {
        $start = microtime(true);
        
        $response = $next($request);
        
        $duration = microtime(true) - $start;
        Log::info("Anfrage verarbeitet in {$duration}s");
        
        return $response;
    }
}
```

### 4. CORS-Header

```php
class CorsMiddleware
{
    public function handle($request, $next)
    {
        $response = $next($request);
        
        $response->header('Access-Control-Allow-Origin', '*');
        $response->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE');
        
        return $response;
    }
}
```

### 5. Rate Limiting

```php
class RateLimitMiddleware
{
    public function handle($request, $next)
    {
        $ip = $request->ip();
        
        if ($this->exceedsLimit($ip)) {
            return response()->json(['error' => 'Zu viele Anfragen'], 429);
        }
        
        return $next($request);
    }
}
```

### 6. Anfrage-Validierung

```php
class ValidateRequestMiddleware
{
    public function handle($request, $next)
    {
        $errors = $this->validate($request);
        
        if (!empty($errors)) {
            return response()->json(['errors' => $errors], 422);
        }
        
        return $next($request);
    }
}
```

## Middleware-Reihenfolge

Middleware wird in der Reihenfolge ausgeführt, in der sie registriert wurde:

```php
Route::get('/test', $action)
    ->middleware([
        FirstMiddleware::class,   // Wird zuerst ausgeführt
        SecondMiddleware::class,  // Wird als zweites ausgeführt
        ThirdMiddleware::class    // Wird als drittes ausgeführt
    ]);
```

## Best Practices

### 1. Einzelne Verantwortung

```php
// Gut: Jede Middleware hat einen Zweck
Route::get('/admin', $action)
    ->middleware([
        AuthMiddleware::class,
        AdminMiddleware::class,
        LoggerMiddleware::class
    ]);
```

### 2. Wiederverwendbare Middleware

```php
// Wiederverwendbare Middleware für häufige Aufgaben erstellen
class CacheMiddleware
{
    public function handle($request, $next)
    {
        $key = $this->getCacheKey($request);
        
        if ($cached = cache()->get($key)) {
            return $cached;
        }
        
        $response = $next($request);
        cache()->put($key, $response, 3600);
        
        return $response;
    }
}
```

### 3. Middleware-Gruppen

```php
// Middleware-Gruppen definieren
Route::group([
    'middleware' => [
        AuthMiddleware::class,
        AdminMiddleware::class,
        SecurityLogger::class
    ]
], function() {
    // Alle Admin-Routen
});
```

## Siehe auch

- [Routen-Gruppen](03_ROUTE_GROUPS.md) - Routen mit Middleware organisieren
- [Sicherheit](20_SECURITY.md) - Sicherheitsfunktionen-Übersicht
- [Rate Limiting](04_RATE_LIMITING.md) - Rate Limiting-Middleware
- [API-Referenz](../API_REFERENCE.md) - Vollständige API-Referenz

---

© 2024 CloudCastle HTTP Router  
[⬆ Nach oben](#middleware)