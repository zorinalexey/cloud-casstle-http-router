# Einführung in HttpRouter

**CloudCastle HttpRouter** ist eine moderne, hochperformante Routing-Bibliothek für PHP 8.2+ mit Schwerpunkt auf Sicherheit, Performance und Entwicklerfreundlichkeit.

## 📊 Statistiken

- **308 Tests** / **748 Assertions** ✅
- **Code-Abdeckung:** >95%
- **PHPStan:** Level Max (3 unkritische Warnungen)
- **PHPCS:** PSR-12 konform
- **PHPMD:** Keine kritischen Probleme
- **PHP-Versionen:** 8.2, 8.3, 8.4

## ✨ Hauptfunktionen

### 🔐 Sicherheit
- **Rate Limiting** — DDoS-Schutz mit flexiblen Limits
- **Auto-Ban-System** — automatische Sperrung böswilliger Akteure
- **Protokollerzwingung** — erzwungene HTTPS/WSS-Nutzung
- **Path-Traversal-Schutz** — Schutz vor Directory-Traversal
- **Mass-Assignment-Schutz** — Schutz vor unerwünschter Parameterzuweisung
- **OWASP Top 10** — vollständige Einhaltung der Sicherheitsempfehlungen

### ⚡ Performance
- **Route-Caching** — Route-Caching für sofortigen Zugriff
- **Lazy Loading** — verzögertes Laden von Middleware
- **Optimiertes Matching** — optimierter Matching-Algorithmus
- **Speichereffizient** — effiziente Speichernutzung (30MB für 308 Tests)
- **Schneller Dispatch** — ~0.001ms pro Dispatch mit Cache

### 🎯 Entwicklerfreundlichkeit
- **Fluent API** — ausdrucksstarke verkettbare Schnittstelle
- **Route-Gruppen** — Gruppierung von Routen mit gemeinsamen Einstellungen
- **Benannte Routen** — benannte Routen für bequeme URL-Generierung
- **Middleware-Unterstützung** — vollständige Middleware-Unterstützung
- **Tag-System** — Tag-System zur Organisation von Routen
- **Statische Fassade** — statische Fassade `Route::` für schnellen Zugriff

### 🛠️ Erweiterbarkeit
- **Benutzerdefinierte Methoden** — Unterstützung für benutzerdefinierte HTTP-Methoden
- **WebSocket-Unterstützung** — vollständige WebSocket-Unterstützung (WS/WSS)
- **Middleware-Ketten** — Middleware-Ketten mit Prioritäten
- **Event-System** — Event-System für Hooks
- **Dependency Injection** — Integration mit DI-Containern

## ⚖️ Vor- und Nachteile

### ✅ Vorteile

1. **Umfassende Sicherheit out of the box**
   - Rate Limiting und Auto-Ban nativ eingebaut
   - Schutz vor allen wichtigen Angriffsvektoren (OWASP Top 10)
   - Protokollerzwingung für sichere Verbindungen

2. **Hohe Performance**
   - Fortgeschrittenes Route-Caching-System
   - Optimierte Matching-Algorithmen
   - Minimaler Speicherverbrauch

3. **Modernes PHP**
   - Vollständige Unterstützung für PHP 8.2+ Features
   - Strikte Typisierung
   - Return Types und Named Arguments

4. **Umfangreiche Funktionalität**
   - WebSocket-Unterstützung (selten für PHP-Router)
   - Tag-System zur Organisation
   - Flexible Zeiteinheiten (Sekunden, Minuten, Stunden, Tage)

5. **Ausgezeichnete Dokumentation**
   - Detaillierte Verwendungsbeispiele
   - Dokumentation in 4 Sprachen
   - Detaillierte Testberichte

6. **100% Testabdeckung**
   - 308 Unit-, Integrations- und funktionale Tests
   - Sicherheitstests (OWASP)
   - Performance-Tests
   - Last- und Stresstests

### ⚠️ Einschränkungen

1. **Benötigt PHP 8.2+**
   - Funktioniert nicht auf älteren PHP-Versionen
   - Benötigt modernes Hosting

2. **Junge Bibliothek**
   - Weniger Produktions-Anwendungsfälle im Vergleich zu Konkurrenten
   - Weniger Community-Plugins

3. **Komplexität für einfache Aufgaben**
   - Overkill für einfache Projekte
   - Kann für Landing Pages überdimensioniert sein

4. **Architektonische Merkmale**
   - Verwendet statische Fassade (nicht jeder mag das)
   - Superglobals-Zugriff ($_SERVER) — gerechtfertigt für HTTP-Router
   - Hohe zyklomatische Komplexität in Router.php — aufgrund der umfangreichen API

## 🔄 Vergleich mit Konkurrenten

