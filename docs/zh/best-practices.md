[🇷🇺 Русский](ru/best-practices.md) | [🇺🇸 English](en/best-practices.md) | [🇩🇪 Deutsch](de/best-practices.md) | [🇫🇷 Français](fr/best-practices.md) | [🇨🇳 中文](zh/best-practices.md)

[📚 Table of Contents](zh/_table-of-contents.md) | [🏠 Home](zh/README.md)

---

# Best Practices - Лучшие практики CloudCastle HTTP Router

**Languages:** 🇷🇺 Русский | [🇬🇧 English](../en/best-practices.md) | [🇩🇪 Deutsch](../de/best-practices.md) | [🇫🇷 Français](../fr/best-practices.md) | [🇨🇳 中文](../zh/best-practices.md)

[📚 Оглавление](_table-of-contents.md) | [🏠 Главная](README.md)

---

## 🎯 Общие рекомендации

### 1. Всегда используйте кеширование в production

```php
use CloudCastle\Http\Router\RouteCache;

$cache = new RouteCache(__DIR__ . '/storage/cache/routes.php');
$router->setCache($cache);

if ($cache->exists()) {
    // Загрузка из кеша - в 7 раз быстрее!
    $router->loadFromCache();
} else {
    // Регистрация маршрутов
    registerRoutes($router);
    // Кеш будет автоматически сохранён
}
```

**Выгода**: 85% faster initial load, 7x improvement

### 2. Используйте named routes для URL generation

```php
// ХОРОШО: named route
$router->get('/users/{id}', 'UserController@show')
    ->name('users.show');

$url = $generator->generate('users.show', ['id' => 123]);

// ПЛОХО: hardcoded URL
$url = "/users/{$id}"; // хрупкий код, сложно рефакторить
```

**Преимущества:**
- ✅ Централизованное управление URL
- ✅ Easy refactoring
- ✅ Type-safe generation
- ✅ No typos

### 3. Группируйте логически

```php
// ХОРОШО: логическая структура
$router->group(['prefix' => '/api/v1'], function($router) {
    $router->group(['middleware' => 'auth'], function($router) {
        $router->get('/users', 'UserController@index');
        $router->get('/posts', 'PostController@index');
    });
    
    $router->get('/public', 'PublicController@index'); // без auth
});

// ПЛОХО: плоская структура
$router->get('/api/v1/users', 'UserController@index')->middleware('auth');
$router->get('/api/v1/posts', 'PostController@index')->middleware('auth');
$router->get('/api/v1/public', 'PublicController@index');
// Дублирование кода, сложнее поддерживать
```

### 4. Применяйте Rate Limiting

```php
// Public endpoints - строгий лимит
$router->get('/api/public', 'ApiController@public')
    ->perMinute(60);

// Authenticated - больше запросов
$router->group(['middleware' => 'auth'], function($router) {
    $router->get('/api/data', 'ApiController@data')
        ->perMinute(1000);
});

// Premium users - ещё больше
$router->get('/api/premium', 'ApiController@premium')
    ->perMinute(10000)
    ->middleware(['auth', 'premium']);
```

### 5. Используйте Expression Language для сложных условий

```php
// Вместо проверок в контроллере
$router->get('/premium-content', 'ContentController@premium')
    ->condition('user.age >= 18 and user.subscription == "premium"')
    ->middleware('auth');

// Вместо множественных маршрутов
$router->get('/api/v2/data', 'ApiV2Controller@data')
    ->condition('api_version >= 2');
```

## 🔒 Security Best Practices

### 1. Включите HTTPS enforcement

```php
use CloudCastle\Http\Router\Middleware\HttpsEnforcement;

if ($env === 'production') {
    $router->middleware(new HttpsEnforcement(
        redirect: true,
        permanent: true
    ));
}
```

### 2. Настройте IP Filtering для sensitive routes

```php
// Admin panel - только офисные IP
$router->group(['prefix' => '/admin'], function($router) {
    $router->whitelistIp([
        '203.0.113.0/24',  // office
        '198.51.100.50'    // VPN
    ]);
    
    $router->get('/dashboard', 'AdminController@dashboard');
});

// Blacklist известных атакующих
$router->get('/api/data', 'ApiController@data')
    ->blacklistIp($knownBadIps);
```

### 3. Используйте Auto-ban для защиты от брутфорса

```php
$banManager = new BanManager();
$router->setBanManager($banManager);

$router->enableAutoBan(
    maxAttempts: 100,     // попыток
    decayMinutes: 60,     // за период
    banDuration: 3600     // длительность бана
);
```

### 4. Включите SSRF Protection

