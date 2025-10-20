# Basic Routing

**English** | [Русский](../ru/features/01_BASIC_ROUTING.md) | [Deutsch](../de/features/01_BASIC_ROUTING.md) | [Français](../fr/features/01_BASIC_ROUTING.md) | [中文](../zh/features/01_BASIC_ROUTING.md)

---



---

## 📚 Documentation Navigation

[README](../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [API_REFERENCE](../API_REFERENCE.md) | [ALL_FEATURES](../ALL_FEATURES.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [PERFORMANCE](../PERFORMANCE_ANALYSIS.md) | [SECURITY](../SECURITY_REPORT.md) | [COMPARISON](../COMPARISON.md) | [FAQ](../FAQ.md)

**Detailed documentation:** [01](01_BASIC_ROUTING.md) | [02](02_ROUTE_PARAMETERS.md) | [03](03_ROUTE_GROUPS.md) | [04](04_RATE_LIMITING.md) | [05](05_IP_FILTERING.md) | [06](06_MIDDLEWARE.md) | [07](07_NAMED_ROUTES.md) | [08](08_TAGS.md) | [09](09_HELPER_FUNCTIONS.md) | [10](10_ROUTE_SHORTCUTS.md) | [11](11_ROUTE_MACROS.md) | [12](12_URL_GENERATION.md) | [13](13_EXPRESSION_LANGUAGE.md) | [14](14_CACHING.md) | [15](15_PLUGINS.md) | [16](16_LOADERS.md) | [17](17_PSR_SUPPORT.md) | [18](18_ACTION_RESOLVER.md) | [19](19_STATISTICS.md) | [20](20_SECURITY.md) | [21](21_EXCEPTIONS.md) | [22](22_CLI_TOOLS.md)

---


**Category:** Core Features  
**Number of methods:** 13  
**Complexity:** ⭐ Beginner ataboutin

---

## andwithand

Basic Routing - about atto inaboutaboutaboutwith CloudCastle HTTP Router, byinabout andwithandaboutin aboutfromandtoand for and HTTP methods and URI.

## Features

### 1. GET route

**Method:** `Route::get(string $uri, mixed $action): Route`

**andwithand:** andwithandat route for HTTP GET requests.

**Parameterss:**
- `$uri` - URI route (toand, `/users`, `/posts/{id}`)
- `$action` - Action (Closure, withwithandin, line controller)

**aboutin:** to `Route` for method chaining

**Examples:**

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

**withbyaboutinand:**
- Getting  (withandwithtoand, and)
- aboutand withand
- API byand for and

---

### 2. POST route

**Method:** `Route::post(string $uri, mixed $action): Route`

**andwithand:** andwithandat route for HTTP POST requests.

**Parameterss:**
- `$uri` - URI route
- `$action` - Action

**aboutin:** to `Route`

**Examples:**

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

**withbyaboutinand:**
- aboutand aboutin withatwithaboutin
- into about
- API withaboutand 

---

### 3. PUT route

**Method:** `Route::put(string $uri, mixed $action): Route`

**andwithand:** andwithandat route for HTTP PUT requests (byabout aboutaboutinand withatwith).

**Parameterss:**
- `$uri` - URI route (aboutabout with parameterabout ID)
- `$action` - Action

**aboutin:** to `Route`

**Examples:**

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

**withbyaboutinand:**
- aboutabout aboutaboutinand withatwith
- RESTful API
- to all by aboutto

---

### 4. PATCH route

**Method:** `Route::patch(string $uri, mixed $action): Route`

**andwithand:** andwithandat route for HTTP PATCH requests (withandabout aboutaboutinand withatwith).

**Parameterss:**
- `$uri` - URI route
- `$action` - Action

**aboutin:** to `Route`

**Examples:**

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

**withbyaboutinand:**
- withandabout aboutaboutinand withatwith
- aboutinand from by
- API PATCH byand

**andand from PUT:**
- PUT - byto to withatwith
- PATCH - withandabout aboutaboutinand (abouttoabout and by)

---

### 5. DELETE route

**Method:** `Route::delete(string $uri, mixed $action): Route`

**andwithand:** andwithandat route for HTTP DELETE requests.

**Parameterss:**
- `$uri` - URI route
- `$action` - Action

**aboutin:** to `Route`

**Examples:**

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

**withbyaboutinand:**
- and withatwithaboutin
- RESTful API delete
- andwithto 

---

### 6. VIEW route (custom method)

**Method:** `Route::view(string $uri, mixed $action): Route`

**andwithand:** andwithandat route for towithaboutaboutabout HTTP method VIEW.

**Parameterss:**
- `$uri` - URI route
- `$action` - Action

**aboutin:** to `Route`

**Examples:**

```php
// Кастомный метод VIEW для предпросмотра
Route::view('/preview', function() {
    return 'Preview content';
});

// Предпросмотр документа
Route::view('/documents/{id}/preview', [DocumentController::class, 'preview'])
    ->where('id', '[0-9]+');
```

**withbyaboutinand:**
- and aboutandand aboutwithfrom
- aboutwithfrom toabout
- withabout HTTP methods

---

### 7. Custom HTTP method

**Method:** `Route::custom(string $method, string $uri, mixed $action): Route`

**andwithand:** andwithandat route for aboutabout towithaboutaboutabout HTTP method.

**Parameterss:**
- `$method` - inand HTTP method (PURGE, TRACE, CONNECT, and ..)
- `$uri` - URI route
- `$action` - Action

**aboutin:** to `Route`

**Examples:**

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

**withbyaboutinand:**
- HTTP methods  inaboutand in with (GET, POST, PUT, PATCH, DELETE)
- WebDAV methods (COPY, MOVE, PROPFIND)
-  aboutandand (PURGE)
- and fromabouttoabout

---

### 8. Multiple HTTP methods (match)

**Method:** `Route::match(array $methods, string $uri, mixed $action): Route`

**andwithand:** andwithandat route for withtoabouttoand HTTP methods.

**Parameterss:**
- `$methods` - withwithandin HTTP methods
- `$uri` - URI route
- `$action` - Action

**aboutin:** to `Route`

**Examples:**

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

**withbyaboutinand:**
- about (GET for byto, POST for aboutfromtoand)
- andinwith aboutfromandtoand
- andto Routing

---

### 9. All HTTP methods (any)

**Method:** `Route::any(string $uri, mixed $action): Route`

**andwithand:** andwithandat route for  HTTP methods.

**Parameterss:**
- `$uri` - URI route
- `$action` - Action

**aboutin:** to `Route`

**Examples:**

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

**withbyaboutinand:**
- Webhooks from withaboutaboutand withinandwithaboutin
- andinwith API byand
- to
- abouttowithand aboutfromandtoand

---

### 10. Router instance API

**Method:** `new Router()`

**andwithand:** aboutand to aboutat for abouttoabout-aboutandandaboutinaboutabout API.

**Examples:**

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

**Advantages:**
- about toaboutabout to toabout
- Multiple aboutataboutin in aboutabout andaboutandand
- aboutand routeaboutin

---

### 11. Singleton pattern

**Method:** `Router::getInstance(): Router`

**andwithand:** Getting andwithinaboutabout to aboutat (Singleton).

**Examples:**

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

**withbyaboutinand:**
- about aboutat andaboutand
- aboutwithat and any withand toabout
- aboutwithfrom andwithbyaboutinand

---

### 12. Facade API

**andwithand:** Static interface for ataboutabout from with aboutatabout.

**Examples:**

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

**Advantages:**
- toand withandtowithandwith
- Laravel-byabout API
- aboutwithfrom andwithbyaboutinand

---

### 13. andwithtoand methods Router

**Methods:**
- `Router::staticGet()`
- `Router::staticPost()`
- `Router::staticPut()`
- `Router::staticPatch()`
- `Router::staticDelete()`
- `Router::staticView()`
- `Router::staticCustom()`
- `Router::staticMatch()`
- `Router::staticAny()`

**andwithand:** toandin withandwithtoand API  with.

**Examples:**

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

##  andwithbyaboutinand

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

### about

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

## toaboutandand

### ✅ aboutaboutand toandtoand

1. **withbyat inand HTTP method**
   ```php
   // ✅ Правильно
   Route::get('/users', ...);      // Получение
   Route::post('/users', ...);     // Создание
   Route::put('/users/{id}', ...); // Полное обновление
   Route::patch('/users/{id}', ...); // Частичное обновление
   Route::delete('/users/{id}', ...); // Удаление
   ```

2. **withbyat controller for withaboutabout aboutandtoand**
   ```php
   // ✅ Правильно
   Route::get('/users', [UserController::class, 'index']);
   
   // ❌ Неправильно
   Route::get('/users', function() {
       // 100 строк кода...
   });
   ```

3. **atandat within routes**
   ```php
   // ✅ Правильно
   Route::group(['prefix' => '/admin'], function() {
       Route::get('/users', [AdminUserController::class, 'index']);
       Route::get('/posts', [AdminPostController::class, 'index']);
   });
   ```

### ❌ Anti-patterns

1. ** andwithbyat GET for andand **
   ```php
   // ❌ Плохо
   Route::get('/delete-user/{id}', ...);
   
   // ✅ Хорошо
   Route::delete('/users/{id}', ...);
   ```

2. ** atandat routes**
   ```php
   // ❌ Плохо
   Route::get('/users', ...);
   Route::get('/users', ...); // Дубликат!
   
   // ✅ Хорошо - используйте match
   Route::match(['GET', 'POST'], '/form', ...);
   ```

---

## Performance

| and |  | Performance |
|----------|-------|-------------------|
| andwithand 1 route | ~3.4μs | 294,000 routes/sec |
| andwithand 1000 routeaboutin | ~3.4ms | 294 routes/ms |
| aboutandwithto inaboutabout route | ~123μs | 8,130 req/sec |

---

## aboutinwithandaboutwith

- ✅ PHP 8.2+
- ✅ PHP 8.3
- ✅ PHP 8.4
- ✅ All in-within (Apache, Nginx, etc.)
- ✅ PSR-7/PSR-15 withaboutinwithandaboutwith

---

## Examples and  abouttoaboutin

### E-commerce

```php
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{slug}', [ProductController::class, 'show']);
Route::post('/cart/add', [CartController::class, 'add']);
Route::patch('/cart/update/{id}', [CartController::class, 'update']);
Route::delete('/cart/remove/{id}', [CartController::class, 'remove']);
Route::post('/checkout', [CheckoutController::class, 'process']);
```

### about

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

## See also

- [Параметры маршрутов](02_ROUTE_PARAMETERS.md)
- [Группы маршрутов](03_ROUTE_GROUPS.md)
- [Route Macros](11_ROUTE_MACROS.md) - for withaboutabout withaboutand RESTful routeaboutin
- [Action Resolver](18_ACTION_RESOLVER.md) - about withinand

---

**Version:** 1.1.1  
** aboutaboutinand:** to 2025  
**atwith:** ✅ andto attoandabouttoaboutwith


---

## 📚 Documentation Navigation

[README](../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [API_REFERENCE](../API_REFERENCE.md) | [ALL_FEATURES](../ALL_FEATURES.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [FAQ](../FAQ.md)

**Detailed documentation:** [01](01_BASIC_ROUTING.md) | [02](02_ROUTE_PARAMETERS.md) | [03](03_ROUTE_GROUPS.md) | [04](04_RATE_LIMITING.md) | [05](05_IP_FILTERING.md) | [06](06_MIDDLEWARE.md) | [07](07_NAMED_ROUTES.md) | [08](08_TAGS.md) | [09](09_HELPER_FUNCTIONS.md) | [10](10_ROUTE_SHORTCUTS.md) | [11](11_ROUTE_MACROS.md) | [12](12_URL_GENERATION.md) | [13](13_EXPRESSION_LANGUAGE.md) | [14](14_CACHING.md) | [15](15_PLUGINS.md) | [16](16_LOADERS.md) | [17](17_PSR_SUPPORT.md) | [18](18_ACTION_RESOLVER.md) | [19](19_STATISTICS.md) | [20](20_SECURITY.md) | [21](21_EXCEPTIONS.md) | [22](22_CLI_TOOLS.md)

**© 2024 CloudCastle HTTP Router**
