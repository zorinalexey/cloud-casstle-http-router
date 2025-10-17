# Introduction à HttpRouter

**CloudCastle HttpRouter** est une bibliothèque de routage moderne et performante pour PHP 8.2+ conçue en mettant l'accent sur la sécurité, les performances et l'expérience développeur.

## 📊 Statistiques

- **308 tests** / **748 assertions** ✅
- **Couverture de code:** >95%
- **PHPStan:** Niveau Max (3 avertissements non critiques)
- **PHPCS:** Conforme PSR-12
- **PHPMD:** Aucun problème critique
- **Versions PHP:** 8.2, 8.3, 8.4

## ✨ Fonctionnalités Principales

### 🔐 Sécurité
- **Rate Limiting** — Protection DDoS avec limites flexibles
- **Système Auto-Ban** — Blocage automatique des acteurs malveillants
- **Application des protocoles** — Usage forcé de HTTPS/WSS
- **Protection Path Traversal** — Défense contre le Directory Traversal
- **Protection Mass Assignment** — Protection contre l'affectation de paramètres non désirés
- **OWASP Top 10** — Conformité complète avec les recommandations de sécurité

### ⚡ Performance
- **Cache de Routes** — Mise en cache des routes pour un accès instantané
- **Lazy Loading** — Chargement différé des middleware
- **Matching Optimisé** — Algorithme de correspondance optimisé
- **Efficacité Mémoire** — Utilisation efficace de la mémoire (30MB pour 308 tests)
- **Dispatch Rapide** — ~0.001ms par dispatch avec cache

### 🎯 Expérience Développeur
- **API Fluide** — Interface chainable expressive
- **Groupes de Routes** — Regroupement de routes avec paramètres partagés
- **Routes Nommées** — Routes nommées pour génération d'URL pratique
- **Support Middleware** — Support complet des middleware
- **Système de Tags** — Système de tags pour l'organisation des routes
- **Facade Statique** — Facade statique `Route::` pour accès rapide

### 🛠️ Extensibilité
- **Méthodes Personnalisées** — Support pour méthodes HTTP personnalisées
- **Support WebSocket** — Support complet WebSocket (WS/WSS)
- **Chaînes de Middleware** — Chaînes de middleware avec priorités
- **Système d'Événements** — Système d'événements pour hooks
- **Injection de Dépendances** — Intégration avec conteneurs DI

## ⚖️ Avantages et Inconvénients

### ✅ Avantages

1. **Sécurité complète prête à l'emploi**
   - Rate limiting et auto-ban intégrés nativement
   - Protection contre tous les vecteurs d'attaque majeurs (OWASP Top 10)
   - Application des protocoles pour connexions sécurisées

2. **Haute performance**
   - Système avancé de mise en cache des routes
   - Algorithmes de correspondance optimisés
   - Consommation mémoire minimale

3. **PHP Moderne**
   - Support complet des fonctionnalités PHP 8.2+
   - Typage strict
   - Types de retour et arguments nommés

4. **Fonctionnalités riches**
   - Support WebSocket (rare pour les routeurs PHP)
   - Système de tags pour l'organisation
   - Unités de temps flexibles (secondes, minutes, heures, jours)

5. **Documentation excellente**
   - Exemples d'utilisation détaillés
   - Documentation en 4 langues
   - Rapports de tests détaillés

6. **100% de couverture de tests**
   - 308 tests unitaires, d'intégration et fonctionnels
   - Tests de sécurité (OWASP)
   - Tests de performance
   - Tests de charge et de stress

### ⚠️ Limitations

1. **Nécessite PHP 8.2+**
   - Ne fonctionne pas sur les anciennes versions PHP
   - Nécessite un hébergement moderne

2. **Bibliothèque jeune**
   - Moins de cas d'utilisation en production par rapport aux concurrents
   - Moins de plugins communautaires

3. **Complexité pour tâches simples**
   - Overkill pour projets simples
   - Peut être excessif pour landing pages

