# Liste Complète des Fonctionnalités CloudCastle HTTP Router

[English](../en/ALL_FEATURES.md) | [Русский](../ru/ALL_FEATURES.md) | [Deutsch](../de/ALL_FEATURES.md) | [**Français**](ALL_FEATURES.md) | [中文](../zh/ALL_FEATURES.md)

---

## 📚 Navigation Documentation

[README](../../README.md) | [USER_GUIDE](USER_GUIDE.md) | [FEATURES_INDEX](FEATURES_INDEX.md) | [API_REFERENCE](API_REFERENCE.md) | [ALL_FEATURES](ALL_FEATURES.md) | [TESTS_SUMMARY](TESTS_SUMMARY.md) | [PERFORMANCE](PERFORMANCE_ANALYSIS.md) | [SECURITY](SECURITY_REPORT.md) | [COMPARISON](COMPARISON.md) | [FAQ](FAQ.md) | [DOC_SUMMARY](DOCUMENTATION_SUMMARY.md)

**Documentation Détaillée:** [Features](features/) (22 fichiers) | [Tests](tests/) (7 rapports)

---

## Contenu

- [1. Routage de Base](#1-routage-de-base)
- [2. Fonctions Helper](#2-fonctions-helper)
- [3. Raccourcis de Route](#3-raccourcis-de-route)
- [4. Macros de Route](#4-macros-de-route)
- [5. Groupes de Routes](#5-groupes-de-routes)
- [6. Middleware](#6-middleware)
- [7. Limitation de Débit](#7-limitation-de-débit)
- [8. Filtrage IP](#8-filtrage-ip)
- [9. Système Auto-Ban](#9-système-auto-ban)
- [10. Routes Nommées](#10-routes-nommées)
- [11. Tags](#11-tags)
- [12. Paramètres de Route](#12-paramètres-de-route)
- [13. Langage d'Expression](#13-langage-dexpression)
- [14. Génération d'URL](#14-génération-durl)
- [15. Mise en Cache](#15-mise-en-cache)
- [16. Plugins](#16-plugins)
- [17. Chargeurs](#17-chargeurs)
- [18. Support PSR](#18-support-psr)
- [19. Résolveur d'Action](#19-résolveur-daction)
- [20. Statistiques et Filtrage](#20-statistiques-et-filtrage)

---

## 1. Routage de Base

### Méthodes HTTP

```php
use CloudCastle\Http\Router\Router;

$router = new Router();

// Toutes les méthodes standard
$router->get('/users', $action);
$router->post('/users', $action);
$router->put('/users/{id}', $action);
$router->patch('/users/{id}', $action);
$router->delete('/users/{id}', $action);

// Méthodes personnalisées
$router->view('/page', $action);  // Méthode VIEW
$router->custom('PURGE', '/cache', $action);  // Toute méthode

// Méthodes multiples
$router->match(['GET', 'POST'], '/form', $action);
$router->any('/endpoint', $action);  // Toutes les méthodes
```

### API Facade

```php
use CloudCastle\Http\Router\Facade\Route;

Route::get('/api/users', $action);
Route::post('/api/users', $action);
// Et ainsi de suite...
```

---

## 2. Fonctions Helper

### route()

Obtenir une route par nom ou la route actuelle :

```php
// Obtenir une route par nom
$route = route('users.show');

// Obtenir la route actuelle
$current = route();
```

### current_route()

Obtenir la route actuelle :

```php
$currentRoute = current_route();
echo $currentRoute->getName();
```

### previous_route()

Obtenir la route précédente :

```php
$prevRoute = previous_route();
```

### route_is()

Vérifier le nom de la route actuelle :

```php
if (route_is('users.index')) {
    // La route actuelle est users.index
}
```

### route_name()

Obtenir le nom de la route actuelle :

```php
$name = route_name(); // 'users.show'
```

### router()

Obtenir l'instance du routeur :

```php
$router = router();
$stats = $router->getRouteStats();
```

### dispatch_route()

Dispatcher la requête HTTP actuelle :

```php
$route = dispatch_route();
if ($route) {
    echo $route->run();
}
```

---

## 3. Raccourcis de Route

### resource()

Créer des routes de ressources RESTful :

```php
Route::resource('users', UserController::class);
// Crée : routes GET, POST, PUT, PATCH, DELETE
```

### apiResource()

Créer des routes de ressources API :

```php
Route::apiResource('users', ApiUserController::class);
// Crée : routes GET, POST, PUT, PATCH, DELETE (pas de routes de vue)
```

### crud()

Créer des opérations CRUD :

```php
Route::crud('products', ProductController::class);
// Crée : index, show, store, update, destroy
```

### auth()

Créer des routes d'authentification :

```php
Route::auth();
// Crée : routes login, register, logout, password reset
```

### adminPanel()

Créer des routes de panneau d'administration :

```php
Route::adminPanel();
// Crée : routes dashboard, users, settings
```

### apiVersion()

Créer des routes de versioning API :

```php
Route::apiVersion('v1', function() {
    Route::get('/users', $action);
});
```

### webhooks()

Créer des routes de webhook :

```php
Route::webhooks('stripe', StripeWebhookController::class);
```

---

## 4. Macros de Route

### Macros Personnalisées

```php
use CloudCastle\Http\Router\Macro\MacroManager;

MacroManager::macro('admin', function($prefix, $controller) {
    Route::group(['prefix' => $prefix, 'middleware' => 'admin'], function() use ($controller) {
        Route::get('/', [$controller, 'index']);
        Route::get('/create', [$controller, 'create']);
        Route::post('/', [$controller, 'store']);
        Route::get('/{id}', [$controller, 'show']);
        Route::get('/{id}/edit', [$controller, 'edit']);
        Route::put('/{id}', [$controller, 'update']);
        Route::delete('/{id}', [$controller, 'destroy']);
    });
});

// Utilisation
Route::admin('users', UserController::class);
```

---

## 5. Groupes de Routes

### Groupes de Base

```php
Route::group(['prefix' => 'api/v1'], function() {
    Route::get('/users', $action);
    Route::get('/posts', $action);
});
```

### Groupes Avancés

```php
Route::group([
    'prefix' => 'admin',
    'middleware' => ['auth', 'admin'],
    'domain' => 'admin.example.com',
    'namespace' => 'Admin',
    'as' => 'admin.',
    'where' => ['id' => '[0-9]+']
], function() {
    Route::get('/dashboard', 'DashboardController@index');
    Route::resource('users', 'UserController');
});
```

### Groupes Imbriqués

```php
Route::group(['prefix' => 'api'], function() {
    Route::group(['prefix' => 'v1'], function() {
        Route::get('/users', $action);
    });
    
    Route::group(['prefix' => 'v2'], function() {
        Route::get('/users', $action);
    });
});
```

---

## 6. Middleware

### Middleware Global

```php
$router->middleware([
    CorsMiddleware::class,
    SecurityMiddleware::class
]);
```

### Middleware de Route

```php
Route::get('/admin', $action)->middleware('auth');
Route::post('/api', $action)->middleware(['auth', 'throttle']);
```

### Middleware de Groupe

```php
Route::group(['middleware' => 'auth'], function() {
    Route::get('/profile', $action);
    Route::get('/settings', $action);
});
```

### Middleware Intégrés

```php
// Authentification
Route::get('/protected', $action)->middleware(AuthMiddleware::class);

// CORS
Route::get('/api', $action)->middleware(CorsMiddleware::class);

// Application HTTPS
Route::get('/secure', $action)->middleware(HttpsEnforcement::class);

// Journalisation de sécurité
Route::get('/sensitive', $action)->middleware(SecurityLogger::class);

// Protection SSRF
Route::get('/proxy', $action)->middleware(SsrfProtection::class);
```

---

## 7. Limitation de Débit

### Limitation de Débit de Base

```php
Route::get('/api', $action)->throttle(60, 1); // 60 requêtes par minute
Route::post('/login', $action)->throttle(5, 1); // 5 requêtes par minute
```

### Unités de Temps

```php
use CloudCastle\Http\Router\RateLimiting\TimeUnit;

Route::get('/api', $action)->throttle(100, TimeUnit::HOUR);
Route::post('/upload', $action)->throttle(10, TimeUnit::DAY);
```

### Clés Personnalisées

```php
Route::get('/api', $action)->throttle(60, 1, 'user:' . $userId);
Route::post('/api', $action)->throttle(100, 1, 'api_key:' . $apiKey);
```

### Classe Rate Limiter

```php
use CloudCastle\Http\Router\RateLimiting\RateLimiter;

$limiter = new RateLimiter(60, TimeUnit::MINUTE);
$limiter->setKey('user:' . $userId);
$limiter->check();
```

### Limites Prédefinies

```php
Route::get('/api', $action)->throttleStandard(); // 60 req/min
Route::post('/api', $action)->throttleStrict();   // 10 req/min
Route::get('/api', $action)->throttleGenerous(); // 1000 req/min
```

---

## 8. Filtrage IP

### Liste Blanche

```php
Route::get('/admin', $action)->whitelistIp(['192.168.1.0/24', '10.0.0.1']);
Route::group(['whitelistIp' => ['192.168.1.0/24']], function() {
    Route::get('/admin', $action);
});
```

### Liste Noire

```php
Route::get('/api', $action)->blacklistIp(['192.168.1.100', '10.0.0.50']);
Route::group(['blacklistIp' => ['192.168.1.100']], function() {
    Route::get('/api', $action);
});
```

### Support CIDR

```php
Route::get('/admin', $action)->whitelistIp([
    '192.168.1.0/24',    // 192.168.1.1-254
    '10.0.0.0/8',        // 10.0.0.0-10.255.255.255
    '172.16.0.0/12'      // 172.16.0.0-172.31.255.255
]);
```

### Protection IP Spoofing

```php
Route::get('/api', $action)->enableIpSpoofingProtection();
```

---

## 9. Système Auto-Ban

### Auto-Ban de Base

```php
use CloudCastle\Http\Router\RateLimiting\BanManager;

$banManager = new BanManager(5, 3600); // Ban après 5 violations pour 1 heure

Route::post('/login', $action)
    ->throttle(3, 1)
    ->getRateLimiter()?->setBanManager($banManager);
```

### Gestion des Bans

```php
$banManager = new BanManager();

// Bannir IP manuellement
$banManager->ban('192.168.1.100', 3600);

// Débannir IP
$banManager->unban('192.168.1.100');

// Vérifier si IP est bannie
if ($banManager->isBanned('192.168.1.100')) {
    throw new BannedException();
}

// Obtenir toutes les IPs bannies
$bannedIps = $banManager->getBannedIps();

// Effacer tous les bans
$banManager->clearAll();
```

### Configuration Auto-Ban

```php
$banManager = new BanManager(
    $violationThreshold = 5,    // Ban après 5 violations
    $banDuration = 3600,        // Ban pendant 1 heure
    $gracePeriod = 300          // Période de grâce de 5 minutes
);
```

---

## 10. Routes Nommées

### Nommage de Base

```php
Route::get('/users/{id}', $action)->name('users.show');
Route::get('/users', $action)->name('users.index');
```

### Nommage de Groupe

```php
Route::group(['as' => 'admin.'], function() {
    Route::get('/dashboard', $action)->name('dashboard');
    // Crée le nom de route : admin.dashboard
});
```

### Helpers de Nom de Route

```php
// Obtenir une route par nom
$route = Route::getRouteByName('users.show');

// Obtenir le nom de la route actuelle
$name = Route::currentRouteName();

// Vérifier si la route actuelle correspond au modèle
if (Route::currentRouteNamed('users.*')) {
    // La route actuelle commence par 'users.'
}

// Obtenir toutes les routes nommées
$namedRoutes = Route::getNamedRoutes();
```

### Nommage Automatique

```php
Route::enableAutoNaming();

Route::get('/users', $action); // Auto-nommé : users.index
Route::get('/users/{id}', $action); // Auto-nommé : users.show
Route::post('/users', $action); // Auto-nommé : users.store
```

---

## 11. Tags

### Tags de Base

```php
Route::get('/api/users', $action)->tag('api');
Route::get('/api/posts', $action)->tag('api');
Route::get('/web/about', $action)->tag('web');
```

### Tags Multiples

```php
Route::get('/api/users', $action)->tag(['api', 'public']);
Route::get('/api/admin', $action)->tag(['api', 'admin']);
```

### Tags de Groupe

```php
Route::group(['tag' => 'api'], function() {
    Route::get('/users', $action);
    Route::get('/posts', $action);
});
```

### Opérations de Tags

```php
// Obtenir des routes par tag
$apiRoutes = Route::getRoutesByTag('api');

// Vérifier si la route a un tag
if ($route->hasTag('api')) {
    // La route a le tag 'api'
}

// Obtenir tous les tags
$allTags = Route::getAllTags();
```

---

## 12. Paramètres de Route

### Paramètres de Base

```php
Route::get('/users/{id}', $action);
Route::get('/posts/{slug}', $action);
Route::get('/categories/{category}/posts/{post}', $action);
```

### Paramètres Optionnels

```php
Route::get('/users/{id?}', $action);
Route::get('/posts/{slug?}', $action);
```

### Contraintes de Paramètres

```php
Route::get('/users/{id}', $action)->where('id', '[0-9]+');
Route::get('/posts/{slug}', $action)->where('slug', '[a-z0-9-]+');
Route::get('/users/{id}/posts/{post}', $action)
    ->where(['id' => '[0-9]+', 'post' => '[0-9]+']);
```

### Contraintes Inline

```php
Route::get('/users/{id:[0-9]+}', $action);
Route::get('/posts/{slug:[a-z0-9-]+}', $action);
```

### Valeurs par Défaut

```php
Route::get('/users/{id}', $action)->defaults(['id' => 1]);
Route::get('/posts/{page?}', $action)->defaults(['page' => 1]);
```

### Accès aux Paramètres

```php
$route = Route::get('/users/{id}', function($id) {
    return "User ID: $id";
});

// Obtenir les paramètres
$params = $route->getParameters();
$id = $route->getParameter('id');
```

---

## 13. Langage d'Expression

### Expressions de Base

```php
Route::get('/users/{id}', $action)
    ->where('id', 'expr: id > 0 and id < 1000');
```

### Expressions Complexes

```php
Route::get('/posts/{year}/{month}', $action)
    ->where('year', 'expr: year >= 2020 and year <= 2030')
    ->where('month', 'expr: month >= 1 and month <= 12');
```

### Fonctions d'Expression

```php
Route::get('/files/{filename}', $action)
    ->where('filename', 'expr: strlen(filename) > 0 and strlen(filename) < 255');
```

---

## 14. Génération d'URL

### Génération d'URL de Base

```php
// Générer une URL pour une route nommée
$url = route('users.show', ['id' => 1]);
// Résultat : /users/1

// Générer une URL avec des paramètres de requête
$url = route('users.index', [], ['page' => 2, 'sort' => 'name']);
// Résultat : /users?page=2&sort=name
```

### Helpers d'URL

```php
// Obtenir l'URL actuelle
$currentUrl = url()->current();

// Obtenir l'URL complète
$fullUrl = url()->full();

// Obtenir l'URL précédente
$previousUrl = url()->previous();

// Générer une URL sécurisée
$secureUrl = url()->secure('users/1');
```

### Génération d'URL de Route

```php
$route = Route::get('/users/{id}', $action)->name('users.show');

// Générer une URL
$url = $route->url(['id' => 1]);
$url = $route->url(['id' => 1], ['absolute' => true]);
```

---

## 15. Mise en Cache

### Cache de Routes

```php
$router->enableCache('cache/routes.php');

// Compiler les routes vers le cache
$router->compile();

// Charger depuis le cache
if (!$router->loadFromCache()) {
    require 'routes/web.php';
    $router->compile();
}
```

### Cache de Réponse

```php
Route::get('/api/users', $action)->cache(3600); // Cache pendant 1 heure
Route::get('/api/posts', $action)->cache(7200, ['tag' => 'posts']); // Cache avec tags
```

### Tags de Cache

```php
Route::get('/api/users', $action)->cache(3600, ['tag' => 'users']);
Route::get('/api/posts', $action)->cache(3600, ['tag' => 'posts']);

// Effacer le cache par tag
Cache::clearByTag('users');
```

---

## 16. Plugins

### Plugins Intégrés

```php
use CloudCastle\Http\Router\Plugin\LoggerPlugin;
use CloudCastle\Http\Router\Plugin\AnalyticsPlugin;
use CloudCastle\Http\Router\Plugin\ResponseCachePlugin;

$router->addPlugin(new LoggerPlugin());
$router->addPlugin(new AnalyticsPlugin());
$router->addPlugin(new ResponseCachePlugin());
```

### Plugins Personnalisés

```php
use CloudCastle\Http\Router\Plugin\PluginInterface;

class CustomPlugin implements PluginInterface
{
    public function beforeDispatch($request, $response)
    {
        // Exécuter avant le dispatch de route
    }
    
    public function afterDispatch($request, $response, $route)
    {
        // Exécuter après le dispatch de route
    }
}

$router->addPlugin(new CustomPlugin());
```

---

## 17. Chargeurs

### Chargeurs de Routes

```php
use CloudCastle\Http\Router\Loader\FileLoader;
use CloudCastle\Http\Router\Loader\DatabaseLoader;

// Charger les routes depuis un fichier
$loader = new FileLoader('routes/web.php');
$loader->load($router);

// Charger les routes depuis une base de données
$loader = new DatabaseLoader($connection);
$loader->load($router);
```

### Chargeurs Personnalisés

```php
use CloudCastle\Http\Router\Loader\LoaderInterface;

class CustomLoader implements LoaderInterface
{
    public function load(Router $router)
    {
        // Charger les routes depuis une source personnalisée
    }
}
```

---

## 18. Support PSR

### Request/Response PSR-7

```php
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

Route::get('/api/users', function(ServerRequestInterface $request): ResponseInterface {
    $response = new Response();
    $response->getBody()->write(json_encode(['users' => []]));
    return $response->withHeader('Content-Type', 'application/json');
});
```

### Middleware PSR-15

```php
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class CustomMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // Traiter la requête
        return $handler->handle($request);
    }
}

Route::get('/api', $action)->middleware(CustomMiddleware::class);
```

### Container PSR-11

```php
use Psr\Container\ContainerInterface;

Route::get('/api/users', function(ContainerInterface $container) {
    $userService = $container->get(UserService::class);
    return $userService->getAll();
});
```

---

## 19. Résolveur d'Action

### Actions de Contrôleur

```php
Route::get('/users', 'UserController@index');
Route::get('/users/{id}', [UserController::class, 'show']);
Route::get('/users', UserController::class . '@index');
```

### Actions de Closure

```php
Route::get('/users', function() {
    return 'Users list';
});

Route::get('/users/{id}', function($id) {
    return "User ID: $id";
});
```

### Actions de Classe

```php
class UserAction
{
    public function __invoke($id)
    {
        return "User ID: $id";
    }
}

Route::get('/users/{id}', UserAction::class);
```

### Injection de Dépendance

```php
Route::get('/users', function(UserService $userService) {
    return $userService->getAll();
});

Route::get('/users/{id}', [UserController::class, 'show']);
```

---

## 20. Statistiques et Filtrage

### Statistiques de Routes

```php
$stats = $router->getRouteStats();

echo "Total des routes : " . $stats->getTotalRoutes();
echo "Routes nommées : " . $stats->getNamedRoutes();
echo "Routes groupées : " . $stats->getGroupedRoutes();
echo "Routes middleware : " . $stats->getMiddlewareRoutes();
```

### Filtrage de Routes

```php
// Filtrer par méthode
$getRoutes = $router->getRoutesByMethod('GET');

// Filtrer par modèle
$apiRoutes = $router->getRoutesByPattern('/api/*');

// Filtrer par middleware
$authRoutes = $router->getRoutesByMiddleware('auth');

// Filtrer par tag
$publicRoutes = $router->getRoutesByTag('public');
```

### Statistiques de Performance

```php
$perfStats = $router->getPerformanceStats();

echo "Temps de dispatch moyen : " . $perfStats->getAverageDispatchTime();
echo "Utilisation mémoire : " . $perfStats->getMemoryUsage();
echo "Taux de réussite du cache : " . $perfStats->getCacheHitRate();
```

---

## Résumé

CloudCastle HTTP Router fournit **209+ fonctionnalités** dans 20 catégories principales :

1. **Routage de Base** - Toutes les méthodes HTTP et méthodes personnalisées
2. **Fonctions Helper** - Helpers de route pratiques
3. **Raccourcis de Route** - Collections de routes pré-construites
4. **Macros de Route** - Modèles de route personnalisés
5. **Groupes de Routes** - Collections de routes organisées
6. **Middleware** - Traitement des requêtes/réponses
7. **Limitation de Débit** - Protection DDoS et contre les abus
8. **Filtrage IP** - Contrôle d'accès par IP
9. **Système Auto-Ban** - Bannissement automatique d'IP
10. **Routes Nommées** - Identification des routes
11. **Tags** - Catégorisation des routes
12. **Paramètres de Route** - Segments d'URL dynamiques
13. **Langage d'Expression** - Validation avancée des paramètres
14. **Génération d'URL** - Création d'URL dynamique
15. **Mise en Cache** - Optimisation des performances
16. **Plugins** - Architecture extensible
17. **Chargeurs** - Stratégies de chargement des routes
18. **Support PSR** - Conformité aux standards
19. **Résolveur d'Action** - Gestion flexible des actions
20. **Statistiques** - Analyse et filtrage des routes

Cet ensemble complet de fonctionnalités fait de CloudCastle HTTP Router la solution de routage la plus complète pour les applications PHP.

---

## 📚 Voir Aussi
- [USER_GUIDE.md](USER_GUIDE.md) - Guide utilisateur complet
- [FEATURES_INDEX.md](FEATURES_INDEX.md) - Catégories de fonctionnalités
- [API_REFERENCE.md](API_REFERENCE.md) - Référence API
- [FAQ.md](FAQ.md) - Questions fréquemment posées

---

© 2024 CloudCastle HTTP Router  
[⬆ Retour en haut](#liste-complète-des-fonctionnalités-cloudcastle-http-router)