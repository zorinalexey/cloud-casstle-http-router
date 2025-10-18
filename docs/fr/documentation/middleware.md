# Middleware

**CloudCastle HTTP Router v1.1.1**  
**Langue**: Français

---

## 🌍 Traductions

- [Русский](../../ru/documentation/middleware.md)
- [English](../../en/documentation/middleware.md)
- [Deutsch](../../de/documentation/middleware.md)
- **[Français](middleware.md)** (actuel)

---

## 📋 Introduction

Les middleware sont des gestionnaires qui s'exécutent avant ou après l'action principale de la route.

---

## 🎯 Créer un Middleware

```php
namespace App\Middleware;

use CloudCastle\Http\Router\Contracts\MiddlewareInterface;

class AuthMiddleware implements MiddlewareInterface
{
    public function handle(mixed $request, callable $next): mixed
    {
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            return 'Non autorisé';
        }
        
        return $next($request);
    }
}
```

---

## 🔧 Appliquer un Middleware

### À une route

```php
Route::get('/dashboard', 'DashboardController@index')
    ->middleware(AuthMiddleware::class);
```

### À un groupe

```php
Route::group(['middleware' => 'auth'], function() {
    Route::get('/dashboard', 'DashboardController@index');
});
```

---

## 🛡️ Middleware intégrés

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

---

**[← Retour au sommaire](README.md)**

