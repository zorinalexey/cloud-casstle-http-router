# Shortcuts

**CloudCastle HTTP Router v1.1.1**  
**Langue**: Français

---

## 🌍 Traductions

- [Русский](../../ru/documentation/shortcuts.md)
- [English](../../en/documentation/shortcuts.md)
- [Deutsch](../../de/documentation/shortcuts.md)
- **[Français](shortcuts.md)** (actuel)

---

## 📋 Introduction

Les shortcuts sont des méthodes pratiques pour configurer rapidement des routes typiques.

---

## 🔧 Shortcuts disponibles

### auth() - Authentification requise

```php
Route::get('/dashboard', 'DashboardController@index')->auth();
```

### api() - Point de terminaison API

```php
Route::post('/api/data', 'ApiController@store')->api();
```

### secure() - Route sécurisée

```php
Route::post('/payment', 'PaymentController@process')->secure();
```

### admin() - Route admin

```php
Route::get('/admin', 'AdminController@index')->admin();
```

---

**[← Retour au sommaire](README.md)**

