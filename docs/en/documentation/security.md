# Security

**CloudCastle HTTP Router v1.1.1**  
**Language**: English

---

## 🌍 Translations

- [Русский](../../ru/documentation/security.md)
- **[English](security.md)** (current)
- [Deutsch](../../de/documentation/security.md)
- [Français](../../fr/documentation/security.md)

---

## 🛡️ Security Overview

CloudCastle Router provides comprehensive application protection with built-in security mechanisms.

**Test Results**: 13/13 security tests passed ✅  
**Compliance**: OWASP Top 10 ✅

---

## 🔒 IP Filtering

### Whitelist

```php
Route::get('/admin', 'AdminController@index')
    ->whitelistIp(['192.168.1.0/24', '10.0.0.1']);
```

**Supported formats**:
- Single IP: `192.168.1.100`
- CIDR notation: `192.168.1.0/24`
- Array of IPs: `['10.0.0.1', '10.0.0.2']`

### Blacklist

```php
Route::get('/public', 'PublicController@index')
    ->blacklistIp(['1.2.3.4', '5.6.7.8']);
```

---

## 🔐 HTTPS Enforcement

### Force HTTPS

```php
Route::post('/login', 'AuthController@login')->https();
Route::post('/payment', 'PaymentController@process')->https();
```

### HTTPS Middleware

```php
use CloudCastle\Http\Router\Middleware\HttpsEnforcement;

Route::post('/secure', 'Controller@secure')
    ->middleware(new HttpsEnforcement(redirectToHttps: true));
```

---

## 🛡️ Built-in Middleware

### 1. HTTPS Enforcement

```php
use CloudCastle\Http\Router\Middleware\HttpsEnforcement;

Route::post('/login', 'AuthController@login')
    ->middleware(new HttpsEnforcement(redirectToHttps: true));
```

### 2. SSRF Protection

```php
use CloudCastle\Http\Router\Middleware\SsrfProtection;

Route::post('/fetch-url', 'Controller@fetchUrl')
    ->middleware(new SsrfProtection());
```

**Protects from**:
- ✅ Requests to localhost
- ✅ Requests to private IPs
- ✅ Requests to metadata endpoints (AWS, GCP)
- ✅ File:// protocol

### 3. Security Logger

```php
use CloudCastle\Http\Router\Middleware\SecurityLogger;

Route::post('/sensitive', 'Controller@sensitive')
    ->middleware(new SecurityLogger('/var/log/security.log'));
```

---

## 🎯 Maximum Security

```php
Route::post('/admin/critical', 'AdminController@critical')
    ->https()
    ->domain('admin.example.com')
    ->whitelistIp(['192.168.1.0/24'])
    ->middleware(['auth', 'admin', '2fa'])
    ->throttleWithBan(3, 60, 1, 86400);
```

---

## 📊 Security Tests

**13 security tests passed**:

✅ Path Traversal Protection  
✅ SQL Injection Protection  
✅ XSS Protection  
✅ IP Whitelist Security  
✅ IP Blacklist Security  
✅ IP Spoofing Protection  
✅ Domain Security  
✅ ReDoS Protection  
✅ Method Override Attack  
✅ Mass Assignment Protection  
✅ Cache Injection Protection  
✅ Resource Exhaustion Protection  
✅ Unicode Security

[Detailed report →](../../reports/security.md)

---

## 🔗 See Also

- [Rate Limiting](rate-limiting.md)
- [Auto-ban](auto-ban.md)
- [Security Report](../../reports/security.md)

---

**[← Back to contents](README.md)**

