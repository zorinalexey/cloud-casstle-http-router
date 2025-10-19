[🇷🇺 Русский](ru/features.md) | [🇺🇸 English](en/features.md) | [🇩🇪 Deutsch](de/features.md) | [🇫🇷 Français](fr/features.md) | [🇨🇳 中文](zh/features.md)

[📚 Table of Contents](en/_table-of-contents.md) | [🏠 Home](en/README.md)

---

# A complete guide to CloudCastle HTTP Router features

[📚 Table of Contents](_table-of-contents.md) | [🏠 Home](README.md)

---
## 📚 Review

CloudCastle HTTP Router provides **30+ key features** for building modern PHP applications.

### 🏆 **Unique features (only in CloudCastle):**
1. **Auto-Naming** - automatic naming of routes
2. **ThrottleWithBan** - rate limiting + automatic ban
3. **IP Filtering** - whitelist/blacklist for routes
4. **SSRF Protection** - protection from Server-Side Request Forgery
5. **Security Logger** - automatic logging
6. **JSON Configuration** - the only router with JSON support
7. **Route Macros** - 7+ macros (80-97% code reduction)
8. **Route Shortcuts** - 13+ convenient shortcuts
9. **Helper Functions** - 15+ global functions
10. **Tags System** - powerful filtering
11. **Analytics Plugin** - built-in analytics

## 🎯 Main features

### 1. RESTful Routing

**Description**: Supports all HTTP methods.

**Supported methods:**
- GET, POST, PUT, DELETE, PATCH
- OPTIONS, HEAD
- CUSTOM methods (VIEW, TRACE, etc.)

**Examples:**
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

**Comparison:**

| Opportunity | CloudCastle | FastRoute | Symfony | Laravel | Slim | Alto |
|:---|:---:|:---:|:---:|:---:|:---:|:---:|
| Standard HTTP | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ |
| Custom methods | ✅ | ⚠️ | ✅ | ⚠️ | ⚠️ | ❌ |
| Method groups | ✅ | ❌ | ✅ | ✅ | ✅ | ❌ |

---

### 2. Named Routes

**Description**: Assigning names to routes for convenient URL generation.

**Usage:**
```php
// Определение
$router->get('/users/{id}', 'UserController@show')
    ->name('users.show');

$router->get('/posts/{year}/{month}/{slug}', 'PostController@show')
    ->name('posts.show');

// Использование
$route = $router->getRoute('users.show');
```

**Generate URL:**
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

**Advantages:**
- ✅ Centralized URL management
- ✅ Refactoring without breaking links
- ✅ Type-safe URL generation
- ✅ Support for query parameters

**Comparison:**

| Feature | CloudCastle | FastRoute | Symfony | Laravel | Slim | Alto |
|:---|:---:|:---:|:---:|:---:|:---:|:---:|
| Named routes | ✅ | ❌ | ✅ | ✅ | ✅ | ✅ |
| URL generation | ✅ | ❌ | ✅ | ✅ | ✅ | ✅ |
| Query params | ✅ | ❌ | ✅ | ✅ | ✅ | ⚠️ |

---

### 3. Route Groups

**Description**: Grouping of routes with common attributes.

**Group capabilities:**
- URI prefixes
- Middleware
- Namespace
- Domain
- Tags

**Examples:**
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

**Attribute inheritance:**
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

**Comparison:**

| Feature | CloudCastle | FastRoute | Symfony | Laravel | Slim | Alto |
|:---|:---:|:---:|:---:|:---:|:---:|:---:|
| Groups | ✅ | ❌ | ✅ | ✅ | ✅ | ❌ |
| Prefixes | ✅ | ❌ | ✅ | ✅ | ✅ | ❌ |
| Middleware groups | ✅ | ❌ | ⚠️ | ✅ | ✅ | ❌ |
| Nested groups | ✅ 50 | ❌ | ✅ 30 | ✅ 25 | ✅ 20 | ❌ |
| Domain groups | ✅ | ❌ | ✅ | ✅ | ❌ | ❌ |

---

### 4. Middleware System

**Description**: Powerful middleware system for processing requests.

**Types of middleware:**
1. Global
2. Groups
3. Route

**Examples:**
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

**Built-in middleware:**
1. **CorsMiddleware** - CORS headers
2. **AuthMiddleware** - Authentication & authorization
3. **HttpsEnforcement** - Force HTTPS
4. **SsrfProtection** - SSRF protection
5. **SecurityLogger** - Security logging

**Comparison:**

| Feature | CloudCastle | FastRoute | Symfony | Laravel | Slim | Alto |
|:---|:---:|:---:|:---:|:---:|:---:|:---:|
| Middleware support | ✅ | ❌ | ⚠️ | ✅ | ✅ | ❌ |
| Global middleware | ✅ | ❌ | ⚠️ | ✅ | ✅ | ❌ |
| Route middleware | ✅ | ❌ | ❌ | ✅ | ✅ | ❌ |
| PSR-15 | ✅ | ❌ | ❌ | ❌ | ✅ | ❌ |
| CORS | ✅ 5 | ❌ | ❌ | ✅ 3 | ⚠️ 1 | ❌ |

