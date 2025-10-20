# URL Generation

[**English**](12_URL_GENERATION.md) | [Русский](../../ru/features/12_URL_GENERATION.md) | [Deutsch](../../de/features/12_URL_GENERATION.md) | [Français](../../fr/features/12_URL_GENERATION.md) | [中文](../../zh/features/12_URL_GENERATION.md)

---

## 📚 Documentation Navigation

[README](../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [API_REFERENCE](../API_REFERENCE.md) | [ALL_FEATURES](../ALL_FEATURES.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [PERFORMANCE](../PERFORMANCE_ANALYSIS.md) | [SECURITY](../SECURITY_REPORT.md) | [COMPARISON](../COMPARISON.md) | [FAQ](../FAQ.md)

**Detailed Documentation:** [01](01_BASIC_ROUTING.md) | [02](02_ROUTE_PARAMETERS.md) | [03](03_ROUTE_GROUPS.md) | [04](04_RATE_LIMITING.md) | [05](05_IP_FILTERING.md) | [06](06_MIDDLEWARE.md) | [07](07_NAMED_ROUTES.md) | [08](08_TAGS.md) | [09](09_HELPER_FUNCTIONS.md) | [10](10_ROUTE_SHORTCUTS.md) | [11](11_ROUTE_MACROS.md) | [12](12_URL_GENERATION.md) | [13](13_EXPRESSION_LANGUAGE.md) | [14](14_CACHING.md) | [15](15_PLUGINS.md) | [16](16_LOADERS.md) | [17](17_PSR_SUPPORT.md) | [18](18_ACTION_RESOLVER.md) | [19](19_STATISTICS.md) | [20](20_SECURITY.md) | [21](21_EXCEPTIONS.md) | [22](22_CLI_TOOLS.md)

---

**Category:** URL Generation  
**Number of Methods:** 11  
**Complexity:** ⭐⭐ Intermediate Level

---

## UrlGenerator Class

```php
use CloudCastle\Http\Router\UrlGenerator;

$generator = new UrlGenerator();
```

## Methods

### 1. generate()
```php
$url = $generator->generate('users.show', ['id' => 123]);
// '/users/123'
```

### 2. absolute()
```php
$url = $generator->generate('users.show', ['id' => 123])->absolute();
// 'http://example.com/users/123'
```

### 3. toDomain()
```php
$url = $generator->generate('users.show', ['id' => 123])
    ->toDomain('api.example.com');
// 'http://api.example.com/users/123'
```

### 4. secure()
```php
$url = $generator->generate('users.show', ['id' => 123])->secure();
// 'https://example.com/users/123'
```

### 5. withQuery()
```php
$url = $generator->generate('users.index')
    ->withQuery(['page' => 2, 'sort' => 'name']);
// '/users?page=2&sort=name'
```

### 6. withFragment()
```php
$url = $generator->generate('users.show', ['id' => 123])
    ->withFragment('section');
// '/users/123#section'
```

## Examples

```php
// Basic URL generation
$url = $generator->generate('users.show', ['id' => 123]);
// '/users/123'

// Absolute URL
$url = $generator->generate('users.show', ['id' => 123])->absolute();
// 'http://example.com/users/123'

// Secure URL
$url = $generator->generate('users.show', ['id' => 123])->secure();
// 'https://example.com/users/123'

// With query parameters
$url = $generator->generate('users.index')
    ->withQuery(['page' => 2, 'filter' => 'active']);
// '/users?page=2&filter=active'

// Complete example
$url = $generator->generate('api.users.show', ['id' => 123])
    ->toDomain('api.example.com')
    ->secure()
    ->withQuery(['include' => 'posts'])
    ->withFragment('profile');
// 'https://api.example.com/users/123?include=posts#profile'
```

## Helper Function

```php
// Using url() helper
$url = url('users.show', ['id' => 123]);
// '/users/123'

$url = url('users.show', ['id' => 123])->absolute();
// 'http://example.com/users/123'
```

## See Also

- [Named Routes](07_NAMED_ROUTES.md) - Route naming
- [Helper Functions](09_HELPER_FUNCTIONS.md) - Helper functions
- [API Reference](../API_REFERENCE.md) - Complete API reference

---

© 2024 CloudCastle HTTP Router  
[⬆ Back to top](#url-generation)