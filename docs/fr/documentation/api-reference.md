# Référence API

**CloudCastle HTTP Router v1.1.1**  
**Langue**: Français

---

## 🌍 Traductions

- [Русский](../../ru/documentation/api-reference.md)
- [English](../../en/documentation/api-reference.md)
- [Deutsch](../../de/documentation/api-reference.md)
- **[Français](api-reference.md)** (actuel)

---

## 📚 Classes principales

### Router

Classe principale du routeur

**Méthodes de création de routes**:
- `get(string $uri, mixed $action): Route`
- `post(string $uri, mixed $action): Route`
- `put(string $uri, mixed $action): Route`
- `delete(string $uri, mixed $action): Route`

**Auto-naming** 🆕:
- `enableAutoNaming(): self`
- `disableAutoNaming(): self`
- `isAutoNamingEnabled(): bool`

**Caching**:
- `enableCache(?string $cacheDir = null): self`
- `compile(bool $force = false): bool`

---

### Route

Classe de route

**Nommage**:
- `name(string $name): self`
- `tag(string|array $tags): self`

**Sécurité**:
- `https(): self`
- `whitelistIp(string|array $ips): self`

**Rate Limiting**:
- `perSecond(int $maxAttempts): self`
- `perMinute(int $maxAttempts): self`
- `perHour(int $maxAttempts): self`

**Auto-Ban**:
- `throttleWithBan(int $maxAttempts, int $decaySeconds, int $maxViolations, int $banDurationSeconds): self`

---

## 🔧 Fonctions helpers

```php
$url = route('user.profile');
$url = route_url('user.show', ['id' => 123]);
$route = current_route();
$router = router();
```

---

**[← Retour au sommaire](README.md)**

