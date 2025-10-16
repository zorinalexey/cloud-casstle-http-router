# Система автобана

**CloudCastle HTTP Router v1.1.0**  
**Язык**: Русский

**Переводы**: [English](../../en/documentation/auto-ban.md) | [Deutsch](../../de/documentation/auto-ban.md) | [Français](../../fr/documentation/auto-ban.md)

---

## 🚫 Что такое автобан?

Автобан - это система автоматической блокировки IP-адресов при повторном превышении rate limit. Защищает от:

- 🛡️ **Brute-force атак** - множественные попытки входа
- 🛡️ **DDoS атак** - массовые запросы
- 🛡️ **API Abuse** - злоупотребление API
- 🛡️ **Сканирования** - сканеры уязвимостей
- 🛡️ **Повторных нарушений** - рецидивисты

## 🚀 Быстрый старт

```php
use CloudCastle\Http\Router\Facade\Route;

Route::post('/login', 'AuthController@login')
    ->throttleWithBan(
        maxAttempts: 5,              // 5 попыток
        decaySeconds: 60,             // за 1 минуту
        maxViolations: 3,             // 3 нарушения до бана
        banDurationSeconds: 7200      // бан на 2 часа
    );
```

## 📋 Параметры throttleWithBan()

| Параметр | Тип | По умолчанию | Описание |
|----------|-----|--------------|----------|
| `maxAttempts` | int | 60 | Максимум запросов в временном окне |
| `decaySeconds` | int | 60 | Размер временного окна (секунды) |
| `maxViolations` | int | 3 | Количество нарушений до бана |
| `banDurationSeconds` | int | 3600 | Длительность бана (секунды) |
| `key` | string\|null | null | Кастомный ключ для rate limiting |

## 🔄 Как это работает

### Сценарий автобана

```
1. Запрос превышает лимит
   ↓
2. TooManyRequestsException выбрасывается
   ↓
3. Нарушение записывается в BanManager
   ↓
4. Счетчик нарушений: 1/3
   ↓
5. Повторное превышение
   ↓
6. Счетчик нарушений: 2/3
   ↓
7. Третье превышение
   ↓
8. IP банится автоматически
   ↓
9. BannedException для всех запросов
   ↓
10. По истечении времени бан снимается
```

### Визуальный пример

```
┌─────────────────────────────────────────────────────────┐
│ Настройки: 5 запросов/мин, 3 нарушения, бан 1 час      │
└─────────────────────────────────────────────────────────┘

[Окно 1]
Запросы 1-5:    ✅ OK
Запрос 6:       ❌ TooManyRequestsException (нарушение 1/3)

[Окно 2 - через минуту]
Запросы 1-5:    ✅ OK
Запрос 6:       ❌ TooManyRequestsException (нарушение 2/3)

[Окно 3 - через минуту]
Запросы 1-5:    ✅ OK
Запрос 6:       ❌ TooManyRequestsException (нарушение 3/3)
                🚫 IP ЗАБАНЕН!

[Во время бана]
Любой запрос:   ❌ BannedException
                "IP забанен на 59:45 минут"

[Через 1 час]
Запросы:        ✅ OK (бан снят автоматически)
```

## 💡 Примеры использования

### Базовый автобан

```php
Route::post('/api/data', 'ApiController@store')
    ->throttleWithBan(
        maxAttempts: 100,          // 100 запросов
        decaySeconds: 60,           // в минуту
        maxViolations: 5,           // 5 нарушений
        banDurationSeconds: 1800    // бан 30 минут
    );
```

### Защита от brute-force

```php
Route::post('/login', 'AuthController@login')
    ->throttleWithBan(
        maxAttempts: 5,             // 5 попыток
        decaySeconds: 60,            // в минуту
        maxViolations: 3,            // 3 нарушения
        banDurationSeconds: 7200     // бан 2 часа
    );
```

### Мгновенный бан для критичных операций

```php
Route::delete('/admin/critical', 'AdminController@critical')
    ->admin()
    ->throttleWithBan(
        maxAttempts: 1,              // 1 запрос
        decaySeconds: 60,             // в минуту
        maxViolations: 1,             // мгновенный бан!
        banDurationSeconds: 86400     // бан 24 часа
    );
```

### Группа с автобаном

