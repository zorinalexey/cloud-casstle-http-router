# Macros

**CloudCastle HTTP Router v1.1.1**  
**Language**: English

---

## 🌍 Translations

- [Русский](../../ru/documentation/macros.md)
- **[English](macros.md)** (current)
- [Deutsch](../../de/documentation/macros.md)
- [Français](../../fr/documentation/macros.md)

---

## 📋 Introduction

Macros are predefined sets of routes for typical scenarios (REST, CRUD, Auth, etc.).

---

## 🎯 Built-in Macros

### resource() - RESTful Resource

```php
Route::resource('posts', 'PostController');
```

Creates routes:
- `GET    /posts          →  index()`
- `POST   /posts          →  store()`
- `GET    /posts/{id}     →  show($id)`
- `PUT    /posts/{id}     →  update($id)`
- `DELETE /posts/{id}     →  destroy($id)`

### apiResource() - API Resource

```php
Route::apiResource('articles', 'ArticleController');
```

Creates routes with rate limiting

### crud() - CRUD Operations

```php
Route::crud('products', 'ProductController');
```

### auth() - Authentication

```php
Route::auth();
```

Creates:
- `GET  /login  → showLogin()`
- `POST /login  → login()`
- `POST /logout → logout()`

---

## 🔗 See Also

- [Macro Examples](../../../examples/macros-usage.php)

---

**[← Back to contents](README.md)**

