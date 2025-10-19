[🇷🇺 Русский](ru/loaders.md) | [🇺🇸 English](en/loaders.md) | [🇩🇪 Deutsch](de/loaders.md) | [🇫🇷 Français](fr/loaders.md) | [🇨🇳 中文](zh/loaders.md)

[📚 Table of Contents](de/_table-of-contents.md) | [🏠 Home](de/README.md)

---

# Routenlader – Routenladesysteme

**Sprachen:** 🇷🇺 Russisch | [🇬🇧 Englisch](../en/loaders.md) | [🇩🇪 Deutsch](../de/loaders.md) | [🇫🇷 Français](../fr/loaders.md) | [🇨🇳中文](../zh/loaders.md)

[📚 Inhaltsverzeichnis](_table-of-contents.md) | [🏠 Startseite](README.md)

---

## 📚 Rezension

Der CloudCastle HTTP Router unterstützt **5 Möglichkeiten** zum Konfigurieren von Routen:
1. PHP (Softwarekonfiguration)
2. YAML-Dateien
3. XML-Dateien
4. JSON-Dateien
5. PHP 8 Attributes

## 🎯 1. PHP-Konfiguration (Software)

### Vorteile
- ✅ Volle Kontrolle
- ✅ IDE autocomplete
- ✅ Type safety
- ✅ Dynamic routing

### Beispiele

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

### Wann zu verwenden
- Small to medium projects
- Wenn dynamisches Routing erforderlich ist
- Wenn Typsicherheit wichtig ist
- Für den Prototypenbau

---

## 📄 2. YAML Configuration

### Vorteile
- ✅ Deklarativer Stil
- ✅ Einfach zu lesen und zu bearbeiten
- ✅ Version control friendly
- ✅ Geeignet für große Projekte

### Installation

```bash
# YAML extension required
pecl install yaml
```

### Grundstruktur

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

### Wird geladen

```php
use CloudCastle\Http\Router\Loader\YamlLoader;

$loader = new YamlLoader($router);
$loader->load(__DIR__ . '/config/routes.yaml');
```

### Modulares Laden

```php
// Загрузка по модулям
$loader = new YamlLoader($router);

$loader->load(__DIR__ . '/config/routes/web.yaml');
$loader->load(__DIR__ . '/config/routes/api.yaml');
$loader->load(__DIR__ . '/config/routes/admin.yaml');
```

### Bedingtes Laden

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

### Wann zu verwenden
- Große Projekte (über 100 Routen)
- Unternehmensanwendungen
- Wenn Routen von Nicht-Entwicklern bearbeitet werden
- Konfiguration für mehrere Umgebungen

---

## 📑 3. XML Configuration

### Vorteile
- ✅ Strukturiertes Format
- ✅ XML validation
- ✅ IDE-Unterstützung mit XSD-Schemata
- ✅ Geeignet für Unternehmen

### Grundstruktur

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

### Wird geladen

```php
use CloudCastle\Http\Router\Loader\XmlLoader;

$loader = new XmlLoader($router);
$loader->load(__DIR__ . '/config/routes.xml');
```

### XSD-Schema (optional)

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

### Wann zu verwenden
- Unternehmensprojekte
- Wann ist eine XML-Validierung erforderlich?
- Integration mit Java/C#-Systemen
- Komplexe Konfiguration

---

## 🎯 4. JSON Configuration

### Vorteile
- ✅ Universelles Datenformat
- ✅ Leicht in allen Sprachen zu analysieren
- ✅ Kompakte Syntax
- ✅ Geeignet für API-Konfigurationen
- ✅ Praktisch für die Generierung aus anderen Systemen

### Grundstruktur

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

### Routengruppen

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

### Verschachtelte Gruppen

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

### Erweiterte Konfiguration

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

### Verwenden von JsonLoader

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

### Vollständige Beispiel-JSON-Konfiguration

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

### Unterstützte Felder

| Feld | Geben Sie | ein Beschreibung |
|:---|:---|:---|
| „Methode“ | Zeichenfolge | HTTP-Methode (GET, POST, PUT, DELETE usw.) |
| `uri` / `path` | Zeichenfolge | Routen-URI |
| `Aktion` / `Handler` | Zeichenfolge | Routenhandler |
| `Name` | Zeichenfolge | Routenname |
| `middleware` | string\|array | Middleware |
| `Standards` | Objekt | Standardwerte für Parameter |
| `Anforderungen` | Objekt | Regex-Anforderungen für Parameter |
| „Zustand“ | Zeichenfolge | Ausdruck Sprachbedingung |
| `Domäne` | Zeichenfolge | Domäne |
| „Hafen“ | Zahl | Hafen |
| `Protokoll` | Zeichenfolge | Protokoll (http/https) |
| `Tags` | Array | Routen-Tags |
| „Drossel“ | Objekt | Ratenbegrenzungskonfiguration |
| `whitelist` | array | IP whitelist |
| `blacklist` | array | IP blacklist |

### Wann zu verwenden
- API-orientierte Projekte
- Wenn die Konfiguration programmgesteuert generiert wird
- Integration mit JavaScript/Node.js
- REST-API-Dienste
- Microservice-Architektur

---

## 🏷️ 5. PHP 8 Attributes

