# Action Resolver - Разрешение действий маршрутов

[English](../../en/features/ACTION_RESOLVER_FEATURES.md) | **Русский** | [Deutsch](../../de/features/ACTION_RESOLVER_FEATURES.md) | [Français](../../fr/features/ACTION_RESOLVER_FEATURES.md) | [中文](../../zh/features/ACTION_RESOLVER_FEATURES.md)

---

## Содержание

- [Closure Actions](#closure-actions)
- [Array Actions](#array-actions)
- [String Actions](#string-actions)
- [Invokable Controllers](#invokable-controllers)
- [Dependency Injection](#dependency-injection)
- [ActionResolver класс](#actionresolver-класс)
- [Сравнение с аналогами](#сравнение-с-аналогами)

---

## Closure Actions

### Описание

Самый простой способ - использовать анонимные функции (Closures).

### Использование

```php
// Простой closure
Route::get('/hello', function() {
    return 'Hello World!';
});

// С параметрами
Route::get('/users/{id}', function($id) {
    return "User ID: $id";
});

// С множественными параметрами
Route::get('/posts/{year}/{month}', function($year, $month) {
    return "Posts from $month/$year";
});

// С dependency injection
Route::get('/users', function(UserRepository $users) {
    return $users->all();
});

// Смешанные параметры
Route::get('/users/{id}/posts', function($id, UserRepository $users) {
    return $users->getPostsForUser($id);
});
```

### Преимущества

✅ Быстро и просто  
✅ Идеально для прототипирования  
✅ Поддержка DI  
✅ Нет дополнительных файлов  

### Недостатки

⚠️ Не переиспользуемо  
⚠️ Сложно тестировать  
⚠️ Не подходит для больших проектов  

---

## Array Actions

### Описание

Массив `[Controller::class, 'method']`.

### Использование

```php
use App\Controllers\UserController;

// Массив [класс, метод]
Route::get('/users', [UserController::class, 'index']);
Route::get('/users/{id}', [UserController::class, 'show']);
Route::post('/users', [UserController::class, 'store']);
Route::put('/users/{id}', [UserController::class, 'update']);
Route::delete('/users/{id}', [UserController::class, 'destroy']);
```

### Контроллер

```php
namespace App\Controllers;

class UserController
{
    public function index()
    {
        return User::all();
    }
    
    public function show($id)
    {
        return User::find($id);
    }
    
    public function store()
    {
        // Создание
    }
    
    public function update($id)
    {
        // Обновление
    }
    
    public function destroy($id)
    {
        // Удаление
    }
}
```

### Преимущества

✅ Четкое указание класса  
✅ IDE автодополнение  
✅ Легко находить использование  
✅ Type-safe  

---

## String Actions

### Описание

Строковый формат `"Controller@method"` или `"Controller::method"`.

### Формат @

```php
// "Controller@method"
Route::get('/users', 'UserController@index');
Route::get('/posts', 'PostController@show');
Route::get('/comments', 'CommentController@list');
```

### Формат ::

```php
// "Controller::method"
Route::get('/users', 'UserController::index');
Route::get('/posts', 'PostController::show');
```

### С namespace

```php
// Полный путь
Route::get('/users', 'App\Controllers\UserController@index');

// С namespace в группе
Route::group(['namespace' => 'App\Controllers'], function() {
    Route::get('/users', 'UserController@index');
    Route::get('/posts', 'PostController@index');
});
```

### Преимущества

✅ Компактная запись  
✅ Поддержка namespace  
✅ Laravel-style синтаксис  

### Недостатки

⚠️ Нет автодополнения IDE  
⚠️ Сложнее рефакторить  
⚠️ String-based (не type-safe)  

---

## Invokable Controllers

### Описание

Контроллеры с методом `__invoke()` - single action controllers.

### Использование

```php
// Invokable controller
Route::get('/profile', ProfileController::class);
Route::post('/contact', ContactFormController::class);
Route::get('/dashboard', DashboardController::class);
```

### Контроллер

```php
namespace App\Controllers;

class ProfileController
{
    public function __invoke($id = null)
    {
        if ($id) {
            return User::find($id);
        }
        
        return auth()->user();
    }
}
```

### С dependency injection

```php
class DashboardController
{
    public function __invoke(
        UserRepository $users,
        StatsService $stats
    ) {
        return view('dashboard', [
            'users' => $users->recent(10),
            'stats' => $stats->calculate(),
        ]);
    }
}
```

### Преимущества

✅ Один контроллер = одно действие  
✅ Чистая архитектура  
✅ Легко тестировать  
✅ SRP (Single Responsibility Principle)  

---

## Dependency Injection

### Описание

Автоматическое внедрение зависимостей в действия маршрутов.

### В Closures

```php
Route::get('/users', function(UserRepository $users) {
    return $users->all();
});

Route::post('/posts', function(
    PostRepository $posts,
    Request $request
) {
    return $posts->create($request->all());
});
```

### В контроллерах

```php
class UserController
{
    public function index(UserRepository $users, Cache $cache)
    {
        return $cache->remember('users', fn() => $users->all());
    }
    
    public function show($id, UserRepository $users)
    {
        return $users->find($id);
    }
}
```

### Смешанные параметры

Параметры маршрута + DI:

```php
Route::get('/users/{id}/posts', function(
    $id,                    // Route parameter
    UserRepository $users,  // DI
    PostRepository $posts   // DI
) {
    $user = $users->find($id);
    return $posts->forUser($user);
});
```

### Порядок параметров

CloudCastle умен enough to handle:

```php
// Route parameters первыми
Route::get('/posts/{year}/{month}', function(
    $year,
    $month,
    PostRepository $posts
) {
    return $posts->forPeriod($year, $month);
});

// Или DI первыми (тоже работает!)
Route::get('/posts/{year}/{month}', function(
    PostRepository $posts,
    $year,
    $month
) {
    return $posts->forPeriod($year, $month);
});
```

---

## ActionResolver класс

### Описание

Внутренний класс для разрешения и выполнения действий.

### API

```php
use CloudCastle\Http\Router\ActionResolver;

$resolver = new ActionResolver();

// Разрешить и выполнить
$result = $resolver->resolve($action, $parameters, $route);
```

### Типы действий

```php
// 1. Closure
$resolver->resolve(function($id) {
    return "User $id";
}, ['id' => 123]);

// 2. Array
$resolver->resolve([UserController::class, 'show'], ['id' => 123]);

// 3. String @
$resolver->resolve('UserController@show', ['id' => 123]);

// 4. String ::
$resolver->resolve('UserController::show', ['id' => 123]);

// 5. Invokable
$resolver->resolve(ProfileController::class, ['id' => 123]);
```

---

## Примеры реального использования

### RESTful API

```php
// Closures для простых endpoint
Route::get('/api/ping', function() {
    return ['status' => 'ok'];
});

// Array для ресурсов
Route::get('/api/users', [UserController::class, 'index']);
Route::get('/api/users/{id}', [UserController::class, 'show']);

// Invokable для single actions
Route::post('/api/export', ExportUsersController::class);
```

### Admin Panel

```php
Route::group(['prefix' => '/admin', 'namespace' => 'App\Admin'], function() {
    // String format
    Route::get('/dashboard', 'DashboardController@index');
    Route::get('/users', 'UserController@index');
    Route::get('/settings', 'SettingsController@show');
});
```

### Webhook Handlers

```php
// Invokable controllers для webhooks
Route::post('/webhooks/github', GithubWebhookController::class);
Route::post('/webhooks/stripe', StripeWebhookController::class);
Route::post('/webhooks/paypal', PaypalWebhookController::class);
```

---

## Сравнение с аналогами

| Тип Action | CloudCastle | Laravel | Symfony | FastRoute | Slim |
|------------|-------------|---------|---------|-----------|------|
| **Closure** | ✅ | ✅ | ✅ | ✅ | ✅ |
| **Array** | ✅ | ✅ | ✅ | ❌ | ✅ |
| **String @** | ✅ | ✅ | ❌ | ❌ | ❌ |
| **String ::** | ✅ | ❌ | ❌ | ❌ | ❌ |
| **Invokable** | ✅ | ✅ | ✅ | ❌ | ✅ |
| **DI** | ✅ | ✅ | ✅ | ❌ | ✅ |

### Уникальные возможности

✅ **String ::** - уникальный синтаксис CloudCastle  
✅ **Смешанный порядок DI** - параметры в любом порядке  
✅ **Умное разрешение** - автоматическое определение типа  

---

## Заключение

**CloudCastle поддерживает 5+ форматов actions:**

✅ Closures с DI  
✅ Array [Controller, method]  
✅ String "Controller@method"  
✅ String "Controller::method" (уникально!)  
✅ Invokable Controllers  
✅ Автоматическое DI  
✅ Гибкий порядок параметров  

**Рекомендации:**
- **Прототипы**: Closures
- **API**: Array format
- **Admin**: String format с namespace
- **Webhooks**: Invokable controllers

---

[⬆ Наверх](#action-resolver---разрешение-действий-маршрутов) | [📚 Все фичи](../ALL_FEATURES.md) | [🏠 Главная](../../../README.md)

© 2024 CloudCastle HTTP Router

