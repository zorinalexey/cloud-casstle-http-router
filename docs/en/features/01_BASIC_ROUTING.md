# Basic Routing

**English** | [Русский](../../ru/features/01_BASIC_ROUTING.md) | [Deutsch](../../de/features/01_BASIC_ROUTING.md) | [Français](../../fr/features/01_BASIC_ROUTING.md) | [中文](../../zh/features/01_BASIC_ROUTING.md)

---







---

## 📚 Documentation Navigation

[README](../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [API_REFERENCE](../API_REFERENCE.md) | [ALL_FEATURES](../ALL_FEATURES.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [PERFORMANCE](../PERFORMANCE_ANALYSIS.md) | [SECURITY](../SECURITY_REPORT.md) | [COMPARISON](../COMPARISON.md) | [FAQ](../FAQ.md)

**Detailed documentation:** [01](01_BASIC_ROUTING.md) | [02](02_ROUTE_PARAMETERS.md) | [03](03_ROUTE_GROUPS.md) | [04](04_RATE_LIMITING.md) | [05](05_IP_FILTERING.md) | [06](06_MIDDLEWARE.md) | [07](07_NAMED_ROUTES.md) | [08](08_TAGS.md) | [09](09_HELPER_FUNCTIONS.md) | [10](10_ROUTE_SHORTCUTS.md) | [11](11_ROUTE_MACROS.md) | [12](12_URL_GENERATION.md) | [13](13_EXPRESSION_LANGUAGE.md) | [14](14_CACHING.md) | [15](15_PLUGINS.md) | [16](16_LOADERS.md) | [17](17_PSR_SUPPORT.md) | [18](18_ACTION_RESOLVER.md) | [19](19_STATISTICS.md) | [20](20_SECURITY.md) | [21](21_EXCEPTIONS.md) | [22](22_CLI_TOOLS.md)

---


**Category:** Core Features  
**Number of methods:** 13  
**Complexity:** ⭐ Beginner уро in ень

---

## Оп and  with ан and е

Basic Routing - это фундаменталь on я  in озможно with ть CloudCastle HTTP Router,  by з in оляющая рег and  with тр and ро in ать обработч and к and   for  разл and чных HTTP methods  and  URI.

## Features

### 1. GET route

**Method:** `Route::get(string $uri, mixed $action): Route`

**Оп and  with ан and е:** Рег and  with тр and рует route  for  HTTP GET requests.

**Parameters:**
- `$uri` - URI routeа ( on пр and мер, `/users`, `/posts/{id}`)
- `$action` - Action (Closure, ма with  with  and  in , line controllerа)

**Воз in ращает:** Объект `Route`  for  method chaining

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

**И with  by льзо in ан and е:**
- Getting данных ( with п and  with к and , детал and )
- Отображен and е  with тран and ц
- API энд by  and нты  for  чтен and я

---

### 2. POST route

**Method:** `Route::post(string $uri, mixed $action): Route`

**Оп and  with ан and е:** Рег and  with тр and рует route  for  HTTP POST requests.

**Parameters:**
- `$uri` - URI routeа
- `$action` - Action

**Воз in ращает:** Объект `Route`

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

**И with  by льзо in ан and е:**
- Создан and е но in ых ре with ур with о in 
- Отпра in ка форм
- API  with оздан and е данных

---

### 3. PUT route

**Method:** `Route::put(string $uri, mixed $action): Route`

**Оп and  with ан and е:** Рег and  with тр and рует route  for  HTTP PUT requests ( by лное обно in лен and е ре with ур with а).

**Parameters:**
- `$uri` - URI routeа (обычно  with  parameterом ID)
- `$action` - Action

**Воз in ращает:** Объект `Route`

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

**И with  by льзо in ан and е:**
- Полное обно in лен and е ре with ур with а
- RESTful API
- Заме on  allх  by лей объекта

---

### 4. PATCH route

**Method:** `Route::patch(string $uri, mixed $action): Route`

**Оп and  with ан and е:** Рег and  with тр and рует route  for  HTTP PATCH requests (ча with т and чное обно in лен and е ре with ур with а).

**Parameters:**
- `$uri` - URI routeа
- `$action` - Action

**Воз in ращает:** Объект `Route`

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

**И with  by льзо in ан and е:**
- Ча with т and чное обно in лен and е ре with ур with а
- Обно in лен and е отдельных  by лей
- API PATCH энд by  and нты

**Отл and ч and е от PUT:**
- PUT -  by л on я заме on  ре with ур with а
- PATCH - ча with т and чное обно in лен and е (только  and змененные  by ля)

---

### 5. DELETE route

**Method:** `Route::delete(string $uri, mixed $action): Route`

**Оп and  with ан and е:** Рег and  with тр and рует route  for  HTTP DELETE requests.

**Parameters:**
- `$uri` - URI routeа
- `$action` - Action

**Воз in ращает:** Объект `Route`

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

**И with  by льзо in ан and е:**
- Удален and е ре with ур with о in 
- RESTful API delete
- Оч and  with тка данных

---

### 6. VIEW route (custom method)

**Method:** `Route::view(string $uri, mixed $action): Route`

**Оп and  with ан and е:** Рег and  with тр and рует route  for  ка with томного HTTP methodа VIEW.

**Parameters:**
- `$uri` - URI routeа
- `$action` - Action

**Воз in ращает:** Объект `Route`

**Examples:**

```php
// Кастомный method VIEW для предпросмотра
Route::view('/preview', function() {
    return 'Preview content';
});

// Предпросмотр документа
Route::view('/documents/{id}/preview', [DocumentController::class, 'preview'])
    ->where('id', '[0-9]+');
```

**И with  by льзо in ан and е:**
- Спец and альные операц and  and  про with мотра
- Предпро with мотр контента
- Ка with томные HTTP methods

---

### 7. Custom HTTP method

**Method:** `Route::custom(string $method, string $uri, mixed $action): Route`

**Оп and  with ан and е:** Рег and  with тр and рует route  for  любого ка with томного HTTP methodа.

**Parameters:**
- `$method` - Наз in ан and е HTTP methodа (PURGE, TRACE, CONNECT,  and  т.д.)
- `$uri` - URI routeа
- `$action` - Action

**Воз in ращает:** Объект `Route`

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

// Любой кастомный method
Route::custom('COPY', '/files/{id}', [FileController::class, 'copy']);
Route::custom('MOVE', '/files/{id}', [FileController::class, 'move']);
```

**И with  by льзо in ан and е:**
- HTTP methods не  in ходящ and е  in   with тандартные (GET, POST, PUT, PATCH, DELETE)
- WebDAV methods (COPY, MOVE, PROPFIND)
- Кеш операц and  and  (PURGE)
- Спец and альные протоколы

---

### 8. Multiple HTTP methods (match)

**Method:** `Route::match(array $methods, string $uri, mixed $action): Route`

**Оп and  with ан and е:** Рег and  with тр and рует route  for  не with кольк and х HTTP methods.

**Parameters:**
- `$methods` - Ма with  with  and  in  HTTP methods
- `$uri` - URI routeа
- `$action` - Action

**Воз in ращает:** Объект `Route`

**Examples:**

```php
// GET и POST для формы
Route::match(['GET', 'POST'], '/contact', function() {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        return 'Show contact form';
    }
    return 'Process contact form';
});

// Multiple methods с контроллером
Route::match(['GET', 'POST'], '/form', [FormController::class, 'handle']);

// PUT и PATCH для обновления
Route::match(['PUT', 'PATCH'], '/users/{id}', [UserController::class, 'update']);

// С middleware
Route::match(['GET', 'POST', 'PUT'], '/api/resource', [ApiController::class, 'handle'])
    ->middleware([AuthMiddleware::class]);
```

**И with  by льзо in ан and е:**
- Формы (GET  for   by каза, POST  for  обработк and )
- Ун and  in ер with альные обработч and к and 
- Г and бкая Routing

---

### 9. All HTTP methods (any)

**Method:** `Route::any(string $uri, mixed $action): Route`

**Оп and  with ан and е:** Рег and  with тр and рует route  for  ВСЕХ HTTP methods.

**Parameters:**
- `$uri` - URI routeа
- `$action` - Action

**Воз in ращает:** Объект `Route`

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

**И with  by льзо in ан and е:**
- Webhooks от  with торонн and х  with ер in  and  with о in 
- Ун and  in ер with альные API энд by  and нты
- Отладка
- Прок with  and  обработч and к and 

---

### 10. Router instance API

**Method:** `new Router()`

**Оп and  with ан and е:** Создан and е экземпляра роутера  for  объектно-ор and ент and ро in анного API.

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
- Полный контроль  on д экземпляром
- Multiple роутеро in   in  одном пр and ложен and  and 
- Изоляц and я routeо in 

---

### 11. Singleton pattern

**Method:** `Router::getInstance(): Router`

**Оп and  with ан and е:** Getting ед and н with т in енного экземпляра роутера (Singleton).

**Examples:**

```php
use CloudCastle\Http\Router\Router;

// Get экземпляр
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

**И with  by льзо in ан and е:**
- Глобальный роутер пр and ложен and я
- До with туп  and з any ча with т and  кода
- Про with тота  and  with  by льзо in ан and я

---

### 12. Facade API

**Оп and  with ан and е:** Static interface  for  удобной работы  with  роутером.

**Examples:**

```php
use CloudCastle\Http\Router\Facade\Route;

// All methods доступны статически
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
- Кратк and й  with  and нтак with  and  with 
- Laravel- by добный API
- Про with тота  and  with  by льзо in ан and я

---

### 13. Стат and че with к and е methods Router

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

**Оп and  with ан and е:** Альтер on т and  in ный  with тат and че with к and й API без фа with ада.

**Examples:**

```php
use CloudCastle\Http\Router\Router;

// Статические methodы
Router::staticGet('/users', fn() => 'Users');
Router::staticPost('/users', fn() => 'Create');
Router::staticDelete('/users/{id}', fn($id) => "Delete: $id");

// Используют singleton экземпляр
$router = Router::getInstance();
```

---

## Паттерны  and  with  by льзо in ан and я

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
// Принимать любой method
Route::any('/webhooks/github', [WebhookController::class, 'github']);
Route::any('/webhooks/stripe', [WebhookController::class, 'stripe']);
```

---

## Рекомендац and  and 

### ✅ Хорош and е практ and к and 

1. **И with  by льзуйте пра in  and льный HTTP method**
   ```php
   // ✅ Правильно
   Route::get('/users', ...);      // Получение
   Route::post('/users', ...);     // Создание
   Route::put('/users/{id}', ...); // Полное обновление
   Route::patch('/users/{id}', ...); // Частичное обновление
   Route::delete('/users/{id}', ...); // Удаление
   ```

2. **И with  by льзуйте controllerы  for   with ложной лог and к and **
   ```php
   // ✅ Правильно
   Route::get('/users', [UserController::class, 'index']);
   
   // ❌ Неправильно
   Route::get('/users', function() {
       // 100 строк кода...
   });
   ```

3. **Групп and руйте  with  in язанные routes**
   ```php
   // ✅ Правильно
   Route::group(['prefix' => '/admin'], function() {
       Route::get('/users', [AdminUserController::class, 'index']);
       Route::get('/posts', [AdminPostController::class, 'index']);
   });
   ```

### ❌ Anti-patterns

1. **Не  and  with  by льзуйте GET  for   and зменен and я данных**
   ```php
   // ❌ Плохо
   Route::get('/delete-user/{id}', ...);
   
   // ✅ Хорошо
   Route::delete('/users/{id}', ...);
   ```

2. **Не дубл and руйте routes**
   ```php
   // ❌ Плохо
   Route::get('/users', ...);
   Route::get('/users', ...); // Дубликат!
   
   // ✅ Хорошо - используйте match
   Route::match(['GET', 'POST'], '/form', ...);
   ```

---

## Performance

| Операц and я | Время | Performance |
|----------|-------|-------------------|
| Рег and  with трац and я 1 routeа | ~3.4μs | 294,000 routes/sec |
| Рег and  with трац and я 1000 routeо in  | ~3.4ms | 294 routes/ms |
| По and  with к пер in ого routeа | ~123μs | 8,130 req/sec |

---

## Со in ме with т and мо with ть

- ✅ PHP 8.2+
- ✅ PHP 8.3
- ✅ PHP 8.4
- ✅ All  in еб- with ер in еры (Apache, Nginx, etc.)
- ✅ PSR-7/PSR-15  with о in ме with т and мо with ть

---

## Examples  and з реальных проекто in 

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

## See also

- [Параметры маршрутов](02_ROUTE_PARAMETERS.md)
- [Группы маршрутов](03_ROUTE_GROUPS.md)
- [Route Macros](11_ROUTE_MACROS.md) -  for  бы with трого  with оздан and я RESTful routeо in 
- [Action Resolver](18_ACTION_RESOLVER.md) - форматы дей with т in  and й

---

**Version:** 1.1.1  
**Дата обно in лен and я:** Октябрь 2025  
**Стату with :** ✅ Стаб and ль on я функц and о on льно with ть


---

## 📚 Documentation Navigation

[README](../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [API_REFERENCE](../API_REFERENCE.md) | [ALL_FEATURES](../ALL_FEATURES.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [FAQ](../FAQ.md)

**Detailed documentation:** [01](01_BASIC_ROUTING.md) | [02](02_ROUTE_PARAMETERS.md) | [03](03_ROUTE_GROUPS.md) | [04](04_RATE_LIMITING.md) | [05](05_IP_FILTERING.md) | [06](06_MIDDLEWARE.md) | [07](07_NAMED_ROUTES.md) | [08](08_TAGS.md) | [09](09_HELPER_FUNCTIONS.md) | [10](10_ROUTE_SHORTCUTS.md) | [11](11_ROUTE_MACROS.md) | [12](12_URL_GENERATION.md) | [13](13_EXPRESSION_LANGUAGE.md) | [14](14_CACHING.md) | [15](15_PLUGINS.md) | [16](16_LOADERS.md) | [17](17_PSR_SUPPORT.md) | [18](18_ACTION_RESOLVER.md) | [19](19_STATISTICS.md) | [20](20_SECURITY.md) | [21](21_EXCEPTIONS.md) | [22](22_CLI_TOOLS.md)

**© 2024 CloudCastle HTTP Router**
