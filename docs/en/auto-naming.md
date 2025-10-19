[🇷🇺 Русский](ru/auto-naming.md) | [🇺🇸 English](en/auto-naming.md) | [🇩🇪 Deutsch](de/auto-naming.md) | [🇫🇷 Français](fr/auto-naming.md) | [🇨🇳 中文](zh/auto-naming.md)

[📚 Table of Contents](en/_table-of-contents.md) | [🏠 Home](en/README.md)

---

# Auto-Naming - Automatic naming of routes

**Languages:** 🇷🇺 Russian | [🇬🇧 English](../en/auto-naming.md) | [🇩🇪 Deutsch](../de/auto-naming.md) | [🇫🇷 Français](../fr/auto-naming.md) | [🇨🇳中文](../zh/auto-naming.md)

[📚 Table of Contents](_table-of-contents.md) | [🏠 Home](README.md)

---

## 📚 Review

**Auto-Naming** is a unique feature of CloudCastle HTTP Router that automatically generates names for routes based on their URI and HTTP method.

## 🎯 Why do you need Auto-Naming?

### Problem without Auto-Naming

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

### Auto-Naming Solution

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

## 🔧 Use

### Turn on/off

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

## 📋 Rules for generating names

### 1. Simple routes

```php
$router->enableAutoNaming();

$router->get('/users', fn() => 'users');
// Name: users.get

$router->post('/users', fn() => 'create');
// Name: users.post

$router->get('/posts', fn() => 'posts');
// Name: posts.get
```

**Rule**: `{path}.{method}` (lowercase)

### 2. Routes with parameters

```php
$router->get('/users/{id}', fn($id) => $id);
// Name: users.id.get

$router->get('/users/{id}/posts', fn($id) => $id);
// Name: users.id.posts.get

$router->get('/users/{id}/posts/{post}', fn($id, $post) => $id);
// Name: users.id.posts.post.get
```

**Rule**: Parameters `{id}` → parts of the name `.id.`

### 3. Nested paths

```php
$router->get('/admin/dashboard', fn() => 'dashboard');
// Name: admin.dashboard.get

$router->get('/api/v1/users', fn() => 'users');
// Name: api.v1.users.get

$router->get('/blog/posts/archive', fn() => 'archive');
// Name: blog.posts.archive.get
```

**Rule**: Slashes `/` → dots `.`

### 4. Special characters

```php
$router->get('/api-v1/user_profile', fn() => 'profile');
// Name: api.v1.user.profile.get

$router->get('/some-route_with-both', fn() => 'test');
// Name: some.route.with.both.get
```

**Rule**: Hyphens `-` and underscores `_` → dots `.`

### 5. Root route

```php
$router->get('/', fn() => 'home');
// Name: root.get
```

**Rule**: `/` → `root`

### 6. Multiple methods

```php
$router->match(['GET', 'POST'], '/form', fn() => 'form');
// Name: form.get.post
```

**Rule**: Methods are combined via `.`

### 7. Regex constraints

```php
$router->get('/users/{id:\d+}', fn($id) => $id);
// Name: users.id.get (regex игнорируется)

$router->get('/posts/{slug:[a-z-]+}', fn($slug) => $slug);
// Name: posts.slug.get (regex игнорируется)
```

**Rule**: Regex patterns are removed from the name

## 🔄 Name priority

### Auto-naming does NOT override explicit names

```php
$router->enableAutoNaming();

// Явное имя имеет приоритет
$router->get('/custom', fn() => 'custom')
    ->name('my.custom.name');

$route = $router->getRoute('my.custom.name'); // OK
$route = $router->getRoute('custom.get'); // null
```

**Rule**: If `name()` is called explicitly, auto-naming is skipped

## 📊 Examples of use

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

###Versioned API

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

###Admin panel

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

### With URL Generator

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

### 1. Enable auto-naming globally

```php
// В начале приложения
$router = new Router();
$router->enableAutoNaming();

// Все маршруты автоматически именуются
require __DIR__ . '/routes/web.php';
require __DIR__ . '/routes/api.php';
```

### 2. Use explicit names for important routes

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

### 3. Structure URIs for friendly names

```php
// ХОРОШО: иерархическая структура
$router->get('/admin/users/list', ...);
// Name: admin.users.list.get - понятно!

// ПЛОХО: плоская структура
$router->get('/adminuserslist', ...);
// Name: adminuserslist.get - непонятно
```

### 4. Use prefixes in groups

```php
$router->group(['prefix' => 'api/v1'], function($router) {
    $router->get('/users', ...);
    // Name: api.v1.users.get - отлично!
    
    $router->get('/posts', ...);
    // Name: api.v1.posts.get - понятная структура!
});
```

## 📊 Statistics and testing

### Tests

Auto-naming is covered by **18 unit tests**:

- ✅ Turn on/off
- ✅ Simple routes
- ✅ Parameterized routes
- ✅ Nested paths
- ✅ Different HTTP methods
- ✅ Root route
- ✅ Special characters
- ✅ Groups with prefixes
- ✅ Priority of explicit names
- ✅ Multiple methods
- ✅ Fluent interface

**All tests passed ✅**

### Test examples

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

## 🆚 Comparison with competitors

| Router | Auto-Naming | Naming Convention | Override |
|:---|:---:|:---:|:---:|
| **CloudCastle** | **✅ Full** | **Smart** | **✅** |
| FastRoute | ❌ | - | - |
| Symfony | ⚠️ Partial | Manual | ⚠️ |
| Laravel | ⚠️ Partial | Manual | ⚠️ |
| Slim | ❌ | - | - |
| AltoRouter | ❌ | - | - |

**Only CloudCastle provides full-fledged auto-naming with smart name generation!**

## ✅ Advantages of Auto-Naming

1. **Saving time**
   - No need to come up with names
   - No need to type `->name()` 100+ times

2. **Consistency**
   - Uniform naming rule
   - No typos
   - No duplication

3. **Predictability**
   - The name is easy to guess from the URI
   - `/api/users/{id}` → `api.users.id.get`

4. **Refactoring Safety**
   - Changed the URI → the name will change automatically
   - No broken links

5. **Compatibility**
   - Works with Macros
   - Works with Groups
   - Works with Loaders (YAML/XML/JSON)

## 💡 When to use

### ✅ Use Auto-Naming if:

- A large number of routes (50+)
- Standard URI structure
- Need consistency
- Want to save time

### ⚠️ Do not use Auto-Naming if:

- Need custom names (for example, for legacy compatibility)
- Specific naming requirements
- Public API with backward compatibility guarantees

### ✅ Hybrid approach (recommended):

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

## 📈 Examples of generated names

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

## ✅ Conclusion

Auto-Naming is a **unique feature of CloudCastle** that:

- ✅ **Saves time** - no need to name manually
- ✅ **Provides consistency** - one rule
- ✅ **Prevents errors** - no typos in names
- ✅ **Makes refactoring easier** - names are updated automatically
- ✅ **Improves readability** - predictable names

**No other PHP router provides this functionality!**

---

*Last update: October 18, 2025*

---

[📚 Table of Contents](_table-of-contents.md) | [🏠 Home](README.md)

---

[📚 Table of Contents](en/_table-of-contents.md) | [🏠 Home](en/README.md)
