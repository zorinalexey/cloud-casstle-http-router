# CloudCastle HTTP Router 完整功能列表

[English](../en/ALL_FEATURES.md) | [Русский](../ru/ALL_FEATURES.md) | [Deutsch](../de/ALL_FEATURES.md) | [Français](../fr/ALL_FEATURES.md) | [**中文**](ALL_FEATURES.md)

---

## 📚 文档导航

[README](../../README.md) | [USER_GUIDE](USER_GUIDE.md) | [FEATURES_INDEX](FEATURES_INDEX.md) | [API_REFERENCE](API_REFERENCE.md) | [ALL_FEATURES](ALL_FEATURES.md) | [TESTS_SUMMARY](TESTS_SUMMARY.md) | [PERFORMANCE](PERFORMANCE_ANALYSIS.md) | [SECURITY](SECURITY_REPORT.md) | [COMPARISON](COMPARISON.md) | [FAQ](FAQ.md) | [DOC_SUMMARY](DOCUMENTATION_SUMMARY.md)

**详细文档:** [Features](features/) (22个文件) | [Tests](tests/) (7个报告)

---

## 内容

- [1. 基本路由](#1-基本路由)
- [2. 辅助函数](#2-辅助函数)
- [3. 路由快捷方式](#3-路由快捷方式)
- [4. 路由宏](#4-路由宏)
- [5. 路由组](#5-路由组)
- [6. 中间件](#6-中间件)
- [7. 速率限制](#7-速率限制)
- [8. IP过滤](#8-ip过滤)
- [9. 自动封禁系统](#9-自动封禁系统)
- [10. 命名路由](#10-命名路由)
- [11. 标签](#11-标签)
- [12. 路由参数](#12-路由参数)
- [13. 表达式语言](#13-表达式语言)
- [14. URL生成](#14-url生成)
- [15. 缓存](#15-缓存)
- [16. 插件](#16-插件)
- [17. 加载器](#17-加载器)
- [18. PSR支持](#18-psr支持)
- [19. 动作解析器](#19-动作解析器)
- [20. 统计和过滤](#20-统计和过滤)

---

## 1. 基本路由

### HTTP方法

```php
use CloudCastle\Http\Router\Router;

$router = new Router();

// 所有标准方法
$router->get('/users', $action);
$router->post('/users', $action);
$router->put('/users/{id}', $action);
$router->patch('/users/{id}', $action);
$router->delete('/users/{id}', $action);

// 自定义方法
$router->view('/page', $action);  // VIEW方法
$router->custom('PURGE', '/cache', $action);  // 任何方法

// 多个方法
$router->match(['GET', 'POST'], '/form', $action);
$router->any('/endpoint', $action);  // 所有方法
```

### Facade API

```php
use CloudCastle\Http\Router\Facade\Route;

Route::get('/api/users', $action);
Route::post('/api/users', $action);
// 等等...
```

---

## 2. 辅助函数

### route()

通过名称获取路由或当前路由：

```php
// 通过名称获取路由
$route = route('users.show');

// 获取当前路由
$current = route();
```

### current_route()

获取当前路由：

```php
$currentRoute = current_route();
echo $currentRoute->getName();
```

### previous_route()

获取上一个路由：

```php
$prevRoute = previous_route();
```

### route_is()

检查当前路由名称：

```php
if (route_is('users.index')) {
    // 当前路由是 users.index
}
```

### route_name()

获取当前路由名称：

```php
$name = route_name(); // 'users.show'
```

### router()

获取路由器实例：

```php
$router = router();
$stats = $router->getRouteStats();
```

### dispatch_route()

分发当前HTTP请求：

```php
$route = dispatch_route();
if ($route) {
    echo $route->run();
}
```

---

## 3. 路由快捷方式

### resource()

创建RESTful资源路由：

```php
Route::resource('users', UserController::class);
// 创建：GET, POST, PUT, PATCH, DELETE路由
```

### apiResource()

创建API资源路由：

```php
Route::apiResource('users', ApiUserController::class);
// 创建：GET, POST, PUT, PATCH, DELETE路由（无视图路由）
```

### crud()

创建CRUD操作：

```php
Route::crud('products', ProductController::class);
// 创建：index, show, store, update, destroy
```

### auth()

创建身份验证路由：

```php
Route::auth();
// 创建：login, register, logout, password reset路由
```

### adminPanel()

创建管理面板路由：

```php
Route::adminPanel();
// 创建：dashboard, users, settings路由
```

### apiVersion()

创建API版本控制路由：

```php
Route::apiVersion('v1', function() {
    Route::get('/users', $action);
});
```

### webhooks()

创建Webhook路由：

```php
Route::webhooks('stripe', StripeWebhookController::class);
```

---

## 4. 路由宏

### 自定义宏

```php
use CloudCastle\Http\Router\Macro\MacroManager;

MacroManager::macro('admin', function($prefix, $controller) {
    Route::group(['prefix' => $prefix, 'middleware' => 'admin'], function() use ($controller) {
        Route::get('/', [$controller, 'index']);
        Route::get('/create', [$controller, 'create']);
        Route::post('/', [$controller, 'store']);
        Route::get('/{id}', [$controller, 'show']);
        Route::get('/{id}/edit', [$controller, 'edit']);
        Route::put('/{id}', [$controller, 'update']);
        Route::delete('/{id}', [$controller, 'destroy']);
    });
});

// 使用
Route::admin('users', UserController::class);
```

---

## 5. 路由组

### 基本组

```php
Route::group(['prefix' => 'api/v1'], function() {
    Route::get('/users', $action);
    Route::get('/posts', $action);
});
```

### 高级组

```php
Route::group([
    'prefix' => 'admin',
    'middleware' => ['auth', 'admin'],
    'domain' => 'admin.example.com',
    'namespace' => 'Admin',
    'as' => 'admin.',
    'where' => ['id' => '[0-9]+']
], function() {
    Route::get('/dashboard', 'DashboardController@index');
    Route::resource('users', 'UserController');
});
```

### 嵌套组

```php
Route::group(['prefix' => 'api'], function() {
    Route::group(['prefix' => 'v1'], function() {
        Route::get('/users', $action);
    });
    
    Route::group(['prefix' => 'v2'], function() {
        Route::get('/users', $action);
    });
});
```

---

## 6. 中间件

### 全局中间件

```php
$router->middleware([
    CorsMiddleware::class,
    SecurityMiddleware::class
]);
```

### 路由中间件

```php
Route::get('/admin', $action)->middleware('auth');
Route::post('/api', $action)->middleware(['auth', 'throttle']);
```

### 组中间件

```php
Route::group(['middleware' => 'auth'], function() {
    Route::get('/profile', $action);
    Route::get('/settings', $action);
});
```

### 内置中间件

```php
// 身份验证
Route::get('/protected', $action)->middleware(AuthMiddleware::class);

// CORS
Route::get('/api', $action)->middleware(CorsMiddleware::class);

// HTTPS强制
Route::get('/secure', $action)->middleware(HttpsEnforcement::class);

// 安全日志记录
Route::get('/sensitive', $action)->middleware(SecurityLogger::class);

// SSRF保护
Route::get('/proxy', $action)->middleware(SsrfProtection::class);
```

---

## 7. 速率限制

### 基本速率限制

```php
Route::get('/api', $action)->throttle(60, 1); // 每分钟60个请求
Route::post('/login', $action)->throttle(5, 1); // 每分钟5个请求
```

### 时间单位

```php
use CloudCastle\Http\Router\RateLimiting\TimeUnit;

Route::get('/api', $action)->throttle(100, TimeUnit::HOUR);
Route::post('/upload', $action)->throttle(10, TimeUnit::DAY);
```

### 自定义键

```php
Route::get('/api', $action)->throttle(60, 1, 'user:' . $userId);
Route::post('/api', $action)->throttle(100, 1, 'api_key:' . $apiKey);
```

### Rate Limiter类

```php
use CloudCastle\Http\Router\RateLimiting\RateLimiter;

$limiter = new RateLimiter(60, TimeUnit::MINUTE);
$limiter->setKey('user:' . $userId);
$limiter->check();
```

### 预定义限制

```php
Route::get('/api', $action)->throttleStandard(); // 60 req/min
Route::post('/api', $action)->throttleStrict();   // 10 req/min
Route::get('/api', $action)->throttleGenerous(); // 1000 req/min
```

---

## 8. IP过滤

### 白名单

```php
Route::get('/admin', $action)->whitelistIp(['192.168.1.0/24', '10.0.0.1']);
Route::group(['whitelistIp' => ['192.168.1.0/24']], function() {
    Route::get('/admin', $action);
});
```

### 黑名单

```php
Route::get('/api', $action)->blacklistIp(['192.168.1.100', '10.0.0.50']);
Route::group(['blacklistIp' => ['192.168.1.100']], function() {
    Route::get('/api', $action);
});
```

### CIDR支持

```php
Route::get('/admin', $action)->whitelistIp([
    '192.168.1.0/24',    // 192.168.1.1-254
    '10.0.0.0/8',        // 10.0.0.0-10.255.255.255
    '172.16.0.0/12'      // 172.16.0.0-172.31.255.255
]);
```

### IP欺骗保护

```php
Route::get('/api', $action)->enableIpSpoofingProtection();
```

---

## 9. 自动封禁系统

### 基本自动封禁

```php
use CloudCastle\Http\Router\RateLimiting\BanManager;

$banManager = new BanManager(5, 3600); // 5次违规后封禁1小时

Route::post('/login', $action)
    ->throttle(3, 1)
    ->getRateLimiter()?->setBanManager($banManager);
```

### 封禁管理

```php
$banManager = new BanManager();

// 手动封禁IP
$banManager->ban('192.168.1.100', 3600);

// 解封IP
$banManager->unban('192.168.1.100');

// 检查IP是否被封禁
if ($banManager->isBanned('192.168.1.100')) {
    throw new BannedException();
}

// 获取所有被封禁的IP
$bannedIps = $banManager->getBannedIps();

// 清除所有封禁
$banManager->clearAll();
```

### 自动封禁配置

```php
$banManager = new BanManager(
    $violationThreshold = 5,    // 5次违规后封禁
    $banDuration = 3600,        // 封禁1小时
    $gracePeriod = 300          // 5分钟宽限期
);
```

---

## 10. 命名路由

### 基本命名

```php
Route::get('/users/{id}', $action)->name('users.show');
Route::get('/users', $action)->name('users.index');
```

### 组命名

```php
Route::group(['as' => 'admin.'], function() {
    Route::get('/dashboard', $action)->name('dashboard');
    // 创建路由名称：admin.dashboard
});
```

### 路由名称辅助函数

```php
// 通过名称获取路由
$route = Route::getRouteByName('users.show');

// 获取当前路由名称
$name = Route::currentRouteName();

// 检查当前路由是否匹配模式
if (Route::currentRouteNamed('users.*')) {
    // 当前路由以'users.'开头
}

// 获取所有命名路由
$namedRoutes = Route::getNamedRoutes();
```

### 自动命名

```php
Route::enableAutoNaming();

Route::get('/users', $action); // 自动命名：users.index
Route::get('/users/{id}', $action); // 自动命名：users.show
Route::post('/users', $action); // 自动命名：users.store
```

---

## 11. 标签

### 基本标签

```php
Route::get('/api/users', $action)->tag('api');
Route::get('/api/posts', $action)->tag('api');
Route::get('/web/about', $action)->tag('web');
```

### 多个标签

```php
Route::get('/api/users', $action)->tag(['api', 'public']);
Route::get('/api/admin', $action)->tag(['api', 'admin']);
```

### 组标签

```php
Route::group(['tag' => 'api'], function() {
    Route::get('/users', $action);
    Route::get('/posts', $action);
});
```

### 标签操作

```php
// 通过标签获取路由
$apiRoutes = Route::getRoutesByTag('api');

// 检查路由是否有标签
if ($route->hasTag('api')) {
    // 路由有'api'标签
}

// 获取所有标签
$allTags = Route::getAllTags();
```

---

## 12. 路由参数

### 基本参数

```php
Route::get('/users/{id}', $action);
Route::get('/posts/{slug}', $action);
Route::get('/categories/{category}/posts/{post}', $action);
```

### 可选参数

```php
Route::get('/users/{id?}', $action);
Route::get('/posts/{slug?}', $action);
```

### 参数约束

```php
Route::get('/users/{id}', $action)->where('id', '[0-9]+');
Route::get('/posts/{slug}', $action)->where('slug', '[a-z0-9-]+');
Route::get('/users/{id}/posts/{post}', $action)
    ->where(['id' => '[0-9]+', 'post' => '[0-9]+']);
```

### 内联约束

```php
Route::get('/users/{id:[0-9]+}', $action);
Route::get('/posts/{slug:[a-z0-9-]+}', $action);
```

### 默认值

```php
Route::get('/users/{id}', $action)->defaults(['id' => 1]);
Route::get('/posts/{page?}', $action)->defaults(['page' => 1]);
```

### 参数访问

```php
$route = Route::get('/users/{id}', function($id) {
    return "User ID: $id";
});

// 获取参数
$params = $route->getParameters();
$id = $route->getParameter('id');
```

---

## 13. 表达式语言

### 基本表达式

```php
Route::get('/users/{id}', $action)
    ->where('id', 'expr: id > 0 and id < 1000');
```

### 复杂表达式

```php
Route::get('/posts/{year}/{month}', $action)
    ->where('year', 'expr: year >= 2020 and year <= 2030')
    ->where('month', 'expr: month >= 1 and month <= 12');
```

### 表达式函数

```php
Route::get('/files/{filename}', $action)
    ->where('filename', 'expr: strlen(filename) > 0 and strlen(filename) < 255');
```

---

## 14. URL生成

### 基本URL生成

```php
// 为命名路由生成URL
$url = route('users.show', ['id' => 1]);
// 结果：/users/1

// 生成带查询参数的URL
$url = route('users.index', [], ['page' => 2, 'sort' => 'name']);
// 结果：/users?page=2&sort=name
```

### URL辅助函数

```php
// 获取当前URL
$currentUrl = url()->current();

// 获取完整URL
$fullUrl = url()->full();

// 获取上一个URL
$previousUrl = url()->previous();

// 生成安全URL
$secureUrl = url()->secure('users/1');
```

### 路由URL生成

```php
$route = Route::get('/users/{id}', $action)->name('users.show');

// 生成URL
$url = $route->url(['id' => 1]);
$url = $route->url(['id' => 1], ['absolute' => true]);
```

---

## 15. 缓存

### 路由缓存

```php
$router->enableCache('cache/routes.php');

// 将路由编译到缓存
$router->compile();

// 从缓存加载
if (!$router->loadFromCache()) {
    require 'routes/web.php';
    $router->compile();
}
```

### 响应缓存

```php
Route::get('/api/users', $action)->cache(3600); // 缓存1小时
Route::get('/api/posts', $action)->cache(7200, ['tag' => 'posts']); // 带标签缓存
```

### 缓存标签

```php
Route::get('/api/users', $action)->cache(3600, ['tag' => 'users']);
Route::get('/api/posts', $action)->cache(3600, ['tag' => 'posts']);

// 按标签清除缓存
Cache::clearByTag('users');
```

---

## 16. 插件

### 内置插件

```php
use CloudCastle\Http\Router\Plugin\LoggerPlugin;
use CloudCastle\Http\Router\Plugin\AnalyticsPlugin;
use CloudCastle\Http\Router\Plugin\ResponseCachePlugin;

$router->addPlugin(new LoggerPlugin());
$router->addPlugin(new AnalyticsPlugin());
$router->addPlugin(new ResponseCachePlugin());
```

### 自定义插件

```php
use CloudCastle\Http\Router\Plugin\PluginInterface;

class CustomPlugin implements PluginInterface
{
    public function beforeDispatch($request, $response)
    {
        // 在路由分发前执行
    }
    
    public function afterDispatch($request, $response, $route)
    {
        // 在路由分发后执行
    }
}

$router->addPlugin(new CustomPlugin());
```

---

## 17. 加载器

### 路由加载器

```php
use CloudCastle\Http\Router\Loader\FileLoader;
use CloudCastle\Http\Router\Loader\DatabaseLoader;

// 从文件加载路由
$loader = new FileLoader('routes/web.php');
$loader->load($router);

// 从数据库加载路由
$loader = new DatabaseLoader($connection);
$loader->load($router);
```

### 自定义加载器

```php
use CloudCastle\Http\Router\Loader\LoaderInterface;

class CustomLoader implements LoaderInterface
{
    public function load(Router $router)
    {
        // 从自定义源加载路由
    }
}
```

---

## 18. PSR支持

### PSR-7 Request/Response

```php
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

Route::get('/api/users', function(ServerRequestInterface $request): ResponseInterface {
    $response = new Response();
    $response->getBody()->write(json_encode(['users' => []]));
    return $response->withHeader('Content-Type', 'application/json');
});
```

### PSR-15 中间件

```php
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class CustomMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // 处理请求
        return $handler->handle($request);
    }
}

Route::get('/api', $action)->middleware(CustomMiddleware::class);
```

### PSR-11 容器

```php
use Psr\Container\ContainerInterface;

Route::get('/api/users', function(ContainerInterface $container) {
    $userService = $container->get(UserService::class);
    return $userService->getAll();
});
```

---

## 19. 动作解析器

### 控制器动作

```php
Route::get('/users', 'UserController@index');
Route::get('/users/{id}', [UserController::class, 'show']);
Route::get('/users', UserController::class . '@index');
```

### 闭包动作

```php
Route::get('/users', function() {
    return 'Users list';
});

Route::get('/users/{id}', function($id) {
    return "User ID: $id";
});
```

### 类动作

```php
class UserAction
{
    public function __invoke($id)
    {
        return "User ID: $id";
    }
}

Route::get('/users/{id}', UserAction::class);
```

### 依赖注入

```php
Route::get('/users', function(UserService $userService) {
    return $userService->getAll();
});

Route::get('/users/{id}', [UserController::class, 'show']);
```

---

## 20. 统计和过滤

### 路由统计

```php
$stats = $router->getRouteStats();

echo "总路由数：" . $stats->getTotalRoutes();
echo "命名路由：" . $stats->getNamedRoutes();
echo "分组路由：" . $stats->getGroupedRoutes();
echo "中间件路由：" . $stats->getMiddlewareRoutes();
```

### 路由过滤

```php
// 按方法过滤
$getRoutes = $router->getRoutesByMethod('GET');

// 按模式过滤
$apiRoutes = $router->getRoutesByPattern('/api/*');

// 按中间件过滤
$authRoutes = $router->getRoutesByMiddleware('auth');

// 按标签过滤
$publicRoutes = $router->getRoutesByTag('public');
```

### 性能统计

```php
$perfStats = $router->getPerformanceStats();

echo "平均分发时间：" . $perfStats->getAverageDispatchTime();
echo "内存使用：" . $perfStats->getMemoryUsage();
echo "缓存命中率：" . $perfStats->getCacheHitRate();
```

---

## 总结

CloudCastle HTTP Router在20个主要类别中提供**209+功能**：

1. **基本路由** - 所有HTTP方法和自定义方法
2. **辅助函数** - 便捷的路由辅助函数
3. **路由快捷方式** - 预构建的路由集合
4. **路由宏** - 自定义路由模式
5. **路由组** - 有组织的路由集合
6. **中间件** - 请求/响应处理
7. **速率限制** - DDoS和滥用保护
8. **IP过滤** - 按IP控制访问
9. **自动封禁系统** - 自动IP封禁
10. **命名路由** - 路由标识
11. **标签** - 路由分类
12. **路由参数** - 动态URL段
13. **表达式语言** - 高级参数验证
14. **URL生成** - 动态URL创建
15. **缓存** - 性能优化
16. **插件** - 可扩展架构
17. **加载器** - 路由加载策略
18. **PSR支持** - 标准合规性
19. **动作解析器** - 灵活的动作处理
20. **统计** - 路由分析和过滤

这个全面的功能集使CloudCastle HTTP Router成为PHP应用程序最完整的路由解决方案。

---

## 📚 另请参阅
- [USER_GUIDE.md](USER_GUIDE.md) - 完整用户指南
- [FEATURES_INDEX.md](FEATURES_INDEX.md) - 功能类别
- [API_REFERENCE.md](API_REFERENCE.md) - API参考
- [FAQ.md](FAQ.md) - 常见问题

---

© 2024 CloudCastle HTTP Router  
[⬆ 返回顶部](#cloudcastle-http-router-完整功能列表)