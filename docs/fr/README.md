# CloudCastle HTTP Router

[English](../en/README.md) | [Русский](../../README.md) | [Deutsch](../de/README.md) | [**Français**](README.md) | [中文](../zh/README.md)

---

[![PHP Version](https://img.shields.io/badge/PHP-8.2%2B-blue.svg)](https://php.net)
[![License](https://img.shields.io/badge/license-MIT-green.svg)](../../LICENSE)
[![Tests](https://img.shields.io/badge/tests-501%2F501-success.svg)](../ru/TESTS_DETAILED.md)
[![PHPStan](https://img.shields.io/badge/PHPStan-Level%20Max-brightgreen.svg)](../../reports/phpstan.txt)
[![Performance](https://img.shields.io/badge/performance-54k%20req%2Fsec-brightgreen.svg)](../ru/PERFORMANCE_ANALYSIS.md)
[![Features](https://img.shields.io/badge/features-209%2B-blue.svg)](../../FEATURES_LIST.md)

**Bibliothèque de routage HTTP puissante, flexible et sécurisée pour PHP 8.2+** axée sur les performances, la sécurité et la facilité d'utilisation.

## ⚡ Pourquoi CloudCastle HTTP Router ?

### 🎯 Avantages clés

- ⚡ **Performance maximale** - **54 891 req/sec**, plus rapide que la plupart des concurrents
- 🔒 **Sécurité complète** - 12+ mécanismes de protection intégrés (OWASP Top 10)
- 💎 **209+ fonctionnalités** - fonctionnalité la plus riche du marché
- 💾 **Empreinte mémoire minimale** - seulement **1,32 KB par route**
- 📊 **Évolutivité extrême** - testé sur **1 160 000 routes**
- 🔌 **Extensibilité** - système de plugins, middleware, macros
- 📦 **Autonomie complète** - indépendant des frameworks
- ✅ **Fiabilité 100%** - 501 tests, 0 erreurs, 95%+ de couverture

---

## 🚀 Démarrage rapide

### Installation

```bash
composer require cloud-castle/http-router
```

### Exemple basique

```php
<?php

use CloudCastle\Http\Router\Facade\Route;

// Routes simples
Route::get('/users', fn() => 'Liste des utilisateurs');
Route::post('/users', fn() => 'Créer un utilisateur');
Route::get('/users/{id}', fn($id) => "Utilisateur: $id")
    ->where('id', '[0-9]+');

// Dispatch
$route = Route::dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
echo $route->run();
```

### Exemple avancé

```php
// API protégée
Route::group(['prefix' => '/api/v1'], function() {
    Route::get('/users', [UserController::class, 'index'])
        ->name('api.users')
        ->throttle(100, 1)  // 100 requêtes par minute
        ->middleware([AuthMiddleware::class])
        ->tag('api');
    
    Route::post('/users', [UserController::class, 'store'])
        ->throttle(20, 1)
        ->whitelistIp(['192.168.1.0/24'])
        ->middleware([AuthMiddleware::class, AdminMiddleware::class]);
});
```

---

## 💡 Fonctionnalités principales

### 1️⃣ Méthodes HTTP (7 façons)

```php
Route::get('/users', $action);
Route::post('/users', $action);
Route::put('/users/{id}', $action);
Route::patch('/users/{id}', $action);
Route::delete('/users/{id}', $action);
Route::any('/page', $action);              // Toute méthode
Route::match(['GET', 'POST'], '/form', $action);  // Plusieurs méthodes
Route::custom('VIEW', '/preview', $action);       // Méthode personnalisée
```

### 2️⃣ Paramètres intelligents

```php
// Paramètres de base
Route::get('/users/{id}', $action);

// Avec validation
Route::get('/users/{id}', $action)->where('id', '[0-9]+');
Route::get('/posts/{slug}', $action)->where('slug', '[a-z0-9-]+');

// Optionnels
Route::get('/blog/{category?}', $action);

// Valeurs par défaut
Route::get('/posts/{page}', $action)->defaults(['page' => 1]);

// Patterns inline
Route::get('/users/{id:[0-9]+}', $action);
```

### 3️⃣ Groupes de routes

```php
Route::group([
    'prefix' => '/api/v1',
    'middleware' => [AuthMiddleware::class],
    'domain' => 'api.example.com',
    'port' => 8080,
    'namespace' => 'App\\Controllers\\Api',
], function() {
    Route::get('/users', 'UserController@index');
    Route::get('/posts', 'PostController@index');
});
```

### 4️⃣ Rate Limiting & Auto-Ban

```php
// Rate limiting
Route::post('/api/login', $action)
    ->throttle(5, 1);  // 5 tentatives par minute

// Avec TimeUnit enum
use CloudCastle\Http\Router\TimeUnit;

Route::post('/api/submit', $action)
    ->throttle(100, TimeUnit::HOUR->value);

// Système Auto-ban
use CloudCastle\Http\Router\BanManager;

$banManager = new BanManager(
    maxViolations: 5,      // 5 violations
    banDuration: 3600      // Bannissement d'1 heure
);

Route::post('/login', $action)
    ->throttle(3, 1)
    ->getRateLimiter()
    ?->setBanManager($banManager);
```

### 5️⃣ Filtrage IP

```php
// Whitelist (IP autorisées uniquement)
Route::get('/admin', $action)
    ->whitelistIp(['192.168.1.1', '10.0.0.0/8']);

// Blacklist (IP bloquées)
Route::get('/public', $action)
    ->blacklistIp(['1.2.3.4', '5.6.7.0/24']);

// Dans un groupe
Route::group(['whitelistIp' => ['192.168.1.0/24']], function() {
    Route::get('/admin/users', $action);
    Route::get('/admin/settings', $action);
});
```

### 6️⃣ Middleware

```php
// Global
Route::middleware([CorsMiddleware::class, LoggerMiddleware::class]);

// Sur la route
Route::get('/admin', $action)
    ->middleware([AuthMiddleware::class, AdminMiddleware::class]);

// Middleware intégrés
Route::get('/api/data', $action)->auth();        // AuthMiddleware
Route::get('/api/public', $action)->cors();      // CorsMiddleware
Route::get('/secure', $action)->secure();        // Enforcement HTTPS
```

### 7️⃣ Routes nommées et génération d'URL

```php
// Nommage
Route::get('/users/{id}', $action)->name('users.show');

// Auto-nommage
Route::enableAutoNaming();

// Génération d'URL
$url = route_url('users.show', ['id' => 5]);  // /users/5

// Avec domaine
use CloudCastle\Http\Router\UrlGenerator;

$generator = new UrlGenerator();
$url = $generator->generate('users.show', ['id' => 5])
    ->toDomain('api.example.com')
    ->toProtocol('https')
    ->absolute();  // https://api.example.com/users/5

// URLs signées
$signedUrl = $generator->signed('verify.email', ['user' => 123], 3600);
```

### 8️⃣ Raccourcis de routes (14 méthodes)

```php
Route::get('/api/data', $action)->apiEndpoint();  // API + CORS + JSON
Route::get('/admin', $action)->admin();           // Auth + Admin + Whitelist
Route::get('/page', $action)->public();           // Tag public
Route::get('/dashboard', $action)->protected();   // Auth + HTTPS
Route::get('/localhost', $action)->localhost();   // Seulement localhost

// Raccourcis throttle
Route::post('/api/submit', $action)->throttleStandard();   // 60/min
Route::post('/api/strict', $action)->throttleStrict();     // 10/min
Route::post('/api/bulk', $action)->throttleGenerous();     // 1000/min
```

### 9️⃣ Macros de routes (7 macros)

```php
// Ressource RESTful
Route::resource('/users', UserController::class);
// Crée : index, create, store, show, edit, update, destroy

// Ressource API (sans create/edit)
Route::apiResource('/posts', PostController::class);

// CRUD (simple)
Route::crud('/products', ProductController::class);

// Authentification
Route::auth();
// Crée : login, logout, register, password.request, password.reset

// Panneau admin
Route::adminPanel('/admin');

// Versioning API
Route::apiVersion('v1', function() {
    Route::get('/users', $action);
});

// Webhooks
Route::webhooks('/webhooks', WebhookController::class);
```

### 🔟 Fonctions d'aide (18 fonctions)

```php
route('users.show');              // Obtenir la route par nom
current_route();                  // Route actuelle
previous_route();                 // Route précédente
route_is('users.*');              // Vérifier le nom de route
route_name();                     // Nom de la route actuelle
router();                         // Instance du routeur
dispatch_route($uri, $method);    // Dispatch
route_url('users.show', ['id' => 5]);  // Générer URL
route_has('users.show');          // Vérifier l'existence
route_stats();                    // Statistiques des routes
routes_by_tag('api');             // Routes par tag
route_back();                     // Retour en arrière
```

---

## 📊 Performance

### Benchmarks (PHPBench)

| Opération | Temps | Performance |
|-----------|-------|-------------|
| **Ajouter 1000 routes** | 3,435ms | 0,0034ms/route |
| **Matcher première route** | 123μs | 8 130 req/sec |
| **Matcher route moyenne** | 1,746ms | 573 req/sec |
| **Matcher dernière route** | 3,472ms | 288 req/sec |
| **Recherche nommée** | 3,858ms | 259 req/sec |
| **Groupes de routes** | 2,577ms | 388 req/sec |
| **Avec middleware** | 2,030ms | 493 req/sec |
| **Avec paramètres** | 73μs | 13 699 req/sec |

### Tests de charge

| Scénario | Routes | Requêtes | Résultat | Mémoire |
|----------|--------|----------|----------|----------|
| **Charge légère** | 100 | 1 000 | **53 975 req/sec** | 6 MB |
| **Charge moyenne** | 500 | 5 000 | **54 135 req/sec** | 6 MB |
| **Charge lourde** | 1 000 | 10 000 | **54 891 req/sec** | 6 MB |

### Tests de stress

- ✅ **1 160 000 routes** traitées
- ✅ **1,46 GB mémoire** (1,32 KB/route)
- ✅ **200 000 requêtes** en 3,8 sec
- ✅ **0 erreurs** sous charge extrême

📖 Plus : [Analyse de performance](../ru/PERFORMANCE_ANALYSIS.md)

---

## 🔒 Sécurité

### Mécanismes de protection intégrés

CloudCastle HTTP Router inclut **12+ couches de sécurité** :

✅ **Rate Limiting** - prévention DDoS  
✅ **Système Auto-Ban** - blocage automatique  
✅ **Filtrage IP** - whitelist/blacklist avec CIDR  
✅ **Enforcement HTTPS** - forcer l'utilisation HTTPS  
✅ **Protection Path Traversal** - protection contre ../../../  
✅ **Protection SQL Injection** - validation des paramètres  
✅ **Protection XSS** - échappement  
✅ **Protection ReDoS** - protection regex DoS  
✅ **Protection Method Override** - protection contre le spoofing de méthodes  
✅ **Protection Cache Injection** - cache sécurisé  
✅ **Protection IP Spoofing** - validation X-Forwarded-For  
✅ **Restrictions de protocole** - HTTP/HTTPS/WS/WSS

### Tests de sécurité

**13/13 tests OWASP Top 10 réussis** ✅

```
✓ Protection Path Traversal
✓ Protection SQL Injection
✓ Protection XSS
✓ Rate Limiting (A07:2021)
✓ Filtrage IP & Spoofing
✓ Attaques Method Override
✓ Cache Injection
✓ Protection ReDoS
✓ Sécurité Unicode
✓ Épuisement des ressources
✓ Enforcement HTTPS
✓ Restrictions Domain/Port
✓ Système Auto-Ban
```

📖 Plus : [Rapport de sécurité](../ru/SECURITY_REPORT.md)

---

## 🧩 Fonctionnalités avancées

### Système de plugins

```php
use CloudCastle\Http\Router\Contracts\PluginInterface;

class LoggerPlugin implements PluginInterface {
    public function beforeDispatch(Route $route, string $uri, string $method): void {
        log("Requête : $method $uri");
    }
    
    public function afterDispatch(Route $route, mixed $result): mixed {
        log("Réponse générée");
        return $result;
    }
    
    public function onRouteRegistered(Route $route): void {
        log("Route enregistrée : {$route->getUri()}");
    }
    
    public function onException(Route $route, \Exception $e): void {
        log("Erreur : " . $e->getMessage());
    }
}

Route::registerPlugin(new LoggerPlugin());
```

### Chargeurs de routes (5 types)

```php
use CloudCastle\Http\Router\Loader\*;

// JSON
$loader = new JsonLoader($router);
$loader->load('routes.json');

// YAML
$loader = new YamlLoader($router);
$loader->load('routes.yaml');

// XML
$loader = new XmlLoader($router);
$loader->load('routes.xml');

// Attributs PHP
$loader = new AttributeLoader($router);
$loader->loadFromDirectory('app/Controllers');

// Fichiers PHP
require 'routes/web.php';
require 'routes/api.php';
```

### Expression Language

```php
Route::get('/admin', $action)
    ->condition('request.ip == "192.168.1.1" and request.time > 9');

Route::get('/api/data', $action)
    ->condition('request.header["X-API-Key"] == "secret"');
```

### Cache des routes

```php
// Activer le cache
$router->enableCache('var/cache/routes');

// Compiler
$router->compile();

// Auto-chargement depuis le cache
if ($router->loadFromCache()) {
    // Cache chargé - démarrage instantané
} else {
    // Enregistrer les routes
    require 'routes/web.php';
    $router->compile();
}

// Effacer
$router->clearCache();
```

### Support PSR

```php
// PSR-7
use Psr\Http\Message\ServerRequestInterface;
$request = ServerRequestFactory::fromGlobals();

// PSR-15
use CloudCastle\Http\Router\Psr15\Psr15MiddlewareAdapter;
$psrMiddleware = new Psr15MiddlewareAdapter($router);
```

---

## 📚 Documentation

### Documentation principale

- 📖 [Guide utilisateur](USER_GUIDE.md) - Guide complet de toutes les fonctionnalités
- 🔍 [Référence API](API_REFERENCE.md) - Documentation API détaillée
- 💡 [Exemples](../../examples/) - 20+ exemples prêts à l'emploi
- ❓ [FAQ](FAQ.md) - Questions fréquemment posées
- 🎯 [Liste des fonctionnalités](../../FEATURES_LIST.md) - Toutes les 209+ fonctionnalités

### Rapports et analyses

- 📊 [Résumé des tests](../ru/SUMMARY.md)
- 🧪 [Tests détaillés](../ru/TESTS_DETAILED.md)
- ⚡ [Analyse de performance](PERFORMANCE_ANALYSIS.md)
- 🔒 [Rapport de sécurité](SECURITY_REPORT.md)
- ⚖️ [Comparaison avec les alternatives](COMPARISON.md)

---

## 🧪 Qualité du code

### Statistiques des tests

```
Total tests :     501
Réussis :          501 ✅
Échoués :          0
Couverture :       ~95%
Assertions :       1 200+
```

### Analyse statique

- **PHPStan :** Niveau MAX - 0 erreurs critiques ✅
- **PHPMD :** 0 problèmes ✅
- **PHPCS :** PSR-12 - 0 violations ✅
- **PHP-CS-Fixer :** 0 fichiers nécessitent des corrections ✅
- **Rector :** 0 changements requis ✅

### Exécution des tests

```bash
# Tous les tests
composer test

# Par catégorie
composer test:unit          # Tests unitaires
composer test:security      # Tests de sécurité
composer test:performance   # Tests de performance
composer test:load          # Tests de charge
composer test:stress        # Tests de stress

# Analyse statique
composer phpstan            # PHPStan
composer phpcs              # PHP_CodeSniffer
composer phpmd              # PHP Mess Detector
composer analyse            # Tous les analyseurs

# Benchmarks
composer benchmark          # PHPBench
```

---

## ⚖️ Comparaison avec les alternatives

| Caractéristique | CloudCastle | Laravel | Symfony | FastRoute | Slim |
|-----------------|-------------|---------|---------|-----------|------|
| **Performance** | **54k req/sec** | 35k | 40k | 60k | 45k |
| **Mémoire (1k routes)** | **6 MB** | 12 MB | 10 MB | 4 MB | 5 MB |
| **Fonctionnalités** | **209+** | 150+ | 180+ | 20+ | 50+ |
| **Rate Limiting** | ✅ Intégré | ✅ | ❌ | ❌ | ⚠️ Package |
| **Auto-Ban** | ✅ | ❌ | ❌ | ❌ | ❌ |
| **Filtrage IP** | ✅ Intégré | ⚠️ Middleware | ❌ | ❌ | ⚠️ Middleware |
| **Expression Lang** | ✅ | ❌ | ⚠️ Limité | ❌ | ❌ |
| **Plugins** | ✅ 4 intégrés | ✅ | ⚠️ Events | ❌ | ❌ |
| **Chargeurs** | ✅ 5 types | ⚠️ PHP seulement | ⚠️ XML/YAML | ❌ | ❌ |
| **Macros** | ✅ 7 macros | ✅ | ❌ | ❌ | ❌ |
| **Raccourcis** | ✅ 14 méthodes | ⚠️ Quelques-uns | ❌ | ❌ | ❌ |
| **Helpers** | ✅ 18 fonctions | ✅ 10+ | ⚠️ Peu | ❌ | ⚠️ Peu |
| **PSR-15** | ✅ | ✅ | ✅ | ❌ | ✅ |
| **Standalone** | ✅ | ❌ Framework | ⚠️ Complexe | ✅ | ✅ |
| **Tests** | **501** | 300+ | 500+ | 100+ | 200+ |
| **Couverture** | **95%+** | 90%+ | 95%+ | 80%+ | 85%+ |

### Conclusion

**CloudCastle HTTP Router** - équilibre optimal entre **performance**, **fonctionnalité** et **sécurité**. 

✅ **Meilleur choix pour :**
- Serveurs API avec exigences de sécurité élevées
- Architecture microservices
- Systèmes haute charge (50k+ req/sec)
- Projets nécessitant un contrôle maximal du routage

📖 Plus : [Comparaison avec les alternatives](COMPARISON.md)

---

## 🤝 Contribution

Nous accueillons les contributions au développement de CloudCastle HTTP Router !

### Comment aider

1. ⭐ Mettez une étoile au projet
2. 🐛 Signalez les bugs
3. 💡 Proposez de nouvelles fonctionnalités
4. 📝 Améliorez la documentation
5. 🔧 Soumettez des Pull Requests

### Processus

```bash
# 1. Fork du projet
git clone https://github.com/YOUR_USERNAME/cloud-casstle-http-router.git

# 2. Créer une branche feature
git checkout -b feature/AmazingFeature

# 3. Commiter les changements
git commit -m 'Add some AmazingFeature'

# 4. Push vers la branche
git push origin feature/AmazingFeature

# 5. Ouvrir une Pull Request
```

### Exigences

- ✅ Suivre PSR-12
- ✅ Écrire des tests (PHPUnit)
- ✅ Mettre à jour la documentation
- ✅ Vérifier PHPStan/PHPCS
- ✅ Un PR = une fonctionnalité

📖 Plus : [CONTRIBUTING.md](../../CONTRIBUTING.md)

---

## 📄 Licence

Ce projet est sous licence **MIT**. Voir [LICENSE](../../LICENSE) pour les détails.

```
MIT License

Copyright (c) 2024 CloudCastle

Permission is hereby granted, free of charge, to any person obtaining a copy...
```

---

## 💬 Support

### Contacts

- 📧 **Email :** zorinalexey59292@gmail.com
- 💬 **Telegram :** [@CloudCastle85](https://t.me/CloudCastle85)
- 📢 **Canal Telegram :** [@cloud_castle_news](https://t.me/cloud_castle_news)
- 🐛 **GitHub Issues :** [Signaler un problème](https://github.com/zorinalexey/cloud-casstle-http-router/issues)
- 💡 **GitHub Discussions :** [Discussions](https://github.com/zorinalexey/cloud-casstle-http-router/discussions)

### Liens utiles

- [📚 Documentation](../ru/)
- [💡 Exemples d'utilisation](../../examples/)
- [📋 Changelog](../../CHANGELOG.md)
- [🗺️ Roadmap](../../ROADMAP.md)
- [🔒 Politique de sécurité](../../SECURITY.md)
- [📜 Code de conduite](../../CODE_OF_CONDUCT.md)
- [🤝 Contributeurs](../../CONTRIBUTORS.md)

---

## 🌟 Remerciements

Un grand merci à tous les [contributeurs](../../CONTRIBUTORS.md) pour leur contribution au projet !

### Technologies utilisées

- [PHPUnit](https://phpunit.de/) - Tests
- [PHPStan](https://phpstan.org/) - Analyse statique
- [PHPCS](https://github.com/squizlabs/PHP_CodeSniffer) - Style de code
- [PHPBench](https://phpbench.readthedocs.io/) - Benchmarks
- [Rector](https://getrector.org/) - Refactoring

---

## 📈 Statistiques du projet

![GitHub Stars](https://img.shields.io/github/stars/zorinalexey/cloud-casstle-http-router?style=social)
![GitHub Forks](https://img.shields.io/github/forks/zorinalexey/cloud-casstle-http-router?style=social)
![GitHub Watchers](https://img.shields.io/github/watchers/zorinalexey/cloud-casstle-http-router?style=social)

![GitHub Issues](https://img.shields.io/github/issues/zorinalexey/cloud-casstle-http-router)
![GitHub Pull Requests](https://img.shields.io/github/issues-pr/zorinalexey/cloud-casstle-http-router)
![GitHub Last Commit](https://img.shields.io/github/last-commit/zorinalexey/cloud-casstle-http-router)

---

**Made with ❤️ by [CloudCastle](https://github.com/zorinalexey)**

---

[⬆ Retour en haut](#cloudcastle-http-router)
