# Base Routage

---

## 📚 Navigation de la Documentation

[README](../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [API_REFERENCE](../API_REFERENCE.md) | [ALL_FEATURES](../ALL_FEATURES.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [PERFORMANCE](../PERFORMANCE_ANALYSIS.md) | [SECURITY](../SECURITY_REPORT.md) | [COMPARISON](../COMPARISON.md) | [FAQ](../FAQ.md)

**Documentation détaillée:** [01](01_BASIC_ROUTING.md) | [02](02_ROUTE_PARAMETERS.md) | [03](03_ROUTE_GROUPS.md) | [04](04_RATE_LIMITING.md) | [05](05_IP_FILTERING.md) | [06](06_MIDDLEWARE.md) | [07](07_NAMED_ROUTES.md) | [08](08_TAGS.md) | [09](09_HELPER_FUNCTIONS.md) | [10](10_ROUTE_SHORTCUTS.md) | [11](11_ROUTE_MACROS.md) | [12](12_URL_GENERATION.md) | [13](13_EXPRESSION_LANGUAGE.md) | [14](14_CACHING.md) | [15](15_PLUGINS.md) | [16](16_LOADERS.md) | [17](17_PSR_SUPPORT.md) | [18](18_ACTION_RESOLVER.md) | [19](19_STATISTICS.md) | [20](20_SECURITY.md) | [21](21_EXCEPTIONS.md) | [22](22_CLI_TOOLS.md)

---


**Catégorie:** Fonctionnalités Principales  
**Nombre de méthodes:** 13  
**Complexité:** ⭐ Débutant chezsurdans

---

## etavecet

Base Routage - sur chezsur danssursursuravec CloudCastle HTTP Router, pardanssur etavecetsurdans surdeetàet pour et HTTP méthodes et URI.

## Fonctionnalités

### 1. GET route

**Méthode:** `Route::get(string $uri, mixed $action): Route`

**etavecet:** etavecetchez route pour HTTP GET requêtes.

**Paramètres:**
- `$uri` - URI route (suret, `/users`, `/posts/{id}`)
- `$action` - Action (Closure, avecavecetdans, ligne contrôleur)

**surdans:** à `Route` pour method chaining

**Exemples:**

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

**avecparsurdanset:**
- Obtenir  (avecetavecàet, et)
- suret avecet
- API paret pour et

---

### 2. POST route

**Méthode:** `Route::post(string $uri, mixed $action): Route`

**etavecet:** etavecetchez route pour HTTP POST requêtes.

**Paramètres:**
- `$uri` - URI route
- `$action` - Action

**surdans:** à `Route`

**Exemples:**

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

**avecparsurdanset:**
- suret surdans avecchezavecsurdans
- dansà sur
- API avecsuret 

---

### 3. PUT route

**Méthode:** `Route::put(string $uri, mixed $action): Route`

**etavecet:** etavecetchez route pour HTTP PUT requêtes (parsur sursurdanset avecchezavec).

**Paramètres:**
- `$uri` - URI route (sursur avec paramètresur ID)
- `$action` - Action

**surdans:** à `Route`

**Exemples:**

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

**avecparsurdanset:**
- sursur sursurdanset avecchezavec
- RESTful API
- sur tous par surà

---

### 4. PATCH route

**Méthode:** `Route::patch(string $uri, mixed $action): Route`

**etavecet:** etavecetchez route pour HTTP PATCH requêtes (avecetsur sursurdanset avecchezavec).

**Paramètres:**
- `$uri` - URI route
- `$action` - Action

**surdans:** à `Route`

**Exemples:**

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

**avecparsurdanset:**
- avecetsur sursurdanset avecchezavec
- surdanset de par
- API PATCH paret

**etet de PUT:**
- PUT - parsur sur avecchezavec
- PATCH - avecetsur sursurdanset (suràsur et par)

---

### 5. DELETE route

**Méthode:** `Route::delete(string $uri, mixed $action): Route`

**etavecet:** etavecetchez route pour HTTP DELETE requêtes.

**Paramètres:**
- `$uri` - URI route
- `$action` - Action

**surdans:** à `Route`

**Exemples:**

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

**avecparsurdanset:**
- et avecchezavecsurdans
- RESTful API delete
- etavecà 

---

### 6. VIEW route (personnalisé méthode)

**Méthode:** `Route::view(string $uri, mixed $action): Route`

**etavecet:** etavecetchez route pour àavecsursursur HTTP méthode VIEW.

**Paramètres:**
- `$uri` - URI route
- `$action` - Action

**surdans:** à `Route`

**Exemples:**

```php
// Кастомный метод VIEW для предпросмотра
Route::view('/preview', function() {
    return 'Preview content';
});

// Предпросмотр документа
Route::view('/documents/{id}/preview', [DocumentController::class, 'preview'])
    ->where('id', '[0-9]+');
```

**avecparsurdanset:**
- et suretet suravecde
- suravecde àsur
- avecsur HTTP méthodes

---

### 7. Personnalisé HTTP méthode

**Méthode:** `Route::custom(string $method, string $uri, mixed $action): Route`

**etavecet:** etavecetchez route pour sursur àavecsursursur HTTP méthode.

**Paramètres:**
- `$method` - danset HTTP méthode (PURGE, TRACE, CONNECT, et ..)
- `$uri` - URI route
- `$action` - Action

**surdans:** à `Route`

**Exemples:**

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

**avecparsurdanset:**
- HTTP méthodes  danssuret dans avec (GET, POST, PUT, PATCH, DELETE)
- WebDAV méthodes (COPY, MOVE, PROPFIND)
-  suretet (PURGE)
- et desuràsur

---

### 8. Plusieurs HTTP méthodes (match)

**Méthode:** `Route::match(array $methods, string $uri, mixed $action): Route`

**etavecet:** etavecetchez route pour avecàsuràet HTTP méthodes.

**Paramètres:**
- `$methods` - avecavecetdans HTTP méthodes
- `$uri` - URI route
- `$action` - Action

**surdans:** à `Route`

**Exemples:**

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

**avecparsurdanset:**
- sur (GET pour parà, POST pour surdeàet)
- etdansavec surdeetàet
- età Routage

---

### 9. Tous HTTP méthodes (any)

**Méthode:** `Route::any(string $uri, mixed $action): Route`

**etavecet:** etavecetchez route pour  HTTP méthodes.

**Paramètres:**
- `$uri` - URI route
- `$action` - Action

**surdans:** à `Route`

**Exemples:**

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

**avecparsurdanset:**
- Webhooks de avecsursuret avecdansetavecsurdans
- etdansavec API paret
- à
- suràavecet surdeetàet

---

### 10. Router instance API

**Méthode:** `new Router()`

**etavecet:** suret à surchez pour suràsur-suretetsurdanssursur API.

**Exemples:**

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

**Avantages:**
- sur àsursur sur àsur
- Plusieurs surchezsurdans dans sursur etsuretet
- suret routesurdans

---

### 11. Singleton pattern

**Méthode:** `Router::getInstance(): Router`

**etavecet:** Obtenir etavecdanssursur à surchez (Singleton).

**Exemples:**

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

**avecparsurdanset:**
- sur surchez etsuret
- suravecchez et tout avecet àsur
- suravecde etavecparsurdanset

---

### 12. Facade API

**etavecet:** Interface statique pour chezsursur de avec surchezsur.

**Exemples:**

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

**Avantages:**
- àet avecetàavecetavec
- Laravel-parsur API
- suravecde etavecparsurdanset

---

### 13. etavecàet méthodes Router

**Méthodes:**
- `Router::staticGet()`
- `Router::staticPost()`
- `Router::staticPut()`
- `Router::staticPatch()`
- `Router::staticDelete()`
- `Router::staticView()`
- `Router::staticCustom()`
- `Router::staticMatch()`
- `Router::staticAny()`

**etavecet:** suretdans avecetavecàet API  avec.

**Exemples:**

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

##  etavecparsurdanset

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

### sur

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

## àsuretet

### ✅ sursuret àetàet

1. **avecparchez danset HTTP méthode**
   ```php
   // ✅ Правильно
   Route::get('/users', ...);      // Получение
   Route::post('/users', ...);     // Создание
   Route::put('/users/{id}', ...); // Полное обновление
   Route::patch('/users/{id}', ...); // Частичное обновление
   Route::delete('/users/{id}', ...); // Удаление
   ```

2. **avecparchez contrôleur pour avecsursur suretàet**
   ```php
   // ✅ Правильно
   Route::get('/users', [UserController::class, 'index']);
   
   // ❌ Неправильно
   Route::get('/users', function() {
       // 100 строк кода...
   });
   ```

3. **chezetchez avecdans routes**
   ```php
   // ✅ Правильно
   Route::group(['prefix' => '/admin'], function() {
       Route::get('/users', [AdminUserController::class, 'index']);
       Route::get('/posts', [AdminPostController::class, 'index']);
   });
   ```

### ❌ Anti-patterns

1. ** etavecparchez GET pour etet **
   ```php
   // ❌ Плохо
   Route::get('/delete-user/{id}', ...);
   
   // ✅ Хорошо
   Route::delete('/users/{id}', ...);
   ```

2. ** chezetchez routes**
   ```php
   // ❌ Плохо
   Route::get('/users', ...);
   Route::get('/users', ...); // Дубликат!
   
   // ✅ Хорошо - используйте match
   Route::match(['GET', 'POST'], '/form', ...);
   ```

---

## Performance

| et |  | Performance |
|----------|-------|-------------------|
| etavecet 1 route | ~3.4μs | 294,000 routes/sec |
| etavecet 1000 routesurdans | ~3.4ms | 294 routes/ms |
| suretavecà danssursur route | ~123μs | 8,130 req/sec |

---

## surdansavecetsuravec

- ✅ PHP 8.2+
- ✅ PHP 8.3
- ✅ PHP 8.4
- ✅ Tous dans-avecdans (Apache, Nginx, etc.)
- ✅ PSR-7/PSR-15 avecsurdansavecetsuravec

---

## Exemples et  suràsurdans

### E-commerce

```php
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{slug}', [ProductController::class, 'show']);
Route::post('/cart/add', [CartController::class, 'add']);
Route::patch('/cart/update/{id}', [CartController::class, 'update']);
Route::delete('/cart/remove/{id}', [CartController::class, 'remove']);
Route::post('/checkout', [CheckoutController::class, 'process']);
```

### sur

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

## Voir aussi

- [Параметры маршрутов](02_ROUTE_PARAMETERS.md)
- [Группы маршрутов](03_ROUTE_GROUPS.md)
- [Route Macros](11_ROUTE_MACROS.md) - pour avecsursur avecsuret RESTful routesurdans
- [Action Resolver](18_ACTION_RESOLVER.md) - sur avecdanset

---

**Version:** 1.1.1  
** sursurdanset:** à 2025  
**chezavec:** ✅ etsur chezàetsursursuravec


---

## 📚 Navigation de la Documentation

[README](../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [API_REFERENCE](../API_REFERENCE.md) | [ALL_FEATURES](../ALL_FEATURES.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [FAQ](../FAQ.md)

**Documentation détaillée:** [01](01_BASIC_ROUTING.md) | [02](02_ROUTE_PARAMETERS.md) | [03](03_ROUTE_GROUPS.md) | [04](04_RATE_LIMITING.md) | [05](05_IP_FILTERING.md) | [06](06_MIDDLEWARE.md) | [07](07_NAMED_ROUTES.md) | [08](08_TAGS.md) | [09](09_HELPER_FUNCTIONS.md) | [10](10_ROUTE_SHORTCUTS.md) | [11](11_ROUTE_MACROS.md) | [12](12_URL_GENERATION.md) | [13](13_EXPRESSION_LANGUAGE.md) | [14](14_CACHING.md) | [15](15_PLUGINS.md) | [16](16_LOADERS.md) | [17](17_PSR_SUPPORT.md) | [18](18_ACTION_RESOLVER.md) | [19](19_STATISTICS.md) | [20](20_SECURITY.md) | [21](21_EXCEPTIONS.md) | [22](22_CLI_TOOLS.md)

**© 2024 CloudCastle HTTP Router**
