# URL Generation - Детальное описание генерации URL

[English](../../en/features/URL_GENERATION_FEATURES.md) | **Русский** | [Deutsch](../../de/features/URL_GENERATION_FEATURES.md) | [Français](../../fr/features/URL_GENERATION_FEATURES.md) | [中文](../../zh/features/URL_GENERATION_FEATURES.md)

---

## Содержание

- [Базовая генерация](#базовая-генерация)
- [С параметрами](#с-параметрами)
- [Абсолютные URL](#абсолютные-url)
- [Signed URLs](#signed-urls)
- [Query параметры](#query-параметры)
- [Сравнение с аналогами](#сравнение-с-аналогами)

---

## Базовая генерация

```php
use CloudCastle\Http\Router\UrlGenerator;

$generator = new UrlGenerator($router);

// Простой URL
$url = $generator->generate('users.index');
// /users

$url = $generator->generate('users.show', ['id' => 123]);
// /users/123
```

---

## С параметрами

```php
// Один параметр
Route::get('/users/{id}', $action)->name('users.show');
$url = $generator->generate('users.show', ['id' => 123]);
// /users/123

// Множественные параметры
Route::get('/posts/{year}/{month}/{slug}', $action)->name('posts.show');
$url = $generator->generate('posts.show', [
    'year' => 2024,
    'month' => 12,
    'slug' => 'my-post'
]);
// /posts/2024/12/my-post

// Необязательные параметры
Route::get('/search/{query?}', $action)->name('search');
$url = $generator->generate('search');
// /search
$url = $generator->generate('search', ['query' => 'test']);
// /search/test
```

---

## Абсолютные URL

```php
// Относительный (по умолчанию)
$url = $generator->generate('users.show', ['id' => 1]);
// /users/1

// Абсолютный
$url = $generator->absolute('users.show', ['id' => 1]);
// https://example.com/users/1

// С кастомным доменом
$url = $generator->toDomain('users.show', ['id' => 1], 'api.example.com');
// https://api.example.com/users/1

// С кастомным протоколом
$url = $generator->toProtocol('users.show', ['id' => 1], 'http');
// http://example.com/users/1
```

---

## Signed URLs

### Описание

Подписанные URL для безопасного доступа к защищенным ресурсам.

```php
// Создать signed URL
$url = $generator->signed('download.file', ['id' => 123], 3600);
// /download/123?signature=abc123&expires=1234567890

// Permanent signed URL (без срока)
$url = $generator->signed('download.file', ['id' => 123]);
// /download/123?signature=abc123

// Проверка signed URL
if ($generator->hasValidSignature($url)) {
    // URL валиден
}
```

### Использование

```php
// Генерация ссылки на скачивание
Route::get('/download/{id}', function($id) {
    // Проверяем signature
    if (!route_signature_valid()) {
        throw new UnauthorizedException();
    }
    
    return download_file($id);
})->name('download.file');

// В контроллере
$downloadUrl = route_url_signed('download.file', ['id' => 123], 3600);

// В email
$email->body("Download: $downloadUrl");
```

---

## Query параметры

```php
// Дополнительные параметры
$url = $generator->generate('users.index', [], [
    'page' => 2,
    'sort' => 'name',
    'order' => 'asc'
]);
// /users?page=2&sort=name&order=asc

// Комбинация
$url = $generator->generate('users.show', ['id' => 123], [
    'comments' => 'true'
]);
// /users/123?comments=true
```

---

## Примеры реального использования

### API Endpoints

```php
// Версионированный API
Route::get('/api/v1/users/{id}', $action)
    ->name('api.v1.users.show')
    ->domain('api.example.com');

$url = $generator->absolute('api.v1.users.show', ['id' => 1]);
// https://api.example.com/api/v1/users/1
```

### Навигация в шаблонах

```php
<!-- Пагинация -->
<a href="<?= route_url('users.index', [], ['page' => $page - 1]) ?>">Prev</a>
<a href="<?= route_url('users.index', [], ['page' => $page + 1]) ?>">Next</a>

<!-- Breadcrumbs -->
<a href="<?= route_url('home') ?>">Home</a> /
<a href="<?= route_url('users.index') ?>">Users</a> /
<span><?= $user->name ?></span>
```

### Email Links

```php
// Подтверждение email
$confirmUrl = $generator->signed('email.confirm', [
    'user' => $user->id,
    'token' => $token
], 86400); // 24 часа

$email->send([
    'subject' => 'Confirm your email',
    'body' => "Click to confirm: $confirmUrl"
]);
```

---

## Сравнение с аналогами

| Роутер | URL Gen | Absolute | Signed | Query | Оценка |
|--------|---------|----------|--------|-------|--------|
| **CloudCastle** | ✅ | ✅ | ✅ | ✅ | **⭐⭐⭐⭐⭐** |
| Laravel | ✅ | ✅ | ✅ | ✅ | **⭐⭐⭐⭐⭐** |
| Symfony | ✅ | ✅ | ⚠️ | ✅ | ⭐⭐⭐⭐ |
| FastRoute | ❌ | ❌ | ❌ | ❌ | ⭐ |
| Slim | ⚠️ | ⚠️ | ❌ | ⚠️ | ⭐⭐ |

### Детальное сравнение

**CloudCastle:**
```php
$generator->generate('route', $params, $query);
$generator->absolute('route', $params);
$generator->signed('route', $params, $ttl);
$generator->toDomain('route', $params, $domain);
```

**Laravel:**
```php
route('route', $params);
route('route', $params, true); // absolute
URL::signedRoute('route', $params);
```

**Symfony:**
```php
$this->generateUrl('route', $params);
$this->generateUrl('route', $params, UrlGeneratorInterface::ABSOLUTE_URL);
// Нет встроенных signed URLs
```

**FastRoute:**
```php
// Нет URL generation
```

**Slim:**
```php
$router->urlFor('route', $params);
// Ограниченные возможности
```

---

## Заключение

**CloudCastle = Laravel по возможностям URL генерации:**

✅ Полная поддержка параметров  
✅ Абсолютные URL  
✅ Signed URLs (уникально!)  
✅ Query параметры  
✅ Кастомные домены и протоколы  

**Рекомендация:** Используйте signed URLs для защищенных ресурсов!

---

[⬆ Наверх](#url-generation---детальное-описание-генерации-url) | [📚 Все фичи](../ALL_FEATURES.md) | [🏠 Главная](../../../README.md)

© 2024 CloudCastle HTTP Router