```php
use CloudCastle\Http\Router\Middleware\SsrfProtection;

// Для маршрутов с user-generated URLs
$router->group(['middleware' => new SsrfProtection()], function($router) {
    $router->get('/proxy/{url}', 'ProxyController@fetch');
    $router->post('/webhook', 'WebhookController@handle');
});
```

### 5. Логируйте security события

```php
use CloudCastle\Http\Router\Middleware\SecurityLogger;

$router->middleware(new SecurityLogger(
    __DIR__ . '/storage/logs/security.log'
));
```

## ⚡ Performance Best Practices

### 1. Минимизируйте middleware на hot paths

```php
// ХОРОШО: минимальный middleware для публичных API
$router->get('/api/public', 'ApiController@public')
    ->middleware('cors'); // только CORS

// ПЛОХО: избыточный middleware
$router->get('/api/public', 'ApiController@public')
    ->middleware(['cors', 'auth', 'log', 'analytics', 'cache']);
// Замедляет каждый запрос!
```

### 2. Используйте regex constraints

```php
// ХОРОШО: specific constraint
$router->get('/users/{id:\d+}', 'UserController@show');
// Быстрее matching, меньше false positives

// ПЛОХО: без constraint
$router->get('/users/{id}', 'UserController@show');
// Медленнее, может совпасть с /users/profile
```

### 3. Оптимизируйте порядок маршрутов

```php
// ХОРОШО: частые маршруты первыми
$router->get('/', 'HomeController@index'); // most frequent
$router->get('/api/popular', 'ApiController@popular'); // frequent
$router->get('/rare/route', 'RareController@index'); // rare

// Роутер проверяет в порядке регистрации
// Частые маршруты сверху = быстрее поиск
```

### 4. Используйте compiled routes

```php
// Роутер автоматически компилирует паттерны
// Но вы можете помочь:

// ХОРОШО: статический паттерн
$router->get('/api/users', ...);
// Compiled: exact match, very fast

// СРЕДНЕ: простой параметр
$router->get('/users/{id}', ...);
// Compiled: regex match, fast

// МЕДЛЕННО: сложный паттерн
$router->get('/complex/{param1}/{param2}/{param3}', ...);
// Compiled: complex regex, slower
```

### 5. Lazy load маршруты

```php
// Для очень больших приложений
$router->group(['lazy' => true], function($router) {
    // Эти маршруты загрузятся только при обращении к группе
    include __DIR__ . '/routes/heavy-module.php';
});
```

## 📁 Организация кода

### 1. Разделяйте маршруты по модулям

```
routes/
├── api.php          # API endpoints
├── web.php          # Web pages
├── admin.php        # Admin panel
└── webhooks.php     # Webhooks
```

```php
// bootstrap/router.php
$router = new Router();

require __DIR__ . '/../routes/web.php';

if ($app->hasModule('api')) {
    require __DIR__ . '/../routes/api.php';
}

if ($app->hasModule('admin')) {
    require __DIR__ . '/../routes/admin.php';
}
```

### 2. Используйте YAML для больших конфигураций

```
config/routes/
├── api/
│   ├── v1.yaml
│   ├── v2.yaml
│   └── public.yaml
├── admin.yaml
└── web.yaml
```

```php
$loader = new YamlLoader($router);

// Модульная загрузка
foreach (glob(__DIR__ . '/config/routes/**/*.yaml') as $file) {
    $loader->load($file);
}
```

### 3. Используйте Attributes для MVC

```
app/Controllers/
├── Api/
│   ├── UserController.php    # Attributes inside
│   └── PostController.php
├── Admin/
│   └── DashboardController.php
└── Web/
    └── HomeController.php
```

```php
$loader = new AttributeLoader($router);
$loader->loadFromDirectory(__DIR__ . '/app/Controllers', 'App\\Controllers');
```

## 🧪 Testing Best Practices

### 1. Тестируйте маршруты

```php
public function testUserRoute(): void
{
    $router = new Router();
    $router->get('/users/{id}', fn($id) => $id);
    
    $result = $router->dispatch('/users/123', 'GET');
    $this->assertEquals('123', $result);
}
```

### 2. Тестируйте middleware

```php
public function testAuthMiddleware(): void
{
    $_SERVER['HTTP_AUTHORIZATION'] = 'Bearer test-token';
    
    $auth = new AuthMiddleware();
    $called = false;
    
    $auth->handle('/test', function() use (&$called) {
        $called = true;
        return 'response';
    });
    
    $this->assertTrue($called);
}
```

### 3. Тестируйте rate limiting

```php
public function testRateLimit(): void
{
    $router = new Router();
    $router->get('/limited', fn() => 'ok')->perMinute(5);
    
    // 5 requests - OK
    for ($i = 0; $i < 5; $i++) {
        $this->assertEquals('ok', $router->dispatch('/limited', 'GET'));
    }
    
    // 6th request - TooManyRequestsException
    $this->expectException(TooManyRequestsException::class);
    $router->dispatch('/limited', 'GET');
}
```

