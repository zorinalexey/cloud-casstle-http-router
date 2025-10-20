# FAQ - Questions Fréquemment Posées

[English](../en/FAQ.md) | [Русский](../ru/FAQ.md) | [Deutsch](../de/FAQ.md) | [**Français**](FAQ.md) | [中文](../zh/FAQ.md)

---

**Version:** 1.1.1  
**Date:** Octobre 2025

---

## 📚 Navigation Documentation

### Documents Principaux
- [README](../../README.md) - Accueil
- [USER_GUIDE](USER_GUIDE.md) - Guide utilisateur complet
- [FEATURES_INDEX](FEATURES_INDEX.md) - Catalogue de toutes les fonctionnalités
- [API_REFERENCE](API_REFERENCE.md) - Référence API

### Fonctionnalités
- [Documentation détaillée](features/) - 22 catégories
- [ALL_FEATURES](ALL_FEATURES.md) - Liste complète des fonctionnalités

### Tests et Rapports
- [TESTS_SUMMARY](TESTS_SUMMARY.md) - Résumé des tests
- [Rapports de tests détaillés](tests/) - 7 rapports
- [PERFORMANCE_ANALYSIS](PERFORMANCE_ANALYSIS.md) - Analyse de performance
- [SECURITY_REPORT](SECURITY_REPORT.md) - Rapport de sécurité

### Supplémentaire
- **[FAQ](FAQ.md) - Questions Fréquentes** ← Vous êtes ici
- [COMPARISON](COMPARISON.md) - Comparaison avec les alternatives
- [DOCUMENTATION_SUMMARY](DOCUMENTATION_SUMMARY.md) - Résumé de la documentation

---

## Contenu

