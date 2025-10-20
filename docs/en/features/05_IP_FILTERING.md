# IP Filtering

[**English**](05_IP_FILTERING.md) | [Русский](../../ru/features/05_IP_FILTERING.md) | [Deutsch](../../de/features/05_IP_FILTERING.md) | [Français](../../fr/features/05_IP_FILTERING.md) | [中文](../../zh/features/05_IP_FILTERING.md)

---

## 📚 Documentation Navigation

[README](../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [API_REFERENCE](../API_REFERENCE.md) | [ALL_FEATURES](../ALL_FEATURES.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [PERFORMANCE](../PERFORMANCE_ANALYSIS.md) | [SECURITY](../SECURITY_REPORT.md) | [COMPARISON](../COMPARISON.md) | [FAQ](../FAQ.md)

**Detailed Documentation:** [01](01_BASIC_ROUTING.md) | [02](02_ROUTE_PARAMETERS.md) | [03](03_ROUTE_GROUPS.md) | [04](04_RATE_LIMITING.md) | [05](05_IP_FILTERING.md) | [06](06_MIDDLEWARE.md) | [07](07_NAMED_ROUTES.md) | [08](08_TAGS.md) | [09](09_HELPER_FUNCTIONS.md) | [10](10_ROUTE_SHORTCUTS.md) | [11](11_ROUTE_MACROS.md) | [12](12_URL_GENERATION.md) | [13](13_EXPRESSION_LANGUAGE.md) | [14](14_CACHING.md) | [15](15_PLUGINS.md) | [16](16_LOADERS.md) | [17](17_PSR_SUPPORT.md) | [18](18_ACTION_RESOLVER.md) | [19](19_STATISTICS.md) | [20](20_SECURITY.md) | [21](21_EXCEPTIONS.md) | [22](22_CLI_TOOLS.md)

---

**Category:** Security  
**Number of Methods:** 4  
**Complexity:** ⭐⭐ Intermediate Level

---

## Description

IP Filtering allows you to control access to routes based on client IP addresses. Supports whitelist (only allowed) and blacklist (only denied), including CIDR notation for subnets.

## Methods

### 1. whitelistIp()

**Method:** `whitelistIp(array $ips): Route`

**Description:** Allow access only from specified IP addresses.

**Examples:**

```php
// Single IP
Route::get('/admin', $action)
    ->whitelistIp(['192.168.1.1']);

// Multiple IPs
Route::get('/admin', $action)
    ->whitelistIp([
        '192.168.1.1',
        '192.168.1.2',
        '10.0.0.100'
    ]);

// CIDR notation (subnet)
Route::get('/admin', $action)
    ->whitelistIp([
        '192.168.1.0/24',    // 192.168.1.0 - 192.168.1.255
        '10.0.0.0/8'         // 10.0.0.0 - 10.255.255.255
    ]);

// Office network
Route::get('/internal', $action)
    ->whitelistIp(['192.168.0.0/16']);
```

### 2. blacklistIp()

**Method:** `blacklistIp(array $ips): Route`

**Description:** Deny access from specified IP addresses.

**Examples:**

```php
// Block specific IPs
Route::get('/public', $action)
    ->blacklistIp(['1.2.3.4', '5.6.7.8']);

// CIDR
Route::get('/api/data', $action)
    ->blacklistIp(['1.2.3.0/24']);

// From database
$bannedIps = DB::table('banned_ips')->pluck('ip')->toArray();
Route::get('/api/data', $action)
    ->blacklistIp($bannedIps);
```

### 3. CIDR Support

**Format:** `IP/MASK`

**Examples:**

```php
// /32 - single IP
Route::get('/test', $action)->whitelistIp(['192.168.1.1/32']);

// /24 - subnet 256 addresses
Route::get('/test', $action)->whitelistIp(['192.168.1.0/24']);

// /16 - 65,536 addresses
Route::get('/test', $action)->whitelistIp(['192.168.0.0/16']);

// /8 - 16,777,216 addresses
Route::get('/test', $action)->whitelistIp(['10.0.0.0/8']);
```

### 4. IP Spoofing Protection

**Description:** Automatic verification of X-Forwarded-For and other headers.

CloudCastle HTTP Router automatically:
- Checks `X-Forwarded-For`
- Checks `X-Real-IP`
- Protects against IP spoofing

## Complete Examples

### Admin Panel

```php
Route::group([
    'prefix' => '/admin',
    'middleware' => [AuthMiddleware::class, AdminMiddleware::class],
    'https' => true,
    'whitelistIp' => ['192.168.1.0/24']  // Only office
], function() {
    Route::get('/dashboard', [AdminController::class, 'dashboard']);
    Route::get('/users', [AdminController::class, 'users']);
    
    // Critical endpoint - even stricter protection
    Route::post('/settings/critical', [AdminController::class, 'critical'])
        ->whitelistIp(['192.168.1.100']);  // Only one IP
});
```

### Internal API

```php
Route::group([
    'prefix' => '/api/internal',
    'whitelistIp' => [
        '10.0.1.100',  // App Server 1
        '10.0.1.101',  // App Server 2
        '10.0.1.102'   // App Server 3
    ]
], function() {
    Route::post('/webhook', [WebhookController::class, 'handle']);
    Route::post('/sync', [SyncController::class, 'sync']);
});
```

### Public API with Blacklist

```php
// Blocked IP ranges
$blockedRanges = [
    '1.2.3.0/24',    // Known bot network
    '5.6.7.0/24',    // Spam source
    '123.45.67.89'   // Abusive IP
];

Route::group([
    'prefix' => '/api/public',
    'blacklistIp' => $blockedRanges
], function() {
    Route::get('/data', [ApiController::class, 'data']);
    Route::get('/stats', [ApiController::class, 'stats']);
});
```

## Best Practices

### 1. Whitelist for Sensitive Routes

```php
// Always use whitelist for admin/internal routes
Route::group(['prefix' => '/admin'], function() {
    // All admin routes
})->whitelistIp(['192.168.1.0/24']);
```

### 2. Environment-based Configuration

```php
$allowedIps = config('app.admin_ips', ['127.0.0.1']);

Route::group([
    'prefix' => '/admin',
    'whitelistIp' => $allowedIps
], function() {
    // Admin routes
});
```

### 3. Combine with Other Security

```php
Route::group([
    'prefix' => '/admin',
    'middleware' => [AuthMiddleware::class, AdminMiddleware::class],
    'whitelistIp' => ['192.168.1.0/24'],
    'throttle' => [100, 1],
    'https' => true
], function() {
    // Multiple layers of security
});
```

## Troubleshooting

### Common Issues

1. **Access denied despite correct IP**
   - Check if behind proxy/load balancer
   - Verify X-Forwarded-For header
   - Check CIDR notation

2. **CIDR not working**
   - Verify notation format
   - Check subnet calculations
   - Test with single IP first

3. **Proxy/Load Balancer**
   - Configure trusted proxies
   - Check X-Forwarded-For handling
   - Verify IP detection

### Debug Tips

```php
// Log actual IP
Route::get('/debug-ip', function() {
    return [
        'REMOTE_ADDR' => $_SERVER['REMOTE_ADDR'],
        'X-Forwarded-For' => $_SERVER['HTTP_X_FORWARDED_FOR'] ?? null,
        'X-Real-IP' => $_SERVER['HTTP_X_REAL_IP'] ?? null
    ];
});
```

## See Also

- [Rate Limiting](04_RATE_LIMITING.md) - Rate limiting and auto-ban
- [Security](20_SECURITY.md) - Security features overview
- [Middleware](06_MIDDLEWARE.md) - Request processing middleware
- [API Reference](../API_REFERENCE.md) - Complete API reference

---

© 2024 CloudCastle HTTP Router  
[⬆ Back to top](#ip-filtering)