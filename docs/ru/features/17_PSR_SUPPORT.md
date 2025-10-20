# PSR Support

**Категория:** Стандарты  
**Количество стандартов:** 3  
**Сложность:** ⭐⭐⭐ Продвинутый уровень

---

## Поддерживаемые PSR стандарты

### PSR-7: HTTP Message Interface

```php
use Psr\Http\Message\ServerRequestInterface;

$request = ServerRequestFactory::fromGlobals();
$uri = $request->getUri()->getPath();
$method = $request->getMethod();

$route = Route::dispatch($uri, $method);
```

### PSR-15: HTTP Server Request Handlers

```php
use CloudCastle\Http\Router\Psr15\Psr15MiddlewareAdapter;

$adapter = new Psr15MiddlewareAdapter($router);
$response = $adapter->process($request, $handler);
```

### PSR-15 Middleware

```php
use Psr\Http\Server\MiddlewareInterface;

class Psr15Middleware implements MiddlewareInterface
{
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        // PSR-15 middleware logic
        return $handler->handle($request);
    }
}
```

---

**Версия:** 1.1.1  
**Статус:** ✅ Полная поддержка PSR-7/15

