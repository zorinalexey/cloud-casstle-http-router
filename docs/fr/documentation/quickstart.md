# Démarrage rapide

**CloudCastle HTTP Router v1.1.0**  
**Langue**: Français

**Traductions**: [Русский](../../ru/documentation/quickstart.md) | [English](../../en/documentation/quickstart.md) | [Deutsch](../../de/documentation/quickstart.md)

---

## 📦 Installation

```bash
composer require cloud-castle/http-router
```

## 🚀 Première Route

```php
<?php
require 'vendor/autoload.php';

use CloudCastle\Http\Router\Facade\Route;

Route::get('/hello', function() {
    return 'Bonjour, Monde!';
});

$result = Route::dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
echo $result;
```

## 📝 Exemples de base

### Requête GET
```php
Route::get('/users', 'UserController@index');
```

### Avec paramètres
```php
Route::get('/user/{id}', function($id) {
    return "Utilisateur ID: $id";
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

**Traductions**: [Русский](../../ru/documentation/quickstart.md) | [English](../../en/documentation/quickstart.md) | [Deutsch](../../de/documentation/quickstart.md)
