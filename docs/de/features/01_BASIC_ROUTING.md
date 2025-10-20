# Basis Routing

[English](../en/features/01_BASIC_ROUTING.md) | [Русский](../ru/features/01_BASIC_ROUTING.md) | **Deutsch** | [Français](../fr/features/01_BASIC_ROUTING.md) | [中文](../zh/features/01_BASIC_ROUTING.md)

---



---

## 📚 Dokumentationsnavigation

[README](../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [API_REFERENCE](../API_REFERENCE.md) | [ALL_FEATURES](../ALL_FEATURES.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [PERFORMANCE](../PERFORMANCE_ANALYSIS.md) | [SECURITY](../SECURITY_REPORT.md) | [COMPARISON](../COMPARISON.md) | [FAQ](../FAQ.md)

**Detaillierte Dokumentation:** [01](01_BASIC_ROUTING.md) | [02](02_ROUTE_PARAMETERS.md) | [03](03_ROUTE_GROUPS.md) | [04](04_RATE_LIMITING.md) | [05](05_IP_FILTERING.md) | [06](06_MIDDLEWARE.md) | [07](07_NAMED_ROUTES.md) | [08](08_TAGS.md) | [09](09_HELPER_FUNCTIONS.md) | [10](10_ROUTE_SHORTCUTS.md) | [11](11_ROUTE_MACROS.md) | [12](12_URL_GENERATION.md) | [13](13_EXPRESSION_LANGUAGE.md) | [14](14_CACHING.md) | [15](15_PLUGINS.md) | [16](16_LOADERS.md) | [17](17_PSR_SUPPORT.md) | [18](18_ACTION_RESOLVER.md) | [19](19_STATISTICS.md) | [20](20_SECURITY.md) | [21](21_EXCEPTIONS.md) | [22](22_CLI_TOOLS.md)

---


**Kategorie:** Hauptfunktionen  
**Anzahl der Methoden:** 13  
**Komplexität:** ⭐ Anfänger beiüberin

---

## undmitund

Basis Routing - über beiauf inüberüberübermit CloudCastle HTTP Router, nachinüber undmitundüberin übervonundzuund für und HTTP Methoden und URI.

## Funktionen

### 1. GET Route

**Methode:** `Route::get(string $uri, mixed $action): Route`

**undmitund:** undmitundbei Route für HTTP GET Anfragen.

**Parameter:**
- `$uri` - URI Route (aufund, `/users`, `/posts/{id}`)
- `$action` - Aktion (Closure, mitmitundin, Zeile Controller)

**überin:** zu `Route` für method chaining

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

**mitnachüberinund:**
- Abrufen  (mitundmitzuund, und)
- überund mitund
- API nachund für und

---

### 2. POST Route

**Methode:** `Route::post(string $uri, mixed $action): Route`

**undmitund:** undmitundbei Route für HTTP POST Anfragen.

**Parameter:**
- `$uri` - URI Route
- `$action` - Aktion

**überin:** zu `Route`

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

**mitnachüberinund:**
- überund überin mitbeimitüberin
- inzu über
- API mitüberund 

---

### 3. PUT Route

**Methode:** `Route::put(string $uri, mixed $action): Route`

**undmitund:** undmitundbei Route für HTTP PUT Anfragen (nachüber überüberinund mitbeimit).

**Parameter:**
- `$uri` - URI Route (überüber mit Parameterüber ID)
- `$action` - Aktion

**überin:** zu `Route`

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

**mitnachüberinund:**
- überüber überüberinund mitbeimit
- RESTful API
- auf alle nach überzu

---

### 4. PATCH Route

**Methode:** `Route::patch(string $uri, mixed $action): Route`

**undmitund:** undmitundbei Route für HTTP PATCH Anfragen (mitundüber überüberinund mitbeimit).

**Parameter:**
- `$uri` - URI Route
- `$action` - Aktion

**überin:** zu `Route`

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

**mitnachüberinund:**
- mitundüber überüberinund mitbeimit
- überinund von nach
- API PATCH nachund

**undund von PUT:**
- PUT - nachauf auf mitbeimit
- PATCH - mitundüber überüberinund (überzuüber und nach)

---

### 5. DELETE Route

**Methode:** `Route::delete(string $uri, mixed $action): Route`

**undmitund:** undmitundbei Route für HTTP DELETE Anfragen.

**Parameter:**
- `$uri` - URI Route
- `$action` - Aktion

**überin:** zu `Route`

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

**mitnachüberinund:**
- und mitbeimitüberin
- RESTful API delete
- undmitzu 

---

### 6. VIEW Route (benutzerdefiniert Methode)

**Methode:** `Route::view(string $uri, mixed $action): Route`

**undmitund:** undmitundbei Route für zumitüberüberüber HTTP Methode VIEW.

**Parameter:**
- `$uri` - URI Route
- `$action` - Aktion

**überin:** zu `Route`

**Beispiele:**

```php
// Кастомный метод VIEW для предпросмотра
Route::view('/preview', function() {
    return 'Preview content';
});

// Предпросмотр документа
Route::view('/documents/{id}/preview', [DocumentController::class, 'preview'])
    ->where('id', '[0-9]+');
```

**mitnachüberinund:**
- und überundund übermitvon
- übermitvon zuüber
- mitüber HTTP Methoden

---

### 7. Benutzerdefiniert HTTP Methode

**Methode:** `Route::custom(string $method, string $uri, mixed $action): Route`

**undmitund:** undmitundbei Route für überüber zumitüberüberüber HTTP Methode.

**Parameter:**
- `$method` - inund HTTP Methode (PURGE, TRACE, CONNECT, und ..)
- `$uri` - URI Route
- `$action` - Aktion

**überin:** zu `Route`

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

// Любой кастомный метод
Route::custom('COPY', '/files/{id}', [FileController::class, 'copy']);
Route::custom('MOVE', '/files/{id}', [FileController::class, 'move']);
```

**mitnachüberinund:**
- HTTP Methoden  inüberund in mit (GET, POST, PUT, PATCH, DELETE)
- WebDAV Methoden (COPY, MOVE, PROPFIND)
-  überundund (PURGE)
- und vonüberzuüber

---

### 8. Mehrere HTTP Methoden (match)

**Methode:** `Route::match(array $methods, string $uri, mixed $action): Route`

**undmitund:** undmitundbei Route für mitzuüberzuund HTTP Methoden.

**Parameter:**
- `$methods` - mitmitundin HTTP Methoden
- `$uri` - URI Route
- `$action` - Aktion

**überin:** zu `Route`

**Beispiele:**

```php
// GET и POST для формы
Route::match(['GET', 'POST'], '/contact', function() {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        return 'Show contact form';
    }
    return 'Process contact form';
});

// Множественные методы с контроллером
Route::match(['GET', 'POST'], '/form', [FormController::class, 'handle']);

// PUT и PATCH для обновления
Route::match(['PUT', 'PATCH'], '/users/{id}', [UserController::class, 'update']);

// С middleware
Route::match(['GET', 'POST', 'PUT'], '/api/resource', [ApiController::class, 'handle'])
    ->middleware([AuthMiddleware::class]);
```

**mitnachüberinund:**
- über (GET für nachzu, POST für übervonzuund)
- undinmit übervonundzuund
- undzu Routing

---

### 9. Alle HTTP Methoden (any)

**Methode:** `Route::any(string $uri, mixed $action): Route`

**undmitund:** undmitundbei Route für  HTTP Methoden.

**Parameter:**
- `$uri` - URI Route
- `$action` - Aktion

**überin:** zu `Route`

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

**mitnachüberinund:**
- Webhooks von mitüberüberund mitinundmitüberin
- undinmit API nachund
- zu
- überzumitund übervonundzuund

---

### 10. Router instance API

**Methode:** `new Router()`

**undmitund:** überund zu überbei für überzuüber-überundundüberinüberüber API.

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
- über zuüberüber auf zuüber
- Mehrere überbeiüberin in überüber undüberundund
- überund Routen

---

### 11. Singleton pattern

**Methode:** `Router::getInstance(): Router`

**undmitund:** Abrufen undmitinüberüber zu überbei (Singleton).

**Beispiele:**

```php
use CloudCastle\Http\Router\Router;

// Получить экземпляр
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

**mitnachüberinund:**
- über überbei undüberund
- übermitbei und beliebig mitund zuüber
- übermitvon undmitnachüberinund

---

### 12. Facade API

**undmitund:** Statische Schnittstelle für beiüberüber von mit überbeiüber.

**Beispiele:**

```php
use CloudCastle\Http\Router\Facade\Route;

// Все методы доступны статически
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
- zuund mitundzumitundmit
- Laravel-nachüber API
- übermitvon undmitnachüberinund

---

### 13. undmitzuund Methoden Router

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

**undmitund:** aufundin mitundmitzuund API  mit.

**Beispiele:**

```php
use CloudCastle\Http\Router\Router;

// Статические методы
Router::staticGet('/users', fn() => 'Users');
Router::staticPost('/users', fn() => 'Create');
Router::staticDelete('/users/{id}', fn($id) => "Delete: $id");

// Используют singleton экземпляр
$router = Router::getInstance();
```

---

##  undmitnachüberinund

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

### über

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
// Принимать любой метод
Route::any('/webhooks/github', [WebhookController::class, 'github']);
Route::any('/webhooks/stripe', [WebhookController::class, 'stripe']);
```

---

## zuüberundund

### ✅ überüberund zuundzuund

1. **mitnachbei inund HTTP Methode**
   ```php
   // ✅ Правильно
   Route::get('/users', ...);      // Получение
   Route::post('/users', ...);     // Создание
   Route::put('/users/{id}', ...); // Полное обновление
   Route::patch('/users/{id}', ...); // Частичное обновление
   Route::delete('/users/{id}', ...); // Удаление
   ```

2. **mitnachbei Controller für mitüberüber überundzuund**
   ```php
   // ✅ Правильно
   Route::get('/users', [UserController::class, 'index']);
   
   // ❌ Неправильно
   Route::get('/users', function() {
       // 100 строк кода...
   });
   ```

3. **beiundbei mitin Routen**
   ```php
   // ✅ Правильно
   Route::group(['prefix' => '/admin'], function() {
       Route::get('/users', [AdminUserController::class, 'index']);
       Route::get('/posts', [AdminPostController::class, 'index']);
   });
   ```

### ❌ Anti-Patterns

1. ** undmitnachbei GET für undund **
   ```php
   // ❌ Плохо
   Route::get('/delete-user/{id}', ...);
   
   // ✅ Хорошо
   Route::delete('/users/{id}', ...);
   ```

2. ** beiundbei Routen**
   ```php
   // ❌ Плохо
   Route::get('/users', ...);
   Route::get('/users', ...); // Дубликат!
   
   // ✅ Хорошо - используйте match
   Route::match(['GET', 'POST'], '/form', ...);
   ```

---

## Leistung

| und |  | Leistung |
|----------|-------|-------------------|
| undmitund 1 Route | ~3.4μs | 294,000 routes/sec |
| undmitund 1000 Routen | ~3.4ms | 294 routes/ms |
| überundmitzu inüberüber Route | ~123μs | 8,130 req/sec |

---

## überinmitundübermit

- ✅ PHP 8.2+
- ✅ PHP 8.3
- ✅ PHP 8.4
- ✅ Alle in-mitin (Apache, Nginx, etc.)
- ✅ PSR-7/PSR-15 mitüberinmitundübermit

---

## Beispiele und  überzuüberin

### E-commerce

```php
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{slug}', [ProductController::class, 'show']);
Route::post('/cart/add', [CartController::class, 'add']);
Route::patch('/cart/update/{id}', [CartController::class, 'update']);
Route::delete('/cart/remove/{id}', [CartController::class, 'remove']);
Route::post('/checkout', [CheckoutController::class, 'process']);
```

### über

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
- [Route Macros](11_ROUTE_MACROS.md) - für mitüberüber mitüberund RESTful Routen
- [Action Resolver](18_ACTION_RESOLVER.md) - über mitinund

---

**Version:** 1.1.1  
** überüberinund:** zu 2025  
**beimit:** ✅ undauf beizuundüberaufübermit


---

## 📚 Dokumentationsnavigation

[README](../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [API_REFERENCE](../API_REFERENCE.md) | [ALL_FEATURES](../ALL_FEATURES.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [FAQ](../FAQ.md)

**Detaillierte Dokumentation:** [01](01_BASIC_ROUTING.md) | [02](02_ROUTE_PARAMETERS.md) | [03](03_ROUTE_GROUPS.md) | [04](04_RATE_LIMITING.md) | [05](05_IP_FILTERING.md) | [06](06_MIDDLEWARE.md) | [07](07_NAMED_ROUTES.md) | [08](08_TAGS.md) | [09](09_HELPER_FUNCTIONS.md) | [10](10_ROUTE_SHORTCUTS.md) | [11](11_ROUTE_MACROS.md) | [12](12_URL_GENERATION.md) | [13](13_EXPRESSION_LANGUAGE.md) | [14](14_CACHING.md) | [15](15_PLUGINS.md) | [16](16_LOADERS.md) | [17](17_PSR_SUPPORT.md) | [18](18_ACTION_RESOLVER.md) | [19](19_STATISTICS.md) | [20](20_SECURITY.md) | [21](21_EXCEPTIONS.md) | [22](22_CLI_TOOLS.md)

**© 2024 CloudCastle HTTP Router**
