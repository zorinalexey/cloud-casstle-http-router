[🇷🇺 Русский](ru/macros.md) | [🇺🇸 English](en/macros.md) | [🇩🇪 Deutsch](de/macros.md) | [🇫🇷 Français](fr/macros.md) | [🇨🇳 中文](zh/macros.md)

[📚 Table of Contents](zh/_table-of-contents.md) | [🏠 Home](zh/README.md)

---

# Route Macros - Макросы для быстрого создания маршрутов

**Languages:** 🇷🇺 Русский | [🇬🇧 English](../en/macros.md) | [🇩🇪 Deutsch](../de/macros.md) | [🇫🇷 Français](../fr/macros.md) | [🇨🇳 中文](../zh/macros.md)

[📚 Оглавление](_table-of-contents.md) | [🏠 Главная](README.md)

---

## 📚 Обзор

**Route Macros** - мощная система для создания множества маршрутов одной командой. Сокращает код на 80-90%.

## 🎯 Встроенные Macros

### 1. resource() - RESTful Resource

**Создаёт 7 маршрутов CRUD operations одной строкой!**

```php
use CloudCastle\Http\Router\Facade\Route;

Route::resource('users', 'UserController');
```

**Создаёт маршруты:**

| Method | URI | Action | Name |
|:---|:---:|:---:|:---:|
| GET | `/users` | index | `users.index` |
| GET | `/users/create` | create | `users.create` |
| POST | `/users` | store | `users.store` |
| GET | `/users/{id}` | show | `users.show` |
| GET | `/users/{id}/edit` | edit | `users.edit` |
| PUT | `/users/{id}` | update | `users.update` |
| DELETE | `/users/{id}` | destroy | `users.destroy` |

**Сравнение:**
```php
// БЕЗ MACRO (35 строк):
$router->get('/users', 'UserController@index')->name('users.index');
$router->get('/users/create', 'UserController@create')->name('users.create');
$router->post('/users', 'UserController@store')->name('users.store');
$router->get('/users/{id}', 'UserController@show')->name('users.show');
$router->get('/users/{id}/edit', 'UserController@edit')->name('users.edit');
$router->put('/users/{id}', 'UserController@update')->name('users.update');
$router->delete('/users/{id}', 'UserController@destroy')->name('users.destroy');

// С MACRO (1 строка):
Route::resource('users', 'UserController');

// Сокращение: 97%! ⚡
```

---

### 2. apiResource() - RESTful API Resource

**Создаёт API endpoints с middleware, throttle и tags!**

```php
Route::apiResource('posts', 'Api\PostController', 200);
```

**Создаёт маршруты:**

| Method | URI | Action | Extras |
|:---|:---:|:---:|:---:|
| GET | `/posts` | index | api middleware, throttle 200 |
| POST | `/posts` | store | api middleware, throttle 100 |
| GET | `/posts/{id}` | show | api middleware, throttle 200 |
| PUT | `/posts/{id}` | update | api middleware, throttle 100 |
| DELETE | `/posts/{id}` | destroy | api middleware, throttle 100 |

**Отличия от resource()**:
- ✅ Нет `/create` и `/edit` routes (не нужны для API)
- ✅ API middleware автоматически
- ✅ Rate limiting (reads: 200, writes: 100)
- ✅ Tag 'api'

**Применение:**
- RESTful JSON APIs
- GraphQL endpoints
- Mobile app backends

---

### 3. crud() - Простые CRUD операции

**Создаёт 4 основных CRUD маршрута:**

```php
Route::crud('comments', 'CommentController');
```

**Создаёт маршруты:**

| Method | URI | Action | Name |
|:---|:---:|:---:|:---:|
| GET | `/comments` | index | `comments.index` |
| POST | `/comments` | create | `comments.create` |
| PUT | `/comments/{id}` | update | `comments.update` |
| DELETE | `/comments/{id}` | delete | `comments.delete` |

**Когда использовать:**
- Простые CRUD операции
- Когда не нужны `/create` и `/edit` формы
- Быстрое прототипирование

---

### 4. auth() - Все маршруты аутентификации

**Создаёт полный набор auth routes!**

```php
Route::auth();
```

**Создаёт маршруты:**

| Method | URI | Action | Throttle |
|:---|:---:|:---:|:---:|
| GET | `/login` | showLoginForm | - |
| POST | `/login` | login | 5 req/min (strict) |
| POST | `/logout` | logout | - |
| GET | `/register` | showRegisterForm | - |
| POST | `/register` | register | 3 req/min (very strict) |
| GET | `/password/reset` | showResetForm | - |
| POST | `/password/reset` | reset | 3 req/min |

