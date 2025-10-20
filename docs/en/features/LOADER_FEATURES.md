# Loader Features - Route Loading

**English** | [Русский](../ru/features/LOADER_FEATURES.md) | [Deutsch](../de/features/LOADER_FEATURES.md) | [Français](../fr/features/LOADER_FEATURES.md) | [中文](../zh/features/LOADER_FEATURES.md)

---





## Overview

5 route loaders for different file formats - maximum flexibility.

---

## JsonLoader

Load routes from JSON files:

```php
use CloudCastle\Http\Router\Loader\JsonLoader;

$loader = new JsonLoader($router);
$loader->load('routes.json');
```

**Format:**
```json
{
  "routes": [
    {
      "method": "GET",
      "uri": "/users",
      "action": "UserController@index",
      "name": "users.index"
    }
  ]
}
```

---

## YamlLoader

Load routes from YAML:

```php
$loader = new YamlLoader($router);
$loader->load('routes.yaml');
```

**Format:**
```yaml
routes:
  - method: GET
    uri: /users
    action: UserController@index
    name: users.index
```

---

## XmlLoader

Load routes from XML:

```php
$loader = new XmlLoader($router);
$loader->load('routes.xml');
```

---

## PhpLoader

Load routes from PHP arrays:

```php
$loader = new PhpLoader($router);
$loader->load('routes.php');
```

---

## AttributeLoader

Load from PHP 8 attributes:

```php
$loader = new AttributeLoader($router);
$loader->loadFromDirectory('app/Controllers');
```

**Controller example:**
```php
#[Route('/users', methods: ['GET'], name: 'users.index')]
class UserController
{
    public function index() { }
}
```

---

## Comparison

| Loader | CloudCastle | Laravel | Symfony | FastRoute | Slim |
|--------|-------------|---------|---------|-----------|------|
| **JSON** | ✅ | ⚠️ | ✅ | ❌ | ❌ |
| **YAML** | ✅ | ⚠️ | ✅ | ❌ | ❌ |
| **XML** | ✅ | ❌ | ✅ | ❌ | ❌ |
| **PHP** | ✅ | ✅ | ✅ | ✅ | ✅ |
| **Attributes** | ✅ | ✅ | ✅ | ❌ | ❌ |
| **Total** | **5** | 3 | 4 | 1 | 1 |

**CloudCastle has the MOST loaders!**

---

[⬆ Back](../FEATURES_INDEX.md) | [🏠 Home](../../../README.md)

---

© 2024 CloudCastle HTTP Router. All Rights Reserved.


