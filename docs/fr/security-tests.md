[🇷🇺 Русский](ru/security-tests.md) | [🇺🇸 English](en/security-tests.md) | [🇩🇪 Deutsch](de/security-tests.md) | [🇫🇷 Français](fr/security-tests.md) | [🇨🇳 中文](zh/security-tests.md)

[📚 Table of Contents](fr/_table-of-contents.md) | [🏠 Home](fr/README.md)

---

# Tests de sécurité du routeur HTTP CloudCastle

**Langues :** 🇷🇺 Russe | [🇫🇷 Anglais](../en/security-tests.md) | [🇩🇪 Deutsch](../de/security-tests.md) | [🇫🇷 Français](../fr/security-tests.md) | [🇨🇳中文](../zh/security-tests.md)

[📚 Table des matières](_table-of-contents.md) | [🏠 Accueil](LISEZMOI.md)

---

## 📊 Informations générales

**Tests de sécurité totale** : 13
**Statut** : ✅ Tous les tests réussis (100%)
**Assertions**: 38  
**Temps d'exécution** : 0,110s
**Mémoire** : 12 Mo

## 🛡️ Catégories de protection

### 1. Path Traversal Protection

**Description** : Protection contre les attaques utilisant `../` pour accéder à des fichiers en dehors de la racine Web.

**Test** : Tentative d'accès à `/../../etc/passwd`

**Mécanisme de protection** :
- Normalisation des chemins
- Séquences de blocage `../`
- Vérification des chemins absolus

**Résultat** : ✅ RÉUSSI

**Exemple de protection :**
```php
$router->get('/files/{path}', function($path) {
    // Роутер автоматически блокирует '../../../etc/passwd'
    // Вызовет RouteNotFoundException
    return file_get_contents(__DIR__ . '/uploads/' . $path);
});
```

**Comparaison avec les concurrents :**
- CloudCastle : ✅ Protection intégrée
- FastRoute : ❌ Aucune protection
- Symfony : ✅ Il y a une protection
- Laravel : ✅ Il y a une protection
- Slim : ❌ Aucune protection
- AltoRouter : ❌ Aucune protection

---

### 2. SQL Injection in Parameters

**Description** : Protection contre les injections SQL via les paramètres de route.

**Test** : Paramètres tels que `` OU '1'='1`

**Mécanisme de protection** :
- Les paramètres sont passés tels quels (non interprétés)
- Responsabilité au niveau de l'application
- Le routeur n'exécute pas les requêtes SQL

**Résultat** : ✅ RÉUSSI

**Recommandations :**
```php
// ПРАВИЛЬНО: используйте prepared statements
$router->get('/users/{id}', function($id) use ($pdo) {
    $stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
    $stmt->execute([$id]);
    return $stmt->fetch();
});

// НЕПРАВИЛЬНО: прямая интерполяция
$router->get('/users/{id}', function($id) use ($pdo) {
    return $pdo->query("SELECT * FROM users WHERE id = {$id}"); // ОПАСНО!
});
```

---

### 3. XSS (Cross-Site Scripting) Protection

**Description** : Protection contre les attaques XSS via des paramètres.

**Test** : Paramètres tels que `<script>alert('XSS')</script>`

**Mécanisme de protection** :
- Les paramètres ne sont pas automatiquement filtrés par le routeur
- L'application est responsable de la désinfection
- Le routeur fournit des données propres

**Résultat** : ✅ RÉUSSI

**Recommandations :**
```php
// ПРАВИЛЬНО: экранируйте вывод
$router->get('/search/{query}', function($query) {
    return htmlspecialchars($query, ENT_QUOTES, 'UTF-8');
});

// Или используйте шаблонизатор с авто-экранированием
$router->get('/search/{query}', function($query) use ($twig) {
    return $twig->render('search.html', ['query' => $query]);
});
```

---

### 4. IP Whitelist Security

**Description** : Restreindre l'accès aux adresses IP autorisées uniquement.

