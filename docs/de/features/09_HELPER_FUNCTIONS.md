# Helper Functions

[English](../../en/features/09_HELPER_FUNCTIONS.md) | [Русский](../../ru/features/09_HELPER_FUNCTIONS.md) | **Deutsch** | [Français](../../fr/features/09_HELPER_FUNCTIONS.md) | [中文](../../zh/features/09_HELPER_FUNCTIONS.md)

---







---

## 📚 Dokumentationsnavigation

[README](../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [API_REFERENCE](../API_REFERENCE.md) | [ALL_FEATURES](../ALL_FEATURES.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [PERFORMANCE](../PERFORMANCE_ANALYSIS.md) | [SECURITY](../SECURITY_REPORT.md) | [COMPARISON](../COMPARISON.md) | [FAQ](../FAQ.md)

**Detaillierte Dokumentation:** [01](01_BASIC_ROUTING.md) | [02](02_ROUTE_PARAMETERS.md) | [03](03_ROUTE_GROUPS.md) | [04](04_RATE_LIMITING.md) | [05](05_IP_FILTERING.md) | [06](06_MIDDLEWARE.md) | [07](07_NAMED_ROUTES.md) | [08](08_TAGS.md) | [09](09_HELPER_FUNCTIONS.md) | [10](10_ROUTE_SHORTCUTS.md) | [11](11_ROUTE_MACROS.md) | [12](12_URL_GENERATION.md) | [13](13_EXPRESSION_LANGUAGE.md) | [14](14_CACHING.md) | [15](15_PLUGINS.md) | [16](16_LOADERS.md) | [17](17_PSR_SUPPORT.md) | [18](18_ACTION_RESOLVER.md) | [19](19_STATISTICS.md) | [20](20_SECURITY.md) | [21](21_EXCEPTIONS.md) | [22](22_CLI_TOOLS.md)

---


**Kategorie:** В mit  nach могательные функц und  und   
**Anzahl der функц und й:** 18  
**Komplexität:** ⭐ Anfänger уро in ень

---

## Оп und  mit ан und е

Helper Functions - это глобальные PHP функц und  und , которые упрощают работу  mit  Route und затором, предо mit та in ляя коротк und й  und  удобный API без необход und мо mit т und   und  mit  nach льзо in ать  nach лные  und ме auf  кла mit  mit о in .

## Alle функц und  und 

### 1. route()

**С und г auf тура:** `route(?string $name = null, array $parameters = []): ?Route`

**Оп und  mit ан und е:** Erhalten Route  nach   und мен und   oder   in ернуть текущ und й Route.

**Parameter:**
- `$name` - Имя Routeа (null = текущ und й Route)
- `$parameters` - Parameter  für   nach д mit тано in к und  (зарезер in  und ро in ано)

**Beispiele:**

```php
// Erhalten маршрут по имени
$route = route('users.show');

// Erhalten текущий маршрут
$current = route();

// Проверить существование
if ($route = route('users.index')) {
    echo "Route exists: " . $route->getUri();
}

// Erhalten информацию о маршруте
$route = route('api.users.show');
if ($route) {
    echo "URI: " . $route->getUri();
    echo "Name: " . $route->getName();
    echo "Methods: " . implode(', ', $route->getMethods());
}
```

**И mit  nach льзо in ан und е:**
- Бы mit трый до mit туп к Routeам
- Про in ерка  mit уще mit т in о in ан und я
- Abrufen метаданных Routeа

---

### 2. current_route()

**С und г auf тура:** `current_route(): ?Route`

**Оп und  mit ан und е:** Erhalten текущ und й  in ы nach лняющ und й mit я Route.

**Beispiele:**

```php
// Erhalten текущий маршрут
$current = current_route();

if ($current) {
    echo "Current URI: " . $current->getUri();
    echo "Current name: " . $current->getName();
    
    // Erhalten параметры
    $params = $current->getParameters();
    print_r($params);
    
    // Erhalten middleware
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

**И mit  nach льзо in ан und е:**
- Лог und ро in ан und е текущего Routeа
- У mit ло in  auf я лог und ка  auf  о mit но in е Routeа
- Отладка

---

### 3. previous_route()

**С und г auf тура:** `previous_route(): ?Route`

**Оп und  mit ан und е:** Erhalten vorherige Route (до текущего).

**Beispiele:**

```php
// Erhalten предыдущий маршрут
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

**И mit  nach льзо in ан und е:**
- Кнопка "Zurück"
- Breadcrumbs
- А auf л und т und ка переходо in 
- History navigation

---

### 4. route_is()

**С und г auf тура:** `route_is(string $pattern): bool`

**Оп und  mit ан und е:** Про in ер und ть,  mit оAntwort mit т in ует л und  текущ und й Route паттерну. Поддерж und  in ает wildcards.

**Parameter:**
- `$pattern` - Паттерн  und мен und  Routeа ( nach ддерж und  in ает `*`)

**Beispiele:**

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
- `*` - любые  mit  und м in олы
- `users.*` - users.index, users.show, users.edit,  und  т.д.
- `api.*.users` - api.v1.users, api.v2.users,  und  т.д.

---

### 5. route_name()

**С und г auf тура:** `route_name(): ?string`

**Оп und  mit ан und е:** Erhalten  und мя текущего Routeа.

**Beispiele:**

```php
// Erhalten имя
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

**С und г auf тура:** `router(): Router`

**Оп und  mit ан und е:** Erhalten экземпляр роутера (singleton).

**Beispiele:**

```php
// Erhalten роутер
$router = router();

// Регистрировать маршруты
$router->get('/users', $action);
$router->post('/users', $action);

// Erhalten все маршруты
$allRoutes = $router->getRoutes();

// Erhalten именованные маршруты
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

**С und г auf тура:** `dispatch_route(string $uri, string $method, ...): Route`

**Оп und  mit ан und е:** Д und  mit петчер und зац und я Routeа без  und  mit  nach льзо in ан und я фа mit ада.

**Parameter:**
- `$uri` - URI Anfrageа
- `$method` - HTTP Methode
- `$domain` - Домен (опц und о auf льно)
- `$clientIp` - IP кл und ента (опц und о auf льно)
- `$port` - Порт (опц und о auf льно)
- `$protocol` - Протокол (опц und о auf льно)

**Beispiele:**

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

**С und г auf тура:** `route_url(string $name, array $parameters = [], array $queryParams = []): string`

**Оп und  mit ан und е:** Генерац und я URL  nach   und мен und  Routeа.

**Parameter:**
- `$name` - Имя Routeа
- `$parameters` - Parameter Routeа
- `$queryParams` - Query Parameter

**Beispiele:**

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

**С und г auf тура:** `route_has(string $name): bool`

**Оп und  mit ан und е:** Про in ер und ть  mit уще mit т in о in ан und е Routeа  nach   und мен und .

**Beispiele:**

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

**С und г auf тура:** `route_stats(): array`

**Оп und  mit ан und е:** Erhalten  mit тат und  mit т und ку Route und затора.

**Beispiele:**

```php
// Erhalten статистику
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

**С und г auf тура:** `routes_by_tag(string $tag): array`

**Оп und  mit ан und е:** Erhalten alle Routen  mit  указанным тегом.

**Beispiele:**

```php
// Erhalten API маршруты
$apiRoutes = routes_by_tag('api');

foreach ($apiRoutes as $route) {
    echo $route->getUri() . "\n";
}

// Erhalten защищенные маршруты
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

**С und г auf тура:** `route_back(): ?Route`

**Оп und  mit ан und е:** Erhalten vorherige Route (alias  für  previous_route).

**Beispiele:**

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

### 13-18. До nach лн und тельные helpers (зарезер in  und ро in аны)

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

## Практ und че mit к und е пр und меры

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

### Акт und  in ное меню

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

### Лог und ро in ан und е

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

## Рекомендац und  und 

### ✅ Хорош und е практ und к und 

1. **И mit  nach льзуйте route_has() перед route_url()**
   ```php
   // ✅ Хорошо
   if (route_has('users.show')) {
       $url = route_url('users.show', ['id' => 123]);
   }
   ```

2. **И mit  nach льзуйте route_is()  für  акт und  in ного меню**
   ```php
   // ✅ Хорошо
   $active = route_is('users.*') ? 'active' : '';
   ```

3. **И mit  nach льзуйте router()  für  ма mit  mit о in ых операц und й**
   ```php
   // ✅ Хорошо
   $router = router();
   $routes = $router->getRoutes();
   ```

### ❌ Anti-Patterns

1. **Не  in ызы in айте route() многократно  in  ц und кле**
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

## Leistung

| Операц und я | Время |
|----------|-------|
| route() | ~1μs |
| current_route() | ~0.5μs |
| route_url() | ~5μs |
| route_has() | ~2μs |

**Вы in од:** Alle helpers очень бы mit трые

---

## Siehe auch

- [Именованные маршруты](07_NAMED_ROUTES.md)
- [URL Generation](12_URL_GENERATION.md)
- [Route Shortcuts](10_ROUTE_SHORTCUTS.md)

---

**Version:** 1.1.1  
**Дата обно in лен und я:** Октябрь 2025  
**Стату mit :** ✅ Стаб und ль auf я функц und о auf льно mit ть


---

## 📚 Dokumentationsnavigation

[README](../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [API_REFERENCE](../API_REFERENCE.md) | [ALL_FEATURES](../ALL_FEATURES.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [FAQ](../FAQ.md)

**Detaillierte Dokumentation:** [01](01_BASIC_ROUTING.md) | [02](02_ROUTE_PARAMETERS.md) | [03](03_ROUTE_GROUPS.md) | [04](04_RATE_LIMITING.md) | [05](05_IP_FILTERING.md) | [06](06_MIDDLEWARE.md) | [07](07_NAMED_ROUTES.md) | [08](08_TAGS.md) | [09](09_HELPER_FUNCTIONS.md) | [10](10_ROUTE_SHORTCUTS.md) | [11](11_ROUTE_MACROS.md) | [12](12_URL_GENERATION.md) | [13](13_EXPRESSION_LANGUAGE.md) | [14](14_CACHING.md) | [15](15_PLUGINS.md) | [16](16_LOADERS.md) | [17](17_PSR_SUPPORT.md) | [18](18_ACTION_RESOLVER.md) | [19](19_STATISTICS.md) | [20](20_SECURITY.md) | [21](21_EXCEPTIONS.md) | [22](22_CLI_TOOLS.md)

**© 2024 CloudCastle HTTP Router**
