[🇷🇺 Русский](ru/shortcuts.md) | [🇺🇸 English](en/shortcuts.md) | [🇩🇪 Deutsch](de/shortcuts.md) | [🇫🇷 Français](fr/shortcuts.md) | [🇨🇳 中文](zh/shortcuts.md)

[📚 Table of Contents](de/_table-of-contents.md) | [🏠 Home](de/README.md)

---

# Routenverknüpfungen – Verknüpfungen für Routen

**Sprachen:** 🇷🇺 Russisch | [🇬🇧 Englisch](../en/shortcuts.md) | [🇩🇪 Deutsch](../de/shortcuts.md) | [🇫🇷 Français](../fr/shortcuts.md) | [🇨🇳中文](../zh/shortcuts.md)

[📚 Inhaltsverzeichnis](_table-of-contents.md) | [🏠 Startseite](README.md)

---

## 📚 Rezension

**Routenverknüpfungen** sind praktische Methoden zum schnellen Einrichten häufig verwendeter Routenkonfigurationen.

## 🎯 Middleware Shortcuts

### auth() – Authentifizierung

```php
// Вместо
$router->get('/profile', 'ProfileController@show')
    ->middleware('auth');

// Используйте
$router->get('/profile', 'ProfileController@show')
    ->auth();  // Короче и понятнее!
```

### Guest() – Nur für Gäste

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

### admin() – Administrator

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

### localhost() – Nur von localhost

```php
// Вместо
$router->get('/debug', 'DebugController@index')
    ->whitelistIp(['127.0.0.1', '::1']);

// Используйте
$router->get('/debug', 'DebugController@index')
    ->localhost();  // Автоматически добавляет localhost IPs
```

**Äquivalent**: `->whitelistIp(['127.0.0.1', '::1', 'localhost'])`

### secure() – nur HTTPS

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

###drosselStandard() – Standardlimit

```php
$router->get('/api/data', 'ApiController@data')
    ->throttleStandard();  // 60 req/min
```

**Äquivalent**: `->throttle(60, 60)` oder `->perMinute(60)`

###drosselStrict() – Strikte Einschränkung

```php
$router->post('/auth/login', 'AuthController@login')
    ->throttleStrict();  // 10 req/min
```

**Äquivalent**: `->throttle(10, 60)` oder `->perMinute(10)`

###drosselGenerous() – Großzügiges Limit

```php
$router->get('/api/premium', 'ApiController@premium')
    ->auth()
    ->throttleGenerous();  // 1000 req/min
```

**Äquivalent**: „->throttle(1000, 60)“ oder „->perMinute(1000)“.

## 🏷️ Tag Shortcuts

### public() – Öffentliche Route

```php
$router->get('/api/public', 'ApiController@public')
    ->public();  // tag('public')
```

### private() – Private Route

```php
$router->get('/internal/api', 'InternalController@api')
    ->private();  // tag('private')
```

## 🎨 Composite Shortcuts

### apiEndpoint() – Vollständige API-Endpunktkonfiguration

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

**Konfiguriert:**
- API middleware
- Ratenbegrenzung (Parameter)
- Tag 'api'

### protected() – Geschützte Ressource

```php
$router->get('/documents', 'DocumentController@index')
    ->protected();  // auth + throttle(100)
```

**Konfiguriert:**
- Auth middleware
- Standard throttle (100 req/min)

## 📋 Vollständige Liste der Verknüpfungen

| Verknüpfung | Äquivalent | Beschreibung |
|:---|:---:|:---:|
| `auth()` | `Middleware('auth')` | Benötigt Autorisierung |
| `guest()` | `Middleware('Gast')` | Nur für Gäste |
| `api()` | `middleware('api')` | API middleware |
| `admin()` | `middleware(['auth','admin'])+tag('admin')` | Admin-Zugriff |
| `localhost()` | `whitelistIp(['127.0.0.1','::1'])` | Nur localhost |
| `secure()` | `port(443)+protocol('https')` | Nur HTTPS |
| `throttleStandard()` | `throttle(60,60)` | 60 req/min |
| `throttleStrict()` | `throttle(10,60)` | 10 req/min |
| `throttleGenerous()` | `throttle(1000,60)` | 1000 req/min |
| `public()` | `tag('public')` | Öffentliches Tag |
| `private()` | `tag('private')` | Privates Tag |
| `apiEndpoint($limit)` | `api()+throttle($limit)+tag('api')` | Vollständiges API-Setup |
| `protected()` | `auth()+throttle(100)` | Geschützte Ressource |

## 🔗 Verknüpfungsketten

Verknüpfungen können kombiniert werden:

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

## 📊 Anwendungsbeispiele

### Schnelle RESTful-API

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

**Code-Reduktion: 75 %!**

###Admin-Panel

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

### Öffentliche API mit Sicherheit

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

### 1. Verwenden Sie zur besseren Lesbarkeit Tastenkombinationen

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

### 2. Erstellen Sie benutzerdefinierte Verknüpfungen für das Projekt

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

### 3. Benutzerdefinierte Verknüpfungen dokumentieren

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

## 🆚 Vergleich mit Mitbewerbern

| Router | Built-in Shortcuts | Custom Shortcuts | Chainable |
|:---|:---:|:---:|:---:|
| **CloudCastle** | **✅ 13+** | **✅ Macros** | **✅** |
| FastRoute | ❌ | ❌ | ❌ |
| Symfony | ⚠️ 3 | ⚠️ | ⚠️ |
| Laravel | ✅ 8 | ✅ | ✅ |
| Slim | ⚠️ 2 | ⚠️ | ✅ |
| AltoRouter | ❌ | ❌ | ❌ |

## ✅ Fazit

Routenverknüpfungen führen den Code aus:

- **50–75 % kürzer**
- **Besser lesbar**
- **Mehr unterstützt**
- **Weniger fehleranfällig**

CloudCastle bietet **die größte Anzahl integrierter Verknüpfungen** aller PHP-Router!

---

*Letzte Aktualisierung: 18. Oktober 2025*

---

[📚 Inhaltsverzeichnis](_table-of-contents.md) | [🏠 Startseite](README.md)

---

[📚 Table of Contents](de/_table-of-contents.md) | [🏠 Home](de/README.md)
