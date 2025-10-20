# Liste Complète des Fonctionnalités CloudCastle HTTP Router

---

## 📚 Navigation de la Documentation

[README](../../README.md) | [USER_GUIDE](USER_GUIDE.md) | [FEATURES_INDEX](FEATURES_INDEX.md) | [API_REFERENCE](API_REFERENCE.md) | [ALL_FEATURES](ALL_FEATURES.md) | [TESTS_SUMMARY](TESTS_SUMMARY.md) | [PERFORMANCE](PERFORMANCE_ANALYSIS.md) | [SECURITY](SECURITY_REPORT.md) | [COMPARISON](COMPARISON.md) | [FAQ](FAQ.md) | [DOC_SUMMARY](DOCUMENTATION_SUMMARY.md)

**Documentation détaillée:** [Features](features/) (22 fichiers) | [Tests](tests/) (7 rapports)

---


[English](../en/ALL_FEATURES.md) | **chezavecavecàet** | [Deutsch](../de/ALL_FEATURES.md) | [Français](../fr/ALL_FEATURES.md) | [中文](../zh/ALL_FEATURES.md)

---

## Table des Matières

- [1. Базовая маршрутизация](#1-базовая-маршрутизация)
- [2. Helper Functions](#2-helper-functions)
- [3. Route Shortcuts](#3-route-shortcuts)
- [4. Route Macros](#4-route-macros)
- [5. Группы маршрутов](#5-группы-маршрутов)
- [6. Middleware](#6-middleware)
- [7. Rate Limiting](#7-rate-limiting)
- [8. IP Filtering](#8-ip-filtering)
- [9. Auto-Ban System](#9-auto-ban-system)
- [10. Именованные маршруты](#10-именованные-маршруты)
- [11. Теги](#11-теги)
- [12. Параметры маршрутов](#12-параметры-маршрутов)
- [13. Expression Language](#13-expression-language)
- [14. URL Generation](#14-url-generation)
- [15. Кеширование](#15-кеширование)
- [16. Plugins](#16-plugins)
- [17. Loaders](#17-loaders)
- [18. PSR Support](#18-psr-support)
- [19. Action Resolver](#19-action-resolver)
- [20. Статистика и фильтрация](#20-статистика-и-фильтрация)

---

## 1. Base Routage

### HTTP Methods

```php
use CloudCastle\Http\Router\Router;

$router = new Router();

// Все стандартные методы
$router->get('/users', $action);
$router->post('/users', $action);
$router->put('/users/{id}', $action);
$router->patch('/users/{id}', $action);
$router->delete('/users/{id}', $action);

// Кастомные методы
$router->view('/page', $action);  // VIEW метод
$router->custom('PURGE', '/cache', $action);  // Любой метод

// Множественные методы
$router->match(['GET', 'POST'], '/form', $action);
$router->any('/endpoint', $action);  // Все методы
```

### Facade API

```php
use CloudCastle\Http\Router\Facade\Route;

Route::get('/api/users', $action);
Route::post('/api/users', $action);
// И так далее...
```

---

## 2. Helper Functions

### route()

surchezet route par etet etet àchezet route:

```php
// Получить маршрут по имени
$route = route('users.show');

// Получить текущий маршрут
$current = route();
```

### current_route()

surchezet àchezet route:

```php
$currentRoute = current_route();
echo $currentRoute->getName();
```

### previous_route()

surchezet précédent route:

```php
$prevRoute = previous_route();
```

### route_is()

surdanset et àchezsur route:

```php
if (route_is('users.index')) {
    // Текущий маршрут users.index
}
```

### route_name()

surchezet et àchezsur route:

```php
$name = route_name(); // 'users.show'
```

### router()

surchezet à surchez:

```php
$router = router();
$stats = $router->getRouteStats();
```

### dispatch_route()

etavecetet àchezsur HTTP requête:

```php
$route = dispatch_route();
$result = $route->run();
```

### route_url()

et URL pour etsurdanssursur route:

```php
$url = route_url('users.show', ['id' => 5]);
// /users/5
```

### route_has()

surdanset avecchezavecdanssurdanset route:

```php
if (route_has('users.show')) {
    // Маршрут существует
}
```

### route_stats()

surchezet avecetavecetàchez routesurdans:

```php
$stats = route_stats();
// [
//     'total' => 100,
//     'named' => 80,
//     'tagged' => 50,
//     ...
// ]
```

### routes_by_tag()

surchezet routes par chez:

```php
$apiRoutes = routes_by_tag('api');
```

### route_back()

URL pour danssurdans sur précédent route:

```php
$backUrl = route_back(); // URI предыдущего маршрута
$backUrl = route_back('/default'); // С fallback
```

---

## 3. Route Shortcuts

sur méthodes pour avecsur suravecsuràet routesurdans:

### auth()

avecsur surdanset middleware 'auth':

```php
Route::get('/dashboard', $action)->auth();
// Эквивалент: ->middleware('auth')
```

### guest()

suràsur pour danssuretsurdans:

```php
Route::get('/login', $action)->guest();
```

### api()

API middleware:

```php
Route::get('/api/data', $action)->api();
```

### web()

Web middleware:

```php
Route::get('/page', $action)->web();
```

### cors()

CORS middleware:

```php
Route::post('/api/external', $action)->cors();
```

### localhost()

suràsur pour localhost:

```php
Route::get('/debug', $action)->localhost();
// Эквивалент: ->whitelistIp(['127.0.0.1', '::1'])
```

### secure()

etchezet HTTPS:

```php
Route::post('/payment', $action)->secure();
// Эквивалент: ->https()
```

### throttleStandard()

 rate limit (60 req/min):

```php
Route::get('/api/data', $action)->throttleStandard();
```

### throttleStrict()

suret rate limit (10 req/min):

```php
Route::post('/api/sensitive', $action)->throttleStrict();
```

### throttleGenerous()

 rate limit (1000 req/min):

```php
Route::get('/api/public', $action)->throttleGenerous();
```

### public()

suret àà chezet route:

```php
Route::get('/about', $action)->public();
// Эквивалент: ->tag('public')
```

### private()

suret àà etdans route:

```php
Route::get('/settings', $action)->private();
```

### admin()

et route avec danssursuravecsuràsur:

```php
Route::get('/admin/users', $action)->admin();
// Эквивалент: ->middleware(['auth', 'admin'])->tag('admin')
```

### apiEndpoint()

avec suravecsurà API endpoint:

```php
Route::get('/api/users', $action)->apiEndpoint(100);
// Эквивалент: ->api()->throttle(100, 1)->tag('api')
```

### protected()

et avecchezavec:

```php
Route::get('/profile', $action)->protected();
// Эквивалент: ->auth()->throttle(100, 1)
```

---

## 4. Route Macros

desurdans sur pour etet .

### resource()

RESTful resource routes:

```php
use CloudCastle\Http\Router\RouteMacros;

// Создает 7 маршрутов для CRUD
RouteMacros::resource('users', UserController::class);

// Создаются маршруты:
// GET    /users           -> users.index   (index)
// GET    /users/create    -> users.create  (create)
// POST   /users           -> users.store   (store)
// GET    /users/{id}      -> users.show    (show)
// GET    /users/{id}/edit -> users.edit    (edit)
// PUT    /users/{id}      -> users.update  (update)
// DELETE /users/{id}      -> users.destroy (destroy)
```

### apiResource()

API resource avec rate limiting:

```php
// API resource с автонастройкой
RouteMacros::apiResource('products', ProductController::class, 100);

// Создаются маршруты:
// GET    /products        -> products.index  (100 req/min)
// POST   /products        -> products.store  (50 req/min)
// GET    /products/{id}   -> products.show   (100 req/min)
// PUT    /products/{id}   -> products.update (50 req/min)
// DELETE /products/{id}   -> products.destroy (50 req/min)
```

### crud()

sur CRUD:

```php
RouteMacros::crud('posts', PostController::class);

// Создаются маршруты:
// GET    /posts       -> index
// POST   /posts       -> create
// PUT    /posts/{id}  -> update
// DELETE /posts/{id}  -> delete
```

### auth()

desurdans routes chezetetàetet:

```php
RouteMacros::auth();

// Создаются маршруты:
// GET  /login              -> login          (guest)
// POST /login              -> login.post     (guest, 10 req/min)
// POST /logout             -> logout         (auth)
// GET  /register           -> register       (guest)
// POST /register           -> register.post  (guest, 3 req/10min)
// GET  /password/reset     -> password.request (guest)
// POST /password/email     -> password.email (guest, 3 req/min)
```

### adminPanel()

et  avec etsur:

```php
RouteMacros::adminPanel(['192.168.1.0/24']);

// Создаются защищенные маршруты:
// GET /admin/dashboard -> admin.dashboard
// GET /admin/users     -> admin.users
// GET /admin/settings  -> admin.settings
// + middleware: auth, admin
// + IP whitelist: 192.168.1.0/24
// + throttle: 100 req/min
```

### apiVersion()

API dansavecetsuretsurdanset:

```php
RouteMacros::apiVersion('v1', function() {
    Route::get('/users', $action);
    Route::get('/posts', $action);
});

// Создаются маршруты:
// GET /api/v1/users
// GET /api/v1/posts
// + middleware: api
// + throttle: 100 req/min
// + tags: api, v1
```

### webhooks()

Webhooks avec etsur:

```php
RouteMacros::webhooks(['10.0.0.0/8']);

// Создаются маршруты:
// POST /webhooks/github -> webhook.github
// POST /webhooks/stripe -> webhook.stripe
// POST /webhooks/paypal -> webhook.paypal
// + middleware: verify_webhook_signature
// + throttle: 1000 req/min
// + IP whitelist
```

---

## 5. Groupes routesurdans

### Préfixe

```php
$router->group(['prefix' => '/api/v1'], function() {
    $router->get('/users', $action);  // /api/v1/users
    $router->get('/posts', $action);  // /api/v1/posts
});
```

### Middleware dans chez

```php
$router->group(['middleware' => [AuthMiddleware::class]], function() {
    $router->get('/dashboard', $action);
    $router->get('/profile', $action);
});
```

### sur groupes

```php
$router->group(['prefix' => '/api'], function() {
    $router->group(['prefix' => '/v1'], function() {
        $router->get('/users', $action);  // /api/v1/users
    });
});
```

### sur

```php
$router->group(['domain' => 'api.example.com'], function() {
    $router->get('/users', $action);
});
```

### sur

```php
$router->group(['port' => 8080], function() {
    $router->get('/admin', $action);
});
```

### Namespace

```php
$router->group(['namespace' => 'App\\Controllers\\Admin'], function() {
    $router->get('/dashboard', 'DashboardController@index');
    // Полный класс: App\Controllers\Admin\DashboardController
});
```

### suretsuret attributs

```php
$router->group([
    'prefix' => '/admin',
    'middleware' => ['auth', 'admin'],
    'domain' => 'admin.example.com',
    'port' => 8443,
    'https' => true,
    'whitelistIp' => ['192.168.1.0/24'],
    'throttle' => 100,
    'tags' => ['admin', 'secure'],
], function() {
    // Все атрибуты применяются к маршрутам
});
```

---

## 6. Middleware

### sur middleware

```php
$router->middleware([
    CorsMiddleware::class,
    SecurityMiddleware::class
]);
```

### Middleware sur route

```php
Route::get('/dashboard', $action)
    ->middleware([AuthMiddleware::class, AdminMiddleware::class]);
```

### avecsur middleware

etetdeà dansà:

- `AuthMiddleware` - surdansà chezetetàetet
- `CorsMiddleware` - CORS sursurdansàet
- `HttpsEnforcement` - etchezet HTTPS
- `SecurityLogger` - suretsurdanset suravecsuravecet
- `SsrfProtection` - et de SSRF

```php
use CloudCastle\Http\Router\Middleware\CorsMiddleware;

Route::get('/api/data', $action)
    ->middleware(CorsMiddleware::class);
```

---

## 7. Rate Limiting

### surdanssur etavecparsurdanset

```php
// 60 запросов в минуту
Route::get('/api/data', $action)->throttle(60, 1);

// 100 запросов в час
Route::post('/api/submit', $action)->throttle(100, 60);
```

###  TimeUnit enum

```php
use CloudCastle\Http\Router\TimeUnit;

// 100 запросов в день
Route::post('/api/report', $action)
    ->throttle(100, TimeUnit::DAY->value);

// 10 запросов в неделю
Route::post('/api/export', $action)
    ->throttle(10, TimeUnit::WEEK->value);

// Доступные единицы:
// TimeUnit::SECOND (1)
// TimeUnit::MINUTE (60)
// TimeUnit::HOUR (3600)
// TimeUnit::DAY (86400)
// TimeUnit::WEEK (604800)
// TimeUnit::MONTH (2592000 - 30 дней)
```

### Personnalisé à

```php
Route::get('/api/search', $action)
    ->throttle(30, 1, function($request) {
        return $request->user()->id;  // Лимит на пользователя
    });
```

### RateLimiter surchez

```php
use CloudCastle\Http\Router\RateLimiter;

$limiter = new RateLimiter(60, 1);  // 60 req/min

// Проверить лимит
if ($limiter->tooManyAttempts($identifier)) {
    $retryAfter = $limiter->availableIn($identifier);
    throw new TooManyRequestsException('Retry after ' . $retryAfter);
}

// Зарегистрировать попытку
$limiter->attempt($identifier);

// Получить оставшиеся попытки
$remaining = $limiter->remaining($identifier);
```

---

## 8. IP Filtering

### Whitelist

```php
// Один IP
Route::get('/admin', $action)
    ->whitelistIp('192.168.1.100');

// Множественные IP
Route::get('/admin', $action)
    ->whitelistIp(['192.168.1.100', '192.168.1.101']);

// CIDR нотация
Route::get('/admin', $action)
    ->whitelistIp(['192.168.1.0/24', '10.0.0.0/8']);
```

### Blacklist

```php
// Блокировать конкретные IP
Route::get('/public', $action)
    ->blacklistIp(['1.2.3.4', '5.6.7.8']);

// CIDR
Route::get('/api', $action)
    ->blacklistIp(['1.2.3.0/24']);
```

### suretsuret

```php
Route::group(['whitelistIp' => ['192.168.0.0/16']], function() {
    Route::get('/internal-api', $action)
        ->blacklistIp(['192.168.1.100']); // Кроме этого IP
});
```

---

## 9. Auto-Ban System

### BanManager

```php
use CloudCastle\Http\Router\BanManager;

$banManager = new BanManager();

// Включить автобан после 5 неудачных попыток
$banManager->enableAutoBan(5);

// Установить длительность бана (в секундах)
$banManager->setAutoBanDuration(3600); // 1 час

// Вручную забанить IP
$banManager->ban('1.2.3.4', 3600);

// Проверить бан
if ($banManager->isBanned('1.2.3.4')) {
    throw new BannedException('Your IP is banned');
}

// Разбанить
$banManager->unban('1.2.3.4');

// Получить все заблокированные IP
$banned = $banManager->getBannedIps();

// Очистить все баны
$banManager->clearAll();
```

---

## 10. surdans routes

### suret etet

```php
Route::get('/users/{id}', $action)->name('users.show');
```

### Obtenir route

```php
$route = $router->getRouteByName('users.show');
$route = route('users.show'); // через helper
```

### surdansà àchezsur route

```php
if (route_is('users.show')) {
    // Текущий маршрут users.show
}

if ($router->currentRouteNamed('users.show')) {
    // То же самое
}
```

### Auto-naming

```php
$router->enableAutoNaming();

Route::get('/api/users/{id}', $action);
// Автоматически: 'api.users.id.get'

Route::post('/admin/settings', $action);
// Автоматически: 'admin.settings.post'
```

---

## 11. et

### surdanset surdans

```php
// Один тег
Route::get('/api/users', $action)->tag('api');

// Множественные теги
Route::get('/admin/users', $action)->tag(['admin', 'users', 'private']);
```

### Obtenir routesurdans par chez

```php
$apiRoutes = $router->getRoutesByTag('api');
$publicRoutes = routes_by_tag('public'); // через helper
```

### surdansà 

```php
if ($router->hasTag('api')) {
    // Есть маршруты с тегом 'api'
}
```

### Obtenir tous surdans

```php
$tags = $router->getAllTags();
// ['api', 'admin', 'public', ...]
```

---

## 12. Paramètres routesurdans

### Basiques paramètres

```php
Route::get('/users/{id}', function($id) {
    return "User: $id";
});
```

###  contrainteset (where)

```php
// Только цифры
Route::get('/users/{id}', $action)
    ->where('id', '[0-9]+');

// Только буквы
Route::get('/posts/{slug}', $action)
    ->where('slug', '[a-z0-9-]+');

// Множественные ограничения
Route::get('/posts/{category}/{slug}', $action)
    ->where([
        'category' => '[a-z]+',
        'slug' => '[a-z0-9-]+'
    ]);
```

### Optionnels paramètres

```php
Route::get('/search/{query?}', function($query = null) {
    return "Search: " . ($query ?? 'all');
});
```

### Valeurs par défaut

```php
Route::get('/page/{page}', $action)
    ->defaults(['page' => 1]);
```

### Inline 

```php
// Паттерн прямо в URI
Route::get('/users/{id:[0-9]+}', $action);
Route::get('/posts/{slug:[a-z0-9-]+}', $action);
```

---

## 13. Expression Language

avecsurdanssur Routage sur suravecsurdans danset:

```php
use CloudCastle\Http\Router\ExpressionLanguage\ExpressionLanguage;

$lang = new ExpressionLanguage();

// Простые сравнения
Route::get('/api/data', $action)
    ->condition('request.user.role == "admin"');

// Логические операторы
Route::get('/premium', $action)
    ->condition('request.user.subscribed and request.user.active');

// Сложные условия
Route::get('/special', $action)
    ->condition('request.ip == "192.168.1.1" or request.user.admin');

// Встроенные операторы:
// ==, !=, >, <, >=, <=
// and, or
```

surdansà chezavecsurdanset:

```php
$result = $lang->evaluate('user.age >= 18', [
    'user' => ['age' => 25]
]);
// true
```

---

## 14. URL Generation

### UrlGenerator

```php
use CloudCastle\Http\Router\UrlGenerator;

$generator = new UrlGenerator($router);

// Базовое использование
$url = $generator->generate('users.show', ['id' => 5]);
// /users/5

// С query параметрами
$url = $generator->generate('users.index', [], ['page' => 2, 'sort' => 'name']);
// /users?page=2&sort=name

// Установить base URL
$generator->setBaseUrl('https://example.com');
$url = $generator->generate('users.show', ['id' => 5]);
// https://example.com/users/5

// Абсолютный URL
$url = $generator->absolute('users.show', ['id' => 5]);
// https://example.com/users/5

// С доменом
$url = $generator->toDomain('api.example.com', 'api.users', ['id' => 5]);
// https://api.example.com/api/users/5

// С протоколом
$url = $generator->toProtocol('https', 'users.show', ['id' => 5]);
// https://example.com/users/5

// Signed URL (с подписью)
$url = $generator->signed('verify.email', ['token' => 'abc123'], 3600);
// /verify/email?token=abc123&signature=...&expires=...
```

### Helper function

```php
$url = route_url('users.show', ['id' => 5]);
// /users/5
```

---

## 15. etsurdanset

### àet à

```php
// С директорией по умолчанию
$router->enableCache();

// С кастомной директорией
$router->enableCache('/custom/cache/path');
```

### suretet

```php
// Компилировать маршруты в кеш
$router->compile();

// Принудительная компиляция
$router->compile(true);
```

### chezà et à

```php
// Автозагрузка при наличии кеша
if ($router->loadFromCache()) {
    // Маршруты загружены из кеша
}
```

### etavecà à

```php
$router->clearCache();
```

### danssuràsuretet

```php
// Компилировать автоматически при shutdown
$router->autoCompile();

// В конце скрипта
register_shutdown_function(function() use ($router) {
    $router->autoCompile();
});
```

### RouteCache àavecavec

```php
use CloudCastle\Http\Router\RouteCache;

$cache = new RouteCache('/path/to/cache');

// Сохранить
$cache->put($compiledRoutes);

// Получить
$cached = $cache->get();

// Проверить существование
if ($cache->exists()) {
    // Кеш существует
}

// Очистить
$cache->clear();

// Включить/выключить
$cache->setEnabled(false);
```

---

## 16. Plugins

### suret etsur

```php
use CloudCastle\Http\Router\Contracts\PluginInterface;
use CloudCastle\Http\Router\Route;
use CloudCastle\Http\Router\Router;

class MyPlugin implements PluginInterface
{
    public function getName(): string
    {
        return 'my-plugin';
    }
    
    public function boot(Router $router): void
    {
        // Инициализация при загрузке
    }
    
    public function beforeDispatch(Route $route, string $uri, string $method): void
    {
        // До выполнения маршрута
        error_log("Dispatching: $method $uri");
    }
    
    public function afterDispatch(Route $route, mixed $result): mixed
    {
        // После выполнения маршрута
        error_log("Result: " . json_encode($result));
        return $result;
    }
    
    public function onRouteRegistered(Route $route): void
    {
        // При регистрации маршрута
    }
    
    public function onException(\Exception $exception): void
    {
        // При исключении
        error_log("Exception: " . $exception->getMessage());
    }
    
    public function isEnabled(): bool
    {
        return true;
    }
}
```

### etavecet etsur

```php
// Глобальный плагин
$router->registerPlugin(new MyPlugin());

// На конкретном маршруте
Route::get('/api/data', $action)
    ->plugins([new AnalyticsPlugin()]);
```

### avecsur et

```php
use CloudCastle\Http\Router\Plugin\LoggerPlugin;
use CloudCastle\Http\Router\Plugin\AnalyticsPlugin;
use CloudCastle\Http\Router\Plugin\ResponseCachePlugin;

// Logger
$router->registerPlugin(new LoggerPlugin('/path/to/log'));

// Analytics
$router->registerPlugin(new AnalyticsPlugin());

// Response Cache
$router->registerPlugin(new ResponseCachePlugin(3600));
```

### danset etsuret

```php
// Получить плагин
$plugin = $router->getPlugin('my-plugin');

// Проверить наличие
if ($router->hasPlugin('logger')) {
    // ...
}

// Удалить плагин
$router->unregisterPlugin('my-plugin');

// Получить все плагины
$plugins = $router->getPlugins();
```

---

## 17. Loaders

### JsonLoader

```php
use CloudCastle\Http\Router\Loader\JsonLoader;

$loader = new JsonLoader($router);
$loader->load('routes.json');
```

**routes.json:**
```json
{
  "routes": [
    {
      "methods": ["GET"],
      "uri": "/users",
      "action": "UserController@index",
      "name": "users.index",
      "middleware": ["auth"]
    }
  ]
}
```

### YamlLoader

```php
use CloudCastle\Http\Router\Loader\YamlLoader;

$loader = new YamlLoader($router);
$loader->load('routes.yaml');
```

**routes.yaml:**
```yaml
routes:
  - methods: [GET]
    uri: /users
    action: UserController@index
    name: users.index
```

### XmlLoader

```php
use CloudCastle\Http\Router\Loader\XmlLoader;

$loader = new XmlLoader($router);
$loader->load('routes.xml');
```

**routes.xml:**
```xml
<routes>
    <route methods="GET" uri="/users" action="UserController@index" name="users.index"/>
</routes>
```

### PhpLoader

```php
use CloudCastle\Http\Router\Loader\PhpLoader;

$loader = new PhpLoader($router);
$loader->load('routes.php');
```

**routes.php:**
```php
return [
    ['GET', '/users', 'UserController@index', 'users.index'],
    ['POST', '/users', 'UserController@store', 'users.store'],
];
```

### AttributeLoader

```php
use CloudCastle\Http\Router\Loader\AttributeLoader;

$loader = new AttributeLoader($router);
$loader->loadFromDirectory('app/Controllers');
```

**Controller avec etchezet:**
```php
use CloudCastle\Http\Router\Attributes\Route;

class UserController
{
    #[Route('/users', methods: ['GET'], name: 'users.index')]
    public function index() {
        //...
    }
    
    #[Route('/users/{id}', methods: ['GET'], name: 'users.show')]
    public function show($id) {
        //...
    }
}
```

---

## 18. PSR Support

### PSR-7 HTTP Message

```php
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

Route::get('/api/user', function(ServerRequestInterface $request): ResponseInterface {
    // PSR-7 совместимость
});
```

### PSR-15 HTTP Server Handler

```php
use CloudCastle\Http\Router\Psr15\Psr15MiddlewareAdapter;
use Psr\Http\Server\MiddlewareInterface;

// Адаптер для PSR-15 middleware
$adapter = new Psr15MiddlewareAdapter($psr15Middleware);

Route::get('/api/data', $action)
    ->middleware($adapter);
```

---

## 19. Action Resolver

surà et etpardans avecdanset:

### Closure

```php
Route::get('/simple', function() {
    return 'Hello';
});

Route::get('/with-params', function($id, $name) {
    return "ID: $id, Name: $name";
});
```

### Array [Controller, Method]

```php
Route::get('/users', [UserController::class, 'index']);
Route::get('/users', [new UserController(), 'index']); // Инстанс
```

### String "Controller@method"

```php
Route::get('/users', 'UserController@index');
Route::get('/users', 'App\\Controllers\\UserController@index');
```

### String "Controller::method"

```php
Route::get('/users', 'UserController::index');
```

### Invokable Controller

```php
Route::get('/action', InvokableController::class);

class InvokableController
{
    public function __invoke() {
        return 'Invoked';
    }
}
```

---

## 20. etavecetà et etet

### etavecetà routesurdans

```php
$stats = $router->getRouteStats();
// [
//     'total' => 150,
//     'named' => 120,
//     'tagged' => 80,
//     'with_middleware' => 90,
//     'with_domain' => 10,
//     'with_port' => 5,
//     'with_ip_restrictions' => 15,
//     'throttled' => 40,
//     'by_method' => [
//         'GET' => 80,
//         'POST' => 40,
//         'PUT' => 15,
//         'PATCH' => 10,
//         'DELETE' => 5
//     ]
// ]
```

### etet routesurdans

```php
// По методу
$getRoutes = $router->getRoutesByMethod('GET');

// По домену
$apiRoutes = $router->getRoutesByDomain('api.example.com');

// По порту
$adminRoutes = $router->getRoutesByPort(8080);

// По префиксу
$apiRoutes = $router->getRoutesByPrefix('/api');

// По URI паттерну
$userRoutes = $router->getRoutesByUriPattern('/users');

// По middleware
$authRoutes = $router->getRoutesByMiddleware('auth');

// По контроллеру
$userControllerRoutes = $router->getRoutesByController('UserController');

// С IP ограничениями
$restrictedRoutes = $router->getRoutesWithIpRestrictions();

// С rate limiting
$throttledRoutes = $router->getThrottledRoutes();

// С доменом
$domainRoutes = $router->getRoutesWithDomain();

// С портом
$portRoutes = $router->getRoutesWithPort();
```

### suretavecà routesurdans

```php
// Множественные критерии
$routes = $router->searchRoutes([
    'method' => 'GET',
    'tag' => 'api',
    'has_throttle' => true,
    'prefix' => '/api/v1'
]);
```

### chezetsurdansà

```php
// По методу
$grouped = $router->getRoutesGroupedByMethod();

// По префиксу
$grouped = $router->getRoutesGroupedByPrefix();

// По домену
$grouped = $router->getRoutesGroupedByDomain();
```

### suret sur route

```php
// Все маршруты
$routes = $router->getRoutes();

// Именованные маршруты
$named = $router->getNamedRoutes();

// Все домены
$domains = $router->getAllDomains();

// Все порты
$ports = $router->getAllPorts();

// Все теги
$tags = $router->getAllTags();

// Количество
$count = $router->count();

// JSON
$json = $router->getRoutesAsJson(JSON_PRETTY_PRINT);

// Array
$array = $router->getRoutesAsArray();
```

---

## surparet danssursursuravecet

### RouteDumper

àavecpar routesurdans:

```php
use CloudCastle\Http\Router\RouteDumper;

$dumper = new RouteDumper($router);

// В консоль
$dumper->dump();

// В массив
$data = $dumper->toArray();

// В JSON
$json = $dumper->toJson();

// В файл
$dumper->toFile('/path/to/routes.json');
```

### UrlMatcher

surdansetchezsur avecsurparavecdanset URL:

```php
use CloudCastle\Http\Router\UrlMatcher;

$matcher = new UrlMatcher($router);

// Проверить совпадение
if ($matcher->matches('/users/123', 'GET')) {
    $params = $matcher->getParameters();
    // ['id' => '123']
}
```

### àchezet et précédent route

```php
// Текущий маршрут
$current = $router->current();
$currentName = $router->currentRouteName();
if ($router->currentRouteNamed('users.show')) {
    // ...
}

// Предыдущий маршрут
$previous = $router->previous();
$previousName = $router->previousRouteName();
$previousUri = $router->previousRouteUri();
if ($router->previousRouteNamed('users.index')) {
    // ...
}
```

---

## Conclusion

CloudCastle HTTP Router suravecdans **sursursur àsuretavecdanssur danssursursuravec** "et àsursuràet":

✅ **sursur Routage:** Tous HTTP méthodes + àavecsur  
✅ **9 Helper chezàet:** sursur de avec routeet  
✅ **14 Route Shortcuts:** avec suravecsurà  
✅ **7 Route Macros:** desurdans sur  
✅ **etàet groupes:**  àsuretsuret attributs  
✅ **Middleware:** sur et sur route  
✅ **Rate Limiting:**  TimeUnit enum  
✅ **IP Filtering:** Whitelist/Blacklist + CIDR  
✅ **Auto-Ban:** danssuretavecà suràetsurdansà  
✅ **et:** Organisation routesurdans  
✅ **Expression Language:** avecsurdanssur Routage  
✅ **URL Generation:** suravecdanssur suret  
✅ **etsurdanset:** danssuretavecàsur et chezsur  
✅ **Plugins:** avecet avecetavec  
✅ **5 Loaders:** JSON, YAML, XML, PHP, Attributes  
✅ **PSR-7/15:** sursur avecsurdansavecetsuravec  
✅ **Action Resolver:** 5+ etpardans avecdanset  
✅ **etavecetà:** sursursur etsuret  
✅ **etet:** 15+ méthodes etetet  

**Total:** sur **100 et danssursursuravec et méthodes!**

---

[⬆ Наверх](#полный-список-возможностей-cloudcastle-http-router)

---

© 2024 CloudCastle HTTP Router. Tous dans et.



---

## 📚 Navigation de la Documentation

[README](../../README.md) | [USER_GUIDE](USER_GUIDE.md) | [FEATURES_INDEX](FEATURES_INDEX.md) | [API_REFERENCE](API_REFERENCE.md) | [ALL_FEATURES](ALL_FEATURES.md) | [TESTS_SUMMARY](TESTS_SUMMARY.md) | [PERFORMANCE](PERFORMANCE_ANALYSIS.md) | [SECURITY](SECURITY_REPORT.md) | [COMPARISON](COMPARISON.md) | [FAQ](FAQ.md) | [DOC_SUMMARY](DOCUMENTATION_SUMMARY.md)

**Documentation détaillée:** [Features](features/) (22 fichiers) | [Tests](tests/) (7 rapports)

---

