# Vollständige Liste der CloudCastle HTTP Router Funktionen

[English](../en/ALL_FEATURES.md) | [Русский](../ru/ALL_FEATURES.md) | [**Deutsch**](ALL_FEATURES.md) | [Français](../fr/ALL_FEATURES.md) | [中文](../zh/ALL_FEATURES.md)

---

## 📚 Dokumentationsnavigation

[README](../../README.md) | [USER_GUIDE](USER_GUIDE.md) | [FEATURES_INDEX](FEATURES_INDEX.md) | [API_REFERENCE](API_REFERENCE.md) | [ALL_FEATURES](ALL_FEATURES.md) | [TESTS_SUMMARY](TESTS_SUMMARY.md) | [PERFORMANCE](PERFORMANCE_ANALYSIS.md) | [SECURITY](SECURITY_REPORT.md) | [COMPARISON](COMPARISON.md) | [FAQ](FAQ.md) | [DOC_SUMMARY](DOCUMENTATION_SUMMARY.md)

**Detaillierte Dokumentation:** [Features](features/) (22 Dateien) | [Tests](tests/) (7 Berichte)

---

## Inhalt

- [1. Grundlegendes Routing](#1-grundlegendes-routing)
- [2. Helper-Funktionen](#2-helper-funktionen)
- [3. Routen-Kurzformen](#3-routen-kurzformen)
- [4. Routen-Makros](#4-routen-makros)
- [5. Routen-Gruppen](#5-routen-gruppen)
- [6. Middleware](#6-middleware)
- [7. Rate Limiting](#7-rate-limiting)
- [8. IP-Filterung](#8-ip-filterung)
- [9. Auto-Ban-System](#9-auto-ban-system)
- [10. Benannte Routen](#10-benannte-routen)
- [11. Tags](#11-tags)
- [12. Routen-Parameter](#12-routen-parameter)
- [13. Ausdruckssprache](#13-ausdruckssprache)
- [14. URL-Generierung](#14-url-generierung)
- [15. Caching](#15-caching)
- [16. Plugins](#16-plugins)
- [17. Loader](#17-loader)
- [18. PSR-Unterstützung](#18-psr-unterstützung)
- [19. Action Resolver](#19-action-resolver)
- [20. Statistiken und Filterung](#20-statistiken-und-filterung)

---

## 1. Grundlegendes Routing

### HTTP-Methoden

```php
use CloudCastle\Http\Router\Router;

$router = new Router();

// Alle Standard-Methoden
$router->get('/users', $action);
$router->post('/users', $action);
$router->put('/users/{id}', $action);
$router->patch('/users/{id}', $action);
$router->delete('/users/{id}', $action);

// Benutzerdefinierte Methoden
$router->view('/page', $action);  // VIEW-Methode
$router->custom('PURGE', '/cache', $action);  // Beliebige Methode

// Mehrere Methoden
$router->match(['GET', 'POST'], '/form', $action);
$router->any('/endpoint', $action);  // Alle Methoden
```

### Facade API

```php
use CloudCastle\Http\Router\Facade\Route;

Route::get('/api/users', $action);
Route::post('/api/users', $action);
// Und so weiter...
```

---

## 2. Helper-Funktionen

### route()

Route nach Namen oder aktuelle Route abrufen:

```php
// Route nach Namen abrufen
$route = route('users.show');

// Aktuelle Route abrufen
$current = route();
```

### current_route()

Aktuelle Route abrufen:

```php
$currentRoute = current_route();
echo $currentRoute->getName();
```

### previous_route()

Vorherige Route abrufen:

```php
$prevRoute = previous_route();
```

### route_is()

Aktuellen Routennamen prüfen:

```php
if (route_is('users.index')) {
    // Aktuelle Route ist users.index
}
```

### route_name()

Aktuellen Routennamen abrufen:

```php
$name = route_name(); // 'users.show'
```

### router()

Router-Instanz abrufen:

```php
$router = router();
$stats = $router->getRouteStats();
```

### dispatch_route()

Aktuelle HTTP-Anfrage weiterleiten:

```php
$route = dispatch_route();
if ($route) {
    echo $route->run();
}
```

---

## 3. Routen-Kurzformen

### resource()

RESTful-Ressourcen-Routen erstellen:

```php
Route::resource('users', UserController::class);
// Erstellt: GET, POST, PUT, PATCH, DELETE Routen
```

### apiResource()

API-Ressourcen-Routen erstellen:

```php
Route::apiResource('users', ApiUserController::class);
// Erstellt: GET, POST, PUT, PATCH, DELETE Routen (keine View-Routen)
```

### crud()

CRUD-Operationen erstellen:

```php
Route::crud('products', ProductController::class);
// Erstellt: index, show, store, update, destroy
```

### auth()

Authentifizierungs-Routen erstellen:

```php
Route::auth();
// Erstellt: login, register, logout, password reset Routen
```

### adminPanel()

Admin-Panel-Routen erstellen:

```php
Route::adminPanel();
// Erstellt: dashboard, users, settings Routen
```

### apiVersion()

API-Versionierungs-Routen erstellen:

```php
Route::apiVersion('v1', function() {
    Route::get('/users', $action);
});
```

### webhooks()

Webhook-Routen erstellen:

```php
Route::webhooks('stripe', StripeWebhookController::class);
```

---

## 4. Routen-Makros

### Benutzerdefinierte Makros

```php
use CloudCastle\Http\Router\Macro\MacroManager;

MacroManager::macro('admin', function($prefix, $controller) {
    Route::group(['prefix' => $prefix, 'middleware' => 'admin'], function() use ($controller) {
        Route::get('/', [$controller, 'index']);
        Route::get('/create', [$controller, 'create']);
        Route::post('/', [$controller, 'store']);
        Route::get('/{id}', [$controller, 'show']);
        Route::get('/{id}/edit', [$controller, 'edit']);
        Route::put('/{id}', [$controller, 'update']);
        Route::delete('/{id}', [$controller, 'destroy']);
    });
});

// Verwendung
Route::admin('users', UserController::class);
```

---

## 5. Routen-Gruppen

### Grundlegende Gruppen

```php
Route::group(['prefix' => 'api/v1'], function() {
    Route::get('/users', $action);
    Route::get('/posts', $action);
});
```

### Erweiterte Gruppen

```php
Route::group([
    'prefix' => 'admin',
    'middleware' => ['auth', 'admin'],
    'domain' => 'admin.example.com',
    'namespace' => 'Admin',
    'as' => 'admin.',
    'where' => ['id' => '[0-9]+']
], function() {
    Route::get('/dashboard', 'DashboardController@index');
    Route::resource('users', 'UserController');
});
```

### Verschachtelte Gruppen

```php
Route::group(['prefix' => 'api'], function() {
    Route::group(['prefix' => 'v1'], function() {
        Route::get('/users', $action);
    });
    
    Route::group(['prefix' => 'v2'], function() {
        Route::get('/users', $action);
    });
});
```

---

## 6. Middleware

### Globales Middleware

```php
$router->middleware([
    CorsMiddleware::class,
    SecurityMiddleware::class
]);
```

### Routen-Middleware

```php
Route::get('/admin', $action)->middleware('auth');
Route::post('/api', $action)->middleware(['auth', 'throttle']);
```

### Gruppen-Middleware

```php
Route::group(['middleware' => 'auth'], function() {
    Route::get('/profile', $action);
    Route::get('/settings', $action);
});
```

### Eingebaute Middleware

```php
// Authentifizierung
Route::get('/protected', $action)->middleware(AuthMiddleware::class);

// CORS
Route::get('/api', $action)->middleware(CorsMiddleware::class);

// HTTPS-Erzwingung
Route::get('/secure', $action)->middleware(HttpsEnforcement::class);

// Sicherheits-Protokollierung
Route::get('/sensitive', $action)->middleware(SecurityLogger::class);

// SSRF-Schutz
Route::get('/proxy', $action)->middleware(SsrfProtection::class);
```

---

## 7. Rate Limiting

### Grundlegendes Rate Limiting

```php
Route::get('/api', $action)->throttle(60, 1); // 60 Anfragen pro Minute
Route::post('/login', $action)->throttle(5, 1); // 5 Anfragen pro Minute
```

### Zeiteinheiten

```php
use CloudCastle\Http\Router\RateLimiting\TimeUnit;

Route::get('/api', $action)->throttle(100, TimeUnit::HOUR);
Route::post('/upload', $action)->throttle(10, TimeUnit::DAY);
```

### Benutzerdefinierte Schlüssel

```php
Route::get('/api', $action)->throttle(60, 1, 'user:' . $userId);
Route::post('/api', $action)->throttle(100, 1, 'api_key:' . $apiKey);
```

### Rate Limiter Klasse

```php
use CloudCastle\Http\Router\RateLimiting\RateLimiter;

$limiter = new RateLimiter(60, TimeUnit::MINUTE);
$limiter->setKey('user:' . $userId);
$limiter->check();
```

### Vordefinierte Limits

```php
Route::get('/api', $action)->throttleStandard(); // 60 req/min
Route::post('/api', $action)->throttleStrict();   // 10 req/min
Route::get('/api', $action)->throttleGenerous(); // 1000 req/min
```

---

## 8. IP-Filterung

### Whitelist

```php
Route::get('/admin', $action)->whitelistIp(['192.168.1.0/24', '10.0.0.1']);
Route::group(['whitelistIp' => ['192.168.1.0/24']], function() {
    Route::get('/admin', $action);
});
```

### Blacklist

```php
Route::get('/api', $action)->blacklistIp(['192.168.1.100', '10.0.0.50']);
Route::group(['blacklistIp' => ['192.168.1.100']], function() {
    Route::get('/api', $action);
});
```

### CIDR-Unterstützung

```php
Route::get('/admin', $action)->whitelistIp([
    '192.168.1.0/24',    // 192.168.1.1-254
    '10.0.0.0/8',        // 10.0.0.0-10.255.255.255
    '172.16.0.0/12'      // 172.16.0.0-172.31.255.255
]);
```

### IP-Spoofing-Schutz

```php
Route::get('/api', $action)->enableIpSpoofingProtection();
```

---

## 9. Auto-Ban-System

### Grundlegendes Auto-Ban

```php
use CloudCastle\Http\Router\RateLimiting\BanManager;

$banManager = new BanManager(5, 3600); // Ban nach 5 Verstößen für 1 Stunde

Route::post('/login', $action)
    ->throttle(3, 1)
    ->getRateLimiter()?->setBanManager($banManager);
```

### Ban-Verwaltung

```php
$banManager = new BanManager();

// IP manuell bannen
$banManager->ban('192.168.1.100', 3600);

// IP entbannen
$banManager->unban('192.168.1.100');

// Prüfen ob IP gebannt ist
if ($banManager->isBanned('192.168.1.100')) {
    throw new BannedException();
}

// Alle gebannten IPs abrufen
$bannedIps = $banManager->getBannedIps();

// Alle Bans löschen
$banManager->clearAll();
```

### Auto-Ban-Konfiguration

```php
$banManager = new BanManager(
    $violationThreshold = 5,    // Ban nach 5 Verstößen
    $banDuration = 3600,        // Ban für 1 Stunde
    $gracePeriod = 300          // Schonfrist von 5 Minuten
);
```

---

## 10. Benannte Routen

### Grundlegende Benennung

```php
Route::get('/users/{id}', $action)->name('users.show');
Route::get('/users', $action)->name('users.index');
```

### Gruppen-Benennung

```php
Route::group(['as' => 'admin.'], function() {
    Route::get('/dashboard', $action)->name('dashboard');
    // Erstellt Routenname: admin.dashboard
});
```

### Routenname-Helper

```php
// Route nach Namen abrufen
$route = Route::getRouteByName('users.show');

// Aktuellen Routennamen abrufen
$name = Route::currentRouteName();

// Prüfen ob aktuelle Route Muster entspricht
if (Route::currentRouteNamed('users.*')) {
    // Aktuelle Route beginnt mit 'users.'
}

// Alle benannten Routen abrufen
$namedRoutes = Route::getNamedRoutes();
```

### Auto-Benennung

```php
Route::enableAutoNaming();

Route::get('/users', $action); // Auto-benannt: users.index
Route::get('/users/{id}', $action); // Auto-benannt: users.show
Route::post('/users', $action); // Auto-benannt: users.store
```

---

## 11. Tags

### Grundlegende Tags

```php
Route::get('/api/users', $action)->tag('api');
Route::get('/api/posts', $action)->tag('api');
Route::get('/web/about', $action)->tag('web');
```

### Mehrere Tags

```php
Route::get('/api/users', $action)->tag(['api', 'public']);
Route::get('/api/admin', $action)->tag(['api', 'admin']);
```

### Gruppen-Tags

```php
Route::group(['tag' => 'api'], function() {
    Route::get('/users', $action);
    Route::get('/posts', $action);
});
```

### Tag-Operationen

```php
// Routen nach Tag abrufen
$apiRoutes = Route::getRoutesByTag('api');

// Prüfen ob Route Tag hat
if ($route->hasTag('api')) {
    // Route hat 'api' Tag
}

// Alle Tags abrufen
$allTags = Route::getAllTags();
```

---

## 12. Routen-Parameter

### Grundlegende Parameter

```php
Route::get('/users/{id}', $action);
Route::get('/posts/{slug}', $action);
Route::get('/categories/{category}/posts/{post}', $action);
```

### Optionale Parameter

```php
Route::get('/users/{id?}', $action);
Route::get('/posts/{slug?}', $action);
```

### Parameter-Einschränkungen

```php
Route::get('/users/{id}', $action)->where('id', '[0-9]+');
Route::get('/posts/{slug}', $action)->where('slug', '[a-z0-9-]+');
Route::get('/users/{id}/posts/{post}', $action)
    ->where(['id' => '[0-9]+', 'post' => '[0-9]+']);
```

### Inline-Einschränkungen

```php
Route::get('/users/{id:[0-9]+}', $action);
Route::get('/posts/{slug:[a-z0-9-]+}', $action);
```

### Standardwerte

```php
Route::get('/users/{id}', $action)->defaults(['id' => 1]);
Route::get('/posts/{page?}', $action)->defaults(['page' => 1]);
```

### Parameter-Zugriff

```php
$route = Route::get('/users/{id}', function($id) {
    return "User ID: $id";
});

// Parameter abrufen
$params = $route->getParameters();
$id = $route->getParameter('id');
```

---

## 13. Ausdruckssprache

### Grundlegende Ausdrücke

```php
Route::get('/users/{id}', $action)
    ->where('id', 'expr: id > 0 and id < 1000');
```

### Komplexe Ausdrücke

```php
Route::get('/posts/{year}/{month}', $action)
    ->where('year', 'expr: year >= 2020 and year <= 2030')
    ->where('month', 'expr: month >= 1 and month <= 12');
```

### Ausdrucksfunktionen

```php
Route::get('/files/{filename}', $action)
    ->where('filename', 'expr: strlen(filename) > 0 and strlen(filename) < 255');
```

---

## 14. URL-Generierung

### Grundlegende URL-Generierung

```php
// URL für benannte Route generieren
$url = route('users.show', ['id' => 1]);
// Ergebnis: /users/1

// URL mit Query-Parametern generieren
$url = route('users.index', [], ['page' => 2, 'sort' => 'name']);
// Ergebnis: /users?page=2&sort=name
```

### URL-Helper

```php
// Aktuelle URL abrufen
$currentUrl = url()->current();

// Vollständige URL abrufen
$fullUrl = url()->full();

// Vorherige URL abrufen
$previousUrl = url()->previous();

// Sichere URL generieren
$secureUrl = url()->secure('users/1');
```

### Routen-URL-Generierung

```php
$route = Route::get('/users/{id}', $action)->name('users.show');

// URL generieren
$url = $route->url(['id' => 1]);
$url = $route->url(['id' => 1], ['absolute' => true]);
```

---

## 15. Caching

### Routen-Caching

```php
$router->enableCache('cache/routes.php');

// Routen zu Cache kompilieren
$router->compile();

// Aus Cache laden
if (!$router->loadFromCache()) {
    require 'routes/web.php';
    $router->compile();
}
```

### Antwort-Caching

```php
Route::get('/api/users', $action)->cache(3600); // Cache für 1 Stunde
Route::get('/api/posts', $action)->cache(7200, ['tag' => 'posts']); // Cache mit Tags
```

### Cache-Tags

```php
Route::get('/api/users', $action)->cache(3600, ['tag' => 'users']);
Route::get('/api/posts', $action)->cache(3600, ['tag' => 'posts']);

// Cache nach Tag löschen
Cache::clearByTag('users');
```

---

## 16. Plugins

### Eingebaute Plugins

```php
use CloudCastle\Http\Router\Plugin\LoggerPlugin;
use CloudCastle\Http\Router\Plugin\AnalyticsPlugin;
use CloudCastle\Http\Router\Plugin\ResponseCachePlugin;

$router->addPlugin(new LoggerPlugin());
$router->addPlugin(new AnalyticsPlugin());
$router->addPlugin(new ResponseCachePlugin());
```

### Benutzerdefinierte Plugins

```php
use CloudCastle\Http\Router\Plugin\PluginInterface;

class CustomPlugin implements PluginInterface
{
    public function beforeDispatch($request, $response)
    {
        // Vor Routen-Weiterleitung ausführen
    }
    
    public function afterDispatch($request, $response, $route)
    {
        // Nach Routen-Weiterleitung ausführen
    }
}

$router->addPlugin(new CustomPlugin());
```

---

## 17. Loader

### Routen-Loader

```php
use CloudCastle\Http\Router\Loader\FileLoader;
use CloudCastle\Http\Router\Loader\DatabaseLoader;

// Routen aus Datei laden
$loader = new FileLoader('routes/web.php');
$loader->load($router);

// Routen aus Datenbank laden
$loader = new DatabaseLoader($connection);
$loader->load($router);
```

### Benutzerdefinierte Loader

```php
use CloudCastle\Http\Router\Loader\LoaderInterface;

class CustomLoader implements LoaderInterface
{
    public function load(Router $router)
    {
        // Routen aus benutzerdefinierter Quelle laden
    }
}
```

---

## 18. PSR-Unterstützung

### PSR-7 Request/Response

```php
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

Route::get('/api/users', function(ServerRequestInterface $request): ResponseInterface {
    $response = new Response();
    $response->getBody()->write(json_encode(['users' => []]));
    return $response->withHeader('Content-Type', 'application/json');
});
```

### PSR-15 Middleware

```php
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class CustomMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // Anfrage verarbeiten
        return $handler->handle($request);
    }
}

Route::get('/api', $action)->middleware(CustomMiddleware::class);
```

### PSR-11 Container

```php
use Psr\Container\ContainerInterface;

Route::get('/api/users', function(ContainerInterface $container) {
    $userService = $container->get(UserService::class);
    return $userService->getAll();
});
```

---

## 19. Action Resolver

### Controller-Aktionen

```php
Route::get('/users', 'UserController@index');
Route::get('/users/{id}', [UserController::class, 'show']);
Route::get('/users', UserController::class . '@index');
```

### Closure-Aktionen

```php
Route::get('/users', function() {
    return 'Users list';
});

Route::get('/users/{id}', function($id) {
    return "User ID: $id";
});
```

### Klassen-Aktionen

```php
class UserAction
{
    public function __invoke($id)
    {
        return "User ID: $id";
    }
}

Route::get('/users/{id}', UserAction::class);
```

### Dependency Injection

```php
Route::get('/users', function(UserService $userService) {
    return $userService->getAll();
});

Route::get('/users/{id}', [UserController::class, 'show']);
```

---

## 20. Statistiken und Filterung

### Routen-Statistiken

```php
$stats = $router->getRouteStats();

echo "Gesamte Routen: " . $stats->getTotalRoutes();
echo "Benannte Routen: " . $stats->getNamedRoutes();
echo "Gruppierte Routen: " . $stats->getGroupedRoutes();
echo "Middleware-Routen: " . $stats->getMiddlewareRoutes();
```

### Routen-Filterung

```php
// Nach Methode filtern
$getRoutes = $router->getRoutesByMethod('GET');

// Nach Muster filtern
$apiRoutes = $router->getRoutesByPattern('/api/*');

// Nach Middleware filtern
$authRoutes = $router->getRoutesByMiddleware('auth');

// Nach Tag filtern
$publicRoutes = $router->getRoutesByTag('public');
```

### Performance-Statistiken

```php
$perfStats = $router->getPerformanceStats();

echo "Durchschnittliche Weiterleitungszeit: " . $perfStats->getAverageDispatchTime();
echo "Speicherverbrauch: " . $perfStats->getMemoryUsage();
echo "Cache-Trefferrate: " . $perfStats->getCacheHitRate();
```

---

## Zusammenfassung

CloudCastle HTTP Router bietet **209+ Funktionen** in 20 Hauptkategorien:

1. **Grundlegendes Routing** - Alle HTTP-Methoden und benutzerdefinierte Methoden
2. **Helper-Funktionen** - Praktische Routen-Helper
3. **Routen-Kurzformen** - Vorgefertigte Routensammlungen
4. **Routen-Makros** - Benutzerdefinierte Routenmuster
5. **Routen-Gruppen** - Organisierte Routensammlungen
6. **Middleware** - Anfrage/Antwort-Verarbeitung
7. **Rate Limiting** - DDoS- und Missbrauchsschutz
8. **IP-Filterung** - Zugriffskontrolle nach IP
9. **Auto-Ban-System** - Automatisches IP-Bannen
10. **Benannte Routen** - Routenidentifikation
11. **Tags** - Routenkategorisierung
12. **Routen-Parameter** - Dynamische URL-Segmente
13. **Ausdruckssprache** - Erweiterte Parameter-Validierung
14. **URL-Generierung** - Dynamische URL-Erstellung
15. **Caching** - Performance-Optimierung
16. **Plugins** - Erweiterbare Architektur
17. **Loader** - Routen-Ladestrategien
18. **PSR-Unterstützung** - Standards-Konformität
19. **Action Resolver** - Flexible Aktionsbehandlung
20. **Statistiken** - Routenanalyse und Filterung

Dieser umfassende Funktionsumfang macht CloudCastle HTTP Router zur vollständigsten Routing-Lösung für PHP-Anwendungen.

---

## 📚 Siehe auch
- [USER_GUIDE.md](USER_GUIDE.md) - Vollständiges Benutzerhandbuch
- [FEATURES_INDEX.md](FEATURES_INDEX.md) - Funktionskategorien
- [API_REFERENCE.md](API_REFERENCE.md) - API-Referenz
- [FAQ.md](FAQ.md) - Häufig gestellte Fragen

---

© 2024 CloudCastle HTTP Router  
[⬆ Nach oben](#vollständige-liste-der-cloudcastle-http-router-funktionen)