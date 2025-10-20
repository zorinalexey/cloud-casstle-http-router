# 速率限制和自动封禁

[English](../../en/features/04_RATE_LIMITING.md) | [Русский](../../ru/features/04_RATE_LIMITING.md) | [Deutsch](../../de/features/04_RATE_LIMITING.md) | [Français](../../fr/features/04_RATE_LIMITING.md) | [**中文**](04_RATE_LIMITING.md)

---

## 📚 文档导航

[README](../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [API_REFERENCE](../API_REFERENCE.md) | [ALL_FEATURES](../ALL_FEATURES.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [PERFORMANCE](../PERFORMANCE_ANALYSIS.md) | [SECURITY](../SECURITY_REPORT.md) | [COMPARISON](../COMPARISON.md) | [FAQ](../FAQ.md)

**详细文档:** [01](01_BASIC_ROUTING.md) | [02](02_ROUTE_PARAMETERS.md) | [03](03_ROUTE_GROUPS.md) | [04](04_RATE_LIMITING.md) | [05](05_IP_FILTERING.md) | [06](06_MIDDLEWARE.md) | [07](07_NAMED_ROUTES.md) | [08](08_TAGS.md) | [09](09_HELPER_FUNCTIONS.md) | [10](10_ROUTE_SHORTCUTS.md) | [11](11_ROUTE_MACROS.md) | [12](12_URL_GENERATION.md) | [13](13_EXPRESSION_LANGUAGE.md) | [14](14_CACHING.md) | [15](15_PLUGINS.md) | [16](16_LOADERS.md) | [17](17_PSR_SUPPORT.md) | [18](18_ACTION_RESOLVER.md) | [19](19_STATISTICS.md) | [20](20_SECURITY.md) | [21](21_EXCEPTIONS.md) | [22](22_CLI_TOOLS.md)

---

**类别:** 安全  
**方法数量:** 15  
**复杂度:** ⭐⭐⭐ 高级

---

## 描述

速率限制和自动封禁是强大的内置机制，用于防护DDoS攻击、暴力破解和API滥用。

## 功能

### 速率限制 (8种方法)

#### 1. 基本节流

**方法:** `throttle(int $maxAttempts, int $decayMinutes, ?callable $keyResolver = null): Route`

**描述:** 限制对路由的请求数量。

**参数:**
- `$maxAttempts` - 最大请求数量
- `$decayMinutes` - 时间周期（分钟）
- `$keyResolver` - 可选函数用于确定键（默认IP）

**示例:**

```php
// 每分钟60个请求
Route::post('/api/submit', $action)
    ->throttle(60, 1);

// 每小时100个请求
Route::post('/api/upload', $action)
    ->throttle(100, 60);

// 每天1000个请求
Route::get('/api/public', $action)
    ->throttle(1000, 1440);

// 使用控制器
Route::post('/login', [AuthController::class, 'login'])
    ->throttle(5, 1);  // 每分钟5次登录尝试
```

**工作原理:**
1. 每次请求时，IP（或自定义键）的计数器递增
2. 如果计数器超过限制 - 抛出 `TooManyRequestsException`
3. 指定时间后，计数器重置

---

#### 2. TimeUnit枚举

**枚举:** `CloudCastle\Http\Router\TimeUnit`

**描述:** 用于方便处理时间单位的枚举。

**值:**
```php
TimeUnit::SECOND->value  // 1/60分钟
TimeUnit::MINUTE->value  // 1分钟
TimeUnit::HOUR->value    // 60分钟
TimeUnit::DAY->value     // 1440分钟
TimeUnit::WEEK->value    // 10080分钟
TimeUnit::MONTH->value   // 43200分钟
```

**示例:**

```php
use CloudCastle\Http\Router\TimeUnit;

// 每秒5个请求
Route::post('/api/realtime', $action)
    ->throttle(5, TimeUnit::SECOND->value);

// 每小时100个请求
Route::get('/api/data', $action)
    ->throttle(100, TimeUnit::HOUR->value);

// 每天1000个请求
Route::get('/api/public', $action)
    ->throttle(1000, TimeUnit::DAY->value);
```

---

#### 3. 自定义键解析器

**方法:** `throttle(int $maxAttempts, int $decayMinutes, callable $keyResolver): Route`

**描述:** 使用自定义函数确定节流键。

**示例:**

```php
// 按用户ID
Route::post('/api/user-action', $action)
    ->throttle(10, 1, function($request) {
        return 'user:' . $request->user()->id;
    });

// 按API密钥
Route::post('/api/external', $action)
    ->throttle(100, 1, function($request) {
        return 'api:' . $request->header('X-API-Key');
    });

// 按组合
Route::post('/api/complex', $action)
    ->throttle(50, 1, function($request) {
        $user = $request->user();
        $ip = $request->ip();
        return "user:{$user->id}:ip:{$ip}";
    });
```

---

#### 4. 组节流

**方法:** `throttle(array $throttle): RouteGroup`

**描述:** 将节流应用到组中的所有路由。

**示例:**

```php
// 带节流的API组
Route::group(['throttle' => [100, 1]], function() {
    Route::get('/api/users', $action);
    Route::get('/api/posts', $action);
});

// 不同组的不同限制
Route::group(['throttle' => [60, 1]], function() {
    Route::get('/api/public', $action);  // 每分钟60个请求
});

Route::group(['throttle' => [1000, 1]], function() {
    Route::get('/api/premium', $action); // 每分钟1000个请求
});
```

---

#### 5. 动态节流

**方法:** `throttle(callable $throttleResolver): Route`

**描述:** 基于请求数据的动态节流。

**示例:**

```php
// 基于用户角色的动态
Route::post('/api/action', $action)
    ->throttle(function($request) {
        $user = $request->user();
        if ($user->isPremium()) {
            return [1000, 1]; // 每分钟1000个请求
        }
        return [100, 1]; // 每分钟100个请求
    });

// 基于请求大小的动态
Route::post('/api/upload', $action)
    ->throttle(function($request) {
        $size = $request->header('Content-Length');
        if ($size > 1000000) { // > 1MB
            return [10, 1]; // 每分钟10个请求
        }
        return [100, 1]; // 每分钟100个请求
    });
```

---

#### 6. 带条件的节流

**方法:** `throttle(int $maxAttempts, int $decayMinutes, ?callable $keyResolver = null, ?callable $condition = null): Route`

**描述:** 带附加条件的节流。

**示例:**

```php
// 仅对POST请求节流
Route::match(['GET', 'POST'], '/api/data', $action)
    ->throttle(100, 1, null, function($request) {
        return $request->isMethod('POST');
    });

// 仅对特定IP节流
Route::post('/api/sensitive', $action)
    ->throttle(5, 1, null, function($request) {
        $ip = $request->ip();
        return in_array($ip, ['192.168.1.100', '10.0.0.50']);
    });
```

---

#### 7. 节流统计

**方法:** `getThrottleStats(): array`

**描述:** 获取节流统计信息。

**示例:**

```php
// 获取节流统计
$stats = Route::getThrottleStats();

// 示例输出:
[
    'total_requests' => 1500,
    'blocked_requests' => 25,
    'active_throttles' => 3,
    'top_ips' => [
        '192.168.1.100' => 150,
        '10.0.0.50' => 120
    ]
]
```

---

#### 8. 节流管理

**方法:**
- `clearThrottle(string $key): void` - 清除特定节流
- `clearAllThrottles(): void` - 清除所有节流
- `getThrottleKey(string $ip): string` - 获取IP的节流键

**示例:**

```php
// 清除特定IP的节流
Route::clearThrottle('192.168.1.100');

// 清除所有节流
Route::clearAllThrottles();

// 获取节流键
$key = Route::getThrottleKey('192.168.1.100');
```

---

### 自动封禁系统 (7种方法)

#### 1. 基本自动封禁

**方法:** `autoBan(int $maxAttempts, int $banMinutes, ?callable $keyResolver = null): Route`

**描述:** 超过尝试次数后自动封禁IP。

**参数:**
- `$maxAttempts` - 封禁前的最大尝试次数
- `$banMinutes` - 封禁持续时间（分钟）
- `$keyResolver` - 可选函数用于确定键

**示例:**

```php
// 10次失败尝试后封禁1小时
Route::post('/login', [AuthController::class, 'login'])
    ->autoBan(10, 60);

// 5次失败尝试后封禁30分钟
Route::post('/api/sensitive', $action)
    ->autoBan(5, 30);

// 20次失败尝试后封禁24小时
Route::post('/api/admin', $action)
    ->autoBan(20, 1440);
```

---

#### 2. 渐进式自动封禁

**方法:** `progressiveAutoBan(array $levels): Route`

**描述:** 持续时间递增的渐进式封禁。

**示例:**

```php
// 渐进式封禁级别
Route::post('/login', $action)
    ->progressiveAutoBan([
        5 => 5,    // 5次尝试 -> 5分钟封禁
        10 => 30,  // 10次尝试 -> 30分钟封禁
        20 => 120, // 20次尝试 -> 2小时封禁
        50 => 1440 // 50次尝试 -> 24小时封禁
    ]);
```

---

#### 3. 带条件的自动封禁

**方法:** `autoBan(int $maxAttempts, int $banMinutes, ?callable $keyResolver = null, ?callable $condition = null): Route`

**描述:** 带附加条件的自动封禁。

**示例:**

```php
// 仅对失败的登录尝试封禁
Route::post('/login', $action)
    ->autoBan(10, 60, null, function($request, $response) {
        return $response->getStatusCode() === 401;
    });

// 仅对特定用户代理封禁
Route::post('/api/action', $action)
    ->autoBan(5, 30, null, function($request) {
        $userAgent = $request->header('User-Agent');
        return strpos($userAgent, 'bot') !== false;
    });
```

---

#### 4. 封禁管理

**方法:**
- `banIp(string $ip, int $minutes): void` - 手动封禁IP
- `unbanIp(string $ip): void` - 解封IP
- `isBanned(string $ip): bool` - 检查IP是否被封禁
- `getBanInfo(string $ip): ?array` - 获取封禁信息

**示例:**

```php
// 手动封禁IP 1小时
Route::banIp('192.168.1.100', 60);

// 解封IP
Route::unbanIp('192.168.1.100');

// 检查IP是否被封禁
if (Route::isBanned('192.168.1.100')) {
    return response('IP已被封禁', 403);
}

// 获取封禁信息
$banInfo = Route::getBanInfo('192.168.1.100');
if ($banInfo) {
    echo "封禁至: " . date('Y-m-d H:i:s', $banInfo['expires_at']);
}
```

---

#### 5. 封禁统计

**方法:** `getBanStats(): array`

**描述:** 获取封禁统计信息。

**示例:**

```php
// 获取封禁统计
$stats = Route::getBanStats();

// 示例输出:
[
    'total_bans' => 150,
    'active_bans' => 25,
    'bans_today' => 10,
    'top_banned_ips' => [
        '192.168.1.100' => 5,
        '10.0.0.50' => 3
    ]
]
```

---

#### 6. 封禁清理

**方法:** `cleanupExpiredBans(): int`

**描述:** 清理过期的封禁。

**示例:**

```php
// 清理过期封禁
$cleaned = Route::cleanupExpiredBans();
echo "清理了 $cleaned 个过期封禁";

// 计划清理（在cron作业中）
Route::cleanupExpiredBans();
```

---

#### 7. 封禁白名单

**方法:** `whitelistBanIp(string $ip): void`

**描述:** 将IP加入自动封禁白名单。

**示例:**

```php
// 将受信任的IP加入白名单
Route::whitelistBanIp('192.168.1.0/24');
Route::whitelistBanIp('10.0.0.0/8');

// 将特定IP加入白名单
Route::whitelistBanIp('192.168.1.100');
Route::whitelistBanIp('10.0.0.50');
```

---

## 最佳实践

### 1. 适当的限制

```php
// 登录尝试 - 严格限制
Route::post('/login', $action)
    ->throttle(5, 1)
    ->autoBan(10, 60);

// API端点 - 中等限制
Route::post('/api/data', $action)
    ->throttle(100, 1);

// 公共端点 - 宽松限制
Route::get('/api/public', $action)
    ->throttle(1000, 1);
```

### 2. 用户特定限制

```php
// 不同用户类型的不同限制
Route::post('/api/action', $action)
    ->throttle(function($request) {
        $user = $request->user();
        if ($user->isPremium()) {
            return [1000, 1];
        }
        return [100, 1];
    });
```

### 3. 监控

```php
// 监控节流和封禁统计
$throttleStats = Route::getThrottleStats();
$banStats = Route::getBanStats();

// 记录可疑活动
if ($throttleStats['blocked_requests'] > 100) {
    Log::warning('大量被阻止的请求', $throttleStats);
}
```

---

## 常见模式

### 1. API保护

```php
Route::group(['prefix' => '/api'], function() {
    Route::post('/login', [AuthController::class, 'login'])
        ->throttle(5, 1)
        ->autoBan(10, 60);
    
    Route::post('/register', [AuthController::class, 'register'])
        ->throttle(3, 1)
        ->autoBan(5, 30);
    
    Route::get('/data', [DataController::class, 'index'])
        ->throttle(100, 1);
});
```

### 2. 管理保护

```php
Route::group(['prefix' => '/admin'], function() {
    Route::post('/login', [AdminController::class, 'login'])
        ->throttle(3, 1)
        ->autoBan(5, 120);
    
    Route::post('/sensitive-action', $action)
        ->throttle(10, 1)
        ->autoBan(15, 60);
});
```

### 3. 公共API

```php
Route::group(['prefix' => '/api/public'], function() {
    Route::get('/health', $action)
        ->throttle(1000, 1);
    
    Route::get('/data', $action)
        ->throttle(100, 1);
    
    Route::post('/contact', $action)
        ->throttle(10, 1)
        ->autoBan(20, 30);
});
```

---

## 性能提示

### 1. 高效存储

```php
// 使用Redis获得更好性能
Route::setThrottleStorage(new RedisStorage());

// 使用文件存储进行简单设置
Route::setThrottleStorage(new FileStorage('/tmp/throttle'));
```

### 2. 清理策略

```php
// 定期清理
Route::cleanupExpiredBans();
Route::cleanupExpiredThrottles();

// 在cron中计划清理
// 0 * * * * php artisan route:cleanup
```

---

## 故障排除

### 常见问题

1. **节流不工作**
   - 检查节流配置
   - 验证存储是否工作
   - 检查IP检测

2. **自动封禁过于激进**
   - 调整封禁阈值
   - 为受信任的IP添加白名单
   - 监控封禁统计

3. **性能问题**
   - 使用Redis存储
   - 实施清理策略
   - 监控资源使用

### 调试提示

```php
// 启用调试模式
Route::enableDebug();

// 检查节流统计
$stats = Route::getThrottleStats();
var_dump($stats);

// 检查封禁统计
$banStats = Route::getBanStats();
var_dump($banStats);
```

---

## 另请参阅

- [IP过滤](05_IP_FILTERING.md) - 基于IP的访问控制
- [中间件](06_MIDDLEWARE.md) - 请求处理中间件
- [安全](20_SECURITY.md) - 安全功能概述
- [API参考](../API_REFERENCE.md) - 完整API参考

---

© 2024 CloudCastle HTTP Router  
[⬆ 返回顶部](#速率限制和自动封禁)