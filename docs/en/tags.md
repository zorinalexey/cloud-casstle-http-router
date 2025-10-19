[🇷🇺 Русский](ru/tags.md) | [🇺🇸 English](en/tags.md) | [🇩🇪 Deutsch](de/tags.md) | [🇫🇷 Français](fr/tags.md) | [🇨🇳 中文](zh/tags.md)

[📚 Table of Contents](en/_table-of-contents.md) | [🏠 Home](en/README.md)

---

# Tags - Tag system for routes

**Languages:** 🇷🇺 Russian | [🇬🇧 English](../en/tags.md) | [🇩🇪 Deutsch](../de/tags.md) | [🇫🇷 Français](../fr/tags.md) | [🇨🇳中文](../zh/tags.md)

[📚 Table of Contents](_table-of-contents.md) | [🏠 Home](README.md)

---

## 📚 Review

**Tags** is a powerful system for grouping and filtering routes by arbitrary tags.

## 🎯 Basics of use

### Adding tags

```php
// Один тег
$router->get('/api/users', 'UserController@index')
    ->tag('api');

// Множественные теги
$router->get('/admin/users', 'AdminController@users')
    ->tag(['admin', 'users', 'management']);

// Через метод tags()
$router->get('/public/data', 'DataController@public')
    ->tags(['public', 'api', 'free']);
```

### Tags in groups

```php
// Все маршруты в группе получают тег
$router->group(['tag' => 'api'], function($router) {
    $router->get('/users', 'UserController@index');
    // Tag: api
    
    $router->get('/posts', 'PostController@index');
    // Tag: api
});

// Множественные теги в группе
$router->group(['tags' => ['api', 'v1']], function($router) {
    $router->get('/data', 'DataController@index');
    // Tags: [api, v1]
});
```

## 🔍 Search by tags

### Getting routes by tag

```php
// Все API маршруты
$apiRoutes = $router->getRoutesByTag('api');

foreach ($apiRoutes as $route) {
    echo "{$route->getUri()} - {$route->getName()}\n";
}

// Все admin маршруты
$adminRoutes = $router->getRoutesByTag('admin');

// Все публичные
$publicRoutes = $router->getRoutesByTag('public');
```

### Helper function

```php
// Используйте helper
$apiRoutes = routes_by_tag('api');
$adminRoutes = routes_by_tag('admin');
```

## 📊 Practical examples

### 1. Organization by functionality

```php
// API endpoints
$router->group(['tag' => 'api'], function($router) {
    $router->get('/api/users', ...);
    $router->get('/api/posts', ...);
});

// Admin endpoints
$router->group(['tag' => 'admin'], function($router) {
    $router->get('/admin/dashboard', ...);
    $router->get('/admin/users', ...);
});

// Public endpoints
$router->group(['tag' => 'public'], function($router) {
    $router->get('/about', ...);
    $router->get('/contact', ...);
});

// Теперь легко фильтровать!
$apiCount = count(routes_by_tag('api'));
$adminCount = count(routes_by_tag('admin'));
```

### 2. Feature flags

```php
// Premium features
$router->group(['tag' => 'premium'], function($router) {
    $router->get('/analytics', 'AnalyticsController@index');
    $router->get('/reports', 'ReportsController@index');
});

// В runtime проверяем
if ($user->isPremium()) {
    $premiumRoutes = routes_by_tag('premium');
    // Показываем пользователю доступные фичи
}
```

### 3. Modular architecture

```php
// Модуль Blog
$router->group(['prefix' => 'blog', 'tag' => 'module-blog'], function($router) {
    $router->get('/', 'Blog\IndexController@index');
    $router->get('/{slug}', 'Blog\PostController@show');
});

// Модуль Shop
$router->group(['prefix' => 'shop', 'tag' => 'module-shop'], function($router) {
    $router->get('/', 'Shop\IndexController@index');
    $router->get('/products/{id}', 'Shop\ProductController@show');
});

// Получить все модули
foreach (['blog', 'shop', 'forum'] as $module) {
    $routes = routes_by_tag("module-{$module}");
    echo "Module {$module}: " . count($routes) . " routes\n";
}
```

### 4. API versioning with tags

```php
// v1
$router->group(['prefix' => 'api/v1', 'tags' => ['api', 'v1']], function($router) {
    $router->get('/users', 'Api\V1\UserController@index');
});

// v2
$router->group(['prefix' => 'api/v2', 'tags' => ['api', 'v2']], function($router) {
    $router->get('/users', 'Api\V2\UserController@index');
});

// Получить все v1 маршруты
$v1Routes = routes_by_tag('v1');

// Получить все v2 маршруты
$v2Routes = routes_by_tag('v2');

// Получить все API маршруты (обе версии)
$allApiRoutes = routes_by_tag('api');
```

### 5. Environments

