# Router Query Features - Методы запроса и проверки маршрутов

[English](../../en/features/ROUTER_QUERY_FEATURES.md) | **Русский** | [Deutsch](../../de/features/ROUTER_QUERY_FEATURES.md) | [Français](../../fr/features/ROUTER_QUERY_FEATURES.md) | [中文](../../zh/features/ROUTER_QUERY_FEATURES.md)

---

## Содержание

- [Проверка существования](#проверка-существования)
- [Получение маршрутов](#получение-маршрутов)
- [Получение уникальных значений](#получение-уникальных-значений)
- [Глобальные настройки](#глобальные-настройки)
- [Сравнение с аналогами](#сравнение-с-аналогами)

---

## Проверка существования

### hasRoute()

Проверка существования маршрута по имени:

```php
if ($router->hasRoute('users.show')) {
    // Маршрут существует
    $url = route_url('users.show', ['id' => 1]);
} else {
    // Fallback
    $url = route_url('users.index');
}
```

**Применение:**
```php
// В middleware
if (!$router->hasRoute($requestedRoute)) {
    throw new RouteNotFoundException();
}

// В шаблоне
<?php if ($router->hasRoute('admin.dashboard')): ?>
    <a href="<?= route_url('admin.dashboard') ?>">Admin</a>
<?php endif; ?>
```

### hasTag()

Проверка существования тега:

```php
if ($router->hasTag('api')) {
    $apiRoutes = $router->getRoutesByTag('api');
    echo "API routes: " . count($apiRoutes);
}

if ($router->hasTag('deprecated')) {
    Log::warning('Deprecated routes still exist');
}
```

---

## Получение маршрутов

### getRouteByName()

Получить конкретный маршрут по имени:

```php
$route = $router->getRouteByName('users.show');

if ($route) {
    echo "URI: " . $route->getUri();
    echo "Methods: " . implode(', ', $route->getMethods());
    echo "Middleware: " . implode(', ', $route->getMiddleware());
}
```

### getRoutesByTag()

Получить все маршруты с определенным тегом:

```php
// Все API маршруты
$apiRoutes = $router->getRoutesByTag('api');

// Все публичные
$publicRoutes = $router->getRoutesByTag('public');

// Все админские
$adminRoutes = $router->getRoutesByTag('admin');

foreach ($apiRoutes as $route) {
    echo $route->getName() . "\n";
}
```

**Применение - генерация документации:**
```php
// Автоматическая генерация API документации
$apiRoutes = $router->getRoutesByTag('api');

foreach ($apiRoutes as $route) {
    echo "## " . $route->getName() . "\n";
    echo "**URI:** `" . $route->getUri() . "`\n";
    echo "**Methods:** " . implode(', ', $route->getMethods()) . "\n";
    echo "**Middleware:** " . implode(', ', $route->getMiddleware()) . "\n";
    echo "\n";
}
```

### getRoutesByMethod()

Получить все маршруты определенного HTTP метода:

```php
// Все GET маршруты
$getRoutes = $router->getRoutesByMethod('GET');

// Все POST маршруты
$postRoutes = $router->getRoutesByMethod('POST');

// Статистика
echo "GET: " . count($getRoutes) . " routes\n";
echo "POST: " . count($postRoutes) . " routes\n";
```

### getNamedRoutes()

Получить все именованные маршруты:

```php
$namedRoutes = $router->getNamedRoutes();

// Array: ['users.index' => Route, 'users.show' => Route, ...]

foreach ($namedRoutes as $name => $route) {
    echo "$name => " . $route->getUri() . "\n";
}
```

**Применение - генерация sitemap:**
```php
$namedRoutes = $router->getNamedRoutes();

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

foreach ($namedRoutes as $name => $route) {
    // Только GET маршруты без параметров
    if (in_array('GET', $route->getMethods()) && !str_contains($route->getUri(), '{')) {
        echo "  <url>\n";
        echo "    <loc>https://example.com" . $route->getUri() . "</loc>\n";
        echo "  </url>\n";
    }
}

echo '</urlset>';
```

---

## Получение уникальных значений

### getAllTags()

Получить все уникальные теги:

```php
$tags = $router->getAllTags();

// Array: ['api', 'public', 'admin', 'protected', ...]

// Вывести все теги
foreach ($tags as $tag) {
    $count = count($router->getRoutesByTag($tag));
    echo "$tag: $count routes\n";
}
```

**Применение - dashboard:**
```php
$tags = $router->getAllTags();

foreach ($tags as $tag) {
    $routes = $router->getRoutesByTag($tag);
    
    echo "<div class='tag-group'>\n";
    echo "  <h3>$tag (" . count($routes) . ")</h3>\n";
    echo "  <ul>\n";
    
    foreach ($routes as $route) {
        echo "    <li>" . $route->getUri() . "</li>\n";
    }
    
    echo "  </ul>\n";
    echo "</div>\n";
}
```

### getAllDomains()

Получить все уникальные домены:

```php
$domains = $router->getAllDomains();

// Array: ['api.example.com', 'admin.example.com', 'example.com', ...]

foreach ($domains as $domain) {
    $routes = $router->getRoutesByDomain($domain);
    echo "$domain: " . count($routes) . " routes\n";
}
```

**Применение - мультидоменный роутинг:**
```php
$domains = $router->getAllDomains();

foreach ($domains as $domain) {
    echo "## Domain: $domain\n\n";
    
    $routes = $router->getRoutesByDomain($domain);
    
    foreach ($routes as $route) {
        echo "- " . implode('|', $route->getMethods()) . " ";
        echo $route->getUri() . " => ";
        echo $route->getName() ?? 'unnamed';
        echo "\n";
    }
    
    echo "\n";
}
```

### getAllPorts()

Получить все уникальные порты:

```php
$ports = $router->getAllPorts();

// Array: [80, 443, 8080, 8443, ...]

foreach ($ports as $port) {
    $routes = $router->getRoutesByPort($port);
    echo "Port $port: " . count($routes) . " routes\n";
}
```

**Применение - микросервисы:**
```php
// Список всех сервисов
$ports = $router->getAllPorts();

foreach ($ports as $port) {
    $routes = $router->getRoutesByPort($port);
    
    echo "Service on port $port:\n";
    
    foreach ($routes as $route) {
        echo "  - " . $route->getUri() . "\n";
    }
}
```

---

## Глобальные настройки

### getGlobalMiddleware()

Получить все глобальные middleware:

```php
$global = $router->getGlobalMiddleware();

// Array: ['CorsMiddleware', 'LoggingMiddleware', ...]

foreach ($global as $middleware) {
    echo "Global: $middleware\n";
}
```

**Применение:**
```php
// Проверка настроек безопасности
$globalMiddleware = $router->getGlobalMiddleware();

$securityMiddleware = [
    'CorsMiddleware',
    'CsrfMiddleware',
    'SecurityHeadersMiddleware'
];

foreach ($securityMiddleware as $required) {
    if (!in_array($required, $globalMiddleware)) {
        Log::warning("Missing security middleware: $required");
    }
}
```

---

## Примеры реального использования

### 1. Admin Dashboard - статистика маршрутов

```php
// /admin/routes
class RoutesController
{
    public function index(Router $router)
    {
        $stats = [
            'total' => $router->count(),
            'named' => count($router->getNamedRoutes()),
            'tags' => $router->getAllTags(),
            'domains' => $router->getAllDomains(),
            'ports' => $router->getAllPorts(),
        ];
        
        $routesByMethod = [];
        foreach (['GET', 'POST', 'PUT', 'PATCH', 'DELETE'] as $method) {
            $routesByMethod[$method] = count($router->getRoutesByMethod($method));
        }
        
        return view('admin.routes.index', [
            'stats' => $stats,
            'methods' => $routesByMethod,
        ]);
    }
}
```

### 2. API Documentation Generator

```php
class ApiDocGenerator
{
    public function generate(Router $router): string
    {
        $apiRoutes = $router->getRoutesByTag('api');
        
        $doc = "# API Documentation\n\n";
        
        foreach ($apiRoutes as $route) {
            $doc .= "## " . $route->getName() . "\n\n";
            $doc .= "**Endpoint:** `" . $route->getUri() . "`\n";
            $doc .= "**Methods:** " . implode(', ', $route->getMethods()) . "\n";
            
            if ($route->getMiddleware()) {
                $doc .= "**Middleware:** " . implode(', ', $route->getMiddleware()) . "\n";
            }
            
            if ($rate = $route->getRateLimiter()) {
                $doc .= "**Rate Limit:** {$rate->getMaxAttempts()} requests per {$rate->getDecayMinutes()} minutes\n";
            }
            
            $doc .= "\n";
        }
        
        return $doc;
    }
}
```

### 3. Route Health Check

```php
class RouteHealthCheck
{
    public function check(Router $router): array
    {
        $issues = [];
        
        // Проверка неименованных маршрутов
        $total = $router->count();
        $named = count($router->getNamedRoutes());
        
        if ($named < $total) {
            $issues[] = ($total - $named) . " routes without names";
        }
        
        // Проверка защищенных маршрутов
        foreach (['admin', 'protected'] as $tag) {
            if ($router->hasTag($tag)) {
                $routes = $router->getRoutesByTag($tag);
                
                foreach ($routes as $route) {
                    if (!in_array('auth', $route->getMiddleware())) {
                        $issues[] = "Route {$route->getName()} tagged '$tag' but no auth middleware";
                    }
                }
            }
        }
        
        return $issues;
    }
}
```

---

## Сравнение с аналогами

| Метод | CloudCastle | Laravel | Symfony | FastRoute | Slim |
|-------|-------------|---------|---------|-----------|------|
| **hasRoute()** | ✅ | ✅ | ⚠️ | ❌ | ❌ |
| **hasTag()** | ✅ | ❌ | ❌ | ❌ | ❌ |
| **getRouteByName()** | ✅ | ✅ | ✅ | ❌ | ✅ |
| **getRoutesByTag()** | ✅ | ❌ | ❌ | ❌ | ❌ |
| **getRoutesByMethod()** | ✅ | ⚠️ | ✅ | ❌ | ❌ |
| **getNamedRoutes()** | ✅ | ✅ | ✅ | ❌ | ✅ |
| **getAllTags()** | ✅ | ❌ | ❌ | ❌ | ❌ |
| **getAllDomains()** | ✅ | ❌ | ❌ | ❌ | ❌ |
| **getAllPorts()** | ✅ | ❌ | ❌ | ❌ | ❌ |
| **getGlobalMiddleware()** | ✅ | ✅ | ✅ | ❌ | ✅ |

### Уникальные возможности CloudCastle

✅ **hasTag() / getRoutesByTag()** - работа с тегами (УНИКАЛЬНО!)  
✅ **getAllTags()** - все теги (УНИКАЛЬНО!)  
✅ **getAllDomains()** - все домены (УНИКАЛЬНО!)  
✅ **getAllPorts()** - все порты (УНИКАЛЬНО!)  

---

## Заключение

**CloudCastle предлагает 10+ методов запроса:**

✅ Проверка существования (hasRoute, hasTag)  
✅ Получение по критериям (ByName, ByTag, ByMethod)  
✅ Уникальные значения (Tags, Domains, Ports)  
✅ Глобальные настройки  

**Рекомендация:** Используйте для admin панелей, документации, health checks!

---

[⬆ Наверх](#router-query-features---методы-запроса-и-проверки-маршрутов) | [📚 Все фичи](../ALL_FEATURES.md) | [🏠 Главная](../../../README.md)

© 2024 CloudCastle HTTP Router

