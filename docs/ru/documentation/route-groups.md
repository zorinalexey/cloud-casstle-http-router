# Группы маршрутов

**CloudCastle HTTP Router v1.1.1**  
**Язык**: Русский

---

## 🌍 Переводы

- **[Русский](route-groups.md)** (текущий)
- [English](../../en/documentation/route-groups.md)
- [Deutsch](../../de/documentation/route-groups.md)
- [Français](../../fr/documentation/route-groups.md)

---

## 📋 Введение

Группы маршрутов позволяют применять общие атрибуты к нескольким маршрутам одновременно, упрощая организацию и управление маршрутизацией.

---

## 🔧 Базовое использование

### Простая группа

```php
use CloudCastle\Http\Router\Facade\Route;

Route::group([], function() {
    Route::get('/users', 'UserController@index');
    Route::get('/posts', 'PostController@index');
});
```

### С префиксом

```php
Route::group(['prefix' => 'admin'], function() {
    Route::get('/users', 'AdminController@users');     // /admin/users
    Route::get('/posts', 'AdminController@posts');     // /admin/posts
    Route::get('/settings', 'AdminController@settings'); // /admin/settings
});
```

---

## 🎨 Атрибуты групп

### Prefix (префикс)

```php
Route::group(['prefix' => 'api/v1'], function() {
    Route::get('/users', 'UserController@index');  // /api/v1/users
    Route::get('/posts', 'PostController@index');  // /api/v1/posts
});
```

### Middleware

```php
Route::group(['middleware' => 'auth'], function() {
    Route::get('/dashboard', 'DashboardController@index');
    Route::get('/profile', 'ProfileController@show');
});

// Несколько middleware
Route::group(['middleware' => ['auth', 'verified']], function() {
    Route::get('/premium', 'PremiumController@index');
});
```

### Domain (домен)

```php
Route::group(['domain' => 'admin.example.com'], function() {
    Route::get('/dashboard', 'AdminController@dashboard');
    Route::get('/users', 'AdminController@users');
});
```

### Port (порт)

```php
Route::group(['port' => 8080'], function() {
    Route::get('/metrics', 'MetricsController@index');
    Route::get('/health', 'HealthController@check');
});
```

### Namespace

```php
Route::group(['namespace' => 'App\\Controllers\\Api'], function() {
    Route::get('/users', 'UserController@index'); // App\Controllers\Api\UserController
    Route::get('/posts', 'PostController@index'); // App\Controllers\Api\PostController
});
```

### Tags (теги)

```php
Route::group(['tags' => 'api'], function() {
    Route::get('/users', 'UserController@index');
    Route::get('/posts', 'PostController@index');
});

// Получение всех маршрутов с тегом
$apiRoutes = Route::getRoutesByTag('api');
```

### IP Whitelist

```php
Route::group(['whitelistIp' => ['192.168.1.0/24', '10.0.0.1']], function() {
    Route::get('/admin', 'AdminController@index');
    Route::get('/sensitive', 'SensitiveController@data');
});
```

### IP Blacklist

```php
Route::group(['blacklistIp' => ['1.2.3.4', '5.6.7.8']], function() {
    Route::get('/public', 'PublicController@index');
});
```

### HTTPS Requirement

```php
Route::group(['https' => true], function() {
    Route::post('/login', 'AuthController@login');
    Route::post('/payment', 'PaymentController@process');
});
```

### Protocol

```php
Route::group(['protocol' => 'https'], function() {
    Route::post('/secure', 'SecureController@process');
});

Route::group(['protocol' => ['ws', 'wss']], function() {
    Route::get('/websocket', 'WebSocketController@connect');
});
```

### Throttle (Rate Limiting)

```php
Route::group(['throttle' => [60, 1]], function() {
    Route::get('/api/users', 'ApiController@users');
    Route::get('/api/posts', 'ApiController@posts');
});
```

---

## 🔄 Вложенные группы

### Простая вложенность

```php
Route::group(['prefix' => 'api'], function() {
    Route::group(['prefix' => 'v1'], function() {
        Route::get('/users', 'UserController@index'); // /api/v1/users
    });
    
    Route::group(['prefix' => 'v2'], function() {
        Route::get('/users', 'UserController@indexV2'); // /api/v2/users
    });
});
```

### Наследование атрибутов

```php
Route::group(['middleware' => 'auth'], function() {
    Route::group(['prefix' => 'admin'], function() {
        // Наследует middleware 'auth'
        Route::get('/dashboard', 'AdminController@dashboard');
        // URI: /admin/dashboard
        // Middleware: ['auth']
    });
});
```

### Объединение атрибутов

```php
Route::group([
    'prefix' => 'api',
    'middleware' => 'api'
], function() {
    Route::group([
        'prefix' => 'v1',
        'middleware' => 'auth'
    ], function() {
        Route::get('/users', 'UserController@index');
        // URI: /api/v1/users
        // Middleware: ['api', 'auth']
    });
});
```

---

## 💡 Практические примеры

### API версионирование

```php
Route::group(['prefix' => 'api'], function() {
    
    // API v1
    Route::group([
        'prefix' => 'v1',
        'namespace' => 'App\\Api\\V1'
    ], function() {
        Route::get('/users', 'UserController@index');
        Route::get('/posts', 'PostController@index');
    });
    
    // API v2
    Route::group([
        'prefix' => 'v2',
        'namespace' => 'App\\Api\\V2'
    ], function() {
        Route::get('/users', 'UserController@index');
        Route::get('/posts', 'PostController@index');
    });
});
```

### Админ панель

```php
Route::group([
    'prefix' => 'admin',
    'middleware' => ['auth', 'admin'],
    'whitelistIp' => ['192.168.1.0/24'],
    'https' => true,
    'tags' => 'admin'
], function() {
    Route::get('/dashboard', 'AdminController@dashboard');
    Route::get('/users', 'AdminController@users');
    Route::get('/settings', 'AdminController@settings');
    
    // Вложенная группа для критичных операций
    Route::group(['perHour' => 10], function() {
        Route::delete('/users/{id}', 'AdminController@deleteUser');
        Route::post('/clear-cache', 'AdminController@clearCache');
    });
});
```

### Multi-tenant приложение

```php
// Тенант 1
Route::group(['domain' => 'tenant1.app.com'], function() {
    Route::get('/', 'Tenant1Controller@home');
    Route::get('/dashboard', 'Tenant1Controller@dashboard');
});

// Тенант 2
Route::group(['domain' => 'tenant2.app.com'], function() {
    Route::get('/', 'Tenant2Controller@home');
    Route::get('/dashboard', 'Tenant2Controller@dashboard');
});
```

---

## 🎯 Комбинирование всех атрибутов

```php
Route::group([
    'prefix' => 'api/v1',
    'middleware' => ['auth', 'api'],
    'namespace' => 'App\\Api\\V1',
    'domain' => 'api.example.com',
    'port' => 443,
    'https' => true,
    'protocol' => 'https',
    'tags' => ['api', 'v1'],
    'whitelistIp' => ['10.0.0.0/8'],
    'throttle' => [100, 1]
], function() {
    Route::get('/users', 'UserController@index');
});
```

---

## ⚡ Производительность

Группы не влияют на производительность маршрутизации:

```
Без групп:        60,095 req/s
С группами:       60,090 req/s  
Разница:          -0.008% (незначительно)
```

---

## 🔗 См. также

- [Маршруты](routes.md)
- [Middleware](middleware.md)
- [Rate Limiting](rate-limiting.md)

---

**[← Назад к оглавлению](README.md)**

