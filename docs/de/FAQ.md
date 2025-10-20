# FAQ - Häufig gestellte Fragen

[English](../en/FAQ.md) | [Русский](../ru/FAQ.md) | [**Deutsch**](FAQ.md) | [Français](../fr/FAQ.md) | [中文](../zh/FAQ.md)

---

**Version:** 1.1.1  
**Datum:** Oktober 2025

---

## 📚 Dokumentationsnavigation

### Hauptdokumente
- [README](../../README.md) - Startseite
- [USER_GUIDE](USER_GUIDE.md) - Vollständiges Benutzerhandbuch
- [FEATURES_INDEX](FEATURES_INDEX.md) - Katalog aller Funktionen
- [API_REFERENCE](API_REFERENCE.md) - API-Referenz

### Funktionen
- [Detaillierte Funktionsdoku](features/) - 22 Kategorien
- [ALL_FEATURES](ALL_FEATURES.md) - Vollständige Liste

### Tests und Berichte
- [TESTS_SUMMARY](TESTS_SUMMARY.md) - Testzusammenfassung
- [Detaillierte Testberichte](tests/) - 7 Berichte
- [PERFORMANCE_ANALYSIS](PERFORMANCE_ANALYSIS.md) - Performance-Analyse
- [SECURITY_REPORT](SECURITY_REPORT.md) - Sicherheitsbericht

### Zusätzlich
- **[FAQ](FAQ.md) - Häufige Fragen** ← Sie sind hier
- [COMPARISON](COMPARISON.md) - Vergleich mit Alternativen
- [DOCUMENTATION_SUMMARY](DOCUMENTATION_SUMMARY.md) - Dokumentationsübersicht

---

## Inhalt

### Allgemein
1. [Was ist CloudCastle HTTP Router?](#was-ist-cloudcastle-http-router)
2. [Warum CloudCastle wählen?](#warum-cloudcastle)
3. [Welche Anforderungen?](#anforderungen)
4. [Wie installieren?](#installation)

### Performance
5. [Wie schnell ist CloudCastle?](#performance)
6. [Wie Performance verbessern?](#optimierung)
7. [Was ist Routen-Caching?](#caching)
8. [Wie viele Routen möglich?](#skalierbarkeit)

### Sicherheit
9. [Wie sicher ist CloudCastle?](#sicherheit)
10. [Was ist Rate Limiting?](#rate-limiting)
11. [Was ist Auto-Ban?](#auto-ban)
12. [Admin-Bereich schützen?](#admin-schuetzen)

### Verwendung
13. [Routen registrieren?](#routen-registrieren)
14. [Was sind Route-Gruppen?](#route-gruppen)
15. [Middleware verwenden?](#middleware)
16. [RESTful API erstellen?](#restful-api)

### Fortgeschritten
17. [Was sind Route-Makros?](#makros)
18. [Plugins verwenden?](#plugins)
19. [PSR-Unterstützung?](#psr-support)
20. [Mit Frameworks nutzbar?](#frameworks)

---

## Allgemein

### Was ist CloudCastle HTTP Router?

Moderne Routing-Bibliothek für PHP 8.2+ mit **209+ Funktionen** für sichere und performante Webanwendungen.

Highlights:
- ⚡ 53.637 Req/Sek
- 🔒 13/13 OWASP Top 10
- 💎 209+ Funktionen
- ✅ 501 Tests (100%)

---

### Warum CloudCastle?

Einziger Router mit:

1. Integriertem Rate Limiting
```php
Route::post('/api', $action)->throttle(60, 1);
```

2. Auto-Ban-System
```php
$banManager = new BanManager(5, 3600);
Route::post('/login', $action)
    ->throttle(3, 1)
    ->getRateLimiter()?->setBanManager($banManager);
```

3. Integrierter IP-Filterung
```php
Route::get('/admin', $action)->whitelistIp(['192.168.1.0/24']);
```

4. 209+ Funktionen — mehr als Wettbewerber.

Vergleich: Symfony 180+, Laravel 150+, FastRoute ~20, Slim ~50

---

### Anforderungen

Minimum: PHP 8.2+, Composer, ~2 MB Speicherplatz  
Empfohlen: PHP 8.3+, Opcache, 128 MB+ memory_limit  
Getestet: 8.2/8.3/8.4

---

### Installation

```bash
composer require cloud-castle/http-router
```

Schnellstart:
```php
<?php
require 'vendor/autoload.php';
use CloudCastle\Http\Router\Facade\Route;
Route::get('/users', fn() => 'Users list');
$route = Route::dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
echo $route->run();
```

---

## Performance

### Wie schnell ist CloudCastle?

- Light (100 Routen): 55.923 Req/Sek
- Medium (500 Routen): 54.680 Req/Sek
- Heavy (1000 Routen): 53.637 Req/Sek

Vergleich (1000 Routen): FastRoute 60k, CloudCastle 53.6k, Slim 45k, Symfony 40k, Laravel 35k

---

### Optimierung

1) Routen-Caching
```php
$router->enableCache('cache/routes');
if (!$router->loadFromCache()) {
  require 'routes/web.php';
  $router->compile();
}
```
2) Inline-Parameter
```php
Route::get('/users/{id:[0-9]+}', $action);
```
3) Gruppierung
```php
Route::group(['prefix' => '/api', 'middleware' => [...]], function() {
  // 100 Routen
});
```

---

### Caching

Kompiliert Routen für sofortiges Laden (10–50x schneller).

---

### Skalierbarkeit

Getestet bis 1.095.000 Routen; ~1,39 KB/Route.

---

## Sicherheit

Eingebaute Schutzmechanismen (13/13 OWASP): Path Traversal, SQL Injection, XSS, IP-Filter, IP Spoofing, ReDoS, Rate Limiting, Auto-Ban, HTTPS, Protokoll-/Domain-/Port-Beschränkungen, Cache Injection.

---

## Verwendung

### Routen registrieren
```php
use CloudCastle\Http\Router\Facade\Route;
Route::get('/users', [UserController::class, 'index']);
```

### Route-Gruppen
```php
Route::group([
  'prefix' => '/api/v1',
  'middleware' => [AuthMiddleware::class],
  'throttle' => [100, 1],
  'tags' => 'api'
], function() {
  Route::get('/users', $action);
});
```

### Middleware
Global, auf Route, in Gruppe

### RESTful API
```php
Route::apiResource('users', ApiUserController::class, 100);
```

---

## Fortgeschritten

### Makros
`resource()`, `apiResource()`, `crud()`, `auth()`, `adminPanel()`, `apiVersion()`, `webhooks()`

### Plugins
`PluginInterface` implementieren; LoggerPlugin, AnalyticsPlugin, ResponseCachePlugin verfügbar.

### PSR-Support
PSR-1, PSR-4, PSR-7, PSR-12, PSR-15

### Frameworks
Standalone nutzbar; Integration mit Laravel/Symfony möglich.

---

## 📚 Siehe auch
- [USER_GUIDE.md](USER_GUIDE.md)
- [FEATURES_INDEX.md](FEATURES_INDEX.md)
- [TESTS_SUMMARY.md](TESTS_SUMMARY.md)
- [COMPARISON.md](COMPARISON.md)

---

© 2024 CloudCastle HTTP Router  
[⬆ Nach oben](#faq---häufig-gestellte-fragen)
