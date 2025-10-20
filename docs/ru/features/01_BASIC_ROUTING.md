# Базовая маршрутизация

**Категория:** Основные возможности  
**Количество методов:** 13  
**Сложность:** ⭐ Начальный уровень

---

## Описание

Базовая маршрутизация - это фундаментальная возможность CloudCastle HTTP Router, позволяющая регистрировать обработчики для различных HTTP методов и URI.

## Возможности

### 1. GET маршрут

**Метод:** `Route::get(string $uri, mixed $action): Route`

**Описание:** Регистрирует маршрут для HTTP GET запросов.

**Параметры:**
- `$uri` - URI маршрута (например, `/users`, `/posts/{id}`)
- `$action` - Действие (Closure, массив, строка контроллера)

**Возвращает:** Объект `Route` для method chaining

**Примеры:**

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

**Использование:**
- Получение данных (списки, детали)
- Отображение страниц
- API эндпоинты для чтения

---

### 2. POST маршрут

**Метод:** `Route::post(string $uri, mixed $action): Route`

**Описание:** Регистрирует маршрут для HTTP POST запросов.

**Параметры:**
- `$uri` - URI маршрута
- `$action` - Действие

**Возвращает:** Объект `Route`

**Примеры:**

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

**Использование:**
- Создание новых ресурсов
- Отправка форм
- API создание данных

---

### 3. PUT маршрут

**Метод:** `Route::put(string $uri, mixed $action): Route`

**Описание:** Регистрирует маршрут для HTTP PUT запросов (полное обновление ресурса).

**Параметры:**
- `$uri` - URI маршрута (обычно с параметром ID)
- `$action` - Действие

**Возвращает:** Объект `Route`

**Примеры:**

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

**Использование:**
- Полное обновление ресурса
- RESTful API
- Замена всех полей объекта

---

### 4. PATCH маршрут

**Метод:** `Route::patch(string $uri, mixed $action): Route`

**Описание:** Регистрирует маршрут для HTTP PATCH запросов (частичное обновление ресурса).

**Параметры:**
- `$uri` - URI маршрута
- `$action` - Действие

**Возвращает:** Объект `Route`

**Примеры:**

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

**Использование:**
- Частичное обновление ресурса
- Обновление отдельных полей
- API PATCH эндпоинты

**Отличие от PUT:**
- PUT - полная замена ресурса
- PATCH - частичное обновление (только измененные поля)

---

### 5. DELETE маршрут

**Метод:** `Route::delete(string $uri, mixed $action): Route`

**Описание:** Регистрирует маршрут для HTTP DELETE запросов.

**Параметры:**
- `$uri` - URI маршрута
- `$action` - Действие

**Возвращает:** Объект `Route`

**Примеры:**

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

**Использование:**
- Удаление ресурсов
- RESTful API delete
- Очистка данных

---

### 6. VIEW маршрут (кастомный метод)

**Метод:** `Route::view(string $uri, mixed $action): Route`

**Описание:** Регистрирует маршрут для кастомного HTTP метода VIEW.

**Параметры:**
- `$uri` - URI маршрута
- `$action` - Действие

**Возвращает:** Объект `Route`

**Примеры:**

```php
// Кастомный метод VIEW для предпросмотра
Route::view('/preview', function() {
    return 'Preview content';
});

// Предпросмотр документа
Route::view('/documents/{id}/preview', [DocumentController::class, 'preview'])
    ->where('id', '[0-9]+');
```

**Использование:**
- Специальные операции просмотра
- Предпросмотр контента
- Кастомные HTTP методы

---

### 7. Кастомный HTTP метод

**Метод:** `Route::custom(string $method, string $uri, mixed $action): Route`

**Описание:** Регистрирует маршрут для любого кастомного HTTP метода.

**Параметры:**
- `$method` - Название HTTP метода (PURGE, TRACE, CONNECT, и т.д.)
- `$uri` - URI маршрута
- `$action` - Действие

**Возвращает:** Объект `Route`

**Примеры:**

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

**Использование:**
- HTTP методы не входящие в стандартные (GET, POST, PUT, PATCH, DELETE)
- WebDAV методы (COPY, MOVE, PROPFIND)
- Кеш операции (PURGE)
- Специальные протоколы

---

### 8. Несколько HTTP методов (match)

**Метод:** `Route::match(array $methods, string $uri, mixed $action): Route`

**Описание:** Регистрирует маршрут для нескольких HTTP методов.

**Параметры:**
- `$methods` - Массив HTTP методов
- `$uri` - URI маршрута
- `$action` - Действие

**Возвращает:** Объект `Route`

**Примеры:**

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

