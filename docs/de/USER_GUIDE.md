# CloudCastle HTTP Router - Vollständiges Benutzerhandbuch

[English](../en/USER_GUIDE.md) | [Русский](../ru/USER_GUIDE.md) | [**Deutsch**](USER_GUIDE.md) | [Français](../fr/USER_GUIDE.md) | [中文](../zh/USER_GUIDE.md)

---

## 📚 Dokumentationsnavigation

[README](../../README.md) | [USER_GUIDE](USER_GUIDE.md) | [FEATURES_INDEX](FEATURES_INDEX.md) | [API_REFERENCE](API_REFERENCE.md) | [ALL_FEATURES](ALL_FEATURES.md) | [TESTS_SUMMARY](TESTS_SUMMARY.md) | [PERFORMANCE](PERFORMANCE_ANALYSIS.md) | [SECURITY](SECURITY_REPORT.md) | [COMPARISON](COMPARISON.md) | [FAQ](FAQ.md) | [DOC_SUMMARY](DOCUMENTATION_SUMMARY.md)

**Detaillierte Dokumentation:** [Features](features/) (22 Dateien) | [Tests](tests/) (7 Berichte)

---

**Version:** 1.1.1  
**Datum:** Oktober 2025  
**Funktionen:** 209+

---

## 📑 Inhaltsverzeichnis

