# Helper Functions

**Категория:** Вспомогательные функции  
**Количество функций:** 18  
**Сложность:** ⭐ Начальный уровень

---

## Описание

Helper Functions - это глобальные PHP функции, которые упрощают работу с маршрутизатором, предоставляя короткий и удобный API без необходимости использовать полные имена классов.

## Все функции

### 1. route()

**Сигнатура:** `route(?string $name = null, array $parameters = []): ?Route`

**Описание:** Получить маршрут по имени или вернуть текущий маршрут.

**Параметры:**
- `$name` - Имя маршрута (null = текущий маршрут)
- `$parameters` - Параметры для подстановки (зарезервировано)

**Примеры:**

```php
// Получить маршрут по имени
$route = route('users.show');

// Получить текущий маршрут
$current = route();

// Проверить существование
if ($route = route('users.index')) {
    echo "Route exists: " . $route->getUri();
}

// Получить информацию о маршруте
$route = route('api.users.show');
if ($route) {
    echo "URI: " . $route->getUri();
    echo "Name: " . $route->getName();
    echo "Methods: " . implode(', ', $route->getMethods());
}
```

**Использование:**
- Быстрый доступ к маршрутам
- Проверка существования
- Получение метаданных маршрута

---

### 2. current_route()

**Сигнатура:** `current_route(): ?Route`

**Описание:** Получить текущий выполняющийся маршрут.

**Примеры:**

```php
// Получить текущий маршрут
$current = current_route();

if ($current) {
    echo "Current URI: " . $current->getUri();
    echo "Current name: " . $current->getName();
    
    // Получить параметры
    $params = $current->getParameters();
    print_r($params);
    
    // Получить middleware
    $middleware = $current->getMiddleware();
    
    // Проверить теги
    if (in_array('api', $current->getTags())) {
        echo "This is API route";
    }
}

// В контроллере
class UserController
{
    public function show($id)
    {
        $route = current_route();
        $routeName = $route->getName();
        
        return "Viewing user $id via route: $routeName";
    }
}

// В middleware
class LoggerMiddleware
{
    public function handle(Route $route, callable $next)
    {
        $current = current_route();
        error_log("Processing: " . $current->getUri());
        
        return $next($route);
    }
}
```

**Использование:**
- Логирование текущего маршрута
- Условная логика на основе маршрута
- Отладка

---

### 3. previous_route()

**Сигнатура:** `previous_route(): ?Route`

**Описание:** Получить предыдущий маршрут (до текущего).

**Примеры:**

```php
// Получить предыдущий маршрут
$previous = previous_route();

if ($previous) {
    echo "Previous URI: " . $previous->getUri();
    echo "Previous name: " . $previous->getName();
}

// Redirect back (использование в функции back)
function redirectBack()
{
    $previous = previous_route();
    if ($previous) {
        header('Location: ' . $previous->getUri());
        exit;
    }
}

// Breadcrumbs
$current = current_route();
$previous = previous_route();

echo '<nav>';
if ($previous) {
    echo '<a href="' . $previous->getUri() . '">Back</a> &gt; ';
}
echo '<span>' . $current->getName() . '</span>';
echo '</nav>';

// Аналитика переходов
class Analytics
{
    public function trackNavigation()
    {
        $from = previous_route()?->getUri() ?? 'direct';
        $to = current_route()->getUri();
        
        $this->log("Navigation: $from → $to");
    }
}
```

**Использование:**
- Кнопка "Назад"
- Breadcrumbs
- Аналитика переходов
- History navigation

---

### 4. route_is()

**Сигнатура:** `route_is(string $pattern): bool`

**Описание:** Проверить, соответствует ли текущий маршрут паттерну. Поддерживает wildcards.

**Параметры:**
- `$pattern` - Паттерн имени маршрута (поддерживает `*`)

**Примеры:**

```php
// Точное совпадение
if (route_is('users.show')) {
    echo "Showing user";
}

// Wildcard - все маршруты users.*
if (route_is('users.*')) {
    echo "Users section";
}

// Wildcard - все API маршруты
if (route_is('api.*')) {
    echo "API request";
}

// Множественные уровни
if (route_is('admin.users.*')) {
    echo "Admin users section";
}

// В шаблоне
<nav>
    <a href="/users" class="<?= route_is('users.*') ? 'active' : '' ?>">
        Users
    </a>
    <a href="/posts" class="<?= route_is('posts.*') ? 'active' : '' ?>">
        Posts
    </a>
</nav>

// Условная логика
if (route_is('admin.*')) {
    // Админ панель - показать дополнительные кнопки
    echo '<button>Admin Panel</button>';
}

// Проверка версии API
if (route_is('api.v1.*')) {
    $apiVersion = 'v1';
} elseif (route_is('api.v2.*')) {
    $apiVersion = 'v2';
}
```

