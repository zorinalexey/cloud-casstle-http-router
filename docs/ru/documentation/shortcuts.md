# Shortcuts

**CloudCastle HTTP Router v1.1.1**  
**Язык**: Русский

---

## 🌍 Переводы

- **[Русский](shortcuts.md)** (текущий)
- [English](../../en/documentation/shortcuts.md)
- [Deutsch](../../de/documentation/shortcuts.md)
- [Français](../../fr/documentation/shortcuts.md)

---

## 📋 Введение

Shortcuts - удобные методы для быстрой настройки типичных конфигураций маршрутов.

---

## 🔧 Доступные shortcuts

### auth() - Требует аутентификации

```php
Route::get('/dashboard', 'DashboardController@index')
    ->auth();
```

### api() - API endpoint

```php
Route::post('/api/data', 'ApiController@store')
    ->api();  // JSON, CORS, rate limiting
```

### secure() - Безопасный маршрут

```php
Route::post('/payment', 'PaymentController@process')
    ->secure();  // HTTPS + CSRF + Auth
```

### admin() - Админ маршрут

```php
Route::get('/admin', 'AdminController@index')
    ->admin();  // Auth + Admin + IP filter
```

---

## 🔗 См. также

- [Примеры shortcuts](../../../examples/shortcuts-usage.php)

---

**[← Назад к оглавлению](README.md)**

