# Route Groups

**CloudCastle HTTP Router v1.1.1**  
**Language**: English

---

## 🌍 Translations

- [Русский](../../ru/documentation/route-groups.md)
- **[English](route-groups.md)** (current)
- [Deutsch](../../de/documentation/route-groups.md)
- [Français](../../fr/documentation/route-groups.md)

---

## 📋 Introduction

Route groups allow you to apply shared attributes to multiple routes at once, simplifying organization and route management.

---

## 🔧 Basic Usage

### Simple Group

```php
use CloudCastle\Http\Router\Facade\Route;

Route::group([], function() {
    Route::get('/users', 'UserController@index');
    Route::get('/posts', 'PostController@index');
});
```

### With Prefix

```php
Route::group(['prefix' => 'admin'], function() {
    Route::get('/users', 'AdminController@users');     // /admin/users
    Route::get('/posts', 'AdminController@posts');     // /admin/posts
});
```

---

## 🎨 Group Attributes

### Prefix

```php
Route::group(['prefix' => 'api/v1'], function() {
    Route::get('/users', 'UserController@index');  // /api/v1/users
});
```

### Middleware

```php
Route::group(['middleware' => 'auth'], function() {
    Route::get('/dashboard', 'DashboardController@index');
});

// Multiple middleware
Route::group(['middleware' => ['auth', 'verified']], function() {
    Route::get('/premium', 'PremiumController@index');
});
```

### Domain

```php
Route::group(['domain' => 'admin.example.com'], function() {
    Route::get('/dashboard', 'AdminController@dashboard');
});
```

### Port

```php
Route::group(['port' => 8080], function() {
    Route::get('/metrics', 'MetricsController@index');
});
```

### Tags

```php
Route::group(['tags' => 'api'], function() {
    Route::get('/users', 'UserController@index');
});
```

### IP Whitelist

```php
Route::group(['whitelistIp' => ['192.168.1.0/24']], function() {
    Route::get('/admin', 'AdminController@index');
});
```

### HTTPS Requirement

```php
Route::group(['https' => true], function() {
    Route::post('/login', 'AuthController@login');
});
```

---

## 🔄 Nested Groups

```php
Route::group(['prefix' => 'api'], function() {
    Route::group(['prefix' => 'v1'], function() {
        Route::get('/users', 'UserController@index'); // /api/v1/users
    });
});
```

---

## 💡 Practical Examples

### API Versioning

```php
Route::group(['prefix' => 'api'], function() {
    
    // API v1
    Route::group([
        'prefix' => 'v1',
        'namespace' => 'App\\Api\\V1'
    ], function() {
        Route::get('/users', 'UserController@index');
    });
    
    // API v2
    Route::group([
        'prefix' => 'v2',
        'namespace' => 'App\\Api\\V2'
    ], function() {
        Route::get('/users', 'UserController@index');
    });
});
```

### Admin Panel

```php
Route::group([
    'prefix' => 'admin',
    'middleware' => ['auth', 'admin'],
    'whitelistIp' => ['192.168.1.0/24'],
    'https' => true
], function() {
    Route::get('/dashboard', 'AdminController@dashboard');
    Route::get('/users', 'AdminController@users');
});
```

---

## 🔗 See Also

- [Routes](routes.md)
- [Middleware](middleware.md)

---

**[← Back to contents](README.md)**