4. **Caractéristiques architecturales**
   - Utilise une Facade statique (tout le monde n'aime pas ça)
   - Accès Superglobals ($_SERVER) — justifié pour routeur HTTP
   - Complexité cyclomatique élevée dans Router.php — due à l'API riche

## 🔄 Comparaison avec les Concurrents

| Fonctionnalité | HttpRouter | Symfony | Laravel | FastRoute | Slim |
|----------------|-----------|---------|---------|-----------|------|
| **Version PHP** | 8.2+ | 8.1+ | 8.2+ | 7.1+ | 8.1+ |
| **Rate Limiting** | ✅ Intégré | ⚠️ Bundle | ✅ Intégré | ❌ | ❌ |
| **Auto-Ban** | ✅ Intégré | ❌ | ❌ | ❌ | ❌ |
| **WebSocket** | ✅ WS/WSS | ❌ | ⚠️ Echo | ❌ | ❌ |
| **Cache Routes** | ✅ | ✅ | ✅ | ⚠️ Manuel | ❌ |
| **Middleware** | ✅ | ✅ | ✅ | ❌ | ✅ |
| **Routes Nommées** | ✅ | ✅ | ✅ | ✅ | ✅ |
| **Groupes Routes** | ✅ | ✅ | ✅ | ✅ | ✅ |
| **Application Protocole** | ✅ HTTPS/WSS | ❌ | ❌ | ❌ | ❌ |
| **Système Tags** | ✅ | ❌ | ❌ | ❌ | ❌ |
| **Facade Statique** | ✅ Route:: | ❌ | ✅ Route:: | ❌ | ❌ |
| **Performance** | ⚡⚡⚡ | ⚡⚡ | ⚡⚡ | ⚡⚡⚡ | ⚡⚡⚡ |
| **Taille** | Petite | Grande | Grande | Minuscule | Moyenne |
| **Dépendances** | Minimales | Nombreuses | Nombreuses | Aucune | Peu |
| **Documentation** | 4 langues | EN | EN | EN | EN |

## 🎯 Quand Utiliser HttpRouter

### ✅ Parfait pour:

1. **Serveurs API avec exigences de sécurité élevées**
   - Rate limiting intégré
   - Protection auto-ban
   - Application des protocoles

2. **Applications WebSocket**
   - Support natif WS/WSS
   - Routage unifié pour HTTP et WebSocket

3. **Microservices**
   - Léger et rapide
   - Dépendances minimales
   - Performance excellente

4. **Projets PHP modernes**
   - Fonctionnalités PHP 8.2+
   - Typage strict
   - Pratiques modernes

### ⚠️ Non recommandé pour:

1. **Projets legacy sur PHP < 8.2**
2. **Sites statiques simples** (overkill)
3. **Projets nécessitant intégration écosystème Laravel/Symfony**

## 📦 Installation

```bash
composer require cloud-castle/http-router
```

## 🚀 Démarrage Rapide

```php
<?php

use CloudCastle\Http\Router\Route;

// Route simple
Route::get('/users', fn() => ['users' => User::all()]);

// Route avec paramètres
Route::get('/users/{id}', function($id) {
    return User::find($id);
});

// Rate limiting
Route::get('/api/data', fn() => getData())
    ->rateLimit(requests: 100, per: '1 minute');

// Groupe de routes
Route::group('/api/v1', function() {
    Route::get('/users', 'UserController@index');
    Route::post('/users', 'UserController@store');
})->middleware('auth')->rateLimit(1000, '1 hour');

// WebSocket
Route::websocket('/chat', 'ChatController@handle')
    ->protocol('wss'); // Seulement WebSocket sécurisé

// Dispatch
$route = Route::dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
echo $route->call();
```

## 📚 Lectures Complémentaires

- [Démarrage Rapide](quickstart.md)
- [Routes](routes.md)
- [Groupes de Routes](route-groups.md)
- [Middleware](middleware.md)
- [Sécurité](security.md)
- [Rate Limiting](rate-limiting.md)
- [Auto-Ban](auto-ban.md)
- [Performance](performance.md)
- [Référence API](api-reference.md)

## 📊 Rapports

- [Tests Unitaires](../reports/unit-tests.md)
- [Analyse Statique](../reports/static-analysis.md)
- [Benchmarks Performance](../reports/performance.md)
- [Tests de Charge](../reports/load-testing.md)
- [Tests Sécurité](../reports/security.md)
- [Comparaison Concurrents](../reports/comparison.md)

## 🤝 Contribuer

Nous accueillons les contributions à la bibliothèque! Voir [CONTRIBUTING.md](../CONTRIBUTING.md)

## 📄 Licence

Licence MIT. Voir [LICENSE](../../LICENSE)

---

**CloudCastle HttpRouter** — le choix pour applications PHP modernes avec exigences élevées de sécurité et performance.
