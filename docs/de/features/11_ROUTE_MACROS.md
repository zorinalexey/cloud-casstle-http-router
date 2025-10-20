# Route Macros

[English](../../en/features/11_ROUTE_MACROS.md) | [Русский](../../ru/features/11_ROUTE_MACROS.md) | **Deutsch** | [Français](../../fr/features/11_ROUTE_MACROS.md) | [中文](../../zh/features/11_ROUTE_MACROS.md)

---







---

## 📚 Dokumentationsnavigation

[README](../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [API_REFERENCE](../API_REFERENCE.md) | [ALL_FEATURES](../ALL_FEATURES.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [PERFORMANCE](../PERFORMANCE_ANALYSIS.md) | [SECURITY](../SECURITY_REPORT.md) | [COMPARISON](../COMPARISON.md) | [FAQ](../FAQ.md)

**Detaillierte Dokumentation:** [01](01_BASIC_ROUTING.md) | [02](02_ROUTE_PARAMETERS.md) | [03](03_ROUTE_GROUPS.md) | [04](04_RATE_LIMITING.md) | [05](05_IP_FILTERING.md) | [06](06_MIDDLEWARE.md) | [07](07_NAMED_ROUTES.md) | [08](08_TAGS.md) | [09](09_HELPER_FUNCTIONS.md) | [10](10_ROUTE_SHORTCUTS.md) | [11](11_ROUTE_MACROS.md) | [12](12_URL_GENERATION.md) | [13](13_EXPRESSION_LANGUAGE.md) | [14](14_CACHING.md) | [15](15_PLUGINS.md) | [16](16_LOADERS.md) | [17](17_PSR_SUPPORT.md) | [18](18_ACTION_RESOLVER.md) | [19](19_STATISTICS.md) | [20](20_SECURITY.md) | [21](21_EXCEPTIONS.md) | [22](22_CLI_TOOLS.md)

---


**Kategorie:** А in томат und зац und я  
**Anzahl der макро mit о in :** 7  
**Komplexität:** ⭐⭐ Mittel уро in ень

---

## Оп und  mit ан und е

Route Macros - это предопределенные шаблоны Routeо in   für  бы mit трого  mit оздан und я  mit тандартных  auf боро in  Routeо in  (RESTful CRUD, а in тор und зац und я, адм und нка  und  т.д.). Од und н макро mit   mit оздает mehrere  mit  in язанных Routeо in .

## Макро mit ы

### 1. resource() - RESTful Resource

**Methode:** `Route::resource(string $name, string $controller): void`

**Оп und  mit ан und е:** Создает  nach лный  auf бор RESTful Routeо in  (7 штук)  für  ре mit ур mit а.

**Созда in аемые Routen:**

| Methode | URI | Action | Имя | Наз auf чен und е |
|-------|-----|--------|-----|------------|
| GET | `/{name}` | `index` | `{name}.index` | Сп und  mit ок |
| GET | `/{name}/create` | `create` | `{name}.create` | Форма  mit оздан und я |
| POST | `/{name}` | `store` | `{name}.store` | Сохранен und е |
| GET | `/{name}/{id}` | `show` | `{name}.show` | Про mit мотр |
| GET | `/{name}/{id}/edit` | `edit` | `{name}.edit` | Форма редакт und ро in ан und я |
| PUT | `/{name}/{id}` | `update` | `{name}.update` | Обно in лен und е |
| DELETE | `/{name}/{id}` | `destroy` | `{name}.destroy` | Удален und е |

**Beispiele:**

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

**Требуемые Methoden Controllerа:**

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

**Methode:** `Route::apiResource(string $name, string $controller, int $rateLimit = 100): void`

**Оп und  mit ан und е:** Создает RESTful Routen  für  API (без create/edit форм).

**Созда in аемые Routen:**

| Methode | URI | Action | Имя |
|-------|-----|--------|-----|
| GET | `/{name}` | `index` | `{name}.index` |
| POST | `/{name}` | `store` | `{name}.store` |
| GET | `/{name}/{id}` | `show` | `{name}.show` |
| PUT | `/{name}/{id}` | `update` | `{name}.update` |
| DELETE | `/{name}/{id}` | `destroy` | `{name}.destroy` |

**Beispiele:**

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

**Требуемые Methoden Controllerа:**

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

**Methode:** `Route::crud(string $name, string $controller): void`

**Оп und  mit ан und е:** Упрощенный CRUD (5 Routeо in ).

**Созда in аемые Routen:**

| Methode | URI | Action | Имя |
|-------|-----|--------|-----|
| GET | `/{name}` | `index` | `{name}.index` |
| POST | `/{name}` | `create` | `{name}.create` |
| GET | `/{name}/{id}` | `read` | `{name}.read` |
| PUT | `/{name}/{id}` | `update` | `{name}.update` |
| DELETE | `/{name}/{id}` | `delete` | `{name}.delete` |

**Beispiele:**

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

**Methode:** `Route::auth(): void`

**Оп und  mit ан und е:** Создает Routen  für   mit  und  mit темы аутент und ф und кац und  und .

**Созда in аемые Routen:**

| Methode | URI | Action | Имя |
|-------|-----|--------|-----|
| GET | `/login` | `showLoginForm` | `login` |
| POST | `/login` | `login` | `login.post` |
| POST | `/logout` | `logout` | `logout` |
| GET | `/register` | `showRegisterForm` | `register` |
| POST | `/register` | `register` | `register.post` |
| GET | `/password/reset` | `showResetForm` | `password.request` |
| POST | `/password/reset` | `reset` | `password.reset` |

**Beispiele:**

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

**Требуемый Controller:**

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

**Methode:** `Route::adminPanel(string $prefix = '/admin', array $allowedIps = []): void`

**Оп und  mit ан und е:** Создает Routen адм und н-панел und   mit  защ und той.

**Parameter:**
- `$prefix` - Präfix URI (default: '/admin')
- `$allowedIps` - Разрешенные IP адре mit а

**Созда in аемые Routen:**

| Methode | URI | Action | Имя |
|-------|-----|--------|-----|
| GET | `/admin/dashboard` | `dashboard` | `admin.dashboard` |
| GET | `/admin/users` | `users` | `admin.users` |
| GET | `/admin/settings` | `settings` | `admin.settings` |
| GET | `/admin/logs` | `logs` | `admin.logs` |

**Beispiele:**

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

**Methode:** `Route::apiVersion(string $version, callable $callback): void`

**Оп und  mit ан und е:** Создает  in ер mit  und он und ро in анные API Routen.

**Parameter:**
- `$version` - Вер mit  und я API ( auf пр und мер, 'v1', 'v2')
- `$callback` - Функц und я  mit  Routeам und 

**Beispiele:**

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

**Создает  mit труктуру:**
```
/api/{version}/{маршруты}
```

---

### 7. webhooks() - Webhook Routes

**Methode:** `Route::webhooks(string $prefix = '/webhooks', array $allowedIps = []): void`

**Оп und  mit ан und е:** Создает Routen  für  webhooks  mit  IP защ und той.

**Parameter:**
- `$prefix` - Präfix URI (default: '/webhooks')
- `$allowedIps` - Разрешенные IP адре mit а

**Созда in аемые Routen:**

| Methode | URI | Action | Имя |
|-------|-----|--------|-----|
| POST | `/webhooks/github` | `github` | `webhooks.github` |
| POST | `/webhooks/stripe` | `stripe` | `webhooks.stripe` |
| POST | `/webhooks/paypal` | `paypal` | `webhooks.paypal` |
| POST | `/webhooks/custom` | `custom` | `webhooks.custom` |

**Beispiele:**

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

## Сра in нен und е макро mit о in 

| Макро mit  | Routeо in  | Наз auf чен und е | И mit  nach льзо in ан und е |
|--------|-----------|------------|---------------|
| `resource()` | 7 | Полный CRUD  mit  формам und  | Web пр und ложен und я |
| `apiResource()` | 5 | API CRUD без форм | REST API |
| `crud()` | 5 | Упрощенный CRUD | Про mit тые адм und нк und  |
| `auth()` | 7 | Аутент und ф und кац und я | Любые пр und ложен und я |
| `adminPanel()` | 4 | Адм und н панель | Адм und нк und  |
| `apiVersion()` | Variable | API  in ер mit  und  und  | Вер mit  und он und ро in ан und е |
| `webhooks()` | 4 | Webhooks | Интеграц und  und  |

---

## Полный пр und мер

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

## Vorteile  und  mit  nach льзо in ан und я макро mit о in 

### ✅ Скоро mit ть разработк und 

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

### ✅ Кон mit  und  mit тентно mit ть

Alle ре mit ур mit ы  mit ледуют одному паттерну:
- Од und  auf ко in ые URI
- Од und  auf ко in ые  und ме auf  Routeо in 
- Стандартные Methoden Controllerо in 

### ✅ Поддерж und  in аемо mit ть

Легко  nach нять  mit труктуру проекта - alle resource  und  mit  nach льзуют одну кон in енц und ю.

---

## Ка mit том und зац und я макро mit о in 

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

## Рекомендац und  und 

### ✅ Хорош und е практ und к und 

1. **И mit  nach льзуйте resource  für   nach лноценных CRUD**
   ```php
   // ✅ Хорошо - Web приложение
   Route::resource('posts', PostController::class);
   ```

2. **И mit  nach льзуйте apiResource  für  API**
   ```php
   // ✅ Хорошо - REST API
   Route::apiResource('users', ApiUserController::class);
   ```

3. **И mit  nach льзуйте  in ер mit  und он und ро in ан und е  für  API**
   ```php
   // ✅ Хорошо
   Route::apiVersion('v1', fn() => Route::apiResource('users', ApiV1UserController::class));
   Route::apiVersion('v2', fn() => Route::apiResource('users', ApiV2UserController::class));
   ```

### ❌ Anti-Patterns

1. **Не  und  mit  nach льзуйте resource е mit л und  не нужны alle Routen**
   ```php
   // ❌ Плохо - создаются лишние маршруты
   Route::resource('users', UserController::class);
   // Но используются только index и show
   
   // ✅ Хорошо - создать только нужные
   Route::get('/users', [UserController::class, 'index']);
   Route::get('/users/{id}', [UserController::class, 'show']);
   ```

---

## Leistung

Макро mit ы - это  mit  und нтак mit  und че mit к und й  mit ахар, он und  не  in л und яют  auf  про und з in од und тельно mit ть runtime. Про mit то  mit оздают mehrere Routeо in  за од und н  in ызо in .

---

## Siehe auch

- [Базовая маршрутизация](01_BASIC_ROUTING.md)
- [Группы маршрутов](03_ROUTE_GROUPS.md)
- [Именованные маршруты](07_NAMED_ROUTES.md)

---

**Version:** 1.1.1  
**Дата обно in лен und я:** Октябрь 2025  
**Стату mit :** ✅ Стаб und ль auf я функц und о auf льно mit ть


---

## 📚 Dokumentationsnavigation

[README](../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [API_REFERENCE](../API_REFERENCE.md) | [ALL_FEATURES](../ALL_FEATURES.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [FAQ](../FAQ.md)

**Detaillierte Dokumentation:** [01](01_BASIC_ROUTING.md) | [02](02_ROUTE_PARAMETERS.md) | [03](03_ROUTE_GROUPS.md) | [04](04_RATE_LIMITING.md) | [05](05_IP_FILTERING.md) | [06](06_MIDDLEWARE.md) | [07](07_NAMED_ROUTES.md) | [08](08_TAGS.md) | [09](09_HELPER_FUNCTIONS.md) | [10](10_ROUTE_SHORTCUTS.md) | [11](11_ROUTE_MACROS.md) | [12](12_URL_GENERATION.md) | [13](13_EXPRESSION_LANGUAGE.md) | [14](14_CACHING.md) | [15](15_PLUGINS.md) | [16](16_LOADERS.md) | [17](17_PSR_SUPPORT.md) | [18](18_ACTION_RESOLVER.md) | [19](19_STATISTICS.md) | [20](20_SECURITY.md) | [21](21_EXCEPTIONS.md) | [22](22_CLI_TOOLS.md)

**© 2024 CloudCastle HTTP Router**
