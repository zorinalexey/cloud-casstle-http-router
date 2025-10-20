# FAQ - 常见问题

[English](../en/FAQ.md) | [Русский](../ru/FAQ.md) | [Deutsch](../de/FAQ.md) | [Français](../fr/FAQ.md) | [**中文**](FAQ.md)

---

**版本:** 1.1.1  
**日期:** 2025年10月

---

## 📚 文档导航

### 主要文档
- [README](../../README.md) - 首页
- [USER_GUIDE](USER_GUIDE.md) - 完整用户指南
- [FEATURES_INDEX](FEATURES_INDEX.md) - 所有功能目录
- [API_REFERENCE](API_REFERENCE.md) - API参考

### 功能
- [详细功能文档](features/) - 22个类别
- [ALL_FEATURES](ALL_FEATURES.md) - 完整功能列表

### 测试和报告
- [TESTS_SUMMARY](TESTS_SUMMARY.md) - 测试摘要
- [详细测试报告](tests/) - 7个报告
- [PERFORMANCE_ANALYSIS](PERFORMANCE_ANALYSIS.md) - 性能分析
- [SECURITY_REPORT](SECURITY_REPORT.md) - 安全报告

### 其他
- **[FAQ](FAQ.md) - 常见问题** ← 您在这里
- [COMPARISON](COMPARISON.md) - 与替代方案比较
- [DOCUMENTATION_SUMMARY](DOCUMENTATION_SUMMARY.md) - 文档摘要

---

## 内容

