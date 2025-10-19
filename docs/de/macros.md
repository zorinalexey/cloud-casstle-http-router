[🇷🇺 Русский](ru/macros.md) | [🇺🇸 English](en/macros.md) | [🇩🇪 Deutsch](de/macros.md) | [🇫🇷 Français](fr/macros.md) | [🇨🇳 中文](zh/macros.md)

[📚 Table of Contents](de/_table-of-contents.md) | [🏠 Home](de/README.md)

---

# Routenmakros – Makros zum schnellen Erstellen von Routen

**Sprachen:** 🇷🇺 Russisch | [🇬🇧 Englisch](../en/macros.md) | [🇩🇪 Deutsch](../de/macros.md) | [🇫🇷 Français](../fr/macros.md) | [🇨🇳中文](../zh/macros.md)

[📚 Inhaltsverzeichnis](_table-of-contents.md) | [🏠 Startseite](README.md)

---

## 📚 Rezension

**Route Macros** ist ein leistungsstarkes System zum Erstellen mehrerer Routen mit einem Befehl. Reduziert den Code um 80–90 %.

## 🎯 Integrierte Makros

### 1. resource() - RESTful Resource

**Erstellt 7 CRUD-Operationsrouten in einer Zeile!**

```php
use CloudCastle\Http\Router\Facade\Route;

Route::resource('users', 'UserController');
```

**Erstellt Routen:**

| Method | URI | Action | Name |
|:---|:---:|:---:|:---:|
| GET | `/users` | index | `users.index` |
| GET | `/users/create` | create | `users.create` |
| POST | `/users` | store | `users.store` |
| GET | `/users/{id}` | show | `users.show` |
| GET | `/users/{id}/edit` | edit | `users.edit` |
| PUT | `/users/{id}` | update | `users.update` |
| DELETE | `/users/{id}` | destroy | `users.destroy` |

**Vergleich:**
```php
// БЕЗ MACRO (35 строк):
$router->get('/users', 'UserController@index')->name('users.index');
$router->get('/users/create', 'UserController@create')->name('users.create');
$router->post('/users', 'UserController@store')->name('users.store');
$router->get('/users/{id}', 'UserController@show')->name('users.show');
$router->get('/users/{id}/edit', 'UserController@edit')->name('users.edit');
$router->put('/users/{id}', 'UserController@update')->name('users.update');
$router->delete('/users/{id}', 'UserController@destroy')->name('users.destroy');

// С MACRO (1 строка):
Route::resource('users', 'UserController');

// Сокращение: 97%! ⚡
```

---

### 2. apiResource() - RESTful API Resource

**Erstellt API-Endpunkte mit Middleware, Drosselung und Tags!**

```php
Route::apiResource('posts', 'Api\PostController', 200);
```

**Erstellt Routen:**

| Method | URI | Action | Extras |
|:---|:---:|:---:|:---:|
| GET | `/posts` | index | api middleware, throttle 200 |
| POST | `/posts` | store | api middleware, throttle 100 |
| GET | `/posts/{id}` | show | api middleware, throttle 200 |
| PUT | `/posts/{id}` | update | api middleware, throttle 100 |
| DELETE | `/posts/{id}` | destroy | api middleware, throttle 100 |

**Unterschiede zu resources()**:
- ✅ Keine „/create“- und „/edit“-Routen (nicht erforderlich für API)
- ✅ API-Middleware automatisch
- ✅ Rate limiting (reads: 200, writes: 100)
- ✅ Tag 'api'

**Anwendung:**
- RESTful JSON APIs
- GraphQL endpoints
- Mobile app backends

---

### 3. crud() – Einfache CRUD-Operationen

**Erstellt 4 Haupt-CRUD-Routen:**

```php
Route::crud('comments', 'CommentController');
```

**Erstellt Routen:**

| Method | URI | Action | Name |
|:---|:---:|:---:|:---:|
| GET | `/comments` | index | `comments.index` |
| POST | `/comments` | create | `comments.create` |
| PUT | `/comments/{id}` | update | `comments.update` |
| DELETE | `/comments/{id}` | delete | `comments.delete` |

**Wann zu verwenden:**
- Einfache CRUD-Operationen
- Wenn die Formulare „/create“ und „/edit“ nicht benötigt werden
- Rapid Prototyping

---

### 4. auth() – Alle Authentifizierungsrouten

**Erstellt einen vollständigen Satz von Authentifizierungsrouten!**

```php
Route::auth();
```

**Erstellt Routen:**

