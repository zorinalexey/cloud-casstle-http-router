# PSR Support - Поддержка стандартов PSR

[English](../../en/features/PSR_SUPPORT_FEATURES.md) | **Русский** | [Deutsch](../../de/features/PSR_SUPPORT_FEATURES.md) | [Français](../../fr/features/PSR_SUPPORT_FEATURES.md) | [中文](../../zh/features/PSR_SUPPORT_FEATURES.md)

---

## Содержание

- [PSR-7 HTTP Message](#psr-7-http-message)
- [PSR-15 HTTP Server Handler](#psr-15-http-server-handler)
- [PSR-15 Middleware](#psr-15-middleware)
- [Psr15MiddlewareAdapter](#psr15middlewareadapter)
- [Сравнение с аналогами](#сравнение-с-аналогами)

---

## PSR-7 HTTP Message

### Описание

Поддержка PSR-7 (HTTP Message Interface) для работы с HTTP запросами и ответами.

### Использование

```php
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

Route::get('/api/user', function(ServerRequestInterface $request): ResponseInterface {
    // PSR-7 Request
    $method = $request->getMethod(); // GET
    $uri = $request->getUri();
    $headers = $request->getHeaders();
    $body = $request->getBody();
    
    // PSR-7 Response
    $response = new Response(200);
    $response->getBody()->write(json_encode(['user' => 'data']));
    
    return $response->withHeader('Content-Type', 'application/json');
});
```

### Преимущества

✅ Стандартизированный интерфейс  
✅ Совместимость с PSR-7 библиотеками  
✅ Immutable объекты  
✅ Stream-based body  

---

## PSR-15 HTTP Server Handler

### Описание

Поддержка PSR-15 (HTTP Server Request Handler).

### Использование

```php
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class UserHandler implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $user = User::find($request->getAttribute('id'));
        
        $response = new Response(200);
        $response->getBody()->write(json_encode($user));
        
        return $response;
    }
}

// Использование
Route::get('/users/{id}', new UserHandler());
```

---

## PSR-15 Middleware

### Описание

Полная поддержка PSR-15 middleware.

### Интерфейс

```php
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;

class AuthMiddleware implements MiddlewareInterface
{
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        // Проверка аутентификации
        $token = $request->getHeader('Authorization')[0] ?? null;
        
        if (!$this->isValidToken($token)) {
            return new Response(401);
        }
        
        // Добавляем пользователя в request
        $user = $this->getUserFromToken($token);
        $request = $request->withAttribute('user', $user);
        
        // Передаем следующему обработчику
        return $handler->handle($request);
    }
    
    private function isValidToken(?string $token): bool
    {
        return $token !== null && str_starts_with($token, 'Bearer ');
    }
    
    private function getUserFromToken(string $token): object
    {
        // Логика получения пользователя
        return (object)['id' => 1, 'name' => 'User'];
    }
}

// Использование
Route::get('/protected', $action)
    ->middleware(new AuthMiddleware());
```

### Примеры PSR-15 Middleware

**Логирование:**
```php
class LoggingMiddleware implements MiddlewareInterface
{
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        $start = microtime(true);
        
        // Логируем запрос
        Log::info('Request', [
            'method' => $request->getMethod(),
            'uri' => (string)$request->getUri(),
        ]);
        
        // Выполняем
        $response = $handler->handle($request);
        
        // Логируем ответ
        $duration = microtime(true) - $start;
        Log::info('Response', [
            'status' => $response->getStatusCode(),
            'duration' => $duration,
        ]);
        
        return $response;
    }
}
```

**CORS:**
```php
class CorsMiddleware implements MiddlewareInterface
{
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        $response = $handler->handle($request);
        
        return $response
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE')
            ->withHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization');
    }
}
```

---

## Psr15MiddlewareAdapter

### Описание

Адаптер для использования PSR-15 middleware в роутере.

### Использование

```php
use CloudCastle\Http\Router\Middleware\Psr15MiddlewareAdapter;

// PSR-15 middleware
$psrMiddleware = new SomePsr15Middleware();

// Адаптер
$adapter = new Psr15MiddlewareAdapter($psrMiddleware);

// Использование в роутере
Route::get('/api/data', $action)
    ->middleware($adapter);

// Или напрямую
Route::get('/api/data', $action)
    ->middleware(new Psr15MiddlewareAdapter(new AuthMiddleware()));
```

### Автоматическое определение

Роутер автоматически определяет PSR-15 middleware:

```php
// Автоматически обернется в адаптер
Route::get('/api/data', $action)
    ->middleware(new Psr15Middleware());
```

---

## Примеры интеграции

### Стек PSR-15 Middleware

```php
Route::group(['prefix' => '/api'], function() {
    Route::get('/users', $action)
        ->middleware([
            new CorsMiddleware(),
            new AuthMiddleware(),
            new RateLimitMiddleware(60),
            new LoggingMiddleware(),
        ]);
});
```

### Смешанный стек

Можно комбинировать PSR-15 и обычные middleware:

```php
Route::get('/mixed', $action)
    ->middleware([
        'auth',                      // CloudCastle middleware
        new Psr15AuthMiddleware(),   // PSR-15 middleware
        'throttle:60',               // CloudCastle middleware
        new Psr15LogMiddleware(),    // PSR-15 middleware
    ]);
```

---

## Сравнение с аналогами

| Роутер | PSR-7 | PSR-15 Handler | PSR-15 Middleware | Adapter | Оценка |
|--------|-------|----------------|-------------------|---------|--------|
| **CloudCastle** | ✅ | ✅ | ✅ | ✅ | **⭐⭐⭐⭐⭐** |
| Laravel | ⚠️ Partial | ⚠️ | ⚠️ | ⚠️ | ⭐⭐⭐ |
| Symfony | ✅ | ✅ | ✅ | ✅ | **⭐⭐⭐⭐⭐** |
| FastRoute | ❌ | ❌ | ❌ | ❌ | ⭐ |
| Slim | ✅ | ✅ | ✅ | ✅ | **⭐⭐⭐⭐⭐** |

### Детальное сравнение

**CloudCastle:**
```php
✅ Полная поддержка PSR-7
✅ Полная поддержка PSR-15
✅ Автоматический адаптер
✅ Смешанные middleware
✅ Нативная интеграция
```

**Laravel:**
```php
⚠️ Не полностью PSR-7/15
⚠️ Требует пакеты для полной поддержки
⚠️ Свой формат middleware
```

**Symfony:**
```php
✅ Отличная PSR поддержка
✅ HttpFoundation + PSR-7 bridge
✅ Полная совместимость
```

**Slim:**
```php
✅ Построен на PSR-7/15
✅ Нативная поддержка
✅ PSR в основе
```

---

## Преимущества PSR-совместимости

### 1. Переиспользование кода

```php
// PSR-15 middleware можно использовать в любом роутере
$authMiddleware = new Psr15AuthMiddleware();

// CloudCastle
Route::get('/api/data', $action)->middleware($authMiddleware);

// Slim
$app->get('/api/data', $handler)->add($authMiddleware);

// Symfony
$route->addMiddleware($authMiddleware);
```

### 2. Экосистема пакетов

Доступ к тысячам PSR-совместимых пакетов:

```php
composer require some/psr15-middleware

$middleware = new SomePsr15Middleware();
Route::get('/route', $action)->middleware($middleware);
```

### 3. Тестирование

```php
use Laminas\Diactoros\ServerRequest;
use Laminas\Diactoros\Response;

// Тестирование с PSR-7 объектами
$request = new ServerRequest();
$response = $middleware->process($request, $handler);

$this->assertEquals(200, $response->getStatusCode());
```

---

## Заключение

**CloudCastle предлагает полную PSR поддержку:**

✅ PSR-7 HTTP Message Interface  
✅ PSR-15 HTTP Server Request Handler  
✅ PSR-15 Middleware  
✅ Автоматический адаптер  
✅ Смешанные стеки middleware  
✅ Совместимость с экосистемой  

**Рекомендация:** Используйте PSR-стандарты для максимальной совместимости!

---

[⬆ Наверх](#psr-support---поддержка-стандартов-psr) | [📚 Все фичи](../ALL_FEATURES.md) | [🏠 Главная](../../../README.md)

© 2024 CloudCastle HTTP Router

