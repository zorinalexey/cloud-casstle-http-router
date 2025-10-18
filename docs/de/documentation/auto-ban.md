# Auto-Ban

**CloudCastle HTTP Router v1.1.1**  
**Sprache**: Deutsch

---

## 🌍 Übersetzungen

- [Русский](../../ru/documentation/auto-ban.md)
- [English](../../en/documentation/auto-ban.md)
- **[Deutsch](auto-ban.md)** (aktuell)
- [Français](../../fr/documentation/auto-ban.md)

---

## 🚫 Einführung

Automatisches Ban-System - einzigartige CloudCastle Router-Funktion zum Schutz vor Brute-Force-Angriffen, DDoS und anderem Missbrauch.

---

## 🎯 Grundlegende Verwendung

### Einfacher Auto-Ban

```php
Route::post('/login', 'AuthController@login')
    ->throttleWithBan(
        maxAttempts: 5,           // 5 Versuche
        decaySeconds: 60,          // in 60 Sekunden
        maxViolations: 3,          // 3 Verstöße bis Ban
        banDurationSeconds: 7200   // Ban für 2 Stunden
    );
```

### Wie es funktioniert

1. Benutzer macht **5 fehlgeschlagene Versuche** in **60 Sekunden**
2. Dies zählt als **1 Verstoß**
3. Nach **3 Verstößen** - IP wird **für 2 Stunden gesperrt**

---

## 💡 Verwendungsbeispiele

### Login-Schutz

```php
Route::post('/login', 'AuthController@login')
    ->throttleWithBan(5, 60, 3, 7200);
```

### API-Schutz

```php
Route::post('/api/data', 'ApiController@store')
    ->throttleWithBan(100, 60, 5, 3600);
```

---

## 🔧 Ban-Verwaltung

### BanManager

```php
use CloudCastle\Http\Router\BanManager;

$banManager = new BanManager();

// Ban prüfen
if ($banManager->isBanned('192.168.1.100')) {
    echo 'IP gesperrt';
}

// Manuelles Sperren
$banManager->ban('192.168.1.100', 3600);

// Entsperren
$banManager->unban('192.168.1.100');
```

---

## ⚠️ Ausnahmebehandlung

```php
use CloudCastle\Http\Router\Exceptions\BannedException;

try {
    Route::dispatch($uri, $method);
} catch (BannedException $e) {
    http_response_code(403);
    echo json_encode([
        'error' => 'IP gesperrt',
        'retry_after' => $e->getRetryAfter()
    ]);
}
```

---

**[← Zurück zum Inhaltsverzeichnis](README.md)**

