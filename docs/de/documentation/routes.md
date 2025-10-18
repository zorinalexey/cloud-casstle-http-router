# Routen

**CloudCastle HTTP Router v1.1.1**  
**Sprache**: Deutsch

---

## 🌍 Übersetzungen

- [Русский](../../ru/documentation/routes.md)
- [English](../../en/documentation/routes.md)
- **[Deutsch](routes.md)** (aktuell)
- [Français](../../fr/documentation/routes.md)

---

## 📋 Grundlagen

### Einfache Route

```php
use CloudCastle\Http\Router\Facade\Route;

Route::get('/users', function() {
    return 'Benutzerliste';
});
```

### HTTP-Methoden

```php
Route::get('/users', 'UserController@index');
Route::post('/users', 'UserController@store');
Route::put('/users/{id}', 'UserController@update');
Route::patch('/users/{id}', 'UserController@patch');
Route::delete('/users/{id}', 'UserController@destroy');
```

### Mehrere Methoden

```php
Route::match(['GET', 'POST'], '/form', 'FormController@handle');
Route::any('/webhook', 'WebhookController@handle');
```

---

## 🔗 Routen-Parameter

### Erforderliche Parameter

```php
Route::get('/user/{id}', function($id) {
    return "Benutzer: $id";
});
```

### Parameter-Einschränkungen

```php
// Nur Ziffern
Route::get('/user/{id}', 'UserController@show')
    ->where('id', '\d+');

// Nur Buchstaben
Route::get('/category/{name}', 'CategoryController@show')
    ->where('name', '[a-z]+');
```

### Inline-Einschränkungen

```php
Route::get('/user/{id:\d+}', 'UserController@show');
Route::get('/slug/{slug:[a-z-]+}', 'PageController@show');
```

---

## 🏷️ Benannte Routen

```php
Route::get('/profile', 'ProfileController@show')
    ->name('profile');

$url = route('profile');  // /profile
```

---

## 🌐 Domain-Routing

```php
Route::get('/dashboard', 'DashboardController@index')
    ->domain('admin.example.com');
```

---

## 🔐 Protokoll-Einschränkungen

```php
// Nur HTTPS
Route::post('/login', 'AuthController@login')->https();

// WebSocket
Route::get('/ws', 'WebSocketController@connect')
    ->protocol('ws');
```

---

## 🎨 Fluent Interface

```php
Route::get('/secure-api', 'ApiController@data')
    ->name('api.secure.data')
    ->https()
    ->whitelistIp(['10.0.0.0/8'])
    ->middleware('auth')
    ->perMinute(100);
```

---

## 🔗 Siehe auch

- [Automatische Benennung](auto-naming.md)
- [Routengruppen](route-groups.md)
- [Middleware](middleware.md)

---

**[← Zurück zum Inhaltsverzeichnis](README.md)**

