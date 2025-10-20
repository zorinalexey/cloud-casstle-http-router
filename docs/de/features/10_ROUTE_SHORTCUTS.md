# Routen-Shortcuts

[English](../../en/features/10_ROUTE_SHORTCUTS.md) | [Русский](../../ru/features/10_ROUTE_SHORTCUTS.md) | [**Deutsch**](10_ROUTE_SHORTCUTS.md) | [Français](../../fr/features/10_ROUTE_SHORTCUTS.md) | [中文](../../zh/features/10_ROUTE_SHORTCUTS.md)

---

## 📚 Dokumentationsnavigation

[README](../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [API_REFERENCE](../API_REFERENCE.md) | [ALL_FEATURES](../ALL_FEATURES.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [PERFORMANCE](../PERFORMANCE_ANALYSIS.md) | [SECURITY](../SECURITY_REPORT.md) | [COMPARISON](../COMPARISON.md) | [FAQ](../FAQ.md)

**Detaillierte Dokumentation:** [01](01_BASIC_ROUTING.md) | [02](02_ROUTE_PARAMETERS.md) | [03](03_ROUTE_GROUPS.md) | [04](04_RATE_LIMITING.md) | [05](05_IP_FILTERING.md) | [06](06_MIDDLEWARE.md) | [07](07_NAMED_ROUTES.md) | [08](08_TAGS.md) | [09](09_HELPER_FUNCTIONS.md) | [10](10_ROUTE_SHORTCUTS.md) | [11](11_ROUTE_MACROS.md) | [12](12_URL_GENERATION.md) | [13](13_EXPRESSION_LANGUAGE.md) | [14](14_CACHING.md) | [15](15_PLUGINS.md) | [16](16_LOADERS.md) | [17](17_PSR_SUPPORT.md) | [18](18_ACTION_RESOLVER.md) | [19](19_STATISTICS.md) | [20](20_SECURITY.md) | [21](21_EXCEPTIONS.md) | [22](22_CLI_TOOLS.md)

---

**Kategorie:** Benutzerfreundlichkeit  
**Anzahl Methoden:** 14  
**Komplexität:** ⭐ Anfänger-Level

---

## Beschreibung

Routen-Shortcuts sind Verknüpfungsmethoden zum schnellen Einrichten typischer Routen-Konfigurationen (Middleware, Throttle, Tags, etc.). Ein Methodenaufruf ersetzt mehrere Konfigurationszeilen.

## Alle Shortcuts

### 1. auth()
```php
Route::get('/dashboard', $action)->auth();
// Entspricht: ->middleware([AuthMiddleware::class])
```

### 2. guest()
```php
Route::get('/login', $action)->guest();
```

### 3. cors()
```php
Route::get('/api/data', $action)->cors();
```

### 4. secure()
```php
Route::post('/payment', $action)->secure();
```

### 5-14. Weitere Shortcuts
- `apiResource()` - API-Ressource
- `resource()` - RESTful-Ressource
- `redirect()` - Umleitung
- `view()` - View rendern
- `permanent()` - 301 Umleitung
- `temporary()` - 302 Umleitung
- `cached()` - Caching
- `throttled()` - Rate Limiting
- `tagged()` - Tags
- `named()` - Name setzen

## Siehe auch

- [Middleware](06_MIDDLEWARE.md) - Middleware-System
- [Routen-Makros](11_ROUTE_MACROS.md) - Routen-Vorlagen
- [API-Referenz](../API_REFERENCE.md) - Vollständige API-Referenz

---

© 2024 CloudCastle HTTP Router  
[⬆ Nach oben](#routen-shortcuts)