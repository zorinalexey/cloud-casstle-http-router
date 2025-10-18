# Rate Limiting

**CloudCastle HTTP Router v1.1.1**  
**Sprache**: Deutsch

---

## 🌍 Übersetzungen

- [Русский](../../ru/documentation/rate-limiting.md)
- [English](../../en/documentation/rate-limiting.md)
- **[Deutsch](rate-limiting.md)** (aktuell)
- [Français](../../fr/documentation/rate-limiting.md)

---

## 📋 Einführung

Rate Limiting ist eine eingebaute CloudCastle Router-Funktion zum Schutz vor Missbrauch und Überlastung.

---

## ⏱️ Zeitfenster

```php
// Pro Sekunde
Route::get('/api/fast', 'ApiController@fast')->perSecond(10);

// Pro Minute
Route::post('/api/data', 'ApiController@store')->perMinute(60);

// Pro Stunde
Route::post('/api/heavy', 'ApiController@heavy')->perHour(100);

// Pro Tag
Route::post('/api/email', 'EmailController@send')->perDay(100);

// Pro Woche
Route::post('/newsletter', 'NewsletterController@subscribe')->perWeek(1);

// Pro Monat
Route::post('/billing', 'BillingController@generate')->perMonth(10);
```

---

## 🔧 Grundlegende Verwendung

### Einfache Begrenzung

```php
Route::post('/api/data', 'ApiController@store')
    ->throttle(60, 1);  // 60 Anfragen pro Minute
```

### Mit benutzerdefiniertem Schlüssel

```php
Route::post('/api/data', 'ApiController@store')
    ->throttle(60, 1, 'api_' . $_SESSION['user_id']);
```

---

## ⚠️ Fehlerbehandlung

### TooManyRequestsException

```php
use CloudCastle\Http\Router\Exceptions\TooManyRequestsException;

try {
    $result = Route::dispatch($uri, $method);
} catch (TooManyRequestsException $e) {
    http_response_code(429);
    echo json_encode([
        'error' => 'Zu viele Anfragen',
        'retry_after' => $e->getRetryAfter()
    ]);
}
```

---

## 🔗 Siehe auch

- [Auto-ban](auto-ban.md)
- [Sicherheit](security.md)
- [Beispiele](../../../examples/throttle-example.php)

---

**[← Zurück zum Inhaltsverzeichnis](README.md)**

