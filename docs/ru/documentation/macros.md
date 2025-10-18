# Макросы

**CloudCastle HTTP Router v1.1.1**  
**Язык**: Русский

---

## 🌍 Переводы

- **[Русский](macros.md)** (текущий)
- [English](../../en/documentation/macros.md)
- [Deutsch](../../de/documentation/macros.md)
- [Français](../../fr/documentation/macros.md)

---

## 📋 Введение

Макросы - это предопределенные наборы маршрутов для типичных сценариев (REST, CRUD, Auth и др.).

---

## 🎯 Встроенные макросы

### resource() - RESTful ресурс

```php
Route::resource('posts', 'PostController');
```

Создаёт маршруты:
- `GET    /posts          →  index()`
- `POST   /posts          →  store()`
- `GET    /posts/{id}     →  show($id)`
- `PUT    /posts/{id}     →  update($id)`
- `DELETE /posts/{id}     →  destroy($id)`

### apiResource() - API ресурс

```php
Route::apiResource('articles', 'ArticleController');
```

Создаёт маршруты с rate limiting:
- `GET    /articles       →  index()` (100/мин)
- `POST   /articles       →  store()` (30/мин)
- `GET    /articles/{id}  →  show()` (200/мин)
- `PUT    /articles/{id}  →  update()` (50/мин)
- `DELETE /articles/{id}  →  destroy()` (10/мин)

### crud() - CRUD операции

```php
Route::crud('products', 'ProductController');
```

### auth() - Аутентификация

```php
Route::auth();
```

Создаёт маршруты:
- `GET  /login  → showLogin()`
- `POST /login  → login()`
- `POST /logout → logout()`

### adminPanel() - Админ панель

```php
Route::adminPanel('admin', 'AdminController');
```

### apiVersion() - Версионированный API

```php
Route::apiVersion('v1', function() {
    Route::get('/users', 'UserController@index');
});
```

### webhooks() - Вебхуки

```php
Route::webhooks('webhooks', 'WebhookController');
```

---

## 🔗 См. также

- [Примеры макросов](../../../examples/macros-usage.php)

---

**[← Назад к оглавлению](README.md)**

