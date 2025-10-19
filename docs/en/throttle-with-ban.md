[🇷🇺 Русский](ru/throttle-with-ban.md) | [🇺🇸 English](en/throttle-with-ban.md) | [🇩🇪 Deutsch](de/throttle-with-ban.md) | [🇫🇷 Français](fr/throttle-with-ban.md) | [🇨🇳 中文](zh/throttle-with-ban.md)

[📚 Table of Contents](en/_table-of-contents.md) | [🏠 Home](en/README.md)

---

# ThrottleWithBan - Rate Limiting with automatic ban

**Languages:** 🇷🇺 Russian | [🇬🇧 English](../en/throttle-with-ban.md) | [🇩🇪 Deutsch](../de/throttle-with-ban.md) | [🇫🇷 Français](../fr/throttle-with-ban.md) | [🇨🇳中文](../zh/throttle-with-ban.md)

[📚 Table of Contents](_table-of-contents.md) | [🏠 Home](README.md)

---

## 📚 Review

**ThrottleWithBan** is a unique CloudCastle feature that combines rate limiting and an auto-ban system for maximum protection against abuse.

## 🎯 Concept

### Regular Rate Limiting

```php
$router->get('/api/data', 'ApiController@data')
    ->perMinute(60);

// 61-й запрос → TooManyRequestsException
// Но злоумышленник может продолжать атаковать каждую минуту
```

### ThrottleWithBan - smart protection

```php
$router->get('/api/data', 'ApiController@data')
    ->throttleWithBan(
        maxAttempts: 60,         // 60 запросов в минуту
        decayMinutes: 1,         // окно 1 минута
        maxViolations: 3,        // после 3 нарушений
        banDurationMinutes: 60   // БАН на 1 час!
    );

// 61-й запрос → TooManyRequestsException (violation 1)
// После минуты опять 61-й запрос → TooManyRequestsException (violation 2)
// После минуты опять 61-й запрос → TooManyRequestsException (violation 3)
// Следующий запрос → BannedException на 1 час!
```

## 🔧 Use

### Basic example

```php
use CloudCastle\Http\Router\Facade\Route;

Route::get('/api/data', 'ApiController@data')
    ->throttleWithBan(
        maxAttempts: 100,        // лимит запросов
        decayMinutes: 1,         // период (минуты)
        maxViolations: 5,        // кол-во нарушений до бана
        banDurationMinutes: 60   // длительность бана (минуты)
    );
```

### Login endpoint protection

```php
Route::post('/login', 'AuthController@login')
    ->throttleWithBan(
        maxAttempts: 5,          // 5 попыток
        decayMinutes: 1,         // в минуту
        maxViolations: 3,        // 3 нарушения
        banDurationMinutes: 120  // бан на 2 часа
    );
```

**Attack scenario:**
1. The attacker makes 6 attempts → 1 violation
2. After a minute, 6 more attempts → 2 violations
3. After a minute, 6 more attempts → 3 violations
4. **Automatic BAN for 2 hours!** 🔒

### Instant ban for critical operations

```php
Route::delete('/admin/critical', 'AdminController@critical')
    ->middleware(['auth', 'admin'])
    ->throttleWithBan(
        maxAttempts: 1,          // 1 запрос в минуту
        decayMinutes: 1,
        maxViolations: 1,        // бан сразу при нарушении!
        banDurationMinutes: 1440 // бан на 24 часа!
    );
```

**Effect:** Any exceeding the limit = immediate ban for a day.

## 📊 Levels of protection

### Public endpoints

```php
// Мягкая защита
Route::get('/api/public/data', 'PublicController@data')
    ->throttleWithBan(
        maxAttempts: 100,        // много запросов разрешено
        decayMinutes: 1,
        maxViolations: 5,        // много попыток до бана
        banDurationMinutes: 30   // короткий бан
    );
```

**When:** Public API, documentation, statics

### Protected endpoints

```php
// Средняя защита
Route::get('/api/protected/data', 'ProtectedController@data')
    ->auth()
    ->throttleWithBan(
        maxAttempts: 50,         // средний лимит
        decayMinutes: 1,
        maxViolations: 3,        // стандартно
        banDurationMinutes: 60   // бан на час
    );
```

**When:** Authenticated API, user data, profiles

### Admin endpoints

