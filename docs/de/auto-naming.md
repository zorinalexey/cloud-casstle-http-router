[🇷🇺 Русский](ru/auto-naming.md) | [🇺🇸 English](en/auto-naming.md) | [🇩🇪 Deutsch](de/auto-naming.md) | [🇫🇷 Français](fr/auto-naming.md) | [🇨🇳 中文](zh/auto-naming.md)

[📚 Table of Contents](de/_table-of-contents.md) | [🏠 Home](de/README.md)

---

# Auto-Naming – Automatische Benennung von Routen

**Sprachen:** 🇷🇺 Russisch | [🇬🇧 Englisch](../en/auto-naming.md) | [🇩🇪 Deutsch](../de/auto-naming.md) | [🇫🇷 Français](../fr/auto-naming.md) | [🇨🇳中文](../zh/auto-naming.md)

[📚 Inhaltsverzeichnis](_table-of-contents.md) | [🏠 Startseite](README.md)

---

## 📚 Rezension

**Auto-Naming** ist eine einzigartige Funktion des CloudCastle HTTP Routers, die automatisch Namen für Routen basierend auf ihrem URI und ihrer HTTP-Methode generiert.

## 🎯 Warum brauchen Sie eine automatische Benennung?

### Problem ohne automatische Benennung

```php
// Нужно вручную именовать каждый маршрут
$router->get('/users', 'UserController@index')->name('users.index');
$router->get('/users/{id}', 'UserController@show')->name('users.show');
$router->post('/users', 'UserController@store')->name('users.store');
$router->put('/users/{id}', 'UserController@update')->name('users.update');
$router->delete('/users/{id}', 'UserController@destroy')->name('users.destroy');

// 100+ маршрутов = 100+ name() вызовов вручную!
// Риск ошибок, опечаток, дублирования
```

### Lösung zur automatischen Benennung

```php
// Включаем auto-naming
$router->enableAutoNaming();

// Маршруты именуются автоматически!
$router->get('/users', 'UserController@index');
// Auto name: users.get

$router->get('/users/{id}', 'UserController@show');
// Auto name: users.id.get

$router->post('/users', 'UserController@store');
// Auto name: users.post

// 100+ маршрутов = 0 name() вызовов!
```

## 🔧 Benutzen

### Ein-/Ausschalten

```php
use CloudCastle\Http\Router\Router;

$router = new Router();

// Включить
$router->enableAutoNaming();

// Проверить статус
if ($router->isAutoNamingEnabled()) {
    echo "Auto-naming enabled";
}

// Выключить
$router->disableAutoNaming();
```

### Fluent interface

```php
$router->enableAutoNaming()
    ->get('/users', 'UserController@index')
    ->get('/posts', 'PostController@index');
```

## 📋 Regeln zur Namensgenerierung

### 1. Einfache Routen

```php
$router->enableAutoNaming();

$router->get('/users', fn() => 'users');
// Name: users.get

$router->post('/users', fn() => 'create');
// Name: users.post

$router->get('/posts', fn() => 'posts');
// Name: posts.get
```

**Regel**: `{path}.{method}` (Kleinbuchstaben)

### 2. Routen mit Parametern

```php
$router->get('/users/{id}', fn($id) => $id);
// Name: users.id.get

$router->get('/users/{id}/posts', fn($id) => $id);
// Name: users.id.posts.get

$router->get('/users/{id}/posts/{post}', fn($id, $post) => $id);
// Name: users.id.posts.post.get
```

**Regel**: Parameter „{id}“ → Teile des Namens „.id.“

### 3. Verschachtelte Pfade

```php
$router->get('/admin/dashboard', fn() => 'dashboard');
// Name: admin.dashboard.get

$router->get('/api/v1/users', fn() => 'users');
// Name: api.v1.users.get

$router->get('/blog/posts/archive', fn() => 'archive');
// Name: blog.posts.archive.get
```

**Regel**: Schrägstriche „/“ → Punkte „.“.

### 4. Sonderzeichen

```php
$router->get('/api-v1/user_profile', fn() => 'profile');
// Name: api.v1.user.profile.get

$router->get('/some-route_with-both', fn() => 'test');
// Name: some.route.with.both.get
```

**Regel**: Bindestriche „-“ und Unterstriche „_“ → Punkte „.“.

### 5. Root-Route

```php
$router->get('/', fn() => 'home');
// Name: root.get
```

**Regel**: `/` → `root`

### 6. Mehrere Methoden

```php
$router->match(['GET', 'POST'], '/form', fn() => 'form');
// Name: form.get.post
```

**Regel**: Methoden werden mit „.“ kombiniert

### 7. Regex constraints

