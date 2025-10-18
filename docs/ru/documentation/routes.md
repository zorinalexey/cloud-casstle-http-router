# Маршруты

**CloudCastle HTTP Router v1.1.1**  
**Язык**: Русский

---

## 🌍 Переводы

- **[Русский](routes.md)** (текущий)
- [English](../../en/documentation/routes.md)
- [Deutsch](../../de/documentation/routes.md)
- [Français](../../fr/documentation/routes.md)

---

## 📋 Основы

### Простой маршрут

```php
use CloudCastle\Http\Router\Facade\Route;

Route::get('/users', function() {
    return 'User list';
});
```

### HTTP методы

```php
Route::get('/users', 'UserController@index');
Route::post('/users', 'UserController@store');
Route::put('/users/{id}', 'UserController@update');
Route::patch('/users/{id}', 'UserController@patch');
Route::delete('/users/{id}', 'UserController@destroy');
Route::options('/users', 'UserController@options');
Route::head('/users', 'UserController@head');
```

### Несколько методов

```php
Route::match(['GET', 'POST'], '/form', 'FormController@handle');
Route::any('/webhook', 'WebhookController@handle');
```

---

## 🔗 Параметры маршрута

### Обязательные параметры

```php
Route::get('/user/{id}', function($id) {
    return "User: $id";
});

// Несколько параметров
Route::get('/post/{year}/{month}/{slug}', function($year, $month, $slug) {
    return "$year/$month/$slug";
});
```

### Необязательные параметры

```php
Route::get('/user/{id?}', function($id = null) {
    return $id ? "User: $id" : "All users";
});
```

### Ограничения параметров

```php
// Только цифры
Route::get('/user/{id}', 'UserController@show')
    ->where('id', '\d+');

// Только буквы
Route::get('/category/{name}', 'CategoryController@show')
    ->where('name', '[a-z]+');

// Несколько ограничений
Route::get('/post/{year}/{month}', 'PostController@show')
    ->where([
        'year' => '\d{4}',
        'month' => '\d{2}'
    ]);

// Сложные паттерны
Route::get('/product/{sku}', 'ProductController@show')
    ->where('sku', '[A-Z]{3}-\d{4}');
```

### Инлайн ограничения

```php
// Прямо в URI
Route::get('/user/{id:\d+}', 'UserController@show');
Route::get('/slug/{slug:[a-z-]+}', 'PageController@show');
Route::get('/post/{year:\d{4}}/{month:\d{2}}', 'PostController@archive');
```

---

## 🏷️ Именованные маршруты

```php
Route::get('/profile', 'ProfileController@show')
    ->name('profile');

// Использование
$url = route('profile');  // /profile

// С параметрами
Route::get('/user/{id}', 'UserController@show')
    ->name('user.show');
    
$url = route_url('user.show', ['id' => 123]); // /user/123
```

---

## 🏷️ Тегированные маршруты

```php
Route::get('/admin/users', 'AdminController@users')
    ->tag('admin');

Route::get('/admin/settings', 'AdminController@settings')
    ->tag(['admin', 'settings']);

// Получение по тегу
$routes = Route::getRoutesByTag('admin');
```

---

## 🌐 Доменная маршрутизация

```php
Route::get('/dashboard', 'DashboardController@index')
    ->domain('admin.example.com');

Route::get('/api', 'ApiController@index')
    ->domain('api.example.com');
```

---

## 🔌 Портовая маршрутизация

```php
Route::get('/metrics', 'MetricsController@index')
    ->port(9090);

Route::get('/admin', 'AdminController@index')
    ->port(8080);
```

---

## 🔐 Протокольные ограничения

```php
// Только HTTPS
Route::post('/login', 'AuthController@login')
    ->https();

// Только HTTP
Route::get('/public', 'PublicController@index')
    ->protocol('http');

// HTTP или HTTPS
Route::get('/flexible', 'Controller@index')
    ->protocol(['http', 'https']);

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

## 🎯 Типы действий (Actions)

### Closure (анонимная функция)

```php
Route::get('/hello', function() {
    return 'Hello World!';
});

Route::get('/user/{id}', function($id) {
    return "User: $id";
});
```

### Строка контроллера

```php
// Controller@method
Route::get('/users', 'UserController@index');

// С namespace
Route::get('/users', 'App\\Controllers\\UserController@index');
```

### Массив с классом

```php
Route::get('/users', [UserController::class, 'index']);
```

### Массив с экземпляром

```php
$controller = new UserController();
Route::get('/users', [$controller, 'index']);
```

---

## 📖 Примеры из практики

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

### С безопасностью и ограничениями

```php
Route::group([
    'prefix' => 'api/v1',
    'middleware' => 'auth'
], function() {
    
    Route::get('/users', 'UserController@index')
        ->perMinute(100);
    
    Route::post('/users', 'UserController@store')
        ->perMinute(30)
        ->whitelistIp(['10.0.0.0/8']);
    
    Route::delete('/users/{id:\d+}', 'UserController@destroy')
        ->perHour(10)
        ->tag(['admin', 'destructive']);
});
```

---

## 🔗 См. также

- [Автоматическое именование](auto-naming.md)
- [Группы маршрутов](route-groups.md)
- [Middleware](middleware.md)
- [Rate Limiting](rate-limiting.md)

---

**[← Назад к оглавлению](README.md)**

