# API Reference

**CloudCastle HTTP Router v1.1.1**  
**Язык**: Русский

---

## 🌍 Переводы

- **[Русский](api-reference.md)** (текущий)
- [English](../../en/documentation/api-reference.md)
- [Deutsch](../../de/documentation/api-reference.md)
- [Français](../../fr/documentation/api-reference.md)

---

## 📚 Основные классы

### Router

Главный класс роутера

**Методы создания маршрутов**:
- `get(string $uri, mixed $action): Route`
- `post(string $uri, mixed $action): Route`
- `put(string $uri, mixed $action): Route`
- `patch(string $uri, mixed $action): Route`
- `delete(string $uri, mixed $action): Route`
- `options(string $uri, mixed $action): Route`
- `head(string $uri, mixed $action): Route`
- `match(array $methods, string $uri, mixed $action): Route`
- `any(string $uri, mixed $action): Route`

**Методы управления**:
- `group(array $attributes, Closure $callback): void`
- `middleware(array|string|callable $middleware): self`
- `dispatch(string $uri, string $method, array $parameters = []): mixed`

**Автонейминг** 🆕:
- `enableAutoNaming(): self`
- `disableAutoNaming(): self`
- `isAutoNamingEnabled(): bool`

**Кеширование**:
- `enableCache(?string $cacheDir = null): self`
- `disableCache(): self`
- `compile(bool $force = false): bool`

**Поиск маршрутов**:
- `getRouteByName(string $name): ?Route`
- `getRoutesByTag(string $tag): array`
- `getRoutes(): array`

---

### Route

Класс маршрута

**Именование**:
- `name(string $name): self`
- `tag(string|array $tags): self`
- `getName(): ?string`
- `getTags(): array`

**Параметры**:
- `where(string|array $name, ?string $pattern = null): self`
- `getParameters(): array`

**Безопасность**:
- `https(): self`
- `protocol(string|array $protocols): self`
- `domain(string $domain): self`
- `port(int $port): self`
- `whitelistIp(string|array $ips): self`
- `blacklistIp(string|array $ips): self`

**Rate Limiting**:
- `throttle(int $maxAttempts, float $decayMinutes = 1.0, ?string $key = null): self`
- `perSecond(int $maxAttempts, ?string $key = null): self`
- `perMinute(int $maxAttempts, ?string $key = null): self`
- `perHour(int $maxAttempts, ?string $key = null): self`
- `perDay(int $maxAttempts, ?string $key = null): self`
- `perWeek(int $maxAttempts, ?string $key = null): self`
- `perMonth(int $maxAttempts, ?string $key = null): self`

**Автобан**:
- `throttleWithBan(int $maxAttempts, int $decaySeconds, int $maxViolations, int $banDurationSeconds): self`

**Middleware**:
- `middleware(array|string|callable $middleware): self`

---

### BanManager

Управление банами

**Методы**:
- `isBanned(string $ip): bool`
- `ban(string $ip, int $duration): void`
- `unban(string $ip): void`
- `recordViolation(string $ip, int $maxViolations, int $banDuration): bool`
- `getBannedIps(): array`
- `getStatistics(): array`
- `getBanTimeRemaining(string $ip): int`
- `clearViolations(string $ip): void`
- `clearAllBans(): void`

---

### RateLimiter

Rate limiting

**Методы**:
- `hit(string $key, int $decaySeconds = 60): int`
- `tooManyAttempts(string $key, int $maxAttempts): bool`
- `remaining(string $key, int $maxAttempts): int`
- `availableIn(string $key): int`
- `clear(string $key): void`
- `resetAll(): void`

---

## 🔧 Helper функции

### route()

Получить URL именованного маршрута

```php
$url = route('user.profile'); // /profile
```

### route_url()

Получить URL с параметрами

```php
$url = route_url('user.show', ['id' => 123]); // /user/123
```

### current_route()

Получить текущий маршрут

```php
$route = current_route();
```

### route_is()

Проверить текущий маршрут

```php
if (route_is('user.*')) {
    // Текущий маршрут начинается с 'user.'
}
```

### router()

Получить экземпляр роутера

```php
$router = router();
```

---

## 🔗 См. также

- [Маршруты](routes.md)
- [Middleware](middleware.md)
- [Rate Limiting](rate-limiting.md)

---

**[← Назад к оглавлению](README.md)**

