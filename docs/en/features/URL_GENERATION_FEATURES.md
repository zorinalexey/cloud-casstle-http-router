# URL Generation Features

**English** | [Русский](../ru/features/URL_GENERATION_FEATURES.md) | [Deutsch](../de/features/URL_GENERATION_FEATURES.md) | [Français](../fr/features/URL_GENERATION_FEATURES.md) | [中文](../zh/features/URL_GENERATION_FEATURES.md)

---





## Overview

Powerful URL generation for named routes with parameters, query strings, and signed URLs.

---

## Basic Generation

```php
use CloudCastle\Http\Router\UrlGenerator;

$generator = new UrlGenerator($router);

// Simple URL
$url = $generator->generate('users.index'); // /users

// With parameters
$url = $generator->generate('users.show', ['id' => 123]); // /users/123
```

---

## Absolute URLs

```php
// Absolute URL
$url = $generator->absolute('users.show', ['id' => 1]);
// https://example.com/users/1

// Custom domain
$url = $generator->toDomain('users.show', ['id' => 1], 'api.example.com');
// https://api.example.com/users/1

// Custom protocol
$url = $generator->toProtocol('users.show', ['id' => 1], 'http');
// http://example.com/users/1
```

---

## Signed URLs

Secure access to protected resources:

```php
// Create signed URL (expires in 1 hour)
$url = $generator->signed('download.file', ['id' => 123], 3600);
// /download/123?signature=abc123&expires=1234567890

// Permanent signed URL
$url = $generator->signed('download.file', ['id' => 123]);

// Verify signature
if ($generator->hasValidSignature($url)) {
    // URL is valid
}
```

---

## Query Parameterss

```php
$url = $generator->generate('users.index', [], [
    'page' => 2,
    'sort' => 'name'
]);
// /users?page=2&sort=name
```

---

## Helper Function

```php
// Using helper
$url = route_url('users.show', ['id' => 123]);
```

---

## Comparison

| Feature | CloudCastle | Laravel | Symfony | FastRoute | Slim |
|---------|-------------|---------|---------|-----------|------|
| **URL Gen** | ✅ | ✅ | ✅ | ❌ | ⚠️ |
| **Absolute** | ✅ | ✅ | ✅ | ❌ | ⚠️ |
| **Signed** | ✅ | ✅ | ⚠️ | ❌ | ❌ |
| **Query** | ✅ | ✅ | ✅ | ❌ | ⚠️ |

---

[⬆ Back](../FEATURES_INDEX.md) | [🏠 Home](../../../README.md)

---

© 2024 CloudCastle HTTP Router. All Rights Reserved.


