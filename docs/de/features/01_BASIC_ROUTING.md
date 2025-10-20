# Grundlegendes Routing

[English](../../en/features/01_BASIC_ROUTING.md) | [Русский](../../ru/features/01_BASIC_ROUTING.md) | [**Deutsch**](01_BASIC_ROUTING.md) | [Français](../../fr/features/01_BASIC_ROUTING.md) | [中文](../../zh/features/01_BASIC_ROUTING.md)

---

## 📚 Dokumentationsnavigation

[README](../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [API_REFERENCE](../API_REFERENCE.md) | [ALL_FEATURES](../ALL_FEATURES.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [PERFORMANCE](../PERFORMANCE_ANALYSIS.md) | [SECURITY](../SECURITY_REPORT.md) | [COMPARISON](../COMPARISON.md) | [FAQ](../FAQ.md)

**Detaillierte Dokumentation:** [01](01_BASIC_ROUTING.md) | [02](02_ROUTE_PARAMETERS.md) | [03](03_ROUTE_GROUPS.md) | [04](04_RATE_LIMITING.md) | [05](05_IP_FILTERING.md) | [06](06_MIDDLEWARE.md) | [07](07_NAMED_ROUTES.md) | [08](08_TAGS.md) | [09](09_HELPER_FUNCTIONS.md) | [10](10_ROUTE_SHORTCUTS.md) | [11](11_ROUTE_MACROS.md) | [12](12_URL_GENERATION.md) | [13](13_EXPRESSION_LANGUAGE.md) | [14](14_CACHING.md) | [15](15_PLUGINS.md) | [16](16_LOADERS.md) | [17](17_PSR_SUPPORT.md) | [18](18_ACTION_RESOLVER.md) | [19](19_STATISTICS.md) | [20](20_SECURITY.md) | [21](21_EXCEPTIONS.md) | [22](22_CLI_TOOLS.md)

---

**Kategorie:** Kernfunktionen  
**Anzahl Methoden:** 13  
**Komplexität:** ⭐ Anfänger-Level

---

## Beschreibung

Grundlegendes Routing ist die fundamentale Fähigkeit von CloudCastle HTTP Router, die es ermöglicht, Handler für verschiedene HTTP-Methoden und URIs zu registrieren.

## Funktionen

### 1. GET-Route

**Methode:** `Route::get(string $uri, mixed $action): Route`

**Beschreibung:** Registriert eine Route für HTTP GET-Anfragen.

**Parameter:**
- `$uri` - Route-URI (z.B. `/users`, `/posts/{id}`)
- `$action` - Aktion (Closure, Array, Controller-String)

**Gibt zurück:** `Route`-Objekt für Method Chaining

**Beispiele:**

```php
use CloudCastle\Http\Router\Facade\Route;

// Einfache Route mit Closure
Route::get('/users', function() {
    return 'Liste der Benutzer';
});

// Mit Controller (Array)
Route::get('/users', [UserController::class, 'index']);

// Mit Controller (String)
Route::get('/users', 'UserController@index');

// Mit Parametern
Route::get('/users/{id}', function($id) {
    return "Benutzer-ID: $id";
});

// Method Chaining
Route::get('/api/users', [UserController::class, 'index'])
    ->name('api.users')
    ->middleware([AuthMiddleware::class])
    ->throttle(100, 1);
```

**Verwendung:**
- Datenabruf (Listen, Details)
- Seitenanzeige
- API-Endpunkte zum Lesen

---

### 2. POST-Route

**Methode:** `Route::post(string $uri, mixed $action): Route`

**Beschreibung:** Registriert eine Route für HTTP POST-Anfragen.

**Parameter:**
- `$uri` - Route-URI
- `$action` - Aktion

**Gibt zurück:** `Route`-Objekt

**Beispiele:**

```php
// Ressourcen-Erstellung
Route::post('/users', function() {
    $data = $_POST;
    // Benutzer erstellen
    return 'Benutzer erstellt';
});

// Mit Controller
Route::post('/users', [UserController::class, 'store']);

// Mit Validierung und Rate Limiting
Route::post('/users', [UserController::class, 'store'])
    ->middleware([ValidateUser::class])
    ->throttle(20, 1);  // 20 Anfragen pro Minute
```

**Verwendung:**
- Erstellen neuer Ressourcen
- Formular-Übermittlung
- API-Datenerstellung

---

### 3. PUT-Route

**Methode:** `Route::put(string $uri, mixed $action): Route`

**Beschreibung:** Registriert eine Route für HTTP PUT-Anfragen (vollständige Ressourcen-Aktualisierung).

**Parameter:**
- `$uri` - Route-URI (normalerweise mit ID-Parameter)
- `$action` - Aktion

**Gibt zurück:** `Route`-Objekt

**Beispiele:**

```php
// Vollständige Ressourcen-Aktualisierung
Route::put('/users/{id}', function($id) {
    $data = json_decode(file_get_contents('php://input'), true);
    // Vollständige Benutzer-Aktualisierung
    return "Benutzer $id aktualisiert";
});

// Mit Controller
Route::put('/users/{id}', [UserController::class, 'update'])
    ->where('id', '[0-9]+');

// RESTful API
Route::put('/api/v1/users/{id}', [ApiUserController::class, 'update'])
    ->middleware([AuthMiddleware::class])
    ->name('api.v1.users.update');
```

**Verwendung:**
- Vollständige Ressourcen-Aktualisierungen
- Komplette Datenersetzung
- RESTful API-Updates

---

### 4. PATCH-Route

**Methode:** `Route::patch(string $uri, mixed $action): Route`

**Beschreibung:** Registriert eine Route für HTTP PATCH-Anfragen (teilweise Ressourcen-Aktualisierung).

**Parameter:**
- `$uri` - Route-URI
- `$action` - Aktion

**Gibt zurück:** `Route`-Objekt

**Beispiele:**

```php
// Teilweise Ressourcen-Aktualisierung
Route::patch('/users/{id}', function($id) {
    $data = json_decode(file_get_contents('php://input'), true);
    // Teilweise Benutzer-Aktualisierung
    return "Benutzer $id teilweise aktualisiert";
});

// Mit Controller
Route::patch('/users/{id}', [UserController::class, 'patch'])
    ->where('id', '[0-9]+');
```

**Verwendung:**
- Teilweise Ressourcen-Aktualisierungen
- Feld-spezifische Änderungen
- Effiziente Updates

---

### 5. DELETE-Route

**Methode:** `Route::delete(string $uri, mixed $action): Route`

**Beschreibung:** Registriert eine Route für HTTP DELETE-Anfragen.

**Parameter:**
- `$uri` - Route-URI
- `$action` - Aktion

**Gibt zurück:** `Route`-Objekt

**Beispiele:**

```php
// Ressourcen-Löschung
Route::delete('/users/{id}', function($id) {
    // Benutzer löschen
    return "Benutzer $id gelöscht";
});

// Mit Controller
Route::delete('/users/{id}', [UserController::class, 'destroy'])
    ->where('id', '[0-9]+');
```

**Verwendung:**
- Ressourcen-Löschung
- Datenentfernung
- Aufräumungsoperationen

---

### 6. VIEW-Route

**Methode:** `Route::view(string $uri, mixed $action): Route`

**Beschreibung:** Registriert eine Route für benutzerdefinierte VIEW-Methode.

**Parameter:**
- `$uri` - Route-URI
- `$action` - Aktion

**Gibt zurück:** `Route`-Objekt

**Beispiele:**

```php
// Benutzerdefinierte VIEW-Methode
Route::view('/page', function() {
    return 'Seiteninhalt';
});

// Mit Controller
Route::view('/page', [PageController::class, 'show']);
```

**Verwendung:**
- Benutzerdefinierte HTTP-Methoden
- Spezialisierte Operationen
- Nicht-standard Endpunkte

---

### 7. Custom-Route

**Methode:** `Route::custom(string $method, string $uri, mixed $action): Route`

**Beschreibung:** Registriert eine Route für beliebige benutzerdefinierte HTTP-Methode.

**Parameter:**
- `$method` - HTTP-Methodenname
- `$uri` - Route-URI
- `$action` - Aktion

**Gibt zurück:** `Route`-Objekt

**Beispiele:**

```php
// Benutzerdefinierte PURGE-Methode
Route::custom('PURGE', '/cache', function() {
    // Cache löschen
    return 'Cache gelöscht';
});

// Benutzerdefinierte OPTIONS-Methode
Route::custom('OPTIONS', '/api', function() {
    return 'CORS Preflight';
});
```

**Verwendung:**
- Benutzerdefinierte HTTP-Methoden
- Spezialisierte Protokolle
- Nicht-standard Operationen

---

### 8. Match-Route

**Methode:** `Route::match(array $methods, string $uri, mixed $action): Route`

**Beschreibung:** Registriert eine Route für mehrere HTTP-Methoden.

**Parameter:**
- `$methods` - Array von HTTP-Methoden
- `$uri` - Route-URI
- `$action` - Aktion

**Gibt zurück:** `Route`-Objekt

**Beispiele:**

```php
// Mehrere Methoden
Route::match(['GET', 'POST'], '/form', function() {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        return 'Formular anzeigen';
    }
    return 'Formular verarbeiten';
});

// Mit Controller
Route::match(['PUT', 'PATCH'], '/users/{id}', [UserController::class, 'update']);
```

**Verwendung:**
- Mehrere Methoden-Behandlung
- Formular-Verarbeitung
- Flexible Endpunkte

---

### 9. Any-Route

**Methode:** `Route::any(string $uri, mixed $action): Route`

**Beschreibung:** Registriert eine Route für alle HTTP-Methoden.

**Parameter:**
- `$uri` - Route-URI
- `$action` - Aktion

**Gibt zurück:** `Route`-Objekt

**Beispiele:**

```php
// Alle Methoden
Route::any('/endpoint', function() {
    $method = $_SERVER['REQUEST_METHOD'];
    return "Behandle $method Anfrage";
});

// Mit Controller
Route::any('/api/endpoint', [ApiController::class, 'handle']);
```

**Verwendung:**
- Universelle Endpunkte
- Methoden-agnostische Behandlung
- Flexible APIs

---

### 10. Router-Instanz

**Methode:** `Router::getInstance(): Router`

**Beschreibung:** Ruft die Singleton-Router-Instanz ab.

**Gibt zurück:** `Router`-Instanz

**Beispiele:**

```php
use CloudCastle\Http\Router\Router;

$router = Router::getInstance();
$router->get('/users', $action);
$router->post('/users', $action);
```

**Verwendung:**
- Direkter Router-Zugriff
- Singleton-Pattern
- Programmatische Steuerung

---

### 11. Facade API

**Beschreibung:** Statische Schnittstelle für Routen-Registrierung.

**Beispiele:**

```php
use CloudCastle\Http\Router\Facade\Route;

Route::get('/users', $action);
Route::post('/users', $action);
Route::put('/users/{id}', $action);
Route::patch('/users/{id}', $action);
Route::delete('/users/{id}', $action);
```

**Verwendung:**
- Saubere Syntax
- Statischer Zugriff
- Method Chaining

---

### 12. Routen-Registrierung

**Beschreibung:** Registrierung von Routen in der Anwendung.

**Beispiele:**

```php
// In routes/web.php
Route::get('/', function() {
    return 'Willkommen';
});

Route::get('/about', function() {
    return 'Über-Seite';
});

Route::get('/contact', function() {
    return 'Kontakt-Seite';
});
```

**Verwendung:**
- Anwendungs-Setup
- Routen-Definitionen
- Konfiguration

---

### 13. Routen-Dispatch

**Beschreibung:** Weiterleitung von Anfragen an registrierte Routen.

**Beispiele:**

```php
use CloudCastle\Http\Router\Facade\Route;

// Routen registrieren
Route::get('/users', $action);

// Anfrage weiterleiten
$route = Route::dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
if ($route) {
    echo $route->run();
}
```

**Verwendung:**
- Anfrage-Behandlung
- Routen-Matching
- Antwort-Generierung

---

## Best Practices

### 1. Routen-Organisation

```php
// Verwandte Routen gruppieren
Route::group(['prefix' => 'api/v1'], function() {
    Route::get('/users', [UserController::class, 'index']);
    Route::post('/users', [UserController::class, 'store']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);
});
```

### 2. Method Chaining

```php
Route::get('/api/users', [UserController::class, 'index'])
    ->name('api.users.index')
    ->middleware([AuthMiddleware::class])
    ->throttle(100, 1)
    ->tag('api');
```

### 3. Parameter-Validierung

```php
Route::get('/users/{id}', [UserController::class, 'show'])
    ->where('id', '[0-9]+');
```

### 4. Sicherheitsüberlegungen

```php
Route::post('/users', [UserController::class, 'store'])
    ->middleware([ValidateUser::class])
    ->throttle(20, 1)
    ->whitelistIp(['192.168.1.0/24']);
```

---

## Häufige Muster

### 1. RESTful-Routen

```php
Route::get('/users', [UserController::class, 'index']);      // Liste
Route::post('/users', [UserController::class, 'store']);   // Erstellen
Route::get('/users/{id}', [UserController::class, 'show']); // Anzeigen
Route::put('/users/{id}', [UserController::class, 'update']); // Aktualisieren
Route::delete('/users/{id}', [UserController::class, 'destroy']); // Löschen
```

### 2. API-Routen

```php
Route::group(['prefix' => 'api/v1', 'middleware' => 'auth'], function() {
    Route::apiResource('users', UserController::class);
    Route::apiResource('posts', PostController::class);
});
```

### 3. Web-Routen

```php
Route::group(['middleware' => 'web'], function() {
    Route::get('/', [HomeController::class, 'index']);
    Route::get('/about', [PageController::class, 'about']);
    Route::get('/contact', [PageController::class, 'contact']);
});
```

---

## Performance-Tipps

### 1. Routen-Caching

```php
$router = Router::getInstance();
$router->enableCache('cache/routes.php');
$router->compile();
```

### 2. Effizientes Matching

```php
// Spezifischere Routen zuerst
Route::get('/users/{id}/posts/{post}', $action);
Route::get('/users/{id}', $action);
Route::get('/users', $action);
```

### 3. Parameter-Einschränkungen

```php
Route::get('/users/{id}', $action)->where('id', '[0-9]+');
```

---

## Fehlerbehebung

### Häufige Probleme

1. **Route nicht gefunden**
   - URI-Pattern überprüfen
   - HTTP-Methode verifizieren
   - Routen-Registrierungsreihenfolge prüfen

2. **Parameter nicht übergeben**
   - Parametername in URI verifizieren
   - Parameter-Einschränkungen prüfen
   - Richtige Aktions-Signatur sicherstellen

3. **Method Chaining-Probleme**
   - Rückgabetyp prüfen
   - Methoden-Verfügbarkeit verifizieren
   - Methoden-Reihenfolge prüfen

### Debug-Tipps

```php
// Debug-Modus aktivieren
Route::enableDebug();

// Alle registrierten Routen abrufen
$routes = Route::getAllRoutes();

// Routen-Matching prüfen
$route = Route::match('/users/123', 'GET');
```

---

## Siehe auch

- [Routen-Parameter](02_ROUTE_PARAMETERS.md) - Dynamische Routen-Parameter
- [Routen-Gruppen](03_ROUTE_GROUPS.md) - Routen-Organisation
- [Middleware](06_MIDDLEWARE.md) - Anfrage-Verarbeitung
- [Benannte Routen](07_NAMED_ROUTES.md) - Routen-Identifikation
- [API-Referenz](../API_REFERENCE.md) - Vollständige API-Referenz

---

© 2024 CloudCastle HTTP Router  
[⬆ Nach oben](#grundlegendes-routing)