# IP-Filterung

[English](../../en/features/05_IP_FILTERING.md) | [Русский](../../ru/features/05_IP_FILTERING.md) | [**Deutsch**](05_IP_FILTERING.md) | [Français](../../fr/features/05_IP_FILTERING.md) | [中文](../../zh/features/05_IP_FILTERING.md)

---

## 📚 Dokumentationsnavigation

[README](../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [API_REFERENCE](../API_REFERENCE.md) | [ALL_FEATURES](../ALL_FEATURES.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [PERFORMANCE](../PERFORMANCE_ANALYSIS.md) | [SECURITY](../SECURITY_REPORT.md) | [COMPARISON](../COMPARISON.md) | [FAQ](../FAQ.md)

**Detaillierte Dokumentation:** [01](01_BASIC_ROUTING.md) | [02](02_ROUTE_PARAMETERS.md) | [03](03_ROUTE_GROUPS.md) | [04](04_RATE_LIMITING.md) | [05](05_IP_FILTERING.md) | [06](06_MIDDLEWARE.md) | [07](07_NAMED_ROUTES.md) | [08](08_TAGS.md) | [09](09_HELPER_FUNCTIONS.md) | [10](10_ROUTE_SHORTCUTS.md) | [11](11_ROUTE_MACROS.md) | [12](12_URL_GENERATION.md) | [13](13_EXPRESSION_LANGUAGE.md) | [14](14_CACHING.md) | [15](15_PLUGINS.md) | [16](16_LOADERS.md) | [17](17_PSR_SUPPORT.md) | [18](18_ACTION_RESOLVER.md) | [19](19_STATISTICS.md) | [20](20_SECURITY.md) | [21](21_EXCEPTIONS.md) | [22](22_CLI_TOOLS.md)

---

**Kategorie:** Sicherheit  
**Anzahl Methoden:** 4  
**Komplexität:** ⭐⭐ Mittelstufe

---

## Beschreibung

IP-Filterung ermöglicht es, den Zugriff auf Routen basierend auf Client-IP-Adressen zu kontrollieren. Unterstützt Whitelist (nur erlaubt) und Blacklist (nur verweigert), einschließlich CIDR-Notation für Subnetze.

## Methoden

### 1. whitelistIp()

**Methode:** `whitelistIp(array $ips): Route`

**Beschreibung:** Zugriff nur von angegebenen IP-Adressen erlauben.

**Beispiele:**

```php
// Einzelne IP
Route::get('/admin', $action)
    ->whitelistIp(['192.168.1.1']);

// Mehrere IPs
Route::get('/admin', $action)
    ->whitelistIp([
        '192.168.1.1',
        '192.168.1.2',
        '10.0.0.100'
    ]);

// CIDR-Notation (Subnetz)
Route::get('/admin', $action)
    ->whitelistIp([
        '192.168.1.0/24',    // 192.168.1.0 - 192.168.1.255
        '10.0.0.0/8'         // 10.0.0.0 - 10.255.255.255
    ]);

// Büronetzwerk
Route::get('/internal', $action)
    ->whitelistIp(['192.168.0.0/16']);
```

### 2. blacklistIp()

**Methode:** `blacklistIp(array $ips): Route`

**Beschreibung:** Zugriff von angegebenen IP-Adressen verweigern.

**Beispiele:**

```php
// Bestimmte IPs blockieren
Route::get('/public', $action)
    ->blacklistIp(['1.2.3.4', '5.6.7.8']);

// CIDR
Route::get('/api/data', $action)
    ->blacklistIp(['1.2.3.0/24']);

// Aus Datenbank
$bannedIps = DB::table('banned_ips')->pluck('ip')->toArray();
Route::get('/api/data', $action)
    ->blacklistIp($bannedIps);
```

### 3. CIDR-Unterstützung

**Format:** `IP/MASK`

**Beispiele:**

```php
// /32 - einzelne IP
Route::get('/test', $action)->whitelistIp(['192.168.1.1/32']);

// /24 - Subnetz 256 Adressen
Route::get('/test', $action)->whitelistIp(['192.168.1.0/24']);

// /16 - 65,536 Adressen
Route::get('/test', $action)->whitelistIp(['192.168.0.0/16']);

// /8 - 16,777,216 Adressen
Route::get('/test', $action)->whitelistIp(['10.0.0.0/8']);
```

### 4. IP-Spoofing-Schutz

**Beschreibung:** Automatische Überprüfung von X-Forwarded-For und anderen Headern.

CloudCastle HTTP Router überprüft automatisch:
- `X-Forwarded-For`
- `X-Real-IP`
- Schützt vor IP-Spoofing

## Vollständige Beispiele

### Admin-Panel

```php
Route::group([
    'prefix' => '/admin',
    'middleware' => [AuthMiddleware::class, AdminMiddleware::class],
    'https' => true,
    'whitelistIp' => ['192.168.1.0/24']  // Nur Büro
], function() {
    Route::get('/dashboard', [AdminController::class, 'dashboard']);
    Route::get('/users', [AdminController::class, 'users']);
    
    // Kritischer Endpunkt - noch strengerer Schutz
    Route::post('/settings/critical', [AdminController::class, 'critical'])
        ->whitelistIp(['192.168.1.100']);  // Nur eine IP
});
```

### Interne API

```php
Route::group([
    'prefix' => '/api/internal',
    'whitelistIp' => [
        '10.0.1.100',  // App Server 1
        '10.0.1.101',  // App Server 2
        '10.0.1.102'   // App Server 3
    ]
], function() {
    Route::post('/webhook', [WebhookController::class, 'handle']);
    Route::post('/sync', [SyncController::class, 'sync']);
});
```

### Öffentliche API mit Blacklist

```php
// Blockierte IP-Bereiche
$blockedRanges = [
    '1.2.3.0/24',    // Bekanntes Bot-Netzwerk
    '5.6.7.0/24',    // Spam-Quelle
    '123.45.67.89'   // Missbräuchliche IP
];

Route::group([
    'prefix' => '/api/public',
    'blacklistIp' => $blockedRanges
], function() {
    Route::get('/data', [ApiController::class, 'data']);
    Route::get('/stats', [ApiController::class, 'stats']);
});
```

## Best Practices

### 1. Whitelist für sensible Routen

```php
// Immer Whitelist für Admin-/Interne Routen verwenden
Route::group(['prefix' => '/admin'], function() {
    // Alle Admin-Routen
})->whitelistIp(['192.168.1.0/24']);
```

### 2. Umgebungsbasierte Konfiguration

```php
$allowedIps = config('app.admin_ips', ['127.0.0.1']);

Route::group([
    'prefix' => '/admin',
    'whitelistIp' => $allowedIps
], function() {
    // Admin-Routen
});
```

### 3. Mit anderer Sicherheit kombinieren

```php
Route::group([
    'prefix' => '/admin',
    'middleware' => [AuthMiddleware::class, AdminMiddleware::class],
    'whitelistIp' => ['192.168.1.0/24'],
    'throttle' => [100, 1],
    'https' => true
], function() {
    // Mehrere Sicherheitsebenen
});
```

## Fehlerbehebung

### Häufige Probleme

1. **Zugriff verweigert trotz korrekter IP**
   - Prüfen ob hinter Proxy/Load Balancer
   - X-Forwarded-For-Header verifizieren
   - CIDR-Notation prüfen

2. **CIDR funktioniert nicht**
   - Notationsformat verifizieren
   - Subnetz-Berechnungen prüfen
   - Zuerst mit einzelner IP testen

3. **Proxy/Load Balancer**
   - Vertrauenswürdige Proxies konfigurieren
   - X-Forwarded-For-Behandlung prüfen
   - IP-Erkennung verifizieren

### Debug-Tipps

```php
// Tatsächliche IP protokollieren
Route::get('/debug-ip', function() {
    return [
        'REMOTE_ADDR' => $_SERVER['REMOTE_ADDR'],
        'X-Forwarded-For' => $_SERVER['HTTP_X_FORWARDED_FOR'] ?? null,
        'X-Real-IP' => $_SERVER['HTTP_X_REAL_IP'] ?? null
    ];
});
```

## Siehe auch

- [Rate Limiting](04_RATE_LIMITING.md) - Rate Limiting und Auto-Ban
- [Sicherheit](20_SECURITY.md) - Sicherheitsfunktionen-Übersicht
- [Middleware](06_MIDDLEWARE.md) - Anfrage-Verarbeitungs-Middleware
- [API-Referenz](../API_REFERENCE.md) - Vollständige API-Referenz

---

© 2024 CloudCastle HTTP Router  
[⬆ Nach oben](#ip-filterung)