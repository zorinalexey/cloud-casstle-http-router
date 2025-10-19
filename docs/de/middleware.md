[🇷🇺 Русский](ru/middleware.md) | [🇺🇸 English](en/middleware.md) | [🇩🇪 Deutsch](de/middleware.md) | [🇫🇷 Français](fr/middleware.md) | [🇨🇳 中文](zh/middleware.md)

[📚 Table of Contents](de/_table-of-contents.md) | [🏠 Home](de/README.md)

---

# Middleware im CloudCastle HTTP Router

**Sprachen:** 🇷🇺 Russisch | [🇬🇧 Englisch](../en/middleware.md) | [🇩🇪 Deutsch](../de/middleware.md) | [🇫🇷 Français](../fr/middleware.md) | [🇨🇳中文](../zh/middleware.md)

[📚 Inhaltsverzeichnis](_table-of-contents.md) | [🏠 Startseite](README.md)

---

## 📚 Rezension

Middleware ist ein leistungsstarker Mechanismus zur Verarbeitung von HTTP-Anfragen vor und nach der Weiterleitung.

## 🎯 Arten von Middleware

### 1. Globale Middleware

Gilt für **alle Routen**.

```php
// Простой глобальный middleware
$router->middleware('cors');

// Множественный глобальный middleware
$router->middleware(['cors', 'auth', 'log']);

// Custom middleware
$router->middleware(new CustomMiddleware());
```

### 2. Group Middleware

Gilt für **alle Routen in der Gruppe**.

```php
$router->group(['middleware' => 'auth'], function($router) {
    $router->get('/profile', 'ProfileController@show');
    $router->get('/settings', 'SettingsController@index');
});

// Вложенные группы наследуют middleware
$router->group(['middleware' => 'cors'], function($router) {
    $router->group(['middleware' => 'auth'], function($router) {
        // Middleware stack: [cors, auth]
        $router->get('/api/user', 'ApiController@user');
    });
});
```

### 3. Route Middleware

Gilt für **spezifische Route**.

```php
$router->get('/admin', 'AdminController@index')
    ->middleware(['auth', 'admin']);

// Chain middleware
$router->get('/special', 'SpecialController@index')
    ->middleware('auth')
    ->middleware('verified')
    ->middleware('premium');
```

## 🛠️ Integrierte Middleware

### 1. CorsMiddleware

**Zweck**: CORS-Header für Cross-Origin-Anfragen verwalten.

**Möglichkeiten:**
- Zulässige Ursprünge (mit Wildcard-Unterstützung)
- Allowed methods
- Allowed headers
- Max age
- Credentials support
- Preflight handling

**Vollständige Konfiguration:**
```php
use CloudCastle\Http\Router\Middleware\CorsMiddleware;

$cors = new CorsMiddleware(
    allowedOrigins: [
        'https://example.com',
        'https://app.example.com',
        // или '*' для всех
    ],
    allowedMethods: [
        'GET', 'POST', 'PUT', 'DELETE', 'OPTIONS', 'PATCH'
    ],
    allowedHeaders: [
        'Content-Type',
        'Authorization',
        'X-Requested-With',
        'X-CSRF-TOKEN'
    ],
    maxAge: 86400,           // 24 hours
    allowCredentials: true    // поддержка cookies
);

$router->middleware($cors);
```

**Einfache Konfiguration (Entwicklung):**
```php
// Разрешить всё (только для development!)
$cors = new CorsMiddleware(
    allowedOrigins: ['*'],
    allowedMethods: ['*'],
    allowedHeaders: ['*']
);
```

**Produktionsbeispiel:**
```php
// API с CORS
$router->group(['prefix' => '/api'], function($router) use ($cors) {
    $router->middleware($cors);
    
    $router->get('/public', 'ApiController@public');
    $router->get('/data', 'ApiController@data');
});
```

**Preflight requests:**
Die CORS-Middleware verarbeitet automatisch OPTIONS-Anfragen für den Preflight.

---

### 2. AuthMiddleware

**Zweck**: Authentifizierung und Autorisierung von Benutzern.

**Möglichkeiten:**
- Bearer token authentication
- Session authentication
- Custom authenticator callback
- Role-based access control
- Multiple roles support

**Grundlegende Verwendung:**
```php
use CloudCastle\Http\Router\Middleware\AuthMiddleware;

// Базовая аутентификация
$auth = new AuthMiddleware();
$router->get('/profile', 'ProfileController@show')
    ->middleware($auth);
```

**Mit Rollen:**
```php
// Только для админов
$adminAuth = new AuthMiddleware(
    allowedRoles: ['admin', 'super-admin']
);

$router->get('/admin/users', 'AdminController@users')
    ->middleware($adminAuth);

// Для нескольких ролей
$moderatorAuth = new AuthMiddleware(
    allowedRoles: ['admin', 'moderator', 'editor']
);

$router->get('/content/moderate', 'ModerateController@index')
    ->middleware($moderatorAuth);
```

