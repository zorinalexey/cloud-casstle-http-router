# Middleware

[English](../../en/features/06_MIDDLEWARE.md) | [Русский](../../ru/features/06_MIDDLEWARE.md) | [Deutsch](../../de/features/06_MIDDLEWARE.md) | [**Français**](06_MIDDLEWARE.md) | [中文](../../zh/features/06_MIDDLEWARE.md)

---

## 📚 Navigation Documentation

[README](../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [API_REFERENCE](../API_REFERENCE.md) | [ALL_FEATURES](../ALL_FEATURES.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [PERFORMANCE](../PERFORMANCE_ANALYSIS.md) | [SECURITY](../SECURITY_REPORT.md) | [COMPARISON](../COMPARISON.md) | [FAQ](../FAQ.md)

**Documentation Détaillée:** [01](01_BASIC_ROUTING.md) | [02](02_ROUTE_PARAMETERS.md) | [03](03_ROUTE_GROUPS.md) | [04](04_RATE_LIMITING.md) | [05](05_IP_FILTERING.md) | [06](06_MIDDLEWARE.md) | [07](07_NAMED_ROUTES.md) | [08](08_TAGS.md) | [09](09_HELPER_FUNCTIONS.md) | [10](10_ROUTE_SHORTCUTS.md) | [11](11_ROUTE_MACROS.md) | [12](12_URL_GENERATION.md) | [13](13_EXPRESSION_LANGUAGE.md) | [14](14_CACHING.md) | [15](15_PLUGINS.md) | [16](16_LOADERS.md) | [17](17_PSR_SUPPORT.md) | [18](18_ACTION_RESOLVER.md) | [19](19_STATISTICS.md) | [20](20_SECURITY.md) | [21](21_EXCEPTIONS.md) | [22](22_CLI_TOOLS.md)

---

**Catégorie:** Traitement des Requêtes  
**Nombre de Types:** 6  
**Complexité:** ⭐⭐ Niveau Intermédiaire

---

## Description

Les middleware sont des gestionnaires intermédiaires qui s'exécutent avant ou après l'action principale de la route. Ils sont utilisés pour l'authentification, la journalisation, CORS, la validation et d'autres tâches.

## Application du Middleware

### 1. Middleware Global

```php
// Appliqué à TOUTES les routes
Route::middleware([CorsMiddleware::class, LoggerMiddleware::class]);
```

### 2. Sur Route Spécifique

```php
Route::get('/dashboard', $action)
    ->middleware([AuthMiddleware::class]);
```

### 3. Dans un Groupe

```php
Route::group(['middleware' => [AuthMiddleware::class]], function() {
    Route::get('/dashboard', $action);
    Route::get('/profile', $action);
});
```

## Middleware Intégrés

### AuthMiddleware

```php
use CloudCastle\Http\Router\Middleware\AuthMiddleware;

Route::get('/dashboard', $action)
    ->middleware([AuthMiddleware::class]);

// Ou via raccourci
Route::get('/dashboard', $action)->auth();
```

### CorsMiddleware

```php
use CloudCastle\Http\Router\Middleware\CorsMiddleware;

Route::get('/api/data', $action)
    ->middleware([CorsMiddleware::class]);

// Ou via raccourci
Route::get('/api/data', $action)->cors();
```

### HttpsEnforcement

```php
use CloudCastle\Http\Router\Middleware\HttpsEnforcement;

Route::post('/payment', $action)
    ->middleware([HttpsEnforcement::class]);

// Ou via raccourci
Route::post('/payment', $action)->secure();
```

### SecurityLogger

```php
use CloudCastle\Http\Router\Middleware\SecurityLogger;

Route::post('/admin/action', $action)
    ->middleware([SecurityLogger::class]);
```

## Middleware Personnalisé

### Créer un Middleware

```php
namespace App\Middleware;

class CustomMiddleware
{
    public function handle($request, $next)
    {
        // Avant l'action de la route
        
        // Exécuter l'action de la route
        $response = $next($request);
        
        // Après l'action de la route
        
        return $response;
    }
}
```

### Utiliser un Middleware Personnalisé

```php
use App\Middleware\CustomMiddleware;

Route::get('/test', $action)
    ->middleware([CustomMiddleware::class]);
```

## Modèles de Middleware

### 1. Authentification

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

### 2. Vérification de Rôle

```php
class AdminMiddleware
{
    public function handle($request, $next)
    {
        if (!$request->user()->isAdmin()) {
            return response()->json(['error' => 'Interdit'], 403);
        }
        
        return $next($request);
    }
}
```

### 3. Journalisation des Requêtes

```php
class LoggerMiddleware
{
    public function handle($request, $next)
    {
        $start = microtime(true);
        
        $response = $next($request);
        
        $duration = microtime(true) - $start;
        Log::info("Requête traitée en {$duration}s");
        
        return $response;
    }
}
```

### 4. En-têtes CORS

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

### 5. Limitation de Débit

```php
class RateLimitMiddleware
{
    public function handle($request, $next)
    {
        $ip = $request->ip();
        
        if ($this->exceedsLimit($ip)) {
            return response()->json(['error' => 'Trop de requêtes'], 429);
        }
        
        return $next($request);
    }
}
```

### 6. Validation de Requête

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

## Ordre du Middleware

Le middleware s'exécute dans l'ordre où il est enregistré:

```php
Route::get('/test', $action)
    ->middleware([
        FirstMiddleware::class,   // S'exécute en premier
        SecondMiddleware::class,  // S'exécute en deuxième
        ThirdMiddleware::class    // S'exécute en troisième
    ]);
```

## Meilleures Pratiques

### 1. Responsabilité Unique

```php
// Bien: Chaque middleware a un objectif
Route::get('/admin', $action)
    ->middleware([
        AuthMiddleware::class,
        AdminMiddleware::class,
        LoggerMiddleware::class
    ]);
```

### 2. Middleware Réutilisable

```php
// Créer un middleware réutilisable pour les tâches courantes
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

### 3. Groupes de Middleware

```php
// Définir des groupes de middleware
Route::group([
    'middleware' => [
        AuthMiddleware::class,
        AdminMiddleware::class,
        SecurityLogger::class
    ]
], function() {
    // Toutes les routes admin
});
```

## Voir Aussi

- [Groupes de Routes](03_ROUTE_GROUPS.md) - Organiser les routes avec middleware
- [Sécurité](20_SECURITY.md) - Aperçu des fonctionnalités de sécurité
- [Limitation de Débit](04_RATE_LIMITING.md) - Middleware de limitation de débit
- [Référence API](../API_REFERENCE.md) - Référence API complète

---

© 2024 CloudCastle HTTP Router  
[⬆ Retour en haut](#middleware)