### Vorteile
- ✅ Type-safe
- ✅ IDE autocomplete
- ✅ Routen neben dem Code
- ✅ Refactoring-freundlich
- ✅ Modern PHP 8+

### Grundlegende Verwendung

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

### Mit Middleware

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

### Mit Domain und Gas

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

### Mehrere Routen auf einer Methode

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

### Wird geladen

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

### Wann zu verwenden
- Moderne PHP 8+-Projekte
- MVC-Anwendungen
- Wenn Typsicherheit wichtig ist
- Für eine bessere Code-Organisation

---

## 📊 Vergleich von Ladern

### Möglichkeiten

| Feature | PHP | YAML | XML | Attributes |
|:---|:---:|:---:|:---:|:---:|
| Type Safety | ✅ | ⚠️ | ⚠️ | ✅ |
| IDE Support | ✅ | ⚠️ | ✅ | ✅ |
| Validation | Runtime | ⚠️ | ✅ | Compile |
| Dynamic | ✅ | ❌ | ❌ | ❌ |
| Version Control | ✅ | ✅ | ✅ | ✅ |
| Non-dev Editing | ❌ | ✅ | ✅ | ❌ |
| Complexity | Medium | Low | Medium | Low |

### Boot-Leistung

| Loader | 100 routes | 1000 routes | Overhead |
|:---|:---:|:---:|:---:|
| PHP (direct) | 0.001s | 0.010s | Baseline |
| **JSON** | **0.012s** | **0.120s** | **12x** |
| Attributes | 0.015s | 0.150s | 15x |
| XML | 0.018s | 0.180s | 18x |
| YAML | 0.020s | 0.200s | 20x |

**Hinweis**: Overhead nur beim ersten Start. Mit Caching arbeiten alle gleich schnell.

### Konfigurationsgröße

| Format | Size for 100 routes | Readability |
|:---|:---:|:---:|
| PHP | ~5 KB | Medium |
| YAML | ~3 KB | High |
| **JSON** | **~4 KB** | **High** |
| XML | ~7 KB | Medium |
| Attributes | ~4 KB | High |

## 💡 Empfehlungen zur Auswahl

### Verwenden Sie PHP, wenn:
- ✅ Benötigen Sie dynamisches Routing
- ✅ Kleines/mittleres Projekt (< 100 Routen)
- ✅ Typensicherheit ist wichtig
- ✅ Brauchen Sie die volle Kontrolle

### Verwenden Sie YAML, wenn:
- ✅ Großes Projekt (100-1000 Routen)
- ✅ Routen werden von Nicht-Entwicklern bearbeitet
- ✅ Brauchen Sie einen modularen Aufbau
- ✅ Lesbarkeit ist wichtig

### Verwenden Sie XML, wenn:
- ✅ Unternehmensprojekt
- ✅ XML-Validierung erforderlich
- ✅ Integration mit anderen XML-Systemen
- ✅ XSD-Schema erforderlich

### Verwenden Sie JSON, wenn:
- ✅ API-orientierte Projekte
- ✅ Integration mit JavaScript/Node.js
- ✅ Die Konfiguration wird programmgesteuert generiert
- ✅ REST-API-Dienste
- ✅ Microservice-Architektur

### Attribute verwenden, wenn:
- ✅ Modernes PHP 8+ Projekt
- ✅ MVC-Architektur
- ✅ Routen in der Nähe von Controllern
- ✅ IDE-Unterstützung ist wichtig

## 🔄 Kombinierte Nutzung

Sie können mehrere Ansätze kombinieren:

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

## 📊 Vergleich mit Mitbewerbern

| Router | PHP | YAML | XML | JSON | Attributes | Total |
|:---|:---:|:---:|:---:|:---:|:---:|:---:|
| **CloudCastle** | **✅** | **✅** | **✅** | **✅** | **✅** | **5/5** |
| Symfony | ✅ | ✅ | ✅ | ❌ | ✅ | 4/5 |
| Laravel | ✅ | ❌ | ❌ | ❌ | ⚠️ | 1.5/5 |
| FastRoute | ✅ | ❌ | ❌ | ❌ | ❌ | 1/5 |
| Slim | ✅ | ❌ | ❌ | ❌ | ❌ | 1/5 |
| AltoRouter | ✅ | ❌ | ❌ | ❌ | ❌ | 1/5 |

**CloudCastle ist der einzige Router, der alle 5 Konfigurationsformate unterstützt!**

## ✅ Fazit

Der CloudCastle HTTP Router bietet **maximale Flexibilität** bei der Routenkonfiguration:

- ✅ **5 Konfigurationsformate** (PHP, YAML, XML, JSON, Attribute)
- ✅ Kombinationsmöglichkeit
- ✅ Automatische Erkennung von Attributen
- ✅ Modulares Laden
- ✅ **Der einzige Router mit vollständiger Unterstützung für alle Formate!**

Wählen Sie ein Format basierend auf Projektgröße, Team und Anforderungen.

---

*Letzte Aktualisierung: 18. Oktober 2025*

---

[📚 Inhaltsverzeichnis](_table-of-contents.md) | [🏠 Startseite](README.md)

---

[📚 Table of Contents](de/_table-of-contents.md) | [🏠 Home](de/README.md)
