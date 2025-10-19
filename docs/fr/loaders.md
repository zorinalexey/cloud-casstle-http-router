[🇷🇺 Русский](ru/loaders.md) | [🇺🇸 English](en/loaders.md) | [🇩🇪 Deutsch](de/loaders.md) | [🇫🇷 Français](fr/loaders.md) | [🇨🇳 中文](zh/loaders.md)

[📚 Table of Contents](fr/_table-of-contents.md) | [🏠 Home](fr/README.md)

---

# Route Loaders - Systèmes de chargement de routes

**Langues :** 🇷🇺 Russe | [🇫🇷 Anglais](../en/loaders.md) | [🇩🇪 Allemand](../de/loaders.md) | [🇫🇷 Français](../fr/loaders.md) | [🇨🇳中文](../zh/loaders.md)

[📚 Table des matières](_table-of-contents.md) | [🏠 Accueil](LISEZMOI.md)

---

## 📚 Bilan

Le routeur HTTP CloudCastle prend en charge **5 façons** de configurer les routes :
1. PHP (configuration du logiciel)
2. Fichiers YAML
3. Fichiers XML
4. Fichiers JSON
5. PHP 8 Attributes

## 🎯 1. Configuration PHP (Logiciel)

### Avantages
- ✅ Contrôle total
- ✅ IDE autocomplete
- ✅ Type safety
- ✅ Dynamic routing

### Exemples

```php
use CloudCastle\Http\Router\Router;

$router = new Router();

// Простые маршруты
$router->get('/', 'HomeController@index');
$router->post('/users', 'UserController@store');

// С middleware
$router->get('/admin', 'AdminController@index')
    ->middleware(['auth', 'admin']);

// С группами
$router->group(['prefix' => '/api/v1'], function($router) {
    $router->get('/users', 'UserController@index');
    $router->get('/posts', 'PostController@index');
});

// С условиями
$router->get('/premium', 'PremiumController@index')
    ->condition('user.subscription == "premium"')
    ->middleware('auth');

// Динамические маршруты
foreach ($modules as $module) {
    $router->get("/{$module->slug}", "{$module->controller}@index");
}
```

### Quand l'utiliser
- Small to medium projects
- Lorsqu'un routage dynamique est nécessaire
- Quand la sécurité des types est importante
- Pour le prototypage

---

## 📄 2. YAML Configuration

### Avantages
- ✅ Style déclaratif
- ✅ Facile à lire et à modifier
- ✅ Version control friendly
- ✅ Convient aux grands projets

###Installation

```bash
# YAML extension required
pecl install yaml
```

### Structure de base

```yaml
# config/routes.yaml

# Простой маршрут
home:
  path: /
  methods: GET
  controller: HomeController::index

# Маршрут с параметрами
users_show:
  path: /users/{id}
  methods: [GET, POST]
  controller: UserController::show

# С middleware
admin_dashboard:
  path: /admin/dashboard
  methods: GET
  controller: AdminController::dashboard
  middleware: [auth, admin]

# С requirements (regex)
user_profile:
  path: /users/{id}
  methods: GET
  controller: UserController::profile
  requirements:
    id: \d+

# С defaults
blog_page:
  path: /blog/{page}
  methods: GET
  controller: BlogController@index
  defaults:
    page: 1
  requirements:
    page: \d+

# С domain
api_data:
  path: /data
  methods: GET
  controller: ApiController::data
  domain: api.example.com

# С throttle (rate limiting)
api_limited:
  path: /api/limited
  methods: POST
  controller: ApiController::limited
  throttle:
    max: 100
    decay: 60

# Комплексный пример
api_v1_users:
  path: /api/v1/users/{id}
  methods: [GET, POST, PUT, DELETE]
  name: api.v1.users
  controller: Api\V1\UserController::handle
  middleware: [cors, auth, rate-limit]
  domain: api.example.com
  requirements:
    id: \d+
  defaults:
    id: null
  throttle:
    max: 1000
    decay: 60
```

### Chargement

```php
use CloudCastle\Http\Router\Loader\YamlLoader;

$loader = new YamlLoader($router);
$loader->load(__DIR__ . '/config/routes.yaml');
```

### Chargement modulaire

```php
// Загрузка по модулям
$loader = new YamlLoader($router);

$loader->load(__DIR__ . '/config/routes/web.yaml');
$loader->load(__DIR__ . '/config/routes/api.yaml');
$loader->load(__DIR__ . '/config/routes/admin.yaml');
```

### Chargement conditionnel

