# Маршруты

**CloudCastle HTTP Router v1.1.0**  
**Язык**: Русский

**Переводы**: [English](../../en/documentation/routes.md) | [Deutsch](../../de/documentation/routes.md) | [Français](../../fr/documentation/routes.md)

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

## 🏷️ Именованные маршруты

```php
Route::get('/profile', 'ProfileController@show')
    ->name('profile');

// Использование
$url = route('profile');  // /profile
```

## 🏷️ Тегированные маршруты

```php
Route::get('/admin/users', 'AdminController@users')
    ->tag('admin');

Route::get('/admin/settings', 'AdminController@settings')
    ->tag(['admin', 'settings']);

// Получение по тегу
$routes = Route::getRoutesByTag('admin');
```

## 🔒 Безопасность

### HTTPS

```php
Route::post('/login', 'Auth@login')
    ->https();
```

### IP фильтрация

```php
// Whitelist
Route::get('/admin', 'AdminController@index')
    ->whitelistIp(['192.168.1.0/24']);

// Blacklist
Route::get('/api', 'ApiController@index')
    ->blacklistIp(['10.0.0.1', '10.0.0.2']);
```

### Домены

```php
Route::get('/dashboard', 'DashboardController@index')
    ->domain('admin.example.com');
```

### Порты

```php
Route::get('/metrics', 'MetricsController@index')
    ->port(9090);
```

## ⚡ Производительность

### Кеширование

```php
// Включить кеш для маршрута
Route::get('/static', fn() => 'data')
    ->cache();

// Кеш всех маршрутов
Route::cacheRoutes('cache/routes.php');
```

---

**Переводы**: [English](../../en/documentation/routes.md) | [Deutsch](../../de/documentation/routes.md) | [Français](../../fr/documentation/routes.md)
