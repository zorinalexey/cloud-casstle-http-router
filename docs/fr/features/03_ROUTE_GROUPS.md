# Groupes routesurdans

---

## 📚 Navigation de la Documentation

[README](../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [API_REFERENCE](../API_REFERENCE.md) | [ALL_FEATURES](../ALL_FEATURES.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [PERFORMANCE](../PERFORMANCE_ANALYSIS.md) | [SECURITY](../SECURITY_REPORT.md) | [COMPARISON](../COMPARISON.md) | [FAQ](../FAQ.md)

**Documentation détaillée:** [01](01_BASIC_ROUTING.md) | [02](02_ROUTE_PARAMETERS.md) | [03](03_ROUTE_GROUPS.md) | [04](04_RATE_LIMITING.md) | [05](05_IP_FILTERING.md) | [06](06_MIDDLEWARE.md) | [07](07_NAMED_ROUTES.md) | [08](08_TAGS.md) | [09](09_HELPER_FUNCTIONS.md) | [10](10_ROUTE_SHORTCUTS.md) | [11](11_ROUTE_MACROS.md) | [12](12_URL_GENERATION.md) | [13](13_EXPRESSION_LANGUAGE.md) | [14](14_CACHING.md) | [15](15_PLUGINS.md) | [16](16_LOADERS.md) | [17](17_PSR_SUPPORT.md) | [18](18_ACTION_RESOLVER.md) | [19](19_STATISTICS.md) | [20](20_SECURITY.md) | [21](21_EXCEPTIONS.md) | [22](22_CLI_TOOLS.md)

---


**Catégorie:** Organisation àsur  
**Nombre de attributs:** 12  
**Complexité:** ⭐⭐ Intermédiaire chezsurdans

---

## etavecet

Groupes routesurdans pardanssur suretsurdansdans routes avec attributs partagés (préfixe, middleware, sur et ..), et et àsur tous route dans chez. sur chezsur àsur et  sur sur paretdans.

## Fonctionnalités

### 1. Préfixe (prefix)

**etchez:** `'prefix' => string`

**etavecet:** surdans préfixe àsur tous URI dans chez.

**Exemples:**

```php
// Простой префикс
Route::group(['prefix' => '/api'], function() {
    Route::get('/users', $action);     // /api/users
    Route::get('/posts', $action);     // /api/posts
});

// Версионирование API
Route::group(['prefix' => '/api/v1'], function() {
    Route::get('/users', [ApiV1UserController::class, 'index']);
    Route::get('/posts', [ApiV1PostController::class, 'index']);
});

// Вложенные префиксы
Route::group(['prefix' => '/admin'], function() {
    Route::group(['prefix' => '/users'], function() {
        Route::get('/', $action);           // /admin/users
        Route::get('/{id}', $action);       // /admin/users/{id}
    });
});

// Несколько уровней
Route::group(['prefix' => '/app'], function() {
    Route::group(['prefix' => '/api'], function() {
        Route::group(['prefix' => '/v1'], function() {
            Route::get('/data', $action);   // /app/api/v1/data
        });
    });
});
```

---

### 2. Middleware

**etchez:** `'middleware' => array|string`

**etavecet:** et middleware àsur tous route dans chez.

**Exemples:**

```php
use CloudCastle\Http\Router\Middleware\AuthMiddleware;

// Один middleware
Route::group(['middleware' => AuthMiddleware::class], function() {
    Route::get('/dashboard', $action);
    Route::get('/profile', $action);
});

// Несколько middleware
Route::group([
    'middleware' => [
        AuthMiddleware::class,
        AdminMiddleware::class,
        LoggerMiddleware::class
    ]
], function() {
    Route::get('/admin/users', $action);
    Route::get('/admin/settings', $action);
});

// Комбинация с префиксом
Route::group([
    'prefix' => '/admin',
    'middleware' => [AuthMiddleware::class, AdminMiddleware::class]
], function() {
    Route::get('/dashboard', $action);    // /admin/dashboard + Auth + Admin
    Route::get('/users', $action);        // /admin/users + Auth + Admin
});

// Вложенные middleware (накапливаются)
Route::group(['middleware' => AuthMiddleware::class], function() {
    Route::group(['middleware' => AdminMiddleware::class], function() {
        Route::get('/admin/settings', $action);  // Auth + Admin middleware
    });
});
```

---

### 3. sur (domain)

**etchez:** `'domain' => string`

**etavecet:** etdansdans routes à sursurchez surchez etet parsurchez.

**Exemples:**

```php
// Поддомен API
Route::group(['domain' => 'api.example.com'], function() {
    Route::get('/users', $action);
    Route::get('/posts', $action);
});

// Админка на отдельном поддомене
Route::group(['domain' => 'admin.example.com'], function() {
    Route::get('/dashboard', $action);
    Route::get('/users', $action);
});

// Динамический поддомен (wildcard)
Route::group(['domain' => '{subdomain}.example.com'], function() {
    Route::get('/', function($subdomain) {
        return "Subdomain: $subdomain";
    });
});

// Мультитенант приложение
Route::group(['domain' => '{tenant}.app.com'], function() {
    Route::get('/dashboard', [TenantController::class, 'dashboard']);
    // tenant передается в контроллер
});

// Комбинация домен + префикс
Route::group([
    'domain' => 'api.example.com',
    'prefix' => '/v1'
], function() {
    Route::get('/users', $action);  // api.example.com/v1/users
});
```

---

### 4. sur (port)

**etchez:** `'port' => int`

**etavecet:** etdansdans routes à sursurchez parchez.

**Exemples:**

```php
// Админка на порту 8080
Route::group(['port' => 8080], function() {
    Route::get('/admin', $action);
    Route::get('/debug', $action);
});

// Микросервисы на разных портах
Route::group(['port' => 8081, 'prefix' => '/users'], function() {
    Route::get('/', [UserServiceController::class, 'index']);
});

Route::group(['port' => 8082, 'prefix' => '/products'], function() {
    Route::get('/', [ProductServiceController::class, 'index']);
});

// WebSocket на порту 3000
Route::group([
    'port' => 3000,
    'protocol' => ['ws', 'wss']
], function() {
    Route::get('/chat', [WebSocketController::class, 'chat']);
});
```

---

### 5. Namespace

**etchez:** `'namespace' => string`

**etavecet:** avecsurdansetdans namespace pour contrôleursurdans dans chez.

**Exemples:**

```php
// API контроллеры
Route::group([
    'namespace' => 'App\\Controllers\\Api',
    'prefix' => '/api'
], function() {
    Route::get('/users', 'UserController@index');
    // → App\Controllers\Api\UserController::index
});

// Админ контроллеры
Route::group([
    'namespace' => 'App\\Controllers\\Admin',
    'prefix' => '/admin'
], function() {
    Route::get('/users', 'UserController@index');
    // → App\Controllers\Admin\UserController::index
});

// Вложенные namespaces
Route::group(['namespace' => 'App\\Controllers'], function() {
    Route::group(['namespace' => 'Api'], function() {
        Route::get('/api/users', 'UserController@index');
        // → App\Controllers\Api\UserController::index
    });
});
```

---

### 6. HTTPS requirement

**etchez:** `'https' => bool`

**etavecet:** chez HTTPS pour tous routesurdans dans chez.

**Exemples:**

```php
// Защищенные страницы
Route::group(['https' => true], function() {
    Route::get('/payment', $action);
    Route::post('/checkout', $action);
});

// Админка только HTTPS
Route::group([
    'prefix' => '/admin',
    'https' => true,
    'middleware' => [AuthMiddleware::class]
], function() {
    Route::get('/dashboard', $action);
    Route::get('/settings', $action);
});

// API только HTTPS
Route::group([
    'prefix' => '/api',
    'domain' => 'api.example.com',
    'https' => true
], function() {
    Route::post('/users', $action);
    Route::post('/auth', $action);
});
```

---

### 7. desuràsur (protocols)

**etchez:** `'protocols' => array`

**etavecet:**  desuràsur pour groupes routesurdans.

**Exemples:**

```php
// WebSocket маршруты
Route::group(['protocols' => ['ws', 'wss']], function() {
    Route::get('/chat', $action);
    Route::get('/notifications', $action);
});

// Только HTTPS
Route::group(['protocols' => ['https']], function() {
    Route::post('/payment', $action);
});

// HTTP и HTTPS
Route::group(['protocols' => ['http', 'https']], function() {
    Route::get('/public', $action);
});

// Комбинация
Route::group([
    'domain' => 'ws.example.com',
    'protocols' => ['ws', 'wss'],
    'port' => 3000
], function() {
    Route::get('/realtime', $action);
});
```

---

### 8. et (tags)

**etchez:** `'tags' => array|string`

**etavecet:** surdans et àsur tous route dans chez.

**Exemples:**

```php
// API теги
Route::group(['tags' => 'api'], function() {
    Route::get('/users', $action);
    Route::get('/posts', $action);
    // Оба маршрута с тегом 'api'
});

// Множественные теги
Route::group(['tags' => ['api', 'public']], function() {
    Route::get('/data', $action);
});

// Вложенные теги (накапливаются)
Route::group(['tags' => 'api'], function() {
    Route::group(['tags' => 'v1'], function() {
        Route::get('/users', $action);  // Теги: 'api', 'v1'
    });
});

// Организация по функциональности
Route::group(['tags' => ['admin', 'protected']], function() {
    Route::get('/admin/users', $action);
    Route::get('/admin/settings', $action);
});
```

---

### 9. Throttle (rate limiting)

**etchez:** `'throttle' => [int $maxAttempts, int $decayMinutes]`

**etavecet:** Rate limiting pour tous groupes.

**Exemples:**

```php
// API с общим лимитом
Route::group(['throttle' => [100, 1]], function() {
    Route::get('/users', $action);
    Route::get('/posts', $action);
    // 100 запросов/мин на ВСЮ группу
});

// Строгий лимит для админки
Route::group([
    'prefix' => '/admin',
    'throttle' => [30, 1]
], function() {
    Route::post('/settings', $action);
    Route::post('/users', $action);
});

// Разные лимиты для разных групп
Route::group(['prefix' => '/api/free', 'throttle' => [100, 60]], function() {
    Route::get('/data', $action);  // 100/час
});

Route::group(['prefix' => '/api/pro', 'throttle' => [10000, 60]], function() {
    Route::get('/data', $action);  // 10000/час
});
```

---

### 10. IP Whitelist

**etchez:** `'whitelistIp' => array`

**etavecet:** et suravecchez suràsur avec chezà IP avecsurdans.

**Exemples:**

```php
// Админка только с офиса
Route::group([
    'prefix' => '/admin',
    'whitelistIp' => ['192.168.1.0/24']
], function() {
    Route::get('/dashboard', $action);
    Route::get('/users', $action);
});

// API только с доверенных серверов
Route::group([
    'prefix' => '/api/internal',
    'whitelistIp' => [
        '10.0.1.100',
        '10.0.1.101',
        '10.0.1.102'
    ]
], function() {
    Route::post('/sync', $action);
    Route::post('/backup', $action);
});

// Комбинация с другими атрибутами
Route::group([
    'prefix' => '/admin',
    'middleware' => [AuthMiddleware::class, AdminMiddleware::class],
    'whitelistIp' => ['192.168.1.0/24'],
    'https' => true
], function() {
    Route::get('/critical', $action);
});
```

---

### 11. IP Blacklist

**etchez:** `'blacklistIp' => array`

**etavecet:** et suravecchez avec chezà IP avecsurdans.

**Exemples:**

```php
// Блокировка известных плохих IP
Route::group([
    'blacklistIp' => [
        '1.2.3.4',
        '5.6.7.8',
        '9.10.11.0/24'
    ]
], function() {
    Route::get('/public', $action);
    Route::get('/api/data', $action);
});

// Защита API от абьюза
Route::group([
    'prefix' => '/api',
    'blacklistIp' => $bannedIps  // Массив из БД
], function() {
    Route::get('/users', $action);
});
```

---

### 12.  groupes (name prefix)

**etchez:** `'name' => string`

**etavecet:** Préfixe pour et routesurdans dans chez.

**Exemples:**

```php
// Префикс имени
Route::group(['name' => 'admin.'], function() {
    Route::get('/users', $action)->name('users');        // Имя: admin.users
    Route::get('/settings', $action)->name('settings');  // Имя: admin.settings
});

// Вложенные префиксы
Route::group(['name' => 'api.'], function() {
    Route::group(['name' => 'v1.'], function() {
        Route::get('/users', $action)->name('users');    // Имя: api.v1.users
    });
});

// Комбинация с prefix
Route::group([
    'prefix' => '/api/v1',
    'name' => 'api.v1.'
], function() {
    Route::get('/users', $action)->name('users.index');  
    // URI: /api/v1/users
    // Имя: api.v1.users.index
});
```

---

## surdanssur suret RouteGroup

**Méthode:** `Route::group(): RouteGroup`

**etavecet:** Méthode group() danssurdans surà RouteGroup avec méthodeet pour de avec chezpar.

**Méthodes RouteGroup:**

```php
$group = Route::group(['prefix' => '/api'], function() {
    Route::get('/users', $action);
    Route::get('/posts', $action);
});

// Получить маршруты группы
$routes = $group->getRoutes();
// [Route, Route]

// Количество маршрутов
$count = $group->count();
// 2

// Получить атрибуты группы
$attrs = $group->getAttributes();
// ['prefix' => '/api']

// Проверить наличие маршрута
foreach ($group->getRoutes() as $route) {
    echo $route->getUri() . "\n";
}
```

**Exemple etavecparsurdanset:**

```php
$apiGroup = Route::group(['prefix' => '/api', 'tags' => 'api'], function() {
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/posts', [PostController::class, 'index']);
    Route::get('/comments', [CommentController::class, 'index']);
});

// Получить все API маршруты
$apiRoutes = $apiGroup->getRoutes();
echo "API routes count: " . $apiGroup->count();

// Применить дополнительный middleware ко всем
foreach ($apiRoutes as $route) {
    $route->middleware([RateLimitMiddleware::class]);
}
```

---

## sur groupes

**etavecet:** Groupes surchez  danssuret, attributs suràetdansavec.

**Exemples:**

```php
// 2 уровня
Route::group(['prefix' => '/api'], function() {
    Route::group(['prefix' => '/v1'], function() {
        Route::get('/users', $action);  // /api/v1/users
    });
});

// 3 уровня
Route::group(['prefix' => '/app'], function() {
    Route::group(['middleware' => AuthMiddleware::class], function() {
        Route::group(['prefix' => '/admin'], function() {
            Route::get('/users', $action);  
            // /app/admin/users + AuthMiddleware
        });
    });
});

// Накопление middleware
Route::group(['middleware' => CorsMiddleware::class], function() {
    Route::group(['middleware' => AuthMiddleware::class], function() {
        Route::group(['middleware' => AdminMiddleware::class], function() {
            Route::get('/admin/critical', $action);
            // CorsMiddleware + AuthMiddleware + AdminMiddleware
        });
    });
});

// Накопление тегов
Route::group(['tags' => 'api'], function() {
    Route::group(['tags' => 'v1'], function() {
        Route::group(['tags' => 'public'], function() {
            Route::get('/data', $action);  // Теги: api, v1, public
        });
    });
});
```

---

##  et

### etàsuravecdansetavec

```php
// User Service
Route::group([
    'port' => 8081,
    'prefix' => '/users',
    'tags' => 'user-service',
    'domain' => 'users.services.local'
], function() {
    Route::get('/', [UserServiceController::class, 'index']);
    Route::get('/{id}', [UserServiceController::class, 'show']);
    Route::post('/', [UserServiceController::class, 'create']);
});

// Product Service
Route::group([
    'port' => 8082,
    'prefix' => '/products',
    'tags' => 'product-service',
    'domain' => 'products.services.local'
], function() {
    Route::get('/', [ProductServiceController::class, 'index']);
    Route::get('/{id}', [ProductServiceController::class, 'show']);
});
```

### SaaS sur

```php
// Free tier
Route::group([
    'prefix' => '/api/free',
    'throttle' => [100, 60],  // 100/час
    'tags' => 'free-tier',
    'middleware' => [AuthMiddleware::class]
], function() {
    Route::get('/data', $action);
    Route::get('/stats', $action);
});

// Pro tier
Route::group([
    'prefix' => '/api/pro',
    'throttle' => [10000, 60],  // 10000/час
    'tags' => 'pro-tier',
    'middleware' => [AuthMiddleware::class, ProMiddleware::class]
], function() {
    Route::get('/data', $action);
    Route::get('/analytics', $action);
    Route::post('/export', $action);
});

// Enterprise tier
Route::group([
    'prefix' => '/api/enterprise',
    'throttle' => [100000, 60],  // 100000/час
    'tags' => 'enterprise-tier',
    'middleware' => [AuthMiddleware::class, EnterpriseMiddleware::class]
], function() {
    Route::get('/data', $action);
    Route::get('/analytics', $action);
    Route::post('/export', $action);
    Route::post('/custom', $action);
});
```

### chezetsursur etsuret

```php
// Главный сайт
Route::group(['domain' => 'example.com'], function() {
    Route::get('/', [HomeController::class, 'index']);
    Route::get('/about', [AboutController::class, 'index']);
});

// API
Route::group([
    'domain' => 'api.example.com',
    'prefix' => '/v1',
    'https' => true,
    'tags' => 'api'
], function() {
    Route::group(['middleware' => [CorsMiddleware::class]], function() {
        Route::get('/users', [ApiUserController::class, 'index']);
        Route::post('/users', [ApiUserController::class, 'store']);
    });
});

// Админка
Route::group([
    'domain' => 'admin.example.com',
    'https' => true,
    'middleware' => [AuthMiddleware::class, AdminMiddleware::class],
    'whitelistIp' => ['192.168.1.0/24']
], function() {
    Route::get('/dashboard', [AdminController::class, 'dashboard']);
    Route::resource('/users', AdminUserController::class);
});
```

---

## àsuretet

### ✅ sursuret àetàet

1. **chezetchez suretavecàet avecdans routes**
   ```php
   // ✅ Хорошо
   Route::group(['prefix' => '/admin'], function() {
       // Все админские маршруты
   });
   ```

2. **avecparchez danssursuravec pour etetet**
   ```php
   // ✅ Хорошо - ясная иерархия
   Route::group(['prefix' => '/api'], function() {
       Route::group(['prefix' => '/v1'], function() {
           // API v1
       });
   });
   ```

3. **et partagé middleware**
   ```php
   // ✅ Хорошо - один раз для всех
   Route::group(['middleware' => AuthMiddleware::class], function() {
       // Все защищенные маршруты
   });
   ```

### ❌ Anti-patterns

1. ** avecsurdans avecetàsur chezsuràet danssursuravecet**
   ```php
   // ❌ Плохо - слишком много уровней
   Route::group([...], function() {
       Route::group([...], function() {
           Route::group([...], function() {
               Route::group([...], function() {
                   // Слишком глубоко!
               });
           });
       });
   });
   ```

2. ** chezetchez attributs**
   ```php
   // ❌ Плохо
   Route::group(['middleware' => AuthMiddleware::class], function() {
       Route::get('/page1', $action)->middleware([AuthMiddleware::class]);  // Дубликат!
   });
   ```

---

## Performance

| et |  | Remarque |
|----------|-------|-----------|
| suret groupes | ~10μs |  avecsur |
| sursur groupe | +5μs/chezsurdans | etet overhead |

---

## Voir aussi

- [Базовая маршрутизация](01_BASIC_ROUTING.md)
- [Middleware](06_MIDDLEWARE.md)
- [Rate Limiting](04_RATE_LIMITING.md)
- [IP Filtering](05_IP_FILTERING.md)

---

**Version:** 1.1.1  
** sursurdanset:** à 2025  
**chezavec:** ✅ etsur chezàetsursursuravec


---

## 📚 Navigation de la Documentation

[README](../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [API_REFERENCE](../API_REFERENCE.md) | [ALL_FEATURES](../ALL_FEATURES.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [FAQ](../FAQ.md)

**Documentation détaillée:** [01](01_BASIC_ROUTING.md) | [02](02_ROUTE_PARAMETERS.md) | [03](03_ROUTE_GROUPS.md) | [04](04_RATE_LIMITING.md) | [05](05_IP_FILTERING.md) | [06](06_MIDDLEWARE.md) | [07](07_NAMED_ROUTES.md) | [08](08_TAGS.md) | [09](09_HELPER_FUNCTIONS.md) | [10](10_ROUTE_SHORTCUTS.md) | [11](11_ROUTE_MACROS.md) | [12](12_URL_GENERATION.md) | [13](13_EXPRESSION_LANGUAGE.md) | [14](14_CACHING.md) | [15](15_PLUGINS.md) | [16](16_LOADERS.md) | [17](17_PSR_SUPPORT.md) | [18](18_ACTION_RESOLVER.md) | [19](19_STATISTICS.md) | [20](20_SECURITY.md) | [21](21_EXCEPTIONS.md) | [22](22_CLI_TOOLS.md)

**© 2024 CloudCastle HTTP Router**
