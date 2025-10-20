# Basic Routing

[**English**](01_BASIC_ROUTING.md) | [Русский](../../ru/features/01_BASIC_ROUTING.md) | [Deutsch](../../de/features/01_BASIC_ROUTING.md) | [Français](../../fr/features/01_BASIC_ROUTING.md) | [中文](../../zh/features/01_BASIC_ROUTING.md)

---

## 📚 Documentation Navigation

[README](../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [API_REFERENCE](../API_REFERENCE.md) | [ALL_FEATURES](../ALL_FEATURES.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [PERFORMANCE](../PERFORMANCE_ANALYSIS.md) | [SECURITY](../SECURITY_REPORT.md) | [COMPARISON](../COMPARISON.md) | [FAQ](../FAQ.md)

**Detailed Documentation:** [01](01_BASIC_ROUTING.md) | [02](02_ROUTE_PARAMETERS.md) | [03](03_ROUTE_GROUPS.md) | [04](04_RATE_LIMITING.md) | [05](05_IP_FILTERING.md) | [06](06_MIDDLEWARE.md) | [07](07_NAMED_ROUTES.md) | [08](08_TAGS.md) | [09](09_HELPER_FUNCTIONS.md) | [10](10_ROUTE_SHORTCUTS.md) | [11](11_ROUTE_MACROS.md) | [12](12_URL_GENERATION.md) | [13](13_EXPRESSION_LANGUAGE.md) | [14](14_CACHING.md) | [15](15_PLUGINS.md) | [16](16_LOADERS.md) | [17](17_PSR_SUPPORT.md) | [18](18_ACTION_RESOLVER.md) | [19](19_STATISTICS.md) | [20](20_SECURITY.md) | [21](21_EXCEPTIONS.md) | [22](22_CLI_TOOLS.md)

---

**Category:** Core Features  
**Number of Methods:** 13  
**Complexity:** ⭐ Beginner Level

---

## Description

Basic routing is the fundamental capability of CloudCastle HTTP Router, allowing you to register handlers for various HTTP methods and URIs.

## Features

### 1. GET Route

**Method:** `Route::get(string $uri, mixed $action): Route`

**Description:** Registers a route for HTTP GET requests.

**Parameters:**
- `$uri` - Route URI (e.g., `/users`, `/posts/{id}`)
- `$action` - Action (Closure, array, controller string)

**Returns:** `Route` object for method chaining

**Examples:**

```php
use CloudCastle\Http\Router\Facade\Route;

// Simple route with Closure
Route::get('/users', function() {
    return 'List of users';
});

// With controller (array)
Route::get('/users', [UserController::class, 'index']);

// With controller (string)
Route::get('/users', 'UserController@index');

// With parameters
Route::get('/users/{id}', function($id) {
    return "User ID: $id";
});

// Method chaining
Route::get('/api/users', [UserController::class, 'index'])
    ->name('api.users')
    ->middleware([AuthMiddleware::class])
    ->throttle(100, 1);
```

**Usage:**
- Data retrieval (lists, details)
- Page display
- API endpoints for reading

---

### 2. POST Route

**Method:** `Route::post(string $uri, mixed $action): Route`

**Description:** Registers a route for HTTP POST requests.

**Parameters:**
- `$uri` - Route URI
- `$action` - Action

**Returns:** `Route` object

**Examples:**

```php
// Resource creation
Route::post('/users', function() {
    $data = $_POST;
    // Create user
    return 'User created';
});

// With controller
Route::post('/users', [UserController::class, 'store']);

// With validation and rate limiting
Route::post('/users', [UserController::class, 'store'])
    ->middleware([ValidateUser::class])
    ->throttle(20, 1);  // 20 requests per minute
```

**Usage:**
- Creating new resources
- Form submission
- API data creation

---

### 3. PUT Route

**Method:** `Route::put(string $uri, mixed $action): Route`

**Description:** Registers a route for HTTP PUT requests (full resource update).

**Parameters:**
- `$uri` - Route URI (usually with ID parameter)
- `$action` - Action

**Returns:** `Route` object

**Examples:**

```php
// Full resource update
Route::put('/users/{id}', function($id) {
    $data = json_decode(file_get_contents('php://input'), true);
    // Full user update
    return "User $id updated";
});

// With controller
Route::put('/users/{id}', [UserController::class, 'update'])
    ->where('id', '[0-9]+');

// RESTful API
Route::put('/api/v1/users/{id}', [ApiUserController::class, 'update'])
    ->middleware([AuthMiddleware::class])
    ->name('api.v1.users.update');
```

**Usage:**
- Full resource updates
- Complete data replacement
- RESTful API updates

---

### 4. PATCH Route

**Method:** `Route::patch(string $uri, mixed $action): Route`

**Description:** Registers a route for HTTP PATCH requests (partial resource update).

**Parameters:**
- `$uri` - Route URI
- `$action` - Action

**Returns:** `Route` object

**Examples:**

```php
// Partial resource update
Route::patch('/users/{id}', function($id) {
    $data = json_decode(file_get_contents('php://input'), true);
    // Partial user update
    return "User $id partially updated";
});

// With controller
Route::patch('/users/{id}', [UserController::class, 'patch'])
    ->where('id', '[0-9]+');
```

**Usage:**
- Partial resource updates
- Field-specific modifications
- Efficient updates

---

### 5. DELETE Route

**Method:** `Route::delete(string $uri, mixed $action): Route`

**Description:** Registers a route for HTTP DELETE requests.

**Parameters:**
- `$uri` - Route URI
- `$action` - Action

**Returns:** `Route` object

**Examples:**

```php
// Resource deletion
Route::delete('/users/{id}', function($id) {
    // Delete user
    return "User $id deleted";
});

// With controller
Route::delete('/users/{id}', [UserController::class, 'destroy'])
    ->where('id', '[0-9]+');
```

**Usage:**
- Resource deletion
- Data removal
- Cleanup operations

---

### 6. VIEW Route

**Method:** `Route::view(string $uri, mixed $action): Route`

**Description:** Registers a route for custom VIEW method.

**Parameters:**
- `$uri` - Route URI
- `$action` - Action

**Returns:** `Route` object

**Examples:**

```php
// Custom VIEW method
Route::view('/page', function() {
    return 'Page content';
});

// With controller
Route::view('/page', [PageController::class, 'show']);
```

**Usage:**
- Custom HTTP methods
- Specialized operations
- Non-standard endpoints

---

### 7. Custom Route

**Method:** `Route::custom(string $method, string $uri, mixed $action): Route`

**Description:** Registers a route for any custom HTTP method.

**Parameters:**
- `$method` - HTTP method name
- `$uri` - Route URI
- `$action` - Action

**Returns:** `Route` object

**Examples:**

```php
// Custom PURGE method
Route::custom('PURGE', '/cache', function() {
    // Clear cache
    return 'Cache cleared';
});

// Custom OPTIONS method
Route::custom('OPTIONS', '/api', function() {
    return 'CORS preflight';
});
```

**Usage:**
- Custom HTTP methods
- Specialized protocols
- Non-standard operations

---

### 8. Match Route

**Method:** `Route::match(array $methods, string $uri, mixed $action): Route`

**Description:** Registers a route for multiple HTTP methods.

**Parameters:**
- `$methods` - Array of HTTP methods
- `$uri` - Route URI
- `$action` - Action

**Returns:** `Route` object

**Examples:**

```php
// Multiple methods
Route::match(['GET', 'POST'], '/form', function() {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        return 'Show form';
    }
    return 'Process form';
});

// With controller
Route::match(['PUT', 'PATCH'], '/users/{id}', [UserController::class, 'update']);
```

**Usage:**
- Multiple method handling
- Form processing
- Flexible endpoints

---

### 9. Any Route

**Method:** `Route::any(string $uri, mixed $action): Route`

**Description:** Registers a route for all HTTP methods.

**Parameters:**
- `$uri` - Route URI
- `$action` - Action

**Returns:** `Route` object

**Examples:**

```php
// All methods
Route::any('/endpoint', function() {
    $method = $_SERVER['REQUEST_METHOD'];
    return "Handling $method request";
});

// With controller
Route::any('/api/endpoint', [ApiController::class, 'handle']);
```

**Usage:**
- Universal endpoints
- Method-agnostic handling
- Flexible APIs

---

### 10. Router Instance

**Method:** `Router::getInstance(): Router`

**Description:** Gets the singleton router instance.

**Returns:** `Router` instance

**Examples:**

```php
use CloudCastle\Http\Router\Router;

$router = Router::getInstance();
$router->get('/users', $action);
$router->post('/users', $action);
```

**Usage:**
- Direct router access
- Singleton pattern
- Programmatic control

---

### 11. Facade API

**Description:** Static interface for route registration.

**Examples:**

```php
use CloudCastle\Http\Router\Facade\Route;

Route::get('/users', $action);
Route::post('/users', $action);
Route::put('/users/{id}', $action);
Route::patch('/users/{id}', $action);
Route::delete('/users/{id}', $action);
```

**Usage:**
- Clean syntax
- Static access
- Method chaining

---

### 12. Route Registration

**Description:** Registering routes in application.

**Examples:**

```php
// In routes/web.php
Route::get('/', function() {
    return 'Welcome';
});

Route::get('/about', function() {
    return 'About page';
});

Route::get('/contact', function() {
    return 'Contact page';
});
```

**Usage:**
- Application setup
- Route definitions
- Configuration

---

### 13. Route Dispatching

**Description:** Dispatching requests to registered routes.

**Examples:**

```php
use CloudCastle\Http\Router\Facade\Route;

// Register routes
Route::get('/users', $action);

// Dispatch request
$route = Route::dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
if ($route) {
    echo $route->run();
}
```

**Usage:**
- Request handling
- Route matching
- Response generation

---

## Best Practices

### 1. Route Organization

```php
// Group related routes
Route::group(['prefix' => 'api/v1'], function() {
    Route::get('/users', [UserController::class, 'index']);
    Route::post('/users', [UserController::class, 'store']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);
});
```

### 2. Method Chaining

```php
Route::get('/api/users', [UserController::class, 'index'])
    ->name('api.users.index')
    ->middleware([AuthMiddleware::class])
    ->throttle(100, 1)
    ->tag('api');
```

### 3. Parameter Validation

```php
Route::get('/users/{id}', [UserController::class, 'show'])
    ->where('id', '[0-9]+');
```

### 4. Security Considerations

```php
Route::post('/users', [UserController::class, 'store'])
    ->middleware([ValidateUser::class])
    ->throttle(20, 1)
    ->whitelistIp(['192.168.1.0/24']);
```

---

## Common Patterns

### 1. RESTful Routes

```php
Route::get('/users', [UserController::class, 'index']);      // List
Route::post('/users', [UserController::class, 'store']);   // Create
Route::get('/users/{id}', [UserController::class, 'show']); // Show
Route::put('/users/{id}', [UserController::class, 'update']); // Update
Route::delete('/users/{id}', [UserController::class, 'destroy']); // Delete
```

### 2. API Routes

```php
Route::group(['prefix' => 'api/v1', 'middleware' => 'auth'], function() {
    Route::apiResource('users', UserController::class);
    Route::apiResource('posts', PostController::class);
});
```

### 3. Web Routes

```php
Route::group(['middleware' => 'web'], function() {
    Route::get('/', [HomeController::class, 'index']);
    Route::get('/about', [PageController::class, 'about']);
    Route::get('/contact', [PageController::class, 'contact']);
});
```

---

## Performance Tips

### 1. Route Caching

```php
$router = Router::getInstance();
$router->enableCache('cache/routes.php');
$router->compile();
```

### 2. Efficient Matching

```php
// More specific routes first
Route::get('/users/{id}/posts/{post}', $action);
Route::get('/users/{id}', $action);
Route::get('/users', $action);
```

### 3. Parameter Constraints

```php
Route::get('/users/{id}', $action)->where('id', '[0-9]+');
```

---

## Troubleshooting

### Common Issues

1. **Route not found**
   - Check URI pattern
   - Verify HTTP method
   - Check route registration order

2. **Parameter not passed**
   - Verify parameter name in URI
   - Check parameter constraints
   - Ensure proper action signature

3. **Method chaining issues**
   - Check return type
   - Verify method availability
   - Check method order

### Debug Tips

```php
// Enable debug mode
Route::enableDebug();

// Get all registered routes
$routes = Route::getAllRoutes();

// Check route matching
$route = Route::match('/users/123', 'GET');
```

---

## See Also

- [Route Parameters](02_ROUTE_PARAMETERS.md) - Dynamic route parameters
- [Route Groups](03_ROUTE_GROUPS.md) - Organizing routes
- [Middleware](06_MIDDLEWARE.md) - Request processing
- [Named Routes](07_NAMED_ROUTES.md) - Route identification
- [API Reference](../API_REFERENCE.md) - Complete API reference

---

© 2024 CloudCastle HTTP Router  
[⬆ Back to top](#basic-routing)