```php
$loader = new YamlLoader($router);

// В зависимости от окружения
if ($env === 'development') {
    $loader->load(__DIR__ . '/config/routes/dev.yaml');
} else {
    $loader->load(__DIR__ . '/config/routes/prod.yaml');
}

// В зависимости от модуля
if ($app->hasModule('blog')) {
    $loader->load(__DIR__ . '/config/routes/blog.yaml');
}
```

### Quand l'utiliser
- Grands projets (plus de 100 itinéraires)
-Applications d'entreprise
- Lorsque les routes sont éditées par des non-développeurs
- Configuration multi-environnement

---

## 📑 3. XML Configuration

### Avantages
- ✅Format structuré
- ✅ XML validation
- ✅ Prise en charge de l'IDE avec les schémas XSD
- ✅ Convient aux entreprises

### Structure de base

```xml
<?xml version="1.0" encoding="UTF-8"?>
<routes>
    <!-- Простой маршрут -->
    <route path="/" name="home" methods="GET" 
           controller="HomeController::index"/>
    
    <!-- С параметрами -->
    <route path="/users/{id}" name="users.show" methods="GET" 
           controller="UserController::show">
        <requirements>
            <requirement param="id" pattern="\d+"/>
        </requirements>
    </route>
    
    <!-- С middleware -->
    <route path="/admin" name="admin.dashboard" methods="GET"
           controller="AdminController::dashboard">
        <middleware>auth,admin</middleware>
    </route>
    
    <!-- С defaults -->
    <route path="/blog/{page}" name="blog.index" methods="GET"
           controller="BlogController::index">
        <requirements>
            <requirement param="page" pattern="\d+"/>
        </requirements>
        <defaults>
            <default param="page" value="1"/>
        </defaults>
    </route>
    
    <!-- С domain -->
    <route path="/api/data" name="api.data" methods="GET"
           controller="ApiController::data"
           domain="api.example.com"/>
    
    <!-- Комплексный пример -->
    <route path="/api/v1/users/{id}" 
           name="api.v1.users.handle"
           methods="GET,POST,PUT,DELETE"
           controller="Api\V1\UserController::handle"
           domain="api.example.com">
        <middleware>cors,auth,rate-limit</middleware>
        <requirements>
            <requirement param="id" pattern="\d+"/>
        </requirements>
        <defaults>
            <default param="id" value=""/>
        </defaults>
    </route>
</routes>
```

### Chargement

```php
use CloudCastle\Http\Router\Loader\XmlLoader;

$loader = new XmlLoader($router);
$loader->load(__DIR__ . '/config/routes.xml');
```

### Schéma XSD (facultatif)

```xml
<?xml version="1.0"?>
<xs:schema xmlns:xs="http://www.w3.org/2001/XMLSchema">
    <xs:element name="routes">
        <xs:complexType>
            <xs:sequence>
                <xs:element name="route" maxOccurs="unbounded">
                    <xs:complexType>
                        <xs:attribute name="path" type="xs:string" required="true"/>
                        <xs:attribute name="name" type="xs:string"/>
                        <xs:attribute name="methods" type="xs:string"/>
                        <xs:attribute name="controller" type="xs:string"/>
                    </xs:complexType>
                </xs:element>
            </xs:sequence>
        </xs:complexType>
    </xs:element>
</xs:schema>
```

### Quand l'utiliser
- Projets d'entreprise
- Quand la validation XML est-elle nécessaire ?
- Intégration avec les systèmes Java/C#
- Configuration complexe

---

## 🎯 4. JSON Configuration

### Avantages
- ✅ Format de données universel
- ✅ Facilement analysé dans toutes les langues
- ✅ Syntaxe compacte
- ✅ Convient aux configurations API
- ✅ Pratique pour générer à partir d'autres systèmes

### Structure de base

```json
{
  "routes": [
    {
      "method": "GET",
      "uri": "/",
      "action": "HomeController@index",
      "name": "home"
    },
    {
      "method": "GET",
      "uri": "/users",
      "action": "UserController@index",
      "name": "users.index",
      "middleware": ["auth"]
    },
    {
      "method": "GET",
      "uri": "/users/{id}",
      "action": "UserController@show",
      "name": "users.show",
      "requirements": {
        "id": "\\d+"
      },
      "defaults": {
        "id": 1
      }
    }
  ]
}
```

### Groupes de routes

