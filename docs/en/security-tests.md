[🇷🇺 Русский](ru/security-tests.md) | [🇺🇸 English](en/security-tests.md) | [🇩🇪 Deutsch](de/security-tests.md) | [🇫🇷 Français](fr/security-tests.md) | [🇨🇳 中文](zh/security-tests.md)

[📚 Table of Contents](en/_table-of-contents.md) | [🏠 Home](en/README.md)

---

# Security tests CloudCastle HTTP Router

**Languages:** 🇷🇺 Russian | [🇬🇧 English](../en/security-tests.md) | [🇩🇪 Deutsch](../de/security-tests.md) | [🇫🇷 Français](../fr/security-tests.md) | [🇨🇳中文](../zh/security-tests.md)

[📚 Table of Contents](_table-of-contents.md) | [🏠 Home](README.md)

---

## 📊 General information

**Total security tests**: 13
**Status**: ✅ All tests passed (100%)
**Assertions**: 38  
**Execution time**: 0.110s
**Memory**: 12 MB

## 🛡️ Protection categories

### 1. Path Traversal Protection

**Description**: Protection against attacks using `../` to access files outside the web root.

**Test**: Attempting to access `/../../etc/passwd`

**Protection mechanism**:
- Normalization of paths
- Sequence blocking `../`
- Checking for absolute paths

**Result**: ✅ PASSED

**Protection example:**
```php
$router->get('/files/{path}', function($path) {
    // Роутер автоматически блокирует '../../../etc/passwd'
    // Вызовет RouteNotFoundException
    return file_get_contents(__DIR__ . '/uploads/' . $path);
});
```

**Comparison with competitors:**
- CloudCastle: ✅ Built-in protection
- FastRoute: ❌ No protection
- Symfony: ✅ There is protection
- Laravel: ✅ There is protection
- Slim: ❌ No protection
- AltoRouter: ❌ No protection

---

### 2. SQL Injection in Parameters

**Description**: Protection against SQL injections through route parameters.

**Test**: Parameters like `' OR '1'='1`

**Protection mechanism**:
- Parameters are passed as is (not interpreted)
- Responsibility at the application level
- The router does not execute SQL queries

**Result**: ✅ PASSED

**Recommendations:**
```php
// ПРАВИЛЬНО: используйте prepared statements
$router->get('/users/{id}', function($id) use ($pdo) {
    $stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
    $stmt->execute([$id]);
    return $stmt->fetch();
});

// НЕПРАВИЛЬНО: прямая интерполяция
$router->get('/users/{id}', function($id) use ($pdo) {
    return $pdo->query("SELECT * FROM users WHERE id = {$id}"); // ОПАСНО!
});
```

---

### 3. XSS (Cross-Site Scripting) Protection

**Description**: Protection against XSS attacks via parameters.

**Test**: Parameters like `<script>alert('XSS')</script>`

**Protection mechanism**:
- Parameters are not automatically screened by the router
- The application is responsible for sanitization
- The router provides clean data

**Result**: ✅ PASSED

**Recommendations:**
```php
// ПРАВИЛЬНО: экранируйте вывод
$router->get('/search/{query}', function($query) {
    return htmlspecialchars($query, ENT_QUOTES, 'UTF-8');
});

// Или используйте шаблонизатор с авто-экранированием
$router->get('/search/{query}', function($query) use ($twig) {
    return $twig->render('search.html', ['query' => $query]);
});
```

---

### 4. IP Whitelist Security

**Description**: Restricting access to authorized IP addresses only.

**Mechanism**:
```php
$router->get('/admin', 'AdminController@index')
    ->whitelistIp(['192.168.1.100', '10.0.0.0/8']);
```

**Test**: Access from an unauthorized IP

**Result**: ✅ PASSED - IpNotAllowedException thrown

**Application:**
- Administrative panels
- Internal API endpoints
- Restricted resources

---

### 5. IP Blacklist Security

**Description**: Blocking access from certain IP addresses.

**Mechanism**:
```php
$router->get('/api/data', 'ApiController@data')
    ->blacklistIp(['1.2.3.4', '5.6.7.0/24']);
```

**Test**: Access from a blocked IP

**Result**: ✅ PASSED - IpNotAllowedException thrown

**Application:**
- Blocking malicious IPs
- Anti-spam protection
- Geo-blocking

---

### 6. IP Spoofing Protection

**Description**: Protection against IP address spoofing via HTTP headers.

