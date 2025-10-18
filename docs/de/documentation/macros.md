# Makros

**CloudCastle HTTP Router v1.1.1**  
**Sprache**: Deutsch

---

## 🌍 Übersetzungen

- [Русский](../../ru/documentation/macros.md)
- [English](../../en/documentation/macros.md)
- **[Deutsch](macros.md)** (aktuell)
- [Français](../../fr/documentation/macros.md)

---

## 📋 Einführung

Makros sind vordefinierte Sätze von Routen für typische Szenarien.

---

## 🎯 Eingebaute Makros

### resource() - RESTful Ressource

```php
Route::resource('posts', 'PostController');
```

Erstellt Routen:
- `GET    /posts          →  index()`
- `POST   /posts          →  store()`
- `GET    /posts/{id}     →  show($id)`
- `PUT    /posts/{id}     →  update($id)`
- `DELETE /posts/{id}     →  destroy($id)`

### apiResource() - API-Ressource

```php
Route::apiResource('articles', 'ArticleController');
```

### crud() - CRUD-Operationen

```php
Route::crud('products', 'ProductController');
```

### auth() - Authentifizierung

```php
Route::auth();
```

---

**[← Zurück zum Inhaltsverzeichnis](README.md)**

