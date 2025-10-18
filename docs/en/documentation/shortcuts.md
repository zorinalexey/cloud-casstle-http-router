# Shortcuts

**CloudCastle HTTP Router v1.1.1**  
**Language**: English

---

## 🌍 Translations

- [Русский](../../ru/documentation/shortcuts.md)
- **[English](shortcuts.md)** (current)
- [Deutsch](../../de/documentation/shortcuts.md)
- [Français](../../fr/documentation/shortcuts.md)

---

## 📋 Introduction

Shortcuts are convenient methods for quickly setting up typical route configurations.

---

## 🔧 Available Shortcuts

### auth() - Requires Authentication

```php
Route::get('/dashboard', 'DashboardController@index')->auth();
```

### api() - API Endpoint

```php
Route::post('/api/data', 'ApiController@store')->api();
```

### secure() - Secure Route

```php
Route::post('/payment', 'PaymentController@process')->secure();
```

### admin() - Admin Route

```php
Route::get('/admin', 'AdminController@index')->admin();
```

---

## 🔗 See Also

- [Shortcut Examples](../../../examples/shortcuts-usage.php)

---

**[← Back to contents](README.md)**

