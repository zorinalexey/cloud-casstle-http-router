# Exceptions - Исключения роутера

[English](../../en/features/EXCEPTIONS_FEATURES.md) | **Русский** | [Deutsch](../../de/features/EXCEPTIONS_FEATURES.md) | [Français](../../fr/features/EXCEPTIONS_FEATURES.md) | [中文](../../zh/features/EXCEPTIONS_FEATURES.md)

---

## Содержание

- [RouteNotFoundException](#routenotfoundexception)
- [MethodNotAllowedException](#methodnotallowedexception)
- [IpNotAllowedException](#ipnotallowedexception)
- [TooManyRequestsException](#toomanyrequestsexception)
- [InsecureConnectionException](#insecureconnectionexception)
- [BannedException](#bannedexception)
- [InvalidActionException](#invalidactionexception)
- [RouterException](#routerexception)
- [Обработка исключений](#обработка-исключений)
- [Сравнение с аналогами](#сравнение-с-аналогами)

---

## RouteNotFoundException

### Описание

Выбрасывается, когда маршрут не найден (404).

### Использование

```php
use CloudCastle\Http\Router\Exceptions\RouteNotFoundException;

try {
    $route = $router->dispatch('/unknown-page', 'GET');
} catch (RouteNotFoundException $e) {
    // Маршрут не найден
    http_response_code(404);
    echo "Page not found";
}
```

### Свойства

```php
$e->getMessage();  // "Route not found: GET /unknown-page"
$e->getUri();      // "/unknown-page"
$e->getMethod();   // "GET"
```

### Пример обработки

```php
try {
    $route = dispatch_route();
    $result = $route->run();
} catch (RouteNotFoundException $e) {
    // Логирование
    Log::warning('404', [
        'uri' => $e->getUri(),
        'method' => $e->getMethod(),
        'ip' => $_SERVER['REMOTE_ADDR'],
    ]);
    
    // Отображение 404 страницы
    http_response_code(404);
    require __DIR__ . '/views/errors/404.php';
}
```

---

## MethodNotAllowedException

### Описание

Выбрасывается, когда маршрут существует, но метод не разрешен (405).

### Использование

```php
use CloudCastle\Http\Router\Exceptions\MethodNotAllowedException;

// Маршрут только GET
Route::get('/users', $action);

try {
    // Попытка POST
    $route = $router->dispatch('/users', 'POST');
} catch (MethodNotAllowedException $e) {
    // Метод не разрешен
    http_response_code(405);
    header('Allow: ' . implode(', ', $e->getAllowedMethods()));
    echo "Method not allowed";
}
```

### Свойства

```php
$e->getMessage();         // "Method POST not allowed for /users"
$e->getUri();             // "/users"
$e->getMethod();          // "POST"
$e->getAllowedMethods();  // ['GET']
```

### Пример

```php
catch (MethodNotAllowedException $e) {
    $allowed = implode(', ', $e->getAllowedMethods());
    
    http_response_code(405);
    header("Allow: $allowed");
    
    echo json_encode([
        'error' => 'Method not allowed',
        'allowed_methods' => $e->getAllowedMethods(),
    ]);
}
```

---

## IpNotAllowedException

### Описание

Выбрасывается при нарушении IP restrictions (403).

### Использование

```php
use CloudCastle\Http\Router\Exceptions\IpNotAllowedException;

// Только для определенных IP
Route::get('/admin', $action)
    ->whitelistIp(['192.168.1.0/24']);

try {
    $route = $router->dispatch('/admin', 'GET', null, '1.2.3.4');
} catch (IpNotAllowedException $e) {
    http_response_code(403);
    echo "Access denied";
}
```

### Свойства

```php
$e->getMessage();  // "IP 1.2.3.4 not allowed for /admin"
$e->getIpAddress(); // "1.2.3.4"
$e->getUri();      // "/admin"
```

---

## TooManyRequestsException

### Описание

Выбрасывается при превышении rate limit (429).

### Использование

```php
use CloudCastle\Http\Router\Exceptions\TooManyRequestsException;

Route::post('/api/data', $action)
    ->throttle(60, 60); // 60 req/min

try {
    $route = $router->dispatch('/api/data', 'POST', null, $clientIp);
} catch (TooManyRequestsException $e) {
    http_response_code(429);
    header('Retry-After: ' . $e->getRetryAfter());
    
    echo json_encode([
        'error' => 'Too many requests',
        'retry_after' => $e->getRetryAfter(),
    ]);
}
```

### Свойства

```php
$e->getMessage();     // "Too many requests for /api/data"
$e->getRetryAfter();  // 45 (seconds)
$e->getUri();         // "/api/data"
```

---

## InsecureConnectionException

### Описание

Выбрасывается при попытке доступа по HTTP к HTTPS-only маршруту.

### Использование

```php
use CloudCastle\Http\Router\Exceptions\InsecureConnectionException;

Route::get('/payment', $action)
    ->https();

try {
    $route = $router->dispatch('/payment', 'GET', null, null, null, 'http');
} catch (InsecureConnectionException $e) {
    // Редирект на HTTPS
    $httpsUrl = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header("Location: $httpsUrl", true, 301);
    exit;
}
```

### Свойства

```php
$e->getMessage();    // "Insecure connection for /payment"
$e->getUri();        // "/payment"
$e->getProtocol();   // "http"
```

---

## BannedException

### Описание

Выбрасывается, когда IP забанен Auto-Ban системой.

### Использование

```php
use CloudCastle\Http\Router\Exceptions\BannedException;

$banManager = new BanManager();
$banManager->enableAutoBan(5);

try {
    if ($banManager->isBanned($clientIp)) {
        throw new BannedException("IP $clientIp is banned");
    }
    
    $route = $router->dispatch($uri, $method, null, $clientIp);
} catch (BannedException $e) {
    http_response_code(403);
    echo "Your IP is banned";
}
```

### Свойства

```php
$e->getMessage();      // "IP 1.2.3.4 is banned"
$e->getIpAddress();    // "1.2.3.4"
$e->getBanDuration();  // 3600 (seconds)
```

---

## InvalidActionException

### Описание

Выбрасывается при некорректном формате action.

### Использование

```php
use CloudCastle\Http\Router\Exceptions\InvalidActionException;

try {
    // Некорректный action
    Route::get('/test', 'Invalid Format');
} catch (InvalidActionException $e) {
    echo "Invalid action format: " . $e->getMessage();
}
```

---

## RouterException

### Описание

Базовое исключение для всех исключений роутера.

### Иерархия

```
RouterException (базовое)
├── RouteNotFoundException
├── MethodNotAllowedException
├── IpNotAllowedException
├── TooManyRequestsException
├── InsecureConnectionException
├── BannedException
└── InvalidActionException
```

### Использование

```php
use CloudCastle\Http\Router\Exceptions\RouterException;

try {
    $route = dispatch_route();
} catch (RouterException $e) {
    // Обработка ВСЕХ исключений роутера
    Log::error('Router error', [
        'exception' => get_class($e),
        'message' => $e->getMessage(),
    ]);
}
```

---

## Обработка исключений

### Глобальный обработчик

```php
set_exception_handler(function(\Throwable $e) {
    if ($e instanceof RouteNotFoundException) {
        http_response_code(404);
        require __DIR__ . '/views/404.php';
    }
    
    elseif ($e instanceof MethodNotAllowedException) {
        http_response_code(405);
        header('Allow: ' . implode(', ', $e->getAllowedMethods()));
        require __DIR__ . '/views/405.php';
    }
    
    elseif ($e instanceof IpNotAllowedException) {
        http_response_code(403);
        require __DIR__ . '/views/403.php';
    }
    
    elseif ($e instanceof TooManyRequestsException) {
        http_response_code(429);
        header('Retry-After: ' . $e->getRetryAfter());
        echo json_encode(['error' => 'Too many requests']);
    }
    
    elseif ($e instanceof InsecureConnectionException) {
        $url = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        header("Location: $url", true, 301);
    }
    
    elseif ($e instanceof BannedException) {
        http_response_code(403);
        echo 'Your IP is banned';
    }
    
    else {
        http_response_code(500);
        require __DIR__ . '/views/500.php';
    }
});
```

### API Error Handler

```php
try {
    $route = dispatch_route();
    $result = $route->run();
    
    header('Content-Type: application/json');
    echo json_encode($result);
    
} catch (RouterException $e) {
    header('Content-Type: application/json');
    
    $response = [
        'error' => true,
        'message' => $e->getMessage(),
    ];
    
    if ($e instanceof TooManyRequestsException) {
        http_response_code(429);
        $response['retry_after'] = $e->getRetryAfter();
        header('Retry-After: ' . $e->getRetryAfter());
    }
    
    elseif ($e instanceof RouteNotFoundException) {
        http_response_code(404);
    }
    
    elseif ($e instanceof MethodNotAllowedException) {
        http_response_code(405);
        $response['allowed_methods'] = $e->getAllowedMethods();
    }
    
    else {
        http_response_code(400);
    }
    
    echo json_encode($response);
}
```

---

## Сравнение с аналогами

| Exception | CloudCastle | Laravel | Symfony | FastRoute | Slim |
|-----------|-------------|---------|---------|-----------|------|
| **RouteNotFound** | ✅ | ✅ | ✅ | ✅ | ✅ |
| **MethodNotAllowed** | ✅ | ✅ | ✅ | ✅ | ✅ |
| **IpNotAllowed** | ✅ | ❌ | ❌ | ❌ | ❌ |
| **TooManyRequests** | ✅ | ✅ | ❌ | ❌ | ❌ |
| **InsecureConnection** | ✅ | ❌ | ❌ | ❌ | ❌ |
| **Banned** | ✅ | ❌ | ❌ | ❌ | ❌ |

### Уникальные исключения CloudCastle

✅ **IpNotAllowedException** - IP filtering (уникально!)  
✅ **InsecureConnectionException** - HTTPS enforcement (уникально!)  
✅ **BannedException** - Auto-ban system (уникально!)  

---

## Заключение

**CloudCastle предлагает 8 типов исключений:**

✅ RouteNotFoundException (404)  
✅ MethodNotAllowedException (405)  
✅ IpNotAllowedException (403) - уникально!  
✅ TooManyRequestsException (429)  
✅ InsecureConnectionException - уникально!  
✅ BannedException - уникально!  
✅ InvalidActionException  
✅ RouterException (базовое)  

**Рекомендация:** Используйте глобальный обработчик для централизованной обработки!

---

[⬆ Наверх](#exceptions---исключения-роутера) | [📚 Все фичи](../ALL_FEATURES.md) | [🏠 Главная](../../../README.md)

© 2024 CloudCastle HTTP Router

