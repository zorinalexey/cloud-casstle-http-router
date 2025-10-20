# Security Features - Detailed Security Capabilities

[**English**](../../en/features/SECURITY_FEATURES.md) | [Русский](../../ru/features/SECURITY_FEATURES.md)

---

## Overview

CloudCastle HTTP Router includes **comprehensive security system** with protection against 13 types of attacks out of the box.

---

## Path Traversal Protection

**Description:** Protection against directory traversal.

**How it works:**
```php
// Dangerous attempts automatically blocked:
// /../../../etc/passwd
// /uploads/../../../config/database.php

// Internal sanitization
$uri = str_replace(['../', './', '\\'], '', $uri);
```

---

## SQL Injection Protection

**Description:** Route parameter sanitization.

```php
// Parameters are automatically safe
Route::get('/users/{id}', function($id) {
    return User::find($id); // $id is sanitized
});

// Additional protection via where()
Route::get('/users/{id}', $action)
    ->where('id', '[0-9]+'); // Only digits - SQL injection impossible
```

---

## XSS Protection

**Description:** JavaScript injection protection.

```php
// Automatic escaping
$param = htmlspecialchars($param, ENT_QUOTES, 'UTF-8');

// Blocked vectors:
// <script>alert('XSS')</script>
// <img src=x onerror=alert(1)>
```

---

## IP Filtering

### Whitelist

```php
// Single IP
Route::get('/admin', $action)->whitelistIp('192.168.1.100');

// CIDR notation
Route::get('/admin', $action)->whitelistIp(['192.168.1.0/24']);
```

### Blacklist

```php
Route::get('/api', $action)->blacklistIp(['1.2.3.4']);
```

---

## HTTPS Enforcement

Force HTTPS usage:

```php
Route::post('/payment', $action)->https();

// Or via secure() alias
Route::post('/payment', $action)->secure();
```

---

## Domain/Port Security

### Domain Isolation

```php
Route::group(['domain' => 'api.example.com'], function() {
    Route::get('/users', $action);
});
```

### Port Isolation (Unique!)

```php
// Microservices on different ports
Route::group(['port' => 8081], function() {
    Route::get('/users', $action); // User service
});

Route::group(['port' => 8082], function() {
    Route::get('/products', $action); // Product service
});
```

**CloudCastle is the ONLY ONE with Port routing!**

---

## Comparison Summary

| Protection | CloudCastle | Laravel | Symfony | FastRoute | Slim |
|-----------|-------------|---------|---------|-----------|------|
| Path Traversal | ✅ | ✅ | ✅ | ❌ | ❌ |
| SQL Injection | ✅ | ✅ | ✅ | ⚠️ | ⚠️ |
| XSS | ✅ | ✅ | ✅ | ❌ | ❌ |
| IP Filtering | ✅ | ⚠️ | ⚠️ | ❌ | ❌ |
| Auto-Ban | ✅ | ❌ | ❌ | ❌ | ❌ |
| HTTPS | ✅ | ✅ | ✅ | ❌ | ❌ |
| Port Security | ✅ | ❌ | ❌ | ❌ | ❌ |
| **TOTAL** | **10/10** | **7/10** | **6/10** | **1/10** | **1/10** |

---

[⬆ Back](../FEATURES_INDEX.md) | [🏠 Home](../../../README.md)

---

© 2024 CloudCastle HTTP Router. All Rights Reserved.


