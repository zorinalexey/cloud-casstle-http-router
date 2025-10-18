# Middleware

**CloudCastle HTTP Router v1.1.1**  
**Sprache**: Deutsch

---

## 🌍 Übersetzungen

- [Русский](../../ru/documentation/middleware.md)
- [English](../../en/documentation/middleware.md)
- **[Deutsch](middleware.md)** (aktuell)
- [Français](../../fr/documentation/middleware.md)

---

## 📋 Einführung

Middleware sind Handler, die vor oder nach der Hauptaktion der Route ausgeführt werden.

---

## 🎯 Middleware erstellen

### MiddlewareInterface

```php
namespace CloudCastle\Http\Router\Contracts;

interface MiddlewareInterface
{
    public function handle(mixed $request, callable $next): mixed;
}
```

### Beispiel-Middleware

```php
namespace App\Middleware;

use CloudCastle\Http\Router\Contracts\MiddlewareInterface;

class AuthMiddleware implements MiddlewareInterface
{
    public function handle(mixed $request, callable $next): mixed
    {
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            return 'Unauthorized';
        }
        
        return $next($request);
    }
}
```

---

## 🔧 Middleware anwenden

### Zu Route

```php
Route::get('/dashboard', 'DashboardController@index')
    ->middleware(AuthMiddleware::class);
```

### Mehrere Middleware

```php
Route::get('/admin', 'AdminController@index')
    ->middleware(['auth', 'admin', 'verified']);
```

### Zu Gruppe

```php
Route::group(['middleware' => 'auth'], function() {
    Route::get('/dashboard', 'DashboardController@index');
});
```

---

## 🛡️ Eingebaute Middleware

### HTTPS Enforcement

```php
use CloudCastle\Http\Router\Middleware\HttpsEnforcement;

Route::post('/login', 'AuthController@login')
    ->middleware(new HttpsEnforcement(redirectToHttps: true));
```

### SSRF Protection

```php
use CloudCastle\Http\Router\Middleware\SsrfProtection;

Route::post('/fetch-url', 'Controller@fetchUrl')
    ->middleware(new SsrfProtection());
```

### Security Logger

```php
use CloudCastle\Http\Router\Middleware\SecurityLogger;

Route::post('/api', 'ApiController@handle')
    ->middleware(new SecurityLogger('/var/log/security.log'));
```

---

**[← Zurück zum Inhaltsverzeichnis](README.md)**

