[🇷🇺 Русский](ru/README.md) | [🇺🇸 English](en/README.md) | [🇩🇪 Deutsch](de/README.md) | [🇫🇷 Français](fr/README.md) | [🇨🇳 中文](zh/README.md)

[📚 Table of Contents](de/_table-of-contents.md) | [🏠 Home](de/README.md)

---

# CloudCastle HTTP-Router-Dokumentation

**Sprachen:** 🇷🇺 Russisch | [🇬🇧 Englisch](../en/README.md) | [🇩🇪 Deutsch](../de/README.md) | [🇫🇷 Français](../fr/README.md) | [🇨🇳中文](../zh/README.md)

[📚 Inhaltsverzeichnis](_table-of-contents.md) | [🏠 Startseite](README.md)

---

Willkommen bei der Dokumentation für CloudCastle HTTP Router – ein moderner, schneller und sicherer Router für PHP 8.2+.

## 📚 Inhalt

### Erste Schritte

- [Hauptseite](../../README.md) – Schnellstart und grundlegende Informationen
- [Erste Schritte](getting-started.md) – Leitfaden für Anfänger
- [Best Practices](best-practices.md) – Best Practices für die Entwicklung

### Testen

- [Zusammenfassung aller Tests](test-summary.md) – Ergebnisse aller Tests und Benchmarks
- [Unit-Tests](unit-tests.md) – detaillierte Ergebnisse von 419 Tests
- [Sicherheitstests](security-tests.md) – Analyse von 13 Sicherheitsprüfungen
- [Leistungstests](performance-tests.md) – Leistungsbenchmarks
- [Lasttests](load-tests.md) – Lasttests (50.000+ Anforderungen/Sek.)
- [Stresstests](stress-tests.md) – extreme Bedingungen (1 Mio.+ Routen)

### Möglichkeiten

- [Alle Funktionen](features.md) – vollständige Liste mit über 30 Funktionen
- [Auto-Naming](auto-naming.md) – automatische Benennung von Routen (ein einzigartiges Feature!)
- [Routenverknüpfungen](shortcuts.md) – 13+ Verknüpfungen für eine schnelle Einrichtung
- [Route-Makros](macros.md) – 7+ Makros (Code-Reduzierung um 80–97 %).
- [Hilfsfunktionen](helpers.md) – 15+ globale Funktionen
- [ThrottleWithBan](throttle-with-ban.md) – Ratenbegrenzung + automatische Sperre (einzigartige Funktion!)
- [Tags System](tags.md) – Tag-System zum Filtern von Routen
– [Route Loaders](loaders.md) – YAML/XML/JSON/Attributes-Konfiguration
- [Middleware](middleware.md) – Middleware und PSR-15-System
- [Fassade](facade.md) – statische Nutzung (Laravel-Stil)
– [Codequalität](code-quality.md) – PHPStan-, PHPMD-, PHPCS-Berichte

### Vergleich

- [Detaillierter Vergleich mit Mitbewerbern](comparison-detailed.md) – vollständige Analyse von 6 Routern

## 🎯 Über das Projekt

CloudCastle HTTP Router ist ein Hochleistungsrouter mit einzigartigen Sicherheitsfunktionen und Konfigurationsflexibilität.

### Schlüsselindikatoren

- **Leistung**: 50.946 Anfragen/Sek. (Durchschnitt)
- **Skalierbarkeit**: 1.095.000+ Routen
- **Sicherheit**: 13 Sicherheitsmechanismen
- **Tests**: 447 Tests, 1043+ Behauptungen
- **Abdeckung**: 100 % Erfolgsquote

## 📊 Testergebnisse

### Leistung

| Kategorie | Ergebnis | Status |
|:---|:---:|:---:|
| Light Load | 52,488 req/sec | ✅ |
| Medium Load | 45,260 req/sec | ✅ |
| Heavy Load | 55,089 req/sec | ✅ |
| Concurrent Access | 8,316 req/sec | ✅ |

### Skalierbarkeit

| Parameter | Bedeutung |
|:---|:---:|
| Maximale Routen | 1.095.000 |
| Speicher/Route | 1,39 KB |
| Schachtelungstiefe | 50 Level |
| URI-Länge | 1.980 Zeichen |

### Sicherheit

✅ Alle 13 Sicherheitstests erfolgreich bestanden:
- Path Traversal Protection
- SQL Injection Prevention
- XSS Protection
- IP Whitelist/Blacklist
- IP Spoofing Protection
- Domain Security
- ReDoS Protection
- Method Override Protection
- Mass Assignment Protection
- Cache Injection Prevention
- Resource Exhaustion Prevention
- Unicode Security

## 🆚 Vergleich mit Mitbewerbern

