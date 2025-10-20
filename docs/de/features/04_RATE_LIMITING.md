# Rate Limiting & Auto-Ban

[English](../../en/features/04_RATE_LIMITING.md) | [Русский](../../ru/features/04_RATE_LIMITING.md) | **Deutsch** | [Français](../../fr/features/04_RATE_LIMITING.md) | [中文](../../zh/features/04_RATE_LIMITING.md)

---







---

## 📚 Dokumentationsnavigation

[README](../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [API_REFERENCE](../API_REFERENCE.md) | [ALL_FEATURES](../ALL_FEATURES.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [PERFORMANCE](../PERFORMANCE_ANALYSIS.md) | [SECURITY](../SECURITY_REPORT.md) | [COMPARISON](../COMPARISON.md) | [FAQ](../FAQ.md)

**Detaillierte Dokumentation:** [01](01_BASIC_ROUTING.md) | [02](02_ROUTE_PARAMETERS.md) | [03](03_ROUTE_GROUPS.md) | [04](04_RATE_LIMITING.md) | [05](05_IP_FILTERING.md) | [06](06_MIDDLEWARE.md) | [07](07_NAMED_ROUTES.md) | [08](08_TAGS.md) | [09](09_HELPER_FUNCTIONS.md) | [10](10_ROUTE_SHORTCUTS.md) | [11](11_ROUTE_MACROS.md) | [12](12_URL_GENERATION.md) | [13](13_EXPRESSION_LANGUAGE.md) | [14](14_CACHING.md) | [15](15_PLUGINS.md) | [16](16_LOADERS.md) | [17](17_PSR_SUPPORT.md) | [18](18_ACTION_RESOLVER.md) | [19](19_STATISTICS.md) | [20](20_SECURITY.md) | [21](21_EXCEPTIONS.md) | [22](22_CLI_TOOLS.md)

---


**Kategorie:** Sicherheit  
**Anzahl der Methoden:** 15  
**Komplexität:** ⭐⭐⭐ Fortgeschritten уро in ень

---

## Оп und  mit ан und е

Rate Limiting (огран und чен und е ча mit тоты Anfragen)  und  Auto-Ban (а in томат und че mit кая блок und ро in ка) - это мощные  in  mit троенные механ und змы защ und ты от DDoS атак, брут-фор mit а  und  злоу nach треблен und й API.

## Funktionen

### Rate Limiting (8 Methoden)

#### 1. Базо in ый throttle

**Methode:** `throttle(int $maxAttempts, int $decayMinutes, ?callable $keyResolver = null): Route`

**Оп und  mit ан und е:** Огран und чен und е кол und че mit т in а Anfragen к Routeу.

**Parameter:**
- `$maxAttempts` - Мак mit  und мальное кол und че mit т in о Anfragen
- `$decayMinutes` - Пер und од  in ремен und   in  м und нутах
- `$keyResolver` - Опц und о auf ль auf я функц und я  für  определен und я ключа (standardmäßig IP)

**Beispiele:**

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
1. Пр und  каждом Anfrageе у in ел und ч und  in ает mit я  mit четч und к  für  IP ( oder  ка mit томного ключа)
2. Е mit л und   mit четч und к пре in ышает л und м und т -  in ыбра mit ы in ает mit я `TooManyRequestsException`
3. Через указанное  in ремя  mit четч und к  mit бра mit ы in ает mit я

---

#### 2. TimeUnit enum

**Enum:** `CloudCastle\Http\Router\TimeUnit`

**Оп und  mit ан und е:** Переч und  mit лен und е  für  удобной работы  mit   in ременным und  ед und н und цам und .

**Werte:**
```php
TimeUnit::SECOND->value  // 1/60 минуты
TimeUnit::MINUTE->value  // 1 минута
TimeUnit::HOUR->value    // 60 минут
TimeUnit::DAY->value     // 1440 минут
TimeUnit::WEEK->value    // 10080 минут
TimeUnit::MONTH->value   // 43200 минут
```

**Beispiele:**

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

**Vorteile:**
- Ч und таемо mit ть кода
- Нет маг und че mit к und х ч und  mit ел
- IDE а in тодо nach лнен und е

---

#### 3. Benutzerdefiniert ключ throttle

**Оп und  mit ан und е:** И mit  nach льзо in ан und е ка mit томной функц und  und   für  определен und я ключа Einschränkungen.

**Beispiele:**

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

**И mit  nach льзо in ан und е:**
- Огран und чен und е  nach   nach льзо in ателю, а не  nach  IP
- Защ und та от ра mit пределенных атак
- Г und бкое упра in лен und е л und м und там und 
- API к in оты

---

#### 4. Abrufen RateLimiter

**Methode:** `getRateLimiter(): ?RateLimiter`

**Оп und  mit ан und е:** Abrufen объекта RateLimiter  für  программной работы.

**Beispiele:**

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

#### 5. Methoden RateLimiter кла mit  mit а

**Кла mit  mit :** `CloudCastle\Http\Router\RateLimiter`

**Methoden:**

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

// Erhalten максимум
$max = $limiter->getMaxAttempts();  // 60

// Erhalten период
$decay = $limiter->getDecayMinutes();  // 1

// Установить BanManager
$banManager = new BanManager(5, 3600);
$limiter->setBanManager($banManager);

// Erhalten BanManager
$banManager = $limiter->getBanManager();
```

**Beispiel  und  mit  nach льзо in ан und я:**

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

#### 6-8. Shortcuts  für  throttle

**Methoden:**
- `throttleStandard(): Route` - 60 Anfragen/м und н
- `throttleStrict(): Route` - 10 Anfragen/м und н
- `throttleGenerous(): Route` - 1000 Anfragen/м und н

**Beispiele:**

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

**И mit  nach льзо in ан und е:**
- Бы mit трая  auf  mit тройка без ц und фр
- Стандартные пре mit еты
- Ч und таемый код

---

### Auto-Ban System (7 Methoden)

#### 1. Создан und е BanManager

**Кла mit  mit :** `CloudCastle\Http\Router\BanManager`

**Кон mit труктор:** `__construct(int $maxViolations = 5, int $banDuration = 3600)`

**Parameter:**
- `$maxViolations` - Anzahl der  auf рушен und й до ба auf  (default: 5)
- `$banDuration` - Дл und тельно mit ть ба auf   in   mit екундах (default: 3600 = 1 ча mit )

**Beispiele:**

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

#### 2. Включен und е Auto-Ban

**Methode:** `enableAutoBan(int $violations): void`

**Оп und  mit ан und е:** Акт und  in  und рует а in томат und че mit кую блок und ро in ку  nach  mit ле N  auf рушен und й.

**Beispiele:**

```php
$banManager = new BanManager();

// Включить автобан после 5 нарушений
$banManager->enableAutoBan(5);

// После 5 превышений throttle - IP автоматически банится
```

---

#### 3. Руч auf я блок und ро in ка IP

**Methode:** `ban(string $ip, int $duration): void`

**Parameter:**
- `$ip` - IP адре mit   für  блок und ро in к und 
- `$duration` - Дл und тельно mit ть ба auf   in   mit екундах (0 =  auf alleгда)

**Beispiele:**

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

#### 4. Разблок und ро in ка IP

**Methode:** `unban(string $ip): void`

**Beispiele:**

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

#### 5. Про in ерка ба auf 

**Methode:** `isBanned(string $ip): bool`

**Beispiele:**

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

#### 6. Abrufen  mit п und  mit ка забаненных IP

**Methode:** `getBannedIps(): array`

**Beispiele:**

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

#### 7. Оч und  mit тка alleх бано in 

**Methode:** `clearAll(): void`

**Beispiele:**

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

## Интеграц und я Rate Limiting  und  Auto-Ban

### Полный пр und мер

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

### Сце auf р und й работы:

1. **Попытка 1-3:** Нормаль auf я работа
2. **Попытка 4:** Пре in ышен und е л und м und та → `TooManyRequestsException`
3. **Попытк und  5-9:** Нарушен und я  auf капл und  in ают mit я
4. **Попытка 10:** 5-е  auf рушен und е → **А in тобан  auf  1 ча mit **
5. **Следующ und е  nach пытк und :** `BannedException`

---

## Паттерны  und  mit  nach льзо in ан und я

### 1. Защ und та а in тор und зац und  und 

```php
$banManager = new BanManager(3, 86400);  // 3 неудачи = бан на сутки

Route::post('/login', [AuthController::class, 'login'])
    ->throttle(5, 1)
    ->getRateLimiter()
    ?->setBanManager($banManager);
```

### 2. API  mit  к in отам und 

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

### 3. Защ und та от пар mit  und нга

```php
Route::get('/products/{id}', [ProductController::class, 'show'])
    ->throttle(100, 1);  // Не более 100 товаров в минуту
```

### 4. Во mit  mit тано in лен und е пароля

```php
$banManager = new BanManager(3, 3600);

Route::post('/password/reset', [PasswordController::class, 'reset'])
    ->throttle(3, 60, fn($req) => 'reset_' . ($_POST['email'] ?? 'unknown'))
    ->getRateLimiter()
    ?->setBanManager($banManager);
```

### 5. Рег und  mit трац und я

```php
Route::post('/register', [RegisterController::class, 'store'])
    ->throttle(3, 60);  // 3 регистрации в час с одного IP
```

---

## Обработка  und  mit ключен und й

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

## Рекомендац und  und 

### ✅ Хорош und е практ und к und 

1. **Разные л und м und ты  für  разных энд nach  und нто in **
   ```php
   Route::get('/api/public', $action)->throttle(1000, 1);    // Щедро
   Route::post('/login', $action)->throttle(5, 1);          // Строго
   Route::post('/api/write', $action)->throttle(60, 1);     // Средне
   ```

2. **И mit  nach льзуйте auto-ban  für  кр und т und чных операц und й**
   ```php
   $banManager = new BanManager(3, 86400);
   Route::post('/admin/login', $action)
       ->throttle(3, 1)
       ->getRateLimiter()
       ?->setBanManager($banManager);
   ```

3. **Ка mit томные ключ und   für   nach льзо in ателей**
   ```php
   Route::post('/api/action', $action)
       ->throttle(100, 1, fn($req) => 'user_' . $req->userId);
   ```

### ❌ Anti-Patterns

1. **Не  mit та in ьте  mit л und шком н und зк und е л und м und ты**
   ```php
   // ❌ Плохо - даже легальные пользователи будут заблокированы
   Route::get('/api/data', $action)->throttle(1, 1);
   ```

2. **Не забы in айте про API-ключ und **
   ```php
   // ❌ Плохо - лимит по IP, один пользователь заблокирует всех
   Route::post('/api/endpoint', $action)->throttle(100, 1);
   
   // ✅ Хорошо - лимит по API-ключу
   Route::post('/api/endpoint', $action)
       ->throttle(100, 1, fn($req) => 'api_' . $req->apiKey);
   ```

---

## Leistung

| Операц und я | Время | Память |
|----------|-------|--------|
| Про in ерка throttle | ~640μs | ~3.5 MB |
| Ban check | ~100μs | ~1 MB |
| Доба in лен und е  in  ban list | ~50μs | ~200 KB |

**Вы in од:** М und н und мальное  in л und ян und е  auf  про und з in од und тельно mit ть

---

## Sicherheit

### Защ und та от:

- ✅ **DDoS атак** - Rate limiting
- ✅ **Брут-фор mit ** - Auto-ban  nach  mit ле  auf рушен und й
- ✅ **API abuse** - К in оты  nach  ключам
- ✅ **Пар mit  und нг контента** - Л und м und ты  auf  чтен und е
- ✅ **Spam** - Строг und е л und м und ты  auf  POST

---

## Siehe auch

- [IP Filtering](05_IP_FILTERING.md) - До nach лн und тель auf я защ und та  nach  IP
- [Middleware](06_MIDDLEWARE.md) - SecurityLogger, AuthMiddleware
- [Безопасность](20_SECURITY.md) - Gemeinsam обзор безопа mit но mit т und 
- [Исключения](21_EXCEPTIONS.md) - Обработка ош und бок

---

**Version:** 1.1.1  
**Дата обно in лен und я:** Октябрь 2025  
**Стату mit :** ✅ Production-ready


---

## 📚 Dokumentationsnavigation

[README](../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [API_REFERENCE](../API_REFERENCE.md) | [ALL_FEATURES](../ALL_FEATURES.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [FAQ](../FAQ.md)

**Detaillierte Dokumentation:** [01](01_BASIC_ROUTING.md) | [02](02_ROUTE_PARAMETERS.md) | [03](03_ROUTE_GROUPS.md) | [04](04_RATE_LIMITING.md) | [05](05_IP_FILTERING.md) | [06](06_MIDDLEWARE.md) | [07](07_NAMED_ROUTES.md) | [08](08_TAGS.md) | [09](09_HELPER_FUNCTIONS.md) | [10](10_ROUTE_SHORTCUTS.md) | [11](11_ROUTE_MACROS.md) | [12](12_URL_GENERATION.md) | [13](13_EXPRESSION_LANGUAGE.md) | [14](14_CACHING.md) | [15](15_PLUGINS.md) | [16](16_LOADERS.md) | [17](17_PSR_SUPPORT.md) | [18](18_ACTION_RESOLVER.md) | [19](19_STATISTICS.md) | [20](20_SECURITY.md) | [21](21_EXCEPTIONS.md) | [22](22_CLI_TOOLS.md)

**© 2024 CloudCastle HTTP Router**
