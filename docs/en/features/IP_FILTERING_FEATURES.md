# IP Filtering & Auto-Ban Features

**English** | [Русский](../../ru/features/IP_FILTERING_FEATURES.md) | [Deutsch](../../de/features/IP_FILTERING_FEATURES.md) | [Français](../../fr/features/IP_FILTERING_FEATURES.md) | [中文](../../zh/features/IP_FILTERING_FEATURES.md)

---









## Overview

Built-in IP filtering with whitelist, blacklist, CIDR support, and **unique Auto-Ban system**.

---

## IP Whitelist

Only specified IPs can access:

```php
// Single IP
Route::get('/admin', $action)->whitelistIp('192.168.1.100');

// Multiple IPs
Route::get('/admin', $action)->whitelistIp(['192.168.1.100', '192.168.1.101']);

// CIDR notation
Route::get('/admin', $action)->whitelistIp(['192.168.1.0/24']);
```

---

## IP Blacklist

Block specific IPs:

```php
Route::get('/api', $action)->blacklistIp(['1.2.3.4', '5.6.7.0/24']);
```

---

## Auto-Ban System (Unique!)

Automatically ban IPs that exceed rate limits:

```php
use CloudCastle\Http\Router\BanManager;

$banManager = new BanManager();
$banManager->enableAutoBan(5); // Ban after 5 violations

// Manual ban
$banManager->ban('1.2.3.4', 3600); // Ban for 1 hour

// Check if banned
if ($banManager->isBanned('1.2.3.4')) {
    throw new BannedException();
}

// Unban
$banManager->unban('1.2.3.4');
```

---

## Integration with Throttling

```php
Route::post('/login', $action)
    ->throttleWithBan(5, 60, 3, 3600);
// Ban after 3 violations for 1 hour
```

---

## Shortcuts

```php
Route::get('/debug', $action)->localhost();
// Equivalent to: whitelistIp(['127.0.0.1', '::1'])
```

---

## Comparison

| Feature | CloudCastle | Laravel | Symfony | FastRoute | Slim |
|---------|-------------|---------|---------|-----------|------|
| **IP Whitelist** | ✅ Built-in | ⚠️ Middleware | ⚠️ Component | ❌ | ❌ |
| **IP Blacklist** | ✅ Built-in | ⚠️ Middleware | ⚠️ Component | ❌ | ❌ |
| **CIDR** | ✅ | ⚠️ Manual | ✅ | ❌ | ❌ |
| **Auto-Ban** | ✅ **Unique!** | ❌ | ❌ | ❌ | ❌ |
| **BanManager** | ✅ | ❌ | ❌ | ❌ | ❌ |

**CloudCastle is the ONLY router with Auto-Ban system!**

---

[⬆ Back](../FEATURES_INDEX.md) | [🏠 Home](../../../README.md)

---

© 2024 CloudCastle HTTP Router. All Rights Reserved.


