[🇷🇺 Русский](ru/features.md) | [🇺🇸 English](en/features.md) | [🇩🇪 Deutsch](de/features.md) | [🇫🇷 Français](fr/features.md) | [🇨🇳 中文](zh/features.md)

[📚 Table of Contents](de/_table-of-contents.md) | [🏠 Home](de/README.md)

---

# Eine vollständige Anleitung zu den Funktionen des CloudCastle HTTP Routers

[📚 Inhaltsverzeichnis](_table-of-contents.md) | [🏠 Startseite](README.md)

---
## 📚 Rezension

Der CloudCastle HTTP Router bietet **30+ Schlüsselfunktionen** für die Erstellung moderner PHP-Anwendungen.

### 🏆 **Einzigartige Funktionen (nur in CloudCastle):**
1. **Auto-Naming** – automatische Benennung von Routen
2. **ThrottleWithBan** – Ratenbegrenzung + automatisches Verbot
3. **IP-Filterung** – Whitelist/Blacklist für Routen
4. **SSRF-Schutz** – Schutz vor serverseitiger Anforderungsfälschung
5. **Sicherheitslogger** – automatische Protokollierung
6. **JSON-Konfiguration** – der einzige Router mit JSON-Unterstützung
7. **Routenmakros** – 7+ Makros (80–97 % Codereduzierung)
8. **Routenverknüpfungen** – 13+ praktische Verknüpfungen
9. **Hilfsfunktionen** – 15+ globale Funktionen
10. **Tags-System** – leistungsstarke Filterung
11. **Analytics-Plugin** – integrierte Analyse

## 🎯 Hauptmerkmale

### 1. RESTful Routing

**Beschreibung**: Unterstützt alle HTTP-Methoden.

**Unterstützte Methoden:**
- GET, POST, PUT, DELETE, PATCH
- OPTIONS, HEAD
- CUSTOM methods (VIEW, TRACE, etc.)

**Beispiele:**
```php
$router->get('/users', 'UserController@index');
$router->post('/users', 'UserController@store');
$router->put('/users/{id}', 'UserController@update');
$router->delete('/users/{id}', 'UserController@destroy');
$router->patch('/users/{id}', 'UserController@patch');
$router->options('/users', 'UserController@options');

// Множественные методы
$router->match(['GET', 'POST'], '/form', 'FormController@handle');

// Все методы
$router->any('/debug', 'DebugController@handle');
```

**Vergleich:**

| Gelegenheit | CloudCastle | FastRoute | Symfony | Laravel | Schlank | Alt |
|:---|:---:|:---:|:---:|:---:|:---:|:---:|
| Standard HTTP | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ |
| Custom methods | ✅ | ⚠️ | ✅ | ⚠️ | ⚠️ | ❌ |
| Method groups | ✅ | ❌ | ✅ | ✅ | ✅ | ❌ |

---

### 2. Named Routes

**Beschreibung**: Zuweisen von Namen zu Routen zur bequemen URL-Generierung.

**Verwendung:**
```php
// Определение
$router->get('/users/{id}', 'UserController@show')
    ->name('users.show');

$router->get('/posts/{year}/{month}/{slug}', 'PostController@show')
    ->name('posts.show');

// Использование
$route = $router->getRoute('users.show');
```

**URL generieren:**
```php
use CloudCastle\Http\Router\UrlGenerator;

$generator = new UrlGenerator($router);
$generator->setBaseUrl('https://example.com');

$url = $generator->generate('users.show', ['id' => 123]);
// https://example.com/users/123

$url = $generator->generate('posts.show', [
    'year' => 2025,
    'month' => 10,
    'slug' => 'my-post'
]);
// https://example.com/posts/2025/10/my-post
```

**Vorteile:**
- ✅ Zentralisierte URL-Verwaltung
- ✅ Refactoring ohne Unterbrechung von Links
- ✅ Type-safe URL generation
- ✅ Unterstützung für Abfrageparameter

**Vergleich:**

| Feature | CloudCastle | FastRoute | Symfony | Laravel | Slim | Alto |
|:---|:---:|:---:|:---:|:---:|:---:|:---:|
| Named routes | ✅ | ❌ | ✅ | ✅ | ✅ | ✅ |
| URL generation | ✅ | ❌ | ✅ | ✅ | ✅ | ✅ |
| Query params | ✅ | ❌ | ✅ | ✅ | ✅ | ⚠️ |

---

### 3. Route Groups

**Beschreibung**: Gruppierung von Routen mit gemeinsamen Attributen.

**Gruppenfunktionen:**
- URI-Präfixe
- Middleware
- Namespace
- Domain
- Tags

**Beispiele:**
```php
// Простая группа с префиксом
$router->group(['prefix' => '/api/v1'], function($router) {
    $router->get('/users', 'UserController@index');
    $router->get('/posts', 'PostController@index');
});
// Создаст: /api/v1/users, /api/v1/posts

// Группа с middleware
$router->group(['middleware' => 'auth'], function($router) {
    $router->get('/profile', 'ProfileController@show');
    $router->get('/settings', 'SettingsController@index');
});

// Вложенные группы
$router->group(['prefix' => '/admin'], function($router) {
    $router->group(['middleware' => 'admin'], function($router) {
        $router->get('/users', 'Admin\UserController@index');
    });
});

// Группа с доменом
$router->group(['domain' => 'api.example.com'], function($router) {
    $router->get('/data', 'ApiController@data');
});

// Группа с namespace
$router->group(['namespace' => 'App\\Admin'], function($router) {
    $router->get('/dashboard', 'DashboardController@index');
    // Resolves to: App\Admin\DashboardController
});

// Комбинированная группа
$router->group([
    'prefix' => '/api/v1',
    'middleware' => ['cors', 'auth'],
    'domain' => 'api.example.com',
    'namespace' => 'App\\Api\\V1',
    'tag' => 'api-v1'
], function($router) {
    $router->get('/users', 'UserController@index');
});
```

