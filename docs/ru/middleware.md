# Middleware в CloudCastle HTTP Router

**Languages:** 🇷🇺 Русский | [🇬🇧 English](../en/middleware.md) | [🇩🇪 Deutsch](../de/middleware.md) | [🇫🇷 Français](../fr/middleware.md) | [🇨🇳 中文](../zh/middleware.md)

[📚 Оглавление](_table-of-contents.md) | [🏠 Главная](README.md)

---

## 📚 Обзор

Middleware - это мощный механизм для обработки HTTP запросов до и после их маршрутизации.

## 🎯 Типы Middleware

### 1. Глобальный Middleware

Применяется ко **всем маршрутам**.

```php
// Простой глобальный middleware
$router->middleware('cors');

// Множественный глобальный middleware
$router->middleware(['cors', 'auth', 'log']);

// Custom middleware
$router->middleware(new CustomMiddleware());
```

### 2. Group Middleware

Применяется ко **всем маршрутам в группе**.

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

Применяется к **конкретному маршруту**.

```php
$router->get('/admin', 'AdminController@index')
    ->middleware(['auth', 'admin']);

// Chain middleware
$router->get('/special', 'SpecialController@index')
    ->middleware('auth')
    ->middleware('verified')
    ->middleware('premium');
```

## 🛠️ Встроенные Middleware

### 1. CorsMiddleware

**Назначение**: Управление CORS headers для cross-origin requests.

**Возможности:**
- Allowed origins (с wildcard поддержкой)
- Allowed methods
- Allowed headers
- Max age
- Credentials support
- Preflight handling

**Полная конфигурация:**
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

**Простая конфигурация (development):**
```php
// Разрешить всё (только для development!)
$cors = new CorsMiddleware(
    allowedOrigins: ['*'],
    allowedMethods: ['*'],
    allowedHeaders: ['*']
);
```

**Production пример:**
```php
// API с CORS
$router->group(['prefix' => '/api'], function($router) use ($cors) {
    $router->middleware($cors);
    
    $router->get('/public', 'ApiController@public');
    $router->get('/data', 'ApiController@data');
});
```

**Preflight requests:**
CORS middleware автоматически обрабатывает OPTIONS requests для preflight.

---

### 2. AuthMiddleware

**Назначение**: Аутентификация и авторизация пользователей.

**Возможности:**
- Bearer token authentication
- Session authentication
- Custom authenticator callback
- Role-based access control
- Multiple roles support

**Базовое использование:**
```php
use CloudCastle\Http\Router\Middleware\AuthMiddleware;

// Базовая аутентификация
$auth = new AuthMiddleware();
$router->get('/profile', 'ProfileController@show')
    ->middleware($auth);
```

**С ролями:**
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

**JWT Authentication пример:**
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

**Исключения:**
- `RuntimeException('Unauthorized', 401)` - не авторизован
- `RuntimeException('Forbidden', 403)` - недостаточно прав

---

### 3. HttpsEnforcement

**Назначение**: Принудительное использование HTTPS.

```php
use CloudCastle\Http\Router\Middleware\HttpsEnforcement;

$https = new HttpsEnforcement(
    redirect: true,     // автоматический redirect
    permanent: true     // 301 вместо 302
);

$router->middleware($https);
```

**Применение:**
- Production приложения
- Защита cookies
- Compliance (PCI DSS, GDPR)

---

### 4. SsrfProtection

**Назначение**: Защита от Server-Side Request Forgery.

**Уникальная фича!**

```php
use CloudCastle\Http\Router\Middleware\SsrfProtection;

$ssrf = new SsrfProtection(
    allowLocalhost: false,
    allowPrivateIps: false,
    allowCloudMetadata: false
);

$router->middleware($ssrf);
```

**Блокирует запросы к:**
- `http://localhost`
- `http://127.0.0.1`
- `http://192.168.1.1` (private)
- `http://10.0.0.1` (private)
- `http://169.254.169.254` (AWS metadata)
- `http://metadata.google.internal` (GCP metadata)

