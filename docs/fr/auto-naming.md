[🇷🇺 Русский](ru/auto-naming.md) | [🇺🇸 English](en/auto-naming.md) | [🇩🇪 Deutsch](de/auto-naming.md) | [🇫🇷 Français](fr/auto-naming.md) | [🇨🇳 中文](zh/auto-naming.md)

[📚 Table of Contents](fr/_table-of-contents.md) | [🏠 Home](fr/README.md)

---

# Auto-Naming - Dénomination automatique des itinéraires

**Langues :** 🇷🇺 Russe | [🇫🇷 Anglais](../en/auto-naming.md) | [🇩🇪 Deutsch](../de/auto-naming.md) | [🇫🇷 Français](../fr/auto-naming.md) | [🇨🇳中文](../zh/auto-naming.md)

[📚 Table des matières](_table-of-contents.md) | [🏠 Accueil](LISEZMOI.md)

---

## 📚 Bilan

**Auto-Naming** est une fonctionnalité unique du routeur HTTP CloudCastle qui génère automatiquement des noms pour les routes en fonction de leur URI et de leur méthode HTTP.

## 🎯 Pourquoi avez-vous besoin d'un nom automatique ?

### Problème sans dénomination automatique

```php
// Нужно вручную именовать каждый маршрут
$router->get('/users', 'UserController@index')->name('users.index');
$router->get('/users/{id}', 'UserController@show')->name('users.show');
$router->post('/users', 'UserController@store')->name('users.store');
$router->put('/users/{id}', 'UserController@update')->name('users.update');
$router->delete('/users/{id}', 'UserController@destroy')->name('users.destroy');

// 100+ маршрутов = 100+ name() вызовов вручную!
// Риск ошибок, опечаток, дублирования
```

### Solution de dénomination automatique

```php
// Включаем auto-naming
$router->enableAutoNaming();

// Маршруты именуются автоматически!
$router->get('/users', 'UserController@index');
// Auto name: users.get

$router->get('/users/{id}', 'UserController@show');
// Auto name: users.id.get

$router->post('/users', 'UserController@store');
// Auto name: users.post

// 100+ маршрутов = 0 name() вызовов!
```

## 🔧 Utilisation

### Allumer/éteindre

```php
use CloudCastle\Http\Router\Router;

$router = new Router();

// Включить
$router->enableAutoNaming();

// Проверить статус
if ($router->isAutoNamingEnabled()) {
    echo "Auto-naming enabled";
}

// Выключить
$router->disableAutoNaming();
```

### Fluent interface

```php
$router->enableAutoNaming()
    ->get('/users', 'UserController@index')
    ->get('/posts', 'PostController@index');
```

## 📋 Règles de génération de noms

### 1. Itinéraires simples

```php
$router->enableAutoNaming();

$router->get('/users', fn() => 'users');
// Name: users.get

$router->post('/users', fn() => 'create');
// Name: users.post

$router->get('/posts', fn() => 'posts');
// Name: posts.get
```

**Règle** : `{path}.{method}` (minuscule)

### 2. Itinéraires avec paramètres

```php
$router->get('/users/{id}', fn($id) => $id);
// Name: users.id.get

$router->get('/users/{id}/posts', fn($id) => $id);
// Name: users.id.posts.get

$router->get('/users/{id}/posts/{post}', fn($id, $post) => $id);
// Name: users.id.posts.post.get
```

**Règle** : Paramètres `{id}` → parties du nom `.id.`

### 3. Chemins imbriqués

```php
$router->get('/admin/dashboard', fn() => 'dashboard');
// Name: admin.dashboard.get

$router->get('/api/v1/users', fn() => 'users');
// Name: api.v1.users.get

$router->get('/blog/posts/archive', fn() => 'archive');
// Name: blog.posts.archive.get
```

**Règle** : Barres obliques `/` → points `.`

### 4. Caractères spéciaux

```php
$router->get('/api-v1/user_profile', fn() => 'profile');
// Name: api.v1.user.profile.get

$router->get('/some-route_with-both', fn() => 'test');
// Name: some.route.with.both.get
```

**Règle** : traits d'union `-` et traits de soulignement `_` → points `.`

### 5. Route racine

```php
$router->get('/', fn() => 'home');
// Name: root.get
```

**Règle** : `/` → `root`

### 6. Plusieurs méthodes

```php
$router->match(['GET', 'POST'], '/form', fn() => 'form');
// Name: form.get.post
```

**Règle** : Les méthodes sont combinées à l'aide de `.`

### 7. Regex constraints

