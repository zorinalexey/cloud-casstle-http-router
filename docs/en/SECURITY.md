# Security Policy

[English](**SECURITY.md**) | [Русский](../../SECURITY.md) | [Deutsch](../de/SECURITY.md) | [Français](../fr/SECURITY.md) | [中文](../zh/SECURITY.md)

---

## Supported Versions

We provide security updates for the following versions:

| Version | Supported          |
| ------- | ------------------ |
| 1.1.x   | :white_check_mark: Yes |
| 1.0.x   | :white_check_mark: Yes |
| < 1.0   | :x: No             |

## Reporting Vulnerabilities

### How to Report

If you discover a security vulnerability in CloudCastle HTTP Router, please report it to us **confidentially**. We take all security issues seriously.

**DO NOT create public GitHub issues for security vulnerabilities.**

### Contact Methods

1. **Email:** zorinalexey59292@gmail.com
   - Subject: `[SECURITY] Description of the issue`
   - Include: version, vulnerability description, reproduction steps

2. **Telegram:** [@CloudCastle85](https://t.me/CloudCastle85)
   - For urgent cases

### What to Include in the Report

Please include the following information in your report:

- **Description** of the vulnerability
- **Steps to reproduce** the issue
- **Version** of the library
- **Potential impact** of the vulnerability
- **Suggested fix** (if any)
- **Your contact** for feedback

### What to Expect

1. **Confirmation of receipt** - within 24 hours
2. **Initial analysis** - within 48 hours
3. **Fix plan** - within 7 days
4. **Patch release** - depending on severity:
   - Critical: 1-3 days
   - High: 7-14 days
   - Medium: 14-30 days
   - Low: next release

### Disclosure Process

1. We confirm receipt of the report
2. We verify and assess the vulnerability
3. We develop a fix
4. We test the fix
5. We release the patch
6. We publish a security advisory
7. We thank the reporter (if they don't object)

## Built-in Protection

CloudCastle HTTP Router includes the following security measures:

### Attack Protection

✅ **Path Traversal Protection**
- Automatic path sanitization
- Blocking dangerous sequences (../, ./, \\)
- URI validation

✅ **SQL Injection Protection**
- Route parameter escaping
- Safe user input handling

✅ **XSS Protection**
- HTML entity encoding
- Dangerous character escaping
- Content Security Policy compatibility

✅ **IP Spoofing Protection**
- X-Forwarded-For header validation
- Real IP validation
- Spoofing protection

✅ **ReDoS Protection**
- Complex regex limitations
- Pattern matching timeouts
- Safe default patterns

✅ **Method Override Attack Protection**
- Controlled X-HTTP-Method-Override handling
- Optional activation
- Whitelist of allowed methods

✅ **Cache Injection Protection**
- Cache path validation
- Safe serialization
- Integrity checks

✅ **Resource Exhaustion Protection**
- Route count limitations
- Memory limits
- Optimized algorithms

✅ **Unicode Security**
- Proper multibyte character handling
- Unicode normalization
- Unicode exploit protection

### Additional Measures

✅ **Rate Limiting**
```php
$route->throttle(60, 1); // 60 requests per minute
```

✅ **IP Filtering**
```php
$route->whitelistIp(['192.168.1.0/24']);
$route->blacklistIp(['10.0.0.1']);
```

✅ **Auto-Ban System**
```php
$banManager->enableAutoBan(5); // Ban after 5 attempts
```

✅ **HTTPS Enforcement**
```php
$route->https(); // Require HTTPS
```

✅ **Domain Isolation**
```php
$router->group(['domain' => 'api.example.com'], function() {
    // Only for api.example.com
});
```

✅ **Port Isolation**
```php
$router->group(['port' => 8080], function() {
    // Only on port 8080
});
```

## Secure Usage Recommendations

### 1. Always use HTTPS in production

```php
// Force HTTPS for sensitive routes
$router->group(['https' => true], function() {
    $router->post('/login', $action);
    $router->post('/register', $action);
});
```

### 2. Restrict access to administrative routes

```php
$router->group([
    'prefix' => '/admin',
    'whitelistIp' => ['192.168.1.0/24'],
    'middleware' => [AuthMiddleware::class, AdminMiddleware::class]
], function() {
    // Admin panel
});
```

### 3. Use Rate Limiting on public endpoints

```php
// API endpoints
$router->get('/api/search', $action)->throttle(30, 1);
$router->post('/api/contact', $action)->throttle(5, 60);
```

### 4. Validate all input data

```php
$router->get('/users/{id}', $action)
    ->where('id', '[0-9]+'); // Only digits

$router->get('/posts/{slug}', $action)
    ->where('slug', '[a-z0-9-]+'); // Only safe characters
```

### 5. Use middleware for authentication

```php
$router->group(['middleware' => [AuthMiddleware::class]], function() {
    // Protected routes
});
```

### 6. Regularly update the library

```bash
composer update cloud-castle/http-router
```

### 7. Monitor suspicious activity

```php
$router->registerPlugin(new SecurityLoggerPlugin());
```

### 8. Use auto-blocking

```php
$banManager = new BanManager();
$banManager->enableAutoBan(5); // Ban after 5 failed attempts
$banManager->setAutoBanDuration(3600); // For 1 hour
```

## Known Limitations

### PHP Version

- Requires PHP 8.2+
- Older PHP versions are not supported and may have vulnerabilities

### Dependencies

- Regularly update PSR dependencies
- Monitor security advisories

### Server Configuration

The router is not responsible for:
- Web server configuration (nginx, Apache)
- PHP-FPM settings
- Firewall rules
- SSL/TLS certificates

Make sure your server is properly configured.

## Security Checklist

Before deploying to production:

- [ ] HTTPS enabled
- [ ] Rate Limiting configured
- [ ] IP filtering for admin
- [ ] All parameters validated
- [ ] Authentication middleware
- [ ] Logging enabled
- [ ] Monitoring configured
- [ ] Security updates applied
- [ ] Passwords and tokens in .env
- [ ] Debug mode disabled
- [ ] Error reporting configured
- [ ] Backup system working

## Hall of Fame

We thank the following researchers for responsible vulnerability disclosure:

*(Empty for now - you can be the first!)*

## Contacts

- **Security Email:** zorinalexey59292@gmail.com
- **Telegram:** [@CloudCastle85](https://t.me/CloudCastle85)
- **GitHub:** [github.com/zorinalexey/cloud-casstle-http-router](https://github.com/zorinalexey/cloud-casstle-http-router)

---

**Thank you for helping secure CloudCastle HTTP Router!**

---

Last updated: 2024-12-20
