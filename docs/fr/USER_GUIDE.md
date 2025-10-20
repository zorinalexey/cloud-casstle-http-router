# CloudCastle HTTP Router - Guide Utilisateur Complet

[English](../en/USER_GUIDE.md) | [Русский](../ru/USER_GUIDE.md) | [Deutsch](../de/USER_GUIDE.md) | [**Français**](USER_GUIDE.md) | [中文](../zh/USER_GUIDE.md)

---

## 📚 Navigation Documentation

[README](../../README.md) | [USER_GUIDE](USER_GUIDE.md) | [FEATURES_INDEX](FEATURES_INDEX.md) | [API_REFERENCE](API_REFERENCE.md) | [ALL_FEATURES](ALL_FEATURES.md) | [TESTS_SUMMARY](TESTS_SUMMARY.md) | [PERFORMANCE](PERFORMANCE_ANALYSIS.md) | [SECURITY](SECURITY_REPORT.md) | [COMPARISON](COMPARISON.md) | [FAQ](FAQ.md) | [DOC_SUMMARY](DOCUMENTATION_SUMMARY.md)

**Documentation détaillée:** [Features](features/) (22 fichiers) | [Tests](tests/) (7 rapports)

---

**Version:** 1.1.1  
**Date:** Octobre 2025  
**Fonctionnalités:** 209+

---

## 📑 Table des Matières

