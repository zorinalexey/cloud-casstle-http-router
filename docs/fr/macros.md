[🇷🇺 Русский](ru/macros.md) | [🇺🇸 English](en/macros.md) | [🇩🇪 Deutsch](de/macros.md) | [🇫🇷 Français](fr/macros.md) | [🇨🇳 中文](zh/macros.md)

[📚 Table of Contents](fr/_table-of-contents.md) | [🏠 Home](fr/README.md)

---

# Macros d'itinéraire - Macros pour créer rapidement des itinéraires

**Langues :** 🇷🇺 Russe | [🇫🇷 Anglais](../en/macros.md) | [🇩🇪 Allemand](../de/macros.md) | [🇫🇷 Français](../fr/macros.md) | [🇨🇳中文](../zh/macros.md)

[📚 Table des matières](_table-of-contents.md) | [🏠 Accueil](LISEZMOI.md)

---

## 📚 Bilan

**Route Macros** est un système puissant permettant de créer plusieurs itinéraires avec une seule commande. Réduit le code de 80 à 90 %.

## 🎯 Macros intégrées

### 1. resource() - RESTful Resource

**Crée 7 routes d'opérations CRUD sur une seule ligne !**

```php
use CloudCastle\Http\Router\Facade\Route;

Route::resource('users', 'UserController');
```

**Crée des itinéraires :**

| Method | URI | Action | Name |
|:---|:---:|:---:|:---:|
| GET | `/users` | index | `users.index` |
| GET | `/users/create` | create | `users.create` |
| POST | `/users` | store | `users.store` |
| GET | `/users/{id}` | show | `users.show` |
| GET | `/users/{id}/edit` | edit | `users.edit` |
| PUT | `/users/{id}` | update | `users.update` |
| DELETE | `/users/{id}` | destroy | `users.destroy` |

**Comparaison:**
```php
// БЕЗ MACRO (35 строк):
$router->get('/users', 'UserController@index')->name('users.index');
$router->get('/users/create', 'UserController@create')->name('users.create');
$router->post('/users', 'UserController@store')->name('users.store');
$router->get('/users/{id}', 'UserController@show')->name('users.show');
$router->get('/users/{id}/edit', 'UserController@edit')->name('users.edit');
$router->put('/users/{id}', 'UserController@update')->name('users.update');
$router->delete('/users/{id}', 'UserController@destroy')->name('users.destroy');

// С MACRO (1 строка):
Route::resource('users', 'UserController');

// Сокращение: 97%! ⚡
```

---

### 2. apiResource() - RESTful API Resource

**Crée des points de terminaison d'API avec un middleware, un régulateur et des balises !**

```php
Route::apiResource('posts', 'Api\PostController', 200);
```

**Crée des itinéraires :**

| Method | URI | Action | Extras |
|:---|:---:|:---:|:---:|
| GET | `/posts` | index | api middleware, throttle 200 |
| POST | `/posts` | store | api middleware, throttle 100 |
| GET | `/posts/{id}` | show | api middleware, throttle 200 |
| PUT | `/posts/{id}` | update | api middleware, throttle 100 |
| DELETE | `/posts/{id}` | destroy | api middleware, throttle 100 |

