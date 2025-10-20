# IP过滤

[English](../../en/features/05_IP_FILTERING.md) | [Русский](../../ru/features/05_IP_FILTERING.md) | [Deutsch](../../de/features/05_IP_FILTERING.md) | [Français](../../fr/features/05_IP_FILTERING.md) | [**中文**](05_IP_FILTERING.md)

---

## 📚 文档导航

[README](../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [API_REFERENCE](../API_REFERENCE.md) | [ALL_FEATURES](../ALL_FEATURES.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [PERFORMANCE](../PERFORMANCE_ANALYSIS.md) | [SECURITY](../SECURITY_REPORT.md) | [COMPARISON](../COMPARISON.md) | [FAQ](../FAQ.md)

**详细文档:** [01](01_BASIC_ROUTING.md) | [02](02_ROUTE_PARAMETERS.md) | [03](03_ROUTE_GROUPS.md) | [04](04_RATE_LIMITING.md) | [05](05_IP_FILTERING.md) | [06](06_MIDDLEWARE.md) | [07](07_NAMED_ROUTES.md) | [08](08_TAGS.md) | [09](09_HELPER_FUNCTIONS.md) | [10](10_ROUTE_SHORTCUTS.md) | [11](11_ROUTE_MACROS.md) | [12](12_URL_GENERATION.md) | [13](13_EXPRESSION_LANGUAGE.md) | [14](14_CACHING.md) | [15](15_PLUGINS.md) | [16](16_LOADERS.md) | [17](17_PSR_SUPPORT.md) | [18](18_ACTION_RESOLVER.md) | [19](19_STATISTICS.md) | [20](20_SECURITY.md) | [21](21_EXCEPTIONS.md) | [22](22_CLI_TOOLS.md)

---

**类别:** 安全  
**方法数量:** 4  
**复杂度:** ⭐⭐ 中级

---

## 描述

IP过滤允许基于客户端IP地址控制对路由的访问。支持白名单（仅允许）和黑名单（仅拒绝），包括子网的CIDR表示法。

## 方法

### 1. whitelistIp()

**方法:** `whitelistIp(array $ips): Route`

**描述:** 仅允许来自指定IP地址的访问。

**示例:**

```php
// 单个IP
Route::get('/admin', $action)
    ->whitelistIp(['192.168.1.1']);

// 多个IP
Route::get('/admin', $action)
    ->whitelistIp([
        '192.168.1.1',
        '192.168.1.2',
        '10.0.0.100'
    ]);

// CIDR表示法（子网）
Route::get('/admin', $action)
    ->whitelistIp([
        '192.168.1.0/24',    // 192.168.1.0 - 192.168.1.255
        '10.0.0.0/8'         // 10.0.0.0 - 10.255.255.255
    ]);

// 办公网络
Route::get('/internal', $action)
    ->whitelistIp(['192.168.0.0/16']);
```

### 2. blacklistIp()

**方法:** `blacklistIp(array $ips): Route`

**描述:** 拒绝来自指定IP地址的访问。

**示例:**

```php
// 阻止特定IP
Route::get('/public', $action)
    ->blacklistIp(['1.2.3.4', '5.6.7.8']);

// CIDR
Route::get('/api/data', $action)
    ->blacklistIp(['1.2.3.0/24']);

// 从数据库
$bannedIps = DB::table('banned_ips')->pluck('ip')->toArray();
Route::get('/api/data', $action)
    ->blacklistIp($bannedIps);
```

### 3. CIDR支持

**格式:** `IP/MASK`

**示例:**

```php
// /32 - 单个IP
Route::get('/test', $action)->whitelistIp(['192.168.1.1/32']);

// /24 - 子网256个地址
Route::get('/test', $action)->whitelistIp(['192.168.1.0/24']);

// /16 - 65,536个地址
Route::get('/test', $action)->whitelistIp(['192.168.0.0/16']);

// /8 - 16,777,216个地址
Route::get('/test', $action)->whitelistIp(['10.0.0.0/8']);
```

### 4. IP欺骗保护

**描述:** 自动验证X-Forwarded-For和其他头部。

CloudCastle HTTP Router自动:
- 检查 `X-Forwarded-For`
- 检查 `X-Real-IP`
- 防止IP欺骗

## 完整示例

### 管理面板

```php
Route::group([
    'prefix' => '/admin',
    'middleware' => [AuthMiddleware::class, AdminMiddleware::class],
    'https' => true,
    'whitelistIp' => ['192.168.1.0/24']  // 仅办公室
], function() {
    Route::get('/dashboard', [AdminController::class, 'dashboard']);
    Route::get('/users', [AdminController::class, 'users']);
    
    // 关键端点 - 更严格的保护
    Route::post('/settings/critical', [AdminController::class, 'critical'])
        ->whitelistIp(['192.168.1.100']);  // 仅一个IP
});
```

### 内部API

```php
Route::group([
    'prefix' => '/api/internal',
    'whitelistIp' => [
        '10.0.1.100',  // 应用服务器1
        '10.0.1.101',  // 应用服务器2
        '10.0.1.102'   // 应用服务器3
    ]
], function() {
    Route::post('/webhook', [WebhookController::class, 'handle']);
    Route::post('/sync', [SyncController::class, 'sync']);
});
```

### 带黑名单的公共API

```php
// 被阻止的IP范围
$blockedRanges = [
    '1.2.3.0/24',    // 已知机器人网络
    '5.6.7.0/24',    // 垃圾邮件来源
    '123.45.67.89'   // 滥用IP
];

Route::group([
    'prefix' => '/api/public',
    'blacklistIp' => $blockedRanges
], function() {
    Route::get('/data', [ApiController::class, 'data']);
    Route::get('/stats', [ApiController::class, 'stats']);
});
```

## 最佳实践

### 1. 敏感路由使用白名单

```php
// 始终对管理/内部路由使用白名单
Route::group(['prefix' => '/admin'], function() {
    // 所有管理路由
})->whitelistIp(['192.168.1.0/24']);
```

### 2. 基于环境的配置

```php
$allowedIps = config('app.admin_ips', ['127.0.0.1']);

Route::group([
    'prefix' => '/admin',
    'whitelistIp' => $allowedIps
], function() {
    // 管理路由
});
```

### 3. 与其他安全结合

```php
Route::group([
    'prefix' => '/admin',
    'middleware' => [AuthMiddleware::class, AdminMiddleware::class],
    'whitelistIp' => ['192.168.1.0/24'],
    'throttle' => [100, 1],
    'https' => true
], function() {
    // 多层安全
});
```

## 故障排除

### 常见问题

1. **尽管IP正确仍被拒绝访问**
   - 检查是否在代理/负载均衡器后面
   - 验证X-Forwarded-For头部
   - 检查CIDR表示法

2. **CIDR不工作**
   - 验证表示法格式
   - 检查子网计算
   - 首先用单个IP测试

3. **代理/负载均衡器**
   - 配置受信任的代理
   - 检查X-Forwarded-For处理
   - 验证IP检测

### 调试提示

```php
// 记录实际IP
Route::get('/debug-ip', function() {
    return [
        'REMOTE_ADDR' => $_SERVER['REMOTE_ADDR'],
        'X-Forwarded-For' => $_SERVER['HTTP_X_FORWARDED_FOR'] ?? null,
        'X-Real-IP' => $_SERVER['HTTP_X_REAL_IP'] ?? null
    ];
});
```

## 另请参阅

- [速率限制](04_RATE_LIMITING.md) - 速率限制和自动封禁
- [安全](20_SECURITY.md) - 安全功能概述
- [中间件](06_MIDDLEWARE.md) - 请求处理中间件
- [API参考](../API_REFERENCE.md) - 完整API参考

---

© 2024 CloudCastle HTTP Router  
[⬆ 返回顶部](#ip过滤)