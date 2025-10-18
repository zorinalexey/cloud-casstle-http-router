# Quick Start

**CloudCastle HTTP Router v1.1.1**  
**Language**: English

---

## 🌍 Translations

- [Русский](../../ru/documentation/quickstart.md)
- **[English](quickstart.md)** (current)
- [Deutsch](../../de/documentation/quickstart.md)
- [Français](../../fr/documentation/quickstart.md)

---

## 📦 Installation

```bash
composer require cloudcastle/http-router
```

---

## 🚀 Your First Route

### Step 1: Create `index.php`

```php
<?php

require 'vendor/autoload.php';

use CloudCastle\Http\Router\Facade\Route;

// Simple GET route
Route::get('/', function() {
    return 'Hello, CloudCastle Router!';
});

// Dispatch
$result = Route::dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
echo $result;
```

### Step 2: Start PHP Built-in Server

```bash
php -S localhost:8000
```

### Step 3: Open Browser

Navigate to: http://localhost:8000

You will see: `Hello, CloudCastle Router!`

---

## 📝 Basic Examples

### GET Request

```php
Route::get('/users', function() {
    return json_encode(['user1', 'user2', 'user3']);
});
```

### POST Request

```php
Route::post('/users', function() {
    // Get data from $_POST
    return 'User created';
});
```

### With Parameters

```php
Route::get('/user/{id}', function($id) {
    return "User profile #$id";
});
```

### Multiple Parameters

```php
Route::get('/posts/{year}/{month}/{slug}', function($year, $month, $slug) {
    return "Article: $year/$month/$slug";
});
```

---

## 🎯 HTTP Methods

```php
// GET
Route::get('/resource', 'Controller@index');

// POST  
Route::post('/resource', 'Controller@store');

// PUT
Route::put('/resource/{id}', 'Controller@update');

// PATCH
Route::patch('/resource/{id}', 'Controller@patch');

// DELETE
Route::delete('/resource/{id}', 'Controller@destroy');

// Multiple methods
Route::match(['GET', 'POST'], '/form', 'Controller@handle');

// Any method
Route::any('/webhook', 'Controller@webhook');
```

---

## 🔧 Using Controllers

### Create Controller

```php
// app/Controllers/UserController.php
namespace App\Controllers;

class UserController
{
    public function index()
    {
        return 'User list';
    }
    
    public function show($id)
    {
        return "User #$id";
    }
}
```

### Use in Routes

```php
Route::get('/users', 'App\\Controllers\\UserController@index');
Route::get('/users/{id}', 'App\\Controllers\\UserController@show');
```

---

## 🏷️ Named Routes

```php
// Create named route
Route::get('/profile', 'ProfileController@show')
    ->name('profile');

// Get URL by name
$url = route('profile'); // /profile

// With parameters
Route::get('/user/{id}', 'UserController@show')
    ->name('user.show');
    
$url = route_url('user.show', ['id' => 123]); // /user/123
```

---

## 🤖 Automatic Naming

```php
use CloudCastle\Http\Router\Router;

$router = Router::getInstance();
$router->enableAutoNaming();

// GET /users -> automatic name: users.get
$router->get('/users', 'UserController@index');

// GET /api/v1/posts/{id} -> automatic name: api.v1.posts.id.get
$router->get('/api/v1/posts/{id}', 'PostController@show');

// Usage
$route = $router->getRouteByName('api.v1.posts.id.get');
```

[More about auto-naming →](auto-naming.md)

---

## 📂 Route Groups

### With Prefix

```php
Route::group(['prefix' => 'admin'], function() {
    Route::get('/users', 'AdminController@users');     // /admin/users
    Route::get('/posts', 'AdminController@posts');     // /admin/posts
});
```

### With Middleware

```php
Route::group(['middleware' => 'auth'], function() {
    Route::get('/dashboard', 'DashboardController@index');
    Route::get('/profile', 'ProfileController@show');
});
```

[More about groups →](route-groups.md)

---

## ⏱️ Rate Limiting

### Basic Limiting

```php
// 60 requests per minute
Route::post('/api/data', 'ApiController@store')
    ->perMinute(60);

// 10 requests per second
Route::get('/api/fast', 'ApiController@fast')
    ->perSecond(10);
```

### With Auto-ban

```php
Route::post('/login', 'AuthController@login')
    ->throttleWithBan(
        maxAttempts: 5,
        decaySeconds: 60,
        maxViolations: 3,
        banDurationSeconds: 7200
    );
```

[More about Rate Limiting →](rate-limiting.md)

---

## 🔒 Security

### IP Filtering

```php
// Whitelist
Route::get('/admin', 'AdminController@index')
    ->whitelistIp(['192.168.1.0/24', '10.0.0.1']);

// Blacklist
Route::get('/api', 'ApiController@index')
    ->blacklistIp(['1.2.3.4']);
```

### HTTPS

```php
Route::post('/login', 'AuthController@login')
    ->https();
```

[More about security →](security.md)

---

## 📚 Next Steps

1. [Routes](routes.md) - Detailed routing study
2. [Middleware](middleware.md) - Creating handlers
3. [Performance](performance.md) - Optimization

---

**CloudCastle HTTP Router** - Production Ready! 🚀

**[← Back to contents](README.md)**