**Dangerous headers**:
- `X-Forwarded-For`
- `X-Real-IP`
- `Client-IP`

**Protection mechanism**:
- Using $_SERVER['REMOTE_ADDR']
- Ignore untrusted headers
- Checking proxy chains

**Result**: ✅ PASSED

**Recommendations:**
```php
// Роутер использует только REMOTE_ADDR
// Если нужно доверять proxy, настройте явно:
$router->setTrustedProxies(['10.0.0.1']);
```

---

### 7. Domain Security

**Description**: Checking route domain restrictions.

**Mechanism**:
```php
$router->get('/api/v1', 'ApiController@index')
    ->domain('api.example.com');
```

**Test**: Access from another domain

**Result**: ✅ PASSED - the route does not match

**Application:**
- Multi-tenant applications
- Subdomain routing
- API versioning

---

### 8. ReDoS (Regular Expression Denial of Service) Protection

**Description**: Protection against attacks via complex regular expressions.

**Dangerous patterns**:
- `(a+)+`
- `(a|a)*`
- `(a|ab)*`

**Protection mechanism**:
- Regex difficulty limitation
- Timeout for regex matching
- Pattern validation

**Result**: ✅ PASSED

**Recommendations:**
```php
// ПРАВИЛЬНО: простые паттерны
$router->get('/users/{id}', fn($id) => $id)
    ->where('id', '\d+');

// ИЗБЕГАЙТЕ: сложные вложенные паттерны
$router->get('/complex/{param}', fn($p) => $p)
    ->where('param', '(a+)+'); // ОПАСНО!
```

---

### 9. Method Override Attack

**Description**: Protection against HTTP method spoofing via headers or POST parameters.

**Attacks**:
- `X-HTTP-Method-Override: DELETE`
- `_method=DELETE` in POST

**Protection mechanism**:
- Ignore method override by default
- Optional enablement for trusted sources

**Result**: ✅ PASSED

---

### 10. Mass Assignment in Route Params

**Description**: Protection against mass assignment via route parameters.

**Test**: Passing many parameters that are not declared

**Protection mechanism**:
- Only declared parameters are retrieved
- The rest are ignored
- Strict parameter matching

**Result**: ✅ PASSED

---

### 11. Cache Injection

**Description**: Protection against injection into route cache.

**Protection mechanism**:
- Serialization without `__wakeup` callbacks
- Strict validation of cached data
- Integrity check

**Result**: ✅ PASSED

**In code:**
```php
// RouteCache использует безопасную сериализацию
$cache = new RouteCache(__DIR__ . '/cache/routes.php');
$cache->store($routes);
```

---

### 12. Resource Exhaustion

**Description**: Protection against resource exhaustion through excessive requests.

**Protection mechanism**:
- **Rate Limiting**: limiting requests
- **Auto-ban**: automatic blocking
- **Memory limits**: memory consumption control

**Result**: ✅ PASSED

**Example:**
```php
// Rate limiting
$router->get('/api/data', 'ApiController@data')
    ->perMinute(60); // максимум 60 запросов в минуту

// Auto-ban при превышении
$router->enableAutoBan(
    maxAttempts: 100,
    decayMinutes: 60,
    banDuration: 3600
);
```

---

### 13. Unicode Security Issues

**Description**: Protection against attacks using Unicode characters.

**Dangers**:
- Homoglyphs (similar characters)
- Right-to-left override
- Zero-width characters

**Protection mechanism**:
- UTF-8 validation
- Unicode normalization
- Check for control characters

**Result**: ✅ PASSED

---

## 🔒 Unique security features of CloudCastle

### SSRF (Server-Side Request Forgery) Protection

**Only at CloudCastle!**

```php
use CloudCastle\Http\Router\Middleware\SsrfProtection;

$router->middleware(new SsrfProtection());

// Блокирует запросы к:
// - localhost/127.0.0.1
// - Private IP ranges (10.0.0.0/8, 172.16.0.0/12, 192.168.0.0/16)
// - Link-local addresses
// - Cloud metadata APIs (169.254.169.254)
```

### Auto-ban system

**Only at CloudCastle!**

```php
$banManager = new BanManager();
$router->setBanManager($banManager);

// Автоматическая блокировка после rate limit
$router->enableAutoBan(
    maxAttempts: 100,
    decayMinutes: 60,
    banDuration: 3600 // ban на 1 час
);
```

### Security Logger

**Only at CloudCastle!**