**Custom Authenticator:**
```php
// API Key authentication
$apiAuth = new AuthMiddleware(
    authenticator: function(): ?array {
        $apiKey = $_SERVER['HTTP_X_API_KEY'] ?? '';
        
        if ($apiKey === 'secret-key-123') {
            return [
                'id' => 1,
                'name' => 'API User',
                'roles' => ['api', 'user']
            ];
        }
        
        return null; // Не авторизован
    },
    allowedRoles: ['api']
);

$router->get('/api/protected', 'ApiController@protected')
    ->middleware($apiAuth);
```

**Beispiel für JWT-Authentifizierung:**
```php
use Firebase\JWT\JWT;

$jwtAuth = new AuthMiddleware(
    authenticator: function(): ?array {
        $authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
        
        if (!str_starts_with($authHeader, 'Bearer ')) {
            return null;
        }
        
        $token = substr($authHeader, 7);
        
        try {
            $decoded = JWT::decode($token, $key, ['HS256']);
            return [
                'id' => $decoded->user_id,
                'name' => $decoded->name,
                'roles' => $decoded->roles
            ];
        } catch (Exception $e) {
            return null;
        }
    },
    allowedRoles: ['user']
);
```

**Ausnahmen:**
- `RuntimeException('Unauthorized', 401)` – nicht autorisiert
- `RuntimeException('Forbidden', 403)` - unzureichende Rechte

---

### 3. HttpsEnforcement

**Zweck**: Erzwingen der Verwendung von HTTPS.

```php
use CloudCastle\Http\Router\Middleware\HttpsEnforcement;

$https = new HttpsEnforcement(
    redirect: true,     // автоматический redirect
    permanent: true     // 301 вместо 302
);

$router->middleware($https);
```

**Anwendung:**
- Produktionsanwendungen
- Cookie-Schutz
- Compliance (PCI DSS, GDPR)

---

### 4. SsrfProtection

**Zweck**: Schutz vor serverseitiger Anforderungsfälschung.

**Einzigartige Funktion!**

```php
use CloudCastle\Http\Router\Middleware\SsrfProtection;

$ssrf = new SsrfProtection(
    allowLocalhost: false,
    allowPrivateIps: false,
    allowCloudMetadata: false
);

$router->middleware($ssrf);
```

**Blockiert Anfragen an:**
- `http://localhost`
- `http://127.0.0.1`
- `http://192.168.1.1` (private)
- `http://10.0.0.1` (private)
- `http://169.254.169.254` (AWS metadata)
- `http://metadata.google.internal` (GCP metadata)

---

### 5. SecurityLogger

**Zweck**: Protokollierung von Sicherheitsereignissen.

```php
use CloudCastle\Http\Router\Middleware\SecurityLogger;

$logger = new SecurityLogger(__DIR__ . '/logs/security.log');
$router->middleware($logger);
```

**Protokollierte Ereignisse:**
- Banned IP attempts
- Rate limit exceeds
- Unauthorized access
- IP filtering blocks
- SSRF attempts

**Protokollformat:**
```
[2025-10-18 22:00:15] BLOCKED: IP 1.2.3.4 - Rate limit exceeded on /api/data
[2025-10-18 22:01:30] BANNED: IP 1.2.3.4 - Auto-ban triggered after 100 attempts
[2025-10-18 22:05:45] SUSPICIOUS: IP 5.6.7.8 - Path traversal attempt /../../../etc/passwd
[2025-10-18 22:10:00] BLOCKED: IP 9.10.11.12 - SSRF attempt to http://169.254.169.254
[2025-10-18 22:15:20] UNAUTHORIZED: IP 13.14.15.16 - Failed auth on /admin/dashboard
```

---

## 🔌 PSR-15 Support

### Verwendung der PSR-15-Middleware

```php
use CloudCastle\Http\Router\Psr15\Psr15MiddlewareAdapter;
use Some\Psr15\Middleware;

// PSR-15 middleware в роутере
$psrMiddleware = new Middleware();
$adapter = new Psr15MiddlewareAdapter(
    $psrMiddleware,
    $serverRequest,
    $response
);

$router->middleware($adapter);
```

### Unsere Middleware als PSR-15

```php
use CloudCastle\Http\Router\Psr15\RouterMiddlewareBridge;

$ourMiddleware = new AuthMiddleware();
$bridge = new RouterMiddlewareBridge($ourMiddleware);

// Используйте в PSR-15 стеке
```

---

## 🎨 Erstellen benutzerdefinierter Middleware

### Grundlegende Middleware

```php
use CloudCastle\Http\Router\Contracts\MiddlewareInterface;

class LoggerMiddleware implements MiddlewareInterface
{
    public function handle(mixed $request, callable $next, array $parameters = []): mixed
    {
        $start = microtime(true);
        
        // Before route handling
        error_log("Request: {$request}");
        
        // Call next middleware
        $response = $next($request);
        
        // After route handling
        $duration = microtime(true) - $start;
        error_log("Duration: {$duration}s");
        
        return $response;
    }
}
```

### Middleware mit Konfiguration

