# Route Parameters

[**English**](02_ROUTE_PARAMETERS.md) | [Русский](../../ru/features/02_ROUTE_PARAMETERS.md) | [Deutsch](../../de/features/02_ROUTE_PARAMETERS.md) | [Français](../../fr/features/02_ROUTE_PARAMETERS.md) | [中文](../../zh/features/02_ROUTE_PARAMETERS.md)

---

## 📚 Documentation Navigation

[README](../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [API_REFERENCE](../API_REFERENCE.md) | [ALL_FEATURES](../ALL_FEATURES.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [PERFORMANCE](../PERFORMANCE_ANALYSIS.md) | [SECURITY](../SECURITY_REPORT.md) | [COMPARISON](../COMPARISON.md) | [FAQ](../FAQ.md)

**Detailed Documentation:** [01](01_BASIC_ROUTING.md) | [02](02_ROUTE_PARAMETERS.md) | [03](03_ROUTE_GROUPS.md) | [04](04_RATE_LIMITING.md) | [05](05_IP_FILTERING.md) | [06](06_MIDDLEWARE.md) | [07](07_NAMED_ROUTES.md) | [08](08_TAGS.md) | [09](09_HELPER_FUNCTIONS.md) | [10](10_ROUTE_SHORTCUTS.md) | [11](11_ROUTE_MACROS.md) | [12](12_URL_GENERATION.md) | [13](13_EXPRESSION_LANGUAGE.md) | [14](14_CACHING.md) | [15](15_PLUGINS.md) | [16](16_LOADERS.md) | [17](17_PSR_SUPPORT.md) | [18](18_ACTION_RESOLVER.md) | [19](19_STATISTICS.md) | [20](20_SECURITY.md) | [21](21_EXCEPTIONS.md) | [22](22_CLI_TOOLS.md)

---

**Category:** Core Features  
**Number of Methods:** 6  
**Complexity:** ⭐⭐ Intermediate Level

---

## Description

Route parameters allow you to create dynamic URIs with variable parts, validate them, and set default values.

## Features

### 1. Basic Parameters

**Syntax:** `{parameter}`

**Description:** Defining a dynamic part of URI as a parameter.

**Examples:**

```php
// Single parameter
Route::get('/users/{id}', function($id) {
    return "User ID: $id";
});

// Dispatch: /users/123 → $id = '123'


// Multiple parameters
Route::get('/posts/{year}/{month}/{slug}', function($year, $month, $slug) {
    return "Post: $year/$month/$slug";
});

// Dispatch: /posts/2024/10/hello-world
// → $year = '2024', $month = '10', $slug = 'hello-world'


// With controller
Route::get('/users/{id}/posts/{postId}', [PostController::class, 'show']);
// In controller:
// public function show($id, $postId) { ... }


// Getting parameters from Route object
Route::get('/api/{version}/users/{id}', function($version, $id) {
    $route = Route::current();
    $params = $route->getParameters();
    // ['version' => 'v1', 'id' => '123']
    
    return "API $version, User $id";
});
```

**Features:**
- Parameters are passed to action in order
- Case sensitive
- Can contain letters, numbers, underscores
- Automatically extracted from URI

---

### 2. Parameter Constraints (where)

**Method:** `where(string|array $parameter, ?string $pattern = null): Route`

**Description:** Adding regular expressions for parameter validation.

**Parameters:**
- `$parameter` - Parameter name or array [parameter => pattern]
- `$pattern` - Regular expression (if $parameter is string)

**Examples:**

```php
// Only digits
Route::get('/users/{id}', $action)
    ->where('id', '[0-9]+');

// Only letters
Route::get('/users/{name}', $action)
    ->where('name', '[a-zA-Z]+');

// Alphanumeric with hyphens
Route::get('/posts/{slug}', $action)
    ->where('slug', '[a-zA-Z0-9-]+');

// Multiple constraints
Route::get('/posts/{year}/{month}', $action)
    ->where([
        'year' => '[0-9]{4}',
        'month' => '0[1-9]|1[0-2]'
    ]);

// Complex patterns
Route::get('/files/{filename}', $action)
    ->where('filename', '[a-zA-Z0-9._-]+\.(jpg|png|gif)');
```

**Common Patterns:**
- `[0-9]+` - Only digits
- `[a-zA-Z]+` - Only letters
- `[a-zA-Z0-9]+` - Alphanumeric
- `[a-zA-Z0-9-]+` - Alphanumeric with hyphens
- `[0-9]{4}` - Exactly 4 digits
- `[0-9]{1,3}` - 1 to 3 digits

---

### 3. Inline Constraints

**Syntax:** `{parameter:pattern}`

**Description:** Defining constraints directly in the URI pattern.

**Examples:**

```php
// Inline digit constraint
Route::get('/users/{id:[0-9]+}', function($id) {
    return "User ID: $id";
});

// Inline alphanumeric constraint
Route::get('/posts/{slug:[a-zA-Z0-9-]+}', function($slug) {
    return "Post slug: $slug";
});

// Multiple inline constraints
Route::get('/posts/{year:[0-9]{4}}/{month:[0-9]{2}}/{slug:[a-zA-Z0-9-]+}', function($year, $month, $slug) {
    return "Post: $year/$month/$slug";
});

// Complex inline patterns
Route::get('/files/{filename:[a-zA-Z0-9._-]+\.(jpg|png|gif)}', function($filename) {
    return "File: $filename";
});
```

**Advantages:**
- More concise syntax
- Constraints visible in URI
- Better readability
- Faster matching

---

### 4. Optional Parameters

**Syntax:** `{parameter?}`

**Description:** Making parameters optional with default values.

**Examples:**

```php
// Optional parameter
Route::get('/users/{id?}', function($id = null) {
    if ($id) {
        return "User ID: $id";
    }
    return "All users";
});

// Dispatch: /users → $id = null
// Dispatch: /users/123 → $id = '123'


// Optional with default value
Route::get('/posts/{page?}', function($page = 1) {
    return "Page: $page";
});

// Dispatch: /posts → $page = 1
// Dispatch: /posts/5 → $page = '5'


// Multiple optional parameters
Route::get('/search/{query?}/{page?}', function($query = '', $page = 1) {
    return "Search: '$query', Page: $page";
});

// Dispatch: /search → $query = '', $page = 1
// Dispatch: /search/php → $query = 'php', $page = 1
// Dispatch: /search/php/2 → $query = 'php', $page = '2'
```

**Rules:**
- Optional parameters must come after required ones
- Default values are set in action signature
- Can have multiple optional parameters

---

### 5. Default Values

**Method:** `defaults(array $defaults): Route`

**Description:** Setting default values for parameters.

**Examples:**

```php
// Default values
Route::get('/posts/{page}', function($page) {
    return "Page: $page";
})->defaults(['page' => 1]);

// Dispatch: /posts → $page = 1
// Dispatch: /posts/5 → $page = '5'


// Multiple defaults
Route::get('/api/{version}/users/{id}', function($version, $id) {
    return "API $version, User $id";
})->defaults(['version' => 'v1', 'id' => 1]);

// Dispatch: /api/users → $version = 'v1', $id = 1
// Dispatch: /api/v2/users/123 → $version = 'v2', $id = '123'


// With constraints and defaults
Route::get('/posts/{year}/{month}', function($year, $month) {
    return "Posts for $year/$month";
})
->where(['year' => '[0-9]{4}', 'month' => '[0-9]{2}'])
->defaults(['year' => date('Y'), 'month' => date('m')]);
```

**Use Cases:**
- API versioning
- Pagination defaults
- Current date/time defaults
- User-specific defaults

---

### 6. Parameter Access

**Methods:**
- `getParameters(): array` - Get all parameters
- `getParameter(string $name): mixed` - Get specific parameter
- `hasParameter(string $name): bool` - Check if parameter exists

**Examples:**

```php
Route::get('/users/{id}/posts/{postId}', function($id, $postId) {
    $route = Route::current();
    
    // Get all parameters
    $params = $route->getParameters();
    // ['id' => '123', 'postId' => '456']
    
    // Get specific parameter
    $userId = $route->getParameter('id');
    $postId = $route->getParameter('postId');
    
    // Check if parameter exists
    if ($route->hasParameter('id')) {
        return "User $userId, Post $postId";
    }
    
    return "No user ID";
});
```

**Advanced Usage:**

```php
Route::get('/api/{version}/users/{id}/posts/{postId}', function($version, $id, $postId) {
    $route = Route::current();
    $params = $route->getParameters();
    
    // Filter parameters
    $filteredParams = array_filter($params, function($value, $key) {
        return !empty($value) && $key !== 'version';
    }, ARRAY_FILTER_USE_BOTH);
    
    // Use parameters for database query
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

## Best Practices

### 1. Parameter Naming

```php
// Good: Descriptive names
Route::get('/users/{userId}', $action);
Route::get('/posts/{postSlug}', $action);
Route::get('/categories/{categoryId}', $action);

// Avoid: Generic names
Route::get('/users/{id}', $action);
Route::get('/posts/{slug}', $action);
Route::get('/categories/{id}', $action);
```

### 2. Constraint Validation

```php
// Always validate parameters
Route::get('/users/{id}', [UserController::class, 'show'])
    ->where('id', '[0-9]+');

Route::get('/posts/{slug}', [PostController::class, 'show'])
    ->where('slug', '[a-zA-Z0-9-]+');
```

### 3. Default Values

```php
// Set sensible defaults
Route::get('/posts/{page}', [PostController::class, 'index'])
    ->defaults(['page' => 1])
    ->where('page', '[0-9]+');
```

### 4. Parameter Order

```php
// Required parameters first
Route::get('/users/{id}/posts/{postId}', $action);

// Optional parameters last
Route::get('/search/{query?}/{page?}', $action);
```

---

## Common Patterns

### 1. RESTful Resources

```php
Route::get('/users/{id}', [UserController::class, 'show'])
    ->where('id', '[0-9]+');

Route::get('/posts/{slug}', [PostController::class, 'show'])
    ->where('slug', '[a-zA-Z0-9-]+');
```

### 2. API Versioning

```php
Route::get('/api/{version}/users/{id}', [ApiController::class, 'show'])
    ->where(['version' => 'v[0-9]+', 'id' => '[0-9]+'])
    ->defaults(['version' => 'v1']);
```

### 3. Pagination

```php
Route::get('/posts/{page}', [PostController::class, 'index'])
    ->where('page', '[0-9]+')
    ->defaults(['page' => 1]);
```

### 4. File Downloads

```php
Route::get('/files/{filename}', [FileController::class, 'download'])
    ->where('filename', '[a-zA-Z0-9._-]+\.(pdf|doc|docx)');
```

---

## Performance Tips

### 1. Specific Constraints First

```php
// More specific routes first
Route::get('/users/{id:[0-9]+}', $action);
Route::get('/users/{name:[a-zA-Z]+}', $action);
Route::get('/users/{slug:[a-zA-Z0-9-]+}', $action);
```

### 2. Avoid Complex Patterns

```php
// Good: Simple patterns
Route::get('/posts/{id:[0-9]+}', $action);

// Avoid: Complex patterns
Route::get('/posts/{id:[0-9]{1,10}}', $action);
```

### 3. Use Inline Constraints

```php
// Faster: Inline constraints
Route::get('/users/{id:[0-9]+}', $action);

// Slower: Separate where() calls
Route::get('/users/{id}', $action)->where('id', '[0-9]+');
```

---

## Troubleshooting

### Common Issues

1. **Parameter not passed**
   - Check parameter name in URI
   - Verify parameter order in action
   - Check for typos

2. **Constraint not working**
   - Verify regex pattern
   - Check parameter name
   - Test pattern separately

3. **Optional parameter issues**
   - Ensure optional parameters come last
   - Set default values in action signature
   - Check URI pattern

### Debug Tips

```php
// Enable debug mode
Route::enableDebug();

// Check parameter matching
$route = Route::match('/users/123', 'GET');
if ($route) {
    $params = $route->getParameters();
    var_dump($params);
}
```

---

## See Also

- [Basic Routing](01_BASIC_ROUTING.md) - Basic route registration
- [Route Groups](03_ROUTE_GROUPS.md) - Organizing routes
- [URL Generation](12_URL_GENERATION.md) - Generating URLs with parameters
- [Expression Language](13_EXPRESSION_LANGUAGE.md) - Advanced parameter validation
- [API Reference](../API_REFERENCE.md) - Complete API reference

---

© 2024 CloudCastle HTTP Router  
[⬆ Back to top](#route-parameters)