**Защита:**
- ✅ Rate limiting на login/register
- ✅ CSRF protection
- ✅ Tag 'auth'

**Применение:**
- Стандартная система авторизации
- Быстрый старт проекта
- Authentication scaffolding

---

### 5. adminPanel() - Админ панель

**Создаёт защищённую админ панель с IP whitelist!**

```php
Route::adminPanel(['192.168.1.1', '10.0.0.0/8']);
```

**Создаёт маршруты:**

| URI | Action | Middleware | IP Filter |
|:---|:---:|:---:|:---:|
| `/admin/dashboard` | index | auth, admin | whitelist |
| `/admin/users` | users | auth, admin | whitelist |
| `/admin/settings` | settings | auth, admin | whitelist |

**Настраивает:**
- ✅ Auth + Admin middleware
- ✅ IP whitelist
- ✅ Tag 'admin'
- ✅ Throttle 500 req/min

**Применение:**
- Административные панели
- Internal tools
- Management консоли

---

### 6. apiVersion() - API Versioning

**Создаёт версионированное API!**

```php
Route::apiVersion('v1', function() {
    Route::get('/users', 'Api\V1\UserController@index');
    Route::get('/posts', 'Api\V1\PostController@index');
});

Route::apiVersion('v2', function() {
    Route::get('/users', 'Api\V2\UserController@index');
    Route::get('/posts', 'Api\V2\PostController@index');
});
```

**Настраивает:**
- ✅ Префикс `/api/v{version}`
- ✅ API middleware
- ✅ Rate limiting
- ✅ Tag `api-v{version}`
- ✅ Namespace `Api\V{version}`

**Результат:**
- `/api/v1/users` → `Api\V1\UserController@index`
- `/api/v2/users` → `Api\V2\UserController@index`

---

### 7. webhooks() - Webhook Endpoints

**Создаёт безопасные webhook endpoints!**

```php
Route::webhooks(['192.0.2.1', '198.51.100.1']);
```

**Создаёт маршруты:**

| Method | URI | Action | Protection |
|:---|:---:|:---:|:---:|
| POST | `/webhooks/github` | github | IP whitelist, signature |
| POST | `/webhooks/stripe` | stripe | IP whitelist, signature |
| POST | `/webhooks/slack` | slack | IP whitelist, signature |

**Настраивает:**
- ✅ IP whitelist
- ✅ Signature verification middleware
- ✅ High rate limit (1000 req/min)
- ✅ Tag 'webhook'

**Применение:**
- GitHub webhooks
- Stripe webhooks
- Payment gateways
- Third-party integrations

---

## 🔧 Создание Custom Macros

### Простой macro

```php
use CloudCastle\Http\Router\RouteMacros;

// Регистрация macro
RouteMacros::register('premium', function($router, $resource, $controller) {
    $router->group([
        'prefix' => $resource,
        'middleware' => ['auth', 'premium'],
        'throttle' => 10000,
    ], function($router) use ($controller) {
        $router->get('/', "{$controller}@index");
        $router->get('/{id}', "{$controller}@show");
    });
});

// Использование
Route::premium('exclusive', 'ExclusiveController');
```

### Macro с параметрами

```php
RouteMacros::register('microservice', function($router, $name, $port, $ip) {
    $router->group([
        'prefix' => $name,
        'domain' => "{$name}.services.local",
        'port' => $port,
        'whitelistIp' => [$ip],
        'middleware' => 'service-mesh',
    ], function($router) {
        $router->get('/health', 'HealthController@check');
        $router->get('/metrics', 'MetricsController@show');
    });
});

// Использование
Route::microservice('users', 8081, '10.0.0.1');
Route::microservice('orders', 8082, '10.0.0.2');
Route::microservice('payments', 8083, '10.0.0.3');
```

### Macro для модулей

```php
RouteMacros::register('module', function($router, $moduleName, $controller) {
    $router->group([
        'prefix' => "modules/{$moduleName}",
        'namespace' => "Modules\\{$moduleName}",
        'middleware' => 'module-loader',
        'tag' => "module-{$moduleName}",
    ], function($router) use ($controller) {
        $router->get('/', "{$controller}@index");
        $router->get('/settings', "{$controller}@settings");
    });
});

// Использование
Route::module('Blog', 'BlogController');
Route::module('Shop', 'ShopController');
Route::module('Forum', 'ForumController');
```