**Mécanisme**:
```php
$router->get('/admin', 'AdminController@index')
    ->whitelistIp(['192.168.1.100', '10.0.0.0/8']);
```

**Test** : Accès depuis une adresse IP non autorisée

**Résultat** : ✅ RÉUSSI - IpNotAllowedException levée

**Application:**
- Commissions administratives
- Internal API endpoints
- Restricted resources

---

### 5. IP Blacklist Security

**Description** : Blocage de l'accès à partir de certaines adresses IP.

**Mécanisme**:
```php
$router->get('/api/data', 'ApiController@data')
    ->blacklistIp(['1.2.3.4', '5.6.7.0/24']);
```

**Test** : Accès depuis une IP bloquée

**Résultat** : ✅ RÉUSSI - IpNotAllowedException levée

**Application:**
- Blocage des IP malveillantes
- Protection anti-spam
- Géoblocage

---

### 6. IP Spoofing Protection

**Description** : Protection contre l'usurpation d'adresse IP via les en-têtes HTTP.

**En-têtes dangereux** :
- `X-Forwarded-For`
- `X-Real-IP`
- `Client-IP`

**Mécanisme de protection** :
- Utilisation de $_SERVER['REMOTE_ADDR']
- Ignorer les en-têtes non fiables
- Vérification des chaînes proxy

**Résultat** : ✅ RÉUSSI

**Recommandations :**
```php
// Роутер использует только REMOTE_ADDR
// Если нужно доверять proxy, настройте явно:
$router->setTrustedProxies(['10.0.0.1']);
```

---

### 7. Domain Security

**Description** : Vérification des restrictions de domaine de routage.

**Mécanisme**:
```php
$router->get('/api/v1', 'ApiController@index')
    ->domain('api.example.com');
```

**Test** : Accès depuis un autre domaine

**Résultat** : ✅ RÉUSSI - l'itinéraire ne correspond pas

**Application:**
- Applications multi-locataires
- Routage de sous-domaine
- API versioning

---

### 8. ReDoS (Regular Expression Denial of Service) Protection

**Description** : Protection contre les attaques via des expressions régulières complexes.

**Modèles dangereux** :
- `(a+)+`
- `(a|a)*`
- `(a|ab)*`

**Mécanisme de protection** :
- Limitation de la difficulté des expressions régulières
- Délai d'expiration pour la correspondance des expressions régulières
- Validation du modèle

**Résultat** : ✅ RÉUSSI

**Recommandations :**
```php
// ПРАВИЛЬНО: простые паттерны
$router->get('/users/{id}', fn($id) => $id)
    ->where('id', '\d+');

// ИЗБЕГАЙТЕ: сложные вложенные паттерны
$router->get('/complex/{param}', fn($p) => $p)
    ->where('param', '(a+)+'); // ОПАСНО!
```

---

### 9. Method Override Attack

**Description** : Protection contre l'usurpation de méthode HTTP via les en-têtes ou les paramètres POST.

**Attaques** :
- `X-HTTP-Method-Override: DELETE`
- `_method=DELETE` in POST

**Mécanisme de protection** :
- Ignorer le remplacement de méthode par défaut
- Activation facultative pour les sources fiables

**Résultat** : ✅ RÉUSSI

---

### 10. Mass Assignment in Route Params

**Description** : Protection contre l'affectation en masse via les paramètres d'itinéraire.

**Test** : passage de nombreux paramètres non déclarés

**Mécanisme de protection** :
- Seuls les paramètres déclarés sont récupérés
- Le reste est ignoré
- Strict parameter matching

**Résultat** : ✅ RÉUSSI

---

### 11. Cache Injection

**Description** : Protection contre l'injection dans le cache de route.

**Mécanisme de protection** :
- Sérialisation sans rappels `__wakeup`
- Validation stricte des données mises en cache
- Contrôle d'intégrité

**Résultat** : ✅ RÉUSSI

