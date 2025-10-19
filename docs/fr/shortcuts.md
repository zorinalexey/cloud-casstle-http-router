[🇷🇺 Русский](ru/shortcuts.md) | [🇺🇸 English](en/shortcuts.md) | [🇩🇪 Deutsch](de/shortcuts.md) | [🇫🇷 Français](fr/shortcuts.md) | [🇨🇳 中文](zh/shortcuts.md)

[📚 Table of Contents](fr/_table-of-contents.md) | [🏠 Home](fr/README.md)

---

# Raccourcis d'itinéraire - Raccourcis pour les itinéraires

**Langues :** 🇷🇺 Russe | [🇫🇷 Anglais](../en/shortcuts.md) | [🇩🇪 Deutsch](../de/shortcuts.md) | [🇫🇷 Français](../fr/shortcuts.md) | [🇨🇳中文](../zh/shortcuts.md)

[📚 Table des matières](_table-of-contents.md) | [🏠 Accueil](LISEZMOI.md)

---

## 📚 Bilan

Les **Raccourcis d'itinéraire** sont des méthodes pratiques pour configurer rapidement les configurations d'itinéraire fréquemment utilisées.

## 🎯 Middleware Shortcuts

### auth() - Authentification

```php
// Вместо
$router->get('/profile', 'ProfileController@show')
    ->middleware('auth');

// Используйте
$router->get('/profile', 'ProfileController@show')
    ->auth();  // Короче и понятнее!
```

### guest() - Pour les invités uniquement

```php
// Только для неавторизованных
$router->get('/login', 'AuthController@showLoginForm')
    ->guest();

$router->get('/register', 'AuthController@showRegisterForm')
    ->guest();
```

### api() - API middleware

```php
$router->get('/api/data', 'ApiController@data')
    ->api();  // API middleware + JSON headers
```

### admin() - Administrateur

```php
// Вместо
$router->get('/admin/dashboard', 'AdminController@index')
    ->middleware(['auth', 'admin'])
    ->tag('admin');

// Используйте
$router->get('/admin/dashboard', 'AdminController@index')
    ->admin();  // auth + admin middleware + tag
```

## 🔒 Security Shortcuts

### localhost() - Uniquement depuis localhost

```php
// Вместо
$router->get('/debug', 'DebugController@index')
    ->whitelistIp(['127.0.0.1', '::1']);

// Используйте
$router->get('/debug', 'DebugController@index')
    ->localhost();  // Автоматически добавляет localhost IPs
```

**Équivalent** : `->whitelistIp(['127.0.0.1', '::1', 'localhost'])`

### secure() - HTTPS uniquement

```php
// Вместо
$router->post('/payment', 'PaymentController@process')
    ->port(443)
    ->protocol('https');

// Используйте
$router->post('/payment', 'PaymentController@process')
    ->secure();  // HTTPS only, port 443
```

## ⚡ Throttle Shortcuts

### throttleStandard() - Limite standard

```php
$router->get('/api/data', 'ApiController@data')
    ->throttleStandard();  // 60 req/min
```

**Équivalent** : `->throttle(60, 60)` ou `->perMinute(60)`

### throttleStrict() - Restriction stricte

```php
$router->post('/auth/login', 'AuthController@login')
    ->throttleStrict();  // 10 req/min
```

**Équivalent** : `->throttle(10, 60)` ou `->perMinute(10)`

### throttleGenerous() - Limite généreuse

```php
$router->get('/api/premium', 'ApiController@premium')
    ->auth()
    ->throttleGenerous();  // 1000 req/min
```

**Équivalent** : `->throttle(1000, 60)` ou `->perMinute(1000)`

## 🏷️ Tag Shortcuts

### public() - Itinéraire public

```php
$router->get('/api/public', 'ApiController@public')
    ->public();  // tag('public')
```

### private() - Itinéraire privé

```php
$router->get('/internal/api', 'InternalController@api')
    ->private();  // tag('private')
```

## 🎨 Composite Shortcuts

### apiEndpoint() - Configuration complète du point de terminaison de l'API

```php
// Вместо
$router->get('/api/users', 'UserController@index')
    ->middleware('api')
    ->throttle(100, 60)
    ->tag('api');

// Используйте
$router->get('/api/users', 'UserController@index')
    ->apiEndpoint(100);  // Всё в одном!
```

**Configure :**
- API middleware
- Limitation de débit (paramètre)
- Tag 'api'

### protected() - Ressource protégée

```php
$router->get('/documents', 'DocumentController@index')
    ->protected();  // auth + throttle(100)
```

**Configure :**
- Auth middleware
- Standard throttle (100 req/min)

## 📋 Liste complète des raccourcis

| Raccourci | Équivalent | Descriptif |
|:---|:---:|:---:|
| `auth()` | `middleware('auth')` | Nécessite une autorisation |
| `invité()` | `middleware('invité')` | Pour les invités seulement |
| `api()` | `middleware('api')` | API middleware |
| `admin()` | `middleware(['auth','admin'])+tag('admin')` | Accès administrateur |
| `localhost()` | `whitelistIp(['127.0.0.1','::1'])` | localhost uniquement |
| `sécurisé()` | `port(443)+protocole('https')` | HTTPS uniquement |
| `throttleStandard()` | `throttle(60,60)` | 60 req/min |
| `throttleStrict()` | `throttle(10,60)` | 10 req/min |
| `throttleGenerous()` | `throttle(1000,60)` | 1000 req/min |
| `public()` | `tag('public')` | Balise publique |
| `privé()` | `tag('privé')` | Balise privée |
| `apiEndpoint($limite)` | `api()+accélérateur($limit)+tag('api')` | Configuration complète de l'API |
| `protégé()` | `auth()+accélérateur(100)` | Ressource protégée |

