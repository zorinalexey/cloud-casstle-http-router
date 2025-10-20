# Rate Limiting & Auto-Ban

**Категория:** Безопасность  
**Количество методов:** 15  
**Сложность:** ⭐⭐⭐ Продвинутый уровень

---

## Описание

Rate Limiting (ограничение частоты запросов) и Auto-Ban (автоматическая блокировка) - это мощные встроенные механизмы защиты от DDoS атак, брут-форса и злоупотреблений API.

## Возможности

### Rate Limiting (8 методов)

#### 1. Базовый throttle

**Метод:** `throttle(int $maxAttempts, int $decayMinutes, ?callable $keyResolver = null): Route`

**Описание:** Ограничение количества запросов к маршруту.

**Параметры:**
- `$maxAttempts` - Максимальное количество запросов
- `$decayMinutes` - Период времени в минутах
- `$keyResolver` - Опциональная функция для определения ключа (по умолчанию IP)

**Примеры:**

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
1. При каждом запросе увеличивается счетчик для IP (или кастомного ключа)
2. Если счетчик превышает лимит - выбрасывается `TooManyRequestsException`
3. Через указанное время счетчик сбрасывается

---

#### 2. TimeUnit enum

**Enum:** `CloudCastle\Http\Router\TimeUnit`

**Описание:** Перечисление для удобной работы с временными единицами.

**Значения:**
```php
TimeUnit::SECOND->value  // 1/60 минуты
TimeUnit::MINUTE->value  // 1 минута
TimeUnit::HOUR->value    // 60 минут
TimeUnit::DAY->value     // 1440 минут
TimeUnit::WEEK->value    // 10080 минут
TimeUnit::MONTH->value   // 43200 минут
```

**Примеры:**

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

**Преимущества:**
- Читаемость кода
- Нет магических чисел
- IDE автодополнение

---

#### 3. Кастомный ключ throttle

**Описание:** Использование кастомной функции для определения ключа ограничения.

**Примеры:**

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

**Использование:**
- Ограничение по пользователю, а не по IP
- Защита от распределенных атак
- Гибкое управление лимитами
- API квоты

---

#### 4. Получение RateLimiter

**Метод:** `getRateLimiter(): ?RateLimiter`

**Описание:** Получение объекта RateLimiter для программной работы.

**Примеры:**

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

#### 5. Методы RateLimiter класса

**Класс:** `CloudCastle\Http\Router\RateLimiter`

**Методы:**

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

**Пример использования:**

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

#### 6-8. Shortcuts для throttle

**Методы:**
- `throttleStandard(): Route` - 60 запросов/мин
- `throttleStrict(): Route` - 10 запросов/мин
- `throttleGenerous(): Route` - 1000 запросов/мин

**Примеры:**

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

**Использование:**
- Быстрая настройка без цифр
- Стандартные пресеты
- Читаемый код

---

### Auto-Ban System (7 методов)

#### 1. Создание BanManager

**Класс:** `CloudCastle\Http\Router\BanManager`

**Конструктор:** `__construct(int $maxViolations = 5, int $banDuration = 3600)`

**Параметры:**
- `$maxViolations` - Количество нарушений до бана (default: 5)
- `$banDuration` - Длительность бана в секундах (default: 3600 = 1 час)

**Примеры:**

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

#### 2. Включение Auto-Ban

**Метод:** `enableAutoBan(int $violations): void`

**Описание:** Активирует автоматическую блокировку после N нарушений.

**Примеры:**

```php
$banManager = new BanManager();

// Включить автобан после 5 нарушений
$banManager->enableAutoBan(5);

// После 5 превышений throttle - IP автоматически банится
```

---

#### 3. Ручная блокировка IP

**Метод:** `ban(string $ip, int $duration): void`

**Параметры:**
- `$ip` - IP адрес для блокировки
- `$duration` - Длительность бана в секундах (0 = навсегда)

**Примеры:**

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

#### 4. Разблокировка IP

**Метод:** `unban(string $ip): void`

**Примеры:**

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

#### 5. Проверка бана

**Метод:** `isBanned(string $ip): bool`

**Примеры:**

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

#### 6. Получение списка забаненных IP

**Метод:** `getBannedIps(): array`

