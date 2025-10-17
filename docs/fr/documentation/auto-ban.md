# Système Auto-Ban

**CloudCastle HTTP Router v1.1.0**  
**Langue**: Français

**Traductions
**: [Русский](../../ru/documentation/auto-ban.md) | [English](../../en/documentation/auto-ban.md) | [Deutsch](../../de/documentation/auto-ban.md)

---

## 🚫 Qu'est-ce que l'Auto-Ban?

Auto-Ban est un système de blocage automatique d'IP en cas de violations répétées des limites de taux.

## 🚀 Démarrage rapide

```php
Route::post('/login', 'AuthController@login')
    ->throttleWithBan(
        maxAttempts: 5,
        decaySeconds: 60,
        maxViolations: 3,
        banDurationSeconds: 7200
    );
```

Protection contre:

- Attaques par force brute
- Attaques DDoS
- Abus d'API

---

**Traductions
**: [Русский](../../ru/documentation/auto-ban.md) | [English](../../en/documentation/auto-ban.md) | [Deutsch](../../de/documentation/auto-ban.md)
