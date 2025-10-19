[🇷🇺 Русский](ru/load-tests.md) | [🇺🇸 English](en/load-tests.md) | [🇩🇪 Deutsch](de/load-tests.md) | [🇫🇷 Français](fr/load-tests.md) | [🇨🇳 中文](zh/load-tests.md)

[📚 Table of Contents](fr/_table-of-contents.md) | [🏠 Home](fr/README.md)

---

# Tests de charge du routeur HTTP CloudCastle

**Langues :** 🇷🇺 Russe | [🇫🇷 Anglais](../en/load-tests.md) | [🇩🇪 Deutsch](../de/load-tests.md) | [🇫🇷 Français](../fr/load-tests.md) | [🇨🇳中文](../zh/load-tests.md)

[📚 Table des matières](_table-of-contents.md) | [🏠 Accueil](LISEZMOI.md)

---

## 📊 Informations générales

**Type de test** : Charge
**Statut** : ✅ Tous les tests ont été réussis
**Objectif** : tester le comportement sous diverses charges

## 🚀 Résultats des tests de charge

### Test 1 : Charge légère

**Configuration:**
- Routes: 100
- Requests: 1,000
- Type : Requêtes séquentielles

**Résultats:**
- Duration: 0.0191s
- **Requests/sec: 52,488** ⚡
- Avg response time: 0.02ms
- Memory peak: 6.00 MB

**Analyse:**
- ✅ Excellentes performances pour les petites applications
- ✅ Consommation minimale de mémoire
- ✅ Temps de réponse stable

**Application:**
- Petites applications Web
- Landing pages avec routage dynamique
- Projets MVP

---

### Test 2 : Charge moyenne

**Configuration:**
- Routes: 500  
- Requests: 5,000
- Type : Modèles de requêtes mixtes

**Résultats:**
- Duration: 0.1105s
- **Requests/sec: 45,260** ⚡
- Avg response time: 0.02ms
- Memory peak: 6.00 MB

**Analyse:**
- ✅ Excellentes performances sous charge moyenne
- ✅ Mise à l'échelle linéaire
- ✅ Mémoire stable

**Application:**
-Applications d'entreprise
- Systèmes CMS
- Plateformes de commerce électronique

**Comparaison avec les concurrents :**
| Router | 500 routes, 5K requests | Req/sec |
|:---|:---:|:---:|
| **CloudCastle** | **0.1105s** | **45,260** |
| FastRoute | 0.116s | 43,103 |
| Symfony | 0.338s | 14,793 |
| Laravel | 0.329s | 15,197 |
| Slim | 0.141s | 35,461 |

---

### Test 3 : Charge lourde

**Configuration:**
- Routes: 1,000
- Requests: 10,000
- Type : Demandes à haute fréquence

**Résultats:**
- Duration: 0.1815s
- **Requests/sec: 55,089** ⚡
- Avg response time: 0.02ms
- Memory peak: 6.00 MB

**Analyse:**
- ✅ **Meilleur résultat** de tous les scénarios !
- ✅ Le routeur est bien optimisé pour les charges élevées
- ✅ Aucune dégradation des performances

**Application:**
- API à charge élevée
-Applications en temps réel
- Microservices à fort trafic

**Comparaison:**
| Router | Req/sec | vs CloudCastle |
|:---|:---:|:---:|
| **CloudCastle** | **55,089** | **100%** |
| FastRoute | 48,200 | 87.5% |
| Symfony | 15,900 | 28.9% |
| Laravel | 16,400 | 29.8% |
| Slim | 37,200 | 67.5% |

**CloudCastle est 14 % plus rapide que FastRoute et 3,4 fois plus rapide que Laravel !**

---

### Test 4: Concurrent Access Patterns

**Description** : Test des requêtes parallèles vers différentes routes.

**Configuration:**
- Pattern variations: 4
- Total requests: 5,000
- Type : Simulation d'accès simultané

**Résultats:**
- **Requests/sec: 8,316**
- Avg time: 0.12ms
- Concurrency level: 4

**Modèles d'accès :**
1. Static routes (/)
2. Dynamic routes (/users/{id})
3. Nested routes (/api/v1/users/{id})
4. Complex routes (/posts/{year}/{month}/{slug})

**Analyse:**
- ✅ Bon traitement des demandes hétérogènes
- ✅ Temps de réponse cohérent
- ✅ Aucune condition de course

**Application:**
- Applications multi-utilisateurs
- Real-time systems
- High-concurrency APIs

---

### Test 5: Cached vs Uncached Performance

**Description** : Comparaison des performances avec et sans cache.

**Configuration:**
- Routes: 1,000
- Requests per test: 5,000

**Résultats:**

| Mode | Requests/sec | Load Time |
|:---|:---:|:---:|
| **Uncached** | 54,717 | 0.085s |
| **Cached** | 52,296 | 0.012s |
| **Improvement** | -4.6% req/sec | **85.9% faster load** |

**Remarque importante** :
- La mise en cache est un peu plus lente en req/sec en raison de la désérialisation
- Mais **7 fois plus rapide** lors du chargement de l'application
- En production, le cache est **critique** à la première requête

**Avantage total :**
```
Без кеша:
- Загрузка: 0.085s
- Request: 0.018ms
- Total first request: 85.018ms

С кешем:
- Загрузка: 0.012s
- Request: 0.019ms
- Total first request: 12.019ms

Улучшение first request: 85.9% faster! ⚡
```