```php
// Строгая защита
Route::post('/api/admin/action', 'AdminController@action')
    ->admin()
    ->throttleWithBan(
        maxAttempts: 10,         // малый лимит
        decayMinutes: 1,
        maxViolations: 2,        // быстрый бан
        banDurationMinutes: 240  // бан на 4 часа
    );
```

**When:** Admin panel, critical operations, destructive actions

### Critical Operations

```php
// Максимальная защита
Route::delete('/database/drop', 'DangerousController@drop')
    ->admin()
    ->localhost()
    ->throttleWithBan(
        maxAttempts: 1,          // 1 запрос
        decayMinutes: 60,        // в час!
        maxViolations: 1,        // мгновенный бан
        banDurationMinutes: 10080 // бан на неделю!
    );
```

**When:** Database operations, system commands, destructive actions

## 🔄 Life cycle of a ban

### 1. Normal operation

```
User → Request → Rate Limit OK → Response
```

### 2. First violation

```
User → 61st request → TooManyRequestsException
                    → Violation counter++
                    → Response 429
```

### 3. Repeated violations

```
User → Violation 2 → TooManyRequestsException
User → Violation 3 → TooManyRequestsException
User → Violation 4 (maxViolations reached) → BAN!
```

### 4. After the ban

```
Banned User → Any request → BannedException
                          → Response 403
                          → Retry-After header
```

### 5. Unban

```
Time passes (banDuration) → Auto unban
                          → Violation counter reset
                          → Normal operation
```

## 🛡️ Protection from attacks

### Brute-force on login

```php
Route::post('/login', 'AuthController@login')
    ->throttleWithBan(3, 1, 2, 120);

// Атака:
// - Попытка 1-3: OK
// - Попытка 4+: TooManyRequests (violation 1)
// - Через минуту попытка 4+: TooManyRequests (violation 2)
// - Через минуту попытка 4+: BANNED на 2 часа!

// Результат: атакующий заблокирован после 2 минут
```

### DDoS on API

```php
Route::get('/api/heavy', 'ApiController@heavy')
    ->throttleWithBan(50, 1, 3, 30);

// DDoS атака:
// - 51-й запрос/мин: violation 1
// - 51-й запрос/мин (2nd minute): violation 2
// - 51-й запрос/мин (3rd minute): violation 3
// - 4th minute: BANNED на 30 минут!

// Результат: DDoS остановлен через 3 минуты
```

### Scanning/Probing

```php
Route::get('/admin/{path}', 'AdminController@handle')
    ->admin()
    ->throttleWithBan(10, 1, 1, 480);

// Сканирование:
// - 11-й запрос: violation 1
// - Ещё 1 запрос: BANNED на 8 часов!

// Результат: сканер заблокирован мгновенно
```

## 📈 Statistics and monitoring

### Getting ban statistics

```php
$route = router()->getRoute('api.data');
$rateLimiter = $route->getRateLimiter();
$banManager = $rateLimiter->getBanManager();

$stats = $banManager->getStatistics();

echo "Total banned: " . $stats['total_banned'] . "\n";
echo "Total violations: " . $stats['total_violations'] . "\n";
echo "Unique IPs: " . $stats['unique_ips_with_violations'] . "\n";
```

### List of banned IPs

```php
$bannedIps = $banManager->getBannedIps();

foreach ($bannedIps as $ip => $expiration) {
    $remaining = $expiration - time();
    echo "IP: {$ip}, Time remaining: " . gmdate('H:i:s', $remaining) . "\n";
}
```

### Manual unban

```php
// Разбанить конкретный IP
$banManager->unban('1.2.3.4');

// Разбанить все IP
$banManager->clearAllBans();
```

## 🎨 Recommended configurations

### Configuration table

| Endpoint Type | maxAttempts | decayMin | maxViolations | banDuration | Usage |
|:---|:---:|:---:|:---:|:---:|:---:|
| Public API | 100 | 1 | 5 | 30 | Public data |
| Public Forms | 20 | 1 | 3 | 60 | Contact forms, feedback |
| Login/Auth | 5 | 1 | 2 | 120 | Brute-force protection |
| Registration | 3 | 5 | 2 | 180 | Spam protection |
| API (auth) | 1000 | 1 | 3 | 60 | Authenticated API |
| API (premium) | 10000 | 1 | 3 | 30 | Premium users |
| Admin Panel | 50 | 1 | 2 | 240 | Admin operations |
| Critical Ops | 1 | 60 | 1 | 10080 | Database, system |

