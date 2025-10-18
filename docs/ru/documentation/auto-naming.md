# Автоматическое именование маршрутов

**CloudCastle HTTP Router v1.1.1**  
**Язык**: Русский

---

## 🌍 Переводы

- **[Русский](auto-naming.md)** (текущий)
- [English](../../en/documentation/auto-naming.md)
- [Deutsch](../../de/documentation/auto-naming.md)
- [Français](../../fr/documentation/auto-naming.md)

---

## 🤖 Введение

Автоматическое именование маршрутов (Auto-Naming) - это уникальная функция CloudCastle Router, которая автоматически генерирует имена для маршрутов на основе их URI и HTTP метода.

**По умолчанию**: Отключено  
**Статус**: Stable (v1.1.1)

---

## 🎯 Зачем нужен автонейминг?

### Проблема

```php
// Без автонейминга - нужно именовать вручную
Route::get('/api/v1/users/{id}/posts/{post}', 'Controller@show')
    ->name('api.v1.users.posts.show');

Route::get('/api/v1/users/{id}/comments/{comment}', 'Controller@comment')
    ->name('api.v1.users.comments.show');

// ... и так для каждого маршрута
```

### Решение

```php
// С автонеймингом - имена генерируются автоматически!
$router->enableAutoNaming();

Route::get('/api/v1/users/{id}/posts/{post}', 'Controller@show');
// Имя: api.v1.users.id.posts.post.get

Route::get('/api/v1/users/{id}/comments/{comment}', 'Controller@comment');
// Имя: api.v1.users.id.comments.comment.get
```

---

## 🚀 Использование

### Включение автонейминга

```php
use CloudCastle\Http\Router\Router;

$router = Router::getInstance();
$router->enableAutoNaming();
```

### Отключение

```php
$router->disableAutoNaming();
```

### Проверка статуса

```php
if ($router->isAutoNamingEnabled()) {
    echo 'Автонейминг включен';
}
```

---

## 📐 Правила генерации имён

### Базовые правила

1. **Слеши** (`/`) → точки (`.`)
2. **Дефисы** (`-`) → точки (`.`)
3. **Подчёркивания** (`_`) → точки (`.`)
4. **Параметры** `{param}` → имя параметра
5. **HTTP метод** → добавляется в конец (lowercase)

### Примеры трансформации

| URI | Метод | Сгенерированное имя |
|-----|-------|---------------------|
| `/users` | GET | `users.get` |
| `/users/{id}` | GET | `users.id.get` |
| `/api/v1/users` | GET | `api.v1.users.get` |
| `/api/v1/users/{id}` | POST | `api.v1.users.id.post` |
| `/users/{id}/posts` | GET | `users.id.posts.get` |
| `/users/{id}/posts/{post}` | DELETE | `users.id.posts.post.delete` |
| `/` | GET | `root.get` |
| `/api-v1/user_profile` | GET | `api.v1.user.profile.get` |

---

## 💡 Примеры использования

### Пример 1: Простые маршруты

```php
$router->enableAutoNaming();

Route::get('/users', 'UserController@index');
// Имя: users.get

Route::get('/posts', 'PostController@index');
// Имя: posts.get

Route::get('/products', 'ProductController@index');
// Имя: products.get

// Использование
$url = route('users.get'); // /users
$route = $router->getRouteByName('posts.get');
```

### Пример 2: С параметрами

```php
$router->enableAutoNaming();

Route::get('/users/{id}', 'UserController@show');
// Имя: users.id.get

Route::get('/users/{id}/posts', 'UserController@posts');
// Имя: users.id.posts.get

Route::get('/users/{id}/posts/{post}', 'PostController@show');
// Имя: users.id.posts.post.get

// Использование
$url = route_url('users.id.get', ['id' => 123]); // /users/123
```

### Пример 3: API версионирование

```php
$router->enableAutoNaming();

Route::get('/api/v1/users', 'Api\V1\UserController@index');
// Имя: api.v1.users.get

Route::get('/api/v1/users/{id}', 'Api\V1\UserController@show');
// Имя: api.v1.users.id.get

Route::get('/api/v2/users', 'Api\V2\UserController@index');
// Имя: api.v2.users.get

// Использование
$v1Route = $router->getRouteByName('api.v1.users.get');
$v2Route = $router->getRouteByName('api.v2.users.get');
```

### Пример 4: Разные HTTP методы

```php
$router->enableAutoNaming();

Route::get('/articles', 'ArticleController@index');
// Имя: articles.get

Route::post('/articles', 'ArticleController@store');
// Имя: articles.post

Route::put('/articles/{id}', 'ArticleController@update');
// Имя: articles.id.put

Route::delete('/articles/{id}', 'ArticleController@destroy');
// Имя: articles.id.delete

// Разные имена благодаря методу!
```

---

## 🔧 Работа с группами