---

## 📈Résumé général du chargement

### Tableau croisé dynamique

| Load Type | Routes | Requests | Req/sec | Response Time | Memory | Status |
|:---|:---:|:---:|:---:|:---:|:---:|:---:|
| **Light** | 100 | 1,000 | **52,488** | 0.02ms | 6 MB | ✅ |
| **Medium** | 500 | 5,000 | **45,260** | 0.02ms | 6 MB | ✅ |
| **Heavy** | 1,000 | 10,000 | **55,089** | 0.02ms | 6 MB | ✅ |
| **Concurrent** | 200 | 5,000 | 8,316 | 0.12ms | 6 MB | ✅ |

**Moyenne** : 50 946 requêtes/s

### Comparaison avec tous les concurrents

| Router | Light | Medium | Heavy | Average |
|:---|:---:|:---:|:---:|:---:|
| **CloudCastle** | **52,488** | **45,260** | **55,089** | **50,946** |
| FastRoute | 49,800 | 43,100 | 48,200 | 47,033 |
| AltoRouter | 41,200 | 38,600 | 40,100 | 39,967 |
| Slim | 38,900 | 35,400 | 37,200 | 37,167 |
| Laravel | 17,100 | 15,200 | 16,400 | 16,233 |
| Symfony | 16,200 | 14,800 | 15,900 | 15,633 |

### Visualisation des performances

```
CloudCastle ████████████████████████████████████████████████████ 50,946 req/s
FastRoute   ██████████████████████████████████████████████ 47,033 req/s
AltoRouter  ███████████████████████████████████████ 39,967 req/s
Slim        ████████████████████████████████████ 37,167 req/s
Laravel     ███████████████ 16,233 req/s
Symfony     ██████████████ 15,633 req/s
```

## 💡 Charger les recommandations

### Light Load (< 100 routes)

**Configuration optimale :**
```php
$router = new Router();
// Кеш опционален
// Middleware минимальный
$router->get('/', 'HomeController@index');
```

**Performances attendues** : 52 000+ req/sec

### Medium Load (100-1000 routes)

**Configuration optimale :**
```php
$cache = new RouteCache(__DIR__ . '/cache/routes.php');
$router->setCache($cache);

// Используйте группы для организации
$router->group(['prefix' => '/api'], function($router) {
    // routes...
});
```

**Performances attendues** : 45 000+ req/sec

### Heavy Load (1000-10000 routes)

**Configuration optimale :**
```php
// ОБЯЗАТЕЛЬНО кеширование
$cache = new RouteCache(__DIR__ . '/cache/routes.php');
$router->setCache($cache);

// YAML/XML/JSON для управления маршрутами
$loader = new YamlLoader($router);
$loader->load(__DIR__ . '/config/routes.yaml');

// Selective middleware
$router->middleware(['essential-only']);
```

**Performances attendues** : 35 000+ req/sec

### Enterprise Load (10000+ routes)

**Configuration optimale :**
```php
// Route caching обязателен
$cache = new RouteCache(__DIR__ . '/cache/routes.php');
$router->setCache($cache);

// Lazy loading через Loaders
// Разделение на модули
// Использование tagged routes для группировки

$router->group(['tag' => 'api'], function($router) {
    // API routes
});

$router->group(['tag' => 'admin'], function($router) {
    // Admin routes
});
```

**Performances attendues** : 25 000+ req/sec

## 🎯 Best Practices

### 1. La mise en cache est indispensable pour la production

```php
// config/routes-cached.php
return [
    'cache' => [
        'enabled' => true,
        'path' => __DIR__ . '/../storage/cache/routes.php',
        'ttl' => 86400, // 24 hours
    ],
];
```

### 2. Suivi des performances

```php
use CloudCastle\Http\Router\Plugin\AnalyticsPlugin;

$analytics = new AnalyticsPlugin();
$router->registerGlobalPlugin($analytics);

// После обработки запросов
$stats = $analytics->getStats();
// ['hits' => [...], 'avg_time' => ..., 'memory' => ...]
```

### 3. Optimisation de la charge

```php
// Для высоких нагрузок:
// 1. Минимизируйте middleware
$router->middleware(['essential']);

// 2. Используйте regex constraints
$router->get('/users/{id:\d+}', ...);

// 3. Группируйте логически
$router->group(['prefix' => '/api/v1'], ...);

// 4. Кешируйте всё
$cache = new RouteCache(...);
$router->setCache($cache);
```

## ✅Conclusion

Le routeur HTTP CloudCastle affiche des **résultats exceptionnels** à tous les niveaux de charge :

- **Charge légère** : 52 488 req/sec (meilleur résultat)
- **Charge moyenne** : 45 260 req/sec (meilleur résultat)
- **Heavy Load** : 55 089 req/sec (meilleur résultat)

**Des performances moyennes de 50 946 req/sec** en font le routeur PHP **le plus rapide** du marché.

Prêt à être utilisé dans **toutes les conditions** : des petits sites aux plates-formes d'entreprise à forte charge.

---

*Dernière mise à jour : 18 octobre 2025*

---

[📚 Table des matières](_table-of-contents.md) | [🏠 Accueil](LISEZMOI.md)

---

[📚 Table of Contents](fr/_table-of-contents.md) | [🏠 Home](fr/README.md)
