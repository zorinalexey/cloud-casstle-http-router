# Middleware

**CloudCastle HTTP Router v1.1.0**  
**Language**: English

**Translations
**: [Русский](../../ru/documentation/middleware.md) | [Deutsch](../../de/documentation/middleware.md) | [Français](../../fr/documentation/middleware.md)

---

## 🎯 What is Middleware?

Middleware is an intermediate layer for processing requests and responses.

## 🚀 Basic Usage

```php
Route::get('/profile', 'ProfileController@show')
    ->middleware('auth');

// Multiple middleware
Route::post('/admin/action', 'AdminController@action')
    ->middleware(['auth', 'admin', 'verified']);
```

## 🛡️ Built-in Middleware

- **HttpsEnforcement** - Force HTTPS
- **SsrfProtection** - SSRF protection
- **SecurityLogger** - Security logging

---

**Translations
**: [Русский](../../ru/documentation/middleware.md) | [Deutsch](../../de/documentation/middleware.md) | [Français](../../fr/documentation/middleware.md)