**Примеры:**

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

#### 7. Очистка всех банов

**Метод:** `clearAll(): void`

**Примеры:**

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

## Интеграция Rate Limiting и Auto-Ban

### Полный пример

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

### Сценарий работы:

1. **Попытка 1-3:** Нормальная работа
2. **Попытка 4:** Превышение лимита → `TooManyRequestsException`
3. **Попытки 5-9:** Нарушения накапливаются
4. **Попытка 10:** 5-е нарушение → **Автобан на 1 час**
5. **Следующие попытки:** `BannedException`

---

## Паттерны использования

### 1. Защита авторизации

```php
$banManager = new BanManager(3, 86400);  // 3 неудачи = бан на сутки

Route::post('/login', [AuthController::class, 'login'])
    ->throttle(5, 1)
    ->getRateLimiter()
    ?->setBanManager($banManager);
```

### 2. API с квотами

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

### 3. Защита от парсинга

```php
Route::get('/products/{id}', [ProductController::class, 'show'])
    ->throttle(100, 1);  // Не более 100 товаров в минуту
```

### 4. Восстановление пароля

```php
$banManager = new BanManager(3, 3600);

Route::post('/password/reset', [PasswordController::class, 'reset'])
    ->throttle(3, 60, fn($req) => 'reset_' . ($_POST['email'] ?? 'unknown'))
    ->getRateLimiter()
    ?->setBanManager($banManager);
```

### 5. Регистрация

```php
Route::post('/register', [RegisterController::class, 'store'])
    ->throttle(3, 60);  // 3 регистрации в час с одного IP
```

---

## Обработка исключений

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

## Рекомендации

### ✅ Хорошие практики

1. **Разные лимиты для разных эндпоинтов**
   ```php
   Route::get('/api/public', $action)->throttle(1000, 1);    // Щедро
   Route::post('/login', $action)->throttle(5, 1);          // Строго
   Route::post('/api/write', $action)->throttle(60, 1);     // Средне
   ```

2. **Используйте auto-ban для критичных операций**
   ```php
   $banManager = new BanManager(3, 86400);
   Route::post('/admin/login', $action)
       ->throttle(3, 1)
       ->getRateLimiter()
       ?->setBanManager($banManager);
   ```

3. **Кастомные ключи для пользователей**
   ```php
   Route::post('/api/action', $action)
       ->throttle(100, 1, fn($req) => 'user_' . $req->userId);
   ```

### ❌ Антипаттерны

1. **Не ставьте слишком низкие лимиты**
   ```php
   // ❌ Плохо - даже легальные пользователи будут заблокированы
   Route::get('/api/data', $action)->throttle(1, 1);
   ```

2. **Не забывайте про API-ключи**
   ```php
   // ❌ Плохо - лимит по IP, один пользователь заблокирует всех
   Route::post('/api/endpoint', $action)->throttle(100, 1);
   
   // ✅ Хорошо - лимит по API-ключу
   Route::post('/api/endpoint', $action)
       ->throttle(100, 1, fn($req) => 'api_' . $req->apiKey);
   ```

---

## Производительность

| Операция | Время | Память |
|----------|-------|--------|
| Проверка throttle | ~640μs | ~3.5 MB |
| Ban check | ~100μs | ~1 MB |
| Добавление в ban list | ~50μs | ~200 KB |

**Вывод:** Минимальное влияние на производительность

---

## Безопасность

### Защита от:

- ✅ **DDoS атак** - Rate limiting
- ✅ **Брут-форс** - Auto-ban после нарушений
- ✅ **API abuse** - Квоты по ключам
- ✅ **Парсинг контента** - Лимиты на чтение
- ✅ **Spam** - Строгие лимиты на POST

---

## См. также

- [IP Filtering](05_IP_FILTERING.md) - Дополнительная защита по IP
- [Middleware](06_MIDDLEWARE.md) - SecurityLogger, AuthMiddleware
- [Безопасность](20_SECURITY.md) - Общий обзор безопасности
- [Исключения](21_EXCEPTIONS.md) - Обработка ошибок

---

**Версия:** 1.1.1  
**Дата обновления:** Октябрь 2025  
**Статус:** ✅ Production-ready

