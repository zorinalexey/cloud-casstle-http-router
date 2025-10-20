# API Reference - CloudCastle HTTP Router

[**English**](API_REFERENCE.md) | [Русский](../ru/API_REFERENCE.md) | [Deutsch](../de/API_REFERENCE.md) | [Français](../fr/API_REFERENCE.md) | [中文](../zh/API_REFERENCE.md)

---

## 📚 Documentation Navigation

[README](../../README.md) | [USER_GUIDE](USER_GUIDE.md) | [FEATURES_INDEX](FEATURES_INDEX.md) | [API_REFERENCE](API_REFERENCE.md) | [ALL_FEATURES](ALL_FEATURES.md) | [TESTS_SUMMARY](TESTS_SUMMARY.md) | [PERFORMANCE](PERFORMANCE_ANALYSIS.md) | [SECURITY](SECURITY_REPORT.md) | [COMPARISON](COMPARISON.md) | [FAQ](FAQ.md) | [DOC_SUMMARY](DOCUMENTATION_SUMMARY.md)

**Detailed docs:** [Features](features/) (22 files) | [Tests](tests/) (7 reports)

---

## Contents

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

### Creation

```php
use CloudCastle\Http\Router\Router;

// New instance
$router = new Router();

// Singleton
$router = Router::getInstance();
```

### Methods

#### addRoute()
```php
$route = $router->addRoute(
    array $methods,     // HTTP methods
    string $uri,        // URI pattern
    mixed $action       // Action
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

See the full catalogue in [ALL_FEATURES.md](ALL_FEATURES.md)

---

## Route

### Creation

```php
use CloudCastle\Http\Router\Route;

$route = new Route(
    array|string $methods,
    string $uri,
    mixed $action
);
```

### Core Methods

```php
// Naming
$route->name(string $name): self

// Middleware
$route->middleware(array|string|callable $middleware): self

// Tags
$route->tag(string|array $tags): self

// Rate Limiting
$route->throttle(int $maxAttempts, int $decayMinutes, ?callable $key = null): self

// IP filtering
$route->whitelistIp(string|array $ips): self
$route->blacklistIp(string|array $ips): self

// Domain
$route->domain(string $domain): self

// Port
$route->port(int $port): self

// HTTPS
$route->https(): self

// Parameters
$route->where(string|array $param, ?string $pattern = null): self
$route->defaults(array $defaults): self
```

---

© 2024 CloudCastle HTTP Router

---

## 📚 Documentation Navigation

[README](../../README.md) | [USER_GUIDE](USER_GUIDE.md) | [FEATURES_INDEX](FEATURES_INDEX.md) | [API_REFERENCE](API_REFERENCE.md) | [ALL_FEATURES](ALL_FEATURES.md) | [TESTS_SUMMARY](TESTS_SUMMARY.md) | [PERFORMANCE](PERFORMANCE_ANALYSIS.md) | [SECURITY](SECURITY_REPORT.md) | [COMPARISON](COMPARISON.md) | [FAQ](FAQ.md) | [DOC_SUMMARY](DOCUMENTATION_SUMMARY.md)

**Detailed docs:** [Features](features/) (22 files) | [Tests](tests/) (7 reports)

---

