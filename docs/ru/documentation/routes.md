# Маршруты

**CloudCastle HTTP Router v1.1.0**  
**Язык**: Русский

**Переводы
**: [English](../../en/documentation/routes.md) | [Deutsch](../../de/documentation/routes.md) | [Français](../../fr/documentation/routes.md)

---

## 📋 Основы

### Простой маршрут

```php
use CloudCastle\Http\Router\Facade\Route;

Route::get('/users', function() {
    return 'User list';
});
```

### HTTP методы

```php
Route::get('/users', 'UserController@index');
Route::post('/users', 'UserController@store');
Route::put('/users/{id}', 'UserController@update');
Route::patch('/users/{id}', 'UserController@patch');
Route::delete('/users/{id}', 'UserController@destroy');
Route::options('/users', 'UserController@options');
Route::head('/users', 'UserController@head');
```

### Несколько методов

```php
Route::match(['GET', 'POST'], '/form', 'FormController@handle');
Route::any('/webhook', 'WebhookController@handle');
```

## 🔗 Параметры маршрута

### Обязательные параметры

```php
Route::get('/user/{id}', function($id) {
    return "User: $id";
});

// Несколько параметров
Route::get('/post/{year}/{month}/{slug}', function($year, $month, $slug) {
    return "$year/$month/$slug";
});
```

### Необязательные параметры

```php
Route::get('/user/{id?}', function($id = null) {
    return $id ? "User: $id" : "All users";
});
```

### Ограничения параметров

```php
// Только цифры
Route::get('/user/{id}', 'UserController@show')
    ->where('id', '\d+');

// Только буквы
Route::get('/category/{name}', 'CategoryController@show')
    ->where('name', '[a-z]+');

// Несколько ограничений
Route::get('/post/{year}/{month}', 'PostController@show')
    ->where([
        'year' => '\d{4}',
        'month' => '\d{2}'
    ]);

// Сложные паттерны
Route::get('/product/{sku}', 'ProductController@show')
    ->where('sku', '[A-Z]{3}-\d{4}');
```

## 🏷️ Именованные маршруты

```php
Route::get('/profile', 'ProfileController@show')
    ->name('profile');

// Использование
$url = route('profile');  // /profile
```

## 🤖 Автоматическое именование маршрутов

Автоматическое именование маршрутов позволяет роутеру генерировать имена для маршрутов автоматически на основе URI и HTTP метода. По умолчанию эта функция отключена.

### Включение автонейминга

```php
use CloudCastle\Http\Router\Router;

$router = Router::getInstance();
$router->enableAutoNaming();
```

### Как работает автонейминг

Автонейминг преобразует URI и метод в удобное имя по следующим правилам:

- Слеши (`/`) заменяются на точки (`.`)
- Дефисы (`-`) заменяются на точки (`.`)
- Подчёркивания (`_`) заменяются на точки (`.`)
- Параметры `{param}` заменяются на имя параметра
- HTTP метод добавляется в конец в нижнем регистре

**Примеры:**

```php
$router->enableAutoNaming();

// GET /users -> users.get
$router->get('/users', 'UserController@index');

// GET /users/{id} -> users.id.get
$router->get('/users/{id}', 'UserController@show');

// GET /api/v1/users/{id} -> api.v1.users.id.get
$router->get('/api/v1/users/{id}', 'ApiController@show');

// POST /articles -> articles.post
$router->post('/articles', 'ArticleController@store');

// PUT /articles/{id} -> articles.id.put
$router->put('/articles/{id}', 'ArticleController@update');

// DELETE /articles/{id} -> articles.id.delete
$router->delete('/articles/{id}', 'ArticleController@destroy');

// GET / -> root.get
$router->get('/', 'HomeController@index');
```

### Использование с группами

Автонейминг учитывает префиксы групп:

```php
$router->enableAutoNaming();

$router->group(['prefix' => 'admin/dashboard'], function(Router $r) {
    // admin.dashboard.users.get
    $r->get('/users', 'AdminController@users');
    
    // admin.dashboard.stats.get
    $r->get('/stats', 'AdminController@stats');
});
```

### Приоритет явных имён

Явно заданные имена маршрутов имеют приоритет над автогенерируемыми:

```php
$router->enableAutoNaming();

// Имя: auto.get
$router->get('/auto', 'Controller@auto');

// Имя: my.custom.name (явное имя не перезаписывается)
$router->get('/manual', 'Controller@manual')->name('my.custom.name');
```

### Управление автонеймингом

```php
// Включить автонейминг
$router->enableAutoNaming();

// Проверить статус
if ($router->isAutoNamingEnabled()) {
    // Автонейминг включён
}

// Отключить автонейминг
$router->disableAutoNaming();
```

### Примеры из реальной практики

```php
$router->enableAutoNaming();

// API маршруты
$router->get('/api/v1/users', 'Api\V1\UserController@index');
// Имя: api.v1.users.get

$router->get('/api/v1/users/{id}/posts/{post}', 'Api\V1\PostController@show');
// Имя: api.v1.users.id.posts.post.get

// Использование сгенерированных имён
$url = route('api.v1.users.get');
$route = Route::getRouteByName('api.v1.users.id.get');
```

### Преимущества автонейминга

- ✅ Экономия времени - не нужно придумывать имена вручную
- ✅ Консистентность - все имена следуют единому формату
- ✅ Удобство - имена понятны и предсказуемы
- ✅ Гибкость - можно переопределить любое имя вручную

**См. также:** [examples/auto-naming-example.php](../../../examples/auto-naming-example.php)

## 🏷️ Тегированные маршруты

```php
Route::get('/admin/users', 'AdminController@users')
    ->tag('admin');

Route::get('/admin/settings', 'AdminController@settings')
    ->tag(['admin', 'settings']);

// Получение по тегу
$routes = Route::getRoutesByTag('admin');
```

## 🔒 Безопасность

### HTTPS

```php
Route::post('/login', 'Auth@login')
    ->https();
```

### IP фильтрация

```php
// Whitelist
Route::get('/admin', 'AdminController@index')
    ->whitelistIp(['192.168.1.0/24']);

// Blacklist
Route::get('/api', 'ApiController@index')
    ->blacklistIp(['10.0.0.1', '10.0.0.2']);
```

### Домены

```php
Route::get('/dashboard', 'DashboardController@index')
    ->domain('admin.example.com');
```

### Порты

```php
Route::get('/metrics', 'MetricsController@index')
    ->port(9090);
```

## ⚡ Производительность

### Кеширование

```php
// Включить кеш для маршрута
Route::get('/static', fn() => 'data')
    ->cache();

// Кеш всех маршрутов
Route::cacheRoutes('cache/routes.php');
```

---

**Переводы
**: [English](../../en/documentation/routes.md) | [Deutsch](../../de/documentation/routes.md) | [Français](../../fr/documentation/routes.md)