**Attributvererbung:**
```php
$router->group(['prefix' => '/api', 'middleware' => 'cors'], function($router) {
    // Prefix: /api, Middleware: cors
    
    $router->group(['prefix' => '/v1', 'middleware' => 'auth'], function($router) {
        // Prefix: /api/v1, Middleware: cors, auth
        
        $router->get('/users', 'UserController@index');
        // URI: /api/v1/users
        // Middleware: [cors, auth]
    });
});
```

**Vergleich:**

| Feature | CloudCastle | FastRoute | Symfony | Laravel | Slim | Alto |
|:---|:---:|:---:|:---:|:---:|:---:|:---:|
| Groups | ✅ | ❌ | ✅ | ✅ | ✅ | ❌ |
| Prefixes | ✅ | ❌ | ✅ | ✅ | ✅ | ❌ |
| Middleware groups | ✅ | ❌ | ⚠️ | ✅ | ✅ | ❌ |
| Nested groups | ✅ 50 | ❌ | ✅ 30 | ✅ 25 | ✅ 20 | ❌ |
| Domain groups | ✅ | ❌ | ✅ | ✅ | ❌ | ❌ |

---

### 4. Middleware System

**Beschreibung**: Leistungsstarkes Middleware-System zur Bearbeitung von Anfragen.

**Arten von Middleware:**
1. Global
2. Gruppen
3. Route

**Beispiele:**
```php
// Глобальный middleware (применяется ко всем маршрутам)
$router->middleware(['cors', 'log']);

// Middleware группы
$router->group(['middleware' => 'auth'], function($router) {
    $router->get('/profile', 'ProfileController@show');
});

// Middleware маршрута
$router->get('/admin', 'AdminController@index')
    ->middleware(['auth', 'admin']);

// Custom middleware
class CustomMiddleware implements MiddlewareInterface
{
    public function handle(mixed $request, callable $next, array $parameters = []): mixed
    {
        // Before logic
        $response = $next($request);
        // After logic
        return $response;
    }
}

$router->middleware(new CustomMiddleware());
```

**Integrierte Middleware:**
1. **CorsMiddleware** - CORS headers
2. **AuthMiddleware** - Authentication & authorization
3. **HttpsEnforcement** - Force HTTPS
4. **SsrfProtection** – SSRF-Schutz
5. **SecurityLogger** - Security logging

**Vergleich:**

| Feature | CloudCastle | FastRoute | Symfony | Laravel | Slim | Alto |
|:---|:---:|:---:|:---:|:---:|:---:|:---:|
| Middleware support | ✅ | ❌ | ⚠️ | ✅ | ✅ | ❌ |
| Global middleware | ✅ | ❌ | ⚠️ | ✅ | ✅ | ❌ |
| Route middleware | ✅ | ❌ | ❌ | ✅ | ✅ | ❌ |
| PSR-15 | ✅ | ❌ | ❌ | ❌ | ✅ | ❌ |
| CORS | ✅ 5 | ❌ | ❌ | ✅ 3 | ⚠️ 1 | ❌ |

---

### 5. PSR-15 Support

**Beschreibung**: Vollständig kompatibel mit PSR-15 HTTP Server Request Handlern.

**Möglichkeiten:**
- Verwendung der PSR-15-Middleware im Router
– Verwendung von Router-Middleware wie PSR-15
- Brücke zwischen Systemen

**Beispiele:**
```php
use CloudCastle\Http\Router\Psr15\Psr15MiddlewareAdapter;
use CloudCastle\Http\Router\Psr15\RouterMiddlewareBridge;
use Psr\Http\Server\MiddlewareInterface as PsrMiddleware;

// PSR-15 middleware в роутере
$psrMiddleware = new SomePsr15Middleware();
$adapter = new Psr15MiddlewareAdapter(
    $psrMiddleware,
    $request, // ServerRequestInterface
    $response // ResponseInterface
);

$router->middleware($adapter);

// Наш middleware как PSR-15
$ourMiddleware = new AuthMiddleware();
$bridge = new RouterMiddlewareBridge($ourMiddleware);

// Используйте в PSR-15 стеке
$middleware->process($request, $handler);
```

**Vorteile:**
- ✅ Interoperabilität mit dem PSR-15-Ökosystem
- ✅ Verwendung vorgefertigter PSR-15-Middleware
- ✅ Standardisierung

**Vergleich:**

| Router | PSR-15 | Adapter | Bridge |
|:---|:---:|:---:|:---:|
| **CloudCastle** | **✅** | **✅** | **✅** |
| FastRoute | ❌ | ❌ | ❌ |
| Symfony | ❌ | ⚠️ | ❌ |
| Laravel | ❌ | ❌ | ❌ |
| Slim | ✅ | ✅ | ⚠️ |
| AltoRouter | ❌ | ❌ | ❌ |

---

### 6. Rate Limiting

**Beschreibung**: Integrierte Begrenzung der Anforderungsrate.

**Möglichkeiten:**
- Per minute, hour, day
- Custom time periods
- Custom keys
- Auto-reset

**Beispiele:**
```php
// Стандартные периоды
$router->get('/api/data', 'ApiController@data')
    ->perMinute(60); // 60 запросов в минуту

$router->get('/api/heavy', 'ApiController@heavy')
    ->perHour(100); // 100 запросов в час

$router->get('/api/daily', 'ApiController@daily')
    ->perDay(1000); // 1000 запросов в день

// Кастомный период
$router->get('/api/custom', 'ApiController@custom')
    ->throttle(100, 3600); // 100 запросов за 3600 секунд

// С кастомным ключом
$router->get('/api/user', 'ApiController@user')
    ->throttle(60, 60, 'user:{user_id}');
```

