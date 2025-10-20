# Route Macros

**Категория:** Автоматизация  
**Количество макросов:** 7  
**Сложность:** ⭐⭐ Средний уровень

---

## Описание

Route Macros - это предопределенные шаблоны маршрутов для быстрого создания стандартных наборов маршрутов (RESTful CRUD, авторизация, админка и т.д.). Один макрос создает несколько связанных маршрутов.

## Макросы

### 1. resource() - RESTful Resource

**Метод:** `Route::resource(string $name, string $controller): void`

**Описание:** Создает полный набор RESTful маршрутов (7 штук) для ресурса.

**Создаваемые маршруты:**

| Метод | URI | Action | Имя | Назначение |
|-------|-----|--------|-----|------------|
| GET | `/{name}` | `index` | `{name}.index` | Список |
| GET | `/{name}/create` | `create` | `{name}.create` | Форма создания |
| POST | `/{name}` | `store` | `{name}.store` | Сохранение |
| GET | `/{name}/{id}` | `show` | `{name}.show` | Просмотр |
| GET | `/{name}/{id}/edit` | `edit` | `{name}.edit` | Форма редактирования |
| PUT | `/{name}/{id}` | `update` | `{name}.update` | Обновление |
| DELETE | `/{name}/{id}` | `destroy` | `{name}.destroy` | Удаление |

**Примеры:**

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

**Требуемые методы контроллера:**

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

**Метод:** `Route::apiResource(string $name, string $controller, int $rateLimit = 100): void`

**Описание:** Создает RESTful маршруты для API (без create/edit форм).

**Создаваемые маршруты:**

| Метод | URI | Action | Имя |
|-------|-----|--------|-----|
| GET | `/{name}` | `index` | `{name}.index` |
| POST | `/{name}` | `store` | `{name}.store` |
| GET | `/{name}/{id}` | `show` | `{name}.show` |
| PUT | `/{name}/{id}` | `update` | `{name}.update` |
| DELETE | `/{name}/{id}` | `destroy` | `{name}.destroy` |

**Примеры:**

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

**Требуемые методы контроллера:**

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

**Метод:** `Route::crud(string $name, string $controller): void`

**Описание:** Упрощенный CRUD (5 маршрутов).

**Создаваемые маршруты:**

| Метод | URI | Action | Имя |
|-------|-----|--------|-----|
| GET | `/{name}` | `index` | `{name}.index` |
| POST | `/{name}` | `create` | `{name}.create` |
| GET | `/{name}/{id}` | `read` | `{name}.read` |
| PUT | `/{name}/{id}` | `update` | `{name}.update` |
| DELETE | `/{name}/{id}` | `delete` | `{name}.delete` |

**Примеры:**

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

**Метод:** `Route::auth(): void`

**Описание:** Создает маршруты для системы аутентификации.

**Создаваемые маршруты:**

| Метод | URI | Action | Имя |
|-------|-----|--------|-----|
| GET | `/login` | `showLoginForm` | `login` |
| POST | `/login` | `login` | `login.post` |
| POST | `/logout` | `logout` | `logout` |
| GET | `/register` | `showRegisterForm` | `register` |
| POST | `/register` | `register` | `register.post` |
| GET | `/password/reset` | `showResetForm` | `password.request` |
| POST | `/password/reset` | `reset` | `password.reset` |

**Примеры:**

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

**Требуемый контроллер:**

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

**Метод:** `Route::adminPanel(string $prefix = '/admin', array $allowedIps = []): void`

**Описание:** Создает маршруты админ-панели с защитой.

**Параметры:**
- `$prefix` - Префикс URI (default: '/admin')
- `$allowedIps` - Разрешенные IP адреса

**Создаваемые маршруты:**

| Метод | URI | Action | Имя |
|-------|-----|--------|-----|
| GET | `/admin/dashboard` | `dashboard` | `admin.dashboard` |
| GET | `/admin/users` | `users` | `admin.users` |
| GET | `/admin/settings` | `settings` | `admin.settings` |
| GET | `/admin/logs` | `logs` | `admin.logs` |

