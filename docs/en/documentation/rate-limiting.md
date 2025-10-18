# Rate Limiting

**CloudCastle HTTP Router v1.1.1**  
**Language**: English

---

## 🌍 Translations

- [Русский](../../ru/documentation/rate-limiting.md)
- **[English](rate-limiting.md)** (current)
- [Deutsch](../../de/documentation/rate-limiting.md)
- [Français](../../fr/documentation/rate-limiting.md)

---

## 📋 Introduction

Rate Limiting is a built-in CloudCastle Router feature for protecting against abuse and overload.

**Features**:
- ✅ Flexible time windows (seconds to months)
- ✅ Custom keys
- ✅ Auto-ban integration
- ✅ Simple API

---

## ⏱️ Time Windows

```php
// Per second
Route::get('/api/fast', 'ApiController@fast')->perSecond(10);

// Per minute (default)
Route::post('/api/data', 'ApiController@store')->perMinute(60);

// Per hour
Route::post('/api/heavy', 'ApiController@heavy')->perHour(100);

// Per day
Route::post('/api/email', 'EmailController@send')->perDay(100);

// Per week
Route::post('/newsletter', 'NewsletterController@subscribe')->perWeek(1);

// Per month
Route::post('/billing', 'BillingController@generate')->perMonth(10);
```

---

## 🔧 Basic Usage

### Simple Limiting

```php
Route::post('/api/data', 'ApiController@store')
    ->throttle(60, 1);  // 60 requests per 1 minute
```

### With Custom Key

```php
Route::post('/api/data', 'ApiController@store')
    ->throttle(60, 1, 'api_' . $_SESSION['user_id']);
```

---

## ⚠️ Error Handling

### TooManyRequestsException

```php
use CloudCastle\Http\Router\Exceptions\TooManyRequestsException;

try {
    $result = Route::dispatch($uri, $method);
} catch (TooManyRequestsException $e) {
    http_response_code(429);
    echo json_encode([
        'error' => 'Too Many Requests',
        'retry_after' => $e->getRetryAfter()
    ]);
}
```

---

## 🔗 See Also

- [Auto-ban](auto-ban.md)
- [Security](security.md)
- [Examples](../../../examples/throttle-example.php)

---

**[← Back to contents](README.md)**