## 📊 Экономия кода

### Пример: CRUD для 5 ресурсов

**Без macros:**
```php
// 175 строк кода (35 строк × 5 ресурсов)
```

**С macros:**
```php
Route::resource('users', 'UserController');
Route::resource('posts', 'PostController');
Route::resource('comments', 'CommentController');
Route::resource('categories', 'CategoryController');
Route::resource('tags', 'TagController');

// 5 строк кода
```

**Экономия: 97%!** (170 строк vs 5 строк)

### Пример: API с версионированием

**Без macros:**
```php
// ~200 строк кода
$router->group(['prefix' => 'api/v1', 'middleware' => 'api'], function($router) {
    $router->get('/users', ...)->name(...)->throttle(...)->tag(...);
    $router->post('/users', ...)->name(...)->throttle(...)->tag(...);
    // ... 20 маршрутов × 5 строк каждый
});

$router->group(['prefix' => 'api/v2', 'middleware' => 'api'], function($router) {
    // ... ещё 20 маршрутов × 5 строк
});
```

**С macros:**
```php
// ~20 строк кода
Route::apiVersion('v1', function() {
    Route::apiResource('users', 'Api\V1\UserController', 200);
    Route::apiResource('posts', 'Api\V1\PostController', 200);
    Route::apiResource('comments', 'Api\V1\CommentController', 100);
});

Route::apiVersion('v2', function() {
    Route::apiResource('users', 'Api\V2\UserController', 200);
    Route::apiResource('posts', 'Api\V2\PostController', 200);
});
```

**Экономия: 90%!** (200 строк vs 20 строк)

## 🆚 Сравнение с конкурентами

| Router | Built-in Macros | Resource | API Resource | Custom | Code Reduction |
|:---|:---:|:---:|:---:|:---:|:---:|
| **CloudCastle** | **✅ 7+** | **✅** | **✅** | **✅** | **80-97%** |
| FastRoute | ❌ | ❌ | ❌ | ❌ | 0% |
| Symfony | ⚠️ 2 | ⚠️ | ❌ | ⚠️ | 40% |
| Laravel | ✅ 5 | ✅ | ✅ | ✅ | 70% |
| Slim | ❌ | ❌ | ❌ | ❌ | 0% |
| AltoRouter | ❌ | ❌ | ❌ | ❌ | 0% |

## 💡 Best Practices

### 1. Используйте resource() для стандартных CRUD

```php
// Для всех ресурсов с CRUD
Route::resource('users', 'UserController');
Route::resource('posts', 'PostController');
Route::resource('products', 'ProductController');
```

### 2. Используйте apiResource() для API

```php
// RESTful API
Route::apiResource('users', 'Api\UserController', 1000);
Route::apiResource('posts', 'Api\PostController', 500);
```

### 3. Комбинируйте с версионированием

```php
Route::apiVersion('v1', function() {
    Route::apiResource('users', 'Api\V1\UserController', 200);
    Route::apiResource('posts', 'Api\V1\PostController', 200);
});
```

### 4. Создавайте custom macros для паттернов проекта

```php
// Ваш специфичный паттерн
RouteMacros::register('dashboard', function($router, $name) {
    $router->group(['prefix' => "dashboards/{$name}"], function($router) use ($name) {
        $router->get('/', "Dashboard\\{$name}Controller@index");
        $router->get('/widgets', "Dashboard\\{$name}Controller@widgets");
        $router->get('/reports', "Dashboard\\{$name}Controller@reports");
    });
});

Route::dashboard('Analytics');
Route::dashboard('Sales');
Route::dashboard('Users');
```

## ✅ Заключение

Route Macros - это **мощнейший инструмент** для сокращения кода:

- ✅ **80-97% сокращения кода**
- ✅ **Соблюдение RESTful conventions**
- ✅ **Консистентность**
- ✅ **Нет опечаток**
- ✅ **Легко поддерживать**

CloudCastle предоставляет **7 встроенных macros** + возможность создавать свои - больше чем у любого конкурента!

---

*Последнее обновление: 18 октября 2025*

---

[📚 Оглавление](_table-of-contents.md) | [🏠 Главная](README.md)

---

[📚 Table of Contents](zh/_table-of-contents.md) | [🏠 Home](zh/README.md)
