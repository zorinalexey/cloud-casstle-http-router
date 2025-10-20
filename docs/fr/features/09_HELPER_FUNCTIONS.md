# Fonctions Helper

[English](../../en/features/09_HELPER_FUNCTIONS.md) | [Русский](../../ru/features/09_HELPER_FUNCTIONS.md) | [Deutsch](../../de/features/09_HELPER_FUNCTIONS.md) | [**Français**](09_HELPER_FUNCTIONS.md) | [中文](../../zh/features/09_HELPER_FUNCTIONS.md)

---

## 📚 Navigation Documentation

[README](../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [API_REFERENCE](../API_REFERENCE.md) | [ALL_FEATURES](../ALL_FEATURES.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [PERFORMANCE](../PERFORMANCE_ANALYSIS.md) | [SECURITY](../SECURITY_REPORT.md) | [COMPARISON](../COMPARISON.md) | [FAQ](../FAQ.md)

**Documentation Détaillée:** [01](01_BASIC_ROUTING.md) | [02](02_ROUTE_PARAMETERS.md) | [03](03_ROUTE_GROUPS.md) | [04](04_RATE_LIMITING.md) | [05](05_IP_FILTERING.md) | [06](06_MIDDLEWARE.md) | [07](07_NAMED_ROUTES.md) | [08](08_TAGS.md) | [09](09_HELPER_FUNCTIONS.md) | [10](10_ROUTE_SHORTCUTS.md) | [11](11_ROUTE_MACROS.md) | [12](12_URL_GENERATION.md) | [13](13_EXPRESSION_LANGUAGE.md) | [14](14_CACHING.md) | [15](15_PLUGINS.md) | [16](16_LOADERS.md) | [17](17_PSR_SUPPORT.md) | [18](18_ACTION_RESOLVER.md) | [19](19_STATISTICS.md) | [20](20_SECURITY.md) | [21](21_EXCEPTIONS.md) | [22](22_CLI_TOOLS.md)

---

**Catégorie:** Fonctions Helper  
**Nombre de Fonctions:** 18  
**Complexité:** ⭐ Niveau Débutant

---

## Description

Les fonctions Helper sont des fonctions PHP globales qui simplifient le travail avec le routeur, fournissant une API courte et pratique sans nécessiter l'utilisation de noms de classes complets.

## Toutes les Fonctions

### 1. route()

**Signature:** `route(?string $name = null, array $parameters = []): ?Route`

**Description:** Obtenir une route par nom ou retourner la route actuelle.

**Paramètres:**
- `$name` - Nom de la route (null = route actuelle)
- `$parameters` - Paramètres pour substitution (réservé)

**Exemples:**

```php
// Obtenir une route par nom
$route = route('users.show');

// Obtenir la route actuelle
$current = route();

// Vérifier l'existence
if ($route = route('users.index')) {
    echo "La route existe: " . $route->getUri();
}

// Obtenir les informations de route
$route = route('api.users.show');
if ($route) {
    echo "URI: " . $route->getUri();
    echo "Nom: " . $route->getName();
    echo "Méthodes: " . implode(', ', $route->getMethods());
}
```

### 2. current_route()

**Signature:** `current_route(): ?Route`

**Description:** Obtenir la route en cours d'exécution.

**Exemples:**

```php
$route = current_route();
echo "Actuelle: " . $route->getUri();
```

### 3. previous_route()

**Signature:** `previous_route(): ?Route`

**Description:** Obtenir la route précédemment exécutée.

**Exemples:**

```php
$previous = previous_route();
if ($previous) {
    echo "Précédente: " . $previous->getUri();
}
```

### 4. route_is()

**Signature:** `route_is(string $name): bool`

**Description:** Vérifier si la route actuelle correspond au nom.

**Exemples:**

```php
if (route_is('users.show')) {
    echo "Affichage utilisateur";
}

if (route_is('admin.*')) {
    echo "Section admin";
}
```

### 5. route_name()

**Signature:** `route_name(): ?string`

**Description:** Obtenir le nom de la route actuelle.

**Exemples:**

```php
$name = route_name();
// 'users.show'
```

### 6. router()

**Signature:** `router(): Router`

**Description:** Obtenir l'instance du routeur.

**Exemples:**

```php
$router = router();
$allRoutes = $router->getAllRoutes();
```

### 7. dispatch_route()

**Signature:** `dispatch_route(string $uri, string $method = 'GET'): ?Route`

**Description:** Dispatcher et exécuter une route.

**Exemples:**

```php
$route = dispatch_route('/users/123', 'GET');
```

## Référence Rapide

```php
// Obtenir routes
route('users.show')          // Par nom
current_route()              // Route actuelle
previous_route()             // Route précédente

// Vérifier routes
route_is('users.show')       // Vérifier nom
route_name()                 // Obtenir nom

// Accès routeur
router()                     // Obtenir routeur
dispatch_route('/users')     // Dispatcher route
```

## Voir Aussi

- [Routes Nommées](07_NAMED_ROUTES.md) - Nommage des routes
- [Génération d'URL](12_URL_GENERATION.md) - Génération d'URL
- [Référence API](../API_REFERENCE.md) - Référence API complète

---

© 2024 CloudCastle HTTP Router  
[⬆ Retour en haut](#fonctions-helper)