[🇷🇺 Русский](ru/auto-naming.md) | [🇺🇸 English](en/auto-naming.md) | [🇩🇪 Deutsch](de/auto-naming.md) | [🇫🇷 Français](fr/auto-naming.md) | [🇨🇳 中文](zh/auto-naming.md)

[📚 Table of Contents](zh/_table-of-contents.md) | [🏠 Home](zh/README.md)

---

# Auto-Naming - Автоматическое именование маршрутов

**Languages:** 🇷🇺 Русский | [🇬🇧 English](../en/auto-naming.md) | [🇩🇪 Deutsch](../de/auto-naming.md) | [🇫🇷 Français](../fr/auto-naming.md) | [🇨🇳 中文](../zh/auto-naming.md)

[📚 Оглавление](_table-of-contents.md) | [🏠 Главная](README.md)

---

## 📚 Обзор

**Auto-Naming** - уникальная фича CloudCastle HTTP Router, которая автоматически генерирует имена для маршрутов на основе их URI и HTTP метода.

## 🎯 Зачем нужно Auto-Naming?

### Проблема без Auto-Naming

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

### Решение с Auto-Naming

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

## 🔧 Использование

### Включение/выключение

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

## 📋 Правила генерации имён

### 1. Простые маршруты

```php
$router->enableAutoNaming();

$router->get('/users', fn() => 'users');
// Name: users.get

$router->post('/users', fn() => 'create');
// Name: users.post

$router->get('/posts', fn() => 'posts');
// Name: posts.get
```

**Правило**: `{path}.{method}` (lowercase)

### 2. Маршруты с параметрами

```php
$router->get('/users/{id}', fn($id) => $id);
// Name: users.id.get

$router->get('/users/{id}/posts', fn($id) => $id);
// Name: users.id.posts.get

$router->get('/users/{id}/posts/{post}', fn($id, $post) => $id);
// Name: users.id.posts.post.get
```

**Правило**: Параметры `{id}` → части имени `.id.`

### 3. Вложенные пути

```php
$router->get('/admin/dashboard', fn() => 'dashboard');
// Name: admin.dashboard.get

$router->get('/api/v1/users', fn() => 'users');
// Name: api.v1.users.get

$router->get('/blog/posts/archive', fn() => 'archive');
// Name: blog.posts.archive.get
```

**Правило**: Слэши `/` → точки `.`

### 4. Специальные символы

```php
$router->get('/api-v1/user_profile', fn() => 'profile');
// Name: api.v1.user.profile.get

$router->get('/some-route_with-both', fn() => 'test');
// Name: some.route.with.both.get
```

**Правило**: Дефисы `-` и подчеркивания `_` → точки `.`

### 5. Root маршрут

```php
$router->get('/', fn() => 'home');
// Name: root.get
```

**Правило**: `/` → `root`

### 6. Множественные методы

```php
$router->match(['GET', 'POST'], '/form', fn() => 'form');
// Name: form.get.post
```

**Правило**: Методы объединяются через `.`

### 7. Regex constraints

```php
$router->get('/users/{id:\d+}', fn($id) => $id);
// Name: users.id.get (regex игнорируется)

$router->get('/posts/{slug:[a-z-]+}', fn($slug) => $slug);
// Name: posts.slug.get (regex игнорируется)
```

**Правило**: Regex паттерны удаляются из имени

## 🔄 Приоритет имён

### Auto-naming НЕ переопределяет явные имена

```php
$router->enableAutoNaming();

// Явное имя имеет приоритет
$router->get('/custom', fn() => 'custom')
    ->name('my.custom.name');

$route = $router->getRoute('my.custom.name'); // OK
$route = $router->getRoute('custom.get'); // null
```

**Правило**: Если `name()` вызван явно, auto-naming пропускается

## 📊 Примеры использования

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

### Версионированное API

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

### Админ панель

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

### С URL Generator

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

### 1. Включайте auto-naming глобально

