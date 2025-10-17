# Группы маршрутов

**CloudCastle HTTP Router v1.1.0**  
**Язык**: Русский

---

**Переводы**: [English](../../en/documentation/route-groups.md) | [Deutsch](../../de/documentation/route-groups.md) | [Français](../../fr/documentation/route-groups.md)

---

## Что такое группы маршрутов?

Группы маршрутов позволяют применять общие атрибуты к нескольким маршрутам одновременно, такие как префикс, middleware, домен, ограничения и др.

## Базовое использование

```php
use CloudCastle\Http\Router\Facade\Route;

Route::group(['prefix' => '/api/v1'], function() {
    Route::get('/users', 'UserController@index');     // /api/v1/users
    Route::post('/users', 'UserController@store');    // /api/v1/users
    Route::get('/posts', 'PostController@index');     // /api/v1/posts
});
```

## Атрибуты группы

### Prefix (Префикс)
```php
Route::group(['prefix' => '/admin'], function() {
    Route::get('/dashboard', fn() => 'dashboard'); // /admin/dashboard
});
```

### Middleware
```php
Route::group(['middleware' => ['auth', 'admin']], function() {
    Route::get('/users', 'AdminController@users');
});
```

### Domain (Домен)
```php
Route::group(['domain' => 'api.example.com'], function() {
    Route::get('/v1/users', 'ApiController@users');
});
```

### Port (Порт)
```php
Route::group(['port' => 8080], function() {
    Route::get('/api/data', 'ApiController@data');
});
```

### Protocol (Протокол)
```php
Route::group(['protocol' => ['https']], function() {
    Route::post('/payment', 'PaymentController@process');
});
```

### Rate Limiting
```php
Route::group(['throttle' => 100], function() {
    Route::get('/api/data', fn() => 'data');
});
```

### IP ограничения
```php
Route::group(['whitelistIp' => ['192.168.1.1']], function() {
    Route::get('/admin', fn() => 'admin panel');
});
```

### Tags (Теги)
```php
Route::group(['tags' => ['api', 'v1']], function() {
    Route::get('/users', fn() => 'users');
});
```

## Вложенные группы

```php
Route::group(['prefix' => '/api'], function() {
    Route::group(['prefix' => '/v1'], function() {
        Route::group(['middleware' => 'auth'], function() {
            Route::get('/users', fn() => 'users'); // /api/v1/users + auth
        });
    });
});
```

## Полный пример

```php
Route::group([
    'prefix' => '/api/v1',
    'middleware' => ['api', 'throttle'],
    'domain' => 'api.example.com',
    'throttle' => 1000,
    'tags' => ['api', 'v1'],
    'https' => true,
], function() {
    Route::get('/users', 'UserController@index');
    Route::post('/users', 'UserController@store');
    
    Route::group(['prefix' => '/admin', 'middleware' => 'admin'], function() {
        Route::get('/stats', 'AdminController@stats');
    });
});
```

---

**[◀ Маршруты](routes.md)** | **[API Reference](api-reference.md)** | **[Middleware ▶](middleware.md)**

