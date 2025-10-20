# CloudCastle HTTP Router - 完整用户指南

[English](../en/USER_GUIDE.md) | [Русский](../ru/USER_GUIDE.md) | [Deutsch](../de/USER_GUIDE.md) | [Français](../fr/USER_GUIDE.md) | [**中文**](USER_GUIDE.md)

---

## 📚 文档导航

[README](../../README.md) | [用户指南](USER_GUIDE.md) | [功能索引](FEATURES_INDEX.md) | [API参考](API_REFERENCE.md) | [所有功能](ALL_FEATURES.md) | [测试总结](TESTS_SUMMARY.md) | [性能](PERFORMANCE_ANALYSIS.md) | [安全](SECURITY_REPORT.md) | [对比](COMPARISON.md) | [常见问题](FAQ.md) | [文档摘要](DOCUMENTATION_SUMMARY.md)

**详细文档:** [功能](features/) (22个文件) | [测试](tests/) (7个报告)

---

**版本:** 1.1.1  
**日期:** 2025年10月  
**功能:** 209+

---

## 📑 目录

1. [介绍](#介绍)
2. [安装和设置](#安装和设置)
3. [基本路由 (13个方法)](#基本路由)
4. [路由参数 (6种方式)](#路由参数)
5. [路由组 (12个属性)](#路由组)
6. [速率限制 (8个方法)](#速率限制)
7. [自动禁止系统 (7个方法)](#自动禁止系统)
8. [IP过滤 (4个方法)](#ip过滤)
9. [中间件 (6种类型)](#中间件)
10. [命名路由 (6个方法)](#命名路由)
11. [标签 (5个方法)](#标签)
12. [辅助函数 (18个函数)](#辅助函数)
13. [路由快捷方式 (14个方法)](#路由快捷方式)
14. [路由宏 (7个宏)](#路由宏)
15. [URL生成 (11个方法)](#url生成)
16. [表达式语言 (5个运算符)](#表达式语言)
17. [路由缓存 (6个方法)](#路由缓存)
18. [插件系统 (13个方法)](#插件系统)
19. [路由加载器 (5种类型)](#路由加载器)
20. [PSR支持 (3个标准)](#psr支持)
21. [动作解析器 (6种格式)](#动作解析器)
22. [统计和查询 (24个方法)](#统计和查询)
23. [安全 (12个机制)](#安全)
24. [异常 (8种类型)](#异常)
25. [CLI工具 (3个命令)](#cli工具)
26. [高级示例](#高级示例)

---

## 介绍

CloudCastle HTTP Router 是一个**高性能** (54k+ req/sec)、**安全** (OWASP Top 10) 和**功能丰富** (209+ 功能) 的 PHP 8.2+ 路由库。

### 主要特性

- ⚡ **性能:** 每秒 54,891 次请求
- 🔒 **安全:** 12+ 个内置保护机制
- 💎 **功能:** 209+ 个方法和特性
- 💾 **效率:** 每个路由 1.32 KB
- 📊 **可扩展性:** 1,160,000+ 个路由
- ✅ **可靠性:** 501 个测试，0 个错误

---

## 安装和设置

### 要求

- PHP 8.2 或更高版本
- Composer
- PSR-7/PSR-15 (可选)

### 通过 Composer 安装

```bash
composer require cloud-castle/http-router
```

### 快速开始

```php
<?php

require __DIR__ . '/vendor/autoload.php';

use CloudCastle\Http\Router\Facade\Route;

// 注册路由
Route::get('/users', fn() => 'List of users');
Route::post('/users', fn() => 'Create user');

// 分发
$route = Route::dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
echo $route->run();
```

---

## 基本路由

### 1. GET 路由

```php
use CloudCastle\Http\Router\Facade\Route;

Route::get('/users', function() {
    return 'List of users';
});
```

### 2. POST 路由

```php
Route::post('/users', function() {
    return 'Create user';
});
```

### 3. PUT 路由

```php
Route::put('/users/{id}', function($id) {
    return "Update user: $id";
});
```

### 4. PATCH 路由

```php
Route::patch('/users/{id}', function($id) {
    return "Partial update user: $id";
});
```

### 5. DELETE 路由

```php
Route::delete('/users/{id}', function($id) {
    return "Delete user: $id";
});
```

### 6. VIEW 路由 (自定义)

```php
Route::view('/preview', function() {
    return 'Preview content';
});
```

### 7. 自定义 HTTP 方法

```php
Route::custom('PURGE', '/cache', function() {
    return 'Cache purged';
});

Route::custom('TRACE', '/debug', function() {
    return 'Debug trace';
});
```

### 8. 多个 HTTP 方法

```php
Route::match(['GET', 'POST'], '/form', function() {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        return 'Show form';
    }
    return 'Process form';
});
```

### 9. 所有 HTTP 方法

```php
Route::any('/webhook', function() {
    return 'Handle any method';
});
```

### 10. 使用 Router 实例

```php
use CloudCastle\Http\Router\Router;

$router = new Router();

$router->get('/users', fn() => 'Users');
$router->post('/users', fn() => 'Create');

$route = $router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
```

### 11-13. 静态 Router 方法

```php
use CloudCastle\Http\Router\Router;

// 单例模式
Router::staticGet('/users', fn() => 'Users');
Router::staticPost('/users', fn() => 'Create');
Router::staticDelete('/users/{id}', fn($id) => "Delete: $id");
```

---

## 路由参数

### 1. 基本参数

```php
Route::get('/users/{id}', function($id) {
    return "User ID: $id";
});

Route::get('/posts/{slug}', function($slug) {
    return "Post: $slug";
});

// 多个参数
Route::get('/users/{userId}/posts/{postId}', function($userId, $postId) {
    return "User $userId, Post $postId";
});
```

### 2. 参数约束 (where)

```php
// 仅数字
Route::get('/users/{id}', $action)
    ->where('id', '[0-9]+');

// 字母和连字符
Route::get('/posts/{slug}', $action)
    ->where('slug', '[a-z0-9-]+');

// 多个约束
Route::get('/api/{version}/users/{id}', $action)
    ->where([
        'version' => 'v[0-9]+',
        'id' => '[0-9]+'
    ]);
```

### 3. 内联参数

```php
// URI 中的模式
Route::get('/users/{id:[0-9]+}', $action);
Route::get('/posts/{slug:[a-z0-9-]+}', $action);
Route::get('/files/{path:.+}', $action);
```

### 4. 可选参数

```php
Route::get('/blog/{category?}', function($category = 'all') {
    return "Category: $category";
});

Route::get('/search/{query?}/{page?}', function($query = '', $page = 1) {
    return "Search: $query, Page: $page";
});
```

### 5. 默认值

```php
Route::get('/posts/{page}', $action)
    ->defaults(['page' => 1]);

Route::get('/search/{query}/{limit}', $action)
    ->defaults([
        'query' => '',
        'limit' => 10
    ]);
```

### 6. 获取参数

```php
Route::get('/users/{id}', function($id) {
    $route = Route::current();
    $params = $route->getParameters();
    // ['id' => '123']
    
    return "User: $id";
});
```

---

## 路由组

### 1. 带前缀的组

```php
Route::group(['prefix' => '/api'], function() {
    Route::get('/users', $action);     // /api/users
    Route::get('/posts', $action);     // /api/posts
});
```

### 2. 带中间件的组

```php
Route::group(['middleware' => [AuthMiddleware::class]], function() {
    Route::get('/dashboard', $action);
    Route::get('/profile', $action);
});
```

### 3. 带域名的组

```php
Route::group(['domain' => 'api.example.com'], function() {
    Route::get('/users', $action);
    Route::get('/posts', $action);
});
```

### 4. 带端口的组

```php
Route::group(['port' => 8080], function() {
    Route::get('/admin', $action);
});
```

### 5. 带命名空间的组

```php
Route::group(['namespace' => 'App\\Controllers\\Api'], function() {
    Route::get('/users', 'UserController@index');
    Route::get('/posts', 'PostController@index');
});
```

### 6. 带 HTTPS 要求的组

```php
Route::group(['https' => true], function() {
    Route::get('/secure', $action);
    Route::post('/payment', $action);
});
```

### 7. 带协议的组

```php
Route::group(['protocols' => ['ws', 'wss']], function() {
    Route::get('/chat', $action);
    Route::get('/notifications', $action);
});
```

### 8. 带标签的组

```php
Route::group(['tags' => ['api', 'public']], function() {
    Route::get('/users', $action);
    Route::get('/posts', $action);
});
```

### 9. 带节流的组

```php
Route::group(['throttle' => [60, 1]], function() {
    Route::get('/api/data', $action);
    Route::post('/api/submit', $action);
});
```

### 10. 带 IP 白名单的组

```php
Route::group(['whitelistIp' => ['192.168.1.0/24']], function() {
    Route::get('/admin/users', $action);
    Route::get('/admin/settings', $action);
});
```

### 11. 嵌套组

```php
Route::group(['prefix' => '/api'], function() {
    Route::group(['prefix' => '/v1'], function() {
        Route::get('/users', $action);  // /api/v1/users
    });
    
    Route::group(['prefix' => '/v2'], function() {
        Route::get('/users', $action);  // /api/v2/users
    });
});
```

### 12. 组合属性

```php
Route::group([
    'prefix' => '/admin',
    'middleware' => [AuthMiddleware::class, AdminMiddleware::class],
    'domain' => 'admin.example.com',
    'port' => 443,
    'https' => true,
    'whitelistIp' => ['192.168.1.0/24'],
    'tags' => ['admin', 'protected'],
    'throttle' => [30, 1],
    'namespace' => 'App\\Controllers\\Admin',
], function() {
    Route::get('/dashboard', 'DashboardController@index');
    Route::get('/users', 'UserController@index');
});
```

### 获取 RouteGroup 对象

```php
$group = Route::group(['prefix' => '/api'], function() {
    Route::get('/users', $action);
    Route::get('/posts', $action);
});

// RouteGroup 方法
$routes = $group->getRoutes();        // 组的所有路由
$count = $group->count();             // 路由数量
$attrs = $group->getAttributes();     // 组属性
```

---

## 速率限制

### 1. 基本节流

```php
// 每分钟 60 个请求
Route::post('/api/submit', $action)
    ->throttle(60, 1);

// 每小时 100 个请求
Route::post('/api/upload', $action)
    ->throttle(100, 60);
```

### 2. TimeUnit 枚举

```php
use CloudCastle\Http\Router\TimeUnit;

// 每秒 5 个请求
Route::post('/api/fast', $action)
    ->throttle(5, TimeUnit::SECOND->value);

// 每分钟 100 个请求
Route::post('/api/normal', $action)
    ->throttle(100, TimeUnit::MINUTE->value);

// 每小时 1000 个请求
Route::post('/api/slow', $action)
    ->throttle(1000, TimeUnit::HOUR->value);

// 每天 10000 个请求
Route::post('/api/daily', $action)
    ->throttle(10000, TimeUnit::DAY->value);

// 每周 50000 个请求
Route::post('/api/weekly', $action)
    ->throttle(50000, TimeUnit::WEEK->value);

// 每月 200000 个请求
Route::post('/api/monthly', $action)
    ->throttle(200000, TimeUnit::MONTH->value);
```

### 3. 自定义节流键

```php
Route::post('/api/user-specific', $action)
    ->throttle(30, 1, function($request) {
        // 按用户 ID 限制
        return 'user_' . $request->userId;
    });

Route::post('/api/ip-specific', $action)
    ->throttle(60, 1, function($request) {
        // 按 IP 限制
        return $request->ip();
});
```

### 4. 获取 RateLimiter

```php
$route = Route::post('/api/data', $action)
    ->throttle(60, 1);

$rateLimiter = $route->getRateLimiter();
```

### 5. RateLimiter 方法

```php
use CloudCastle\Http\Router\RateLimiter;

$limiter = new RateLimiter(60, 1);  // 每分钟 60 个请求

// 检查是否超过限制
if ($limiter->tooManyAttempts('user_123')) {
    $seconds = $limiter->availableIn('user_123');
    echo "Retry after $seconds seconds";
}

// 添加尝试
$limiter->attempt('user_123');

// 剩余尝试次数
$remaining = $limiter->remaining('user_123');

// 重置计数器
$limiter->clear('user_123');

// 清除所有
$limiter->clearAll();

// 获取最大尝试次数
$max = $limiter->getMaxAttempts();

// 获取衰减周期（分钟）
$period = $limiter->getDecayMinutes();
```

### 6. 为 RateLimiter 设置 BanManager

```php
use CloudCastle\Http\Router\BanManager;

$banManager = new BanManager(5, 3600);  // 5 次违规 = 禁止 1 小时

Route::post('/login', $action)
    ->throttle(3, 1)
    ->getRateLimiter()
    ?->setBanManager($banManager);
```

### 7-8. 节流快捷方式

```php
// 每分钟 60 个请求
Route::post('/api/standard', $action)->throttleStandard();

// 每分钟 10 个请求
Route::post('/api/strict', $action)->throttleStrict();

// 每分钟 1000 个请求
Route::post('/api/generous', $action)->throttleGenerous();
```

---

## 自动禁止系统

### 1. 创建 BanManager

```php
use CloudCastle\Http\Router\BanManager;

// 5 次违规 = 禁止 1 小时 (3600 秒)
$banManager = new BanManager(
    maxViolations: 5,
    banDuration: 3600
);
```

### 2. 启用自动禁止

```php
$banManager->enableAutoBan(5);  // 5 次违规后自动禁止
```

### 3. 手动 IP 封禁

```php
// 封禁 IP 1 小时
$banManager->ban('1.2.3.4', 3600);

// 永久封禁 IP (0 秒)
$banManager->ban('5.6.7.8', 0);
```

### 4. 解禁 IP

```php
$banManager->unban('1.2.3.4');
```

### 5. 检查封禁

```php
if ($banManager->isBanned('1.2.3.4')) {
    throw new \CloudCastle\Http\Router\Exceptions\BannedException(
        'Your IP is banned'
    );
}
```

### 6. 获取封禁 IP 列表

```php
$bannedIps = $banManager->getBannedIps();
// ['1.2.3.4', '5.6.7.8']
```

### 7. 清除所有封禁

```php
$banManager->clearAll();
```

### 自动禁止完整示例

```php
use CloudCastle\Http\Router\BanManager;
use CloudCastle\Http\Router\Facade\Route;

$banManager = new BanManager(5, 3600);

Route::post('/login', function() {
    // 登录逻辑
    return 'Login success';
})
->throttle(3, 1)  // 每分钟 3 次尝试
->getRateLimiter()
?->setBanManager($banManager);

// 超过限制 5 次后 - 自动禁止 1 小时
```

---

## IP过滤

### 1. IP 白名单

```php
// 单个 IP
Route::get('/admin', $action)
    ->whitelistIp(['192.168.1.1']);

// 多个 IP
Route::get('/admin', $action)
    ->whitelistIp([
        '192.168.1.1',
        '192.168.1.2',
        '10.0.0.1'
    ]);
```

### 2. CIDR 表示法

```php
Route::get('/admin', $action)
    ->whitelistIp([
        '192.168.1.0/24',    // 192.168.1.0 - 192.168.1.255
        '10.0.0.0/8',        // 10.0.0.0 - 10.255.255.255
    ]);
```

### 3. IP 黑名单

```php
Route::get('/public', $action)
    ->blacklistIp([
        '1.2.3.4',
        '5.6.7.8'
    ]);
```

### 4. 白名单和黑名单组合

```php
Route::get('/api/data', $action)
    ->whitelistIp(['192.168.1.0/24'])  // 允许本地网络
    ->blacklistIp(['192.168.1.100']);   // 除了这个 IP
```

---

## 中间件

### 1. 全局中间件

```php
use CloudCastle\Http\Router\Middleware\CorsMiddleware;

Route::middleware([CorsMiddleware::class]);
```

### 2. 路由中间件

```php
use CloudCastle\Http\Router\Middleware\AuthMiddleware;

Route::get('/dashboard', $action)
    ->middleware([AuthMiddleware::class]);
```

### 3. 多个中间件

```php
Route::get('/admin/users', $action)
    ->middleware([
        AuthMiddleware::class,
        AdminMiddleware::class,
        LoggerMiddleware::class
    ]);
```

### 4. 内置中间件

```php
use CloudCastle\Http\Router\Middleware\{
    AuthMiddleware,
    CorsMiddleware,
    HttpsEnforcement,
    SecurityLogger,
    SsrfProtection
};

Route::get('/api/data', $action)
    ->middleware([
        CorsMiddleware::class,
        SecurityLogger::class
    ]);

Route::get('/secure', $action)
    ->middleware([HttpsEnforcement::class]);

Route::post('/webhook', $action)
    ->middleware([SsrfProtection::class]);
```

### 5. 创建自定义中间件

```php
use CloudCastle\Http\Router\Contracts\MiddlewareInterface;
use CloudCastle\Http\Router\Route;

class CustomMiddleware implements MiddlewareInterface
{
    public function handle(Route $route, callable $next): mixed
    {
        // 之前的逻辑
        echo "Before route execution\n";
        
        // 执行路由
        $response = $next($route);
        
        // 之后的逻辑
        echo "After route execution\n";
        
        return $response;
    }
}

Route::get('/test', $action)
    ->middleware([CustomMiddleware::class]);
```

### 6. MiddlewareDispatcher

```php
use CloudCastle\Http\Router\MiddlewareDispatcher;

$dispatcher = new MiddlewareDispatcher();

$dispatcher->add(AuthMiddleware::class);
$dispatcher->add(LoggerMiddleware::class);

$response = $dispatcher->dispatch($route, function($route) {
    return $route->run();
});
```

---

## 命名路由

### 1. 分配名称

```php
Route::get('/users/{id}', $action)
    ->name('users.show');

Route::post('/users', $action)
    ->name('users.store');
```

### 2. 按名称获取路由

```php
$route = Route::getRouteByName('users.show');
```

### 3. 当前路由名称

```php
$name = Route::currentRouteName();
// 'users.show'
```

### 4. 检查当前路由名称

```php
if (Route::currentRouteNamed('users.show')) {
    echo "Viewing user";
}
```

### 5. 自动命名

```php
// 启用自动命名
Route::enableAutoNaming();

// 路由将自动获得名称
Route::get('/users', $action);       // auto: users.get
Route::post('/users', $action);      // auto: users.post
Route::get('/users/{id}', $action);  // auto: users.id.get

// API 示例
Route::get('/api/v1/users', $action);         // auto: api.v1.users.get
Route::post('/api/v1/users/{id}', $action);   // auto: api.v1.users.id.post

// 根路由
Route::get('/', $action);                     // auto: root.get

// 特殊字符被规范化
Route::get('/api-v1/user_profile', $action);  // auto: api.v1.user.profile.get

// 禁用自动命名
Route::disableAutoNaming();

// 检查状态
$enabled = Route::router()->isAutoNamingEnabled();
```

### 6. 获取所有命名路由

```php
$namedRoutes = Route::getNamedRoutes();
// ['users.show' => Route, 'users.store' => Route, ...]
```

---

## 标签

### 1. 添加单个标签

```php
Route::get('/api/users', $action)
    ->tag('api');
```

### 2. 多个标签

```php
Route::get('/api/public/posts', $action)
    ->tag(['api', 'public', 'posts']);
```

### 3. 按标签获取路由

```php
$apiRoutes = Route::getRoutesByTag('api');
```

### 4. 检查标签存在

```php
if (Route::router()->hasTag('api')) {
    echo "Has API routes";
}
```

### 5. 获取所有标签

```php
$allTags = Route::router()->getAllTags();
// ['api', 'public', 'admin', ...]
```

---

## 辅助函数

### 1. route()

```php
// 按名称获取路由
$route = route('users.show');
```

### 2. current_route()

```php
// 获取当前路由
$current = current_route();
echo $current->getUri();
```

### 3. previous_route()

```php
// 获取前一个路由
$previous = previous_route();
```

### 4. route_is()

```php
// 检查路由名称 (支持通配符)
if (route_is('users.*')) {
    echo "User route";
}

if (route_is('admin.users.show')) {
    echo "Admin user show";
}
```

### 5. route_name()

```php
// 获取当前路由名称
$name = route_name();
// 'users.show'
```

### 6. router()

```php
// 获取路由器实例
$router = router();
$routes = $router->getRoutes();
```

### 7. dispatch_route()

```php
// 分发路由
$route = dispatch_route('/users/123', 'GET');
```

### 8. route_url()

```php
// 生成 URL
$url = route_url('users.show', ['id' => 123]);
// '/users/123'

$url = route_url('posts.show', ['slug' => 'hello-world']);
// '/posts/hello-world'
```

### 9. route_has()

```php
// 检查路由存在
if (route_has('users.show')) {
    echo "Route exists";
}
```

### 10. route_stats()

```php
// 获取路由统计
$stats = route_stats();
/*
[
    'total' => 150,
    'methods' => ['GET' => 80, 'POST' => 40, ...],
    'named' => 120,
    'with_middleware' => 60,
    ...
]
*/
```

### 11. routes_by_tag()

```php
// 按标签获取路由
$apiRoutes = routes_by_tag('api');
```

### 12. route_back()

```php
// 返回前一个路由
$previous = route_back();
```

### 13-18. 其他辅助函数

```php
// 检查当前路由是否命名
if (route_is('users.show')) {
    // ...
}

// 获取当前路由参数
$route = current_route();
$params = $route->getParameters();

// 获取当前路由中间件
$middleware = current_route()->getMiddleware();

// 获取当前路由标签
$tags = current_route()->getTags();
```

---

## 路由快捷方式

### 1. auth()

```php
Route::get('/dashboard', $action)->auth();
// 添加 AuthMiddleware
```

### 2. guest()

```php
Route::get('/login', $action)->guest();
// 仅用于未认证用户
```

### 3. api()

```php
Route::get('/api/data', $action)->api();
// API 中间件
```

### 4. web()

```php
Route::get('/page', $action)->web();
// Web 中间件 (CSRF, Session, etc.)
```

### 5. cors()

```php
Route::get('/api/public', $action)->cors();
// CorsMiddleware
```

### 6. localhost()

```php
Route::get('/debug', $action)->localhost();
// 仅 localhost (127.0.0.1)
```

### 7. secure()

```php
Route::get('/payment', $action)->secure();
// 仅 HTTPS
```

### 8-10. 节流快捷方式

```php
// 每分钟 60 个请求 (标准)
Route::post('/api/data', $action)->throttleStandard();

// 每分钟 10 个请求 (严格)
Route::post('/api/critical', $action)->throttleStrict();

// 每分钟 1000 个请求 (宽松)
Route::post('/api/bulk', $action)->throttleGenerous();
```

### 11. public()

```php
Route::get('/page', $action)->public();
// 标签 'public'
```

### 12. private()

```php
Route::get('/page', $action)->private();
// 标签 'private'
```

### 13. admin()

```php
Route::get('/admin/users', $action)->admin();
// AuthMiddleware + AdminMiddleware + HTTPS + IP 白名单
```

### 14. apiEndpoint()

```php
Route::get('/api/data', $action)->apiEndpoint();
// API + CORS + JSON + throttle
```

---

## 路由宏

### 1. resource()

```php
use CloudCastle\Http\Router\Facade\Route;

// 为资源创建 RESTful 路由
Route::resource('/users', UserController::class);

// 创建:
// GET    /users           -> UserController::index
// GET    /users/create    -> UserController::create
// POST   /users           -> UserController::store
// GET    /users/{id}      -> UserController::show
// GET    /users/{id}/edit -> UserController::edit
// PUT    /users/{id}      -> UserController::update
// DELETE /users/{id}      -> UserController::destroy
```

### 2. apiResource()

```php
// API 资源 (无 create/edit 页面)
Route::apiResource('/posts', PostController::class, 100);

// 创建:
// GET    /posts       -> PostController::index    (throttle: 100/min)
// POST   /posts       -> PostController::store    (throttle: 100/min)
// GET    /posts/{id}  -> PostController::show     (throttle: 100/min)
// PUT    /posts/{id}  -> PostController::update   (throttle: 100/min)
// DELETE /posts/{id}  -> PostController::destroy  (throttle: 100/min)
```

### 3. crud()

```php
// 简单 CRUD
Route::crud('/products', ProductController::class);

// 创建:
// GET    /products       -> ProductController::index
// POST   /products       -> ProductController::create
// GET    /products/{id}  -> ProductController::read
// PUT    /products/{id}  -> ProductController::update
// DELETE /products/{id}  -> ProductController::delete
```

### 4. auth()

```php
// 认证路由
Route::auth();

// 创建:
// GET  /login            -> AuthController::showLoginForm
// POST /login            -> AuthController::login
// POST /logout           -> AuthController::logout
// GET  /register         -> AuthController::showRegisterForm
// POST /register         -> AuthController::register
// GET  /password/reset   -> AuthController::showResetForm
// POST /password/reset   -> AuthController::reset
```

### 5. adminPanel()

```php
// 带 IP 白名单的管理面板
Route::adminPanel('/admin', ['192.168.1.0/24']);

// 创建 (带 Auth + Admin 中间件 + HTTPS):
// GET /admin/dashboard -> AdminController::dashboard
// GET /admin/users     -> AdminController::users
// GET /admin/settings  -> AdminController::settings
// GET /admin/logs      -> AdminController::logs
```

### 6. apiVersion()

```php
// API 版本控制
Route::apiVersion('v1', function() {
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/posts', [PostController::class, 'index']);
});

// 路由可作为 /api/v1/users, /api/v1/posts 访问
```

### 7. webhooks()

```php
// 带 IP 白名单的 Webhooks
Route::webhooks('/webhooks', ['192.168.1.0/24']);

// 创建:
// POST /webhooks/github  -> WebhookController::github
// POST /webhooks/stripe  -> WebhookController::stripe
// POST /webhooks/custom  -> WebhookController::custom
```

---

## URL生成

### 1. 基本生成

```php
use CloudCastle\Http\Router\UrlGenerator;

$generator = new UrlGenerator();

$url = $generator->generate('users.show', ['id' => 123]);
// '/users/123'
```

### 2. 绝对 URL

```php
$url = $generator->generate('users.show', ['id' => 123])
    ->absolute();
// 'http://example.com/users/123'
```

### 3. 带域名的 URL

```php
$url = $generator->generate('users.show', ['id' => 123])
    ->toDomain('api.example.com');
// 'http://api.example.com/users/123'
```

### 4. 带协议的 URL

```php
$url = $generator->generate('users.show', ['id' => 123])
    ->toProtocol('https');
// 'https://example.com/users/123'
```

### 5. 带查询参数的 URL

```php
$url = $generator->generate('users.index', [], [
    'page' => 2,
    'limit' => 10,
    'sort' => 'name'
]);
// '/users?page=2&limit=10&sort=name'
```

### 6. 签名 URL

```php
$signedUrl = $generator->signed('verify.email', ['user' => 123], 3600);
// '/verify/email/123?signature=abc...&expires=1234567890'
```

### 7. 设置基础 URL

```php
$generator->setBaseUrl('https://api.example.com');
```

### 8-11. 组合生成

```php
$url = $generator->generate('api.users.show', ['id' => 123])
    ->toDomain('api.example.com')
    ->toProtocol('https')
    ->absolute();
// 'https://api.example.com/api/users/123'

// 通过辅助函数
$url = route_url('users.show', ['id' => 123]);
```

---

## 表达式语言

### 1. 基本条件

```php
Route::get('/admin', $action)
    ->condition('request.ip == "192.168.1.1"');
```

### 2. 比较运算符

```php
Route::get('/api/data', $action)
    ->condition('request.time > 9 and request.time < 18');

Route::get('/premium', $action)
    ->condition('user.level >= 5');

Route::get('/limited', $action)
    ->condition('request.count <= 100');
```

### 3. 逻辑运算符

```php
Route::get('/api/secure', $action)
    ->condition('request.ip == "192.168.1.1" and request.method == "GET"');

Route::get('/public', $action)
    ->condition('request.path == "/public" or request.path == "/open"');
```

### 4. ExpressionLanguage 类

```php
use CloudCastle\Http\Router\ExpressionLanguage\ExpressionLanguage;

$expr = new ExpressionLanguage();

$result = $expr->evaluate('10 > 5 and 20 < 30', []);
// true

$result = $expr->evaluate('user.age >= 18', ['user' => ['age' => 25]]);
// true
```

### 5. 复杂表达式

```php
Route::get('/api/restricted', $action)
    ->condition(
        '(request.ip == "192.168.1.1" or request.ip == "10.0.0.1") ' .
        'and request.time >= 9 and request.time <= 18'
    );
```

---

## 路由缓存

### 1. 启用缓存

```php
$router->enableCache('var/cache/routes');
```

### 2. 编译路由

```php
// 编译
$router->compile();

// 强制编译
$router->compile(force: true);
```

### 3. 从缓存加载

```php
if ($router->loadFromCache()) {
    echo "Routes loaded from cache";
} else {
    // 注册路由
    require 'routes/web.php';
    $router->compile();
}
```

### 4. 清除缓存

```php
$router->clearCache();
```

### 5. 自动编译

```php
$router->autoCompile();
// 更改时自动编译
```

### 6. 检查缓存加载

```php
if ($router->isCacheLoaded()) {
    echo "Cache is loaded";
}
```

### 缓存完整示例

```php
use CloudCastle\Http\Router\Router;

$router = new Router();
$router->enableCache(__DIR__ . '/var/cache/routes');

if (!$router->loadFromCache()) {
    // 注册路由
    require __DIR__ . '/routes/web.php';
    require __DIR__ . '/routes/api.php';
    
    // 编译
    $router->compile();
}

// 使用路由
$route = $router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
```

---

## 插件系统

### 1. PluginInterface

```php
use CloudCastle\Http\Router\Contracts\PluginInterface;
use CloudCastle\Http\Router\Route;

interface PluginInterface
{
    public function beforeDispatch(Route $route, string $uri, string $method): void;
    public function afterDispatch(Route $route, mixed $result): mixed;
    public function onRouteRegistered(Route $route): void;
    public function onException(Route $route, \Exception $e): void;
}
```

### 2. 注册插件

```php
Route::registerPlugin(new LoggerPlugin());
```

### 3. 注销插件

```php
Route::unregisterPlugin('logger');
```

### 4. 获取插件

```php
$plugin = Route::getPlugin('logger');
```

### 5. 检查插件存在

```php
if (Route::hasPlugin('logger')) {
    echo "Logger plugin registered";
}
```

### 6. 获取所有插件

```php
$plugins = Route::getPlugins();
```

### 7. LoggerPlugin (内置)

```php
use CloudCastle\Http\Router\Plugin\LoggerPlugin;

$logger = new LoggerPlugin('/var/log/routes.log');
Route::registerPlugin($logger);
```

### 8. AnalyticsPlugin (内置)

```php
use CloudCastle\Http\Router\Plugin\AnalyticsPlugin;

$analytics = new AnalyticsPlugin();
Route::registerPlugin($analytics);

// 获取统计
$stats = $analytics->getStats();
```

### 9. ResponseCachePlugin (内置)

```php
use CloudCastle\Http\Router\Plugin\ResponseCachePlugin;

$cache = new ResponseCachePlugin('/var/cache/responses', 3600);
Route::registerPlugin($cache);
```

### 10. AbstractPlugin

```php
use CloudCastle\Http\Router\Plugin\AbstractPlugin;

class MyPlugin extends AbstractPlugin
{
    public function beforeDispatch(Route $route, string $uri, string $method): void
    {
        // 分发前的逻辑
    }
    
    public function afterDispatch(Route $route, mixed $result): mixed
    {
        // 分发后的逻辑
        return $result;
    }
}
```

### 11-13. 插件钩子

```php
class FullPlugin implements PluginInterface
{
    // 分发前钩子
    public function beforeDispatch(Route $route, string $uri, string $method): void
    {
        echo "Before: $method $uri\n";
    }
    
    // 分发后钩子
    public function afterDispatch(Route $route, mixed $result): mixed
    {
        echo "After dispatch\n";
        return $result;
    }
    
    // 路由注册时钩子
    public function onRouteRegistered(Route $route): void
    {
        echo "Route registered: {$route->getUri()}\n";
    }
    
    // 异常时钩子
    public function onException(Route $route, \Exception $e): void
    {
        echo "Exception: {$e->getMessage()}\n";
    }
}
```

---

## 路由加载器

### 1. JsonLoader

```php
use CloudCastle\Http\Router\Loader\JsonLoader;

$loader = new JsonLoader($router);
$loader->load(__DIR__ . '/routes.json');
```

**routes.json:**
```json
{
    "routes": [
        {
            "method": "GET",
            "uri": "/users",
            "action": "UserController@index",
            "name": "users.index"
        },
        {
            "method": "POST",
            "uri": "/users",
            "action": "UserController@store",
            "name": "users.store",
            "middleware": ["auth"],
            "throttle": [60, 1]
        }
    ]
}
```

### 2. YamlLoader

```php
use CloudCastle\Http\Router\Loader\YamlLoader;

$loader = new YamlLoader($router);
$loader->load(__DIR__ . '/routes.yaml');
```

**routes.yaml:**
```yaml
routes:
  - method: GET
    uri: /users
    action: UserController@index
    name: users.index
  
  - method: POST
    uri: /users
    action: UserController@store
    name: users.store
    middleware:
      - auth
    throttle: [60, 1]
```

### 3. XmlLoader

```php
use CloudCastle\Http\Router\Loader\XmlLoader;

$loader = new XmlLoader($router);
$loader->load(__DIR__ . '/routes.xml');
```

**routes.xml:**
```xml
<?xml version="1.0"?>
<routes>
    <route method="GET" uri="/users" action="UserController@index" name="users.index"/>
    <route method="POST" uri="/users" action="UserController@store" name="users.store">
        <middleware>auth</middleware>
        <throttle>60,1</throttle>
    </route>
</routes>
```

### 4. AttributeLoader

```php
use CloudCastle\Http\Router\Loader\AttributeLoader;

$loader = new AttributeLoader($router);
$loader->loadFromDirectory(__DIR__ . '/app/Controllers');
```

**UserController.php:**
```php
use CloudCastle\Http\Router\Attributes\Route as RouteAttribute;

#[RouteAttribute('/users', 'GET', name: 'users.index')]
class UserController
{
    #[RouteAttribute('/users/{id}', 'GET', name: 'users.show')]
    public function show(int $id)
    {
        return "User $id";
    }
}
```

### 5. PHP 文件 (标准方式)

```php
// routes/web.php
Route::get('/users', [UserController::class, 'index']);
Route::post('/users', [UserController::class, 'store']);

// routes/api.php
Route::group(['prefix' => '/api'], function() {
    Route::get('/users', [ApiUserController::class, 'index']);
});

// index.php
require 'routes/web.php';
require 'routes/api.php';
```

---

## PSR支持

### 1. PSR-7 HTTP Message

```php
use Psr\Http\Message\ServerRequestInterface;
use Nyholm\Psr7\ServerRequestFactory;

$request = ServerRequestFactory::fromGlobals();
// PSR-7 请求对象

// 与路由器一起使用
$uri = $request->getUri()->getPath();
$method = $request->getMethod();

$route = Route::dispatch($uri, $method);
```

### 2. PSR-15 HTTP Server Handler

```php
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class RouteHandler implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $uri = $request->getUri()->getPath();
        $method = $request->getMethod();
        
        $route = Route::dispatch($uri, $method);
        $result = $route->run();
        
        // 返回 PSR-7 Response
        return new Response(200, [], $result);
    }
}
```

### 3. Psr15MiddlewareAdapter

```php
use CloudCastle\Http\Router\Psr15\Psr15MiddlewareAdapter;

$adapter = new Psr15MiddlewareAdapter($router);

// 作为 PSR-15 中间件使用
$response = $adapter->process($request, $handler);
```

---

## 动作解析器

CloudCastle HTTP Router 支持 **6 种格式**的路由动作:

### 1. 闭包

```php
Route::get('/users', function() {
    return 'Users list';
});
```

### 2. 数组 [Controller::class, 'method']

```php
Route::get('/users', [UserController::class, 'index']);
```

### 3. 字符串 "Controller@method"

```php
Route::get('/users', 'UserController@index');
```

### 4. 字符串 "Controller::method"

```php
Route::get('/users', 'UserController::index');
```

### 5. 可调用控制器

```php
class ShowUserController
{
    public function __invoke(int $id)
    {
        return "User: $id";
    }
}

Route::get('/users/{id}', ShowUserController::class);
```

### 6. 依赖注入

```php
class UserController
{
    public function __construct(
        private UserRepository $repository
    ) {}
    
    public function index()
    {
        return $this->repository->all();
    }
}

Route::get('/users', [UserController::class, 'index']);
// ActionResolver 将自动解析依赖
```

---

## 统计和查询

### 1. getRouteStats()

```php
$stats = Route::getRouteStats();
/*
[
    'total' => 150,
    'named' => 120,
    'with_middleware' => 60,
    'with_tags' => 80,
    'methods' => ['GET' => 80, 'POST' => 40, ...],
    'domains' => ['api.example.com' => 30, ...],
    'ports' => [8080 => 20, ...],
]
*/
```

### 2. getRoutesByMethod()

```php
$getRoutes = Route::router()->getRoutesByMethod('GET');
$postRoutes = Route::router()->getRoutesByMethod('POST');
```

### 3. getRoutesByDomain()

```php
$apiRoutes = Route::router()->getRoutesByDomain('api.example.com');
```

### 4. getRoutesByPort()

```php
$routes = Route::router()->getRoutesByPort(8080);
```

### 5. getRoutesByPrefix()

```php
$apiRoutes = Route::router()->getRoutesByPrefix('/api');
```

### 6. getRoutesByUriPattern()

```php
$userRoutes = Route::router()->getRoutesByUriPattern('/users');
```

### 7. getRoutesByMiddleware()

```php
$authRoutes = Route::router()->getRoutesByMiddleware(AuthMiddleware::class);
```

### 8. getRoutesByController()

```php
$routes = Route::router()->getRoutesByController(UserController::class);
```

### 9. getRoutesWithIpRestrictions()

```php
$restrictedRoutes = Route::router()->getRoutesWithIpRestrictions();
```

### 10. getThrottledRoutes()

```php
$throttledRoutes = Route::router()->getThrottledRoutes();
```

### 11. getRoutesWithDomain()

```php
$domainRoutes = Route::router()->getRoutesWithDomain();
```

### 12. getRoutesWithPort()

```php
$portRoutes = Route::router()->getRoutesWithPort();
```

### 13. searchRoutes()

```php
$results = Route::router()->searchRoutes('user');
// 所有在 URI 或名称中包含 'user' 的路由
```

### 14. getRoutesGroupedByMethod()

```php
$grouped = Route::getRoutesGroupedByMethod();
/*
[
    'GET' => [Route, Route, ...],
    'POST' => [Route, Route, ...],
    ...
]
*/
```

### 15. getRoutesGroupedByPrefix()

```php
$grouped = Route::getRoutesGroupedByPrefix();
/*
[
    '/api' => [Route, Route, ...],
    '/admin' => [Route, Route, ...],
    ...
]
*/
```

### 16. getRoutesGroupedByDomain()

```php
$grouped = Route::getRoutesGroupedByDomain();
/*
[
    'api.example.com' => [Route, Route, ...],
    'admin.example.com' => [Route, Route, ...],
    ...
]
*/
```

### 17. getRoutes()

```php
$allRoutes = Route::getRoutes();
```

### 18. getNamedRoutes()

```php
$namedRoutes = Route::getNamedRoutes();
```

### 19. getAllDomains()

```php
$domains = Route::router()->getAllDomains();
// ['api.example.com', 'admin.example.com', ...]
```

### 20. getAllPorts()

```php
$ports = Route::router()->getAllPorts();
// [8080, 8081, 443, ...]
```

### 21. getAllTags()

```php
$tags = Route::router()->getAllTags();
// ['api', 'admin', 'public', ...]
```

### 22. count()

```php
$total = Route::count();
echo "Total routes: $total";
```

### 23. getRoutesAsJson()

```php
$json = Route::getRoutesAsJson(JSON_PRETTY_PRINT);
echo $json;
```

### 24. getRoutesAsArray()

```php
$array = Route::getRoutesAsArray();
```

---

## 安全

### 1. 路径遍历保护

```php
// 路由器自动防止 ../../../
Route::get('/files/{path}', function($path) {
    // $path 永远不会包含 ../
    return "File: $path";
});
```

### 2. SQL 注入保护

```php
// 参数自动验证
Route::get('/users/{id}', function($id) {
    // 在 SQL 中安全使用
    return DB::find($id);
})->where('id', '[0-9]+');
```

### 3. XSS 保护

```php
Route::get('/search/{query}', function($query) {
    // 转义输出
    return htmlspecialchars($query);
});
```

### 4. 速率限制

```php
// DDoS 保护
Route::post('/api/submit', $action)
    ->throttle(60, 1);
```

### 5. IP 过滤

```php
// 仅白名单可信 IP
Route::get('/admin', $action)
    ->whitelistIp(['192.168.1.0/24']);
```

### 6. HTTPS 强制

```php
// 强制使用 HTTPS
Route::get('/payment', $action)->https();
Route::get('/secure', $action)->secure();
```

### 7. 协议限制

```php
// 仅 HTTPS/WSS
Route::get('/ws/secure', $action)
    ->protocol(['wss']);
```

### 8. ReDoS 保护

```php
// 路由器防止正则 DoS
// 自动使用安全模式
Route::get('/users/{id}', $action)
    ->where('id', '[0-9]+');  // 安全
```

### 9. 方法覆盖保护

```php
// 防止方法欺骗
// 路由器检查真实 HTTP 方法
```

### 10. 缓存注入保护

```php
// 安全缓存
$router->enableCache('var/cache/routes');
// 缓存被签名和验证
```

### 11. IP 欺骗保护

```php
// 路由器检查 X-Forwarded-For
// 并防止 IP 欺骗
```

### 12. 自动禁止系统

```php
// 自动阻止攻击 IP
$banManager = new BanManager(5, 3600);
Route::post('/login', $action)
    ->throttle(3, 1)
    ->getRateLimiter()
    ?->setBanManager($banManager);
```

---

## 异常

### 1. RouteNotFoundException

```php
try {
    $route = Route::dispatch('/nonexistent', 'GET');
} catch (\CloudCastle\Http\Router\Exceptions\RouteNotFoundException $e) {
    http_response_code(404);
    echo "404 Not Found";
}
```

### 2. MethodNotAllowedException

```php
try {
    $route = Route::dispatch('/users', 'DELETE');  // 方法不允许
} catch (\CloudCastle\Http\Router\Exceptions\MethodNotAllowedException $e) {
    http_response_code(405);
    $allowed = $e->getAllowedMethods();
    header('Allow: ' . implode(', ', $allowed));
    echo "405 Method Not Allowed";
}
```

### 3. IpNotAllowedException

```php
try {
    $route = Route::dispatch('/admin', 'GET');
} catch (\CloudCastle\Http\Router\Exceptions\IpNotAllowedException $e) {
    http_response_code(403);
    echo "403 Forbidden: IP not allowed";
}
```

### 4. TooManyRequestsException

```php
try {
    $route = Route::dispatch('/api/submit', 'POST');
} catch (\CloudCastle\Http\Router\Exceptions\TooManyRequestsException $e) {
    http_response_code(429);
    $retryAfter = $e->getRetryAfter();
    header("Retry-After: $retryAfter");
    echo "429 Too Many Requests";
}
```

### 5. InsecureConnectionException

```php
try {
    $route = Route::dispatch('/payment', 'POST');
} catch (\CloudCastle\Http\Router\Exceptions\InsecureConnectionException $e) {
    http_response_code(403);
    echo "403 Forbidden: HTTPS required";
}
```

### 6. BannedException

```php
try {
    $route = Route::dispatch('/api/data', 'GET');
} catch (\CloudCastle\Http\Router\Exceptions\BannedException $e) {
    http_response_code(403);
    echo "403 Forbidden: IP is banned";
}
```

### 7. InvalidActionException

```php
try {
    Route::get('/test', 'InvalidController@method');
    $route = Route::dispatch('/test', 'GET');
    $route->run();
} catch (\CloudCastle\Http\Router\Exceptions\InvalidActionException $e) {
    http_response_code(500);
    echo "500 Internal Server Error: Invalid action";
}
```

### 8. RouterException

```php
try {
    // 任何路由器错误
} catch (\CloudCastle\Http\Router\Exceptions\RouterException $e) {
    http_response_code(500);
    echo "Router Error: " . $e->getMessage();
}
```

---

## CLI工具

### 1. routes-list

```bash
# 显示所有路由
php bin/routes-list

# 带过滤器
php bin/routes-list --method=GET
php bin/routes-list --tag=api
php bin/routes-list --name=users.*
```

### 2. analyse

```bash
# 路由分析
php bin/analyse

# 显示:
# - 路由总数
# - 按方法分组的路由
# - 按域名分组的路由
# - 带中间件的路由
# - 等等
```

### 3. router

```bash
# 路由器管理
php bin/router compile        # 编译缓存
php bin/router clear          # 清除缓存
php bin/router stats          # 统计
```

---

## 高级示例

### 示例 1: 具有完全保护的 REST API

```php
use CloudCastle\Http\Router\Facade\Route;
use CloudCastle\Http\Router\BanManager;
use CloudCastle\Http\Router\Middleware\{
    AuthMiddleware,
    CorsMiddleware,
    SecurityLogger
};

// 设置自动禁止
$banManager = new BanManager(5, 3600);

// API v1
Route::group([
    'prefix' => '/api/v1',
    'middleware' => [CorsMiddleware::class, SecurityLogger::class],
    'domain' => 'api.example.com',
    'https' => true,
    'tags' => ['api', 'v1'],
], function() use ($banManager) {
    
    // 公共端点
    Route::get('/posts', [PostController::class, 'index'])
        ->name('api.v1.posts.index')
        ->throttle(100, 1)
        ->tag('public');
    
    // 受保护的端点
    Route::group(['middleware' => [AuthMiddleware::class]], function() use ($banManager) {
        
        Route::post('/posts', [PostController::class, 'store'])
            ->name('api.v1.posts.store')
            ->throttle(20, 1)
            ->getRateLimiter()
            ?->setBanManager($banManager);
        
        Route::put('/posts/{id}', [PostController::class, 'update'])
            ->name('api.v1.posts.update')
            ->where('id', '[0-9]+')
            ->throttle(30, 1);
        
        Route::delete('/posts/{id}', [PostController::class, 'destroy'])
            ->name('api.v1.posts.destroy')
            ->where('id', '[0-9]+')
            ->throttle(10, 1);
    });
});
```

### 示例 2: 微服务架构

```php
// 用户服务 (端口 8081)
Route::group([
    'prefix' => '/users',
    'port' => 8081,
    'domain' => 'users.service.local',
    'tags' => ['user-service', 'microservice'],
], function() {
    Route::get('/', [UserServiceController::class, 'index']);
    Route::get('/{id}', [UserServiceController::class, 'show'])
        ->where('id', '[0-9]+');
    Route::post('/', [UserServiceController::class, 'create']);
});

// 产品服务 (端口 8082)
Route::group([
    'prefix' => '/products',
    'port' => 8082,
    'domain' => 'products.service.local',
    'tags' => ['product-service', 'microservice'],
], function() {
    Route::get('/', [ProductServiceController::class, 'index']);
    Route::get('/{id}', [ProductServiceController::class, 'show']);
});

// 订单服务 (端口 8083)
Route::group([
    'prefix' => '/orders',
    'port' => 8083,
    'domain' => 'orders.service.local',
    'tags' => ['order-service', 'microservice'],
], function() {
    Route::post('/', [OrderServiceController::class, 'create']);
    Route::get('/{id}', [OrderServiceController::class, 'show']);
});
```

### 示例 3: 带层级的 SaaS 平台

```php
// 免费层
Route::group([
    'prefix' => '/api/free',
    'middleware' => [AuthMiddleware::class],
    'tags' => ['free-tier'],
], function() {
    Route::get('/data', $action)
        ->throttle(10, 1);  // 10 请求/分钟
});

// 专业层
Route::group([
    'prefix' => '/api/pro',
    'middleware' => [AuthMiddleware::class, ProMiddleware::class],
    'tags' => ['pro-tier'],
], function() {
    Route::get('/data', $action)
        ->throttle(100, 1);  // 100 请求/分钟
});

// 企业层
Route::group([
    'prefix' => '/api/enterprise',
    'middleware' => [AuthMiddleware::class, EnterpriseMiddleware::class],
    'tags' => ['enterprise-tier'],
], function() {
    Route::get('/data', $action)
        ->throttle(1000, 1);  // 1000 请求/分钟
});
```

### 示例 4: 多域名应用

```php
// 主站点
Route::group(['domain' => 'example.com'], function() {
    Route::get('/', [HomeController::class, 'index']);
    Route::get('/about', [AboutController::class, 'index']);
});

// API 子域
Route::group(['domain' => 'api.example.com', 'https' => true], function() {
    Route::apiResource('/users', UserApiController::class);
    Route::apiResource('/posts', PostApiController::class);
});

// 管理员
Route::group([
    'domain' => 'admin.example.com',
    'https' => true,
    'middleware' => [AuthMiddleware::class, AdminMiddleware::class],
    'whitelistIp' => ['192.168.1.0/24'],
], function() {
    Route::get('/dashboard', [AdminController::class, 'dashboard']);
    Route::resource('/users', AdminUserController::class);
});

// 博客
Route::group(['domain' => 'blog.example.com'], function() {
    Route::get('/', [BlogController::class, 'index']);
    Route::get('/{slug}', [BlogController::class, 'show']);
});
```

### 示例 5: 性能缓存

```php
use CloudCastle\Http\Router\Router;
use CloudCastle\Http\Router\Plugin\ResponseCachePlugin;

$router = new Router();

// 启用路由缓存
$router->enableCache(__DIR__ . '/var/cache/routes');

// 添加响应缓存插件
$responseCache = new ResponseCachePlugin(__DIR__ . '/var/cache/responses', 3600);
$router->registerPlugin($responseCache);

// 从缓存加载或注册
if (!$router->loadFromCache()) {
    require __DIR__ . '/routes/web.php';
    require __DIR__ . '/routes/api.php';
    $router->compile();
}

// 分发
$route = $router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
$response = $route->run();

echo $response;
```

---

## 结论

CloudCastle HTTP Router 提供 **209+ 功能**用于在 PHP 8.2+ 上构建现代、安全和高性能的 Web 应用程序。

### 主要优势:

- ⚡ **高性能:** 每秒 54,891 次请求
- 🔒 **全面安全:** 12+ 个保护机制
- 💎 **丰富功能:** 209+ 个方法
- 💾 **高效内存:** 每路由 1.32 KB
- 📊 **可扩展性:** 1,160,000+ 个路由
- ✅ **可靠性:** 501 个测试，0 个错误

### 下一步:

1. 学习 [API 参考](API_REFERENCE.md) 获取详细信息
2. 查看 [示例](../../examples/) 进行实际应用
3. 阅读 [常见问题](FAQ.md) 获取常见问题答案
4. 查看 [安全报告](SECURITY_REPORT.md)
5. 检查 [性能分析](PERFORMANCE_ANALYSIS.md)

---

**© 2024 CloudCastle HTTP Router**  
**版本:** 1.1.1  
**许可证:** MIT

[⬆ 返回顶部](#cloudcastle-http-router---完整用户指南)


---

## 📚 文档导航

[README](../../README.md) | [用户指南](USER_GUIDE.md) | [功能索引](FEATURES_INDEX.md) | [API参考](API_REFERENCE.md) | [所有功能](ALL_FEATURES.md) | [测试总结](TESTS_SUMMARY.md) | [性能](PERFORMANCE_ANALYSIS.md) | [安全](SECURITY_REPORT.md) | [对比](COMPARISON.md) | [常见问题](FAQ.md) | [文档摘要](DOCUMENTATION_SUMMARY.md)

**详细文档:** [功能](features/) (22个文件) | [测试](tests/) (7个报告)

---
