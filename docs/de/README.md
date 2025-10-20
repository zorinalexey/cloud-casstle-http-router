# CloudCastle HTTP Router

[English](../en/README.md) | [Русский](../../README.md) | [**Deutsch**](README.md) | [Français](../fr/README.md) | [中文](../zh/README.md)

---

[![PHP Version](https://img.shields.io/badge/PHP-8.2%2B-blue.svg)](https://php.net)
[![License](https://img.shields.io/badge/license-MIT-green.svg)](../../LICENSE)
[![Tests](https://img.shields.io/badge/tests-501%2F501-success.svg)](../ru/TESTS_DETAILED.md)
[![PHPStan](https://img.shields.io/badge/PHPStan-Level%20Max-brightgreen.svg)](../../reports/phpstan.txt)
[![Performance](https://img.shields.io/badge/performance-54k%20req%2Fsec-brightgreen.svg)](../ru/PERFORMANCE_ANALYSIS.md)
[![Features](https://img.shields.io/badge/features-209%2B-blue.svg)](../../FEATURES_LIST.md)

**Leistungsstarke, flexible und sichere HTTP-Routing-Bibliothek für PHP 8.2+** mit Fokus auf Performance, Sicherheit und Benutzerfreundlichkeit.

## ⚡ Warum CloudCastle HTTP Router?

### 🎯 Hauptvorteile

- ⚡ **Höchste Performance** - **54.891 Req/Sek**, schneller als die meisten Konkurrenten
- 🔒 **Umfassende Sicherheit** - 12+ integrierte Schutzmechanismen (OWASP Top 10)
- 💎 **209+ Funktionen** - reichste Funktionalität auf dem Markt
- 💾 **Minimaler Speicherbedarf** - nur **1,32 KB pro Route**
- 📊 **Extreme Skalierbarkeit** - getestet mit **1.160.000 Routen**
- 🔌 **Erweiterbarkeit** - Plugin-System, Middleware, Makros
- 📦 **Volle Autonomie** - Framework-unabhängig
- ✅ **100% Zuverlässigkeit** - 501 Tests, 0 Fehler, 95%+ Coverage

---

## 🚀 Schnellstart

### Installation

```bash
composer require cloud-castle/http-router
```

### Einfaches Beispiel

```php
<?php

use CloudCastle\Http\Router\Facade\Route;

// Einfache Routen
Route::get('/users', fn() => 'Benutzerliste');
Route::post('/users', fn() => 'Benutzer erstellen');
Route::get('/users/{id}', fn($id) => "Benutzer: $id")
    ->where('id', '[0-9]+');

// Dispatch
$route = Route::dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
echo $route->run();
```

### Fortgeschrittenes Beispiel

```php
// Geschützte API
Route::group(['prefix' => '/api/v1'], function() {
    Route::get('/users', [UserController::class, 'index'])
        ->name('api.users')
        ->throttle(100, 1)  // 100 Anfragen pro Minute
        ->middleware([AuthMiddleware::class])
        ->tag('api');
    
    Route::post('/users', [UserController::class, 'store'])
        ->throttle(20, 1)
        ->whitelistIp(['192.168.1.0/24'])
        ->middleware([AuthMiddleware::class, AdminMiddleware::class]);
});
```

---

## 💡 Kernfunktionen

### 1️⃣ HTTP-Methoden (7 Wege)

```php
Route::get('/users', $action);
Route::post('/users', $action);
Route::put('/users/{id}', $action);
Route::patch('/users/{id}', $action);
Route::delete('/users/{id}', $action);
Route::any('/page', $action);              // Beliebige Methode
Route::match(['GET', 'POST'], '/form', $action);  // Mehrere Methoden
Route::custom('VIEW', '/preview', $action);       // Benutzerdefinierte Methode
```

### 2️⃣ Intelligente Parameter

```php
// Basis-Parameter
Route::get('/users/{id}', $action);

// Mit Validierung
Route::get('/users/{id}', $action)->where('id', '[0-9]+');
Route::get('/posts/{slug}', $action)->where('slug', '[a-z0-9-]+');

// Optional
Route::get('/blog/{category?}', $action);

// Standardwerte
Route::get('/posts/{page}', $action)->defaults(['page' => 1]);

// Inline-Muster
Route::get('/users/{id:[0-9]+}', $action);
```

### 3️⃣ Route-Gruppen

```php
Route::group([
    'prefix' => '/api/v1',
    'middleware' => [AuthMiddleware::class],
    'domain' => 'api.example.com',
    'port' => 8080,
    'namespace' => 'App\\Controllers\\Api',
], function() {
    Route::get('/users', 'UserController@index');
    Route::get('/posts', 'PostController@index');
});
```

### 4️⃣ Rate Limiting & Auto-Ban

```php
// Rate Limiting
Route::post('/api/login', $action)
    ->throttle(5, 1);  // 5 Versuche pro Minute

// Mit TimeUnit Enum
use CloudCastle\Http\Router\TimeUnit;

Route::post('/api/submit', $action)
    ->throttle(100, TimeUnit::HOUR->value);

// Auto-Ban-System
use CloudCastle\Http\Router\BanManager;

$banManager = new BanManager(
    maxViolations: 5,      // 5 Verstöße
    banDuration: 3600      // 1 Stunde Ban
);

Route::post('/login', $action)
    ->throttle(3, 1)
    ->getRateLimiter()
    ?->setBanManager($banManager);
```

### 5️⃣ IP-Filterung

```php
// Whitelist (nur erlaubte IPs)
Route::get('/admin', $action)
    ->whitelistIp(['192.168.1.1', '10.0.0.0/8']);

// Blacklist (gesperrte IPs)
Route::get('/public', $action)
    ->blacklistIp(['1.2.3.4', '5.6.7.0/24']);

// In Gruppe
Route::group(['whitelistIp' => ['192.168.1.0/24']], function() {
    Route::get('/admin/users', $action);
    Route::get('/admin/settings', $action);
});
```

### 6️⃣ Middleware

```php
// Global
Route::middleware([CorsMiddleware::class, LoggerMiddleware::class]);

// Auf Route
Route::get('/admin', $action)
    ->middleware([AuthMiddleware::class, AdminMiddleware::class]);

// Integrierte Middleware
Route::get('/api/data', $action)->auth();        // AuthMiddleware
Route::get('/api/public', $action)->cors();      // CorsMiddleware
Route::get('/secure', $action)->secure();        // HTTPS-Erzwingung
```

### 7️⃣ Benannte Routen und URL-Generierung

```php
// Benennung
Route::get('/users/{id}', $action)->name('users.show');

// Auto-Benennung
Route::enableAutoNaming();

// URL-Generierung
$url = route_url('users.show', ['id' => 5]);  // /users/5

// Mit Domain
use CloudCastle\Http\Router\UrlGenerator;

$generator = new UrlGenerator();
$url = $generator->generate('users.show', ['id' => 5])
    ->toDomain('api.example.com')
    ->toProtocol('https')
    ->absolute();  // https://api.example.com/users/5

// Signierte URLs
$signedUrl = $generator->signed('verify.email', ['user' => 123], 3600);
```

### 8️⃣ Route-Shortcuts (14 Methoden)

```php
Route::get('/api/data', $action)->apiEndpoint();  // API + CORS + JSON
Route::get('/admin', $action)->admin();           // Auth + Admin + Whitelist
Route::get('/page', $action)->public();           // Public Tag
Route::get('/dashboard', $action)->protected();   // Auth + HTTPS
Route::get('/localhost', $action)->localhost();   // Nur Localhost

// Throttle-Shortcuts
Route::post('/api/submit', $action)->throttleStandard();   // 60/Min
Route::post('/api/strict', $action)->throttleStrict();     // 10/Min
Route::post('/api/bulk', $action)->throttleGenerous();     // 1000/Min
```

### 9️⃣ Route-Makros (7 Makros)

```php
// RESTful-Ressource
Route::resource('/users', UserController::class);
// Erstellt: index, create, store, show, edit, update, destroy

// API-Ressource (ohne create/edit)
Route::apiResource('/posts', PostController::class);

// CRUD (einfach)
Route::crud('/products', ProductController::class);

// Authentifizierung
Route::auth();
// Erstellt: login, logout, register, password.request, password.reset

// Admin-Panel
Route::adminPanel('/admin');

// API-Versionierung
Route::apiVersion('v1', function() {
    Route::get('/users', $action);
});

// Webhooks
Route::webhooks('/webhooks', WebhookController::class);
```

### 🔟 Hilfsfunktionen (18 Funktionen)

```php
route('users.show');              // Route nach Name abrufen
current_route();                  // Aktuelle Route
previous_route();                 // Vorherige Route
route_is('users.*');              // Routennamen prüfen
route_name();                     // Name der aktuellen Route
router();                         // Router-Instanz
dispatch_route($uri, $method);    // Dispatch
route_url('users.show', ['id' => 5]);  // URL generieren
route_has('users.show');          // Existenz prüfen
route_stats();                    // Routen-Statistiken
routes_by_tag('api');             // Routen nach Tag
route_back();                     // Zurück gehen
```

---

## 📊 Performance

### Benchmarks (PHPBench)

| Operation | Zeit | Performance |
|-----------|------|-------------|
| **1000 Routen hinzufügen** | 3,435ms | 0,0034ms/Route |
| **Erste Route matchen** | 123μs | 8.130 Req/Sek |
| **Mittlere Route matchen** | 1,746ms | 573 Req/Sek |
| **Letzte Route matchen** | 3,472ms | 288 Req/Sek |
| **Benannte Suche** | 3,858ms | 259 Req/Sek |
| **Route-Gruppen** | 2,577ms | 388 Req/Sek |
| **Mit Middleware** | 2,030ms | 493 Req/Sek |
| **Mit Parametern** | 73μs | 13.699 Req/Sek |

### Load-Tests

| Szenario | Routen | Anfragen | Ergebnis | Speicher |
|----------|--------|----------|----------|----------|
| **Leichte Last** | 100 | 1.000 | **53.975 Req/Sek** | 6 MB |
| **Mittlere Last** | 500 | 5.000 | **54.135 Req/Sek** | 6 MB |
| **Schwere Last** | 1.000 | 10.000 | **54.891 Req/Sek** | 6 MB |

### Stress-Tests

- ✅ **1.160.000 Routen** verarbeitet
- ✅ **1,46 GB Speicher** (1,32 KB/Route)
- ✅ **200.000 Anfragen** in 3,8 Sek
- ✅ **0 Fehler** unter extremer Last

📖 Mehr: [Performance-Analyse](../ru/PERFORMANCE_ANALYSIS.md)

---

## 🔒 Sicherheit

### Integrierte Schutzmechanismen

CloudCastle HTTP Router umfasst **12+ Sicherheitsebenen**:

✅ **Rate Limiting** - DDoS-Prävention  
✅ **Auto-Ban-System** - automatische Sperrung  
✅ **IP-Filterung** - Whitelist/Blacklist mit CIDR  
✅ **HTTPS-Erzwingung** - erzwungene HTTPS-Nutzung  
✅ **Path-Traversal-Schutz** - Schutz vor ../../../  
✅ **SQL-Injection-Schutz** - Parameter-Validierung  
✅ **XSS-Schutz** - Escaping  
✅ **ReDoS-Schutz** - Regex-DoS-Schutz  
✅ **Method-Override-Schutz** - Schutz vor Methoden-Spoofing  
✅ **Cache-Injection-Schutz** - sicheres Caching  
✅ **IP-Spoofing-Schutz** - X-Forwarded-For-Validierung  
✅ **Protokoll-Beschränkungen** - HTTP/HTTPS/WS/WSS

### Sicherheitstests

**13/13 OWASP Top 10 Tests bestanden** ✅

```
✓ Path-Traversal-Schutz
✓ SQL-Injection-Schutz
✓ XSS-Schutz
✓ Rate Limiting (A07:2021)
✓ IP-Filterung & Spoofing
✓ Method-Override-Angriffe
✓ Cache-Injection
✓ ReDoS-Schutz
✓ Unicode-Sicherheit
✓ Ressourcenerschöpfung
✓ HTTPS-Erzwingung
✓ Domain/Port-Beschränkungen
✓ Auto-Ban-System
```

📖 Mehr: [Sicherheitsbericht](../ru/SECURITY_REPORT.md)

---

## 🧩 Erweiterte Funktionen

### Plugin-System

```php
use CloudCastle\Http\Router\Contracts\PluginInterface;

class LoggerPlugin implements PluginInterface {
    public function beforeDispatch(Route $route, string $uri, string $method): void {
        log("Anfrage: $method $uri");
    }
    
    public function afterDispatch(Route $route, mixed $result): mixed {
        log("Antwort generiert");
        return $result;
    }
    
    public function onRouteRegistered(Route $route): void {
        log("Route registriert: {$route->getUri()}");
    }
    
    public function onException(Route $route, \Exception $e): void {
        log("Fehler: " . $e->getMessage());
    }
}

Route::registerPlugin(new LoggerPlugin());
```

### Route-Loader (5 Typen)

```php
use CloudCastle\Http\Router\Loader\*;

// JSON
$loader = new JsonLoader($router);
$loader->load('routes.json');

// YAML
$loader = new YamlLoader($router);
$loader->load('routes.yaml');

// XML
$loader = new XmlLoader($router);
$loader->load('routes.xml');

// PHP-Attribute
$loader = new AttributeLoader($router);
$loader->loadFromDirectory('app/Controllers');

// PHP-Dateien
require 'routes/web.php';
require 'routes/api.php';
```

### Expression Language

```php
Route::get('/admin', $action)
    ->condition('request.ip == "192.168.1.1" and request.time > 9');

Route::get('/api/data', $action)
    ->condition('request.header["X-API-Key"] == "secret"');
```

### Routen-Caching

```php
// Cache aktivieren
$router->enableCache('var/cache/routes');

// Kompilieren
$router->compile();

// Auto-Laden aus Cache
if ($router->loadFromCache()) {
    // Cache geladen - sofortiger Start
} else {
    // Routen registrieren
    require 'routes/web.php';
    $router->compile();
}

// Löschen
$router->clearCache();
```

### PSR-Unterstützung

```php
// PSR-7
use Psr\Http\Message\ServerRequestInterface;
$request = ServerRequestFactory::fromGlobals();

// PSR-15
use CloudCastle\Http\Router\Psr15\Psr15MiddlewareAdapter;
$psrMiddleware = new Psr15MiddlewareAdapter($router);
```

---

## 📚 Dokumentation

### Hauptdokumentation

- 📖 [Benutzerhandbuch](USER_GUIDE.md) - Vollständige Anleitung zu allen Funktionen
- 🔍 [API-Referenz](API_REFERENCE.md) - Detaillierte API-Dokumentation
- 💡 [Beispiele](../../examples/) - 20+ einsatzbereite Beispiele
- ❓ [FAQ](FAQ.md) - Häufig gestellte Fragen
- 🎯 [Funktionsliste](../../FEATURES_LIST.md) - Alle 209+ Funktionen

### Berichte und Analysen

- 📊 [Test-Zusammenfassung](../ru/SUMMARY.md)
- 🧪 [Detaillierte Tests](../ru/TESTS_DETAILED.md)
- ⚡ [Performance-Analyse](PERFORMANCE_ANALYSIS.md)
- 🔒 [Sicherheitsbericht](SECURITY_REPORT.md)
- ⚖️ [Vergleich mit Alternativen](COMPARISON.md)

---

## 🧪 Code-Qualität

### Test-Statistiken

```
Gesamt Tests:     501
Bestanden:        501 ✅
Fehlgeschlagen:   0
Coverage:         ~95%
Assertions:       1.200+
```

### Statische Analyse

- **PHPStan:** Level MAX - 0 kritische Fehler ✅
- **PHPMD:** 0 Probleme ✅
- **PHPCS:** PSR-12 - 0 Verstöße ✅
- **PHP-CS-Fixer:** 0 Dateien benötigen Korrekturen ✅
- **Rector:** 0 Änderungen erforderlich ✅

### Tests ausführen

```bash
# Alle Tests
composer test

# Nach Kategorie
composer test:unit          # Unit-Tests
composer test:security      # Sicherheitstests
composer test:performance   # Performance-Tests
composer test:load          # Load-Tests
composer test:stress        # Stress-Tests

# Statische Analyse
composer phpstan            # PHPStan
composer phpcs              # PHP_CodeSniffer
composer phpmd              # PHP Mess Detector
composer analyse            # Alle Analysatoren

# Benchmarks
composer benchmark          # PHPBench
```

---

## ⚖️ Vergleich mit Alternativen

| Merkmal | CloudCastle | Laravel | Symfony | FastRoute | Slim |
|---------|-------------|---------|---------|-----------|------|
| **Performance** | **54k Req/Sek** | 35k | 40k | 60k | 45k |
| **Speicher (1k Routen)** | **6 MB** | 12 MB | 10 MB | 4 MB | 5 MB |
| **Funktionen** | **209+** | 150+ | 180+ | 20+ | 50+ |
| **Rate Limiting** | ✅ Integriert | ✅ | ❌ | ❌ | ⚠️ Paket |
| **Auto-Ban** | ✅ | ❌ | ❌ | ❌ | ❌ |
| **IP-Filterung** | ✅ Integriert | ⚠️ Middleware | ❌ | ❌ | ⚠️ Middleware |
| **Expression Lang** | ✅ | ❌ | ⚠️ Begrenzt | ❌ | ❌ |
| **Plugins** | ✅ 4 integriert | ✅ | ⚠️ Events | ❌ | ❌ |
| **Loader** | ✅ 5 Typen | ⚠️ Nur PHP | ⚠️ XML/YAML | ❌ | ❌ |
| **Makros** | ✅ 7 Makros | ✅ | ❌ | ❌ | ❌ |
| **Shortcuts** | ✅ 14 Methoden | ⚠️ Einige | ❌ | ❌ | ❌ |
| **Helpers** | ✅ 18 Funktionen | ✅ 10+ | ⚠️ Wenige | ❌ | ⚠️ Wenige |
| **PSR-15** | ✅ | ✅ | ✅ | ❌ | ✅ |
| **Standalone** | ✅ | ❌ Framework | ⚠️ Komplex | ✅ | ✅ |
| **Tests** | **501** | 300+ | 500+ | 100+ | 200+ |
| **Coverage** | **95%+** | 90%+ | 95%+ | 80%+ | 85%+ |

### Fazit

**CloudCastle HTTP Router** - optimale Balance zwischen **Performance**, **Funktionalität** und **Sicherheit**. 

✅ **Beste Wahl für:**
- API-Server mit hohen Sicherheitsanforderungen
- Microservice-Architektur
- Hochlastsysteme (50k+ Req/Sek)
- Projekte, die maximale Routing-Kontrolle erfordern

📖 Mehr: [Vergleich mit Alternativen](COMPARISON.md)

---

## 🤝 Beitragen

Wir begrüßen Beiträge zur Entwicklung von CloudCastle HTTP Router!

### Wie helfen

1. ⭐ Projekt mit Stern versehen
2. 🐛 Fehler melden
3. 💡 Neue Funktionen vorschlagen
4. 📝 Dokumentation verbessern
5. 🔧 Pull Requests einreichen

### Prozess

```bash
# 1. Projekt forken
git clone https://github.com/YOUR_USERNAME/cloud-casstle-http-router.git

# 2. Feature-Branch erstellen
git checkout -b feature/AmazingFeature

# 3. Änderungen committen
git commit -m 'Add some AmazingFeature'

# 4. Zu Branch pushen
git push origin feature/AmazingFeature

# 5. Pull Request öffnen
```

### Anforderungen

- ✅ PSR-12 folgen
- ✅ Tests schreiben (PHPUnit)
- ✅ Dokumentation aktualisieren
- ✅ PHPStan/PHPCS prüfen
- ✅ Ein PR = eine Funktion

📖 Mehr: [CONTRIBUTING.md](../../CONTRIBUTING.md)

---

## 📄 Lizenz

Dieses Projekt ist unter der **MIT-Lizenz** lizenziert. Siehe [LICENSE](../../LICENSE) für Details.

```
MIT License

Copyright (c) 2024 CloudCastle

Permission is hereby granted, free of charge, to any person obtaining a copy...
```

---

## 💬 Support

### Kontakte

- 📧 **Email:** zorinalexey59292@gmail.com
- 💬 **Telegram:** [@CloudCastle85](https://t.me/CloudCastle85)
- 📢 **Telegram-Kanal:** [@cloud_castle_news](https://t.me/cloud_castle_news)
- 🐛 **GitHub Issues:** [Problem melden](https://github.com/zorinalexey/cloud-casstle-http-router/issues)
- 💡 **GitHub Discussions:** [Diskussionen](https://github.com/zorinalexey/cloud-casstle-http-router/discussions)

### Nützliche Links

- [📚 Dokumentation](../ru/)
- [💡 Verwendungsbeispiele](../../examples/)
- [📋 Changelog](../../CHANGELOG.md)
- [🗺️ Roadmap](../../ROADMAP.md)
- [🔒 Sicherheitsrichtlinie](../../SECURITY.md)
- [📜 Verhaltenskodex](../../CODE_OF_CONDUCT.md)
- [🤝 Mitwirkende](../../CONTRIBUTORS.md)

---

## 🌟 Danksagungen

Vielen Dank an alle [Mitwirkenden](../../CONTRIBUTORS.md) für ihren Beitrag zum Projekt!

### Verwendete Technologien

- [PHPUnit](https://phpunit.de/) - Testing
- [PHPStan](https://phpstan.org/) - Statische Analyse
- [PHPCS](https://github.com/squizlabs/PHP_CodeSniffer) - Code-Stil
- [PHPBench](https://phpbench.readthedocs.io/) - Benchmarks
- [Rector](https://getrector.org/) - Refactoring

---

## 📈 Projekt-Statistiken

![GitHub Stars](https://img.shields.io/github/stars/zorinalexey/cloud-casstle-http-router?style=social)
![GitHub Forks](https://img.shields.io/github/forks/zorinalexey/cloud-casstle-http-router?style=social)
![GitHub Watchers](https://img.shields.io/github/watchers/zorinalexey/cloud-casstle-http-router?style=social)

![GitHub Issues](https://img.shields.io/github/issues/zorinalexey/cloud-casstle-http-router)
![GitHub Pull Requests](https://img.shields.io/github/issues-pr/zorinalexey/cloud-casstle-http-router)
![GitHub Last Commit](https://img.shields.io/github/last-commit/zorinalexey/cloud-casstle-http-router)

---

**Made with ❤️ by [CloudCastle](https://github.com/zorinalexey)**

---

[⬆ Nach Oben](#cloudcastle-http-router)
