# Schnellstart

**CloudCastle HTTP Router v1.1.0**  
**Sprache**: Deutsch

**Übersetzungen
**: [Русский](../../ru/documentation/quickstart.md) | [English](../../en/documentation/quickstart.md) | [Français](../../fr/documentation/quickstart.md)

---

## 📦 Installation

```bash
composer require cloud-castle/http-router
```

## 🚀 Erste Route

```php
<?php
require 'vendor/autoload.php';

use CloudCastle\Http\Router\Facade\Route;

Route::get('/hello', function() {
    return 'Hallo, Welt!';
});

$result = Route::dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
echo $result;
```

## 📝 Grundlegende Beispiele

### GET-Anfrage

```php
Route::get('/users', 'UserController@index');
```

### Mit Parametern

```php
Route::get('/user/{id}', function($id) {
    return "Benutzer ID: $id";
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

**Übersetzungen
**: [Русский](../../ru/documentation/quickstart.md) | [English](../../en/documentation/quickstart.md) | [Français](../../fr/documentation/quickstart.md)