```php
$router->get('/users/{id:\d+}', fn($id) => $id);
// Name: users.id.get (regex игнорируется)

$router->get('/posts/{slug:[a-z-]+}', fn($slug) => $slug);
// Name: posts.slug.get (regex игнорируется)
```

**Règle** : Les modèles Regex sont supprimés du nom

## 🔄 Priorité du nom

### La dénomination automatique ne remplace PAS les noms explicites

```php
$router->enableAutoNaming();

// Явное имя имеет приоритет
$router->get('/custom', fn() => 'custom')
    ->name('my.custom.name');

$route = $router->getRoute('my.custom.name'); // OK
$route = $router->getRoute('custom.get'); // null
```

**Règle** : Si `name()` est appelé explicitement, la dénomination automatique est ignorée

## 📊 Exemples d'utilisation

### REST API

```php
$router->enableAutoNaming();

// users resource
$router->get('/api/users', 'UserController@index');
// Name: api.users.get

$router->post('/api/users', 'UserController@store');  
// Name: api.users.post

$router->get('/api/users/{id}', 'UserController@show');
// Name: api.users.id.get

$router->put('/api/users/{id}', 'UserController@update');
// Name: api.users.id.put

$router->delete('/api/users/{id}', 'UserController@destroy');
// Name: api.users.id.delete

// posts resource
$router->get('/api/posts', 'PostController@index');
// Name: api.posts.get

$router->get('/api/posts/{slug}', 'PostController@show');
// Name: api.posts.slug.get
```

###API versionnée

```php
$router->enableAutoNaming();

// API v1
$router->get('/api/v1/users', 'Api\V1\UserController@index');
// Name: api.v1.users.get

$router->get('/api/v1/posts', 'Api\V1\PostController@index');
// Name: api.v1.posts.get

// API v2
$router->get('/api/v2/users', 'Api\V2\UserController@index');
// Name: api.v2.users.get

$router->get('/api/v2/posts', 'Api\V2\PostController@index');
// Name: api.v2.posts.get

// Легко различать версии!
```

###Panneau d'administration

```php
$router->enableAutoNaming();

$router->group(['prefix' => 'admin/dashboard'], function($router) {
    $router->get('/stats', 'Admin\StatsController@index');
    // Name: admin.dashboard.stats.get
    
    $router->get('/users', 'Admin\UserController@index');
    // Name: admin.dashboard.users.get
    
    $router->get('/settings', 'Admin\SettingsController@index');
    // Name: admin.dashboard.settings.get
});
```

### Avec le générateur d'URL

```php
use CloudCastle\Http\Router\UrlGenerator;

$router->enableAutoNaming();

$router->get('/users/{id}/posts/{post}', 'PostController@show');

$generator = new UrlGenerator($router);

// Используем auto-generated имя
$url = $generator->generate('users.id.posts.post.get', [
    'id' => 123,
    'post' => 456
]);
// /users/123/posts/456
```

## 💡 Best Practices

### 1. Activer la dénomination automatique globalement

```php
// В начале приложения
$router = new Router();
$router->enableAutoNaming();

// Все маршруты автоматически именуются
require __DIR__ . '/routes/web.php';
require __DIR__ . '/routes/api.php';
```

### 2. Utilisez des noms explicites pour les itinéraires importants

```php
$router->enableAutoNaming();

// Auto-naming для обычных маршрутов
$router->get('/users', 'UserController@index');
// Name: users.get

// Явное имя для важных/публичных маршрутов
$router->get('/checkout', 'CheckoutController@index')
    ->name('checkout'); // Лучше явное имя

$router->post('/payment/process', 'PaymentController@process')
    ->name('payment.process'); // Точный контроль
```

### 3. Structurez les URI pour les noms conviviaux

```php
// ХОРОШО: иерархическая структура
$router->get('/admin/users/list', ...);
// Name: admin.users.list.get - понятно!

// ПЛОХО: плоская структура
$router->get('/adminuserslist', ...);
// Name: adminuserslist.get - непонятно
```

### 4. Utilisez des préfixes dans les groupes

```php
$router->group(['prefix' => 'api/v1'], function($router) {
    $router->get('/users', ...);
    // Name: api.v1.users.get - отлично!
    
    $router->get('/posts', ...);
    // Name: api.v1.posts.get - понятная структура!
});
```

## 📊 Statistiques et tests

### Tests

La dénomination automatique est couverte par **18 tests unitaires** :

