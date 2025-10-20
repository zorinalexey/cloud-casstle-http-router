# Routen-Makros

[English](../../en/features/11_ROUTE_MACROS.md) | [Русский](../../ru/features/11_ROUTE_MACROS.md) | [**Deutsch**](11_ROUTE_MACROS.md) | [Français](../../fr/features/11_ROUTE_MACROS.md) | [中文](../../zh/features/11_ROUTE_MACROS.md)

---

## 📚 Dokumentationsnavigation

[README](../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [API_REFERENCE](../API_REFERENCE.md) | [ALL_FEATURES](../ALL_FEATURES.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [PERFORMANCE](../PERFORMANCE_ANALYSIS.md) | [SECURITY](../SECURITY_REPORT.md) | [COMPARISON](../COMPARISON.md) | [FAQ](../FAQ.md)

**Detaillierte Dokumentation:** [01](01_BASIC_ROUTING.md) | [02](02_ROUTE_PARAMETERS.md) | [03](03_ROUTE_GROUPS.md) | [04](04_RATE_LIMITING.md) | [05](05_IP_FILTERING.md) | [06](06_MIDDLEWARE.md) | [07](07_NAMED_ROUTES.md) | [08](08_TAGS.md) | [09](09_HELPER_FUNCTIONS.md) | [10](10_ROUTE_SHORTCUTS.md) | [11](11_ROUTE_MACROS.md) | [12](12_URL_GENERATION.md) | [13](13_EXPRESSION_LANGUAGE.md) | [14](14_CACHING.md) | [15](15_PLUGINS.md) | [16](16_LOADERS.md) | [17](17_PSR_SUPPORT.md) | [18](18_ACTION_RESOLVER.md) | [19](19_STATISTICS.md) | [20](20_SECURITY.md) | [21](21_EXCEPTIONS.md) | [22](22_CLI_TOOLS.md)

---

**Kategorie:** Automatisierung  
**Anzahl Makros:** 7  
**Komplexität:** ⭐⭐ Mittelstufe

---

## Beschreibung

Routen-Makros sind vordefinierte Routen-Vorlagen zum schnellen Erstellen von Standard-Routen-Sets (RESTful CRUD, Authentifizierung, Admin, etc.).

## Makros

### 1. resource() - RESTful-Ressource

```php
Route::resource('users', UserController::class);
```

Erstellt 7 Routen: index, create, store, show, edit, update, destroy

### 2. apiResource() - API-Ressource

```php
Route::apiResource('posts', PostController::class);
```

Erstellt 5 Routen (ohne create/edit Formulare)

### 3-7. Weitere Makros
- `auth()` - Authentifizierungs-Routen
- `admin()` - Admin-Panel-Routen
- `api()` - API-Routen
- `crud()` - Einfaches CRUD
- Custom Macro - Benutzerdefinierte Makros

## Siehe auch

- [Routen-Shortcuts](10_ROUTE_SHORTCUTS.md) - Schnelle Konfiguration
- [Grundlegendes Routing](01_BASIC_ROUTING.md) - Routen-Registrierung
- [API-Referenz](../API_REFERENCE.md) - Vollständige API-Referenz

---

© 2024 CloudCastle HTTP Router  
[⬆ Nach oben](#routen-makros)