### Автонейминг учитывает префиксы

```php
$router->enableAutoNaming();

$router->group(['prefix' => 'admin/dashboard'], function(Router $r) {
    $r->get('/users', 'AdminController@users');
    // Имя: admin.dashboard.users.get
    // URI: admin/dashboard/users
    
    $r->get('/stats', 'AdminController@stats');
    // Имя: admin.dashboard.stats.get
    
    $r->get('/settings', 'AdminController@settings');
    // Имя: admin.dashboard.settings.get
});

// Использование
$route = $router->getRouteByName('admin.dashboard.users.get');
```

### Вложенные группы

```php
$router->enableAutoNaming();

$router->group(['prefix' => 'api'], function(Router $r) {
    $r->group(['prefix' => 'v1'], function(Router $r) {
        $r->group(['prefix' => 'admin'], function(Router $r) {
            $r->get('/users', 'Controller@users');
            // Имя: api.v1.admin.users.get
        });
    });
});
```

---

## ⚙️ Приоритет явных имён

### Явные имена НЕ перезаписываются

```php
$router->enableAutoNaming();

// Автоматическое имя
Route::get('/auto', 'Controller@auto');
// Имя: auto.get

// Явное имя - сохраняется!
Route::get('/manual', 'Controller@manual')
    ->name('my.custom.name');
// Имя: my.custom.name (НЕ manual.get)

// Использование
$autoRoute = $router->getRouteByName('auto.get');
$manualRoute = $router->getRouteByName('my.custom.name');
```

### Порядок вызовов

```php
// Вариант 1: Имя задано ДО автонейминга
Route::get('/users', 'Controller@index')
    ->name('custom.users'); // Используется это имя

$router->enableAutoNaming(); // Не влияет на уже созданные маршруты

// Вариант 2: Имя задано ПОСЛЕ автонейминга
$router->enableAutoNaming();
Route::get('/posts', 'Controller@index')
    ->name('custom.posts'); // Перезаписывает автогенерированное
```

---

## 🎨 Специальные случаи

### Корневой маршрут

```php
$router->enableAutoNaming();

Route::get('/', 'HomeController@index');
// Имя: root.get

Route::post('/', 'HomeController@post');
// Имя: root.post
```

### Спецсимволы нормализуются

```php
$router->enableAutoNaming();

Route::get('/api-v1/user_profile', 'Controller@profile');
// Имя: api.v1.user.profile.get
// Дефисы и подчёркивания заменяются точками

Route::get('/api//v1///users', 'Controller@users');
// Имя: api.v1.users.get
// Множественные слеши нормализуются
```

### Сложные regex паттерны

```php
$router->enableAutoNaming();

Route::get('/users/{id:\d+}/profile', 'Controller@profile');
// Имя: users.id.profile.get
// Regex паттерн игнорируется, используется только имя параметра

Route::get('/post/{slug:[a-z0-9-]+}', 'Controller@show');
// Имя: post.slug.get
```

### Несколько методов

```php
$router->enableAutoNaming();

Route::match(['GET', 'POST'], '/form', 'Controller@handle');
// Имя: form.get (используется первый метод)

Route::any('/webhook', 'Controller@webhook');
// Имя: webhook.get (по умолчанию GET)
```

---

## 🔍 Поиск и использование

### Получение маршрута по автогенерированному имени

```php
$router->enableAutoNaming();
Route::get('/api/v1/users/{id}', 'UserController@show');

// Поиск
$route = $router->getRouteByName('api.v1.users.id.get');

if ($route) {
    echo $route->getUri();    // /api/v1/users/{id}
    echo $route->getName();   // api.v1.users.id.get
}
```

### Генерация URL

```php
$router->enableAutoNaming();
Route::get('/users/{id}/posts/{post}', 'Controller@show');

// Генерация URL
$url = route_url('users.id.posts.post.get', [
    'id' => 123,
    'post' => 456
]);
// Результат: /users/123/posts/456
```

---

## ⚡ Производительность

### Влияние на производительность

Автонейминг добавляет минимальные накладные расходы:

```
Без автонейминга:    60,095 req/s
С автонеймингом:     59,800 req/s
Разница:             -0.5% (незначительно)
```

**Вывод**: Практически не влияет на производительность!

### Память

```
Без автонейминга:    1.45 KB/route
С автонеймингом:     1.47 KB/route
Дополнительно:       +20 bytes на имя
```

---

## 📋 Лучшие практики

### ✅ Когда использовать автонейминг

- Большое количество маршрутов (> 50)
- API с версионированием
- Консистентная структура URI
- RESTful архитектура
- Проекты с быстрой разработкой

### ❌ Когда НЕ использовать

- Маленькие проекты (< 20 маршрутов)
- Нестандартные схемы именования
- Когда нужен полный контроль над именами

### 💡 Рекомендации

