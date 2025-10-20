# 路由组

[English](../../en/features/03_ROUTE_GROUPS.md) | [Русский](../../ru/features/03_ROUTE_GROUPS.md) | [Deutsch](../../de/features/03_ROUTE_GROUPS.md) | [Français](../../fr/features/03_ROUTE_GROUPS.md) | [**中文**](03_ROUTE_GROUPS.md)

---

## 📚 文档导航

[README](../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [API_REFERENCE](../API_REFERENCE.md) | [ALL_FEATURES](../ALL_FEATURES.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [PERFORMANCE](../PERFORMANCE_ANALYSIS.md) | [SECURITY](../SECURITY_REPORT.md) | [COMPARISON](../COMPARISON.md) | [FAQ](../FAQ.md)

**详细文档:** [01](01_BASIC_ROUTING.md) | [02](02_ROUTE_PARAMETERS.md) | [03](03_ROUTE_GROUPS.md) | [04](04_RATE_LIMITING.md) | [05](05_IP_FILTERING.md) | [06](06_MIDDLEWARE.md) | [07](07_NAMED_ROUTES.md) | [08](08_TAGS.md) | [09](09_HELPER_FUNCTIONS.md) | [10](10_ROUTE_SHORTCUTS.md) | [11](11_ROUTE_MACROS.md) | [12](12_URL_GENERATION.md) | [13](13_EXPRESSION_LANGUAGE.md) | [14](14_CACHING.md) | [15](15_PLUGINS.md) | [16](16_LOADERS.md) | [17](17_PSR_SUPPORT.md) | [18](18_ACTION_RESOLVER.md) | [19](19_STATISTICS.md) | [20](20_SECURITY.md) | [21](21_EXCEPTIONS.md) | [22](22_CLI_TOOLS.md)

---

**类别:** 代码组织  
**属性数量:** 12  
**复杂度:** ⭐⭐ 中级

---

## 描述

路由组允许使用共同属性（前缀、中间件、域名等）组织路由，并将它们应用到组中的所有路由。这简化了代码并使其更易维护。

## 功能

### 1. 前缀

**属性:** `'prefix' => string`

**描述:** 为组中所有URI添加前缀。

**示例:**

```php
// 简单前缀
Route::group(['prefix' => '/api'], function() {
    Route::get('/users', $action);     // /api/users
    Route::get('/posts', $action);     // /api/posts
});

// API版本控制
Route::group(['prefix' => '/api/v1'], function() {
    Route::get('/users', [ApiV1UserController::class, 'index']);
    Route::get('/posts', [ApiV1PostController::class, 'index']);
});

// 嵌套前缀
Route::group(['prefix' => '/admin'], function() {
    Route::group(['prefix' => '/users'], function() {
        Route::get('/', $action);           // /admin/users
        Route::get('/{id}', $action);       // /admin/users/{id}
    });
});

// 多个级别
Route::group(['prefix' => '/app'], function() {
    Route::group(['prefix' => '/api'], function() {
        Route::group(['prefix' => '/v1'], function() {
            Route::get('/data', $action);   // /app/api/v1/data
        });
    });
});
```

---

### 2. 中间件

**属性:** `'middleware' => array|string`

**描述:** 将中间件应用到组中的所有路由。

**示例:**

```php
use CloudCastle\Http\Router\Middleware\AuthMiddleware;

// 单个中间件
Route::group(['middleware' => AuthMiddleware::class], function() {
    Route::get('/dashboard', $action);
    Route::get('/profile', $action);
});

// 多个中间件
Route::group([
    'middleware' => [
        AuthMiddleware::class,
        AdminMiddleware::class,
        LoggerMiddleware::class
    ]
], function() {
    Route::get('/admin/users', $action);
    Route::get('/admin/posts', $action);
});

// 混合中间件（组 + 单独）
Route::group(['middleware' => AuthMiddleware::class], function() {
    Route::get('/dashboard', $action);  // 仅AuthMiddleware
    
    Route::get('/admin', $action)
        ->middleware([AdminMiddleware::class]);  // AuthMiddleware + AdminMiddleware
});
```

---

### 3. 域名

**属性:** `'domain' => string`

**描述:** 将路由限制到特定域名。

**示例:**

```php
// API子域名
Route::group(['domain' => 'api.example.com'], function() {
    Route::get('/users', $action);
    Route::get('/posts', $action);
});

// 管理子域名
Route::group(['domain' => 'admin.example.com'], function() {
    Route::get('/dashboard', $action);
    Route::get('/users', $action);
});

// 通配符子域名
Route::group(['domain' => '{subdomain}.example.com'], function() {
    Route::get('/data', function($subdomain) {
        return "子域名: $subdomain";
    });
});

// 多个域名
Route::group(['domain' => ['api.example.com', 'api.local']], function() {
    Route::get('/users', $action);
});
```

---

### 4. 命名空间

**属性:** `'namespace' => string`

**描述:** 为组中的控制器设置命名空间。

**示例:**

```php
// API命名空间
Route::group(['namespace' => 'App\\Http\\Controllers\\Api'], function() {
    Route::get('/users', 'UserController@index');  // App\Http\Controllers\Api\UserController
    Route::get('/posts', 'PostController@index');  // App\Http\Controllers\Api\PostController
});

// 管理命名空间
Route::group(['namespace' => 'App\\Http\\Controllers\\Admin'], function() {
    Route::get('/dashboard', 'DashboardController@index');
    Route::get('/users', 'UserController@index');
});

// 嵌套命名空间
Route::group(['namespace' => 'App\\Http\\Controllers'], function() {
    Route::group(['namespace' => 'Api\\V1'], function() {
        Route::get('/users', 'UserController@index');  // App\Http\Controllers\Api\V1\UserController
    });
});
```

---

### 5. 路由名称

**属性:** `'as' => string`

**描述:** 为组中的路由名称添加前缀。

**示例:**

```php
// API路由名称
Route::group(['as' => 'api.'], function() {
    Route::get('/users', $action)->name('users');      // api.users
    Route::get('/posts', $action)->name('posts');      // api.posts
});

// 管理路由名称
Route::group(['as' => 'admin.'], function() {
    Route::get('/dashboard', $action)->name('dashboard');  // admin.dashboard
    Route::get('/users', $action)->name('users');          // admin.users
});

// 嵌套路由名称
Route::group(['as' => 'api.v1.'], function() {
    Route::get('/users', $action)->name('users');      // api.v1.users
    Route::get('/posts', $action)->name('posts');      // api.v1.posts
});
```

---

### 6. 速率限制

**属性:** `'throttle' => array`

**描述:** 将速率限制应用到组中的所有路由。

**示例:**

```php
// API速率限制
Route::group(['throttle' => [100, 1]], function() {
    Route::get('/users', $action);     // 每分钟100个请求
    Route::get('/posts', $action);     // 每分钟100个请求
});

// 不同组的不同限制
Route::group(['throttle' => [60, 1]], function() {
    Route::get('/public/data', $action);  // 每分钟60个请求
});

Route::group(['throttle' => [1000, 1]], function() {
    Route::get('/premium/data', $action); // 每分钟1000个请求
});
```

---

### 7. IP过滤

**属性:** `'whitelist' => array` | `'blacklist' => array`

**描述:** 将IP过滤应用到组中的所有路由。

**示例:**

```php
// 白名单特定IP
Route::group(['whitelist' => ['192.168.1.0/24', '10.0.0.0/8']], function() {
    Route::get('/admin', $action);
    Route::get('/internal', $action);
});

// 黑名单特定IP
Route::group(['blacklist' => ['192.168.1.100', '10.0.0.50']], function() {
    Route::get('/public', $action);
});
```

---

### 8. 标签

**属性:** `'tag' => array|string`

**描述:** 为组中的所有路由添加标签。

**示例:**

```php
// 单个标签
Route::group(['tag' => 'api'], function() {
    Route::get('/users', $action);
    Route::get('/posts', $action);
});

// 多个标签
Route::group(['tag' => ['api', 'v1', 'public']], function() {
    Route::get('/users', $action);
    Route::get('/posts', $action);
});
```

---

### 9. 缓存设置

**属性:** `'cache' => array`

**描述:** 为组中的所有路由设置缓存设置。

**示例:**

```php
// 缓存1小时
Route::group(['cache' => [3600]], function() {
    Route::get('/static-data', $action);
    Route::get('/public-info', $action);
});

// 带标签的缓存
Route::group(['cache' => [3600, ['api', 'v1']]], function() {
    Route::get('/users', $action);
    Route::get('/posts', $action);
});
```

---

### 10. 多个属性

**描述:** 在单个组中组合多个属性。

**示例:**

```php
// 完整API组
Route::group([
    'prefix' => '/api/v1',
    'middleware' => [AuthMiddleware::class],
    'namespace' => 'App\\Http\\Controllers\\Api\\V1',
    'as' => 'api.v1.',
    'throttle' => [100, 1],
    'tag' => ['api', 'v1']
], function() {
    Route::get('/users', 'UserController@index')->name('users');
    Route::get('/posts', 'PostController@index')->name('posts');
});

// 管理组
Route::group([
    'prefix' => '/admin',
    'domain' => 'admin.example.com',
    'middleware' => [AuthMiddleware::class, AdminMiddleware::class],
    'namespace' => 'App\\Http\\Controllers\\Admin',
    'as' => 'admin.',
    'whitelist' => ['192.168.1.0/24']
], function() {
    Route::get('/dashboard', 'DashboardController@index');
    Route::get('/users', 'UserController@index');
});
```

---

### 11. 嵌套组

**描述:** 组内的组用于复杂组织。

**示例:**

```php
// 主API组
Route::group(['prefix' => '/api', 'middleware' => AuthMiddleware::class], function() {
    
    // 公共路由（无需认证）
    Route::group(['middleware' => []], function() {
        Route::get('/health', $action);
        Route::get('/version', $action);
    });
    
    // V1 API
    Route::group(['prefix' => '/v1', 'as' => 'v1.'], function() {
        Route::get('/users', $action)->name('users');
        Route::get('/posts', $action)->name('posts');
    });
    
    // V2 API
    Route::group(['prefix' => '/v2', 'as' => 'v2.'], function() {
        Route::get('/users', $action)->name('users');
        Route::get('/posts', $action)->name('posts');
    });
    
    // 管理API
    Route::group(['prefix' => '/admin', 'middleware' => AdminMiddleware::class], function() {
        Route::get('/stats', $action);
        Route::get('/logs', $action);
    });
});
```

---

### 12. 条件组

**描述:** 带条件属性的组。

**示例:**

```php
// 基于环境的组
if (app()->environment('production')) {
    Route::group(['domain' => 'api.example.com'], function() {
        Route::get('/users', $action);
    });
} else {
    Route::group(['domain' => 'api.local'], function() {
        Route::get('/users', $action);
    });
}

// 基于功能的组
if (config('features.api_v2')) {
    Route::group(['prefix' => '/api/v2'], function() {
        Route::get('/users', $action);
    });
}
```

---

## 最佳实践

### 1. 逻辑分组

```php
// 按功能分组
Route::group(['prefix' => '/api/v1'], function() {
    Route::group(['prefix' => '/users'], function() {
        Route::get('/', [UserController::class, 'index']);
        Route::post('/', [UserController::class, 'store']);
        Route::get('/{id}', [UserController::class, 'show']);
        Route::put('/{id}', [UserController::class, 'update']);
        Route::delete('/{id}', [UserController::class, 'destroy']);
    });
});
```

### 2. 中间件组织

```php
// 按中间件要求分组
Route::group(['middleware' => AuthMiddleware::class], function() {
    Route::get('/dashboard', $action);
    Route::get('/profile', $action);
    
    Route::group(['middleware' => AdminMiddleware::class], function() {
        Route::get('/admin/users', $action);
        Route::get('/admin/posts', $action);
    });
});
```

### 3. 一致命名

```php
// 一致的路由命名
Route::group(['as' => 'api.v1.'], function() {
    Route::get('/users', $action)->name('users.index');
    Route::post('/users', $action)->name('users.store');
    Route::get('/users/{id}', $action)->name('users.show');
});
```

---

## 常见模式

### 1. API版本控制

```php
Route::group(['prefix' => '/api'], function() {
    Route::group(['prefix' => '/v1', 'as' => 'v1.'], function() {
        Route::apiResource('users', UserController::class);
        Route::apiResource('posts', PostController::class);
    });
    
    Route::group(['prefix' => '/v2', 'as' => 'v2.'], function() {
        Route::apiResource('users', UserV2Controller::class);
        Route::apiResource('posts', PostV2Controller::class);
    });
});
```

### 2. 管理面板

```php
Route::group([
    'prefix' => '/admin',
    'middleware' => [AuthMiddleware::class, AdminMiddleware::class],
    'namespace' => 'App\\Http\\Controllers\\Admin',
    'as' => 'admin.'
], function() {
    Route::get('/dashboard', 'DashboardController@index');
    Route::resource('users', 'UserController');
    Route::resource('posts', 'PostController');
});
```

### 3. 公共 vs 私有

```php
// 公共路由
Route::group(['middleware' => []], function() {
    Route::get('/', [HomeController::class, 'index']);
    Route::get('/about', [PageController::class, 'about']);
});

// 私有路由
Route::group(['middleware' => AuthMiddleware::class], function() {
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/profile', [ProfileController::class, 'show']);
});
```

---

## 性能提示

### 1. 最小化嵌套

```php
// 好: 扁平结构
Route::group(['prefix' => '/api/v1'], function() {
    Route::get('/users', $action);
    Route::get('/posts', $action);
});

// 避免: 深度嵌套
Route::group(['prefix' => '/api'], function() {
    Route::group(['prefix' => '/v1'], function() {
        Route::group(['prefix' => '/users'], function() {
            Route::get('/', $action);
        });
    });
});
```

### 2. 高效中间件

```php
// 在组级别应用中间件
Route::group(['middleware' => AuthMiddleware::class], function() {
    Route::get('/dashboard', $action);
    Route::get('/profile', $action);
});
```

---

## 故障排除

### 常见问题

1. **中间件未应用**
   - 检查中间件注册
   - 验证中间件类存在
   - 检查中间件顺序

2. **前缀不工作**
   - 验证前缀格式
   - 检查前导/尾随斜杠
   - 确保正确嵌套

3. **命名空间问题**
   - 检查命名空间格式
   - 验证控制器类存在
   - 检查自动加载

### 调试提示

```php
// 启用调试模式
Route::enableDebug();

// 检查组属性
$routes = Route::getAllRoutes();
foreach ($routes as $route) {
    echo $route->getUri() . ' - ' . $route->getName() . PHP_EOL;
}
```

---

## 另请参阅

- [基本路由](01_BASIC_ROUTING.md) - 基本路由注册
- [路由参数](02_ROUTE_PARAMETERS.md) - 动态路由参数
- [中间件](06_MIDDLEWARE.md) - 请求处理中间件
- [命名路由](07_NAMED_ROUTES.md) - 路由标识
- [API参考](../API_REFERENCE.md) - 完整API参考

---

© 2024 CloudCastle HTTP Router  
[⬆ 返回顶部](#路由组)