**Time Units:**
```php
use CloudCastle\Http\Router\TimeUnit;

// Доступные единицы
TimeUnit::SECOND; // 1 секунда
TimeUnit::MINUTE; // 60 секунд
TimeUnit::HOUR;   // 3600 секунд
TimeUnit::DAY;    // 86400 секунд
TimeUnit::WEEK;   // 604800 секунд
```

**Übermäßige Handhabung:**
```php
try {
    $router->dispatch('/api/data', 'GET');
} catch (TooManyRequestsException $e) {
    header('HTTP/1.1 429 Too Many Requests');
    header('Retry-After: ' . $e->getRetryAfter());
    echo json_encode([
        'error' => 'Rate limit exceeded',
        'retry_after' => $e->getRetryAfter()
    ]);
}
```

**Vergleich:**

| Router | Built-in | Time Units | Custom Keys | Auto-reset |
|:---|:---:|:---:|:---:|:---:|
| **CloudCastle** | **✅** | **✅ 5** | **✅** | **✅** |
| FastRoute | ❌ | - | - | - |
| Symfony | ❌ | - | - | - |
| Laravel | ✅ | ✅ 3 | ⚠️ | ✅ |
| Slim | ❌ | - | - | - |
| AltoRouter | ❌ | - | - | - |

---

### 7. Auto-ban System

**Beschreibung**: Automatische IP-Blockierung bei Überschreitung der Grenzwerte.

**Einzigartige Funktion von CloudCastle!**

**Möglichkeiten:**
- Automatische Sperre bei Überschreitung des Ratenlimits
- Anpassbare Sperrdauer
- Manual ban/unban
- Ban reasons
- Temporary & permanent bans

**Beispiele:**
```php
use CloudCastle\Http\Router\BanManager;

// Создание ban manager
$banManager = new BanManager();
$router->setBanManager($banManager);

// Включение auto-ban
$router->enableAutoBan(
    maxAttempts: 100,      // после 100 попыток
    decayMinutes: 60,      // в течение 1 часа  
    banDuration: 3600      // бан на 1 час
);

// Manual ban
$banManager->ban('1.2.3.4', 'Malicious activity', 7200);

// Проверка статуса
if ($banManager->isBanned('1.2.3.4')) {
    throw new BannedException('Your IP is banned');
}

// Unban
$banManager->unban('1.2.3.4');

// Permanent ban
$banManager->ban('5.6.7.8', 'Spam bot', null); // null = permanent
```

**Integration mit Routen:**
```php
$router->get('/api/data', 'ApiController@data')
    ->perMinute(60) // rate limit
    ->blacklistIp(['known-bad-ip']); // additional protection
```

**Vergleich:**

| Router | Auto-ban | Manual ban | Reasons | Temporary |
|:---|:---:|:---:|:---:|:---:|
| **CloudCastle** | **✅** | **✅** | **✅** | **✅** |
| FastRoute | ❌ | ❌ | ❌ | ❌ |
| Symfony | ❌ | ❌ | ❌ | ❌ |
| Laravel | ❌ | ❌ | ❌ | ❌ |
| Slim | ❌ | ❌ | ❌ | ❌ |
| AltoRouter | ❌ | ❌ | ❌ | ❌ |

---

### 8. IP Filtering

**Beschreibung**: Weiße und schwarze Listen von IP-Adressen für Routen.

**Einzigartige Funktion von CloudCastle!**

**Whitelist:**
```php
// Только для офисных IP
$router->get('/admin/dashboard', 'AdminController@dashboard')
    ->whitelistIp([
        '192.168.1.0/24',  // office network
        '10.0.0.50',       // specific IP
        '203.0.113.0/24',  // VPN network
    ]);

// Множественные IP
$router->get('/internal/api', 'InternalController@api')
    ->whitelistIp(['10.0.0.1', '10.0.0.2', '10.0.0.3']);
```

**Blacklist:**
```php
// Блокировка известных злоумышленников
$router->get('/public/api', 'PublicController@api')
    ->blacklistIp([
        '1.2.3.4',
        '5.6.7.8',
        '9.10.11.0/24', // целая подсеть
    ]);

// Динамическая blacklist
$badIps = $banManager->getAllBannedIps();
$router->get('/api/data', 'ApiController@data')
    ->blacklistIp($badIps);
```

**Kombinierte Verwendung:**
```php
$router->get('/sensitive/data', 'SensitiveController@data')
    ->whitelistIp(['trusted-ip'])
    ->blacklistIp(['known-attacker'])
    ->perMinute(10); // дополнительная защита
```

**Unterstützte Formate:**
- Einzelne IP: „192.168.1.100“.
- CIDR notation: `192.168.1.0/24`
- IP ranges: `192.168.1.1-192.168.1.255`

**Vergleich:**

| Router | Whitelist | Blacklist | CIDR | Dynamic |
|:---|:---:|:---:|:---:|:---:|
| **CloudCastle** | **✅** | **✅** | **✅** | **✅** |
| FastRoute | ❌ | ❌ | ❌ | ❌ |
| Symfony | ❌ | ❌ | ❌ | ❌ |
| Laravel | ❌ | ❌ | ❌ | ❌ |
| Slim | ❌ | ❌ | ❌ | ❌ |
| AltoRouter | ❌ | ❌ | ❌ | ❌ |

---

### 9. SSRF Protection