## 🔗 Chaînes de raccourcis

Les raccourcis peuvent être combinés :

```php
$router->post('/api/secure/data', 'SecureController@data')
    ->secure()           // HTTPS only
    ->auth()             // Authenticated
    ->admin()            // Admin role
    ->throttleStrict()   // 10 req/min
    ->localhost()        // Localhost only
    ->name('secure.data');

// Эквивалентно длинной цепочке:
// ->port(443)
// ->protocol('https')
// ->middleware('auth')
// ->middleware('admin')
// ->tag('admin')
// ->throttle(10, 60)
// ->whitelistIp(['127.0.0.1', '::1'])
// ->name('secure.data')
```

## 📊 Exemples d'utilisation

### API RESTful rapide

```php
// С shortcuts - 8 строк
$router->get('/api/users', 'UserController@index')
    ->api()->throttleGenerous();

$router->post('/api/users', 'UserController@store')
    ->api()->auth()->throttleStandard();

$router->get('/api/users/{id}', 'UserController@show')
    ->api()->throttleGenerous();

$router->put('/api/users/{id}', 'UserController@update')
    ->api()->auth()->throttleStandard();

// Без shortcuts - 32 строки
$router->get('/api/users', 'UserController@index')
    ->middleware('api')
    ->throttle(1000, 60)
    ->tag('api');

$router->post('/api/users', 'UserController@store')
    ->middleware(['api', 'auth'])
    ->throttle(60, 60)
    ->tag('api');
// ... и так далее
```

**Réduction des codes : 75 % !**

###Panneau d'administration

```php
// С shortcuts
$router->group(['prefix' => 'admin'], function($router) {
    $router->get('/dashboard', 'DashboardController@index')
        ->admin()->localhost();
    
    $router->get('/users', 'UserController@index')
        ->admin()->localhost();
    
    $router->post('/settings', 'SettingsController@update')
        ->admin()->localhost()->throttleStrict();
});

// Каждый маршрут:
// - Auth + admin middleware
// - Tag 'admin'
// - Localhost only
// - Throttle (для POST)
```

### API publique avec sécurité

```php
$router->group(['prefix' => 'api/public'], function($router) {
    $router->get('/data', 'ApiController@data')
        ->apiEndpoint(100)  // api + throttle(100) + tag
        ->public();         // tag('public')
    
    $router->get('/stats', 'ApiController@stats')
        ->apiEndpoint(50);  // более строгий лимит
});
```

## 💡 Best Practices

### 1. Utilisez des raccourcis pour plus de lisibilité

```php
// ХОРОШО: с shortcuts
$router->get('/admin', 'AdminController@index')
    ->admin()
    ->secure()
    ->localhost();

// Понятно: админ, HTTPS, локально

// ПЛОХО: без shortcuts
$router->get('/admin', 'AdminController@index')
    ->middleware(['auth', 'admin'])
    ->port(443)
    ->protocol('https')
    ->whitelistIp(['127.0.0.1', '::1'])
    ->tag('admin');

// Слишком многословно
```

### 2. Créez des raccourcis personnalisés pour le projet

```php
// Расширение Route через макрос
Route::macro('premium', function() {
    return $this->auth()
        ->middleware('premium')
        ->throttleGenerous()
        ->tag('premium');
});

// Использование
$router->get('/premium/content', 'PremiumController@index')
    ->premium();  // Custom shortcut!
```

### 3. Documentez les raccourcis personnalisés

```php
/**
 * Configure route as a premium endpoint.
 * 
 * Applies:
 * - Auth middleware
 * - Premium middleware  
 * - Generous throttle (1000 req/min)
 * - Premium tag
 */
Route::macro('premium', function() {
    // ...
});
```

## 🆚 Comparaison avec les concurrents

| Router | Built-in Shortcuts | Custom Shortcuts | Chainable |
|:---|:---:|:---:|:---:|
| **CloudCastle** | **✅ 13+** | **✅ Macros** | **✅** |
| FastRoute | ❌ | ❌ | ❌ |
| Symfony | ⚠️ 3 | ⚠️ | ⚠️ |
| Laravel | ✅ 8 | ✅ | ✅ |
| Slim | ⚠️ 2 | ⚠️ | ✅ |
| AltoRouter | ❌ | ❌ | ❌ |

## ✅Conclusion

Les raccourcis d'itinéraire font le code :

- **50 à 75 % plus court**
- **Plus lisible**
- **Plus pris en charge**
- **Moins sujet aux erreurs**

CloudCastle fournit **le plus grand nombre de raccourcis intégrés** de tous les routeurs PHP !

---

*Dernière mise à jour : 18 octobre 2025*

---

[📚 Table des matières](_table-of-contents.md) | [🏠 Accueil](LISEZMOI.md)

---

[📚 Table of Contents](fr/_table-of-contents.md) | [🏠 Home](fr/README.md)