```json
{
  "groups": [
    {
      "prefix": "/api",
      "middleware": ["api"],
      "routes": [
        {
          "method": "GET",
          "uri": "/status",
          "action": "ApiController@status",
          "name": "api.status"
        },
        {
          "method": "GET",
          "uri": "/users",
          "action": "ApiController@users",
          "name": "api.users",
          "throttle": {
            "limit": 100,
            "per_minutes": 1
          }
        }
      ]
    }
  ]
}
```

### Groupes imbriqués

```json
{
  "groups": [
    {
      "prefix": "/api",
      "routes": [],
      "groups": [
        {
          "prefix": "/v1",
          "middleware": ["api.v1"],
          "routes": [
            {
              "method": "GET",
              "uri": "/users",
              "action": "ApiV1Controller@users",
              "name": "api.v1.users"
            }
          ]
        },
        {
          "prefix": "/v2",
          "middleware": ["api.v2"],
          "routes": [
            {
              "method": "GET",
              "uri": "/users",
              "action": "ApiV2Controller@users",
              "name": "api.v2.users"
            }
          ]
        }
      ]
    }
  ]
}
```

### Configuration avancée

```json
{
  "routes": [
    {
      "method": "POST",
      "uri": "/api/data",
      "action": "ApiController@data",
      "name": "api.data",
      "middleware": ["auth", "csrf"],
      "domain": "api.example.com",
      "port": 443,
      "protocol": "https",
      "tags": ["api", "public"],
      "throttle": {
        "limit": 60,
        "per_minutes": 1
      },
      "whitelist": ["192.168.1.0/24", "10.0.0.1"],
      "condition": "user.premium == true"
    }
  ],
  "groups": [
    {
      "prefix": "/admin",
      "middleware": ["auth", "admin"],
      "domain": "admin.example.com",
      "port": 443,
      "protocol": "https",
      "routes": [
        {
          "method": "GET",
          "uri": "/dashboard",
          "action": "AdminController@dashboard",
          "name": "admin.dashboard"
        },
        {
          "method": "GET",
          "uri": "/users",
          "action": "AdminController@users",
          "name": "admin.users",
          "whitelist": ["192.168.1.0/24"]
        }
      ]
    }
  ]
}
```

### Utilisation de JsonLoader

```php
use CloudCastle\Http\Router\Router;
use CloudCastle\Http\Router\Loader\JsonLoader;

$router = new Router();
$loader = new JsonLoader($router);

// Загрузить маршруты из JSON файла
$loader->load(__DIR__ . '/config/routes.json');

// Dispatch
$result = $router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
```

### Exemple complet de configuration JSON

```json
{
  "routes": [
    {
      "method": "GET",
      "uri": "/",
      "action": "HomeController@index",
      "name": "home"
    },
    {
      "method": "GET",
      "path": "/about",
      "handler": "PageController@about",
      "name": "about"
    }
  ],
  "groups": [
    {
      "prefix": "/api",
      "middleware": ["api"],
      "routes": [
        {
          "method": "GET",
          "uri": "/status",
          "action": "ApiController@status",
          "name": "api.status"
        }
      ],
      "groups": [
        {
          "prefix": "/v1",
          "middleware": ["api.v1"],
          "routes": [
            {
              "method": "GET",
              "uri": "/users",
              "action": "ApiV1Controller@users",
              "name": "api.v1.users",
              "throttle": {
                "limit": 100,
                "per_minutes": 1
              }
            },
            {
              "method": "POST",
              "uri": "/users",
              "action": "ApiV1Controller@store",
              "name": "api.v1.users.store",
              "middleware": ["csrf"],
              "throttle": {
                "limit": 10,
                "per_minutes": 1
              }
            }
          ]
        }
      ]
    },
    {
      "prefix": "/admin",
      "middleware": ["auth", "admin"],
      "domain": "admin.example.com",
      "routes": [
        {
          "method": "GET",
          "uri": "/dashboard",
          "action": "AdminController@dashboard",
          "name": "admin.dashboard",
          "whitelist": ["192.168.1.0/24", "10.0.0.1"]
        },
        {
          "method": "GET",
          "uri": "/settings",
          "action": "AdminController@settings",
          "name": "admin.settings",
          "condition": "user.role == 'super_admin'"
        }
      ]
    }
  ]
}
```

### Champs pris en charge