**Beschreibung**: Schutz vor serverseitigen Request-Forgery-Angriffen.

**Einzigartige Funktion von CloudCastle!**

**Blöcke:**

- localhost (127.0.0.1, ::1)
- Private IP ranges (10.0.0.0/8, 172.16.0.0/12, 192.168.0.0/16)
- Link-local (169.254.0.0/16)
- Cloud metadata APIs (169.254.169.254, metadata.google.internal)

**Verwendung:**
```php
use CloudCastle\Http\Router\Middleware\SsrfProtection;

$router->middleware(new SsrfProtection(
    allowLocalhost: false,      // блокировать localhost
    allowPrivateIps: false,     // блокировать private IPs
    allowCloudMetadata: false   // блокировать cloud metadata
));
```

**Beispiel für einen Angriff, der blockiert wird:**
```php
// Злоумышленник пытается получить AWS credentials
$router->get('/proxy/{url}', function($url) {
    // Без защиты: можно запросить
    // http://169.254.169.254/latest/meta-data/iam/security-credentials/
    
    // С SsrfProtection: InsecureConnectionException
    return file_get_contents($url);
});
```

**Anwendung:**
- URL shorteners
- Proxy services
- Webhook handlers
- Image processors
- PDF generators

**Vergleich:**

| Router | SSRF Protection |
|:---|:---:|
| **CloudCastle** | **✅ Eingebaut** |
| FastRoute | ❌ |
| Symfony | ❌ |
| Laravel | ❌ |
| Slim | ❌ |
| AltoRouter | ❌ |

---

### 10. YAML Configuration

**Beschreibung**: Routen aus YAML-Dateien laden.

**Vorteile:**
- ✅ Deklarative Konfiguration
- ✅ Einfach zu bearbeiten
- ✅ Version control friendly
- ✅ Geeignet für große Projekte

**Beispiel „routes.yaml“:**
```yaml
# Простой маршрут
home:
  path: /
  methods: GET
  controller: HomeController::index

# С параметрами и requirements
user_profile:
  path: /users/{id}
  methods: [GET, POST]
  controller: UserController::profile
  requirements:
    id: \d+
  defaults:
    id: 1

# С middleware и throttle
api_endpoint:
  path: /api/v1/{resource}
  methods: [GET, POST, PUT, DELETE]
  controller: ApiController::handle
  middleware: [cors, auth]
  throttle:
    max: 100
    decay: 60
  requirements:
    resource: \w+

# С domain constraint
api_public:
  path: /public/data
  methods: GET
  controller: PublicApiController@data
  domain: api.example.com
```

**Laden:**
```php
use CloudCastle\Http\Router\Loader\YamlLoader;

$loader = new YamlLoader($router);
$loader->load(__DIR__ . '/config/routes.yaml');
```

**Vergleich:**

| Router | YAML Support |
|:---|:---:|
| **CloudCastle** | **✅ Full** |
| FastRoute | ❌ |
| Symfony | ✅ Full |
| Laravel | ❌ |
| Slim | ❌ |
| AltoRouter | ❌ |

---

### 11. XML Configuration

**Beschreibung**: Routen aus XML-Dateien laden.

**Beispiel „routes.xml“:**
```xml
<?xml version="1.0" encoding="UTF-8"?>
<routes>
    <route path="/" name="home" methods="GET" 
           controller="HomeController::index"/>
    
    <route path="/users/{id}" name="users.show" 
           methods="GET,POST" controller="UserController::show">
        <middleware>auth,admin</middleware>
        <requirements>
            <requirement param="id" pattern="\d+"/>
        </requirements>
        <defaults>
            <default param="id" value="1"/>
        </defaults>
    </route>
    
    <route path="/api/v1/{resource}" name="api.resource"
           methods="GET,POST,PUT,DELETE" 
           controller="ApiController::handle"
           domain="api.example.com">
        <middleware>cors,auth</middleware>
    </route>
</routes>
```

**Laden:**
```php
use CloudCastle\Http\Router\Loader\XmlLoader;

$loader = new XmlLoader($router);
$loader->load(__DIR__ . '/config/routes.xml');
```

**Vergleich:**

| Router | XML Support |
|:---|:---:|
| **CloudCastle** | **✅ Full** |
| FastRoute | ❌ |
| Symfony | ✅ Full |
| Laravel | ❌ |
| Slim | ❌ |
| AltoRouter | ❌ |

---

### 12. JSON Configuration

**Beschreibung**: Routen aus JSON-Konfigurationsdateien laden.

**Einzigartige Funktion!** (Nur in CloudCastle!)

**Verwendung:**
```php
use CloudCastle\Http\Router\Loader\JsonLoader;

$loader = new JsonLoader($router);
$loader->load(__DIR__ . '/config/routes.json');
```

**Beispiel für eine JSON-Konfiguration:**
```json
{
  "routes": [
    {
      "method": "GET",
      "uri": "/users/{id}",
      "action": "UserController@show",
      "name": "users.show",
      "requirements": {
        "id": "\\d+"
      },
      "middleware": ["auth"],
      "throttle": {
        "limit": 100,
        "per_minutes": 1
      }
    }
  ],
  "groups": [
    {
      "prefix": "/api",
      "middleware": ["api"],
      "domain": "api.example.com",
      "routes": [
        {
          "method": "GET",
          "uri": "/status",
          "action": "ApiController@status",
          "name": "api.status"
        }
      ]
    }
  ]
}
```

**Vorteile:**
- ✅ Universelles Format
- ✅ Einfach zu analysieren
- ✅ Kompakte Syntax
- ✅ API-orientiert
- ✅ Programmgesteuert generiert

**Vergleich:**

