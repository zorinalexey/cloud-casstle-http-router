# Routing Features - Detailed Description

[**English**](../../en/features/ROUTING_FEATURES.md) | [Русский](../../ru/features/ROUTING_FEATURES.md)

---

## Table of Contents

- [HTTP Methods](#http-methods)
- [Route Parameters](#route-parameters)
- [Named Routes](#named-routes)
- [Tags](#tags)
- [Route Groups](#route-groups)
- [Comparison with Alternatives](#comparison-with-alternatives)

---

## HTTP Methods

### GET Method

**Description:** Register route for GET requests.

**Usage:**
```php
use CloudCastle\Http\Router\Router;

$router = new Router();

$router->get('/users', function() {
    return json_encode(['users' => [...] ]);
});

// With controller
$router->get('/users', [UserController::class, 'index']);

// With string
$router->get('/users', 'UserController@index');
```

**Comparison:**

| Router | Syntax | Flexibility | Rating |
|--------|--------|-------------|--------|
| **CloudCastle** | `$router->get($uri, $action)` | ⭐⭐⭐⭐⭐ | ⭐⭐⭐⭐⭐ |
| Laravel | `Route::get($uri, $action)` | ⭐⭐⭐⭐⭐ | ⭐⭐⭐⭐⭐ |
| Symfony | `$routes->add('name', new Route($uri))` | ⭐⭐⭐ | ⭐⭐⭐⭐ |
| FastRoute | `$r->addRoute('GET', $uri, $handler)` | ⭐⭐⭐ | ⭐⭐⭐⭐ |

---

### POST Method

**Description:** Register route for POST requests (resource creation).

**Usage:**
```php
$router->post('/users', function() {
    // Create user
    $data = json_decode(file_get_contents('php://input'), true);
    return json_encode(['id' => 123, 'status' => 'created']);
});

// With rate limiting
$router->post('/users', $action)->throttle(10, 1);
```

---

### PUT/PATCH Methods

**Description:** Update resources.

```php
// PUT - full update
$router->put('/users/{id}', function($id) {
    // Update ALL fields
});

// PATCH - partial update
$router->patch('/users/{id}', function($id) {
    // Update ONLY provided fields
});
```

---

### DELETE Method

**Description:** Delete resources.

```php
$router->delete('/users/{id}', function($id) {
    // Delete user
    return json_encode(['status' => 'deleted']);
});
```

---

### VIEW Method (Unique!)

CloudCastle-specific method for view-only operations:

```php
$router->view('/dashboard', 'dashboard.view');
$router->view('/profile', 'profile.view');
```

---

### MATCH Method

Respond to multiple HTTP methods:

```php
$router->match(['GET', 'POST'], '/form', function() {
    // Handle both GET and POST
});
```

---

### ANY Method

Respond to ALL HTTP methods:

```php
$router->any('/webhook', function() {
    // Handle any method
});
```

---

### CUSTOM Method

Register custom HTTP methods:

```php
$router->custom('PURGE', '/cache', function() {
    // Handle PURGE method
});
```

---

## Route Parameters

### Basic Parameters

```php
$router->get('/users/{id}', function($id) {
    return "User ID: $id";
});
```

### Optional Parameters

```php
$router->get('/posts/{id?}', function($id = null) {
    return $id ? "Post $id" : "All posts";
});
```

### Parameter Constraints

```php
$router->get('/users/{id}', $action)
    ->where('id', '[0-9]+');

$router->get('/posts/{slug}', $action)
    ->where('slug', '[a-z0-9-]+');
```

---

## Named Routes

### Basic Naming

```php
$router->get('/users', $action)->name('users.index');
$router->get('/users/{id}', $action)->name('users.show');
```

### Auto-naming

```php
$router->enableAutoNaming();

$router->get('/users', $action);  // Auto-named: users.get
$router->post('/posts', $action); // Auto-named: posts.post
```

---

## Tags

Group routes with tags:

```php
$router->get('/api/users', $action)->tags(['api', 'v1']);

// Find by tag
$apiRoutes = $router->getRoutesByTag('api');
```

---

## Route Groups

```php
Route::group(['prefix' => '/api', 'middleware' => ['auth']], function() {
    Route::get('/users', $action);
    Route::get('/posts', $action);
});
```

---

## Comparison Summary

| Feature | CloudCastle | Laravel | Symfony | FastRoute | Slim |
|---------|-------------|---------|---------|-----------|------|
| **HTTP Methods** | 9 | 7 | 7 | Any | Any |
| **VIEW method** | ✅ | ❌ | ❌ | ❌ | ❌ |
| **CUSTOM method** | ✅ | ⚠️ | ⚠️ | ✅ | ⚠️ |
| **Parameters** | ✅ | ✅ | ✅ | ✅ | ✅ |
| **Optional params** | ✅ | ✅ | ⚠️ | ⚠️ | ✅ |
| **Constraints** | ✅ | ✅ | ✅ | ✅ | ✅ |
| **Named routes** | ✅ | ✅ | ✅ | ⚠️ | ✅ |
| **Auto-naming** | ✅ | ❌ | ❌ | ❌ | ❌ |
| **Tags** | ✅ | ❌ | ❌ | ❌ | ❌ |

**CloudCastle = Most feature-rich routing!**

---

[⬆ Back](../FEATURES_INDEX.md) | [🏠 Home](../../../README.md)

---

© 2024 CloudCastle HTTP Router. All Rights Reserved.


