# Route Shortcuts

---

## 📚 Navigation de la Documentation

[README](../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [API_REFERENCE](../API_REFERENCE.md) | [ALL_FEATURES](../ALL_FEATURES.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [PERFORMANCE](../PERFORMANCE_ANALYSIS.md) | [SECURITY](../SECURITY_REPORT.md) | [COMPARISON](../COMPARISON.md) | [FAQ](../FAQ.md)

**Documentation détaillée:** [01](01_BASIC_ROUTING.md) | [02](02_ROUTE_PARAMETERS.md) | [03](03_ROUTE_GROUPS.md) | [04](04_RATE_LIMITING.md) | [05](05_IP_FILTERING.md) | [06](06_MIDDLEWARE.md) | [07](07_NAMED_ROUTES.md) | [08](08_TAGS.md) | [09](09_HELPER_FUNCTIONS.md) | [10](10_ROUTE_SHORTCUTS.md) | [11](11_ROUTE_MACROS.md) | [12](12_URL_GENERATION.md) | [13](13_EXPRESSION_LANGUAGE.md) | [14](14_CACHING.md) | [15](15_PLUGINS.md) | [16](16_LOADERS.md) | [17](17_PSR_SUPPORT.md) | [18](18_ACTION_RESOLVER.md) | [19](19_STATISTICS.md) | [20](20_SECURITY.md) | [21](21_EXCEPTIONS.md) | [22](22_CLI_TOOLS.md)

---


**Catégorie:** suret etavecparsurdanset  
**Nombre de méthodes:** 14  
**Complexité:** ⭐ Débutant chezsurdans

---

## etavecet

Route Shortcuts - sur méthodes-avecsuràet pour avecsur suravecsuràet etet àsuretchezet routesurdans (middleware, throttle, et et ..). et danssurdans méthode  plusieurs lignes àsuretchezetet.

## Tous shortcuts

### 1. auth()

**Méthode:** `auth(): Route`

**etavecet:** surdans `AuthMiddleware`.

**Exemples:**

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

**Méthode:** `guest(): Route`

**etavecet:** Route suràsur pour danssuretsurdans parsurdans (surdans `GuestMiddleware`).

**Exemples:**

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

**Méthode:** `api(): Route`

**etavecet:** avecsurà pour API route (surdans API middleware).

**Exemples:**

```php
Route::get('/api/users', $action)->api();

// Добавляет:
// - API middleware
// - JSON header
// - CORS (опционально)
```

---

### 4. web()

**Méthode:** `web(): Route`

**etavecet:** avecsurà pour Web route (CSRF, Session, Cookies).

**Exemples:**

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

**Méthode:** `cors(): Route`

**etavecet:** surdans `CorsMiddleware`.

**Exemples:**

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

**Méthode:** `localhost(): Route`

**etavecet:** etet suravecchez suràsur avec localhost (127.0.0.1).

**Exemples:**

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

**Méthode:** `secure(): Route`

**etavecet:** chez HTTPS (etchezetsur etavecparsurdanset).

**Exemples:**

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

**Méthode:** `throttleStandard(): Route`

**etavecet:**  rate limit - 60 requêtes dans etchezchez.

**Exemples:**

```php
// 60 запросов/мин
Route::post('/api/data', $action)->throttleStandard();

// Эквивалентно:
Route::post('/api/data', $action)->throttle(60, 1);
```

---

### 9. throttleStrict()

**Méthode:** `throttleStrict(): Route`

**etavecet:** suret rate limit - 10 requêtes dans etchezchez.

**Exemples:**

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

**Méthode:** `throttleGenerous(): Route`

**etavecet:**  rate limit - 1000 requêtes dans etchezchez.

**Exemples:**

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

**Méthode:** `public(): Route`

**etavecet:** surdans  'public'.

**Exemples:**

```php
Route::get('/page', $action)->public();

// Эквивалентно:
Route::get('/page', $action)->tag('public');

// Для публичных API
Route::get('/api/news', $action)->public()->cors();
```

---

### 12. private()

**Méthode:** `private(): Route`

**etavecet:** surdans  'private'.

**Exemples:**

```php
Route::get('/internal', $action)->private();

// Эквивалентно:
Route::get('/internal', $action)->tag('private');

// Часто с auth
Route::get('/user/data', $action)->private()->auth();
```

---

### 13. admin()

**Méthode:** `admin(): Route`

**etavecet:** sursur suravecsurà etavecàsursur route.

**surdans:**
- `AuthMiddleware`
- `AdminMiddleware`
- HTTPS enforcement
- IP whitelist (avecet suravecsursur)

**Exemples:**

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

**Méthode:** `apiEndpoint(): Route`

**etavecet:** sursur suravecsurà API paret.

**surdans:**
- API middleware
- CORS
- JSON content-type
- Rate limiting (60/min)

**Exemples:**

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

## suretsuretet shortcuts

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

## àsuretet

### ✅ sursuret àetàet

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

## Voir aussi

- [Middleware](06_MIDDLEWARE.md)
- [Rate Limiting](04_RATE_LIMITING.md)
- [Теги](08_TAGS.md)

---

**Version:** 1.1.1  
**chezavec:** ✅ etsur chezàetsursursuravec


---

## 📚 Navigation de la Documentation

[README](../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [API_REFERENCE](../API_REFERENCE.md) | [ALL_FEATURES](../ALL_FEATURES.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [FAQ](../FAQ.md)

**Documentation détaillée:** [01](01_BASIC_ROUTING.md) | [02](02_ROUTE_PARAMETERS.md) | [03](03_ROUTE_GROUPS.md) | [04](04_RATE_LIMITING.md) | [05](05_IP_FILTERING.md) | [06](06_MIDDLEWARE.md) | [07](07_NAMED_ROUTES.md) | [08](08_TAGS.md) | [09](09_HELPER_FUNCTIONS.md) | [10](10_ROUTE_SHORTCUTS.md) | [11](11_ROUTE_MACROS.md) | [12](12_URL_GENERATION.md) | [13](13_EXPRESSION_LANGUAGE.md) | [14](14_CACHING.md) | [15](15_PLUGINS.md) | [16](16_LOADERS.md) | [17](17_PSR_SUPPORT.md) | [18](18_ACTION_RESOLVER.md) | [19](19_STATISTICS.md) | [20](20_SECURITY.md) | [21](21_EXCEPTIONS.md) | [22](22_CLI_TOOLS.md)

**© 2024 CloudCastle HTTP Router**
