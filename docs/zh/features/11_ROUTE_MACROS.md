# Route Macros

[English](../../en/features/11_ROUTE_MACROS.md) | [Русский](../../ru/features/11_ROUTE_MACROS.md) | [Deutsch](../../de/features/11_ROUTE_MACROS.md) | [Français](../../fr/features/11_ROUTE_MACROS.md) | **中文**

---







---

## 📚 文档导航

[README](../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [API_REFERENCE](../API_REFERENCE.md) | [ALL_FEATURES](../ALL_FEATURES.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [PERFORMANCE](../PERFORMANCE_ANALYSIS.md) | [SECURITY](../SECURITY_REPORT.md) | [COMPARISON](../COMPARISON.md) | [FAQ](../FAQ.md)

**详细文档：** [01](01_BASIC_ROUTING.md) | [02](02_ROUTE_PARAMETERS.md) | [03](03_ROUTE_GROUPS.md) | [04](04_RATE_LIMITING.md) | [05](05_IP_FILTERING.md) | [06](06_MIDDLEWARE.md) | [07](07_NAMED_ROUTES.md) | [08](08_TAGS.md) | [09](09_HELPER_FUNCTIONS.md) | [10](10_ROUTE_SHORTCUTS.md) | [11](11_ROUTE_MACROS.md) | [12](12_URL_GENERATION.md) | [13](13_EXPRESSION_LANGUAGE.md) | [14](14_CACHING.md) | [15](15_PLUGINS.md) | [16](16_LOADERS.md) | [17](17_PSR_SUPPORT.md) | [18](18_ACTION_RESOLVER.md) | [19](19_STATISTICS.md) | [20](20_SECURITY.md) | [21](21_EXCEPTIONS.md) | [22](22_CLI_TOOLS.md)

---


**类别:**   
**数量 :** 7  
**复杂度：** ⭐⭐ 中级 

---

## 

Route Macros -    路由      路由 (RESTful CRUD, ,   ..).    多个  路由.

## 

### 1. resource() - RESTful Resource

**方法:** `Route::resource(string $name, string $controller): void`

**:**    RESTful 路由 (7 )  .

** 路由:**

| 方法 | URI | Action |  |  |
|-------|-----|--------|-----|------------|
| GET | `/{name}` | `index` | `{name}.index` |  |
| GET | `/{name}/create` | `create` | `{name}.create` |   |
| POST | `/{name}` | `store` | `{name}.store` |  |
| GET | `/{name}/{id}` | `show` | `{name}.show` |  |
| GET | `/{name}/{id}/edit` | `edit` | `{name}.edit` |   |
| PUT | `/{name}/{id}` | `update` | `{name}.update` |  |
| DELETE | `/{name}/{id}` | `destroy` | `{name}.destroy` |  |

**示例:**

```php
use CloudCastle\Http\Router\Facade\Route;

// Создать RESTful resource для пользователей
Route::resource('users', UserController::class);

// Создаются маршруты:
// GET    /users           → UserController::index    (users.index)
// GET    /users/create    → UserController::create   (users.create)
// POST   /users           → UserController::store    (users.store)
// GET    /users/{id}      → UserController::show     (users.show)
// GET    /users/{id}/edit → UserController::edit     (users.edit)
// PUT    /users/{id}      → UserController::update   (users.update)
// DELETE /users/{id}      → UserController::destroy  (users.destroy)


// Для постов
Route::resource('posts', PostController::class);
// GET    /posts           → PostController::index
// GET    /posts/create    → PostController::create
// ...и т.д.


// В группе с префиксом
Route::group(['prefix' => '/admin'], function() {
    Route::resource('products', AdminProductController::class);
    // /admin/products
    // /admin/products/create
    // /admin/products/{id}
    // ...
});
```

** 方法 控制器:**

```php
class UserController
{
    public function index() {
        // Показать список пользователей
    }
    
    public function create() {
        // Показать форму создания
    }
    
    public function store() {
        // Сохранить нового пользователя
    }
    
    public function show($id) {
        // Показать конкретного пользователя
    }
    
    public function edit($id) {
        // Показать форму редактирования
    }
    
    public function update($id) {
        // Обновить пользователя
    }
    
    public function destroy($id) {
        // Удалить пользователя
    }
}
```

---

### 2. apiResource() - API Resource

**方法:** `Route::apiResource(string $name, string $controller, int $rateLimit = 100): void`

**:**  RESTful 路由  API ( create/edit ).

** 路由:**

| 方法 | URI | Action |  |
|-------|-----|--------|-----|
| GET | `/{name}` | `index` | `{name}.index` |
| POST | `/{name}` | `store` | `{name}.store` |
| GET | `/{name}/{id}` | `show` | `{name}.show` |
| PUT | `/{name}/{id}` | `update` | `{name}.update` |
| DELETE | `/{name}/{id}` | `destroy` | `{name}.destroy` |

**示例:**

```php
// API resource с лимитом 100 req/min
Route::apiResource('posts', ApiPostController::class, 100);

// Создаются:
// GET    /posts       → ApiPostController::index    (throttle: 100/min)
// POST   /posts       → ApiPostController::store    (throttle: 100/min)
// GET    /posts/{id}  → ApiPostController::show     (throttle: 100/min)
// PUT    /posts/{id}  → ApiPostController::update   (throttle: 100/min)
// DELETE /posts/{id}  → ApiPostController::destroy  (throttle: 100/min)


// С другим лимитом
Route::apiResource('users', ApiUserController::class, 200);
// 200 запросов в минуту


// В API группе
Route::group(['prefix' => '/api/v1'], function() {
    Route::apiResource('products', ApiV1ProductController::class);
    Route::apiResource('orders', ApiV1OrderController::class);
});
```

** 方法 控制器:**

```php
class ApiPostController
{
    public function index() {
        // GET /posts - список
        return Post::all();
    }
    
    public function store() {
        // POST /posts - создание
        return Post::create($_POST);
    }
    
    public function show($id) {
        // GET /posts/{id} - просмотр
        return Post::find($id);
    }
    
    public function update($id) {
        // PUT /posts/{id} - обновление
        return Post::update($id, $_POST);
    }
    
    public function destroy($id) {
        // DELETE /posts/{id} - удаление
        return Post::delete($id);
    }
}
```

---

### 3. crud() - Simple CRUD

**方法:** `Route::crud(string $name, string $controller): void`

**:**  CRUD (5 路由).

** 路由:**

| 方法 | URI | Action |  |
|-------|-----|--------|-----|
| GET | `/{name}` | `index` | `{name}.index` |
| POST | `/{name}` | `create` | `{name}.create` |
| GET | `/{name}/{id}` | `read` | `{name}.read` |
| PUT | `/{name}/{id}` | `update` | `{name}.update` |
| DELETE | `/{name}/{id}` | `delete` | `{name}.delete` |

**示例:**

```php
// Простой CRUD
Route::crud('products', ProductController::class);

// Создаются:
// GET    /products       → ProductController::index
// POST   /products       → ProductController::create
// GET    /products/{id}  → ProductController::read
// PUT    /products/{id}  → ProductController::update
// DELETE /products/{id}  → ProductController::delete


// Для простых админок
Route::group(['prefix' => '/admin'], function() {
    Route::crud('categories', CategoryController::class);
    Route::crud('tags', TagController::class);
});
```

---

### 4. auth() - Authentication Routes

**方法:** `Route::auth(): void`

**:**  路由   .

** 路由:**

| 方法 | URI | Action |  |
|-------|-----|--------|-----|
| GET | `/login` | `showLoginForm` | `login` |
| POST | `/login` | `login` | `login.post` |
| POST | `/logout` | `logout` | `logout` |
| GET | `/register` | `showRegisterForm` | `register` |
| POST | `/register` | `register` | `register.post` |
| GET | `/password/reset` | `showResetForm` | `password.request` |
| POST | `/password/reset` | `reset` | `password.reset` |

**示例:**

```php
// Создать все маршруты аутентификации
Route::auth();

// Пользователь получает:
// - Форму входа (GET /login)
// - Обработку входа (POST /login)
// - Выход (POST /logout)
// - Форму регистрации (GET /register)
// - Обработку регистрации (POST /register)
// - Форму сброса пароля (GET /password/reset)
// - Обработку сброса пароля (POST /password/reset)


// С префиксом
Route::group(['prefix' => '/auth'], function() {
    Route::auth();
    // /auth/login
    // /auth/register
    // ...
});


// С middleware на защищенные маршруты
Route::auth();

// Затем добавить middleware к logout
Route::getRouteByName('logout')
    ?->middleware([AuthMiddleware::class]);
```

** 控制器:**

```php
class AuthController
{
    public function showLoginForm() { }
    public function login() { }
    public function logout() { }
    public function showRegisterForm() { }
    public function register() { }
    public function showResetForm() { }
    public function reset() { }
}
```

---

### 5. adminPanel() - Admin Panel Routes

**方法:** `Route::adminPanel(string $prefix = '/admin', array $allowedIps = []): void`

**:**  路由 -  .

**参数:**
- `$prefix` - 前缀 URI (default: '/admin')
- `$allowedIps` -  IP 

** 路由:**

| 方法 | URI | Action |  |
|-------|-----|--------|-----|
| GET | `/admin/dashboard` | `dashboard` | `admin.dashboard` |
| GET | `/admin/users` | `users` | `admin.users` |
| GET | `/admin/settings` | `settings` | `admin.settings` |
| GET | `/admin/logs` | `logs` | `admin.logs` |

**示例:**

```php
// Базовая админка
Route::adminPanel('/admin');

// С IP whitelist
Route::adminPanel('/admin', ['192.168.1.0/24']);

// С кастомным префиксом
Route::adminPanel('/panel', ['10.0.0.1', '10.0.0.2']);

// Все маршруты создаются с:
// - AuthMiddleware
// - AdminMiddleware
// - HTTPS enforcement
// - IP whitelist (если указан)
```

---

### 6. apiVersion() - API Versioning

**方法:** `Route::apiVersion(string $version, callable $callback): void`

**:**   API 路由.

**参数:**
- `$version` -  API (, 'v1', 'v2')
- `$callback` -   路由

**示例:**

```php
// API v1
Route::apiVersion('v1', function() {
    Route::get('/users', [ApiV1UserController::class, 'index']);
    Route::get('/posts', [ApiV1PostController::class, 'index']);
});
// Создаются: /api/v1/users, /api/v1/posts


// API v2
Route::apiVersion('v2', function() {
    Route::get('/users', [ApiV2UserController::class, 'index']);
    Route::get('/posts', [ApiV2PostController::class, 'index']);
});
// Создаются: /api/v2/users, /api/v2/posts


// Несколько версий
Route::apiVersion('v1', function() {
    Route::apiResource('users', ApiV1UserController::class);
});

Route::apiVersion('v2', function() {
    Route::apiResource('users', ApiV2UserController::class, 200);
});

// Новая версия с обратной совместимостью
Route::apiVersion('v3', function() {
    Route::get('/users', [ApiV3UserController::class, 'index']);
    Route::get('/posts', [ApiV3PostController::class, 'index']);
    
    // Новые эндпоинты только в v3
    Route::get('/analytics', [ApiV3AnalyticsController::class, 'index']);
});
```

** :**
```
/api/{version}/{маршруты}
```

---

### 7. webhooks() - Webhook Routes

**方法:** `Route::webhooks(string $prefix = '/webhooks', array $allowedIps = []): void`

**:**  路由  webhooks  IP .

**参数:**
- `$prefix` - 前缀 URI (default: '/webhooks')
- `$allowedIps` -  IP 

** 路由:**

| 方法 | URI | Action |  |
|-------|-----|--------|-----|
| POST | `/webhooks/github` | `github` | `webhooks.github` |
| POST | `/webhooks/stripe` | `stripe` | `webhooks.stripe` |
| POST | `/webhooks/paypal` | `paypal` | `webhooks.paypal` |
| POST | `/webhooks/custom` | `custom` | `webhooks.custom` |

**示例:**

```php
// Базовые webhooks
Route::webhooks('/webhooks');

// С IP whitelist (только от серверов GitHub/Stripe)
Route::webhooks('/webhooks', [
    '192.30.252.0/22',   // GitHub
    '54.187.174.169',    // Stripe
]);

// Кастомный префикс
Route::webhooks('/api/hooks', ['10.0.0.0/8']);

// Контроллер
class WebhookController
{
    public function github() {
        $payload = json_decode(file_get_contents('php://input'), true);
        // Обработка GitHub webhook
    }
    
    public function stripe() {
        $payload = json_decode(file_get_contents('php://input'), true);
        // Обработка Stripe webhook
    }
    
    public function paypal() {
        // Обработка PayPal webhook
    }
    
    public function custom() {
        // Кастомный webhook
    }
}
```

---

##  

|  | 路由 |  |  |
|--------|-----------|------------|---------------|
| `resource()` | 7 |  CRUD   | Web  |
| `apiResource()` | 5 | API CRUD   | REST API |
| `crud()` | 5 |  CRUD |   |
| `auth()` | 7 |  |   |
| `adminPanel()` | 4 |   |  |
| `apiVersion()` | Variable | API  |  |
| `webhooks()` | 4 | Webhooks |  |

---

##  

```php
use CloudCastle\Http\Router\Facade\Route;

// 1. Аутентификация
Route::auth();  // 7 маршрутов

// 2. Админ панель с защитой
Route::adminPanel('/admin', ['192.168.1.0/24']);  // 4 маршрута

// 3. RESTful ресурсы
Route::group(['prefix' => '/admin', 'middleware' => [AuthMiddleware::class, AdminMiddleware::class]], function() {
    Route::resource('users', AdminUserController::class);      // 7 маршрутов
    Route::resource('posts', AdminPostController::class);      // 7 маршрутов
    Route::resource('products', AdminProductController::class); // 7 маршрутов
});

// 4. API v1
Route::apiVersion('v1', function() {
    Route::apiResource('users', ApiV1UserController::class, 100);   // 5 маршрутов
    Route::apiResource('posts', ApiV1PostController::class, 100);   // 5 маршрутов
});

// 5. API v2
Route::apiVersion('v2', function() {
    Route::apiResource('users', ApiV2UserController::class, 200);   // 5 маршрутов
});

// 6. Webhooks
Route::webhooks('/webhooks', ['192.30.252.0/22', '54.187.174.169']);  // 4 маршрута

// ИТОГО: 7 + 4 + 21 + 10 + 5 + 4 = 51 маршрут одной строкой!
```

---

## 优势  

### ✅  

```php
// БЕЗ макросов - 7 маршрутов вручную
Route::get('/users', [UserController::class, 'index']);
Route::get('/users/create', [UserController::class, 'create']);
Route::post('/users', [UserController::class, 'store']);
Route::get('/users/{id}', [UserController::class, 'show']);
Route::get('/users/{id}/edit', [UserController::class, 'edit']);
Route::put('/users/{id}', [UserController::class, 'update']);
Route::delete('/users/{id}', [UserController::class, 'destroy']);

// С МАКРОСАМИ - 1 строка!
Route::resource('users', UserController::class);
```

### ✅ 

所有    :
-  URI
-   路由
-  方法 控制器

### ✅ 

    - 所有 resource   .

---

##  

```php
// Создать resource и затем модифицировать
Route::resource('users', UserController::class);

// Добавить middleware к конкретным маршрутам
Route::getRouteByName('users.store')
    ?->middleware([ValidateUserMiddleware::class]);

Route::getRouteByName('users.destroy')
    ?->middleware([AdminMiddleware::class]);

// Изменить throttle
Route::getRouteByName('users.index')
    ?->throttle(100, 1);
```

---

## 

### ✅  

1. ** resource   CRUD**
   ```php
   // ✅ Хорошо - Web приложение
   Route::resource('posts', PostController::class);
   ```

2. ** apiResource  API**
   ```php
   // ✅ Хорошо - REST API
   Route::apiResource('users', ApiUserController::class);
   ```

3. **   API**
   ```php
   // ✅ Хорошо
   Route::apiVersion('v1', fn() => Route::apiResource('users', ApiV1UserController::class));
   Route::apiVersion('v2', fn() => Route::apiResource('users', ApiV2UserController::class));
   ```

### ❌ 反模式

1. **  resource    所有 路由**
   ```php
   // ❌ Плохо - создаются лишние маршруты
   Route::resource('users', UserController::class);
   // Но используются только index и show
   
   // ✅ Хорошо - создать только нужные
   Route::get('/users', [UserController::class, 'index']);
   Route::get('/users/{id}', [UserController::class, 'show']);
   ```

---

## 性能

 -   ,      runtime.   多个 路由   .

---

## . 

- [Базовая маршрутизация](01_BASIC_ROUTING.md)
- [Группы маршрутов](03_ROUTE_GROUPS.md)
- [Именованные маршруты](07_NAMED_ROUTES.md)

---

**版本：** 1.1.1  
** :** 十月 2025  
**:** ✅  


---

## 📚 文档导航

[README](../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [API_REFERENCE](../API_REFERENCE.md) | [ALL_FEATURES](../ALL_FEATURES.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [FAQ](../FAQ.md)

**详细文档：** [01](01_BASIC_ROUTING.md) | [02](02_ROUTE_PARAMETERS.md) | [03](03_ROUTE_GROUPS.md) | [04](04_RATE_LIMITING.md) | [05](05_IP_FILTERING.md) | [06](06_MIDDLEWARE.md) | [07](07_NAMED_ROUTES.md) | [08](08_TAGS.md) | [09](09_HELPER_FUNCTIONS.md) | [10](10_ROUTE_SHORTCUTS.md) | [11](11_ROUTE_MACROS.md) | [12](12_URL_GENERATION.md) | [13](13_EXPRESSION_LANGUAGE.md) | [14](14_CACHING.md) | [15](15_PLUGINS.md) | [16](16_LOADERS.md) | [17](17_PSR_SUPPORT.md) | [18](18_ACTION_RESOLVER.md) | [19](19_STATISTICS.md) | [20](20_SECURITY.md) | [21](21_EXCEPTIONS.md) | [22](22_CLI_TOOLS.md)

**© 2024 CloudCastle HTTP Router**