| Router | JSON Support |
|:---|:---:|
| **CloudCastle** | **✅ Full** |
| FastRoute | ❌ |
| Symfony | ❌ |
| Laravel | ❌ |
| Slim | ❌ |
| AltoRouter | ❌ |

---

### 13. PHP Attributes (PHP 8+)

**Beschreibung**: Verwendung von PHP 8-Attributen zum Definieren von Routen.

**Moderner Ansatz!**

**Beispiel:**
```php
use CloudCastle\Http\Router\Loader\Route;
use CloudCastle\Http\Router\Loader\AttributeLoader;

class UserController
{
    #[Route('/users', methods: 'GET', name: 'users.index')]
    public function index(): array
    {
        return ['users' => User::all()];
    }
    
    #[Route('/users/{id}', methods: 'GET', name: 'users.show')]
    public function show(int $id): array
    {
        return ['user' => User::find($id)];
    }
    
    #[Route(
        '/users',
        methods: 'POST',
        name: 'users.store',
        middleware: ['auth', 'admin']
    )]
    public function store(): array
    {
        return ['created' => true];
    }
    
    #[Route(
        '/admin/users/{id}',
        methods: ['GET', 'PUT'],
        name: 'admin.users.edit',
        middleware: ['auth', 'admin'],
        domain: 'admin.example.com',
        throttle: 30
    )]
    public function adminEdit(int $id): array
    {
        return ['admin' => true, 'id' => $id];
    }
    
    // Множественные маршруты на одном методе
    #[Route('/user/{id}', methods: 'GET')]
    #[Route('/profile/{id}', methods: 'GET')]
    public function showProfile(int $id): array
    {
        return ['id' => $id];
    }
}

// Загрузка
$loader = new AttributeLoader($router);
$loader->loadFromController(UserController::class);

// Или из директории
$loader->loadFromDirectory(__DIR__ . '/Controllers', 'App\\Controllers');
```

**Vorteile:**
- ✅ Routen neben dem Code
- ✅ Type-safe configuration
- ✅ IDE autocomplete
- ✅ Refactoring-freundlich

**Vergleich:**

| Router | PHP 8 Attributes | Auto-discovery |
|:---|:---:|:---:|
| **CloudCastle** | **✅** | **✅** |
| FastRoute | ❌ | ❌ |
| Symfony | ✅ | ✅ |
| Laravel | ⚠️ (partial) | ⚠️ |
| Slim | ❌ | ❌ |
| AltoRouter | ❌ | ❌ |

---

### 14. URL Matching

**Beschreibung**: Bedingtes Routing basierend auf Ausdrücken.

**Einzigartige Funktion!**

**Betreiber:**
- Vergleich: `==`, `!=`, `>`, `<`, `>=`, `<=`
- Logik: „und“, „oder“.
- Dot notation: `user.age`, `request.header.auth`

**Beispiele:**
```php
// Простое условие
$router->get('/admin', 'AdminController@index')
    ->condition('role == "admin"');

// Сложное условие
$router->get('/premium', 'PremiumController@content')
    ->condition('user.subscription == "premium" and user.age >= 18');

// С логикой OR
$router->get('/manager', 'ManagerController@panel')
    ->condition('role == "admin" or role == "manager"');

// Числовые сравнения
$router->get('/api/v2', 'ApiV2Controller@index')
    ->condition('api_version >= 2');

// Вложенные данные
$router->get('/verified', 'VerifiedController@index')
    ->condition('user.profile.verified and user.profile.age > 18');
```

**Zur Laufzeit verwenden:**
```php
use CloudCastle\Http\Router\ExpressionLanguage\ExpressionLanguage;

$expr = new ExpressionLanguage();

$context = [
    'user' => [
        'age' => 25,
        'subscription' => 'premium',
        'verified' => true
    ],
    'api_version' => 2
];

// Проверка условий
if ($expr->evaluate('user.age > 18', $context)) {
    // Allow access
}
```

**Vergleich:**

| Router | Expression Language | Operators | Dot Notation |
|:---|:---:|:---:|:---:|
| **CloudCastle** | **✅** | **✅ 8** | **✅** |
| FastRoute | ❌ | - | - |
| Symfony | ✅ | ✅ 10+ | ✅ |
| Laravel | ❌ | - | - |
| Slim | ❌ | - | - |
| AltoRouter | ❌ | - | - |

---

### 15. Expression Language

**Beschreibung**: Suche nach einer Route nach URL und Methode.

**Verwendung:**
```php
use CloudCastle\Http\Router\UrlMatcher;

$matcher = new UrlMatcher($router);

// Найти маршрут
$result = $matcher->match('/users/123', 'GET');
// [
//     'route' => Route instance,
//     'parameters' => ['id' => '123']
// ]

// Проверить существование
if ($matcher->matches('/users', 'GET')) {
    // Route exists
}
```

**Möglichkeiten:**
- ✅ Genaue Suche nach URL
- ✅ Parameter extrahieren
- ✅ Methoden, bei denen die Groß-/Kleinschreibung nicht berücksichtigt wird
- ✅ Trailing slash handling

**Vergleich:**

| Router | URL Matching | Parameters | Check Exists |
|:---|:---:|:---:|:---:|
| **CloudCastle** | **✅** | **✅** | **✅** |
| FastRoute | ✅ | ✅ | ⚠️ |
| Symfony | ✅ | ✅ | ✅ |
| Laravel | ✅ | ✅ | ✅ |
| Slim | ✅ | ✅ | ⚠️ |
| AltoRouter | ✅ | ✅ | ⚠️ |

---

### 16. URL Generation

**Beschreibung**: URLs aus benannten Routen generieren.