### Example settings

**E-commerce:**
```php
// Поиск - мягко
Route::get('/search', 'SearchController@index')
    ->throttleWithBan(100, 1, 5, 30);

// Checkout - средне
Route::post('/checkout', 'CheckoutController@process')
    ->auth()
    ->throttleWithBan(10, 1, 2, 60);

// Payment - строго
Route::post('/payment', 'PaymentController@process')
    ->auth()
    ->secure()
    ->throttleWithBan(3, 5, 1, 1440);
```

**SaaS Platform:**
```php
// Free tier
Route::get('/api/data', 'ApiController@data')
    ->throttleWithBan(100, 1, 3, 60);

// Pro tier
Route::get('/api/pro/data', 'ApiController@proData')
    ->auth()
    ->throttleWithBan(1000, 1, 3, 30);

// Enterprise tier
Route::get('/api/enterprise/data', 'ApiController@enterpriseData')
    ->auth()
    ->throttleWithBan(10000, 1, 5, 15);
```

## 🆚 Comparison with competitors

| Feature | CloudCastle | FastRoute | Symfony | Laravel | Slim | Alto |
|:---|:---:|:---:|:---:|:---:|:---:|:---:|
| Rate Limiting | ✅ | ❌ | ❌ | ✅ | ❌ | ❌ |
| Auto-ban | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ |
| **ThrottleWithBan** | **✅** | **❌** | **❌** | **❌** | **❌** | **❌** |
| Violation tracking | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ |
| Ban statistics | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ |
| Manual unban | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ |

**ThrottleWithBan is a CloudCastle exclusive feature!**

No other router provides such advanced security out of the box.

## 💡 Best Practices

### 1. Different levels for different endpoints

```php
// Публичные - мягко
->throttleWithBan(100, 1, 5, 30)

// Authenticated - средне
->throttleWithBan(50, 1, 3, 60)

// Admin - строго
->throttleWithBan(10, 1, 2, 240)

// Critical - очень строго
->throttleWithBan(1, 60, 1, 10080)
```

### 2. Log bans

```php
use CloudCastle\Http\Router\Middleware\SecurityLogger;

$router->middleware(new SecurityLogger(__DIR__ . '/logs/bans.log'));

// Автоматически логирует:
// [2025-10-18 23:00:00] BANNED: IP 1.2.3.4 - Max violations reached (3/3)
// [2025-10-18 23:00:01] BLOCKED: IP 1.2.3.4 - Banned until 2025-10-19 00:00:00
```

### 3. Monitor your statistics

```php
// Каждый день
$stats = $banManager->getStatistics();

if ($stats['total_banned'] > 100) {
    // Alert: возможная атака
    notify_admin("High ban activity: {$stats['total_banned']} IPs banned");
}
```

### 4. Configure different settings for different roles

```php
// Для пользователей
if ($user->role === 'free') {
    $route->throttleWithBan(100, 1, 3, 60);
} elseif ($user->role === 'pro') {
    $route->throttleWithBan(1000, 1, 5, 30);
} elseif ($user->role === 'enterprise') {
    $route->throttleWithBan(10000, 1, 10, 15);
}
```

## ✅ Benefits

1. **Automatic protection**
   - No need to manually ban
   - The system itself monitors violations
   - Automatic unban

2. **Flexible settings**
   - Customization for any scenario
   - Different levels for different endpoints
   - Customization of all parameters

3. **Detailed statistics**
   - How many IPs are banned
   - How many violations
   - When the ban expires

4. **Protection against replay attacks**
   - Regular rate limiting only protects the current minute
   - ThrottleWithBan bans forever (or for a long time)
   - The attacker cannot repeat the attack

## ✅ Conclusion

ThrottleWithBan is a **revolutionary feature** for protecting applications:

- 🏆 **Unique** - only in CloudCastle
- 🔒 **Automatic** - without manual control
- 📊 **With statistics** - full control
- ⚡ **Effective** - stops attacks in minutes

**Required for use** for:
- Login/Registration endpoints
- Public APIs
- Payment processing
- Admin panels
- Any abuse-prone endpoints

---

*Last update: October 18, 2025*

---

[📚 Table of Contents](_table-of-contents.md) | [🏠 Home](README.md)

---

[📚 Table of Contents](en/_table-of-contents.md) | [🏠 Home](en/README.md)
