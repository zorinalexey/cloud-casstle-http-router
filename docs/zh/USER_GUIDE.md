# CloudCastle HTTP Router - 完整用户指南

[English](../en/USER_GUIDE.md) | [Русский](../ru/USER_GUIDE.md) | [Deutsch](../de/USER_GUIDE.md) | [Français](../fr/USER_GUIDE.md) | **中文**

---







---

## 📚 文档导航

[README](../../README.md) | [USER_GUIDE](USER_GUIDE.md) | [FEATURES_INDEX](FEATURES_INDEX.md) | [API_REFERENCE](API_REFERENCE.md) | [ALL_FEATURES](ALL_FEATURES.md) | [TESTS_SUMMARY](TESTS_SUMMARY.md) | [PERFORMANCE](PERFORMANCE_ANALYSIS.md) | [SECURITY](SECURITY_REPORT.md) | [COMPARISON](COMPARISON.md) | [FAQ](FAQ.md) | [DOC_SUMMARY](DOCUMENTATION_SUMMARY.md)

**详细文档：** [Features](features/) (22 文件) | [Tests](tests/) (7 报告)

---


**版本：** 1.1.1  
**日期：** 十月 2025  
**功能:** 209+

---

## 📑 目录

1. [Введение](#введение)
2. [Установка и настройка](#установка-и-настройка)
3. [基础路由 (13 методов)](#базовая-маршрутизация)
4. [路由参数 (6 способов)](#параметры-маршрутов)
5. [路由组 (12 атрибутов)](#группы-маршрутов)
6. [Rate Limiting (8 методов)](#rate-limiting)
7. [Auto-Ban система (7 методов)](#auto-ban-система)
8. [IP Filtering (4 метода)](#ip-filtering)
9. [Middleware (6 типов)](#middleware)
10. [命名路由 (6 методов)](#именованные-маршруты)
11. [标签 (5 методов)](#теги)
12. [Helper Functions (18 функций)](#helper-functions)
13. [Route Shortcuts (14 методов)](#route-shortcuts)
14. [Route Macros (7 макросов)](#route-macros)
15. [URL Generation (11 методов)](#url-generation)
16. [Expression Language (5 операторов)](#expression-language)
17. [缓存 маршрутов (6 методов)](#кеширование-маршрутов)
18. [Система плагинов (13 методов)](#система-плагинов)
19. [Загрузчики маршрутов (5 типов)](#загрузчики-маршрутов)
20. [PSR Support (3 стандарта)](#psr-support)
21. [Action Resolver (6 форматов)](#action-resolver)
22. [Статистика и запросы (24 метода)](#статистика-и-запросы)
23. [Безопасность (12 механизмов)](#безопасность)
24. [Исключения (8 типов)](#исключения)
25. [CLI Tools (3 команды)](#cli-tools)
26. [Продвинутые примеры](#продвинутые-примеры)

---

## 简介

CloudCastle HTTP Router -  **** (54k+ req/sec), **** (OWASP Top 10)  **** (209+ )  路由  PHP 8.2+.

###  

- ⚡ **性能:** 54,891 请求/
- 🔒 **安全性:** 12+   
- 💎 **:** 209+ 方法  
- 💾 **:** 1.32 KB  路由
- 📊 **:** 1,160,000+ 路由
- ✅ **:** 501 测试, 0 

---

## 安装  

### 

- PHP 8.2  
- Composer
- PSR-7/PSR-15 ()

### 安装  Composer

```bash
composer require cloud-castle/http-router
```

### 快速开始

```php
<?php

require __DIR__ . '/vendor/autoload.php';

use CloudCastle\Http\Router\Facade\Route;

// Регистрация маршрутов
Route::get('/users', fn() => 'List of users');
Route::post('/users', fn() => 'Create user');

// Dispatch
$route = Route::dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
echo $route->run();
```

---

## 基础 路由

### 1. GET 路由

```php
use CloudCastle\Http\Router\Facade\Route;

Route::get('/users', function() {
    return 'List of users';
});
```

### 2. POST 路由

```php
Route::post('/users', function() {
    return 'Create user';
});
```

### 3. PUT 路由

```php
Route::put('/users/{id}', function($id) {
    return "Update user: $id";
});
```

### 4. PATCH 路由

```php
Route::patch('/users/{id}', function($id) {
    return "Partial update user: $id";
});
```

### 5. DELETE 路由

```php
Route::delete('/users/{id}', function($id) {
    return "Delete user: $id";
});
```

### 6. VIEW 路由 (自定义)

```php
Route::view('/preview', function() {
    return 'Preview content';
});
```

### 7. 自定义 HTTP 方法

```php
Route::custom('PURGE', '/cache', function() {
    return 'Cache purged';
});

Route::custom('TRACE', '/debug', function() {
    return 'Debug trace';
});
```

### 8. 多个 HTTP 方法

```php
Route::match(['GET', 'POST'], '/form', function() {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        return 'Show form';
    }
    return 'Process form';
});
```

### 9. 所有 HTTP 方法

```php
Route::any('/webhook', function() {
    return 'Handle any method';
});
```

### 10.   Router

```php
use CloudCastle\Http\Router\Router;

$router = new Router();

$router->get('/users', fn() => 'Users');
$router->post('/users', fn() => 'Create');

$route = $router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
```

### 11-13.  方法 Router

```php
use CloudCastle\Http\Router\Router;

// Singleton pattern
Router::staticGet('/users', fn() => 'Users');
Router::staticPost('/users', fn() => 'Create');
Router::staticDelete('/users/{id}', fn($id) => "Delete: $id");
```

---

## 参数 路由

### 1. 基本 参数

```php
Route::get('/users/{id}', function($id) {
    return "User ID: $id";
});

Route::get('/posts/{slug}', function($slug) {
    return "Post: $slug";
});

// Множественные параметры
Route::get('/users/{userId}/posts/{postId}', function($userId, $postId) {
    return "User $userId, Post $postId";
});
```

### 2. 约束 参数 (where)

```php
// Только цифры
Route::get('/users/{id}', $action)
    ->where('id', '[0-9]+');

// Буквы и дефисы
Route::get('/posts/{slug}', $action)
    ->where('slug', '[a-z0-9-]+');

// Множественные ограничения
Route::get('/api/{version}/users/{id}', $action)
    ->where([
        'version' => 'v[0-9]+',
        'id' => '[0-9]+'
    ]);
```

### 3. Inline 参数

```php
// Паттерн в самом URI
Route::get('/users/{id:[0-9]+}', $action);
Route::get('/posts/{slug:[a-z0-9-]+}', $action);
Route::get('/files/{path:.+}', $action);
```

### 4. 可选 参数

```php
Route::get('/blog/{category?}', function($category = 'all') {
    return "Category: $category";
});

Route::get('/search/{query?}/{page?}', function($query = '', $page = 1) {
    return "Search: $query, Page: $page";
});
```

### 5. 默认值

```php
Route::get('/posts/{page}', $action)
    ->defaults(['page' => 1]);

Route::get('/search/{query}/{limit}', $action)
    ->defaults([
        'query' => '',
        'limit' => 10
    ]);
```

### 6. 获取 参数

```php
Route::get('/users/{id}', function($id) {
    $route = Route::current();
    $params = $route->getParameters();
    // ['id' => '123']
    
    return "User: $id";
});
```

---

## 组 路由

### 1. 组  前缀

```php
Route::group(['prefix' => '/api'], function() {
    Route::get('/users', $action);     // /api/users
    Route::get('/posts', $action);     // /api/posts
});
```

### 2. 组  middleware

```php
Route::group(['middleware' => [AuthMiddleware::class]], function() {
    Route::get('/dashboard', $action);
    Route::get('/profile', $action);
});
```

### 3. 组  

```php
Route::group(['domain' => 'api.example.com'], function() {
    Route::get('/users', $action);
    Route::get('/posts', $action);
});
```

### 4. 组  

```php
Route::group(['port' => 8080], function() {
    Route::get('/admin', $action);
});
```

### 5. 组  namespace

```php
Route::group(['namespace' => 'App\\Controllers\\Api'], function() {
    Route::get('/users', 'UserController@index');
    Route::get('/posts', 'PostController@index');
});
```

### 6. 组  HTTPS requirement

```php
Route::group(['https' => true], function() {
    Route::get('/secure', $action);
    Route::post('/payment', $action);
});
```

### 7. 组  

```php
Route::group(['protocols' => ['ws', 'wss']], function() {
    Route::get('/chat', $action);
    Route::get('/notifications', $action);
});
```

### 8. 组  

```php
Route::group(['tags' => ['api', 'public']], function() {
    Route::get('/users', $action);
    Route::get('/posts', $action);
});
```

### 9. 组  throttle

```php
Route::group(['throttle' => [60, 1]], function() {
    Route::get('/api/data', $action);
    Route::post('/api/submit', $action);
});
```

### 10. 组  IP whitelist

```php
Route::group(['whitelistIp' => ['192.168.1.0/24']], function() {
    Route::get('/admin/users', $action);
    Route::get('/admin/settings', $action);
});
```

### 11.  组

```php
Route::group(['prefix' => '/api'], function() {
    Route::group(['prefix' => '/v1'], function() {
        Route::get('/users', $action);  // /api/v1/users
    });
    
    Route::group(['prefix' => '/v2'], function() {
        Route::get('/users', $action);  // /api/v2/users
    });
});
```

### 12.  属性

```php
Route::group([
    'prefix' => '/admin',
    'middleware' => [AuthMiddleware::class, AdminMiddleware::class],
    'domain' => 'admin.example.com',
    'port' => 443,
    'https' => true,
    'whitelistIp' => ['192.168.1.0/24'],
    'tags' => ['admin', 'protected'],
    'throttle' => [30, 1],
    'namespace' => 'App\\Controllers\\Admin',
], function() {
    Route::get('/dashboard', 'DashboardController@index');
    Route::get('/users', 'UserController@index');
});
```

### 获取  RouteGroup

```php
$group = Route::group(['prefix' => '/api'], function() {
    Route::get('/users', $action);
    Route::get('/posts', $action);
});

// Методы RouteGroup
$routes = $group->getRoutes();        // Все маршруты группы
$count = $group->count();             // Количество маршрутов
$attrs = $group->getAttributes();     // Атрибуты группы
```

---

## Rate Limiting

### 1.  throttle

```php
// 60 запросов в минуту
Route::post('/api/submit', $action)
    ->throttle(60, 1);

// 100 запросов в час
Route::post('/api/upload', $action)
    ->throttle(100, 60);
```

### 2. TimeUnit enum

```php
use CloudCastle\Http\Router\TimeUnit;

// 5 запросов в секунду
Route::post('/api/fast', $action)
    ->throttle(5, TimeUnit::SECOND->value);

// 100 запросов в минуту
Route::post('/api/normal', $action)
    ->throttle(100, TimeUnit::MINUTE->value);

// 1000 запросов в час
Route::post('/api/slow', $action)
    ->throttle(1000, TimeUnit::HOUR->value);

// 10000 запросов в день
Route::post('/api/daily', $action)
    ->throttle(10000, TimeUnit::DAY->value);

// 50000 запросов в неделю
Route::post('/api/weekly', $action)
    ->throttle(50000, TimeUnit::WEEK->value);

// 200000 запросов в месяц
Route::post('/api/monthly', $action)
    ->throttle(200000, TimeUnit::MONTH->value);
```

### 3. 自定义  throttle

```php
Route::post('/api/user-specific', $action)
    ->throttle(30, 1, function($request) {
        // Ограничение по ID пользователя
        return 'user_' . $request->userId;
    });

Route::post('/api/ip-specific', $action)
    ->throttle(60, 1, function($request) {
        // Ограничение по IP
        return $request->ip();
});
```

### 4. 获取 RateLimiter

```php
$route = Route::post('/api/data', $action)
    ->throttle(60, 1);

$rateLimiter = $route->getRateLimiter();
```

### 5. 方法 RateLimiter

```php
use CloudCastle\Http\Router\RateLimiter;

$limiter = new RateLimiter(60, 1);  // 60 запросов в минуту

// Проверить превышение лимита
if ($limiter->tooManyAttempts('user_123')) {
    $seconds = $limiter->availableIn('user_123');
    echo "Retry after $seconds seconds";
}

// Добавить попытку
$limiter->attempt('user_123');

// Количество оставшихся попыток
$remaining = $limiter->remaining('user_123');

// Сбросить счетчик
$limiter->clear('user_123');

// Очистить всё
$limiter->clearAll();

// 获取 максимум попыток
$max = $limiter->getMaxAttempts();

// 获取 период в минутах
$period = $limiter->getDecayMinutes();
```

### 6. 安装 BanManager  RateLimiter

```php
use CloudCastle\Http\Router\BanManager;

$banManager = new BanManager(5, 3600);  // 5 нарушений = бан на 1 час

Route::post('/login', $action)
    ->throttle(3, 1)
    ->getRateLimiter()
    ?->setBanManager($banManager);
```

### 7-8. Shortcuts  throttle

```php
// 60 запросов в минуту
Route::post('/api/standard', $action)->throttleStandard();

// 10 запросов в минуту
Route::post('/api/strict', $action)->throttleStrict();

// 1000 запросов в минуту
Route::post('/api/generous', $action)->throttleGenerous();
```

---

## Auto-Ban 

### 1.  BanManager

```php
use CloudCastle\Http\Router\BanManager;

// 5 нарушений = бан на 1 час (3600 сек)
$banManager = new BanManager(
    maxViolations: 5,
    banDuration: 3600
);
```

### 2.  Auto-Ban

```php
$banManager->enableAutoBan(5);  // Автобан после 5 нарушений
```

### 3.   IP

```php
// Забанить IP на 1 час
$banManager->ban('1.2.3.4', 3600);

// Забанить IP навсегда (0 секунд)
$banManager->ban('5.6.7.8', 0);
```

### 4.  IP

```php
$banManager->unban('1.2.3.4');
```

### 5.  

```php
if ($banManager->isBanned('1.2.3.4')) {
    throw new \CloudCastle\Http\Router\Exceptions\BannedException(
        'Your IP is banned'
    );
}
```

### 6. 获取   IP

```php
$bannedIps = $banManager->getBannedIps();
// ['1.2.3.4', '5.6.7.8']
```

### 7.  所有 

```php
$banManager->clearAll();
```

###    Auto-Ban

```php
use CloudCastle\Http\Router\BanManager;
use CloudCastle\Http\Router\Facade\Route;

$banManager = new BanManager(5, 3600);

Route::post('/login', function() {
    // Login logic
    return 'Login success';
})
->throttle(3, 1)  // 3 попытки в минуту
->getRateLimiter()
?->setBanManager($banManager);

// При превышении лимита 5 раз - автоматический бан на 1 час
```

---

## IP Filtering

### 1. Whitelist IP

```php
// Один IP
Route::get('/admin', $action)
    ->whitelistIp(['192.168.1.1']);

// Множественные IP
Route::get('/admin', $action)
    ->whitelistIp([
        '192.168.1.1',
        '192.168.1.2',
        '10.0.0.1'
    ]);
```

### 2. CIDR 

```php
Route::get('/admin', $action)
    ->whitelistIp([
        '192.168.1.0/24',    // 192.168.1.0 - 192.168.1.255
        '10.0.0.0/8',        // 10.0.0.0 - 10.255.255.255
    ]);
```

### 3. Blacklist IP

```php
Route::get('/public', $action)
    ->blacklistIp([
        '1.2.3.4',
        '5.6.7.8'
    ]);
```

### 4.  whitelist  blacklist

```php
Route::get('/api/data', $action)
    ->whitelistIp(['192.168.1.0/24'])  // Разрешить локальную сеть
    ->blacklistIp(['192.168.1.100']);   // Кроме этого IP
```

---

## Middleware

### 1.  middleware

```php
use CloudCastle\Http\Router\Middleware\CorsMiddleware;

Route::middleware([CorsMiddleware::class]);
```

### 2. Middleware  路由

```php
use CloudCastle\Http\Router\Middleware\AuthMiddleware;

Route::get('/dashboard', $action)
    ->middleware([AuthMiddleware::class]);
```

### 3.  middleware

```php
Route::get('/admin/users', $action)
    ->middleware([
        AuthMiddleware::class,
        AdminMiddleware::class,
        LoggerMiddleware::class
    ]);
```

### 4.  middleware

```php
use CloudCastle\Http\Router\Middleware\{
    AuthMiddleware,
    CorsMiddleware,
    HttpsEnforcement,
    SecurityLogger,
    SsrfProtection
};

Route::get('/api/data', $action)
    ->middleware([
        CorsMiddleware::class,
        SecurityLogger::class
    ]);

Route::get('/secure', $action)
    ->middleware([HttpsEnforcement::class]);

Route::post('/webhook', $action)
    ->middleware([SsrfProtection::class]);
```

### 5.   middleware

```php
use CloudCastle\Http\Router\Contracts\MiddlewareInterface;
use CloudCastle\Http\Router\Route;

class CustomMiddleware implements MiddlewareInterface
{
    public function handle(Route $route, callable $next): mixed
    {
        // Before logic
        echo "Before route execution\n";
        
        // Execute route
        $response = $next($route);
        
        // After logic
        echo "After route execution\n";
        
        return $response;
    }
}

Route::get('/test', $action)
    ->middleware([CustomMiddleware::class]);
```

### 6. MiddlewareDispatcher

```php
use CloudCastle\Http\Router\MiddlewareDispatcher;

$dispatcher = new MiddlewareDispatcher();

$dispatcher->add(AuthMiddleware::class);
$dispatcher->add(LoggerMiddleware::class);

$response = $dispatcher->dispatch($route, function($route) {
    return $route->run();
});
```

---

##  路由

### 1.  

```php
Route::get('/users/{id}', $action)
    ->name('users.show');

Route::post('/users', $action)
    ->name('users.store');
```

### 2. 获取 路由  

```php
$route = Route::getRouteByName('users.show');
```

### 3.   路由

```php
$name = Route::currentRouteName();
// 'users.show'
```

### 4.    路由

```php
if (Route::currentRouteNamed('users.show')) {
    echo "Viewing user";
}
```

### 5. 

```php
// Включить автоименование
Route::enableAutoNaming();

// Маршруты автоматически получат имена
Route::get('/users', $action);       // auto: users.get
Route::post('/users', $action);      // auto: users.post
Route::get('/users/{id}', $action);  // auto: users.id.get

// Примеры с API
Route::get('/api/v1/users', $action);         // auto: api.v1.users.get
Route::post('/api/v1/users/{id}', $action);   // auto: api.v1.users.id.post

// Корневой маршрут
Route::get('/', $action);                     // auto: root.get

// Специальные символы нормализуются
Route::get('/api-v1/user_profile', $action);  // auto: api.v1.user.profile.get

// Отключить автоименование
Route::disableAutoNaming();

// Проверить статус
$enabled = Route::router()->isAutoNamingEnabled();
```

### 6. 获取 所有  路由

```php
$namedRoutes = Route::getNamedRoutes();
// ['users.show' => Route, 'users.store' => Route, ...]
```

---

## 

### 1.   

```php
Route::get('/api/users', $action)
    ->tag('api');
```

### 2.  

```php
Route::get('/api/public/posts', $action)
    ->tag(['api', 'public', 'posts']);
```

### 3. 获取 路由  

```php
$apiRoutes = Route::getRoutesByTag('api');
```

### 4.   

```php
if (Route::router()->hasTag('api')) {
    echo "Has API routes";
}
```

### 5. 获取 所有 

```php
$allTags = Route::router()->getAllTags();
// ['api', 'public', 'admin', ...]
```

---

## Helper Functions

### 1. route()

```php
// 获取 маршрут по имени
$route = route('users.show');
```

### 2. current_route()

```php
// 获取 текущий маршрут
$current = current_route();
echo $current->getUri();
```

### 3. previous_route()

```php
// 获取 предыдущий маршрут
$previous = previous_route();
```

### 4. route_is()

```php
// Проверка имени маршрута (с поддержкой wildcards)
if (route_is('users.*')) {
    echo "User route";
}

if (route_is('admin.users.show')) {
    echo "Admin user show";
}
```

### 5. route_name()

```php
// 获取 имя текущего маршрута
$name = route_name();
// 'users.show'
```

### 6. router()

```php
// 获取 экземпляр роутера
$router = router();
$routes = $router->getRoutes();
```

### 7. dispatch_route()

```php
// Диспетчеризация маршрута
$route = dispatch_route('/users/123', 'GET');
```

### 8. route_url()

```php
// Генерация URL
$url = route_url('users.show', ['id' => 123]);
// '/users/123'

$url = route_url('posts.show', ['slug' => 'hello-world']);
// '/posts/hello-world'
```

### 9. route_has()

```php
// Проверка существования маршрута
if (route_has('users.show')) {
    echo "Route exists";
}
```

### 10. route_stats()

```php
// 获取 статистику маршрутов
$stats = route_stats();
/*
[
    'total' => 150,
    'methods' => ['GET' => 80, 'POST' => 40, ...],
    'named' => 120,
    'with_middleware' => 60,
    ...
]
*/
```

### 11. routes_by_tag()

```php
// 获取 маршруты по тегу
$apiRoutes = routes_by_tag('api');
```

### 12. route_back()

```php
// Вернуться к предыдущему маршруту
$previous = route_back();
```

### 13-18.  helpers

```php
// Проверка - текущий маршрут именованный
if (route_is('users.show')) {
    // ...
}

// 获取 параметры текущего маршрута
$route = current_route();
$params = $route->getParameters();

// 获取 middleware текущего маршрута
$middleware = current_route()->getMiddleware();

// 获取 теги текущего маршрута
$tags = current_route()->getTags();
```

---

## Route Shortcuts

### 1. auth()

```php
Route::get('/dashboard', $action)->auth();
// Добавляет AuthMiddleware
```

### 2. guest()

```php
Route::get('/login', $action)->guest();
// Только для неавторизованных
```

### 3. api()

```php
Route::get('/api/data', $action)->api();
// API middleware
```

### 4. web()

```php
Route::get('/page', $action)->web();
// Web middleware (CSRF, Session, etc.)
```

### 5. cors()

```php
Route::get('/api/public', $action)->cors();
// CorsMiddleware
```

### 6. localhost()

```php
Route::get('/debug', $action)->localhost();
// Только localhost (127.0.0.1)
```

### 7. secure()

```php
Route::get('/payment', $action)->secure();
// HTTPS only
```

### 8-10. Throttle shortcuts

```php
// 60 запросов в минуту (стандарт)
Route::post('/api/data', $action)->throttleStandard();

// 10 запросов в минуту (строгий)
Route::post('/api/critical', $action)->throttleStrict();

// 1000 запросов в минуту (щедрый)
Route::post('/api/bulk', $action)->throttleGenerous();
```

### 11. public()

```php
Route::get('/page', $action)->public();
// Тег 'public'
```

### 12. private()

```php
Route::get('/page', $action)->private();
// Тег 'private'
```

### 13. admin()

```php
Route::get('/admin/users', $action)->admin();
// AuthMiddleware + AdminMiddleware + HTTPS + IP whitelist
```

### 14. apiEndpoint()

```php
Route::get('/api/data', $action)->apiEndpoint();
// API + CORS + JSON + throttle
```

---

## Route Macros

### 1. resource()

```php
use CloudCastle\Http\Router\Facade\Route;

// Создает RESTful маршруты для ресурса
Route::resource('/users', UserController::class);

// Создаются:
// GET    /users           -> UserController::index
// GET    /users/create    -> UserController::create
// POST   /users           -> UserController::store
// GET    /users/{id}      -> UserController::show
// GET    /users/{id}/edit -> UserController::edit
// PUT    /users/{id}      -> UserController::update
// DELETE /users/{id}      -> UserController::destroy
```

### 2. apiResource()

```php
// API resource (без create/edit страниц)
Route::apiResource('/posts', PostController::class, 100);

// Создаются:
// GET    /posts       -> PostController::index    (throttle: 100/min)
// POST   /posts       -> PostController::store    (throttle: 100/min)
// GET    /posts/{id}  -> PostController::show     (throttle: 100/min)
// PUT    /posts/{id}  -> PostController::update   (throttle: 100/min)
// DELETE /posts/{id}  -> PostController::destroy  (throttle: 100/min)
```

### 3. crud()

```php
// Простой CRUD
Route::crud('/products', ProductController::class);

// Создаются:
// GET    /products       -> ProductController::index
// POST   /products       -> ProductController::create
// GET    /products/{id}  -> ProductController::read
// PUT    /products/{id}  -> ProductController::update
// DELETE /products/{id}  -> ProductController::delete
```

### 4. auth()

```php
// Маршруты аутентификации
Route::auth();

// Создаются:
// GET  /login            -> AuthController::showLoginForm
// POST /login            -> AuthController::login
// POST /logout           -> AuthController::logout
// GET  /register         -> AuthController::showRegisterForm
// POST /register         -> AuthController::register
// GET  /password/reset   -> AuthController::showResetForm
// POST /password/reset   -> AuthController::reset
```

### 5. adminPanel()

```php
// Админ-панель с IP whitelist
Route::adminPanel('/admin', ['192.168.1.0/24']);

// Создаются (с Auth + Admin middleware + HTTPS):
// GET /admin/dashboard -> AdminController::dashboard
// GET /admin/users     -> AdminController::users
// GET /admin/settings  -> AdminController::settings
// GET /admin/logs      -> AdminController::logs
```

### 6. apiVersion()

```php
// API версионирование
Route::apiVersion('v1', function() {
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/posts', [PostController::class, 'index']);
});

// Маршруты доступны как /api/v1/users, /api/v1/posts
```

### 7. webhooks()

```php
// Webhooks с IP whitelist
Route::webhooks('/webhooks', ['192.168.1.0/24']);

// Создаются:
// POST /webhooks/github  -> WebhookController::github
// POST /webhooks/stripe  -> WebhookController::stripe
// POST /webhooks/custom  -> WebhookController::custom
```

---

## URL Generation

### 1. 基础 

```php
use CloudCastle\Http\Router\UrlGenerator;

$generator = new UrlGenerator();

$url = $generator->generate('users.show', ['id' => 123]);
// '/users/123'
```

### 2. Absolute URL

```php
$url = $generator->generate('users.show', ['id' => 123])
    ->absolute();
// 'http://example.com/users/123'
```

### 3. URL  

```php
$url = $generator->generate('users.show', ['id' => 123])
    ->toDomain('api.example.com');
// 'http://api.example.com/users/123'
```

### 4. URL  

```php
$url = $generator->generate('users.show', ['id' => 123])
    ->toProtocol('https');
// 'https://example.com/users/123'
```

### 5. URL  query 参数

```php
$url = $generator->generate('users.index', [], [
    'page' => 2,
    'limit' => 10,
    'sort' => 'name'
]);
// '/users?page=2&limit=10&sort=name'
```

### 6.  URL

```php
$signedUrl = $generator->signed('verify.email', ['user' => 123], 3600);
// '/verify/email/123?signature=abc...&expires=1234567890'
```

### 7. 安装  URL

```php
$generator->setBaseUrl('https://api.example.com');
```

### 8-11.  

```php
$url = $generator->generate('api.users.show', ['id' => 123])
    ->toDomain('api.example.com')
    ->toProtocol('https')
    ->absolute();
// 'https://api.example.com/api/users/123'

// Через helper
$url = route_url('users.show', ['id' => 123]);
```

---

## Expression Language

### 1.  

```php
Route::get('/admin', $action)
    ->condition('request.ip == "192.168.1.1"');
```

### 2.  

```php
Route::get('/api/data', $action)
    ->condition('request.time > 9 and request.time < 18');

Route::get('/premium', $action)
    ->condition('user.level >= 5');

Route::get('/limited', $action)
    ->condition('request.count <= 100');
```

### 3.  

```php
Route::get('/api/secure', $action)
    ->condition('request.ip == "192.168.1.1" and request.method == "GET"');

Route::get('/public', $action)
    ->condition('request.path == "/public" or request.path == "/open"');
```

### 4. ExpressionLanguage 

```php
use CloudCastle\Http\Router\ExpressionLanguage\ExpressionLanguage;

$expr = new ExpressionLanguage();

$result = $expr->evaluate('10 > 5 and 20 < 30', []);
// true

$result = $expr->evaluate('user.age >= 18', ['user' => ['age' => 25]]);
// true
```

### 5.  

```php
Route::get('/api/restricted', $action)
    ->condition(
        '(request.ip == "192.168.1.1" or request.ip == "10.0.0.1") ' .
        'and request.time >= 9 and request.time <= 18'
    );
```

---

##  路由

### 1.  

```php
$router->enableCache('var/cache/routes');
```

### 2.  路由

```php
// Компиляция
$router->compile();

// Принудительная компиляция
$router->compile(force: true);
```

### 3.   

```php
if ($router->loadFromCache()) {
    echo "Routes loaded from cache";
} else {
    // Регистрируем маршруты
    require 'routes/web.php';
    $router->compile();
}
```

### 4.  

```php
$router->clearCache();
```

### 5. 

```php
$router->autoCompile();
// Автоматически компилирует при изменениях
```

### 6.   

```php
if ($router->isCacheLoaded()) {
    echo "Cache is loaded";
}
```

###    

```php
use CloudCastle\Http\Router\Router;

$router = new Router();
$router->enableCache(__DIR__ . '/var/cache/routes');

if (!$router->loadFromCache()) {
    // Регистрируем маршруты
    require __DIR__ . '/routes/web.php';
    require __DIR__ . '/routes/api.php';
    
    // Компилируем
    $router->compile();
}

// Используем маршруты
$route = $router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
```

---

##  

### 1.  PluginInterface

```php
use CloudCastle\Http\Router\Contracts\PluginInterface;
use CloudCastle\Http\Router\Route;

interface PluginInterface
{
    public function beforeDispatch(Route $route, string $uri, string $method): void;
    public function afterDispatch(Route $route, mixed $result): mixed;
    public function onRouteRegistered(Route $route): void;
    public function onException(Route $route, \Exception $e): void;
}
```

### 2.  

```php
Route::registerPlugin(new LoggerPlugin());
```

### 3.   

```php
Route::unregisterPlugin('logger');
```

### 4. 获取 

```php
$plugin = Route::getPlugin('logger');
```

### 5.   

```php
if (Route::hasPlugin('logger')) {
    echo "Logger plugin registered";
}
```

### 6. 获取 所有 

```php
$plugins = Route::getPlugins();
```

### 7. LoggerPlugin ()

```php
use CloudCastle\Http\Router\Plugin\LoggerPlugin;

$logger = new LoggerPlugin('/var/log/routes.log');
Route::registerPlugin($logger);
```

### 8. AnalyticsPlugin ()

```php
use CloudCastle\Http\Router\Plugin\AnalyticsPlugin;

$analytics = new AnalyticsPlugin();
Route::registerPlugin($analytics);

// 获取 статистику
$stats = $analytics->getStats();
```

### 9. ResponseCachePlugin ()

```php
use CloudCastle\Http\Router\Plugin\ResponseCachePlugin;

$cache = new ResponseCachePlugin('/var/cache/responses', 3600);
Route::registerPlugin($cache);
```

### 10. AbstractPlugin

```php
use CloudCastle\Http\Router\Plugin\AbstractPlugin;

class MyPlugin extends AbstractPlugin
{
    public function beforeDispatch(Route $route, string $uri, string $method): void
    {
        // Логика перед dispatch
    }
    
    public function afterDispatch(Route $route, mixed $result): mixed
    {
        // Логика после dispatch
        return $result;
    }
}
```

### 11-13.  

```php
class FullPlugin implements PluginInterface
{
    // Хук перед dispatch
    public function beforeDispatch(Route $route, string $uri, string $method): void
    {
        echo "Before: $method $uri\n";
    }
    
    // Хук после dispatch
    public function afterDispatch(Route $route, mixed $result): mixed
    {
        echo "After dispatch\n";
        return $result;
    }
    
    // Хук при регистрации маршрута
    public function onRouteRegistered(Route $route): void
    {
        echo "Route registered: {$route->getUri()}\n";
    }
    
    // Хук при исключении
    public function onException(Route $route, \Exception $e): void
    {
        echo "Exception: {$e->getMessage()}\n";
    }
}
```

---

##  路由

### 1. JsonLoader

```php
use CloudCastle\Http\Router\Loader\JsonLoader;

$loader = new JsonLoader($router);
$loader->load(__DIR__ . '/routes.json');
```

**routes.json:**
```json
{
    "routes": [
        {
            "method": "GET",
            "uri": "/users",
            "action": "UserController@index",
            "name": "users.index"
        },
        {
            "method": "POST",
            "uri": "/users",
            "action": "UserController@store",
            "name": "users.store",
            "middleware": ["auth"],
            "throttle": [60, 1]
        }
    ]
}
```

### 2. YamlLoader

```php
use CloudCastle\Http\Router\Loader\YamlLoader;

$loader = new YamlLoader($router);
$loader->load(__DIR__ . '/routes.yaml');
```

**routes.yaml:**
```yaml
routes:
  - method: GET
    uri: /users
    action: UserController@index
    name: users.index
  
  - method: POST
    uri: /users
    action: UserController@store
    name: users.store
    middleware:
      - auth
    throttle: [60, 1]
```

### 3. XmlLoader

```php
use CloudCastle\Http\Router\Loader\XmlLoader;

$loader = new XmlLoader($router);
$loader->load(__DIR__ . '/routes.xml');
```

**routes.xml:**
```xml
<?xml version="1.0"?>
<routes>
    <route method="GET" uri="/users" action="UserController@index" name="users.index"/>
    <route method="POST" uri="/users" action="UserController@store" name="users.store">
        <middleware>auth</middleware>
        <throttle>60,1</throttle>
    </route>
</routes>
```

### 4. AttributeLoader

```php
use CloudCastle\Http\Router\Loader\AttributeLoader;

$loader = new AttributeLoader($router);
$loader->loadFromDirectory(__DIR__ . '/app/Controllers');
```

**UserController.php:**
```php
use CloudCastle\Http\Router\Attributes\Route as RouteAttribute;

#[RouteAttribute('/users', 'GET', name: 'users.index')]
class UserController
{
    #[RouteAttribute('/users/{id}', 'GET', name: 'users.show')]
    public function show(int $id)
    {
        return "User $id";
    }
}
```

### 5. PHP 文件 ( 方式)

```php
// routes/web.php
Route::get('/users', [UserController::class, 'index']);
Route::post('/users', [UserController::class, 'store']);

// routes/api.php
Route::group(['prefix' => '/api'], function() {
    Route::get('/users', [ApiUserController::class, 'index']);
});

// index.php
require 'routes/web.php';
require 'routes/api.php';
```

---

## PSR Support

### 1. PSR-7 HTTP Message

```php
use Psr\Http\Message\ServerRequestInterface;
use Nyholm\Psr7\ServerRequestFactory;

$request = ServerRequestFactory::fromGlobals();
// PSR-7 request object

// Использование с роутером
$uri = $request->getUri()->getPath();
$method = $request->getMethod();

$route = Route::dispatch($uri, $method);
```

### 2. PSR-15 HTTP Server Handler

```php
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class RouteHandler implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $uri = $request->getUri()->getPath();
        $method = $request->getMethod();
        
        $route = Route::dispatch($uri, $method);
        $result = $route->run();
        
        // Return PSR-7 Response
        return new Response(200, [], $result);
    }
}
```

### 3. Psr15MiddlewareAdapter

```php
use CloudCastle\Http\Router\Psr15\Psr15MiddlewareAdapter;

$adapter = new Psr15MiddlewareAdapter($router);

// Использование как PSR-15 middleware
$response = $adapter->process($request, $handler);
```

---

## Action Resolver

CloudCastle HTTP Router  **6 **  路由:

### 1. Closure

```php
Route::get('/users', function() {
    return 'Users list';
});
```

### 2. Array [Controller::class, 'method']

```php
Route::get('/users', [UserController::class, 'index']);
```

### 3. String "Controller@method"

```php
Route::get('/users', 'UserController@index');
```

### 4. String "Controller::method"

```php
Route::get('/users', 'UserController::index');
```

### 5. Invokable controller

```php
class ShowUserController
{
    public function __invoke(int $id)
    {
        return "User: $id";
    }
}

Route::get('/users/{id}', ShowUserController::class);
```

### 6. Dependency Injection

```php
class UserController
{
    public function __construct(
        private UserRepository $repository
    ) {}
    
    public function index()
    {
        return $this->repository->all();
    }
}

Route::get('/users', [UserController::class, 'index']);
// ActionResolver автоматически разрешит зависимости
```

---

##   请求

### 1. getRouteStats()

```php
$stats = Route::getRouteStats();
/*
[
    'total' => 150,
    'named' => 120,
    'with_middleware' => 60,
    'with_tags' => 80,
    'methods' => ['GET' => 80, 'POST' => 40, ...],
    'domains' => ['api.example.com' => 30, ...],
    'ports' => [8080 => 20, ...],
]
*/
```

### 2. getRoutesByMethod()

```php
$getRoutes = Route::router()->getRoutesByMethod('GET');
$postRoutes = Route::router()->getRoutesByMethod('POST');
```

### 3. getRoutesByDomain()

```php
$apiRoutes = Route::router()->getRoutesByDomain('api.example.com');
```

### 4. getRoutesByPort()

```php
$routes = Route::router()->getRoutesByPort(8080);
```

### 5. getRoutesByPrefix()

```php
$apiRoutes = Route::router()->getRoutesByPrefix('/api');
```

### 6. getRoutesByUriPattern()

```php
$userRoutes = Route::router()->getRoutesByUriPattern('/users');
```

### 7. getRoutesByMiddleware()

```php
$authRoutes = Route::router()->getRoutesByMiddleware(AuthMiddleware::class);
```

### 8. getRoutesByController()

```php
$routes = Route::router()->getRoutesByController(UserController::class);
```

### 9. getRoutesWithIpRestrictions()

```php
$restrictedRoutes = Route::router()->getRoutesWithIpRestrictions();
```

### 10. getThrottledRoutes()

```php
$throttledRoutes = Route::router()->getThrottledRoutes();
```

### 11. getRoutesWithDomain()

```php
$domainRoutes = Route::router()->getRoutesWithDomain();
```

### 12. getRoutesWithPort()

```php
$portRoutes = Route::router()->getRoutesWithPort();
```

### 13. searchRoutes()

```php
$results = Route::router()->searchRoutes('user');
// Все маршруты содержащие 'user' в URI или имени
```

### 14. getRoutesGroupedByMethod()

```php
$grouped = Route::getRoutesGroupedByMethod();
/*
[
    'GET' => [Route, Route, ...],
    'POST' => [Route, Route, ...],
    ...
]
*/
```

### 15. getRoutesGroupedByPrefix()

```php
$grouped = Route::getRoutesGroupedByPrefix();
/*
[
    '/api' => [Route, Route, ...],
    '/admin' => [Route, Route, ...],
    ...
]
*/
```

### 16. getRoutesGroupedByDomain()

```php
$grouped = Route::getRoutesGroupedByDomain();
/*
[
    'api.example.com' => [Route, Route, ...],
    'admin.example.com' => [Route, Route, ...],
    ...
]
*/
```

### 17. getRoutes()

```php
$allRoutes = Route::getRoutes();
```

### 18. getNamedRoutes()

```php
$namedRoutes = Route::getNamedRoutes();
```

### 19. getAllDomains()

```php
$domains = Route::router()->getAllDomains();
// ['api.example.com', 'admin.example.com', ...]
```

### 20. getAllPorts()

```php
$ports = Route::router()->getAllPorts();
// [8080, 8081, 443, ...]
```

### 21. getAllTags()

```php
$tags = Route::router()->getAllTags();
// ['api', 'admin', 'public', ...]
```

### 22. count()

```php
$total = Route::count();
echo "Total routes: $total";
```

### 23. getRoutesAsJson()

```php
$json = Route::getRoutesAsJson(JSON_PRETTY_PRINT);
echo $json;
```

### 24. getRoutesAsArray()

```php
$array = Route::getRoutesAsArray();
```

---

## 安全性

### 1. Path Traversal Protection

```php
// Роутер автоматически защищает от ../../../
Route::get('/files/{path}', function($path) {
    // $path никогда не будет содержать ../
    return "File: $path";
});
```

### 2. SQL Injection Protection

```php
// Параметры автоматически валидируются
Route::get('/users/{id}', function($id) {
    // Безопасно использовать в SQL
    return DB::find($id);
})->where('id', '[0-9]+');
```

### 3. XSS Protection

```php
Route::get('/search/{query}', function($query) {
    // Экранируйте вывод
    return htmlspecialchars($query);
});
```

### 4. Rate Limiting

```php
// Защита от DDoS
Route::post('/api/submit', $action)
    ->throttle(60, 1);
```

### 5. IP Filtering

```php
// Whitelist только доверенные IP
Route::get('/admin', $action)
    ->whitelistIp(['192.168.1.0/24']);
```

### 6. HTTPS Enforcement

```php
// Принудительное использование HTTPS
Route::get('/payment', $action)->https();
Route::get('/secure', $action)->secure();
```

### 7. Protocol Restrictions

```php
// Только HTTPS/WSS
Route::get('/ws/secure', $action)
    ->protocol(['wss']);
```

### 8. ReDoS Protection

```php
// Роутер защищает от regex DoS
// Безопасные паттерны автоматически
Route::get('/users/{id}', $action)
    ->where('id', '[0-9]+');  // Safe
```

### 9. Method Override Protection

```php
// Защита от подмены 方法ов
// Роутер проверяет реальный HTTP 方法
```

### 10. Cache Injection Protection

```php
// Безопасное кеширование
$router->enableCache('var/cache/routes');
// Кеш подписывается и валидируется
```

### 11. IP Spoofing Protection

```php
// Роутер проверяет X-Forwarded-For
// и защищает от подмены IP
```

### 12. Auto-Ban System

```php
// Автоматическая блокировка атакующих IP
$banManager = new BanManager(5, 3600);
Route::post('/login', $action)
    ->throttle(3, 1)
    ->getRateLimiter()
    ?->setBanManager($banManager);
```

---

## 

### 1. RouteNotFoundException

```php
try {
    $route = Route::dispatch('/nonexistent', 'GET');
} catch (\CloudCastle\Http\Router\Exceptions\RouteNotFoundException $e) {
    http_response_code(404);
    echo "404 Not Found";
}
```

### 2. MethodNotAllowedException

```php
try {
    $route = Route::dispatch('/users', 'DELETE');  // Метод не разрешен
} catch (\CloudCastle\Http\Router\Exceptions\MethodNotAllowedException $e) {
    http_response_code(405);
    $allowed = $e->getAllowedMethods();
    header('Allow: ' . implode(', ', $allowed));
    echo "405 Method Not Allowed";
}
```

### 3. IpNotAllowedException

```php
try {
    $route = Route::dispatch('/admin', 'GET');
} catch (\CloudCastle\Http\Router\Exceptions\IpNotAllowedException $e) {
    http_response_code(403);
    echo "403 Forbidden: IP not allowed";
}
```

### 4. TooManyRequestsException

```php
try {
    $route = Route::dispatch('/api/submit', 'POST');
} catch (\CloudCastle\Http\Router\Exceptions\TooManyRequestsException $e) {
    http_response_code(429);
    $retryAfter = $e->getRetryAfter();
    header("Retry-After: $retryAfter");
    echo "429 Too Many Requests";
}
```

### 5. InsecureConnectionException

```php
try {
    $route = Route::dispatch('/payment', 'POST');
} catch (\CloudCastle\Http\Router\Exceptions\InsecureConnectionException $e) {
    http_response_code(403);
    echo "403 Forbidden: HTTPS required";
}
```

### 6. BannedException

```php
try {
    $route = Route::dispatch('/api/data', 'GET');
} catch (\CloudCastle\Http\Router\Exceptions\BannedException $e) {
    http_response_code(403);
    echo "403 Forbidden: IP is banned";
}
```

### 7. InvalidActionException

```php
try {
    Route::get('/test', 'InvalidController@method');
    $route = Route::dispatch('/test', 'GET');
    $route->run();
} catch (\CloudCastle\Http\Router\Exceptions\InvalidActionException $e) {
    http_response_code(500);
    echo "500 Internal Server Error: Invalid action";
}
```

### 8. RouterException

```php
try {
    // Любая ошибка роутера
} catch (\CloudCastle\Http\Router\Exceptions\RouterException $e) {
    http_response_code(500);
    echo "Router Error: " . $e->getMessage();
}
```

---

## CLI Tools

### 1. routes-list

```bash
# Показать все маршруты
php bin/routes-list

# С фильтром
php bin/routes-list --method=GET
php bin/routes-list --tag=api
php bin/routes-list --name=users.*
```

### 2. analyse

```bash
# Анализ маршрутов
php bin/analyse

# Показывает:
# - Общее количество маршрутов
# - Маршруты по методам
# - Маршруты по доменам
# - Маршруты с middleware
# - И т.д.
```

### 3. router

```bash
# Управление роутером
php bin/router compile        # Компилировать кеш
php bin/router clear          # Очистить кеш
php bin/router stats          # Статистика
```

---

##  

### 示例 1: REST API   

```php
use CloudCastle\Http\Router\Facade\Route;
use CloudCastle\Http\Router\BanManager;
use CloudCastle\Http\Router\Middleware\{
    AuthMiddleware,
    CorsMiddleware,
    SecurityLogger
};

// Настройка Auto-Ban
$banManager = new BanManager(5, 3600);

// API v1
Route::group([
    'prefix' => '/api/v1',
    'middleware' => [CorsMiddleware::class, SecurityLogger::class],
    'domain' => 'api.example.com',
    'https' => true,
    'tags' => ['api', 'v1'],
], function() use ($banManager) {
    
    // Публичные эндпоинты
    Route::get('/posts', [PostController::class, 'index'])
        ->name('api.v1.posts.index')
        ->throttle(100, 1)
        ->tag('public');
    
    // Защищенные эндпоинты
    Route::group(['middleware' => [AuthMiddleware::class]], function() use ($banManager) {
        
        Route::post('/posts', [PostController::class, 'store'])
            ->name('api.v1.posts.store')
            ->throttle(20, 1)
            ->getRateLimiter()
            ?->setBanManager($banManager);
        
        Route::put('/posts/{id}', [PostController::class, 'update'])
            ->name('api.v1.posts.update')
            ->where('id', '[0-9]+')
            ->throttle(30, 1);
        
        Route::delete('/posts/{id}', [PostController::class, 'destroy'])
            ->name('api.v1.posts.destroy')
            ->where('id', '[0-9]+')
            ->throttle(10, 1);
    });
});
```

### 示例 2:  

```php
// User Service (port 8081)
Route::group([
    'prefix' => '/users',
    'port' => 8081,
    'domain' => 'users.service.local',
    'tags' => ['user-service', 'microservice'],
], function() {
    Route::get('/', [UserServiceController::class, 'index']);
    Route::get('/{id}', [UserServiceController::class, 'show'])
        ->where('id', '[0-9]+');
    Route::post('/', [UserServiceController::class, 'create']);
});

// Product Service (port 8082)
Route::group([
    'prefix' => '/products',
    'port' => 8082,
    'domain' => 'products.service.local',
    'tags' => ['product-service', 'microservice'],
], function() {
    Route::get('/', [ProductServiceController::class, 'index']);
    Route::get('/{id}', [ProductServiceController::class, 'show']);
});

// Order Service (port 8083)
Route::group([
    'prefix' => '/orders',
    'port' => 8083,
    'domain' => 'orders.service.local',
    'tags' => ['order-service', 'microservice'],
], function() {
    Route::post('/', [OrderServiceController::class, 'create']);
    Route::get('/{id}', [OrderServiceController::class, 'show']);
});
```

### 示例 3: SaaS   

```php
// Free tier
Route::group([
    'prefix' => '/api/free',
    'middleware' => [AuthMiddleware::class],
    'tags' => ['free-tier'],
], function() {
    Route::get('/data', $action)
        ->throttle(10, 1);  // 10 запросов/мин
});

// Pro tier
Route::group([
    'prefix' => '/api/pro',
    'middleware' => [AuthMiddleware::class, ProMiddleware::class],
    'tags' => ['pro-tier'],
], function() {
    Route::get('/data', $action)
        ->throttle(100, 1);  // 100 запросов/мин
});

// Enterprise tier
Route::group([
    'prefix' => '/api/enterprise',
    'middleware' => [AuthMiddleware::class, EnterpriseMiddleware::class],
    'tags' => ['enterprise-tier'],
], function() {
    Route::get('/data', $action)
        ->throttle(1000, 1);  // 1000 запросов/мин
});
```

### 示例 4:  

```php
// Главный сайт
Route::group(['domain' => 'example.com'], function() {
    Route::get('/', [HomeController::class, 'index']);
    Route::get('/about', [AboutController::class, 'index']);
});

// API поддомен
Route::group(['domain' => 'api.example.com', 'https' => true], function() {
    Route::apiResource('/users', UserApiController::class);
    Route::apiResource('/posts', PostApiController::class);
});

// Админка
Route::group([
    'domain' => 'admin.example.com',
    'https' => true,
    'middleware' => [AuthMiddleware::class, AdminMiddleware::class],
    'whitelistIp' => ['192.168.1.0/24'],
], function() {
    Route::get('/dashboard', [AdminController::class, 'dashboard']);
    Route::resource('/users', AdminUserController::class);
});

// Блог
Route::group(['domain' => 'blog.example.com'], function() {
    Route::get('/', [BlogController::class, 'index']);
    Route::get('/{slug}', [BlogController::class, 'show']);
});
```

### 示例 5:   

```php
use CloudCastle\Http\Router\Router;
use CloudCastle\Http\Router\Plugin\ResponseCachePlugin;

$router = new Router();

// Включить кеш маршрутов
$router->enableCache(__DIR__ . '/var/cache/routes');

// Добавить плагин кеширования ответов
$responseCache = new ResponseCachePlugin(__DIR__ . '/var/cache/responses', 3600);
$router->registerPlugin($responseCache);

// Загрузить из кеша или зарегистрировать
if (!$router->loadFromCache()) {
    require __DIR__ . '/routes/web.php';
    require __DIR__ . '/routes/api.php';
    $router->compile();
}

// Dispatch
$route = $router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
$response = $route->run();

echo $response;
```

---

## 结论

CloudCastle HTTP Router  **209+ **   ,    -  PHP 8.2+.

### 主要 :

- ⚡ ** :** 54,891 req/sec
- 🔒 ** :** 12+  
- 💎 ** :** 209+ 方法
- 💾 ** :** 1.32 KB/route
- 📊 **:** 1,160,000+ routes
- ✅ **:** 501 测试, 0 

###  :

1.  [API Reference](API_REFERENCE.md)   
2.  [пр和меры](../../examples/)   
3.  [FAQ](FAQ.md)  响应   
4.   [отчетами по безопасности](SECURITY_REPORT.md)
5.  [анализ производительности](PERFORMANCE_ANALYSIS.md)

---

**© 2024 CloudCastle HTTP Router**  
**版本：** 1.1.1  
**:** MIT

[⬆ Наверх](#cloudcastle-http-router---полное-руководство-пользователя)


---

## 📚 文档导航

[README](../../README.md) | [USER_GUIDE](USER_GUIDE.md) | [FEATURES_INDEX](FEATURES_INDEX.md) | [API_REFERENCE](API_REFERENCE.md) | [ALL_FEATURES](ALL_FEATURES.md) | [TESTS_SUMMARY](TESTS_SUMMARY.md) | [PERFORMANCE](PERFORMANCE_ANALYSIS.md) | [SECURITY](SECURITY_REPORT.md) | [COMPARISON](COMPARISON.md) | [FAQ](FAQ.md) | [DOC_SUMMARY](DOCUMENTATION_SUMMARY.md)

**详细文档：** [Features](features/) (22 文件) | [Tests](tests/) (7 报告)

---