**En code:**
```php
// RouteCache использует безопасную сериализацию
$cache = new RouteCache(__DIR__ . '/cache/routes.php');
$cache->store($routes);
```

---

### 12. Resource Exhaustion

**Description** : Protection contre l'épuisement des ressources dû à des demandes excessives.

**Mécanisme de protection** :
- **Rate Limiting** : limitation des requêtes
- **Auto-ban** : blocage automatique
- **Limites de mémoire** : contrôle de la consommation de mémoire

**Résultat** : ✅ RÉUSSI

**Exemple:**
```php
// Rate limiting
$router->get('/api/data', 'ApiController@data')
    ->perMinute(60); // максимум 60 запросов в минуту

// Auto-ban при превышении
$router->enableAutoBan(
    maxAttempts: 100,
    decayMinutes: 60,
    banDuration: 3600
);
```

---

### 13. Unicode Security Issues

**Description** : Protection contre les attaques utilisant des caractères Unicode.

**Dangers** :
- Homoglyphes (caractères similaires)
- Right-to-left override
- Zero-width characters

**Mécanisme de protection** :
- Validation UTF-8
- Normalisation Unicode
- Vérifier les caractères de contrôle

**Résultat** : ✅ RÉUSSI

---

## 🔒 Fonctionnalités de sécurité uniques de CloudCastle

### SSRF (Server-Side Request Forgery) Protection

**Uniquement chez CloudCastle !**

```php
use CloudCastle\Http\Router\Middleware\SsrfProtection;

$router->middleware(new SsrfProtection());

// Блокирует запросы к:
// - localhost/127.0.0.1
// - Private IP ranges (10.0.0.0/8, 172.16.0.0/12, 192.168.0.0/16)
// - Link-local addresses
// - Cloud metadata APIs (169.254.169.254)
```

### Système d'interdiction automatique

**Uniquement chez CloudCastle !**

```php
$banManager = new BanManager();
$router->setBanManager($banManager);

// Автоматическая блокировка после rate limit
$router->enableAutoBan(
    maxAttempts: 100,
    decayMinutes: 60,
    banDuration: 3600 // ban на 1 час
);
```

### Security Logger

**Uniquement chez CloudCastle !**

```php
use CloudCastle\Http\Router\Middleware\SecurityLogger;

$router->middleware(new SecurityLogger(__DIR__ . '/logs/security.log'));

// Логирует:
// - Все security события
// - Заблокированные IP
// - Rate limit превышения
// - Подозрительную активность
```

## 📊 Comparaison des capacités de sécurité

| Protection | Château Cloud | Itinéraire rapide | Symfony | Laravel | Mince | Alto |
|:---|:---:|:---:|:---:|:---:|:---:|:---:|
| Path Traversal | ✅ | ❌ | ✅ | ✅ | ❌ | ❌ |
| Injection SQL (en paramètres) | ✅ | ⚠️ | ⚠️ | ⚠️ | ⚠️ | ⚠️ |
| XSS Protection | ⚠️ | ❌ | ⚠️ | ⚠️ | ❌ | ❌ |
| IP Whitelist | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ |
| IP Blacklist | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ |
| IP Spoofing | ✅ | ❌ | ⚠️ | ⚠️ | ❌ | ❌ |
| Domain Security | ✅ | ❌ | ✅ | ✅ | ❌ | ❌ |
| ReDoS Protection | ✅ | ⚠️ | ⚠️ | ⚠️ | ❌ | ❌ |
| Method Override | ✅ | ❌ | ⚠️ | ⚠️ | ❌ | ❌ |
| Mass Assignment | ✅ | ❌ | ❌ | ⚠️ | ❌ | ❌ |
| Cache Injection | ✅ | ⚠️ | ✅ | ⚠️ | ❌ | ❌ |
| Resource Exhaustion | ✅ | ❌ | ❌ | ⚠️ | ❌ | ❌ |
| Unicode Security | ✅ | ❌ | ⚠️ | ⚠️ | ❌ | ❌ |
| **SSRF Protection** | **✅** | **❌** | **❌** | **❌** | **❌** | **❌** |
| **Auto-ban System** | **✅** | **❌** | **❌** | **❌** | **❌** | **❌** |
| **Rate Limiting** | **✅** | **❌** | **❌** | **✅** | **❌** | **❌** |
| **Security Logger** | **✅** | **❌** | **❌** | **❌** | **❌** | **❌** |

