# 路由参数

[English](../../en/features/02_ROUTE_PARAMETERS.md) | [Русский](../../ru/features/02_ROUTE_PARAMETERS.md) | [Deutsch](../../de/features/02_ROUTE_PARAMETERS.md) | [Français](../../fr/features/02_ROUTE_PARAMETERS.md) | [**中文**](02_ROUTE_PARAMETERS.md)

---

## 📚 文档导航

[README](../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [API_REFERENCE](../API_REFERENCE.md) | [ALL_FEATURES](../ALL_FEATURES.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [PERFORMANCE](../PERFORMANCE_ANALYSIS.md) | [SECURITY](../SECURITY_REPORT.md) | [COMPARISON](../COMPARISON.md) | [FAQ](../FAQ.md)

**详细文档:** [01](01_BASIC_ROUTING.md) | [02](02_ROUTE_PARAMETERS.md) | [03](03_ROUTE_GROUPS.md) | [04](04_RATE_LIMITING.md) | [05](05_IP_FILTERING.md) | [06](06_MIDDLEWARE.md) | [07](07_NAMED_ROUTES.md) | [08](08_TAGS.md) | [09](09_HELPER_FUNCTIONS.md) | [10](10_ROUTE_SHORTCUTS.md) | [11](11_ROUTE_MACROS.md) | [12](12_URL_GENERATION.md) | [13](13_EXPRESSION_LANGUAGE.md) | [14](14_CACHING.md) | [15](15_PLUGINS.md) | [16](16_LOADERS.md) | [17](17_PSR_SUPPORT.md) | [18](18_ACTION_RESOLVER.md) | [19](19_STATISTICS.md) | [20](20_SECURITY.md) | [21](21_EXCEPTIONS.md) | [22](22_CLI_TOOLS.md)

---

**类别:** 核心功能  
**方法数量:** 6  
**复杂度:** ⭐⭐ 中级

---

## 描述

路由参数允许创建带有可变部分的动态URI，验证它们并设置默认值。

## 功能

### 1. 基本参数

**语法:** `{parameter}`

**描述:** 将URI的动态部分定义为参数。

**示例:**

```php
// 单个参数
Route::get('/users/{id}', function($id) {
    return "用户ID: $id";
});

// 调度: /users/123 → $id = '123'


// 多个参数
Route::get('/posts/{year}/{month}/{slug}', function($year, $month, $slug) {
    return "文章: $year/$month/$slug";
});

// 调度: /posts/2024/10/hello-world
// → $year = '2024', $month = '10', $slug = 'hello-world'


// 使用控制器
Route::get('/users/{id}/posts/{postId}', [PostController::class, 'show']);
// 在控制器中:
// public function show($id, $postId) { ... }


// 从Route对象获取参数
Route::get('/api/{version}/users/{id}', function($version, $id) {
    $route = Route::current();
    $params = $route->getParameters();
    // ['version' => 'v1', 'id' => '123']
    
    return "API $version, 用户 $id";
});
```

**特性:**
- 参数按顺序传递给动作
- 区分大小写
- 可包含字母、数字、下划线
- 自动从URI提取

---

### 2. 参数约束 (where)

**方法:** `where(string|array $parameter, ?string $pattern = null): Route`

**描述:** 添加正则表达式进行参数验证。

**参数:**
- `$parameter` - 参数名或数组 [parameter => pattern]
- `$pattern` - 正则表达式（如果$parameter是字符串）

**示例:**

```php
// 仅数字
Route::get('/users/{id}', $action)
    ->where('id', '[0-9]+');

// 仅字母
Route::get('/users/{name}', $action)
    ->where('name', '[a-zA-Z]+');

// 字母数字加连字符
Route::get('/posts/{slug}', $action)
    ->where('slug', '[a-zA-Z0-9-]+');

// 多个约束
Route::get('/posts/{year}/{month}', $action)
    ->where([
        'year' => '[0-9]{4}',
        'month' => '0[1-9]|1[0-2]'
    ]);

// 复杂模式
Route::get('/files/{filename}', $action)
    ->where('filename', '[a-zA-Z0-9._-]+\.(jpg|png|gif)');
```

**常见模式:**
- `[0-9]+` - 仅数字
- `[a-zA-Z]+` - 仅字母
- `[a-zA-Z0-9]+` - 字母数字
- `[a-zA-Z0-9-]+` - 字母数字加连字符
- `[0-9]{4}` - 恰好4位数字
- `[0-9]{1,3}` - 1到3位数字

---

### 3. 内联约束

**语法:** `{parameter:pattern}`

**描述:** 直接在URI模式中定义约束。

**示例:**

```php
// 内联数字约束
Route::get('/users/{id:[0-9]+}', function($id) {
    return "用户ID: $id";
});

// 内联字母数字约束
Route::get('/posts/{slug:[a-zA-Z0-9-]+}', function($slug) {
    return "文章别名: $slug";
});

// 多个内联约束
Route::get('/posts/{year:[0-9]{4}}/{month:[0-9]{2}}/{slug:[a-zA-Z0-9-]+}', function($year, $month, $slug) {
    return "文章: $year/$month/$slug";
});

// 复杂内联模式
Route::get('/files/{filename:[a-zA-Z0-9._-]+\.(jpg|png|gif)}', function($filename) {
    return "文件: $filename";
});
```

**优势:**
- 更简洁的语法
- 约束在URI中可见
- 更好的可读性
- 更快的匹配

---

### 4. 可选参数

**语法:** `{parameter?}`

**描述:** 使参数可选并设置默认值。

**示例:**

```php
// 可选参数
Route::get('/users/{id?}', function($id = null) {
    if ($id) {
        return "用户ID: $id";
    }
    return "所有用户";
});

// 调度: /users → $id = null
// 调度: /users/123 → $id = '123'


// 带默认值的可选参数
Route::get('/posts/{page?}', function($page = 1) {
    return "页面: $page";
});

// 调度: /posts → $page = 1
// 调度: /posts/5 → $page = '5'


// 多个可选参数
Route::get('/search/{query?}/{page?}', function($query = '', $page = 1) {
    return "搜索: '$query', 页面: $page";
});

// 调度: /search → $query = '', $page = 1
// 调度: /search/php → $query = 'php', $page = 1
// 调度: /search/php/2 → $query = 'php', $page = '2'
```

**规则:**
- 可选参数必须在必需参数之后
- 默认值在动作签名中设置
- 可以有多个可选参数

---

### 5. 默认值

**方法:** `defaults(array $defaults): Route`

**描述:** 为参数设置默认值。

**示例:**

```php
// 默认值
Route::get('/posts/{page}', function($page) {
    return "页面: $page";
})->defaults(['page' => 1]);

// 调度: /posts → $page = 1
// 调度: /posts/5 → $page = '5'


// 多个默认值
Route::get('/api/{version}/users/{id}', function($version, $id) {
    return "API $version, 用户 $id";
})->defaults(['version' => 'v1', 'id' => 1]);

// 调度: /api/users → $version = 'v1', $id = 1
// 调度: /api/v2/users/123 → $version = 'v2', $id = '123'


// 带约束和默认值
Route::get('/posts/{year}/{month}', function($year, $month) {
    return "文章 $year/$month";
})
->where(['year' => '[0-9]{4}', 'month' => '[0-9]{2}'])
->defaults(['year' => date('Y'), 'month' => date('m')]);
```

**用例:**
- API版本控制
- 分页默认值
- 当前日期/时间默认值
- 用户特定默认值

---

### 6. 参数访问

**方法:**
- `getParameters(): array` - 获取所有参数
- `getParameter(string $name): mixed` - 获取特定参数
- `hasParameter(string $name): bool` - 检查参数是否存在

**示例:**

```php
Route::get('/users/{id}/posts/{postId}', function($id, $postId) {
    $route = Route::current();
    
    // 获取所有参数
    $params = $route->getParameters();
    // ['id' => '123', 'postId' => '456']
    
    // 获取特定参数
    $userId = $route->getParameter('id');
    $postId = $route->getParameter('postId');
    
    // 检查参数是否存在
    if ($route->hasParameter('id')) {
        return "用户 $userId, 文章 $postId";
    }
    
    return "无用户ID";
});
```

**高级用法:**

```php
Route::get('/api/{version}/users/{id}/posts/{postId}', function($version, $id, $postId) {
    $route = Route::current();
    $params = $route->getParameters();
    
    // 过滤参数
    $filteredParams = array_filter($params, function($value, $key) {
        return !empty($value) && $key !== 'version';
    }, ARRAY_FILTER_USE_BOTH);
    
    // 使用参数进行数据库查询
    $user = User::find($params['id']);
    $post = Post::where('user_id', $params['id'])
                ->where('id', $params['postId'])
                ->first();
    
    return response()->json([
        'user' => $user,
        'post' => $post,
        'api_version' => $params['version']
    ]);
});
```

---

## 最佳实践

### 1. 参数命名

```php
// 好: 描述性名称
Route::get('/users/{userId}', $action);
Route::get('/posts/{postSlug}', $action);
Route::get('/categories/{categoryId}', $action);

// 避免: 通用名称
Route::get('/users/{id}', $action);
Route::get('/posts/{slug}', $action);
Route::get('/categories/{id}', $action);
```

### 2. 约束验证

```php
// 始终验证参数
Route::get('/users/{id}', [UserController::class, 'show'])
    ->where('id', '[0-9]+');

Route::get('/posts/{slug}', [PostController::class, 'show'])
    ->where('slug', '[a-zA-Z0-9-]+');
```

### 3. 默认值

```php
// 设置合理的默认值
Route::get('/posts/{page}', [PostController::class, 'index'])
    ->defaults(['page' => 1])
    ->where('page', '[0-9]+');
```

### 4. 参数顺序

```php
// 必需参数在前
Route::get('/users/{id}/posts/{postId}', $action);

// 可选参数在后
Route::get('/search/{query?}/{page?}', $action);
```

---

## 常见模式

### 1. RESTful资源

```php
Route::get('/users/{id}', [UserController::class, 'show'])
    ->where('id', '[0-9]+');

Route::get('/posts/{slug}', [PostController::class, 'show'])
    ->where('slug', '[a-zA-Z0-9-]+');
```

### 2. API版本控制

```php
Route::get('/api/{version}/users/{id}', [ApiController::class, 'show'])
    ->where(['version' => 'v[0-9]+', 'id' => '[0-9]+'])
    ->defaults(['version' => 'v1']);
```

### 3. 分页

```php
Route::get('/posts/{page}', [PostController::class, 'index'])
    ->where('page', '[0-9]+')
    ->defaults(['page' => 1]);
```

### 4. 文件下载

```php
Route::get('/files/{filename}', [FileController::class, 'download'])
    ->where('filename', '[a-zA-Z0-9._-]+\.(pdf|doc|docx)');
```

---

## 性能提示

### 1. 特定约束优先

```php
// 更具体的路由在前
Route::get('/users/{id:[0-9]+}', $action);
Route::get('/users/{name:[a-zA-Z]+}', $action);
Route::get('/users/{slug:[a-zA-Z0-9-]+}', $action);
```

### 2. 避免复杂模式

```php
// 好: 简单模式
Route::get('/posts/{id:[0-9]+}', $action);

// 避免: 复杂模式
Route::get('/posts/{id:[0-9]{1,10}}', $action);
```

### 3. 使用内联约束

```php
// 更快: 内联约束
Route::get('/users/{id:[0-9]+}', $action);

// 更慢: 单独的where()调用
Route::get('/users/{id}', $action)->where('id', '[0-9]+');
```

---

## 故障排除

### 常见问题

1. **参数未传递**
   - 检查URI中的参数名
   - 验证动作中的参数顺序
   - 检查拼写错误

2. **约束不工作**
   - 验证正则表达式模式
   - 检查参数名
   - 单独测试模式

3. **可选参数问题**
   - 确保可选参数在最后
   - 在动作签名中设置默认值
   - 检查URI模式

### 调试提示

```php
// 启用调试模式
Route::enableDebug();

// 检查参数匹配
$route = Route::match('/users/123', 'GET');
if ($route) {
    $params = $route->getParameters();
    var_dump($params);
}
```

---

## 另请参阅

- [基本路由](01_BASIC_ROUTING.md) - 基本路由注册
- [路由组](03_ROUTE_GROUPS.md) - 路由组织
- [URL生成](12_URL_GENERATION.md) - 生成带参数的URL
- [表达式语言](13_EXPRESSION_LANGUAGE.md) - 高级参数验证
- [API参考](../API_REFERENCE.md) - 完整API参考

---

© 2024 CloudCastle HTTP Router  
[⬆ 返回顶部](#路由参数)