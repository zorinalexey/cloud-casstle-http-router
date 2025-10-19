[🇷🇺 Русский](ru/throttle-with-ban.md) | [🇺🇸 English](en/throttle-with-ban.md) | [🇩🇪 Deutsch](de/throttle-with-ban.md) | [🇫🇷 Français](fr/throttle-with-ban.md) | [🇨🇳 中文](zh/throttle-with-ban.md)

[📚 Table of Contents](de/_table-of-contents.md) | [🏠 Home](de/README.md)

---

# ThrottleWithBan – Ratenbegrenzung mit automatischer Sperre

**Sprachen:** 🇷🇺 Russisch | [🇬🇧 Englisch](../en/throttle-with-ban.md) | [🇩🇪 Deutsch](../de/throttle-with-ban.md) | [🇫🇷 Français](../fr/throttle-with-ban.md) | [🇨🇳中文](../zh/throttle-with-ban.md)

[📚 Inhaltsverzeichnis](_table-of-contents.md) | [🏠 Startseite](README.md)

---

## 📚 Rezension

**ThrottleWithBan** ist eine einzigartige CloudCastle-Funktion, die Ratenbegrenzung und ein automatisches Sperrsystem für maximalen Schutz vor Missbrauch kombiniert.

## 🎯 Konzept

### Regelmäßige Ratenbegrenzung

```php
$router->get('/api/data', 'ApiController@data')
    ->perMinute(60);

// 61-й запрос → TooManyRequestsException
// Но злоумышленник может продолжать атаковать каждую минуту
```

### ThrottleWithBan – intelligenter Schutz

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

## 🔧 Benutzen

### Einfaches Beispiel

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

### Login-Endpunktschutz

```php
Route::post('/login', 'AuthController@login')
    ->throttleWithBan(
        maxAttempts: 5,          // 5 попыток
        decayMinutes: 1,         // в минуту
        maxViolations: 3,        // 3 нарушения
        banDurationMinutes: 120  // бан на 2 часа
    );
```

**Angriffsszenario:**
1. Der Angreifer unternimmt 6 Versuche → 1 Verstoß
2. Nach einer Minute 6 weitere Versuche → 2 Verstöße
3. Nach einer Minute 6 weitere Versuche → 3 Verstöße
4. **Automatisches BAN für 2 Stunden!** 🔒

### Sofortiges Verbot für kritische Operationen

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

**Auswirkung:** Jede Überschreitung des Limits = sofortige Sperre für einen Tag.

## 📊 Schutzstufen

### Öffentliche Endpunkte

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

**Wann:** Öffentliche API, Dokumentation, Statik

### Geschützte Endpunkte

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

**Wann:** Authentifizierte API, Benutzerdaten, Profile

### Admin-Endpunkte

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

**Wann:** Admin-Panel, kritische Vorgänge, destruktive Aktionen

### Kritische Vorgänge

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

**Wann:** Datenbankoperationen, Systembefehle, destruktive Aktionen

## 🔄 Lebenszyklus eines Verbots

### 1. Normaler Betrieb

```
User → Request → Rate Limit OK → Response
```

### 2. Erster Verstoß

```
User → 61st request → TooManyRequestsException
                    → Violation counter++
                    → Response 429
```

### 3. Wiederholte Verstöße

```
User → Violation 2 → TooManyRequestsException
User → Violation 3 → TooManyRequestsException
User → Violation 4 (maxViolations reached) → BAN!
```

### 4. Nach dem Verbot

```
Banned User → Any request → BannedException
                          → Response 403
                          → Retry-After header
```

### 5. Ban aufheben

```
Time passes (banDuration) → Auto unban
                          → Violation counter reset
                          → Normal operation
```

## 🛡️ Schutz vor Angriffen

### Brute-Force beim Login

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

### DDoS auf der API

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

## 📈 Statistik und Überwachung

### Sperrstatistiken abrufen

```php
$route = router()->getRoute('api.data');
$rateLimiter = $route->getRateLimiter();
$banManager = $rateLimiter->getBanManager();

$stats = $banManager->getStatistics();

echo "Total banned: " . $stats['total_banned'] . "\n";
echo "Total violations: " . $stats['total_violations'] . "\n";
echo "Unique IPs: " . $stats['unique_ips_with_violations'] . "\n";
```