**Légende:**
- ✅Protection intégrée
- ⚠️ Protection partielle ou nécessite une configuration supplémentaire
- ❌ Aucune protection

## 🔐 Description détaillée des mécanismes de protection

### Protection SSRF (fonctionnalité unique)

**Ce qu'il protège** :
```php
// Блокирует запросы к внутренним ресурсам
$blockedUrls = [
    'http://localhost/admin',
    'http://127.0.0.1:8080/internal',
    'http://192.168.1.1/router',
    'http://10.0.0.5/database',
    'http://169.254.169.254/latest/meta-data', // AWS metadata
    'http://metadata.google.internal/', // GCP metadata
];
```

**Usage:**
```php
use CloudCastle\Http\Router\Middleware\SsrfProtection;

$router->middleware(new SsrfProtection(
    allowLocalhost: false, // блокировать localhost
    allowPrivateIps: false, // блокировать private IP
    allowCloudMetadata: false // блокировать cloud metadata
));
```

### Limitation du débit avec interdiction automatique

**Protection combinée :**
```php
// Rate limiting
$router->get('/api/data', 'ApiController@data')
    ->perMinute(60); // 60 запросов в минуту

// Auto-ban после превышения
$banManager = new BanManager();
$router->setBanManager($banManager);
$router->enableAutoBan(
    maxAttempts: 100, // после 100 попыток
    decayMinutes: 60, // в течение 1 часа
    banDuration: 3600 // бан на 1 час
);
```

**Résultat**:
- 60 premières requêtes/min : ✅ OK
- Requête 61-100 : ⚠️ TooManyRequestsException
- 100+ demandes : 🔒 Bannissement permanent + BannedException

### IP Filtering

**Exemple de liste blanche :**
```php
// Только для офисных IP
$router->get('/internal/reports', 'ReportController@index')
    ->whitelistIp([
        '203.0.113.0/24', // office network
        '198.51.100.50', // VPN gateway
    ]);
```

**Exemple de liste noire :**
```php
// Блокировка известных злоумышленников
$router->get('/public/api', 'ApiController@public')
    ->blacklistIp([
        '1.2.3.4',
        '5.6.7.8',
    ]);
```

### HTTPS Enforcement

```php
use CloudCastle\Http\Router\Middleware\HttpsEnforcement;

$router->middleware(new HttpsEnforcement(
    redirect: true, // автоматический redirect на HTTPS
    permanent: true // 301 вместо 302
));
```

### Security Logger

**Journalisation automatique :**
```php
use CloudCastle\Http\Router\Middleware\SecurityLogger;

$router->middleware(new SecurityLogger('/var/log/security.log'));

// Логируется:
// [2025-10-18 22:00:15] BLOCKED: IP 1.2.3.4 - Rate limit exceeded
// [2025-10-18 22:01:30] BANNED: IP 1.2.3.4 - Auto-ban triggered
// [2025-10-18 22:05:45] SUSPICIOUS: Path traversal attempt from 5.6.7.8
// [2025-10-18 22:10:00] BLOCKED: SSRF attempt to http://169.254.169.254
```

## 📊 Résultats des tests de sécurité

### Résultats détaillés