### Général
1. [Qu'est-ce que CloudCastle HTTP Router ?](#quest-ce-que-cloudcastle-http-router)
2. [Pourquoi choisir CloudCastle ?](#pourquoi-choisir-cloudcastle)
3. [Quelles sont les exigences ?](#exigences)
4. [Comment installer CloudCastle ?](#installation)

### Performance
5. [À quelle vitesse est CloudCastle ?](#performance)
6. [Comment améliorer les performances ?](#optimisation)
7. [Qu'est-ce que le cache de routes ?](#cache)
8. [Combien de routes peut-il gérer ?](#scalabilite)

### Sécurité
9. [À quel point CloudCastle est-il sécurisé ?](#securite)
10. [Qu'est-ce que le Rate Limiting ?](#rate-limiting)
11. [Qu'est-ce que le système Auto-Ban ?](#auto-ban)
12. [Comment protéger le panneau admin ?](#proteger-admin)

### Utilisation
13. [Comment enregistrer des routes ?](#enregistrer-routes)
14. [Que sont les groupes de routes ?](#groupes-routes)
15. [Comment utiliser le middleware ?](#middleware)
16. [Comment créer une API RESTful ?](#api-restful)

### Avancé
17. [Que sont les Macros de Route ?](#macros)
18. [Comment utiliser les plugins ?](#plugins)
19. [Support PSR ?](#support-psr)
20. [Peut-il être utilisé avec des frameworks ?](#frameworks)

---

## Général

### Qu'est-ce que CloudCastle HTTP Router ?

CloudCastle HTTP Router est une bibliothèque de routage moderne pour PHP 8.2+ qui fournit **209+ fonctionnalités** pour créer des applications web sécurisées et performantes.

Points clés :
- ⚡ 53 637 req/sec de performance
- 🔒 Conformité 13/13 OWASP Top 10
- 💎 209+ fonctionnalités
- ✅ 501 tests (100% réussis)

---

### Pourquoi choisir CloudCastle ?

CloudCastle est le seul routeur avec :

1. Rate Limiting intégré
```php
Route::post('/api', $action)->throttle(60, 1);
```

2. Système Auto-Ban
```php
$banManager = new BanManager(5, 3600);
Route::post('/login', $action)
    ->throttle(3, 1)
    ->getRateLimiter()?->setBanManager($banManager);
```

3. Filtrage IP intégré
```php
Route::get('/admin', $action)->whitelistIp(['192.168.1.0/24']);
```

4. 209+ fonctionnalités — plus que les concurrents.

Comparaison :
- Symfony : 180+ fonctionnalités, pas de rate limiting intégré
- Laravel : 150+ fonctionnalités, framework uniquement
- FastRoute : ~20 fonctionnalités, vitesse pure
- Slim : ~50 fonctionnalités, fonctionnalité de base

CloudCastle = meilleur équilibre vitesse, sécurité et fonctionnalité.

---

### Exigences

Minimum :
- PHP 8.2+
- Composer
- ~2 MB d'espace disque

Recommandé :
- PHP 8.3+
- Opcache activé
- 128 MB+ memory_limit

Versions PHP supportées : 8.2/8.3/8.4

---

### Installation

```bash
composer require cloud-castle/http-router
```

Démarrage rapide :
```php
<?php
require 'vendor/autoload.php';
use CloudCastle\Http\Router\Facade\Route;
Route::get('/users', fn() => 'Liste des utilisateurs');
$route = Route::dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
echo $route->run();
```

---

## Performance

### À quelle vitesse est CloudCastle ?

Tests de charge :
- Léger (100 routes) : 55 923 req/sec
- Moyen (500 routes) : 54 680 req/sec
- Lourd (1000 routes) : 53 637 req/sec

Comparaison (1000 routes) :
1. FastRoute : 60 000 req/sec
2. CloudCastle : 53 637 req/sec (avec 209+ fonctionnalités)
3. Slim : 45 000 req/sec
4. Symfony : 40 000 req/sec
5. Laravel : 35 000 req/sec

---

### Optimisation

1) Cache de routes
```php
$router->enableCache('cache/routes');
if (!$router->loadFromCache()) {
    require 'routes/web.php';
    $router->compile();
}
```

2) Paramètres inline
```php
// Plus rapide
Route::get('/users/{id:[0-9]+}', $action);
// Plus lent
Route::get('/users/{id}', $action)->where('id', '[0-9]+');
```

3) Groupement
```php
Route::group(['prefix' => '/api', 'middleware' => [...]], function() {
    // 100 routes
});
```

---

### Cache

Compile les routes dans un format optimisé pour un chargement instantané.

Sans cache : ~10–50 ms init  
Avec cache : ~0,1–1 ms init  
Accélération : 10–50x

---

### Scalabilité

Testé jusqu'à 1 095 000 routes ; ~1,39 KB/route.

---

## Sécurité

### À quel point CloudCastle est-il sécurisé ?

Protections intégrées (13/13 OWASP) : Path Traversal, SQL Injection, XSS, Filtrage IP, IP Spoofing, ReDoS, Rate Limiting, Auto-Ban, HTTPS, Protocole, Domaine/Port, Cache Injection.

---

### Rate Limiting
```php
Route::post('/api/submit', $action)->throttle(60, 1);
// En cas de dépassement → TooManyRequestsException (HTTP 429)
```

### Auto-Ban
```php
$banManager = new BanManager(5, 3600);
Route::post('/login', $action)
    ->throttle(3, 1)
    ->getRateLimiter()?->setBanManager($banManager);
```

### Protection Admin
```php
Route::group([
  'prefix' => '/admin',
  'middleware' => [AuthMiddleware::class, AdminMiddleware::class],
  'https' => true,
  'whitelistIp' => ['192.168.1.0/24'],
  'throttle' => [30, 1]
], function() {
  Route::get('/dashboard', [AdminController::class, 'dashboard']);
});
```

---

## Utilisation

### Enregistrement de Routes
```php
use CloudCastle\Http\Router\Facade\Route;
Route::get('/users', [UserController::class, 'index']);
Route::post('/users', [UserController::class, 'store']);
```

### Groupes de Routes
```php
Route::group([
  'prefix' => '/api/v1',
  'middleware' => [AuthMiddleware::class],
  'throttle' => [100, 1],
  'tags' => 'api'
], function() {
  Route::get('/users', $action);
  Route::get('/posts', $action);
});
```

### Middleware
- Global : `Route::middleware([...])`
- Route : `->middleware([...])`
- Groupe : `Route::group(['middleware'=>[...]])`

### API RESTful
```php
Route::apiResource('users', ApiUserController::class, 100);
```

---

## Avancé

### Macros
- `resource()`, `apiResource()`, `crud()`, `auth()`, `adminPanel()`, `apiVersion()`, `webhooks()`

### Plugins
Implémenter `PluginInterface` ; intégrés : LoggerPlugin, AnalyticsPlugin, ResponseCachePlugin.

### Support PSR
PSR-1, PSR-4, PSR-7, PSR-12, PSR-15

### Frameworks
Fonctionne standalone ; intégrable avec Laravel/Symfony.

---

## 📚 Voir Aussi
- [USER_GUIDE.md](USER_GUIDE.md)
- [FEATURES_INDEX.md](FEATURES_INDEX.md)
- [TESTS_SUMMARY.md](TESTS_SUMMARY.md)
- [COMPARISON.md](COMPARISON.md)

---

© 2024 CloudCastle HTTP Router  
[⬆ Retour en haut](#faq---questions-fréquemment-posées)