1. [Einführung](#einführung)
2. [Installation und Einrichtung](#installation-und-einrichtung)
3. [Basis-Routing (13 Methoden)](#basis-routing)
4. [Routen-Parameter (6 Wege)](#routen-parameter)
5. [Routen-Gruppen (12 Attribute)](#routen-gruppen)
6. [Rate Limiting (8 Methoden)](#rate-limiting)
7. [Auto-Ban System (7 Methoden)](#auto-ban-system)
8. [IP Filtering (4 Methoden)](#ip-filtering)
9. [Middleware (6 Typen)](#middleware)
10. [Benannte Routen (6 Methoden)](#benannte-routen)
11. [Tags (5 Methoden)](#tags)
12. [Hilfsfunktionen (18 Funktionen)](#hilfsfunktionen)
13. [Routen-Shortcuts (14 Methoden)](#routen-shortcuts)
14. [Routen-Makros (7 Makros)](#routen-makros)
15. [URL-Generierung (11 Methoden)](#url-generierung)
16. [Expression Language (5 Operatoren)](#expression-language)
17. [Routen-Caching (6 Methoden)](#routen-caching)
18. [Plugin-System (13 Methoden)](#plugin-system)
19. [Routen-Loader (5 Typen)](#routen-loader)
20. [PSR-Unterstützung (3 Standards)](#psr-unterstützung)
21. [Action Resolver (6 Formate)](#action-resolver)
22. [Statistiken und Abfragen (24 Methoden)](#statistiken-und-abfragen)
23. [Sicherheit (12 Mechanismen)](#sicherheit)
24. [Ausnahmen (8 Typen)](#ausnahmen)
25. [CLI-Tools (3 Befehle)](#cli-tools)
26. [Erweiterte Beispiele](#erweiterte-beispiele)

---

## Einführung

CloudCastle HTTP Router ist eine **hochperformante** (54k+ req/sec), **sichere** (OWASP Top 10) und **funktionsreiche** (209+ Funktionen) Routing-Bibliothek für PHP 8.2+.

### Hauptmerkmale

- ⚡ **Performance:** 54,891 Anfragen/Sek
- 🔒 **Sicherheit:** 12+ eingebaute Schutzmechanismen
- 💎 **Funktionalität:** 209+ Methoden und Features
- 💾 **Effizienz:** 1.32 KB pro Route
- 📊 **Skalierbarkeit:** 1,160,000+ Routen
- ✅ **Zuverlässigkeit:** 501 Tests, 0 Fehler

---

## Installation und Einrichtung

### Anforderungen

- PHP 8.2 oder höher
- Composer
- PSR-7/PSR-15 (optional)

### Installation über Composer

```bash
composer require cloud-castle/http-router
```

### Schnellstart

```php
<?php

require __DIR__ . '/vendor/autoload.php';

use CloudCastle\Http\Router\Facade\Route;

// Routen registrieren
Route::get('/users', fn() => 'List of users');
Route::post('/users', fn() => 'Create user');

// Dispatch
$route = Route::dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
echo $route->run();
```

---

## Basis-Routing

### 1. GET Route

```php
use CloudCastle\Http\Router\Facade\Route;

Route::get('/users', function() {
    return 'List of users';
});
```

### 2. POST Route

```php
Route::post('/users', function() {
    return 'Create user';
});
```

### 3. PUT Route

```php
Route::put('/users/{id}', function($id) {
    return "Update user: $id";
});
```

### 4. PATCH Route

```php
Route::patch('/users/{id}', function($id) {
    return "Partial update user: $id";
});
```

### 5. DELETE Route

```php
Route::delete('/users/{id}', function($id) {
    return "Delete user: $id";
});
```

### 6. VIEW Route (benutzerdefiniert)

```php
Route::view('/preview', function() {
    return 'Preview content';
});
```

### 7. Benutzerdefinierte HTTP-Methode

```php
Route::custom('PURGE', '/cache', function() {
    return 'Cache purged';
});

Route::custom('TRACE', '/debug', function() {
    return 'Debug trace';
});
```

### 8. Mehrere HTTP-Methoden

```php
Route::match(['GET', 'POST'], '/form', function() {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        return 'Show form';
    }
    return 'Process form';
});
```

### 9. Alle HTTP-Methoden

```php
Route::any('/webhook', function() {
    return 'Handle any method';
});
```

### 10. Verwendung von Router-Instanz

```php
use CloudCastle\Http\Router\Router;

$router = new Router();

$router->get('/users', fn() => 'Users');
$router->post('/users', fn() => 'Create');

$route = $router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
```

### 11-13. Statische Router-Methoden

```php
use CloudCastle\Http\Router\Router;

// Singleton-Pattern
Router::staticGet('/users', fn() => 'Users');
Router::staticPost('/users', fn() => 'Create');
Router::staticDelete('/users/{id}', fn($id) => "Delete: $id");
```

---

## Routen-Parameter

### 1. Basis-Parameter

```php
Route::get('/users/{id}', function($id) {
    return "User ID: $id";
});

Route::get('/posts/{slug}', function($slug) {
    return "Post: $slug";
});

// Mehrere Parameter
Route::get('/users/{userId}/posts/{postId}', function($userId, $postId) {
    return "User $userId, Post $postId";
});
```

### 2. Parameter-Einschränkungen (where)

```php
// Nur Ziffern
Route::get('/users/{id}', $action)
    ->where('id', '[0-9]+');

// Buchstaben und Bindestriche
Route::get('/posts/{slug}', $action)
    ->where('slug', '[a-z0-9-]+');

// Mehrere Einschränkungen
Route::get('/api/{version}/users/{id}', $action)
    ->where([
        'version' => 'v[0-9]+',
        'id' => '[0-9]+'
    ]);
```

### 3. Inline-Parameter

```php
// Muster in URI selbst
Route::get('/users/{id:[0-9]+}', $action);
Route::get('/posts/{slug:[a-z0-9-]+}', $action);
Route::get('/files/{path:.+}', $action);
```

### 4. Optionale Parameter

```php
Route::get('/blog/{category?}', function($category = 'all') {
    return "Category: $category";
});

Route::get('/search/{query?}/{page?}', function($query = '', $page = 1) {
    return "Search: $query, Page: $page";
});
```

### 5. Standardwerte

```php
Route::get('/posts/{page}', $action)
    ->defaults(['page' => 1]);

Route::get('/search/{query}/{limit}', $action)
    ->defaults([
        'query' => '',
        'limit' => 10
    ]);
```

### 6. Parameter Abrufen

```php
Route::get('/users/{id}', function($id) {
    $route = Route::current();
    $params = $route->getParameters();
    // ['id' => '123']
    
    return "User: $id";
});
```

---

## Routen-Gruppen

### 1. Gruppe mit Präfix

```php
Route::group(['prefix' => '/api'], function() {
    Route::get('/users', $action);     // /api/users
    Route::get('/posts', $action);     // /api/posts
});
```

### 2. Gruppe mit Middleware

```php
Route::group(['middleware' => [AuthMiddleware::class]], function() {
    Route::get('/dashboard', $action);
    Route::get('/profile', $action);
});
```

### 3. Gruppe mit Domain

```php
Route::group(['domain' => 'api.example.com'], function() {
    Route::get('/users', $action);
    Route::get('/posts', $action);
});
```

### 4. Gruppe mit Port

```php
Route::group(['port' => 8080], function() {
    Route::get('/admin', $action);
});
```

### 5. Gruppe mit Namespace

```php
Route::group(['namespace' => 'App\\Controllers\\Api'], function() {
    Route::get('/users', 'UserController@index');
    Route::get('/posts', 'PostController@index');
});
```

### 6. Gruppe mit HTTPS-Anforderung

```php
Route::group(['https' => true], function() {
    Route::get('/secure', $action);
    Route::post('/payment', $action);
});
```

### 7. Gruppe mit Protokollen

```php
Route::group(['protocols' => ['ws', 'wss']], function() {
    Route::get('/chat', $action);
    Route::get('/notifications', $action);
});
```

### 8. Gruppe mit Tags

```php
Route::group(['tags' => ['api', 'public']], function() {
    Route::get('/users', $action);
    Route::get('/posts', $action);
});
```

### 9. Gruppe mit Throttle

```php
Route::group(['throttle' => [60, 1]], function() {
    Route::get('/api/data', $action);
    Route::post('/api/submit', $action);
});
```

### 10. Gruppe mit IP-Whitelist

```php
Route::group(['whitelistIp' => ['192.168.1.0/24']], function() {
    Route::get('/admin/users', $action);
    Route::get('/admin/settings', $action);
});
```

### 11. Verschachtelte Gruppen

```php
Route::group(['prefix' => '/api'], function() {
    Route::group(['prefix' => '/v1'], function() {
        Route::get('/users', $action);  // /api/v1/users
    });
    
    Route::group(['prefix' => '/v2'], function() {
        Route::get('/users', $action);  // /api/v2/users
    });
});
```

### 12. Kombinierte Attribute

```php
Route::group([
    'prefix' => '/admin',
    'middleware' => [AuthMiddleware::class, AdminMiddleware::class],
    'domain' => 'admin.example.com',
    'port' => 443,
    'https' => true,
    'whitelistIp' => ['192.168.1.0/24'],
    'tags' => ['admin', 'protected'],
    'throttle' => [30, 1],
    'namespace' => 'App\\Controllers\\Admin',
], function() {
    Route::get('/dashboard', 'DashboardController@index');
    Route::get('/users', 'UserController@index');
});
```

### RouteGroup-Objekt Abrufen

```php
$group = Route::group(['prefix' => '/api'], function() {
    Route::get('/users', $action);
    Route::get('/posts', $action);
});

// RouteGroup-Methoden
$routes = $group->getRoutes();        // Alle Gruppen-Routen
$count = $group->count();             // Routen-Anzahl
$attrs = $group->getAttributes();     // Gruppen-Attribute
```

---

## Rate Limiting

### 1. Basis-Throttle

```php
// 60 Anfragen pro Minute
Route::post('/api/submit', $action)
    ->throttle(60, 1);

// 100 Anfragen pro Stunde
Route::post('/api/upload', $action)
    ->throttle(100, 60);
```

### 2. TimeUnit Enum

```php
use CloudCastle\Http\Router\TimeUnit;

// 5 Anfragen pro Sekunde
Route::post('/api/fast', $action)
    ->throttle(5, TimeUnit::SECOND->value);

// 100 Anfragen pro Minute
Route::post('/api/normal', $action)
    ->throttle(100, TimeUnit::MINUTE->value);

// 1000 Anfragen pro Stunde
Route::post('/api/slow', $action)
    ->throttle(1000, TimeUnit::HOUR->value);

// 10000 Anfragen pro Tag
Route::post('/api/daily', $action)
    ->throttle(10000, TimeUnit::DAY->value);

// 50000 Anfragen pro Woche
Route::post('/api/weekly', $action)
    ->throttle(50000, TimeUnit::WEEK->value);

// 200000 Anfragen pro Monat
Route::post('/api/monthly', $action)
    ->throttle(200000, TimeUnit::MONTH->value);
```

### 3. Benutzerdefinierter Throttle-Schlüssel

```php
Route::post('/api/user-specific', $action)
    ->throttle(30, 1, function($request) {
        // Begrenzung nach Benutzer-ID
        return 'user_' . $request->userId;
    });

Route::post('/api/ip-specific', $action)
    ->throttle(60, 1, function($request) {
        // Begrenzung nach IP
        return $request->ip();
});
```

### 4. RateLimiter Abrufen

```php
$route = Route::post('/api/data', $action)
    ->throttle(60, 1);

$rateLimiter = $route->getRateLimiter();
```

### 5. RateLimiter-Methoden

```php
use CloudCastle\Http\Router\RateLimiter;

$limiter = new RateLimiter(60, 1);  // 60 Anfragen pro Minute

// Prüfen ob Limit überschritten
if ($limiter->tooManyAttempts('user_123')) {
    $seconds = $limiter->availableIn('user_123');
    echo "Retry after $seconds seconds";
}

// Versuch hinzufügen
$limiter->attempt('user_123');

// Verbleibende Versuche
$remaining = $limiter->remaining('user_123');

// Zähler zurücksetzen
$limiter->clear('user_123');

// Alles löschen
$limiter->clearAll();

// Maximale Versuche abrufen
$max = $limiter->getMaxAttempts();

// Verfallszeit in Minuten abrufen
$period = $limiter->getDecayMinutes();
```

### 6. BanManager für RateLimiter Setzen

```php
use CloudCastle\Http\Router\BanManager;

$banManager = new BanManager(5, 3600);  // 5 Verstöße = Sperre für 1 Stunde

Route::post('/login', $action)
    ->throttle(3, 1)
    ->getRateLimiter()
    ?->setBanManager($banManager);
```

### 7-8. Throttle-Shortcuts

```php
// 60 Anfragen pro Minute
Route::post('/api/standard', $action)->throttleStandard();

// 10 Anfragen pro Minute
Route::post('/api/strict', $action)->throttleStrict();

// 1000 Anfragen pro Minute
Route::post('/api/generous', $action)->throttleGenerous();
```

---

## Auto-Ban System

### 1. BanManager Erstellen

```php
use CloudCastle\Http\Router\BanManager;

// 5 Verstöße = Sperre für 1 Stunde (3600 Sek)
$banManager = new BanManager(
    maxViolations: 5,
    banDuration: 3600
);
```

### 2. Auto-Ban Aktivieren

```php
$banManager->enableAutoBan(5);  // Auto-Ban nach 5 Verstößen
```

### 3. Manuelle IP-Sperre

```php
// IP für 1 Stunde sperren
$banManager->ban('1.2.3.4', 3600);

// IP dauerhaft sperren (0 Sekunden)
$banManager->ban('5.6.7.8', 0);
```

### 4. IP Entsperren

```php
$banManager->unban('1.2.3.4');
```

### 5. Sperre Prüfen

```php
if ($banManager->isBanned('1.2.3.4')) {
    throw new \CloudCastle\Http\Router\Exceptions\BannedException(
        'Your IP is banned'
    );
}
```

### 6. Liste Gesperrter IPs Abrufen

```php
$bannedIps = $banManager->getBannedIps();
// ['1.2.3.4', '5.6.7.8']
```

### 7. Alle Sperren Löschen

```php
$banManager->clearAll();
```

### Vollständiges Beispiel mit Auto-Ban

```php
use CloudCastle\Http\Router\BanManager;
use CloudCastle\Http\Router\Facade\Route;

$banManager = new BanManager(5, 3600);

Route::post('/login', function() {
    // Login-Logik
    return 'Login success';
})
->throttle(3, 1)  // 3 Versuche pro Minute
->getRateLimiter()
?->setBanManager($banManager);

// Nach 5-maligem Überschreiten des Limits - automatische Sperre für 1 Stunde
```

---

## IP Filtering

### 1. Whitelist IP

```php
// Einzelne IP
Route::get('/admin', $action)
    ->whitelistIp(['192.168.1.1']);

// Mehrere IPs
Route::get('/admin', $action)
    ->whitelistIp([
        '192.168.1.1',
        '192.168.1.2',
        '10.0.0.1'
    ]);
```

### 2. CIDR-Notation

```php
Route::get('/admin', $action)
    ->whitelistIp([
        '192.168.1.0/24',    // 192.168.1.0 - 192.168.1.255
        '10.0.0.0/8',        // 10.0.0.0 - 10.255.255.255
    ]);
```

### 3. Blacklist IP

```php
Route::get('/public', $action)
    ->blacklistIp([
        '1.2.3.4',
        '5.6.7.8'
    ]);
```

### 4. Kombination von Whitelist und Blacklist

```php
Route::get('/api/data', $action)
    ->whitelistIp(['192.168.1.0/24'])  // Lokales Netzwerk erlauben
    ->blacklistIp(['192.168.1.100']);   // Außer dieser IP
```

---

## Middleware

### 1. Globale Middleware

```php
use CloudCastle\Http\Router\Middleware\CorsMiddleware;

Route::middleware([CorsMiddleware::class]);
```

### 2. Routen-Middleware

```php
use CloudCastle\Http\Router\Middleware\AuthMiddleware;

Route::get('/dashboard', $action)
    ->middleware([AuthMiddleware::class]);
```

### 3. Mehrere Middleware

```php
Route::get('/admin/users', $action)
    ->middleware([
        AuthMiddleware::class,
        AdminMiddleware::class,
        LoggerMiddleware::class
    ]);
```

### 4. Eingebaute Middleware

```php
use CloudCastle\Http\Router\Middleware\{
    AuthMiddleware,
    CorsMiddleware,
    HttpsEnforcement,
    SecurityLogger,
    SsrfProtection
};

Route::get('/api/data', $action)
    ->middleware([
        CorsMiddleware::class,
        SecurityLogger::class
    ]);

Route::get('/secure', $action)
    ->middleware([HttpsEnforcement::class]);

Route::post('/webhook', $action)
    ->middleware([SsrfProtection::class]);
```

### 5. Benutzerdefinierte Middleware Erstellen

```php
use CloudCastle\Http\Router\Contracts\MiddlewareInterface;
use CloudCastle\Http\Router\Route;

class CustomMiddleware implements MiddlewareInterface
{
    public function handle(Route $route, callable $next): mixed
    {
        // Vor-Logik
        echo "Before route execution\n";
        
        // Route ausführen
        $response = $next($route);
        
        // Nach-Logik
        echo "After route execution\n";
        
        return $response;
    }
}

Route::get('/test', $action)
    ->middleware([CustomMiddleware::class]);
```

### 6. MiddlewareDispatcher

```php
use CloudCastle\Http\Router\MiddlewareDispatcher;

$dispatcher = new MiddlewareDispatcher();

$dispatcher->add(AuthMiddleware::class);
$dispatcher->add(LoggerMiddleware::class);

$response = $dispatcher->dispatch($route, function($route) {
    return $route->run();
});
```

---

## Benannte Routen

### 1. Namen Zuweisen

```php
Route::get('/users/{id}', $action)
    ->name('users.show');

Route::post('/users', $action)
    ->name('users.store');
```

### 2. Route nach Namen Abrufen

```php
$route = Route::getRouteByName('users.show');
```

### 3. Aktueller Routen-Name

```php
$name = Route::currentRouteName();
// 'users.show'
```

### 4. Aktuellen Routen-Namen Prüfen

```php
if (Route::currentRouteNamed('users.show')) {
    echo "Viewing user";
}
```

### 5. Auto-Benennung

```php
// Auto-Benennung aktivieren
Route::enableAutoNaming();

// Routen erhalten automatisch Namen
Route::get('/users', $action);       // auto: users.get
Route::post('/users', $action);      // auto: users.post
Route::get('/users/{id}', $action);  // auto: users.id.get

// Beispiele mit API
Route::get('/api/v1/users', $action);         // auto: api.v1.users.get
Route::post('/api/v1/users/{id}', $action);   // auto: api.v1.users.id.post

// Root-Route
Route::get('/', $action);                     // auto: root.get

// Sonderzeichen werden normalisiert
Route::get('/api-v1/user_profile', $action);  // auto: api.v1.user.profile.get

// Auto-Benennung deaktivieren
Route::disableAutoNaming();

// Status prüfen
$enabled = Route::router()->isAutoNamingEnabled();
```

### 6. Alle Benannten Routen Abrufen

```php
$namedRoutes = Route::getNamedRoutes();
// ['users.show' => Route, 'users.store' => Route, ...]
```

---

## Tags

### 1. Einzelnen Tag Hinzufügen

```php
Route::get('/api/users', $action)
    ->tag('api');
```

### 2. Mehrere Tags

```php
Route::get('/api/public/posts', $action)
    ->tag(['api', 'public', 'posts']);
```

### 3. Routen nach Tag Abrufen

```php
$apiRoutes = Route::getRoutesByTag('api');
```

### 4. Tag-Existenz Prüfen

```php
if (Route::router()->hasTag('api')) {
    echo "Has API routes";
}
```

### 5. Alle Tags Abrufen

```php
$allTags = Route::router()->getAllTags();
// ['api', 'public', 'admin', ...]
```

---

## Hilfsfunktionen

### 1. route()

```php
// Route nach Namen abrufen
$route = route('users.show');
```

### 2. current_route()

```php
// Aktuelle Route abrufen
$current = current_route();
echo $current->getUri();
```

### 3. previous_route()

```php
// Vorherige Route abrufen
$previous = previous_route();
```

### 4. route_is()

```php
// Routen-Namen prüfen (mit Wildcard-Unterstützung)
if (route_is('users.*')) {
    echo "User route";
}

if (route_is('admin.users.show')) {
    echo "Admin user show";
}
```

### 5. route_name()

```php
// Aktuellen Routen-Namen abrufen
$name = route_name();
// 'users.show'
```

### 6. router()

```php
// Router-Instanz abrufen
$router = router();
$routes = $router->getRoutes();
```

### 7. dispatch_route()

```php
// Route dispatchen
$route = dispatch_route('/users/123', 'GET');
```

### 8. route_url()

```php
// URL generieren
$url = route_url('users.show', ['id' => 123]);
// '/users/123'

$url = route_url('posts.show', ['slug' => 'hello-world']);
// '/posts/hello-world'
```

### 9. route_has()

```php
// Routen-Existenz prüfen
if (route_has('users.show')) {
    echo "Route exists";
}
```

### 10. route_stats()

```php
// Routen-Statistiken abrufen
$stats = route_stats();
/*
[
    'total' => 150,
    'methods' => ['GET' => 80, 'POST' => 40, ...],
    'named' => 120,
    'with_middleware' => 60,
    ...
]
*/
```

### 11. routes_by_tag()

```php
// Routen nach Tag abrufen
$apiRoutes = routes_by_tag('api');
```

### 12. route_back()

```php
// Zur vorherigen Route zurückkehren
$previous = route_back();
```

### 13-18. Zusätzliche Helpers

```php
// Prüfen ob aktuelle Route benannt ist
if (route_is('users.show')) {
    // ...
}

// Aktuelle Routen-Parameter abrufen
$route = current_route();
$params = $route->getParameters();

// Aktuelle Routen-Middleware abrufen
$middleware = current_route()->getMiddleware();

// Aktuelle Routen-Tags abrufen
$tags = current_route()->getTags();
```

---

## Routen-Shortcuts

### 1. auth()

```php
Route::get('/dashboard', $action)->auth();
// Fügt AuthMiddleware hinzu
```

### 2. guest()

```php
Route::get('/login', $action)->guest();
// Nur für nicht-authentifizierte Benutzer
```

### 3. api()

```php
Route::get('/api/data', $action)->api();
// API-Middleware
```

### 4. web()

```php
Route::get('/page', $action)->web();
// Web-Middleware (CSRF, Session, etc.)
```

### 5. cors()

```php
Route::get('/api/public', $action)->cors();
// CorsMiddleware
```

### 6. localhost()

```php
Route::get('/debug', $action)->localhost();
// Nur localhost (127.0.0.1)
```

### 7. secure()

```php
Route::get('/payment', $action)->secure();
// Nur HTTPS
```

### 8-10. Throttle-Shortcuts

```php
// 60 Anfragen pro Minute (Standard)
Route::post('/api/data', $action)->throttleStandard();

// 10 Anfragen pro Minute (Streng)
Route::post('/api/critical', $action)->throttleStrict();

// 1000 Anfragen pro Minute (Großzügig)
Route::post('/api/bulk', $action)->throttleGenerous();
```

### 11. public()

```php
Route::get('/page', $action)->public();
// Tag 'public'
```

### 12. private()

```php
Route::get('/page', $action)->private();
// Tag 'private'
```

### 13. admin()

```php
Route::get('/admin/users', $action)->admin();
// AuthMiddleware + AdminMiddleware + HTTPS + IP whitelist
```

### 14. apiEndpoint()

```php
Route::get('/api/data', $action)->apiEndpoint();
// API + CORS + JSON + throttle
```

---

## Routen-Makros

### 1. resource()

```php
use CloudCastle\Http\Router\Facade\Route;

// Erstellt RESTful-Routen für Ressource
Route::resource('/users', UserController::class);

// Erstellt:
// GET    /users           -> UserController::index
// GET    /users/create    -> UserController::create
// POST   /users           -> UserController::store
// GET    /users/{id}      -> UserController::show
// GET    /users/{id}/edit -> UserController::edit
// PUT    /users/{id}      -> UserController::update
// DELETE /users/{id}      -> UserController::destroy
```

### 2. apiResource()

```php
// API-Ressource (ohne create/edit-Seiten)
Route::apiResource('/posts', PostController::class, 100);

// Erstellt:
// GET    /posts       -> PostController::index    (throttle: 100/min)
// POST   /posts       -> PostController::store    (throttle: 100/min)
// GET    /posts/{id}  -> PostController::show     (throttle: 100/min)
// PUT    /posts/{id}  -> PostController::update   (throttle: 100/min)
// DELETE /posts/{id}  -> PostController::destroy  (throttle: 100/min)
```

### 3. crud()

```php
// Einfaches CRUD
Route::crud('/products', ProductController::class);

// Erstellt:
// GET    /products       -> ProductController::index
// POST   /products       -> ProductController::create
// GET    /products/{id}  -> ProductController::read
// PUT    /products/{id}  -> ProductController::update
// DELETE /products/{id}  -> ProductController::delete
```

### 4. auth()

```php
// Authentifizierungs-Routen
Route::auth();

// Erstellt:
// GET  /login            -> AuthController::showLoginForm
// POST /login            -> AuthController::login
// POST /logout           -> AuthController::logout
// GET  /register         -> AuthController::showRegisterForm
// POST /register         -> AuthController::register
// GET  /password/reset   -> AuthController::showResetForm
// POST /password/reset   -> AuthController::reset
```

### 5. adminPanel()

```php
// Admin-Panel mit IP-Whitelist
Route::adminPanel('/admin', ['192.168.1.0/24']);

// Erstellt (mit Auth + Admin Middleware + HTTPS):
// GET /admin/dashboard -> AdminController::dashboard
// GET /admin/users     -> AdminController::users
// GET /admin/settings  -> AdminController::settings
// GET /admin/logs      -> AdminController::logs
```

### 6. apiVersion()

```php
// API-Versionierung
Route::apiVersion('v1', function() {
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/posts', [PostController::class, 'index']);
});

// Routen verfügbar als /api/v1/users, /api/v1/posts
```

### 7. webhooks()

```php
// Webhooks mit IP-Whitelist
Route::webhooks('/webhooks', ['192.168.1.0/24']);

// Erstellt:
// POST /webhooks/github  -> WebhookController::github
// POST /webhooks/stripe  -> WebhookController::stripe
// POST /webhooks/custom  -> WebhookController::custom
```

---

## URL-Generierung

### 1. Basis-Generierung

```php
use CloudCastle\Http\Router\UrlGenerator;

$generator = new UrlGenerator();

$url = $generator->generate('users.show', ['id' => 123]);
// '/users/123'
```

### 2. Absolute URL

```php
$url = $generator->generate('users.show', ['id' => 123])
    ->absolute();
// 'http://example.com/users/123'
```

### 3. URL mit Domain

```php
$url = $generator->generate('users.show', ['id' => 123])
    ->toDomain('api.example.com');
// 'http://api.example.com/users/123'
```

### 4. URL mit Protokoll

```php
$url = $generator->generate('users.show', ['id' => 123])
    ->toProtocol('https');
// 'https://example.com/users/123'
```

### 5. URL mit Query-Parametern

```php
$url = $generator->generate('users.index', [], [
    'page' => 2,
    'limit' => 10,
    'sort' => 'name'
]);
// '/users?page=2&limit=10&sort=name'
```

### 6. Signierte URL

```php
$signedUrl = $generator->signed('verify.email', ['user' => 123], 3600);
// '/verify/email/123?signature=abc...&expires=1234567890'
```

### 7. Basis-URL Setzen

```php
$generator->setBaseUrl('https://api.example.com');
```

### 8-11. Kombinierte Generierung

```php
$url = $generator->generate('api.users.show', ['id' => 123])
    ->toDomain('api.example.com')
    ->toProtocol('https')
    ->absolute();
// 'https://api.example.com/api/users/123'

// Über Helper
$url = route_url('users.show', ['id' => 123]);
```

---

## Expression Language

### 1. Basis-Bedingung

```php
Route::get('/admin', $action)
    ->condition('request.ip == "192.168.1.1"');
```

### 2. Vergleichsoperatoren

```php
Route::get('/api/data', $action)
    ->condition('request.time > 9 and request.time < 18');

Route::get('/premium', $action)
    ->condition('user.level >= 5');

Route::get('/limited', $action)
    ->condition('request.count <= 100');
```

### 3. Logische Operatoren

```php
Route::get('/api/secure', $action)
    ->condition('request.ip == "192.168.1.1" and request.method == "GET"');

Route::get('/public', $action)
    ->condition('request.path == "/public" or request.path == "/open"');
```

### 4. ExpressionLanguage-Klasse

```php
use CloudCastle\Http\Router\ExpressionLanguage\ExpressionLanguage;

$expr = new ExpressionLanguage();

$result = $expr->evaluate('10 > 5 and 20 < 30', []);
// true

$result = $expr->evaluate('user.age >= 18', ['user' => ['age' => 25]]);
// true
```

### 5. Komplexe Ausdrücke

```php
Route::get('/api/restricted', $action)
    ->condition(
        '(request.ip == "192.168.1.1" or request.ip == "10.0.0.1") ' .
        'and request.time >= 9 and request.time <= 18'
    );
```

---

## Routen-Caching

### 1. Cache Aktivieren

```php
$router->enableCache('var/cache/routes');
```

### 2. Routen Kompilieren

```php
// Kompilieren
$router->compile();

// Erzwungene Kompilierung
$router->compile(force: true);
```

### 3. Aus Cache Laden

```php
if ($router->loadFromCache()) {
    echo "Routes loaded from cache";
} else {
    // Routen registrieren
    require 'routes/web.php';
    $router->compile();
}
```

### 4. Cache Löschen

```php
$router->clearCache();
```

### 5. Auto-Kompilierung

```php
$router->autoCompile();
// Kompiliert automatisch bei Änderungen
```

### 6. Cache-Laden Prüfen

```php
if ($router->isCacheLoaded()) {
    echo "Cache is loaded";
}
```

### Vollständiges Beispiel mit Caching

```php
use CloudCastle\Http\Router\Router;

$router = new Router();
$router->enableCache(__DIR__ . '/var/cache/routes');

if (!$router->loadFromCache()) {
    // Routen registrieren
    require __DIR__ . '/routes/web.php';
    require __DIR__ . '/routes/api.php';
    
    // Kompilieren
    $router->compile();
}

// Routen verwenden
$route = $router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
```

---

## Plugin-System

### 1. PluginInterface

```php
use CloudCastle\Http\Router\Contracts\PluginInterface;
use CloudCastle\Http\Router\Route;

interface PluginInterface
{
    public function beforeDispatch(Route $route, string $uri, string $method): void;
    public function afterDispatch(Route $route, mixed $result): mixed;
    public function onRouteRegistered(Route $route): void;
    public function onException(Route $route, \Exception $e): void;
}
```

### 2. Plugin Registrieren

```php
Route::registerPlugin(new LoggerPlugin());
```

### 3. Plugin Abmelden

```php
Route::unregisterPlugin('logger');
```

### 4. Plugin Abrufen

```php
$plugin = Route::getPlugin('logger');
```

### 5. Plugin-Existenz Prüfen

```php
if (Route::hasPlugin('logger')) {
    echo "Logger plugin registered";
}
```

### 6. Alle Plugins Abrufen

```php
$plugins = Route::getPlugins();
```

### 7. LoggerPlugin (eingebaut)

```php
use CloudCastle\Http\Router\Plugin\LoggerPlugin;

$logger = new LoggerPlugin('/var/log/routes.log');
Route::registerPlugin($logger);
```

### 8. AnalyticsPlugin (eingebaut)

```php
use CloudCastle\Http\Router\Plugin\AnalyticsPlugin;

$analytics = new AnalyticsPlugin();
Route::registerPlugin($analytics);

// Statistiken abrufen
$stats = $analytics->getStats();
```

### 9. ResponseCachePlugin (eingebaut)

```php
use CloudCastle\Http\Router\Plugin\ResponseCachePlugin;

$cache = new ResponseCachePlugin('/var/cache/responses', 3600);
Route::registerPlugin($cache);
```

### 10. AbstractPlugin

```php
use CloudCastle\Http\Router\Plugin\AbstractPlugin;

class MyPlugin extends AbstractPlugin
{
    public function beforeDispatch(Route $route, string $uri, string $method): void
    {
        // Logik vor Dispatch
    }
    
    public function afterDispatch(Route $route, mixed $result): mixed
    {
        // Logik nach Dispatch
        return $result;
    }
}
```

### 11-13. Plugin-Hooks

```php
class FullPlugin implements PluginInterface
{
    // Hook vor Dispatch
    public function beforeDispatch(Route $route, string $uri, string $method): void
    {
        echo "Before: $method $uri\n";
    }
    
    // Hook nach Dispatch
    public function afterDispatch(Route $route, mixed $result): mixed
    {
        echo "After dispatch\n";
        return $result;
    }
    
    // Hook bei Routen-Registrierung
    public function onRouteRegistered(Route $route): void
    {
        echo "Route registered: {$route->getUri()}\n";
    }
    
    // Hook bei Ausnahme
    public function onException(Route $route, \Exception $e): void
    {
        echo "Exception: {$e->getMessage()}\n";
    }
}
```

---

## Routen-Loader

### 1. JsonLoader

```php
use CloudCastle\Http\Router\Loader\JsonLoader;

$loader = new JsonLoader($router);
$loader->load(__DIR__ . '/routes.json');
```

**routes.json:**
```json
{
    "routes": [
        {
            "method": "GET",
            "uri": "/users",
            "action": "UserController@index",
            "name": "users.index"
        },
        {
            "method": "POST",
            "uri": "/users",
            "action": "UserController@store",
            "name": "users.store",
            "middleware": ["auth"],
            "throttle": [60, 1]
        }
    ]
}
```

### 2. YamlLoader

```php
use CloudCastle\Http\Router\Loader\YamlLoader;

$loader = new YamlLoader($router);
$loader->load(__DIR__ . '/routes.yaml');
```

**routes.yaml:**
```yaml
routes:
  - method: GET
    uri: /users
    action: UserController@index
    name: users.index
  
  - method: POST
    uri: /users
    action: UserController@store
    name: users.store
    middleware:
      - auth
    throttle: [60, 1]
```

### 3. XmlLoader

```php
use CloudCastle\Http\Router\Loader\XmlLoader;

$loader = new XmlLoader($router);
$loader->load(__DIR__ . '/routes.xml');
```

**routes.xml:**
```xml
<?xml version="1.0"?>
<routes>
    <route method="GET" uri="/users" action="UserController@index" name="users.index"/>
    <route method="POST" uri="/users" action="UserController@store" name="users.store">
        <middleware>auth</middleware>
        <throttle>60,1</throttle>
    </route>
</routes>
```

### 4. AttributeLoader

```php
use CloudCastle\Http\Router\Loader\AttributeLoader;

$loader = new AttributeLoader($router);
$loader->loadFromDirectory(__DIR__ . '/app/Controllers');
```

**UserController.php:**
```php
use CloudCastle\Http\Router\Attributes\Route as RouteAttribute;

#[RouteAttribute('/users', 'GET', name: 'users.index')]
class UserController
{
    #[RouteAttribute('/users/{id}', 'GET', name: 'users.show')]
    public function show(int $id)
    {
        return "User $id";
    }
}
```

### 5. PHP-Dateien (Standardweg)

```php
// routes/web.php
Route::get('/users', [UserController::class, 'index']);
Route::post('/users', [UserController::class, 'store']);

// routes/api.php
Route::group(['prefix' => '/api'], function() {
    Route::get('/users', [ApiUserController::class, 'index']);
});

// index.php
require 'routes/web.php';
require 'routes/api.php';
```

---

## PSR-Unterstützung

### 1. PSR-7 HTTP Message

```php
use Psr\Http\Message\ServerRequestInterface;
use Nyholm\Psr7\ServerRequestFactory;

$request = ServerRequestFactory::fromGlobals();
// PSR-7 Request-Objekt

// Verwendung mit Router
$uri = $request->getUri()->getPath();
$method = $request->getMethod();

$route = Route::dispatch($uri, $method);
```

### 2. PSR-15 HTTP Server Handler

```php
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class RouteHandler implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $uri = $request->getUri()->getPath();
        $method = $request->getMethod();
        
        $route = Route::dispatch($uri, $method);
        $result = $route->run();
        
        // PSR-7 Response zurückgeben
        return new Response(200, [], $result);
    }
}
```

### 3. Psr15MiddlewareAdapter

```php
use CloudCastle\Http\Router\Psr15\Psr15MiddlewareAdapter;

$adapter = new Psr15MiddlewareAdapter($router);

// Als PSR-15 Middleware verwenden
$response = $adapter->process($request, $handler);
```

---

## Action Resolver

CloudCastle HTTP Router unterstützt **6 Formate** für Routen-Actions:

### 1. Closure

```php
Route::get('/users', function() {
    return 'Users list';
});
```

### 2. Array [Controller::class, 'method']

```php
Route::get('/users', [UserController::class, 'index']);
```

### 3. String "Controller@method"

```php
Route::get('/users', 'UserController@index');
```

### 4. String "Controller::method"

```php
Route::get('/users', 'UserController::index');
```

### 5. Aufrufbarer Controller

```php
class ShowUserController
{
    public function __invoke(int $id)
    {
        return "User: $id";
    }
}

Route::get('/users/{id}', ShowUserController::class);
```

### 6. Dependency Injection

```php
class UserController
{
    public function __construct(
        private UserRepository $repository
    ) {}
    
    public function index()
    {
        return $this->repository->all();
    }
}

Route::get('/users', [UserController::class, 'index']);
// ActionResolver löst Abhängigkeiten automatisch auf
```

---

## Statistiken und Abfragen

### 1. getRouteStats()

```php
$stats = Route::getRouteStats();
/*
[
    'total' => 150,
    'named' => 120,
    'with_middleware' => 60,
    'with_tags' => 80,
    'methods' => ['GET' => 80, 'POST' => 40, ...],
    'domains' => ['api.example.com' => 30, ...],
    'ports' => [8080 => 20, ...],
]
*/
```

### 2. getRoutesByMethod()

```php
$getRoutes = Route::router()->getRoutesByMethod('GET');
$postRoutes = Route::router()->getRoutesByMethod('POST');
```

### 3. getRoutesByDomain()

```php
$apiRoutes = Route::router()->getRoutesByDomain('api.example.com');
```

### 4. getRoutesByPort()

```php
$routes = Route::router()->getRoutesByPort(8080);
```

### 5. getRoutesByPrefix()

```php
$apiRoutes = Route::router()->getRoutesByPrefix('/api');
```

### 6. getRoutesByUriPattern()

```php
$userRoutes = Route::router()->getRoutesByUriPattern('/users');
```

### 7. getRoutesByMiddleware()

```php
$authRoutes = Route::router()->getRoutesByMiddleware(AuthMiddleware::class);
```

### 8. getRoutesByController()

```php
$routes = Route::router()->getRoutesByController(UserController::class);
```

### 9. getRoutesWithIpRestrictions()

```php
$restrictedRoutes = Route::router()->getRoutesWithIpRestrictions();
```

### 10. getThrottledRoutes()

```php
$throttledRoutes = Route::router()->getThrottledRoutes();
```

### 11. getRoutesWithDomain()

```php
$domainRoutes = Route::router()->getRoutesWithDomain();
```

### 12. getRoutesWithPort()

```php
$portRoutes = Route::router()->getRoutesWithPort();
```

### 13. searchRoutes()

```php
$results = Route::router()->searchRoutes('user');
// Alle Routen die 'user' in URI oder Namen enthalten
```

### 14. getRoutesGroupedByMethod()

```php
$grouped = Route::getRoutesGroupedByMethod();
/*
[
    'GET' => [Route, Route, ...],
    'POST' => [Route, Route, ...],
    ...
]
*/
```

### 15. getRoutesGroupedByPrefix()

```php
$grouped = Route::getRoutesGroupedByPrefix();
/*
[
    '/api' => [Route, Route, ...],
    '/admin' => [Route, Route, ...],
    ...
]
*/
```

### 16. getRoutesGroupedByDomain()

```php
$grouped = Route::getRoutesGroupedByDomain();
/*
[
    'api.example.com' => [Route, Route, ...],
    'admin.example.com' => [Route, Route, ...],
    ...
]
*/
```

### 17. getRoutes()

```php
$allRoutes = Route::getRoutes();
```

### 18. getNamedRoutes()

```php
$namedRoutes = Route::getNamedRoutes();
```

### 19. getAllDomains()

```php
$domains = Route::router()->getAllDomains();
// ['api.example.com', 'admin.example.com', ...]
```

### 20. getAllPorts()

```php
$ports = Route::router()->getAllPorts();
// [8080, 8081, 443, ...]
```

### 21. getAllTags()

```php
$tags = Route::router()->getAllTags();
// ['api', 'admin', 'public', ...]
```

### 22. count()

```php
$total = Route::count();
echo "Total routes: $total";
```

### 23. getRoutesAsJson()

```php
$json = Route::getRoutesAsJson(JSON_PRETTY_PRINT);
echo $json;
```

### 24. getRoutesAsArray()

```php
$array = Route::getRoutesAsArray();
```

---

## Sicherheit

### 1. Path Traversal Protection

```php
// Router schützt automatisch gegen ../../../
Route::get('/files/{path}', function($path) {
    // $path enthält niemals ../
    return "File: $path";
});
```

### 2. SQL Injection Protection

```php
// Parameter werden automatisch validiert
Route::get('/users/{id}', function($id) {
    // Sicher in SQL zu verwenden
    return DB::find($id);
})->where('id', '[0-9]+');
```

### 3. XSS Protection

```php
Route::get('/search/{query}', function($query) {
    // Ausgabe escapen
    return htmlspecialchars($query);
});
```

### 4. Rate Limiting

```php
// DDoS-Schutz
Route::post('/api/submit', $action)
    ->throttle(60, 1);
```

### 5. IP Filtering

```php
// Nur vertrauenswürdige IPs whitelisten
Route::get('/admin', $action)
    ->whitelistIp(['192.168.1.0/24']);
```

### 6. HTTPS Enforcement

```php
// HTTPS erzwingen
Route::get('/payment', $action)->https();
Route::get('/secure', $action)->secure();
```

### 7. Protokoll-Einschränkungen

```php
// Nur HTTPS/WSS
Route::get('/ws/secure', $action)
    ->protocol(['wss']);
```

### 8. ReDoS Protection

```php
// Router schützt gegen Regex DoS
// Sichere Muster automatisch
Route::get('/users/{id}', $action)
    ->where('id', '[0-9]+');  // Sicher
```

### 9. Method Override Protection

```php
// Schutz gegen Methoden-Spoofing
// Router prüft echte HTTP-Methode
```

### 10. Cache Injection Protection

```php
// Sicheres Caching
$router->enableCache('var/cache/routes');
// Cache wird signiert und validiert
```

### 11. IP Spoofing Protection

```php
// Router prüft X-Forwarded-For
// und schützt gegen IP-Spoofing
```

### 12. Auto-Ban System

```php
// Automatisches Sperren angreifender IPs
$banManager = new BanManager(5, 3600);
Route::post('/login', $action)
    ->throttle(3, 1)
    ->getRateLimiter()
    ?->setBanManager($banManager);
```

---

## Ausnahmen

### 1. RouteNotFoundException

```php
try {
    $route = Route::dispatch('/nonexistent', 'GET');
} catch (\CloudCastle\Http\Router\Exceptions\RouteNotFoundException $e) {
    http_response_code(404);
    echo "404 Not Found";
}
```

### 2. MethodNotAllowedException

```php
try {
    $route = Route::dispatch('/users', 'DELETE');  // Methode nicht erlaubt
} catch (\CloudCastle\Http\Router\Exceptions\MethodNotAllowedException $e) {
    http_response_code(405);
    $allowed = $e->getAllowedMethods();
    header('Allow: ' . implode(', ', $allowed));
    echo "405 Method Not Allowed";
}
```

### 3. IpNotAllowedException

```php
try {
    $route = Route::dispatch('/admin', 'GET');
} catch (\CloudCastle\Http\Router\Exceptions\IpNotAllowedException $e) {
    http_response_code(403);
    echo "403 Forbidden: IP not allowed";
}
```

### 4. TooManyRequestsException

```php
try {
    $route = Route::dispatch('/api/submit', 'POST');
} catch (\CloudCastle\Http\Router\Exceptions\TooManyRequestsException $e) {
    http_response_code(429);
    $retryAfter = $e->getRetryAfter();
    header("Retry-After: $retryAfter");
    echo "429 Too Many Requests";
}
```

### 5. InsecureConnectionException

```php
try {
    $route = Route::dispatch('/payment', 'POST');
} catch (\CloudCastle\Http\Router\Exceptions\InsecureConnectionException $e) {
    http_response_code(403);
    echo "403 Forbidden: HTTPS required";
}
```

### 6. BannedException

```php
try {
    $route = Route::dispatch('/api/data', 'GET');
} catch (\CloudCastle\Http\Router\Exceptions\BannedException $e) {
    http_response_code(403);
    echo "403 Forbidden: IP is banned";
}
```

### 7. InvalidActionException

```php
try {
    Route::get('/test', 'InvalidController@method');
    $route = Route::dispatch('/test', 'GET');
    $route->run();
} catch (\CloudCastle\Http\Router\Exceptions\InvalidActionException $e) {
    http_response_code(500);
    echo "500 Internal Server Error: Invalid action";
}
```

### 8. RouterException

```php
try {
    // Beliebiger Router-Fehler
} catch (\CloudCastle\Http\Router\Exceptions\RouterException $e) {
    http_response_code(500);
    echo "Router Error: " . $e->getMessage();
}
```

---

## CLI-Tools

### 1. routes-list

```bash
# Alle Routen anzeigen
php bin/routes-list

# Mit Filter
php bin/routes-list --method=GET
php bin/routes-list --tag=api
php bin/routes-list --name=users.*
```

### 2. analyse

```bash
# Routen-Analyse
php bin/analyse

# Zeigt:
# - Gesamt-Routen-Anzahl
# - Routen nach Methoden
# - Routen nach Domains
# - Routen mit Middleware
# - Etc.
```

### 3. router

```bash
# Router-Verwaltung
php bin/router compile        # Cache kompilieren
php bin/router clear          # Cache löschen
php bin/router stats          # Statistiken
```

---

## Erweiterte Beispiele

### Beispiel 1: REST API mit vollem Schutz

```php
use CloudCastle\Http\Router\Facade\Route;
use CloudCastle\Http\Router\BanManager;
use CloudCastle\Http\Router\Middleware\{
    AuthMiddleware,
    CorsMiddleware,
    SecurityLogger
};

// Auto-Ban einrichten
$banManager = new BanManager(5, 3600);

// API v1
Route::group([
    'prefix' => '/api/v1',
    'middleware' => [CorsMiddleware::class, SecurityLogger::class],
    'domain' => 'api.example.com',
    'https' => true,
    'tags' => ['api', 'v1'],
], function() use ($banManager) {
    
    // Öffentliche Endpunkte
    Route::get('/posts', [PostController::class, 'index'])
        ->name('api.v1.posts.index')
        ->throttle(100, 1)
        ->tag('public');
    
    // Geschützte Endpunkte
    Route::group(['middleware' => [AuthMiddleware::class]], function() use ($banManager) {
        
        Route::post('/posts', [PostController::class, 'store'])
            ->name('api.v1.posts.store')
            ->throttle(20, 1)
            ->getRateLimiter()
            ?->setBanManager($banManager);
        
        Route::put('/posts/{id}', [PostController::class, 'update'])
            ->name('api.v1.posts.update')
            ->where('id', '[0-9]+')
            ->throttle(30, 1);
        
        Route::delete('/posts/{id}', [PostController::class, 'destroy'])
            ->name('api.v1.posts.destroy')
            ->where('id', '[0-9]+')
            ->throttle(10, 1);
    });
});
```

### Beispiel 2: Microservice-Architektur

```php
// User Service (Port 8081)
Route::group([
    'prefix' => '/users',
    'port' => 8081,
    'domain' => 'users.service.local',
    'tags' => ['user-service', 'microservice'],
], function() {
    Route::get('/', [UserServiceController::class, 'index']);
    Route::get('/{id}', [UserServiceController::class, 'show'])
        ->where('id', '[0-9]+');
    Route::post('/', [UserServiceController::class, 'create']);
});

// Product Service (Port 8082)
Route::group([
    'prefix' => '/products',
    'port' => 8082,
    'domain' => 'products.service.local',
    'tags' => ['product-service', 'microservice'],
], function() {
    Route::get('/', [ProductServiceController::class, 'index']);
    Route::get('/{id}', [ProductServiceController::class, 'show']);
});

// Order Service (Port 8083)
Route::group([
    'prefix' => '/orders',
    'port' => 8083,
    'domain' => 'orders.service.local',
    'tags' => ['order-service', 'microservice'],
], function() {
    Route::post('/', [OrderServiceController::class, 'create']);
    Route::get('/{id}', [OrderServiceController::class, 'show']);
});
```

### Beispiel 3: SaaS-Plattform mit Tarifen

```php
// Free Tier
Route::group([
    'prefix' => '/api/free',
    'middleware' => [AuthMiddleware::class],
    'tags' => ['free-tier'],
], function() {
    Route::get('/data', $action)
        ->throttle(10, 1);  // 10 Anfragen/Min
});

// Pro Tier
Route::group([
    'prefix' => '/api/pro',
    'middleware' => [AuthMiddleware::class, ProMiddleware::class],
    'tags' => ['pro-tier'],
], function() {
    Route::get('/data', $action)
        ->throttle(100, 1);  // 100 Anfragen/Min
});

// Enterprise Tier
Route::group([
    'prefix' => '/api/enterprise',
    'middleware' => [AuthMiddleware::class, EnterpriseMiddleware::class],
    'tags' => ['enterprise-tier'],
], function() {
    Route::get('/data', $action)
        ->throttle(1000, 1);  // 1000 Anfragen/Min
});
```

### Beispiel 4: Multi-Domain-Anwendung

```php
// Hauptseite
Route::group(['domain' => 'example.com'], function() {
    Route::get('/', [HomeController::class, 'index']);
    Route::get('/about', [AboutController::class, 'index']);
});

// API-Subdomain
Route::group(['domain' => 'api.example.com', 'https' => true], function() {
    Route::apiResource('/users', UserApiController::class);
    Route::apiResource('/posts', PostApiController::class);
});

// Admin
Route::group([
    'domain' => 'admin.example.com',
    'https' => true,
    'middleware' => [AuthMiddleware::class, AdminMiddleware::class],
    'whitelistIp' => ['192.168.1.0/24'],
], function() {
    Route::get('/dashboard', [AdminController::class, 'dashboard']);
    Route::resource('/users', AdminUserController::class);
});

// Blog
Route::group(['domain' => 'blog.example.com'], function() {
    Route::get('/', [BlogController::class, 'index']);
    Route::get('/{slug}', [BlogController::class, 'show']);
});
```

### Beispiel 5: Caching für Performance

```php
use CloudCastle\Http\Router\Router;
use CloudCastle\Http\Router\Plugin\ResponseCachePlugin;

$router = new Router();

// Routen-Cache aktivieren
$router->enableCache(__DIR__ . '/var/cache/routes');

// Response-Caching-Plugin hinzufügen
$responseCache = new ResponseCachePlugin(__DIR__ . '/var/cache/responses', 3600);
$router->registerPlugin($responseCache);

// Aus Cache laden oder registrieren
if (!$router->loadFromCache()) {
    require __DIR__ . '/routes/web.php';
    require __DIR__ . '/routes/api.php';
    $router->compile();
}

// Dispatch
$route = $router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
$response = $route->run();

echo $response;
```

---

## Fazit

CloudCastle HTTP Router bietet **209+ Funktionen** zum Erstellen moderner, sicherer und hochperformanter Webanwendungen auf PHP 8.2+.

### Hauptvorteile:

- ⚡ **Hohe Performance:** 54,891 req/sec
- 🔒 **Umfassende Sicherheit:** 12+ Schutzmechanismen
- 💎 **Reiche Funktionalität:** 209+ Methoden
- 💾 **Effizienter Speicher:** 1.32 KB/Route
- 📊 **Skalierbarkeit:** 1,160,000+ Routen
- ✅ **Zuverlässigkeit:** 501 Tests, 0 Fehler

### Nächste Schritte:

1. Studieren Sie [API Reference](API_REFERENCE.md) für detaillierte Informationen
2. Sehen Sie sich [Beispiele](../../examples/) für praktische Anwendung an
3. Lesen Sie [FAQ](FAQ.md) für Antworten auf häufige Fragen
4. Informieren Sie sich über [Sicherheitsberichte](SECURITY_REPORT.md)
5. Prüfen Sie [Performance-Analyse](PERFORMANCE_ANALYSIS.md)

---

**© 2024 CloudCastle HTTP Router**  
**Version:** 1.1.1  
**Lizenz:** MIT

[⬆ Nach oben](#cloudcastle-http-router---vollständiges-benutzerhandbuch)


---

## 📚 Dokumentationsnavigation

[README](../../README.md) | [USER_GUIDE](USER_GUIDE.md) | [FEATURES_INDEX](FEATURES_INDEX.md) | [API_REFERENCE](API_REFERENCE.md) | [ALL_FEATURES](ALL_FEATURES.md) | [TESTS_SUMMARY](TESTS_SUMMARY.md) | [PERFORMANCE](PERFORMANCE_ANALYSIS.md) | [SECURITY](SECURITY_REPORT.md) | [COMPARISON](COMPARISON.md) | [FAQ](FAQ.md) | [DOC_SUMMARY](DOCUMENTATION_SUMMARY.md)

**Detaillierte Dokumentation:** [Features](features/) (22 Dateien) | [Tests](tests/) (7 Berichte)

---
