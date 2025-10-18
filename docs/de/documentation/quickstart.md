# Schnellstart

**CloudCastle HTTP Router v1.1.1**  
**Sprache**: Deutsch

---

## 🌍 Übersetzungen

- [Русский](../../ru/documentation/quickstart.md)
- [English](../../en/documentation/quickstart.md)
- **[Deutsch](quickstart.md)** (aktuell)
- [Français](../../fr/documentation/quickstart.md)

---

## 📦 Installation

```bash
composer require cloudcastle/http-router
```

---

## 🚀 Ihre erste Route

### Schritt 1: Erstellen Sie `index.php`

```php
<?php

require 'vendor/autoload.php';

use CloudCastle\Http\Router\Facade\Route;

// Einfache GET-Route
Route::get('/', function() {
    return 'Hallo, CloudCastle Router!';
});

// Dispatch
$result = Route::dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
echo $result;
```

### Schritt 2: Starten Sie den PHP-Server

```bash
php -S localhost:8000
```

### Schritt 3: Browser öffnen

Navigieren Sie zu: http://localhost:8000

Sie sehen: `Hallo, CloudCastle Router!`

---

## 📝 Grundlegende Beispiele

### GET-Anfrage

```php
Route::get('/users', function() {
    return json_encode(['user1', 'user2', 'user3']);
});
```

### POST-Anfrage

```php
Route::post('/users', function() {
    return 'Benutzer erstellt';
});
```

### Mit Parametern

```php
Route::get('/user/{id}', function($id) {
    return "Benutzerprofil #$id";
});
```

---

## 🎯 HTTP-Methoden

```php
Route::get('/resource', 'Controller@index');
Route::post('/resource', 'Controller@store');
Route::put('/resource/{id}', 'Controller@update');
Route::delete('/resource/{id}', 'Controller@destroy');
```

---

## 🏷️ Benannte Routen

```php
Route::get('/profile', 'ProfileController@show')
    ->name('profile');

$url = route('profile'); // /profile
```

---

## 🤖 Automatische Benennung

```php
use CloudCastle\Http\Router\Router;

$router = Router::getInstance();
$router->enableAutoNaming();

// GET /users -> automatischer Name: users.get
$router->get('/users', 'UserController@index');
```

[Mehr über Auto-Naming →](auto-naming.md)

---

## ⏱️ Rate Limiting

```php
Route::post('/api/data', 'ApiController@store')
    ->perMinute(60);

Route::post('/login', 'AuthController@login')
    ->throttleWithBan(5, 60, 3, 7200);
```

---

## 🔒 Sicherheit

```php
Route::post('/login', 'AuthController@login')->https();

Route::get('/admin', 'AdminController@index')
    ->whitelistIp(['192.168.1.0/24']);
```

---

**[← Zurück zum Inhaltsverzeichnis](README.md)**

