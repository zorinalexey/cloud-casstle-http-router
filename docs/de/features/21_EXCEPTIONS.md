# Ausnahmen

[English](../../en/features/21_EXCEPTIONS.md) | [Русский](../../ru/features/21_EXCEPTIONS.md) | [**Deutsch**](21_EXCEPTIONS.md) | [Français](../../fr/features/21_EXCEPTIONS.md) | [中文](../../zh/features/21_EXCEPTIONS.md)

---

## 📚 Dokumentationsnavigation

[README](../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [API_REFERENCE](../API_REFERENCE.md) | [ALL_FEATURES](../ALL_FEATURES.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [PERFORMANCE](../PERFORMANCE_ANALYSIS.md) | [SECURITY](../SECURITY_REPORT.md) | [COMPARISON](../COMPARISON.md) | [FAQ](../FAQ.md)

**Detaillierte Dokumentation:** [01](01_BASIC_ROUTING.md) | [02](02_ROUTE_PARAMETERS.md) | [03](03_ROUTE_GROUPS.md) | [04](04_RATE_LIMITING.md) | [05](05_IP_FILTERING.md) | [06](06_MIDDLEWARE.md) | [07](07_NAMED_ROUTES.md) | [08](08_TAGS.md) | [09](09_HELPER_FUNCTIONS.md) | [10](10_ROUTE_SHORTCUTS.md) | [11](11_ROUTE_MACROS.md) | [12](12_URL_GENERATION.md) | [13](13_EXPRESSION_LANGUAGE.md) | [14](14_CACHING.md) | [15](15_PLUGINS.md) | [16](16_LOADERS.md) | [17](17_PSR_SUPPORT.md) | [18](18_ACTION_RESOLVER.md) | [19](19_STATISTICS.md) | [20](20_SECURITY.md) | [21](21_EXCEPTIONS.md) | [22](22_CLI_TOOLS.md)

---

**Kategorie:** Fehlerbehandlung  
**Anzahl Typen:** 8  
**Komplexität:** ⭐ Anfänger-Level

---

## Alle Ausnahmen

### 1. RouteNotFoundException
```php
try {
    $route = Route::dispatch('/nonexistent', 'GET');
} catch (\CloudCastle\Http\Router\Exceptions\RouteNotFoundException $e) {
    http_response_code(404);
}
```

### 2. MethodNotAllowedException
```php
catch (\CloudCastle\Http\Router\Exceptions\MethodNotAllowedException $e) {
    http_response_code(405);
}
```

### 3. TooManyRequestsException
```php
catch (\CloudCastle\Http\Router\Exceptions\TooManyRequestsException $e) {
    http_response_code(429);
}
```

### 4-8. Weitere Ausnahmen

## Siehe auch

- [Grundlegendes Routing](01_BASIC_ROUTING.md) - Routen-Registrierung
- [API-Referenz](../API_REFERENCE.md) - Vollständige API-Referenz

---

© 2024 CloudCastle HTTP Router  
[⬆ Nach oben](#ausnahmen)