1. [Introduction](#introduction)
2. [Installation et Configuration](#installation-et-configuration)
3. [Routage de Base (13 méthodes)](#routage-de-base)
4. [Paramètres Routes (6 façons)](#paramètres-routes)
5. [Groupes Routes (12 attributs)](#groupes-routes)
6. [Rate Limiting (8 méthodes)](#rate-limiting)
7. [Système Auto-Ban (7 méthodes)](#système-auto-ban)
8. [Filtrage IP (4 méthodes)](#filtrage-ip)
9. [Middleware (6 types)](#middleware)
10. [Routes Nommées (6 méthodes)](#routes-nommées)
11. [Tags (5 méthodes)](#tags)
12. [Fonctions Utilitaires (18 fonctions)](#fonctions-utilitaires)
13. [Raccourcis Routes (14 méthodes)](#raccourcis-routes)
14. [Macros Routes (7 macros)](#macros-routes)
15. [Génération URL (11 méthodes)](#génération-url)
16. [Expression Language (5 opérateurs)](#expression-language)
17. [Mise en Cache Routes (6 méthodes)](#mise-en-cache-routes)
18. [Système Plugin (13 méthodes)](#système-plugin)
19. [Chargeurs Routes (5 types)](#chargeurs-routes)
20. [Support PSR (3 standards)](#support-psr)
21. [Action Resolver (6 formats)](#action-resolver)
22. [Statistiques et Requêtes (24 méthodes)](#statistiques-et-requêtes)
23. [Sécurité (12 mécanismes)](#sécurité)
24. [Exceptions (8 types)](#exceptions)
25. [Outils CLI (3 commandes)](#outils-cli)
26. [Exemples Avancés](#exemples-avancés)

---

## Introduction

CloudCastle HTTP Router est une bibliothèque de routage **haute performance** (54k+ req/sec), **sécurisée** (OWASP Top 10) et **riche en fonctionnalités** (209+ capacités) pour PHP 8.2+.

### Caractéristiques Principales

- ⚡ **Performance:** 54,891 requêtes/sec
- 🔒 **Sécurité:** 12+ mécanismes protection intégrés
- 💎 **Fonctionnalité:** 209+ méthodes et fonctionnalités
- 💾 **Efficacité:** 1.32 KB par route
- 📊 **Évolutivité:** 1,160,000+ routes
- ✅ **Fiabilité:** 501 tests, 0 erreurs

---

## Installation et Configuration

### Prérequis

- PHP 8.2 ou supérieur
- Composer
- PSR-7/PSR-15 (optionnel)

### Installation via Composer

```bash
composer require cloud-castle/http-router
```

### Démarrage Rapide

```php
<?php

require __DIR__ . '/vendor/autoload.php';

use CloudCastle\Http\Router\Facade\Route;

// Enregistrer routes
Route::get('/users', fn() => 'List of users');
Route::post('/users', fn() => 'Create user');

// Dispatch
$route = Route::dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
echo $route->run();
```

---

## Routage de Base

### 1. Route GET

```php
use CloudCastle\Http\Router\Facade\Route;

Route::get('/users', function() {
    return 'List of users';
});
```

### 2. Route POST

```php
Route::post('/users', function() {
    return 'Create user';
});
```

### 3. Route PUT

```php
Route::put('/users/{id}', function($id) {
    return "Update user: $id";
});
```

### 4. Route PATCH

```php
Route::patch('/users/{id}', function($id) {
    return "Partial update user: $id";
});
```

### 5. Route DELETE

```php
Route::delete('/users/{id}', function($id) {
    return "Delete user: $id";
});
```

### 6. Route VIEW (personnalisée)

```php
Route::view('/preview', function() {
    return 'Preview content';
});
```

### 7. Méthode HTTP Personnalisée

```php
Route::custom('PURGE', '/cache', function() {
    return 'Cache purged';
});

Route::custom('TRACE', '/debug', function() {
    return 'Debug trace';
});
```

### 8. Plusieurs Méthodes HTTP

```php
Route::match(['GET', 'POST'], '/form', function() {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        return 'Show form';
    }
    return 'Process form';
});
```

### 9. Toutes Méthodes HTTP

```php
Route::any('/webhook', function() {
    return 'Handle any method';
});
```

### 10. Utilisation Instance Router

```php
use CloudCastle\Http\Router\Router;

$router = new Router();

$router->get('/users', fn() => 'Users');
$router->post('/users', fn() => 'Create');

$route = $router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
```

### 11-13. Méthodes Router Statiques

```php
use CloudCastle\Http\Router\Router;

// Pattern Singleton
Router::staticGet('/users', fn() => 'Users');
Router::staticPost('/users', fn() => 'Create');
Router::staticDelete('/users/{id}', fn($id) => "Delete: $id");
```

---

## Paramètres Routes

### 1. Paramètres de Base

```php
Route::get('/users/{id}', function($id) {
    return "User ID: $id";
});

Route::get('/posts/{slug}', function($slug) {
    return "Post: $slug";
});

// Paramètres multiples
Route::get('/users/{userId}/posts/{postId}', function($userId, $postId) {
    return "User $userId, Post $postId";
});
```

### 2. Contraintes Paramètres (where)

```php
// Seulement chiffres
Route::get('/users/{id}', $action)
    ->where('id', '[0-9]+');

// Lettres et tirets
Route::get('/posts/{slug}', $action)
    ->where('slug', '[a-z0-9-]+');

// Contraintes multiples
Route::get('/api/{version}/users/{id}', $action)
    ->where([
        'version' => 'v[0-9]+',
        'id' => '[0-9]+'
    ]);
```

### 3. Paramètres Inline

```php
// Motif dans URI lui-même
Route::get('/users/{id:[0-9]+}', $action);
Route::get('/posts/{slug:[a-z0-9-]+}', $action);
Route::get('/files/{path:.+}', $action);
```

### 4. Paramètres Optionnels

```php
Route::get('/blog/{category?}', function($category = 'all') {
    return "Category: $category";
});

Route::get('/search/{query?}/{page?}', function($query = '', $page = 1) {
    return "Search: $query, Page: $page";
});
```

### 5. Valeurs par Défaut

```php
Route::get('/posts/{page}', $action)
    ->defaults(['page' => 1]);

Route::get('/search/{query}/{limit}', $action)
    ->defaults([
        'query' => '',
        'limit' => 10
    ]);
```

### 6. Obtenir Paramètres

```php
Route::get('/users/{id}', function($id) {
    $route = Route::current();
    $params = $route->getParameters();
    // ['id' => '123']
    
    return "User: $id";
});
```

---

## Groupes Routes

### 1. Groupe avec Préfixe

```php
Route::group(['prefix' => '/api'], function() {
    Route::get('/users', $action);     // /api/users
    Route::get('/posts', $action);     // /api/posts
});
```

### 2. Groupe avec Middleware

```php
Route::group(['middleware' => [AuthMiddleware::class]], function() {
    Route::get('/dashboard', $action);
    Route::get('/profile', $action);
});
```

### 3. Groupe avec Domaine

```php
Route::group(['domain' => 'api.example.com'], function() {
    Route::get('/users', $action);
    Route::get('/posts', $action);
});
```

### 4. Groupe avec Port

```php
Route::group(['port' => 8080], function() {
    Route::get('/admin', $action);
});
```

### 5. Groupe avec Namespace

```php
Route::group(['namespace' => 'App\\Controllers\\Api'], function() {
    Route::get('/users', 'UserController@index');
    Route::get('/posts', 'PostController@index');
});
```

### 6. Groupe avec Exigence HTTPS

```php
Route::group(['https' => true], function() {
    Route::get('/secure', $action);
    Route::post('/payment', $action);
});
```

### 7. Groupe avec Protocoles

```php
Route::group(['protocols' => ['ws', 'wss']], function() {
    Route::get('/chat', $action);
    Route::get('/notifications', $action);
});
```

### 8. Groupe avec Tags

```php
Route::group(['tags' => ['api', 'public']], function() {
    Route::get('/users', $action);
    Route::get('/posts', $action);
});
```

### 9. Groupe avec Throttle

```php
Route::group(['throttle' => [60, 1]], function() {
    Route::get('/api/data', $action);
    Route::post('/api/submit', $action);
});
```

### 10. Groupe avec IP Whitelist

```php
Route::group(['whitelistIp' => ['192.168.1.0/24']], function() {
    Route::get('/admin/users', $action);
    Route::get('/admin/settings', $action);
});
```

### 11. Groupes Imbriqués

```php
Route::group(['prefix' => '/api'], function() {
    Route::group(['prefix' => '/v1'], function() {
        Route::get('/users', $action);  // /api/v1/users
    });
    
    Route::group(['prefix' => '/v2'], function() {
        Route::get('/users', $action);  // /api/v2/users
    });
});
```

### 12. Attributs Combinés

```php
Route::group([
    'prefix' => '/admin',
    'middleware' => [AuthMiddleware::class, AdminMiddleware::class],
    'domain' => 'admin.example.com',
    'port' => 443,
    'https' => true,
    'whitelistIp' => ['192.168.1.0/24'],
    'tags' => ['admin', 'protected'],
    'throttle' => [30, 1],
    'namespace' => 'App\\Controllers\\Admin',
], function() {
    Route::get('/dashboard', 'DashboardController@index');
    Route::get('/users', 'UserController@index');
});
```

### Obtenir Objet RouteGroup

```php
$group = Route::group(['prefix' => '/api'], function() {
    Route::get('/users', $action);
    Route::get('/posts', $action);
});

// Méthodes RouteGroup
$routes = $group->getRoutes();        // Toutes routes du groupe
$count = $group->count();             // Nombre de routes
$attrs = $group->getAttributes();     // Attributs du groupe
```

---

## Rate Limiting

### 1. Throttle de Base

```php
// 60 requêtes par minute
Route::post('/api/submit', $action)
    ->throttle(60, 1);

// 100 requêtes par heure
Route::post('/api/upload', $action)
    ->throttle(100, 60);
```

### 2. TimeUnit Enum

```php
use CloudCastle\Http\Router\TimeUnit;

// 5 requêtes par seconde
Route::post('/api/fast', $action)
    ->throttle(5, TimeUnit::SECOND->value);

// 100 requêtes par minute
Route::post('/api/normal', $action)
    ->throttle(100, TimeUnit::MINUTE->value);

// 1000 requêtes par heure
Route::post('/api/slow', $action)
    ->throttle(1000, TimeUnit::HOUR->value);

// 10000 requêtes par jour
Route::post('/api/daily', $action)
    ->throttle(10000, TimeUnit::DAY->value);

// 50000 requêtes par semaine
Route::post('/api/weekly', $action)
    ->throttle(50000, TimeUnit::WEEK->value);

// 200000 requêtes par mois
Route::post('/api/monthly', $action)
    ->throttle(200000, TimeUnit::MONTH->value);
```

### 3. Clé Throttle Personnalisée

```php
Route::post('/api/user-specific', $action)
    ->throttle(30, 1, function($request) {
        // Limitation par ID utilisateur
        return 'user_' . $request->userId;
    });

Route::post('/api/ip-specific', $action)
    ->throttle(60, 1, function($request) {
        // Limitation par IP
        return $request->ip();
});
```

### 4. Obtenir RateLimiter

```php
$route = Route::post('/api/data', $action)
    ->throttle(60, 1);

$rateLimiter = $route->getRateLimiter();
```

### 5. Méthodes RateLimiter

```php
use CloudCastle\Http\Router\RateLimiter;

$limiter = new RateLimiter(60, 1);  // 60 requêtes par minute

// Vérifier si limite dépassée
if ($limiter->tooManyAttempts('user_123')) {
    $seconds = $limiter->availableIn('user_123');
    echo "Retry after $seconds seconds";
}

// Ajouter tentative
$limiter->attempt('user_123');

// Tentatives restantes
$remaining = $limiter->remaining('user_123');

// Réinitialiser compteur
$limiter->clear('user_123');

// Tout effacer
$limiter->clearAll();

// Obtenir maximum tentatives
$max = $limiter->getMaxAttempts();

// Obtenir période en minutes
$period = $limiter->getDecayMinutes();
```

### 6. Définir BanManager pour RateLimiter

```php
use CloudCastle\Http\Router\BanManager;

$banManager = new BanManager(5, 3600);  // 5 violations = ban pour 1 heure

Route::post('/login', $action)
    ->throttle(3, 1)
    ->getRateLimiter()
    ?->setBanManager($banManager);
```

### 7-8. Raccourcis Throttle

```php
// 60 requêtes par minute
Route::post('/api/standard', $action)->throttleStandard();

// 10 requêtes par minute
Route::post('/api/strict', $action)->throttleStrict();

// 1000 requêtes par minute
Route::post('/api/generous', $action)->throttleGenerous();
```

---

## Système Auto-Ban

### 1. Créer BanManager

```php
use CloudCastle\Http\Router\BanManager;

// 5 violations = ban pour 1 heure (3600 sec)
$banManager = new BanManager(
    maxViolations: 5,
    banDuration: 3600
);
```

### 2. Activer Auto-Ban

```php
$banManager->enableAutoBan(5);  // Auto-ban après 5 violations
```

### 3. Blocage IP Manuel

```php
// Bannir IP pour 1 heure
$banManager->ban('1.2.3.4', 3600);

// Bannir IP définitivement (0 secondes)
$banManager->ban('5.6.7.8', 0);
```

### 4. Débloquer IP

```php
$banManager->unban('1.2.3.4');
```

### 5. Vérifier Ban

```php
if ($banManager->isBanned('1.2.3.4')) {
    throw new \CloudCastle\Http\Router\Exceptions\BannedException(
        'Your IP is banned'
    );
}
```

### 6. Obtenir Liste IPs Bannies

```php
$bannedIps = $banManager->getBannedIps();
// ['1.2.3.4', '5.6.7.8']
```

### 7. Effacer Tous Bans

```php
$banManager->clearAll();
```

### Exemple Complet avec Auto-Ban

```php
use CloudCastle\Http\Router\BanManager;
use CloudCastle\Http\Router\Facade\Route;

$banManager = new BanManager(5, 3600);

Route::post('/login', function() {
    // Logique login
    return 'Login success';
})
->throttle(3, 1)  // 3 tentatives par minute
->getRateLimiter()
?->setBanManager($banManager);

// Après dépassement limite 5 fois - ban automatique pour 1 heure
```

---

## Filtrage IP

### 1. Whitelist IP

```php
// IP unique
Route::get('/admin', $action)
    ->whitelistIp(['192.168.1.1']);

// IPs multiples
Route::get('/admin', $action)
    ->whitelistIp([
        '192.168.1.1',
        '192.168.1.2',
        '10.0.0.1'
    ]);
```

### 2. Notation CIDR

```php
Route::get('/admin', $action)
    ->whitelistIp([
        '192.168.1.0/24',    // 192.168.1.0 - 192.168.1.255
        '10.0.0.0/8',        // 10.0.0.0 - 10.255.255.255
    ]);
```

### 3. Blacklist IP

```php
Route::get('/public', $action)
    ->blacklistIp([
        '1.2.3.4',
        '5.6.7.8'
    ]);
```

### 4. Combinaison Whitelist et Blacklist

```php
Route::get('/api/data', $action)
    ->whitelistIp(['192.168.1.0/24'])  // Autoriser réseau local
    ->blacklistIp(['192.168.1.100']);   // Sauf cette IP
```

---

## Middleware

### 1. Middleware Global

```php
use CloudCastle\Http\Router\Middleware\CorsMiddleware;

Route::middleware([CorsMiddleware::class]);
```

### 2. Middleware sur Route

```php
use CloudCastle\Http\Router\Middleware\AuthMiddleware;

Route::get('/dashboard', $action)
    ->middleware([AuthMiddleware::class]);
```

### 3. Middleware Multiples

```php
Route::get('/admin/users', $action)
    ->middleware([
        AuthMiddleware::class,
        AdminMiddleware::class,
        LoggerMiddleware::class
    ]);
```

### 4. Middleware Intégrés

```php
use CloudCastle\Http\Router\Middleware\{
    AuthMiddleware,
    CorsMiddleware,
    HttpsEnforcement,
    SecurityLogger,
    SsrfProtection
};

Route::get('/api/data', $action)
    ->middleware([
        CorsMiddleware::class,
        SecurityLogger::class
    ]);

Route::get('/secure', $action)
    ->middleware([HttpsEnforcement::class]);

Route::post('/webhook', $action)
    ->middleware([SsrfProtection::class]);
```

### 5. Créer Middleware Personnalisé

```php
use CloudCastle\Http\Router\Contracts\MiddlewareInterface;
use CloudCastle\Http\Router\Route;

class CustomMiddleware implements MiddlewareInterface
{
    public function handle(Route $route, callable $next): mixed
    {
        // Logique avant
        echo "Before route execution\n";
        
        // Exécuter route
        $response = $next($route);
        
        // Logique après
        echo "After route execution\n";
        
        return $response;
    }
}

Route::get('/test', $action)
    ->middleware([CustomMiddleware::class]);
```

### 6. MiddlewareDispatcher

```php
use CloudCastle\Http\Router\MiddlewareDispatcher;

$dispatcher = new MiddlewareDispatcher();

$dispatcher->add(AuthMiddleware::class);
$dispatcher->add(LoggerMiddleware::class);

$response = $dispatcher->dispatch($route, function($route) {
    return $route->run();
});
```

---

## Routes Nommées

### 1. Assigner Nom

```php
Route::get('/users/{id}', $action)
    ->name('users.show');

Route::post('/users', $action)
    ->name('users.store');
```

### 2. Obtenir Route par Nom

```php
$route = Route::getRouteByName('users.show');
```

### 3. Nom Route Actuelle

```php
$name = Route::currentRouteName();
// 'users.show'
```

### 4. Vérifier Nom Route Actuelle

```php
if (Route::currentRouteNamed('users.show')) {
    echo "Viewing user";
}
```

### 5. Auto-Nommage

```php
// Activer auto-nommage
Route::enableAutoNaming();

// Routes obtiennent automatiquement noms
Route::get('/users', $action);       // auto: users.get
Route::post('/users', $action);      // auto: users.post
Route::get('/users/{id}', $action);  // auto: users.id.get

// Exemples avec API
Route::get('/api/v1/users', $action);         // auto: api.v1.users.get
Route::post('/api/v1/users/{id}', $action);   // auto: api.v1.users.id.post

// Route racine
Route::get('/', $action);                     // auto: root.get

// Caractères spéciaux sont normalisés
Route::get('/api-v1/user_profile', $action);  // auto: api.v1.user.profile.get

// Désactiver auto-nommage
Route::disableAutoNaming();

// Vérifier statut
$enabled = Route::router()->isAutoNamingEnabled();
```

### 6. Obtenir Toutes Routes Nommées

```php
$namedRoutes = Route::getNamedRoutes();
// ['users.show' => Route, 'users.store' => Route, ...]
```

---

## Tags

### 1. Ajouter Tag Unique

```php
Route::get('/api/users', $action)
    ->tag('api');
```

### 2. Tags Multiples

```php
Route::get('/api/public/posts', $action)
    ->tag(['api', 'public', 'posts']);
```

### 3. Obtenir Routes par Tag

```php
$apiRoutes = Route::getRoutesByTag('api');
```

### 4. Vérifier Existence Tag

```php
if (Route::router()->hasTag('api')) {
    echo "Has API routes";
}
```

### 5. Obtenir Tous Tags

```php
$allTags = Route::router()->getAllTags();
// ['api', 'public', 'admin', ...]
```

---

## Fonctions Utilitaires

### 1. route()

```php
// Obtenir route par nom
$route = route('users.show');
```

### 2. current_route()

```php
// Obtenir route actuelle
$current = current_route();
echo $current->getUri();
```

### 3. previous_route()

```php
// Obtenir route précédente
$previous = previous_route();
```

### 4. route_is()

```php
// Vérifier nom route (avec support wildcards)
if (route_is('users.*')) {
    echo "User route";
}

if (route_is('admin.users.show')) {
    echo "Admin user show";
}
```

### 5. route_name()

```php
// Obtenir nom route actuelle
$name = route_name();
// 'users.show'
```

### 6. router()

```php
// Obtenir instance router
$router = router();
$routes = $router->getRoutes();
```

### 7. dispatch_route()

```php
// Dispatcher route
$route = dispatch_route('/users/123', 'GET');
```

### 8. route_url()

```php
// Générer URL
$url = route_url('users.show', ['id' => 123]);
// '/users/123'

$url = route_url('posts.show', ['slug' => 'hello-world']);
// '/posts/hello-world'
```

### 9. route_has()

```php
// Vérifier existence route
if (route_has('users.show')) {
    echo "Route exists";
}
```

### 10. route_stats()

```php
// Obtenir statistiques routes
$stats = route_stats();
/*
[
    'total' => 150,
    'methods' => ['GET' => 80, 'POST' => 40, ...],
    'named' => 120,
    'with_middleware' => 60,
    ...
]
*/
```

### 11. routes_by_tag()

```php
// Obtenir routes par tag
$apiRoutes = routes_by_tag('api');
```

### 12. route_back()

```php
// Retourner à route précédente
$previous = route_back();
```

### 13-18. Helpers Additionnels

```php
// Vérifier si route actuelle est nommée
if (route_is('users.show')) {
    // ...
}

// Obtenir paramètres route actuelle
$route = current_route();
$params = $route->getParameters();

// Obtenir middleware route actuelle
$middleware = current_route()->getMiddleware();

// Obtenir tags route actuelle
$tags = current_route()->getTags();
```

---

## Raccourcis Routes

### 1. auth()

```php
Route::get('/dashboard', $action)->auth();
// Ajoute AuthMiddleware
```

### 2. guest()

```php
Route::get('/login', $action)->guest();
// Seulement pour utilisateurs non-authentifiés
```

### 3. api()

```php
Route::get('/api/data', $action)->api();
// Middleware API
```

### 4. web()

```php
Route::get('/page', $action)->web();
// Middleware Web (CSRF, Session, etc.)
```

### 5. cors()

```php
Route::get('/api/public', $action)->cors();
// CorsMiddleware
```

### 6. localhost()

```php
Route::get('/debug', $action)->localhost();
// Seulement localhost (127.0.0.1)
```

### 7. secure()

```php
Route::get('/payment', $action)->secure();
// HTTPS seulement
```

### 8-10. Raccourcis Throttle

```php
// 60 requêtes par minute (standard)
Route::post('/api/data', $action)->throttleStandard();

// 10 requêtes par minute (strict)
Route::post('/api/critical', $action)->throttleStrict();

// 1000 requêtes par minute (généreux)
Route::post('/api/bulk', $action)->throttleGenerous();
```

### 11. public()

```php
Route::get('/page', $action)->public();
// Tag 'public'
```

### 12. private()

```php
Route::get('/page', $action)->private();
// Tag 'private'
```

### 13. admin()

```php
Route::get('/admin/users', $action)->admin();
// AuthMiddleware + AdminMiddleware + HTTPS + IP whitelist
```

### 14. apiEndpoint()

```php
Route::get('/api/data', $action)->apiEndpoint();
// API + CORS + JSON + throttle
```

---

## Macros Routes

### 1. resource()

```php
use CloudCastle\Http\Router\Facade\Route;

// Crée routes RESTful pour ressource
Route::resource('/users', UserController::class);

// Crée:
// GET    /users           -> UserController::index
// GET    /users/create    -> UserController::create
// POST   /users           -> UserController::store
// GET    /users/{id}      -> UserController::show
// GET    /users/{id}/edit -> UserController::edit
// PUT    /users/{id}      -> UserController::update
// DELETE /users/{id}      -> UserController::destroy
```

### 2. apiResource()

```php
// Ressource API (sans pages create/edit)
Route::apiResource('/posts', PostController::class, 100);

// Crée:
// GET    /posts       -> PostController::index    (throttle: 100/min)
// POST   /posts       -> PostController::store    (throttle: 100/min)
// GET    /posts/{id}  -> PostController::show     (throttle: 100/min)
// PUT    /posts/{id}  -> PostController::update   (throttle: 100/min)
// DELETE /posts/{id}  -> PostController::destroy  (throttle: 100/min)
```

### 3. crud()

```php
// CRUD simple
Route::crud('/products', ProductController::class);

// Crée:
// GET    /products       -> ProductController::index
// POST   /products       -> ProductController::create
// GET    /products/{id}  -> ProductController::read
// PUT    /products/{id}  -> ProductController::update
// DELETE /products/{id}  -> ProductController::delete
```

### 4. auth()

```php
// Routes authentification
Route::auth();

// Crée:
// GET  /login            -> AuthController::showLoginForm
// POST /login            -> AuthController::login
// POST /logout           -> AuthController::logout
// GET  /register         -> AuthController::showRegisterForm
// POST /register         -> AuthController::register
// GET  /password/reset   -> AuthController::showResetForm
// POST /password/reset   -> AuthController::reset
```

### 5. adminPanel()

```php
// Panneau admin avec IP whitelist
Route::adminPanel('/admin', ['192.168.1.0/24']);

// Crée (avec Auth + Admin middleware + HTTPS):
// GET /admin/dashboard -> AdminController::dashboard
// GET /admin/users     -> AdminController::users
// GET /admin/settings  -> AdminController::settings
// GET /admin/logs      -> AdminController::logs
```

### 6. apiVersion()

```php
// Versionnage API
Route::apiVersion('v1', function() {
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/posts', [PostController::class, 'index']);
});

// Routes disponibles comme /api/v1/users, /api/v1/posts
```

### 7. webhooks()

```php
// Webhooks avec IP whitelist
Route::webhooks('/webhooks', ['192.168.1.0/24']);

// Crée:
// POST /webhooks/github  -> WebhookController::github
// POST /webhooks/stripe  -> WebhookController::stripe
// POST /webhooks/custom  -> WebhookController::custom
```

---

## Génération URL

### 1. Génération de Base

```php
use CloudCastle\Http\Router\UrlGenerator;

$generator = new UrlGenerator();

$url = $generator->generate('users.show', ['id' => 123]);
// '/users/123'
```

### 2. URL Absolute

```php
$url = $generator->generate('users.show', ['id' => 123])
    ->absolute();
// 'http://example.com/users/123'
```

### 3. URL avec Domaine

```php
$url = $generator->generate('users.show', ['id' => 123])
    ->toDomain('api.example.com');
// 'http://api.example.com/users/123'
```

### 4. URL avec Protocole

```php
$url = $generator->generate('users.show', ['id' => 123])
    ->toProtocol('https');
// 'https://example.com/users/123'
```

### 5. URL avec Paramètres Query

```php
$url = $generator->generate('users.index', [], [
    'page' => 2,
    'limit' => 10,
    'sort' => 'name'
]);
// '/users?page=2&limit=10&sort=name'
```

### 6. URL Signée

```php
$signedUrl = $generator->signed('verify.email', ['user' => 123], 3600);
// '/verify/email/123?signature=abc...&expires=1234567890'
```

### 7. Définir URL de Base

```php
$generator->setBaseUrl('https://api.example.com');
```

### 8-11. Génération Combinée

```php
$url = $generator->generate('api.users.show', ['id' => 123])
    ->toDomain('api.example.com')
    ->toProtocol('https')
    ->absolute();
// 'https://api.example.com/api/users/123'

// Via helper
$url = route_url('users.show', ['id' => 123]);
```

---

## Expression Language

### 1. Condition de Base

```php
Route::get('/admin', $action)
    ->condition('request.ip == "192.168.1.1"');
```

### 2. Opérateurs Comparaison

```php
Route::get('/api/data', $action)
    ->condition('request.time > 9 and request.time < 18');

Route::get('/premium', $action)
    ->condition('user.level >= 5');

Route::get('/limited', $action)
    ->condition('request.count <= 100');
```

### 3. Opérateurs Logiques

```php
Route::get('/api/secure', $action)
    ->condition('request.ip == "192.168.1.1" and request.method == "GET"');

Route::get('/public', $action)
    ->condition('request.path == "/public" or request.path == "/open"');
```

### 4. Classe ExpressionLanguage

```php
use CloudCastle\Http\Router\ExpressionLanguage\ExpressionLanguage;

$expr = new ExpressionLanguage();

$result = $expr->evaluate('10 > 5 and 20 < 30', []);
// true

$result = $expr->evaluate('user.age >= 18', ['user' => ['age' => 25]]);
// true
```

### 5. Expressions Complexes

```php
Route::get('/api/restricted', $action)
    ->condition(
        '(request.ip == "192.168.1.1" or request.ip == "10.0.0.1") ' .
        'and request.time >= 9 and request.time <= 18'
    );
```

---

## Mise en Cache Routes

### 1. Activer Cache

```php
$router->enableCache('var/cache/routes');
```

### 2. Compiler Routes

```php
// Compiler
$router->compile();

// Compilation forcée
$router->compile(force: true);
```

### 3. Charger depuis Cache

```php
if ($router->loadFromCache()) {
    echo "Routes loaded from cache";
} else {
    // Enregistrer routes
    require 'routes/web.php';
    $router->compile();
}
```

### 4. Effacer Cache

```php
$router->clearCache();
```

### 5. Auto-Compilation

```php
$router->autoCompile();
// Compile automatiquement lors changements
```

### 6. Vérifier Chargement Cache

```php
if ($router->isCacheLoaded()) {
    echo "Cache is loaded";
}
```

### Exemple Complet avec Mise en Cache

```php
use CloudCastle\Http\Router\Router;

$router = new Router();
$router->enableCache(__DIR__ . '/var/cache/routes');

if (!$router->loadFromCache()) {
    // Enregistrer routes
    require __DIR__ . '/routes/web.php';
    require __DIR__ . '/routes/api.php';
    
    // Compiler
    $router->compile();
}

// Utiliser routes
$route = $router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
```

---

## Système Plugin

### 1. PluginInterface

```php
use CloudCastle\Http\Router\Contracts\PluginInterface;
use CloudCastle\Http\Router\Route;

interface PluginInterface
{
    public function beforeDispatch(Route $route, string $uri, string $method): void;
    public function afterDispatch(Route $route, mixed $result): mixed;
    public function onRouteRegistered(Route $route): void;
    public function onException(Route $route, \Exception $e): void;
}
```

### 2. Enregistrer Plugin

```php
Route::registerPlugin(new LoggerPlugin());
```

### 3. Désenregistrer Plugin

```php
Route::unregisterPlugin('logger');
```

### 4. Obtenir Plugin

```php
$plugin = Route::getPlugin('logger');
```

### 5. Vérifier Existence Plugin

```php
if (Route::hasPlugin('logger')) {
    echo "Logger plugin registered";
}
```

### 6. Obtenir Tous Plugins

```php
$plugins = Route::getPlugins();
```

### 7. LoggerPlugin (intégré)

```php
use CloudCastle\Http\Router\Plugin\LoggerPlugin;

$logger = new LoggerPlugin('/var/log/routes.log');
Route::registerPlugin($logger);
```

### 8. AnalyticsPlugin (intégré)

```php
use CloudCastle\Http\Router\Plugin\AnalyticsPlugin;

$analytics = new AnalyticsPlugin();
Route::registerPlugin($analytics);

// Obtenir statistiques
$stats = $analytics->getStats();
```

### 9. ResponseCachePlugin (intégré)

```php
use CloudCastle\Http\Router\Plugin\ResponseCachePlugin;

$cache = new ResponseCachePlugin('/var/cache/responses', 3600);
Route::registerPlugin($cache);
```

### 10. AbstractPlugin

```php
use CloudCastle\Http\Router\Plugin\AbstractPlugin;

class MyPlugin extends AbstractPlugin
{
    public function beforeDispatch(Route $route, string $uri, string $method): void
    {
        // Logique avant dispatch
    }
    
    public function afterDispatch(Route $route, mixed $result): mixed
    {
        // Logique après dispatch
        return $result;
    }
}
```

### 11-13. Hooks Plugin

```php
class FullPlugin implements PluginInterface
{
    // Hook avant dispatch
    public function beforeDispatch(Route $route, string $uri, string $method): void
    {
        echo "Before: $method $uri\n";
    }
    
    // Hook après dispatch
    public function afterDispatch(Route $route, mixed $result): mixed
    {
        echo "After dispatch\n";
        return $result;
    }
    
    // Hook lors enregistrement route
    public function onRouteRegistered(Route $route): void
    {
        echo "Route registered: {$route->getUri()}\n";
    }
    
    // Hook lors exception
    public function onException(Route $route, \Exception $e): void
    {
        echo "Exception: {$e->getMessage()}\n";
    }
}
```

---

## Chargeurs Routes

### 1. JsonLoader

```php
use CloudCastle\Http\Router\Loader\JsonLoader;

$loader = new JsonLoader($router);
$loader->load(__DIR__ . '/routes.json');
```

**routes.json:**
```json
{
    "routes": [
        {
            "method": "GET",
            "uri": "/users",
            "action": "UserController@index",
            "name": "users.index"
        },
        {
            "method": "POST",
            "uri": "/users",
            "action": "UserController@store",
            "name": "users.store",
            "middleware": ["auth"],
            "throttle": [60, 1]
        }
    ]
}
```

### 2. YamlLoader

```php
use CloudCastle\Http\Router\Loader\YamlLoader;

$loader = new YamlLoader($router);
$loader->load(__DIR__ . '/routes.yaml');
```

**routes.yaml:**
```yaml
routes:
  - method: GET
    uri: /users
    action: UserController@index
    name: users.index
  
  - method: POST
    uri: /users
    action: UserController@store
    name: users.store
    middleware:
      - auth
    throttle: [60, 1]
```

### 3. XmlLoader

```php
use CloudCastle\Http\Router\Loader\XmlLoader;

$loader = new XmlLoader($router);
$loader->load(__DIR__ . '/routes.xml');
```

**routes.xml:**
```xml
<?xml version="1.0"?>
<routes>
    <route method="GET" uri="/users" action="UserController@index" name="users.index"/>
    <route method="POST" uri="/users" action="UserController@store" name="users.store">
        <middleware>auth</middleware>
        <throttle>60,1</throttle>
    </route>
</routes>
```

### 4. AttributeLoader

```php
use CloudCastle\Http\Router\Loader\AttributeLoader;

$loader = new AttributeLoader($router);
$loader->loadFromDirectory(__DIR__ . '/app/Controllers');
```

**UserController.php:**
```php
use CloudCastle\Http\Router\Attributes\Route as RouteAttribute;

#[RouteAttribute('/users', 'GET', name: 'users.index')]
class UserController
{
    #[RouteAttribute('/users/{id}', 'GET', name: 'users.show')]
    public function show(int $id)
    {
        return "User $id";
    }
}
```

### 5. Fichiers PHP (méthode standard)

```php
// routes/web.php
Route::get('/users', [UserController::class, 'index']);
Route::post('/users', [UserController::class, 'store']);

// routes/api.php
Route::group(['prefix' => '/api'], function() {
    Route::get('/users', [ApiUserController::class, 'index']);
});

// index.php
require 'routes/web.php';
require 'routes/api.php';
```

---

## Support PSR

### 1. PSR-7 HTTP Message

```php
use Psr\Http\Message\ServerRequestInterface;
use Nyholm\Psr7\ServerRequestFactory;

$request = ServerRequestFactory::fromGlobals();
// Objet request PSR-7

// Utilisation avec router
$uri = $request->getUri()->getPath();
$method = $request->getMethod();

$route = Route::dispatch($uri, $method);
```

### 2. PSR-15 HTTP Server Handler

```php
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class RouteHandler implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $uri = $request->getUri()->getPath();
        $method = $request->getMethod();
        
        $route = Route::dispatch($uri, $method);
        $result = $route->run();
        
        // Retourner Response PSR-7
        return new Response(200, [], $result);
    }
}
```

### 3. Psr15MiddlewareAdapter

```php
use CloudCastle\Http\Router\Psr15\Psr15MiddlewareAdapter;

$adapter = new Psr15MiddlewareAdapter($router);

// Utiliser comme middleware PSR-15
$response = $adapter->process($request, $handler);
```

---

## Action Resolver

CloudCastle HTTP Router supporte **6 formats** pour actions routes:

### 1. Closure

```php
Route::get('/users', function() {
    return 'Users list';
});
```

### 2. Array [Controller::class, 'method']

```php
Route::get('/users', [UserController::class, 'index']);
```

### 3. String "Controller@method"

```php
Route::get('/users', 'UserController@index');
```

### 4. String "Controller::method"

```php
Route::get('/users', 'UserController::index');
```

### 5. Controller Invocable

```php
class ShowUserController
{
    public function __invoke(int $id)
    {
        return "User: $id";
    }
}

Route::get('/users/{id}', ShowUserController::class);
```

### 6. Injection Dépendances

```php
class UserController
{
    public function __construct(
        private UserRepository $repository
    ) {}
    
    public function index()
    {
        return $this->repository->all();
    }
}

Route::get('/users', [UserController::class, 'index']);
// ActionResolver résoudra automatiquement dépendances
```

---

## Statistiques et Requêtes

### 1. getRouteStats()

```php
$stats = Route::getRouteStats();
/*
[
    'total' => 150,
    'named' => 120,
    'with_middleware' => 60,
    'with_tags' => 80,
    'methods' => ['GET' => 80, 'POST' => 40, ...],
    'domains' => ['api.example.com' => 30, ...],
    'ports' => [8080 => 20, ...],
]
*/
```

### 2. getRoutesByMethod()

```php
$getRoutes = Route::router()->getRoutesByMethod('GET');
$postRoutes = Route::router()->getRoutesByMethod('POST');
```

### 3. getRoutesByDomain()

```php
$apiRoutes = Route::router()->getRoutesByDomain('api.example.com');
```

### 4. getRoutesByPort()

```php
$routes = Route::router()->getRoutesByPort(8080);
```

### 5. getRoutesByPrefix()

```php
$apiRoutes = Route::router()->getRoutesByPrefix('/api');
```

### 6. getRoutesByUriPattern()

```php
$userRoutes = Route::router()->getRoutesByUriPattern('/users');
```

### 7. getRoutesByMiddleware()

```php
$authRoutes = Route::router()->getRoutesByMiddleware(AuthMiddleware::class);
```

### 8. getRoutesByController()

```php
$routes = Route::router()->getRoutesByController(UserController::class);
```

### 9. getRoutesWithIpRestrictions()

```php
$restrictedRoutes = Route::router()->getRoutesWithIpRestrictions();
```

### 10. getThrottledRoutes()

```php
$throttledRoutes = Route::router()->getThrottledRoutes();
```

### 11. getRoutesWithDomain()

```php
$domainRoutes = Route::router()->getRoutesWithDomain();
```

### 12. getRoutesWithPort()

```php
$portRoutes = Route::router()->getRoutesWithPort();
```

### 13. searchRoutes()

```php
$results = Route::router()->searchRoutes('user');
// Toutes routes contenant 'user' dans URI ou nom
```

### 14. getRoutesGroupedByMethod()

```php
$grouped = Route::getRoutesGroupedByMethod();
/*
[
    'GET' => [Route, Route, ...],
    'POST' => [Route, Route, ...],
    ...
]
*/
```

### 15. getRoutesGroupedByPrefix()

```php
$grouped = Route::getRoutesGroupedByPrefix();
/*
[
    '/api' => [Route, Route, ...],
    '/admin' => [Route, Route, ...],
    ...
]
*/
```

### 16. getRoutesGroupedByDomain()

```php
$grouped = Route::getRoutesGroupedByDomain();
/*
[
    'api.example.com' => [Route, Route, ...],
    'admin.example.com' => [Route, Route, ...],
    ...
]
*/
```

### 17. getRoutes()

```php
$allRoutes = Route::getRoutes();
```

### 18. getNamedRoutes()

```php
$namedRoutes = Route::getNamedRoutes();
```

### 19. getAllDomains()

```php
$domains = Route::router()->getAllDomains();
// ['api.example.com', 'admin.example.com', ...]
```

### 20. getAllPorts()

```php
$ports = Route::router()->getAllPorts();
// [8080, 8081, 443, ...]
```

### 21. getAllTags()

```php
$tags = Route::router()->getAllTags();
// ['api', 'admin', 'public', ...]
```

### 22. count()

```php
$total = Route::count();
echo "Total routes: $total";
```

### 23. getRoutesAsJson()

```php
$json = Route::getRoutesAsJson(JSON_PRETTY_PRINT);
echo $json;
```

### 24. getRoutesAsArray()

```php
$array = Route::getRoutesAsArray();
```

---

## Sécurité

### 1. Protection Path Traversal

```php
// Router protège automatiquement contre ../../../
Route::get('/files/{path}', function($path) {
    // $path ne contiendra jamais ../
    return "File: $path";
});
```

### 2. Protection SQL Injection

```php
// Paramètres validés automatiquement
Route::get('/users/{id}', function($id) {
    // Sûr à utiliser dans SQL
    return DB::find($id);
})->where('id', '[0-9]+');
```

### 3. Protection XSS

```php
Route::get('/search/{query}', function($query) {
    // Échapper sortie
    return htmlspecialchars($query);
});
```

### 4. Rate Limiting

```php
// Protection DDoS
Route::post('/api/submit', $action)
    ->throttle(60, 1);
```

### 5. Filtrage IP

```php
// Whitelist seulement IPs fiables
Route::get('/admin', $action)
    ->whitelistIp(['192.168.1.0/24']);
```

### 6. Application HTTPS

```php
// Forcer utilisation HTTPS
Route::get('/payment', $action)->https();
Route::get('/secure', $action)->secure();
```

### 7. Restrictions Protocole

```php
// Seulement HTTPS/WSS
Route::get('/ws/secure', $action)
    ->protocol(['wss']);
```

### 8. Protection ReDoS

```php
// Router protège contre regex DoS
// Motifs sûrs automatiquement
Route::get('/users/{id}', $action)
    ->where('id', '[0-9]+');  // Sûr
```

### 9. Protection Method Override

```php
// Protection contre usurpation méthodes
// Router vérifie vraie méthode HTTP
```

### 10. Protection Injection Cache

```php
// Mise en cache sécurisée
$router->enableCache('var/cache/routes');
// Cache est signé et validé
```

### 11. Protection IP Spoofing

```php
// Router vérifie X-Forwarded-For
// et protège contre usurpation IP
```

### 12. Système Auto-Ban

```php
// Blocage automatique IPs attaquantes
$banManager = new BanManager(5, 3600);
Route::post('/login', $action)
    ->throttle(3, 1)
    ->getRateLimiter()
    ?->setBanManager($banManager);
```

---

## Exceptions

### 1. RouteNotFoundException

```php
try {
    $route = Route::dispatch('/nonexistent', 'GET');
} catch (\CloudCastle\Http\Router\Exceptions\RouteNotFoundException $e) {
    http_response_code(404);
    echo "404 Not Found";
}
```

### 2. MethodNotAllowedException

```php
try {
    $route = Route::dispatch('/users', 'DELETE');  // Méthode non autorisée
} catch (\CloudCastle\Http\Router\Exceptions\MethodNotAllowedException $e) {
    http_response_code(405);
    $allowed = $e->getAllowedMethods();
    header('Allow: ' . implode(', ', $allowed));
    echo "405 Method Not Allowed";
}
```

### 3. IpNotAllowedException

```php
try {
    $route = Route::dispatch('/admin', 'GET');
} catch (\CloudCastle\Http\Router\Exceptions\IpNotAllowedException $e) {
    http_response_code(403);
    echo "403 Forbidden: IP not allowed";
}
```

### 4. TooManyRequestsException

```php
try {
    $route = Route::dispatch('/api/submit', 'POST');
} catch (\CloudCastle\Http\Router\Exceptions\TooManyRequestsException $e) {
    http_response_code(429);
    $retryAfter = $e->getRetryAfter();
    header("Retry-After: $retryAfter");
    echo "429 Too Many Requests";
}
```

### 5. InsecureConnectionException

```php
try {
    $route = Route::dispatch('/payment', 'POST');
} catch (\CloudCastle\Http\Router\Exceptions\InsecureConnectionException $e) {
    http_response_code(403);
    echo "403 Forbidden: HTTPS required";
}
```

### 6. BannedException

```php
try {
    $route = Route::dispatch('/api/data', 'GET');
} catch (\CloudCastle\Http\Router\Exceptions\BannedException $e) {
    http_response_code(403);
    echo "403 Forbidden: IP is banned";
}
```

### 7. InvalidActionException

```php
try {
    Route::get('/test', 'InvalidController@method');
    $route = Route::dispatch('/test', 'GET');
    $route->run();
} catch (\CloudCastle\Http\Router\Exceptions\InvalidActionException $e) {
    http_response_code(500);
    echo "500 Internal Server Error: Invalid action";
}
```

### 8. RouterException

```php
try {
    // Toute erreur router
} catch (\CloudCastle\Http\Router\Exceptions\RouterException $e) {
    http_response_code(500);
    echo "Router Error: " . $e->getMessage();
}
```

---

## Outils CLI

### 1. routes-list

```bash
# Afficher toutes routes
php bin/routes-list

# Avec filtre
php bin/routes-list --method=GET
php bin/routes-list --tag=api
php bin/routes-list --name=users.*
```

### 2. analyse

```bash
# Analyse routes
php bin/analyse

# Affiche:
# - Nombre total routes
# - Routes par méthodes
# - Routes par domaines
# - Routes avec middleware
# - Etc.
```

### 3. router

```bash
# Gestion router
php bin/router compile        # Compiler cache
php bin/router clear          # Effacer cache
php bin/router stats          # Statistiques
```

---

## Exemples Avancés

### Exemple 1: REST API avec Protection Complète

```php
use CloudCastle\Http\Router\Facade\Route;
use CloudCastle\Http\Router\BanManager;
use CloudCastle\Http\Router\Middleware\{
    AuthMiddleware,
    CorsMiddleware,
    SecurityLogger
};

// Configuration Auto-Ban
$banManager = new BanManager(5, 3600);

// API v1
Route::group([
    'prefix' => '/api/v1',
    'middleware' => [CorsMiddleware::class, SecurityLogger::class],
    'domain' => 'api.example.com',
    'https' => true,
    'tags' => ['api', 'v1'],
], function() use ($banManager) {
    
    // Endpoints publics
    Route::get('/posts', [PostController::class, 'index'])
        ->name('api.v1.posts.index')
        ->throttle(100, 1)
        ->tag('public');
    
    // Endpoints protégés
    Route::group(['middleware' => [AuthMiddleware::class]], function() use ($banManager) {
        
        Route::post('/posts', [PostController::class, 'store'])
            ->name('api.v1.posts.store')
            ->throttle(20, 1)
            ->getRateLimiter()
            ?->setBanManager($banManager);
        
        Route::put('/posts/{id}', [PostController::class, 'update'])
            ->name('api.v1.posts.update')
            ->where('id', '[0-9]+')
            ->throttle(30, 1);
        
        Route::delete('/posts/{id}', [PostController::class, 'destroy'])
            ->name('api.v1.posts.destroy')
            ->where('id', '[0-9]+')
            ->throttle(10, 1);
    });
});
```

### Exemple 2: Architecture Microservices

```php
// Service User (port 8081)
Route::group([
    'prefix' => '/users',
    'port' => 8081,
    'domain' => 'users.service.local',
    'tags' => ['user-service', 'microservice'],
], function() {
    Route::get('/', [UserServiceController::class, 'index']);
    Route::get('/{id}', [UserServiceController::class, 'show'])
        ->where('id', '[0-9]+');
    Route::post('/', [UserServiceController::class, 'create']);
});

// Service Product (port 8082)
Route::group([
    'prefix' => '/products',
    'port' => 8082,
    'domain' => 'products.service.local',
    'tags' => ['product-service', 'microservice'],
], function() {
    Route::get('/', [ProductServiceController::class, 'index']);
    Route::get('/{id}', [ProductServiceController::class, 'show']);
});

// Service Order (port 8083)
Route::group([
    'prefix' => '/orders',
    'port' => 8083,
    'domain' => 'orders.service.local',
    'tags' => ['order-service', 'microservice'],
], function() {
    Route::post('/', [OrderServiceController::class, 'create']);
    Route::get('/{id}', [OrderServiceController::class, 'show']);
});
```

### Exemple 3: Plateforme SaaS avec Niveaux

```php
// Niveau Free
Route::group([
    'prefix' => '/api/free',
    'middleware' => [AuthMiddleware::class],
    'tags' => ['free-tier'],
], function() {
    Route::get('/data', $action)
        ->throttle(10, 1);  // 10 requêtes/min
});

// Niveau Pro
Route::group([
    'prefix' => '/api/pro',
    'middleware' => [AuthMiddleware::class, ProMiddleware::class],
    'tags' => ['pro-tier'],
], function() {
    Route::get('/data', $action)
        ->throttle(100, 1);  // 100 requêtes/min
});

// Niveau Enterprise
Route::group([
    'prefix' => '/api/enterprise',
    'middleware' => [AuthMiddleware::class, EnterpriseMiddleware::class],
    'tags' => ['enterprise-tier'],
], function() {
    Route::get('/data', $action)
        ->throttle(1000, 1);  // 1000 requêtes/min
});
```

### Exemple 4: Application Multi-Domaines

```php
// Site principal
Route::group(['domain' => 'example.com'], function() {
    Route::get('/', [HomeController::class, 'index']);
    Route::get('/about', [AboutController::class, 'index']);
});

// Sous-domaine API
Route::group(['domain' => 'api.example.com', 'https' => true], function() {
    Route::apiResource('/users', UserApiController::class);
    Route::apiResource('/posts', PostApiController::class);
});

// Admin
Route::group([
    'domain' => 'admin.example.com',
    'https' => true,
    'middleware' => [AuthMiddleware::class, AdminMiddleware::class],
    'whitelistIp' => ['192.168.1.0/24'],
], function() {
    Route::get('/dashboard', [AdminController::class, 'dashboard']);
    Route::resource('/users', AdminUserController::class);
});

// Blog
Route::group(['domain' => 'blog.example.com'], function() {
    Route::get('/', [BlogController::class, 'index']);
    Route::get('/{slug}', [BlogController::class, 'show']);
});
```

### Exemple 5: Mise en Cache pour Performance

```php
use CloudCastle\Http\Router\Router;
use CloudCastle\Http\Router\Plugin\ResponseCachePlugin;

$router = new Router();

// Activer cache routes
$router->enableCache(__DIR__ . '/var/cache/routes');

// Ajouter plugin cache réponses
$responseCache = new ResponseCachePlugin(__DIR__ . '/var/cache/responses', 3600);
$router->registerPlugin($responseCache);

// Charger depuis cache ou enregistrer
if (!$router->loadFromCache()) {
    require __DIR__ . '/routes/web.php';
    require __DIR__ . '/routes/api.php';
    $router->compile();
}

// Dispatch
$route = $router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
$response = $route->run();

echo $response;
```

---

## Conclusion

CloudCastle HTTP Router fournit **209+ fonctionnalités** pour créer applications web modernes, sécurisées et hautement performantes sur PHP 8.2+.

### Avantages Principaux:

- ⚡ **Haute Performance:** 54,891 req/sec
- 🔒 **Sécurité Complète:** 12+ mécanismes protection
- 💎 **Riche Fonctionnalité:** 209+ méthodes
- 💾 **Mémoire Efficace:** 1.32 KB/route
- 📊 **Évolutivité:** 1,160,000+ routes
- ✅ **Fiabilité:** 501 tests, 0 erreurs

### Prochaines Étapes:

1. Étudiez [API Reference](API_REFERENCE.md) pour informations détaillées
2. Consultez [exemples](../../examples/) pour application pratique
3. Lisez [FAQ](FAQ.md) pour réponses questions fréquentes
4. Examinez [rapports sécurité](SECURITY_REPORT.md)
5. Vérifiez [analyse performance](PERFORMANCE_ANALYSIS.md)

---

**© 2024 CloudCastle HTTP Router**  
**Version:** 1.1.1  
**Licence:** MIT

[⬆ Retour en haut](#cloudcastle-http-router---guide-utilisateur-complet)


---

## 📚 Navigation Documentation

[README](../../README.md) | [USER_GUIDE](USER_GUIDE.md) | [FEATURES_INDEX](FEATURES_INDEX.md) | [API_REFERENCE](API_REFERENCE.md) | [ALL_FEATURES](ALL_FEATURES.md) | [TESTS_SUMMARY](TESTS_SUMMARY.md) | [PERFORMANCE](PERFORMANCE_ANALYSIS.md) | [SECURITY](SECURITY_REPORT.md) | [COMPARISON](COMPARISON.md) | [FAQ](FAQ.md) | [DOC_SUMMARY](DOCUMENTATION_SUMMARY.md)

**Documentation détaillée:** [Features](features/) (22 fichiers) | [Tests](tests/) (7 rapports)

---