| Method | URI | Action | Throttle |
|:---|:---:|:---:|:---:|
| GET | `/login` | showLoginForm | - |
| POST | `/login` | login | 5 req/min (strict) |
| POST | `/logout` | logout | - |
| GET | `/register` | showRegisterForm | - |
| POST | `/register` | register | 3 req/min (very strict) |
| GET | `/password/reset` | showResetForm | - |
| POST | `/password/reset` | reset | 3 req/min |

**Schutz:**
- ✅ Tarifbegrenzung beim Anmelden/Registrieren
- ✅ CSRF protection
- ✅ Tag 'auth'

**Anwendung:**
- Standard-Autorisierungssystem
- Schneller Start des Projekts
- Authentication scaffolding

---

### 5. adminPanel() – Admin-Panel

**Erstellt ein sicheres Admin-Panel mit einer IP-Whitelist!**

```php
Route::adminPanel(['192.168.1.1', '10.0.0.0/8']);
```

**Erstellt Routen:**

| URI | Action | Middleware | IP Filter |
|:---|:---:|:---:|:---:|
| `/admin/dashboard` | index | auth, admin | whitelist |
| `/admin/users` | users | auth, admin | whitelist |
| `/admin/settings` | settings | auth, admin | whitelist |

**Konfiguriert:**
- ✅ Auth + Admin middleware
- ✅ IP whitelist
- ✅ Tag 'admin'
- ✅ Throttle 500 req/min

**Anwendung:**
- Verwaltungsgremien
- Internal tools
- Verwaltungskonsole

---

### 6. apiVersion() - API Versioning

**Erstellt eine versionierte API!**

```php
Route::apiVersion('v1', function() {
    Route::get('/users', 'Api\V1\UserController@index');
    Route::get('/posts', 'Api\V1\PostController@index');
});

Route::apiVersion('v2', function() {
    Route::get('/users', 'Api\V2\UserController@index');
    Route::get('/posts', 'Api\V2\PostController@index');
});
```

**Konfiguriert:**
- ✅ Präfix „/api/v{version}“.
- ✅ API middleware
- ✅ Rate limiting
- ✅ Tag `api-v{version}`
- ✅ Namespace `Api\V{version}`

**Ergebnis:**
- `/api/v1/users` → `Api\V1\UserController@index`
- `/api/v2/users` → `Api\V2\UserController@index`

---

### 7. webhooks() - Webhook Endpoints

**Erstellt sichere Webhook-Endpunkte!**

```php
Route::webhooks(['192.0.2.1', '198.51.100.1']);
```

**Erstellt Routen:**

| Method | URI | Action | Protection |
|:---|:---:|:---:|:---:|
| POST | `/webhooks/github` | github | IP whitelist, signature |
| POST | `/webhooks/stripe` | stripe | IP whitelist, signature |
| POST | `/webhooks/slack` | slack | IP whitelist, signature |

**Konfiguriert:**
- ✅ IP whitelist
- ✅ Signature verification middleware
- ✅ High rate limit (1000 req/min)
- ✅ Tag 'webhook'

**Anwendung:**
- GitHub webhooks
- Stripe webhooks
- Payment gateways
- Third-party integrations

---

## 🔧 Erstellen benutzerdefinierter Makros

### Einfaches Makro

```php
use CloudCastle\Http\Router\RouteMacros;

// Регистрация macro
RouteMacros::register('premium', function($router, $resource, $controller) {
    $router->group([
        'prefix' => $resource,
        'middleware' => ['auth', 'premium'],
        'throttle' => 10000,
    ], function($router) use ($controller) {
        $router->get('/', "{$controller}@index");
        $router->get('/{id}', "{$controller}@show");
    });
});

// Использование
Route::premium('exclusive', 'ExclusiveController');
```

### Makro mit Parametern

```php
RouteMacros::register('microservice', function($router, $name, $port, $ip) {
    $router->group([
        'prefix' => $name,
        'domain' => "{$name}.services.local",
        'port' => $port,
        'whitelistIp' => [$ip],
        'middleware' => 'service-mesh',
    ], function($router) {
        $router->get('/health', 'HealthController@check');
        $router->get('/metrics', 'MetricsController@show');
    });
});

// Использование
Route::microservice('users', 8081, '10.0.0.1');
Route::microservice('orders', 8082, '10.0.0.2');
Route::microservice('payments', 8083, '10.0.0.3');
```

### Makro für Module

```php
RouteMacros::register('module', function($router, $moduleName, $controller) {
    $router->group([
        'prefix' => "modules/{$moduleName}",
        'namespace' => "Modules\\{$moduleName}",
        'middleware' => 'module-loader',
        'tag' => "module-{$moduleName}",
    ], function($router) use ($controller) {
        $router->get('/', "{$controller}@index");
        $router->get('/settings', "{$controller}@settings");
    });
});

// Использование
Route::module('Blog', 'BlogController');
Route::module('Shop', 'ShopController');
Route::module('Forum', 'ForumController');
```

