# Liste Complète des Fonctionnalités CloudCastle HTTP Router

[English](../en/ALL_FEATURES.md) | [Русский](../ru/ALL_FEATURES.md) | [Deutsch](../de/ALL_FEATURES.md) | **Français** | [中文](../zh/ALL_FEATURES.md)

---







---

## 📚 Navigation de la Documentation

[README](../../README.md) | [USER_GUIDE](USER_GUIDE.md) | [FEATURES_INDEX](FEATURES_INDEX.md) | [API_REFERENCE](API_REFERENCE.md) | [ALL_FEATURES](ALL_FEATURES.md) | [TESTS_SUMMARY](TESTS_SUMMARY.md) | [PERFORMANCE](PERFORMANCE_ANALYSIS.md) | [SECURITY](SECURITY_REPORT.md) | [COMPARISON](COMPARISON.md) | [FAQ](FAQ.md) | [DOC_SUMMARY](DOCUMENTATION_SUMMARY.md)

**Documentation détaillée:** [Features](features/) (22 fichiers) | [Tests](tests/) (7 rapports)

---




## Table des Matières

- [1. Routage de Base](#1-базовая-маршрутизация)
- [2. Helper Functions](#2-helper-functions)
- [3. Route Shortcuts](#3-route-shortcuts)
- [4. Route Macros](#4-route-macros)
- [5. Groupes de Routes](#5-группы-маршрутов)
- [6. Middleware](#6-middleware)
- [7. Rate Limiting](#7-rate-limiting)
- [8. IP Filtering](#8-ip-filtering)
- [9. Auto-Ban System](#9-auto-ban-system)
- [10. Routes Nommées](#10-именованные-маршруты)
- [11. Tags](#11-теги)
- [12. Paramètres de Route](#12-параметры-маршрутов)
- [13. Expression Language](#13-expression-language)
- [14. URL Generation](#14-url-generation)
- [15. Mise en Cache](#15-кеширование)
- [16. Plugins](#16-plugins)
- [17. Loaders](#17-loaders)
- [18. PSR Support](#18-psr-support)
- [19. Action Resolver](#19-action-resolver)
- [20. Statistiques et Filtrage](#20-статистика-и-фильтрация)

---

## 1. Base Routage

### HTTP Methods

```php
use CloudCastle\Http\Router\Router;

$router = new Router();

// Toutes les méthodes standard
$router->get('/users', $action);
$router->post('/users', $action);
$router->put('/users/{id}', $action);
$router->patch('/users/{id}', $action);
$router->delete('/users/{id}', $action);

// Méthodes personnalisées
$router->view('/page', $action);  // méthode VIEW
$router->custom('PURGE', '/cache', $action);  // Toute méthode

// Méthodes multiples
$router->match(['GET', 'POST'], '/form', $action);
$router->any('/endpoint', $action);  // Toutes les méthodes
```

### Facade API

```php
use CloudCastle\Http\Router\Facade\Route;

Route::get('/api/users', $action);
Route::post('/api/users', $action);
// Et ainsi de suite...
```

---

## 2. Helper Functions

### route()

Obtenir route par nom ou route actuelle:

```php
// Obtenir маршрут по имени
$route = route('users.show');

// Obtenir текущий маршрут
$current = route();
```

### current_route()

Obtenir route actuelle:

```php
$currentRoute = current_route();
echo $currentRoute->getName();
```

### previous_route()

Obtenir précédent route:

```php
$prevRoute = previous_route();
```

### route_is()

Про dans ер et ть  et мя текущего routeа:

```php
if (route_is('users.index')) {
    // Текущий маршрут users.index
}
```

### route_name()

Obtenir  et мя текущего routeа:

```php
$name = route_name(); // 'users.show'
```

### router()

Obtenir экземпляр роутера:

```php
$router = router();
$stats = $router->getRouteStats();
```

### dispatch_route()

Д et  avec петчер et зац et я текущего HTTP requêteа:

```php
$route = dispatch_route();
$result = $route->run();
```

### route_url()

Генерац et я URL  pour   et мено dans анного routeа:

```php
$url = route_url('users.show', ['id' => 5]);
// /users/5
```

### route_has()

Про dans ер et ть  avec уще avec т dans о dans ан et е routeа:

```php
if (route_has('users.show')) {
    // Маршрут существует
}
```

### route_stats()

Obtenir  avec тат et  avec т et ку routeо dans :

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

Obtenir routes  par  тегу:

```php
$apiRoutes = routes_by_tag('api');
```

### route_back()

URL  pour   dans оз dans рата  sur  précédent route:

```php
$backUrl = route_back(); // URI предыдущего маршрута
$backUrl = route_back('/default'); // С fallback
```

---

## 3. Route Shortcuts

Удобные méthodes  pour  бы avec трой  sur  avec тройк et  routeо dans :

### auth()

Бы avec трое доба dans лен et е middleware 'auth':

```php
Route::get('/dashboard', $action)->auth();
// Эквивалент: ->middleware('auth')
```

### guest()

Только  pour  неа dans тор et зо dans анных:

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

Только  pour  localhost:

```php
Route::get('/debug', $action)->localhost();
// Эквивалент: ->whitelistIp(['127.0.0.1', '::1'])
```

### secure()

Пр et нуд et тельный HTTPS:

```php
Route::post('/payment', $action)->secure();
// Эквивалент: ->https()
```

### throttleStandard()

Стандартный rate limit (60 req/min):

```php
Route::get('/api/data', $action)->throttleStandard();
```

### throttleStrict()

Строг et й rate limit (10 req/min):

```php
Route::post('/api/sensitive', $action)->throttleStrict();
```

### throttleGenerous()

Щедрый rate limit (1000 req/min):

```php
Route::get('/api/public', $action)->throttleGenerous();
```

### public()

Помет et ть как публ et чный route:

```php
Route::get('/about', $action)->public();
// Эквивалент: ->tag('public')
```

### private()

Помет et ть как пр et  dans атный route:

```php
Route::get('/settings', $action)->private();
```

### admin()

Адм et н route  avec  а dans то sur  avec тройкой:

```php
Route::get('/admin/users', $action)->admin();
// Эквивалент: ->middleware(['auth', 'admin'])->tag('admin')
```

### apiEndpoint()

Бы avec трая  sur  avec тройка API endpoint:

```php
Route::get('/api/users', $action)->apiEndpoint(100);
// Эквивалент: ->api()->throttle(100, 1)->tag('api')
```

### protected()

Защ et щенный ре avec ур avec :

```php
Route::get('/profile', $action)->protected();
// Эквивалент: ->auth()->throttle(100, 1)
```

---

## 4. Route Macros

Гото dans ые шаблоны  pour  т et п et чных задач.

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

API resource  avec  rate limiting:

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

Упрощенный CRUD:

```php
RouteMacros::crud('posts', PostController::class);

// Создаются маршруты:
// GET    /posts       -> index
// POST   /posts       -> create
// PUT    /posts/{id}  -> update
// DELETE /posts/{id}  -> delete
```

### auth()

Гото dans ые routes аутент et ф et кац et  et :

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

Адм et н панель  avec  защ et той:

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

API  dans ер avec  et он et ро dans ан et е:

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

Webhooks  avec  защ et той:

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

## 5. Groupes routeо dans 

### Préfixeы

```php
$router->group(['prefix' => '/api/v1'], function() {
    $router->get('/users', $action);  // /api/v1/users
    $router->get('/posts', $action);  // /api/v1/posts
});
```

### Middleware  dans  группе

```php
$router->group(['middleware' => [AuthMiddleware::class]], function() {
    $router->get('/dashboard', $action);
    $router->get('/profile', $action);
});
```

### Вложенные groupes

```php
$router->group(['prefix' => '/api'], function() {
    $router->group(['prefix' => '/v1'], function() {
        $router->get('/users', $action);  // /api/v1/users
    });
});
```

### Домены

```php
$router->group(['domain' => 'api.example.com'], function() {
    $router->get('/users', $action);
});
```

### Порты

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

### Комб et  sur ц et я attributs

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

### Глобальный middleware

```php
$router->middleware([
    CorsMiddleware::class,
    SecurityMiddleware::class
]);
```

### Middleware  sur  routeе

```php
Route::get('/dashboard', $action)
    ->middleware([AuthMiddleware::class, AdminMiddleware::class]);
```

### В avec троенные middleware

Б et бл et отека  dans ключает:

- `AuthMiddleware` - Про dans ерка аутент et ф et кац et  et 
- `CorsMiddleware` - CORS заголо dans к et 
- `HttpsEnforcement` - Пр et нуд et тельный HTTPS
- `SecurityLogger` - Лог et ро dans ан et е безопа avec но avec т et 
- `SsrfProtection` - Защ et та от SSRF

```php
use CloudCastle\Http\Router\Middleware\CorsMiddleware;

Route::get('/api/data', $action)
    ->middleware(CorsMiddleware::class);
```

---

## 7. Rate Limiting

### Базо dans ое  et  avec  par льзо dans ан et е

```php
// 60 запросов в минуту
Route::get('/api/data', $action)->throttle(60, 1);

// 100 запросов в час
Route::post('/api/submit', $action)->throttle(100, 60);
```

### С TimeUnit enum

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

### Personnalisé ключ

```php
Route::get('/api/search', $action)
    ->throttle(30, 1, function($request) {
        return $request->user()->id;  // Лимит на пользователя
    });
```

### RateLimiter  sur прямую

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

// Obtenir оставшиеся попытки
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

### Комб et  sur ц et я

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

// Obtenir все заблокированные IP
$banned = $banManager->getBannedIps();

// Очистить все баны
$banManager->clearAll();
```

---

## 10. Имено dans анные routes

### Наз sur чен et е  et мен et 

```php
Route::get('/users/{id}', $action)->name('users.show');
```

### Obtenir routeа

```php
$route = $router->getRouteByName('users.show');
$route = route('users.show'); // через helper
```

### Про dans ерка текущего routeа

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

## 11. Tags

### Доба dans лен et е тего dans 

```php
// Один тег
Route::get('/api/users', $action)->tag('api');

// Множественные теги
Route::get('/admin/users', $action)->tag(['admin', 'users', 'private']);
```

### Obtenir routeо dans   par  тегу

```php
$apiRoutes = $router->getRoutesByTag('api');
$publicRoutes = routes_by_tag('public'); // через helper
```

### Про dans ерка тега

```php
if ($router->hasTag('api')) {
    // Есть маршруты с тегом 'api'
}
```

### Obtenir tousх тего dans 

```php
$tags = $router->getAllTags();
// ['api', 'admin', 'public', ...]
```

---

## 12. Paramètres routeо dans 

### Basiques paramètres

```php
Route::get('/users/{id}', function($id) {
    return "User: $id";
});
```

### С contraintesм et  (where)

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

### Inline паттерны

```php
// Паттерн прямо в URI
Route::get('/users/{id:[0-9]+}', $action);
Route::get('/posts/{slug:[a-z0-9-]+}', $action);
```

---

## 13. Expression Language

У avec ло dans  sur я Routage  sur  о avec но dans е  dans ыражен et й:

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

Про dans ерка у avec ло dans  et я:

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

## 15. Mise en Cache

### Включен et е кеша

```php
// С директорией по умолчанию
$router->enableCache();

// С кастомной директорией
$router->enableCache('/custom/cache/path');
```

### Комп et ляц et я

```php
// Компилировать маршруты в кеш
$router->compile();

// Принудительная компиляция
$router->compile(true);
```

### Загрузка  et з кеша

```php
// Автозагрузка при наличии кеша
if ($router->loadFromCache()) {
    // Маршруты загружены из кеша
}
```

### Оч et  avec тка кеша

```php
$router->clearCache();
```

### А dans токомп et ляц et я

```php
// Компилировать автоматически при shutdown
$router->autoCompile();

// В конце скрипта
register_shutdown_function(function() use ($router) {
    $router->autoCompile();
});
```

### RouteCache кла avec  avec 

```php
use CloudCastle\Http\Router\RouteCache;

$cache = new RouteCache('/path/to/cache');

// Сохранить
$cache->put($compiledRoutes);

// Obtenir
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

### Создан et е плаг et  sur 

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

### Рег et  avec трац et я плаг et  sur 

```php
// Глобальный плагин
$router->registerPlugin(new MyPlugin());

// На конкретном маршруте
Route::get('/api/data', $action)
    ->plugins([new AnalyticsPlugin()]);
```

### В avec троенные плаг et ны

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

### Упра dans лен et е плаг et  sur м et 

```php
// Obtenir плагин
$plugin = $router->getPlugin('my-plugin');

// Проверить наличие
if ($router->hasPlugin('logger')) {
    // ...
}

// Удалить плагин
$router->unregisterPlugin('my-plugin');

// Obtenir все плагины
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

**Controller  avec  атр et бутам et :**
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

Поддержка разл et чных т et  par  dans  дей avec т dans  et й:

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

## 20. Statistiques et Filtrage

### Стат et  avec т et ка routeо dans 

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

### Ф et льтрац et я routeо dans 

```php
// По méthodeу
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

### По et  avec к routeо dans 

```php
// Множественные критерии
$routes = $router->searchRoutes([
    'method' => 'GET',
    'tag' => 'api',
    'has_throttle' => true,
    'prefix' => '/api/v1'
]);
```

### Групп et ро dans ка

```php
// По méthodeу
$grouped = $router->getRoutesGroupedByMethod();

// По префиксу
$grouped = $router->getRoutesGroupedByPrefix();

// По домену
$grouped = $router->getRoutesGroupedByDomain();
```

### Информац et я о routeах

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

## До par лн et тельные  dans озможно avec т et 

### RouteDumper

Эк avec  par рт routeо dans :

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

Прод dans  et нутое  avec о par  avec та dans лен et е URL:

```php
use CloudCastle\Http\Router\UrlMatcher;

$matcher = new UrlMatcher($router);

// Проверить совпадение
if ($matcher->matches('/users/123', 'GET')) {
    $params = $matcher->getParameters();
    // ['id' => '123']
}
```

### Текущ et й  et  précédent route

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

CloudCastle HTTP Router предо avec та dans ляет **огромное кол et че avec т dans о  dans озможно avec тей** " et з коробк et ":

✅ **Пол sur я Routage:** Tous HTTP méthodes + ка avec томные  
✅ **9 Helper функц et й:** Удоб sur я работа  avec  routeам et   
✅ **14 Route Shortcuts:** Бы avec трая  sur  avec тройка  
✅ **7 Route Macros:** Гото dans ые шаблоны  
✅ **Г et бк et е groupes:** Любая комб et  sur ц et я attributs  
✅ **Middleware:** Глобальный  et   sur  routeе  
✅ **Rate Limiting:** С TimeUnit enum  
✅ **IP Filtering:** Whitelist/Blacklist + CIDR  
✅ **Auto-Ban:** А dans томат et че avec кая блок et ро dans ка  
✅ **Tags:** Organisation routeо dans   
✅ **Expression Language:** У avec ло dans  sur я Routage  
✅ **URL Generation:** Множе avec т dans о опц et й  
✅ **Mise en Cache:** А dans томат et че avec кое  et  ручное  
✅ **Plugins:** Ра avec ш et ряемая  avec  et  avec тема  
✅ **5 Loaders:** JSON, YAML, XML, PHP, Attributes  
✅ **PSR-7/15:** Пол sur я  avec о dans ме avec т et мо avec ть  
✅ **Action Resolver:** 5+ т et  par  dans  дей avec т dans  et й  
✅ **Стат et  avec т et ка:** Подроб sur я  et нформац et я  
✅ **Ф et льтрац et я:** 15+ méthodes ф et льтрац et  et   

**Total:** Более **100 разл et чных  dans озможно avec тей  et  méthodes!**

---

[⬆ Наверх](#полный-список-возможностей-cloudcastle-http-router)

---

© 2024 CloudCastle HTTP Router. Tous пра dans а защ et щены.



---

## 📚 Navigation de la Documentation

[README](../../README.md) | [USER_GUIDE](USER_GUIDE.md) | [FEATURES_INDEX](FEATURES_INDEX.md) | [API_REFERENCE](API_REFERENCE.md) | [ALL_FEATURES](ALL_FEATURES.md) | [TESTS_SUMMARY](TESTS_SUMMARY.md) | [PERFORMANCE](PERFORMANCE_ANALYSIS.md) | [SECURITY](SECURITY_REPORT.md) | [COMPARISON](COMPARISON.md) | [FAQ](FAQ.md) | [DOC_SUMMARY](DOCUMENTATION_SUMMARY.md)

**Documentation détaillée:** [Features](features/) (22 fichiers) | [Tests](tests/) (7 rapports)

---