**Wildcards:**
- `*` - любые символы
- `users.*` - users.index, users.show, users.edit, и т.д.
- `api.*.users` - api.v1.users, api.v2.users, и т.д.

---

### 5. route_name()

**Сигнатура:** `route_name(): ?string`

**Описание:** Получить имя текущего маршрута.

**Примеры:**

```php
// Получить имя
$name = route_name();
echo "Current route: $name";  // users.show

// Проверка
if (route_name() === 'users.show') {
    echo "Viewing user";
}

// В логике
switch (route_name()) {
    case 'users.index':
        $title = 'Users List';
        break;
    case 'users.show':
        $title = 'View User';
        break;
    case 'users.edit':
        $title = 'Edit User';
        break;
}

// Логирование
error_log("Route: " . route_name());

// Метрики
$metrics->track('route.' . route_name());
```

---

### 6. router()

**Сигнатура:** `router(): Router`

**Описание:** Получить экземпляр роутера (singleton).

**Примеры:**

```php
// Получить роутер
$router = router();

// Регистрировать маршруты
$router->get('/users', $action);
$router->post('/users', $action);

// Получить все маршруты
$allRoutes = $router->getRoutes();

// Получить именованные маршруты
$namedRoutes = $router->getNamedRoutes();

// Статистика
$stats = $router->getRouteStats();
print_r($stats);

// Кеширование
$router->enableCache('cache/routes');
$router->compile();

// Middleware
$router->middleware([CorsMiddleware::class]);

// Группы
$router->group(['prefix' => '/admin'], function($r) {
    $r->get('/dashboard', $action);
});
```

---

### 7. dispatch_route()

**Сигнатура:** `dispatch_route(string $uri, string $method, ...): Route`

**Описание:** Диспетчеризация маршрута без использования фасада.

**Параметры:**
- `$uri` - URI запроса
- `$method` - HTTP метод
- `$domain` - Домен (опционально)
- `$clientIp` - IP клиента (опционально)
- `$port` - Порт (опционально)
- `$protocol` - Протокол (опционально)

**Примеры:**

```php
// Базовая диспетчеризация
$route = dispatch_route('/users/123', 'GET');

// С полными параметрами
$route = dispatch_route(
    uri: '/api/users/123',
    method: 'GET',
    domain: 'api.example.com',
    clientIp: '192.168.1.1',
    port: 443,
    protocol: 'https'
);

// Программная маршрутизация
function handleRequest($uri, $method) {
    try {
        $route = dispatch_route($uri, $method);
        return $route->run();
    } catch (RouteNotFoundException $e) {
        return '404 Not Found';
    }
}

// Тестирование маршрутов
function testRoute($uri, $method, $expectedResult) {
    $route = dispatch_route($uri, $method);
    $result = $route->run();
    assert($result === $expectedResult);
}

testRoute('/users', 'GET', 'Users list');
```

---

### 8. route_url()

**Сигнатура:** `route_url(string $name, array $parameters = [], array $queryParams = []): string`

**Описание:** Генерация URL по имени маршрута.

**Параметры:**
- `$name` - Имя маршрута
- `$parameters` - Параметры маршрута
- `$queryParams` - Query параметры

**Примеры:**

```php
// Простой URL
$url = route_url('users.index');
// /users

// С параметрами
$url = route_url('users.show', ['id' => 123]);
// /users/123

// С query параметрами
$url = route_url('users.index', [], ['page' => 2, 'limit' => 10]);
// /users?page=2&limit=10

// Комбинация
$url = route_url('posts.show', 
    ['slug' => 'hello-world'],
    ['ref' => 'twitter']
);
// /posts/hello-world?ref=twitter

// В шаблоне
<a href="<?= route_url('users.edit', ['id' => $user->id]) ?>">
    Edit User
</a>

// Redirect
header('Location: ' . route_url('users.show', ['id' => $newUser->id]));

// API
return json_encode([
    'user' => $user,
    'links' => [
        'self' => route_url('api.users.show', ['id' => $user->id]),
        'posts' => route_url('api.users.posts', ['id' => $user->id])
    ]
]);
```

---

### 9. route_has()

**Сигнатура:** `route_has(string $name): bool`

**Описание:** Проверить существование маршрута по имени.

**Примеры:**

