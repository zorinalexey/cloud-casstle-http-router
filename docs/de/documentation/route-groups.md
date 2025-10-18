# Routengruppen

**CloudCastle HTTP Router v1.1.1**  
**Sprache**: Deutsch

---

## 🌍 Übersetzungen

- [Русский](../../ru/documentation/route-groups.md)
- [English](../../en/documentation/route-groups.md)
- **[Deutsch](route-groups.md)** (aktuell)
- [Français](../../fr/documentation/route-groups.md)

---

## 📋 Einführung

Routengruppen ermöglichen es, gemeinsame Attribute auf mehrere Routen gleichzeitig anzuwenden.

---

## 🔧 Grundlegende Verwendung

### Mit Präfix

```php
Route::group(['prefix' => 'admin'], function() {
    Route::get('/users', 'AdminController@users');     // /admin/users
    Route::get('/posts', 'AdminController@posts');     // /admin/posts
});
```

### Mit Middleware

```php
Route::group(['middleware' => 'auth'], function() {
    Route::get('/dashboard', 'DashboardController@index');
});
```

---

## 🎨 Gruppen-Attribute

- **prefix** - URL-Präfix
- **middleware** - Middleware
- **domain** - Domain-Einschränkung
- **port** - Port-Einschränkung
- **https** - HTTPS erforderlich
- **whitelistIp** - IP-Whitelist
- **tags** - Route-Tags

---

## 🔄 Verschachtelte Gruppen

```php
Route::group(['prefix' => 'api'], function() {
    Route::group(['prefix' => 'v1'], function() {
        Route::get('/users', 'UserController@index'); // /api/v1/users
    });
});
```

---

## 💡 Praktische Beispiele

### Admin-Panel

```php
Route::group([
    'prefix' => 'admin',
    'middleware' => ['auth', 'admin'],
    'whitelistIp' => ['192.168.1.0/24'],
    'https' => true
], function() {
    Route::get('/dashboard', 'AdminController@dashboard');
    Route::get('/users', 'AdminController@users');
});
```

---

**[← Zurück zum Inhaltsverzeichnis](README.md)**

