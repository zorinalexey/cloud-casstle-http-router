[🇷🇺 Русский](ru/facade.md) | [🇺🇸 English](en/facade.md) | [🇩🇪 Deutsch](de/facade.md) | [🇫🇷 Français](fr/facade.md) | [🇨🇳 中文](zh/facade.md)

[📚 Table of Contents](de/_table-of-contents.md) | [🏠 Home](de/README.md)

---

# Fassade – Statische Nutzung des Routers

**Sprachen:** 🇷🇺 Russisch | [🇬🇧 Englisch](../en/facade.md) | [🇩🇪 Deutsch](../de/facade.md) | [🇫🇷 Français](../fr/facade.md) | [🇨🇳中文](../zh/facade.md)

[📚 Inhaltsverzeichnis](_table-of-contents.md) | [🏠 Startseite](README.md)

---

## 📚 Rezension

**Route Facade** ist eine praktische statische Schnittstelle für die Arbeit mit einem Router im Laravel-Stil.

## 🎯 Mit Fassade

### Einfaches Beispiel

```php
use CloudCastle\Http\Router\Facade\Route;

// Вместо
$router = new Router();
$router->get('/users', 'UserController@index');

// Используйте
Route::get('/users', 'UserController@index');
```

**Vorteile:**
- ✅ Kompakterer Code
- ✅ Laravel-style API
- ✅ Globaler Zugriff
- ✅ Weniger Boilerplate

## 📋 Alle Fassadenmethoden

### HTTP Methods

```php
Route::get('/users', 'UserController@index');
Route::post('/users', 'UserController@store');
Route::put('/users/{id}', 'UserController@update');
Route::delete('/users/{id}', 'UserController@destroy');
Route::patch('/users/{id}', 'UserController@patch');
Route::options('/users', 'UserController@options');

// Множественные методы
Route::match(['GET', 'POST'], '/form', 'FormController@handle');

// Все методы
Route::any('/debug', 'DebugController@handle');
```

### Groups

```php
Route::group(['prefix' => '/api'], function() {
    Route::get('/users', 'ApiController@users');
    Route::get('/posts', 'ApiController@posts');
});
```

### Middleware

```php
// Глобальный
Route::middleware('cors');
Route::middleware(['auth', 'log']);

// На маршруте
Route::get('/admin', 'AdminController@index')
    ->middleware(['auth', 'admin']);
```

### Dispatch

```php
// Автоматический dispatch
$route = Route::dispatch();

// С параметрами
$route = Route::dispatch('/users', 'GET');

// С IP
$route = Route::dispatch('/api/data', 'GET', null, '192.168.1.1');
```

### Getters

```php
// Получить маршрут по имени
$route = Route::getRoute('users.show');

// Или через helper
$route = Route::getRouteByName('users.show');

// Получить все маршруты
$all = Route::getRoutes();

// Статистика
$stats = Route::getRouteStats();
```

## 🔄 Vergleich: Instanz vs. Fassade

### Instanzbasiert (klassisch)

```php
use CloudCastle\Http\Router\Router;

$router = new Router();

$router->get('/users', 'UserController@index')
    ->name('users.index')
    ->middleware('auth');

$router->get('/posts', 'PostController@index')
    ->name('posts.index');

$router->group(['prefix' => 'api'], function($router) {
    $router->get('/data', 'ApiController@data');
});

$route = $router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
```

### Fassadenbasiert (statisch)

```php
use CloudCastle\Http\Router\Facade\Route;

Route::get('/users', 'UserController@index')
    ->name('users.index')
    ->middleware('auth');

Route::get('/posts', 'PostController@index')
    ->name('posts.index');

Route::group(['prefix' => 'api'], function() {
    Route::get('/data', 'ApiController@data');
});

$route = Route::dispatch();
```

**Reduzierung**: ~15 % weniger Code, sauberere Syntax

## 📊 Vollständige Beispielanwendung

### Routen/web.php (von Facade)

```php
use CloudCastle\Http\Router\Facade\Route;

// ============================================
// Public routes
// ============================================
Route::get('/', 'HomeController@index')->name('home');
Route::get('/about', 'AboutController@index')->name('about');
Route::get('/contact', 'ContactController@show')->name('contact');
Route::post('/contact', 'ContactController@send')
    ->throttleWithBan(5, 1, 2, 60);

// ============================================
// Auth routes
// ============================================
Route::auth(); // Макрос создаёт все auth маршруты

// ============================================
// User area
// ============================================
Route::group(['middleware' => 'auth', 'prefix' => 'user'], function() {
    Route::get('/dashboard', 'UserController@dashboard')->name('dashboard');
    Route::get('/profile', 'UserController@profile')->name('profile');
    Route::post('/profile', 'UserController@updateProfile');
    Route::get('/settings', 'SettingsController@index')->name('settings');
});

// ============================================
// Admin area
// ============================================
Route::group(['prefix' => 'admin'], function() {
    Route::get('/dashboard', 'Admin\DashboardController@index')
        ->admin()
        ->name('admin.dashboard');
    
    Route::resource('users', 'Admin\UserController');
    Route::resource('posts', 'Admin\PostController');
});

// ============================================
// API
// ============================================
Route::apiVersion('v1', function() {
    Route::apiResource('users', 'Api\V1\UserController', 1000);
    Route::apiResource('posts', 'Api\V1\PostController', 500);
});
```