```php
// Проверка существования
if (route_has('users.show')) {
    echo "Route exists";
}

// Условная навигация
if (route_has('admin.dashboard')) {
    echo '<a href="' . route_url('admin.dashboard') . '">Admin</a>';
}

// Защита от ошибок
function safeRouteUrl($name, $params = []) {
    if (!route_has($name)) {
        return '/';
    }
    return route_url($name, $params);
}

// Валидация конфигурации
$requiredRoutes = ['home', 'login', 'register', 'logout'];
foreach ($requiredRoutes as $route) {
    if (!route_has($route)) {
        throw new Exception("Missing required route: $route");
    }
}

// Динамическое меню
$menuItems = [
    ['name' => 'users.index', 'label' => 'Users'],
    ['name' => 'posts.index', 'label' => 'Posts'],
    ['name' => 'admin.dashboard', 'label' => 'Admin']
];

foreach ($menuItems as $item) {
    if (route_has($item['name'])) {
        echo "<a href=\"" . route_url($item['name']) . "\">{$item['label']}</a>";
    }
}
```

---

### 10. route_stats()

**Сигнатура:** `route_stats(): array`

**Описание:** Получить статистику маршрутизатора.

**Примеры:**

```php
// Получить статистику
$stats = route_stats();

/*
Array (
    [total] => 150
    [named] => 120
    [with_middleware] => 60
    [with_tags] => 80
    [methods] => Array (
        [GET] => 80
        [POST] => 40
        [PUT] => 15
        [DELETE] => 15
    )
    [domains] => Array (
        [api.example.com] => 30
    )
    [ports] => Array (
        [8080] => 20
    )
)
*/

// Вывести статистику
echo "Total routes: {$stats['total']}\n";
echo "Named routes: {$stats['named']}\n";
echo "GET routes: {$stats['methods']['GET']}\n";

// Админ панель
function showRouteStatistics() {
    $stats = route_stats();
    
    echo "<h2>Route Statistics</h2>";
    echo "<p>Total: {$stats['total']}</p>";
    echo "<p>Named: {$stats['named']}</p>";
    
    echo "<h3>By Method:</h3><ul>";
    foreach ($stats['methods'] as $method => $count) {
        echo "<li>$method: $count</li>";
    }
    echo "</ul>";
}

// Метрики
$stats = route_stats();
$metrics->gauge('routes.total', $stats['total']);
$metrics->gauge('routes.named', $stats['named']);
```

---

### 11. routes_by_tag()

**Сигнатура:** `routes_by_tag(string $tag): array`

**Описание:** Получить все маршруты с указанным тегом.

**Примеры:**

```php
// Получить API маршруты
$apiRoutes = routes_by_tag('api');

foreach ($apiRoutes as $route) {
    echo $route->getUri() . "\n";
}

// Получить защищенные маршруты
$protectedRoutes = routes_by_tag('protected');

// Применить middleware ко всем
foreach ($protectedRoutes as $route) {
    $route->middleware([SecurityMiddleware::class]);
}

// Документация API
function generateApiDocs() {
    $apiRoutes = routes_by_tag('api');
    
    foreach ($apiRoutes as $route) {
        echo "Endpoint: " . $route->getUri() . "\n";
        echo "Methods: " . implode(', ', $route->getMethods()) . "\n";
        echo "Name: " . $route->getName() . "\n\n";
    }
}

// Тестирование
function testApiRoutes() {
    $apiRoutes = routes_by_tag('api');
    
    foreach ($apiRoutes as $route) {
        // Test each API route
        $uri = $route->getUri();
        $method = $route->getMethods()[0];
        
        testEndpoint($uri, $method);
    }
}
```

---

### 12. route_back()

**Сигнатура:** `route_back(): ?Route`

**Описание:** Получить предыдущий маршрут (alias для previous_route).

**Примеры:**

```php
// Кнопка назад
$back = route_back();
if ($back) {
    echo '<a href="' . $back->getUri() . '">Back</a>';
}

// Redirect back
function redirectBack() {
    $backRoute = route_back();
    if ($backRoute) {
        header('Location: ' . $backRoute->getUri());
        exit;
    }
    header('Location: /');
    exit;
}

// После сохранения формы
Route::post('/users', function() {
    // Save user...
    
    $backRoute = route_back();
    if ($backRoute && str_starts_with($backRoute->getUri(), '/users')) {
        // Вернуться к списку пользователей
        header('Location: ' . $backRoute->getUri());
    } else {
        // Иначе на главную
        header('Location: /');
    }
});
```

