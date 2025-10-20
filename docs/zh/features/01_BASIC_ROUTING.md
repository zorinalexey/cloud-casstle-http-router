# 基本路由

[English](../../en/features/01_BASIC_ROUTING.md) | [Русский](../../ru/features/01_BASIC_ROUTING.md) | [Deutsch](../../de/features/01_BASIC_ROUTING.md) | [Français](../../fr/features/01_BASIC_ROUTING.md) | [**中文**](01_BASIC_ROUTING.md)

---

## 📚 文档导航

[README](../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [API_REFERENCE](../API_REFERENCE.md) | [ALL_FEATURES](../ALL_FEATURES.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [PERFORMANCE](../PERFORMANCE_ANALYSIS.md) | [SECURITY](../SECURITY_REPORT.md) | [COMPARISON](../COMPARISON.md) | [FAQ](../FAQ.md)

**详细文档:** [01](01_BASIC_ROUTING.md) | [02](02_ROUTE_PARAMETERS.md) | [03](03_ROUTE_GROUPS.md) | [04](04_RATE_LIMITING.md) | [05](05_IP_FILTERING.md) | [06](06_MIDDLEWARE.md) | [07](07_NAMED_ROUTES.md) | [08](08_TAGS.md) | [09](09_HELPER_FUNCTIONS.md) | [10](10_ROUTE_SHORTCUTS.md) | [11](11_ROUTE_MACROS.md) | [12](12_URL_GENERATION.md) | [13](13_EXPRESSION_LANGUAGE.md) | [14](14_CACHING.md) | [15](15_PLUGINS.md) | [16](16_LOADERS.md) | [17](17_PSR_SUPPORT.md) | [18](18_ACTION_RESOLVER.md) | [19](19_STATISTICS.md) | [20](20_SECURITY.md) | [21](21_EXCEPTIONS.md) | [22](22_CLI_TOOLS.md)

---

**类别:** 核心功能  
**方法数量:** 13  
**复杂度:** ⭐ 初学者级别

---

## 描述

基本路由是CloudCastle HTTP Router的基本功能，允许为各种HTTP方法和URI注册处理器。

## 功能

### 1. GET路由

**方法:** `Route::get(string $uri, mixed $action): Route`

**描述:** 为HTTP GET请求注册路由。

**参数:**
- `$uri` - 路由URI（例如：`/users`，`/posts/{id}`）
- `$action` - 动作（闭包、数组、控制器字符串）

**返回:** `Route`对象用于方法链

**示例:**

```php
use CloudCastle\Http\Router\Facade\Route;

// 简单的闭包路由
Route::get('/users', function() {
    return '用户列表';
});

// 使用控制器（数组）
Route::get('/users', [UserController::class, 'index']);

// 使用控制器（字符串）
Route::get('/users', 'UserController@index');

// 带参数
Route::get('/users/{id}', function($id) {
    return "用户ID: $id";
});

// 方法链
Route::get('/api/users', [UserController::class, 'index'])
    ->name('api.users')
    ->middleware([AuthMiddleware::class])
    ->throttle(100, 1);
```

**用途:**
- 数据检索（列表、详情）
- 页面显示
- 读取API端点

---

### 2. POST路由

**方法:** `Route::post(string $uri, mixed $action): Route`

**描述:** 为HTTP POST请求注册路由。

**参数:**
- `$uri` - 路由URI
- `$action` - 动作

**返回:** `Route`对象

**示例:**

```php
// 资源创建
Route::post('/users', function() {
    $data = $_POST;
    // 创建用户
    return '用户已创建';
});

// 使用控制器
Route::post('/users', [UserController::class, 'store']);

// 带验证和速率限制
Route::post('/users', [UserController::class, 'store'])
    ->middleware([ValidateUser::class])
    ->throttle(20, 1);  // 每分钟20个请求
```

**用途:**
- 创建新资源
- 表单提交
- API数据创建

---

### 3. PUT路由

**方法:** `Route::put(string $uri, mixed $action): Route`

**描述:** 为HTTP PUT请求注册路由（完整资源更新）。

**参数:**
- `$uri` - 路由URI（通常带ID参数）
- `$action` - 动作

**返回:** `Route`对象

**示例:**

```php
// 完整资源更新
Route::put('/users/{id}', function($id) {
    $data = json_decode(file_get_contents('php://input'), true);
    // 完整用户更新
    return "用户 $id 已更新";
});

// 使用控制器
Route::put('/users/{id}', [UserController::class, 'update'])
    ->where('id', '[0-9]+');

// RESTful API
Route::put('/api/v1/users/{id}', [ApiUserController::class, 'update'])
    ->middleware([AuthMiddleware::class])
    ->name('api.v1.users.update');
```

**用途:**
- 完整资源更新
- 完整数据替换
- RESTful API更新

---

### 4. PATCH路由

**方法:** `Route::patch(string $uri, mixed $action): Route`

**描述:** 为HTTP PATCH请求注册路由（部分资源更新）。

**参数:**
- `$uri` - 路由URI
- `$action` - 动作

**返回:** `Route`对象

**示例:**

```php
// 部分资源更新
Route::patch('/users/{id}', function($id) {
    $data = json_decode(file_get_contents('php://input'), true);
    // 部分用户更新
    return "用户 $id 部分更新";
});

// 使用控制器
Route::patch('/users/{id}', [UserController::class, 'patch'])
    ->where('id', '[0-9]+');
```

**用途:**
- 部分资源更新
- 字段特定修改
- 高效更新

---

### 5. DELETE路由

**方法:** `Route::delete(string $uri, mixed $action): Route`

**描述:** 为HTTP DELETE请求注册路由。

**参数:**
- `$uri` - 路由URI
- `$action` - 动作

**返回:** `Route`对象

**示例:**

```php
// 资源删除
Route::delete('/users/{id}', function($id) {
    // 删除用户
    return "用户 $id 已删除";
});

// 使用控制器
Route::delete('/users/{id}', [UserController::class, 'destroy'])
    ->where('id', '[0-9]+');
```

**用途:**
- 资源删除
- 数据移除
- 清理操作

---

### 6. VIEW路由

**方法:** `Route::view(string $uri, mixed $action): Route`

**描述:** 为自定义VIEW方法注册路由。

**参数:**
- `$uri` - 路由URI
- `$action` - 动作

**返回:** `Route`对象

**示例:**

```php
// 自定义VIEW方法
Route::view('/page', function() {
    return '页面内容';
});

// 使用控制器
Route::view('/page', [PageController::class, 'show']);
```

**用途:**
- 自定义HTTP方法
- 专门操作
- 非标准端点

---

### 7. 自定义路由

**方法:** `Route::custom(string $method, string $uri, mixed $action): Route`

**描述:** 为任何自定义HTTP方法注册路由。

**参数:**
- `$method` - HTTP方法名
- `$uri` - 路由URI
- `$action` - 动作

**返回:** `Route`对象

**示例:**

```php
// 自定义PURGE方法
Route::custom('PURGE', '/cache', function() {
    // 清除缓存
    return '缓存已清除';
});

// 自定义OPTIONS方法
Route::custom('OPTIONS', '/api', function() {
    return 'CORS预检';
});
```

**用途:**
- 自定义HTTP方法
- 专门协议
- 非标准操作

---

### 8. 匹配路由

**方法:** `Route::match(array $methods, string $uri, mixed $action): Route`

**描述:** 为多个HTTP方法注册路由。

**参数:**
- `$methods` - HTTP方法数组
- `$uri` - 路由URI
- `$action` - 动作

**返回:** `Route`对象

**示例:**

```php
// 多个方法
Route::match(['GET', 'POST'], '/form', function() {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        return '显示表单';
    }
    return '处理表单';
});

// 使用控制器
Route::match(['PUT', 'PATCH'], '/users/{id}', [UserController::class, 'update']);
```

**用途:**
- 多方法处理
- 表单处理
- 灵活端点

---

### 9. 任意路由

**方法:** `Route::any(string $uri, mixed $action): Route`

**描述:** 为所有HTTP方法注册路由。

**参数:**
- `$uri` - 路由URI
- `$action` - 动作

**返回:** `Route`对象

**示例:**

```php
// 所有方法
Route::any('/endpoint', function() {
    $method = $_SERVER['REQUEST_METHOD'];
    return "处理 $method 请求";
});

// 使用控制器
Route::any('/api/endpoint', [ApiController::class, 'handle']);
```

**用途:**
- 通用端点
- 方法无关处理
- 灵活API

---

### 10. 路由器实例

**方法:** `Router::getInstance(): Router`

**描述:** 获取单例路由器实例。

**返回:** `Router`实例

**示例:**

```php
use CloudCastle\Http\Router\Router;

$router = Router::getInstance();
$router->get('/users', $action);
$router->post('/users', $action);
```

**用途:**
- 直接路由器访问
- 单例模式
- 程序控制

---

### 11. Facade API

**描述:** 路由注册的静态接口。

**示例:**

```php
use CloudCastle\Http\Router\Facade\Route;

Route::get('/users', $action);
Route::post('/users', $action);
Route::put('/users/{id}', $action);
Route::patch('/users/{id}', $action);
Route::delete('/users/{id}', $action);
```

**用途:**
- 简洁语法
- 静态访问
- 方法链

---

### 12. 路由注册

**描述:** 在应用程序中注册路由。

**示例:**

```php
// 在routes/web.php中
Route::get('/', function() {
    return '欢迎';
});

Route::get('/about', function() {
    return '关于页面';
});

Route::get('/contact', function() {
    return '联系页面';
});
```

**用途:**
- 应用程序设置
- 路由定义
- 配置

---

### 13. 路由调度

**描述:** 将请求调度到注册的路由。

**示例:**

```php
use CloudCastle\Http\Router\Facade\Route;

// 注册路由
Route::get('/users', $action);

// 调度请求
$route = Route::dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
if ($route) {
    echo $route->run();
}
```

**用途:**
- 请求处理
- 路由匹配
- 响应生成

---

## 最佳实践

### 1. 路由组织

```php
// 分组相关路由
Route::group(['prefix' => 'api/v1'], function() {
    Route::get('/users', [UserController::class, 'index']);
    Route::post('/users', [UserController::class, 'store']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);
});
```

### 2. 方法链

```php
Route::get('/api/users', [UserController::class, 'index'])
    ->name('api.users.index')
    ->middleware([AuthMiddleware::class])
    ->throttle(100, 1)
    ->tag('api');
```

### 3. 参数验证

```php
Route::get('/users/{id}', [UserController::class, 'show'])
    ->where('id', '[0-9]+');
```

### 4. 安全考虑

```php
Route::post('/users', [UserController::class, 'store'])
    ->middleware([ValidateUser::class])
    ->throttle(20, 1)
    ->whitelistIp(['192.168.1.0/24']);
```

---

## 常见模式

### 1. RESTful路由

```php
Route::get('/users', [UserController::class, 'index']);      // 列表
Route::post('/users', [UserController::class, 'store']);   // 创建
Route::get('/users/{id}', [UserController::class, 'show']); // 显示
Route::put('/users/{id}', [UserController::class, 'update']); // 更新
Route::delete('/users/{id}', [UserController::class, 'destroy']); // 删除
```

### 2. API路由

```php
Route::group(['prefix' => 'api/v1', 'middleware' => 'auth'], function() {
    Route::apiResource('users', UserController::class);
    Route::apiResource('posts', PostController::class);
});
```

### 3. Web路由

```php
Route::group(['middleware' => 'web'], function() {
    Route::get('/', [HomeController::class, 'index']);
    Route::get('/about', [PageController::class, 'about']);
    Route::get('/contact', [PageController::class, 'contact']);
});
```

---

## 性能提示

### 1. 路由缓存

```php
$router = Router::getInstance();
$router->enableCache('cache/routes.php');
$router->compile();
```

### 2. 高效匹配

```php
// 更具体的路由在前
Route::get('/users/{id}/posts/{post}', $action);
Route::get('/users/{id}', $action);
Route::get('/users', $action);
```

### 3. 参数约束

```php
Route::get('/users/{id}', $action)->where('id', '[0-9]+');
```

---

## 故障排除

### 常见问题

1. **找不到路由**
   - 检查URI模式
   - 验证HTTP方法
   - 检查路由注册顺序

2. **参数未传递**
   - 验证URI中的参数名
   - 检查参数约束
   - 确保正确的动作签名

3. **方法链问题**
   - 检查返回类型
   - 验证方法可用性
   - 检查方法顺序

### 调试提示

```php
// 启用调试模式
Route::enableDebug();

// 获取所有注册的路由
$routes = Route::getAllRoutes();

// 检查路由匹配
$route = Route::match('/users/123', 'GET');
```

---

## 另请参阅

- [路由参数](02_ROUTE_PARAMETERS.md) - 动态路由参数
- [路由组](03_ROUTE_GROUPS.md) - 路由组织
- [中间件](06_MIDDLEWARE.md) - 请求处理
- [命名路由](07_NAMED_ROUTES.md) - 路由标识
- [API参考](../API_REFERENCE.md) - 完整API参考

---

© 2024 CloudCastle HTTP Router  
[⬆ 返回顶部](#基本路由)