# Génération d'URL

[English](../../en/features/12_URL_GENERATION.md) | [Русский](../../ru/features/12_URL_GENERATION.md) | [Deutsch](../../de/features/12_URL_GENERATION.md) | [**Français**](12_URL_GENERATION.md) | [中文](../../zh/features/12_URL_GENERATION.md)

---

## 📚 Navigation Documentation

[README](../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [API_REFERENCE](../API_REFERENCE.md) | [ALL_FEATURES](../ALL_FEATURES.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [PERFORMANCE](../PERFORMANCE_ANALYSIS.md) | [SECURITY](../SECURITY_REPORT.md) | [COMPARISON](../COMPARISON.md) | [FAQ](../FAQ.md)

**Documentation Détaillée:** [01](01_BASIC_ROUTING.md) | [02](02_ROUTE_PARAMETERS.md) | [03](03_ROUTE_GROUPS.md) | [04](04_RATE_LIMITING.md) | [05](05_IP_FILTERING.md) | [06](06_MIDDLEWARE.md) | [07](07_NAMED_ROUTES.md) | [08](08_TAGS.md) | [09](09_HELPER_FUNCTIONS.md) | [10](10_ROUTE_SHORTCUTS.md) | [11](11_ROUTE_MACROS.md) | [12](12_URL_GENERATION.md) | [13](13_EXPRESSION_LANGUAGE.md) | [14](14_CACHING.md) | [15](15_PLUGINS.md) | [16](16_LOADERS.md) | [17](17_PSR_SUPPORT.md) | [18](18_ACTION_RESOLVER.md) | [19](19_STATISTICS.md) | [20](20_SECURITY.md) | [21](21_EXCEPTIONS.md) | [22](22_CLI_TOOLS.md)

---

**Catégorie:** Génération d'URL  
**Nombre de Méthodes:** 11  
**Complexité:** ⭐⭐ Niveau Intermédiaire

---

## Classe UrlGenerator

```php
use CloudCastle\Http\Router\UrlGenerator;

$generator = new UrlGenerator();
```

## Méthodes

### 1. generate()
```php
$url = $generator->generate('users.show', ['id' => 123]);
// '/users/123'
```

### 2. absolute()
```php
$url = $generator->generate('users.show', ['id' => 123])->absolute();
// 'http://example.com/users/123'
```

### 3-6. Autres Méthodes
- `toDomain()` - Vers domaine spécifique
- `secure()` - Forcer HTTPS
- `withQuery()` - Ajouter paramètres de requête
- `withFragment()` - Ajouter fragment

## Voir Aussi

- [Routes Nommées](07_NAMED_ROUTES.md) - Nommage des routes
- [Fonctions Helper](09_HELPER_FUNCTIONS.md) - Fonctions helper
- [Référence API](../API_REFERENCE.md) - Référence API complète

---

© 2024 CloudCastle HTTP Router  
[⬆ Retour en haut](#génération-durl)