**Verwendung:**
```php
use CloudCastle\Http\Router\UrlGenerator;

$generator = new UrlGenerator($router);
$generator->setBaseUrl('https://example.com');

// Простой URL
$url = $generator->generate('home');
// https://example.com/

// С параметрами
$url = $generator->generate('users.show', ['id' => 123]);
// https://example.com/users/123

// С query parameters
$url = $generator->generate('users.show', 
    ['id' => 123],
    ['edit' => 1, 'tab' => 'profile']
);
// https://example.com/users/123?edit=1&tab=profile

// Absolute URL (с авто-определением base)
$url = $generator->absolute('posts.show', [
    'year' => 2025,
    'month' => 10,
    'slug' => 'my-post'
]);
// https://detected-host.com/posts/2025/10/my-post
```

**Vorteile:**
- ✅ Zentralisierte URL-Verwaltung
- ✅ Refactoring-sicher
- ✅ Type-safe parameters
- ✅ Query string support

---

### 17. Route Dumper

**Beschreibung**: Routen zum Debuggen und zur Dokumentation exportieren.

**Ausgabeformate:**
- JSON
- CLI Table
- Array

**Beispiele:**
```php
use CloudCastle\Http\Router\RouteDumper;

$dumper = new RouteDumper($router);

// JSON export (для API)
$json = $dumper->dumpJson();
file_put_contents('routes.json', $json);

// CLI table (для консоли)
echo $dumper->dumpTable();
// --------------------------------------------------------
// | METHOD | URI         | NAME        | ACTION          |
// --------------------------------------------------------
// | GET    | /users      | users.index | UserController  |
// | POST   | /users      | users.store | UserController  |
// --------------------------------------------------------

// Array (для программной обработки)
$routes = $dumper->dump();
foreach ($routes as $route) {
    echo $route['methods'][0] . ' ' . $route['uri'] . "\n";
}
```

**Anwendung:**
- Documentation generation
- Debugging
- Route analysis
- API documentation
- Postman/Swagger export

---

### 18. Route Defaults

**Beschreibung**: Standardwerte für Routenparameter.

**Beispiele:**
```php
// Одно значение
$router->get('/page/{num}', 'PageController@show')
    ->default('num', 1);
// /page → num = 1
// /page/5 → num = 5

// Множественные defaults
$router->get('/archive/{year}/{month}', 'ArchiveController@show')
    ->defaults([
        'year' => 2025,
        'month' => 1
    ]);
// /archive → year = 2025, month = 1
// /archive/2024 → year = 2024, month = 1
// /archive/2024/12 → year = 2024, month = 12

// Комбинация с requirements
$router->get('/blog/{category}/{page}', 'BlogController@index')
    ->where('category', '\w+')
    ->where('page', '\d+')
    ->defaults(['category' => 'all', 'page' => 1]);
```

**Vergleich:**

| Router | Defaults | Multiple | Types |
|:---|:---:|:---:|:---:|
| **CloudCastle** | **✅** | **✅** | **✅ All** |
| FastRoute | ❌ | - | - |
| Symfony | ✅ | ✅ | ✅ All |
| Laravel | ✅ | ✅ | ⚠️ Limited |
| Slim | ⚠️ | ⚠️ | ⚠️ |
| AltoRouter | ✅ | ⚠️ | ⚠️ |

---

### 19. Route Conditions

**Beschreibung**: Bedingungen für das Routing basierend auf Expression Language.

**Beispiele:**
```php
// Основано на роли
$router->get('/admin', 'AdminController@index')
    ->condition('role == "admin"');

// Основано на подписке
$router->get('/premium', 'PremiumController@content')
    ->condition('subscription == "premium" or subscription == "enterprise"');

// Версия API
$router->get('/api/v2/data', 'ApiV2Controller@data')
    ->condition('api_version >= 2');

// Комбинация условий
$router->get('/special', 'SpecialController@index')
    ->condition('user.age >= 18 and user.verified and user.subscription == "premium"');
```

**Vergleich:**

| Router | Conditions |
|:---|:---:|
| **CloudCastle** | **✅ Expression Language** |
| FastRoute | ❌ |
| Symfony | ✅ Expression Language |
| Laravel | ⚠️ Basic |
| Slim | ❌ |
| AltoRouter | ❌ |

---

### 20. Domain Routing

**Beschreibung**: Routing basierend auf Domänen und Subdomänen.

**Beispiele:**
```php
// Поддомен API
$router->get('/data', 'ApiController@data')
    ->domain('api.example.com');

// Wildcard поддомены
$router->get('/dashboard', 'TenantController@dashboard')
    ->domain('{tenant}.example.com');

// Multi-domain
$router->group(['domain' => 'admin.example.com'], function($router) {
    $router->get('/users', 'Admin\UserController@index');
    $router->get('/settings', 'Admin\SettingsController@index');
});
```

**Anwendung:**
- Multi-tenant applications
- Subdomain routing
- API versioning
- Admin panels

**Vergleich:**

| Router | Domain Routing | Wildcards |
|:---|:---:|:---:|
| **CloudCastle** | **✅** | **✅** |
| FastRoute | ❌ | ❌ |
| Symfony | ✅ | ✅ |
| Laravel | ✅ | ✅ |
| Slim | ❌ | ❌ |
| AltoRouter | ❌ | ❌ |

---

### 21. Port Routing

**Beschreibung**: Portbasiertes Routing.

**Einzigartige Funktion!**

**Beispiele:**
```php
// Микросервисная архитектура
$router->get('/users', 'UserService@index')
    ->port(8081);

$router->get('/posts', 'PostService@index')
    ->port(8082);

$router->get('/comments', 'CommentService@index')
    ->port(8083);
```

