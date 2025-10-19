[🇷🇺 Русский](ru/README.md) | [🇺🇸 English](en/README.md) | [🇩🇪 Deutsch](de/README.md) | [🇫🇷 Français](fr/README.md) | [🇨🇳 中文](zh/README.md)

[📚 Table of Contents](fr/_table-of-contents.md) | [🏠 Home](fr/README.md)

---

# Documentation du routeur HTTP CloudCastle

**Langues :** 🇷🇺 Russe | [🇫🇷 Anglais](../en/README.md) | [🇩🇪 Allemand](../de/README.md) | [🇫🇷 Français](../fr/README.md) | [🇨🇳中文](../zh/README.md)

[📚 Table des matières](_table-of-contents.md) | [🏠 Accueil](LISEZMOI.md)

---

Bienvenue dans la documentation de CloudCastle HTTP Router - un routeur moderne, rapide et sécurisé pour PHP 8.2+.

## 📚 Contenu

### Commencer

- [Page principale](../../README.md) - démarrage rapide et informations de base
- [Mise en route](getting-started.md) - guide pour les débutants
- [Bonnes pratiques](best-practices.md) - meilleures pratiques de développement

### Tests

- [Résumé de tous les tests](test-summary.md) - résultats de tous les tests et benchmarks
-[Tests unitaires](unit-tests.md) - résultats détaillés de 419 tests
- [Tests de sécurité](security-tests.md) - analyse de 13 contrôles de sécurité
- [Tests de performances](performance-tests.md) - benchmarks de performances
- [Tests de charge](load-tests.md) - tests de charge (50 000+ req/sec)
-[Stress tests](stress-tests.md) - conditions extrêmes (1M+ itinéraires)

### Possibilités