## 📈 Production Checklist

### Before Deploy

- [ ] Route caching enabled
- [ ] HTTPS enforcement (if applicable)
- [ ] Rate limiting configured
- [ ] Auto-ban enabled
- [ ] IP filtering for sensitive routes
- [ ] SSRF protection for user URLs
- [ ] Security logging enabled
- [ ] Error handling configured
- [ ] All tests passing
- [ ] PHPStan checks passing

### Configuration

```php
// config/router.php
return [
    'cache' => [
        'enabled' => env('ROUTER_CACHE', true),
        'path' => storage_path('cache/routes.php'),
    ],
    'security' => [
        'https' => env('FORCE_HTTPS', true),
        'ssrf_protection' => true,
        'auto_ban' => [
            'enabled' => true,
            'max_attempts' => 100,
            'decay_minutes' => 60,
            'ban_duration' => 3600,
        ],
    ],
    'rate_limiting' => [
        'default_limit' => 1000, // per minute
    ],
];
```

## 🔧 Maintenance

### 1. Регулярно очищайте cache

```php
// artisan command или cron
$cache = new RouteCache(__DIR__ . '/cache/routes.php');
$cache->clear();

// Кеш пересоздастся при следующем запросе
```

### 2. Мониторьте banned IPs

```php
$banManager = $router->getBanManager();

// Периодически проверяйте
$bannedCount = count($banManager->getAllBannedIps());

if ($bannedCount > 1000) {
    // Alert admins
    mail('admin@example.com', 'High ban count', "Currently {$bannedCount} IPs banned");
}
```

### 3. Анализируйте производительность

```php
use CloudCastle\Http\Router\Plugin\AnalyticsPlugin;

$analytics = new AnalyticsPlugin();
$router->registerGlobalPlugin($analytics);

// После некоторого времени работы
$stats = $analytics->getStats();

// Найдите медленные маршруты
foreach ($stats['routes'] as $route => $data) {
    if ($data['avg_time'] > 100) { // > 100ms
        error_log("Slow route: {$route} - {$data['avg_time']}ms");
    }
}
```

## ✅ Code Style

### 1. Консистентный стиль определения маршрутов

```php
// ХОРОШО: одинаковый стиль
$router->get('/users', 'UserController@index')
    ->name('users.index')
    ->middleware('auth');

$router->get('/posts', 'PostController@index')
    ->name('posts.index')
    ->middleware('auth');

// ПЛОХО: смешанный стиль
$router->get('/users', 'UserController@index')->name('users.index')->middleware('auth');
$router->get('/posts', fn() => PostController::index())
    ->name('posts.index')
    ->middleware('auth');
```

### 2. Используйте constants для middleware

```php
// config/middleware.php
class Middleware
{
    public const AUTH = 'auth';
    public const ADMIN = 'admin';
    public const CORS = 'cors';
    public const RATE_LIMIT = 'rate-limit';
}

// routes.php
$router->get('/admin', 'AdminController@index')
    ->middleware([Middleware::AUTH, Middleware::ADMIN]);
```

### 3. Документируйте сложные маршруты

```php
/**
 * API endpoint для получения статистики пользователя.
 * 
 * Требует:
 * - Авторизацию (auth middleware)
 * - Premium подписку (condition)
 * - Лимит 100 запросов/час
 * 
 * @param int $userId User ID
 * @return array Статистика пользователя
 */
$router->get('/api/users/{userId}/stats', 'UserStatsController@show')
    ->name('api.users.stats')
    ->where('userId', '\d+')
    ->middleware(['auth', 'premium'])
    ->condition('user.subscription == "premium"')
    ->perHour(100);
```

## 🏗️ Архитектурные паттерны

### 1. Service Layer

```php
// ХОРОШО: тонкие контроллеры
class UserController
{
    public function __construct(
        private readonly UserService $userService
    ) {}
    
    public function index(): array
    {
        return $this->userService->getAllUsers();
    }
}

$router->get('/users', [UserController::class, 'index']);

// ПЛОХО: толстые контроллеры
$router->get('/users', function() {
    $db = new PDO(...);
    $stmt = $db->query('SELECT * FROM users');
    $users = $stmt->fetchAll();
    // ... бизнес-логика в маршруте
    return $users;
});
```

### 2. Repository Pattern

```php
class UserController
{
    public function __construct(
        private readonly UserRepository $users
    ) {}
    
    public function show(int $id): User
    {
        return $this->users->find($id);
    }
}

$router->get('/users/{id}', [UserController::class, 'show'])
    ->where('id', '\d+');
```

