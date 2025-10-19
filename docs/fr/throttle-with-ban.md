[🇷🇺 Русский](ru/throttle-with-ban.md) | [🇺🇸 English](en/throttle-with-ban.md) | [🇩🇪 Deutsch](de/throttle-with-ban.md) | [🇫🇷 Français](fr/throttle-with-ban.md) | [🇨🇳 中文](zh/throttle-with-ban.md)

[📚 Table of Contents](fr/_table-of-contents.md) | [🏠 Home](fr/README.md)

---

# ThrottleWithBan - Limitation de débit avec interdiction automatique

**Langues :** 🇷🇺 Russe | [🇫🇷 Anglais](../en/throttle-with-ban.md) | [🇩🇪 Deutsch](../de/throttle-with-ban.md) | [🇫🇷 Français](../fr/throttle-with-ban.md) | [🇨🇳中文](../zh/throttle-with-ban.md)

[📚 Table des matières](_table-of-contents.md) | [🏠 Accueil](LISEZMOI.md)

---

## 📚 Bilan

**ThrottleWithBan** est une fonctionnalité CloudCastle unique qui combine une limitation de débit et un système d'interdiction automatique pour une protection maximale contre les abus.

## 🎯Concept

### Limitation du débit régulier

```php
$router->get('/api/data', 'ApiController@data')
    ->perMinute(60);

// 61-й запрос → TooManyRequestsException
// Но злоумышленник может продолжать атаковать каждую минуту
```

### ThrottleWithBan - protection intelligente

```php
$router->get('/api/data', 'ApiController@data')
    ->throttleWithBan(
        maxAttempts: 60,         // 60 запросов в минуту
        decayMinutes: 1,         // окно 1 минута
        maxViolations: 3,        // после 3 нарушений
        banDurationMinutes: 60   // БАН на 1 час!
    );

// 61-й запрос → TooManyRequestsException (violation 1)
// После минуты опять 61-й запрос → TooManyRequestsException (violation 2)
// После минуты опять 61-й запрос → TooManyRequestsException (violation 3)
// Следующий запрос → BannedException на 1 час!
```

## 🔧 Utilisation

### Exemple de base

```php
use CloudCastle\Http\Router\Facade\Route;

Route::get('/api/data', 'ApiController@data')
    ->throttleWithBan(
        maxAttempts: 100,        // лимит запросов
        decayMinutes: 1,         // период (минуты)
        maxViolations: 5,        // кол-во нарушений до бана
        banDurationMinutes: 60   // длительность бана (минуты)
    );
```

### Protection des points de terminaison de connexion

```php
Route::post('/login', 'AuthController@login')
    ->throttleWithBan(
        maxAttempts: 5,          // 5 попыток
        decayMinutes: 1,         // в минуту
        maxViolations: 3,        // 3 нарушения
        banDurationMinutes: 120  // бан на 2 часа
    );
```

**Scénario d'attaque :**
1. L'attaquant fait 6 tentatives → 1 violation
2. Après une minute, 6 tentatives supplémentaires → 2 violations
3. Après une minute, 6 tentatives supplémentaires → 3 violations
4. **BAN automatique pendant 2 heures !** 🔒

### Interdiction instantanée des opérations critiques

```php
Route::delete('/admin/critical', 'AdminController@critical')
    ->middleware(['auth', 'admin'])
    ->throttleWithBan(
        maxAttempts: 1,          // 1 запрос в минуту
        decayMinutes: 1,
        maxViolations: 1,        // бан сразу при нарушении!
        banDurationMinutes: 1440 // бан на 24 часа!
    );
```

**Effet :** Tout dépassement de la limite = bannissement immédiat d'une journée.

## 📊 Niveaux de protection

### Points de terminaison publics

```php
// Мягкая защита
Route::get('/api/public/data', 'PublicController@data')
    ->throttleWithBan(
        maxAttempts: 100,        // много запросов разрешено
        decayMinutes: 1,
        maxViolations: 5,        // много попыток до бана
        banDurationMinutes: 30   // короткий бан
    );
```

**Quand :** API publique, documentation, statistiques

### Points de terminaison protégés

```php
// Средняя защита
Route::get('/api/protected/data', 'ProtectedController@data')
    ->auth()
    ->throttleWithBan(
        maxAttempts: 50,         // средний лимит
        decayMinutes: 1,
        maxViolations: 3,        // стандартно
        banDurationMinutes: 60   // бан на час
    );
```

**Quand :** API authentifiée, données utilisateur, profils

### Points de terminaison d'administration

