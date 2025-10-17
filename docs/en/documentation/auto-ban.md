# Auto-Ban System

**CloudCastle HTTP Router v1.1.0**  
**Language**: English

**Translations
**: [Русский](../../ru/documentation/auto-ban.md) | [Deutsch](../../de/documentation/auto-ban.md) | [Français](../../fr/documentation/auto-ban.md)

---

## 🚫 What is Auto-Ban?

Auto-ban is an automatic IP blocking system on repeated rate limit violations. Protects from:

- 🛡️ **Brute-force attacks**
- 🛡️ **DDoS attacks**
- 🛡️ **API Abuse**
- 🛡️ **Vulnerability scanning**
- 🛡️ **Repeat offenders**

## 🚀 Quick Start

```php
Route::post('/login', 'AuthController@login')
    ->throttleWithBan(
        maxAttempts: 5,
        decaySeconds: 60,
        maxViolations: 3,
        banDurationSeconds: 7200
    );
```

## 📋 Parameters

| Parameter          | Type | Default | Description             |
|--------------------|------|---------|-------------------------|
| maxAttempts        | int  | 60      | Max requests per window |
| decaySeconds       | int  | 60      | Time window (seconds)   |
| maxViolations      | int  | 3       | Violations before ban   |
| banDurationSeconds | int  | 3600    | Ban duration (seconds)  |

---

**Translations
**: [Русский](../../ru/documentation/auto-ban.md) | [Deutsch](../../de/documentation/auto-ban.md) | [Français](../../fr/documentation/auto-ban.md)
