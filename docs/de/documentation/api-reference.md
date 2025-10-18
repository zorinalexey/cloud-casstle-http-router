# API-Referenz

**CloudCastle HTTP Router v1.1.1**  
**Sprache**: Deutsch

---

## 🌍 Übersetzungen

- [Русский](../../ru/documentation/api-reference.md)
- [English](../../en/documentation/api-reference.md)
- **[Deutsch](api-reference.md)** (aktuell)
- [Français](../../fr/documentation/api-reference.md)

---

## 📚 Hauptklassen

### Router

Haupt-Router-Klasse

**Routen-Erstellungsmethoden**:
- `get(string $uri, mixed $action): Route`
- `post(string $uri, mixed $action): Route`
- `put(string $uri, mixed $action): Route`
- `delete(string $uri, mixed $action): Route`

**Auto-Naming** 🆕:
- `enableAutoNaming(): self`
- `disableAutoNaming(): self`
- `isAutoNamingEnabled(): bool`

**Caching**:
- `enableCache(?string $cacheDir = null): self`
- `compile(bool $force = false): bool`

---

### Route

Routen-Klasse

**Benennung**:
- `name(string $name): self`
- `tag(string|array $tags): self`

**Sicherheit**:
- `https(): self`
- `whitelistIp(string|array $ips): self`
- `blacklistIp(string|array $ips): self`

**Rate Limiting**:
- `perSecond(int $maxAttempts): self`
- `perMinute(int $maxAttempts): self`
- `perHour(int $maxAttempts): self`

**Auto-Ban**:
- `throttleWithBan(int $maxAttempts, int $decaySeconds, int $maxViolations, int $banDurationSeconds): self`

---

## 🔧 Hilfsfunktionen

```php
$url = route('user.profile');
$url = route_url('user.show', ['id' => 123]);
$route = current_route();
$router = router();
```

---

**[← Zurück zum Inhaltsverzeichnis](README.md)**

