# Rate Limiting & Auto-Ban

[**English**](04_RATE_LIMITING.md) | [Русский](../../ru/features/04_RATE_LIMITING.md) | [Deutsch](../../de/features/04_RATE_LIMITING.md) | [Français](../../fr/features/04_RATE_LIMITING.md) | [中文](../../zh/features/04_RATE_LIMITING.md)

---

## 📚 Documentation Navigation

[README](../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [API_REFERENCE](../API_REFERENCE.md) | [ALL_FEATURES](../ALL_FEATURES.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [PERFORMANCE](../PERFORMANCE_ANALYSIS.md) | [SECURITY](../SECURITY_REPORT.md) | [COMPARISON](../COMPARISON.md) | [FAQ](../FAQ.md)

**Detailed Documentation:** [01](01_BASIC_ROUTING.md) | [02](02_ROUTE_PARAMETERS.md) | [03](03_ROUTE_GROUPS.md) | [04](04_RATE_LIMITING.md) | [05](05_IP_FILTERING.md) | [06](06_MIDDLEWARE.md) | [07](07_NAMED_ROUTES.md) | [08](08_TAGS.md) | [09](09_HELPER_FUNCTIONS.md) | [10](10_ROUTE_SHORTCUTS.md) | [11](11_ROUTE_MACROS.md) | [12](12_URL_GENERATION.md) | [13](13_EXPRESSION_LANGUAGE.md) | [14](14_CACHING.md) | [15](15_PLUGINS.md) | [16](16_LOADERS.md) | [17](17_PSR_SUPPORT.md) | [18](18_ACTION_RESOLVER.md) | [19](19_STATISTICS.md) | [20](20_SECURITY.md) | [21](21_EXCEPTIONS.md) | [22](22_CLI_TOOLS.md)

---

**Category:** Security  
**Number of Methods:** 15  
**Complexity:** ⭐⭐⭐ Advanced Level

---

## Description

Rate Limiting and Auto-Ban are powerful built-in mechanisms for protection against DDoS attacks, brute force, and API abuse.

## Features

### Rate Limiting (8 methods)

#### 1. Basic throttle

**Method:** `throttle(int $maxAttempts, int $decayMinutes, ?callable $keyResolver = null): Route`

**Description:** Limiting the number of requests to a route.

**Parameters:**
- `$maxAttempts` - Maximum number of requests
- `$decayMinutes` - Time period in minutes
- `$keyResolver` - Optional function to determine the key (default IP)

**Examples:**

```php
// 60 requests per minute
Route::post('/api/submit', $action)
    ->throttle(60, 1);

// 100 requests per hour
Route::post('/api/upload', $action)
    ->throttle(100, 60);

// 1000 requests per day
Route::get('/api/public', $action)
    ->throttle(1000, 1440);

// With controller
Route::post('/login', [AuthController::class, 'login'])
    ->throttle(5, 1);  // 5 login attempts per minute
```

**How it works:**
1. On each request, counter for IP (or custom key) is incremented
2. If counter exceeds limit - `TooManyRequestsException` is thrown
3. After specified time, counter is reset

---

#### 2. TimeUnit enum

**Enum:** `CloudCastle\Http\Router\TimeUnit`

**Description:** Enumeration for convenient work with time units.

**Values:**
```php
TimeUnit::SECOND->value  // 1/60 minute
TimeUnit::MINUTE->value  // 1 minute
TimeUnit::HOUR->value    // 60 minutes
TimeUnit::DAY->value     // 1440 minutes
TimeUnit::WEEK->value    // 10080 minutes
TimeUnit::MONTH->value   // 43200 minutes
```

**Examples:**

```php
use CloudCastle\Http\Router\TimeUnit;

// 5 requests per second
Route::post('/api/realtime', $action)
    ->throttle(5, TimeUnit::SECOND->value);

// 100 requests per hour
Route::get('/api/data', $action)
    ->throttle(100, TimeUnit::HOUR->value);

// 1000 requests per day
Route::get('/api/public', $action)
    ->throttle(1000, TimeUnit::DAY->value);
```

---

#### 3. Custom key resolver

**Method:** `throttle(int $maxAttempts, int $decayMinutes, callable $keyResolver): Route`

**Description:** Using custom function to determine throttle key.

**Examples:**

```php
// By user ID
Route::post('/api/user-action', $action)
    ->throttle(10, 1, function($request) {
        return 'user:' . $request->user()->id;
    });

// By API key
Route::post('/api/external', $action)
    ->throttle(100, 1, function($request) {
        return 'api:' . $request->header('X-API-Key');
    });

// By combination
Route::post('/api/complex', $action)
    ->throttle(50, 1, function($request) {
        $user = $request->user();
        $ip = $request->ip();
        return "user:{$user->id}:ip:{$ip}";
    });
```

---

#### 4. Group throttle

**Method:** `throttle(array $throttle): RouteGroup`

**Description:** Applying throttle to all routes in a group.

**Examples:**

```php
// API group with throttle
Route::group(['throttle' => [100, 1]], function() {
    Route::get('/api/users', $action);
    Route::get('/api/posts', $action);
});

// Different limits for different groups
Route::group(['throttle' => [60, 1]], function() {
    Route::get('/api/public', $action);  // 60 requests per minute
});

Route::group(['throttle' => [1000, 1]], function() {
    Route::get('/api/premium', $action); // 1000 requests per minute
});
```

---

#### 5. Dynamic throttle

**Method:** `throttle(callable $throttleResolver): Route`

**Description:** Dynamic throttle based on request data.

**Examples:**

```php
// Dynamic based on user role
Route::post('/api/action', $action)
    ->throttle(function($request) {
        $user = $request->user();
        if ($user->isPremium()) {
            return [1000, 1]; // 1000 requests per minute
        }
        return [100, 1]; // 100 requests per minute
    });

// Dynamic based on request size
Route::post('/api/upload', $action)
    ->throttle(function($request) {
        $size = $request->header('Content-Length');
        if ($size > 1000000) { // > 1MB
            return [10, 1]; // 10 requests per minute
        }
        return [100, 1]; // 100 requests per minute
    });
```

---

#### 6. Throttle with conditions

**Method:** `throttle(int $maxAttempts, int $decayMinutes, ?callable $keyResolver = null, ?callable $condition = null): Route`

**Description:** Throttle with additional conditions.

**Examples:**

```php
// Throttle only for POST requests
Route::match(['GET', 'POST'], '/api/data', $action)
    ->throttle(100, 1, null, function($request) {
        return $request->isMethod('POST');
    });

// Throttle only for specific IPs
Route::post('/api/sensitive', $action)
    ->throttle(5, 1, null, function($request) {
        $ip = $request->ip();
        return in_array($ip, ['192.168.1.100', '10.0.0.50']);
    });
```

---

#### 7. Throttle statistics

**Method:** `getThrottleStats(): array`

**Description:** Getting throttle statistics.

**Examples:**

```php
// Get throttle stats
$stats = Route::getThrottleStats();

// Example output:
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

#### 8. Throttle management

**Methods:**
- `clearThrottle(string $key): void` - Clear specific throttle
- `clearAllThrottles(): void` - Clear all throttles
- `getThrottleKey(string $ip): string` - Get throttle key for IP

**Examples:**

```php
// Clear throttle for specific IP
Route::clearThrottle('192.168.1.100');

// Clear all throttles
Route::clearAllThrottles();

// Get throttle key
$key = Route::getThrottleKey('192.168.1.100');
```

---

### Auto-Ban System (7 methods)

#### 1. Basic auto-ban

**Method:** `autoBan(int $maxAttempts, int $banMinutes, ?callable $keyResolver = null): Route`

**Description:** Automatic IP blocking after exceeding attempts.

**Parameters:**
- `$maxAttempts` - Maximum attempts before ban
- `$banMinutes` - Ban duration in minutes
- `$keyResolver` - Optional function to determine the key

**Examples:**

```php
// Ban after 10 failed attempts for 1 hour
Route::post('/login', [AuthController::class, 'login'])
    ->autoBan(10, 60);

// Ban after 5 failed attempts for 30 minutes
Route::post('/api/sensitive', $action)
    ->autoBan(5, 30);

// Ban after 20 failed attempts for 24 hours
Route::post('/api/admin', $action)
    ->autoBan(20, 1440);
```

---

#### 2. Progressive auto-ban

**Method:** `progressiveAutoBan(array $levels): Route`

**Description:** Progressive ban with increasing duration.

**Examples:**

```php
// Progressive ban levels
Route::post('/login', $action)
    ->progressiveAutoBan([
        5 => 5,    // 5 attempts -> 5 minutes ban
        10 => 30,  // 10 attempts -> 30 minutes ban
        20 => 120, // 20 attempts -> 2 hours ban
        50 => 1440 // 50 attempts -> 24 hours ban
    ]);
```

---

#### 3. Auto-ban with conditions

**Method:** `autoBan(int $maxAttempts, int $banMinutes, ?callable $keyResolver = null, ?callable $condition = null): Route`

**Description:** Auto-ban with additional conditions.

**Examples:**

```php
// Ban only for failed login attempts
Route::post('/login', $action)
    ->autoBan(10, 60, null, function($request, $response) {
        return $response->getStatusCode() === 401;
    });

// Ban only for specific user agents
Route::post('/api/action', $action)
    ->autoBan(5, 30, null, function($request) {
        $userAgent = $request->header('User-Agent');
        return strpos($userAgent, 'bot') !== false;
    });
```

---

#### 4. Ban management

**Methods:**
- `banIp(string $ip, int $minutes): void` - Manually ban IP
- `unbanIp(string $ip): void` - Unban IP
- `isBanned(string $ip): bool` - Check if IP is banned
- `getBanInfo(string $ip): ?array` - Get ban information

**Examples:**

```php
// Manually ban IP for 1 hour
Route::banIp('192.168.1.100', 60);

// Unban IP
Route::unbanIp('192.168.1.100');

// Check if IP is banned
if (Route::isBanned('192.168.1.100')) {
    return response('IP is banned', 403);
}

// Get ban information
$banInfo = Route::getBanInfo('192.168.1.100');
if ($banInfo) {
    echo "Banned until: " . date('Y-m-d H:i:s', $banInfo['expires_at']);
}
```

---

#### 5. Ban statistics

**Method:** `getBanStats(): array`

**Description:** Getting ban statistics.

**Examples:**

```php
// Get ban stats
$stats = Route::getBanStats();

// Example output:
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

#### 6. Ban cleanup

**Method:** `cleanupExpiredBans(): int`

**Description:** Clean up expired bans.

**Examples:**

```php
// Clean up expired bans
$cleaned = Route::cleanupExpiredBans();
echo "Cleaned up $cleaned expired bans";

// Schedule cleanup (in cron job)
Route::cleanupExpiredBans();
```

---

#### 7. Ban whitelist

**Method:** `whitelistBanIp(string $ip): void`

**Description:** Whitelist IP from auto-ban.

**Examples:**

```php
// Whitelist trusted IPs
Route::whitelistBanIp('192.168.1.0/24');
Route::whitelistBanIp('10.0.0.0/8');

// Whitelist specific IPs
Route::whitelistBanIp('192.168.1.100');
Route::whitelistBanIp('10.0.0.50');
```

---

## Best Practices

### 1. Appropriate Limits

```php
// Login attempts - strict limits
Route::post('/login', $action)
    ->throttle(5, 1)
    ->autoBan(10, 60);

// API endpoints - moderate limits
Route::post('/api/data', $action)
    ->throttle(100, 1);

// Public endpoints - generous limits
Route::get('/api/public', $action)
    ->throttle(1000, 1);
```

### 2. User-Specific Limits

```php
// Different limits for different user types
Route::post('/api/action', $action)
    ->throttle(function($request) {
        $user = $request->user();
        if ($user->isPremium()) {
            return [1000, 1];
        }
        return [100, 1];
    });
```

### 3. Monitoring

```php
// Monitor throttle and ban stats
$throttleStats = Route::getThrottleStats();
$banStats = Route::getBanStats();

// Log suspicious activity
if ($throttleStats['blocked_requests'] > 100) {
    Log::warning('High number of blocked requests', $throttleStats);
}
```

---

## Common Patterns

### 1. API Protection

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

### 2. Admin Protection

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

### 3. Public API

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

## Performance Tips

### 1. Efficient Storage

```php
// Use Redis for better performance
Route::setThrottleStorage(new RedisStorage());

// Use file storage for simple setups
Route::setThrottleStorage(new FileStorage('/tmp/throttle'));
```

### 2. Cleanup Strategy

```php
// Regular cleanup
Route::cleanupExpiredBans();
Route::cleanupExpiredThrottles();

// Schedule cleanup in cron
// 0 * * * * php artisan route:cleanup
```

---

## Troubleshooting

### Common Issues

1. **Throttle not working**
   - Check throttle configuration
   - Verify storage is working
   - Check IP detection

2. **Auto-ban too aggressive**
   - Adjust ban thresholds
   - Add whitelist for trusted IPs
   - Monitor ban statistics

3. **Performance issues**
   - Use Redis storage
   - Implement cleanup strategy
   - Monitor resource usage

### Debug Tips

```php
// Enable debug mode
Route::enableDebug();

// Check throttle stats
$stats = Route::getThrottleStats();
var_dump($stats);

// Check ban stats
$banStats = Route::getBanStats();
var_dump($banStats);
```

---

## See Also

- [IP Filtering](05_IP_FILTERING.md) - IP-based access control
- [Middleware](06_MIDDLEWARE.md) - Request processing middleware
- [Security](20_SECURITY.md) - Security features overview
- [API Reference](../API_REFERENCE.md) - Complete API reference

---

© 2024 CloudCastle HTTP Router  
[⬆ Back to top](#rate-limiting--auto-ban)