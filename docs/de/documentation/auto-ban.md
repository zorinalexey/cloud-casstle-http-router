# Auto-Ban System

**CloudCastle HTTP Router v1.1.0**  
**Sprache**: Deutsch

**Übersetzungen**: [Русский](../../ru/documentation/auto-ban.md) | [English](../../en/documentation/auto-ban.md) | [Français](../../fr/documentation/auto-ban.md)

---

## 🚫 Was ist Auto-Ban?

Auto-Ban ist ein automatisches IP-Sperrsystem bei wiederholten Rate-Limit-Verletzungen.

## 🚀 Schnellstart

```php
Route::post('/login', 'AuthController@login')
    ->throttleWithBan(
        maxAttempts: 5,
        decaySeconds: 60,
        maxViolations: 3,
        banDurationSeconds: 7200
    );
```

Schützt vor:
- Brute-Force-Angriffen
- DDoS-Angriffen
- API-Missbrauch

---

**Übersetzungen**: [Русский](../../ru/documentation/auto-ban.md) | [English](../../en/documentation/auto-ban.md) | [Français](../../fr/documentation/auto-ban.md)
