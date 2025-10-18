# Groupes de routes

**CloudCastle HTTP Router v1.1.1**  
**Langue**: Français

---

## 🌍 Traductions

- [Русский](../../ru/documentation/route-groups.md)
- [English](../../en/documentation/route-groups.md)
- [Deutsch](../../de/documentation/route-groups.md)
- **[Français](route-groups.md)** (actuel)

---

## 📋 Introduction

Les groupes de routes permettent d'appliquer des attributs partagés à plusieurs routes simultanément.

---

## 🔧 Utilisation de base

### Avec préfixe

```php
Route::group(['prefix' => 'admin'], function() {
    Route::get('/users', 'AdminController@users');     // /admin/users
    Route::get('/posts', 'AdminController@posts');     // /admin/posts
});
```

### Avec middleware

```php
Route::group(['middleware' => 'auth'], function() {
    Route::get('/dashboard', 'DashboardController@index');
});
```

---

## 🎨 Attributs de groupe

- **prefix** - Préfixe d'URL
- **middleware** - Middleware
- **domain** - Restriction de domaine
- **https** - HTTPS requis
- **whitelistIp** - Liste blanche IP

---

## 🔄 Groupes imbriqués

```php
Route::group(['prefix' => 'api'], function() {
    Route::group(['prefix' => 'v1'], function() {
        Route::get('/users', 'UserController@index'); // /api/v1/users
    });
});
```

---

**[← Retour au sommaire](README.md)**