| Champ | Tapez | Descriptif |
|:---|:---|:---|
| `méthode` | chaîne | Méthode HTTP (GET, POST, PUT, DELETE, etc.) |
| `uri` / `chemin` | chaîne | URI d'itinéraire |
| `action` / `gestionnaire` | chaîne | Gestionnaire d'itinéraire |
| `nom` | chaîne | Nom de l'itinéraire |
| `middleware` | string\|array | Middleware |
| `valeurs par défaut` | objet | Valeurs par défaut des paramètres |
| `exigences` | objet | Exigences Regex pour les paramètres |
| `état` | chaîne | Condition de langage d'expression |
| `domaine` | chaîne | Domaine |
| `port` | numéro | Port |
| `protocole` | chaîne | Protocole (http/https) |
| `balises` | tableau | Balises d'itinéraire |
| `accélérateur` | objet | Configuration de limitation de débit |
| `whitelist` | array | IP whitelist |
| `blacklist` | array | IP blacklist |

### Quand l'utiliser
- Projets orientés API
- Lorsque la configuration est générée par programme
- Intégration avec JavaScript/Node.js
-Services API REST
- Architecture de microservices

---

## 🏷️ 5. PHP 8 Attributes

### Avantages
- ✅ Type-safe
- ✅ IDE autocomplete
- ✅ Itinéraires à côté du code
- ✅ Compatible avec la refactorisation
- ✅ Modern PHP 8+

### Utilisation de base

```php
use CloudCastle\Http\Router\Loader\Route;

class UserController
{
    #[Route('/users', methods: 'GET', name: 'users.index')]
    public function index(): array
    {
        return User::all();
    }
    
    #[Route('/users/{id}', methods: 'GET', name: 'users.show')]
    public function show(int $id): User
    {
        return User::find($id);
    }
    
    #[Route('/users', methods: 'POST', name: 'users.store')]
    public function store(): User
    {
        return User::create($request->all());
    }
}
```

### Avec middleware

```php
class AdminController
{
    #[Route(
        '/admin/dashboard',
        methods: 'GET',
        name: 'admin.dashboard',
        middleware: ['auth', 'admin']
    )]
    public function dashboard(): View
    {
        return view('admin.dashboard');
    }
}
```

### Avec domaine et limitation

```php
class ApiController
{
    #[Route(
        '/api/data',
        methods: 'GET',
        name: 'api.data',
        domain: 'api.example.com',
        throttle: 60,
        middleware: ['cors', 'auth']
    )]
    public function data(): array
    {
        return ['data' => $this->getData()];
    }
}
```

### Plusieurs itinéraires sur une seule méthode

```php
class ProfileController
{
    // Оба маршрута ведут на один метод
    #[Route('/user/{id}', methods: 'GET', name: 'user.profile')]
    #[Route('/profile/{id}', methods: 'GET', name: 'profile.show')]
    public function show(int $id): array
    {
        return User::find($id)->toArray();
    }
}
```

### Chargement

```php
use CloudCastle\Http\Router\Loader\AttributeLoader;

$loader = new AttributeLoader($router);

// Из одного контроллера
$loader->loadFromController(UserController::class);

// Из нескольких контроллеров
$loader->loadFromControllers([
    UserController::class,
    PostController::class,
    CommentController::class,
]);

// Из директории (auto-discovery)
$loader->loadFromDirectory(
    __DIR__ . '/Controllers',
    'App\\Controllers'
);
```

### Best Practices

```php
// 1. Группируйте логически по контроллерам
class UserApiController
{
    #[Route('/api/users', methods: 'GET')]
    public function index() {}
    
    #[Route('/api/users/{id}', methods: 'GET')]
    public function show(int $id) {}
}

// 2. Используйте named routes
#[Route('/users/{id}', name: 'users.show')]

// 3. Документируйте сложные маршруты
/**
 * Show user profile with statistics.
 */
#[Route(
    '/users/{id}/stats',
    methods: ['GET', 'POST'],
    middleware: ['auth', 'admin']
)]
public function stats(int $id) {}
```

### Quand l'utiliser
- Projets PHP 8+ modernes
-Applications MVC
- Quand la sécurité des types est importante
- Pour une meilleure organisation du code

---

## 📊 Comparaison des chargeurs

### Possibilités

| Feature | PHP | YAML | XML | Attributes |
|:---|:---:|:---:|:---:|:---:|
| Type Safety | ✅ | ⚠️ | ⚠️ | ✅ |
| IDE Support | ✅ | ⚠️ | ✅ | ✅ |
| Validation | Runtime | ⚠️ | ✅ | Compile |
| Dynamic | ✅ | ❌ | ❌ | ❌ |
| Version Control | ✅ | ✅ | ✅ | ✅ |
| Non-dev Editing | ❌ | ✅ | ✅ | ❌ |
| Complexity | Medium | Low | Medium | Low |