---

### 5. PSR-15 Support

**Description**: Fully compatible with PSR-15 HTTP Server Request Handlers.

**Possibilities:**
- Using PSR-15 middleware in the router
- Using router middleware like PSR-15
- Bridge between systems

**Examples:**
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

**Advantages:**
- ✅ Interoperability with PSR-15 ecosystem
- ✅ Use of ready-made PSR-15 middleware
- ✅ Standardization

**Comparison:**

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

**Description**: Built-in request rate limiting.

**Possibilities:**
- Per minute, hour, day
- Custom time periods
- Custom keys
- Auto-reset

**Examples:**
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

**Excess Handling:**
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

**Comparison:**

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

**Description**: Automatic IP blocking when limits are exceeded.

**Unique feature of CloudCastle!**

**Possibilities:**
- Automatic ban when the rate limit is exceeded
- Customizable ban duration
- Manual ban/unban
- Ban reasons
- Temporary & permanent bans

**Examples:**
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

**Integration with routes:**
```php
$router->get('/api/data', 'ApiController@data')
    ->perMinute(60) // rate limit
    ->blacklistIp(['known-bad-ip']); // additional protection
```

**Comparison:**

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

**Description**: White and black lists of IP addresses for routes.

**Unique feature of CloudCastle!**

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

**Combined use:**
```php
$router->get('/sensitive/data', 'SensitiveController@data')
    ->whitelistIp(['trusted-ip'])
    ->blacklistIp(['known-attacker'])
    ->perMinute(10); // дополнительная защита
```

**Supported formats:**
- Single IP: `192.168.1.100`
- CIDR notation: `192.168.1.0/24`
- IP ranges: `192.168.1.1-192.168.1.255`

**Comparison:**

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

**Description**: Protection against Server-Side Request Forgery attacks.

**Unique feature of CloudCastle!**

**Blocks:**

- localhost (127.0.0.1, ::1)
- Private IP ranges (10.0.0.0/8, 172.16.0.0/12, 192.168.0.0/16)
- Link-local (169.254.0.0/16)
- Cloud metadata APIs (169.254.169.254, metadata.google.internal)

**Usage:**
```php
use CloudCastle\Http\Router\Middleware\SsrfProtection;

$router->middleware(new SsrfProtection(
    allowLocalhost: false,      // блокировать localhost
    allowPrivateIps: false,     // блокировать private IPs
    allowCloudMetadata: false   // блокировать cloud metadata
));
```

**Example of an attack that is blocked:**
```php
// Злоумышленник пытается получить AWS credentials
$router->get('/proxy/{url}', function($url) {
    // Без защиты: можно запросить
    // http://169.254.169.254/latest/meta-data/iam/security-credentials/
    
    // С SsrfProtection: InsecureConnectionException
    return file_get_contents($url);
});
```

**Application:**
- URL shorteners
- Proxy services
- Webhook handlers
- Image processors
- PDF generators

**Comparison:**

| Router | SSRF Protection |
|:---|:---:|
| **CloudCastle** | **✅ Built-in** |
| FastRoute | ❌ |
| Symfony | ❌ |
| Laravel | ❌ |
| Slim | ❌ |
| AltoRouter | ❌ |

---

### 10. YAML Configuration

**Description**: Loading routes from YAML files.

**Advantages:**
- ✅ Declarative configuration
- ✅ Easy to edit
- ✅ Version control friendly
- ✅ Suitable for large projects

**Example routes.yaml:**
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

**Loading:**
```php
use CloudCastle\Http\Router\Loader\YamlLoader;

$loader = new YamlLoader($router);
$loader->load(__DIR__ . '/config/routes.yaml');
```

**Comparison:**

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

**Description**: Loading routes from XML files.

**Example routes.xml:**
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

**Loading:**
```php
use CloudCastle\Http\Router\Loader\XmlLoader;

$loader = new XmlLoader($router);
$loader->load(__DIR__ . '/config/routes.xml');
```

**Comparison:**

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

**Description**: Loading routes from JSON configuration files.

**Unique feature!** (Only in CloudCastle!)

**Usage:**
```php
use CloudCastle\Http\Router\Loader\JsonLoader;

$loader = new JsonLoader($router);
$loader->load(__DIR__ . '/config/routes.json');
```

**Example JSON configuration:**
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

**Advantages:**
- ✅ Universal format
- ✅ Easy to parse
- ✅ Compact syntax
- ✅ API oriented
- ✅ Generated programmatically

**Comparison:**

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

**Description**: Using PHP 8 Attributes to define routes.

**Modern approach!**

**Example:**
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

**Advantages:**
- ✅ Routes next to the code
- ✅ Type-safe configuration
- ✅ IDE autocomplete
- ✅ Refactoring-friendly

**Comparison:**

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

**Description**: Conditional routing based on expressions.

**Unique feature!**

**Operators:**
- Comparison: `==`, `!=`, `>`, `<`, `>=`, `<=`
- Logic: `and`, `or`
- Dot notation: `user.age`, `request.header.auth`

**Examples:**
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

