# Автобан

**CloudCastle HTTP Router v1.1.1**  
**Язык**: Русский

---

## 🌍 Переводы

- **[Русский](auto-ban.md)** (текущий)
- [English](../../en/documentation/auto-ban.md)
- [Deutsch](../../de/documentation/auto-ban.md)
- [Français](../../fr/documentation/auto-ban.md)

---

## 🚫 Введение

Система автоматического бана - уникальная функция CloudCastle Router для защиты от brute-force атак, DDoS и других злоупотреблений.

**Принцип работы**: При превышении лимитов запросов определённое количество раз, IP адрес автоматически блокируется на заданное время.

---

## 🎯 Базовое использование

### Простой автобан

```php
Route::post('/login', 'AuthController@login')
    ->throttleWithBan(
        maxAttempts: 5,           // 5 попыток
        decaySeconds: 60,          // за 60 секунд
        maxViolations: 3,          // 3 нарушения до бана
        banDurationSeconds: 7200   // бан на 2 часа
    );
```

### Как это работает

1. Пользователь делает **5 неудачных попыток** входа за **60 секунд**
2. Это считается как **1 нарушение**
3. После **3 нарушений** - IP **блокируется на 2 часа**

---

## ⚙️ Параметры

### maxAttempts
**Тип**: `int`  
**Описание**: Максимальное количество запросов в временном окне

```php
->throttleWithBan(
    maxAttempts: 10,  // 10 запросов
    // ...
)
```

### decaySeconds
**Тип**: `int`  
**Описание**: Временное окно в секундах

```php
->throttleWithBan(
    maxAttempts: 10,
    decaySeconds: 60,  // за 60 секунд
    // ...
)
```

### maxViolations
**Тип**: `int`  
**Описание**: Количество нарушений до бана

```php
->throttleWithBan(
    maxAttempts: 5,
    decaySeconds: 60,
    maxViolations: 3,  // 3 нарушения
    // ...
)
```

### banDurationSeconds
**Тип**: `int`  
**Описание**: Длительность бана в секундах

```php
->throttleWithBan(
    maxAttempts: 5,
    decaySeconds: 60,
    maxViolations: 3,
    banDurationSeconds: 3600  // бан на 1 час
)
```

---

## 💡 Примеры использования

### Защита login endpoint

```php
Route::post('/login', 'AuthController@login')
    ->throttleWithBan(
        maxAttempts: 5,           // 5 попыток входа
        decaySeconds: 60,          // за минуту
        maxViolations: 3,          // 3 превышения
        banDurationSeconds: 7200   // бан на 2 часа
    );
```

**Сценарий**:
- Атакующий пытается подобрать пароль
- После 5 неудачных попыток за минуту - 1 нарушение
- После 3 таких нарушений - IP заблокирован на 2 часа

### Защита API endpoint

```php
Route::post('/api/data', 'ApiController@store')
    ->throttleWithBan(
        maxAttempts: 100,          // 100 запросов
        decaySeconds: 60,           // за минуту
        maxViolations: 5,           // 5 превышений
        banDurationSeconds: 3600    // бан на час
    );
```

### Защита регистрации

```php
Route::post('/register', 'AuthController@register')
    ->throttleWithBan(
        maxAttempts: 3,            // 3 регистрации
        decaySeconds: 3600,         // за час
        maxViolations: 2,           // 2 превышения
        banDurationSeconds: 86400   // бан на день
    );
```

### Защита от парсинга

```php
Route::get('/api/catalog', 'CatalogController@index')
    ->throttleWithBan(
        maxAttempts: 100,           // 100 запросов
        decaySeconds: 60,            // за минуту
        maxViolations: 10,           // 10 превышений
        banDurationSeconds: 86400    // бан на сутки
    );
```

---

## 🔧 Управление банами

### BanManager

```php
use CloudCastle\Http\Router\BanManager;

$banManager = new BanManager();

// Проверка бана
if ($banManager->isBanned('192.168.1.100')) {
    echo 'IP заблокирован';
}

// Ручная блокировка
$banManager->ban('192.168.1.100', 3600); // на час

// Разблокировка
$banManager->unban('192.168.1.100');

// Список заблокированных IP
$bannedIps = $banManager->getBannedIps();

// Статистика
$stats = $banManager->getStatistics();
echo "Заблокировано IP: " . $stats['total_banned'];
echo "Всего нарушений: " . $stats['total_violations'];
```

---

## 📊 Статистика

### Получение статистики бана

```php
$stats = $banManager->getStatistics();

print_r($stats);
/* Вывод:
[
    'total_banned' => 15,
    'total_violations' => 127,
    'active_bans' => 8,
    'expired_bans' => 7
]
*/
```

### Время до разблокировки

```php
$timeRemaining = $banManager->getBanTimeRemaining('192.168.1.100');

if ($timeRemaining > 0) {
    $minutes = ceil($timeRemaining / 60);
    echo "Разблокировка через $minutes минут";
}
```

---

## ⚠️ Обработка исключений

### BannedException

```php
use CloudCastle\Http\Router\Exceptions\BannedException;

try {
    $result = Route::dispatch($uri, $method);
} catch (BannedException $e) {
    http_response_code(403);
    
    echo json_encode([
        'error' => 'IP адрес заблокирован',
        'ip' => $e->getIp(),
        'retry_after' => $e->getRetryAfter(),
        'reason' => $e->getReason()
    ]);
}
```

### Детали исключения

```php
try {
    Route::dispatch($uri, $method);
} catch (BannedException $e) {
    $ip = $e->getIp();              // Заблокированный IP
    $retryAfter = $e->getRetryAfter(); // Секунд до разблокировки
    $reason = $e->getReason();      // Причина бана
    
    // Логирование
    error_log("Banned IP $ip attempted access. Retry after: $retryAfter seconds");
    
    // Ответ клиенту
    header("Retry-After: $retryAfter");
    http_response_code(403);
}
```

---

## 🎯 Стратегии защиты

### Агрессивная защита (строгая)

```php
Route::post('/admin/login', 'AdminController@login')
    ->throttleWithBan(
        maxAttempts: 3,            // Мало попыток
        decaySeconds: 60,
        maxViolations: 1,          // Бан сразу
        banDurationSeconds: 86400  // Долгий бан (сутки)
    );
```

### Умеренная защита (обычная)

```php
Route::post('/login', 'AuthController@login')
    ->throttleWithBan(
        maxAttempts: 5,
        decaySeconds: 60,
        maxViolations: 3,
        banDurationSeconds: 3600   // 1 час
    );
```

### Мягкая защита (либеральная)

```php
Route::get('/api/public', 'ApiController@public')
    ->throttleWithBan(
        maxAttempts: 100,
        decaySeconds: 60,
        maxViolations: 10,
        banDurationSeconds: 600    // 10 минут
    );
```

---

## 📊 Мониторинг

### Логирование нарушений

```php
Route::post('/api/data', 'ApiController@store')
    ->throttleWithBan(5, 60, 3, 7200)
    ->middleware(function($request, $next) {
        try {
            return $next($request);
        } catch (BannedException $e) {
            error_log("[BAN] IP: {$e->getIp()}, Retry: {$e->getRetryAfter()}s");
            throw $e;
        }
    });
```

---

## 🔗 См. также

- [Rate Limiting](rate-limiting.md)
- [Безопасность](security.md)
- [Примеры](../../../examples/autoban-example.php)

---

**[← Назад к оглавлению](README.md)**

