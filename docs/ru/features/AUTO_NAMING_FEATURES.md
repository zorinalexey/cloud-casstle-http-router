# Auto-Naming - Автоматическое именование маршрутов

[English](../../en/features/AUTO_NAMING_FEATURES.md) | **Русский** | [Deutsch](../../de/features/AUTO_NAMING_FEATURES.md) | [Français](../../fr/features/AUTO_NAMING_FEATURES.md) | [中文](../../zh/features/AUTO_NAMING_FEATURES.md)

---

## Содержание

- [Введение](#введение)
- [enableAutoNaming()](#enableautonaming)
- [disableAutoNaming()](#disableautonaming)
- [Генерация имён](#генерация-имён)
- [Примеры](#примеры)
- [Сравнение с аналогами](#сравнение-с-аналогами)

---

## Введение

Auto-Naming автоматически генерирует уникальные имена для маршрутов, которые не были явно именованы.

### Зачем это нужно?

✅ Не нужно вручную именовать каждый маршрут  
✅ Автоматическая уникальность имён  
✅ Удобно для прототипирования  
✅ Упрощает рефакторинг  

---

## enableAutoNaming()

### Описание

Включает автоматическую генерацию имён для маршрутов.

### Использование

```php
// Включить автонейминг
$router->enableAutoNaming();

// Теперь маршруты без имён получат автоматические
Route::get('/users', UserController::class);
// Автоматическое имя: "users.get"

Route::post('/posts', PostController::class);
// Автоматическое имя: "posts.post"

Route::get('/api/data', DataController::class);
// Автоматическое имя: "api.data.get"
```

### С группами

```php
$router->enableAutoNaming();

Route::group(['prefix' => '/api'], function() {
    Route::get('/users', $action);
    // Имя: "api.users.get"
    
    Route::post('/users', $action);
    // Имя: "api.users.post"
});
```

### С параметрами

```php
$router->enableAutoNaming();

Route::get('/users/{id}', $action);
// Имя: "users.id.get"

Route::get('/posts/{year}/{month}', $action);
// Имя: "posts.year.month.get"
```

---

## disableAutoNaming()

### Описание

Отключает автоматическую генерацию имён.

### Использование

```php
// Отключить
$router->disableAutoNaming();

// Теперь маршруты без имён остаются без имён
Route::get('/users', UserController::class);
// Имя: null
```

### Проверка статуса

```php
if ($router->isAutoNamingEnabled()) {
    echo "Auto-naming включен";
} else {
    echo "Auto-naming отключен";
}
```

---

## Генерация имён

### Алгоритм

Автоматические имена генерируются по формату:

```
{uri_segments}.{method}
```

**Важно:** Метод добавляется **В КОНЦЕ**, а не в начале!

### Примеры генерации

```php
$router->enableAutoNaming();

// Простые маршруты
Route::get('/');              // "root.get"
Route::get('/users');         // "users.get"
Route::post('/posts');        // "posts.post"
Route::delete('/comments');   // "comments.delete"

// С префиксами
Route::group(['prefix' => '/api'], function() {
    Route::get('/users');     // "api.users.get"
    Route::get('/posts');     // "api.posts.get"
});

// С параметрами (параметры извлекаются и включаются в имя)
Route::get('/users/{id}');           // "users.id.get"
Route::get('/users/{id}/posts');     // "users.id.posts.get"
Route::get('/posts/{year}/{month}'); // "posts.year.month.get"

// Множественные методы
Route::match(['GET', 'POST'], '/form');  // "form.get" (первый метод)
Route::any('/webhook');                  // "webhook.head" (первый из all)
```

### Обработка специальных символов

Дефисы и подчеркивания заменяются на точки:

```php
Route::get('/api-v1/users');  // "api.v1.users.get"
Route::get('/api_v2/posts');  // "api.v2.posts.get"
Route::get('/user-profile');  // "user.profile.get"
```

---

## Примеры

### RESTful API

```php
$router->enableAutoNaming();

Route::group(['prefix' => '/api/v1'], function() {
    // Все получат автоматические имена
    Route::get('/users');           // "api.v1.users.get"
    Route::get('/users/{id}');      // "api.v1.users.id.get"
    Route::post('/users');          // "api.v1.users.post"
    Route::put('/users/{id}');      // "api.v1.users.id.put"
    Route::delete('/users/{id}');   // "api.v1.users.id.delete"
    
    Route::get('/posts');           // "api.v1.posts.get"
    Route::get('/posts/{id}');      // "api.v1.posts.id.get"
});

// Использование сгенерированных имён
$url = route_url('api.v1.users.id.get', ['id' => 123]);
// /api/v1/users/123
```

### Прототипирование

```php
// Быстро создаём маршруты без именования
$router->enableAutoNaming();

Route::get('/dashboard', DashboardController::class);
Route::get('/profile', ProfileController::class);
Route::get('/settings', SettingsController::class);
Route::post('/upload', UploadController::class);

// Позже можем использовать их
$url = route_url('dashboard.get');
$url = route_url('profile.get');
```

### Смешанный режим

```php
$router->enableAutoNaming();

// Некоторые именуем явно
Route::get('/users', UserController::class)
    ->name('users.index');  // Явное имя приоритетнее

// Другие получат автоимена
Route::get('/posts', PostController::class);
// Автоимя: "posts.get"

Route::get('/comments', CommentController::class);
// Автоимя: "comments.get"
```

---

## Конфликты имён

### Обработка дубликатов

Если автоимя уже существует, второй маршрут просто перезапишет первый (стандартное поведение):

```php
$router->enableAutoNaming();

Route::get('/users', $action1);
// Имя: "users.get"

Route::get('/users', $action2);
// Имя: "users.get" (перезаписывает первый)
```

**Рекомендация:** Для дублирующихся маршрутов используйте явные имена!

### Избежание конфликтов

Используйте явные имена для важных маршрутов:

```php
$router->enableAutoNaming();

// Важный маршрут - явное имя
Route::get('/users', UserController::class)
    ->name('users.index');

// Остальные - автоимена
Route::get('/posts', PostController::class);
Route::get('/comments', CommentController::class);
```

---

## Получение списка автоименованных

```php
$router->enableAutoNaming();

// Регистрируем маршруты
Route::get('/users', $action);
Route::get('/posts', $action);
Route::get('/comments', $action)->name('comments.list'); // Явное имя

// Получаем все именованные
$named = $router->getNamedRoutes();

foreach ($named as $name => $route) {
    if (str_ends_with($name, '.get') || 
        str_ends_with($name, '.post') ||
        str_ends_with($name, '.put')) {
        echo "Auto-named: $name\n";
    } else {
        echo "Manual: $name\n";
    }
}

/*
Output:
Auto-named: users.get
Auto-named: posts.get
Manual: comments.list
*/
```

---

## Сравнение с аналогами

| Роутер | Auto-Naming | Формат | Конфликты | Оценка |
|--------|-------------|--------|-----------|--------|
| **CloudCastle** | ✅ | method.uri.segments | ✅ Суффикс | **⭐⭐⭐⭐⭐** |
| Laravel | ⚠️ Опционально | Кастомный | ⚠️ | ⭐⭐⭐ |
| Symfony | ❌ | - | - | ⭐ |
| FastRoute | ❌ | - | - | ⭐ |
| Slim | ❌ | - | - | ⭐ |

### Детальное сравнение

**CloudCastle:**
```php
✅ enableAutoNaming() / disableAutoNaming()
✅ Понятный формат (uri.method)
✅ Работает с группами
✅ Обработка параметров
✅ Обработка спецсимволов (-, _)
```

**Laravel:**
```php
⚠️ Нет встроенного auto-naming
⚠️ Требует ручное именование или пакеты
```

**Другие:**
```php
❌ Нет поддержки auto-naming
```

---

## Плюсы и минусы

### Плюсы

✅ **Скорость разработки** - не нужно думать об именах  
✅ **Уникальность** - автоматически уникальные имена  
✅ **Консистентность** - единый формат  
✅ **Рефакторинг** - при изменении URI имя обновится  

### Минусы

⚠️ **Читаемость** - автоимена менее понятные  
⚠️ **Зависимость от URI** - изменение URI = изменение имени  
⚠️ **Длинные имена** - для вложенных маршрутов  

### Когда использовать

**✅ Используйте для:**
- Прототипирования
- Внутренних маршрутов
- Большого количества простых маршрутов
- Временных endpoint

**❌ Не используйте для:**
- Публичных API (используйте явные имена)
- Критичных маршрутов
- Когда важна стабильность имён

---

## Best Practices

### 1. Смешанный подход

```php
$router->enableAutoNaming();

// Важные маршруты - явные имена
Route::get('/api/users', UserController::class)
    ->name('api.users.index');

Route::get('/api/users/{id}', UserController::class)
    ->name('api.users.show');

// Второстепенные - автоимена
Route::get('/api/stats', StatsController::class);
Route::get('/api/health', HealthController::class);
```

### 2. Префиксы для clarity

```php
Route::group(['prefix' => '/api/v1'], function() {
    // Автоимена будут содержать префикс
    Route::get('/users', $action);     // "get.api.v1.users"
    Route::get('/posts', $action);     // "get.api.v1.posts"
});
```

### 3. Документирование

```php
/**
 * Users API
 * 
 * Routes (auto-named):
 * - api.users.get (GET /api/users)
 * - api.users.post (POST /api/users)
 * - api.users.id.get (GET /api/users/{id})
 */
Route::group(['prefix' => '/api'], function() {
    Route::get('/users', $action);
    Route::post('/users', $action);
    Route::get('/users/{id}', $action);
});
```

---

## Заключение

**CloudCastle Auto-Naming - мощная возможность:**

✅ Автоматическая генерация уникальных имён  
✅ Формат: uri.segments.method (метод в конце!)  
✅ Работа с группами и параметрами  
✅ Обработка спецсимволов (-, _ → .)  
✅ enableAutoNaming() / disableAutoNaming()  

**Рекомендация:** Используйте для прототипирования и второстепенных маршрутов, явные имена - для публичных API!

---

[⬆ Наверх](#auto-naming---автоматическое-именование-маршрутов) | [📚 Все фичи](../ALL_FEATURES.md) | [🏠 Главная](../../../README.md)

© 2024 CloudCastle HTTP Router

