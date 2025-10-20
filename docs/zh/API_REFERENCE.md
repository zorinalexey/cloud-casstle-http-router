# API 参考 - CloudCastle HTTP Router

[English](../en/API_REFERENCE.md) | [Русский](../ru/API_REFERENCE.md) | [Deutsch](../de/API_REFERENCE.md) | [Français](../fr/API_REFERENCE.md) | [**中文**](API_REFERENCE.md)

---

## 📚 文档导航

[README](../../README.md) | [USER_GUIDE](USER_GUIDE.md) | [FEATURES_INDEX](FEATURES_INDEX.md) | [API_REFERENCE](API_REFERENCE.md) | [ALL_FEATURES](ALL_FEATURES.md) | [TESTS_SUMMARY](TESTS_SUMMARY.md) | [PERFORMANCE](PERFORMANCE_ANALYSIS.md) | [SECURITY](SECURITY_REPORT.md) | [COMPARISON](COMPARISON.md) | [FAQ](FAQ.md) | [DOC_SUMMARY](DOCUMENTATION_SUMMARY.md)

**详细文档：** [Features](features/)（22 个文件）| [Tests](tests/)（7 个报告）

---

## 目录

- [Router](#router)
- [Route](#route)
- [RouteGroup](#routegroup)
- [RouteMacros](#routemacros)
- [RateLimiter](#ratelimiter)
- [BanManager](#banmanager)
- [UrlGenerator](#urlgenerator)
- [加载器](#加载器)
- [插件](#插件)
- [辅助函数](#辅助函数)

---

## Router

### 创建

```php
use CloudCastle\Http\Router\Router;

// 新实例
$router = new Router();

// 单例
$router = Router::getInstance();
```

### 方法

#### addRoute()
```php
$route = $router->addRoute(
    array $methods,     // HTTP 方法
    string $uri,        // URI 模式
    mixed $action       // 动作
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

完整目录见 [ALL_FEATURES.md](ALL_FEATURES.md)

---

## Route

### 创建

```php
use CloudCastle\Http\Router\Route;

$route = new Route(
    array|string $methods,
    string $uri,
    mixed $action
);
```

### 核心方法

```php
// 命名
$route->name(string $name): self

// 中间件
$route->middleware(array|string|callable $middleware): self

// 标签
$route->tag(string|array $tags): self

// 速率限制
$route->throttle(int $maxAttempts, int $decayMinutes, ?callable $key = null): self

// IP 过滤
$route->whitelistIp(string|array $ips): self
$route->blacklistIp(string|array $ips): self

// 域名
$route->domain(string $domain): self

// 端口
$route->port(int $port): self

// HTTPS
$route->https(): self

// 参数
$route->where(string|array $param, ?string $pattern = null): self
$route->defaults(array $defaults): self
```

---

© 2024 CloudCastle HTTP Router

---

## 📚 文档导航

[README](../../README.md) | [USER_GUIDE](USER_GUIDE.md) | [FEATURES_INDEX](FEATURES_INDEX.md) | [API_REFERENCE](API_REFERENCE.md) | [ALL_FEATURES](ALL_FEATURES.md) | [TESTS_SUMMARY](TESTS_SUMMARY.md) | [PERFORMANCE](PERFORMANCE_ANALYSIS.md) | [SECURITY](SECURITY_REPORT.md) | [COMPARISON](COMPARISON.md) | [FAQ](FAQ.md) | [DOC_SUMMARY](DOCUMENTATION_SUMMARY.md)

**详细文档：** [Features](features/)（22 个文件）| [Tests](tests/)（7 个报告）

---

