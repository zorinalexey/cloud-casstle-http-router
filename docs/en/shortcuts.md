[🇷🇺 Русский](ru/shortcuts.md) | [🇺🇸 English](en/shortcuts.md) | [🇩🇪 Deutsch](de/shortcuts.md) | [🇫🇷 Français](fr/shortcuts.md) | [🇨🇳 中文](zh/shortcuts.md)

[📚 Table of Contents](en/_table-of-contents.md) | [🏠 Home](en/README.md)

---

# Route Shortcuts - Shortcuts for routes

**Languages:** 🇷🇺 Russian | [🇬🇧 English](../en/shortcuts.md) | [🇩🇪 Deutsch](../de/shortcuts.md) | [🇫🇷 Français](../fr/shortcuts.md) | [🇨🇳中文](../zh/shortcuts.md)

[📚 Table of Contents](_table-of-contents.md) | [🏠 Home](README.md)

---

## 📚 Review

**Route Shortcuts** are convenient methods for quickly setting up frequently used route configurations.

## 🎯 Middleware Shortcuts

### auth() - Authentication

```php
// Вместо
$router->get('/profile', 'ProfileController@show')
    ->middleware('auth');

// Используйте
$router->get('/profile', 'ProfileController@show')
    ->auth();  // Короче и понятнее!
```

### guest() - For guests only

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

### admin() - Administrator

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

### localhost() - Only from localhost

```php
// Вместо
$router->get('/debug', 'DebugController@index')
    ->whitelistIp(['127.0.0.1', '::1']);

// Используйте
$router->get('/debug', 'DebugController@index')
    ->localhost();  // Автоматически добавляет localhost IPs
```

**Equivalent**: `->whitelistIp(['127.0.0.1', '::1', 'localhost'])`

### secure() - HTTPS only

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

### throttleStandard() - Standard limit

```php
$router->get('/api/data', 'ApiController@data')
    ->throttleStandard();  // 60 req/min
```

**Equivalent**: `->throttle(60, 60)` or `->perMinute(60)`

### throttleStrict() - Strict restriction

```php
$router->post('/auth/login', 'AuthController@login')
    ->throttleStrict();  // 10 req/min
```

**Equivalent**: `->throttle(10, 60)` or `->perMinute(10)`

### throttleGenerous() - Generous limit

```php
$router->get('/api/premium', 'ApiController@premium')
    ->auth()
    ->throttleGenerous();  // 1000 req/min
```

**Equivalent**: `->throttle(1000, 60)` or `->perMinute(1000)`

## 🏷️ Tag Shortcuts

### public() - Public route

```php
$router->get('/api/public', 'ApiController@public')
    ->public();  // tag('public')
```

### private() - Private route

```php
$router->get('/internal/api', 'InternalController@api')
    ->private();  // tag('private')
```

## 🎨 Composite Shortcuts

### apiEndpoint() - Full API endpoint configuration

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

**Configures:**
- API middleware
- Rate limiting (parameter)
- Tag 'api'

### protected() - Protected resource

```php
$router->get('/documents', 'DocumentController@index')
    ->protected();  // auth + throttle(100)
```

**Configures:**
- Auth middleware
- Standard throttle (100 req/min)

## 📋 Full list of Shortcuts

| Shortcut | Equivalent | Description |
|:---|:---:|:---:|
| `auth()` | `middleware('auth')` | Requires authorization |
| `guest()` | `middleware('guest')` | For guests only |
| `api()` | `middleware('api')` | API middleware |
| `admin()` | `middleware(['auth','admin'])+tag('admin')` | Admin access |
| `localhost()` | `whitelistIp(['127.0.0.1','::1'])` | localhost only |
| `secure()` | `port(443)+protocol('https')` | HTTPS only |
| `throttleStandard()` | `throttle(60,60)` | 60 req/min |
| `throttleStrict()` | `throttle(10,60)` | 10 req/min |
| `throttleGenerous()` | `throttle(1000,60)` | 1000 req/min |
| `public()` | `tag('public')` | Public tag |
| `private()` | `tag('private')` | Private tag |
| `apiEndpoint($limit)` | `api()+throttle($limit)+tag('api')` | Full API setup |
| `protected()` | `auth()+throttle(100)` | Protected resource |

## 🔗 Shortcuts chains

Shortcuts can be combined:

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

## 📊 Examples of use

### Fast RESTful API

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

**Code reduction: 75%!**

###Admin panel

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

### Public API with security

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

### 1. Use shortcuts for readability

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

### 2. Create custom shortcuts for the project

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

### 3. Document custom shortcuts

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

## 🆚 Comparison with competitors

| Router | Built-in Shortcuts | Custom Shortcuts | Chainable |
|:---|:---:|:---:|:---:|
| **CloudCastle** | **✅ 13+** | **✅ Macros** | **✅** |
| FastRoute | ❌ | ❌ | ❌ |
| Symfony | ⚠️ 3 | ⚠️ | ⚠️ |
| Laravel | ✅ 8 | ✅ | ✅ |
| Slim | ⚠️ 2 | ⚠️ | ✅ |
| AltoRouter | ❌ | ❌ | ❌ |

## ✅ Conclusion

Route Shortcuts do the code:

- **50-75% shorter**
- **More readable**
- **More supported**
- **Less error prone**

CloudCastle provides **the largest number of built-in shortcuts** of any PHP router!

---

*Last update: October 18, 2025*

---

[📚 Table of Contents](_table-of-contents.md) | [🏠 Home](README.md)

---

[📚 Table of Contents](en/_table-of-contents.md) | [🏠 Home](en/README.md)
