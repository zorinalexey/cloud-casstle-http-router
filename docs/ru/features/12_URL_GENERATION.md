# URL Generation

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

**Версия:** 1.1.1  
**Статус:** ✅ Стабильная функциональность

