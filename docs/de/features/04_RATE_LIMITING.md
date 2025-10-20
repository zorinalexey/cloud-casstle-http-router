# Rate Limiting & Auto-Ban

[English](../../en/features/04_RATE_LIMITING.md) | [Русский](../../ru/features/04_RATE_LIMITING.md) | [**Deutsch**](04_RATE_LIMITING.md) | [Français](../../fr/features/04_RATE_LIMITING.md) | [中文](../../zh/features/04_RATE_LIMITING.md)

---

## 📚 Dokumentationsnavigation

[README](../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [API_REFERENCE](../API_REFERENCE.md) | [ALL_FEATURES](../ALL_FEATURES.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [PERFORMANCE](../PERFORMANCE_ANALYSIS.md) | [SECURITY](../SECURITY_REPORT.md) | [COMPARISON](../COMPARISON.md) | [FAQ](../FAQ.md)

**Detaillierte Dokumentation:** [01](01_BASIC_ROUTING.md) | [02](02_ROUTE_PARAMETERS.md) | [03](03_ROUTE_GROUPS.md) | [04](04_RATE_LIMITING.md) | [05](05_IP_FILTERING.md) | [06](06_MIDDLEWARE.md) | [07](07_NAMED_ROUTES.md) | [08](08_TAGS.md) | [09](09_HELPER_FUNCTIONS.md) | [10](10_ROUTE_SHORTCUTS.md) | [11](11_ROUTE_MACROS.md) | [12](12_URL_GENERATION.md) | [13](13_EXPRESSION_LANGUAGE.md) | [14](14_CACHING.md) | [15](15_PLUGINS.md) | [16](16_LOADERS.md) | [17](17_PSR_SUPPORT.md) | [18](18_ACTION_RESOLVER.md) | [19](19_STATISTICS.md) | [20](20_SECURITY.md) | [21](21_EXCEPTIONS.md) | [22](22_CLI_TOOLS.md)

---

**Kategorie:** Sicherheit  
**Anzahl Methoden:** 15  
**Komplexität:** ⭐⭐⭐ Fortgeschrittenes Level

---

## Beschreibung

Rate Limiting und Auto-Ban sind leistungsstarke eingebaute Mechanismen zum Schutz vor DDoS-Angriffen, Brute-Force und API-Missbrauch.

## Funktionen

### Rate Limiting (8 Methoden)

#### 1. Grundlegendes Throttle

**Methode:** `throttle(int $maxAttempts, int $decayMinutes, ?callable $keyResolver = null): Route`

**Beschreibung:** Begrenzung der Anzahl von Anfragen an eine Route.

**Parameter:**
- `$maxAttempts` - Maximale Anzahl von Anfragen
- `$decayMinutes` - Zeitraum in Minuten
- `$keyResolver` - Optionale Funktion zur Bestimmung des Schlüssels (Standard IP)

**Beispiele:**

```php
// 60 Anfragen pro Minute
Route::post('/api/submit', $action)
    ->throttle(60, 1);

// 100 Anfragen pro Stunde
Route::post('/api/upload', $action)
    ->throttle(100, 60);

// 1000 Anfragen pro Tag
Route::get('/api/public', $action)
    ->throttle(1000, 1440);

// Mit Controller
Route::post('/login', [AuthController::class, 'login'])
    ->throttle(5, 1);  // 5 Login-Versuche pro Minute
```

**Wie es funktioniert:**
1. Bei jeder Anfrage wird der Zähler für IP (oder benutzerdefinierten Schlüssel) erhöht
2. Wenn der Zähler das Limit überschreitet - wird `TooManyRequestsException` geworfen
3. Nach der angegebenen Zeit wird der Zähler zurückgesetzt

---

#### 2. TimeUnit Enum

**Enum:** `CloudCastle\Http\Router\TimeUnit`

**Beschreibung:** Aufzählung für bequeme Arbeit mit Zeiteinheiten.

**Werte:**
```php
TimeUnit::SECOND->value  // 1/60 Minute
TimeUnit::MINUTE->value  // 1 Minute
TimeUnit::HOUR->value    // 60 Minuten
TimeUnit::DAY->value     // 1440 Minuten
TimeUnit::WEEK->value    // 10080 Minuten
TimeUnit::MONTH->value   // 43200 Minuten
```

**Beispiele:**

```php
use CloudCastle\Http\Router\TimeUnit;

// 5 Anfragen pro Sekunde
Route::post('/api/realtime', $action)
    ->throttle(5, TimeUnit::SECOND->value);

// 100 Anfragen pro Stunde
Route::get('/api/data', $action)
    ->throttle(100, TimeUnit::HOUR->value);

// 1000 Anfragen pro Tag
Route::get('/api/public', $action)
    ->throttle(1000, TimeUnit::DAY->value);
```

---

#### 3. Benutzerdefinierter Schlüssel-Resolver

**Methode:** `throttle(int $maxAttempts, int $decayMinutes, callable $keyResolver): Route`

**Beschreibung:** Verwendung einer benutzerdefinierten Funktion zur Bestimmung des Throttle-Schlüssels.

**Beispiele:**

```php
// Nach Benutzer-ID
Route::post('/api/user-action', $action)
    ->throttle(10, 1, function($request) {
        return 'user:' . $request->user()->id;
    });

// Nach API-Schlüssel
Route::post('/api/external', $action)
    ->throttle(100, 1, function($request) {
        return 'api:' . $request->header('X-API-Key');
    });

// Nach Kombination
Route::post('/api/complex', $action)
    ->throttle(50, 1, function($request) {
        $user = $request->user();
        $ip = $request->ip();
        return "user:{$user->id}:ip:{$ip}";
    });
```

---

#### 4. Gruppen-Throttle

**Methode:** `throttle(array $throttle): RouteGroup`

**Beschreibung:** Anwendung von Throttle auf alle Routen in einer Gruppe.

**Beispiele:**

```php
// API-Gruppe mit Throttle
Route::group(['throttle' => [100, 1]], function() {
    Route::get('/api/users', $action);
    Route::get('/api/posts', $action);
});

// Verschiedene Limits für verschiedene Gruppen
Route::group(['throttle' => [60, 1]], function() {
    Route::get('/api/public', $action);  // 60 Anfragen pro Minute
});

Route::group(['throttle' => [1000, 1]], function() {
    Route::get('/api/premium', $action); // 1000 Anfragen pro Minute
});
```

---

#### 5. Dynamisches Throttle

**Methode:** `throttle(callable $throttleResolver): Route`

**Beschreibung:** Dynamisches Throttle basierend auf Anfragedaten.

**Beispiele:**

```php
// Dynamisch basierend auf Benutzerrolle
Route::post('/api/action', $action)
    ->throttle(function($request) {
        $user = $request->user();
        if ($user->isPremium()) {
            return [1000, 1]; // 1000 Anfragen pro Minute
        }
        return [100, 1]; // 100 Anfragen pro Minute
    });

// Dynamisch basierend auf Anfragegröße
Route::post('/api/upload', $action)
    ->throttle(function($request) {
        $size = $request->header('Content-Length');
        if ($size > 1000000) { // > 1MB
            return [10, 1]; // 10 Anfragen pro Minute
        }
        return [100, 1]; // 100 Anfragen pro Minute
    });
```

---

#### 6. Throttle mit Bedingungen

**Methode:** `throttle(int $maxAttempts, int $decayMinutes, ?callable $keyResolver = null, ?callable $condition = null): Route`

**Beschreibung:** Throttle mit zusätzlichen Bedingungen.

**Beispiele:**

```php
// Throttle nur für POST-Anfragen
Route::match(['GET', 'POST'], '/api/data', $action)
    ->throttle(100, 1, null, function($request) {
        return $request->isMethod('POST');
    });

// Throttle nur für bestimmte IPs
Route::post('/api/sensitive', $action)
    ->throttle(5, 1, null, function($request) {
        $ip = $request->ip();
        return in_array($ip, ['192.168.1.100', '10.0.0.50']);
    });
```

---

#### 7. Throttle-Statistiken

**Methode:** `getThrottleStats(): array`

**Beschreibung:** Abrufen von Throttle-Statistiken.

**Beispiele:**

```php
// Throttle-Statistiken abrufen
$stats = Route::getThrottleStats();

// Beispiel-Ausgabe:
[
    'total_requests' => 1500,
    'blocked_requests' => 25,
    'active_throttles' => 3,
    'top_ips' => [
        '192.168.1.100' => 150,
        '10.0.0.50' => 120
    ]
]
```

---

#### 8. Throttle-Verwaltung

**Methoden:**
- `clearThrottle(string $key): void` - Spezifisches Throttle löschen
- `clearAllThrottles(): void` - Alle Throttles löschen
- `getThrottleKey(string $ip): string` - Throttle-Schlüssel für IP abrufen

**Beispiele:**

```php
// Throttle für bestimmte IP löschen
Route::clearThrottle('192.168.1.100');

// Alle Throttles löschen
Route::clearAllThrottles();

// Throttle-Schlüssel abrufen
$key = Route::getThrottleKey('192.168.1.100');
```

---

### Auto-Ban-System (7 Methoden)

#### 1. Grundlegendes Auto-Ban

**Methode:** `autoBan(int $maxAttempts, int $banMinutes, ?callable $keyResolver = null): Route`

**Beschreibung:** Automatische IP-Sperrung nach Überschreitung der Versuche.

**Parameter:**
- `$maxAttempts` - Maximale Versuche vor Sperrung
- `$banMinutes` - Sperrungsdauer in Minuten
- `$keyResolver` - Optionale Funktion zur Bestimmung des Schlüssels

**Beispiele:**

```php
// Sperrung nach 10 fehlgeschlagenen Versuchen für 1 Stunde
Route::post('/login', [AuthController::class, 'login'])
    ->autoBan(10, 60);

// Sperrung nach 5 fehlgeschlagenen Versuchen für 30 Minuten
Route::post('/api/sensitive', $action)
    ->autoBan(5, 30);

// Sperrung nach 20 fehlgeschlagenen Versuchen für 24 Stunden
Route::post('/api/admin', $action)
    ->autoBan(20, 1440);
```

---

#### 2. Progressives Auto-Ban

**Methode:** `progressiveAutoBan(array $levels): Route`

**Beschreibung:** Progressive Sperrung mit zunehmender Dauer.

**Beispiele:**

```php
// Progressive Sperrungsstufen
Route::post('/login', $action)
    ->progressiveAutoBan([
        5 => 5,    // 5 Versuche -> 5 Minuten Sperrung
        10 => 30,  // 10 Versuche -> 30 Minuten Sperrung
        20 => 120, // 20 Versuche -> 2 Stunden Sperrung
        50 => 1440 // 50 Versuche -> 24 Stunden Sperrung
    ]);
```

---

#### 3. Auto-Ban mit Bedingungen

**Methode:** `autoBan(int $maxAttempts, int $banMinutes, ?callable $keyResolver = null, ?callable $condition = null): Route`

**Beschreibung:** Auto-Ban mit zusätzlichen Bedingungen.

**Beispiele:**

```php
// Sperrung nur bei fehlgeschlagenen Login-Versuchen
Route::post('/login', $action)
    ->autoBan(10, 60, null, function($request, $response) {
        return $response->getStatusCode() === 401;
    });

// Sperrung nur für bestimmte User Agents
Route::post('/api/action', $action)
    ->autoBan(5, 30, null, function($request) {
        $userAgent = $request->header('User-Agent');
        return strpos($userAgent, 'bot') !== false;
    });
```

---

#### 4. Sperrungs-Verwaltung

**Methoden:**
- `banIp(string $ip, int $minutes): void` - IP manuell sperren
- `unbanIp(string $ip): void` - IP entsperren
- `isBanned(string $ip): bool` - Prüfen ob IP gesperrt ist
- `getBanInfo(string $ip): ?array` - Sperrungsinformationen abrufen

**Beispiele:**

```php
// IP manuell für 1 Stunde sperren
Route::banIp('192.168.1.100', 60);

// IP entsperren
Route::unbanIp('192.168.1.100');

// Prüfen ob IP gesperrt ist
if (Route::isBanned('192.168.1.100')) {
    return response('IP ist gesperrt', 403);
}

// Sperrungsinformationen abrufen
$banInfo = Route::getBanInfo('192.168.1.100');
if ($banInfo) {
    echo "Gesperrt bis: " . date('Y-m-d H:i:s', $banInfo['expires_at']);
}
```

---

#### 5. Sperrungs-Statistiken

**Methode:** `getBanStats(): array`

**Beschreibung:** Abrufen von Sperrungs-Statistiken.

**Beispiele:**

```php
// Sperrungs-Statistiken abrufen
$stats = Route::getBanStats();

// Beispiel-Ausgabe:
[
    'total_bans' => 150,
    'active_bans' => 25,
    'bans_today' => 10,
    'top_banned_ips' => [
        '192.168.1.100' => 5,
        '10.0.0.50' => 3
    ]
]
```

---

#### 6. Sperrungs-Bereinigung

**Methode:** `cleanupExpiredBans(): int`

**Beschreibung:** Bereinigung abgelaufener Sperrungen.

**Beispiele:**

```php
// Abgelaufene Sperrungen bereinigen
$cleaned = Route::cleanupExpiredBans();
echo "Bereinigt $cleaned abgelaufene Sperrungen";

// Bereinigung planen (in Cron-Job)
Route::cleanupExpiredBans();
```

---

#### 7. Sperrungs-Whitelist

**Methode:** `whitelistBanIp(string $ip): void`

**Beschreibung:** IP von Auto-Ban auf Whitelist setzen.

**Beispiele:**

```php
// Vertrauenswürdige IPs auf Whitelist setzen
Route::whitelistBanIp('192.168.1.0/24');
Route::whitelistBanIp('10.0.0.0/8');

// Bestimmte IPs auf Whitelist setzen
Route::whitelistBanIp('192.168.1.100');
Route::whitelistBanIp('10.0.0.50');
```

---

## Best Practices

### 1. Angemessene Limits

```php
// Login-Versuche - strenge Limits
Route::post('/login', $action)
    ->throttle(5, 1)
    ->autoBan(10, 60);

// API-Endpunkte - moderate Limits
Route::post('/api/data', $action)
    ->throttle(100, 1);

// Öffentliche Endpunkte - großzügige Limits
Route::get('/api/public', $action)
    ->throttle(1000, 1);
```

### 2. Benutzer-spezifische Limits

```php
// Verschiedene Limits für verschiedene Benutzertypen
Route::post('/api/action', $action)
    ->throttle(function($request) {
        $user = $request->user();
        if ($user->isPremium()) {
            return [1000, 1];
        }
        return [100, 1];
    });
```

### 3. Überwachung

```php
// Throttle- und Sperrungs-Statistiken überwachen
$throttleStats = Route::getThrottleStats();
$banStats = Route::getBanStats();

// Verdächtige Aktivitäten protokollieren
if ($throttleStats['blocked_requests'] > 100) {
    Log::warning('Hohe Anzahl blockierter Anfragen', $throttleStats);
}
```

---

## Häufige Muster

### 1. API-Schutz

```php
Route::group(['prefix' => '/api'], function() {
    Route::post('/login', [AuthController::class, 'login'])
        ->throttle(5, 1)
        ->autoBan(10, 60);
    
    Route::post('/register', [AuthController::class, 'register'])
        ->throttle(3, 1)
        ->autoBan(5, 30);
    
    Route::get('/data', [DataController::class, 'index'])
        ->throttle(100, 1);
});
```

### 2. Admin-Schutz

```php
Route::group(['prefix' => '/admin'], function() {
    Route::post('/login', [AdminController::class, 'login'])
        ->throttle(3, 1)
        ->autoBan(5, 120);
    
    Route::post('/sensitive-action', $action)
        ->throttle(10, 1)
        ->autoBan(15, 60);
});
```

### 3. Öffentliche API

```php
Route::group(['prefix' => '/api/public'], function() {
    Route::get('/health', $action)
        ->throttle(1000, 1);
    
    Route::get('/data', $action)
        ->throttle(100, 1);
    
    Route::post('/contact', $action)
        ->throttle(10, 1)
        ->autoBan(20, 30);
});
```

---

## Performance-Tipps

### 1. Effiziente Speicherung

```php
// Redis für bessere Performance verwenden
Route::setThrottleStorage(new RedisStorage());

// Dateispeicherung für einfache Setups verwenden
Route::setThrottleStorage(new FileStorage('/tmp/throttle'));
```

### 2. Bereinigungsstrategie

```php
// Regelmäßige Bereinigung
Route::cleanupExpiredBans();
Route::cleanupExpiredThrottles();

// Bereinigung in Cron planen
// 0 * * * * php artisan route:cleanup
```

---

## Fehlerbehebung

### Häufige Probleme

1. **Throttle funktioniert nicht**
   - Throttle-Konfiguration prüfen
   - Speicherung verifizieren
   - IP-Erkennung prüfen

2. **Auto-Ban zu aggressiv**
   - Sperrungs-Schwellenwerte anpassen
   - Whitelist für vertrauenswürdige IPs hinzufügen
   - Sperrungs-Statistiken überwachen

3. **Performance-Probleme**
   - Redis-Speicherung verwenden
   - Bereinigungsstrategie implementieren
   - Ressourcenverbrauch überwachen

### Debug-Tipps

```php
// Debug-Modus aktivieren
Route::enableDebug();

// Throttle-Statistiken prüfen
$stats = Route::getThrottleStats();
var_dump($stats);

// Sperrungs-Statistiken prüfen
$banStats = Route::getBanStats();
var_dump($banStats);
```

---

## Siehe auch

- [IP-Filterung](05_IP_FILTERING.md) - IP-basierte Zugriffskontrolle
- [Middleware](06_MIDDLEWARE.md) - Anfrage-Verarbeitungs-Middleware
- [Sicherheit](20_SECURITY.md) - Sicherheitsfunktionen-Übersicht
- [API-Referenz](../API_REFERENCE.md) - Vollständige API-Referenz

---

© 2024 CloudCastle HTTP Router  
[⬆ Nach oben](#rate-limiting--auto-ban)