```php
// Хорошо: Включить глобально для всего приложения
$router->enableAutoNaming();

// Хорошо: Переопределить только важные маршруты
Route::get('/critical', 'Controller@critical')
    ->name('app.critical.route');

// Плохо: Постоянно переключать вкл/выкл
$router->enableAutoNaming();
Route::get('/one', 'C@one');
$router->disableAutoNaming();
Route::get('/two', 'C@two');
$router->enableAutoNaming(); // Не делайте так!
```

---

## 🔧 Примеры интеграции

### С макросами

```php
$router->enableAutoNaming();

// Resource макрос
Route::resource('posts', 'PostController');
// Генерирует имена:
// posts.get, posts.post, posts.id.get, 
// posts.id.put, posts.id.delete

// API Resource
Route::apiResource('articles', 'ArticleController');
// articles.get, articles.post, articles.id.get, etc.
```

### С shortcuts

```php
$router->enableAutoNaming();

Route::get('/api/data', 'ApiController@data')
    ->api()        // API shortcuts
    ->perMinute(100);
// Имя: api.data.get
```

### В реальном приложении

```php
use CloudCastle\Http\Router\Router;

class Application
{
    private Router $router;
    
    public function __construct()
    {
        $this->router = Router::getInstance();
        $this->router->enableAutoNaming();
        $this->registerRoutes();
    }
    
    private function registerRoutes(): void
    {
        // Публичные маршруты
        $this->router->get('/', 'HomeController@index');
        $this->router->get('/about', 'PageController@about');
        
        // API v1
        $this->router->group(['prefix' => 'api/v1'], function(Router $r) {
            $r->post('/login', 'AuthController@login');
            $r->post('/register', 'AuthController@register');
            
            $r->group(['middleware' => 'auth'], function(Router $r) {
                $r->get('/profile', 'UserController@profile');
                $r->get('/users', 'UserController@index');
                $r->get('/users/{id}', 'UserController@show');
            });
        });
    }
    
    public function getLoginRoute(): string
    {
        // Предсказуемое имя!
        return route('api.v1.login.post');
    }
}
```

---

## 📖 Полный пример

```php
<?php

require 'vendor/autoload.php';

use CloudCastle\Http\Router\Router;
use CloudCastle\Http\Router\Facade\Route as RouteFacade;

// Создаём роутер с автонеймингом
$router = Router::getInstance();
$router->enableAutoNaming();

// Главная страница
$router->get('/', fn() => 'Home');
// Имя: root.get

// Пользователи
$router->get('/users', fn() => 'Users list');
// Имя: users.get

$router->get('/users/{id}', fn($id) => "User $id");
// Имя: users.id.get

// API v1
$router->group(['prefix' => 'api/v1'], function(Router $r) {
    $r->get('/users', fn() => 'API Users');
    // Имя: api.v1.users.get
    
    $r->get('/users/{id}', fn($id) => "API User $id");
    // Имя: api.v1.users.id.get
    
    $r->post('/users', fn() => 'Create user');
    // Имя: api.v1.users.post
});

// Вывод всех маршрутов с именами
echo "Зарегистрированные маршруты:\n";
foreach ($router->getRoutes() as $route) {
    printf("%-30s -> %s\n", $route->getName(), $route->getUri());
}

/* Вывод:
root.get                       -> /
users.get                      -> /users
users.id.get                   -> /users/{id}
api.v1.users.get               -> api/v1/users
api.v1.users.id.get            -> api/v1/users/{id}
api.v1.users.post              -> api/v1/users
*/
```

---

## 🎯 Преимущества

| Преимущество | Описание |
|--------------|----------|
| **Экономия времени** | Не нужно придумывать имена для каждого маршрута |
| **Консистентность** | Все имена следуют единому формату |
| **Предсказуемость** | Имя можно предсказать по URI и методу |
| **Гибкость** | Можно переопределить любое имя вручную |
| **Масштабируемость** | Упрощает работу с большим количеством маршрутов |

---

## ⚠️ Ограничения

1. **Уникальность**: Если два маршрута имеют одинаковый URI и метод, будет конфликт имён
2. **Предсказуемость**: Имена зависят от структуры URI
3. **Переопределение**: Нельзя изменить правила генерации

### Решение конфликтов

```php
$router->enableAutoNaming();

// Конфликт: оба будут иметь имя users.get
Route::get('/users', 'Controller@list');
Route::get('/users', 'Controller@all');

// Решение: задать явное имя для одного из них
Route::get('/users', 'Controller@list'); // users.get
Route::get('/users', 'Controller@all')
    ->name('users.all.list'); // Явное имя
```

---

## 🔗 См. также

- [Маршруты](routes.md)
- [Группы маршрутов](route-groups.md)
- [Примеры кода](../../../examples/auto-naming-example.php)

---

**[← Назад к оглавлению](README.md)**

