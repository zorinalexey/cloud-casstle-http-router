# Route Macros - Детальное описание макросов маршрутов

[English](../../en/features/ROUTE_MACROS_FEATURES.md) | **Русский** | [Deutsch](../../de/features/ROUTE_MACROS_FEATURES.md) | [Français](../../fr/features/ROUTE_MACROS_FEATURES.md) | [中文](../../zh/features/ROUTE_MACROS_FEATURES.md)

---

## Содержание

- [resource()](#resource)
- [apiResource()](#apiresource)
- [auth()](#auth)
- [adminPanel()](#adminpanel)
- [apiVersion()](#apiversion)
- [webhooks()](#webhooks)
- [Сравнение с аналогами](#сравнение-с-аналогами)

---

## resource()

### Описание

Создание полного RESTful ресурса (7 маршрутов).

### Использование

```php
use CloudCastle\Http\Router\RouteMacros;

RouteMacros::resource('users', UserController::class);
```

### Создаваемые маршруты

| Method | URI | Action | Name |
|--------|-----|--------|------|
| GET | /users | index | users.index |
| GET | /users/create | create | users.create |
| POST | /users | store | users.store |
| GET | /users/{id} | show | users.show |
| GET | /users/{id}/edit | edit | users.edit |
| PUT/PATCH | /users/{id} | update | users.update |
| DELETE | /users/{id} | destroy | users.destroy |

### Опции

```php
// Только определенные действия
RouteMacros::resource('posts', PostController::class, [
    'only' => ['index', 'show']
]);

// Исключить действия
RouteMacros::resource('comments', CommentController::class, [
    'except' => ['create', 'edit']
]);

// С middleware
RouteMacros::resource('admin/users', UserController::class, [
    'middleware' => ['auth', 'admin']
]);
```

---

## apiResource()

### Описание

RESTful ресурс для API (без create/edit форм).

### Использование

```php
RouteMacros::apiResource('posts', PostController::class);
```

### Создаваемые маршруты

| Method | URI | Action | Name |
|--------|-----|--------|------|
| GET | /posts | index | posts.index |
| POST | /posts | store | posts.store |
| GET | /posts/{id} | show | posts.show |
| PUT/PATCH | /posts/{id} | update | posts.update |
| DELETE | /posts/{id} | destroy | posts.destroy |

### С rate limiting

```php
RouteMacros::apiResource('posts', PostController::class, [
    'throttle' => 60,
    'middleware' => ['api']
]);
```

---

## auth()

### Описание

Все маршруты аутентификации.

### Использование

```php
RouteMacros::auth();
```

### Создаваемые маршруты

| Method | URI | Action | Name |
|--------|-----|--------|------|
| GET | /login | showLoginForm | login |
| POST | /login | login | login.post |
| POST | /logout | logout | logout |
| GET | /register | showRegisterForm | register |
| POST | /register | register | register.post |
| GET | /password/reset | showResetForm | password.request |
| POST | /password/email | sendResetEmail | password.email |
| GET | /password/reset/{token} | showResetForm | password.reset |
| POST | /password/reset | reset | password.update |

### С кастомным контроллером

```php
RouteMacros::auth([
    'controller' => MyAuthController::class,
    'throttle' => 5, // 5 попыток/мин для login
]);
```

---

## adminPanel()

### Описание

Полная админ-панель с защитой.

### Использование

```php
RouteMacros::adminPanel('/admin', [
    'namespace' => 'App\\Http\\Controllers\\Admin',
    'middleware' => ['auth', 'admin'],
    'throttle' => 100,
    'whitelistIp' => ['192.168.1.0/24'],
]);
```

### Создаваемые маршруты

| URI | Description |
|-----|-------------|
| /admin | Dashboard |
| /admin/users | User management |
| /admin/users/{id} | User details |
| /admin/settings | Settings |
| /admin/logs | Activity logs |
| /admin/reports | Reports |

---

## apiVersion()

### Описание

Версионирование API.

### Использование

```php
// v1
RouteMacros::apiVersion('v1', function() {
    Route::get('/users', UserV1Controller::class);
    Route::get('/posts', PostV1Controller::class);
});

// v2
RouteMacros::apiVersion('v2', function() {
    Route::get('/users', UserV2Controller::class);
    Route::get('/posts', PostV2Controller::class);
}, [
    'throttle' => 120, // v2 имеет больший лимит
]);
```

### Результат

```
GET /api/v1/users  -> UserV1Controller
GET /api/v2/users  -> UserV2Controller
```

---

## webhooks()

### Описание

Webhook endpoints с безопасностью.

### Использование

```php
RouteMacros::webhooks([
    'github' => GithubWebhookController::class,
    'stripe' => StripeWebhookController::class,
    'paypal' => PaypalWebhookController::class,
], [
    'prefix' => '/webhooks',
    'middleware' => ['verify-signature'],
    'whitelistIp' => [
        '192.30.252.0/22', // GitHub IPs
        '54.187.174.169',  // Stripe
    ],
]);
```

### Результат

```
POST /webhooks/github  -> GithubWebhookController
POST /webhooks/stripe  -> StripeWebhookController
POST /webhooks/paypal  -> PaypalWebhookController
```

---

## Сравнение с аналогами

| Роутер | resource() | apiResource() | auth() | adminPanel() | apiVersion() | webhooks() | Оценка |
|--------|------------|---------------|--------|--------------|--------------|------------|--------|
| **CloudCastle** | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | **⭐⭐⭐⭐⭐** |
| Laravel | ✅ | ✅ | ✅ | ❌ | ⚠️ | ❌ | ⭐⭐⭐⭐ |
| Symfony | ⚠️ | ⚠️ | ❌ | ❌ | ❌ | ❌ | ⭐⭐ |
| FastRoute | ❌ | ❌ | ❌ | ❌ | ❌ | ❌ | ⭐ |
| Slim | ❌ | ❌ | ❌ | ❌ | ❌ | ❌ | ⭐ |

### Детальное сравнение

**CloudCastle: 6 макросов**
```php
RouteMacros::resource('users', UserController::class);
RouteMacros::apiResource('posts', PostController::class);
RouteMacros::auth();
RouteMacros::adminPanel('/admin');
RouteMacros::apiVersion('v1', $callback);
RouteMacros::webhooks($hooks);
```

**Laravel: 3 макроса**
```php
Route::resource('users', UserController::class);
Route::apiResource('posts', PostController::class);
Route::auth(); // Через UI пакет
// Нет adminPanel, webhooks
```

**Symfony: Частично**
```php
// Требует annotations/attributes
// Нет готовых макросов
```

---

## Экономия кода

### Без макросов

```php
// 50+ строк для resource
Route::get('/users', [UserController::class, 'index'])->name('users.index');
Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
Route::post('/users', [UserController::class, 'store'])->name('users.store');
Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show');
Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');

// + auth routes (~100 строк)
// + admin panel (~200 строк)
// Итого: ~350 строк
```

### С макросами

```php
// 3 строки!
RouteMacros::resource('users', UserController::class);
RouteMacros::auth();
RouteMacros::adminPanel('/admin');
// Итого: 3 строки

// Экономия: 350 → 3 строки (в 100+ раз!)
```

---

## Заключение

**CloudCastle - ЛИДЕР по макросам:**

✅ 6 мощных макросов  
✅ Экономия кода в 100+ раз  
✅ Уникальные возможности (adminPanel, webhooks)  
✅ Гибкая конфигурация  

**Рекомендация:** Используйте макросы для быстрого старта!

---

[⬆ Наверх](#route-macros---детальное-описание-макросов-маршрутов) | [📚 Все фичи](../ALL_FEATURES.md) | [🏠 Главная](../../../README.md)

© 2024 CloudCastle HTTP Router