**Anwendung:**
- Microservices architecture
- Service isolation
- Port-based routing
- Development environments

**Vergleich:**

| Router | Port Routing |
|:---|:---:|
| **CloudCastle** | **✅** |
| FastRoute | ❌ |
| Symfony | ⚠️ Complex |
| Laravel | ❌ |
| Slim | ❌ |
| AltoRouter | ❌ |

---

### 22. Automatische Benennung (einzigartige Funktion!)

**Beschreibung**: Automatische Generierung von Routennamen.

```php
$router->enableAutoNaming();

$router->get('/users/{id}', 'UserController@show');
// Auto name: users.id.get

$router->get('/api/v1/posts', 'PostController@index');
// Auto name: api.v1.posts.get
```

**Regeln:**
- `/users` → `users.get`
- `/users/{id}` → `users.id.get`
- `/api/v1/data` → `api.v1.data.get`
- `/` → `root.get`

[Weitere Details in der Auto-Naming-Dokumentation](auto-naming.md)

### 23. ThrottleWithBan (einzigartige Funktion!)

**Beschreibung**: Ratenbegrenzung mit automatischer Sperre bei Überschreitung.

```php
$router->get('/api/data', 'ApiController@data')
    ->throttleWithBan(
        maxAttempts: 100,        // лимит
        decayMinutes: 1,         // период
        maxViolations: 3,        // нарушений до бана
        banDurationMinutes: 60   // длительность бана
    );
```

**Wirkung:**
- 101. Antrag → Verstoß 1
- 101. Anfrage (2. Fenster) → Verstoß 2
- 101. Anfrage (3. Fenster) → Verstoß 3
- Nächste Anfrage → **VERBOT für 1 Stunde!**

[Weitere Details in der ThrottleWithBan-Dokumentation](throttle-with-ban.md)

### 24. Route Macros

**Beschreibung**: Erstellen Sie mehrere Routen mit einem Befehl.

```php
// RESTful resource - 7 маршрутов!
Route::resource('users', 'UserController');

// API resource
Route::apiResource('posts', 'Api\PostController', 200);

// CRUD - 4 маршрута
Route::crud('comments', 'CommentController');

// Auth routes
Route::auth();

// Admin panel
Route::adminPanel(['office-ip']);

// API versioning
Route::apiVersion('v1', function() {
    Route::apiResource('users', 'Api\V1\UserController', 1000);
});

// Webhooks
Route::webhooks(['webhook-ip']);
```

**Ersparnis**: 80–97 % Code!

[Weitere Details in der Makros-Dokumentation](macros.md)

### 25. Route Shortcuts

**Beschreibung**: Praktische Methoden für häufige Konfigurationen.

**Middleware shortcuts:**
```php
->auth()      // вместо ->middleware('auth')
->guest()     // вместо ->middleware('guest')
->api()       // вместо ->middleware('api')
->admin()     // вместо ->middleware(['auth','admin'])+tag('admin')
```

**Security shortcuts:**
```php
->localhost()         // вместо ->whitelistIp(['127.0.0.1'])
->secure()            // вместо ->port(443)->protocol('https')
```

**Throttle shortcuts:**
```php
->throttleStandard()  // 60 req/min
->throttleStrict()    // 10 req/min
->throttleGenerous()  // 1000 req/min
```

**Composite shortcuts:**
```php
->apiEndpoint(100)   // api + throttle(100) + tag
->protected()        // auth + throttle(100)
```

[Weitere Details in der Shortcuts-Dokumentation](shortcuts.md)

### 26. Helper Functions

**Beschreibung**: Über 15 globale Funktionen.

```php
// Получение маршрутов
route('users.show')      // по имени
current_route()          // текущий
previous_route()         // предыдущий

// Проверки
route_is('users.index')  // проверка текущего
route_has('admin.panel') // существование
route_name()             // имя текущего

// URL generation
route_url('users.show', ['id' => 123])
route_back('/')

// Фильтрация
routes_by_tag('api')

// Статистика
route_stats()

// Dispatch
dispatch_route()

// Router instance
router()
```

[Weitere Details in der Helpers-Dokumentation](helpers.md)

### 27. Tags System

**Beschreibung**: Routen nach Tags gruppieren und filtern.

```php
// Добавление тегов
$router->get('/api/data', 'ApiController@data')
    ->tag('api')
    ->tag(['public', 'free']);

// В группах
$router->group(['tags' => ['api', 'v1']], function($router) {
    $router->get('/users', ...);
});

// Фильтрация
$apiRoutes = routes_by_tag('api');
$adminRoutes = routes_by_tag('admin');
```

[Weitere Details in der Tags-Dokumentation](tags.md)

### 28. Protocol Filtering

```php
// Только HTTPS
$router->get('/secure', 'SecureController@index')
    ->protocol('https');

// WebSocket
$router->get('/ws', 'WebSocketController@handle')
    ->protocol('ws');
```

### 29. Route Caching

```php
$cache = new RouteCache(__DIR__ . '/cache/routes.php');
$router->setCache($cache);

// 7x faster load time!
```

### 30. Analytics Plugin

```php
$analytics = new AnalyticsPlugin();
$router->registerGlobalPlugin($analytics);

$stats = $analytics->getStats();
// Route hits, timing, errors, etc.
```

### 31. Plugins System

```php
$logger = new LoggerPlugin($psr3Logger);
$router->registerGlobalPlugin($logger);

$responseCache = new ResponseCachePlugin(__DIR__ . '/cache/responses');
$router->registerGlobalPlugin($responseCache);
```

### 32. Facade/Static API