```php
// Строгая защита
Route::post('/api/admin/action', 'AdminController@action')
    ->admin()
    ->throttleWithBan(
        maxAttempts: 10,         // малый лимит
        decayMinutes: 1,
        maxViolations: 2,        // быстрый бан
        banDurationMinutes: 240  // бан на 4 часа
    );
```

**Quand :** Panneau d'administration, opérations critiques, actions destructrices

### Opérations critiques

```php
// Максимальная защита
Route::delete('/database/drop', 'DangerousController@drop')
    ->admin()
    ->localhost()
    ->throttleWithBan(
        maxAttempts: 1,          // 1 запрос
        decayMinutes: 60,        // в час!
        maxViolations: 1,        // мгновенный бан
        banDurationMinutes: 10080 // бан на неделю!
    );
```

**Quand :** Opérations de base de données, commandes système, actions destructrices

## 🔄 Cycle de vie d'un bannissement

### 1. Fonctionnement normal

```
User → Request → Rate Limit OK → Response
```

### 2. Première infraction

```
User → 61st request → TooManyRequestsException
                    → Violation counter++
                    → Response 429
```

### 3. Violations répétées

```
User → Violation 2 → TooManyRequestsException
User → Violation 3 → TooManyRequestsException
User → Violation 4 (maxViolations reached) → BAN!
```

### 4. Après l'interdiction

```
Banned User → Any request → BannedException
                          → Response 403
                          → Retry-After header
```

### 5. Annuler le bannissement

```
Time passes (banDuration) → Auto unban
                          → Violation counter reset
                          → Normal operation
```

## 🛡️ Protection contre les attaques

### Brute-force lors de la connexion

```php
Route::post('/login', 'AuthController@login')
    ->throttleWithBan(3, 1, 2, 120);

// Атака:
// - Попытка 1-3: OK
// - Попытка 4+: TooManyRequests (violation 1)
// - Через минуту попытка 4+: TooManyRequests (violation 2)
// - Через минуту попытка 4+: BANNED на 2 часа!

// Результат: атакующий заблокирован после 2 минут
```

### DDoS sur l'API

```php
Route::get('/api/heavy', 'ApiController@heavy')
    ->throttleWithBan(50, 1, 3, 30);

// DDoS атака:
// - 51-й запрос/мин: violation 1
// - 51-й запрос/мин (2nd minute): violation 2
// - 51-й запрос/мин (3rd minute): violation 3
// - 4th minute: BANNED на 30 минут!

// Результат: DDoS остановлен через 3 минуты
```

### Scanning/Probing

```php
Route::get('/admin/{path}', 'AdminController@handle')
    ->admin()
    ->throttleWithBan(10, 1, 1, 480);

// Сканирование:
// - 11-й запрос: violation 1
// - Ещё 1 запрос: BANNED на 8 часов!

// Результат: сканер заблокирован мгновенно
```

## 📈 Statistiques et suivi

### Obtenir des statistiques d'interdiction

```php
$route = router()->getRoute('api.data');
$rateLimiter = $route->getRateLimiter();
$banManager = $rateLimiter->getBanManager();

$stats = $banManager->getStatistics();

echo "Total banned: " . $stats['total_banned'] . "\n";
echo "Total violations: " . $stats['total_violations'] . "\n";
echo "Unique IPs: " . $stats['unique_ips_with_violations'] . "\n";
```

### Liste des IP interdites

```php
$bannedIps = $banManager->getBannedIps();

foreach ($bannedIps as $ip => $expiration) {
    $remaining = $expiration - time();
    echo "IP: {$ip}, Time remaining: " . gmdate('H:i:s', $remaining) . "\n";
}
```

### Manual unban

```php
// Разбанить конкретный IP
$banManager->unban('1.2.3.4');

// Разбанить все IP
$banManager->clearAllBans();
```

## 🎨Configurations recommandées

### Tableau de configuration

| Type de point de terminaison | tentatives maximales | désintégrationMin | maxViolations | banDurée | Utilisation |
|:---|:---:|:---:|:---:|:---:|:---:|
| API publique | 100 | 1 | 5 | 30 | Données publiques |
| Public Forms | 20 | 1 | 3 | 60 | Contact forms, feedback |
| Connexion/Authentification | 5 | 1 | 2 | 120 | Protection contre les forces brutes |
| Inscription | 3 | 5 | 2 | 180 | Protection anti-spam |
| API (auth) | 1000 | 1 | 3 | 60 | Authenticated API |
| API (premium) | 10000 | 1 | 3 | 30 | Premium users |
| Admin Panel | 50 | 1 | 2 | 240 | Admin operations |
| Critical Ops | 1 | 60 | 1 | 10080 | Database, system |

### Exemples de paramètres

