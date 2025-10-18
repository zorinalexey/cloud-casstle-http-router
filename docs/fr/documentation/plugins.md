# Système de plugins

**CloudCastle HTTP Router v1.1.1**  
**Date**: Septembre 2025  
**Langue**: Français

---

## 🌍 Traductions

- **[Русский](../../ru/documentation/plugins.md)**
- **[English](../../en/documentation/plugins.md)**
- **[Deutsch](../../de/documentation/plugins.md)**
- **[Français](../../fr/documentation/plugins.md)** (actuel)

---

## Table des matières

1. [Introduction](#introduction)
2. [Concepts de base](#concepts-de-base)
3. [Plugins intégrés](#plugins-intégrés)
4. [Créer des plugins personnalisés](#créer-des-plugins-personnalisés)
5. [Cycle de vie](#cycle-de-vie)
6. [Référence API](#référence-api)
7. [Exemples d'utilisation](#exemples-dutilisation)

---

## Introduction

Le système de plugins de CloudCastle Router fournit un mécanisme puissant pour étendre la fonctionnalité du routeur sans modifier son code source. Les plugins peuvent intercepter les événements du cycle de vie du routage et ajouter une logique personnalisée.

### Avantages

- 🔌 **Extensibilité** - ajouter de nouvelles fonctionnalités sans modifier le code de base
- 🎯 **Modularité** - activer uniquement les plugins nécessaires
- 🚀 **Performance** - les plugins s'exécutent uniquement lorsque nécessaire
- 🔧 **Flexibilité** - créer des plugins personnalisés pour vos besoins
- 📊 **Surveillance** - suivre les performances du routeur en temps réel

---

## Concepts de base

### PluginInterface

Tous les plugins implémentent l'interface `PluginInterface`:

```php
interface PluginInterface
{
    public function getName(): string;
    public function getVersion(): string;
    public function boot(Router $router): void;
    public function isEnabled(): bool;
    public function enable(): void;
    public function disable(): void;
    
    // Hooks de cycle de vie
    public function onRouteRegistered(Route $route): void;
    public function beforeDispatch(Route $route, string $uri, string $method): void;
    public function afterDispatch(Route $route, mixed $result): mixed;
    public function onException(\Exception $exception): void;
}
```

### AbstractPlugin

Classe de base pour créer des plugins:

```php
use CloudCastle\Http\Router\Plugin\AbstractPlugin;

class MonPlugin extends AbstractPlugin
{
    public function getName(): string
    {
        return 'mon_plugin';
    }
    
    // Remplacer uniquement les méthodes nécessaires
    public function beforeDispatch(Route $route, string $uri, string $method): void
    {
        // Votre logique ici
    }
}
```

---

## Plugins intégrés

### LoggerPlugin

Journalisation des événements du routeur.

**Fonctionnalités:**
- Journaliser les enregistrements de routes
- Journaliser les dispatches
- Journaliser les exceptions
- Format de journal personnalisable
- Journalisation sélective des événements

**Exemple:**

```php
use CloudCastle\Http\Router\Plugin\LoggerPlugin;

$logger = new LoggerPlugin('/var/log/router.log');

// Configuration
$logger->setLogRouteRegistration(true);   // Journaliser les enregistrements de routes
$logger->setLogDispatches(true);          // Journaliser les dispatches
$logger->setLogExceptions(true);          // Journaliser les exceptions

$router->registerPlugin($logger);
```

---

### AnalyticsPlugin

Collecte de statistiques et de métriques du routeur.

**Métriques collectées:**
- Nombre de dispatches
- Hits de routes
- Statistiques des méthodes HTTP
- Temps d'exécution des routes
- Nombre d'exceptions
- Route la plus populaire
- Méthode la plus utilisée
- Temps d'exécution moyen

**Exemple:**

```php
use CloudCastle\Http\Router\Plugin\AnalyticsPlugin;

$analytics = new AnalyticsPlugin();
$router->registerPlugin($analytics);

// ... utiliser le routeur ...

// Obtenir les statistiques
$stats = $analytics->getStatistics();

echo "Total des dispatches: {$stats['total_dispatches']}\n";
echo "Route la plus populaire: {$stats['most_popular_route']}\n";
echo "Temps d'exécution moyen: {$stats['average_execution_time']}s\n";

// Réinitialiser les statistiques
$analytics->reset();
```

---

### ResponseCachePlugin

Mise en cache des réponses de routes.

**Fonctionnalités:**
- Mettre en cache toutes les routes ou sélectivement
- TTL configurable (Time To Live)
- Nettoyage automatique des entrées expirées
- Statistiques de cache
- Effacer le cache par route

**Exemple:**

```php
use CloudCastle\Http\Router\Plugin\ResponseCachePlugin;

$cache = new ResponseCachePlugin(
    300,      // TTL de 5 minutes
    false     // Ne pas mettre en cache toutes les routes
);

// Spécifier quelles routes mettre en cache
$cache->setCacheableRoutes(['users.list', 'posts.index', 'api.data']);

$router->registerPlugin($cache);

// Effacer le cache
$cache->clearCache();                  // Effacer tout le cache
$cache->clearRouteCache($route);       // Effacer une route spécifique

// Statistiques
$stats = $cache->getCacheStats();
echo "Total mis en cache: {$stats['total_cached']}\n";
```

---

## Créer des plugins personnalisés

### Plugin minimal

```php
use CloudCastle\Http\Router\Plugin\AbstractPlugin;
use CloudCastle\Http\Router\Route;

class CompteurRequetesPlugin extends AbstractPlugin
{
    private int $count = 0;
    
    public function getName(): string
    {
        return 'compteur_requetes';
    }
    
    public function beforeDispatch(Route $route, string $uri, string $method): void
    {
        $this->count++;
    }
    
    public function getCount(): int
    {
        return $this->count;
    }
}

// Utilisation
$compteur = new CompteurRequetesPlugin();
$router->registerPlugin($compteur);

// Plus tard
echo "Requêtes traitées: " . $compteur->getCount();
```

### Plugin avancé

```php
class MoniteurPerformancePlugin extends AbstractPlugin
{
    private array $timings = [];
    private array $routesLentes = [];
    private float $seuil = 0.5; // 500ms
    
    public function getName(): string
    {
        return 'moniteur_performance';
    }
    
    public function beforeDispatch(Route $route, string $uri, string $method): void
    {
        $key = $this->getRouteKey($route);
        $this->timings[$key] = microtime(true);
    }
    
    public function afterDispatch(Route $route, mixed $result): mixed
    {
        $key = $this->getRouteKey($route);
        
        if (isset($this->timings[$key])) {
            $duree = microtime(true) - $this->timings[$key];
            
            if ($duree > $this->seuil) {
                $this->routesLentes[$key] = [
                    'route' => $route->getName() ?? $route->getUri(),
                    'duree' => $duree,
                    'timestamp' => time(),
                ];
            }
        }
        
        return $result;
    }
    
    public function getRoutesLentes(): array
    {
        return $this->routesLentes;
    }
}
```

---

## Cycle de vie

### Ordre d'exécution des hooks

1. **Enregistrement de route**
   ```
   Router::get() → Plugin::onRouteRegistered()
   ```

2. **Dispatch**
   ```
   Router::dispatch() → Plugin::beforeDispatch()
   ```

3. **Exécution**
   ```
   Router::executeRoute() → Middleware → Action
   ```

4. **Après exécution**
   ```
   Résultat Action → Plugin::afterDispatch() → return
   ```

5. **En cas d'exception**
   ```
   Exception → Plugin::onException() → throw
   ```

---

## Référence API

### Méthodes Router

```php
// Enregistrer un plugin
$router->registerPlugin(PluginInterface $plugin): self

// Désenregistrer un plugin
$router->unregisterPlugin(string $name): self

// Vérifier si un plugin existe
$router->hasPlugin(string $name): bool

// Obtenir un plugin par nom
$router->getPlugin(string $name): ?PluginInterface

// Obtenir tous les plugins
$router->getPlugins(): array
```

### Méthodes Plugin

```php
// Basiques
getName(): string                    // Nom unique du plugin
getVersion(): string                 // Version du plugin
boot(Router $router): void          // Initialisation lors de l'enregistrement
isEnabled(): bool                   // Vérifier si activé
enable(): void                      // Activer le plugin
disable(): void                     // Désactiver le plugin

// Hooks de cycle de vie
onRouteRegistered(Route $route): void
beforeDispatch(Route $route, string $uri, string $method): void
afterDispatch(Route $route, mixed $result): mixed
onException(\Exception $exception): void
```

---

## Exemples d'utilisation

### Utilisation de base

```php
use CloudCastle\Http\Router\Router;
use CloudCastle\Http\Router\Plugin\LoggerPlugin;
use CloudCastle\Http\Router\Plugin\AnalyticsPlugin;

$router = Router::getInstance();

// Enregistrer les plugins
$router->registerPlugin(new LoggerPlugin('/tmp/router.log'));
$router->registerPlugin(new AnalyticsPlugin());

// Définir les routes
$router->get('/users', 'UserController@index')->name('users.index');

// Utiliser le routeur
$route = $router->dispatch('/users', 'GET');
$result = $router->executeRoute($route);

// Obtenir les statistiques
$analytics = $router->getPlugin('analytics');
$stats = $analytics->getStatistics();
```

### Plusieurs plugins

```php
$router
    ->registerPlugin(new LoggerPlugin('/var/log/router.log'))
    ->registerPlugin(new AnalyticsPlugin())
    ->registerPlugin(new ResponseCachePlugin(300))
    ->registerPlugin(new MoniteurPerformancePlugin());

// Tous les plugins fonctionnent en parallèle
```

---

## Voir aussi

- [Référence API](api-reference.md)
- [Middleware](middleware.md)
- [Exemples](../../../examples/)

---

**Créé**: Septembre 2025  
**Dernière mise à jour**: Octobre 2025

