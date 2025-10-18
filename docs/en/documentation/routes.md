# Routes

**CloudCastle HTTP Router v1.1.1**  
**Language**: English

---

## 🌍 Translations

- [Русский](../../ru/documentation/routes.md)
- **[English](routes.md)** (current)
- [Deutsch](../../de/documentation/routes.md)
- [Français](../../fr/documentation/routes.md)

---

## 📋 Basics

### Simple Route

```php
use CloudCastle\Http\Router\Facade\Route;

Route::get('/users', function() {
    return 'User list';
});
```

### HTTP Methods

```php
Route::get('/users', 'UserController@index');
Route::post('/users', 'UserController@store');
Route::put('/users/{id}', 'UserController@update');
Route::patch('/users/{id}', 'UserController@patch');
Route::delete('/users/{id}', 'UserController@destroy');
Route::options('/users', 'UserController@options');
Route::head('/users', 'UserController@head');
```

### Multiple Methods

```php
Route::match(['GET', 'POST'], '/form', 'FormController@handle');
Route::any('/webhook', 'WebhookController@handle');
```

---

## 🔗 Route Parameters

### Required Parameters

```php
Route::get('/user/{id}', function($id) {
    return "User: $id";
});

// Multiple parameters
Route::get('/post/{year}/{month}/{slug}', function($year, $month, $slug) {
    return "$year/$month/$slug";
});
```

### Optional Parameters

```php
Route::get('/user/{id?}', function($id = null) {
    return $id ? "User: $id" : "All users";
});
```

### Parameter Constraints

```php
// Digits only
Route::get('/user/{id}', 'UserController@show')
    ->where('id', '\d+');

// Letters only
Route::get('/category/{name}', 'CategoryController@show')
    ->where('name', '[a-z]+');

// Multiple constraints
Route::get('/post/{year}/{month}', 'PostController@show')
    ->where([
        'year' => '\d{4}',
        'month' => '\d{2}'
    ]);

// Complex patterns
Route::get('/product/{sku}', 'ProductController@show')
    ->where('sku', '[A-Z]{3}-\d{4}');
```

### Inline Constraints

```php
// Directly in URI
Route::get('/user/{id:\d+}', 'UserController@show');
Route::get('/slug/{slug:[a-z-]+}', 'PageController@show');
Route::get('/post/{year:\d{4}}/{month:\d{2}}', 'PostController@archive');
```

---

## 🏷️ Named Routes

```php
Route::get('/profile', 'ProfileController@show')
    ->name('profile');

// Usage
$url = route('profile');  // /profile

// With parameters
Route::get('/user/{id}', 'UserController@show')
    ->name('user.show');
    
$url = route_url('user.show', ['id' => 123]); // /user/123
```

---

## 🏷️ Tagged Routes

```php
Route::get('/admin/users', 'AdminController@users')
    ->tag('admin');

Route::get('/admin/settings', 'AdminController@settings')
    ->tag(['admin', 'settings']);

// Get by tag
$routes = Route::getRoutesByTag('admin');
```

---

## 🌐 Domain Routing

```php
Route::get('/dashboard', 'DashboardController@index')
    ->domain('admin.example.com');
```

---

## 🔌 Port Routing

```php
Route::get('/metrics', 'MetricsController@index')
    ->port(9090);
```

---

## 🔐 Protocol Restrictions

```php
// HTTPS only
Route::post('/login', 'AuthController@login')
    ->https();

// HTTP only
Route::get('/public', 'PublicController@index')
    ->protocol('http');

// WebSocket
Route::get('/ws', 'WebSocketController@connect')
    ->protocol('ws');
```

---

## 🎨 Fluent Interface

```php
Route::get('/secure-api', 'ApiController@data')
    ->name('api.secure.data')
    ->tag(['api', 'secure'])
    ->https()
    ->whitelistIp(['10.0.0.0/8'])
    ->middleware('auth')
    ->perMinute(100);
```

---

## 📖 Practical Examples

### RESTful API

```php
use CloudCastle\Http\Router\Router;

$router = Router::getInstance();
$router->enableAutoNaming();

$router->group(['prefix' => 'api/v1'], function(Router $r) {
    // Users
    $r->get('/users', 'UserController@index');           // api.v1.users.get
    $r->post('/users', 'UserController@store');          // api.v1.users.post
    $r->get('/users/{id}', 'UserController@show');       // api.v1.users.id.get
    $r->put('/users/{id}', 'UserController@update');     // api.v1.users.id.put
    $r->delete('/users/{id}', 'UserController@destroy'); // api.v1.users.id.delete
});
```

---

## 🔗 See Also

- [Automatic Naming](auto-naming.md)
- [Route Groups](route-groups.md)
- [Middleware](middleware.md)

---

**[← Back to contents](README.md)**

