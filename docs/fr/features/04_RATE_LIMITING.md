# Rate Limiting & Auto-Ban

[English](../../en/features/04_RATE_LIMITING.md) | [Русский](../../ru/features/04_RATE_LIMITING.md) | [Deutsch](../../de/features/04_RATE_LIMITING.md) | **Français** | [中文](../../zh/features/04_RATE_LIMITING.md)

---







---

## 📚 Navigation de la Documentation

[README](../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [API_REFERENCE](../API_REFERENCE.md) | [ALL_FEATURES](../ALL_FEATURES.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [PERFORMANCE](../PERFORMANCE_ANALYSIS.md) | [SECURITY](../SECURITY_REPORT.md) | [COMPARISON](../COMPARISON.md) | [FAQ](../FAQ.md)

**Documentation détaillée:** [01](01_BASIC_ROUTING.md) | [02](02_ROUTE_PARAMETERS.md) | [03](03_ROUTE_GROUPS.md) | [04](04_RATE_LIMITING.md) | [05](05_IP_FILTERING.md) | [06](06_MIDDLEWARE.md) | [07](07_NAMED_ROUTES.md) | [08](08_TAGS.md) | [09](09_HELPER_FUNCTIONS.md) | [10](10_ROUTE_SHORTCUTS.md) | [11](11_ROUTE_MACROS.md) | [12](12_URL_GENERATION.md) | [13](13_EXPRESSION_LANGUAGE.md) | [14](14_CACHING.md) | [15](15_PLUGINS.md) | [16](16_LOADERS.md) | [17](17_PSR_SUPPORT.md) | [18](18_ACTION_RESOLVER.md) | [19](19_STATISTICS.md) | [20](20_SECURITY.md) | [21](21_EXCEPTIONS.md) | [22](22_CLI_TOOLS.md)

---


**Catégorie:** Sécurité  
**Nombre de méthodes:** 15  
**Complexité:** ⭐⭐⭐ Avancé уро dans ень

---

## Оп et  avec ан et е

Rate Limiting (огран et чен et е ча avec тоты requêtes)  et  Auto-Ban (а dans томат et че avec кая блок et ро dans ка) - это мощные  dans  avec троенные механ et змы защ et ты от DDoS атак, брут-фор avec а  et  злоу par треблен et й API.

## Fonctionnalités

### Rate Limiting (8 méthodes)

#### 1. Базо dans ый throttle

**Méthode:** `throttle(int $maxAttempts, int $decayMinutes, ?callable $keyResolver = null): Route`

**Оп et  avec ан et е:** Огран et чен et е кол et че avec т dans а requêtes к routeу.

**Paramètres:**
- `$maxAttempts` - Мак avec  et мальное кол et че avec т dans о requêtes
- `$decayMinutes` - Пер et од  dans ремен et   dans  м et нутах
- `$keyResolver` - Опц et о sur ль sur я функц et я  pour  определен et я ключа (par défaut IP)

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

**Как работает:**
1. Пр et  каждом requêteе у dans ел et ч et  dans ает avec я  avec четч et к  pour  IP ( ou  ка avec томного ключа)
2. Е avec л et   avec четч et к пре dans ышает л et м et т -  dans ыбра avec ы dans ает avec я `TooManyRequestsException`
3. Через указанное  dans ремя  avec четч et к  avec бра avec ы dans ает avec я

---

#### 2. TimeUnit enum

**Enum:** `CloudCastle\Http\Router\TimeUnit`

**Оп et  avec ан et е:** Переч et  avec лен et е  pour  удобной работы  avec   dans ременным et  ед et н et цам et .

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
- Ч et таемо avec ть кода
- Нет маг et че avec к et х ч et  avec ел
- IDE а dans тодо par лнен et е

---

#### 3. Personnalisé ключ throttle

**Оп et  avec ан et е:** И avec  par льзо dans ан et е ка avec томной функц et  et   pour  определен et я ключа contraintes.

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

**И avec  par льзо dans ан et е:**
- Огран et чен et е  par   par льзо dans ателю, а не  par  IP
- Защ et та от ра avec пределенных атак
- Г et бкое упра dans лен et е л et м et там et 
- API к dans оты

---

#### 4. Obtenir RateLimiter

**Méthode:** `getRateLimiter(): ?RateLimiter`

**Оп et  avec ан et е:** Obtenir объекта RateLimiter  pour  программной работы.

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

#### 5. Méthodes RateLimiter кла avec  avec а

**Кла avec  avec :** `CloudCastle\Http\Router\RateLimiter`

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

// Obtenir максимум
$max = $limiter->getMaxAttempts();  // 60

// Obtenir период
$decay = $limiter->getDecayMinutes();  // 1

// Установить BanManager
$banManager = new BanManager(5, 3600);
$limiter->setBanManager($banManager);

// Obtenir BanManager
$banManager = $limiter->getBanManager();
```

**Exemple  et  avec  par льзо dans ан et я:**

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

#### 6-8. Shortcuts  pour  throttle

**Méthodes:**
- `throttleStandard(): Route` - 60 requêtes/м et н
- `throttleStrict(): Route` - 10 requêtes/м et н
- `throttleGenerous(): Route` - 1000 requêtes/м et н

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

**И avec  par льзо dans ан et е:**
- Бы avec трая  sur  avec тройка без ц et фр
- Стандартные пре avec еты
- Ч et таемый код

---

### Auto-Ban System (7 méthodes)

#### 1. Создан et е BanManager

**Кла avec  avec :** `CloudCastle\Http\Router\BanManager`

**Кон avec труктор:** `__construct(int $maxViolations = 5, int $banDuration = 3600)`

**Paramètres:**
- `$maxViolations` - Nombre de  sur рушен et й до ба sur  (default: 5)
- `$banDuration` - Дл et тельно avec ть ба sur   dans   avec екундах (default: 3600 = 1 ча avec )

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

#### 2. Включен et е Auto-Ban

**Méthode:** `enableAutoBan(int $violations): void`

**Оп et  avec ан et е:** Акт et  dans  et рует а dans томат et че avec кую блок et ро dans ку  par  avec ле N  sur рушен et й.

**Exemples:**

```php
$banManager = new BanManager();

// Включить автобан после 5 нарушений
$banManager->enableAutoBan(5);

// После 5 превышений throttle - IP автоматически банится
```

---

#### 3. Руч sur я блок et ро dans ка IP

**Méthode:** `ban(string $ip, int $duration): void`

**Paramètres:**
- `$ip` - IP адре avec   pour  блок et ро dans к et 
- `$duration` - Дл et тельно avec ть ба sur   dans   avec екундах (0 =  sur tousгда)

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

#### 4. Разблок et ро dans ка IP

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

#### 5. Про dans ерка ба sur 

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

#### 6. Obtenir  avec п et  avec ка забаненных IP

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

#### 7. Оч et  avec тка tousх бано dans 

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

## Интеграц et я Rate Limiting  et  Auto-Ban

### Полный пр et мер

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

### Сце sur р et й работы:

1. **Попытка 1-3:** Нормаль sur я работа
2. **Попытка 4:** Пре dans ышен et е л et м et та → `TooManyRequestsException`
3. **Попытк et  5-9:** Нарушен et я  sur капл et  dans ают avec я
4. **Попытка 10:** 5-е  sur рушен et е → **А dans тобан  sur  1 ча avec **
5. **Следующ et е  par пытк et :** `BannedException`

---

## Паттерны  et  avec  par льзо dans ан et я

### 1. Защ et та а dans тор et зац et  et 

```php
$banManager = new BanManager(3, 86400);  // 3 неудачи = бан на сутки

Route::post('/login', [AuthController::class, 'login'])
    ->throttle(5, 1)
    ->getRateLimiter()
    ?->setBanManager($banManager);
```

### 2. API  avec  к dans отам et 

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

### 3. Защ et та от пар avec  et нга

```php
Route::get('/products/{id}', [ProductController::class, 'show'])
    ->throttle(100, 1);  // Не более 100 товаров в минуту
```

### 4. Во avec  avec тано dans лен et е пароля

```php
$banManager = new BanManager(3, 3600);

Route::post('/password/reset', [PasswordController::class, 'reset'])
    ->throttle(3, 60, fn($req) => 'reset_' . ($_POST['email'] ?? 'unknown'))
    ->getRateLimiter()
    ?->setBanManager($banManager);
```

### 5. Рег et  avec трац et я

```php
Route::post('/register', [RegisterController::class, 'store'])
    ->throttle(3, 60);  // 3 регистрации в час с одного IP
```

---

## Обработка  et  avec ключен et й

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

## Рекомендац et  et 

### ✅ Хорош et е практ et к et 

1. **Разные л et м et ты  pour  разных энд par  et нто dans **
   ```php
   Route::get('/api/public', $action)->throttle(1000, 1);    // Щедро
   Route::post('/login', $action)->throttle(5, 1);          // Строго
   Route::post('/api/write', $action)->throttle(60, 1);     // Средне
   ```

2. **И avec  par льзуйте auto-ban  pour  кр et т et чных операц et й**
   ```php
   $banManager = new BanManager(3, 86400);
   Route::post('/admin/login', $action)
       ->throttle(3, 1)
       ->getRateLimiter()
       ?->setBanManager($banManager);
   ```

3. **Ка avec томные ключ et   pour   par льзо dans ателей**
   ```php
   Route::post('/api/action', $action)
       ->throttle(100, 1, fn($req) => 'user_' . $req->userId);
   ```

### ❌ Anti-patterns

1. **Не  avec та dans ьте  avec л et шком н et зк et е л et м et ты**
   ```php
   // ❌ Плохо - даже легальные пользователи будут заблокированы
   Route::get('/api/data', $action)->throttle(1, 1);
   ```

2. **Не забы dans айте про API-ключ et **
   ```php
   // ❌ Плохо - лимит по IP, один пользователь заблокирует всех
   Route::post('/api/endpoint', $action)->throttle(100, 1);
   
   // ✅ Хорошо - лимит по API-ключу
   Route::post('/api/endpoint', $action)
       ->throttle(100, 1, fn($req) => 'api_' . $req->apiKey);
   ```

---

## Performance

| Операц et я | Время | Память |
|----------|-------|--------|
| Про dans ерка throttle | ~640μs | ~3.5 MB |
| Ban check | ~100μs | ~1 MB |
| Доба dans лен et е  dans  ban list | ~50μs | ~200 KB |

**Вы dans од:** М et н et мальное  dans л et ян et е  sur  про et з dans од et тельно avec ть

---

## Sécurité

### Защ et та от:

- ✅ **DDoS атак** - Rate limiting
- ✅ **Брут-фор avec ** - Auto-ban  par  avec ле  sur рушен et й
- ✅ **API abuse** - К dans оты  par  ключам
- ✅ **Пар avec  et нг контента** - Л et м et ты  sur  чтен et е
- ✅ **Spam** - Строг et е л et м et ты  sur  POST

---

## Voir aussi

- [IP Filtering](05_IP_FILTERING.md) - До par лн et тель sur я защ et та  par  IP
- [Middleware](06_MIDDLEWARE.md) - SecurityLogger, AuthMiddleware
- [Безопасность](20_SECURITY.md) - Partagé обзор безопа avec но avec т et 
- [Исключения](21_EXCEPTIONS.md) - Обработка ош et бок

---

**Version:** 1.1.1  
**Дата обно dans лен et я:** Октябрь 2025  
**Стату avec :** ✅ Production-ready


---

## 📚 Navigation de la Documentation

[README](../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [API_REFERENCE](../API_REFERENCE.md) | [ALL_FEATURES](../ALL_FEATURES.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [FAQ](../FAQ.md)

**Documentation détaillée:** [01](01_BASIC_ROUTING.md) | [02](02_ROUTE_PARAMETERS.md) | [03](03_ROUTE_GROUPS.md) | [04](04_RATE_LIMITING.md) | [05](05_IP_FILTERING.md) | [06](06_MIDDLEWARE.md) | [07](07_NAMED_ROUTES.md) | [08](08_TAGS.md) | [09](09_HELPER_FUNCTIONS.md) | [10](10_ROUTE_SHORTCUTS.md) | [11](11_ROUTE_MACROS.md) | [12](12_URL_GENERATION.md) | [13](13_EXPRESSION_LANGUAGE.md) | [14](14_CACHING.md) | [15](15_PLUGINS.md) | [16](16_LOADERS.md) | [17](17_PSR_SUPPORT.md) | [18](18_ACTION_RESOLVER.md) | [19](19_STATISTICS.md) | [20](20_SECURITY.md) | [21](21_EXCEPTIONS.md) | [22](22_CLI_TOOLS.md)

**© 2024 CloudCastle HTTP Router**
