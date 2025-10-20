# Routage de Base

[English](../../en/features/01_BASIC_ROUTING.md) | [Русский](../../ru/features/01_BASIC_ROUTING.md) | [Deutsch](../../de/features/01_BASIC_ROUTING.md) | [**Français**](01_BASIC_ROUTING.md) | [中文](../../zh/features/01_BASIC_ROUTING.md)

---

## 📚 Navigation Documentation

[README](../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [API_REFERENCE](../API_REFERENCE.md) | [ALL_FEATURES](../ALL_FEATURES.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [PERFORMANCE](../PERFORMANCE_ANALYSIS.md) | [SECURITY](../SECURITY_REPORT.md) | [COMPARISON](../COMPARISON.md) | [FAQ](../FAQ.md)

**Documentation Détaillée:** [01](01_BASIC_ROUTING.md) | [02](02_ROUTE_PARAMETERS.md) | [03](03_ROUTE_GROUPS.md) | [04](04_RATE_LIMITING.md) | [05](05_IP_FILTERING.md) | [06](06_MIDDLEWARE.md) | [07](07_NAMED_ROUTES.md) | [08](08_TAGS.md) | [09](09_HELPER_FUNCTIONS.md) | [10](10_ROUTE_SHORTCUTS.md) | [11](11_ROUTE_MACROS.md) | [12](12_URL_GENERATION.md) | [13](13_EXPRESSION_LANGUAGE.md) | [14](14_CACHING.md) | [15](15_PLUGINS.md) | [16](16_LOADERS.md) | [17](17_PSR_SUPPORT.md) | [18](18_ACTION_RESOLVER.md) | [19](19_STATISTICS.md) | [20](20_SECURITY.md) | [21](21_EXCEPTIONS.md) | [22](22_CLI_TOOLS.md)

---

**Catégorie:** Fonctionnalités Principales  
**Nombre de Méthodes:** 13  
**Complexité:** ⭐ Niveau Débutant

---

## Description

Le routage de base est la capacité fondamentale de CloudCastle HTTP Router, permettant d'enregistrer des gestionnaires pour diverses méthodes HTTP et URIs.

## Fonctionnalités

### 1. Route GET

**Méthode:** `Route::get(string $uri, mixed $action): Route`

**Description:** Enregistre une route pour les requêtes HTTP GET.

**Paramètres:**
- `$uri` - URI de la route (ex: `/users`, `/posts/{id}`)
- `$action` - Action (Closure, array, string contrôleur)

**Retourne:** Objet `Route` pour le chaînage de méthodes

**Exemples:**

```php
use CloudCastle\Http\Router\Facade\Route;

// Route simple avec Closure
Route::get('/users', function() {
    return 'Liste des utilisateurs';
});

// Avec contrôleur (array)
Route::get('/users', [UserController::class, 'index']);

// Avec contrôleur (string)
Route::get('/users', 'UserController@index');

// Avec paramètres
Route::get('/users/{id}', function($id) {
    return "ID Utilisateur: $id";
});

// Chaînage de méthodes
Route::get('/api/users', [UserController::class, 'index'])
    ->name('api.users')
    ->middleware([AuthMiddleware::class])
    ->throttle(100, 1);
```

**Utilisation:**
- Récupération de données (listes, détails)
- Affichage de pages
- Points de terminaison API pour la lecture

---

### 2. Route POST

**Méthode:** `Route::post(string $uri, mixed $action): Route`

**Description:** Enregistre une route pour les requêtes HTTP POST.

**Paramètres:**
- `$uri` - URI de la route
- `$action` - Action

**Retourne:** Objet `Route`

**Exemples:**

```php
// Création de ressource
Route::post('/users', function() {
    $data = $_POST;
    // Créer utilisateur
    return 'Utilisateur créé';
});

// Avec contrôleur
Route::post('/users', [UserController::class, 'store']);

// Avec validation et limitation de débit
Route::post('/users', [UserController::class, 'store'])
    ->middleware([ValidateUser::class])
    ->throttle(20, 1);  // 20 requêtes par minute
```

**Utilisation:**
- Création de nouvelles ressources
- Soumission de formulaires
- Création de données API

---

### 3. Route PUT

**Méthode:** `Route::put(string $uri, mixed $action): Route`

**Description:** Enregistre une route pour les requêtes HTTP PUT (mise à jour complète de ressource).

**Paramètres:**
- `$uri` - URI de la route (généralement avec paramètre ID)
- `$action` - Action

**Retourne:** Objet `Route`

**Exemples:**

```php
// Mise à jour complète de ressource
Route::put('/users/{id}', function($id) {
    $data = json_decode(file_get_contents('php://input'), true);
    // Mise à jour complète utilisateur
    return "Utilisateur $id mis à jour";
});

// Avec contrôleur
Route::put('/users/{id}', [UserController::class, 'update'])
    ->where('id', '[0-9]+');

// API RESTful
Route::put('/api/v1/users/{id}', [ApiUserController::class, 'update'])
    ->middleware([AuthMiddleware::class])
    ->name('api.v1.users.update');
```

**Utilisation:**
- Mises à jour complètes de ressources
- Remplacement complet de données
- Mises à jour API RESTful

---

### 4. Route PATCH

**Méthode:** `Route::patch(string $uri, mixed $action): Route`

**Description:** Enregistre une route pour les requêtes HTTP PATCH (mise à jour partielle de ressource).

**Paramètres:**
- `$uri` - URI de la route
- `$action` - Action

**Retourne:** Objet `Route`

**Exemples:**

```php
// Mise à jour partielle de ressource
Route::patch('/users/{id}', function($id) {
    $data = json_decode(file_get_contents('php://input'), true);
    // Mise à jour partielle utilisateur
    return "Utilisateur $id partiellement mis à jour";
});

// Avec contrôleur
Route::patch('/users/{id}', [UserController::class, 'patch'])
    ->where('id', '[0-9]+');
```

**Utilisation:**
- Mises à jour partielles de ressources
- Modifications spécifiques aux champs
- Mises à jour efficaces

---

### 5. Route DELETE

**Méthode:** `Route::delete(string $uri, mixed $action): Route`

**Description:** Enregistre une route pour les requêtes HTTP DELETE.

**Paramètres:**
- `$uri` - URI de la route
- `$action` - Action

**Retourne:** Objet `Route`

**Exemples:**

```php
// Suppression de ressource
Route::delete('/users/{id}', function($id) {
    // Supprimer utilisateur
    return "Utilisateur $id supprimé";
});

// Avec contrôleur
Route::delete('/users/{id}', [UserController::class, 'destroy'])
    ->where('id', '[0-9]+');
```

**Utilisation:**
- Suppression de ressources
- Suppression de données
- Opérations de nettoyage

---

### 6. Route VIEW

**Méthode:** `Route::view(string $uri, mixed $action): Route`

**Description:** Enregistre une route pour la méthode HTTP VIEW personnalisée.

**Paramètres:**
- `$uri` - URI de la route
- `$action` - Action

**Retourne:** Objet `Route`

**Exemples:**

```php
// Méthode VIEW personnalisée
Route::view('/page', function() {
    return 'Contenu de la page';
});

// Avec contrôleur
Route::view('/page', [PageController::class, 'show']);
```

**Utilisation:**
- Méthodes HTTP personnalisées
- Opérations spécialisées
- Points de terminaison non standard

---

### 7. Route Custom

**Méthode:** `Route::custom(string $method, string $uri, mixed $action): Route`

**Description:** Enregistre une route pour toute méthode HTTP personnalisée.

**Paramètres:**
- `$method` - Nom de la méthode HTTP
- `$uri` - URI de la route
- `$action` - Action

**Retourne:** Objet `Route`

**Exemples:**

```php
// Méthode PURGE personnalisée
Route::custom('PURGE', '/cache', function() {
    // Vider le cache
    return 'Cache vidé';
});

// Méthode OPTIONS personnalisée
Route::custom('OPTIONS', '/api', function() {
    return 'CORS preflight';
});
```

**Utilisation:**
- Méthodes HTTP personnalisées
- Protocoles spécialisés
- Opérations non standard

---

### 8. Route Match

**Méthode:** `Route::match(array $methods, string $uri, mixed $action): Route`

**Description:** Enregistre une route pour plusieurs méthodes HTTP.

**Paramètres:**
- `$methods` - Array des méthodes HTTP
- `$uri` - URI de la route
- `$action` - Action

**Retourne:** Objet `Route`

**Exemples:**

```php
// Plusieurs méthodes
Route::match(['GET', 'POST'], '/form', function() {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        return 'Afficher formulaire';
    }
    return 'Traiter formulaire';
});

// Avec contrôleur
Route::match(['PUT', 'PATCH'], '/users/{id}', [UserController::class, 'update']);
```

**Utilisation:**
- Gestion de plusieurs méthodes
- Traitement de formulaires
- Points de terminaison flexibles

---

### 9. Route Any

**Méthode:** `Route::any(string $uri, mixed $action): Route`

**Description:** Enregistre une route pour toutes les méthodes HTTP.

**Paramètres:**
- `$uri` - URI de la route
- `$action` - Action

**Retourne:** Objet `Route`

**Exemples:**

```php
// Toutes les méthodes
Route::any('/endpoint', function() {
    $method = $_SERVER['REQUEST_METHOD'];
    return "Traitement requête $method";
});

// Avec contrôleur
Route::any('/api/endpoint', [ApiController::class, 'handle']);
```

**Utilisation:**
- Points de terminaison universels
- Gestion agnostique des méthodes
- APIs flexibles

---

### 10. Instance Router

**Méthode:** `Router::getInstance(): Router`

**Description:** Obtient l'instance singleton du routeur.

**Retourne:** Instance `Router`

**Exemples:**

```php
use CloudCastle\Http\Router\Router;

$router = Router::getInstance();
$router->get('/users', $action);
$router->post('/users', $action);
```

**Utilisation:**
- Accès direct au routeur
- Pattern singleton
- Contrôle programmatique

---

### 11. API Facade

**Description:** Interface statique pour l'enregistrement de routes.

**Exemples:**

```php
use CloudCastle\Http\Router\Facade\Route;

Route::get('/users', $action);
Route::post('/users', $action);
Route::put('/users/{id}', $action);
Route::patch('/users/{id}', $action);
Route::delete('/users/{id}', $action);
```

**Utilisation:**
- Syntaxe propre
- Accès statique
- Chaînage de méthodes

---

### 12. Enregistrement de Routes

**Description:** Enregistrement de routes dans l'application.

**Exemples:**

```php
// Dans routes/web.php
Route::get('/', function() {
    return 'Bienvenue';
});

Route::get('/about', function() {
    return 'Page À propos';
});

Route::get('/contact', function() {
    return 'Page Contact';
});
```

**Utilisation:**
- Configuration d'application
- Définitions de routes
- Configuration

---

### 13. Dispatch de Routes

**Description:** Dispatch des requêtes vers les routes enregistrées.

**Exemples:**

```php
use CloudCastle\Http\Router\Facade\Route;

// Enregistrer routes
Route::get('/users', $action);

// Dispatcher requête
$route = Route::dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
if ($route) {
    echo $route->run();
}
```

**Utilisation:**
- Gestion des requêtes
- Correspondance de routes
- Génération de réponses

---

## Meilleures Pratiques

### 1. Organisation des Routes

```php
// Grouper les routes liées
Route::group(['prefix' => 'api/v1'], function() {
    Route::get('/users', [UserController::class, 'index']);
    Route::post('/users', [UserController::class, 'store']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);
});
```

### 2. Chaînage de Méthodes

```php
Route::get('/api/users', [UserController::class, 'index'])
    ->name('api.users.index')
    ->middleware([AuthMiddleware::class])
    ->throttle(100, 1)
    ->tag('api');
```

### 3. Validation des Paramètres

```php
Route::get('/users/{id}', [UserController::class, 'show'])
    ->where('id', '[0-9]+');
```

### 4. Considérations de Sécurité

```php
Route::post('/users', [UserController::class, 'store'])
    ->middleware([ValidateUser::class])
    ->throttle(20, 1)
    ->whitelistIp(['192.168.1.0/24']);
```

---

## Modèles Courants

### 1. Routes RESTful

```php
Route::get('/users', [UserController::class, 'index']);      // Liste
Route::post('/users', [UserController::class, 'store']);   // Créer
Route::get('/users/{id}', [UserController::class, 'show']); // Afficher
Route::put('/users/{id}', [UserController::class, 'update']); // Mettre à jour
Route::delete('/users/{id}', [UserController::class, 'destroy']); // Supprimer
```

### 2. Routes API

```php
Route::group(['prefix' => 'api/v1', 'middleware' => 'auth'], function() {
    Route::apiResource('users', UserController::class);
    Route::apiResource('posts', PostController::class);
});
```

### 3. Routes Web

```php
Route::group(['middleware' => 'web'], function() {
    Route::get('/', [HomeController::class, 'index']);
    Route::get('/about', [PageController::class, 'about']);
    Route::get('/contact', [PageController::class, 'contact']);
});
```

---

## Conseils de Performance

### 1. Cache des Routes

```php
$router = Router::getInstance();
$router->enableCache('cache/routes.php');
$router->compile();
```

### 2. Correspondance Efficace

```php
// Routes plus spécifiques en premier
Route::get('/users/{id}/posts/{post}', $action);
Route::get('/users/{id}', $action);
Route::get('/users', $action);
```

### 3. Contraintes de Paramètres

```php
Route::get('/users/{id}', $action)->where('id', '[0-9]+');
```

---

## Dépannage

### Problèmes Courants

1. **Route non trouvée**
   - Vérifier le pattern URI
   - Vérifier la méthode HTTP
   - Vérifier l'ordre d'enregistrement des routes

2. **Paramètre non passé**
   - Vérifier le nom du paramètre dans l'URI
   - Vérifier les contraintes de paramètres
   - S'assurer de la signature d'action correcte

3. **Problèmes de chaînage de méthodes**
   - Vérifier le type de retour
   - Vérifier la disponibilité de la méthode
   - Vérifier l'ordre des méthodes

### Conseils de Debug

```php
// Activer le mode debug
Route::enableDebug();

// Obtenir toutes les routes enregistrées
$routes = Route::getAllRoutes();

// Vérifier la correspondance de route
$route = Route::match('/users/123', 'GET');
```

---

## Voir Aussi

- [Paramètres de Route](02_ROUTE_PARAMETERS.md) - Paramètres de route dynamiques
- [Groupes de Routes](03_ROUTE_GROUPS.md) - Organisation des routes
- [Middleware](06_MIDDLEWARE.md) - Traitement des requêtes
- [Routes Nommées](07_NAMED_ROUTES.md) - Identification des routes
- [Référence API](../API_REFERENCE.md) - Référence API complète

---

© 2024 CloudCastle HTTP Router  
[⬆ Retour en haut](#routage-de-base)