### Liste der gesperrten IPs

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

## 🎨 Empfohlene Konfigurationen

### Konfigurationstabelle

| Endpunkttyp | maxAttempts | VerfallMin | maxViolations | banDuration | Verwendung |
|:---|:---:|:---:|:---:|:---:|:---:|
| Öffentliche API | 100 | 1 | 5 | 30 | Öffentliche Daten |
| Public Forms | 20 | 1 | 3 | 60 | Contact forms, feedback |
| Login/Auth | 5 | 1 | 2 | 120 | Brute-Force-Schutz |
| Registrierung | 3 | 5 | 2 | 180 | Spam-Schutz |
| API (auth) | 1000 | 1 | 3 | 60 | Authenticated API |
| API (premium) | 10000 | 1 | 3 | 30 | Premium users |
| Admin Panel | 50 | 1 | 2 | 240 | Admin operations |
| Critical Ops | 1 | 60 | 1 | 10080 | Database, system |

### Beispieleinstellungen

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

## 🆚 Vergleich mit Mitbewerbern

| Feature | CloudCastle | FastRoute | Symfony | Laravel | Slim | Alto |
|:---|:---:|:---:|:---:|:---:|:---:|:---:|
| Rate Limiting | ✅ | ❌ | ❌ | ✅ | ❌ | ❌ |
| Auto-ban | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ |
| **ThrottleWithBan** | **✅** | **❌** | **❌** | **❌** | **❌** | **❌** |
| Violation tracking | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ |
| Ban statistics | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ |
| Manual unban | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ |

**ThrottleWithBan ist eine exklusive CloudCastle-Funktion!**

Kein anderer Router bietet sofort eine derart fortschrittliche Sicherheit.

## 💡 Best Practices

### 1. Verschiedene Ebenen für verschiedene Endpunkte

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

### 2. Log-Verbote

```php
use CloudCastle\Http\Router\Middleware\SecurityLogger;

$router->middleware(new SecurityLogger(__DIR__ . '/logs/bans.log'));

// Автоматически логирует:
// [2025-10-18 23:00:00] BANNED: IP 1.2.3.4 - Max violations reached (3/3)
// [2025-10-18 23:00:01] BLOCKED: IP 1.2.3.4 - Banned until 2025-10-19 00:00:00
```

### 3. Überwachen Sie Ihre Statistiken

```php
// Каждый день
$stats = $banManager->getStatistics();

if ($stats['total_banned'] > 100) {
    // Alert: возможная атака
    notify_admin("High ban activity: {$stats['total_banned']} IPs banned");
}
```

### 4. Konfigurieren Sie verschiedene Einstellungen für verschiedene Rollen

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

## ✅ Vorteile

1. **Automatischer Schutz**
   - Kein manuelles Sperren erforderlich
   - Das System selbst überwacht Verstöße
   - Automatische Entsperrung

2. **Flexible Einstellungen**
   - Anpassung für jedes Szenario
   - Verschiedene Ebenen für verschiedene Endpunkte
   - Anpassung aller Parameter

3. **Detaillierte Statistiken**
   - Wie viele IPs sind gesperrt
   - Wie viele Verstöße
   - Wenn das Verbot abläuft

4. **Schutz vor Replay-Angriffen**
   - Die reguläre Tarifbegrenzung schützt nur die aktuelle Minute
   - ThrottleWithBan sperrt für immer (oder für eine lange Zeit)
   - Der Angreifer kann den Angriff nicht wiederholen

## ✅ Fazit

ThrottleWithBan ist eine **revolutionäre Funktion** zum Schutz von Anwendungen:

- 🏆 **Einzigartig** – nur in CloudCastle
- 🔒 **Automatisch** – ohne manuelle Steuerung
- 📊 **Mit Statistiken** – volle Kontrolle
- ⚡ **Effektiv** – stoppt Angriffe in wenigen Minuten

**Erforderlich für die Verwendung** für:
- Login/Registration endpoints
- Public APIs
- Payment processing
- Admin panels
- Any abuse-prone endpoints

---

*Letzte Aktualisierung: 18. Oktober 2025*

---

[📚 Inhaltsverzeichnis](_table-of-contents.md) | [🏠 Startseite](README.md)

---

[📚 Table of Contents](de/_table-of-contents.md) | [🏠 Home](de/README.md)