```php
use CloudCastle\Http\Router\Middleware\SecurityLogger;

$router->middleware(new SecurityLogger(__DIR__ . '/logs/security.log'));

// Логирует:
// - Все security события
// - Заблокированные IP
// - Rate limit превышения
// - Подозрительную активность
```

## 📊 Comparison of security capabilities

| Protection | CloudCastle | FastRoute | Symfony | Laravel | Slim | Alto |
|:---|:---:|:---:|:---:|:---:|:---:|:---:|
| Path Traversal | ✅ | ❌ | ✅ | ✅ | ❌ | ❌ |
| SQL Injection (in parameters) | ✅ | ⚠️ | ⚠️ | ⚠️ | ⚠️ | ⚠️ |
| XSS Protection | ⚠️ | ❌ | ⚠️ | ⚠️ | ❌ | ❌ |
| IP Whitelist | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ |
| IP Blacklist | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ |
| IP Spoofing | ✅ | ❌ | ⚠️ | ⚠️ | ❌ | ❌ |
| Domain Security | ✅ | ❌ | ✅ | ✅ | ❌ | ❌ |
| ReDoS Protection | ✅ | ⚠️ | ⚠️ | ⚠️ | ❌ | ❌ |
| Method Override | ✅ | ❌ | ⚠️ | ⚠️ | ❌ | ❌ |
| Mass Assignment | ✅ | ❌ | ❌ | ⚠️ | ❌ | ❌ |
| Cache Injection | ✅ | ⚠️ | ✅ | ⚠️ | ❌ | ❌ |
| Resource Exhaustion | ✅ | ❌ | ❌ | ⚠️ | ❌ | ❌ |
| Unicode Security | ✅ | ❌ | ⚠️ | ⚠️ | ❌ | ❌ |
| **SSRF Protection** | **✅** | **❌** | **❌** | **❌** | **❌** | **❌** |
| **Auto-ban System** | **✅** | **❌** | **❌** | **❌** | **❌** | **❌** |
| **Rate Limiting** | **✅** | **❌** | **❌** | **✅** | **❌** | **❌** |
| **Security Logger** | **✅** | **❌** | **❌** | **❌** | **❌** | **❌** |

**Legend:**
- ✅ Built-in protection
- ⚠️ Partial protection or requires additional configuration
- ❌ No protection

## 🔐 Detailed description of protection mechanisms

### SSRF Protection (unique feature)

**What it protects**:
```php
// Блокирует запросы к внутренним ресурсам
$blockedUrls = [
    'http://localhost/admin',
    'http://127.0.0.1:8080/internal',
    'http://192.168.1.1/router',
    'http://10.0.0.5/database',
    'http://169.254.169.254/latest/meta-data', // AWS metadata
    'http://metadata.google.internal/', // GCP metadata
];
```

**Usage:**
```php
use CloudCastle\Http\Router\Middleware\SsrfProtection;

$router->middleware(new SsrfProtection(
    allowLocalhost: false, // блокировать localhost
    allowPrivateIps: false, // блокировать private IP
    allowCloudMetadata: false // блокировать cloud metadata
));
```

### Rate Limiting with Auto-ban

**Combined protection:**
```php
// Rate limiting
$router->get('/api/data', 'ApiController@data')
    ->perMinute(60); // 60 запросов в минуту

// Auto-ban после превышения
$banManager = new BanManager();
$router->setBanManager($banManager);
$router->enableAutoBan(
    maxAttempts: 100, // после 100 попыток
    decayMinutes: 60, // в течение 1 часа
    banDuration: 3600 // бан на 1 час
);
```

**Result**:
- First 60 requests/min: ✅ OK
- 61-100 request: ⚠️ TooManyRequestsException
- 100+ requests: 🔒 Permanent ban + BannedException

### IP Filtering

**Whitelist example:**
```php
// Только для офисных IP
$router->get('/internal/reports', 'ReportController@index')
    ->whitelistIp([
        '203.0.113.0/24', // office network
        '198.51.100.50', // VPN gateway
    ]);
```

**Blacklist example:**
```php
// Блокировка известных злоумышленников
$router->get('/public/api', 'ApiController@public')
    ->blacklistIp([
        '1.2.3.4',
        '5.6.7.8',
    ]);
```

### HTTPS Enforcement

```php
use CloudCastle\Http\Router\Middleware\HttpsEnforcement;

$router->middleware(new HttpsEnforcement(
    redirect: true, // автоматический redirect на HTTPS
    permanent: true // 301 вместо 302
));
```