| Feature | HttpRouter | Symfony | Laravel | FastRoute | Slim |
|---------|-----------|---------|---------|-----------|------|
| **PHP-Version** | 8.2+ | 8.1+ | 8.2+ | 7.1+ | 8.1+ |
| **Rate Limiting** | ✅ Eingebaut | ⚠️ Bundle | ✅ Eingebaut | ❌ | ❌ |
| **Auto-Ban** | ✅ Eingebaut | ❌ | ❌ | ❌ | ❌ |
| **WebSocket** | ✅ WS/WSS | ❌ | ⚠️ Echo | ❌ | ❌ |
| **Route-Caching** | ✅ | ✅ | ✅ | ⚠️ Manuell | ❌ |
| **Middleware** | ✅ | ✅ | ✅ | ❌ | ✅ |
| **Benannte Routen** | ✅ | ✅ | ✅ | ✅ | ✅ |
| **Route-Gruppen** | ✅ | ✅ | ✅ | ✅ | ✅ |
| **Protokollerzwingung** | ✅ HTTPS/WSS | ❌ | ❌ | ❌ | ❌ |
| **Tag-System** | ✅ | ❌ | ❌ | ❌ | ❌ |
| **Statische Fassade** | ✅ Route:: | ❌ | ✅ Route:: | ❌ | ❌ |
| **Performance** | ⚡⚡⚡ | ⚡⚡ | ⚡⚡ | ⚡⚡⚡ | ⚡⚡⚡ |
| **Größe** | Klein | Groß | Groß | Winzig | Mittel |
| **Abhängigkeiten** | Minimal | Viele | Viele | Keine | Wenige |
| **Dokumentation** | 4 Sprachen | EN | EN | EN | EN |

## 🎯 Wann HttpRouter verwenden

### ✅ Perfekt für:

1. **API-Server mit hohen Sicherheitsanforderungen**
   - Eingebautes Rate Limiting
   - Auto-Ban-Schutz
   - Protokollerzwingung

2. **WebSocket-Anwendungen**
   - Native WS/WSS-Unterstützung
   - Einheitliches Routing für HTTP und WebSocket

3. **Microservices**
   - Leichtgewichtig und schnell
   - Minimale Abhängigkeiten
   - Ausgezeichnete Performance

4. **Moderne PHP-Projekte**
   - PHP 8.2+ Features
   - Strikte Typisierung
   - Moderne Best Practices

### ⚠️ Nicht empfohlen für:

1. **Legacy-Projekte auf PHP < 8.2**
2. **Einfache statische Websites** (Overkill)
3. **Projekte, die Laravel/Symfony-Ecosystem-Integration benötigen**

## 📦 Installation

```bash
composer require cloud-castle/http-router
```

## 🚀 Schnellstart

```php
<?php

use CloudCastle\Http\Router\Route;

// Einfache Route
Route::get('/users', fn() => ['users' => User::all()]);

// Route mit Parametern
Route::get('/users/{id}', function($id) {
    return User::find($id);
});

// Rate Limiting
Route::get('/api/data', fn() => getData())
    ->rateLimit(requests: 100, per: '1 minute');

// Route-Gruppe
Route::group('/api/v1', function() {
    Route::get('/users', 'UserController@index');
    Route::post('/users', 'UserController@store');
})->middleware('auth')->rateLimit(1000, '1 hour');

// WebSocket
Route::websocket('/chat', 'ChatController@handle')
    ->protocol('wss'); // Nur sicheres WebSocket

// Dispatch
$route = Route::dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
echo $route->call();
```

## 📚 Weiterführende Literatur

- [Schnellstart](quickstart.md)
- [Routen](routes.md)
- [Route-Gruppen](route-groups.md)
- [Middleware](middleware.md)
- [Sicherheit](security.md)
- [Rate Limiting](rate-limiting.md)
- [Auto-Ban](auto-ban.md)
- [Performance](performance.md)
- [API-Referenz](api-reference.md)

## 📊 Berichte

- [Unit-Tests](../reports/unit-tests.md)
- [Statische Analyse](../reports/static-analysis.md)
- [Performance-Benchmarks](../reports/performance.md)
- [Lasttests](../reports/load-testing.md)
- [Sicherheitstests](../reports/security.md)
- [Vergleich mit Konkurrenten](../reports/comparison.md)

## 🤝 Mitwirken

Wir begrüßen Beiträge zur Bibliothek! Siehe [CONTRIBUTING.md](../CONTRIBUTING.md)

## 📄 Lizenz

MIT-Lizenz. Siehe [LICENSE](../../LICENSE)

---

**CloudCastle HttpRouter** — die Wahl für moderne PHP-Anwendungen mit hohen Sicherheits- und Performance-Anforderungen.
