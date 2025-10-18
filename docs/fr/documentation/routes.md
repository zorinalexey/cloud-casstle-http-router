# Routes

**CloudCastle HTTP Router v1.1.1**  
**Langue**: Français

---

## 🌍 Traductions

- [Русский](../../ru/documentation/routes.md)
- [English](../../en/documentation/routes.md)
- [Deutsch](../../de/documentation/routes.md)
- **[Français](routes.md)** (actuel)

---

## 📋 Bases

### Route simple

```php
use CloudCastle\Http\Router\Facade\Route;

Route::get('/users', function() {
    return 'Liste des utilisateurs';
});
```

### Méthodes HTTP

```php
Route::get('/users', 'UserController@index');
Route::post('/users', 'UserController@store');
Route::put('/users/{id}', 'UserController@update');
Route::delete('/users/{id}', 'UserController@destroy');
```

### Plusieurs méthodes

```php
Route::match(['GET', 'POST'], '/form', 'FormController@handle');
Route::any('/webhook', 'WebhookController@handle');
```

---

## 🔗 Paramètres de route

### Paramètres obligatoires

```php
Route::get('/user/{id}', function($id) {
    return "Utilisateur: $id";
});
```

### Contraintes de paramètres

```php
// Chiffres uniquement
Route::get('/user/{id}', 'UserController@show')
    ->where('id', '\d+');

// Lettres uniquement
Route::get('/category/{name}', 'CategoryController@show')
    ->where('name', '[a-z]+');
```

### Contraintes en ligne

```php
Route::get('/user/{id:\d+}', 'UserController@show');
Route::get('/slug/{slug:[a-z-]+}', 'PageController@show');
```

---

## 🏷️ Routes nommées

```php
Route::get('/profile', 'ProfileController@show')
    ->name('profile');

$url = route('profile');  // /profile
```

---

## 🌐 Routage par domaine

```php
Route::get('/dashboard', 'DashboardController@index')
    ->domain('admin.example.com');
```

---

## 🔐 Restrictions de protocole

```php
// HTTPS uniquement
Route::post('/login', 'AuthController@login')->https();

// WebSocket
Route::get('/ws', 'WebSocketController@connect')
    ->protocol('ws');
```

---

## 🎨 Interface fluide

```php
Route::get('/secure-api', 'ApiController@data')
    ->name('api.secure.data')
    ->https()
    ->whitelistIp(['10.0.0.0/8'])
    ->middleware('auth')
    ->perMinute(100);
```

---

## 🔗 Voir aussi

- [Nommage automatique](auto-naming.md)
- [Groupes de routes](route-groups.md)
- [Middleware](middleware.md)

---

**[← Retour au sommaire](README.md)**