**Примеры:**

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

**Метод:** `Route::apiVersion(string $version, callable $callback): void`

**Описание:** Создает версионированные API маршруты.

**Параметры:**
- `$version` - Версия API (например, 'v1', 'v2')
- `$callback` - Функция с маршрутами

**Примеры:**

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

**Создает структуру:**
```
/api/{version}/{маршруты}
```

---

### 7. webhooks() - Webhook Routes

**Метод:** `Route::webhooks(string $prefix = '/webhooks', array $allowedIps = []): void`

**Описание:** Создает маршруты для webhooks с IP защитой.

**Параметры:**
- `$prefix` - Префикс URI (default: '/webhooks')
- `$allowedIps` - Разрешенные IP адреса

**Создаваемые маршруты:**

| Метод | URI | Action | Имя |
|-------|-----|--------|-----|
| POST | `/webhooks/github` | `github` | `webhooks.github` |
| POST | `/webhooks/stripe` | `stripe` | `webhooks.stripe` |
| POST | `/webhooks/paypal` | `paypal` | `webhooks.paypal` |
| POST | `/webhooks/custom` | `custom` | `webhooks.custom` |

**Примеры:**

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

## Сравнение макросов

| Макрос | Маршрутов | Назначение | Использование |
|--------|-----------|------------|---------------|
| `resource()` | 7 | Полный CRUD с формами | Web приложения |
| `apiResource()` | 5 | API CRUD без форм | REST API |
| `crud()` | 5 | Упрощенный CRUD | Простые админки |
| `auth()` | 7 | Аутентификация | Любые приложения |
| `adminPanel()` | 4 | Админ панель | Админки |
| `apiVersion()` | Variable | API версии | Версионирование |
| `webhooks()` | 4 | Webhooks | Интеграции |

---

## Полный пример

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

## Преимущества использования макросов

### ✅ Скорость разработки

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

### ✅ Консистентность

Все ресурсы следуют одному паттерну:
- Одинаковые URI
- Одинаковые имена маршрутов
- Стандартные методы контроллеров

### ✅ Поддерживаемость

Легко понять структуру проекта - все resource используют одну конвенцию.

---

## Кастомизация макросов

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

## Рекомендации

### ✅ Хорошие практики

1. **Используйте resource для полноценных CRUD**
   ```php
   // ✅ Хорошо - Web приложение
   Route::resource('posts', PostController::class);
   ```

2. **Используйте apiResource для API**
   ```php
   // ✅ Хорошо - REST API
   Route::apiResource('users', ApiUserController::class);
   ```

3. **Используйте версионирование для API**
   ```php
   // ✅ Хорошо
   Route::apiVersion('v1', fn() => Route::apiResource('users', ApiV1UserController::class));
   Route::apiVersion('v2', fn() => Route::apiResource('users', ApiV2UserController::class));
   ```

### ❌ Антипаттерны

1. **Не используйте resource если не нужны все маршруты**
   ```php
   // ❌ Плохо - создаются лишние маршруты
   Route::resource('users', UserController::class);
   // Но используются только index и show
   
   // ✅ Хорошо - создать только нужные
   Route::get('/users', [UserController::class, 'index']);
   Route::get('/users/{id}', [UserController::class, 'show']);
   ```

---

## Производительность

Макросы - это синтаксический сахар, они не влияют на производительность runtime. Просто создают несколько маршрутов за один вызов.

---

## См. также

- [Базовая маршрутизация](01_BASIC_ROUTING.md)
- [Группы маршрутов](03_ROUTE_GROUPS.md)
- [Именованные маршруты](07_NAMED_ROUTES.md)

---

**Версия:** 1.1.1  
**Дата обновления:** Октябрь 2025  
**Статус:** ✅ Стабильная функциональность

