# API Reference - CloudCastle HTTP Router

[English](../en/API_REFERENCE.md) | **Русский** | [Deutsch](../de/API_REFERENCE.md) | [Français](../fr/API_REFERENCE.md) | [中文](../zh/API_REFERENCE.md)

---

## Содержание

- [Router](#router)
- [Route](#route)
- [RouteGroup](#routegroup)
- [RouteMacros](#routemacros)
- [RateLimiter](#ratelimiter)
- [BanManager](#banmanager)
- [UrlGenerator](#urlgenerator)
- [Loaders](#loaders)
- [Plugins](#plugins)
- [Helper Functions](#helper-functions)

---

## Router

### Создание

```php
use CloudCastle\Http\Router\Router;

// Новый экземпляр
$router = new Router();

// Singleton
$router = Router::getInstance();
```

### Методы

#### addRoute()
```php
$route = $router->addRoute(
    array $methods,     // HTTP методы
    string $uri,        // URI паттерн
    mixed $action       // Действие
): Route
```

#### get(), post(), put(), patch(), delete()
```php
$router->get(string $uri, mixed $action): Route
$router->post(string $uri, mixed $action): Route
$router->put(string $uri, mixed $action): Route
$router->patch(string $uri, mixed $action): Route
$router->delete(string $uri, mixed $action): Route
```

#### dispatch()
```php
$route = $router->dispatch(
    string $uri,
    string $method,
    ?string $domain = null,
    ?string $clientIp = null,
    ?int $port = null,
    ?string $protocol = null
): Route
```

#### group()
```php
$group = $router->group(
    array $attributes,
    Closure $callback
): RouteGroup
```

Полный справочник см. в [ALL_FEATURES.md](ALL_FEATURES.md)

---

## Route

### Создание

```php
use CloudCastle\Http\Router\Route;

$route = new Route(
    array|string $methods,
    string $uri,
    mixed $action
);
```

### Основные методы

```php
// Именование
$route->name(string $name): self

// Middleware
$route->middleware(array|string|callable $middleware): self

// Теги
$route->tag(string|array $tags): self

// Rate Limiting
$route->throttle(int $maxAttempts, int $decayMinutes, ?callable $key = null): self

// IP фильтрация
$route->whitelistIp(string|array $ips): self
$route->blacklistIp(string|array $ips): self

// Домен
$route->domain(string $domain): self

// Порт
$route->port(int $port): self

// HTTPS
$route->https(): self

// Параметры
$route->where(string|array $param, ?string $pattern = null): self
$route->defaults(array $defaults): self
```

---

© 2024 CloudCastle HTTP Router