```php
// Development routes
if ($env === 'development') {
    $router->group(['tag' => 'dev'], function($router) {
        $router->get('/debug', 'DebugController@index');
        $router->get('/phpinfo', fn() => phpinfo());
    });
}

// Testing routes  
if ($env === 'testing') {
    $router->group(['tag' => 'test'], function($router) {
        $router->post('/test/reset-db', 'TestController@resetDatabase');
    });
}
```

### 6. Rate limiting by tags

```php
// Применить rate limit ко всем API маршрутам
$apiRoutes = routes_by_tag('api');

foreach ($apiRoutes as $route) {
    if (!$route->getRateLimiter()) {
        $route->perMinute(100); // default limit
    }
}
```

### 7. Documentation generation

```php
// Генерация API документации
$publicRoutes = routes_by_tag('public');
$apiRoutes = routes_by_tag('api');

$doc = [
    'public_endpoints' => array_map(fn($r) => [
        'uri' => $r->getUri(),
        'methods' => $r->getMethods(),
        'description' => '...'
    ], $publicRoutes),
    
    'api_endpoints' => array_map(fn($r) => [
        'uri' => $r->getUri(),
        'methods' => $r->getMethods(),
        'rate_limit' => $r->getRateLimiter()?->getMaxAttempts()
    ], $apiRoutes),
];

file_put_contents('api-docs.json', json_encode($doc, JSON_PRETTY_PRINT));
```

## 🔧 Advanced use

### Multiple tags on a route

```php
$router->get('/api/premium/analytics', 'AnalyticsController@index')
    ->tags(['api', 'premium', 'analytics', 'paid', 'v2']);

// Маршрут найдётся по любому из тегов
routes_by_tag('api');       // найдёт
routes_by_tag('premium');   // найдёт
routes_by_tag('analytics'); // найдёт
routes_by_tag('v2');        // найдёт
```

### Dynamic tags

```php
// Тег на основе окружения
$router->get('/feature', 'FeatureController@index')
    ->tag($env); // 'development', 'staging', 'production'

// Тег на основе версии
$router->get('/api/users', 'UserController@index')
    ->tag("api-v{$apiVersion}");
```

### Tags with shortcuts

```php
// Shortcuts автоматически добавляют теги

$router->get('/api/data', ...)->api();
// Автоматически: tag('api')

$router->get('/admin/panel', ...)->admin();
// Автоматически: tag('admin')

$router->get('/public/info', ...)->public();
// Автоматически: tag('public')
```

## 📊 Statistics by tags

```php
$stats = $router->getRouteStats();

// Маршруты с тегами
echo "Tagged routes: {$stats['tagged']}\n";

// Получить все используемые теги
$allTags = [];
foreach ($router->getRoutes() as $route) {
    $allTags = array_merge($allTags, $route->getTags());
}
$uniqueTags = array_unique($allTags);

echo "Unique tags: " . count($uniqueTags) . "\n";
foreach ($uniqueTags as $tag) {
    $count = count(routes_by_tag($tag));
    echo "  {$tag}: {$count} routes\n";
}
```

## 🆚 Comparison with competitors

| Feature | CloudCastle | FastRoute | Symfony | Laravel | Slim | Alto |
|:---|:---:|:---:|:---:|:---:|:---:|:---:|
| Tags support | ✅ | ❌ | ⚠️ | ⚠️ | ❌ | ❌ |
| Multiple tags | ✅ | ❌ | ⚠️ | ⚠️ | ❌ | ❌ |
| Filter by tag | ✅ | ❌ | ⚠️ | ⚠️ | ❌ | ❌ |
| Tags in groups | ✅ | ❌ | ❌ | ⚠️ | ❌ | ❌ |
| Tag helpers | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ |
| Auto-tags (shortcuts) | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ |

## 💡 Best Practices

### 1. Use hierarchical tags

```php
// ХОРОШО: иерархия
->tag(['api', 'api-public', 'api-public-v1'])

// Можно фильтровать на разных уровнях:
routes_by_tag('api');           // все API
routes_by_tag('api-public');    // только публичное API
routes_by_tag('api-public-v1'); // только v1 публичного API
```

### 2. Naming convention

```php
// Рекомендуемые префиксы:
'module-{name}'     // для модулей
'api-{version}'     // для версий API
'feature-{name}'    // для фич
'env-{env}'         // для окружений
'tier-{tier}'       // для тарифов
```

### 3. Document tags

```php
// В README или docs
/**
 * Available tags:
 * - api: All API endpoints
 * - api-v1: API version 1
 * - api-v2: API version 2
 * - admin: Admin panel
 * - public: Public endpoints
 * - premium: Premium features
 */
```

## ✅ Conclusion

The CloudCastle tagging system provides:

- ✅ **Flexible organization** of routes
- ✅ **Quick filtering** by criteria
- ✅ **Modular architecture**
- ✅ **Feature flags** support
- ✅ **Powerful statistics**

**The most complete implementation** of the tag system among PHP routers!

---

*Last update: October 18, 2025*

---

[📚 Table of Contents](_table-of-contents.md) | [🏠 Home](README.md)

---

[📚 Table of Contents](en/_table-of-contents.md) | [🏠 Home](en/README.md)
