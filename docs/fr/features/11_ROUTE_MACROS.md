# Macros de Routes

[English](../../en/features/11_ROUTE_MACROS.md) | [Русский](../../ru/features/11_ROUTE_MACROS.md) | [Deutsch](../../de/features/11_ROUTE_MACROS.md) | [**Français**](11_ROUTE_MACROS.md) | [中文](../../zh/features/11_ROUTE_MACROS.md)

---

## 📚 Navigation Documentation

[README](../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [API_REFERENCE](../API_REFERENCE.md) | [ALL_FEATURES](../ALL_FEATURES.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [PERFORMANCE](../PERFORMANCE_ANALYSIS.md) | [SECURITY](../SECURITY_REPORT.md) | [COMPARISON](../COMPARISON.md) | [FAQ](../FAQ.md)

**Documentation Détaillée:** [01](01_BASIC_ROUTING.md) | [02](02_ROUTE_PARAMETERS.md) | [03](03_ROUTE_GROUPS.md) | [04](04_RATE_LIMITING.md) | [05](05_IP_FILTERING.md) | [06](06_MIDDLEWARE.md) | [07](07_NAMED_ROUTES.md) | [08](08_TAGS.md) | [09](09_HELPER_FUNCTIONS.md) | [10](10_ROUTE_SHORTCUTS.md) | [11](11_ROUTE_MACROS.md) | [12](12_URL_GENERATION.md) | [13](13_EXPRESSION_LANGUAGE.md) | [14](14_CACHING.md) | [15](15_PLUGINS.md) | [16](16_LOADERS.md) | [17](17_PSR_SUPPORT.md) | [18](18_ACTION_RESOLVER.md) | [19](19_STATISTICS.md) | [20](20_SECURITY.md) | [21](21_EXCEPTIONS.md) | [22](22_CLI_TOOLS.md)

---

**Catégorie:** Automatisation  
**Nombre de Macros:** 7  
**Complexité:** ⭐⭐ Niveau Intermédiaire

---

## Description

Les macros de routes sont des templates de routes prédéfinis pour créer rapidement des ensembles de routes standard (RESTful CRUD, authentification, admin, etc.).

## Macros

### 1. resource() - Ressource RESTful

```php
Route::resource('users', UserController::class);
```

Crée 7 routes: index, create, store, show, edit, update, destroy

### 2. apiResource() - Ressource API

```php
Route::apiResource('posts', PostController::class);
```

Crée 5 routes (sans formulaires create/edit)

### 3-7. Autres Macros
- `auth()` - Routes d'authentification
- `admin()` - Routes panneau admin
- `api()` - Routes API
- `crud()` - CRUD simple
- Custom Macro - Macros personnalisées

## Voir Aussi

- [Raccourcis de Routes](10_ROUTE_SHORTCUTS.md) - Configuration rapide
- [Routage de Base](01_BASIC_ROUTING.md) - Enregistrement de routes
- [Référence API](../API_REFERENCE.md) - Référence API complète

---

© 2024 CloudCastle HTTP Router  
[⬆ Retour en haut](#macros-de-routes)