## 📊 Code-Einsparungen

### Beispiel: CRUD für 5 Ressourcen

**Ohne Makros:**
```php
// 175 строк кода (35 строк × 5 ресурсов)
```

**Mit Makros:**
```php
Route::resource('users', 'UserController');
Route::resource('posts', 'PostController');
Route::resource('comments', 'CommentController');
Route::resource('categories', 'CategoryController');
Route::resource('tags', 'TagController');

// 5 строк кода
```

**Ersparnis: 97 %!** (170 Zeilen gegenüber 5 Zeilen)

### Beispiel: Versionierte API

**Ohne Makros:**
```php
// ~200 строк кода
$router->group(['prefix' => 'api/v1', 'middleware' => 'api'], function($router) {
    $router->get('/users', ...)->name(...)->throttle(...)->tag(...);
    $router->post('/users', ...)->name(...)->throttle(...)->tag(...);
    // ... 20 маршрутов × 5 строк каждый
});

$router->group(['prefix' => 'api/v2', 'middleware' => 'api'], function($router) {
    // ... ещё 20 маршрутов × 5 строк
});
```

**Mit Makros:**
```php
// ~20 строк кода
Route::apiVersion('v1', function() {
    Route::apiResource('users', 'Api\V1\UserController', 200);
    Route::apiResource('posts', 'Api\V1\PostController', 200);
    Route::apiResource('comments', 'Api\V1\CommentController', 100);
});

Route::apiVersion('v2', function() {
    Route::apiResource('users', 'Api\V2\UserController', 200);
    Route::apiResource('posts', 'Api\V2\PostController', 200);
});
```

**Ersparnis: 90 %!** (200 Zeilen gegenüber 20 Zeilen)

## 🆚 Vergleich mit Mitbewerbern

| Router | Built-in Macros | Resource | API Resource | Custom | Code Reduction |
|:---|:---:|:---:|:---:|:---:|:---:|
| **CloudCastle** | **✅ 7+** | **✅** | **✅** | **✅** | **80-97%** |
| FastRoute | ❌ | ❌ | ❌ | ❌ | 0% |
| Symfony | ⚠️ 2 | ⚠️ | ❌ | ⚠️ | 40% |
| Laravel | ✅ 5 | ✅ | ✅ | ✅ | 70% |
| Slim | ❌ | ❌ | ❌ | ❌ | 0% |
| AltoRouter | ❌ | ❌ | ❌ | ❌ | 0% |

## 💡 Best Practices

### 1. Verwenden Sie „resource()“ für Standard-CRUD

```php
// Для всех ресурсов с CRUD
Route::resource('users', 'UserController');
Route::resource('posts', 'PostController');
Route::resource('products', 'ProductController');
```

### 2. Verwenden Sie apiResource() für die API

```php
// RESTful API
Route::apiResource('users', 'Api\UserController', 1000);
Route::apiResource('posts', 'Api\PostController', 500);
```

### 3. Mit Versionierung kombinieren

```php
Route::apiVersion('v1', function() {
    Route::apiResource('users', 'Api\V1\UserController', 200);
    Route::apiResource('posts', 'Api\V1\PostController', 200);
});
```

### 4. Erstellen Sie benutzerdefinierte Makros für Projektmuster

```php
// Ваш специфичный паттерн
RouteMacros::register('dashboard', function($router, $name) {
    $router->group(['prefix' => "dashboards/{$name}"], function($router) use ($name) {
        $router->get('/', "Dashboard\\{$name}Controller@index");
        $router->get('/widgets', "Dashboard\\{$name}Controller@widgets");
        $router->get('/reports', "Dashboard\\{$name}Controller@reports");
    });
});

Route::dashboard('Analytics');
Route::dashboard('Sales');
Route::dashboard('Users');
```

## ✅ Fazit

Route Macros ist ein **leistungsstarkes Tool** zum Verkürzen von Code:

- ✅ **80–97 % Codereduzierung**
- ✅ **Einhaltung der RESTful-Konventionen**
- ✅ **Konsistenz**
- ✅ **Keine Tippfehler**
- ✅ **Einfach zu pflegen**

CloudCastle bietet **7 integrierte Makros** + die Möglichkeit, eigene zu erstellen – mehr als jeder Mitbewerber!

---

*Letzte Aktualisierung: 18. Oktober 2025*

---

[📚 Inhaltsverzeichnis](_table-of-contents.md) | [🏠 Startseite](README.md)

---

[📚 Table of Contents](de/_table-of-contents.md) | [🏠 Home](de/README.md)
