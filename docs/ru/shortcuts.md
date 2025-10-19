# Route Shortcuts - Сокращения для маршрутов

**Languages:** 🇷🇺 Русский | [🇬🇧 English](../en/shortcuts.md) | [🇩🇪 Deutsch](../de/shortcuts.md) | [🇫🇷 Français](../fr/shortcuts.md) | [🇨🇳 中文](../zh/shortcuts.md)

[📚 Оглавление](_table-of-contents.md) | [🏠 Главная](README.md)

---

## 📚 Обзор

**Route Shortcuts** - удобные методы для быстрой настройки часто используемых конфигураций маршрутов.

## 🎯 Middleware Shortcuts

### auth() - Аутентификация

```php
// Вместо
$router->get('/profile', 'ProfileController@show')
    ->middleware('auth');

// Используйте
$router->get('/profile', 'ProfileController@show')
    ->auth();  // Короче и понятнее!
```

### guest() - Только для гостей

```php
// Только для неавторизованных
$router->get('/login', 'AuthController@showLoginForm')
    ->guest();

$router->get('/register', 'AuthController@showRegisterForm')
    ->guest();
```

### api() - API middleware

```php
$router->get('/api/data', 'ApiController@data')
    ->api();  // API middleware + JSON headers
```

### admin() - Администратор

```php
// Вместо
$router->get('/admin/dashboard', 'AdminController@index')
    ->middleware(['auth', 'admin'])
    ->tag('admin');

// Используйте
$router->get('/admin/dashboard', 'AdminController@index')
    ->admin();  // auth + admin middleware + tag
```

## 🔒 Security Shortcuts

### localhost() - Только с localhost

```php
// Вместо
$router->get('/debug', 'DebugController@index')
    ->whitelistIp(['127.0.0.1', '::1']);

// Используйте
$router->get('/debug', 'DebugController@index')
    ->localhost();  // Автоматически добавляет localhost IPs
```

**Эквивалентно**: `->whitelistIp(['127.0.0.1', '::1', 'localhost'])`

### secure() - Только HTTPS

```php
// Вместо
$router->post('/payment', 'PaymentController@process')
    ->port(443)
    ->protocol('https');

// Используйте
$router->post('/payment', 'PaymentController@process')
    ->secure();  // HTTPS only, port 443
```

## ⚡ Throttle Shortcuts

### throttleStandard() - Стандартное ограничение

```php
$router->get('/api/data', 'ApiController@data')
    ->throttleStandard();  // 60 req/min
```

**Эквивалентно**: `->throttle(60, 60)` или `->perMinute(60)`

### throttleStrict() - Строгое ограничение

```php
$router->post('/auth/login', 'AuthController@login')
    ->throttleStrict();  // 10 req/min
```

**Эквивалентно**: `->throttle(10, 60)` или `->perMinute(10)`

### throttleGenerous() - Щедрое ограничение

```php
$router->get('/api/premium', 'ApiController@premium')
    ->auth()
    ->throttleGenerous();  // 1000 req/min
```

**Эквивалентно**: `->throttle(1000, 60)` или `->perMinute(1000)`

## 🏷️ Tag Shortcuts

### public() - Публичный маршрут

```php
$router->get('/api/public', 'ApiController@public')
    ->public();  // tag('public')
```

### private() - Приватный маршрут

```php
$router->get('/internal/api', 'InternalController@api')
    ->private();  // tag('private')
```

## 🎨 Composite Shortcuts

### apiEndpoint() - Полная настройка API endpoint

```php
// Вместо
$router->get('/api/users', 'UserController@index')
    ->middleware('api')
    ->throttle(100, 60)
    ->tag('api');

// Используйте
$router->get('/api/users', 'UserController@index')
    ->apiEndpoint(100);  // Всё в одном!
```

**Настраивает:**
- API middleware
- Rate limiting (параметр)
- Tag 'api'

### protected() - Защищённый ресурс

```php
$router->get('/documents', 'DocumentController@index')
    ->protected();  // auth + throttle(100)
```

**Настраивает:**
- Auth middleware
- Standard throttle (100 req/min)

## 📋 Полный список Shortcuts

| Shortcut | Эквивалент | Описание |
|:---|:---:|:---:|
| `auth()` | `middleware('auth')` | Требует авторизацию |
| `guest()` | `middleware('guest')` | Только для гостей |
| `api()` | `middleware('api')` | API middleware |
| `admin()` | `middleware(['auth','admin'])+tag('admin')` | Админ доступ |
| `localhost()` | `whitelistIp(['127.0.0.1','::1'])` | Только localhost |
| `secure()` | `port(443)+protocol('https')` | Только HTTPS |
| `throttleStandard()` | `throttle(60,60)` | 60 req/min |
| `throttleStrict()` | `throttle(10,60)` | 10 req/min |
| `throttleGenerous()` | `throttle(1000,60)` | 1000 req/min |
| `public()` | `tag('public')` | Публичный тег |
| `private()` | `tag('private')` | Приватный тег |
| `apiEndpoint($limit)` | `api()+throttle($limit)+tag('api')` | Полный API setup |
| `protected()` | `auth()+throttle(100)` | Защищённый ресурс |

