# Ausdruckssprache

[English](../../en/features/13_EXPRESSION_LANGUAGE.md) | [Русский](../../ru/features/13_EXPRESSION_LANGUAGE.md) | [**Deutsch**](13_EXPRESSION_LANGUAGE.md) | [Français](../../fr/features/13_EXPRESSION_LANGUAGE.md) | [中文](../../zh/features/13_EXPRESSION_LANGUAGE.md)

---

## 📚 Dokumentationsnavigation

[README](../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [API_REFERENCE](../API_REFERENCE.md) | [ALL_FEATURES](../ALL_FEATURES.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [PERFORMANCE](../PERFORMANCE_ANALYSIS.md) | [SECURITY](../SECURITY_REPORT.md) | [COMPARISON](../COMPARISON.md) | [FAQ](../FAQ.md)

**Detaillierte Dokumentation:** [01](01_BASIC_ROUTING.md) | [02](02_ROUTE_PARAMETERS.md) | [03](03_ROUTE_GROUPS.md) | [04](04_RATE_LIMITING.md) | [05](05_IP_FILTERING.md) | [06](06_MIDDLEWARE.md) | [07](07_NAMED_ROUTES.md) | [08](08_TAGS.md) | [09](09_HELPER_FUNCTIONS.md) | [10](10_ROUTE_SHORTCUTS.md) | [11](11_ROUTE_MACROS.md) | [12](12_URL_GENERATION.md) | [13](13_EXPRESSION_LANGUAGE.md) | [14](14_CACHING.md) | [15](15_PLUGINS.md) | [16](16_LOADERS.md) | [17](17_PSR_SUPPORT.md) | [18](18_ACTION_RESOLVER.md) | [19](19_STATISTICS.md) | [20](20_SECURITY.md) | [21](21_EXCEPTIONS.md) | [22](22_CLI_TOOLS.md)

---

**Kategorie:** Erweiterte Funktionen  
**Anzahl Operatoren:** 5  
**Komplexität:** ⭐⭐⭐ Fortgeschrittenes Level

---

## Beschreibung

Ausdruckssprache ermöglicht das Erstellen von Bedingungen für Routen basierend auf berechneten Ausdrücken (IP, Zeit, Header, etc.).

## Verwendung

```php
// Nach IP
Route::get('/admin', $action)
    ->condition('request.ip == "192.168.1.1"');

// Nach Zeit
Route::get('/api/data', $action)
    ->condition('request.time > 9 and request.time < 18');

// Nach Headers
Route::get('/api/secure', $action)
    ->condition('request.header["X-API-Key"] == "secret"');
```

## Operatoren

- Vergleich: `==`, `!=`, `>`, `<`, `>=`, `<=`
- Logisch: `and`, `or`, `not`
- String: `matches`, `contains`, `starts_with`, `ends_with`

## Siehe auch

- [Routen-Parameter](02_ROUTE_PARAMETERS.md) - Parameter-Validierung
- [IP-Filterung](05_IP_FILTERING.md) - IP-basierte Zugriffskontrolle
- [API-Referenz](../API_REFERENCE.md) - Vollständige API-Referenz

---

© 2024 CloudCastle HTTP Router  
[⬆ Nach oben](#ausdruckssprache)