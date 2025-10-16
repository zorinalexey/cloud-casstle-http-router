# Middleware (Промежуточные обработчики)

**CloudCastle HTTP Router v1.1.0**  
**Язык**: Русский

**Переводы**: [English](../../en/documentation/middleware.md) | [Deutsch](../../de/documentation/middleware.md) | [Français](../../fr/documentation/middleware.md)

---

## 🎯 Что такое Middleware?

Middleware - это промежуточный слой обработки запросов и ответов.

## 🚀 Базовое использование

```php
Route::get('/profile', 'ProfileController@show')
    ->middleware('auth');

// Несколько middleware
Route::post('/admin/action', 'AdminController@action')
    ->middleware(['auth', 'admin', 'verified']);
```

## 🔧 Создание Middleware

```php
use CloudCastle\Http\Router\Contracts\MiddlewareInterface;

class AuthMiddleware implements MiddlewareInterface
{
    public function handle($request, $next)
    {
        if (!isAuthenticated()) {
            throw new UnauthorizedException();
        }
        
        return $next($request);
    }
}
```

## 🛡️ Встроенные Middleware

### HTTPS Enforcement

```php
use CloudCastle\Http\Router\Middleware\HttpsEnforcement;

Route::post('/login', 'Auth@login')
    ->middleware(HttpsEnforcement::class);
```

### SSRF Protection

```php
use CloudCastle\Http\Router\Middleware\SsrfProtection;

Route::post('/webhook', 'WebhookController@handle')
    ->middleware(SsrfProtection::class);
```

### Security Logger

```php
use CloudCastle\Http\Router\Middleware\SecurityLogger;

Route::group(['middleware' => SecurityLogger::class], function() {
    // Все маршруты будут логироваться
});
```

## 📊 Shortcuts

```php
// Auth middleware
Route::get('/dashboard', fn() => 'Dashboard')
    ->auth();

// Guest middleware
Route::get('/login', fn() => 'Login')
    ->guest();

// Admin middleware
Route::get('/admin', fn() => 'Admin')
    ->admin();
```

---

**Переводы**: [English](../../en/documentation/middleware.md) | [Deutsch](../../de/documentation/middleware.md) | [Français](../../fr/documentation/middleware.md)