## 🔗 Цепочки Shortcuts

Shortcuts можно комбинировать:

```php
$router->post('/api/secure/data', 'SecureController@data')
    ->secure()           // HTTPS only
    ->auth()             // Authenticated
    ->admin()            // Admin role
    ->throttleStrict()   // 10 req/min
    ->localhost()        // Localhost only
    ->name('secure.data');

// Эквивалентно длинной цепочке:
// ->port(443)
// ->protocol('https')
// ->middleware('auth')
// ->middleware('admin')
// ->tag('admin')
// ->throttle(10, 60)
// ->whitelistIp(['127.0.0.1', '::1'])
// ->name('secure.data')
```

## 📊 Примеры использования

### Быстрый RESTful API

```php
// С shortcuts - 8 строк
$router->get('/api/users', 'UserController@index')
    ->api()->throttleGenerous();

$router->post('/api/users', 'UserController@store')
    ->api()->auth()->throttleStandard();

$router->get('/api/users/{id}', 'UserController@show')
    ->api()->throttleGenerous();

$router->put('/api/users/{id}', 'UserController@update')
    ->api()->auth()->throttleStandard();

// Без shortcuts - 32 строки
$router->get('/api/users', 'UserController@index')
    ->middleware('api')
    ->throttle(1000, 60)
    ->tag('api');

$router->post('/api/users', 'UserController@store')
    ->middleware(['api', 'auth'])
    ->throttle(60, 60)
    ->tag('api');
// ... и так далее
```

**Сокращение кода: 75%!**

### Админ панель

```php
// С shortcuts
$router->group(['prefix' => 'admin'], function($router) {
    $router->get('/dashboard', 'DashboardController@index')
        ->admin()->localhost();
    
    $router->get('/users', 'UserController@index')
        ->admin()->localhost();
    
    $router->post('/settings', 'SettingsController@update')
        ->admin()->localhost()->throttleStrict();
});

// Каждый маршрут:
// - Auth + admin middleware
// - Tag 'admin'
// - Localhost only
// - Throttle (для POST)
```

### Публичный API с защитой

```php
$router->group(['prefix' => 'api/public'], function($router) {
    $router->get('/data', 'ApiController@data')
        ->apiEndpoint(100)  // api + throttle(100) + tag
        ->public();         // tag('public')
    
    $router->get('/stats', 'ApiController@stats')
        ->apiEndpoint(50);  // более строгий лимит
});
```

## 💡 Best Practices

### 1. Используйте shortcuts для читаемости

```php
// ХОРОШО: с shortcuts
$router->get('/admin', 'AdminController@index')
    ->admin()
    ->secure()
    ->localhost();

// Понятно: админ, HTTPS, локально

// ПЛОХО: без shortcuts
$router->get('/admin', 'AdminController@index')
    ->middleware(['auth', 'admin'])
    ->port(443)
    ->protocol('https')
    ->whitelistIp(['127.0.0.1', '::1'])
    ->tag('admin');

// Слишком многословно
```

### 2. Создавайте custom shortcuts для проекта

```php
// Расширение Route через макрос
Route::macro('premium', function() {
    return $this->auth()
        ->middleware('premium')
        ->throttleGenerous()
        ->tag('premium');
});

// Использование
$router->get('/premium/content', 'PremiumController@index')
    ->premium();  // Custom shortcut!
```

### 3. Документируйте кастомные shortcuts

```php
/**
 * Configure route as a premium endpoint.
 * 
 * Applies:
 * - Auth middleware
 * - Premium middleware  
 * - Generous throttle (1000 req/min)
 * - Premium tag
 */
Route::macro('premium', function() {
    // ...
});
```

## 🆚 Сравнение с конкурентами

| Router | Built-in Shortcuts | Custom Shortcuts | Chainable |
|:---|:---:|:---:|:---:|
| **CloudCastle** | **✅ 13+** | **✅ Macros** | **✅** |
| FastRoute | ❌ | ❌ | ❌ |
| Symfony | ⚠️ 3 | ⚠️ | ⚠️ |
| Laravel | ✅ 8 | ✅ | ✅ |
| Slim | ⚠️ 2 | ⚠️ | ✅ |
| AltoRouter | ❌ | ❌ | ❌ |

## ✅ Заключение

Route Shortcuts делают код:

- **На 50-75% короче**
- **Более читаемым**
- **Более поддерживаемым**
- **Менее подверженным ошибкам**

CloudCastle предоставляет **наибольшее количество встроенных shortcuts** среди всех PHP роутеров!

---

*Последнее обновление: 18 октября 2025*

---

[📚 Оглавление](_table-of-contents.md) | [🏠 Главная](README.md)