**Использование:**
- Формы (GET для показа, POST для обработки)
- Универсальные обработчики
- Гибкая маршрутизация

---

### 9. Все HTTP методы (any)

**Метод:** `Route::any(string $uri, mixed $action): Route`

**Описание:** Регистрирует маршрут для ВСЕХ HTTP методов.

**Параметры:**
- `$uri` - URI маршрута
- `$action` - Действие

**Возвращает:** Объект `Route`

**Примеры:**

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

**Использование:**
- Webhooks от сторонних сервисов
- Универсальные API эндпоинты
- Отладка
- Прокси обработчики

---

### 10. Router instance API

**Метод:** `new Router()`

**Описание:** Создание экземпляра роутера для объектно-ориентированного API.

**Примеры:**

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

**Преимущества:**
- Полный контроль над экземпляром
- Несколько роутеров в одном приложении
- Изоляция маршрутов

---

### 11. Singleton pattern

**Метод:** `Router::getInstance(): Router`

**Описание:** Получение единственного экземпляра роутера (Singleton).

**Примеры:**

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

**Использование:**
- Глобальный роутер приложения
- Доступ из любой части кода
- Простота использования

---

### 12. Facade API

**Описание:** Статический интерфейс для удобной работы с роутером.

**Примеры:**

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

**Преимущества:**
- Краткий синтаксис
- Laravel-подобный API
- Простота использования

---

### 13. Статические методы Router

**Методы:**
- `Router::staticGet()`
- `Router::staticPost()`
- `Router::staticPut()`
- `Router::staticPatch()`
- `Router::staticDelete()`
- `Router::staticView()`
- `Router::staticCustom()`
- `Router::staticMatch()`
- `Router::staticAny()`

**Описание:** Альтернативный статический API без фасада.

**Примеры:**

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

## Паттерны использования

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
// Принимать любой метод
Route::any('/webhooks/github', [WebhookController::class, 'github']);
Route::any('/webhooks/stripe', [WebhookController::class, 'stripe']);
```

---

## Рекомендации

### ✅ Хорошие практики

1. **Используйте правильный HTTP метод**
   ```php
   // ✅ Правильно
   Route::get('/users', ...);      // Получение
   Route::post('/users', ...);     // Создание
   Route::put('/users/{id}', ...); // Полное обновление
   Route::patch('/users/{id}', ...); // Частичное обновление
   Route::delete('/users/{id}', ...); // Удаление
   ```

2. **Используйте контроллеры для сложной логики**
   ```php
   // ✅ Правильно
   Route::get('/users', [UserController::class, 'index']);
   
   // ❌ Неправильно
   Route::get('/users', function() {
       // 100 строк кода...
   });
   ```

3. **Группируйте связанные маршруты**
   ```php
   // ✅ Правильно
   Route::group(['prefix' => '/admin'], function() {
       Route::get('/users', [AdminUserController::class, 'index']);
       Route::get('/posts', [AdminPostController::class, 'index']);
   });
   ```

### ❌ Антипаттерны

1. **Не используйте GET для изменения данных**
   ```php
   // ❌ Плохо
   Route::get('/delete-user/{id}', ...);
   
   // ✅ Хорошо
   Route::delete('/users/{id}', ...);
   ```

2. **Не дублируйте маршруты**
   ```php
   // ❌ Плохо
   Route::get('/users', ...);
   Route::get('/users', ...); // Дубликат!
   
   // ✅ Хорошо - используйте match
   Route::match(['GET', 'POST'], '/form', ...);
   ```

---

## Производительность

| Операция | Время | Производительность |
|----------|-------|-------------------|
| Регистрация 1 маршрута | ~3.4μs | 294,000 routes/sec |
| Регистрация 1000 маршрутов | ~3.4ms | 294 routes/ms |
| Поиск первого маршрута | ~123μs | 8,130 req/sec |

---

## Совместимость

- ✅ PHP 8.2+
- ✅ PHP 8.3
- ✅ PHP 8.4
- ✅ Все веб-серверы (Apache, Nginx, etc.)
- ✅ PSR-7/PSR-15 совместимость

---

## Примеры из реальных проектов

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

## См. также

- [Параметры маршрутов](02_ROUTE_PARAMETERS.md)
- [Группы маршрутов](03_ROUTE_GROUPS.md)
- [Route Macros](11_ROUTE_MACROS.md) - для быстрого создания RESTful маршрутов
- [Action Resolver](18_ACTION_RESOLVER.md) - форматы действий

---

**Версия:** 1.1.1  
**Дата обновления:** Октябрь 2025  
**Статус:** ✅ Стабильная функциональность

