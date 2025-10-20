# Filtrage IP

[English](../../en/features/05_IP_FILTERING.md) | [Русский](../../ru/features/05_IP_FILTERING.md) | [Deutsch](../../de/features/05_IP_FILTERING.md) | [**Français**](05_IP_FILTERING.md) | [中文](../../zh/features/05_IP_FILTERING.md)

---

## 📚 Navigation Documentation

[README](../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [API_REFERENCE](../API_REFERENCE.md) | [ALL_FEATURES](../ALL_FEATURES.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [PERFORMANCE](../PERFORMANCE_ANALYSIS.md) | [SECURITY](../SECURITY_REPORT.md) | [COMPARISON](../COMPARISON.md) | [FAQ](../FAQ.md)

**Documentation Détaillée:** [01](01_BASIC_ROUTING.md) | [02](02_ROUTE_PARAMETERS.md) | [03](03_ROUTE_GROUPS.md) | [04](04_RATE_LIMITING.md) | [05](05_IP_FILTERING.md) | [06](06_MIDDLEWARE.md) | [07](07_NAMED_ROUTES.md) | [08](08_TAGS.md) | [09](09_HELPER_FUNCTIONS.md) | [10](10_ROUTE_SHORTCUTS.md) | [11](11_ROUTE_MACROS.md) | [12](12_URL_GENERATION.md) | [13](13_EXPRESSION_LANGUAGE.md) | [14](14_CACHING.md) | [15](15_PLUGINS.md) | [16](16_LOADERS.md) | [17](17_PSR_SUPPORT.md) | [18](18_ACTION_RESOLVER.md) | [19](19_STATISTICS.md) | [20](20_SECURITY.md) | [21](21_EXCEPTIONS.md) | [22](22_CLI_TOOLS.md)

---

**Catégorie:** Sécurité  
**Nombre de Méthodes:** 4  
**Complexité:** ⭐⭐ Niveau Intermédiaire

---

## Description

Le filtrage IP permet de contrôler l'accès aux routes en fonction des adresses IP des clients. Supporte la whitelist (uniquement autorisés) et la blacklist (uniquement refusés), y compris la notation CIDR pour les sous-réseaux.

## Méthodes

### 1. whitelistIp()

**Méthode:** `whitelistIp(array $ips): Route`

**Description:** Autoriser l'accès uniquement depuis les adresses IP spécifiées.

**Exemples:**

```php
// IP unique
Route::get('/admin', $action)
    ->whitelistIp(['192.168.1.1']);

// IPs multiples
Route::get('/admin', $action)
    ->whitelistIp([
        '192.168.1.1',
        '192.168.1.2',
        '10.0.0.100'
    ]);

// Notation CIDR (sous-réseau)
Route::get('/admin', $action)
    ->whitelistIp([
        '192.168.1.0/24',    // 192.168.1.0 - 192.168.1.255
        '10.0.0.0/8'         // 10.0.0.0 - 10.255.255.255
    ]);

// Réseau de bureau
Route::get('/internal', $action)
    ->whitelistIp(['192.168.0.0/16']);
```

### 2. blacklistIp()

**Méthode:** `blacklistIp(array $ips): Route`

**Description:** Refuser l'accès depuis les adresses IP spécifiées.

**Exemples:**

```php
// Bloquer des IPs spécifiques
Route::get('/public', $action)
    ->blacklistIp(['1.2.3.4', '5.6.7.8']);

// CIDR
Route::get('/api/data', $action)
    ->blacklistIp(['1.2.3.0/24']);

// Depuis la base de données
$bannedIps = DB::table('banned_ips')->pluck('ip')->toArray();
Route::get('/api/data', $action)
    ->blacklistIp($bannedIps);
```

### 3. Support CIDR

**Format:** `IP/MASK`

**Exemples:**

```php
// /32 - IP unique
Route::get('/test', $action)->whitelistIp(['192.168.1.1/32']);

// /24 - sous-réseau 256 adresses
Route::get('/test', $action)->whitelistIp(['192.168.1.0/24']);

// /16 - 65,536 adresses
Route::get('/test', $action)->whitelistIp(['192.168.0.0/16']);

// /8 - 16,777,216 adresses
Route::get('/test', $action)->whitelistIp(['10.0.0.0/8']);
```

### 4. Protection contre l'Usurpation d'IP

**Description:** Vérification automatique de X-Forwarded-For et autres en-têtes.

CloudCastle HTTP Router vérifie automatiquement:
- `X-Forwarded-For`
- `X-Real-IP`
- Protège contre l'usurpation d'IP

## Exemples Complets

### Panneau d'Administration

```php
Route::group([
    'prefix' => '/admin',
    'middleware' => [AuthMiddleware::class, AdminMiddleware::class],
    'https' => true,
    'whitelistIp' => ['192.168.1.0/24']  // Seulement bureau
], function() {
    Route::get('/dashboard', [AdminController::class, 'dashboard']);
    Route::get('/users', [AdminController::class, 'users']);
    
    // Point de terminaison critique - protection encore plus stricte
    Route::post('/settings/critical', [AdminController::class, 'critical'])
        ->whitelistIp(['192.168.1.100']);  // Seulement une IP
});
```

### API Interne

```php
Route::group([
    'prefix' => '/api/internal',
    'whitelistIp' => [
        '10.0.1.100',  // Serveur App 1
        '10.0.1.101',  // Serveur App 2
        '10.0.1.102'   // Serveur App 3
    ]
], function() {
    Route::post('/webhook', [WebhookController::class, 'handle']);
    Route::post('/sync', [SyncController::class, 'sync']);
});
```

### API Publique avec Blacklist

```php
// Plages IP bloquées
$blockedRanges = [
    '1.2.3.0/24',    // Réseau de bots connu
    '5.6.7.0/24',    // Source de spam
    '123.45.67.89'   // IP abusive
];

Route::group([
    'prefix' => '/api/public',
    'blacklistIp' => $blockedRanges
], function() {
    Route::get('/data', [ApiController::class, 'data']);
    Route::get('/stats', [ApiController::class, 'stats']);
});
```

## Meilleures Pratiques

### 1. Whitelist pour Routes Sensibles

```php
// Toujours utiliser whitelist pour routes admin/internes
Route::group(['prefix' => '/admin'], function() {
    // Toutes les routes admin
})->whitelistIp(['192.168.1.0/24']);
```

### 2. Configuration Basée sur l'Environnement

```php
$allowedIps = config('app.admin_ips', ['127.0.0.1']);

Route::group([
    'prefix' => '/admin',
    'whitelistIp' => $allowedIps
], function() {
    // Routes admin
});
```

### 3. Combiner avec Autre Sécurité

```php
Route::group([
    'prefix' => '/admin',
    'middleware' => [AuthMiddleware::class, AdminMiddleware::class],
    'whitelistIp' => ['192.168.1.0/24'],
    'throttle' => [100, 1],
    'https' => true
], function() {
    // Plusieurs couches de sécurité
});
```

## Dépannage

### Problèmes Courants

1. **Accès refusé malgré IP correcte**
   - Vérifier si derrière proxy/load balancer
   - Vérifier l'en-tête X-Forwarded-For
   - Vérifier la notation CIDR

2. **CIDR ne fonctionne pas**
   - Vérifier le format de notation
   - Vérifier les calculs de sous-réseau
   - Tester d'abord avec une IP unique

3. **Proxy/Load Balancer**
   - Configurer les proxies de confiance
   - Vérifier la gestion X-Forwarded-For
   - Vérifier la détection d'IP

### Conseils de Debug

```php
// Enregistrer l'IP réelle
Route::get('/debug-ip', function() {
    return [
        'REMOTE_ADDR' => $_SERVER['REMOTE_ADDR'],
        'X-Forwarded-For' => $_SERVER['HTTP_X_FORWARDED_FOR'] ?? null,
        'X-Real-IP' => $_SERVER['HTTP_X_REAL_IP'] ?? null
    ];
});
```

## Voir Aussi

- [Limitation de Débit](04_RATE_LIMITING.md) - Limitation de débit et auto-ban
- [Sécurité](20_SECURITY.md) - Aperçu des fonctionnalités de sécurité
- [Middleware](06_MIDDLEWARE.md) - Middleware de traitement des requêtes
- [Référence API](../API_REFERENCE.md) - Référence API complète

---

© 2024 CloudCastle HTTP Router  
[⬆ Retour en haut](#filtrage-ip)