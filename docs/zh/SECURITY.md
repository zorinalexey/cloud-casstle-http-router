# 安全策略

[English](../en/SECURITY.md) | [Русский](../../SECURITY.md) | [Deutsch](../de/SECURITY.md) | [Français](../fr/SECURITY.md) | [**中文**](SECURITY.md)

---

## 支持的版本

我们为以下版本提供安全更新：

| 版本 | 支持状态          |
| ------- | ------------------ |
| 1.1.x   | :white_check_mark: 是 |
| 1.0.x   | :white_check_mark: 是 |
| < 1.0   | :x: 否            |

## 报告漏洞

### 如何报告

如果您在 CloudCastle HTTP Router 中发现了安全漏洞，请**保密**地向我们报告。我们认真对待所有安全问题。

**不要为安全漏洞创建公开的 GitHub issues。**

### 联系方式

1. **邮箱：** zorinalexey59292@gmail.com
   - 主题：`[SECURITY] 问题描述`
   - 包含：版本、漏洞描述、重现步骤

2. **Telegram：** [@CloudCastle85](https://t.me/CloudCastle85)
   - 用于紧急情况

### 报告中应包含的内容

请在您的报告中包含以下信息：

- 漏洞的**描述**
- **重现步骤**
- 库的**版本**
- 漏洞的**潜在影响**
- **建议的修复**（如果有）
- 您的**联系方式**以便反馈

### 预期时间

1. **确认收到** - 24小时内
2. **初步分析** - 48小时内
3. **修复计划** - 7天内
4. **补丁发布** - 根据严重程度：
   - 严重：1-3天
   - 高：7-14天
   - 中等：14-30天
   - 低：下一个版本

### 披露流程

1. 我们确认收到报告
2. 我们验证和评估漏洞
3. 我们开发修复方案
4. 我们测试修复方案
5. 我们发布补丁
6. 我们发布安全公告
7. 我们感谢报告者（如果他们不反对）

## 内置保护

CloudCastle HTTP Router 包含以下安全措施：

### 攻击保护

✅ **路径遍历保护**
- 自动路径清理
- 阻止危险序列（../, ./, \\）
- URI 验证

✅ **SQL 注入保护**
- 路由参数转义
- 安全的用户输入处理

✅ **XSS 保护**
- HTML 实体编码
- 危险字符转义
- 内容安全策略兼容性

✅ **IP 欺骗保护**
- X-Forwarded-For 标头验证
- 真实 IP 验证
- 欺骗保护

✅ **ReDoS 保护**
- 复杂正则表达式限制
- 模式匹配超时
- 安全的默认模式

✅ **方法覆盖攻击保护**
- 受控的 X-HTTP-Method-Override 处理
- 可选激活
- 允许方法的白名单

✅ **缓存注入保护**
- 缓存路径验证
- 安全序列化
- 完整性检查

✅ **资源耗尽保护**
- 路由数量限制
- 内存限制
- 优化算法

✅ **Unicode 安全**
- 正确处理多字节字符
- Unicode 规范化
- Unicode 漏洞保护

### 额外措施

✅ **速率限制**
```php
$route->throttle(60, 1); // 每分钟 60 个请求
```

✅ **IP 过滤**
```php
$route->whitelistIp(['192.168.1.0/24']);
$route->blacklistIp(['10.0.0.1']);
```

✅ **自动封禁系统**
```php
$banManager->enableAutoBan(5); // 5 次尝试后封禁
```

✅ **HTTPS 强制**
```php
$route->https(); // 要求 HTTPS
```

✅ **域名隔离**
```php
$router->group(['domain' => 'api.example.com'], function() {
    // 仅限 api.example.com
});
```

✅ **端口隔离**
```php
$router->group(['port' => 8080], function() {
    // 仅在端口 8080
});
```

## 安全使用建议

### 1. 在生产环境中始终使用 HTTPS

```php
// 对敏感路由强制 HTTPS
$router->group(['https' => true], function() {
    $router->post('/login', $action);
    $router->post('/register', $action);
});
```

### 2. 限制对管理路由的访问

```php
$router->group([
    'prefix' => '/admin',
    'whitelistIp' => ['192.168.1.0/24'],
    'middleware' => [AuthMiddleware::class, AdminMiddleware::class]
], function() {
    // 管理面板
});
```

### 3. 在公共端点上使用速率限制

```php
// API 端点
$router->get('/api/search', $action)->throttle(30, 1);
$router->post('/api/contact', $action)->throttle(5, 60);
```

### 4. 验证所有输入数据

```php
$router->get('/users/{id}', $action)
    ->where('id', '[0-9]+'); // 仅数字

$router->get('/posts/{slug}', $action)
    ->where('slug', '[a-z0-9-]+'); // 仅安全字符
```

### 5. 使用中间件进行身份验证

```php
$router->group(['middleware' => [AuthMiddleware::class]], function() {
    // 受保护的路由
});
```

### 6. 定期更新库

```bash
composer update cloud-castle/http-router
```

### 7. 监控可疑活动

```php
$router->registerPlugin(new SecurityLoggerPlugin());
```

### 8. 使用自动封禁

```php
$banManager = new BanManager();
$banManager->enableAutoBan(5); // 5 次失败尝试后封禁
$banManager->setAutoBanDuration(3600); // 封禁 1 小时
```

## 已知限制

### PHP 版本

- 需要 PHP 8.2+
- 不支持较旧的 PHP 版本，可能包含漏洞

### 依赖项

- 定期更新 PSR 依赖项
- 监控安全公告

### 服务器配置

路由器不负责：
- Web 服务器配置（nginx、Apache）
- PHP-FPM 设置
- 防火墙规则
- SSL/TLS 证书

确保您的服务器配置正确。

## 安全检查清单

部署到生产环境前：

- [ ] HTTPS 已启用
- [ ] 速率限制已配置
- [ ] 管理员 IP 过滤
- [ ] 所有参数已验证
- [ ] 身份验证中间件
- [ ] 日志记录已启用
- [ ] 监控已配置
- [ ] 安全更新已应用
- [ ] 密码和令牌在 .env 中
- [ ] 调试模式已禁用
- [ ] 错误报告已配置
- [ ] 备份系统正常工作

## 名人堂

我们感谢以下研究人员负责任地披露漏洞：

*（目前为空 - 您可以成为第一个！）*

## 联系方式

- **安全邮箱：** zorinalexey59292@gmail.com
- **Telegram：** [@CloudCastle85](https://t.me/CloudCastle85)
- **GitHub：** [github.com/zorinalexey/cloud-casstle-http-router](https://github.com/zorinalexey/cloud-casstle-http-router)

---

**感谢您帮助保护 CloudCastle HTTP Router 的安全！**

---

最后更新：2024-12-20