```php
// В начале приложения
$router = new Router();
$router->enableAutoNaming();

// Все маршруты автоматически именуются
require __DIR__ . '/routes/web.php';
require __DIR__ . '/routes/api.php';
```

### 2. Используйте явные имена для важных маршрутов

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

### 3. Структурируйте URI для понятных имён

```php
// ХОРОШО: иерархическая структура
$router->get('/admin/users/list', ...);
// Name: admin.users.list.get - понятно!

// ПЛОХО: плоская структура
$router->get('/adminuserslist', ...);
// Name: adminuserslist.get - непонятно
```

### 4. Используйте префиксы в группах

```php
$router->group(['prefix' => 'api/v1'], function($router) {
    $router->get('/users', ...);
    // Name: api.v1.users.get - отлично!
    
    $router->get('/posts', ...);
    // Name: api.v1.posts.get - понятная структура!
});
```

## 📊 Статистика и тестирование

### Тесты

Auto-naming покрыт **18 unit тестами**:

- ✅ Включение/выключение
- ✅ Простые маршруты
- ✅ Параметризованные маршруты
- ✅ Вложенные пути
- ✅ Разные HTTP методы
- ✅ Root маршрут
- ✅ Специальные символы
- ✅ Группы с префиксами
- ✅ Приоритет явных имён
- ✅ Множественные методы
- ✅ Fluent interface

**Все тесты пройдены ✅**

### Примеры тестов

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

## 🆚 Сравнение с конкурентами

| Router | Auto-Naming | Naming Convention | Override |
|:---|:---:|:---:|:---:|
| **CloudCastle** | **✅ Full** | **Smart** | **✅** |
| FastRoute | ❌ | - | - |
| Symfony | ⚠️ Partial | Manual | ⚠️ |
| Laravel | ⚠️ Partial | Manual | ⚠️ |
| Slim | ❌ | - | - |
| AltoRouter | ❌ | - | - |

**Только CloudCastle предоставляет полноценный auto-naming с умной генерацией имён!**

## ✅ Преимущества Auto-Naming

1. **Экономия времени**
   - Не нужно придумывать имена
   - Не нужно набирать `->name()` 100+ раз

2. **Консистентность**
   - Единое правило именования
   - Нет опечаток
   - Нет дублирования

3. **Предсказуемость**
   - Имя легко угадать по URI
   - `/api/users/{id}` → `api.users.id.get`

4. **Безопасность рефакторинга**
   - Изменили URI → имя изменится автоматически
   - Никаких сломанных ссылок

5. **Совместимость**
   - Работает с Macros
   - Работает с Groups
   - Работает с Loaders (YAML/XML/JSON)

## 💡 Когда использовать

### ✅ Используйте Auto-Naming если:

- Большое количество маршрутов (50+)
- Стандартная структура URI
- Нужна консистентность
- Хотите сэкономить время

### ⚠️ Не используйте Auto-Naming если:

- Нужны кастомные имена (например, для legacy compatibility)
- Специфичные требования к именованию
- Публичное API с гарантиями обратной совместимости

### ✅ Гибридный подход (рекомендуется):

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

## 📈 Примеры сгенерированных имён

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

## ✅ Заключение

Auto-Naming - это **уникальная фича CloudCastle**, которая:

- ✅ **Экономит время** - не нужно именовать вручную
- ✅ **Обеспечивает консистентность** - единое правило
- ✅ **Предотвращает ошибки** - нет опечаток в именах
- ✅ **Упрощает рефакторинг** - имена обновляются автоматически
- ✅ **Улучшает читаемость** - предсказуемые имена

**Ни один другой PHP роутер не предоставляет такой функциональности!**

---

*Последнее обновление: 18 октября 2025*

---

[📚 Оглавление](_table-of-contents.md) | [🏠 Главная](README.md)

---

[📚 Table of Contents](zh/_table-of-contents.md) | [🏠 Home](zh/README.md)
