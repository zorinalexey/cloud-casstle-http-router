# Rate Limiting & Auto-Ban

---

## 📚 Navigation de la Documentation

[README](../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [API_REFERENCE](../API_REFERENCE.md) | [ALL_FEATURES](../ALL_FEATURES.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [PERFORMANCE](../PERFORMANCE_ANALYSIS.md) | [SECURITY](../SECURITY_REPORT.md) | [COMPARISON](../COMPARISON.md) | [FAQ](../FAQ.md)

**Documentation détaillée:** [01](01_BASIC_ROUTING.md) | [02](02_ROUTE_PARAMETERS.md) | [03](03_ROUTE_GROUPS.md) | [04](04_RATE_LIMITING.md) | [05](05_IP_FILTERING.md) | [06](06_MIDDLEWARE.md) | [07](07_NAMED_ROUTES.md) | [08](08_TAGS.md) | [09](09_HELPER_FUNCTIONS.md) | [10](10_ROUTE_SHORTCUTS.md) | [11](11_ROUTE_MACROS.md) | [12](12_URL_GENERATION.md) | [13](13_EXPRESSION_LANGUAGE.md) | [14](14_CACHING.md) | [15](15_PLUGINS.md) | [16](16_LOADERS.md) | [17](17_PSR_SUPPORT.md) | [18](18_ACTION_RESOLVER.md) | [19](19_STATISTICS.md) | [20](20_SECURITY.md) | [21](21_EXCEPTIONS.md) | [22](22_CLI_TOOLS.md)

---


**Catégorie:** Sécurité  
**Nombre de méthodes:** 15  
**Complexité:** ⭐⭐⭐ Avancé chezsurdans

---

## etavecet

Rate Limiting (suretet avecde requêtes) et Auto-Ban (danssuretavecà suràetsurdansà) - sur sur dansavecsur et et de DDoS à, chez-suravec et surchezparet API.

## Fonctionnalités

### Rate Limiting (8 méthodes)

#### 1. surdans throttle

**Méthode:** `throttle(int $maxAttempts, int $decayMinutes, ?callable $keyResolver = null): Route`

**etavecet:** etet àsuretavecdans requêtes à routechez.

**Paramètres:**
- `$maxAttempts` - àavecetsur àsuretavecdanssur requêtes
- `$decayMinutes` - etsur danset dans etchez
- `$keyResolver` - etsursursur chezàet pour suret à (par défaut IP)

**Exemples:**

```php
// 60 запросов в минуту
Route::post('/api/submit', $action)
    ->throttle(60, 1);

// 100 запросов в час
Route::post('/api/upload', $action)
    ->throttle(100, 60);

// 1000 запросов в день
Route::get('/api/public', $action)
    ->throttle(1000, 1440);

// С контроллером
Route::post('/login', [AuthController::class, 'login'])
    ->throttle(5, 1);  // 5 попыток входа в минуту
```

**à de:**
1. et àsur requête chezdansetetdansavec avecetà pour IP (etet àavecsursursur à)
2. avecet avecetà dans etet - dansavecdansavec `TooManyRequestsException`
3.  chezàsur dans avecetà avecavecdansavec

---

#### 2. TimeUnit enum

**Enum:** `CloudCastle\Http\Router\TimeUnit`

**etavecet:** etavecet pour chezsursur de avec danset etetet.

**Valeurs:**
```php
TimeUnit::SECOND->value  // 1/60 минуты
TimeUnit::MINUTE->value  // 1 минута
TimeUnit::HOUR->value    // 60 минут
TimeUnit::DAY->value     // 1440 минут
TimeUnit::WEEK->value    // 10080 минут
TimeUnit::MONTH->value   // 43200 минут
```

**Exemples:**

```php
use CloudCastle\Http\Router\TimeUnit;

// 5 запросов в секунду
Route::post('/api/realtime', $action)
    ->throttle(5, TimeUnit::SECOND->value);

// 100 запросов в минуту
Route::post('/api/normal', $action)
    ->throttle(100, TimeUnit::MINUTE->value);

// 1000 запросов в час
Route::get('/api/hourly', $action)
    ->throttle(1000, TimeUnit::HOUR->value);

// 10000 запросов в день
Route::get('/api/daily', $action)
    ->throttle(10000, TimeUnit::DAY->value);

// 50000 запросов в неделю
Route::post('/api/weekly', $action)
    ->throttle(50000, TimeUnit::WEEK->value);

// 200000 запросов в месяц
Route::post('/api/monthly', $action)
    ->throttle(200000, TimeUnit::MONTH->value);
```

**Avantages:**
- etsuravec àsur
-  etavecàet etavec
- IDE danssursurparet

---

#### 3. Personnalisé à throttle

**etavecet:** avecparsurdanset àavecsursur chezàetet pour suret à contraintes.

**Exemples:**

```php
// По ID пользователя
Route::post('/api/user-action', $action)
    ->throttle(30, 1, function($request) {
        return 'user_' . ($request->userId ?? 'guest');
    });

// По комбинации IP + User Agent
Route::post('/api/combined', $action)
    ->throttle(60, 1, function($request) {
        $ip = $request->ip();
        $ua = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
        return md5($ip . $ua);
    });

// По API ключу
Route::post('/api/endpoint', $action)
    ->throttle(1000, 60, function($request) {
        $apiKey = $_SERVER['HTTP_X_API_KEY'] ?? 'default';
        return 'api_' . $apiKey;
    });

// По email для восстановления пароля
Route::post('/password/reset', $action)
    ->throttle(3, 60, function($request) {
        return 'reset_' . ($_POST['email'] ?? 'unknown');
    });

// Глобальный лимит для всего приложения
Route::post('/api/global', $action)
    ->throttle(10000, 1, fn() => 'global_limit');
```

**avecparsurdanset:**
- etet par parsurdans,   par IP
- et de avec à
- etàsur chezdanset etetet
- API àdansde

---

#### 4. Obtenir RateLimiter

**Méthode:** `getRateLimiter(): ?RateLimiter`

**etavecet:** Obtenir surà RateLimiter pour sursur de.

**Exemples:**

```php
$route = Route::post('/api/data', $action)
    ->throttle(60, 1);

$rateLimiter = $route->getRateLimiter();

if ($rateLimiter) {
    // Работа с RateLimiter
    $max = $rateLimiter->getMaxAttempts();        // 60
    $decay = $rateLimiter->getDecayMinutes();     // 1
    
    // Проверить лимит для конкретного IP
    $ip = '192.168.1.1';
    if ($rateLimiter->tooManyAttempts($ip)) {
        $seconds = $rateLimiter->availableIn($ip);
        echo "Retry after $seconds seconds";
    }
}
```

---

#### 5. Méthodes RateLimiter àavecavec

**avecavec:** `CloudCastle\Http\Router\RateLimiter`

**Méthodes:**

```php
use CloudCastle\Http\Router\RateLimiter;

// Создание
$limiter = new RateLimiter(60, 1);  // 60 запросов в минуту

// Проверка превышения лимита
$tooMany = $limiter->tooManyAttempts('192.168.1.1');
// true если превышен лимит

// Добавить попытку
$limiter->attempt('192.168.1.1');

// Сколько попыток осталось
$remaining = $limiter->remaining('192.168.1.1');
// 59, 58, 57...

// Через сколько секунд доступно
$seconds = $limiter->availableIn('192.168.1.1');
// 45 (если осталось 45 секунд до сброса)

// Сбросить счетчик для IP
$limiter->clear('192.168.1.1');

// Очистить всё
$limiter->clearAll();

// Получить максимум
$max = $limiter->getMaxAttempts();  // 60

// Получить период
$decay = $limiter->getDecayMinutes();  // 1

// Установить BanManager
$banManager = new BanManager(5, 3600);
$limiter->setBanManager($banManager);

// Получить BanManager
$banManager = $limiter->getBanManager();
```

**Exemple etavecparsurdanset:**

```php
Route::post('/api/action', function() {
    $route = Route::current();
    $limiter = $route->getRateLimiter();
    $ip = $_SERVER['REMOTE_ADDR'];
    
    if ($limiter && $limiter->tooManyAttempts($ip)) {
        $seconds = $limiter->availableIn($ip);
        $remaining = $limiter->remaining($ip);
        
        return response()->json([
            'error' => 'Too many requests',
            'retry_after' => $seconds,
            'remaining' => $remaining
        ], 429);
    }
    
    // Обработка запроса
    $limiter?->attempt($ip);
    
    return 'Success';
})
->throttle(60, 1);
```

---

#### 6-8. Shortcuts pour throttle

**Méthodes:**
- `throttleStandard(): Route` - 60 requêtes/et
- `throttleStrict(): Route` - 10 requêtes/et
- `throttleGenerous(): Route` - 1000 requêtes/et

**Exemples:**

```php
// 60 запросов в минуту (стандарт)
Route::post('/api/standard', $action)
    ->throttleStandard();
// Эквивалентно: ->throttle(60, 1)

// 10 запросов в минуту (строгий)
Route::post('/api/critical', $action)
    ->throttleStrict();
// Эквивалентно: ->throttle(10, 1)

// 1000 запросов в минуту (щедрый)
Route::post('/api/bulk', $action)
    ->throttleGenerous();
// Эквивалентно: ->throttle(1000, 1)
```

**avecparsurdanset:**
- avec suravecsurà  et
-  avec
- et àsur

---

### Auto-Ban System (7 méthodes)

#### 1. suret BanManager

**avecavec:** `CloudCastle\Http\Router\BanManager`

**suravecchezàsur:** `__construct(int $maxViolations = 5, int $banDuration = 3600)`

**Paramètres:**
- `$maxViolations` - Nombre de surchezet sur sur (default: 5)
- `$banDuration` - etsuravec sur dans avecàchez (default: 3600 = 1 avec)

**Exemples:**

```php
use CloudCastle\Http\Router\BanManager;

// 5 нарушений = бан на 1 час
$banManager = new BanManager(5, 3600);

// 3 нарушения = бан на 24 часа
$banManager = new BanManager(3, 86400);

// 10 нарушений = бан на 30 минут
$banManager = new BanManager(10, 1800);

// 1 нарушение = мгновенный бан навсегда
$banManager = new BanManager(1, 0);
```

---

#### 2. àet Auto-Ban

**Méthode:** `enableAutoBan(int $violations): void`

**etavecet:** àetdansetchez danssuretavecàchez suràetsurdansàchez paravec N surchezet.

**Exemples:**

```php
$banManager = new BanManager();

// Включить автобан после 5 нарушений
$banManager->enableAutoBan(5);

// После 5 превышений throttle - IP автоматически банится
```

---

#### 3. chezsur suràetsurdansà IP

**Méthode:** `ban(string $ip, int $duration): void`

**Paramètres:**
- `$ip` - IP avec pour suràetsurdansàet
- `$duration` - etsuravec sur dans avecàchez (0 = surtous)

**Exemples:**

```php
$banManager = new BanManager();

// Забанить на 1 час
$banManager->ban('1.2.3.4', 3600);

// Забанить на сутки
$banManager->ban('5.6.7.8', 86400);

// Забанить навсегда
$banManager->ban('9.10.11.12', 0);

// Динамическая блокировка
if ($suspiciousActivity) {
    $banManager->ban($_SERVER['REMOTE_ADDR'], 7200);  // 2 часа
}
```

---

#### 4. suràetsurdansà IP

**Méthode:** `unban(string $ip): void`

**Exemples:**

```php
// Разбанить IP
$banManager->unban('1.2.3.4');

// Массовая разблокировка
$bannedIps = $banManager->getBannedIps();
foreach ($bannedIps as $ip) {
    if (isWhitelisted($ip)) {
        $banManager->unban($ip);
    }
}
```

---

#### 5. surdansà sur

**Méthode:** `isBanned(string $ip): bool`

**Exemples:**

```php
use CloudCastle\Http\Router\Exceptions\BannedException;

$banManager = new BanManager();

// Проверка в middleware
if ($banManager->isBanned($_SERVER['REMOTE_ADDR'])) {
    throw new BannedException('Your IP is banned');
}

// Проверка перед обработкой
Route::post('/api/action', function() use ($banManager) {
    $ip = $_SERVER['REMOTE_ADDR'];
    
    if ($banManager->isBanned($ip)) {
        return response()->json([
            'error' => 'IP banned'
        ], 403);
    }
    
    // Обработка
});
```

---

#### 6. Obtenir avecetavecà  IP

**Méthode:** `getBannedIps(): array`

**Exemples:**

```php
$bannedIps = $banManager->getBannedIps();
// ['1.2.3.4', '5.6.7.8', ...]

// Показать админу
foreach ($bannedIps as $ip) {
    echo "Banned: $ip<br>";
}

// Экспорт в файл
file_put_contents('banned.txt', implode("\n", $bannedIps));

// Статистика
$count = count($bannedIps);
echo "Total banned IPs: $count";
```

---

#### 7. etavecà tous surdans

**Méthode:** `clearAll(): void`

**Exemples:**

```php
// Очистить все баны
$banManager->clearAll();

// Очистка по расписанию (cron)
if (date('H') === '00') {  // В полночь
    $banManager->clearAll();
}

// Очистка старых банов
$banManager->clearAll();  // Сбросить всё
```

---

## et Rate Limiting et Auto-Ban

### sur et

```php
use CloudCastle\Http\Router\BanManager;
use CloudCastle\Http\Router\Facade\Route;

// Создать BanManager
$banManager = new BanManager(
    maxViolations: 5,      // 5 нарушений
    banDuration: 3600      // Бан на 1 час
);

// Включить автобан
$banManager->enableAutoBan(5);

// Маршрут с защитой
Route::post('/login', [AuthController::class, 'login'])
    ->throttle(3, 1)  // 3 попытки в минуту
    ->getRateLimiter()
    ?->setBanManager($banManager);

// При превышении лимита 5 раз → автоматический бан на 1 час
```

### suret de:

1. **surà 1-3:** sursur de
2. **surà 4:** danset etet → `TooManyRequestsException`
3. **suràet 5-9:** chezet suràetdansavec
4. **surà 10:** 5- surchezet → **danssur sur 1 avec**
5. **chezet paràet:** `BannedException`

---

##  etavecparsurdanset

### 1. et danssuretetet

```php
$banManager = new BanManager(3, 86400);  // 3 неудачи = бан на сутки

Route::post('/login', [AuthController::class, 'login'])
    ->throttle(5, 1)
    ->getRateLimiter()
    ?->setBanManager($banManager);
```

### 2. API avec àdansdeet

```php
// Free tier: 100 запросов/час
Route::group(['prefix' => '/api/free'], function() {
    Route::get('/data', $action)
        ->throttle(100, 60);
});

// Pro tier: 10000 запросов/час
Route::group(['prefix' => '/api/pro'], function() {
    Route::get('/data', $action)
        ->throttle(10000, 60);
});
```

### 3. et de avecet

```php
Route::get('/products/{id}', [ProductController::class, 'show'])
    ->throttle(100, 1);  // Не более 100 товаров в минуту
```

### 4. suravecavecsurdanset sur

```php
$banManager = new BanManager(3, 3600);

Route::post('/password/reset', [PasswordController::class, 'reset'])
    ->throttle(3, 60, fn($req) => 'reset_' . ($_POST['email'] ?? 'unknown'))
    ->getRateLimiter()
    ?->setBanManager($banManager);
```

### 5. etavecet

```php
Route::post('/register', [RegisterController::class, 'store'])
    ->throttle(3, 60);  // 3 регистрации в час с одного IP
```

---

## deà etavecàet

```php
use CloudCastle\Http\Router\Exceptions\TooManyRequestsException;
use CloudCastle\Http\Router\Exceptions\BannedException;

try {
    $route = Route::dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
    echo $route->run();
    
} catch (BannedException $e) {
    http_response_code(403);
    echo json_encode([
        'error' => 'IP banned',
        'message' => $e->getMessage()
    ]);
    
} catch (TooManyRequestsException $e) {
    http_response_code(429);
    $retryAfter = $e->getRetryAfter();
    header("Retry-After: $retryAfter");
    
    echo json_encode([
        'error' => 'Too many requests',
        'retry_after' => $retryAfter
    ]);
}
```

---

## àsuretet

### ✅ sursuret àetàet

1. ** etet pour  paretsurdans**
   ```php
   Route::get('/api/public', $action)->throttle(1000, 1);    // Щедро
   Route::post('/login', $action)->throttle(5, 1);          // Строго
   Route::post('/api/write', $action)->throttle(60, 1);     // Средне
   ```

2. **avecparchez auto-ban pour àetet suret**
   ```php
   $banManager = new BanManager(3, 86400);
   Route::post('/admin/login', $action)
       ->throttle(3, 1)
       ->getRateLimiter()
       ?->setBanManager($banManager);
   ```

3. **avecsur àet pour parsurdans**
   ```php
   Route::post('/api/action', $action)
       ->throttle(100, 1, fn($req) => 'user_' . $req->userId);
   ```

### ❌ Anti-patterns

1. ** avecdans avecetàsur etàet etet**
   ```php
   // ❌ Плохо - даже легальные пользователи будут заблокированы
   Route::get('/api/data', $action)->throttle(1, 1);
   ```

2. ** dans sur API-àet**
   ```php
   // ❌ Плохо - лимит по IP, один пользователь заблокирует всех
   Route::post('/api/endpoint', $action)->throttle(100, 1);
   
   // ✅ Хорошо - лимит по API-ключу
   Route::post('/api/endpoint', $action)
       ->throttle(100, 1, fn($req) => 'api_' . $req->apiKey);
   ```

---

## Performance

| et |  |  |
|----------|-------|--------|
| surdansà throttle | ~640μs | ~3.5 MB |
| Ban check | ~100μs | ~1 MB |
| surdanset dans ban list | ~50μs | ~200 KB |

**danssur:** etetsur dansetet sur suretdanssuretsuravec

---

## Sécurité

### et de:

- ✅ **DDoS à** - Rate limiting
- ✅ **chez-suravec** - Auto-ban paravec surchezet
- ✅ **API abuse** - dansde par à
- ✅ **avecet àsur** - etet sur et
- ✅ **Spam** - suret etet sur POST

---

## Voir aussi

- [IP Filtering](05_IP_FILTERING.md) - surparetsur et par IP
- [Middleware](06_MIDDLEWARE.md) - SecurityLogger, AuthMiddleware
- [Безопасность](20_SECURITY.md) - Partagé sursur suravecsuravecet
- [Исключения](21_EXCEPTIONS.md) - deà suretsurà

---

**Version:** 1.1.1  
** sursurdanset:** à 2025  
**chezavec:** ✅ Production-ready


---

## 📚 Navigation de la Documentation

[README](../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [API_REFERENCE](../API_REFERENCE.md) | [ALL_FEATURES](../ALL_FEATURES.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [FAQ](../FAQ.md)

**Documentation détaillée:** [01](01_BASIC_ROUTING.md) | [02](02_ROUTE_PARAMETERS.md) | [03](03_ROUTE_GROUPS.md) | [04](04_RATE_LIMITING.md) | [05](05_IP_FILTERING.md) | [06](06_MIDDLEWARE.md) | [07](07_NAMED_ROUTES.md) | [08](08_TAGS.md) | [09](09_HELPER_FUNCTIONS.md) | [10](10_ROUTE_SHORTCUTS.md) | [11](11_ROUTE_MACROS.md) | [12](12_URL_GENERATION.md) | [13](13_EXPRESSION_LANGUAGE.md) | [14](14_CACHING.md) | [15](15_PLUGINS.md) | [16](16_LOADERS.md) | [17](17_PSR_SUPPORT.md) | [18](18_ACTION_RESOLVER.md) | [19](19_STATISTICS.md) | [20](20_SECURITY.md) | [21](21_EXCEPTIONS.md) | [22](22_CLI_TOOLS.md)

**© 2024 CloudCastle HTTP Router**
