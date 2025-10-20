# User Guide - CloudCastle HTTP Router

**English** | [Русский](../ru/USER_GUIDE.md)

---

## Table of Contents

- [Installation](#installation)
- [Quick Start](#quick-start)
- [Basic Routing](#basic-routing)
- [Route Parameters](#route-parameters)
- [Named Routes](#named-routes)
- [Route Groups](#route-groups)
- [Middleware](#middleware)
- [Rate Limiting](#rate-limiting)
- [IP Filtering](#ip-filtering)
- [Caching](#caching)
- [Advanced Features](#advanced-features)

---

## Installation

```bash
composer require cloud-castle/http-router
```

### Requirements

- PHP 8.2 or higher
- Composer

---

## Quick Start

```php
<?php

require 'vendor/autoload.php';

use CloudCastle\Http\Router\Router;

$router = new Router();

// Define routes
$router->get('/', function() {
    return 'Hello World!';
});

$router->get('/users', function() {
    return json_encode(['users' => []]);
});

// Dispatch request
$uri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

try {
    $route = $router->dispatch($uri, $method);
    $response = $route->run();
    echo $response;
} catch (\Exception $e) {
    http_response_code(404);
    echo 'Not Found';
}
```

---

## Basic Routing

### HTTP Methods

```php
// GET request
$router->get('/users', function() {
    return 'List users';
});

// POST request
$router->post('/users', function() {
    return 'Create user';
});

// PUT request
$router->put('/users/{id}', function($id) {
    return "Update user $id";
});

// DELETE request
$router->delete('/users/{id}', function($id) {
    return "Delete user $id";
});

// Multiple methods
$router->match(['GET', 'POST'], '/form', function() {
    return 'Form handler';
});

// Any method
$router->any('/api/webhook', function() {
    return 'Webhook handler';
});
```

---

## Route Parameters

### Basic Parameters

```php
$router->get('/users/{id}', function($id) {
    return "User ID: $id";
});

$router->get('/posts/{year}/{month}', function($year, $month) {
    return "Posts from $year-$month";
});
```

### Optional Parameters

```php
$router->get('/users/{id?}', function($id = null) {
    return $id ? "User $id" : "All users";
});
```

### Parameter Constraints

```php
$router->get('/users/{id}', function($id) {
    return "User $id";
})->where('id', '[0-9]+');

$router->get('/posts/{slug}', function($slug) {
    return "Post: $slug";
})->where('slug', '[a-z0-9-]+');
```

---

## Named Routes

```php
// Define named route
$router->get('/users', $action)->name('users.index');
$router->get('/users/{id}', $action)->name('users.show');

// Generate URL
$url = route_url('users.index'); // /users
$url = route_url('users.show', ['id' => 123]); // /users/123

// Check current route
if (route_is('users.show')) {
    echo 'Viewing user';
}
```

---

## Route Groups

```php
// With prefix
$router->group(['prefix' => '/api'], function() use ($router) {
    $router->get('/users', $action); // /api/users
    $router->get('/posts', $action); // /api/posts
});

// With middleware
$router->group(['middleware' => ['auth']], function() use ($router) {
    $router->get('/dashboard', $action);
    $router->get('/profile', $action);
});

// Combined attributes
$router->group([
    'prefix' => '/admin',
    'middleware' => ['auth', 'admin'],
    'https' => true,
], function() use ($router) {
    $router->get('/dashboard', $action);
});
```

---

## Middleware

```php
// Global middleware
$router->middleware([
    'cors',
    'logging',
]);

// Route middleware
$router->get('/admin', $action)
    ->middleware(['auth', 'admin']);

// Group middleware
$router->group(['middleware' => ['auth']], function() use ($router) {
    // All routes have 'auth' middleware
});
```

---

## Rate Limiting

```php
// Basic throttling
$router->post('/api/users', $action)
    ->throttle(60, 1); // 60 requests per minute

// With custom key
$router->post('/login', $action)
    ->throttle(5, 1, function($request) {
        return $request->input('email');
    });

// Per day/week/month
$router->post('/api/upload', $action)
    ->perDay(100); // 100 requests per day

// With auto-ban
$router->post('/login', $action)
    ->throttleWithBan(5, 1, 3, 3600);
// Ban after 3 violations for 1 hour
```

---

## IP Filtering

```php
// Whitelist
$router->get('/admin', $action)
    ->whitelistIp(['192.168.1.0/24']);

// Blacklist
$router->get('/api', $action)
    ->blacklistIp(['1.2.3.4']);

// Localhost only
$router->get('/debug', $action)->localhost();
```

---

## Caching

```php
// Enable cache
$router->enableCache('/var/cache/routes');

// Production setup
if (!$router->loadFromCache()) {
    require 'routes.php';
    $router->compile();
}

// Clear cache
$router->clearCache();
```

---

## Advanced Features

### Tags

```php
$router->get('/api/users', $action)->tags(['api', 'v1']);

// Get routes by tag
$apiRoutes = routes_by_tag('api');
```

### Plugins

```php
$router->registerPlugin(new LoggerPlugin());
$router->registerPlugin(new AnalyticsPlugin());
```

### URL Generation

```php
use CloudCastle\Http\Router\UrlGenerator;

$generator = new UrlGenerator($router);
$url = $generator->generate('users.show', ['id' => 123]);
$absoluteUrl = $generator->absolute('users.show', ['id' => 123]);
```

### Auto-Naming

```php
$router->enableAutoNaming();

$router->get('/users', $action); // Auto-named: users.get
$router->post('/posts', $action); // Auto-named: posts.post
```

---

## Best Practices

1. **Use route caching in production** - 10x performance boost
2. **Apply rate limiting to public endpoints** - prevent abuse
3. **Use HTTPS for sensitive routes** - security first
4. **Group related routes** - better organization
5. **Use named routes** - easier refactoring
6. **Apply IP filtering to admin routes** - additional security

---

## See Also

- [All Features](ALL_FEATURES.md) - Complete feature list
- [API Reference](API_REFERENCE.md) - Full API documentation
- [FAQ](FAQ.md) - Frequently asked questions

---

[⬆ Back to top](#user-guide---cloudcastle-http-router) | [🏠 Home](../../README.md)

---

© 2024 CloudCastle HTTP Router. All Rights Reserved.


