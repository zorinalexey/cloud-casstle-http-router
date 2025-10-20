# Middleware - Детальное описание возможностей

[English](../../en/features/MIDDLEWARE_FEATURES.md) | **Русский** | [Deutsch](../../de/features/MIDDLEWARE_FEATURES.md) | [Français](../../fr/features/MIDDLEWARE_FEATURES.md) | [中文](../../zh/features/MIDDLEWARE_FEATURES.md)

---

## Глобальный Middleware

**Описание:** Middleware применяемый ко всем маршрутам.

**Использование:**
```php
$router->middleware([
    CorsMiddleware::class,
    SecurityMiddleware::class
]);

// Все маршруты будут использовать эти middleware
```

**Сравнение:**
- **CloudCastle:** ✅ Простой API
- **Laravel:** ✅ Kernel middleware
- **Symfony:** ⚠️ Event subscribers
- **FastRoute:** ❌ Нет встроенной поддержки

**Плюсы:** Применяется автоматически ко всем маршрутам
**Минусы:** Невозможно исключить для конкретного маршрута

**Рекомендации:**
```php
// Только критически важные middleware
$router->middleware([
    CorsMiddleware::class,      // CORS для всех
    SecurityHeadersMiddleware::class  // Security headers
]);
```

---

## Middleware на маршруте

**Использование:**
```php
Route::get('/dashboard', $action)
    ->middleware([AuthMiddleware::class]);

// Множественные
Route::get('/admin', $action)
    ->middleware([
        AuthMiddleware::class,
        AdminMiddleware::class,
        LogMiddleware::class
    ]);
```

**Порядок выполнения:** Слева направо (первый → последний)

**Сравнение:**
- **CloudCastle:** ⭐⭐⭐⭐⭐ Простой и мощный
- **Laravel:** ⭐⭐⭐⭐⭐ Аналогично
- **Symfony:** ⭐⭐⭐⭐ Через event system
- **FastRoute:** ❌ Нет

---

## Встроенные Middleware

### 1. AuthMiddleware
**Назначение:** Проверка аутентификации

**Использование:**
```php
use CloudCastle\Http\Router\Middleware\AuthMiddleware;

Route::get('/profile', $action)
    ->middleware(AuthMiddleware::class);
```

**Сравнение:**
- CloudCastle: ✅ Встроен
- Laravel: ✅ Встроен (auth)
- Symfony: ✅ Security component
- FastRoute/Slim: ❌ Нужно писать свой

### 2. CorsMiddleware
**Назначение:** CORS заголовки

**Использование:**
```php
use CloudCastle\Http\Router\Middleware\CorsMiddleware;

Route::post('/api/external', $action)
    ->middleware(CorsMiddleware::class);
```

**Настройка:**
```php
// Конфигурируемые заголовки
Access-Control-Allow-Origin: *
Access-Control-Allow-Methods: GET, POST, PUT, DELETE
Access-Control-Allow-Headers: Content-Type, Authorization
```

**Сравнение:**
- CloudCastle: ✅ Встроен и настраиваемый
- Laravel: ✅ Laravel-cors package
- Symfony: ✅ NelmioCorsBundle
- FastRoute/Slim: ❌ Отдельный пакет

### 3. HttpsEnforcement
**Назначение:** Принудительный HTTPS

```php
Route::group(['middleware' => HttpsEnforcement::class], function() {
    Route::post('/payment', $action);
    Route::post('/login', $action);
});
```

**Уникальность:** CloudCastle имеет встроенный, не требует конфигурации.

### 4. SecurityLogger
**Назначение:** Логирование security events

```php
Route::group(['middleware' => SecurityLogger::class], function() {
    // Все попытки доступа логируются
});
```

### 5. SsrfProtection
**Назначение:** Защита от SSRF атак

```php
Route::post('/fetch-url', $action)
    ->middleware(SsrfProtection::class);
```

---

## Сравнение с аналогами

| Фича | CloudCastle | Laravel | Symfony | FastRoute | Slim |
|------|-------------|---------|---------|-----------|------|
| Глобальный MW | ✅ | ✅ | ⚠️ | ❌ | ❌ |
| На маршруте | ✅ | ✅ | ⚠️ | ❌ | ✅ |
| В группе | ✅ | ✅ | ⚠️ | ❌ | ✅ |
| Встроенные | 5 | 10+ | 20+ | 0 | 0 |
| PSR-15 | ✅ | ✅ | ✅ | ❌ | ✅ |

---

[⬆ Наверх](#middleware---детальное-описание-возможностей) | [🏠 Главная](../../../README.md)

© 2024 CloudCastle HTTP Router