- ✅ Allumer/éteindre
- ✅ Itinéraires simples
- ✅ Itinéraires paramétrés
- ✅ Chemins imbriqués
- ✅ Différentes méthodes HTTP
- ✅ Itinéraire racine
- ✅ Caractères spéciaux
- ✅ Groupes avec préfixes
- ✅ Priorité des noms explicites
- ✅ Plusieurs méthodes
- ✅ Fluent interface

**Tous les tests ont été réussis ✅**

### Exemples de tests

```php
public function testAutoNamingWithSimpleRoute(): void
{
    $this->router->enableAutoNaming();
    $route = $this->router->get('/users', fn() => 'users');
    
    $this->assertEquals('users.get', $route->getName());
}

public function testAutoNamingDoesNotOverrideExplicitName(): void
{
    $this->router->enableAutoNaming();
    $route = $this->router->get('/test', fn() => 'test')
        ->name('custom.name');
    
    $this->assertEquals('custom.name', $route->getName());
}
```

## 🆚 Comparaison avec les concurrents

| Router | Auto-Naming | Naming Convention | Override |
|:---|:---:|:---:|:---:|
| **CloudCastle** | **✅ Full** | **Smart** | **✅** |
| FastRoute | ❌ | - | - |
| Symfony | ⚠️ Partial | Manual | ⚠️ |
| Laravel | ⚠️ Partial | Manual | ⚠️ |
| Slim | ❌ | - | - |
| AltoRouter | ❌ | - | - |

**Seul CloudCastle propose une dénomination automatique complète avec génération de noms intelligente !**

## ✅ Avantages de la dénomination automatique

1. **Gain de temps**
   - Pas besoin de trouver des noms
   - Pas besoin de taper `->name()` plus de 100 fois

2. **Cohérence**
   - Règle de dénomination uniforme
   - Pas de fautes de frappe
   - Pas de double emploi

3. **Prévisibilité**
   - Le nom est facile à deviner à partir de l'URI
   - `/api/users/{id}` → `api.users.id.get`

4. **Sécurité de la refactorisation**
   - Modification de l'URI → le nom changera automatiquement
   - Pas de liens brisés

5. **Compatibilité**
   - Fonctionne avec les macros
   - Fonctionne avec des groupes
   - Fonctionne avec les chargeurs (YAML/XML/JSON)

## 💡 Quand l'utiliser

### ✅ Utilisez la dénomination automatique si :

- Un grand nombre d'itinéraires (50+)
- Structure d'URI standard
- Besoin de cohérence
- Vous voulez gagner du temps

### ⚠️ N'utilisez pas la dénomination automatique si :

- Besoin de noms personnalisés (par exemple, pour une compatibilité existante)
- Exigences de dénomination spécifiques
- API publique avec garanties de compatibilité ascendante

### ✅ Approche hybride (recommandée) :

```php
$router->enableAutoNaming();

// 90% маршрутов - auto-naming
$router->get('/users', 'UserController@index');
$router->get('/posts', 'PostController@index');
// ... hundreds of routes

// 10% важных маршрутов - явные имена
$router->get('/checkout', 'CheckoutController@index')
    ->name('checkout'); // публичное API

$router->post('/payment', 'PaymentController@process')
    ->name('payment.process'); // важный endpoint
```

## 📈 Exemples de noms générés

| URI | Method | Auto-Generated Name |
|:---|:---:|:---:|
| `/` | GET | `root.get` |
| `/users` | GET | `users.get` |
| `/users/{id}` | GET | `users.id.get` |
| `/api/v1/users` | GET | `api.v1.users.get` |
| `/api/v1/users/{id}` | POST | `api.v1.users.id.post` |
| `/admin/dashboard/stats` | GET | `admin.dashboard.stats.get` |
| `/users/{id}/posts/{post}` | GET | `users.id.posts.post.get` |
| `/api-v2/user_profile` | GET | `api.v2.user.profile.get` |

## ✅Conclusion

La dénomination automatique est une **fonctionnalité unique de CloudCastle** qui :

- ✅ **Gain de temps** - pas besoin de nommer manuellement
- ✅ **Assure la cohérence** - une règle
- ✅ **Empêche les erreurs** - pas de fautes de frappe dans les noms
- ✅ **Facilite le refactoring** - les noms sont mis à jour automatiquement
- ✅ **Améliore la lisibilité** - noms prévisibles

**Aucun autre routeur PHP n'offre cette fonctionnalité !**

---

*Dernière mise à jour : 18 octobre 2025*

---

[📚 Table des matières](_table-of-contents.md) | [🏠 Accueil](LISEZMOI.md)

---

[📚 Table of Contents](fr/_table-of-contents.md) | [🏠 Home](fr/README.md)
