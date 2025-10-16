# API Reference

**CloudCastle HTTP Router v1.1.0**  
**Language**: English

**Translations**: [Русский](../../ru/documentation/api-reference.md) | [Deutsch](../../de/documentation/api-reference.md) | [Français](../../fr/documentation/api-reference.md)

---

## Route Class

### HTTP Methods
- `Route::get($uri, $action)`
- `Route::post($uri, $action)`
- `Route::put($uri, $action)`
- `Route::delete($uri, $action)`

### Rate Limiting 🆕
- `->perSecond($max, $seconds = 1)`
- `->perMinute($max, $minutes = 1)`
- `->perHour($max, $hours = 1)`
- `->perDay($max, $days = 1)`
- `->perWeek($max, $weeks = 1)`
- `->perMonth($max, $months = 1)`

### Auto-Ban 🆕
- `->throttleWithBan($max, $decay, $violations, $banDuration)`

## BanManager Class 🆕

### Methods
- `isBanned($ip): bool`
- `ban($ip, $duration): void`
- `unban($ip): void`
- `recordViolation($ip): bool`
- `getStatistics(): array`

## TimeUnit Enum 🆕

- `TimeUnit::SECOND` - 1 second
- `TimeUnit::MINUTE` - 60 seconds
- `TimeUnit::HOUR` - 3600 seconds
- `TimeUnit::DAY` - 86400 seconds
- `TimeUnit::WEEK` - 604800 seconds
- `TimeUnit::MONTH` - 2592000 seconds

---

**Translations**: [Русский](../../ru/documentation/api-reference.md) | [Deutsch](../../de/documentation/api-reference.md) | [Français](../../fr/documentation/api-reference.md)
