# CloudCastle HTTP Router

[English](../en/README.md) | [Русский](../../README.md) | [Deutsch](../de/README.md) | [Français](../fr/README.md) | [**中文**](README.md)

---

[![PHP Version](https://img.shields.io/badge/PHP-8.2%2B-blue.svg)](https://php.net)
[![License](https://img.shields.io/badge/license-MIT-green.svg)](../../LICENSE)
[![Tests](https://img.shields.io/badge/tests-501%2F501-success.svg)](../ru/TESTS_DETAILED.md)
[![PHPStan](https://img.shields.io/badge/PHPStan-Level%20Max-brightgreen.svg)](../../reports/phpstan.txt)
[![Performance](https://img.shields.io/badge/performance-54k%20req%2Fsec-brightgreen.svg)](../ru/PERFORMANCE_ANALYSIS.md)
[![Features](https://img.shields.io/badge/features-209%2B-blue.svg)](../../FEATURES_LIST.md)

**功能强大、灵活且安全的 PHP 8.2+ HTTP 路由库**，专注于性能、安全性和易用性。

## ⚡ 为什么选择 CloudCastle HTTP Router？

### 🎯 核心优势

- ⚡ **最高性能** - **54,891 请求/秒**，比大多数竞争对手更快
- 🔒 **全面安全** - 12+ 内置保护机制（OWASP Top 10）
- 💎 **209+ 功能** - 市场上功能最丰富的路由库
- 💾 **最小内存占用** - 每个路由仅 **1.32 KB**
- 📊 **极致可扩展性** - 测试过 **1,160,000 个路由**
- 🔌 **可扩展性** - 插件系统、中间件、宏
- 📦 **完全自主** - 不依赖框架
- ✅ **100% 可靠性** - 501 个测试，0 错误，95%+ 覆盖率

---

## 🚀 快速开始

### 安装

```bash
composer require cloud-castle/http-router
```

### 基础示例

```php
<?php

use CloudCastle\Http\Router\Facade\Route;

// 简单路由
Route::get('/users', fn() => '用户列表');
Route::post('/users', fn() => '创建用户');
Route::get('/users/{id}', fn($id) => "用户: $id")
    ->where('id', '[0-9]+');

// 调度
$route = Route::dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
echo $route->run();
```

### 高级示例

```php
// 受保护的 API
Route::group(['prefix' => '/api/v1'], function() {
    Route::get('/users', [UserController::class, 'index'])
        ->name('api.users')
        ->throttle(100, 1)  // 每分钟 100 个请求
        ->middleware([AuthMiddleware::class])
        ->tag('api');
    
    Route::post('/users', [UserController::class, 'store'])
        ->throttle(20, 1)
        ->whitelistIp(['192.168.1.0/24'])
        ->middleware([AuthMiddleware::class, AdminMiddleware::class]);
});
```

---

## 💡 核心功能

### 1️⃣ HTTP 方法（7 种方式）

```php
Route::get('/users', $action);
Route::post('/users', $action);
Route::put('/users/{id}', $action);
Route::patch('/users/{id}', $action);
Route::delete('/users/{id}', $action);
Route::any('/page', $action);              // 任意方法
Route::match(['GET', 'POST'], '/form', $action);  // 多种方法
Route::custom('VIEW', '/preview', $action);       // 自定义方法
```

### 2️⃣ 智能参数

```php
// 基础参数
Route::get('/users/{id}', $action);

// 带验证
Route::get('/users/{id}', $action)->where('id', '[0-9]+');
Route::get('/posts/{slug}', $action)->where('slug', '[a-z0-9-]+');

// 可选参数
Route::get('/blog/{category?}', $action);

// 默认值
Route::get('/posts/{page}', $action)->defaults(['page' => 1]);

// 内联模式
Route::get('/users/{id:[0-9]+}', $action);
```

### 3️⃣ 路由组

```php
Route::group([
    'prefix' => '/api/v1',
    'middleware' => [AuthMiddleware::class],
    'domain' => 'api.example.com',
    'port' => 8080,
    'namespace' => 'App\\Controllers\\Api',
], function() {
    Route::get('/users', 'UserController@index');
    Route::get('/posts', 'PostController@index');
});
```

### 4️⃣ 速率限制和自动封禁

```php
// 速率限制
Route::post('/api/login', $action)
    ->throttle(5, 1);  // 每分钟 5 次尝试

// 使用 TimeUnit 枚举
use CloudCastle\Http\Router\TimeUnit;

Route::post('/api/submit', $action)
    ->throttle(100, TimeUnit::HOUR->value);

// 自动封禁系统
use CloudCastle\Http\Router\BanManager;

$banManager = new BanManager(
    maxViolations: 5,      // 5 次违规
    banDuration: 3600      // 封禁 1 小时
);

Route::post('/login', $action)
    ->throttle(3, 1)
    ->getRateLimiter()
    ?->setBanManager($banManager);
```

### 5️⃣ IP 过滤

```php
// 白名单（仅允许的 IP）
Route::get('/admin', $action)
    ->whitelistIp(['192.168.1.1', '10.0.0.0/8']);

// 黑名单（被阻止的 IP）
Route::get('/public', $action)
    ->blacklistIp(['1.2.3.4', '5.6.7.0/24']);

// 在组中
Route::group(['whitelistIp' => ['192.168.1.0/24']], function() {
    Route::get('/admin/users', $action);
    Route::get('/admin/settings', $action);
});
```

### 6️⃣ 中间件

```php
// 全局
Route::middleware([CorsMiddleware::class, LoggerMiddleware::class]);

// 在路由上
Route::get('/admin', $action)
    ->middleware([AuthMiddleware::class, AdminMiddleware::class]);

// 内置中间件
Route::get('/api/data', $action)->auth();        // AuthMiddleware
Route::get('/api/public', $action)->cors();      // CorsMiddleware
Route::get('/secure', $action)->secure();        // HTTPS 强制
```

### 7️⃣ 命名路由和 URL 生成

```php
// 命名
Route::get('/users/{id}', $action)->name('users.show');

// 自动命名
Route::enableAutoNaming();

// URL 生成
$url = route_url('users.show', ['id' => 5]);  // /users/5

// 带域名
use CloudCastle\Http\Router\UrlGenerator;

$generator = new UrlGenerator();
$url = $generator->generate('users.show', ['id' => 5])
    ->toDomain('api.example.com')
    ->toProtocol('https')
    ->absolute();  // https://api.example.com/users/5

// 签名 URL
$signedUrl = $generator->signed('verify.email', ['user' => 123], 3600);
```

### 8️⃣ 路由快捷方式（14 种方法）

```php
Route::get('/api/data', $action)->apiEndpoint();  // API + CORS + JSON
Route::get('/admin', $action)->admin();           // Auth + Admin + Whitelist
Route::get('/page', $action)->public();           // Public 标签
Route::get('/dashboard', $action)->protected();   // Auth + HTTPS
Route::get('/localhost', $action)->localhost();   // 仅本地主机

// 节流快捷方式
Route::post('/api/submit', $action)->throttleStandard();   // 60/分钟
Route::post('/api/strict', $action)->throttleStrict();     // 10/分钟
Route::post('/api/bulk', $action)->throttleGenerous();     // 1000/分钟
```

### 9️⃣ 路由宏（7 个宏）

```php
// RESTful 资源
Route::resource('/users', UserController::class);
// 创建：index, create, store, show, edit, update, destroy

// API 资源（无 create/edit）
Route::apiResource('/posts', PostController::class);

// CRUD（简单）
Route::crud('/products', ProductController::class);

// 认证
Route::auth();
// 创建：login, logout, register, password.request, password.reset

// 管理面板
Route::adminPanel('/admin');

// API 版本控制
Route::apiVersion('v1', function() {
    Route::get('/users', $action);
});

// Webhooks
Route::webhooks('/webhooks', WebhookController::class);
```

### 🔟 辅助函数（18 个函数）

```php
route('users.show');              // 按名称获取路由
current_route();                  // 当前路由
previous_route();                 // 上一个路由
route_is('users.*');              // 检查路由名称
route_name();                     // 当前路由名称
router();                         // 路由器实例
dispatch_route($uri, $method);    // 调度
route_url('users.show', ['id' => 5]);  // 生成 URL
route_has('users.show');          // 检查存在性
route_stats();                    // 路由统计
routes_by_tag('api');             // 按标签获取路由
route_back();                     // 返回
```

---

## 📊 性能

### 基准测试（PHPBench）

| 操作 | 时间 | 性能 |
|------|------|------|
| **添加 1000 个路由** | 3.435ms | 0.0034ms/路由 |
| **匹配第一个路由** | 123μs | 8,130 请求/秒 |
| **匹配中间路由** | 1.746ms | 573 请求/秒 |
| **匹配最后一个路由** | 3.472ms | 288 请求/秒 |
| **命名查找** | 3.858ms | 259 请求/秒 |
| **路由组** | 2.577ms | 388 请求/秒 |
| **带中间件** | 2.030ms | 493 请求/秒 |
| **带参数** | 73μs | 13,699 请求/秒 |

### 负载测试

| 场景 | 路由 | 请求 | 结果 | 内存 |
|------|------|------|------|------|
| **轻负载** | 100 | 1,000 | **53,975 请求/秒** | 6 MB |
| **中等负载** | 500 | 5,000 | **54,135 请求/秒** | 6 MB |
| **重负载** | 1,000 | 10,000 | **54,891 请求/秒** | 6 MB |

### 压力测试

- ✅ **1,160,000 个路由** 已处理
- ✅ **1.46 GB 内存**（1.32 KB/路由）
- ✅ **200,000 个请求** 在 3.8 秒内
- ✅ **0 错误** 在极端负载下

📖 更多：[性能分析](../ru/PERFORMANCE_ANALYSIS.md)

---

## 🔒 安全性

### 内置保护机制

CloudCastle HTTP Router 包含 **12+ 安全层**：

✅ **速率限制** - DDoS 防护  
✅ **自动封禁系统** - 自动阻止  
✅ **IP 过滤** - 带 CIDR 的白名单/黑名单  
✅ **HTTPS 强制** - 强制使用 HTTPS  
✅ **路径遍历保护** - 防止 ../../../  
✅ **SQL 注入保护** - 参数验证  
✅ **XSS 保护** - 转义  
✅ **ReDoS 保护** - 正则表达式 DoS 保护  
✅ **方法覆盖保护** - 防止方法欺骗  
✅ **缓存注入保护** - 安全缓存  
✅ **IP 欺骗保护** - X-Forwarded-For 验证  
✅ **协议限制** - HTTP/HTTPS/WS/WSS

### 安全测试

**13/13 OWASP Top 10 测试通过** ✅

```
✓ 路径遍历保护
✓ SQL 注入保护
✓ XSS 保护
✓ 速率限制（A07:2021）
✓ IP 过滤和欺骗
✓ 方法覆盖攻击
✓ 缓存注入
✓ ReDoS 保护
✓ Unicode 安全
✓ 资源耗尽
✓ HTTPS 强制
✓ 域名/端口限制
✓ 自动封禁系统
```

📖 更多：[安全报告](../ru/SECURITY_REPORT.md)

---

## 🧩 高级功能

### 插件系统

```php
use CloudCastle\Http\Router\Contracts\PluginInterface;

class LoggerPlugin implements PluginInterface {
    public function beforeDispatch(Route $route, string $uri, string $method): void {
        log("请求: $method $uri");
    }
    
    public function afterDispatch(Route $route, mixed $result): mixed {
        log("响应已生成");
        return $result;
    }
    
    public function onRouteRegistered(Route $route): void {
        log("路由已注册: {$route->getUri()}");
    }
    
    public function onException(Route $route, \Exception $e): void {
        log("错误: " . $e->getMessage());
    }
}

Route::registerPlugin(new LoggerPlugin());
```

### 路由加载器（5 种类型）

```php
use CloudCastle\Http\Router\Loader\*;

// JSON
$loader = new JsonLoader($router);
$loader->load('routes.json');

// YAML
$loader = new YamlLoader($router);
$loader->load('routes.yaml');

// XML
$loader = new XmlLoader($router);
$loader->load('routes.xml');

// PHP 属性
$loader = new AttributeLoader($router);
$loader->loadFromDirectory('app/Controllers');

// PHP 文件
require 'routes/web.php';
require 'routes/api.php';
```

### 表达式语言

```php
Route::get('/admin', $action)
    ->condition('request.ip == "192.168.1.1" and request.time > 9');

Route::get('/api/data', $action)
    ->condition('request.header["X-API-Key"] == "secret"');
```

### 路由缓存

```php
// 启用缓存
$router->enableCache('var/cache/routes');

// 编译
$router->compile();

// 从缓存自动加载
if ($router->loadFromCache()) {
    // 缓存已加载 - 即时启动
} else {
    // 注册路由
    require 'routes/web.php';
    $router->compile();
}

// 清除
$router->clearCache();
```

### PSR 支持

```php
// PSR-7
use Psr\Http\Message\ServerRequestInterface;
$request = ServerRequestFactory::fromGlobals();

// PSR-15
use CloudCastle\Http\Router\Psr15\Psr15MiddlewareAdapter;
$psrMiddleware = new Psr15MiddlewareAdapter($router);
```

---

## 📚 文档

### 主要文档

- 📖 [用户指南](USER_GUIDE.md) - 所有功能的完整指南
- 🔍 [API 参考](API_REFERENCE.md) - 详细的 API 文档
- 💡 [示例](../../examples/) - 20+ 即用示例
- ❓ [常见问题](FAQ.md) - 常见问题解答
- 🎯 [功能列表](../../FEATURES_LIST.md) - 所有 209+ 功能

### 报告和分析

- 📊 [测试摘要](../ru/SUMMARY.md)
- 🧪 [详细测试](../ru/TESTS_DETAILED.md)
- ⚡ [性能分析](PERFORMANCE_ANALYSIS.md)
- 🔒 [安全报告](SECURITY_REPORT.md)
- ⚖️ [与替代方案比较](COMPARISON.md)

---

## 🧪 代码质量

### 测试统计

```
总测试数：     501
通过：          501 ✅
失败：          0
覆盖率：        ~95%
断言：          1,200+
```

### 静态分析

- **PHPStan：** 级别 MAX - 0 个关键错误 ✅
- **PHPMD：** 0 个问题 ✅
- **PHPCS：** PSR-12 - 0 个违规 ✅
- **PHP-CS-Fixer：** 0 个文件需要修复 ✅
- **Rector：** 0 个更改需要 ✅

### 运行测试

```bash
# 所有测试
composer test

# 按类别
composer test:unit          # 单元测试
composer test:security      # 安全测试
composer test:performance   # 性能测试
composer test:load          # 负载测试
composer test:stress        # 压力测试

# 静态分析
composer phpstan            # PHPStan
composer phpcs              # PHP_CodeSniffer
composer phpmd              # PHP Mess Detector
composer analyse            # 所有分析器

# 基准测试
composer benchmark          # PHPBench
```

---

## ⚖️ 与替代方案比较

| 特性 | CloudCastle | Laravel | Symfony | FastRoute | Slim |
|------|-------------|---------|---------|-----------|------|
| **性能** | **54k 请求/秒** | 35k | 40k | 60k | 45k |
| **内存（1k 路由）** | **6 MB** | 12 MB | 10 MB | 4 MB | 5 MB |
| **功能** | **209+** | 150+ | 180+ | 20+ | 50+ |
| **速率限制** | ✅ 内置 | ✅ | ❌ | ❌ | ⚠️ 包 |
| **自动封禁** | ✅ | ❌ | ❌ | ❌ | ❌ |
| **IP 过滤** | ✅ 内置 | ⚠️ 中间件 | ❌ | ❌ | ⚠️ 中间件 |
| **表达式语言** | ✅ | ❌ | ⚠️ 有限 | ❌ | ❌ |
| **插件** | ✅ 4 个内置 | ✅ | ⚠️ 事件 | ❌ | ❌ |
| **加载器** | ✅ 5 种类型 | ⚠️ 仅 PHP | ⚠️ XML/YAML | ❌ | ❌ |
| **宏** | ✅ 7 个宏 | ✅ | ❌ | ❌ | ❌ |
| **快捷方式** | ✅ 14 种方法 | ⚠️ 一些 | ❌ | ❌ | ❌ |
| **辅助函数** | ✅ 18 个函数 | ✅ 10+ | ⚠️ 很少 | ❌ | ⚠️ 很少 |
| **PSR-15** | ✅ | ✅ | ✅ | ❌ | ✅ |
| **独立** | ✅ | ❌ 框架 | ⚠️ 复杂 | ✅ | ✅ |
| **测试** | **501** | 300+ | 500+ | 100+ | 200+ |
| **覆盖率** | **95%+** | 90%+ | 95%+ | 80%+ | 85%+ |

### 结论

**CloudCastle HTTP Router** - **性能**、**功能**和**安全性**之间的最佳平衡。

✅ **最适合：**
- 高安全要求的 API 服务器
- 微服务架构
- 高负载系统（50k+ 请求/秒）
- 需要最大路由控制的项目

📖 更多：[与替代方案比较](COMPARISON.md)

---

## 🤝 贡献

我们欢迎对 CloudCastle HTTP Router 开发的贡献！

### 如何帮助

1. ⭐ 给项目加星
2. 🐛 报告错误
3. 💡 建议新功能
4. 📝 改进文档
5. 🔧 提交 Pull Request

### 流程

```bash
# 1. Fork 项目
git clone https://github.com/YOUR_USERNAME/cloud-casstle-http-router.git

# 2. 创建功能分支
git checkout -b feature/AmazingFeature

# 3. 提交更改
git commit -m 'Add some AmazingFeature'

# 4. 推送到分支
git push origin feature/AmazingFeature

# 5. 打开 Pull Request
```

### 要求

- ✅ 遵循 PSR-12
- ✅ 编写测试（PHPUnit）
- ✅ 更新文档
- ✅ 检查 PHPStan/PHPCS
- ✅ 一个 PR = 一个功能

📖 更多：[CONTRIBUTING.md](../../CONTRIBUTING.md)

---

## 📄 许可证

此项目在 **MIT 许可证** 下发布。详见 [LICENSE](../../LICENSE)。

```
MIT License

Copyright (c) 2024 CloudCastle

Permission is hereby granted, free of charge, to any person obtaining a copy...
```

---

## 💬 支持

### 联系方式

- 📧 **邮箱：** zorinalexey59292@gmail.com
- 💬 **Telegram：** [@CloudCastle85](https://t.me/CloudCastle85)
- 📢 **Telegram 频道：** [@cloud_castle_news](https://t.me/cloud_castle_news)
- 🐛 **GitHub Issues：** [报告问题](https://github.com/zorinalexey/cloud-casstle-http-router/issues)
- 💡 **GitHub Discussions：** [讨论](https://github.com/zorinalexey/cloud-casstle-http-router/discussions)

### 有用链接

- [📚 文档](../ru/)
- [💡 使用示例](../../examples/)
- [📋 更新日志](../../CHANGELOG.md)
- [🗺️ 路线图](../../ROADMAP.md)
- [🔒 安全策略](../../SECURITY.md)
- [📜 行为准则](../../CODE_OF_CONDUCT.md)
- [🤝 贡献者](../../CONTRIBUTORS.md)

---

## 🌟 致谢

非常感谢所有[贡献者](../../CONTRIBUTORS.md)对项目的贡献！

### 使用的技术

- [PHPUnit](https://phpunit.de/) - 测试
- [PHPStan](https://phpstan.org/) - 静态分析
- [PHPCS](https://github.com/squizlabs/PHP_CodeSniffer) - 代码风格
- [PHPBench](https://phpbench.readthedocs.io/) - 基准测试
- [Rector](https://getrector.org/) - 重构

---

## 📈 项目统计

![GitHub Stars](https://img.shields.io/github/stars/zorinalexey/cloud-casstle-http-router?style=social)
![GitHub Forks](https://img.shields.io/github/forks/zorinalexey/cloud-casstle-http-router?style=social)
![GitHub Watchers](https://img.shields.io/github/watchers/zorinalexey/cloud-casstle-http-router?style=social)

![GitHub Issues](https://img.shields.io/github/issues/zorinalexey/cloud-casstle-http-router)
![GitHub Pull Requests](https://img.shields.io/github/issues-pr/zorinalexey/cloud-casstle-http-router)
![GitHub Last Commit](https://img.shields.io/github/last-commit/zorinalexey/cloud-casstle-http-router)

---

**Made with ❤️ by [CloudCastle](https://github.com/zorinalexey)**

---

[⬆ 返回顶部](#cloudcastle-http-router)