**E-commerce:**
```php
// Поиск - мягко
Route::get('/search', 'SearchController@index')
    ->throttleWithBan(100, 1, 5, 30);

// Checkout - средне
Route::post('/checkout', 'CheckoutController@process')
    ->auth()
    ->throttleWithBan(10, 1, 2, 60);

// Payment - строго
Route::post('/payment', 'PaymentController@process')
    ->auth()
    ->secure()
    ->throttleWithBan(3, 5, 1, 1440);
```

**SaaS Platform:**
```php
// Free tier
Route::get('/api/data', 'ApiController@data')
    ->throttleWithBan(100, 1, 3, 60);

// Pro tier
Route::get('/api/pro/data', 'ApiController@proData')
    ->auth()
    ->throttleWithBan(1000, 1, 3, 30);

// Enterprise tier
Route::get('/api/enterprise/data', 'ApiController@enterpriseData')
    ->auth()
    ->throttleWithBan(10000, 1, 5, 15);
```

## 🆚 Comparaison avec les concurrents

| Feature | CloudCastle | FastRoute | Symfony | Laravel | Slim | Alto |
|:---|:---:|:---:|:---:|:---:|:---:|:---:|
| Rate Limiting | ✅ | ❌ | ❌ | ✅ | ❌ | ❌ |
| Auto-ban | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ |
| **ThrottleWithBan** | **✅** | **❌** | **❌** | **❌** | **❌** | **❌** |
| Violation tracking | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ |
| Ban statistics | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ |
| Manual unban | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ |

**ThrottleWithBan est une fonctionnalité exclusive à CloudCastle !**

Aucun autre routeur n'offre une sécurité aussi avancée dès le départ.

## 💡 Best Practices

### 1. Différents niveaux pour différents points de terminaison

```php
// Публичные - мягко
->throttleWithBan(100, 1, 5, 30)

// Authenticated - средне
->throttleWithBan(50, 1, 3, 60)

// Admin - строго
->throttleWithBan(10, 1, 2, 240)

// Critical - очень строго
->throttleWithBan(1, 60, 1, 10080)
```

### 2. Journaliser les interdictions

```php
use CloudCastle\Http\Router\Middleware\SecurityLogger;

$router->middleware(new SecurityLogger(__DIR__ . '/logs/bans.log'));

// Автоматически логирует:
// [2025-10-18 23:00:00] BANNED: IP 1.2.3.4 - Max violations reached (3/3)
// [2025-10-18 23:00:01] BLOCKED: IP 1.2.3.4 - Banned until 2025-10-19 00:00:00
```

### 3. Surveillez vos statistiques

```php
// Каждый день
$stats = $banManager->getStatistics();

if ($stats['total_banned'] > 100) {
    // Alert: возможная атака
    notify_admin("High ban activity: {$stats['total_banned']} IPs banned");
}
```

### 4. Configurez différents paramètres pour différents rôles

```php
// Для пользователей
if ($user->role === 'free') {
    $route->throttleWithBan(100, 1, 3, 60);
} elseif ($user->role === 'pro') {
    $route->throttleWithBan(1000, 1, 5, 30);
} elseif ($user->role === 'enterprise') {
    $route->throttleWithBan(10000, 1, 10, 15);
}
```

## ✅ Avantages

1. **Protection automatique**
   - Pas besoin de bannir manuellement
   - Le système lui-même surveille les violations
   - Débannissement automatique

2. **Paramètres flexibles**
   - Personnalisation pour n'importe quel scénario
   - Différents niveaux pour différents points finaux
   - Personnalisation de tous les paramètres

3. **Statistiques détaillées**
   - Combien d'IP sont interdites
   - Combien de violations
   - Quand l'interdiction expire

4. **Protection contre les attaques par rejeu**
   - La limitation régulière du débit ne protège que la minute en cours
   - ThrottleWithBan bannit pour toujours (ou pour longtemps)
   - L'attaquant ne peut pas répéter l'attaque

## ✅Conclusion

ThrottleWithBan est une **fonctionnalité révolutionnaire** pour protéger les applications :

- 🏆 **Unique** - uniquement dans CloudCastle
- 🔒 **Automatique** - sans commande manuelle
- 📊 **Avec statistiques** - contrôle total
- ⚡ **Efficace** - arrête les attaques en quelques minutes

**Utilisation obligatoire** pour :
- Login/Registration endpoints
- Public APIs
- Payment processing
- Admin panels
- Any abuse-prone endpoints

---

*Dernière mise à jour : 18 octobre 2025*

---

[📚 Table des matières](_table-of-contents.md) | [🏠 Accueil](LISEZMOI.md)

---

[📚 Table of Contents](fr/_table-of-contents.md) | [🏠 Home](fr/README.md)
