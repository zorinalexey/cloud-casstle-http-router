# API Reference

**CloudCastle HTTP Router v1.1.1**  
**Language**: English

---

## 🌍 Translations

- [Русский](../../ru/documentation/api-reference.md)
- **[English](api-reference.md)** (current)
- [Deutsch](../../de/documentation/api-reference.md)
- [Français](../../fr/documentation/api-reference.md)

---

## 📚 Main Classes

### Router

Main router class

**Route creation methods**:
- `get(string $uri, mixed $action): Route`
- `post(string $uri, mixed $action): Route`
- `put(string $uri, mixed $action): Route`
- `patch(string $uri, mixed $action): Route`
- `delete(string $uri, mixed $action): Route`
- `match(array $methods, string $uri, mixed $action): Route`
- `any(string $uri, mixed $action): Route`

**Management methods**:
- `group(array $attributes, Closure $callback): void`
- `middleware(array|string|callable $middleware): self`
- `dispatch(string $uri, string $method): mixed`

**Auto-naming** 🆕:
- `enableAutoNaming(): self`
- `disableAutoNaming(): self`
- `isAutoNamingEnabled(): bool`

**Caching**:
- `enableCache(?string $cacheDir = null): self`
- `disableCache(): self`
- `compile(bool $force = false): bool`

---

### Route

Route class

**Naming**:
- `name(string $name): self`
- `tag(string|array $tags): self`

**Security**:
- `https(): self`
- `whitelistIp(string|array $ips): self`
- `blacklistIp(string|array $ips): self`

**Rate Limiting**:
- `perSecond(int $maxAttempts): self`
- `perMinute(int $maxAttempts): self`
- `perHour(int $maxAttempts): self`
- `perDay(int $maxAttempts): self`
- `perWeek(int $maxAttempts): self`
- `perMonth(int $maxAttempts): self`

**Auto-ban**:
- `throttleWithBan(int $maxAttempts, int $decaySeconds, int $maxViolations, int $banDurationSeconds): self`

---

### BanManager

Ban management

**Methods**:
- `isBanned(string $ip): bool`
- `ban(string $ip, int $duration): void`
- `unban(string $ip): void`
- `getBannedIps(): array`
- `getStatistics(): array`

---

## 🔧 Helper Functions

### route()

Get URL of named route

```php
$url = route('user.profile');
```

### route_url()

Get URL with parameters

```php
$url = route_url('user.show', ['id' => 123]);
```

### current_route()

Get current route

```php
$route = current_route();
```

### router()

Get router instance

```php
$router = router();
```

---

**[← Back to contents](README.md)**