---

### 5. SecurityLogger

**Назначение**: Логирование security событий.

```php
use CloudCastle\Http\Router\Middleware\SecurityLogger;

$logger = new SecurityLogger(__DIR__ . '/logs/security.log');
$router->middleware($logger);
```

**Логируемые события:**
- Banned IP attempts
- Rate limit exceeds
- Unauthorized access
- IP filtering blocks
- SSRF attempts

**Формат лога:**
```
[2025-10-18 22:00:15] BLOCKED: IP 1.2.3.4 - Rate limit exceeded on /api/data
[2025-10-18 22:01:30] BANNED: IP 1.2.3.4 - Auto-ban triggered after 100 attempts
[2025-10-18 22:05:45] SUSPICIOUS: IP 5.6.7.8 - Path traversal attempt /../../../etc/passwd
[2025-10-18 22:10:00] BLOCKED: IP 9.10.11.12 - SSRF attempt to http://169.254.169.254
[2025-10-18 22:15:20] UNAUTHORIZED: IP 13.14.15.16 - Failed auth on /admin/dashboard
```

---

## 🔌 PSR-15 Support

### Использование PSR-15 Middleware

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

### Наш Middleware как PSR-15

```php
use CloudCastle\Http\Router\Psr15\RouterMiddlewareBridge;

$ourMiddleware = new AuthMiddleware();
$bridge = new RouterMiddlewareBridge($ourMiddleware);

// Используйте в PSR-15 стеке
```

---

## 🎨 Создание Custom Middleware

### Базовый middleware

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

### Middleware с конфигурацией

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

### Middleware с dependency injection

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

### Порядок выполнения

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

### Визуализация стека

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

### 1. Порядок имеет значение

```php
// ПРАВИЛЬНО: CORS перед Auth
$router->middleware(new CorsMiddleware());
$router->middleware(new AuthMiddleware());

// НЕПРАВИЛЬНО: Auth заблокирует preflight
$router->middleware(new AuthMiddleware());
$router->middleware(new CorsMiddleware()); // Не сработает для OPTIONS
```

### 2. Минимизируйте middleware на hot paths

```php
// ХОРОШО: middleware только где нужно
$router->get('/public', 'PublicController@index'); // fast

// ПЛОХО: лишний middleware
$router->get('/public', 'PublicController@index')
    ->middleware(['auth', 'admin', 'log', 'analytics']); // slow
```

### 3. Группируйте логически

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

### 4. Используйте type hints

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

## 📊 Сравнение middleware систем

| Feature | CloudCastle | FastRoute | Symfony | Laravel | Slim | Alto |
|:---|:---:|:---:|:---:|:---:|:---:|:---:|
| Global middleware | ✅ | ❌ | ⚠️ | ✅ | ✅ | ❌ |
| Group middleware | ✅ | ❌ | ⚠️ | ✅ | ✅ | ❌ |
| Route middleware | ✅ | ❌ | ❌ | ✅ | ✅ | ❌ |
| PSR-15 | ✅ | ❌ | ❌ | ❌ | ✅ | ❌ |
| Built-in middleware | ✅ 5 | ❌ | ❌ | ✅ 3 | ⚠️ 1 | ❌ |
| Custom middleware | ✅ | ❌ | ✅ | ✅ | ✅ | ❌ |
| Middleware params | ✅ | ❌ | ✅ | ✅ | ✅ | ❌ |

## ✅ Заключение

CloudCastle HTTP Router предоставляет **наиболее полную middleware систему**:

- ✅ 3 уровня middleware (global, group, route)
- ✅ 5 встроенных middleware
- ✅ PSR-15 совместимость
- ✅ Простое создание custom middleware
- ✅ Гибкая конфигурация

---

*Последнее обновление: 18 октября 2025*

---

[📚 Оглавление](_table-of-contents.md) | [🏠 Главная](README.md)

