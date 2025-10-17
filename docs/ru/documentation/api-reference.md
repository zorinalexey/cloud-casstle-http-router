# API Reference

**CloudCastle HTTP Router v1.1.0**  
**Язык**: Русский

**Переводы
**: [English](../../en/documentation/api-reference.md) | [Deutsch](../../de/documentation/api-reference.md) | [Français](../../fr/documentation/api-reference.md)

---

## Route Class

### HTTP Methods

- `Route::get($uri, $action)` - GET запрос
- `Route::post($uri, $action)` - POST запрос
- `Route::put($uri, $action)` - PUT запрос
- `Route::patch($uri, $action)` - PATCH запрос
- `Route::delete($uri, $action)` - DELETE запрос
- `Route::options($uri, $action)` - OPTIONS запрос
- `Route::head($uri, $action)` - HEAD запрос
- `Route::match($methods, $uri, $action)` - несколько методов
- `Route::any($uri, $action)` - любой метод

### Конфигурация маршрута

- `->name($name)` - именование
- `->tag($tags)` - тегирование
- `->where($param, $pattern)` - ограничение параметра
- `->middleware($middleware)` - middleware
- `->domain($domain)` - домен
- `->port($port)` - порт
- `->protocol($protocols)` - протоколы

### Rate Limiting 🆕

- `->throttle($max, $seconds)` - базовое ограничение
- `->perSecond($max, $seconds = 1)` - по секундам
- `->perMinute($max, $minutes = 1)` - по минутам
- `->perHour($max, $hours = 1)` - по часам
- `->perDay($max, $days = 1)` - по дням
- `->perWeek($max, $weeks = 1)` - по неделям
- `->perMonth($max, $months = 1)` - по месяцам

### Auto-Ban 🆕

- `->throttleWithBan($max, $decay, $violations, $banDuration)` - с автобаном

### Безопасность

- `->https()` - только HTTPS
- `->whitelistIp($ips)` - белый список
- `->blacklistIp($ips)` - черный список

### Shortcuts

- `->auth()` - требует авторизацию
- `->guest()` - только для гостей
- `->admin()` - только для админов
- `->throttleStandard()` - стандартный лимит
- `->throttleStrict()` - строгий лимит

## BanManager Class 🆕

### Constructor

```php
new BanManager($maxViolations = 3, $banDuration = 3600)
```

### Methods

- `isBanned($ip): bool`
- `ban($ip, $duration = null): void`
- `unban($ip): void`
- `recordViolation($ip): bool`
- `getBanTimeRemaining($ip): int`
- `getStatistics(): array`
- `clearAllBans(): void`

## RateLimiter Class

### Constructor

```php
new RateLimiter($maxAttempts = 60, $decaySeconds = 60, $key = null)
```

### Static Methods 🆕

- `RateLimiter::perSecond($max, $seconds, $key)`
- `RateLimiter::perMinute($max, $minutes, $key)`
- `RateLimiter::perHour($max, $hours, $key)`
- `RateLimiter::perDay($max, $days, $key)`
- `RateLimiter::perWeek($max, $weeks, $key)`
- `RateLimiter::perMonth($max, $months, $key)`
- `RateLimiter::make($max, $decay, TimeUnit $unit, $key)`

### Methods

- `tooManyAttempts($identifier): bool`
- `hit($identifier): void`
- `remaining($identifier): int`
- `availableIn($identifier): int`
- `attempts($identifier): int`
- `enableAutoBan($violations, $duration): self` 🆕
- `getBanManager(): ?BanManager` 🆕

## TimeUnit Enum 🆕

### Values

- `TimeUnit::SECOND` - 1 секунда
- `TimeUnit::MINUTE` - 60 секунд
- `TimeUnit::HOUR` - 3600 секунд
- `TimeUnit::DAY` - 86400 секунд
- `TimeUnit::WEEK` - 604800 секунд
- `TimeUnit::MONTH` - 2592000 секунд

### Methods

- `toSeconds($value): int` - конвертация в секунды
- `getName(): string` - имя единицы
- `getPlural(): string` - множественное число

## Helper Functions

- `route($name, $params = [])` - URL по имени
- `current_route()` - текущий маршрут
- `route_has($name)` - проверка существования
- `route_back()` - предыдущий URL
- `route_url($name, $params)` - полный URL
- `route_is($name)` - проверка текущего маршрута
- `route_action($name)` - получение action

---

**CloudCastle HTTP Router** - Complete API Reference! 📚

---

**Переводы
**: [English](../../en/documentation/api-reference.md) | [Deutsch](../../de/documentation/api-reference.md) | [Français](../../fr/documentation/api-reference.md)