### 3. DTO Pattern

```php
use CloudCastle\Http\Router\Loader\Route;

class UserController
{
    #[Route('/users', methods: 'POST')]
    public function store(CreateUserRequest $request): User
    {
        return User::create($request->validated());
    }
}
```

## 📊 Monitoring & Debugging

### 1. Route Dumper для documentation

```php
// Generate API documentation
$dumper = new RouteDumper($router);

// JSON для Postman/Swagger
file_put_contents(
    __DIR__ . '/docs/routes.json',
    $dumper->dumpJson()
);

// Table для CLI
file_put_contents(
    __DIR__ . '/docs/routes.txt',
    $dumper->dumpTable()
);
```

### 2. Analytics для мониторинга

```php
$analytics = new AnalyticsPlugin();
$router->registerGlobalPlugin($analytics);

// Periodic reporting
$stats = $analytics->getStats();

// Top 10 most hit routes
arsort($stats['hits']);
$top10 = array_slice($stats['hits'], 0, 10);

// Routes with errors
$errors = array_filter($stats['errors'], fn($count) => $count > 0);
```

### 3. Логирование для debugging

```php
use CloudCastle\Http\Router\Plugin\LoggerPlugin;
use Monolog\Logger;

$logger = new Logger('router');
$loggerPlugin = new LoggerPlugin($logger);
$router->registerGlobalPlugin($loggerPlugin);

// Все маршруты будут логироваться
```

## 🎨 API Design

### 1. RESTful URLs

```php
// CRUD для users
$router->get('/api/users', 'UserController@index');           // List
$router->post('/api/users', 'UserController@store');          // Create
$router->get('/api/users/{id}', 'UserController@show');       // Read
$router->put('/api/users/{id}', 'UserController@update');     // Update
$router->delete('/api/users/{id}', 'UserController@destroy'); // Delete

// Nested resources
$router->get('/api/users/{userId}/posts', 'UserPostController@index');
$router->post('/api/users/{userId}/posts', 'UserPostController@store');
```

### 2. API Versioning

**URL-based:**
```php
$router->group(['prefix' => '/api/v1'], function($router) {
    $router->get('/users', 'Api\\V1\\UserController@index');
});

$router->group(['prefix' => '/api/v2'], function($router) {
    $router->get('/users', 'Api\\V2\\UserController@index');
});
```

**Header-based:**
```php
$router->get('/api/users', 'UserController@index')
    ->condition('api_version == 2');
```

**Domain-based:**
```php
$router->get('/users', 'UserController@index')
    ->domain('v1.api.example.com');

$router->get('/users', 'UserControllerV2@index')
    ->domain('v2.api.example.com');
```

### 3. Response Format

```php
// JSON API
$router->get('/api/users', function() {
    header('Content-Type: application/json');
    return json_encode(['users' => User::all()]);
});

// XML API
$router->get('/api/users.xml', function() {
    header('Content-Type: application/xml');
    return $xmlGenerator->generate(User::all());
});
```

## 🔄 Migration Strategies

### От FastRoute

```php
// FastRoute
$dispatcher = FastRoute\simpleDispatcher(function($r) {
    $r->get('/users/{id:\d+}', 'handler');
});

// CloudCastle
$router = new Router();
$router->get('/users/{id}', 'handler')
    ->where('id', '\d+');
```

### От Laravel

```php
// Laravel
Route::get('/users/{id}', 'UserController@show')
    ->middleware('auth')
    ->where('id', '[0-9]+')
    ->name('users.show');

// CloudCastle
$router->get('/users/{id}', 'UserController@show')
    ->middleware('auth')
    ->where('id', '\d+')
    ->name('users.show');

// Практически идентичный API!
```

### От Symfony

```yaml
# Symfony routes.yaml
users_show:
    path: /users/{id}
    controller: App\Controller\UserController::show
    requirements:
        id: '\d+'
    methods: [GET]
```

```yaml
# CloudCastle routes.yaml
users_show:
  path: /users/{id}
  controller: App\Controller\UserController::show
  methods: GET
  requirements:
    id: \d+

# Очень похожий формат!
```

## ✅ Заключение

Следуя этим best practices, вы получите:

- ⚡ **Максимальную производительность** (50K+ req/sec)
- 🔒 **Максимальную безопасность** (13+ защит)
- 📈 **Лучшую поддерживаемость** кода
- 🎯 **Production-ready** приложение

CloudCastle HTTP Router спроектирован с учётом этих практик и делает их применение простым и естественным.

---

*Последнее обновление: 18 октября 2025*

---

[📚 Оглавление](_table-of-contents.md) | [🏠 Главная](README.md)

---

[📚 Table of Contents](zh/_table-of-contents.md) | [🏠 Home](zh/README.md)
