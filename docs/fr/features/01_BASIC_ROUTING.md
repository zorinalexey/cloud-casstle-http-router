# Base Routage

[English](../../en/features/01_BASIC_ROUTING.md) | [Русский](../../ru/features/01_BASIC_ROUTING.md) | [Deutsch](../../de/features/01_BASIC_ROUTING.md) | **Français** | [中文](../../zh/features/01_BASIC_ROUTING.md)

---







---

## 📚 Navigation de la Documentation

[README](../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [API_REFERENCE](../API_REFERENCE.md) | [ALL_FEATURES](../ALL_FEATURES.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [PERFORMANCE](../PERFORMANCE_ANALYSIS.md) | [SECURITY](../SECURITY_REPORT.md) | [COMPARISON](../COMPARISON.md) | [FAQ](../FAQ.md)

**Documentation détaillée:** [01](01_BASIC_ROUTING.md) | [02](02_ROUTE_PARAMETERS.md) | [03](03_ROUTE_GROUPS.md) | [04](04_RATE_LIMITING.md) | [05](05_IP_FILTERING.md) | [06](06_MIDDLEWARE.md) | [07](07_NAMED_ROUTES.md) | [08](08_TAGS.md) | [09](09_HELPER_FUNCTIONS.md) | [10](10_ROUTE_SHORTCUTS.md) | [11](11_ROUTE_MACROS.md) | [12](12_URL_GENERATION.md) | [13](13_EXPRESSION_LANGUAGE.md) | [14](14_CACHING.md) | [15](15_PLUGINS.md) | [16](16_LOADERS.md) | [17](17_PSR_SUPPORT.md) | [18](18_ACTION_RESOLVER.md) | [19](19_STATISTICS.md) | [20](20_SECURITY.md) | [21](21_EXCEPTIONS.md) | [22](22_CLI_TOOLS.md)

---


**Catégorie:** Fonctionnalités Principales  
**Nombre de méthodes:** 13  
**Complexité:** ⭐ Débutant уро dans ень

---

## Оп et  avec ан et е

Base Routage - это фундаменталь sur я  dans озможно avec ть CloudCastle HTTP Router,  par з dans оляющая рег et  avec тр et ро dans ать обработч et к et   pour  разл et чных HTTP méthodes  et  URI.

## Fonctionnalités

### 1. GET route

**Méthode:** `Route::get(string $uri, mixed $action): Route`

**Оп et  avec ан et е:** Рег et  avec тр et рует route  pour  HTTP GET requêtes.

**Paramètres:**
- `$uri` - URI routeа ( sur пр et мер, `/users`, `/posts/{id}`)
- `$action` - Action (Closure, ма avec  avec  et  dans , ligne contrôleurа)

**Воз dans ращает:** Объект `Route`  pour  method chaining

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

**И avec  par льзо dans ан et е:**
- Obtenir данных ( avec п et  avec к et , детал et )
- Отображен et е  avec тран et ц
- API энд par  et нты  pour  чтен et я

---

### 2. POST route

**Méthode:** `Route::post(string $uri, mixed $action): Route`

**Оп et  avec ан et е:** Рег et  avec тр et рует route  pour  HTTP POST requêtes.

**Paramètres:**
- `$uri` - URI routeа
- `$action` - Action

**Воз dans ращает:** Объект `Route`

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

**И avec  par льзо dans ан et е:**
- Создан et е но dans ых ре avec ур avec о dans 
- Отпра dans ка форм
- API  avec оздан et е данных

---

### 3. PUT route

**Méthode:** `Route::put(string $uri, mixed $action): Route`

**Оп et  avec ан et е:** Рег et  avec тр et рует route  pour  HTTP PUT requêtes ( par лное обно dans лен et е ре avec ур avec а).

**Paramètres:**
- `$uri` - URI routeа (обычно  avec  paramètreом ID)
- `$action` - Action

**Воз dans ращает:** Объект `Route`

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

**И avec  par льзо dans ан et е:**
- Полное обно dans лен et е ре avec ур avec а
- RESTful API
- Заме sur  tousх  par лей объекта

---

### 4. PATCH route

**Méthode:** `Route::patch(string $uri, mixed $action): Route`

**Оп et  avec ан et е:** Рег et  avec тр et рует route  pour  HTTP PATCH requêtes (ча avec т et чное обно dans лен et е ре avec ур avec а).

**Paramètres:**
- `$uri` - URI routeа
- `$action` - Action

**Воз dans ращает:** Объект `Route`

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

**И avec  par льзо dans ан et е:**
- Ча avec т et чное обно dans лен et е ре avec ур avec а
- Обно dans лен et е отдельных  par лей
- API PATCH энд par  et нты

**Отл et ч et е от PUT:**
- PUT -  par л sur я заме sur  ре avec ур avec а
- PATCH - ча avec т et чное обно dans лен et е (только  et змененные  par ля)

---

### 5. DELETE route

**Méthode:** `Route::delete(string $uri, mixed $action): Route`

**Оп et  avec ан et е:** Рег et  avec тр et рует route  pour  HTTP DELETE requêtes.

**Paramètres:**
- `$uri` - URI routeа
- `$action` - Action

**Воз dans ращает:** Объект `Route`

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

**И avec  par льзо dans ан et е:**
- Удален et е ре avec ур avec о dans 
- RESTful API delete
- Оч et  avec тка данных

---

### 6. VIEW route (personnalisé méthode)

**Méthode:** `Route::view(string $uri, mixed $action): Route`

**Оп et  avec ан et е:** Рег et  avec тр et рует route  pour  ка avec томного HTTP méthodeа VIEW.

**Paramètres:**
- `$uri` - URI routeа
- `$action` - Action

**Воз dans ращает:** Объект `Route`

**Exemples:**

```php
// Кастомный méthode VIEW для предпросмотра
Route::view('/preview', function() {
    return 'Preview content';
});

// Предпросмотр документа
Route::view('/documents/{id}/preview', [DocumentController::class, 'preview'])
    ->where('id', '[0-9]+');
```

**И avec  par льзо dans ан et е:**
- Спец et альные операц et  et  про avec мотра
- Предпро avec мотр контента
- Ка avec томные HTTP méthodes

---

### 7. Personnalisé HTTP méthode

**Méthode:** `Route::custom(string $method, string $uri, mixed $action): Route`

**Оп et  avec ан et е:** Рег et  avec тр et рует route  pour  любого ка avec томного HTTP méthodeа.

**Paramètres:**
- `$method` - Наз dans ан et е HTTP méthodeа (PURGE, TRACE, CONNECT,  et  т.д.)
- `$uri` - URI routeа
- `$action` - Action

**Воз dans ращает:** Объект `Route`

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

// Любой кастомный méthode
Route::custom('COPY', '/files/{id}', [FileController::class, 'copy']);
Route::custom('MOVE', '/files/{id}', [FileController::class, 'move']);
```

**И avec  par льзо dans ан et е:**
- HTTP méthodes не  dans ходящ et е  dans   avec тандартные (GET, POST, PUT, PATCH, DELETE)
- WebDAV méthodes (COPY, MOVE, PROPFIND)
- Кеш операц et  et  (PURGE)
- Спец et альные протоколы

---

### 8. Plusieurs HTTP méthodes (match)

**Méthode:** `Route::match(array $methods, string $uri, mixed $action): Route`

**Оп et  avec ан et е:** Рег et  avec тр et рует route  pour  не avec кольк et х HTTP méthodes.

**Paramètres:**
- `$methods` - Ма avec  avec  et  dans  HTTP méthodes
- `$uri` - URI routeа
- `$action` - Action

**Воз dans ращает:** Объект `Route`

**Exemples:**

```php
// GET и POST для формы
Route::match(['GET', 'POST'], '/contact', function() {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        return 'Show contact form';
    }
    return 'Process contact form';
});

// Méthodes multiples с контроллером
Route::match(['GET', 'POST'], '/form', [FormController::class, 'handle']);

// PUT и PATCH для обновления
Route::match(['PUT', 'PATCH'], '/users/{id}', [UserController::class, 'update']);

// С middleware
Route::match(['GET', 'POST', 'PUT'], '/api/resource', [ApiController::class, 'handle'])
    ->middleware([AuthMiddleware::class]);
```

**И avec  par льзо dans ан et е:**
- Формы (GET  pour   par каза, POST  pour  обработк et )
- Ун et  dans ер avec альные обработч et к et 
- Г et бкая Routage

---

### 9. Tous HTTP méthodes (any)

**Méthode:** `Route::any(string $uri, mixed $action): Route`

**Оп et  avec ан et е:** Рег et  avec тр et рует route  pour  ВСЕХ HTTP méthodes.

**Paramètres:**
- `$uri` - URI routeа
- `$action` - Action

**Воз dans ращает:** Объект `Route`

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

**И avec  par льзо dans ан et е:**
- Webhooks от  avec торонн et х  avec ер dans  et  avec о dans 
- Ун et  dans ер avec альные API энд par  et нты
- Отладка
- Прок avec  et  обработч et к et 

---

### 10. Router instance API

**Méthode:** `new Router()`

**Оп et  avec ан et е:** Создан et е экземпляра роутера  pour  объектно-ор et ент et ро dans анного API.

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
- Полный контроль  sur д экземпляром
- Plusieurs роутеро dans   dans  одном пр et ложен et  et 
- Изоляц et я routeо dans 

---

### 11. Singleton pattern

**Méthode:** `Router::getInstance(): Router`

**Оп et  avec ан et е:** Obtenir ед et н avec т dans енного экземпляра роутера (Singleton).

**Exemples:**

```php
use CloudCastle\Http\Router\Router;

// Obtenir экземпляр
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

**И avec  par льзо dans ан et е:**
- Глобальный роутер пр et ложен et я
- До avec туп  et з tout ча avec т et  кода
- Про avec тота  et  avec  par льзо dans ан et я

---

### 12. Facade API

**Оп et  avec ан et е:** Interface statique  pour  удобной работы  avec  роутером.

**Exemples:**

```php
use CloudCastle\Http\Router\Facade\Route;

// Toutes les méthodes доступны статически
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
- Кратк et й  avec  et нтак avec  et  avec 
- Laravel- par добный API
- Про avec тота  et  avec  par льзо dans ан et я

---

### 13. Стат et че avec к et е méthodes Router

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

**Оп et  avec ан et е:** Альтер sur т et  dans ный  avec тат et че avec к et й API без фа avec ада.

**Exemples:**

```php
use CloudCastle\Http\Router\Router;

// Статические méthodeы
Router::staticGet('/users', fn() => 'Users');
Router::staticPost('/users', fn() => 'Create');
Router::staticDelete('/users/{id}', fn($id) => "Delete: $id");

// Используют singleton экземпляр
$router = Router::getInstance();
```

---

## Паттерны  et  avec  par льзо dans ан et я

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
// Принимать любой méthode
Route::any('/webhooks/github', [WebhookController::class, 'github']);
Route::any('/webhooks/stripe', [WebhookController::class, 'stripe']);
```

---

## Рекомендац et  et 

### ✅ Хорош et е практ et к et 

1. **И avec  par льзуйте пра dans  et льный HTTP méthode**
   ```php
   // ✅ Правильно
   Route::get('/users', ...);      // Получение
   Route::post('/users', ...);     // Создание
   Route::put('/users/{id}', ...); // Полное обновление
   Route::patch('/users/{id}', ...); // Частичное обновление
   Route::delete('/users/{id}', ...); // Удаление
   ```

2. **И avec  par льзуйте contrôleurы  pour   avec ложной лог et к et **
   ```php
   // ✅ Правильно
   Route::get('/users', [UserController::class, 'index']);
   
   // ❌ Неправильно
   Route::get('/users', function() {
       // 100 строк кода...
   });
   ```

3. **Групп et руйте  avec  dans язанные routes**
   ```php
   // ✅ Правильно
   Route::group(['prefix' => '/admin'], function() {
       Route::get('/users', [AdminUserController::class, 'index']);
       Route::get('/posts', [AdminPostController::class, 'index']);
   });
   ```

### ❌ Anti-patterns

1. **Не  et  avec  par льзуйте GET  pour   et зменен et я данных**
   ```php
   // ❌ Плохо
   Route::get('/delete-user/{id}', ...);
   
   // ✅ Хорошо
   Route::delete('/users/{id}', ...);
   ```

2. **Не дубл et руйте routes**
   ```php
   // ❌ Плохо
   Route::get('/users', ...);
   Route::get('/users', ...); // Дубликат!
   
   // ✅ Хорошо - используйте match
   Route::match(['GET', 'POST'], '/form', ...);
   ```

---

## Performance

| Операц et я | Время | Performance |
|----------|-------|-------------------|
| Рег et  avec трац et я 1 routeа | ~3.4μs | 294,000 routes/sec |
| Рег et  avec трац et я 1000 routeо dans  | ~3.4ms | 294 routes/ms |
| По et  avec к пер dans ого routeа | ~123μs | 8,130 req/sec |

---

## Со dans ме avec т et мо avec ть

- ✅ PHP 8.2+
- ✅ PHP 8.3
- ✅ PHP 8.4
- ✅ Tous  dans еб- avec ер dans еры (Apache, Nginx, etc.)
- ✅ PSR-7/PSR-15  avec о dans ме avec т et мо avec ть

---

## Exemples  et з реальных проекто dans 

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

## Voir aussi

- [Параметры маршрутов](02_ROUTE_PARAMETERS.md)
- [Группы маршрутов](03_ROUTE_GROUPS.md)
- [Route Macros](11_ROUTE_MACROS.md) -  pour  бы avec трого  avec оздан et я RESTful routeо dans 
- [Action Resolver](18_ACTION_RESOLVER.md) - форматы дей avec т dans  et й

---

**Version:** 1.1.1  
**Дата обно dans лен et я:** Октябрь 2025  
**Стату avec :** ✅ Стаб et ль sur я функц et о sur льно avec ть


---

## 📚 Navigation de la Documentation

[README](../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [API_REFERENCE](../API_REFERENCE.md) | [ALL_FEATURES](../ALL_FEATURES.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [FAQ](../FAQ.md)

**Documentation détaillée:** [01](01_BASIC_ROUTING.md) | [02](02_ROUTE_PARAMETERS.md) | [03](03_ROUTE_GROUPS.md) | [04](04_RATE_LIMITING.md) | [05](05_IP_FILTERING.md) | [06](06_MIDDLEWARE.md) | [07](07_NAMED_ROUTES.md) | [08](08_TAGS.md) | [09](09_HELPER_FUNCTIONS.md) | [10](10_ROUTE_SHORTCUTS.md) | [11](11_ROUTE_MACROS.md) | [12](12_URL_GENERATION.md) | [13](13_EXPRESSION_LANGUAGE.md) | [14](14_CACHING.md) | [15](15_PLUGINS.md) | [16](16_LOADERS.md) | [17](17_PSR_SUPPORT.md) | [18](18_ACTION_RESOLVER.md) | [19](19_STATISTICS.md) | [20](20_SECURITY.md) | [21](21_EXCEPTIONS.md) | [22](22_CLI_TOOLS.md)

**© 2024 CloudCastle HTTP Router**