```php
class CacheMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly string $cacheDir,
        private readonly int $ttl = 3600
    ) {}
    
    public function handle(mixed $request, callable $next, array $parameters = []): mixed
    {
        $cacheKey = md5($request);
        $cacheFile = $this->cacheDir . '/' . $cacheKey;
        
        // Check cache
        if (file_exists($cacheFile) && 
            (time() - filemtime($cacheFile)) < $this->ttl) {
            return file_get_contents($cacheFile);
        }
        
        // Generate response
        $response = $next($request);
        
        // Store in cache
        file_put_contents($cacheFile, $response);
        
        return $response;
    }
}
```

### Middleware mit Abhängigkeitsinjektion

```php
class DatabaseMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly PDO $pdo,
        private readonly LoggerInterface $logger
    ) {}
    
    public function handle(mixed $request, callable $next, array $parameters = []): mixed
    {
        $this->pdo->beginTransaction();
        
        try {
            $response = $next($request);
            $this->pdo->commit();
            return $response;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            $this->logger->error('Transaction failed', ['exception' => $e]);
            throw $e;
        }
    }
}
```

## 📊 Middleware Stack

### Ausführungsreihenfolge

```php
$router->middleware('global1');
$router->middleware('global2');

$router->group(['middleware' => 'group1'], function($router) {
    $router->get('/test', 'TestController@index')
        ->middleware(['route1', 'route2']);
});

// Execution order:
// 1. global1
// 2. global2
// 3. group1
// 4. route1
// 5. route2
// → Controller
// ← route2 (after)
// ← route1 (after)
// ← group1 (after)
// ← global2 (after)
// ← global1 (after)
```

### Stapelvisualisierung

```
Request
  ↓
[Global1]
  ↓
[Global2]
  ↓
[Group1]
  ↓
[Route1]
  ↓
[Route2]
  ↓
Controller → Response
  ↑
[Route2]
  ↑
[Route1]
  ↑
[Group1]
  ↑
[Global2]
  ↑
[Global1]
  ↑
Response
```

## 💡 Best Practices

### 1. Ordnung ist wichtig

```php
// ПРАВИЛЬНО: CORS перед Auth
$router->middleware(new CorsMiddleware());
$router->middleware(new AuthMiddleware());

// НЕПРАВИЛЬНО: Auth заблокирует preflight
$router->middleware(new AuthMiddleware());
$router->middleware(new CorsMiddleware()); // Не сработает для OPTIONS
```

### 2. Middleware auf Hot Paths minimieren

```php
// ХОРОШО: middleware только где нужно
$router->get('/public', 'PublicController@index'); // fast

// ПЛОХО: лишний middleware
$router->get('/public', 'PublicController@index')
    ->middleware(['auth', 'admin', 'log', 'analytics']); // slow
```

### 3. Gruppieren Sie logisch

```php
// API группа
$router->group([
    'prefix' => '/api',
    'middleware' => ['cors', 'rate-limit']
], function($router) {
    
    // Public API
    $router->get('/public', 'ApiController@public');
    
    // Protected API
    $router->group(['middleware' => 'auth'], function($router) {
        $router->get('/user', 'ApiController@user');
    });
});
```

### 4. Typhinweise verwenden

```php
class TypedMiddleware implements MiddlewareInterface
{
    public function handle(
        mixed $request,
        callable $next,
        array $parameters = []
    ): mixed {
        // Реализация
    }
}
```

## 📊 Vergleich von Middleware-Systemen

| Feature | CloudCastle | FastRoute | Symfony | Laravel | Slim | Alto |
|:---|:---:|:---:|:---:|:---:|:---:|:---:|
| Global middleware | ✅ | ❌ | ⚠️ | ✅ | ✅ | ❌ |
| Group middleware | ✅ | ❌ | ⚠️ | ✅ | ✅ | ❌ |
| Route middleware | ✅ | ❌ | ❌ | ✅ | ✅ | ❌ |
| PSR-15 | ✅ | ❌ | ❌ | ❌ | ✅ | ❌ |
| Built-in middleware | ✅ 5 | ❌ | ❌ | ✅ 3 | ⚠️ 1 | ❌ |
| Custom middleware | ✅ | ❌ | ✅ | ✅ | ✅ | ❌ |
| Middleware params | ✅ | ❌ | ✅ | ✅ | ✅ | ❌ |

## ✅ Fazit

CloudCastle HTTP Router bietet **das umfassendste Middleware-System**:

- ✅ 3 Ebenen der Middleware (global, Gruppe, Route)
- ✅ 5 integrierte Middleware
- ✅ PSR-15-Kompatibilität
- ✅ Einfache Erstellung benutzerdefinierter Middleware
- ✅ Flexible Konfiguration

---

*Letzte Aktualisierung: 18. Oktober 2025*

---

[📚 Inhaltsverzeichnis](_table-of-contents.md) | [🏠 Startseite](README.md)

---

[📚 Table of Contents](de/_table-of-contents.md) | [🏠 Home](de/README.md)
