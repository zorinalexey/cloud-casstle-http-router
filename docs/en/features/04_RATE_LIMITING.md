# Rate Limiting & Auto-Ban

**English** | [Русский](../../ru/features/04_RATE_LIMITING.md) | [Deutsch](../../de/features/04_RATE_LIMITING.md) | [Français](../../fr/features/04_RATE_LIMITING.md) | [中文](../../zh/features/04_RATE_LIMITING.md)

---







---

## 📚 Documentation Navigation

[README](../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [API_REFERENCE](../API_REFERENCE.md) | [ALL_FEATURES](../ALL_FEATURES.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [PERFORMANCE](../PERFORMANCE_ANALYSIS.md) | [SECURITY](../SECURITY_REPORT.md) | [COMPARISON](../COMPARISON.md) | [FAQ](../FAQ.md)

**Detailed documentation:** [01](01_BASIC_ROUTING.md) | [02](02_ROUTE_PARAMETERS.md) | [03](03_ROUTE_GROUPS.md) | [04](04_RATE_LIMITING.md) | [05](05_IP_FILTERING.md) | [06](06_MIDDLEWARE.md) | [07](07_NAMED_ROUTES.md) | [08](08_TAGS.md) | [09](09_HELPER_FUNCTIONS.md) | [10](10_ROUTE_SHORTCUTS.md) | [11](11_ROUTE_MACROS.md) | [12](12_URL_GENERATION.md) | [13](13_EXPRESSION_LANGUAGE.md) | [14](14_CACHING.md) | [15](15_PLUGINS.md) | [16](16_LOADERS.md) | [17](17_PSR_SUPPORT.md) | [18](18_ACTION_RESOLVER.md) | [19](19_STATISTICS.md) | [20](20_SECURITY.md) | [21](21_EXCEPTIONS.md) | [22](22_CLI_TOOLS.md)

---


**Category:** Security  
**Number of methods:** 15  
**Complexity:** ⭐⭐⭐ Advanced уро in ень

---

## Оп and  with ан and е

Rate Limiting (огран and чен and е ча with тоты requests)  and  Auto-Ban (а in томат and че with кая блок and ро in ка) - это мощные  in  with троенные механ and змы защ and ты от DDoS атак, брут-фор with а  and  злоу by треблен and й API.

## Features

### Rate Limiting (8 methods)

#### 1. Базо in ый throttle

**Method:** `throttle(int $maxAttempts, int $decayMinutes, ?callable $keyResolver = null): Route`

**Оп and  with ан and е:** Огран and чен and е кол and че with т in а requests к routeу.

**Parameters:**
- `$maxAttempts` - Мак with  and мальное кол and че with т in о requests
- `$decayMinutes` - Пер and од  in ремен and   in  м and нутах
- `$keyResolver` - Опц and о on ль on я функц and я  for  определен and я ключа (by default IP)

**Examples:**

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

**Как работает:**
1. Пр and  каждом requestе у in ел and ч and  in ает with я  with четч and к  for  IP ( or  ка with томного ключа)
2. Е with л and   with четч and к пре in ышает л and м and т -  in ыбра with ы in ает with я `TooManyRequestsException`
3. Через указанное  in ремя  with четч and к  with бра with ы in ает with я

---

#### 2. TimeUnit enum

**Enum:** `CloudCastle\Http\Router\TimeUnit`

**Оп and  with ан and е:** Переч and  with лен and е  for  удобной работы  with   in ременным and  ед and н and цам and .

**Values:**
```php
TimeUnit::SECOND->value  // 1/60 минуты
TimeUnit::MINUTE->value  // 1 минута
TimeUnit::HOUR->value    // 60 минут
TimeUnit::DAY->value     // 1440 минут
TimeUnit::WEEK->value    // 10080 минут
TimeUnit::MONTH->value   // 43200 минут
```

**Examples:**

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

**Advantages:**
- Ч and таемо with ть кода
- Нет маг and че with к and х ч and  with ел
- IDE а in тодо by лнен and е

---

#### 3. Custom ключ throttle

**Оп and  with ан and е:** И with  by льзо in ан and е ка with томной функц and  and   for  определен and я ключа constraints.

**Examples:**

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

**И with  by льзо in ан and е:**
- Огран and чен and е  by   by льзо in ателю, а не  by  IP
- Защ and та от ра with пределенных атак
- Г and бкое упра in лен and е л and м and там and 
- API к in оты

---

#### 4. Getting RateLimiter

**Method:** `getRateLimiter(): ?RateLimiter`

**Оп and  with ан and е:** Getting объекта RateLimiter  for  программной работы.

**Examples:**

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

#### 5. Methods RateLimiter кла with  with а

**Кла with  with :** `CloudCastle\Http\Router\RateLimiter`

**Methods:**

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

// Get максимум
$max = $limiter->getMaxAttempts();  // 60

// Get период
$decay = $limiter->getDecayMinutes();  // 1

// Установить BanManager
$banManager = new BanManager(5, 3600);
$limiter->setBanManager($banManager);

// Get BanManager
$banManager = $limiter->getBanManager();
```

**Example  and  with  by льзо in ан and я:**

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

#### 6-8. Shortcuts  for  throttle

**Methods:**
- `throttleStandard(): Route` - 60 requests/м and н
- `throttleStrict(): Route` - 10 requests/м and н
- `throttleGenerous(): Route` - 1000 requests/м and н

**Examples:**

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

**И with  by льзо in ан and е:**
- Бы with трая  on  with тройка без ц and фр
- Стандартные пре with еты
- Ч and таемый код

---

### Auto-Ban System (7 methods)

#### 1. Создан and е BanManager

**Кла with  with :** `CloudCastle\Http\Router\BanManager`

**Кон with труктор:** `__construct(int $maxViolations = 5, int $banDuration = 3600)`

**Parameters:**
- `$maxViolations` - Number of  on рушен and й до ба on  (default: 5)
- `$banDuration` - Дл and тельно with ть ба on   in   with екундах (default: 3600 = 1 ча with )

**Examples:**

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

#### 2. Включен and е Auto-Ban

**Method:** `enableAutoBan(int $violations): void`

**Оп and  with ан and е:** Акт and  in  and рует а in томат and че with кую блок and ро in ку  by  with ле N  on рушен and й.

**Examples:**

```php
$banManager = new BanManager();

// Включить автобан после 5 нарушений
$banManager->enableAutoBan(5);

// После 5 превышений throttle - IP автоматически банится
```

---

#### 3. Руч on я блок and ро in ка IP

**Method:** `ban(string $ip, int $duration): void`

**Parameters:**
- `$ip` - IP адре with   for  блок and ро in к and 
- `$duration` - Дл and тельно with ть ба on   in   with екундах (0 =  on allгда)

**Examples:**

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

#### 4. Разблок and ро in ка IP

**Method:** `unban(string $ip): void`

**Examples:**

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

#### 5. Про in ерка ба on 

**Method:** `isBanned(string $ip): bool`

**Examples:**

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

#### 6. Getting  with п and  with ка забаненных IP

**Method:** `getBannedIps(): array`

**Examples:**

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

#### 7. Оч and  with тка allх бано in 

**Method:** `clearAll(): void`

**Examples:**

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

## Интеграц and я Rate Limiting  and  Auto-Ban

### Полный пр and мер

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

### Сце on р and й работы:

1. **Попытка 1-3:** Нормаль on я работа
2. **Попытка 4:** Пре in ышен and е л and м and та → `TooManyRequestsException`
3. **Попытк and  5-9:** Нарушен and я  on капл and  in ают with я
4. **Попытка 10:** 5-е  on рушен and е → **А in тобан  on  1 ча with **
5. **Следующ and е  by пытк and :** `BannedException`

---

## Паттерны  and  with  by льзо in ан and я

### 1. Защ and та а in тор and зац and  and 

```php
$banManager = new BanManager(3, 86400);  // 3 неудачи = бан на сутки

Route::post('/login', [AuthController::class, 'login'])
    ->throttle(5, 1)
    ->getRateLimiter()
    ?->setBanManager($banManager);
```

### 2. API  with  к in отам and 

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

### 3. Защ and та от пар with  and нга

```php
Route::get('/products/{id}', [ProductController::class, 'show'])
    ->throttle(100, 1);  // Не более 100 товаров в минуту
```

### 4. Во with  with тано in лен and е пароля

```php
$banManager = new BanManager(3, 3600);

Route::post('/password/reset', [PasswordController::class, 'reset'])
    ->throttle(3, 60, fn($req) => 'reset_' . ($_POST['email'] ?? 'unknown'))
    ->getRateLimiter()
    ?->setBanManager($banManager);
```

### 5. Рег and  with трац and я

```php
Route::post('/register', [RegisterController::class, 'store'])
    ->throttle(3, 60);  // 3 регистрации в час с одного IP
```

---

## Обработка  and  with ключен and й

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

## Рекомендац and  and 

### ✅ Хорош and е практ and к and 

1. **Разные л and м and ты  for  разных энд by  and нто in **
   ```php
   Route::get('/api/public', $action)->throttle(1000, 1);    // Щедро
   Route::post('/login', $action)->throttle(5, 1);          // Строго
   Route::post('/api/write', $action)->throttle(60, 1);     // Средне
   ```

2. **И with  by льзуйте auto-ban  for  кр and т and чных операц and й**
   ```php
   $banManager = new BanManager(3, 86400);
   Route::post('/admin/login', $action)
       ->throttle(3, 1)
       ->getRateLimiter()
       ?->setBanManager($banManager);
   ```

3. **Ка with томные ключ and   for   by льзо in ателей**
   ```php
   Route::post('/api/action', $action)
       ->throttle(100, 1, fn($req) => 'user_' . $req->userId);
   ```

### ❌ Anti-patterns

1. **Не  with та in ьте  with л and шком н and зк and е л and м and ты**
   ```php
   // ❌ Плохо - даже легальные пользователи будут заблокированы
   Route::get('/api/data', $action)->throttle(1, 1);
   ```

2. **Не забы in айте про API-ключ and **
   ```php
   // ❌ Плохо - лимит по IP, один пользователь заблокирует всех
   Route::post('/api/endpoint', $action)->throttle(100, 1);
   
   // ✅ Хорошо - лимит по API-ключу
   Route::post('/api/endpoint', $action)
       ->throttle(100, 1, fn($req) => 'api_' . $req->apiKey);
   ```

---

## Performance

| Операц and я | Время | Память |
|----------|-------|--------|
| Про in ерка throttle | ~640μs | ~3.5 MB |
| Ban check | ~100μs | ~1 MB |
| Доба in лен and е  in  ban list | ~50μs | ~200 KB |

**Вы in од:** М and н and мальное  in л and ян and е  on  про and з in од and тельно with ть

---

## Security

### Защ and та от:

- ✅ **DDoS атак** - Rate limiting
- ✅ **Брут-фор with ** - Auto-ban  by  with ле  on рушен and й
- ✅ **API abuse** - К in оты  by  ключам
- ✅ **Пар with  and нг контента** - Л and м and ты  on  чтен and е
- ✅ **Spam** - Строг and е л and м and ты  on  POST

---

## See also

- [IP Filtering](05_IP_FILTERING.md) - До by лн and тель on я защ and та  by  IP
- [Middleware](06_MIDDLEWARE.md) - SecurityLogger, AuthMiddleware
- [Безопасность](20_SECURITY.md) - Shared обзор безопа with но with т and 
- [Исключения](21_EXCEPTIONS.md) - Обработка ош and бок

---

**Version:** 1.1.1  
**Дата обно in лен and я:** Октябрь 2025  
**Стату with :** ✅ Production-ready


---

## 📚 Documentation Navigation

[README](../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [API_REFERENCE](../API_REFERENCE.md) | [ALL_FEATURES](../ALL_FEATURES.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [FAQ](../FAQ.md)

**Detailed documentation:** [01](01_BASIC_ROUTING.md) | [02](02_ROUTE_PARAMETERS.md) | [03](03_ROUTE_GROUPS.md) | [04](04_RATE_LIMITING.md) | [05](05_IP_FILTERING.md) | [06](06_MIDDLEWARE.md) | [07](07_NAMED_ROUTES.md) | [08](08_TAGS.md) | [09](09_HELPER_FUNCTIONS.md) | [10](10_ROUTE_SHORTCUTS.md) | [11](11_ROUTE_MACROS.md) | [12](12_URL_GENERATION.md) | [13](13_EXPRESSION_LANGUAGE.md) | [14](14_CACHING.md) | [15](15_PLUGINS.md) | [16](16_LOADERS.md) | [17](17_PSR_SUPPORT.md) | [18](18_ACTION_RESOLVER.md) | [19](19_STATISTICS.md) | [20](20_SECURITY.md) | [21](21_EXCEPTIONS.md) | [22](22_CLI_TOOLS.md)

**© 2024 CloudCastle HTTP Router**
