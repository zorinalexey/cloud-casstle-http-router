# Route Shortcuts

**Категория:** Упрощение использования  
**Количество методов:** 14  
**Сложность:** ⭐ Начальный уровень

---

## Описание

Route Shortcuts - это методы-сокращения для быстрой настройки типичных конфигураций маршрутов (middleware, throttle, теги и т.д.). Один вызов метода заменяет несколько строк конфигурации.

## Все shortcuts

### 1. auth()

**Метод:** `auth(): Route`

**Описание:** Добавляет `AuthMiddleware`.

**Примеры:**

```php
// Быстрая защита маршрута
Route::get('/dashboard', $action)->auth();

// Эквивалентно:
Route::get('/dashboard', $action)
    ->middleware([AuthMiddleware::class]);

// Для группы маршрутов
Route::get('/profile', $action)->auth();
Route::get('/settings', $action)->auth();
Route::post('/upload', $action)->auth();
```

---

### 2. guest()

**Метод:** `guest(): Route`

**Описание:** Маршрут только для неавторизованных пользователей (добавляет `GuestMiddleware`).

**Примеры:**

```php
// Только для гостей
Route::get('/login', $action)->guest();
Route::get('/register', $action)->guest();

// Эквивалентно:
Route::get('/login', $action)
    ->middleware([GuestMiddleware::class]);
```

---

### 3. api()

**Метод:** `api(): Route`

**Описание:** Настройка для API маршрута (добавляет API middleware).

**Примеры:**

```php
Route::get('/api/users', $action)->api();

// Добавляет:
// - API middleware
// - JSON header
// - CORS (опционально)
```

---

### 4. web()

**Метод:** `web(): Route`

**Описание:** Настройка для Web маршрута (CSRF, Session, Cookies).

**Примеры:**

```php
Route::get('/page', $action)->web();
Route::post('/form', $action)->web();

// Добавляет:
// - CSRF protection
// - Session middleware
// - Cookie middleware
```

---

### 5. cors()

**Метод:** `cors(): Route`

**Описание:** Добавляет `CorsMiddleware`.

**Примеры:**

```php
Route::get('/api/public', $action)->cors();

// Эквивалентно:
Route::get('/api/public', $action)
    ->middleware([CorsMiddleware::class]);

// Часто используется с API
Route::get('/api/data', $action)->api()->cors();
```

---

### 6. localhost()

**Метод:** `localhost(): Route`

**Описание:** Ограничить доступ только с localhost (127.0.0.1).

**Примеры:**

```php
// Только localhost
Route::get('/debug', $action)->localhost();

// Эквивалентно:
Route::get('/debug', $action)
    ->whitelistIp(['127.0.0.1', '::1']);

// Отладочные эндпоинты
Route::get('/phpinfo', fn() => phpinfo())->localhost();
Route::get('/debug/routes', fn() => route_stats())->localhost();
```

---

### 7. secure()

**Метод:** `secure(): Route`

**Описание:** Требует HTTPS (принудительное использование).

**Примеры:**

```php
// HTTPS required
Route::get('/payment', $action)->secure();
Route::post('/checkout', $action)->secure();

// Эквивалентно:
Route::get('/payment', $action)->https();

// Для всех важных операций
Route::post('/api/sensitive', $action)->secure();
```

---

### 8. throttleStandard()

**Метод:** `throttleStandard(): Route`

**Описание:** Стандартный rate limit - 60 запросов в минуту.

**Примеры:**

```php
// 60 запросов/мин
Route::post('/api/data', $action)->throttleStandard();

// Эквивалентно:
Route::post('/api/data', $action)->throttle(60, 1);
```

---

### 9. throttleStrict()

**Метод:** `throttleStrict(): Route`

**Описание:** Строгий rate limit - 10 запросов в минуту.

**Примеры:**

```php
// 10 запросов/мин для критичных операций
Route::post('/api/critical', $action)->throttleStrict();

// Эквивалентно:
Route::post('/api/critical', $action)->throttle(10, 1);

// Для login, password reset
Route::post('/login', $action)->throttleStrict();
```

