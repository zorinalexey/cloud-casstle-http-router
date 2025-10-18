# Rate Limiting

**CloudCastle HTTP Router v1.1.1**  
**Язык**: Русский

---

## 🌍 Переводы

- **[Русский](rate-limiting.md)** (текущий)
- [English](../../en/documentation/rate-limiting.md)
- [Deutsch](../../de/documentation/rate-limiting.md)
- [Français](../../fr/documentation/rate-limiting.md)

---

## 📋 Введение

Rate Limiting (ограничение частоты запросов) - встроенная функция CloudCastle Router для защиты от злоупотреблений и перегрузки.

**Особенности**:
- ✅ Гибкие временные окна (секунды, минуты, часы, дни, недели, месяцы)
- ✅ Кастомные ключи ограничения
- ✅ Интеграция с автобаном
- ✅ Простой API

---

## ⏱️ Временные окна

### perSecond() - В секунду

```php
Route::get('/api/fast', 'ApiController@fast')
    ->perSecond(10);  // 10 запросов в секунду
```

### perMinute() - В минуту

```php
Route::post('/api/data', 'ApiController@store')
    ->perMinute(60);  // 60 запросов в минуту (по умолчанию)
```

### perHour() - В час

```php
Route::post('/api/heavy', 'ApiController@heavy')
    ->perHour(100);  // 100 запросов в час
```

### perDay() - В день

```php
Route::post('/api/email', 'EmailController@send')
    ->perDay(100);  // 100 писем в день
```

### perWeek() - В неделю

```php
Route::post('/newsletter/subscribe', 'NewsletterController@subscribe')
    ->perWeek(1);  // 1 подписка в неделю
```

### perMonth() - В месяц

```php
Route::post('/billing/invoice', 'BillingController@generate')
    ->perMonth(10);  // 10 счетов в месяц
```

---

## 🔧 Базовое использование

### Простое ограничение

```php
Route::post('/api/data', 'ApiController@store')
    ->throttle(60, 1);  // 60 запросов за 1 минуту
```

### С кастомным ключом

```php
Route::post('/api/data', 'ApiController@store')
    ->throttle(60, 1, 'api_' . $_SESSION['user_id']);
```

---

## 🎯 Продвинутые примеры

### Разные лимиты для разных эндпоинтов

```php
// Быстрые запросы
Route::get('/api/status', 'ApiController@status')
    ->perSecond(100);

// Обычные запросы
Route::get('/api/users', 'ApiController@users')
    ->perMinute(60);

// Тяжёлые операции
Route::post('/api/export', 'ApiController@export')
    ->perHour(5);

// Критичные действия
Route::post('/api/delete-account', 'AccountController@delete')
    ->perDay(1);
```

### С группами

```php
Route::group(['throttle' => [100, 1]], function() {
    Route::get('/api/users', 'ApiController@users');
    Route::get('/api/posts', 'ApiController@posts');
    // Оба: 100 запросов в минуту
});
```

### Каскадные лимиты

```php
Route::group(['perMinute' => 1000], function() {
    // Общий лимит группы: 1000/минуту
    
    Route::get('/api/list', 'ApiController@list')
        ->perSecond(50);  // + индивидуальный: 50/секунду
        
    Route::post('/api/create', 'ApiController@create')
        ->perMinute(100);  // + индивидуальный: 100/минуту
});
```

---

## 📊 Информация о лимитах

### Проверка оставшихся попыток

```php
$rateLimiter = Route::get('/api/data', 'ApiController@data')
    ->perMinute(60)
    ->getRateLimiter();

// Доступно попыток
$remaining = $rateLimiter->remaining('user_key');

// Всего попыток
$maxAttempts = $rateLimiter->maxAttempts();

// Время до сброса
$availableIn = $rateLimiter->availableIn('user_key');
```

---

## 🔑 Кастомные ключи

### По IP адресу (по умолчанию)

```php
Route::post('/api/data', 'ApiController@store')
    ->perMinute(60);
// Ключ: IP адрес пользователя
```

### По ID пользователя

```php
$userId = $_SESSION['user_id'] ?? 'guest';

Route::post('/api/data', 'ApiController@store')
    ->throttle(60, 1, 'user_' . $userId);
```

### По API ключу

```php
$apiKey = $_SERVER['HTTP_X_API_KEY'] ?? 'anonymous';

Route::post('/api/data', 'ApiController@store')
    ->throttle(1000, 1, 'api_key_' . $apiKey);
```

### Комбинированный ключ

```php
$key = implode('_', [
    $_SERVER['REMOTE_ADDR'],
    $_SESSION['user_id'] ?? 'guest',
    'api_data'
]);

Route::post('/api/data', 'ApiController@store')
    ->throttle(100, 1, $key);
```

---

## ⚠️ Обработка ошибок

### TooManyRequestsException

```php
use CloudCastle\Http\Router\Exceptions\TooManyRequestsException;

try {
    $result = Route::dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
} catch (TooManyRequestsException $e) {
    http_response_code(429);
    echo json_encode([
        'error' => 'Too Many Requests',
        'retry_after' => $e->getRetryAfter()
    ]);
}
```

### Детали исключения

```php
try {
    $result = Route::dispatch($uri, $method);
} catch (TooManyRequestsException $e) {
    $maxAttempts = $e->getMaxAttempts();    // Максимум попыток
    $retryAfter = $e->getRetryAfter();      // Секунд до retry
    $key = $e->getKey();                     // Ключ ограничения
    
    header("X-RateLimit-Limit: $maxAttempts");
    header("X-RateLimit-Retry-After: $retryAfter");
    http_response_code(429);
}
```

---

## 🎯 Сценарии использования

### API endpoint

```php
Route::post('/api/v1/data', 'ApiController@store')
    ->perMinute(100)
    ->middleware('auth');
```

### Login endpoint

```php
Route::post('/login', 'AuthController@login')
    ->perMinute(5)  // 5 попыток входа в минуту
    ->throttle(3, 0.0166);  // 3 попытки в секунду
```

### Публичный API

```php
Route::get('/api/public/data', 'PublicApiController@data')
    ->perHour(1000);  // 1000 запросов в час для анонимных
```

### Premium пользователи

```php
$isPremium = isPremiumUser();
$limit = $isPremium ? 10000 : 1000;

Route::get('/api/data', 'ApiController@data')
    ->perHour($limit);
```

---

## 🔄 Интеграция с автобаном

См. [Автобан](auto-ban.md) для интеграции rate limiting с системой автоматического бана.

```php
Route::post('/login', 'AuthController@login')
    ->throttleWithBan(
        maxAttempts: 5,
        decaySeconds: 60,
        maxViolations: 3,
        banDurationSeconds: 7200
    );
```

---

## 📈 Производительность

Rate limiting добавляет минимальные накладные расходы:

```
Без rate limiting:   60,095 req/s
С rate limiting:     59,850 req/s
Разница:             -0.4%
```

---

## 🔗 См. также

- [Автобан](auto-ban.md)
- [Безопасность](security.md)
- [Примеры](../../../examples/throttle-example.php)

---

**[← Назад к оглавлению](README.md)**

