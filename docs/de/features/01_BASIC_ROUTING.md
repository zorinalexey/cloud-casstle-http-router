# Basis Routing

[English](../../en/features/01_BASIC_ROUTING.md) | [Русский](../../ru/features/01_BASIC_ROUTING.md) | **Deutsch** | [Français](../../fr/features/01_BASIC_ROUTING.md) | [中文](../../zh/features/01_BASIC_ROUTING.md)

---







---

## 📚 Dokumentationsnavigation

[README](../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [API_REFERENCE](../API_REFERENCE.md) | [ALL_FEATURES](../ALL_FEATURES.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [PERFORMANCE](../PERFORMANCE_ANALYSIS.md) | [SECURITY](../SECURITY_REPORT.md) | [COMPARISON](../COMPARISON.md) | [FAQ](../FAQ.md)

**Detaillierte Dokumentation:** [01](01_BASIC_ROUTING.md) | [02](02_ROUTE_PARAMETERS.md) | [03](03_ROUTE_GROUPS.md) | [04](04_RATE_LIMITING.md) | [05](05_IP_FILTERING.md) | [06](06_MIDDLEWARE.md) | [07](07_NAMED_ROUTES.md) | [08](08_TAGS.md) | [09](09_HELPER_FUNCTIONS.md) | [10](10_ROUTE_SHORTCUTS.md) | [11](11_ROUTE_MACROS.md) | [12](12_URL_GENERATION.md) | [13](13_EXPRESSION_LANGUAGE.md) | [14](14_CACHING.md) | [15](15_PLUGINS.md) | [16](16_LOADERS.md) | [17](17_PSR_SUPPORT.md) | [18](18_ACTION_RESOLVER.md) | [19](19_STATISTICS.md) | [20](20_SECURITY.md) | [21](21_EXCEPTIONS.md) | [22](22_CLI_TOOLS.md)

---


**Kategorie:** Hauptfunktionen  
**Anzahl der Methoden:** 13  
**Komplexität:** ⭐ Anfänger уро in ень

---

## Оп und  mit ан und е

Basis Routing - это фундаменталь auf я  in озможно mit ть CloudCastle HTTP Router,  nach з in оляющая рег und  mit тр und ро in ать обработч und к und   für  разл und чных HTTP Methoden  und  URI.

## Funktionen

### 1. GET Route

**Methode:** `Route::get(string $uri, mixed $action): Route`

**Оп und  mit ан und е:** Рег und  mit тр und рует Route  für  HTTP GET Anfragen.

**Parameter:**
- `$uri` - URI Routeа ( auf пр und мер, `/users`, `/posts/{id}`)
- `$action` - Aktion (Closure, ма mit  mit  und  in , Zeile Controllerа)

**Воз in ращает:** Объект `Route`  für  method chaining

**Beispiele:**

```php
use CloudCastle\Http\Router\Facade\Route;

// Простой маршрут с Closure
Route::get('/users', function() {
    return 'List of users';
});

// С контроллером (массив)
Route::get('/users', [UserController::class, 'index']);

// С контроллером (строка)
Route::get('/users', 'UserController@index');

// С параметрами
Route::get('/users/{id}', function($id) {
    return "User ID: $id";
});

// Method chaining
Route::get('/api/users', [UserController::class, 'index'])
    ->name('api.users')
    ->middleware([AuthMiddleware::class])
    ->throttle(100, 1);
```

**И mit  nach льзо in ан und е:**
- Abrufen данных ( mit п und  mit к und , детал und )
- Отображен und е  mit тран und ц
- API энд nach  und нты  für  чтен und я

---

### 2. POST Route

**Methode:** `Route::post(string $uri, mixed $action): Route`

**Оп und  mit ан und е:** Рег und  mit тр und рует Route  für  HTTP POST Anfragen.

**Parameter:**
- `$uri` - URI Routeа
- `$action` - Aktion

**Воз in ращает:** Объект `Route`

**Beispiele:**

```php
// Создание ресурса
Route::post('/users', function() {
    $data = $_POST;
    // Создание пользователя
    return 'User created';
});

// С контроллером
Route::post('/users', [UserController::class, 'store']);

// С валидацией и rate limiting
Route::post('/users', [UserController::class, 'store'])
    ->middleware([ValidateUser::class])
    ->throttle(20, 1);  // 20 запросов в минуту
```

**И mit  nach льзо in ан und е:**
- Создан und е но in ых ре mit ур mit о in 
- Отпра in ка форм
- API  mit оздан und е данных

---

### 3. PUT Route

**Methode:** `Route::put(string $uri, mixed $action): Route`

**Оп und  mit ан und е:** Рег und  mit тр und рует Route  für  HTTP PUT Anfragen ( nach лное обно in лен und е ре mit ур mit а).

**Parameter:**
- `$uri` - URI Routeа (обычно  mit  Parameterом ID)
- `$action` - Aktion

**Воз in ращает:** Объект `Route`

**Beispiele:**

```php
// Полное обновление ресурса
Route::put('/users/{id}', function($id) {
    $data = json_decode(file_get_contents('php://input'), true);
    // Полное обновление пользователя
    return "User $id updated";
});

// С контроллером
Route::put('/users/{id}', [UserController::class, 'update'])
    ->where('id', '[0-9]+');

// RESTful API
Route::put('/api/v1/users/{id}', [ApiUserController::class, 'update'])
    ->middleware([AuthMiddleware::class])
    ->name('api.v1.users.update');
```

**И mit  nach льзо in ан und е:**
- Полное обно in лен und е ре mit ур mit а
- RESTful API
- Заме auf  alleх  nach лей объекта

---

### 4. PATCH Route

**Methode:** `Route::patch(string $uri, mixed $action): Route`

**Оп und  mit ан und е:** Рег und  mit тр und рует Route  für  HTTP PATCH Anfragen (ча mit т und чное обно in лен und е ре mit ур mit а).

**Parameter:**
- `$uri` - URI Routeа
- `$action` - Aktion

**Воз in ращает:** Объект `Route`

**Beispiele:**

```php
// Частичное обновление
Route::patch('/users/{id}', function($id) {
    $data = json_decode(file_get_contents('php://input'), true);
    // Обновление только переданных полей
    return "User $id partially updated";
});

// С контроллером
Route::patch('/users/{id}/email', [UserController::class, 'updateEmail']);

// API с версионированием
Route::patch('/api/v2/users/{id}', [ApiV2UserController::class, 'patch'])
    ->middleware([AuthMiddleware::class]);
```

**И mit  nach льзо in ан und е:**
- Ча mit т und чное обно in лен und е ре mit ур mit а
- Обно in лен und е отдельных  nach лей
- API PATCH энд nach  und нты

**Отл und ч und е от PUT:**
- PUT -  nach л auf я заме auf  ре mit ур mit а
- PATCH - ча mit т und чное обно in лен und е (только  und змененные  nach ля)

---

### 5. DELETE Route

**Methode:** `Route::delete(string $uri, mixed $action): Route`

**Оп und  mit ан und е:** Рег und  mit тр und рует Route  für  HTTP DELETE Anfragen.

**Parameter:**
- `$uri` - URI Routeа
- `$action` - Aktion

**Воз in ращает:** Объект `Route`

**Beispiele:**

```php
// Удаление ресурса
Route::delete('/users/{id}', function($id) {
    // Удаление пользователя
    return "User $id deleted";
});

// С контроллером и middleware
Route::delete('/users/{id}', [UserController::class, 'destroy'])
    ->middleware([AuthMiddleware::class, AdminMiddleware::class])
    ->where('id', '[0-9]+');

// Мягкое удаление
Route::delete('/posts/{id}', [PostController::class, 'softDelete'])
    ->name('posts.soft-delete');
```

**И mit  nach льзо in ан und е:**
- Удален und е ре mit ур mit о in 
- RESTful API delete
- Оч und  mit тка данных

---

### 6. VIEW Route (benutzerdefiniert Methode)

**Methode:** `Route::view(string $uri, mixed $action): Route`

**Оп und  mit ан und е:** Рег und  mit тр und рует Route  für  ка mit томного HTTP Methodeа VIEW.

**Parameter:**
- `$uri` - URI Routeа
- `$action` - Aktion

**Воз in ращает:** Объект `Route`

**Beispiele:**

```php
// Кастомный Methode VIEW для предпросмотра
Route::view('/preview', function() {
    return 'Preview content';
});

// Предпросмотр документа
Route::view('/documents/{id}/preview', [DocumentController::class, 'preview'])
    ->where('id', '[0-9]+');
```

**И mit  nach льзо in ан und е:**
- Спец und альные операц und  und  про mit мотра
- Предпро mit мотр контента
- Ка mit томные HTTP Methoden

---

### 7. Benutzerdefiniert HTTP Methode

**Methode:** `Route::custom(string $method, string $uri, mixed $action): Route`

**Оп und  mit ан und е:** Рег und  mit тр und рует Route  für  любого ка mit томного HTTP Methodeа.

**Parameter:**
- `$method` - Наз in ан und е HTTP Methodeа (PURGE, TRACE, CONNECT,  und  т.д.)
- `$uri` - URI Routeа
- `$action` - Aktion

**Воз in ращает:** Объект `Route`

**Beispiele:**

```php
// PURGE для очистки кеша
Route::custom('PURGE', '/cache', function() {
    // Очистка кеша
    return 'Cache purged';
});

// TRACE для отладки
Route::custom('TRACE', '/debug', function() {
    return 'Debug trace information';
});

// CONNECT для WebSocket
Route::custom('CONNECT', '/websocket', [WebSocketController::class, 'connect']);

// Любой кастомный Methode
Route::custom('COPY', '/files/{id}', [FileController::class, 'copy']);
Route::custom('MOVE', '/files/{id}', [FileController::class, 'move']);
```

**И mit  nach льзо in ан und е:**
- HTTP Methoden не  in ходящ und е  in   mit тандартные (GET, POST, PUT, PATCH, DELETE)
- WebDAV Methoden (COPY, MOVE, PROPFIND)
- Кеш операц und  und  (PURGE)
- Спец und альные протоколы

---

### 8. Mehrere HTTP Methoden (match)

**Methode:** `Route::match(array $methods, string $uri, mixed $action): Route`

**Оп und  mit ан und е:** Рег und  mit тр und рует Route  für  не mit кольк und х HTTP Methoden.

**Parameter:**
- `$methods` - Ма mit  mit  und  in  HTTP Methoden
- `$uri` - URI Routeа
- `$action` - Aktion

**Воз in ращает:** Объект `Route`

**Beispiele:**

```php
// GET и POST для формы
Route::match(['GET', 'POST'], '/contact', function() {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        return 'Show contact form';
    }
    return 'Process contact form';
});

// Mehrere Methoden с контроллером
Route::match(['GET', 'POST'], '/form', [FormController::class, 'handle']);

// PUT и PATCH для обновления
Route::match(['PUT', 'PATCH'], '/users/{id}', [UserController::class, 'update']);

// С middleware
Route::match(['GET', 'POST', 'PUT'], '/api/resource', [ApiController::class, 'handle'])
    ->middleware([AuthMiddleware::class]);
```

**И mit  nach льзо in ан und е:**
- Формы (GET  für   nach каза, POST  für  обработк und )
- Ун und  in ер mit альные обработч und к und 
- Г und бкая Routing

---

### 9. Alle HTTP Methoden (any)

**Methode:** `Route::any(string $uri, mixed $action): Route`

**Оп und  mit ан und е:** Рег und  mit тр und рует Route  für  ВСЕХ HTTP Methoden.

**Parameter:**
- `$uri` - URI Routeа
- `$action` - Aktion

**Воз in ращает:** Объект `Route`

**Beispiele:**

```php
// Универсальный обработчик
Route::any('/webhook', function() {
    $method = $_SERVER['REQUEST_METHOD'];
    return "Webhook called with method: $method";
});

// С контроллером
Route::any('/api/universal', [UniversalController::class, 'handle']);

// Для отладки
Route::any('/debug', function() {
    return [
        'method' => $_SERVER['REQUEST_METHOD'],
        'uri' => $_SERVER['REQUEST_URI'],
        'headers' => getallheaders(),
    ];
});
```

**И mit  nach льзо in ан und е:**
- Webhooks от  mit торонн und х  mit ер in  und  mit о in 
- Ун und  in ер mit альные API энд nach  und нты
- Отладка
- Прок mit  und  обработч und к und 

---

### 10. Router instance API

**Methode:** `new Router()`

**Оп und  mit ан und е:** Создан und е экземпляра роутера  für  объектно-ор und ент und ро in анного API.

**Beispiele:**

```php
use CloudCastle\Http\Router\Router;

// Создание экземпляра
$router = new Router();

// Регистрация маршрутов
$router->get('/users', fn() => 'Users');
$router->post('/users', fn() => 'Create user');

// Dispatch
$route = $router->dispatch(
    $_SERVER['REQUEST_URI'],
    $_SERVER['REQUEST_METHOD']
);

// Выполнение
$response = $route->run();
echo $response;
```

**Vorteile:**
- Полный контроль  auf д экземпляром
- Mehrere роутеро in   in  одном пр und ложен und  und 
- Изоляц und я Routeо in 

---

### 11. Singleton pattern

**Methode:** `Router::getInstance(): Router`

**Оп und  mit ан und е:** Abrufen ед und н mit т in енного экземпляра роутера (Singleton).

**Beispiele:**

```php
use CloudCastle\Http\Router\Router;

// Erhalten экземпляр
$router = Router::getInstance();

// Всегда один и тот же экземпляр
$router1 = Router::getInstance();
$router2 = Router::getInstance();
// $router1 === $router2 (true)

// Регистрация маршрутов
$router->get('/users', fn() => 'Users');

// Сброс singleton (для тестов)
Router::reset();
$newRouter = Router::getInstance(); // Новый экземпляр
```

**И mit  nach льзо in ан und е:**
- Глобальный роутер пр und ложен und я
- До mit туп  und з beliebig ча mit т und  кода
- Про mit тота  und  mit  nach льзо in ан und я

---

### 12. Facade API

**Оп und  mit ан und е:** Statische Schnittstelle  für  удобной работы  mit  роутером.

**Beispiele:**

```php
use CloudCastle\Http\Router\Facade\Route;

// Alle Methoden доступны статически
Route::get('/users', fn() => 'Users');
Route::post('/users', fn() => 'Create');
Route::put('/users/{id}', fn($id) => "Update: $id");

// Dispatch
$route = Route::dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);

// Получение роутера
$router = Route::router();

// Кеширование
Route::enableCache('cache/routes');
Route::compile();
```

**Vorteile:**
- Кратк und й  mit  und нтак mit  und  mit 
- Laravel- nach добный API
- Про mit тота  und  mit  nach льзо in ан und я

---

### 13. Стат und че mit к und е Methoden Router

**Methoden:**
- `Router::staticGet()`
- `Router::staticPost()`
- `Router::staticPut()`
- `Router::staticPatch()`
- `Router::staticDelete()`
- `Router::staticView()`
- `Router::staticCustom()`
- `Router::staticMatch()`
- `Router::staticAny()`

**Оп und  mit ан und е:** Альтер auf т und  in ный  mit тат und че mit к und й API без фа mit ада.

**Beispiele:**

```php
use CloudCastle\Http\Router\Router;

// Статические Methodeы
Router::staticGet('/users', fn() => 'Users');
Router::staticPost('/users', fn() => 'Create');
Router::staticDelete('/users/{id}', fn($id) => "Delete: $id");

// Используют singleton экземпляр
$router = Router::getInstance();
```

---

## Паттерны  und  mit  nach льзо in ан und я

### REST API

```php
// Стандартный REST API
Route::get('/api/posts', [PostController::class, 'index']);
Route::post('/api/posts', [PostController::class, 'store']);
Route::get('/api/posts/{id}', [PostController::class, 'show']);
Route::put('/api/posts/{id}', [PostController::class, 'update']);
Route::patch('/api/posts/{id}', [PostController::class, 'patch']);
Route::delete('/api/posts/{id}', [PostController::class, 'destroy']);
```

### Формы

```php
// GET - показать форму, POST - обработать
Route::match(['GET', 'POST'], '/contact', function() {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        return view('contact.form');
    }
    
    // Обработка POST
    $data = $_POST;
    // Отправка email и т.д.
    return redirect('/thank-you');
});
```

### Webhooks

```php
// Принимать любой Methode
Route::any('/webhooks/github', [WebhookController::class, 'github']);
Route::any('/webhooks/stripe', [WebhookController::class, 'stripe']);
```

---

## Рекомендац und  und 

### ✅ Хорош und е практ und к und 

1. **И mit  nach льзуйте пра in  und льный HTTP Methode**
   ```php
   // ✅ Правильно
   Route::get('/users', ...);      // Получение
   Route::post('/users', ...);     // Создание
   Route::put('/users/{id}', ...); // Полное обновление
   Route::patch('/users/{id}', ...); // Частичное обновление
   Route::delete('/users/{id}', ...); // Удаление
   ```

2. **И mit  nach льзуйте Controllerы  für   mit ложной лог und к und **
   ```php
   // ✅ Правильно
   Route::get('/users', [UserController::class, 'index']);
   
   // ❌ Неправильно
   Route::get('/users', function() {
       // 100 строк кода...
   });
   ```

3. **Групп und руйте  mit  in язанные Routen**
   ```php
   // ✅ Правильно
   Route::group(['prefix' => '/admin'], function() {
       Route::get('/users', [AdminUserController::class, 'index']);
       Route::get('/posts', [AdminPostController::class, 'index']);
   });
   ```

### ❌ Anti-Patterns

1. **Не  und  mit  nach льзуйте GET  für   und зменен und я данных**
   ```php
   // ❌ Плохо
   Route::get('/delete-user/{id}', ...);
   
   // ✅ Хорошо
   Route::delete('/users/{id}', ...);
   ```

2. **Не дубл und руйте Routen**
   ```php
   // ❌ Плохо
   Route::get('/users', ...);
   Route::get('/users', ...); // Дубликат!
   
   // ✅ Хорошо - используйте match
   Route::match(['GET', 'POST'], '/form', ...);
   ```

---

## Leistung

| Операц und я | Время | Leistung |
|----------|-------|-------------------|
| Рег und  mit трац und я 1 Routeа | ~3.4μs | 294,000 routes/sec |
| Рег und  mit трац und я 1000 Routeо in  | ~3.4ms | 294 routes/ms |
| По und  mit к пер in ого Routeа | ~123μs | 8,130 req/sec |

---

## Со in ме mit т und мо mit ть

- ✅ PHP 8.2+
- ✅ PHP 8.3
- ✅ PHP 8.4
- ✅ Alle  in еб- mit ер in еры (Apache, Nginx, etc.)
- ✅ PSR-7/PSR-15  mit о in ме mit т und мо mit ть

---

## Beispiele  und з реальных проекто in 

### E-commerce

```php
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{slug}', [ProductController::class, 'show']);
Route::post('/cart/add', [CartController::class, 'add']);
Route::patch('/cart/update/{id}', [CartController::class, 'update']);
Route::delete('/cart/remove/{id}', [CartController::class, 'remove']);
Route::post('/checkout', [CheckoutController::class, 'process']);
```

### Блог

```php
Route::get('/', [HomeController::class, 'index']);
Route::get('/posts', [PostController::class, 'index']);
Route::get('/posts/{slug}', [PostController::class, 'show']);
Route::post('/posts/{id}/comments', [CommentController::class, 'store']);
Route::match(['GET', 'POST'], '/contact', [ContactController::class, 'handle']);
```

### API

```php
Route::group(['prefix' => '/api/v1'], function() {
    Route::get('/users', [ApiUserController::class, 'index']);
    Route::post('/users', [ApiUserController::class, 'store']);
    Route::get('/users/{id}', [ApiUserController::class, 'show']);
    Route::put('/users/{id}', [ApiUserController::class, 'update']);
    Route::delete('/users/{id}', [ApiUserController::class, 'destroy']);
});
```

---

## Siehe auch

- [Параметры маршрутов](02_ROUTE_PARAMETERS.md)
- [Группы маршрутов](03_ROUTE_GROUPS.md)
- [Route Macros](11_ROUTE_MACROS.md) -  für  бы mit трого  mit оздан und я RESTful Routeо in 
- [Action Resolver](18_ACTION_RESOLVER.md) - форматы дей mit т in  und й

---

**Version:** 1.1.1  
**Дата обно in лен und я:** Октябрь 2025  
**Стату mit :** ✅ Стаб und ль auf я функц und о auf льно mit ть


---

## 📚 Dokumentationsnavigation

[README](../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [API_REFERENCE](../API_REFERENCE.md) | [ALL_FEATURES](../ALL_FEATURES.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [FAQ](../FAQ.md)

**Detaillierte Dokumentation:** [01](01_BASIC_ROUTING.md) | [02](02_ROUTE_PARAMETERS.md) | [03](03_ROUTE_GROUPS.md) | [04](04_RATE_LIMITING.md) | [05](05_IP_FILTERING.md) | [06](06_MIDDLEWARE.md) | [07](07_NAMED_ROUTES.md) | [08](08_TAGS.md) | [09](09_HELPER_FUNCTIONS.md) | [10](10_ROUTE_SHORTCUTS.md) | [11](11_ROUTE_MACROS.md) | [12](12_URL_GENERATION.md) | [13](13_EXPRESSION_LANGUAGE.md) | [14](14_CACHING.md) | [15](15_PLUGINS.md) | [16](16_LOADERS.md) | [17](17_PSR_SUPPORT.md) | [18](18_ACTION_RESOLVER.md) | [19](19_STATISTICS.md) | [20](20_SECURITY.md) | [21](21_EXCEPTIONS.md) | [22](22_CLI_TOOLS.md)

**© 2024 CloudCastle HTTP Router**
