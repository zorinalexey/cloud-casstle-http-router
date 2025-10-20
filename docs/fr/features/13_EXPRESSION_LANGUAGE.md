# Langage d'Expression

[English](../../en/features/13_EXPRESSION_LANGUAGE.md) | [Русский](../../ru/features/13_EXPRESSION_LANGUAGE.md) | [Deutsch](../../de/features/13_EXPRESSION_LANGUAGE.md) | [**Français**](13_EXPRESSION_LANGUAGE.md) | [中文](../../zh/features/13_EXPRESSION_LANGUAGE.md)

---

## 📚 Navigation Documentation

[README](../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [API_REFERENCE](../API_REFERENCE.md) | [ALL_FEATURES](../ALL_FEATURES.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [PERFORMANCE](../PERFORMANCE_ANALYSIS.md) | [SECURITY](../SECURITY_REPORT.md) | [COMPARISON](../COMPARISON.md) | [FAQ](../FAQ.md)

**Documentation Détaillée:** [01](01_BASIC_ROUTING.md) | [02](02_ROUTE_PARAMETERS.md) | [03](03_ROUTE_GROUPS.md) | [04](04_RATE_LIMITING.md) | [05](05_IP_FILTERING.md) | [06](06_MIDDLEWARE.md) | [07](07_NAMED_ROUTES.md) | [08](08_TAGS.md) | [09](09_HELPER_FUNCTIONS.md) | [10](10_ROUTE_SHORTCUTS.md) | [11](11_ROUTE_MACROS.md) | [12](12_URL_GENERATION.md) | [13](13_EXPRESSION_LANGUAGE.md) | [14](14_CACHING.md) | [15](15_PLUGINS.md) | [16](16_LOADERS.md) | [17](17_PSR_SUPPORT.md) | [18](18_ACTION_RESOLVER.md) | [19](19_STATISTICS.md) | [20](20_SECURITY.md) | [21](21_EXCEPTIONS.md) | [22](22_CLI_TOOLS.md)

---

**Catégorie:** Fonctionnalités Avancées  
**Nombre d'Opérateurs:** 5  
**Complexité:** ⭐⭐⭐ Niveau Avancé

---

## Description

Le langage d'expression permet de créer des conditions pour les routes basées sur des expressions calculées (IP, temps, en-têtes, etc.).

## Utilisation

```php
// Par IP
Route::get('/admin', $action)
    ->condition('request.ip == "192.168.1.1"');

// Par temps
Route::get('/api/data', $action)
    ->condition('request.time > 9 and request.time < 18');

// Par en-têtes
Route::get('/api/secure', $action)
    ->condition('request.header["X-API-Key"] == "secret"');
```

## Opérateurs

- Comparaison: `==`, `!=`, `>`, `<`, `>=`, `<=`
- Logiques: `and`, `or`, `not`
- Chaînes: `matches`, `contains`, `starts_with`, `ends_with`

## Voir Aussi

- [Paramètres de Route](02_ROUTE_PARAMETERS.md) - Validation de paramètres
- [Filtrage IP](05_IP_FILTERING.md) - Contrôle d'accès basé sur IP
- [Référence API](../API_REFERENCE.md) - Référence API complète

---

© 2024 CloudCastle HTTP Router  
[⬆ Retour en haut](#langage-dexpression)