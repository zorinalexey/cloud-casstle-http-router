# Route Shortcuts

**English** | [Русский](../../ru/features/10_ROUTE_SHORTCUTS.md) | [Deutsch](../../de/features/10_ROUTE_SHORTCUTS.md) | [Français](../../fr/features/10_ROUTE_SHORTCUTS.md) | [中文](../../zh/features/10_ROUTE_SHORTCUTS.md)

---







---

## 📚 Documentation Navigation

[README](../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [API_REFERENCE](../API_REFERENCE.md) | [ALL_FEATURES](../ALL_FEATURES.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [PERFORMANCE](../PERFORMANCE_ANALYSIS.md) | [SECURITY](../SECURITY_REPORT.md) | [COMPARISON](../COMPARISON.md) | [FAQ](../FAQ.md)

**Detailed documentation:** [01](01_BASIC_ROUTING.md) | [02](02_ROUTE_PARAMETERS.md) | [03](03_ROUTE_GROUPS.md) | [04](04_RATE_LIMITING.md) | [05](05_IP_FILTERING.md) | [06](06_MIDDLEWARE.md) | [07](07_NAMED_ROUTES.md) | [08](08_TAGS.md) | [09](09_HELPER_FUNCTIONS.md) | [10](10_ROUTE_SHORTCUTS.md) | [11](11_ROUTE_MACROS.md) | [12](12_URL_GENERATION.md) | [13](13_EXPRESSION_LANGUAGE.md) | [14](14_CACHING.md) | [15](15_PLUGINS.md) | [16](16_LOADERS.md) | [17](17_PSR_SUPPORT.md) | [18](18_ACTION_RESOLVER.md) | [19](19_STATISTICS.md) | [20](20_SECURITY.md) | [21](21_EXCEPTIONS.md) | [22](22_CLI_TOOLS.md)

---


**Category:** Упрощение использования  
**Number of methods:** 14  
**Complexity:** ⭐ Beginner уровень

---

## Описание

Route Shortcuts - это methods-сокращения для быстрой настройки типичных конфигураций routeов (middleware, throttle, теги и т.д.). Один вызов methodа заменяет multiple lines конфигурации.

## All shortcuts

### 1. auth()

**Method:** `auth(): Route`

**Описание:** Добавляет `AuthMiddleware`.

**Examples:**

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

**Method:** `guest(): Route`

**Описание:** Route только для неавторизованных пользователей (добавляет `GuestMiddleware`).

**Examples:**

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

**Method:** `api(): Route`

**Описание:** Настройка для API routeа (добавляет API middleware).

**Examples:**

```php
Route::get('/api/users', $action)->api();

// Добавляет:
// - API middleware
// - JSON header
// - CORS (опционально)
```

---

### 4. web()

**Method:** `web(): Route`

**Описание:** Настройка для Web routeа (CSRF, Session, Cookies).

**Examples:**

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

**Method:** `cors(): Route`

**Описание:** Добавляет `CorsMiddleware`.

**Examples:**

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

**Method:** `localhost(): Route`

**Описание:** Ограничить доступ только с localhost (127.0.0.1).

**Examples:**

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

**Method:** `secure(): Route`

**Описание:** Требует HTTPS (принудительное использование).

**Examples:**

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

**Method:** `throttleStandard(): Route`

**Описание:** Стандартный rate limit - 60 requests в минуту.

**Examples:**

```php
// 60 запросов/мин
Route::post('/api/data', $action)->throttleStandard();

// Эквивалентно:
Route::post('/api/data', $action)->throttle(60, 1);
```

---

### 9. throttleStrict()

**Method:** `throttleStrict(): Route`

**Описание:** Строгий rate limit - 10 requests в минуту.

**Examples:**

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

**Method:** `throttleGenerous(): Route`

**Описание:** Щедрый rate limit - 1000 requests в минуту.

**Examples:**

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

**Method:** `public(): Route`

**Описание:** Добавляет тег 'public'.

**Examples:**

```php
Route::get('/page', $action)->public();

// Эквивалентно:
Route::get('/page', $action)->tag('public');

// Для публичных API
Route::get('/api/news', $action)->public()->cors();
```

---

### 12. private()

**Method:** `private(): Route`

**Описание:** Добавляет тег 'private'.

**Examples:**

```php
Route::get('/internal', $action)->private();

// Эквивалентно:
Route::get('/internal', $action)->tag('private');

// Часто с auth
Route::get('/user/data', $action)->private()->auth();
```

---

### 13. admin()

**Method:** `admin(): Route`

**Описание:** Полная настройка админского routeа.

**Добавляет:**
- `AuthMiddleware`
- `AdminMiddleware`
- HTTPS enforcement
- IP whitelist (если настроено)

**Examples:**

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

**Method:** `apiEndpoint(): Route`

**Описание:** Полная настройка API эндпоинта.

**Добавляет:**
- API middleware
- CORS
- JSON content-type
- Rate limiting (60/min)

**Examples:**

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

### ❌ Anti-patterns

```php
// ❌ Не дублируйте функциональность
Route::get('/api/data', $action)
    ->apiEndpoint()  // Уже добавляет throttle
    ->throttle(60, 1);  // Дублирование!
```

---

## See also

- [Middleware](06_MIDDLEWARE.md)
- [Rate Limiting](04_RATE_LIMITING.md)
- [Теги](08_TAGS.md)

---

**Version:** 1.1.1  
**Статус:** ✅ Стабильная функциональность


---

## 📚 Documentation Navigation

[README](../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [API_REFERENCE](../API_REFERENCE.md) | [ALL_FEATURES](../ALL_FEATURES.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [FAQ](../FAQ.md)

**Detailed documentation:** [01](01_BASIC_ROUTING.md) | [02](02_ROUTE_PARAMETERS.md) | [03](03_ROUTE_GROUPS.md) | [04](04_RATE_LIMITING.md) | [05](05_IP_FILTERING.md) | [06](06_MIDDLEWARE.md) | [07](07_NAMED_ROUTES.md) | [08](08_TAGS.md) | [09](09_HELPER_FUNCTIONS.md) | [10](10_ROUTE_SHORTCUTS.md) | [11](11_ROUTE_MACROS.md) | [12](12_URL_GENERATION.md) | [13](13_EXPRESSION_LANGUAGE.md) | [14](14_CACHING.md) | [15](15_PLUGINS.md) | [16](16_LOADERS.md) | [17](17_PSR_SUPPORT.md) | [18](18_ACTION_RESOLVER.md) | [19](19_STATISTICS.md) | [20](20_SECURITY.md) | [21](21_EXCEPTIONS.md) | [22](22_CLI_TOOLS.md)

**© 2024 CloudCastle HTTP Router**