```php
$router->get('/users/{id:\d+}', fn($id) => $id);
// Name: users.id.get (regex игнорируется)

$router->get('/posts/{slug:[a-z-]+}', fn($slug) => $slug);
// Name: posts.slug.get (regex игнорируется)
```

**Regel**: Regex-Muster werden aus dem Namen entfernt

## 🔄 Namenspriorität

### Die automatische Benennung überschreibt NICHT explizite Namen

```php
$router->enableAutoNaming();

// Явное имя имеет приоритет
$router->get('/custom', fn() => 'custom')
    ->name('my.custom.name');

$route = $router->getRoute('my.custom.name'); // OK
$route = $router->getRoute('custom.get'); // null
```

**Regel**: Wenn „name()“ explizit aufgerufen wird, wird die automatische Benennung übersprungen

## 📊 Anwendungsbeispiele

### REST API

```php
$router->enableAutoNaming();

// users resource
$router->get('/api/users', 'UserController@index');
// Name: api.users.get

$router->post('/api/users', 'UserController@store');  
// Name: api.users.post

$router->get('/api/users/{id}', 'UserController@show');
// Name: api.users.id.get

$router->put('/api/users/{id}', 'UserController@update');
// Name: api.users.id.put

$router->delete('/api/users/{id}', 'UserController@destroy');
// Name: api.users.id.delete

// posts resource
$router->get('/api/posts', 'PostController@index');
// Name: api.posts.get

$router->get('/api/posts/{slug}', 'PostController@show');
// Name: api.posts.slug.get
```

###Versionierte API

```php
$router->enableAutoNaming();

// API v1
$router->get('/api/v1/users', 'Api\V1\UserController@index');
// Name: api.v1.users.get

$router->get('/api/v1/posts', 'Api\V1\PostController@index');
// Name: api.v1.posts.get

// API v2
$router->get('/api/v2/users', 'Api\V2\UserController@index');
// Name: api.v2.users.get

$router->get('/api/v2/posts', 'Api\V2\PostController@index');
// Name: api.v2.posts.get

// Легко различать версии!
```

###Admin-Panel

```php
$router->enableAutoNaming();

$router->group(['prefix' => 'admin/dashboard'], function($router) {
    $router->get('/stats', 'Admin\StatsController@index');
    // Name: admin.dashboard.stats.get
    
    $router->get('/users', 'Admin\UserController@index');
    // Name: admin.dashboard.users.get
    
    $router->get('/settings', 'Admin\SettingsController@index');
    // Name: admin.dashboard.settings.get
});
```

### Mit URL-Generator

```php
use CloudCastle\Http\Router\UrlGenerator;

$router->enableAutoNaming();

$router->get('/users/{id}/posts/{post}', 'PostController@show');

$generator = new UrlGenerator($router);

// Используем auto-generated имя
$url = $generator->generate('users.id.posts.post.get', [
    'id' => 123,
    'post' => 456
]);
// /users/123/posts/456
```

## 💡 Best Practices

### 1. Aktivieren Sie die automatische Benennung global

```php
// В начале приложения
$router = new Router();
$router->enableAutoNaming();

// Все маршруты автоматически именуются
require __DIR__ . '/routes/web.php';
require __DIR__ . '/routes/api.php';
```

### 2. Verwenden Sie explizite Namen für wichtige Routen

```php
$router->enableAutoNaming();

// Auto-naming для обычных маршрутов
$router->get('/users', 'UserController@index');
// Name: users.get

// Явное имя для важных/публичных маршрутов
$router->get('/checkout', 'CheckoutController@index')
    ->name('checkout'); // Лучше явное имя

$router->post('/payment/process', 'PaymentController@process')
    ->name('payment.process'); // Точный контроль
```

### 3. Struktur-URIs für Anzeigenamen

```php
// ХОРОШО: иерархическая структура
$router->get('/admin/users/list', ...);
// Name: admin.users.list.get - понятно!

// ПЛОХО: плоская структура
$router->get('/adminuserslist', ...);
// Name: adminuserslist.get - непонятно
```

### 4. Verwenden Sie Präfixe in Gruppen

```php
$router->group(['prefix' => 'api/v1'], function($router) {
    $router->get('/users', ...);
    // Name: api.v1.users.get - отлично!
    
    $router->get('/posts', ...);
    // Name: api.v1.posts.get - понятная структура!
});
```

## 📊 Statistiken und Tests

### Tests

Die automatische Benennung wird durch **18 Unit-Tests** abgedeckt:

- ✅ Ein-/Ausschalten
- ✅ Einfache Routen
- ✅ Parametrisierte Routen
- ✅ Verschachtelte Pfade
- ✅ Verschiedene HTTP-Methoden
- ✅ Root-Route
- ✅ Sonderzeichen
- ✅ Gruppen mit Präfixen
- ✅ Priorität expliziter Namen
- ✅ Mehrere Methoden
- ✅ Fluent interface

