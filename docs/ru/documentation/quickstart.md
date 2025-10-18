# Быстрый старт

**CloudCastle HTTP Router v1.1.1**  
**Язык**: Русский

---

## 🌍 Переводы

- **[Русский](quickstart.md)** (текущий)
- [English](../../en/documentation/quickstart.md)
- [Deutsch](../../de/documentation/quickstart.md)
- [Français](../../fr/documentation/quickstart.md)

---

## 📦 Установка

```bash
composer require cloudcastle/http-router
```

---

## 🚀 Первый маршрут

### Шаг 1: Создайте файл `index.php`

```php
<?php

require 'vendor/autoload.php';

use CloudCastle\Http\Router\Facade\Route;

// Простой GET маршрут
Route::get('/', function() {
    return 'Привет, CloudCastle Router!';
});

// Диспетчеризация
$result = Route::dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
echo $result;
```

### Шаг 2: Запустите встроенный сервер PHP

```bash
php -S localhost:8000
```

### Шаг 3: Откройте браузер

Перейдите по адресу: http://localhost:8000

Вы увидите: `Привет, CloudCastle Router!`

---

## 📝 Базовые примеры

### GET запрос

```php
Route::get('/users', function() {
    return json_encode(['user1', 'user2', 'user3']);
});
```

### POST запрос

```php
Route::post('/users', function() {
    // Получение данных из $_POST
    return 'Пользователь создан';
});
```

### С параметрами

```php
Route::get('/user/{id}', function($id) {
    return "Профиль пользователя #$id";
});
```

### С несколькими параметрами

```php
Route::get('/posts/{year}/{month}/{slug}', function($year, $month, $slug) {
    return "Статья: $year/$month/$slug";
});
```

---

## 🎯 HTTP методы

```php
// GET
Route::get('/resource', 'Controller@index');

// POST  
Route::post('/resource', 'Controller@store');

// PUT
Route::put('/resource/{id}', 'Controller@update');

// PATCH
Route::patch('/resource/{id}', 'Controller@patch');

// DELETE
Route::delete('/resource/{id}', 'Controller@destroy');

// Несколько методов
Route::match(['GET', 'POST'], '/form', 'Controller@handle');

// Любой метод
Route::any('/webhook', 'Controller@webhook');
```

---

## 🔧 Использование контроллеров

### Создайте контроллер

```php
// app/Controllers/UserController.php
namespace App\Controllers;

class UserController
{
    public function index()
    {
        return 'Список пользователей';
    }
    
    public function show($id)
    {
        return "Пользователь #$id";
    }
}
```

### Используйте в маршрутах

```php
Route::get('/users', 'App\\Controllers\\UserController@index');
Route::get('/users/{id}', 'App\\Controllers\\UserController@show');
```

---

## 🏷️ Именованные маршруты

```php
// Создание именованного маршрута
Route::get('/profile', 'ProfileController@show')
    ->name('profile');

// Получение URL по имени
$url = route('profile'); // /profile

// С параметрами
Route::get('/user/{id}', 'UserController@show')
    ->name('user.show');
    
$url = route_url('user.show', ['id' => 123]); // /user/123
```

---

## 🤖 Автоматическое именование

```php
use CloudCastle\Http\Router\Router;

$router = Router::getInstance();
$router->enableAutoNaming();

// GET /users -> автоматическое имя: users.get
$router->get('/users', 'UserController@index');

// GET /api/v1/posts/{id} -> автоматическое имя: api.v1.posts.id.get
$router->get('/api/v1/posts/{id}', 'PostController@show');

// Использование
$route = $router->getRouteByName('api.v1.posts.id.get');
```

[Подробнее об автонейминге →](auto-naming.md)

---

## 📂 Группы маршрутов

### С префиксом

```php
Route::group(['prefix' => 'admin'], function() {
    Route::get('/users', 'AdminController@users');     // /admin/users
    Route::get('/posts', 'AdminController@posts');     // /admin/posts
});
```

### С middleware

```php
Route::group(['middleware' => 'auth'], function() {
    Route::get('/dashboard', 'DashboardController@index');
    Route::get('/profile', 'ProfileController@show');
});
```

### Комбинированные атрибуты

```php
Route::group([
    'prefix' => 'api/v1',
    'middleware' => 'auth'
], function() {
    Route::get('/users', 'ApiController@users');
});
```

[Подробнее о группах →](route-groups.md)

---

## ⏱️ Rate Limiting

### Базовое ограничение

```php
// 60 запросов в минуту
Route::post('/api/data', 'ApiController@store')
    ->perMinute(60);

// 10 запросов в секунду
Route::get('/api/fast', 'ApiController@fast')
    ->perSecond(10);
```

### С автобаном

```php
Route::post('/login', 'AuthController@login')
    ->throttleWithBan(
        maxAttempts: 5,           // 5 попыток
        decaySeconds: 60,          // за 60 секунд
        maxViolations: 3,          // 3 нарушения
        banDurationSeconds: 7200   // бан на 2 часа
    );
```

[Подробнее о Rate Limiting →](rate-limiting.md)

---

## 🔒 Безопасность

### IP фильтрация

```php
// Whitelist
Route::get('/admin', 'AdminController@index')
    ->whitelistIp(['192.168.1.0/24', '10.0.0.1']);

// Blacklist
Route::get('/api', 'ApiController@index')
    ->blacklistIp(['1.2.3.4']);
```

### HTTPS

```php
Route::post('/login', 'AuthController@login')
    ->https();
```

[Подробнее о безопасности →](security.md)

---

## 🔗 Полный пример

```php
<?php

require 'vendor/autoload.php';

use CloudCastle\Http\Router\Facade\Route;
use CloudCastle\Http\Router\Router;

// Включить автонейминг
Router::getInstance()->enableAutoNaming();

// Главная страница
Route::get('/', function() {
    return 'Главная страница';
});

// API группа
Route::group(['prefix' => 'api/v1'], function() {
    
    // Публичные маршруты
    Route::post('/register', 'AuthController@register')
        ->perMinute(5);
    
    Route::post('/login', 'AuthController@login')
        ->throttleWithBan(5, 60, 3, 7200);
    
    // Защищенные маршруты
    Route::group(['middleware' => 'auth'], function() {
        Route::get('/users', 'UserController@index')
            ->perMinute(100);
            
        Route::get('/users/{id}', 'UserController@show')
            ->perMinute(200);
            
        Route::post('/users', 'UserController@store')
            ->perMinute(30);
    });
});

// Админ панель
Route::group([
    'prefix' => 'admin',
    'middleware' => ['auth', 'admin'],
    'whitelistIp' => ['192.168.1.0/24']
], function() {
    Route::get('/dashboard', 'AdminController@dashboard');
    Route::get('/users', 'AdminController@users');
});

// Диспетчеризация
try {
    $result = Route::dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
    echo $result;
} catch (\Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
```

---

## 📚 Следующие шаги

1. [Маршруты](routes.md) - Детальное изучение маршрутизации
2. [Middleware](middleware.md) - Создание обработчиков
3. [Производительность](performance.md) - Оптимизация

---

## 💡 Полезные ссылки

- [Примеры кода](../../../examples/)
- [API Reference](api-reference.md)
- [FAQ](introduction.md#faq)

---

**Готово!** Теперь вы можете создавать мощные маршруты с CloudCastle Router! 🚀

