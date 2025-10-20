# Limitation de Débit & Auto-Ban

[English](../../en/features/04_RATE_LIMITING.md) | [Русский](../../ru/features/04_RATE_LIMITING.md) | [Deutsch](../../de/features/04_RATE_LIMITING.md) | [**Français**](04_RATE_LIMITING.md) | [中文](../../zh/features/04_RATE_LIMITING.md)

---

## 📚 Navigation Documentation

[README](../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [API_REFERENCE](../API_REFERENCE.md) | [ALL_FEATURES](../ALL_FEATURES.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [PERFORMANCE](../PERFORMANCE_ANALYSIS.md) | [SECURITY](../SECURITY_REPORT.md) | [COMPARISON](../COMPARISON.md) | [FAQ](../FAQ.md)

**Documentation Détaillée:** [01](01_BASIC_ROUTING.md) | [02](02_ROUTE_PARAMETERS.md) | [03](03_ROUTE_GROUPS.md) | [04](04_RATE_LIMITING.md) | [05](05_IP_FILTERING.md) | [06](06_MIDDLEWARE.md) | [07](07_NAMED_ROUTES.md) | [08](08_TAGS.md) | [09](09_HELPER_FUNCTIONS.md) | [10](10_ROUTE_SHORTCUTS.md) | [11](11_ROUTE_MACROS.md) | [12](12_URL_GENERATION.md) | [13](13_EXPRESSION_LANGUAGE.md) | [14](14_CACHING.md) | [15](15_PLUGINS.md) | [16](16_LOADERS.md) | [17](17_PSR_SUPPORT.md) | [18](18_ACTION_RESOLVER.md) | [19](19_STATISTICS.md) | [20](20_SECURITY.md) | [21](21_EXCEPTIONS.md) | [22](22_CLI_TOOLS.md)

---

**Catégorie:** Sécurité  
**Nombre de Méthodes:** 15  
**Complexité:** ⭐⭐⭐ Niveau Avancé

---

## Description

La limitation de débit et l'Auto-Ban sont des mécanismes puissants intégrés pour la protection contre les attaques DDoS, la force brute et l'abus d'API.

## Fonctionnalités

### Limitation de Débit (8 méthodes)

#### 1. Throttle de base

**Méthode:** `throttle(int $maxAttempts, int $decayMinutes, ?callable $keyResolver = null): Route`

**Description:** Limitation du nombre de requêtes vers une route.

**Paramètres:**
- `$maxAttempts` - Nombre maximum de requêtes
- `$decayMinutes` - Période de temps en minutes
- `$keyResolver` - Fonction optionnelle pour déterminer la clé (IP par défaut)

**Exemples:**

```php
// 60 requêtes par minute
Route::post('/api/submit', $action)
    ->throttle(60, 1);

// 100 requêtes par heure
Route::post('/api/upload', $action)
    ->throttle(100, 60);

// 1000 requêtes par jour
Route::get('/api/public', $action)
    ->throttle(1000, 1440);

// Avec contrôleur
Route::post('/login', [AuthController::class, 'login'])
    ->throttle(5, 1);  // 5 tentatives de connexion par minute
```

**Comment ça fonctionne:**
1. À chaque requête, le compteur pour l'IP (ou clé personnalisée) est incrémenté
2. Si le compteur dépasse la limite - `TooManyRequestsException` est lancée
3. Après le temps spécifié, le compteur est réinitialisé

---

#### 2. Enum TimeUnit

**Enum:** `CloudCastle\Http\Router\TimeUnit`

**Description:** Énumération pour un travail pratique avec les unités de temps.

**Valeurs:**
```php
TimeUnit::SECOND->value  // 1/60 minute
TimeUnit::MINUTE->value  // 1 minute
TimeUnit::HOUR->value    // 60 minutes
TimeUnit::DAY->value     // 1440 minutes
TimeUnit::WEEK->value    // 10080 minutes
TimeUnit::MONTH->value   // 43200 minutes
```

**Exemples:**

```php
use CloudCastle\Http\Router\TimeUnit;

// 5 requêtes par seconde
Route::post('/api/realtime', $action)
    ->throttle(5, TimeUnit::SECOND->value);

// 100 requêtes par heure
Route::get('/api/data', $action)
    ->throttle(100, TimeUnit::HOUR->value);

// 1000 requêtes par jour
Route::get('/api/public', $action)
    ->throttle(1000, TimeUnit::DAY->value);
```

---

#### 3. Résolveur de clé personnalisé

**Méthode:** `throttle(int $maxAttempts, int $decayMinutes, callable $keyResolver): Route`

**Description:** Utilisation d'une fonction personnalisée pour déterminer la clé de throttle.

**Exemples:**

```php
// Par ID utilisateur
Route::post('/api/user-action', $action)
    ->throttle(10, 1, function($request) {
        return 'user:' . $request->user()->id;
    });

// Par clé API
Route::post('/api/external', $action)
    ->throttle(100, 1, function($request) {
        return 'api:' . $request->header('X-API-Key');
    });

// Par combinaison
Route::post('/api/complex', $action)
    ->throttle(50, 1, function($request) {
        $user = $request->user();
        $ip = $request->ip();
        return "user:{$user->id}:ip:{$ip}";
    });
```

---

#### 4. Throttle de groupe

**Méthode:** `throttle(array $throttle): RouteGroup`

**Description:** Application du throttle à toutes les routes d'un groupe.

**Exemples:**

```php
// Groupe API avec throttle
Route::group(['throttle' => [100, 1]], function() {
    Route::get('/api/users', $action);
    Route::get('/api/posts', $action);
});

// Limites différentes pour différents groupes
Route::group(['throttle' => [60, 1]], function() {
    Route::get('/api/public', $action);  // 60 requêtes par minute
});

Route::group(['throttle' => [1000, 1]], function() {
    Route::get('/api/premium', $action); // 1000 requêtes par minute
});
```

---

#### 5. Throttle dynamique

**Méthode:** `throttle(callable $throttleResolver): Route`

**Description:** Throttle dynamique basé sur les données de requête.

**Exemples:**

```php
// Dynamique basé sur le rôle utilisateur
Route::post('/api/action', $action)
    ->throttle(function($request) {
        $user = $request->user();
        if ($user->isPremium()) {
            return [1000, 1]; // 1000 requêtes par minute
        }
        return [100, 1]; // 100 requêtes par minute
    });

// Dynamique basé sur la taille de requête
Route::post('/api/upload', $action)
    ->throttle(function($request) {
        $size = $request->header('Content-Length');
        if ($size > 1000000) { // > 1MB
            return [10, 1]; // 10 requêtes par minute
        }
        return [100, 1]; // 100 requêtes par minute
    });
```

---

#### 6. Throttle avec conditions

**Méthode:** `throttle(int $maxAttempts, int $decayMinutes, ?callable $keyResolver = null, ?callable $condition = null): Route`

**Description:** Throttle avec conditions supplémentaires.

**Exemples:**

```php
// Throttle seulement pour les requêtes POST
Route::match(['GET', 'POST'], '/api/data', $action)
    ->throttle(100, 1, null, function($request) {
        return $request->isMethod('POST');
    });

// Throttle seulement pour des IPs spécifiques
Route::post('/api/sensitive', $action)
    ->throttle(5, 1, null, function($request) {
        $ip = $request->ip();
        return in_array($ip, ['192.168.1.100', '10.0.0.50']);
    });
```

---

#### 7. Statistiques de throttle

**Méthode:** `getThrottleStats(): array`

**Description:** Obtenir les statistiques de throttle.

**Exemples:**

```php
// Obtenir les stats de throttle
$stats = Route::getThrottleStats();

// Exemple de sortie:
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

#### 8. Gestion de throttle

**Méthodes:**
- `clearThrottle(string $key): void` - Effacer un throttle spécifique
- `clearAllThrottles(): void` - Effacer tous les throttles
- `getThrottleKey(string $ip): string` - Obtenir la clé de throttle pour IP

**Exemples:**

```php
// Effacer le throttle pour une IP spécifique
Route::clearThrottle('192.168.1.100');

// Effacer tous les throttles
Route::clearAllThrottles();

// Obtenir la clé de throttle
$key = Route::getThrottleKey('192.168.1.100');
```

---

### Système Auto-Ban (7 méthodes)

#### 1. Auto-ban de base

**Méthode:** `autoBan(int $maxAttempts, int $banMinutes, ?callable $keyResolver = null): Route`

**Description:** Blocage automatique d'IP après dépassement des tentatives.

**Paramètres:**
- `$maxAttempts` - Nombre maximum de tentatives avant bannissement
- `$banMinutes` - Durée du bannissement en minutes
- `$keyResolver` - Fonction optionnelle pour déterminer la clé

**Exemples:**

```php
// Bannir après 10 tentatives échouées pendant 1 heure
Route::post('/login', [AuthController::class, 'login'])
    ->autoBan(10, 60);

// Bannir après 5 tentatives échouées pendant 30 minutes
Route::post('/api/sensitive', $action)
    ->autoBan(5, 30);

// Bannir après 20 tentatives échouées pendant 24 heures
Route::post('/api/admin', $action)
    ->autoBan(20, 1440);
```

---

#### 2. Auto-ban progressif

**Méthode:** `progressiveAutoBan(array $levels): Route`

**Description:** Bannissement progressif avec durée croissante.

**Exemples:**

```php
// Niveaux de bannissement progressif
Route::post('/login', $action)
    ->progressiveAutoBan([
        5 => 5,    // 5 tentatives -> 5 minutes de bannissement
        10 => 30,  // 10 tentatives -> 30 minutes de bannissement
        20 => 120, // 20 tentatives -> 2 heures de bannissement
        50 => 1440 // 50 tentatives -> 24 heures de bannissement
    ]);
```

---

#### 3. Auto-ban avec conditions

**Méthode:** `autoBan(int $maxAttempts, int $banMinutes, ?callable $keyResolver = null, ?callable $condition = null): Route`

**Description:** Auto-ban avec conditions supplémentaires.

**Exemples:**

```php
// Bannir seulement pour les tentatives de connexion échouées
Route::post('/login', $action)
    ->autoBan(10, 60, null, function($request, $response) {
        return $response->getStatusCode() === 401;
    });

// Bannir seulement pour des user agents spécifiques
Route::post('/api/action', $action)
    ->autoBan(5, 30, null, function($request) {
        $userAgent = $request->header('User-Agent');
        return strpos($userAgent, 'bot') !== false;
    });
```

---

#### 4. Gestion de bannissement

**Méthodes:**
- `banIp(string $ip, int $minutes): void` - Bannir manuellement une IP
- `unbanIp(string $ip): void` - Débannir une IP
- `isBanned(string $ip): bool` - Vérifier si une IP est bannie
- `getBanInfo(string $ip): ?array` - Obtenir les informations de bannissement

**Exemples:**

```php
// Bannir manuellement une IP pendant 1 heure
Route::banIp('192.168.1.100', 60);

// Débannir une IP
Route::unbanIp('192.168.1.100');

// Vérifier si une IP est bannie
if (Route::isBanned('192.168.1.100')) {
    return response('IP est bannie', 403);
}

// Obtenir les informations de bannissement
$banInfo = Route::getBanInfo('192.168.1.100');
if ($banInfo) {
    echo "Bannie jusqu'à: " . date('Y-m-d H:i:s', $banInfo['expires_at']);
}
```

---

#### 5. Statistiques de bannissement

**Méthode:** `getBanStats(): array`

**Description:** Obtenir les statistiques de bannissement.

**Exemples:**

```php
// Obtenir les stats de bannissement
$stats = Route::getBanStats();

// Exemple de sortie:
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

#### 6. Nettoyage de bannissement

**Méthode:** `cleanupExpiredBans(): int`

**Description:** Nettoyer les bannissements expirés.

**Exemples:**

```php
// Nettoyer les bannissements expirés
$cleaned = Route::cleanupExpiredBans();
echo "Nettoyé $cleaned bannissements expirés";

// Planifier le nettoyage (dans un cron job)
Route::cleanupExpiredBans();
```

---

#### 7. Whitelist de bannissement

**Méthode:** `whitelistBanIp(string $ip): void`

**Description:** Mettre une IP sur la whitelist de l'auto-ban.

**Exemples:**

```php
// Mettre les IPs de confiance sur la whitelist
Route::whitelistBanIp('192.168.1.0/24');
Route::whitelistBanIp('10.0.0.0/8');

// Mettre des IPs spécifiques sur la whitelist
Route::whitelistBanIp('192.168.1.100');
Route::whitelistBanIp('10.0.0.50');
```

---

## Meilleures Pratiques

### 1. Limites Appropriées

```php
// Tentatives de connexion - limites strictes
Route::post('/login', $action)
    ->throttle(5, 1)
    ->autoBan(10, 60);

// Points de terminaison API - limites modérées
Route::post('/api/data', $action)
    ->throttle(100, 1);

// Points de terminaison publics - limites généreuses
Route::get('/api/public', $action)
    ->throttle(1000, 1);
```

### 2. Limites Spécifiques à l'Utilisateur

```php
// Limites différentes pour différents types d'utilisateurs
Route::post('/api/action', $action)
    ->throttle(function($request) {
        $user = $request->user();
        if ($user->isPremium()) {
            return [1000, 1];
        }
        return [100, 1];
    });
```

### 3. Surveillance

```php
// Surveiller les stats de throttle et de bannissement
$throttleStats = Route::getThrottleStats();
$banStats = Route::getBanStats();

// Enregistrer les activités suspectes
if ($throttleStats['blocked_requests'] > 100) {
    Log::warning('Nombre élevé de requêtes bloquées', $throttleStats);
}
```

---

## Modèles Courants

### 1. Protection API

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

### 2. Protection Admin

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

### 3. API Publique

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

## Conseils de Performance

### 1. Stockage Efficace

```php
// Utiliser Redis pour de meilleures performances
Route::setThrottleStorage(new RedisStorage());

// Utiliser le stockage fichier pour des configurations simples
Route::setThrottleStorage(new FileStorage('/tmp/throttle'));
```

### 2. Stratégie de Nettoyage

```php
// Nettoyage régulier
Route::cleanupExpiredBans();
Route::cleanupExpiredThrottles();

// Planifier le nettoyage dans cron
// 0 * * * * php artisan route:cleanup
```

---

## Dépannage

### Problèmes Courants

1. **Throttle ne fonctionne pas**
   - Vérifier la configuration du throttle
   - Vérifier que le stockage fonctionne
   - Vérifier la détection d'IP

2. **Auto-ban trop agressif**
   - Ajuster les seuils de bannissement
   - Ajouter une whitelist pour les IPs de confiance
   - Surveiller les statistiques de bannissement

3. **Problèmes de performance**
   - Utiliser le stockage Redis
   - Implémenter une stratégie de nettoyage
   - Surveiller l'utilisation des ressources

### Conseils de Debug

```php
// Activer le mode debug
Route::enableDebug();

// Vérifier les stats de throttle
$stats = Route::getThrottleStats();
var_dump($stats);

// Vérifier les stats de bannissement
$banStats = Route::getBanStats();
var_dump($banStats);
```

---

## Voir Aussi

- [Filtrage IP](05_IP_FILTERING.md) - Contrôle d'accès basé sur IP
- [Middleware](06_MIDDLEWARE.md) - Middleware de traitement des requêtes
- [Sécurité](20_SECURITY.md) - Aperçu des fonctionnalités de sécurité
- [Référence API](../API_REFERENCE.md) - Référence API complète

---

© 2024 CloudCastle HTTP Router  
[⬆ Retour en haut](#limitation-de-débit--auto-ban)