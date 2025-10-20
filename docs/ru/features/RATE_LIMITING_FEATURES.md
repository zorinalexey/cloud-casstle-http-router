# Rate Limiting - Детальное описание возможностей

[English](../../en/features/RATE_LIMITING_FEATURES.md) | **Русский** | [Deutsch](../../de/features/RATE_LIMITING_FEATURES.md) | [Français](../../fr/features/RATE_LIMITING_FEATURES.md) | [中文](../../zh/features/RATE_LIMITING_FEATURES.md)

---

## Содержание

- [Введение](#введение)
- [Базовый throttle](#базовый-throttle)
- [TimeUnit enum](#timeunit-enum)
- [Удобные методы по времени](#удобные-методы-по-времени)
- [throttleWithBan - автобан](#throttlewithban---автобан)
- [Кастомные ключи](#кастомные-ключи)
- [RateLimiter класс](#ratelimiter-класс)
- [Route Shortcuts](#route-shortcuts)
- [Сравнение с аналогами](#сравнение-с-аналогами)
- [Рекомендации](#рекомендации)

---

## Введение

Rate Limiting (ограничение частоты запросов) - это критически важная функция для защиты API и веб-приложений от:
- DDoS атак
- Брутфорс атак
- Спама
- Злоупотребления ресурсами

CloudCastle HTTP Router включает **мощную и гибкую систему Rate Limiting** из коробки.

---

## Базовый throttle

### Описание

Метод `throttle()` ограничивает количество запросов к маршруту в единицу времени.

### Использование

```php
use CloudCastle\Http\Router\Facade\Route;

// 60 запросов в минуту
Route::get('/api/data', $action)
    ->throttle(60, 1);

// 100 запросов в час
Route::post('/api/submit', $action)
    ->throttle(100, 60);

// 10 запросов в день
Route::post('/api/export', $action)
    ->throttle(10, 1440);
```

### Параметры

```php
throttle(
    int $maxAttempts,     // Максимум попыток
    int $decayMinutes,    // Временное окно в минутах
    ?callable $key = null // Опциональный ключ идентификации
)
```

### Сравнение с аналогами

| Роутер | Встроенный Rate Limiting | API | Гибкость | Оценка |
|--------|--------------------------|-----|----------|--------|
| **CloudCastle** | ✅ **Да** | **->throttle()** | **⭐⭐⭐⭐⭐** | **⭐⭐⭐⭐⭐** |
| Laravel | ✅ Да | ->middleware('throttle:60,1') | ⭐⭐⭐⭐ | ⭐⭐⭐⭐⭐ |
| Symfony | ❌ Нет | Требует RateLimiter component | ⭐⭐⭐ | ⭐⭐⭐ |
| FastRoute | ❌ Нет | Нужно писать свой | ⭐ | ⭐ |
| Slim | ❌ Нет | Требует middleware пакет | ⭐⭐ | ⭐⭐ |

**Плюсы CloudCastle:**
- ✅ Встроено из коробки
- ✅ Простой API (один метод)
- ✅ Fluent interface
- ✅ Не требует дополнительных пакетов

**Минусы:**
- ⚠️ Хранение в памяти (не Redis по умолчанию)

### Примеры использования

**API endpoint:**
```php
// Публичный API
Route::get('/api/public/data', $action)
    ->throttle(100, 1); // 100 req/min

// Приватный API
Route::get('/api/private/data', $action)
    ->middleware(['auth'])
    ->throttle(1000, 1); // 1000 req/min для авторизованных
```

**Формы:**
```php
// Форма обратной связи
Route::post('/contact', $action)
    ->throttle(5, 60); // 5 отправок в час

// Регистрация
Route::post('/register', $action)
    ->throttle(3, 600); // 3 регистрации в 10 минут
```

**Авторизация:**
```php
// Попытки входа
Route::post('/login', $action)
    ->throttle(5, 1); // 5 попыток в минуту
```

---

## TimeUnit enum

### Описание

TimeUnit - это enum для удобного указания временных интервалов без ручного подсчета минут.

### Доступные единицы

```php
use CloudCastle\Http\Router\TimeUnit;

TimeUnit::SECOND  // 1 секунда
TimeUnit::MINUTE  // 60 секунд
TimeUnit::HOUR    // 3600 секунд
TimeUnit::DAY     // 86400 секунд
TimeUnit::WEEK    // 604800 секунд
TimeUnit::MONTH   // 2592000 секунд (30 дней)
```

### Использование

```php
// 100 запросов в день
Route::post('/api/report', $action)
    ->throttle(100, TimeUnit::DAY->value / 60); // Конвертируем в минуты

// Или в секундах
$limiter = new RateLimiter(10, TimeUnit::HOUR->value);
```

### Методы TimeUnit

```php
$unit = TimeUnit::HOUR;

$seconds = $unit->toSeconds(2);  // 7200 (2 часа в секундах)
$name = $unit->getName();        // 'hour'
$plural = $unit->getPlural();    // 'hours'
```

### Сравнение с аналогами

| Роутер | TimeUnit enum | Удобство | Оценка |
|--------|---------------|----------|--------|
| **CloudCastle** | ✅ **Да (6 единиц)** | **⭐⭐⭐⭐⭐** | **⭐⭐⭐⭐⭐** |
| Laravel | ❌ Нет | ⭐⭐⭐ | ⭐⭐⭐⭐ |
| Symfony | ❌ Нет | ⭐⭐⭐ | ⭐⭐⭐ |
| FastRoute | ❌ Нет | - | - |
| Slim | ❌ Нет | - | - |

**Уникальность:** CloudCastle - единственный роутер с TimeUnit enum!

**Плюсы:**
- ✅ Читаемый код
- ✅ Нет magic numbers
- ✅ Type-safe
- ✅ Самодокументируемый

**Примеры:**
```php
// Ясно и понятно
Route::post('/export', $action)
    ->throttle(5, TimeUnit::DAY->value / 60);

// Вместо магических чисел
// ->throttle(5, 1440) // Что это за 1440?
```

---

## Удобные методы по времени

### perSecond()

Лимит на секунду:

```php
// 10 запросов в секунду
Route::post('/api/track', $action)->perSecond(10);

// 5 запросов за 3 секунды
Route::post('/api/upload', $action)->perSecond(5, 3);
```

### perMinute()

Лимит на минуту:

```php
// 60 запросов в минуту
Route::get('/api/data', $action)->perMinute(60);

// 20 запросов за 5 минут
Route::post('/api/search', $action)->perMinute(20, 5);
```

### perHour()

Лимит на час:

```php
// 1000 запросов в час
Route::get('/api/export', $action)->perHour(1000);

// 500 запросов за 6 часов
Route::post('/api/batch', $action)->perHour(500, 6);
```

### perDay()

Лимит на день:

```php
// 10000 запросов в день
Route::get('/api/reports', $action)->perDay(10000);

// 1000 запросов за 7 дней
Route::post('/api/heavy', $action)->perDay(1000, 7);
```

### perWeek()

```php
// 50000 запросов в неделю
Route::get('/api/analytics', $action)->perWeek(50000);
```

### perMonth()

```php
// 1000000 запросов в месяц
Route::get('/api/stats', $action)->perMonth(1000000);
```

### Сравнение

| Метод | CloudCastle | Laravel | Symfony |
|-------|-------------|---------|---------|
| perSecond() | ✅ | ❌ | ❌ |
| perMinute() | ✅ | ⚠️ Частично | ❌ |
| perHour() | ✅ | ❌ | ❌ |
| perDay() | ✅ | ❌ | ❌ |
| perWeek() | ✅ | ❌ | ❌ |
| perMonth() | ✅ | ❌ | ❌ |

**CloudCastle - ЕДИНСТВЕННЫЙ с полным набором time-based методов!**

---

## throttleWithBan - автобан

### Описание

**УНИКАЛЬНАЯ ВОЗМОЖНОСТЬ!** Автоматический бан IP после множественных превышений лимита.

### Использование

```php
Route::post('/login', $action)
    ->throttleWithBan(
        maxAttempts: 5,           // 5 попыток
        decaySeconds: 60,         // за 60 секунд
        maxViolations: 3,         // после 3 нарушений
        banDurationSeconds: 3600  // бан на 1 час
    );

// Результат:
// 1. Пользователь делает 6 запросов за минуту → нарушение #1
// 2. Через минуту делает еще 6 → нарушение #2
// 3. Еще раз 6 запросов → нарушение #3
// 4. IP ЗАБАНЕН на 1 час!
```

### Примеры

**Защита от брутфорса:**
```php
Route::post('/api/auth', LoginController::class)
    ->throttleWithBan(
        maxAttempts: 10,
        decaySeconds: 60,
        maxViolations: 5,
        banDurationSeconds: 86400 // 24 часа
    );
```

**Защита формы регистрации:**
```php
Route::post('/register', $action)
    ->throttleWithBan(
        maxAttempts: 3,
        decaySeconds: 300,  // 5 минут
        maxViolations: 2,
        banDurationSeconds: 1800  // 30 минут
    );
```

**API защита:**
```php
Route::post('/api/sensitive', $action)
    ->throttleWithBan(
        maxAttempts: 20,
        decaySeconds: 60,
        maxViolations: 10,
        banDurationSeconds: 3600
    );
```

### Сравнение

| Роутер | throttleWithBan | Оценка |
|--------|-----------------|--------|
| **CloudCastle** | ✅ **Встроено!** | **⭐⭐⭐⭐⭐** |
| Laravel | ❌ Требует пакет | ⭐⭐ |
| Symfony | ❌ Требует пакет | ⭐⭐ |
| FastRoute | ❌ | ⭐ |
| Slim | ❌ | ⭐ |

**УНИКАЛЬНОСТЬ:** Только CloudCastle имеет встроенный throttleWithBan!

---

## Кастомные ключи

### Описание

По умолчанию Rate Limiting использует IP адрес клиента. Но можно использовать кастомный ключ идентификации.

### Использование

```php
// По IP (по умолчанию)
Route::get('/api/data', $action)
    ->throttle(60, 1);

// По User ID
Route::get('/api/data', $action)
    ->throttle(60, 1, function($request) {
        return $request->user()->id;
    });

// По API ключу
Route::get('/api/data', $action)
    ->throttle(1000, 1, function($request) {
        return $request->header('X-API-Key');
    });

// Комбинированный ключ
Route::post('/api/action', $action)
    ->throttle(10, 1, function($request) {
        return $request->user()->id . ':' . $request->ip();
    });
```

### Сравнение

| Роутер | Кастомные ключи | Примеры | Оценка |
|--------|-----------------|---------|--------|
| **CloudCastle** | ✅ **Да (Closure)** | **User ID, API Key** | **⭐⭐⭐⭐⭐** |
| Laravel | ✅ Да | User ID, API Key | ⭐⭐⭐⭐⭐ |
| Symfony | ⚠️ Сложно | Требует конфигурацию | ⭐⭐⭐ |
| FastRoute | ❌ Нет | - | - |
| Slim | ❌ Нет | - | - |

**Плюсы CloudCastle:**
- ✅ Простой API (Closure)
- ✅ Любая логика идентификации
- ✅ Гибкость

**Примеры реального использования:**

**1. Разные лимиты для разных планов:**
```php
Route::get('/api/premium', $action)
    ->throttle(1000, 1, function($request) {
        $user = $request->user();
        return $user->isPremium() 
            ? "premium:{$user->id}" 
            : "free:{$user->id}";
    });

// В middleware проверяем ключ и применяем разные лимиты
```

**2. По организации (multi-tenant):**
```php
Route::get('/api/data', $action)
    ->throttle(100, 1, function($request) {
        return $request->user()->organization_id;
    });
```

**3. По endpoint:**
```php
Route::group(['prefix' => '/api'], function() {
    Route::get('/search', $action)
        ->throttle(30, 1, fn($req) => 'search:' . $req->ip());
    
    Route::get('/export', $action)
        ->throttle(5, 1, fn($req) => 'export:' . $req->ip());
});
```

---

## RateLimiter класс

### Описание

RateLimiter - это самостоятельный класс для ручного управления лимитами.

### Создание

```php
use CloudCastle\Http\Router\RateLimiter;

// 60 запросов в 1 минуту
$limiter = new RateLimiter(60, 1);

// 100 запросов в час
$limiter = new RateLimiter(100, 60);
```

### Методы

#### attempt()

Регистрация попытки:

```php
$limiter->attempt($identifier);
```

#### tooManyAttempts()

Проверка превышения лимита:

```php
if ($limiter->tooManyAttempts($identifier)) {
    throw new TooManyRequestsException();
}
```

#### remaining()

Оставшиеся попытки:

```php
$remaining = $limiter->remaining($identifier);
// 45 (из 60)
```

#### availableIn()

Когда снова доступно:

```php
$seconds = $limiter->availableIn($identifier);
// 42 (секунд до сброса)
```

### Полный пример

```php
use CloudCastle\Http\Router\RateLimiter;
use CloudCastle\Http\Router\Exceptions\TooManyRequestsException;

$limiter = new RateLimiter(60, 1);
$identifier = $_SERVER['REMOTE_ADDR'];

// Проверка
if ($limiter->tooManyAttempts($identifier)) {
    $retryAfter = $limiter->availableIn($identifier);
    $remaining = $limiter->remaining($identifier);
    
    $exception = new TooManyRequestsException('Too many requests');
    $exception->setRetryAfter($retryAfter);
    $exception->setRemaining($remaining);
    
    throw $exception;
}

// Регистрация попытки
$limiter->attempt($identifier);

// Получение информации
$info = [
    'remaining' => $limiter->remaining($identifier),
    'reset_in' => $limiter->availableIn($identifier),
];
```

### Сравнение

| Роутер | Отдельный класс | API | Информация | Оценка |
|--------|-----------------|-----|------------|--------|
| **CloudCastle** | ✅ **RateLimiter** | **Полный** | **remaining, availableIn** | **⭐⭐⭐⭐⭐** |
| Laravel | ✅ RateLimiter | Полный | remaining, availableIn | ⭐⭐⭐⭐⭐ |
| Symfony | ✅ RateLimiter | Полный | Детальная инфо | ⭐⭐⭐⭐⭐ |
| FastRoute | ❌ Нет | - | - | ❌ |
| Slim | ❌ Нет | - | - | ❌ |

---

## Route Shortcuts

### Описание

Быстрые методы для типичных сценариев Rate Limiting.

### throttleStandard()

Стандартный лимит - 60 запросов в минуту:

```php
Route::get('/api/data', $action)
    ->throttleStandard();

// Эквивалент
// ->throttle(60, 1)
```

**Когда использовать:**
- Обычные API endpoints
- Публичные данные
- Средняя нагрузка

### throttleStrict()

Строгий лимит - 10 запросов в минуту:

```php
Route::post('/api/sensitive', $action)
    ->throttleStrict();

// Эквивалент
// ->throttle(10, 1)
```

**Когда использовать:**
- Чувствительные операции
- Формы (контакт, регистрация)
- Операции с побочными эффектами

### throttleGenerous()

Щедрый лимит - 1000 запросов в минуту:

```php
Route::get('/api/public', $action)
    ->throttleGenerous();

// Эквивалент
// ->throttle(1000, 1)
```

**Когда использовать:**
- Публичные данные
- Статический контент через API
- High-traffic endpoints

### Сравнение

| Shortcuts | CloudCastle | Laravel | Symfony | Оценка |
|-----------|-------------|---------|---------|--------|
| Встроенные | ✅ 3 шт | ❌ Нет | ❌ Нет | ⭐⭐⭐⭐⭐ |

**Уникальность:** CloudCastle - единственный с готовыми shortcuts!

**Плюсы:**
- ✅ Не нужно помнить магические числа
- ✅ Самодокументируемый код
- ✅ Консистентность в проекте

**Пример использования:**
```php
// Ясный и читаемый код
Route::group(['prefix' => '/api'], function() {
    Route::get('/public/news', $action)->throttleGenerous();
    Route::get('/users', $action)->throttleStandard();
    Route::post('/contact', $action)->throttleStrict();
});
```

---

## Сравнение с аналогами

### Детальное сравнение реализаций

#### CloudCastle HTTP Router

**Синтаксис:**
```php
Route::post('/api/data', $action)
    ->throttle(60, 1);

// Или
Route::post('/api/data', $action)
    ->throttleStandard();
```

**Возможности:**
- ✅ Встроенный Rate Limiting
- ✅ TimeUnit enum
- ✅ Кастомные ключи
- ✅ RateLimiter класс
- ✅ 3 shortcuts
- ✅ Информация о лимитах (remaining, availableIn)

**Плюсы:**
- ✅ Из коробки
- ✅ Простой API
- ✅ Гибкость
- ✅ Shortcuts

**Минусы:**
- ⚠️ In-memory storage (для production лучше Redis)

**Оценка:** ⭐⭐⭐⭐⭐ (5/5)

---

#### Laravel

**Синтаксис:**
```php
Route::post('/api/data', $action)
    ->middleware('throttle:60,1');

// Или в RouteServiceProvider
RateLimiter::for('api', function (Request $request) {
    return Limit::perMinute(60);
});
```

**Возможности:**
- ✅ Встроенный Rate Limiting
- ✅ Named limiters
- ✅ Множественные лимиты
- ✅ Redis поддержка
- ❌ Нет TimeUnit enum
- ❌ Нет shortcuts

**Плюсы:**
- ✅ Мощный и гибкий
- ✅ Redis из коробки
- ✅ Named limiters

**Минусы:**
- ⚠️ Middleware syntax менее удобен
- ⚠️ Требует больше настройки

**Оценка:** ⭐⭐⭐⭐⭐ (5/5)

---

#### Symfony

**Синтаксис:**
```php
// Требует установки компонента
composer require symfony/rate-limiter

// В контроллере
use Symfony\Component\RateLimiter\RateLimiterFactory;

$limiter = $factory->create($identifier);
if (!$limiter->consume()->isAccepted()) {
    throw new TooManyRequestsException();
}
```

**Возможности:**
- ⚠️ Отдельный компонент
- ✅ Мощный RateLimiter
- ✅ Множество стратегий
- ✅ Redis/Memcached

**Плюсы:**
- ✅ Очень гибкий
- ✅ Различные алгоритмы (Token Bucket, Fixed Window, Sliding Window)

**Минусы:**
- ❌ Не встроен в роутер
- ❌ Сложная настройка
- ❌ Требует дополнительный код

**Оценка:** ⭐⭐⭐⭐ (4/5) - мощный, но сложный

---

#### FastRoute

**Реализация:**
```php
// Нет встроенной поддержки
// Нужно писать свой middleware

$rateLimiter = new CustomRateLimiter();
if ($rateLimiter->isLimited($ip)) {
    throw new Exception('Rate limit exceeded');
}
```

**Возможности:**
- ❌ Нет встроенного
- ⚠️ Требует полная реализация

**Плюсы:**
- Нет (нужно писать с нуля)

**Минусы:**
- ❌ Нет из коробки
- ❌ Нужно писать самому
- ❌ Дополнительная работа

**Оценка:** ⭐ (1/5) - отсутствует

---

#### Slim

**Реализация:**
```php
// Требует middleware пакет
composer require psr7-sessions/storageless

// Middleware
$app->add(new RateLimitMiddleware($storage, 60, 1));
```

**Возможности:**
- ❌ Не встроен
- ⚠️ Требует сторонний пакет

**Плюсы:**
- ⚠️ Есть готовые пакеты

**Минусы:**
- ❌ Не из коробки
- ❌ Дополнительные зависимости

**Оценка:** ⭐⭐ (2/5) - требует доп. пакеты

---

### Сводная таблица

| Фича | CloudCastle | Laravel | Symfony | FastRoute | Slim |
|------|-------------|---------|---------|-----------|------|
| Встроенный | ✅ | ✅ | ❌ | ❌ | ❌ |
| Простой API | ✅ | ⚠️ | ❌ | - | - |
| TimeUnit enum | ✅ | ❌ | ❌ | - | - |
| Кастомные ключи | ✅ | ✅ | ✅ | - | - |
| Shortcuts | ✅ | ❌ | ❌ | - | - |
| Информация (remaining) | ✅ | ✅ | ✅ | - | - |
| Redis поддержка | ⚠️ | ✅ | ✅ | - | - |
| **Оценка** | **⭐⭐⭐⭐⭐** | **⭐⭐⭐⭐⭐** | **⭐⭐⭐⭐** | **⭐** | **⭐⭐** |

---

## Рекомендации

### Применение в Production

1. **Всегда используйте на публичных endpoints:**
   ```php
   Route::group(['prefix' => '/api'], function() {
       Route::get('/public/*', $action)->throttle(100, 1);
       Route::post('/public/*', $action)->throttle(50, 1);
   });
   ```

2. **Строже для write операций:**
   ```php
   Route::get('/api/read', $action)->throttle(100, 1);
   Route::post('/api/write', $action)->throttle(20, 1); // В 5 раз строже
   ```

3. **Очень строго для аутентификации:**
   ```php
   Route::post('/login', $action)->throttle(5, 1);
   Route::post('/register', $action)->throttle(3, 60);
   Route::post('/password/reset', $action)->throttle(3, 1);
   ```

4. **Используйте кастомные ключи для авторизованных:**
   ```php
   Route::get('/api/user/data', $action)
       ->middleware(['auth'])
       ->throttle(1000, 1, fn($req) => $req->user()->id);
   ```

### Best Practices

**DO:**
- ✅ Используйте разные лимиты для разных типов операций
- ✅ Комбинируйте с аутентификацией
- ✅ Логируйте превышения лимитов
- ✅ Возвращайте Retry-After header

**DON'T:**
- ❌ Не делайте лимиты слишком строгими (UX страдает)
- ❌ Не забывайте про burst traffic
- ❌ Не используйте одинаковый лимит везде

### Мониторинг

```php
// Логирование превышений
Route::get('/api/data', $action)
    ->throttle(60, 1)
    ->middleware([function($request, $next) {
        try {
            return $next($request);
        } catch (TooManyRequestsException $e) {
            Log::warning('Rate limit exceeded', [
                'ip' => $request->ip(),
                'route' => $request->path(),
            ]);
            throw $e;
        }
    }]);
```

### Улучшения для CloudCastle

Возможные улучшения:

1. **Redis backend:**
   ```php
   $limiter = new RateLimiter(60, 1, new RedisStorage());
   ```

2. **Различные алгоритмы:**
   - Token Bucket
   - Leaky Bucket
   - Sliding Window

3. **Burst поддержка:**
   ```php
   ->throttle(60, 1, burst: 10) // 60/min + burst 10
   ```

4. **По весу запросов:**
   ```php
   ->throttleWeighted(['simple' => 1, 'complex' => 5]);
   ```

---

## Заключение

**CloudCastle HTTP Router предоставляет одну из лучших реализаций Rate Limiting:**

✅ Встроено из коробки  
✅ Простой и интуитивный API  
✅ Уникальные фичи (TimeUnit enum, shortcuts)  
✅ Гибкость (кастомные ключи)  
✅ Полный контроль (RateLimiter класс)  

**Рекомендация:** Отличная реализация, готовая к использованию в production. Для enterprise можно добавить Redis backend.

---

[⬆ Наверх](#rate-limiting---детальное-описание-возможностей) | [📚 Все фичи](../ALL_FEATURES.md) | [🏠 Главная](../../../README.md)

---

© 2024 CloudCastle HTTP Router. Все права защищены.
