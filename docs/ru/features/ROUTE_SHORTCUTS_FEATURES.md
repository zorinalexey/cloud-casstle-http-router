# Route Shortcuts - Детальное описание быстрых методов

[English](../../en/features/ROUTE_SHORTCUTS_FEATURES.md) | **Русский** | [Deutsch](../../de/features/ROUTE_SHORTCUTS_FEATURES.md) | [Français](../../fr/features/ROUTE_SHORTCUTS_FEATURES.md) | [中文](../../zh/features/ROUTE_SHORTCUTS_FEATURES.md)

---

## Содержание

- [Middleware Shortcuts](#middleware-shortcuts)
- [Security Shortcuts](#security-shortcuts)
- [Throttling Shortcuts](#throttling-shortcuts)
- [Tagging Shortcuts](#tagging-shortcuts)
- [Combined Shortcuts](#combined-shortcuts)
- [Сравнение с аналогами](#сравнение-с-аналогами)

---

## Middleware Shortcuts

### auth()

Добавить Auth middleware:

```php
Route::get('/dashboard', $action)->auth();
// Вместо: ->middleware(['auth'])

Route::get('/profile', $action)->auth();
```

### guest()

Только для гостей (не авторизованных):

```php
Route::get('/login', $action)->guest();
Route::get('/register', $action)->guest();
```

### api()

API middleware:

```php
Route::get('/api/users', $action)->api();
// Вместо: ->middleware(['api'])
```

### web()

Web middleware:

```php
Route::get('/home', $action)->web();
// Вместо: ->middleware(['web'])
```

### cors()

CORS middleware:

```php
Route::get('/api/public', $action)->cors();
```

---

## Security Shortcuts

### localhost()

Доступ только с localhost:

```php
Route::get('/debug', $action)->localhost();
// Вместо: ->whitelistIp(['127.0.0.1', '::1'])
```

### secure() / https()

Только HTTPS:

```php
Route::get('/payment', $action)->secure();
Route::post('/checkout', $action)->https();
// Вместо: ->httpsOnly()
```

---

## Throttling Shortcuts

### throttleStandard()

Стандартный лимит (60 req/min):

```php
Route::post('/api/users', $action)->throttleStandard();
// Вместо: ->throttle(60, TimeUnit::MINUTE)
```

### throttleStrict()

Строгий лимит (10 req/min):

```php
Route::post('/login', $action)->throttleStrict();
// Вместо: ->throttle(10, TimeUnit::MINUTE)
```

### throttleGenerous()

Щедрый лимит (1000 req/hour):

```php
Route::get('/api/public', $action)->throttleGenerous();
// Вместо: ->throttle(1000, TimeUnit::HOUR)
```

---

## Tagging Shortcuts

### public()

Добавить тег 'public':

```php
Route::get('/about', $action)->public();
// Вместо: ->tags(['public'])
```

### private()

Добавить тег 'private':

```php
Route::get('/account', $action)->private();
```

### admin()

Добавить тег 'admin':

```php
Route::get('/admin/panel', $action)->admin();
```

---

## Combined Shortcuts

### apiEndpoint()

Полная конфигурация API endpoint:

```php
Route::get('/api/users', $action)->apiEndpoint();

// Эквивалентно:
// ->middleware(['api'])
// ->throttle(60, TimeUnit::MINUTE)
// ->tags(['api'])
// ->cors()
```

### protected()

Защищенный ресурс:

```php
Route::get('/dashboard', $action)->protected();

// Эквивалентно:
// ->middleware(['auth'])
// ->httpsOnly()
// ->tags(['protected'])
```

---

## Примеры использования

### API Routes

```php
Route::group(['prefix' => '/api'], function() {
    // Публичные
    Route::get('/posts', $action)->apiEndpoint()->public();
    
    // Защищенные
    Route::post('/posts', $action)->apiEndpoint()->auth();
    Route::delete('/posts/{id}', $action)->apiEndpoint()->auth();
});
```

### Admin Panel

```php
Route::group(['prefix' => '/admin'], function() {
    Route::get('/dashboard', $action)
        ->auth()
        ->admin()
        ->secure()
        ->throttleStandard();
        
    Route::get('/users', $action)
        ->protected()
        ->admin();
});
```

### Auth Routes

```php
// Login with rate limiting
Route::post('/login', $action)
    ->guest()
    ->throttleStrict(); // 10 req/min

// Register
Route::post('/register', $action)
    ->guest()
    ->throttleStandard();

// Dashboard
Route::get('/dashboard', $action)
    ->protected();
```

---

## Полный список shortcuts

| Shortcut | Что делает | Пример |
|----------|------------|--------|
| `auth()` | Auth middleware | `->auth()` |
| `guest()` | Guest middleware | `->guest()` |
| `api()` | API middleware | `->api()` |
| `web()` | Web middleware | `->web()` |
| `cors()` | CORS middleware | `->cors()` |
| `localhost()` | Only localhost | `->localhost()` |
| `secure()` | HTTPS only | `->secure()` |
| `https()` | HTTPS only | `->https()` |
| `throttleStandard()` | 60/min | `->throttleStandard()` |
| `throttleStrict()` | 10/min | `->throttleStrict()` |
| `throttleGenerous()` | 1000/hour | `->throttleGenerous()` |
| `public()` | Tag: public | `->public()` |
| `private()` | Tag: private | `->private()` |
| `admin()` | Tag: admin | `->admin()` |
| `apiEndpoint()` | Full API setup | `->apiEndpoint()` |
| `protected()` | Full protection | `->protected()` |

---

## Сравнение с аналогами

| Роутер | Shortcuts | Количество | Chainable | Оценка |
|--------|-----------|------------|-----------|--------|
| **CloudCastle** | ✅ | **16** | ✅ | **⭐⭐⭐⭐⭐** |
| Laravel | ⚠️ | 5 | ✅ | ⭐⭐⭐⭐ |
| Symfony | ❌ | 0 | - | ⭐⭐ |
| FastRoute | ❌ | 0 | - | ⭐ |
| Slim | ❌ | 0 | - | ⭐ |

### Детальное сравнение

**CloudCastle: 16 shortcuts**
```php
->auth()->secure()->throttleStandard()->admin()
// Chainable!
```

**Laravel: ~5 shortcuts**
```php
->middleware('auth')
->name('route.name')
// Меньше готовых shortcuts
```

**Symfony, FastRoute, Slim:**
```php
// Нет shortcuts вообще
```

---

## Преимущества

**CloudCastle предлагает МАКСИМУМ shortcuts:**

✅ 16 готовых методов  
✅ Chainable API  
✅ Удобная комбинация  
✅ Сокращение кода в 3-5 раз  

**Было:**
```php
Route::get('/admin/users', $action)
    ->middleware(['auth', 'admin'])
    ->httpsOnly()
    ->throttle(60, TimeUnit::MINUTE)
    ->tags(['admin', 'protected']);
```

**Стало:**
```php
Route::get('/admin/users', $action)
    ->protected()
    ->admin();
```

**5 строк → 2 строки!**

---

## Заключение

**CloudCastle - ЛИДЕР по количеству shortcuts!**

✅ 16 методов (больше всех)  
✅ Удобные комбинации  
✅ Chainable API  
✅ Экономия кода  

**Рекомендация:** Используйте shortcuts для чистого кода!

---

[⬆ Наверх](#route-shortcuts---детальное-описание-быстрых-методов) | [📚 Все фичи](../ALL_FEATURES.md) | [🏠 Главная](../../../README.md)

© 2024 CloudCastle HTTP Router