### Leistung (Anfragen/Sek.)

1. **CloudCastle** - 50,946 🥇
2. FastRoute - 47,033 🥈
3. AltoRouter - 39,967 🥉
4. Slim - 37,167
5. Laravel - 16,233
6. Symfony - 15,633

### Funktionalität (Anzahl der Features)

1. **CloudCastle** - 25/25 (100%) 🥇
2. Symfony - 10/25 (40%) 🥈
3. Laravel - 9/25 (36%) 🥉
4. Slim - 7/25 (28%)
5. AltoRouter - 4/25 (16%)
6. FastRoute - 3/25 (12%)

### Skalierbarkeit (maximale Routen)

1. **CloudCastle** - 1,095,000 🥇
2. FastRoute - 500,000 🥈
3. Slim - 200,000 🥉
4. AltoRouter - 150,000
5. Symfony - 100,000
6. Laravel - 80,000

## 🚀 Schnellstart

### Installation

```bash
composer require cloud-castle/http-router
```

### Grundlegende Verwendung

```php
use CloudCastle\Http\Router\Router;

$router = new Router();

$router->get('/', function() {
    return 'Hello, World!';
});

$router->get('/users/{id}', function($id) {
    return "User: {$id}";
});

$result = $router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
```

###Erweiterte Funktionen

```php
// Middleware
$router->get('/admin', 'AdminController@index')
    ->middleware(['auth', 'admin']);

// Rate Limiting
$router->get('/api/data', 'ApiController@data')
    ->perMinute(60);

// Conditions
$router->get('/premium', 'PremiumController@index')
    ->condition('user.subscription == "premium"');

// Groups
$router->group(['prefix' => '/api/v1'], function($router) {
    $router->get('/users', 'UserController@index');
    $router->get('/posts', 'PostController@index');
});
```

## 💡 Empfehlungen

### Wann man CloudCastle verwenden sollte

✅ **Ideal für:**
- API-Dienste mit hoher Auslastung
- Microservice-Architektur
- Projekte mit Sicherheitsanforderungen
- Unternehmensanwendungen
- Mandantenfähige Systeme

✅ **Vorteile:**
- Maximale Leistung
- Bessere Skalierbarkeit
- Umfassende Sicherheit
- Umfangreiche Funktionalität
- Moderner Code (PHP 8.2+)

### Best Practices

1. **Caching** in der Produktion verwenden
2. **Routen gruppieren** nach Funktionalität
3. **Verwenden Sie benannte Routen** für die URL-Generierung
4. **Verwenden Sie eine Ratenbegrenzung** für öffentliche APIs
5. **Anpassen von YAML/XML/JSON** für große Konfigurationen

## 📖 Zusätzliche Ressourcen

### Dokumentation

- [Testzusammenfassung](test-summary.md) – detaillierte Ergebnisse aller Tests
- [Vergleich von Routern](comparison-detailed.md) – vollständige Analyse der Alternativen

### Beispiele

Anwendungsbeispiele finden Sie im Verzeichnis „examples/“:
- `basic-usage.php` – grundlegendes Routing
- `yaml-routes.yaml` – YAML-Konfiguration
- `xml-routes.xml` – XML-Konfiguration
- `json-routes.json` – JSON-Konfiguration ⭐
- `attributes-usage.php` - PHP 8 Attributes
- „middleware-advanced.php“ – erweiterte Middleware
- `expression-usage.php` - Expression Language

### Berichte

Testergebnisse im Verzeichnis „reports/“:
- `phpunit.txt` – PHPUnit-Ergebnisse
- „security-tests.txt“ – Sicherheitstests
- „performance-tests.txt“ – Benchmarks
- `load-tests.txt` – Tests laden
- „stress-tests.txt“ – Stresstests
- „phpstan.txt“ – statische Analyse
- `phpcs.txt` - code style
- `phpmd.txt` - code quality

## 🤝 Unterstützung

- **Issues**: [GitHub Issues](https://github.com/zorinalexey/cloud-casstle-http-router/issues)
- **Email**: zorinalexey59292@gmail.com
- **Telegram**: [@CloudCastle85](https://t.me/CloudCastle85)

## 📄 Lizenz

MIT-Lizenz – siehe Datei [LICENSE](../../LICENSE).

---

**CloudCastle HTTP Router** – Maximale Leistung. Vollständige Sicherheit. Reichhaltigste Funktionalität.

*Letzte Aktualisierung: 18. Oktober 2025*

---

[📚 Inhaltsverzeichnis](_table-of-contents.md) | [🏠 Startseite](README.md)

---

[📚 Table of Contents](de/_table-of-contents.md) | [🏠 Home](de/README.md)
