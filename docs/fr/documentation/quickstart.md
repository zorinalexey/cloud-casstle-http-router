# Démarrage rapide

**CloudCastle HTTP Router v1.1.1**  
**Langue**: Français

---

## 🌍 Traductions

- [Русский](../../ru/documentation/quickstart.md)
- [English](../../en/documentation/quickstart.md)
- [Deutsch](../../de/documentation/quickstart.md)
- **[Français](quickstart.md)** (actuel)

---

## 📦 Installation

```bash
composer require cloudcastle/http-router
```

---

## 🚀 Votre première route

### Étape 1: Créez `index.php`

```php
<?php

require 'vendor/autoload.php';

use CloudCastle\Http\Router\Facade\Route;

// Route GET simple
Route::get('/', function() {
    return 'Bonjour, CloudCastle Router!';
});

// Dispatch
$result = Route::dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
echo $result;
```

### Étape 2: Démarrez le serveur PHP

```bash
php -S localhost:8000
```

### Étape 3: Ouvrez le navigateur

Naviguez vers: http://localhost:8000

Vous verrez: `Bonjour, CloudCastle Router!`

---

## 📝 Exemples de base

### Requête GET

```php
Route::get('/users', function() {
    return json_encode(['user1', 'user2', 'user3']);
});
```

### Requête POST

```php
Route::post('/users', function() {
    return 'Utilisateur créé';
});
```

### Avec paramètres

```php
Route::get('/user/{id}', function($id) {
    return "Profil utilisateur #$id";
});
```

---

## 🎯 Méthodes HTTP

```php
Route::get('/resource', 'Controller@index');
Route::post('/resource', 'Controller@store');
Route::put('/resource/{id}', 'Controller@update');
Route::delete('/resource/{id}', 'Controller@destroy');
```

---

## 🏷️ Routes nommées

```php
Route::get('/profile', 'ProfileController@show')
    ->name('profile');

$url = route('profile'); // /profile
```

---

## 🤖 Nommage automatique

```php
use CloudCastle\Http\Router\Router;

$router = Router::getInstance();
$router->enableAutoNaming();

// GET /users -> nom automatique: users.get
$router->get('/users', 'UserController@index');
```

[En savoir plus sur l'auto-naming →](auto-naming.md)

---

## ⏱️ Rate Limiting

```php
Route::post('/api/data', 'ApiController@store')
    ->perMinute(60);

Route::post('/login', 'AuthController@login')
    ->throttleWithBan(5, 60, 3, 7200);
```

---

## 🔒 Sécurité

```php
Route::post('/login', 'AuthController@login')->https();

Route::get('/admin', 'AdminController@index')
    ->whitelistIp(['192.168.1.0/24']);
```

---

**[← Retour au sommaire](README.md)**

