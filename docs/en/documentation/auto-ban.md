# Auto-ban

**CloudCastle HTTP Router v1.1.1**  
**Language**: English

---

## 🌍 Translations

- [Русский](../../ru/documentation/auto-ban.md)
- **[English](auto-ban.md)** (current)
- [Deutsch](../../de/documentation/auto-ban.md)
- [Français](../../fr/documentation/auto-ban.md)

---

## 🚫 Introduction

Automatic ban system - unique CloudCastle Router feature for protection against brute-force attacks, DDoS and other abuse.

**How it works**: When rate limits are exceeded a certain number of times, the IP address is automatically blocked for a specified time.

---

## 🎯 Basic Usage

### Simple Auto-ban

```php
Route::post('/login', 'AuthController@login')
    ->throttleWithBan(
        maxAttempts: 5,           // 5 attempts
        decaySeconds: 60,          // in 60 seconds
        maxViolations: 3,          // 3 violations until ban
        banDurationSeconds: 7200   // ban for 2 hours
    );
```

### How It Works

1. User makes **5 failed attempts** in **60 seconds**
2. This counts as **1 violation**
3. After **3 violations** - IP is **blocked for 2 hours**

---

## ⚙️ Parameters

### maxAttempts
**Type**: `int`  
**Description**: Maximum requests in time window

### decaySeconds
**Type**: `int`  
**Description**: Time window in seconds

### maxViolations
**Type**: `int`  
**Description**: Violations count until ban

### banDurationSeconds
**Type**: `int`  
**Description**: Ban duration in seconds

---

## 💡 Usage Examples

### Login Protection

```php
Route::post('/login', 'AuthController@login')
    ->throttleWithBan(
        maxAttempts: 5,
        decaySeconds: 60,
        maxViolations: 3,
        banDurationSeconds: 7200
    );
```

### API Protection

```php
Route::post('/api/data', 'ApiController@store')
    ->throttleWithBan(
        maxAttempts: 100,
        decaySeconds: 60,
        maxViolations: 5,
        banDurationSeconds: 3600
    );
```

---

## 🔧 Ban Management

### BanManager

```php
use CloudCastle\Http\Router\BanManager;

$banManager = new BanManager();

// Check ban
if ($banManager->isBanned('192.168.1.100')) {
    echo 'IP banned';
}

// Manual ban
$banManager->ban('192.168.1.100', 3600); // for 1 hour

// Unban
$banManager->unban('192.168.1.100');

// Get banned IPs list
$bannedIps = $banManager->getBannedIps();

// Statistics
$stats = $banManager->getStatistics();
```

---

## ⚠️ Exception Handling

### BannedException

```php
use CloudCastle\Http\Router\Exceptions\BannedException;

try {
    Route::dispatch($uri, $method);
} catch (BannedException $e) {
    http_response_code(403);
    echo json_encode([
        'error' => 'IP banned',
        'ip' => $e->getIp(),
        'retry_after' => $e->getRetryAfter()
    ]);
}
```

---

## 🔗 See Also

- [Rate Limiting](rate-limiting.md)
- [Security](security.md)
- [Examples](../../../examples/autoban-example.php)

---

**[← Back to contents](README.md)**