### index.php (entry point)

```php
require 'vendor/autoload.php';

use CloudCastle\Http\Router\Facade\Route;

// Загрузка маршрутов
require __DIR__ . '/routes/web.php';

// Dispatch
try {
    $route = Route::dispatch();
    
    // Выполнение action
    $result = $route->run();
    
    echo $result;
} catch (Exception $e) {
    http_response_code($e->getCode() ?: 500);
    echo json_encode(['error' => $e->getMessage()]);
}
```

## 🎨 Integration mit Frameworks

### Laravel-style

```php
// Почти идентичный Laravel API!

// Laravel
Route::get('/users', 'UserController@index')->middleware('auth')->name('users.index');

// CloudCastle
Route::get('/users', 'UserController@index')->middleware('auth')->name('users.index');

// 100% совместимость синтаксиса!
```

### Migration from Laravel

Fast **keine Änderungen**:

```php
// Laravel routes/web.php
use Illuminate\Support\Facades\Route;

Route::get('/', 'HomeController@index');
Route::resource('users', 'UserController');

// CloudCastle routes/web.php
use CloudCastle\Http\Router\Facade\Route;

Route::get('/', 'HomeController@index');
Route::resource('users', 'UserController');

// Тот же код!
```

## 🔧 Erweiterte Verwendung

### Automatische Benennung mit Facade

```php
// Включить auto-naming
Route::enableAutoNaming();

Route::get('/users', 'UserController@index');
// Auto name: users.get

Route::get('/posts/{id}', 'PostController@show');
// Auto name: posts.id.get
```

### Makros mit Fassade

```php
// Использование встроенных macros
Route::resource('products', 'ProductController');
Route::apiResource('categories', 'Api\CategoryController', 200);
Route::auth();
Route::adminPanel(['192.168.1.1']);
```

### Alle Verknüpfungen sind verfügbar

```php
Route::get('/admin', 'AdminController@index')
    ->admin()           // shortcut
    ->localhost()       // shortcut
    ->secure()          // shortcut
    ->throttleStrict(); // shortcut
```

### Cache integration

```php
use CloudCastle\Http\Router\RouteCache;

$cache = new RouteCache(__DIR__ . '/cache/routes.php');

if ($cache->exists()) {
    Route::loadFromCache($cache);
} else {
    // Регистрация маршрутов
    require __DIR__ . '/routes/web.php';
}
```

## 🆚 Vergleich mit Mitbewerbern

| Feature | CloudCastle | Laravel | Symfony | Others |
|:---|:---:|:---:|:---:|:---:|
| Static Facade | ✅ | ✅ | ❌ | ❌ |
| Fluent API | ✅ | ✅ | ⚠️ | ⚠️ |
| All HTTP methods | ✅ | ✅ | ✅ | ⚠️ |
| Groups | ✅ | ✅ | ✅ | ⚠️ |
| Middleware | ✅ | ✅ | ⚠️ | ⚠️ |
| Macros | ✅ | ✅ | ❌ | ❌ |
| Shortcuts | ✅ | ⚠️ | ❌ | ❌ |
| Helpers | ✅ | ✅ | ⚠️ | ❌ |

**CloudCastle Facade = Laravel API + Weitere Funktionen!**

## 💡 Best Practices

### 1. Verwenden Sie Facade für sauberen Code

```php
// ХОРОШО: чисто и понятно
use CloudCastle\Http\Router\Facade\Route;

Route::get('/users', 'UserController@index');
Route::post('/users', 'UserController@store');

// VS Instance-based (более verbose)
$router = new Router();
$router->get('/users', 'UserController@index');
$router->post('/users', 'UserController@store');
```

### 2. Gruppieren Sie Routen in separate Dateien

```
routes/
├── web.php      # Web маршруты
├── api.php      # API маршруты
└── admin.php    # Admin маршруты
```

```php
// bootstrap/app.php
Route::group(['middleware' => 'web'], function() {
    require __DIR__ . '/../routes/web.php';
});

Route::group(['prefix' => 'api', 'middleware' => 'api'], function() {
    require __DIR__ . '/../routes/api.php';
});
```

### 3. Verwendung in Controllern

```php
class UserController
{
    public function show($id)
    {
        // Генерация URL
        $editUrl = route_url('users.edit', ['id' => $id]);
        
        // Redirect
        if (!$user) {
            return redirect(route_url('users.index'));
        }
        
        return view('users.show', compact('user', 'editUrl'));
    }
}
```

## ✅ Fazit

Route Facade führt den Code aus:

- **15–20 % kürzer**
- **Eher wie Laravel** (einfache Migration)
- **Besser lesbar**
- **Einfacher für Anfänger**

Gleichzeitig behält es **die gesamte Leistung** des CloudCastle HTTP Routers!

---

*Letzte Aktualisierung: 18. Oktober 2025*

---

[📚 Inhaltsverzeichnis](_table-of-contents.md) | [🏠 Startseite](README.md)

---

[📚 Table of Contents](de/_table-of-contents.md) | [🏠 Home](de/README.md)
