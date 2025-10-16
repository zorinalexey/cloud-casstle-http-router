# Быстрый старт

**CloudCastle HTTP Router v1.1.0**  
**Язык**: Русский

**Переводы**: [English](../../en/documentation/quickstart.md) | [Deutsch](../../de/documentation/quickstart.md) | [Français](../../fr/documentation/quickstart.md)

---

## 📦 Установка

```bash
composer require cloudcastle/http-router
```

## 🚀 Первый маршрут

```php
<?php

require 'vendor/autoload.php';

use CloudCastle\Http\Router\Facade\Route;

// Простой маршрут
Route::get('/hello', function() {
    return 'Hello, World!';
});

// Диспетчеризация
$result = Route::dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
echo $result;
```

## 📝 Базовые примеры

### GET запрос
```php
Route::get('/users', 'UserController@index');
```

### POST запрос
```php
Route::post('/users', 'UserController@store');
```

### С параметрами
```php
Route::get('/user/{id}', function($id) {
    return "User ID: $id";
});
```

### С ограничениями
```php
Route::get('/user/{id}', 'UserController@show')
    ->where('id', '\d+');
```

## 🔒 Защита маршрутов

### Rate Limiting
```php
// 60 запросов в минуту
Route::post('/api/data', 'ApiController@store')
    ->perMinute(60);
```

### Автобан
```php
// Защита от brute-force
Route::post('/login', 'AuthController@login')
    ->throttleWithBan(5, 60, 3, 7200);
```

### Middleware
```php
Route::get('/profile', 'ProfileController@show')
    ->middleware('auth');
```

## 📚 Далее

- [Маршруты](routes.md)
- [Rate Limiting](rate-limiting.md)
- [Автобан](auto-ban.md)
- [API Reference](api-reference.md)

---

**Переводы**: [English](../../en/documentation/quickstart.md) | [Deutsch](../../de/documentation/quickstart.md) | [Français](../../fr/documentation/quickstart.md)
