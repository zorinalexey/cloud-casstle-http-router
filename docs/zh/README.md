# CloudCastle HTTP 路由器文档

**语言：** 🇷🇺 俄语 | [🇬🇧 英文](../en/README.md) | [🇩🇪 德语](../de/README.md) | [🇫🇷 法语](../fr/README.md) | [🇨🇳中文](../zh/README.md)

[📚 目录](_table-of-contents.md) | [🏠主页](README.md)

---

欢迎使用 CloudCastle HTTP 路由器的文档 - 适用于 PHP 8.2+ 的现代、快速且安全的路由器。

## 📚 内容

＃＃＃ 入门

- [主页](../../README.md) - 快速入门和基本信息
- [入门](getting-started.md) - 初学者指南
- [Best Practices](best-practices.md) - 最佳开发实践

### 测试

- [所有测试摘要](test-summary.md) - 所有测试和基准测试的结果
- [单元测试](unit-tests.md) - 419个测试的详细结果
- [安全测试](security-tests.md) - 13项安全检查分析
- [性能测试](performance-tests.md) - 性能基准
- [负载测试](load-tests.md) - 负载测试（50K+ 请求/秒）
- [压力测试](stress-tests.md) - 极端条件（1M+路线）

### 可能性

- [所有功能](features.md) - 30 多个功能的完整列表
- [自动命名](auto-naming.md) - 自动命名路由（独特的功能！）
- [路线快捷方式](shortcuts.md) - 超过 13 个用于快速设置的快捷方式
- [路由宏](macros.md) - 7+ 宏（代码减少 80-97%）
- [帮助函数](helpers.md) - 15+ 全局函数
- [ThrottleWithBan](throttle-with-ban.md) - 速率限制 + 自动禁止（独特功能！）
- [Tags System](tags.md) - 用于过滤路由的标签系统
- [路由加载器](loaders.md) - YAML/XML/JSON/属性配置
- [中间件](middleware.md) - 中间件和 PSR-15 系统
- [Facade](facade.md) - 静态使用（Laravel 风格）
- [代码质量](code-quality.md) - PHPStan、PHPMD、PHPCS 报告

＃＃＃ 比较

- [与竞争对手详细比较](comparison-detailed.md) - 6款路由器全面分析

## 🎯 关于项目

CloudCastle HTTP 路由器是一款高性能路由器，具有一组独特的安全功能和配置灵活性。

### 关键指标

- **性能**：50,946 请求/秒（平均）
- **可扩展性**：1,095,000 多条路线
- **安全**：13项安全机制
- **测试**：447 次测试，1043 多个断言
- **覆盖率**：100% 成功率

## 📊 测试结果

＃＃＃ 表现

|类别 |结果 |状态 |
|:---|:---:|:---:|
| Light Load | 52,488 req/sec | ✅ |
| Medium Load | 45,260 req/sec | ✅ |
| Heavy Load | 55,089 req/sec | ✅ |
| Concurrent Access | 8,316 req/sec | ✅ |

### 可扩展性

|参数|意义|
|:---|:---:|
|最大路线| 1,095,000 | 1,095,000
|记忆/路线| 1.39 KB |
|嵌套深度| 50 个级别 |
| URI 长度 | 1,980 个字符 |

＃＃＃ 安全

✅ 全部 13 项安全测试均成功通过：
- Path Traversal Protection
- SQL Injection Prevention
- XSS Protection
- IP Whitelist/Blacklist
- IP Spoofing Protection
- Domain Security
- ReDoS Protection
- Method Override Protection
- Mass Assignment Protection
- Cache Injection Prevention
- Resource Exhaustion Prevention
- Unicode Security

## 🆚 与竞争对手的比较

### 性能（请求/秒）

1. **CloudCastle** - 50,946 🥇
2. FastRoute - 47,033 🥈
3. AltoRouter - 39,967 🥉
4. Slim - 37,167
5. Laravel - 16,233
6. Symfony - 15,633

### 功能（功能数量）

1. **CloudCastle** - 25/25 (100%) 🥇
2. Symfony - 10/25 (40%) 🥈
3. Laravel - 9/25 (36%) 🥉
4. Slim - 7/25 (28%)
5. AltoRouter - 4/25 (16%)
6. FastRoute - 3/25 (12%)

### 可扩展性（最大路由）

1. **CloudCastle** - 1,095,000 🥇
2. FastRoute - 500,000 🥈
3. Slim - 200,000 🥉
4. AltoRouter - 150,000
5. Symfony - 100,000
6. Laravel - 80,000

## 🚀 快速开始

＃＃＃ 安装

```bash
composer require cloud-castle/http-router
```

### 基本使用

```php
use CloudCastle\Http\Router\Router;

$router = new Router();

$router->get('/', function() {
    return 'Hello, World!';
});

$router->get('/users/{id}', function($id) {
    return "User: {$id}";
});

$result = $router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
```

###高级功能

```php
// Middleware
$router->get('/admin', 'AdminController@index')
    ->middleware(['auth', 'admin']);

// Rate Limiting
$router->get('/api/data', 'ApiController@data')
    ->perMinute(60);

// Conditions
$router->get('/premium', 'PremiumController@index')
    ->condition('user.subscription == "premium"');

// Groups
$router->group(['prefix' => '/api/v1'], function($router) {
    $router->get('/users', 'UserController@index');
    $router->get('/posts', 'PostController@index');
});
```

## 💡推荐

### 何时使用 CloudCastle

✅ **适合：**
- 高负载API服务
- 微服务架构
- 有安全要求的项目
- 企业应用
- 多租户系统

✅ **优点：**
- 最高性能
- 更好的可扩展性
- 全面的安全保障
- 丰富的功能
- 现代代码（PHP 8.2+）

### Best Practices

1. **在生产中使用缓存**
2. **按功能对路线进行分组**
3. **使用命名路由**进行 URL 生成
4. **公共 API 的使用速率限制**
5. **为大型配置自定义 YAML/XML/JSON**

## 📖 其他资源

### 文档

- [测试摘要](test-summary.md) - 所有测试的详细结果
- [路由器比较](comparison-detailed.md) - 替代方案的完整分析

### 示例

使用示例位于“examples/”目录中：
- `basic-usage.php` - 基本路由
- `yaml-routes.yaml` - YAML 配置
- `xml-routes.xml` - XML配置
- `json-routes.json` - JSON 配置 ⭐
- `attributes-usage.php` - PHP 8 Attributes
- `middleware-advanced.php` - 高级中间件
- `expression-usage.php` - Expression Language

### 报告

`reports/`目录下的测试结果：
- `phpunit.txt` - PHPUnit 结果
- `security-tests.txt` - 安全测试
- `性能测试.txt` - 基准测试
- `load-tests.txt` - 负载测试
- `stress-tests.txt` - 压力测试
- `phpstan.txt` - 静态分析
- `phpcs.txt` - code style
- `phpmd.txt` - code quality

## 🤝 支持

- **Issues**: [GitHub Issues](https://github.com/zorinalexey/cloud-casstle-http-router/issues)
- **Email**: zorinalexey59292@gmail.com
- **Telegram**: [@CloudCastle85](https://t.me/CloudCastle85)

## 📄 许可证

MIT 许可证 - 请参阅 [LICENSE](../../LICENSE) 文件。

---

**CloudCastle HTTP 路由器** - 最高性能。完整的安全保障。最丰富的功能。

*最后更新：2025 年 10 月 18 日*

---

[📚 目录](_table-of-contents.md) | [🏠主页](README.md)