```php
use CloudCastle\Http\Router\Facade\Route;

// Laravel-style static API
Route::get('/users', 'UserController@index');
Route::resource('posts', 'PostController');
Route::group(['prefix' => 'api'], function() {
    Route::apiResource('data', 'ApiController', 200);
});
```

[Weitere Details in der Facade-Dokumentation](facade.md)

## 📊 Übersichtstabelle aller Möglichkeiten

| Nein. | Gelegenheit | CloudCastle | FastRoute | Symfony | Laravel | Schlank | Alt |
|:---:|:---|:---:|:---:|:---:|:---:|:---:|:---:|
| 1 | RESTful routing | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ |
| 2 | Named routes | ✅ | ❌ | ✅ | ✅ | ✅ | ✅ |
| 3 | Route groups | ✅ | ❌ | ✅ | ✅ | ✅ | ❌ |
| 4 | Middleware | ✅ | ❌ | ⚠️ | ✅ | ✅ | ❌ |
| 5 | PSR-15 | ✅ | ❌ | ❌ | ❌ | ✅ | ❌ |
| 6 | Rate Limiting | ✅ | ❌ | ❌ | ✅ | ❌ | ❌ |
| 7 | **Auto-ban** | **✅** | **❌** | **❌** | **❌** | **❌** | **❌** |
| 8 | **IP Filtering** | **✅** | **❌** | **❌** | **❌** | **❌** | **❌** |
| 9 | **SSRF Protection** | **✅** | **❌** | **❌** | **❌** | **❌** | **❌** |
| 10 | YAML config | ✅ | ❌ | ✅ | ❌ | ❌ | ❌ |
| 11 | XML config | ✅ | ❌ | ✅ | ❌ | ❌ | ❌ |
| 12 | **JSON config** | **✅** | **❌** | **❌** | **❌** | **❌** | **❌** |
| 13 | PHP Attributes | ✅ | ❌ | ✅ | ⚠️ | ❌ | ❌ |
| 14 | Expression Language | ✅ | ❌ | ✅ | ❌ | ❌ | ❌ |
| 15 | URL Matching | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ |
| 16 | URL Generation | ✅ | ❌ | ✅ | ✅ | ✅ | ✅ |
| 17 | Route Dumper | ✅ | ❌ | ⚠️ | ⚠️ | ❌ | ❌ |
| 18 | Route Defaults | ✅ | ❌ | ✅ | ✅ | ⚠️ | ⚠️ |
| 19 | Route Conditions | ✅ | ❌ | ✅ | ⚠️ | ❌ | ❌ |
| 20 | Domain routing | ✅ | ❌ | ✅ | ✅ | ❌ | ❌ |
| 21 | **Port routing** | **✅** | **❌** | **⚠️** | **❌** | **❌** | **❌** |
| 22 | Protocol filtering | ✅ | ❌ | ⚠️ | ⚠️ | ❌ | ❌ |
| 23 | Route caching | ✅ | ✅ | ✅ | ✅ | ❌ | ❌ |
| 24 | **Analytics** | **✅** | **❌** | **❌** | **⚠️** | **❌** | **❌** |
| 25 | **Plugins** | **✅** | **❌** | **❌** | **❌** | **❌** | **❌** |
| 26 | **Security Logger** | **✅** | **❌** | **❌** | **❌** | **❌** | **❌** |
| 27 | **Route Macros** | **✅ 7+** | **❌** | **⚠️ 2** | **✅ 5** | **❌** | **❌** |
| 28 | **Route Shortcuts** | **✅ 13+** | **❌** | **⚠️ 3** | **✅ 8** | **⚠️ 2** | **❌** |
| 29 | **Helper Functions** | **✅ 15+** | **❌** | **⚠️ 4** | **✅ 8** | **❌** | **❌** |
| 30 | **Tags System** | **✅** | **❌** | **⚠️** | **⚠️** | **❌** | **❌** |
| 31 | **Auto-Naming** | **✅** | **❌** | **❌** | **❌** | **❌** | **❌** |
| 32 | **ThrottleWithBan** | **✅** | **❌** | **❌** | **❌** | **❌** | **❌** |
| 33 | **Facade/Static** | **✅** | **❌** | **❌** | **✅** | **❌** | **❌** |
| | **GESAMT** | **33/33** | **3/33** | **33.11.** | **33.12.** | **7/33** | **4/33** |
| | **Prozent** | **100%** | **9%** | **33%** | **36%** | **21%** | **12%** |

---

## ✅ Fazit

Der CloudCastle HTTP Router bietet **vollständige Funktionen** für die Erstellung moderner Anwendungen:

- 🏆 **33/33 Möglichkeiten** – 100 % Funktionsabdeckung
- 🏆 **11 einzigartige Funktionen** – Konkurrenten haben keine Analoga (einschließlich JSON-Konfiguration)
- 🏆 **100 % kompatibel** mit modernen Standards (PSR-15, PHP 8.2+)
- 🏆 **Enterprise-ready** – bereit für die Produktion

**Zusätzliche Dokumente:**
- [Automatische Benennung](auto-naming.md) – Details zur automatischen Benennung
- [ThrottleWithBan](throttle-with-ban.md) - rate limiting + ban
- [Makros](macros.md) – alle Makros im Detail
- [Verknüpfungen](shortcuts.md) – alle Verknüpfungen
- [Helfer](helpers.md) – alle Hilfsfunktionen
- [Tags](tags.md) – Tag-System
- [Fassade](facade.md) – statische Nutzung

---

*Letzte Aktualisierung: 18. Oktober 2025*

---

[📚 Inhaltsverzeichnis](_table-of-contents.md) | [🏠 Startseite](README.md)

---

[📚 Table of Contents](de/_table-of-contents.md) | [🏠 Home](de/README.md)