### 一般
1. [什么是CloudCastle HTTP Router？](#什么是cloudcastle-http-router)
2. [为什么选择CloudCastle？](#为什么选择cloudcastle)
3. [有什么要求？](#要求)
4. [如何安装CloudCastle？](#安装)

### 性能
5. [CloudCastle有多快？](#性能)
6. [如何提高性能？](#优化)
7. [什么是路由缓存？](#缓存)
8. [能处理多少路由？](#可扩展性)

### 安全
9. [CloudCastle有多安全？](#安全)
10. [什么是速率限制？](#速率限制)
11. [什么是自动封禁系统？](#自动封禁)
12. [如何保护管理面板？](#保护管理)

### 使用
13. [如何注册路由？](#注册路由)
14. [什么是路由组？](#路由组)
15. [如何使用中间件？](#中间件)
16. [如何构建RESTful API？](#restful-api)

### 高级
17. [什么是路由宏？](#宏)
18. [如何使用插件？](#插件)
19. [PSR支持？](#psr支持)
20. [可以与框架一起使用吗？](#框架)

---

## 一般

### 什么是CloudCastle HTTP Router？

CloudCastle HTTP Router是一个现代化的PHP 8.2+路由库，提供**209+功能**来构建安全和高性能的Web应用程序。

关键亮点：
- ⚡ 53,637 req/sec性能
- 🔒 13/13 OWASP Top 10合规
- 💎 209+功能
- ✅ 501个测试（100%通过）

---

### 为什么选择CloudCastle？

CloudCastle是唯一具有以下功能的路由器：

1. 内置速率限制
```php
Route::post('/api', $action)->throttle(60, 1);
```

2. 自动封禁系统
```php
$banManager = new BanManager(5, 3600);
Route::post('/login', $action)
    ->throttle(3, 1)
    ->getRateLimiter()?->setBanManager($banManager);
```

3. 内置IP过滤
```php
Route::get('/admin', $action)->whitelistIp(['192.168.1.0/24']);
```

4. 209+功能 — 比竞争对手更多。

比较：
- Symfony: 180+功能，无内置速率限制
- Laravel: 150+功能，仅框架
- FastRoute: ~20功能，纯速度
- Slim: ~50功能，基本功能

CloudCastle = 速度、安全性和功能的最佳平衡。

---

### 要求

最低要求：
- PHP 8.2+
- Composer
- ~2 MB磁盘空间

推荐：
- PHP 8.3+
- 启用Opcache
- 128 MB+ memory_limit

支持的PHP版本：8.2/8.3/8.4

---

### 安装

```bash
composer require cloud-castle/http-router
```

快速开始：
```php
<?php
require 'vendor/autoload.php';
use CloudCastle\Http\Router\Facade\Route;
Route::get('/users', fn() => '用户列表');
$route = Route::dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
echo $route->run();
```

---

## 性能

### CloudCastle有多快？

负载测试：
- 轻量（100路由）：55,923 req/sec
- 中等（500路由）：54,680 req/sec
- 重量（1000路由）：53,637 req/sec

比较（1000路由）：
1. FastRoute: 60,000 req/sec
2. CloudCastle: 53,637 req/sec（具有209+功能）
3. Slim: 45,000 req/sec
4. Symfony: 40,000 req/sec
5. Laravel: 35,000 req/sec

---

### 优化

1) 路由缓存
```php
$router->enableCache('cache/routes');
if (!$router->loadFromCache()) {
    require 'routes/web.php';
    $router->compile();
}
```

2) 内联参数
```php
// 更快
Route::get('/users/{id:[0-9]+}', $action);
// 更慢
Route::get('/users/{id}', $action)->where('id', '[0-9]+');
```

3) 分组
```php
Route::group(['prefix' => '/api', 'middleware' => [...]], function() {
    // 100个路由
});
```

---

### 缓存

将路由编译为优化格式以实现即时加载。

无缓存：~10–50 ms初始化  
有缓存：~0.1–1 ms初始化  
加速：10–50倍

---

### 可扩展性

测试高达1,095,000路由；~1.39 KB/路由。

---

## 安全

### CloudCastle有多安全？

内置保护（13/13 OWASP）：路径遍历、SQL注入、XSS、IP过滤、IP欺骗、ReDoS、速率限制、自动封禁、HTTPS、协议、域名/端口、缓存注入。

---

### 速率限制
```php
Route::post('/api/submit', $action)->throttle(60, 1);
// 超出时 → TooManyRequestsException (HTTP 429)
```

### 自动封禁
```php
$banManager = new BanManager(5, 3600);
Route::post('/login', $action)
    ->throttle(3, 1)
    ->getRateLimiter()?->setBanManager($banManager);
```

### 保护管理
```php
Route::group([
  'prefix' => '/admin',
  'middleware' => [AuthMiddleware::class, AdminMiddleware::class],
  'https' => true,
  'whitelistIp' => ['192.168.1.0/24'],
  'throttle' => [30, 1]
], function() {
  Route::get('/dashboard', [AdminController::class, 'dashboard']);
});
```

---

## 使用

### 注册路由
```php
use CloudCastle\Http\Router\Facade\Route;
Route::get('/users', [UserController::class, 'index']);
Route::post('/users', [UserController::class, 'store']);
```

### 路由组
```php
Route::group([
  'prefix' => '/api/v1',
  'middleware' => [AuthMiddleware::class],
  'throttle' => [100, 1],
  'tags' => 'api'
], function() {
  Route::get('/users', $action);
  Route::get('/posts', $action);
});
```

### 中间件
- 全局：`Route::middleware([...])`
- 路由：`->middleware([...])`
- 组：`Route::group(['middleware'=>[...]])`

### RESTful API
```php
Route::apiResource('users', ApiUserController::class, 100);
```

---

## 高级

### 宏
- `resource()`, `apiResource()`, `crud()`, `auth()`, `adminPanel()`, `apiVersion()`, `webhooks()`

### 插件
实现`PluginInterface`；内置：LoggerPlugin, AnalyticsPlugin, ResponseCachePlugin。

### PSR支持
PSR-1, PSR-4, PSR-7, PSR-12, PSR-15

### 框架
独立工作；可与Laravel/Symfony集成。

---

## 📚 另请参阅
- [USER_GUIDE.md](USER_GUIDE.md)
- [FEATURES_INDEX.md](FEATURES_INDEX.md)
- [TESTS_SUMMARY.md](TESTS_SUMMARY.md)
- [COMPARISON.md](COMPARISON.md)

---

© 2024 CloudCastle HTTP Router  
[⬆ 返回顶部](#faq---常见问题)