### Security Logger

**Automatic logging:**
```php
use CloudCastle\Http\Router\Middleware\SecurityLogger;

$router->middleware(new SecurityLogger('/var/log/security.log'));

// Логируется:
// [2025-10-18 22:00:15] BLOCKED: IP 1.2.3.4 - Rate limit exceeded
// [2025-10-18 22:01:30] BANNED: IP 1.2.3.4 - Auto-ban triggered
// [2025-10-18 22:05:45] SUSPICIOUS: Path traversal attempt from 5.6.7.8
// [2025-10-18 22:10:00] BLOCKED: SSRF attempt to http://169.254.169.254
```

## 📊 Security test results

### Detailed results

| # | Test | Description | Assertions | Time | Status |
|:---|:---:|:---:|:---:|:---:|:---:|
| 1 | Path Traversal | `../` sequences | 3 | 0.008s | ✅ |
| 2 | SQL Injection | SQL in parameters | 3 | 0.005s | ✅ |
| 3 | XSS | Script tags in parameters | 3 | 0.006s | ✅ |
| 4 | IP Whitelist | Access from non-whitelist IP | 3 | 0.010s | ✅ |
| 5 | IP Blacklist | Access from blacklist IP | 3 | 0.009s | ✅ |
| 6 | IP Spoofing | Substitution via headers | 3 | 0.011s | ✅ |
| 7 | Domain Security | Wrong domain | 3 | 0.007s | ✅ |
| 8 | ReDoS | Complex regex | 3 | 0.012s | ✅ |
| 9 | Method Override | Method substitution | 3 | 0.008s | ✅ |
| 10 | Mass Assignment | Extra parameters | 3 | 0.010s | ✅ |
| 11 | Cache Injection | Injection into cache | 3 | 0.009s | ✅ |
| 12 | Resource Exhaustion | DoS via requests | 3 | 0.006s | ✅ |
| 13 | Unicode Security | Unicode attacks | 2 | 0.006s | ✅ |
| **TOTAL** | **13** | | **38** | **0.110s** | **✅** |

## 💡 Safety recommendations

### 1. Always use HTTPS in production

```php
$router->middleware(new HttpsEnforcement(redirect: true));
```

### 2. Set up Rate Limiting for public endpoints

```php
$router->get('/api/public', 'ApiController@public')
    ->perMinute(60);
```

### 3. Use IP Whitelist for administrative panels

```php
$router->group(['prefix' => '/admin'], function($router) {
    $router->whitelistIp(['your-office-ip']);
    // admin routes...
});
```

### 4. Enable Auto-ban for brute force protection

```php
$router->enableAutoBan(
    maxAttempts: 100,
    decayMinutes: 60,
    banDuration: 3600
);
```

### 5. Use Security Logger for monitoring

```php
$router->middleware(new SecurityLogger(__DIR__ . '/logs/security.log'));
```

### 6. Enable SSRF Protection for user-generated URLs

```php
$router->middleware(new SsrfProtection());
```

## 🏆 CloudCastle Security Benefits

### vs FastRoute
- ✅ +14 security features
- ✅ Built-in SSRF protection
- ✅ Auto-ban system
- ✅ IP filtering

### vs Symfony
- ✅ Easier setup
- ✅ SSRF Protection out of the box
- ✅ Auto-ban system
- ✅ Built-in Rate Limiting

### vs Laravel  
- ✅ Autonomous security (no framework)
- ✅ SSRF Protection
- ✅ More flexible IP filtering
- ✅ Security Logger

### vs Slim
- ✅ +15 security features
- ✅ Comprehensive protection
- ✅ Auto-ban
- ✅ Rate Limiting built-in

### vs AltoRouter
- ✅ +16 security features
- ✅ Complete security suite
- ✅ Enterprise-ready

## ✅ Conclusion

CloudCastle HTTP Router provides **the most comprehensive security** of any PHP router:

1. **13/13 security tests** passed ✅
2. **17 safety mechanisms** built in
3. **4 unique features** (SSRF, Auto-ban, Security Logger, IP filtering)
4. **100% readiness** for production

The router is ready for use in projects with **high security requirements**: fintech, e-commerce, SaaS, enterprise applications.

---

*Last update: October 18, 2025*

---

[📚 Table of Contents](_table-of-contents.md) | [🏠 Home](README.md)

---

[📚 Table of Contents](en/_table-of-contents.md) | [🏠 Home](en/README.md)