---

### 13-18. Дополнительные helpers (зарезервированы)

```php
// 13. current_route_params() - параметры текущего маршрута
function current_route_params(): array {
    return current_route()?->getParameters() ?? [];
}

// 14. current_route_middleware() - middleware текущего маршрута
function current_route_middleware(): array {
    return current_route()?->getMiddleware() ?? [];
}

// 15. current_route_tags() - теги текущего маршрута
function current_route_tags(): array {
    return current_route()?->getTags() ?? [];
}

// 16. route_cache_enabled() - проверка кеша
function route_cache_enabled(): bool {
    return router()->isCacheLoaded();
}

// 17. route_cache_clear() - очистка кеша
function route_cache_clear(): bool {
    return router()->clearCache();
}

// 18. route_compile() - компиляция маршрутов
function route_compile(bool $force = false): bool {
    return router()->compile($force);
}
```

---

## Практические примеры

### Breadcrumbs

```php
function renderBreadcrumbs() {
    $breadcrumbs = [];
    
    // Предыдущий маршрут
    if ($prev = route_back()) {
        $breadcrumbs[] = [
            'name' => $prev->getName(),
            'url' => $prev->getUri()
        ];
    }
    
    // Текущий маршрут
    if ($current = current_route()) {
        $breadcrumbs[] = [
            'name' => $current->getName(),
            'url' => null  // Current page
        ];
    }
    
    echo '<nav class="breadcrumbs">';
    foreach ($breadcrumbs as $crumb) {
        if ($crumb['url']) {
            echo "<a href=\"{$crumb['url']}\">{$crumb['name']}</a> &gt; ";
        } else {
            echo "<span>{$crumb['name']}</span>";
        }
    }
    echo '</nav>';
}
```

### Активное меню

```php
function menu($items) {
    foreach ($items as $item) {
        $active = route_is($item['route']) ? 'active' : '';
        
        if (route_has($item['route'])) {
            $url = route_url($item['route']);
            echo "<a href=\"$url\" class=\"$active\">{$item['label']}</a>";
        }
    }
}

menu([
    ['route' => 'home', 'label' => 'Home'],
    ['route' => 'users.*', 'label' => 'Users'],
    ['route' => 'posts.*', 'label' => 'Posts'],
]);
```

### API Links (HATEOAS)

```php
function apiResponse($data, $routeName, $params) {
    return [
        'data' => $data,
        'links' => [
            'self' => route_url($routeName, $params),
            'index' => route_url(str_replace('.show', '.index', $routeName))
        ]
    ];
}

// Usage
$user = User::find($id);
return apiResponse($user, 'api.users.show', ['id' => $id]);
```

### Логирование

```php
class RouteLogger {
    public function log() {
        $route = current_route();
        $previous = route_back();
        
        error_log(sprintf(
            "Route: %s (from: %s, params: %s)",
            route_name(),
            $previous?->getName() ?? 'direct',
            json_encode(current_route_params())
        ));
    }
}
```

---

## Рекомендации

### ✅ Хорошие практики

1. **Используйте route_has() перед route_url()**
   ```php
   // ✅ Хорошо
   if (route_has('users.show')) {
       $url = route_url('users.show', ['id' => 123]);
   }
   ```

2. **Используйте route_is() для активного меню**
   ```php
   // ✅ Хорошо
   $active = route_is('users.*') ? 'active' : '';
   ```

3. **Используйте router() для массовых операций**
   ```php
   // ✅ Хорошо
   $router = router();
   $routes = $router->getRoutes();
   ```

### ❌ Антипаттерны

1. **Не вызывайте route() многократно в цикле**
   ```php
   // ❌ Плохо
   foreach ($items as $item) {
       $route = route($item->routeName);  // N запросов
   }
   
   // ✅ Хорошо
   $namedRoutes = router()->getNamedRoutes();
   foreach ($items as $item) {
       $route = $namedRoutes[$item->routeName] ?? null;
   }
   ```

---

## Производительность

| Операция | Время |
|----------|-------|
| route() | ~1μs |
| current_route() | ~0.5μs |
| route_url() | ~5μs |
| route_has() | ~2μs |

**Вывод:** Все helpers очень быстрые

---

## См. также

- [Именованные маршруты](07_NAMED_ROUTES.md)
- [URL Generation](12_URL_GENERATION.md)
- [Route Shortcuts](10_ROUTE_SHORTCUTS.md)

---

**Версия:** 1.1.1  
**Дата обновления:** Октябрь 2025  
**Статус:** ✅ Стабильная функциональность

