# Einführung

**CloudCastle HTTP Router v1.1.1**  
**Sprache**: Deutsch

---

## 🌍 Übersetzungen

- [Русский](../../ru/documentation/introduction.md)
- [English](../../en/documentation/introduction.md)
- **[Deutsch](introduction.md)** (aktuell)
- [Français](../../fr/documentation/introduction.md)

---

## Über das Projekt

**CloudCastle HTTP Router** ist ein hochleistungsfähiger HTTP-Router für PHP 8.2+, entwickelt mit Fokus auf Leistung, Sicherheit und Benutzerfreundlichkeit.

### Projekt-Philosophie

Wir haben einen Router geschaffen, der vereint:
- **Geschwindigkeit** - 60.000+ Anfragen pro Sekunde
- **Skalierbarkeit** - Unterstützung für 740.000+ Routen
- **Sicherheit** - eingebauter Schutz vor Angriffen
- **Komfort** - intuitive API und Automatisierung

---

## ✨ Hauptfunktionen

### Routing
- ✅ Alle HTTP-Methoden (GET, POST, PUT, PATCH, DELETE, OPTIONS, HEAD)
- ✅ Dynamische Parameter mit Regex-Einschränkungen
- ✅ Routengruppen mit Präfixen
- ✅ Benannte Routen
- ✅ Getaggte Routen
- ✅ **Automatische Routenbenennung** 🆕
- ✅ Verschachtelte Gruppen
- ✅ Routen-Caching

### Sicherheit
- 🛡️ **Rate Limiting** mit flexiblen Zeitfenstern
- 🚫 **Auto-Ban** bei Grenzwertüberschreitung
- 🔒 **IP-Filterung** (Weiße/Schwarze Listen)
- 🌐 **Domain-Einschränkungen**
- 🔐 **Protokoll-Einschränkungen** (HTTP/HTTPS/WS/WSS)
- 🛡️ **HTTPS Enforcement** Middleware
- 🛡️ **SSRF Protection** Middleware
- 📝 **Security Logging** Middleware
- ✅ **OWASP Top 10** konform

### Leistung
- 🚀 **60.000+ req/s** bei leichter Last
- 📊 **O(1)** Suchkomplexität
- 💾 **1,47 KB** Speicher pro Route
- ⚡ Kompilierung und Caching
- 🎯 Indizierung für schnelle Suche

---

## 🎯 Für wen ist dieser Router?

### Perfekt für:

✅ **High-Load-Anwendungen** - wenn Leistung wichtig ist  
✅ **API-Dienste** - mit eingebautem Rate Limiting und Schutz  
✅ **Microservices** - leichtgewichtig und eigenständig  
✅ **Enterprise-Projekte** - mit hohen Qualitätsanforderungen  
✅ **Projekte mit vielen Routen** - Skalierbarkeit  

---

## 📦 Installation

### Anforderungen

- **PHP**: 8.2, 8.3 oder 8.4
- **Composer**: 2.x
- **Erweiterungen**: mbstring, json

### Installation über Composer

```bash
composer require cloudcastle/http-router
```

---

## 🚀 Schnellstart

```php
use CloudCastle\Http\Router\Facade\Route;

Route::get('/users', fn() => 'Benutzerliste');
Route::post('/users', fn() => 'Benutzer erstellt');

$result = Route::dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
echo $result;
```

---

## 📊 Leistung

### Benchmarks

| Last | Req/sec | Speicher |
|------|---------|----------|
| Leicht (100 Routen) | 60.095 | 4 MB |
| Mittel (500 Routen) | 58.905 | 4 MB |
| Schwer (1.000 Routen) | 59.599 | 6 MB |

---

## 🛡️ Sicherheit

### Eingebauter Schutz

- **Rate Limiting**: Von Sekunden bis Monaten
- **Auto-Ban**: Automatische Sperrung bei Verstößen
- **IP-Filterung**: Weiße und schwarze Listen
- **HTTPS Enforcement**: Erzwungene HTTPS-Nutzung
- **SSRF Protection**: Schutz vor SSRF-Angriffen

### Sicherheitstests

✅ 13/13 Sicherheitstests bestanden  
✅ OWASP Top 10 konform  
✅ Schutz: XSS, SQL Injection, Path Traversal, ReDoS  

---

## 📚 Dokumentation

### Hauptthemen

1. [Schnellstart](quickstart.md)
2. [Routen](routes.md)
3. [Auto-Naming](auto-naming.md) 🆕
4. [Routengruppen](route-groups.md)
5. [Middleware](middleware.md)
6. [Rate Limiting](rate-limiting.md)
7. [Auto-ban](auto-ban.md)
8. [Sicherheit](security.md)
9. [Leistung](performance.md)
10. [API-Referenz](api-reference.md)

---

## 🤝 Community

### Support

- **GitHub Issues**: https://github.com/zorinalexey/cloud-casstle-http-router/issues
- **Telegram**: https://t.me/cloud_castle_news
- **E-Mail**: zorinalexey59292@gmail.com

---

**CloudCastle HTTP Router** - Ihre Wahl für hochleistungsfähiges Routing! 🚀

**[← Zurück zum Inhaltsverzeichnis](README.md)**

