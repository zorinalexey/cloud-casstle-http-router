# Complete List of CloudCastle HTTP Router Features

[**English**](ALL_FEATURES.md) | [Русский](../ru/ALL_FEATURES.md) | [Deutsch](../de/ALL_FEATURES.md) | [Français](../fr/ALL_FEATURES.md) | [中文](../zh/ALL_FEATURES.md)

---

## 📚 Documentation Navigation

[README](../../README.md) | [USER_GUIDE](USER_GUIDE.md) | [FEATURES_INDEX](FEATURES_INDEX.md) | [API_REFERENCE](API_REFERENCE.md) | [ALL_FEATURES](ALL_FEATURES.md) | [TESTS_SUMMARY](TESTS_SUMMARY.md) | [PERFORMANCE](PERFORMANCE_ANALYSIS.md) | [SECURITY](SECURITY_REPORT.md) | [COMPARISON](COMPARISON.md) | [FAQ](FAQ.md) | [DOC_SUMMARY](DOCUMENTATION_SUMMARY.md)

**Detailed Documentation:** [Features](features/) (22 files) | [Tests](tests/) (7 reports)

---

## Contents

- [1. Basic Routing](#1-basic-routing)
- [2. Helper Functions](#2-helper-functions)
- [3. Route Shortcuts](#3-route-shortcuts)
- [4. Route Macros](#4-route-macros)
- [5. Route Groups](#5-route-groups)
- [6. Middleware](#6-middleware)
- [7. Rate Limiting](#7-rate-limiting)
- [8. IP Filtering](#8-ip-filtering)
- [9. Auto-Ban System](#9-auto-ban-system)
- [10. Named Routes](#10-named-routes)
- [11. Tags](#11-tags)
- [12. Route Parameters](#12-route-parameters)
- [13. Expression Language](#13-expression-language)
- [14. URL Generation](#14-url-generation)
- [15. Caching](#15-caching)
- [16. Plugins](#16-plugins)
- [17. Loaders](#17-loaders)
- [18. PSR Support](#18-psr-support)
- [19. Action Resolver](#19-action-resolver)
- [20. Statistics and Filtering](#20-statistics-and-filtering)

---

## 1. Basic Routing

### HTTP Methods

```php
use CloudCastle\Http\Router\Router;

$router = new Router();

// All standard methods
$router->get('/users', $action);
$router->post('/users', $action);
$router->put('/users/{id}', $action);
$router->patch('/users/{id}', $action);
$router->delete('/users/{id}', $action);

// Custom methods
$router->view('/page', $action);  // VIEW method
$router->custom('PURGE', '/cache', $action);  // Any method

// Multiple methods
$router->match(['GET', 'POST'], '/form', $action);
$router->any('/endpoint', $action);  // All methods
```

### Facade API

```php
use CloudCastle\Http\Router\Facade\Route;

Route::get('/api/users', $action);
Route::post('/api/users', $action);
// And so on...
```

---

## 2. Helper Functions

### route()

Get route by name or current route:

```php
// Get route by name
$route = route('users.show');

// Get current route
$current = route();
```

### current_route()

Get current route:

```php
$currentRoute = current_route();
echo $currentRoute->getName();
```

### previous_route()

Get previous route:

```php
$prevRoute = previous_route();
```

### route_is()

Check current route name:

```php
if (route_is('users.index')) {
    // Current route is users.index
}
```

### route_name()

Get current route name:

```php
$name = route_name(); // 'users.show'
```

### router()

Get router instance:

```php
$router = router();
$stats = $router->getRouteStats();
```

### dispatch_route()

Dispatch current HTTP request:

```php
$route = dispatch_route();
if ($route) {
    echo $route->run();
}
```

---

## 3. Route Shortcuts

### resource()

Create RESTful resource routes:

```php
Route::resource('users', UserController::class);
// Creates: GET, POST, PUT, PATCH, DELETE routes
```

### apiResource()

Create API resource routes:

```php
Route::apiResource('users', ApiUserController::class);
// Creates: GET, POST, PUT, PATCH, DELETE routes (no view routes)
```

### crud()

Create CRUD operations:

```php
Route::crud('products', ProductController::class);
// Creates: index, show, store, update, destroy
```

### auth()

Create authentication routes:

```php
Route::auth();
// Creates: login, register, logout, password reset routes
```

### adminPanel()

Create admin panel routes:

```php
Route::adminPanel();
// Creates: dashboard, users, settings routes
```

### apiVersion()

Create API versioning routes:

```php
Route::apiVersion('v1', function() {
    Route::get('/users', $action);
});
```

### webhooks()

Create webhook routes:

```php
Route::webhooks('stripe', StripeWebhookController::class);
```

---

## 4. Route Macros

### Custom Macros

```php
use CloudCastle\Http\Router\Macro\MacroManager;

MacroManager::macro('admin', function($prefix, $controller) {
    Route::group(['prefix' => $prefix, 'middleware' => 'admin'], function() use ($controller) {
        Route::get('/', [$controller, 'index']);
        Route::get('/create', [$controller, 'create']);
        Route::post('/', [$controller, 'store']);
        Route::get('/{id}', [$controller, 'show']);
        Route::get('/{id}/edit', [$controller, 'edit']);
        Route::put('/{id}', [$controller, 'update']);
        Route::delete('/{id}', [$controller, 'destroy']);
    });
});

// Usage
Route::admin('users', UserController::class);
```

---

## 5. Route Groups

### Basic Groups

```php
Route::group(['prefix' => 'api/v1'], function() {
    Route::get('/users', $action);
    Route::get('/posts', $action);
});
```

### Advanced Groups

```php
Route::group([
    'prefix' => 'admin',
    'middleware' => ['auth', 'admin'],
    'domain' => 'admin.example.com',
    'namespace' => 'Admin',
    'as' => 'admin.',
    'where' => ['id' => '[0-9]+']
], function() {
    Route::get('/dashboard', 'DashboardController@index');
    Route::resource('users', 'UserController');
});
```

### Nested Groups

```php
Route::group(['prefix' => 'api'], function() {
    Route::group(['prefix' => 'v1'], function() {
        Route::get('/users', $action);
    });
    
    Route::group(['prefix' => 'v2'], function() {
        Route::get('/users', $action);
    });
});
```

---

## 6. Middleware

### Global Middleware

```php
$router->middleware([
    CorsMiddleware::class,
    SecurityMiddleware::class
]);
```

### Route Middleware

```php
Route::get('/admin', $action)->middleware('auth');
Route::post('/api', $action)->middleware(['auth', 'throttle']);
```

### Group Middleware

```php
Route::group(['middleware' => 'auth'], function() {
    Route::get('/profile', $action);
    Route::get('/settings', $action);
});
```

### Built-in Middleware

```php
// Authentication
Route::get('/protected', $action)->middleware(AuthMiddleware::class);

// CORS
Route::get('/api', $action)->middleware(CorsMiddleware::class);

// HTTPS Enforcement
Route::get('/secure', $action)->middleware(HttpsEnforcement::class);

// Security Logging
Route::get('/sensitive', $action)->middleware(SecurityLogger::class);

// SSRF Protection
Route::get('/proxy', $action)->middleware(SsrfProtection::class);
```

---

## 7. Rate Limiting

### Basic Rate Limiting

```php
Route::get('/api', $action)->throttle(60, 1); // 60 requests per minute
Route::post('/login', $action)->throttle(5, 1); // 5 requests per minute
```

### Time Units

```php
use CloudCastle\Http\Router\RateLimiting\TimeUnit;

Route::get('/api', $action)->throttle(100, TimeUnit::HOUR);
Route::post('/upload', $action)->throttle(10, TimeUnit::DAY);
```

### Custom Keys

```php
Route::get('/api', $action)->throttle(60, 1, 'user:' . $userId);
Route::post('/api', $action)->throttle(100, 1, 'api_key:' . $apiKey);
```

### Rate Limiter Class

```php
use CloudCastle\Http\Router\RateLimiting\RateLimiter;

$limiter = new RateLimiter(60, TimeUnit::MINUTE);
$limiter->setKey('user:' . $userId);
$limiter->check();
```

### Predefined Limits

```php
Route::get('/api', $action)->throttleStandard(); // 60 req/min
Route::post('/api', $action)->throttleStrict();   // 10 req/min
Route::get('/api', $action)->throttleGenerous(); // 1000 req/min
```

---

## 8. IP Filtering

### Whitelist

```php
Route::get('/admin', $action)->whitelistIp(['192.168.1.0/24', '10.0.0.1']);
Route::group(['whitelistIp' => ['192.168.1.0/24']], function() {
    Route::get('/admin', $action);
});
```

### Blacklist

```php
Route::get('/api', $action)->blacklistIp(['192.168.1.100', '10.0.0.50']);
Route::group(['blacklistIp' => ['192.168.1.100']], function() {
    Route::get('/api', $action);
});
```

### CIDR Support

```php
Route::get('/admin', $action)->whitelistIp([
    '192.168.1.0/24',    // 192.168.1.1-254
    '10.0.0.0/8',        // 10.0.0.0-10.255.255.255
    '172.16.0.0/12'      // 172.16.0.0-172.31.255.255
]);
```

### IP Spoofing Protection

```php
Route::get('/api', $action)->enableIpSpoofingProtection();
```

---

## 9. Auto-Ban System

### Basic Auto-Ban

```php
use CloudCastle\Http\Router\RateLimiting\BanManager;

$banManager = new BanManager(5, 3600); // Ban after 5 violations for 1 hour

Route::post('/login', $action)
    ->throttle(3, 1)
    ->getRateLimiter()?->setBanManager($banManager);
```

### Ban Management

```php
$banManager = new BanManager();

// Ban IP manually
$banManager->ban('192.168.1.100', 3600);

// Unban IP
$banManager->unban('192.168.1.100');

// Check if IP is banned
if ($banManager->isBanned('192.168.1.100')) {
    throw new BannedException();
}

// Get all banned IPs
$bannedIps = $banManager->getBannedIps();

// Clear all bans
$banManager->clearAll();
```

### Auto-Ban Configuration

```php
$banManager = new BanManager(
    $violationThreshold = 5,    // Ban after 5 violations
    $banDuration = 3600,        // Ban for 1 hour
    $gracePeriod = 300          // Grace period of 5 minutes
);
```

---

## 10. Named Routes

### Basic Naming

```php
Route::get('/users/{id}', $action)->name('users.show');
Route::get('/users', $action)->name('users.index');
```

### Group Naming

```php
Route::group(['as' => 'admin.'], function() {
    Route::get('/dashboard', $action)->name('dashboard');
    // Creates route name: admin.dashboard
});
```

### Route Name Helpers

```php
// Get route by name
$route = Route::getRouteByName('users.show');

// Get current route name
$name = Route::currentRouteName();

// Check if current route matches pattern
if (Route::currentRouteNamed('users.*')) {
    // Current route starts with 'users.'
}

// Get all named routes
$namedRoutes = Route::getNamedRoutes();
```

### Auto-Naming

```php
Route::enableAutoNaming();

Route::get('/users', $action); // Auto-named: users.index
Route::get('/users/{id}', $action); // Auto-named: users.show
Route::post('/users', $action); // Auto-named: users.store
```

---

## 11. Tags

### Basic Tags

```php
Route::get('/api/users', $action)->tag('api');
Route::get('/api/posts', $action)->tag('api');
Route::get('/web/about', $action)->tag('web');
```

### Multiple Tags

```php
Route::get('/api/users', $action)->tag(['api', 'public']);
Route::get('/api/admin', $action)->tag(['api', 'admin']);
```

### Group Tags

```php
Route::group(['tag' => 'api'], function() {
    Route::get('/users', $action);
    Route::get('/posts', $action);
});
```

### Tag Operations

```php
// Get routes by tag
$apiRoutes = Route::getRoutesByTag('api');

// Check if route has tag
if ($route->hasTag('api')) {
    // Route has 'api' tag
}

// Get all tags
$allTags = Route::getAllTags();
```

---

## 12. Route Parameters

### Basic Parameters

```php
Route::get('/users/{id}', $action);
Route::get('/posts/{slug}', $action);
Route::get('/categories/{category}/posts/{post}', $action);
```

### Optional Parameters

```php
Route::get('/users/{id?}', $action);
Route::get('/posts/{slug?}', $action);
```

### Parameter Constraints

```php
Route::get('/users/{id}', $action)->where('id', '[0-9]+');
Route::get('/posts/{slug}', $action)->where('slug', '[a-z0-9-]+');
Route::get('/users/{id}/posts/{post}', $action)
    ->where(['id' => '[0-9]+', 'post' => '[0-9]+']);
```

### Inline Constraints

```php
Route::get('/users/{id:[0-9]+}', $action);
Route::get('/posts/{slug:[a-z0-9-]+}', $action);
```

### Default Values

```php
Route::get('/users/{id}', $action)->defaults(['id' => 1]);
Route::get('/posts/{page?}', $action)->defaults(['page' => 1]);
```

### Parameter Access

```php
$route = Route::get('/users/{id}', function($id) {
    return "User ID: $id";
});

// Get parameters
$params = $route->getParameters();
$id = $route->getParameter('id');
```

---

## 13. Expression Language

### Basic Expressions

```php
Route::get('/users/{id}', $action)
    ->where('id', 'expr: id > 0 and id < 1000');
```

### Complex Expressions

```php
Route::get('/posts/{year}/{month}', $action)
    ->where('year', 'expr: year >= 2020 and year <= 2030')
    ->where('month', 'expr: month >= 1 and month <= 12');
```

### Expression Functions

```php
Route::get('/files/{filename}', $action)
    ->where('filename', 'expr: strlen(filename) > 0 and strlen(filename) < 255');
```

---

## 14. URL Generation

### Basic URL Generation

```php
// Generate URL for named route
$url = route('users.show', ['id' => 1]);
// Result: /users/1

// Generate URL with query parameters
$url = route('users.index', [], ['page' => 2, 'sort' => 'name']);
// Result: /users?page=2&sort=name
```

### URL Helpers

```php
// Get current URL
$currentUrl = url()->current();

// Get full URL
$fullUrl = url()->full();

// Get previous URL
$previousUrl = url()->previous();

// Generate secure URL
$secureUrl = url()->secure('users/1');
```

### Route URL Generation

```php
$route = Route::get('/users/{id}', $action)->name('users.show');

// Generate URL
$url = $route->url(['id' => 1]);
$url = $route->url(['id' => 1], ['absolute' => true]);
```

---

## 15. Caching

### Route Caching

```php
$router->enableCache('cache/routes.php');

// Compile routes to cache
$router->compile();

// Load from cache
if (!$router->loadFromCache()) {
    require 'routes/web.php';
    $router->compile();
}
```

### Response Caching

```php
Route::get('/api/users', $action)->cache(3600); // Cache for 1 hour
Route::get('/api/posts', $action)->cache(7200, ['tag' => 'posts']); // Cache with tags
```

### Cache Tags

```php
Route::get('/api/users', $action)->cache(3600, ['tag' => 'users']);
Route::get('/api/posts', $action)->cache(3600, ['tag' => 'posts']);

// Clear cache by tag
Cache::clearByTag('users');
```

---

## 16. Plugins

### Built-in Plugins

```php
use CloudCastle\Http\Router\Plugin\LoggerPlugin;
use CloudCastle\Http\Router\Plugin\AnalyticsPlugin;
use CloudCastle\Http\Router\Plugin\ResponseCachePlugin;

$router->addPlugin(new LoggerPlugin());
$router->addPlugin(new AnalyticsPlugin());
$router->addPlugin(new ResponseCachePlugin());
```

### Custom Plugins

```php
use CloudCastle\Http\Router\Plugin\PluginInterface;

class CustomPlugin implements PluginInterface
{
    public function beforeDispatch($request, $response)
    {
        // Execute before route dispatch
    }
    
    public function afterDispatch($request, $response, $route)
    {
        // Execute after route dispatch
    }
}

$router->addPlugin(new CustomPlugin());
```

---

## 17. Loaders

### Route Loaders

```php
use CloudCastle\Http\Router\Loader\FileLoader;
use CloudCastle\Http\Router\Loader\DatabaseLoader;

// Load routes from file
$loader = new FileLoader('routes/web.php');
$loader->load($router);

// Load routes from database
$loader = new DatabaseLoader($connection);
$loader->load($router);
```

### Custom Loaders

```php
use CloudCastle\Http\Router\Loader\LoaderInterface;

class CustomLoader implements LoaderInterface
{
    public function load(Router $router)
    {
        // Load routes from custom source
    }
}
```

---

## 18. PSR Support

### PSR-7 Request/Response

```php
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

Route::get('/api/users', function(ServerRequestInterface $request): ResponseInterface {
    $response = new Response();
    $response->getBody()->write(json_encode(['users' => []]));
    return $response->withHeader('Content-Type', 'application/json');
});
```

### PSR-15 Middleware

```php
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class CustomMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // Process request
        return $handler->handle($request);
    }
}

Route::get('/api', $action)->middleware(CustomMiddleware::class);
```

### PSR-11 Container

```php
use Psr\Container\ContainerInterface;

Route::get('/api/users', function(ContainerInterface $container) {
    $userService = $container->get(UserService::class);
    return $userService->getAll();
});
```

---

## 19. Action Resolver

### Controller Actions

```php
Route::get('/users', 'UserController@index');
Route::get('/users/{id}', [UserController::class, 'show']);
Route::get('/users', UserController::class . '@index');
```

### Closure Actions

```php
Route::get('/users', function() {
    return 'Users list';
});

Route::get('/users/{id}', function($id) {
    return "User ID: $id";
});
```

### Class Actions

```php
class UserAction
{
    public function __invoke($id)
    {
        return "User ID: $id";
    }
}

Route::get('/users/{id}', UserAction::class);
```

### Dependency Injection

```php
Route::get('/users', function(UserService $userService) {
    return $userService->getAll();
});

Route::get('/users/{id}', [UserController::class, 'show']);
```

---

## 20. Statistics and Filtering

### Route Statistics

```php
$stats = $router->getRouteStats();

echo "Total routes: " . $stats->getTotalRoutes();
echo "Named routes: " . $stats->getNamedRoutes();
echo "Grouped routes: " . $stats->getGroupedRoutes();
echo "Middleware routes: " . $stats->getMiddlewareRoutes();
```

### Route Filtering

```php
// Filter by method
$getRoutes = $router->getRoutesByMethod('GET');

// Filter by pattern
$apiRoutes = $router->getRoutesByPattern('/api/*');

// Filter by middleware
$authRoutes = $router->getRoutesByMiddleware('auth');

// Filter by tag
$publicRoutes = $router->getRoutesByTag('public');
```

### Performance Statistics

```php
$perfStats = $router->getPerformanceStats();

echo "Average dispatch time: " . $perfStats->getAverageDispatchTime();
echo "Memory usage: " . $perfStats->getMemoryUsage();
echo "Cache hit rate: " . $perfStats->getCacheHitRate();
```

---

## Summary

CloudCastle HTTP Router provides **209+ features** across 20 major categories:

1. **Basic Routing** - All HTTP methods and custom methods
2. **Helper Functions** - Convenient route helpers
3. **Route Shortcuts** - Pre-built route collections
4. **Route Macros** - Custom route patterns
5. **Route Groups** - Organized route collections
6. **Middleware** - Request/response processing
7. **Rate Limiting** - DDoS and abuse protection
8. **IP Filtering** - Access control by IP
9. **Auto-Ban System** - Automatic IP banning
10. **Named Routes** - Route identification
11. **Tags** - Route categorization
12. **Route Parameters** - Dynamic URL segments
13. **Expression Language** - Advanced parameter validation
14. **URL Generation** - Dynamic URL creation
15. **Caching** - Performance optimization
16. **Plugins** - Extensible architecture
17. **Loaders** - Route loading strategies
18. **PSR Support** - Standards compliance
19. **Action Resolver** - Flexible action handling
20. **Statistics** - Route analysis and filtering

This comprehensive feature set makes CloudCastle HTTP Router the most complete routing solution for PHP applications.

---

## 📚 See Also
- [USER_GUIDE.md](USER_GUIDE.md) - Complete user guide
- [FEATURES_INDEX.md](FEATURES_INDEX.md) - Feature categories
- [API_REFERENCE.md](API_REFERENCE.md) - API reference
- [FAQ.md](FAQ.md) - Frequently asked questions

---

© 2024 CloudCastle HTTP Router  
[⬆ Back to top](#complete-list-of-cloudcastle-http-router-features)