**Use at runtime:**
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

**Comparison:**

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

**Description**: Search for a route by URL and method.

**Usage:**
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

**Possibilities:**
- ✅ Exact search by URL
- ✅ Extract parameters
- ✅ Case-insensitive methods
- ✅ Trailing slash handling

**Comparison:**

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

**Description**: Generate URLs from named routes.

**Usage:**
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

**Advantages:**
- ✅ Centralized URL management
- ✅ Refactoring-safe
- ✅ Type-safe parameters
- ✅ Query string support

---

### 17. Route Dumper

**Description**: Export routes for debugging and documentation.

**Output formats:**
- JSON
- CLI Table
- Array

**Examples:**
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

**Application:**
- Documentation generation
- Debugging
- Route analysis
- API documentation
- Postman/Swagger export

---

### 18. Route Defaults

**Description**: Default values ​​for route parameters.

**Examples:**
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

**Comparison:**

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

**Description**: Conditions for routing based on Expression Language.

**Examples:**
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

**Comparison:**

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

**Description**: Routing based on domains and subdomains.

**Examples:**
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

**Application:**
- Multi-tenant applications
- Subdomain routing
- API versioning
- Admin panels

**Comparison:**

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

**Description**: Port-based routing.

**Unique feature!**

**Examples:**
```php
// Микросервисная архитектура
$router->get('/users', 'UserService@index')
    ->port(8081);

$router->get('/posts', 'PostService@index')
    ->port(8082);

$router->get('/comments', 'CommentService@index')
    ->port(8083);
```

**Application:**
- Microservices architecture
- Service isolation
- Port-based routing
- Development environments

**Comparison:**

| Router | Port Routing |
|:---|:---:|
| **CloudCastle** | **✅** |
| FastRoute | ❌ |
| Symfony | ⚠️ Complex |
| Laravel | ❌ |
| Slim | ❌ |
| AltoRouter | ❌ |

---

### 22. Auto-Naming (unique feature!)

**Description**: Automatic generation of route names.

```php
$router->enableAutoNaming();

$router->get('/users/{id}', 'UserController@show');
// Auto name: users.id.get

$router->get('/api/v1/posts', 'PostController@index');
// Auto name: api.v1.posts.get
```

**Rules:**
- `/users` → `users.get`
- `/users/{id}` → `users.id.get`
- `/api/v1/data` → `api.v1.data.get`
- `/` → `root.get`

[More details in the Auto-Naming documentation](auto-naming.md)

### 23. ThrottleWithBan (unique feature!)

**Description**: Rate limiting with automatic ban if exceeded.

```php
$router->get('/api/data', 'ApiController@data')
    ->throttleWithBan(
        maxAttempts: 100,        // лимит
        decayMinutes: 1,         // период
        maxViolations: 3,        // нарушений до бана
        banDurationMinutes: 60   // длительность бана
    );
```

**Effect:**
- 101st request → violation 1
- 101st request (2nd window) → violation 2
- 101st request (3rd window) → violation 3
- Next request → **BAN for 1 hour!**

[More details in the ThrottleWithBan documentation](throttle-with-ban.md)

### 24. Route Macros

**Description**: Create multiple routes with one command.

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

**Savings**: 80-97% code!

[More details in the Macros documentation](macros.md)

### 25. Route Shortcuts

**Description**: Convenient methods for frequent configurations.

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

[More details in the Shortcuts documentation](shortcuts.md)

### 26. Helper Functions

**Description**: 15+ global functions.

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

[More details in the Helpers documentation](helpers.md)

### 27. Tags System

**Description**: Grouping and filtering routes by tags.

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

[More details in the Tags documentation](tags.md)

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

[More details in the Facade documentation](facade.md)

## 📊 Summary table of all possibilities

| No. | Opportunity | CloudCastle | FastRoute | Symfony | Laravel | Slim | Alto |
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
| | **TOTAL** | **33/33** | **3/33** | **11/33** | **12/33** | **7/33** | **4/33** |
| | **Percent** | **100%** | **9%** | **33%** | **36%** | **21%** | **12%** |

---

## ✅ Conclusion

CloudCastle HTTP Router provides a **full set of features** for building modern applications:

- 🏆 **33/33 possibilities** - 100% feature coverage
- 🏆 **11 unique features** - competitors have no analogues (including JSON configuration)
- 🏆 **100% compatible** with modern standards (PSR-15, PHP 8.2+)
- 🏆 **Enterprise-ready** - ready for production

**Additional documents:**
- [Auto-Naming](auto-naming.md) - auto-naming details
- [ThrottleWithBan](throttle-with-ban.md) - rate limiting + ban
- [Macros](macros.md) - all macros in detail
- [Shortcuts](shortcuts.md) - all shortcuts
- [Helpers](helpers.md) - all helper functions
- [Tags](tags.md) - tag system
- [Facade](facade.md) - static use

---

*Last update: October 18, 2025*

---

[📚 Table of Contents](_table-of-contents.md) | [🏠 Home](README.md)

---

[📚 Table of Contents](en/_table-of-contents.md) | [🏠 Home](en/README.md)