**Différences par rapport à resource()** :
- ✅ Pas de routes `/create` et `/edit` (non nécessaires pour l'API)
- ✅ Middleware API automatiquement
- ✅ Rate limiting (reads: 200, writes: 100)
- ✅ Tag 'api'

**Application:**
- RESTful JSON APIs
- GraphQL endpoints
- Mobile app backends

---

### 3. crud() - Opérations CRUD simples

**Crée 4 routes CRUD principales :**

```php
Route::crud('comments', 'CommentController');
```

**Crée des itinéraires :**

| Method | URI | Action | Name |
|:---|:---:|:---:|:---:|
| GET | `/comments` | index | `comments.index` |
| POST | `/comments` | create | `comments.create` |
| PUT | `/comments/{id}` | update | `comments.update` |
| DELETE | `/comments/{id}` | delete | `comments.delete` |

**Quand l'utiliser :**
- Opérations CRUD simples
- Lorsque les formulaires `/create` et `/edit` ne sont pas nécessaires
- Prototypage rapide

---

### 4. auth() - Toutes les routes d'authentification

**Crée un ensemble complet de routes d'authentification !**

```php
Route::auth();
```

**Crée des itinéraires :**

| Method | URI | Action | Throttle |
|:---|:---:|:---:|:---:|
| GET | `/login` | showLoginForm | - |
| POST | `/login` | login | 5 req/min (strict) |
| POST | `/logout` | logout | - |
| GET | `/register` | showRegisterForm | - |
| POST | `/register` | register | 3 req/min (very strict) |
| GET | `/password/reset` | showResetForm | - |
| POST | `/password/reset` | reset | 3 req/min |

**Protection:**
- ✅ Limitation du tarif lors de la connexion/inscription
- ✅ CSRF protection
- ✅ Tag 'auth'

**Application:**
- Système d'autorisation standard
- Démarrage rapide du projet
- Authentication scaffolding

---

### 5. adminPanel() - Panneau d'administration

**Crée un panneau d'administration sécurisé avec une liste blanche IP !**

```php
Route::adminPanel(['192.168.1.1', '10.0.0.0/8']);
```

**Crée des itinéraires :**

| URI | Action | Middleware | IP Filter |
|:---|:---:|:---:|:---:|
| `/admin/dashboard` | index | auth, admin | whitelist |
| `/admin/users` | users | auth, admin | whitelist |
| `/admin/settings` | settings | auth, admin | whitelist |

**Configure :**
- ✅ Auth + Admin middleware
- ✅ IP whitelist
- ✅ Tag 'admin'
- ✅ Throttle 500 req/min

**Application:**
- Commissions administratives
- Internal tools
-Console de gestion

---

### 6. apiVersion() - API Versioning

**Crée une API versionnée !**

```php
Route::apiVersion('v1', function() {
    Route::get('/users', 'Api\V1\UserController@index');
    Route::get('/posts', 'Api\V1\PostController@index');
});

Route::apiVersion('v2', function() {
    Route::get('/users', 'Api\V2\UserController@index');
    Route::get('/posts', 'Api\V2\PostController@index');
});
```

**Configure :**
- ✅ Préfixe `/api/v{version}`
- ✅ API middleware
- ✅ Rate limiting
- ✅ Tag `api-v{version}`
- ✅ Namespace `Api\V{version}`

**Résultat:**
- `/api/v1/users` → `Api\V1\UserController@index`
- `/api/v2/users` → `Api\V2\UserController@index`

---

### 7. webhooks() - Webhook Endpoints

**Crée des points de terminaison de webhook sécurisés !**

```php
Route::webhooks(['192.0.2.1', '198.51.100.1']);
```

**Crée des itinéraires :**

| Method | URI | Action | Protection |
|:---|:---:|:---:|:---:|
| POST | `/webhooks/github` | github | IP whitelist, signature |
| POST | `/webhooks/stripe` | stripe | IP whitelist, signature |
| POST | `/webhooks/slack` | slack | IP whitelist, signature |

**Configure :**
- ✅ IP whitelist
- ✅ Signature verification middleware
- ✅ High rate limit (1000 req/min)
- ✅ Tag 'webhook'

**Application:**
- GitHub webhooks
- Stripe webhooks
- Payment gateways
- Third-party integrations

---

## 🔧 Création de macros personnalisées

### Macros simples

```php
use CloudCastle\Http\Router\RouteMacros;

// Регистрация macro
RouteMacros::register('premium', function($router, $resource, $controller) {
    $router->group([
        'prefix' => $resource,
        'middleware' => ['auth', 'premium'],
        'throttle' => 10000,
    ], function($router) use ($controller) {
        $router->get('/', "{$controller}@index");
        $router->get('/{id}', "{$controller}@show");
    });
});

// Использование
Route::premium('exclusive', 'ExclusiveController');
```

### Macro avec paramètres

```php
RouteMacros::register('microservice', function($router, $name, $port, $ip) {
    $router->group([
        'prefix' => $name,
        'domain' => "{$name}.services.local",
        'port' => $port,
        'whitelistIp' => [$ip],
        'middleware' => 'service-mesh',
    ], function($router) {
        $router->get('/health', 'HealthController@check');
        $router->get('/metrics', 'MetricsController@show');
    });
});

// Использование
Route::microservice('users', 8081, '10.0.0.1');
Route::microservice('orders', 8082, '10.0.0.2');
Route::microservice('payments', 8083, '10.0.0.3');
```

### Macro pour les modules

```php
RouteMacros::register('module', function($router, $moduleName, $controller) {
    $router->group([
        'prefix' => "modules/{$moduleName}",
        'namespace' => "Modules\\{$moduleName}",
        'middleware' => 'module-loader',
        'tag' => "module-{$moduleName}",
    ], function($router) use ($controller) {
        $router->get('/', "{$controller}@index");
        $router->get('/settings', "{$controller}@settings");
    });
});

// Использование
Route::module('Blog', 'BlogController');
Route::module('Shop', 'ShopController');
Route::module('Forum', 'ForumController');
```

## 📊 Économies de codes

### Exemple : CRUD pour 5 ressources

**Sans macros :**
```php
// 175 строк кода (35 строк × 5 ресурсов)
```

**Avec macros :**
```php
Route::resource('users', 'UserController');
Route::resource('posts', 'PostController');
Route::resource('comments', 'CommentController');
Route::resource('categories', 'CategoryController');
Route::resource('tags', 'TagController');

// 5 строк кода
```

**Économies : 97 % !** (170 lignes contre 5 lignes)

### Exemple : API versionnée

**Sans macros :**
```php
// ~200 строк кода
$router->group(['prefix' => 'api/v1', 'middleware' => 'api'], function($router) {
    $router->get('/users', ...)->name(...)->throttle(...)->tag(...);
    $router->post('/users', ...)->name(...)->throttle(...)->tag(...);
    // ... 20 маршрутов × 5 строк каждый
});

$router->group(['prefix' => 'api/v2', 'middleware' => 'api'], function($router) {
    // ... ещё 20 маршрутов × 5 строк
});
```

**Avec macros :**
```php
// ~20 строк кода
Route::apiVersion('v1', function() {
    Route::apiResource('users', 'Api\V1\UserController', 200);
    Route::apiResource('posts', 'Api\V1\PostController', 200);
    Route::apiResource('comments', 'Api\V1\CommentController', 100);
});

Route::apiVersion('v2', function() {
    Route::apiResource('users', 'Api\V2\UserController', 200);
    Route::apiResource('posts', 'Api\V2\PostController', 200);
});
```

**Économies : 90 % !** (200 lignes contre 20 lignes)

## 🆚 Comparaison avec les concurrents

| Router | Built-in Macros | Resource | API Resource | Custom | Code Reduction |
|:---|:---:|:---:|:---:|:---:|:---:|
| **CloudCastle** | **✅ 7+** | **✅** | **✅** | **✅** | **80-97%** |
| FastRoute | ❌ | ❌ | ❌ | ❌ | 0% |
| Symfony | ⚠️ 2 | ⚠️ | ❌ | ⚠️ | 40% |
| Laravel | ✅ 5 | ✅ | ✅ | ✅ | 70% |
| Slim | ❌ | ❌ | ❌ | ❌ | 0% |
| AltoRouter | ❌ | ❌ | ❌ | ❌ | 0% |

## 💡 Best Practices

### 1. Utilisez resource() pour le CRUD standard

```php
// Для всех ресурсов с CRUD
Route::resource('users', 'UserController');
Route::resource('posts', 'PostController');
Route::resource('products', 'ProductController');
```

### 2. Utilisez apiResource() pour l'API

```php
// RESTful API
Route::apiResource('users', 'Api\UserController', 1000);
Route::apiResource('posts', 'Api\PostController', 500);
```

### 3. Combiner avec le contrôle de version

```php
Route::apiVersion('v1', function() {
    Route::apiResource('users', 'Api\V1\UserController', 200);
    Route::apiResource('posts', 'Api\V1\PostController', 200);
});
```

### 4. Créez des macros personnalisées pour les modèles de projet

```php
// Ваш специфичный паттерн
RouteMacros::register('dashboard', function($router, $name) {
    $router->group(['prefix' => "dashboards/{$name}"], function($router) use ($name) {
        $router->get('/', "Dashboard\\{$name}Controller@index");
        $router->get('/widgets', "Dashboard\\{$name}Controller@widgets");
        $router->get('/reports', "Dashboard\\{$name}Controller@reports");
    });
});

Route::dashboard('Analytics');
Route::dashboard('Sales');
Route::dashboard('Users');
```

## ✅Conclusion

Route Macros est un **outil puissant** pour raccourcir le code :

- ✅ **Réduction de code de 80 à 97 %**
- ✅ **Conformité aux conventions RESTful**
- ✅ **Cohérence**
- ✅ **Pas de fautes de frappe**
- ✅ **Facile à entretenir**

CloudCastle fournit **7 macros intégrées** + la possibilité de créer les vôtres - plus que n'importe quel concurrent !

---

*Dernière mise à jour : 18 octobre 2025*

---

[📚 Table des matières](_table-of-contents.md) | [🏠 Accueil](LISEZMOI.md)

---

[📚 Table of Contents](fr/_table-of-contents.md) | [🏠 Home](fr/README.md)
