# Rate Limiting

**CloudCastle HTTP Router v1.1.1**  
**Langue**: Français

---

## 🌍 Traductions

- [Русский](../../ru/documentation/rate-limiting.md)
- [English](../../en/documentation/rate-limiting.md)
- [Deutsch](../../de/documentation/rate-limiting.md)
- **[Français](rate-limiting.md)** (actuel)

---

## 📋 Introduction

Le Rate Limiting est une fonctionnalité intégrée de CloudCastle Router pour protéger contre les abus et la surcharge.

---

## ⏱️ Fenêtres temporelles

```php
// Par seconde
Route::get('/api/fast', 'ApiController@fast')->perSecond(10);

// Par minute
Route::post('/api/data', 'ApiController@store')->perMinute(60);

// Par heure
Route::post('/api/heavy', 'ApiController@heavy')->perHour(100);

// Par jour
Route::post('/api/email', 'EmailController@send')->perDay(100);

// Par semaine
Route::post('/newsletter', 'NewsletterController@subscribe')->perWeek(1);

// Par mois
Route::post('/billing', 'BillingController@generate')->perMonth(10);
```

---

## 🔧 Utilisation de base

### Limitation simple

```php
Route::post('/api/data', 'ApiController@store')
    ->throttle(60, 1);  // 60 requêtes par minute
```

---

## ⚠️ Gestion des erreurs

```php
use CloudCastle\Http\Router\Exceptions\TooManyRequestsException;

try {
    $result = Route::dispatch($uri, $method);
} catch (TooManyRequestsException $e) {
    http_response_code(429);
    echo json_encode([
        'error' => 'Trop de requêtes',
        'retry_after' => $e->getRetryAfter()
    ]);
}
```

---

**[← Retour au sommaire](README.md)**

