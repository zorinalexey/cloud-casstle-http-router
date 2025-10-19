# 自动命名 - 路由自动命名

**语言：** 🇷🇺 俄语 | [🇬🇧 英文](../en/auto-naming.md) | [🇩🇪 德语](../de/auto-naming.md) | [🇫🇷 法语](../fr/auto-naming.md) | [🇨🇳中文](../zh/auto-naming.md)

[📚 目录](_table-of-contents.md) | [🏠主页](README.md)

---

## 📚 评论

**自动命名** 是 CloudCastle HTTP Router 的一项独特功能，可根据 URI 和 HTTP 方法自动生成路由名称。

## 🎯 为什么需要自动命名？

### 没有自动命名的问题

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

### 自动命名解决方案

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

## 🔧 使用

### 打开/关闭

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

## 📋 名称生成规则

### 1.简单路线

```php
$router->enableAutoNaming();

$router->get('/users', fn() => 'users');
// Name: users.get

$router->post('/users', fn() => 'create');
// Name: users.post

$router->get('/posts', fn() => 'posts');
// Name: posts.get
```

**规则**：`{path}.{method}`（小写）

### 2.带参数的路由

```php
$router->get('/users/{id}', fn($id) => $id);
// Name: users.id.get

$router->get('/users/{id}/posts', fn($id) => $id);
// Name: users.id.posts.get

$router->get('/users/{id}/posts/{post}', fn($id, $post) => $id);
// Name: users.id.posts.post.get
```

**规则**：参数 `{id}` → 名称 `.id.` 的部分

### 3. 嵌套路径

```php
$router->get('/admin/dashboard', fn() => 'dashboard');
// Name: admin.dashboard.get

$router->get('/api/v1/users', fn() => 'users');
// Name: api.v1.users.get

$router->get('/blog/posts/archive', fn() => 'archive');
// Name: blog.posts.archive.get
```

**规则**：斜杠`/` → 点`.`

### 4.特殊字符

```php
$router->get('/api-v1/user_profile', fn() => 'profile');
// Name: api.v1.user.profile.get

$router->get('/some-route_with-both', fn() => 'test');
// Name: some.route.with.both.get
```

**规则**：连字符“-”和下划线“_”→ 点“.”

### 5.根路由

```php
$router->get('/', fn() => 'home');
// Name: root.get
```

**规则**： `/` → `root`

### 6.多种方法

```php
$router->match(['GET', 'POST'], '/form', fn() => 'form');
// Name: form.get.post
```

**规则**：方法使用“.”组合

### 7. Regex constraints

```php
$router->get('/users/{id:\d+}', fn($id) => $id);
// Name: users.id.get (regex игнорируется)

$router->get('/posts/{slug:[a-z-]+}', fn($slug) => $slug);
// Name: posts.slug.get (regex игнорируется)
```

**规则**：从名称中删除正则表达式模式

## 🔄 名称优先级

### 自动命名不会覆盖显式名称

```php
$router->enableAutoNaming();

// Явное имя имеет приоритет
$router->get('/custom', fn() => 'custom')
    ->name('my.custom.name');

$route = $router->getRoute('my.custom.name'); // OK
$route = $router->getRoute('custom.get'); // null
```

**规则**：如果显式调用 `name()`，则跳过自动命名

## 📊 使用示例

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

###版本化 API

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

###管理面板

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

### 使用 URL 生成器

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

### 1. 全局启用自动命名

```php
// В начале приложения
$router = new Router();
$router->enableAutoNaming();

// Все маршруты автоматически именуются
require __DIR__ . '/routes/web.php';
require __DIR__ . '/routes/api.php';
```

### 2. 对重要路线使用显式名称

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

### 3. 为友好名称构建 URI

```php
// ХОРОШО: иерархическая структура
$router->get('/admin/users/list', ...);
// Name: admin.users.list.get - понятно!

// ПЛОХО: плоская структура
$router->get('/adminuserslist', ...);
// Name: adminuserslist.get - непонятно
```

### 4. 分组使用前缀

```php
$router->group(['prefix' => 'api/v1'], function($router) {
    $router->get('/users', ...);
    // Name: api.v1.users.get - отлично!
    
    $router->get('/posts', ...);
    // Name: api.v1.posts.get - понятная структура!
});
```

## 📊 统计和测试

### 测试

**18 个单元测试**涵盖了自动命名：

- ✅ 打开/关闭
- ✅简单的路线
- ✅ 参数化路线
- ✅ 嵌套路径
- ✅ 不同的 HTTP 方法
- ✅ 根路线
- ✅ 特殊字符
- ✅ 带前缀的组
- ✅ 明确名称的优先级
- ✅ 多种方法
- ✅ Fluent interface

**所有测试均通过 ✅**

### 测试示例

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

## 🆚 与竞争对手的比较

| Router | Auto-Naming | Naming Convention | Override |
|:---|:---:|:---:|:---:|
| **CloudCastle** | **✅ Full** | **Smart** | **✅** |
| FastRoute | ❌ | - | - |
| Symfony | ⚠️ Partial | Manual | ⚠️ |
| Laravel | ⚠️ Partial | Manual | ⚠️ |
| Slim | ❌ | - | - |
| AltoRouter | ❌ | - | - |

**只有 CloudCastle 提供成熟的自动命名和智能名称生成功能！**

## ✅ 自动命名的优点

1. **节省时间**
   - 无需想出名字
   - 无需输入 `->name()` 100 多次

2. **一致性**
   - 统一命名规则
   - 没有错别字
   - 没有重复

3. **可预测性**
   - 该名称很容易从 URI 中猜出
   - `/api/users/{id}` → `api.users.id.get`

4. **重构安全**
   - 更改了 URI → 名称将自动更改
   - 没有损坏的链接

5. **兼容性**
   - 与宏一起使用
   - 与团体合作
   - 与加载器一起使用（YAML/XML/JSON）

## 💡 何时使用

### ✅ 在以下情况下使用自动命名：

- 大量航线（50+）
- 标准URI结构
- 需要一致性
- 想要节省时间

### ⚠️ 如果出现以下情况，请勿使用自动命名：

- 需要自定义名称（例如，为了兼容旧版）
- 具体命名要求
- 具有向后兼容性保证的公共API

### ✅ 混合方法（推荐）：

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

## 📈 生成名称的示例

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

## ✅ 结论

自动命名是 CloudCastle 的一项**独特功能**，它：

- ✅ **节省时间** - 无需手动命名
- ✅ **提供一致性** - 一条规则
- ✅ **防止错误** - 名称中没有拼写错误
- ✅ **使重构更容易** - 名称会自动更新
- ✅ **提高可读性** - 可预测的名称

**没有其他 PHP 路由器提供此功能！**

---

*最后更新：2025 年 10 月 18 日*

---

[📚 目录](_table-of-contents.md) | [🏠主页](README.md)