- [Toutes les fonctionnalités] (features.md) - liste complète de plus de 30 fonctionnalités
- [Auto-Naming](auto-naming.md) - dénomination automatique des itinéraires (une fonctionnalité unique !)
- [Raccourcis d'itinéraire] (shortcuts.md) - Plus de 13 raccourcis pour une configuration rapide
- [Macros d'itinéraire] (macros.md) - 7+ macros (réduction de code de 80 à 97 %)
- [Fonctions d'assistance](helpers.md) - 15+ fonctions globales
- [ThrottleWithBan](throttle-with-ban.md) - limitation de débit + interdiction automatique (fonctionnalité unique !)
- [Système de balises](tags.md) - système de balises pour filtrer les itinéraires
- [Route Loaders](loaders.md) - Configuration YAML/XML/JSON/Attributs
- [Middleware](middleware.md) - middleware et système PSR-15
- [Facade](facade.md) - utilisation statique (style Laravel)
- [Qualité du code] (code-quality.md) - Rapports PHPStan, PHPMD, PHPCS

### Comparaison

- [Comparaison détaillée avec les concurrents](comparison-detailed.md) - analyse complète de 6 routeurs

## 🎯 À propos du projet

CloudCastle HTTP Router est un routeur hautes performances doté d'un ensemble unique de fonctionnalités de sécurité et d'une flexibilité de configuration.

### Indicateurs clés

- **Performances** : 50 946 requêtes/s (moyenne)
- **Évolutivité** : plus de 1 095 000 itinéraires
- **Sécurité** : 13 mécanismes de sécurité
- **Tests** : 447 tests, 1043+ assertions
- **Couverture** : taux de réussite de 100 %

## 📊 Résultats des tests

### Performance

| Catégorie | Résultat | Statut |
|:---|:---:|:---:|
| Light Load | 52,488 req/sec | ✅ |
| Medium Load | 45,260 req/sec | ✅ |
| Heavy Load | 55,089 req/sec | ✅ |
| Concurrent Access | 8,316 req/sec | ✅ |

### Évolutivité

| Paramètre | Signification |
|:---|:---:|
| Itinéraires maximum | 1 095 000 |
| Mémoire/itinéraire | 1,39 Ko |
| Profondeur de nidification | 50 niveaux |
| Longueur de l'URI | 1 980 caractères |

### Sécurité

✅ Les 13 tests de sécurité ont été réussis :
- Path Traversal Protection
- SQL Injection Prevention
- XSS Protection
- IP Whitelist/Blacklist
- IP Spoofing Protection
- Domain Security
- ReDoS Protection
- Method Override Protection
- Mass Assignment Protection
- Cache Injection Prevention
- Resource Exhaustion Prevention
- Unicode Security

## 🆚 Comparaison avec les concurrents

### Performances (requêtes/s)

1. **CloudCastle** - 50,946 🥇
2. FastRoute - 47,033 🥈
3. AltoRouter - 39,967 🥉
4. Slim - 37,167
5. Laravel - 16,233
6. Symfony - 15,633

### Fonctionnalité (nombre de fonctionnalités)

1. **CloudCastle** - 25/25 (100%) 🥇
2. Symfony - 10/25 (40%) 🥈
3. Laravel - 9/25 (36%) 🥉
4. Slim - 7/25 (28%)
5. AltoRouter - 4/25 (16%)
6. FastRoute - 3/25 (12%)

### Évolutivité (itinéraires maximum)

1. **CloudCastle** - 1,095,000 🥇
2. FastRoute - 500,000 🥈
3. Slim - 200,000 🥉
4. AltoRouter - 150,000
5. Symfony - 100,000
6. Laravel - 80,000

## 🚀 Démarrage rapide

###Installation

```bash
composer require cloud-castle/http-router
```

### Utilisation de base

```php
use CloudCastle\Http\Router\Router;

$router = new Router();

$router->get('/', function() {
    return 'Hello, World!';
});

$router->get('/users/{id}', function($id) {
    return "User: {$id}";
});

$result = $router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
```

###Fonctionnalités avancées

```php
// Middleware
$router->get('/admin', 'AdminController@index')
    ->middleware(['auth', 'admin']);

// Rate Limiting
$router->get('/api/data', 'ApiController@data')
    ->perMinute(60);

// Conditions
$router->get('/premium', 'PremiumController@index')
    ->condition('user.subscription == "premium"');

// Groups
$router->group(['prefix' => '/api/v1'], function($router) {
    $router->get('/users', 'UserController@index');
    $router->get('/posts', 'PostController@index');
});
```

## 💡 Recommandations

### Quand utiliser CloudCastle

✅ **Idéal pour :**
- Services API à charge élevée
- Architecture de microservices
- Projets avec des exigences de sécurité
-Applications d'entreprise
- Systèmes multi-locataires

✅ **Avantages :**
- Performances maximales
- Meilleure évolutivité
- Sécurité complète
- Fonctionnalité riche
- Code moderne (PHP 8.2+)

### Best Practices

1. **Utiliser la mise en cache** en production
2. **Regrouper les itinéraires** par fonctionnalité
3. **Utilisez des routes nommées** pour la génération d'URL
4. **Utiliser la limitation de débit** pour les API publiques
5. **Personnalisez YAML/XML/JSON** pour les grandes configurations

## 📖 Ressources supplémentaires

###Documentations

- [Résumé du test](test-summary.md) - résultats détaillés de tous les tests
- [Comparaison des routeurs](comparison-detailed.md) - analyse complète des alternatives

### Exemples

Des exemples d'utilisation se trouvent dans le répertoire `examples/` :
- `basic-usage.php` - routage de base
- `yaml-routes.yaml` - Configuration YAML
- `xml-routes.xml` - Configuration XML
- `json-routes.json` - Configuration JSON ⭐
- `attributes-usage.php` - PHP 8 Attributes
- `middleware-advanced.php` - middleware avancé
- `expression-usage.php` - Expression Language

### Rapports

Résultats des tests dans le répertoire `reports/` :
- `phpunit.txt` - Résultats PHPUnit
- `security-tests.txt` - tests de sécurité
- `performance-tests.txt` - benchmarks
- `load-tests.txt` - tests de charge
- `stress-tests.txt` - tests de résistance
- `phpstan.txt` - analyse statique
- `phpcs.txt` - code style
- `phpmd.txt` - code quality

## 🤝 Assistance

- **Issues**: [GitHub Issues](https://github.com/zorinalexey/cloud-casstle-http-router/issues)
- **Email**: zorinalexey59292@gmail.com
- **Telegram**: [@CloudCastle85](https://t.me/CloudCastle85)

## 📄 Licence

Licence MIT - voir le fichier [LICENSE](../../LICENSE).

---

**Routeur HTTP CloudCastle** - Performances maximales. Sécurité totale. Fonctionnalité la plus riche.

*Dernière mise à jour : 18 octobre 2025*

---

[📚 Table des matières](_table-of-contents.md) | [🏠 Accueil](LISEZMOI.md)

---

[📚 Table of Contents](fr/_table-of-contents.md) | [🏠 Home](fr/README.md)