---

### 10. throttleGenerous()

**Метод:** `throttleGenerous(): Route`

**Описание:** Щедрый rate limit - 1000 запросов в минуту.

**Примеры:**

```php
// 1000 запросов/мин для массовых операций
Route::post('/api/bulk', $action)->throttleGenerous();

// Эквивалентно:
Route::post('/api/bulk', $action)->throttle(1000, 1);

// Для публичных API
Route::get('/api/public/data', $action)->throttleGenerous();
```

---

### 11. public()

**Метод:** `public(): Route`

**Описание:** Добавляет тег 'public'.

**Примеры:**

```php
Route::get('/page', $action)->public();

// Эквивалентно:
Route::get('/page', $action)->tag('public');

// Для публичных API
Route::get('/api/news', $action)->public()->cors();
```

---

### 12. private()

**Метод:** `private(): Route`

**Описание:** Добавляет тег 'private'.

**Примеры:**

```php
Route::get('/internal', $action)->private();

// Эквивалентно:
Route::get('/internal', $action)->tag('private');

// Часто с auth
Route::get('/user/data', $action)->private()->auth();
```

---

### 13. admin()

**Метод:** `admin(): Route`

**Описание:** Полная настройка админского маршрута.

**Добавляет:**
- `AuthMiddleware`
- `AdminMiddleware`
- HTTPS enforcement
- IP whitelist (если настроено)

**Примеры:**

```php
// Быстрая настройка админа
Route::get('/admin/users', $action)->admin();

// Эквивалентно:
Route::get('/admin/users', $action)
    ->middleware([AuthMiddleware::class, AdminMiddleware::class])
    ->https();

// Комбинация
Route::delete('/admin/users/{id}', $action)
    ->admin()
    ->throttleStrict();
```

---

### 14. apiEndpoint()

**Метод:** `apiEndpoint(): Route`

**Описание:** Полная настройка API эндпоинта.

**Добавляет:**
- API middleware
- CORS
- JSON content-type
- Rate limiting (60/min)

**Примеры:**

```php
// Быстрая настройка API
Route::get('/api/data', $action)->apiEndpoint();

// Эквивалентно:
Route::get('/api/data', $action)
    ->middleware([ApiMiddleware::class, CorsMiddleware::class])
    ->throttle(60, 1);

// Публичный API эндпоинт
Route::get('/api/public/news', $action)
    ->apiEndpoint()
    ->public();
```

---

## Комбинации shortcuts

```php
// Публичный API с CORS
Route::get('/api/data', $action)
    ->apiEndpoint()
    ->public()
    ->throttleGenerous();

// Защищенный админский эндпоинт
Route::post('/admin/critical', $action)
    ->admin()
    ->throttleStrict()
    ->localhost();

// Приватный API с аутентификацией
Route::post('/api/user/action', $action)
    ->auth()
    ->private()
    ->throttleStandard()
    ->secure();
```

---

## Рекомендации

### ✅ Хорошие практики

```php
// ✅ Используйте shortcuts для типичных случаев
Route::get('/dashboard', $action)->auth();

// ✅ Комбинируйте shortcuts
Route::post('/api/data', $action)->apiEndpoint()->auth();

// ✅ Shortcuts читаемее
Route::post('/login', $action)->throttleStrict();
// vs
Route::post('/login', $action)->throttle(10, 1);
```

### ❌ Антипаттерны

```php
// ❌ Не дублируйте функциональность
Route::get('/api/data', $action)
    ->apiEndpoint()  // Уже добавляет throttle
    ->throttle(60, 1);  // Дублирование!
```

---

## См. также

- [Middleware](06_MIDDLEWARE.md)
- [Rate Limiting](04_RATE_LIMITING.md)
- [Теги](08_TAGS.md)

---

**Версия:** 1.1.1  
**Статус:** ✅ Стабильная функциональность

