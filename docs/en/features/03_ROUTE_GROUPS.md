# Route Groups

[**English**](03_ROUTE_GROUPS.md) | [Русский](../../ru/features/03_ROUTE_GROUPS.md) | [Deutsch](../../de/features/03_ROUTE_GROUPS.md) | [Français](../../fr/features/03_ROUTE_GROUPS.md) | [中文](../../zh/features/03_ROUTE_GROUPS.md)

---

## 📚 Documentation Navigation

[README](../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [API_REFERENCE](../API_REFERENCE.md) | [ALL_FEATURES](../ALL_FEATURES.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [PERFORMANCE](../PERFORMANCE_ANALYSIS.md) | [SECURITY](../SECURITY_REPORT.md) | [COMPARISON](../COMPARISON.md) | [FAQ](../FAQ.md)

**Detailed Documentation:** [01](01_BASIC_ROUTING.md) | [02](02_ROUTE_PARAMETERS.md) | [03](03_ROUTE_GROUPS.md) | [04](04_RATE_LIMITING.md) | [05](05_IP_FILTERING.md) | [06](06_MIDDLEWARE.md) | [07](07_NAMED_ROUTES.md) | [08](08_TAGS.md) | [09](09_HELPER_FUNCTIONS.md) | [10](10_ROUTE_SHORTCUTS.md) | [11](11_ROUTE_MACROS.md) | [12](12_URL_GENERATION.md) | [13](13_EXPRESSION_LANGUAGE.md) | [14](14_CACHING.md) | [15](15_PLUGINS.md) | [16](16_LOADERS.md) | [17](17_PSR_SUPPORT.md) | [18](18_ACTION_RESOLVER.md) | [19](19_STATISTICS.md) | [20](20_SECURITY.md) | [21](21_EXCEPTIONS.md) | [22](22_CLI_TOOLS.md)

---

**Category:** Code Organization  
**Number of Attributes:** 12  
**Complexity:** ⭐⭐ Intermediate Level

---

## Description

Route groups allow you to organize routes with common attributes (prefix, middleware, domain, etc.), applying them to all routes in the group. This simplifies code and makes it more maintainable.

## Features

### 1. Prefix

**Attribute:** `'prefix' => string`

**Description:** Adds a prefix to all URIs in the group.

**Examples:**

```php
// Simple prefix
Route::group(['prefix' => '/api'], function() {
    Route::get('/users', $action);     // /api/users
    Route::get('/posts', $action);     // /api/posts
});

// API versioning
Route::group(['prefix' => '/api/v1'], function() {
    Route::get('/users', [ApiV1UserController::class, 'index']);
    Route::get('/posts', [ApiV1PostController::class, 'index']);
});

// Nested prefixes
Route::group(['prefix' => '/admin'], function() {
    Route::group(['prefix' => '/users'], function() {
        Route::get('/', $action);           // /admin/users
        Route::get('/{id}', $action);       // /admin/users/{id}
    });
});

// Multiple levels
Route::group(['prefix' => '/app'], function() {
    Route::group(['prefix' => '/api'], function() {
        Route::group(['prefix' => '/v1'], function() {
            Route::get('/data', $action);   // /app/api/v1/data
        });
    });
});
```

---

### 2. Middleware

**Attribute:** `'middleware' => array|string`

**Description:** Applies middleware to all routes in the group.

**Examples:**

```php
use CloudCastle\Http\Router\Middleware\AuthMiddleware;

// Single middleware
Route::group(['middleware' => AuthMiddleware::class], function() {
    Route::get('/dashboard', $action);
    Route::get('/profile', $action);
});

// Multiple middleware
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

// Mixed middleware (group + individual)
Route::group(['middleware' => AuthMiddleware::class], function() {
    Route::get('/dashboard', $action);  // AuthMiddleware only
    
    Route::get('/admin', $action)
        ->middleware([AdminMiddleware::class]);  // AuthMiddleware + AdminMiddleware
});
```

---

### 3. Domain

**Attribute:** `'domain' => string`

**Description:** Restricts routes to a specific domain.

**Examples:**

```php
// API subdomain
Route::group(['domain' => 'api.example.com'], function() {
    Route::get('/users', $action);
    Route::get('/posts', $action);
});

// Admin subdomain
Route::group(['domain' => 'admin.example.com'], function() {
    Route::get('/dashboard', $action);
    Route::get('/users', $action);
});

// Wildcard subdomain
Route::group(['domain' => '{subdomain}.example.com'], function() {
    Route::get('/data', function($subdomain) {
        return "Subdomain: $subdomain";
    });
});

// Multiple domains
Route::group(['domain' => ['api.example.com', 'api.local']], function() {
    Route::get('/users', $action);
});
```

---

### 4. Namespace

**Attribute:** `'namespace' => string`

**Description:** Sets the namespace for controllers in the group.

**Examples:**

```php
// API namespace
Route::group(['namespace' => 'App\\Http\\Controllers\\Api'], function() {
    Route::get('/users', 'UserController@index');  // App\Http\Controllers\Api\UserController
    Route::get('/posts', 'PostController@index');  // App\Http\Controllers\Api\PostController
});

// Admin namespace
Route::group(['namespace' => 'App\\Http\\Controllers\\Admin'], function() {
    Route::get('/dashboard', 'DashboardController@index');
    Route::get('/users', 'UserController@index');
});

// Nested namespaces
Route::group(['namespace' => 'App\\Http\\Controllers'], function() {
    Route::group(['namespace' => 'Api\\V1'], function() {
        Route::get('/users', 'UserController@index');  // App\Http\Controllers\Api\V1\UserController
    });
});
```

---

### 5. Route Names

**Attribute:** `'as' => string`

**Description:** Adds a prefix to route names in the group.

**Examples:**

```php
// API route names
Route::group(['as' => 'api.'], function() {
    Route::get('/users', $action)->name('users');      // api.users
    Route::get('/posts', $action)->name('posts');      // api.posts
});

// Admin route names
Route::group(['as' => 'admin.'], function() {
    Route::get('/dashboard', $action)->name('dashboard');  // admin.dashboard
    Route::get('/users', $action)->name('users');          // admin.users
});

// Nested route names
Route::group(['as' => 'api.v1.'], function() {
    Route::get('/users', $action)->name('users');      // api.v1.users
    Route::get('/posts', $action)->name('posts');      // api.v1.posts
});
```

---

### 6. Rate Limiting

**Attribute:** `'throttle' => array`

**Description:** Applies rate limiting to all routes in the group.

**Examples:**

```php
// API rate limiting
Route::group(['throttle' => [100, 1]], function() {
    Route::get('/users', $action);     // 100 requests per minute
    Route::get('/posts', $action);     // 100 requests per minute
});

// Different limits for different groups
Route::group(['throttle' => [60, 1]], function() {
    Route::get('/public/data', $action);  // 60 requests per minute
});

Route::group(['throttle' => [1000, 1]], function() {
    Route::get('/premium/data', $action); // 1000 requests per minute
});
```

---

### 7. IP Filtering

**Attribute:** `'whitelist' => array` | `'blacklist' => array`

**Description:** Applies IP filtering to all routes in the group.

**Examples:**

```php
// Whitelist specific IPs
Route::group(['whitelist' => ['192.168.1.0/24', '10.0.0.0/8']], function() {
    Route::get('/admin', $action);
    Route::get('/internal', $action);
});

// Blacklist specific IPs
Route::group(['blacklist' => ['192.168.1.100', '10.0.0.50']], function() {
    Route::get('/public', $action);
});
```

---

### 8. Tags

**Attribute:** `'tag' => array|string`

**Description:** Adds tags to all routes in the group.

**Examples:**

```php
// Single tag
Route::group(['tag' => 'api'], function() {
    Route::get('/users', $action);
    Route::get('/posts', $action);
});

// Multiple tags
Route::group(['tag' => ['api', 'v1', 'public']], function() {
    Route::get('/users', $action);
    Route::get('/posts', $action);
});
```

---

### 9. Cache Settings

**Attribute:** `'cache' => array`

**Description:** Sets cache settings for all routes in the group.

**Examples:**

```php
// Cache for 1 hour
Route::group(['cache' => [3600]], function() {
    Route::get('/static-data', $action);
    Route::get('/public-info', $action);
});

// Cache with tags
Route::group(['cache' => [3600, ['api', 'v1']]], function() {
    Route::get('/users', $action);
    Route::get('/posts', $action);
});
```

---

### 10. Multiple Attributes

**Description:** Combining multiple attributes in a single group.

**Examples:**

```php
// Complete API group
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

// Admin group
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

### 11. Nested Groups

**Description:** Groups within groups for complex organization.

**Examples:**

```php
// Main API group
Route::group(['prefix' => '/api', 'middleware' => AuthMiddleware::class], function() {
    
    // Public routes (no auth required)
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
    
    // Admin API
    Route::group(['prefix' => '/admin', 'middleware' => AdminMiddleware::class], function() {
        Route::get('/stats', $action);
        Route::get('/logs', $action);
    });
});
```

---

### 12. Conditional Groups

**Description:** Groups with conditional attributes.

**Examples:**

```php
// Environment-based groups
if (app()->environment('production')) {
    Route::group(['domain' => 'api.example.com'], function() {
        Route::get('/users', $action);
    });
} else {
    Route::group(['domain' => 'api.local'], function() {
        Route::get('/users', $action);
    });
}

// Feature-based groups
if (config('features.api_v2')) {
    Route::group(['prefix' => '/api/v2'], function() {
        Route::get('/users', $action);
    });
}
```

---

## Best Practices

### 1. Logical Grouping

```php
// Group by functionality
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

### 2. Middleware Organization

```php
// Group by middleware requirements
Route::group(['middleware' => AuthMiddleware::class], function() {
    Route::get('/dashboard', $action);
    Route::get('/profile', $action);
    
    Route::group(['middleware' => AdminMiddleware::class], function() {
        Route::get('/admin/users', $action);
        Route::get('/admin/posts', $action);
    });
});
```

### 3. Consistent Naming

```php
// Consistent route naming
Route::group(['as' => 'api.v1.'], function() {
    Route::get('/users', $action)->name('users.index');
    Route::post('/users', $action)->name('users.store');
    Route::get('/users/{id}', $action)->name('users.show');
});
```

---

## Common Patterns

### 1. API Versioning

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

### 2. Admin Panel

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

### 3. Public vs Private

```php
// Public routes
Route::group(['middleware' => []], function() {
    Route::get('/', [HomeController::class, 'index']);
    Route::get('/about', [PageController::class, 'about']);
});

// Private routes
Route::group(['middleware' => AuthMiddleware::class], function() {
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/profile', [ProfileController::class, 'show']);
});
```

---

## Performance Tips

### 1. Minimize Nesting

```php
// Good: Flat structure
Route::group(['prefix' => '/api/v1'], function() {
    Route::get('/users', $action);
    Route::get('/posts', $action);
});

// Avoid: Deep nesting
Route::group(['prefix' => '/api'], function() {
    Route::group(['prefix' => '/v1'], function() {
        Route::group(['prefix' => '/users'], function() {
            Route::get('/', $action);
        });
    });
});
```

### 2. Efficient Middleware

```php
// Apply middleware at group level
Route::group(['middleware' => AuthMiddleware::class], function() {
    Route::get('/dashboard', $action);
    Route::get('/profile', $action);
});
```

---

## Troubleshooting

### Common Issues

1. **Middleware not applied**
   - Check middleware registration
   - Verify middleware class exists
   - Check middleware order

2. **Prefix not working**
   - Verify prefix format
   - Check for leading/trailing slashes
   - Ensure proper nesting

3. **Namespace issues**
   - Check namespace format
   - Verify controller classes exist
   - Check autoloading

### Debug Tips

```php
// Enable debug mode
Route::enableDebug();

// Check group attributes
$routes = Route::getAllRoutes();
foreach ($routes as $route) {
    echo $route->getUri() . ' - ' . $route->getName() . PHP_EOL;
}
```

---

## See Also

- [Basic Routing](01_BASIC_ROUTING.md) - Basic route registration
- [Route Parameters](02_ROUTE_PARAMETERS.md) - Dynamic route parameters
- [Middleware](06_MIDDLEWARE.md) - Request processing middleware
- [Named Routes](07_NAMED_ROUTES.md) - Route identification
- [API Reference](../API_REFERENCE.md) - Complete API reference

---

© 2024 CloudCastle HTTP Router  
[⬆ Back to top](#route-groups)