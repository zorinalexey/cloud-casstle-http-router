# Возможности маршрутизации - Детальное описание

[English](../../en/features/ROUTING_FEATURES.md) | **Русский** | [Deutsch](../../de/features/ROUTING_FEATURES.md) | [Français](../../fr/features/ROUTING_FEATURES.md) | [中文](../../zh/features/ROUTING_FEATURES.md)

---

## Содержание

- [HTTP Methods](#http-methods)
- [Параметры маршрутов](#параметры-маршрутов)
- [Именованные маршруты](#именованные-маршруты)
- [Теги](#теги)
- [Группы маршрутов](#группы-маршрутов)
- [Сравнение с аналогами](#сравнение-с-аналогами)

---

## HTTP Methods

### GET Method

**Описание:** Регистрация маршрута для GET запросов.

**Использование:**
```php
use CloudCastle\Http\Router\Router;

$router = new Router();

$router->get('/users', function() {
    return json_encode(['users' => [...] ]);
});

// С контроллером
$router->get('/users', [UserController::class, 'index']);

// Со строкой
$router->get('/users', 'UserController@index');
```

**Сравнение с аналогами:**

| Роутер | Синтаксис | Гибкость | Оценка |
|--------|-----------|----------|--------|
| **CloudCastle** | `$router->get($uri, $action)` | ⭐⭐⭐⭐⭐ | ⭐⭐⭐⭐⭐ |
| Laravel | `Route::get($uri, $action)` | ⭐⭐⭐⭐⭐ | ⭐⭐⭐⭐⭐ |
| Symfony | `$routes->add('name', new Route($uri))` | ⭐⭐⭐ | ⭐⭐⭐⭐ |
| FastRoute | `$r->addRoute('GET', $uri, $handler)` | ⭐⭐⭐ | ⭐⭐⭐⭐ |
| Slim | `$app->get($uri, $callable)` | ⭐⭐⭐⭐ | ⭐⭐⭐⭐ |

**Плюсы CloudCastle:**
- ✅ Интуитивный API
- ✅ Поддержка всех типов actions
- ✅ Fluent interface
- ✅ Method chaining

**Минусы:**
- Нет (аналогичен лучшим практикам)

**Рекомендации:**
1. Используйте для read-only операций
2. Кешируйте результаты GET запросов
3. Применяйте rate limiting на публичных endpoint'ах

---

### POST Method

**Описание:** Регистрация маршрута для POST запросов (создание ресурсов).

**Использование:**
```php
$router->post('/users', function() {
    // Создание пользователя
    $data = json_decode(file_get_contents('php://input'), true);
    // Валидация и сохранение
    return json_encode(['id' => 123, 'status' => 'created']);
});

// С rate limiting
$router->post('/users', $action)
    ->throttle(10, 1); // 10 запросов в минуту
```

**Сравнение:**

| Особенность | CloudCastle | Laravel | Symfony | FastRoute |
|-------------|-------------|---------|---------|-----------|
| Синтаксис | ✅ Простой | ✅ Простой | ⚠️ Сложный | ✅ Простой |
| Rate limiting | ✅ Встроен | ✅ Встроен | ❌ Нет | ❌ Нет |
| Валидация | ⚠️ Ручная | ✅ Form Request | ✅ Validator | ❌ Нет |

**Плюсы CloudCastle:**
- ✅ Встроенный rate limiting
- ✅ Простая настройка
- ✅ Цепочка методов

**Минусы:**
- ⚠️ Нет встроенной валидации (требует отдельная библиотека)

**Рекомендации:**
1. Всегда используйте rate limiting на POST
2. Применяйте HTTPS для чувствительных данных
3. Валидируйте входные данные
4. Используйте CSRF защиту

```php
// Рекомендуемая настройка
$router->post('/api/users', $action)
    ->throttle(20, 1)        // 20 в минуту
    ->https()                // Только HTTPS
    ->middleware(['csrf']);  // CSRF защита
```

---

### PUT/PATCH Methods

**Описание:** Обновление ресурсов.

**Различия:**
- **PUT:** Полное обновление ресурса
- **PATCH:** Частичное обновление

**Использование:**
```php
// PUT - полное обновление
$router->put('/users/{id}', function($id) {
    // Обновить ВСЕ поля пользователя
});

// PATCH - частичное обновление
$router->patch('/users/{id}', function($id) {
    // Обновить ТОЛЬКО переданные поля
});
```

**Сравнение:**

| Роутер | PUT | PATCH | Семантика | Оценка |
|--------|-----|-------|-----------|--------|
| **CloudCastle** | ✅ | ✅ | **Правильная** | ⭐⭐⭐⭐⭐ |
| Laravel | ✅ | ✅ | Правильная | ⭐⭐⭐⭐⭐ |
| Symfony | ✅ | ✅ | Правильная | ⭐⭐⭐⭐⭐ |
| FastRoute | ✅ | ✅ | Нейтральная | ⭐⭐⭐⭐ |

**Плюсы CloudCastle:**
- ✅ Поддержка обоих методов
- ✅ Правильная HTTP семантика
- ✅ Одинаковый API

**Рекомендации:**
1. PUT для замены ресурса целиком
2. PATCH для обновления полей
3. Валидируйте данные
4. Используйте аутентификацию

```php
// PUT - полная замена
$router->put('/users/{id}', $action)
    ->middleware(['auth'])
    ->throttle(30, 1);

// PATCH - частичное
$router->patch('/users/{id}/status', $action)
    ->middleware(['auth'])
    ->throttle(50, 1);
```

---

### DELETE Method

**Описание:** Удаление ресурсов.

**Использование:**
```php
$router->delete('/users/{id}', function($id) {
    // Удалить пользователя
    return json_encode(['status' => 'deleted']);
});

// С подтверждением
$router->delete('/users/{id}', $action)
    ->middleware(['auth', 'confirm-delete']);
```

**Сравнение:**

**CloudCastle:**
```php
Route::delete('/users/{id}', $action)
    ->middleware(['auth', 'admin'])
    ->throttle(10, 1);
```

**Laravel:**
```php
Route::delete('/users/{id}', $action)
    ->middleware(['auth', 'admin'])
    ->throttle(10);
```

**Symfony:**
```php
$routes->add('user.delete', new Route('/users/{id}', [
    '_controller' => '...',
], [], [], '', [], ['DELETE']
));
```

**Плюсы CloudCastle:**
- ✅ Простой синтаксис
- ✅ Встроенный rate limiting
- ✅ Fluent interface

**Рекомендации:**
1. Всегда требуйте аутентификацию
2. Строгий rate limiting
3. Логирование удалений
4. Soft delete где возможно

---

### ANY Method

**Описание:** Маршрут отвечающий на ВСЕ HTTP методы.

**Использование:**
```php
$router->any('/webhook', function() {
    $method = $_SERVER['REQUEST_METHOD'];
    // Обработка любого метода
    return "Received: $method";
});
```

**Методы включенные в ANY:**
- GET, POST, PUT, PATCH, DELETE
- OPTIONS, HEAD, VIEW

**Сравнение:**

| Роутер | ANY support | Методов | Оценка |
|--------|-------------|---------|--------|
| **CloudCastle** | ✅ | **8** | ⭐⭐⭐⭐⭐ |
| Laravel | ✅ | 7 | ⭐⭐⭐⭐⭐ |
| Symfony | ⚠️ Manual | All | ⭐⭐⭐⭐ |
| FastRoute | ❌ | - | ⭐⭐ |

**Плюсы:**
- ✅ Удобно для webhooks
- ✅ Один обработчик для всех методов

**Минусы:**
- ⚠️ Менее RESTful
- ⚠️ Сложнее кешировать

**Рекомендации:**
1. Используйте для webhooks
2. Используйте для CORS preflight
3. Избегайте для REST API
4. Обрабатывайте методы внутри action

```php
// Хороший пример - webhook
$router->any('/webhooks/github', function() {
    $method = $_SERVER['REQUEST_METHOD'];
    match($method) {
        'POST' => handleWebhook(),
        'GET' => showWebhookInfo(),
        default => http_response_code(405)
    };
});
```

---

### MATCH Method

**Описание:** Маршрут для нескольких конкретных методов.

**Использование:**
```php
// Только GET и POST
$router->match(['GET', 'POST'], '/form', function() {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        return showForm();
    }
    return processForm();
});
```

**Сравнение:**

**CloudCastle:**
```php
Route::match(['GET', 'POST'], '/contact', $action);
```

**Laravel:**
```php
Route::match(['GET', 'POST'], '/contact', $action);
```

**Symfony:**
```php
new Route('/contact', [...], [], [], '', [], ['GET', 'POST'])
```

**FastRoute:**
```php
$r->addRoute(['GET', 'POST'], '/contact', $handler);
```

**Плюсы CloudCastle:**
- ✅ Простой синтаксис
- ✅ Как в Laravel
- ✅ Гибкость

**Когда использовать:**
1. Формы (GET показать, POST обработать)
2. Dual endpoints (GET для info, POST для action)
3. CORS handling

**Рекомендации:**
```php
// Форма контакта
Route::match(['GET', 'POST'], '/contact', ContactController::class)
    ->throttle(10, 1);  // Защита от спама

// API endpoint с опциями
Route::match(['GET', 'OPTIONS'], '/api/data', $action);
```

---

### Custom HTTP Methods

**Описание:** Поддержка кастомных HTTP методов.

**Использование:**
```php
// VIEW метод
$router->view('/page', $action);

// Любой кастомный метод
$router->custom('PURGE', '/cache', function() {
    // Очистка кеша
    clearCache();
});

$router->custom('TRACE', '/debug', $action);
$router->custom('CONNECT', '/proxy', $action);
```

**Уникальность:**

| Роутер | Custom Methods | VIEW метод | Оценка |
|--------|----------------|------------|--------|
| **CloudCastle** | ✅ **Да** | ✅ **Встроен** | ⭐⭐⭐⭐⭐ |
| Laravel | ⚠️ Сложно | ❌ Нет | ⭐⭐⭐ |
| Symfony | ✅ Да | ❌ Нет | ⭐⭐⭐⭐ |
| FastRoute | ✅ Да | ❌ Нет | ⭐⭐⭐⭐ |

**Плюсы CloudCastle:**
- ✅ VIEW метод встроен
- ✅ custom() метод для любых методов
- ✅ Простое API

**Примеры использования:**

**VIEW метод (уникальная фича):**
```php
// Для read-only представлений без side effects
Route::view('/dashboard', fn() => renderDashboard())
    ->middleware(['auth']);
```

**PURGE метод (для кеша):**
```php
Route::custom('PURGE', '/cache/{key}', function($key) {
    Cache::forget($key);
    return ['status' => 'purged'];
})->whitelistIp(['127.0.0.1']);
```

**Рекомендации:**
1. VIEW для read-only операций
2. PURGE для очистки кеша (CDN совместимость)
3. Ограничивайте кастомные методы по IP
4. Документируйте кастомные методы

---

## Параметры маршрутов

### Базовые параметры

**Описание:** Извлечение данных из URI.

**Использование:**
```php
// Один параметр
Route::get('/users/{id}', function($id) {
    return "User ID: $id";
});

// Множественные параметры
Route::get('/posts/{category}/{slug}', function($category, $slug) {
    return "Category: $category, Slug: $slug";
});

// Параметры передаются в том же порядке
```

**Сравнение синтаксиса:**

**CloudCastle:**
```php
Route::get('/users/{id}', fn($id) => "User $id");
```

**Laravel:**
```php
Route::get('/users/{id}', fn($id) => "User $id");
```

**Symfony:**
```php
new Route('/users/{id}', ['_controller' => fn($id) => "User $id"])
```

**FastRoute:**
```php
$r->addRoute('GET', '/users/{id:\d+}', $handler);
```

**Плюсы CloudCastle:**
- ✅ Чистый синтаксис как Laravel
- ✅ Автоматическое извлечение
- ✅ Поддержка inline patterns

**Рекомендации:**
1. Используйте понятные имена параметров
2. Всегда валидируйте параметры
3. Применяйте where() для ограничений

---

### Параметры с ограничениями (where)

**Описание:** Валидация параметров через regex.

**Использование:**
```php
// Только цифры
Route::get('/users/{id}', $action)
    ->where('id', '[0-9]+');

// Только буквы и дефисы
Route::get('/posts/{slug}', $action)
    ->where('slug', '[a-z0-9-]+');

// Множественные ограничения
Route::get('/posts/{year}/{month}', $action)
    ->where([
        'year' => '[0-9]{4}',
        'month' => '0[1-9]|1[0-2]'
    ]);

// Email формат
Route::get('/verify/{email}', $action)
    ->where('email', '[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}');
```

**Сравнение:**

| Роутер | Where метод | Inline patterns | Global patterns | Оценка |
|--------|-------------|-----------------|-----------------|--------|
| **CloudCastle** | ✅ | ✅ | ⚠️ Нет | ⭐⭐⭐⭐⭐ |
| Laravel | ✅ | ❌ | ✅ | ⭐⭐⭐⭐⭐ |
| Symfony | ✅ Requirements | ❌ | ✅ | ⭐⭐⭐⭐ |
| FastRoute | ❌ | ✅ Только | ❌ | ⭐⭐⭐ |

**Inline patterns в CloudCastle:**
```php
// Можно и так
Route::get('/users/{id:[0-9]+}', $action);

// И так
Route::get('/users/{id}', $action)->where('id', '[0-9]+');
```

**Плюсы CloudCastle:**
- ✅ Два способа: where() и inline
- ✅ Множественные ограничения
- ✅ Fluent API

**Минусы:**
- ⚠️ Нет глобальных паттернов (как в Laravel)

**Улучшение для CloudCastle:**
```php
// Можно добавить в будущем:
Router::pattern('id', '[0-9]+');
Router::pattern('slug', '[a-z0-9-]+');
```

**Рекомендации:**
1. Всегда используйте where() для безопасности
2. Создавайте константы для частых паттернов
3. Документируйте форматы параметров

```php
// Константы для паттернов
const PATTERN_ID = '[0-9]+';
const PATTERN_SLUG = '[a-z0-9-]+';
const PATTERN_UUID = '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}';

Route::get('/users/{id}', $action)->where('id', PATTERN_ID);
Route::get('/posts/{slug}', $action)->where('slug', PATTERN_SLUG);
Route::get('/orders/{uuid}', $action)->where('uuid', PATTERN_UUID);
```

---

### Опциональные параметры

**Описание:** Параметры которые могут отсутствовать.

**Использование:**
```php
// Опциональный параметр
Route::get('/search/{query?}', function($query = null) {
    if ($query === null) {
        return "Show search form";
    }
    return "Search for: $query";
});

// С значением по умолчанию
Route::get('/page/{page?}', function($page = 1) {
    return "Page: $page";
});
```

**Сравнение:**

| Роутер | Синтаксис опциональности | Defaults | Оценка |
|--------|--------------------------|----------|--------|
| **CloudCastle** | `{param?}` | ✅ В closure | ⭐⭐⭐⭐⭐ |
| Laravel | `{param?}` | ✅ В closure | ⭐⭐⭐⭐⭐ |
| Symfony | Defaults в Route | ✅ | ⭐⭐⭐⭐ |
| FastRoute | `[{param}]` | ⚠️ Ручное | ⭐⭐⭐ |

**Плюсы CloudCastle:**
- ✅ Интуитивный синтаксис ({param?})
- ✅ Работает как в Laravel
- ✅ Defaults в closure

**Пример реального использования:**
```php
// Пагинация
Route::get('/products/{page?}', function($page = 1) {
    $products = Product::paginate(20, $page);
    return json_encode($products);
})->where('page', '[0-9]+');

// Поиск
Route::get('/search/{query?}', function($query = null) {
    if (!$query) {
        return ['results' => [], 'message' => 'Enter search query'];
    }
    return ['results' => search($query)];
});

// Фильтр
Route::get('/users/{role?}', function($role = 'all') {
    return User::byRole($role)->get();
})->where('role', 'admin|user|guest|all');
```

**Рекомендации:**
1. Используйте для пагинации
2. Используйте для опциональных фильтров
3. Всегда задавайте разумные defaults
4. Валидируйте опциональные параметры

---

### Default Values

**Описание:** Значения по умолчанию для параметров.

**Использование:**
```php
Route::get('/page/{page}', $action)
    ->defaults(['page' => 1]);

// Теперь /page и /page/5 работают
```

**Уникальность:**

| Роутер | defaults() метод | Где задается | Оценка |
|--------|------------------|--------------|--------|
| **CloudCastle** | ✅ | **Route level** | ⭐⭐⭐⭐⭐ |
| Laravel | ⚠️ | Closure | ⭐⭐⭐⭐ |
| Symfony | ✅ | Route config | ⭐⭐⭐⭐⭐ |
| FastRoute | ❌ | Manual | ⭐⭐ |

**Плюсы:**
- ✅ Явное задание defaults
- ✅ Не зависит от closure
- ✅ Документировано в маршруте

**Примеры:**
```php
// API версионирование с default
Route::get('/api/{version}/users', $action)
    ->defaults(['version' => 'v1'])
    ->where('version', 'v1|v2|v3');

// Локализация
Route::get('/{locale}/page', $action)
    ->defaults(['locale' => 'en'])
    ->where('locale', 'en|ru|de|fr');

// Sorting
Route::get('/products/{sort}', $action)
    ->defaults(['sort' => 'name'])
    ->where('sort', 'name|price|date');
```

**Рекомендации:**
1. Используйте для версионирования API
2. Используйте для локализации
3. Используйте для сортировки/фильтрации
4. Документируйте defaults

---

[⬆ Наверх](#возможности-маршрутизации---детальное-описание) | [📚 Все фичи](../ALL_FEATURES.md) | [🏠 Главная](../../../README.md)

---

© 2024 CloudCastle HTTP Router. Все права защищены.

