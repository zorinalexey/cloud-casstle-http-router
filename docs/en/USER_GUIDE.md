# CloudCastle HTTP Router - Complete User Guide

[**English**](USER_GUIDE.md) | [Русский](../ru/USER_GUIDE.md) | [Deutsch](../de/USER_GUIDE.md) | [Français](../fr/USER_GUIDE.md) | [中文](../zh/USER_GUIDE.md)

---

## 📚 Documentation Navigation

[README](../../README.md) | [USER_GUIDE](USER_GUIDE.md) | [FEATURES_INDEX](FEATURES_INDEX.md) | [API_REFERENCE](API_REFERENCE.md) | [ALL_FEATURES](ALL_FEATURES.md) | [TESTS_SUMMARY](TESTS_SUMMARY.md) | [PERFORMANCE](PERFORMANCE_ANALYSIS.md) | [SECURITY](SECURITY_REPORT.md) | [COMPARISON](COMPARISON.md) | [FAQ](FAQ.md) | [DOC_SUMMARY](DOCUMENTATION_SUMMARY.md)

**Detailed documentation:** [Features](features/) (22 files) | [Tests](tests/) (7 reports)

---

**Version:** 1.1.1  
**Date:** October 2025  
**Features:** 209+

---

## 📑 Table of Contents