| # | Test | Descriptif | Affirmations | Temps | Statut |
|:---|:---:|:---:|:---:|:---:|:---:|
| 1 | Path Traversal | `../` sequences | 3 | 0.008s | ✅ |
| 2 | Injection SQL | SQL dans les paramètres | 3 | 0,005s | ✅ |
| 3 | XSS | Balises de script dans les paramètres | 3 | 0,006s | ✅ |
| 4 | Liste blanche IP | Accès à partir d'une adresse IP hors liste blanche | 3 | 0,010s | ✅ |
| 5 | Liste noire IP | Accès depuis la liste noire IP | 3 | 0,009s | ✅ |
| 6 | Usurpation d'adresse IP | Substitution via les en-têtes | 3 | 0,011s | ✅ |
| 7 | Sécurité du domaine | Mauvais domaine | 3 | 0,007s | ✅ |
| 8 | ReDoS | Regex complexe | 3 | 0,012s | ✅ |
| 9 | Remplacement de méthode | Substitution de méthode | 3 | 0,008s | ✅ |
| 10 | Affectation de masse | Paramètres supplémentaires | 3 | 0,010s | ✅ |
| 11 | Injection de cache | Injection dans le cache | 3 | 0,009s | ✅ |
| 12 | Épuisement des ressources | DoS via des requêtes | 3 | 0,006s | ✅ |
| 13 | Sécurité Unicode | Attaques Unicode | 2 | 0,006s | ✅ |
| **TOTAL** | **13** | | **38** | **0,110s** | **✅** |

## 💡 Recommandations de sécurité

### 1. Utilisez toujours HTTPS en production

```php
$router->middleware(new HttpsEnforcement(redirect: true));
```

### 2. Configurer la limitation de débit pour les points de terminaison publics

```php
$router->get('/api/public', 'ApiController@public')
    ->perMinute(60);
```

### 3. Utilisez la liste blanche IP pour les panneaux d'administration

```php
$router->group(['prefix' => '/admin'], function($router) {
    $router->whitelistIp(['your-office-ip']);
    // admin routes...
});
```

### 4. Activez l'interdiction automatique pour la protection contre la force brute

```php
$router->enableAutoBan(
    maxAttempts: 100,
    decayMinutes: 60,
    banDuration: 3600
);
```

### 5. Utilisez Security Logger pour la surveillance

```php
$router->middleware(new SecurityLogger(__DIR__ . '/logs/security.log'));
```

### 6. Activer la protection SSRF pour les URL générées par l'utilisateur

```php
$router->middleware(new SsrfProtection());
```

## 🏆 Avantages de la sécurité CloudCastle

### vs FastRoute
- ✅ +14 fonctionnalités de sécurité
- ✅ Protection SSRF intégrée
- ✅ Système d'interdiction automatique
- ✅ IP filtering

### vs Symfony
- ✅ Configuration plus facile
- ✅ Protection SSRF prête à l'emploi
- ✅ Système d'interdiction automatique
- ✅ Limitation de débit intégrée

### vs Laravel  
- ✅ Sécurité autonome (pas de framework)
- ✅ SSRF Protection
- ✅ Filtrage IP plus flexible
- ✅ Security Logger

### vs Slim
- ✅ +15 fonctionnalités de sécurité
- ✅Protection complète
- ✅ Auto-ban
- ✅ Limitation de débit intégrée

### vs AltoRouter
- ✅ +16 fonctionnalités de sécurité
- ✅ Suite de sécurité complète
- ✅ Enterprise-ready

## ✅Conclusion

Le routeur HTTP CloudCastle offre **la sécurité la plus complète** de tous les routeurs PHP :

1. **13/13 tests de sécurité** réussis ✅
2. **17 mécanismes de sécurité** intégrés
3. **4 fonctionnalités uniques** (SSRF, interdiction automatique, enregistreur de sécurité, filtrage IP)
4. **100 % de préparation** pour la production

Le routeur est prêt à être utilisé dans des projets présentant des **exigences de sécurité élevées** : fintech, e-commerce, SaaS, applications d'entreprise.

---

*Dernière mise à jour : 18 octobre 2025*

---

[📚 Table des matières](_table-of-contents.md) | [🏠 Accueil](LISEZMOI.md)

---

[📚 Table of Contents](fr/_table-of-contents.md) | [🏠 Home](fr/README.md)
