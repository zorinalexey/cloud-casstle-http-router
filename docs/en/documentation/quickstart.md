# Quick Start

**CloudCastle HTTP Router v1.1.0**  
**Language**: English

**Translations**: [Русский](../../ru/documentation/quickstart.md) | [Deutsch](../../de/documentation/quickstart.md) | [Français](../../fr/documentation/quickstart.md)

---

## 📦 Installation

```bash
composer require cloud-castle/http-router
```

## 🚀 First Route

```php
<?php
require 'vendor/autoload.php';

use CloudCastle\Http\Router\Facade\Route;

Route::get('/hello', function() {
    return 'Hello, World!';
});

$result = Route::dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
echo $result;
```

## 📝 Basic Examples

### GET Request
```php
Route::get('/users', 'UserController@index');
```

### With Parameters
```php
Route::get('/user/{id}', function($id) {
    return "User ID: $id";
});
```

### Rate Limiting
```php
Route::post('/api/data', 'ApiController@store')
    ->perMinute(60);
```

### Auto-Ban
```php
Route::post('/login', 'AuthController@login')
    ->throttleWithBan(5, 60, 3, 7200);
```

---

**Translations**: [Русский](../../ru/documentation/quickstart.md) | [Deutsch](../../de/documentation/quickstart.md) | [Français](../../fr/documentation/quickstart.md)
