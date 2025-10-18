# CloudCastle HTTP Router - Dokumentation

**Version**: 1.1.1  
**Sprache**: Deutsch

---

## 🌍 Übersetzungen

- [Русский](../../ru/documentation/README.md) (Vollständige Dokumentation)
- [English](../../en/documentation/README.md)
- **[Deutsch](README.md)** (aktuell)
- [Français](../../fr/documentation/README.md)

---

## 📚 Dokumentation

**Hinweis**: Die vollständige detaillierte Dokumentation ist derzeit auf Russisch verfügbar. Die deutsche Übersetzung ist in Arbeit.

Für die vollständige Dokumentation siehe:
- **[Russische Dokumentation](../../ru/documentation/README.md)** (Vollständig)

---

## 🚀 Schnellstart

```php
<?php

use CloudCastle\Http\Router\Facade\Route;

// Einfache Route
Route::get('/users', fn() => 'User list');

// Mit Parametern
Route::get('/user/{id}', fn($id) => "User: $id");

// Mit Rate Limiting und Auto-Ban
Route::post('/login', 'AuthController@login')
    ->throttleWithBan(
        maxAttempts: 5,
        decaySeconds: 60,
        maxViolations: 3,
        banDurationSeconds: 7200
    );

// Dispatch
$result = Route::dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
```

---

## ✨ Hauptmerkmale

- ✅ Alle HTTP-Methoden (GET, POST, PUT, PATCH, DELETE, OPTIONS, HEAD)
- ✅ Dynamische Parameter mit Einschränkungen
- ✅ Routengruppen mit gemeinsamen Attributen
- ✅ Benannte und getaggte Routen
- ✅ **Automatische Routenbenennung** 🆕
- ✅ Reguläre Ausdrücke
- ✅ Routen-Caching
- 🛡️ **Auto-Ban** - Schutz vor Brute-Force und DDoS
- ⏱️ **Flexible Zeitfenster** - von Sekunden bis Monaten
- 🔒 IP-Filterung (Weiße/Schwarze Listen)
- 🚀 **60.000+ req/s** Leistung
- 📊 **740.000+ Routen** unterstützt

---

## 📊 Testergebnisse

### Unit-Tests
- **263 Tests** - alle bestanden ✅
- **611 Assertions**
- **Abdeckung**: ~95%

### Leistung
- **Light Load**: 60.095 req/s
- **Heavy Load**: 59.599 req/s
- **Speicher**: 1,47 KB pro Route

### Statische Analyse
- **PHPStan**: Level MAX - 0 Fehler ✅
- **PHPCS**: PSR-12 - 0 Fehler ✅

---

## 📦 Installation

```bash
composer require cloudcastle/http-router
```

**Anforderungen**:
- PHP 8.2, 8.3 oder 8.4
- Composer 2.x

---

## 🆚 Vergleich mit Alternativen

| Merkmal | CloudCastle | FastRoute | Symfony | Laravel |
|---------|-------------|-----------|---------|---------|
| **Leistung** | **60k req/s** | 50k | 30k | 25k |
| **Max. Routen** | **740k+** | 100k | 50k | 30k |
| **Speicher/Route** | **1,47 KB** | 2,5 KB | 3,8 KB | 4,2 KB |
| **Rate Limiting** | ✅ Eingebaut | ❌ | ❌ | ✅ Paket |
| **Auto-Ban** | ✅ | ❌ | ❌ | ❌ |
| **Auto-Naming** | ✅ | ❌ | ❌ | ❌ |

---

## 🔗 Links

- **GitHub**: https://github.com/zorinalexey/cloud-casstle-http-router
- **Packagist**: https://packagist.org/packages/cloudcastle/http-router
- **Support-Chat**: [Telegram](https://t.me/cloud_castle_news)
- **E-Mail**: zorinalexey59292@gmail.com

---

**CloudCastle HTTP Router** - Leistung. Sicherheit. Einfachheit.

**Sprache**: Deutsch | [Русский](../../ru/documentation/README.md) | [English](../../en/documentation/README.md) | [Français](../../fr/documentation/README.md)

