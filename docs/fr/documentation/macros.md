# Macros

**CloudCastle HTTP Router v1.1.1**  
**Langue**: Français

---

## 🌍 Traductions

- [Русский](../../ru/documentation/macros.md)
- [English](../../en/documentation/macros.md)
- [Deutsch](../../de/documentation/macros.md)
- **[Français](macros.md)** (actuel)

---

## 📋 Introduction

Les macros sont des ensembles prédéfinis de routes pour des scénarios typiques.

---

## 🎯 Macros intégrées

### resource() - Ressource RESTful

```php
Route::resource('posts', 'PostController');
```

Crée les routes:
- `GET    /posts          →  index()`
- `POST   /posts          →  store()`
- `GET    /posts/{id}     →  show($id)`
- `PUT    /posts/{id}     →  update($id)`
- `DELETE /posts/{id}     →  destroy($id)`

### apiResource() - Ressource API

```php
Route::apiResource('articles', 'ArticleController');
```

### crud() - Opérations CRUD

```php
Route::crud('products', 'ProductController');
```

### auth() - Authentification

```php
Route::auth();
```

---

**[← Retour au sommaire](README.md)**