### Performances de démarrage

| Loader | 100 routes | 1000 routes | Overhead |
|:---|:---:|:---:|:---:|
| PHP (direct) | 0.001s | 0.010s | Baseline |
| **JSON** | **0.012s** | **0.120s** | **12x** |
| Attributes | 0.015s | 0.150s | 15x |
| XML | 0.018s | 0.180s | 18x |
| YAML | 0.020s | 0.200s | 20x |

**Remarque** : surcharge uniquement au premier démarrage. Avec la mise en cache, tout le monde travaille avec la même rapidité.

### Taille de la configuration

| Format | Size for 100 routes | Readability |
|:---|:---:|:---:|
| PHP | ~5 KB | Medium |
| YAML | ~3 KB | High |
| **JSON** | **~4 KB** | **High** |
| XML | ~7 KB | Medium |
| Attributes | ~4 KB | High |

## 💡 Recommandations pour choisir

### Utilisez PHP si :
- ✅ Besoin de routage dynamique
- ✅ Petit/moyen projet (< 100 parcours)
- ✅ La sécurité des types est importante
- ✅ Besoin d'un contrôle total

### Utilisez YAML si :
- ✅ Grand projet (100-1000 itinéraires)
- ✅ Les itinéraires sont édités par des non-développeurs
- ✅ Besoin d'une structure modulaire
- ✅ La lisibilité est importante

### Utilisez XML si :
- ✅ Projet d'entreprise
- ✅ Validation XML requise
- ✅ Intégration avec d'autres systèmes XML
- ✅ Schéma XSD requis

### Utilisez JSON si :
- ✅ Projets orientés API
- ✅ Intégration avec JavaScript/Node.js
- ✅ La configuration est générée par programme
- ✅Services API REST
- ✅ Architecture de microservices

### Utilisez les attributs si :
- ✅ Projet PHP 8+ moderne
- ✅Architecture MVC
- ✅ Itinéraires proches des contrôleurs
- ✅ Le support de l'IDE est important

## 🔄 Utilisation combinée

Vous pouvez combiner plusieurs approches :

```php
// 1. Базовые маршруты через PHP
$router->get('/', 'HomeController@index');

// 2. API маршруты через JSON
$jsonLoader = new JsonLoader($router);
$jsonLoader->load(__DIR__ . '/config/routes/api.json');

// 3. Admin через YAML
$yamlLoader = new YamlLoader($router);
$yamlLoader->load(__DIR__ . '/config/routes/admin.yaml');

// 4. Module через Attributes
$attrLoader = new AttributeLoader($router);
$attrLoader->loadFromDirectory(__DIR__ . '/Controllers/Module', 'App\\Module');

// 5. Legacy через XML
$xmlLoader = new XmlLoader($router);
$xmlLoader->load(__DIR__ . '/config/legacy-routes.xml');
```

## 📊 Comparaison avec les concurrents

| Router | PHP | YAML | XML | JSON | Attributes | Total |
|:---|:---:|:---:|:---:|:---:|:---:|:---:|
| **CloudCastle** | **✅** | **✅** | **✅** | **✅** | **✅** | **5/5** |
| Symfony | ✅ | ✅ | ✅ | ❌ | ✅ | 4/5 |
| Laravel | ✅ | ❌ | ❌ | ❌ | ⚠️ | 1.5/5 |
| FastRoute | ✅ | ❌ | ❌ | ❌ | ❌ | 1/5 |
| Slim | ✅ | ❌ | ❌ | ❌ | ❌ | 1/5 |
| AltoRouter | ✅ | ❌ | ❌ | ❌ | ❌ | 1/5 |

**CloudCastle est le seul routeur qui prend en charge les 5 formats de configuration !**

## ✅Conclusion

Le routeur HTTP CloudCastle offre une **flexibilité maximale** dans la configuration des routes :

- ✅ **5 formats de configuration** (PHP, YAML, XML, JSON, Attributs)
- ✅ Possibilité de combinaison
- ✅ Découverte automatique des attributs
- ✅ Chargement modulaire
- ✅ **Le seul routeur prenant entièrement en charge tous les formats !**

Choisissez un format en fonction de la taille du projet, de l'équipe et des exigences.

---

*Dernière mise à jour : 18 octobre 2025*

---

[📚 Table des matières](_table-of-contents.md) | [🏠 Accueil](LISEZMOI.md)

---

[📚 Table of Contents](fr/_table-of-contents.md) | [🏠 Home](fr/README.md)