**Alle Tests bestanden ✅**

### Testbeispiele

```php
public function testAutoNamingWithSimpleRoute(): void
{
    $this->router->enableAutoNaming();
    $route = $this->router->get('/users', fn() => 'users');
    
    $this->assertEquals('users.get', $route->getName());
}

public function testAutoNamingDoesNotOverrideExplicitName(): void
{
    $this->router->enableAutoNaming();
    $route = $this->router->get('/test', fn() => 'test')
        ->name('custom.name');
    
    $this->assertEquals('custom.name', $route->getName());
}
```

## 🆚 Vergleich mit Mitbewerbern

| Router | Auto-Naming | Naming Convention | Override |
|:---|:---:|:---:|:---:|
| **CloudCastle** | **✅ Full** | **Smart** | **✅** |
| FastRoute | ❌ | - | - |
| Symfony | ⚠️ Partial | Manual | ⚠️ |
| Laravel | ⚠️ Partial | Manual | ⚠️ |
| Slim | ❌ | - | - |
| AltoRouter | ❌ | - | - |

**Nur CloudCastle bietet vollwertige automatische Benennung mit intelligenter Namensgenerierung!**

## ✅ Vorteile der automatischen Benennung

1. **Zeitersparnis**
   - Keine Notwendigkeit, sich Namen auszudenken
   - Sie müssen „->name()“ nicht mehr als 100 Mal eingeben

2. **Konsistenz**
   - Einheitliche Namensregel
   - Keine Tippfehler
   - Keine Duplizierung

3. **Vorhersehbarkeit**
   - Der Name ist anhand der URI leicht zu erraten
   - `/api/users/{id}` → `api.users.id.get`

4. **Refactoring-Sicherheit**
   - URI geändert → der Name ändert sich automatisch
   - Keine defekten Links

5. **Kompatibilität**
   - Funktioniert mit Makros
   - Funktioniert mit Gruppen
   - Funktioniert mit Loadern (YAML/XML/JSON)

## 💡 Wann zu verwenden

### ✅ Verwenden Sie die automatische Benennung, wenn:

- Eine große Anzahl von Routen (50+)
- Standard-URI-Struktur
- Brauchen Sie Konsistenz
- Sie möchten Zeit sparen

### ⚠️ Verwenden Sie die automatische Benennung nicht, wenn:

– Benutzerdefinierte Namen erforderlich (z. B. aus Gründen der Legacy-Kompatibilität)
- Spezifische Namensanforderungen
- Öffentliche API mit Abwärtskompatibilitätsgarantien

### ✅ Hybrider Ansatz (empfohlen):

```php
$router->enableAutoNaming();

// 90% маршрутов - auto-naming
$router->get('/users', 'UserController@index');
$router->get('/posts', 'PostController@index');
// ... hundreds of routes

// 10% важных маршрутов - явные имена
$router->get('/checkout', 'CheckoutController@index')
    ->name('checkout'); // публичное API

$router->post('/payment', 'PaymentController@process')
    ->name('payment.process'); // важный endpoint
```

## 📈 Beispiele für generierte Namen

| URI | Method | Auto-Generated Name |
|:---|:---:|:---:|
| `/` | GET | `root.get` |
| `/users` | GET | `users.get` |
| `/users/{id}` | GET | `users.id.get` |
| `/api/v1/users` | GET | `api.v1.users.get` |
| `/api/v1/users/{id}` | POST | `api.v1.users.id.post` |
| `/admin/dashboard/stats` | GET | `admin.dashboard.stats.get` |
| `/users/{id}/posts/{post}` | GET | `users.id.posts.post.get` |
| `/api-v2/user_profile` | GET | `api.v2.user.profile.get` |

## ✅ Fazit

Die automatische Benennung ist eine **einzigartige Funktion von CloudCastle**, die:

- ✅ **Spart Zeit** – keine manuelle Benennung erforderlich
- ✅ **Sorgt für Konsistenz** – eine Regel
- ✅ **Verhindert Fehler** – keine Tippfehler in Namen
- ✅ **Erleichtert das Refactoring** – Namen werden automatisch aktualisiert
- ✅ **Verbessert die Lesbarkeit** – vorhersehbare Namen

**Kein anderer PHP-Router bietet diese Funktionalität!**

---

*Letzte Aktualisierung: 18. Oktober 2025*

---

[📚 Inhaltsverzeichnis](_table-of-contents.md) | [🏠 Startseite](README.md)

---

[📚 Table of Contents](de/_table-of-contents.md) | [🏠 Home](de/README.md)
