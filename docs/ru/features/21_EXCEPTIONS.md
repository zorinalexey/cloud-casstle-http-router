# Исключения

**Категория:** Обработка ошибок  
**Количество типов:** 8  
**Сложность:** ⭐ Начальный уровень

---

## Все исключения

### 1. RouteNotFoundException

```php
try {
    $route = Route::dispatch('/nonexistent', 'GET');
} catch (\CloudCastle\Http\Router\Exceptions\RouteNotFoundException $e) {
    http_response_code(404);
    echo "404 Not Found";
}
```

### 2. MethodNotAllowedException

```php
try {
    $route = Route::dispatch('/users', 'DELETE');
} catch (\CloudCastle\Http\Router\Exceptions\MethodNotAllowedException $e) {
    http_response_code(405);
    $allowed = $e->getAllowedMethods();
    header('Allow: ' . implode(', ', $allowed));
    echo "405 Method Not Allowed";
}
```

### 3. IpNotAllowedException

```php
try {
    $route = Route::dispatch('/admin', 'GET');
} catch (\CloudCastle\Http\Router\Exceptions\IpNotAllowedException $e) {
    http_response_code(403);
    echo "403 Forbidden: IP not allowed";
}
```

### 4. TooManyRequestsException

```php
try {
    $route = Route::dispatch('/api/submit', 'POST');
} catch (\CloudCastle\Http\Router\Exceptions\TooManyRequestsException $e) {
    http_response_code(429);
    $retryAfter = $e->getRetryAfter();
    header("Retry-After: $retryAfter");
    echo "429 Too Many Requests";
}
```

### 5. InsecureConnectionException

```php
try {
    $route = Route::dispatch('/payment', 'POST');
} catch (\CloudCastle\Http\Router\Exceptions\InsecureConnectionException $e) {
    http_response_code(403);
    echo "403 Forbidden: HTTPS required";
}
```

### 6. BannedException

```php
try {
    $route = Route::dispatch('/api/data', 'GET');
} catch (\CloudCastle\Http\Router\Exceptions\BannedException $e) {
    http_response_code(403);
    echo "403 Forbidden: IP banned";
}
```

### 7. InvalidActionException

```php
try {
    $route->run();
} catch (\CloudCastle\Http\Router\Exceptions\InvalidActionException $e) {
    http_response_code(500);
    echo "500 Internal Server Error";
}
```

### 8. RouterException

```php
try {
    // Router operations
} catch (\CloudCastle\Http\Router\Exceptions\RouterException $e) {
    echo "Router Error: " . $e->getMessage();
}
```

## Централизованная обработка

```php
try {
    $route = Route::dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
    echo $route->run();
    
} catch (BannedException $e) {
    http_response_code(403);
    echo "IP banned";
    
} catch (TooManyRequestsException $e) {
    http_response_code(429);
    header("Retry-After: " . $e->getRetryAfter());
    echo "Too many requests";
    
} catch (IpNotAllowedException $e) {
    http_response_code(403);
    echo "IP not allowed";
    
} catch (InsecureConnectionException $e) {
    http_response_code(403);
    echo "HTTPS required";
    
} catch (MethodNotAllowedException $e) {
    http_response_code(405);
    header('Allow: ' . implode(', ', $e->getAllowedMethods()));
    echo "Method not allowed";
    
} catch (RouteNotFoundException $e) {
    http_response_code(404);
    echo "404 Not Found";
    
} catch (RouterException $e) {
    http_response_code(500);
    echo "Server Error";
}
```

---

**Версия:** 1.1.1  
**Статус:** ✅ Стабильная функциональность