```php
Route::group(['prefix' => 'api/v1'], function() {
    // Разные уровни защиты
    
    Route::get('/users', 'UserController@index')
        ->throttleWithBan(100, 60, 5, 1800);
    
    Route::post('/users', 'UserController@store')
        ->throttleWithBan(20, 60, 3, 3600);
    
    Route::delete('/users/{id}', 'UserController@destroy')
        ->throttleWithBan(5, 60, 1, 7200);
});
```

## 🔧 BanManager API

### Создание

```php
use CloudCastle\Http\Router\BanManager;

$banManager = new BanManager(
    maxViolations: 3,      // нарушений до бана
    banDuration: 3600      // длительность (секунды)
);
```

### Основные методы

```php
// Проверка бана
if ($banManager->isBanned('192.168.1.1')) {
    echo "IP забанен";
}

// Время до снятия бана
$seconds = $banManager->getBanTimeRemaining('192.168.1.1');
echo "Осталось: " . gmdate('H:i:s', $seconds);

// Запись нарушения
$shouldBan = $banManager->recordViolation('192.168.1.1');
if ($shouldBan) {
    echo "IP забанен!";
}

// Ручной бан
$banManager->ban('192.168.1.1', 7200); // 2 часа

// Снятие бана
$banManager->unban('192.168.1.1');

// Счетчик нарушений
$count = $banManager->getViolationCount('192.168.1.1');

// Список забаненных
$banned = $banManager->getBannedIps();
foreach ($banned as $ip => $expiration) {
    echo "$ip забанен до " . date('Y-m-d H:i:s', $expiration);
}

// Статистика
$stats = $banManager->getStatistics();
/*
[
    'total_banned' => 10,
    'total_violations' => 45,
    'unique_ips_with_violations' => 25,
    'max_violations' => 3,
    'ban_duration' => 3600
]
*/
```

## 🎯 Обработка исключений

### BannedException

```php
use CloudCastle\Http\Router\Exceptions\BannedException;

try {
    Route::dispatch('/api/endpoint', 'GET', null, '192.168.1.1');
} catch (BannedException $e) {
    // Детальная информация о бане
    
    $ip = $e->getBannedIp();              // '192.168.1.1'
    $reason = $e->getReason();            // 'Rate limit violations'
    $remaining = $e->getTimeRemaining();  // секунды до снятия
    $expiresAt = $e->getBanExpiresAt();   // timestamp истечения
    
    // Ответ клиенту
    http_response_code(403);
    echo json_encode([
        'error' => 'IP Banned',
        'ip' => $ip,
        'reason' => $reason,
        'time_remaining' => gmdate('H:i:s', $remaining),
        'expires_at' => date('c', $expiresAt)
    ]);
}
```

## 📊 Рекомендации по настройке

### Таблица настроек

| Тип операции | maxAttempts | Окно | maxViolations | Бан | Пример |
|--------------|-------------|------|---------------|-----|--------|
| Публичный API | 100 | 1 мин | 5 | 30 мин | `/api/public` |
| Authenticated API | 50 | 1 мин | 3 | 1 час | `/api/protected` |
| Login/Auth | 5 | 1 мин | 3 | 2 часа | `/login` |
| Password Reset | 3 | 5 мин | 2 | 4 часа | `/password/reset` |
| Admin Panel | 10 | 1 мин | 1 | 8 часов | `/admin/*` |
| Critical Ops | 1 | 1 мин | 1 | 24 часа | `/admin/delete` |

### Примеры кода

**Публичный API:**
```php
->throttleWithBan(100, 60, 5, 1800)
```

**Authentication:**
```php
->throttleWithBan(5, 60, 3, 7200)
```

**Critical:**
```php
->throttleWithBan(1, 60, 1, 86400)
```

## 🔍 Мониторинг и статистика

### Получение статистики

```php
$route = Route::getRouteByName('api.endpoint');
$rateLimiter = $route->getRateLimiter();
$banManager = $rateLimiter->getBanManager();

$stats = $banManager->getStatistics();

echo "Забанено IP: " . $stats['total_banned'];
echo "Всего нарушений: " . $stats['total_violations'];
echo "IP с нарушениями: " . $stats['unique_ips_with_violations'];
```

### Список забаненных IP

```php
$bannedIps = $banManager->getBannedIps();

foreach ($bannedIps as $ip => $expiration) {
    $remaining = $expiration - time();
    echo sprintf(
        "IP: %s, осталось: %s\n",
        $ip,
        gmdate('H:i:s', $remaining)
    );
}
```

