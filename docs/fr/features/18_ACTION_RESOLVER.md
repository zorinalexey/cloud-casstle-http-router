# Résolveur d'Actions

[English](../../en/features/18_ACTION_RESOLVER.md) | [Русский](../../ru/features/18_ACTION_RESOLVER.md) | [Deutsch](../../de/features/18_ACTION_RESOLVER.md) | [**Français**](18_ACTION_RESOLVER.md) | [中文](../../zh/features/18_ACTION_RESOLVER.md)

---

## 📚 Navigation Documentation

[README](../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [API_REFERENCE](../API_REFERENCE.md) | [ALL_FEATURES](../ALL_FEATURES.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [PERFORMANCE](../PERFORMANCE_ANALYSIS.md) | [SECURITY](../SECURITY_REPORT.md) | [COMPARISON](../COMPARISON.md) | [FAQ](../FAQ.md)

**Documentation Détaillée:** [01](01_BASIC_ROUTING.md) | [02](02_ROUTE_PARAMETERS.md) | [03](03_ROUTE_GROUPS.md) | [04](04_RATE_LIMITING.md) | [05](05_IP_FILTERING.md) | [06](06_MIDDLEWARE.md) | [07](07_NAMED_ROUTES.md) | [08](08_TAGS.md) | [09](09_HELPER_FUNCTIONS.md) | [10](10_ROUTE_SHORTCUTS.md) | [11](11_ROUTE_MACROS.md) | [12](12_URL_GENERATION.md) | [13](13_EXPRESSION_LANGUAGE.md) | [14](14_CACHING.md) | [15](15_PLUGINS.md) | [16](16_LOADERS.md) | [17](17_PSR_SUPPORT.md) | [18](18_ACTION_RESOLVER.md) | [19](19_STATISTICS.md) | [20](20_SECURITY.md) | [21](21_EXCEPTIONS.md) | [22](22_CLI_TOOLS.md)

---

**Catégorie:** Traitement d'Actions  
**Nombre de Formats:** 6  
**Complexité:** ⭐⭐ Niveau Intermédiaire

---

## Formats Supportés

```php
// 1. Closure
Route::get('/users', function() { return 'Users'; });

// 2. Array
Route::get('/users', [UserController::class, 'index']);

// 3. String
Route::get('/users', 'UserController@index');

// 4-6. Autres formats
```

## Voir Aussi

- [Routage de Base](01_BASIC_ROUTING.md) - Enregistrement de routes
- [Référence API](../API_REFERENCE.md) - Référence API complète

---

© 2024 CloudCastle HTTP Router  
[⬆ Retour en haut](#résolveur-dactions)