1. [Introduction](#introduction)
2. [Installation and Setup](#installation-and-setup)
3. [Basic Routing (13 methods)](#basic-routing)
4. [Route Parameters (6 ways)](#route-parameters)
5. [Route Groups (12 attributes)](#route-groups)
6. [Rate Limiting (8 methods)](#rate-limiting)
7. [Auto-Ban System (7 methods)](#auto-ban-system)
8. [IP Filtering (4 methods)](#ip-filtering)
9. [Middleware (6 types)](#middleware)
10. [Named Routes (6 methods)](#named-routes)
11. [Tags (5 methods)](#tags)
12. [Helper Functions (18 functions)](#helper-functions)
13. [Route Shortcuts (14 methods)](#route-shortcuts)
14. [Route Macros (7 macros)](#route-macros)
15. [URL Generation (11 methods)](#url-generation)
16. [Expression Language (5 operators)](#expression-language)
17. [Route Caching (6 methods)](#route-caching)
18. [Plugin System (13 methods)](#plugin-system)
19. [Route Loaders (5 types)](#route-loaders)
20. [PSR Support (3 standards)](#psr-support)
21. [Action Resolver (6 formats)](#action-resolver)
22. [Statistics and Queries (24 methods)](#statistics-and-queries)
23. [Security (12 mechanisms)](#security)
24. [Exceptions (8 types)](#exceptions)
25. [CLI Tools (3 commands)](#cli-tools)
26. [Advanced Examples](#advanced-examples)

---

## Introduction

CloudCastle HTTP Router is a **high-performance** (54k+ req/sec), **secure** (OWASP Top 10), and **feature-rich** (209+ capabilities) routing library for PHP 8.2+.

### Key Features

- ⚡ **Performance:** 54,891 requests/sec
- 🔒 **Security:** 12+ built-in protection mechanisms
- 💎 **Functionality:** 209+ methods and features
- 💾 **Efficiency:** 1.32 KB per route
- 📊 **Scalability:** 1,160,000+ routes
- ✅ **Reliability:** 501 tests, 0 errors

---

## Installation and Setup

### Requirements

- PHP 8.2 or higher
- Composer
- PSR-7/PSR-15 (optional)

### Installation via Composer

```bash
composer require cloud-castle/http-router
```

### Quick Start

```php
<?php

require __DIR__ . '/vendor/autoload.php';

use CloudCastle\Http\Router\Facade\Route;

// Register routes
Route::get('/users', fn() => 'List of users');
Route::post('/users', fn() => 'Create user');

// Dispatch
$route = Route::dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
echo $route->run();
```

---

## Basic Routing

### 1. GET Route

```php
use CloudCastle\Http\Router\Facade\Route;

Route::get('/users', function() {
    return 'List of users';
});
```

### 2. POST Route

```php
Route::post('/users', function() {
    return 'Create user';
});
```

### 3. PUT Route

```php
Route::put('/users/{id}', function($id) {
    return "Update user: $id";
});
```

### 4. PATCH Route

```php
Route::patch('/users/{id}', function($id) {
    return "Partial update user: $id";
});
```

### 5. DELETE Route

```php
Route::delete('/users/{id}', function($id) {
    return "Delete user: $id";
});
```

### 6. VIEW Route (custom)

```php
Route::view('/preview', function() {
    return 'Preview content';
});
```

### 7. Custom HTTP Method

```php
Route::custom('PURGE', '/cache', function() {
    return 'Cache purged';
});

Route::custom('TRACE', '/debug', function() {
    return 'Debug trace';
});
```

### 8. Multiple HTTP Methods

```php
Route::match(['GET', 'POST'], '/form', function() {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        return 'Show form';
    }
    return 'Process form';
});
```

### 9. All HTTP Methods

```php
Route::any('/webhook', function() {
    return 'Handle any method';
});
```

### 10. Using Router Instance

```php
use CloudCastle\Http\Router\Router;

$router = new Router();

$router->get('/users', fn() => 'Users');
$router->post('/users', fn() => 'Create');

$route = $router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
```

### 11-13. Static Router Methods

```php
use CloudCastle\Http\Router\Router;

// Singleton pattern
Router::staticGet('/users', fn() => 'Users');
Router::staticPost('/users', fn() => 'Create');
Router::staticDelete('/users/{id}', fn($id) => "Delete: $id");
```

---

## Route Parameters

### 1. Basic Parameters

```php
Route::get('/users/{id}', function($id) {
    return "User ID: $id";
});

Route::get('/posts/{slug}', function($slug) {
    return "Post: $slug";
});

// Multiple parameters
Route::get('/users/{userId}/posts/{postId}', function($userId, $postId) {
    return "User $userId, Post $postId";
});
```

### 2. Parameter Constraints (where)

```php
// Only digits
Route::get('/users/{id}', $action)
    ->where('id', '[0-9]+');

// Letters and hyphens
Route::get('/posts/{slug}', $action)
    ->where('slug', '[a-z0-9-]+');

// Multiple constraints
Route::get('/api/{version}/users/{id}', $action)
    ->where([
        'version' => 'v[0-9]+',
        'id' => '[0-9]+'
    ]);
```

### 3. Inline Parameters

```php
// Pattern in URI itself
Route::get('/users/{id:[0-9]+}', $action);
Route::get('/posts/{slug:[a-z0-9-]+}', $action);
Route::get('/files/{path:.+}', $action);
```

### 4. Optional Parameters

```php
Route::get('/blog/{category?}', function($category = 'all') {
    return "Category: $category";
});

Route::get('/search/{query?}/{page?}', function($query = '', $page = 1) {
    return "Search: $query, Page: $page";
});
```

### 5. Default Values

```php
Route::get('/posts/{page}', $action)
    ->defaults(['page' => 1]);

Route::get('/search/{query}/{limit}', $action)
    ->defaults([
        'query' => '',
        'limit' => 10
    ]);
```

### 6. Getting Parameters

```php
Route::get('/users/{id}', function($id) {
    $route = Route::current();
    $params = $route->getParameters();
    // ['id' => '123']
    
    return "User: $id";
});
```

---

## Route Groups

### 1. Group with Prefix

```php
Route::group(['prefix' => '/api'], function() {
    Route::get('/users', $action);     // /api/users
    Route::get('/posts', $action);     // /api/posts
});
```

### 2. Group with Middleware

```php
Route::group(['middleware' => [AuthMiddleware::class]], function() {
    Route::get('/dashboard', $action);
    Route::get('/profile', $action);
});
```

### 3. Group with Domain

```php
Route::group(['domain' => 'api.example.com'], function() {
    Route::get('/users', $action);
    Route::get('/posts', $action);
});
```

### 4. Group with Port

```php
Route::group(['port' => 8080], function() {
    Route::get('/admin', $action);
});
```

### 5. Group with Namespace

```php
Route::group(['namespace' => 'App\\Controllers\\Api'], function() {
    Route::get('/users', 'UserController@index');
    Route::get('/posts', 'PostController@index');
});
```

### 6. Group with HTTPS Requirement

```php
Route::group(['https' => true], function() {
    Route::get('/secure', $action);
    Route::post('/payment', $action);
});
```

### 7. Group with Protocols

```php
Route::group(['protocols' => ['ws', 'wss']], function() {
    Route::get('/chat', $action);
    Route::get('/notifications', $action);
});
```

### 8. Group with Tags

```php
Route::group(['tags' => ['api', 'public']], function() {
    Route::get('/users', $action);
    Route::get('/posts', $action);
});
```

### 9. Group with Throttle

```php
Route::group(['throttle' => [60, 1]], function() {
    Route::get('/api/data', $action);
    Route::post('/api/submit', $action);
});
```

### 10. Group with IP Whitelist

```php
Route::group(['whitelistIp' => ['192.168.1.0/24']], function() {
    Route::get('/admin/users', $action);
    Route::get('/admin/settings', $action);
});
```

### 11. Nested Groups

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

### 12. Combined Attributes

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

### Getting RouteGroup Object

```php
$group = Route::group(['prefix' => '/api'], function() {
    Route::get('/users', $action);
    Route::get('/posts', $action);
});

// RouteGroup methods
$routes = $group->getRoutes();        // All group routes
$count = $group->count();             // Route count
$attrs = $group->getAttributes();     // Group attributes
```

---

## Rate Limiting

### 1. Basic Throttle

```php
// 60 requests per minute
Route::post('/api/submit', $action)
    ->throttle(60, 1);

// 100 requests per hour
Route::post('/api/upload', $action)
    ->throttle(100, 60);
```

### 2. TimeUnit Enum

```php
use CloudCastle\Http\Router\TimeUnit;

// 5 requests per second
Route::post('/api/fast', $action)
    ->throttle(5, TimeUnit::SECOND->value);

// 100 requests per minute
Route::post('/api/normal', $action)
    ->throttle(100, TimeUnit::MINUTE->value);

// 1000 requests per hour
Route::post('/api/slow', $action)
    ->throttle(1000, TimeUnit::HOUR->value);

// 10000 requests per day
Route::post('/api/daily', $action)
    ->throttle(10000, TimeUnit::DAY->value);

// 50000 requests per week
Route::post('/api/weekly', $action)
    ->throttle(50000, TimeUnit::WEEK->value);

// 200000 requests per month
Route::post('/api/monthly', $action)
    ->throttle(200000, TimeUnit::MONTH->value);
```

### 3. Custom Throttle Key

```php
Route::post('/api/user-specific', $action)
    ->throttle(30, 1, function($request) {
        // Limit by user ID
        return 'user_' . $request->userId;
    });

Route::post('/api/ip-specific', $action)
    ->throttle(60, 1, function($request) {
        // Limit by IP
        return $request->ip();
});
```

### 4. Getting RateLimiter

```php
$route = Route::post('/api/data', $action)
    ->throttle(60, 1);

$rateLimiter = $route->getRateLimiter();
```

### 5. RateLimiter Methods

```php
use CloudCastle\Http\Router\RateLimiter;

$limiter = new RateLimiter(60, 1);  // 60 requests per minute

// Check if limit exceeded
if ($limiter->tooManyAttempts('user_123')) {
    $seconds = $limiter->availableIn('user_123');
    echo "Retry after $seconds seconds";
}

// Add attempt
$limiter->attempt('user_123');

// Remaining attempts
$remaining = $limiter->remaining('user_123');

// Reset counter
$limiter->clear('user_123');

// Clear all
$limiter->clearAll();

// Get max attempts
$max = $limiter->getMaxAttempts();

// Get decay period in minutes
$period = $limiter->getDecayMinutes();
```

### 6. Setting BanManager for RateLimiter

```php
use CloudCastle\Http\Router\BanManager;

$banManager = new BanManager(5, 3600);  // 5 violations = ban for 1 hour

Route::post('/login', $action)
    ->throttle(3, 1)
    ->getRateLimiter()
    ?->setBanManager($banManager);
```

### 7-8. Throttle Shortcuts

```php
// 60 requests per minute
Route::post('/api/standard', $action)->throttleStandard();

// 10 requests per minute
Route::post('/api/strict', $action)->throttleStrict();

// 1000 requests per minute
Route::post('/api/generous', $action)->throttleGenerous();
```

---

## Auto-Ban System

### 1. Creating BanManager

```php
use CloudCastle\Http\Router\BanManager;

// 5 violations = ban for 1 hour (3600 sec)
$banManager = new BanManager(
    maxViolations: 5,
    banDuration: 3600
);
```

### 2. Enabling Auto-Ban

```php
$banManager->enableAutoBan(5);  // Auto-ban after 5 violations
```

### 3. Manual IP Blocking

```php
// Ban IP for 1 hour
$banManager->ban('1.2.3.4', 3600);

// Ban IP permanently (0 seconds)
$banManager->ban('5.6.7.8', 0);
```

### 4. Unblocking IP

```php
$banManager->unban('1.2.3.4');
```

### 5. Checking Ban

```php
if ($banManager->isBanned('1.2.3.4')) {
    throw new \CloudCastle\Http\Router\Exceptions\BannedException(
        'Your IP is banned'
    );
}
```

### 6. Getting List of Banned IPs

```php
$bannedIps = $banManager->getBannedIps();
// ['1.2.3.4', '5.6.7.8']
```

### 7. Clearing All Bans

```php
$banManager->clearAll();
```

### Complete Example with Auto-Ban

```php
use CloudCastle\Http\Router\BanManager;
use CloudCastle\Http\Router\Facade\Route;

$banManager = new BanManager(5, 3600);

Route::post('/login', function() {
    // Login logic
    return 'Login success';
})
->throttle(3, 1)  // 3 attempts per minute
->getRateLimiter()
?->setBanManager($banManager);

// After exceeding limit 5 times - automatic ban for 1 hour
```

---

## IP Filtering

### 1. Whitelist IP

```php
// Single IP
Route::get('/admin', $action)
    ->whitelistIp(['192.168.1.1']);

// Multiple IPs
Route::get('/admin', $action)
    ->whitelistIp([
        '192.168.1.1',
        '192.168.1.2',
        '10.0.0.1'
    ]);
```

### 2. CIDR Notation

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

### 4. Combining Whitelist and Blacklist

```php
Route::get('/api/data', $action)
    ->whitelistIp(['192.168.1.0/24'])  // Allow local network
    ->blacklistIp(['192.168.1.100']);   // Except this IP
```

---

## Middleware

### 1. Global Middleware

```php
use CloudCastle\Http\Router\Middleware\CorsMiddleware;

Route::middleware([CorsMiddleware::class]);
```

### 2. Route Middleware

```php
use CloudCastle\Http\Router\Middleware\AuthMiddleware;

Route::get('/dashboard', $action)
    ->middleware([AuthMiddleware::class]);
```

### 3. Multiple Middleware

```php
Route::get('/admin/users', $action)
    ->middleware([
        AuthMiddleware::class,
        AdminMiddleware::class,
        LoggerMiddleware::class
    ]);
```

### 4. Built-in Middleware

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

### 5. Creating Custom Middleware

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

## Named Routes

### 1. Assigning Name

```php
Route::get('/users/{id}', $action)
    ->name('users.show');

Route::post('/users', $action)
    ->name('users.store');
```

### 2. Getting Route by Name

```php
$route = Route::getRouteByName('users.show');
```

### 3. Current Route Name

```php
$name = Route::currentRouteName();
// 'users.show'
```

### 4. Checking Current Route Name

```php
if (Route::currentRouteNamed('users.show')) {
    echo "Viewing user";
}
```

### 5. Auto-Naming

```php
// Enable auto-naming
Route::enableAutoNaming();

// Routes will automatically get names
Route::get('/users', $action);       // auto: users.get
Route::post('/users', $action);      // auto: users.post
Route::get('/users/{id}', $action);  // auto: users.id.get

// Examples with API
Route::get('/api/v1/users', $action);         // auto: api.v1.users.get
Route::post('/api/v1/users/{id}', $action);   // auto: api.v1.users.id.post

// Root route
Route::get('/', $action);                     // auto: root.get

// Special characters are normalized
Route::get('/api-v1/user_profile', $action);  // auto: api.v1.user.profile.get

// Disable auto-naming
Route::disableAutoNaming();

// Check status
$enabled = Route::router()->isAutoNamingEnabled();
```

### 6. Getting All Named Routes

```php
$namedRoutes = Route::getNamedRoutes();
// ['users.show' => Route, 'users.store' => Route, ...]
```

---

## Tags

### 1. Adding Single Tag

```php
Route::get('/api/users', $action)
    ->tag('api');
```

### 2. Multiple Tags

```php
Route::get('/api/public/posts', $action)
    ->tag(['api', 'public', 'posts']);
```

### 3. Getting Routes by Tag

```php
$apiRoutes = Route::getRoutesByTag('api');
```

### 4. Checking Tag Existence

```php
if (Route::router()->hasTag('api')) {
    echo "Has API routes";
}
```

### 5. Getting All Tags

```php
$allTags = Route::router()->getAllTags();
// ['api', 'public', 'admin', ...]
```

---

## Helper Functions

### 1. route()

```php
// Get route by name
$route = route('users.show');
```

### 2. current_route()

```php
// Get current route
$current = current_route();
echo $current->getUri();
```

### 3. previous_route()

```php
// Get previous route
$previous = previous_route();
```

### 4. route_is()

```php
// Check route name (with wildcard support)
if (route_is('users.*')) {
    echo "User route";
}

if (route_is('admin.users.show')) {
    echo "Admin user show";
}
```

### 5. route_name()

```php
// Get current route name
$name = route_name();
// 'users.show'
```

### 6. router()

```php
// Get router instance
$router = router();
$routes = $router->getRoutes();
```

### 7. dispatch_route()

```php
// Dispatch route
$route = dispatch_route('/users/123', 'GET');
```

### 8. route_url()

```php
// Generate URL
$url = route_url('users.show', ['id' => 123]);
// '/users/123'

$url = route_url('posts.show', ['slug' => 'hello-world']);
// '/posts/hello-world'
```

### 9. route_has()

```php
// Check route existence
if (route_has('users.show')) {
    echo "Route exists";
}
```

### 10. route_stats()

```php
// Get route statistics
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
// Get routes by tag
$apiRoutes = routes_by_tag('api');
```

### 12. route_back()

```php
// Go back to previous route
$previous = route_back();
```

### 13-18. Additional Helpers

```php
// Check if current route is named
if (route_is('users.show')) {
    // ...
}

// Get current route parameters
$route = current_route();
$params = $route->getParameters();

// Get current route middleware
$middleware = current_route()->getMiddleware();

// Get current route tags
$tags = current_route()->getTags();
```

---

## Route Shortcuts

### 1. auth()

```php
Route::get('/dashboard', $action)->auth();
// Adds AuthMiddleware
```

### 2. guest()

```php
Route::get('/login', $action)->guest();
// Only for unauthenticated users
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
// Only localhost (127.0.0.1)
```

### 7. secure()

```php
Route::get('/payment', $action)->secure();
// HTTPS only
```

### 8-10. Throttle Shortcuts

```php
// 60 requests per minute (standard)
Route::post('/api/data', $action)->throttleStandard();

// 10 requests per minute (strict)
Route::post('/api/critical', $action)->throttleStrict();

// 1000 requests per minute (generous)
Route::post('/api/bulk', $action)->throttleGenerous();
```

### 11. public()

```php
Route::get('/page', $action)->public();
// Tag 'public'
```

### 12. private()

```php
Route::get('/page', $action)->private();
// Tag 'private'
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

// Creates RESTful routes for resource
Route::resource('/users', UserController::class);

// Creates:
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
// API resource (without create/edit pages)
Route::apiResource('/posts', PostController::class, 100);

// Creates:
// GET    /posts       -> PostController::index    (throttle: 100/min)
// POST   /posts       -> PostController::store    (throttle: 100/min)
// GET    /posts/{id}  -> PostController::show     (throttle: 100/min)
// PUT    /posts/{id}  -> PostController::update   (throttle: 100/min)
// DELETE /posts/{id}  -> PostController::destroy  (throttle: 100/min)
```

### 3. crud()

```php
// Simple CRUD
Route::crud('/products', ProductController::class);

// Creates:
// GET    /products       -> ProductController::index
// POST   /products       -> ProductController::create
// GET    /products/{id}  -> ProductController::read
// PUT    /products/{id}  -> ProductController::update
// DELETE /products/{id}  -> ProductController::delete
```

### 4. auth()

```php
// Authentication routes
Route::auth();

// Creates:
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
// Admin panel with IP whitelist
Route::adminPanel('/admin', ['192.168.1.0/24']);

// Creates (with Auth + Admin middleware + HTTPS):
// GET /admin/dashboard -> AdminController::dashboard
// GET /admin/users     -> AdminController::users
// GET /admin/settings  -> AdminController::settings
// GET /admin/logs      -> AdminController::logs
```

### 6. apiVersion()

```php
// API versioning
Route::apiVersion('v1', function() {
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/posts', [PostController::class, 'index']);
});

// Routes available as /api/v1/users, /api/v1/posts
```

### 7. webhooks()

```php
// Webhooks with IP whitelist
Route::webhooks('/webhooks', ['192.168.1.0/24']);

// Creates:
// POST /webhooks/github  -> WebhookController::github
// POST /webhooks/stripe  -> WebhookController::stripe
// POST /webhooks/custom  -> WebhookController::custom
```

---

## URL Generation

### 1. Basic Generation

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

### 3. URL with Domain

```php
$url = $generator->generate('users.show', ['id' => 123])
    ->toDomain('api.example.com');
// 'http://api.example.com/users/123'
```

### 4. URL with Protocol

```php
$url = $generator->generate('users.show', ['id' => 123])
    ->toProtocol('https');
// 'https://example.com/users/123'
```

### 5. URL with Query Parameters

```php
$url = $generator->generate('users.index', [], [
    'page' => 2,
    'limit' => 10,
    'sort' => 'name'
]);
// '/users?page=2&limit=10&sort=name'
```

### 6. Signed URL

```php
$signedUrl = $generator->signed('verify.email', ['user' => 123], 3600);
// '/verify/email/123?signature=abc...&expires=1234567890'
```

### 7. Setting Base URL

```php
$generator->setBaseUrl('https://api.example.com');
```

### 8-11. Combined Generation

```php
$url = $generator->generate('api.users.show', ['id' => 123])
    ->toDomain('api.example.com')
    ->toProtocol('https')
    ->absolute();
// 'https://api.example.com/api/users/123'

// Via helper
$url = route_url('users.show', ['id' => 123]);
```

---

## Expression Language

### 1. Basic Condition

```php
Route::get('/admin', $action)
    ->condition('request.ip == "192.168.1.1"');
```

### 2. Comparison Operators

```php
Route::get('/api/data', $action)
    ->condition('request.time > 9 and request.time < 18');

Route::get('/premium', $action)
    ->condition('user.level >= 5');

Route::get('/limited', $action)
    ->condition('request.count <= 100');
```

### 3. Logical Operators

```php
Route::get('/api/secure', $action)
    ->condition('request.ip == "192.168.1.1" and request.method == "GET"');

Route::get('/public', $action)
    ->condition('request.path == "/public" or request.path == "/open"');
```

### 4. ExpressionLanguage Class

```php
use CloudCastle\Http\Router\ExpressionLanguage\ExpressionLanguage;

$expr = new ExpressionLanguage();

$result = $expr->evaluate('10 > 5 and 20 < 30', []);
// true

$result = $expr->evaluate('user.age >= 18', ['user' => ['age' => 25]]);
// true
```

### 5. Complex Expressions

```php
Route::get('/api/restricted', $action)
    ->condition(
        '(request.ip == "192.168.1.1" or request.ip == "10.0.0.1") ' .
        'and request.time >= 9 and request.time <= 18'
    );
```

---

## Route Caching

### 1. Enabling Cache

```php
$router->enableCache('var/cache/routes');
```

### 2. Compiling Routes

```php
// Compile
$router->compile();

// Force compile
$router->compile(force: true);
```

### 3. Loading from Cache

```php
if ($router->loadFromCache()) {
    echo "Routes loaded from cache";
} else {
    // Register routes
    require 'routes/web.php';
    $router->compile();
}
```

### 4. Clearing Cache

```php
$router->clearCache();
```

### 5. Auto-Compilation

```php
$router->autoCompile();
// Automatically compiles on changes
```

### 6. Checking Cache Load

```php
if ($router->isCacheLoaded()) {
    echo "Cache is loaded";
}
```

### Complete Example with Caching

```php
use CloudCastle\Http\Router\Router;

$router = new Router();
$router->enableCache(__DIR__ . '/var/cache/routes');

if (!$router->loadFromCache()) {
    // Register routes
    require __DIR__ . '/routes/web.php';
    require __DIR__ . '/routes/api.php';
    
    // Compile
    $router->compile();
}

// Use routes
$route = $router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
```

---

## Plugin System

### 1. PluginInterface

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

### 2. Registering Plugin

```php
Route::registerPlugin(new LoggerPlugin());
```

### 3. Unregistering Plugin

```php
Route::unregisterPlugin('logger');
```

### 4. Getting Plugin

```php
$plugin = Route::getPlugin('logger');
```

### 5. Checking Plugin Existence

```php
if (Route::hasPlugin('logger')) {
    echo "Logger plugin registered";
}
```

### 6. Getting All Plugins

```php
$plugins = Route::getPlugins();
```

### 7. LoggerPlugin (built-in)

```php
use CloudCastle\Http\Router\Plugin\LoggerPlugin;

$logger = new LoggerPlugin('/var/log/routes.log');
Route::registerPlugin($logger);
```

### 8. AnalyticsPlugin (built-in)

```php
use CloudCastle\Http\Router\Plugin\AnalyticsPlugin;

$analytics = new AnalyticsPlugin();
Route::registerPlugin($analytics);

// Get statistics
$stats = $analytics->getStats();
```

### 9. ResponseCachePlugin (built-in)

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
        // Logic before dispatch
    }
    
    public function afterDispatch(Route $route, mixed $result): mixed
    {
        // Logic after dispatch
        return $result;
    }
}
```

### 11-13. Plugin Hooks

```php
class FullPlugin implements PluginInterface
{
    // Hook before dispatch
    public function beforeDispatch(Route $route, string $uri, string $method): void
    {
        echo "Before: $method $uri\n";
    }
    
    // Hook after dispatch
    public function afterDispatch(Route $route, mixed $result): mixed
    {
        echo "After dispatch\n";
        return $result;
    }
    
    // Hook on route registration
    public function onRouteRegistered(Route $route): void
    {
        echo "Route registered: {$route->getUri()}\n";
    }
    
    // Hook on exception
    public function onException(Route $route, \Exception $e): void
    {
        echo "Exception: {$e->getMessage()}\n";
    }
}
```

---

## Route Loaders

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

### 5. PHP Files (standard way)

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

// Use with router
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

// Use as PSR-15 middleware
$response = $adapter->process($request, $handler);
```

---

## Action Resolver

CloudCastle HTTP Router supports **6 formats** for route actions:

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

### 5. Invokable Controller

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
// ActionResolver will automatically resolve dependencies
```

---

## Statistics and Queries

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
// All routes containing 'user' in URI or name
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

## Security

### 1. Path Traversal Protection

```php
// Router automatically protects against ../../../
Route::get('/files/{path}', function($path) {
    // $path will never contain ../
    return "File: $path";
});
```

### 2. SQL Injection Protection

```php
// Parameters are automatically validated
Route::get('/users/{id}', function($id) {
    // Safe to use in SQL
    return DB::find($id);
})->where('id', '[0-9]+');
```

### 3. XSS Protection

```php
Route::get('/search/{query}', function($query) {
    // Escape output
    return htmlspecialchars($query);
});
```

### 4. Rate Limiting

```php
// DDoS protection
Route::post('/api/submit', $action)
    ->throttle(60, 1);
```

### 5. IP Filtering

```php
// Whitelist only trusted IPs
Route::get('/admin', $action)
    ->whitelistIp(['192.168.1.0/24']);
```

### 6. HTTPS Enforcement

```php
// Force HTTPS usage
Route::get('/payment', $action)->https();
Route::get('/secure', $action)->secure();
```

### 7. Protocol Restrictions

```php
// Only HTTPS/WSS
Route::get('/ws/secure', $action)
    ->protocol(['wss']);
```

### 8. ReDoS Protection

```php
// Router protects against regex DoS
// Safe patterns automatically
Route::get('/users/{id}', $action)
    ->where('id', '[0-9]+');  // Safe
```

### 9. Method Override Protection

```php
// Protection against method spoofing
// Router checks real HTTP method
```

### 10. Cache Injection Protection

```php
// Secure caching
$router->enableCache('var/cache/routes');
// Cache is signed and validated
```

### 11. IP Spoofing Protection

```php
// Router checks X-Forwarded-For
// and protects against IP spoofing
```

### 12. Auto-Ban System

```php
// Automatic blocking of attacking IPs
$banManager = new BanManager(5, 3600);
Route::post('/login', $action)
    ->throttle(3, 1)
    ->getRateLimiter()
    ?->setBanManager($banManager);
```

---

## Exceptions

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
    $route = Route::dispatch('/users', 'DELETE');  // Method not allowed
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
    // Any router error
} catch (\CloudCastle\Http\Router\Exceptions\RouterException $e) {
    http_response_code(500);
    echo "Router Error: " . $e->getMessage();
}
```

---

## CLI Tools

### 1. routes-list

```bash
# Show all routes
php bin/routes-list

# With filter
php bin/routes-list --method=GET
php bin/routes-list --tag=api
php bin/routes-list --name=users.*
```

### 2. analyse

```bash
# Route analysis
php bin/analyse

# Shows:
# - Total routes count
# - Routes by methods
# - Routes by domains
# - Routes with middleware
# - Etc.
```

### 3. router

```bash
# Router management
php bin/router compile        # Compile cache
php bin/router clear          # Clear cache
php bin/router stats          # Statistics
```

---

## Advanced Examples

### Example 1: REST API with Full Protection

```php
use CloudCastle\Http\Router\Facade\Route;
use CloudCastle\Http\Router\BanManager;
use CloudCastle\Http\Router\Middleware\{
    AuthMiddleware,
    CorsMiddleware,
    SecurityLogger
};

// Setup Auto-Ban
$banManager = new BanManager(5, 3600);

// API v1
Route::group([
    'prefix' => '/api/v1',
    'middleware' => [CorsMiddleware::class, SecurityLogger::class],
    'domain' => 'api.example.com',
    'https' => true,
    'tags' => ['api', 'v1'],
], function() use ($banManager) {
    
    // Public endpoints
    Route::get('/posts', [PostController::class, 'index'])
        ->name('api.v1.posts.index')
        ->throttle(100, 1)
        ->tag('public');
    
    // Protected endpoints
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

### Example 2: Microservice Architecture

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

### Example 3: SaaS Platform with Tiers

```php
// Free tier
Route::group([
    'prefix' => '/api/free',
    'middleware' => [AuthMiddleware::class],
    'tags' => ['free-tier'],
], function() {
    Route::get('/data', $action)
        ->throttle(10, 1);  // 10 requests/min
});

// Pro tier
Route::group([
    'prefix' => '/api/pro',
    'middleware' => [AuthMiddleware::class, ProMiddleware::class],
    'tags' => ['pro-tier'],
], function() {
    Route::get('/data', $action)
        ->throttle(100, 1);  // 100 requests/min
});

// Enterprise tier
Route::group([
    'prefix' => '/api/enterprise',
    'middleware' => [AuthMiddleware::class, EnterpriseMiddleware::class],
    'tags' => ['enterprise-tier'],
], function() {
    Route::get('/data', $action)
        ->throttle(1000, 1);  // 1000 requests/min
});
```

### Example 4: Multi-Domain Application

```php
// Main site
Route::group(['domain' => 'example.com'], function() {
    Route::get('/', [HomeController::class, 'index']);
    Route::get('/about', [AboutController::class, 'index']);
});

// API subdomain
Route::group(['domain' => 'api.example.com', 'https' => true], function() {
    Route::apiResource('/users', UserApiController::class);
    Route::apiResource('/posts', PostApiController::class);
});

// Admin
Route::group([
    'domain' => 'admin.example.com',
    'https' => true,
    'middleware' => [AuthMiddleware::class, AdminMiddleware::class],
    'whitelistIp' => ['192.168.1.0/24'],
], function() {
    Route::get('/dashboard', [AdminController::class, 'dashboard']);
    Route::resource('/users', AdminUserController::class);
});

// Blog
Route::group(['domain' => 'blog.example.com'], function() {
    Route::get('/', [BlogController::class, 'index']);
    Route::get('/{slug}', [BlogController::class, 'show']);
});
```

### Example 5: Caching for Performance

```php
use CloudCastle\Http\Router\Router;
use CloudCastle\Http\Router\Plugin\ResponseCachePlugin;

$router = new Router();

// Enable route cache
$router->enableCache(__DIR__ . '/var/cache/routes');

// Add response caching plugin
$responseCache = new ResponseCachePlugin(__DIR__ . '/var/cache/responses', 3600);
$router->registerPlugin($responseCache);

// Load from cache or register
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

## Conclusion

CloudCastle HTTP Router provides **209+ features** for building modern, secure, and high-performance web applications on PHP 8.2+.

### Main Advantages:

- ⚡ **High Performance:** 54,891 req/sec
- 🔒 **Comprehensive Security:** 12+ protection mechanisms
- 💎 **Rich Functionality:** 209+ methods
- 💾 **Efficient Memory:** 1.32 KB/route
- 📊 **Scalability:** 1,160,000+ routes
- ✅ **Reliability:** 501 tests, 0 errors

### Next Steps:

1. Study [API Reference](API_REFERENCE.md) for detailed information
2. View [examples](../../examples/) for practical application
3. Read [FAQ](FAQ.md) for answers to common questions
4. Review [security reports](SECURITY_REPORT.md)
5. Check [performance analysis](PERFORMANCE_ANALYSIS.md)

---

**© 2024 CloudCastle HTTP Router**  
**Version:** 1.1.1  
**License:** MIT

[⬆ Back to top](#cloudcastle-http-router---complete-user-guide)


---

## 📚 Documentation Navigation

[README](../../README.md) | [USER_GUIDE](USER_GUIDE.md) | [FEATURES_INDEX](FEATURES_INDEX.md) | [API_REFERENCE](API_REFERENCE.md) | [ALL_FEATURES](ALL_FEATURES.md) | [TESTS_SUMMARY](TESTS_SUMMARY.md) | [PERFORMANCE](PERFORMANCE_ANALYSIS.md) | [SECURITY](SECURITY_REPORT.md) | [COMPARISON](COMPARISON.md) | [FAQ](FAQ.md) | [DOC_SUMMARY](DOCUMENTATION_SUMMARY.md)

**Detailed documentation:** [Features](features/) (22 files) | [Tests](tests/) (7 reports)

---