## ⚙️ Продвинутое использование

### Кастомный BanManager

```php
use CloudCastle\Http\Router\BanManager;
use CloudCastle\Http\Router\RateLimiter;

// Создаем свой ban manager
$banManager = new BanManager(
    maxViolations: 2,      // строже
    banDuration: 14400     // 4 часа
);

// Создаем rate limiter
$rateLimiter = RateLimiter::perMinute(50);
$rateLimiter->setBanManager($banManager);

// Применяем к маршруту
Route::post('/api/sensitive', 'ApiController@sensitive')
    ->setRateLimiter($rateLimiter);
```

### Разные лимиты для разных IP

```php
// Можно использовать кастомные ключи
Route::post('/api/data', 'ApiController@data')
    ->throttleWithBan(100, 60, 3, 1800, 'api-key-' . $userId);
```

### Очистка банов

```php
// Очистка конкретного IP
$banManager->unban('192.168.1.1');

// Очистка всех банов
$banManager->clearAllBans();

// Очистка только нарушений (без бана)
$banManager->clearViolations('192.168.1.1');
```

## 📈 Производительность

Система автобана работает в памяти и не влияет на производительность:

- ✅ Проверка бана: O(1) - < 0.001ms
- ✅ Запись нарушения: O(1) - < 0.001ms
- ✅ Автоматическое истечение банов
- ✅ Минимальное потребление памяти

## 🧪 Тестирование

Полное покрытие тестами:

- ✅ **BanManagerTest** - 12 тестов
- ✅ **AutoBanIntegrationTest** - 4 теста
- ✅ **100% покрытие кода**

Запуск:
```bash
./vendor/bin/phpunit tests/Unit/BanManagerTest.php
./vendor/bin/phpunit tests/Unit/AutoBanIntegrationTest.php
```

## 🎓 Best Practices

### 1. Градация уровней защиты

```php
// Уровень 1: Мягкий (публичный API)
->throttleWithBan(100, 60, 5, 1800)

// Уровень 2: Средний (authenticated)
->throttleWithBan(50, 60, 3, 3600)

// Уровень 3: Строгий (admin)
->throttleWithBan(10, 60, 2, 14400)

// Уровень 4: Критичный (sensitive ops)
->throttleWithBan(1, 60, 1, 86400)
```

### 2. Комбинация с другими методами

```php
Route::post('/login', 'AuthController@login')
    ->throttleWithBan(5, 60, 3, 7200)
    ->https()                    // Только HTTPS
    ->middleware('csrf')          // CSRF защита
    ->guest();                    // Только для гостей
```

### 3. Логирование банов

```php
use CloudCastle\Http\Router\Exceptions\BannedException;

try {
    Route::dispatch($uri, $method, null, $ip);
} catch (BannedException $e) {
    // Логируем бан
    error_log(sprintf(
        "IP %s banned: %s, remaining: %d sec",
        $e->getBannedIp(),
        $e->getReason(),
        $e->getTimeRemaining()
    ));
    
    // Уведомление админу
    notifyAdmin($e);
    
    // Ответ клиенту
    http_response_code(403);
    echo json_encode(['error' => 'Banned']);
}
```

## 🔒 Безопасность

### Защита от обхода

Автобан работает по IP, поэтому важно:

1. ✅ Использовать правильный IP клиента
2. ✅ Учитывать proxy/load balancer
3. ✅ Проверять X-Forwarded-For (с осторожностью!)

```php
// Получение реального IP
$ip = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'];

// Диспетчеризация
Route::dispatch($uri, $method, null, $ip);
```

### Rate Limiting + IP Whitelist

```php
Route::post('/api/critical', 'ApiController@critical')
    ->whitelistIp(['192.168.1.0/24'])  // Только локальная сеть
    ->throttleWithBan(10, 60, 1, 3600); // + автобан
```

## 📚 Связанные разделы

- [Rate Limiting](rate-limiting.md) - базовое ограничение запросов
- [Временные окна](time-units.md) - различные временные периоды
- [Безопасность](security.md) - общая безопасность
- [Middleware](middleware.md) - промежуточные обработчики

---

**CloudCastle HTTP Router** - максимальная защита с автобаном! 🚫

---

**Переводы**: [English](../../en/documentation/auto-ban.md) | [Deutsch](../../de/documentation/auto-ban.md) | [Français](../../fr/documentation/auto-ban.md)

