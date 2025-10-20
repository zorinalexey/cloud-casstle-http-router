# Changelog

[English](../en/CHANGELOG.md) | [Русский](../../CHANGELOG.md) | [**Deutsch**](CHANGELOG.md) | [Français](../fr/CHANGELOG.md) | [中文](../zh/CHANGELOG.md)

---

Alle bedeutsamen Änderungen am Projekt werden in dieser Datei dokumentiert.

Das Format basiert auf [Keep a Changelog](https://keepachangelog.com/de/1.0.0/),
und dieses Projekt folgt [Semantic Versioning](https://semver.org/lang/de/).

## [Unreleased]

### Geplant
- Trie-Struktur für parametrisierte Routen
- Kompilierter Regex-Cache
- PHP JIT-Optimierungen
- WebSocket-Unterstützung
- GraphQL-Routing-Unterstützung

## [1.1.1] - 2024-12-20

### Behoben
- Parameter `protocol` zu `dispatch`-Methoden in Facade und Router hinzugefügt
- Leere Zeichenfolge nach Statement in JsonLoaderTest behoben
- Rector-Konfiguration aktualisiert, um False-Positive-Warnungen auszuschließen

### Verbessert
- Vollständige Kompatibilität mit PHP 8.4
- Verbesserte Dokumentation
- Detaillierte Testberichte hinzugefügt

## [1.1.0] - 2024-12-01

### Hinzugefügt
- Expression Language für komplexe Routing-Bedingungen
- Plugin-System für Erweiterbarkeit
- Auto-Naming für Routen
- Port-basiertes Routing
- Verbessertes Tagging-System
- BanManager für automatische IP-Blockierung
- TimeUnit-Enum für bequeme Zeitintervall-Spezifikation
- Route-Dumper für Route-Export
- UrlMatcher für erweiterte URL-Zuordnung

### Geändert
- Indexierungs-System für Routensuche optimiert
- Rate-Limiter-Performance verbessert
- RouteCompiler für bessere Performance refaktoriert

### Behoben
- Probleme mit tiefer Gruppenverschachtelung
- Speicherlecks bei großer Anzahl von Routen
- Fehlerhafte Whitelist/Blacklist-IP-Operation
  
## [1.0.0] - 2024-11-01

### Hinzugefügt
- Grundlegende Router-Funktionalität
- Unterstützung für alle HTTP-Methoden (GET, POST, PUT, PATCH, DELETE, VIEW, ANY, MATCH)
- Route-Gruppen-System
- Middleware-Unterstützung
- Benannte Routen
- Rate Limiting
- IP-Filterung (Whitelist/Blacklist)
- Domain-Routing
- HTTPS-Erzwingung
- Route-Caching
- URL-Generator
- Mehrere Route-Loader:
  - JsonLoader
  - YamlLoader
  - XmlLoader
  - PhpLoader
  - AttributeLoader
- MiddlewareDispatcher
- Route-Parameter mit Einschränkungen
- PSR-7 und PSR-15 Kompatibilität

### Tests
- 501 Unit-Tests
- 13 Sicherheitstests
- 5 Performance-Tests
- Load-Tests
- Stress-Tests
- PHPBench-Benchmarks

### Dokumentation
- README.md
- Detaillierte API-Dokumentation
- Verwendungsbeispiele
- Benutzerhandbuch

## [0.9.0] - 2024-10-15

### Hinzugefügt
- Erste Beta-Version
- Grundlegendes Routing
- Parameter-Unterstützung
- Einfache Gruppen

## [0.5.0] - 2024-10-01

### Hinzugefügt
- Alpha-Version
- Proof of Concept
- Grundlegende Tests

---

## Änderungstypen

- **Hinzugefügt** - für neue Funktionalität
- **Geändert** - für Änderungen an bestehender Funktionalität
- **Veraltet** - für Funktionalität, die bald entfernt wird
- **Entfernt** - für entfernte Funktionalität
- **Behoben** - für Bug-Fixes
- **Sicherheit** - für Sicherheitslücken-Fixes

---

[Unreleased]: https://github.com/zorinalexey/cloud-casstle-http-router/compare/v1.1.1...HEAD
[1.1.1]: https://github.com/zorinalexey/cloud-casstle-http-router/compare/v1.1.0...v1.1.1
[1.1.0]: https://github.com/zorinalexey/cloud-casstle-http-router/compare/v1.0.0...v1.1.0
[1.0.0]: https://github.com/zorinalexey/cloud-casstle-http-router/compare/v0.9.0...v1.0.0
[0.9.0]: https://github.com/zorinalexey/cloud-casstle-http-router/compare/v0.5.0...v0.9.0
[0.5.0]: https://github.com/zorinalexey/cloud-casstle-http-router/releases/tag/v0.5.0

