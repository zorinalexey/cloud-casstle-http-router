# URL Generation

---

## 📚 Dokumentationsnavigation

[README](../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [API_REFERENCE](../API_REFERENCE.md) | [ALL_FEATURES](../ALL_FEATURES.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [PERFORMANCE](../PERFORMANCE_ANALYSIS.md) | [SECURITY](../SECURITY_REPORT.md) | [COMPARISON](../COMPARISON.md) | [FAQ](../FAQ.md)

**Детальная документация:** [01](01_BASIC_ROUTING.md) | [02](02_ROUTE_PARAMETERS.md) | [03](03_ROUTE_GROUPS.md) | [04](04_RATE_LIMITING.md) | [05](05_IP_FILTERING.md) | [06](06_MIDDLEWARE.md) | [07](07_NAMED_ROUTES.md) | [08](08_TAGS.md) | [09](09_HELPER_FUNCTIONS.md) | [10](10_ROUTE_SHORTCUTS.md) | [11](11_ROUTE_MACROS.md) | [12](12_URL_GENERATION.md) | [13](13_EXPRESSION_LANGUAGE.md) | [14](14_CACHING.md) | [15](15_PLUGINS.md) | [16](16_LOADERS.md) | [17](17_PSR_SUPPORT.md) | [18](18_ACTION_RESOLVER.md) | [19](19_STATISTICS.md) | [20](20_SECURITY.md) | [21](21_EXCEPTIONS.md) | [22](22_CLI_TOOLS.md)

---


**Категория:** URL генерация  
**Количество методов:** 11  
**Сложность:** ⭐⭐ Средний уровень

---

## UrlGenerator класс

```php
use CloudCastle\Http\Router\UrlGenerator;

$generator = new UrlGenerator();
```

## Методы

### 1. generate()

```php
$url = $generator->generate('users.show', ['id' => 123]);
// '/users/123'
```

### 2. absolute()

```php
$url = $generator->generate('users.show', ['id' => 123])
    ->absolute();
// 'http://example.com/users/123'
```

### 3. toDomain()

```php
$url = $generator->generate('users.show', ['id' => 123])
    ->toDomain('api.example.com');
// 'http://api.example.com/users/123'
```

### 4. toProtocol()

```php
$url = $generator->generate('users.show', ['id' => 123])
    ->toProtocol('https');
// 'https://example.com/users/123'
```

### 5. signed()

```php
$signedUrl = $generator->signed('verify.email', ['user' => 123], 3600);
// '/verify/email/123?signature=abc...&expires=1234567890'
```

### 6. setBaseUrl()

```php
$generator->setBaseUrl('https://api.example.com');
```

### 7-11. Query параметры и комбинации

```php
// С query параметрами
$url = $generator->generate('users.index', [], [
    'page' => 2,
    'limit' => 10
]);
// '/users?page=2&limit=10'

// Полная генерация
$url = $generator->generate('api.users.show', ['id' => 123])
    ->toDomain('api.example.com')
    ->toProtocol('https')
    ->absolute();
// 'https://api.example.com/api/users/123'

// Через helper
$url = route_url('users.show', ['id' => 123]);
// '/users/123'
```

## HATEOAS

```php
return [
    'user' => $user,
    'links' => [
        'self' => route_url('api.users.show', ['id' => $user->id]),
        'posts' => route_url('api.users.posts', ['id' => $user->id]),
        'edit' => route_url('api.users.update', ['id' => $user->id])
    ]
];
```

---

**Version:** 1.1.1  
**Статус:** ✅ Стабильная функциональность


---

## 📚 Dokumentationsnavigation

[README](../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [API_REFERENCE](../API_REFERENCE.md) | [ALL_FEATURES](../ALL_FEATURES.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [FAQ](../FAQ.md)

**Детальная документация:** [01](01_BASIC_ROUTING.md) | [02](02_ROUTE_PARAMETERS.md) | [03](03_ROUTE_GROUPS.md) | [04](04_RATE_LIMITING.md) | [05](05_IP_FILTERING.md) | [06](06_MIDDLEWARE.md) | [07](07_NAMED_ROUTES.md) | [08](08_TAGS.md) | [09](09_HELPER_FUNCTIONS.md) | [10](10_ROUTE_SHORTCUTS.md) | [11](11_ROUTE_MACROS.md) | [12](12_URL_GENERATION.md) | [13](13_EXPRESSION_LANGUAGE.md) | [14](14_CACHING.md) | [15](15_PLUGINS.md) | [16](16_LOADERS.md) | [17](17_PSR_SUPPORT.md) | [18](18_ACTION_RESOLVER.md) | [19](19_STATISTICS.md) | [20](20_SECURITY.md) | [21](21_EXCEPTIONS.md) | [22](22_CLI_TOOLS.md)

**© 2024 CloudCastle HTTP Router**
