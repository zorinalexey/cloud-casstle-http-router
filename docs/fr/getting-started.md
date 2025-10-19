[🇷🇺 Русский](ru/getting-started.md) | [🇺🇸 English](en/getting-started.md) | [🇩🇪 Deutsch](de/getting-started.md) | [🇫🇷 Français](fr/getting-started.md) | [🇨🇳 中文](zh/getting-started.md)

[📚 Table of Contents](fr/_table-of-contents.md) | [🏠 Home](fr/README.md)

---

# Premiers pas avec le routeur HTTP CloudCastle

**Langues :** 🇷🇺 Russe | [🇫🇷 Anglais](../en/getting-started.md) | [🇩🇪 Deutsch](../de/getting-started.md) | [🇫🇷 Français](../fr/getting-started.md) | [🇨🇳中文](../zh/getting-started.md)

[📚 Table des matières](_table-of-contents.md) | [🏠 Accueil](LISEZMOI.md)

---

## 🚀 Démarrage rapide

###Installation

```bash
composer require cloud-castle/http-router
```

### Exemple minimal

```php
<?php

require 'vendor/autoload.php';

use CloudCastle\Http\Router\Router;

$router = new Router();

$router->get('/', function() {
    return 'Hello, World!';
});

$router->get('/users/{id}', function($id) {
    return "User ID: {$id}";
});

// Dispatch
$result = $router->dispatch(
    $_SERVER['REQUEST_URI'],
    $_SERVER['REQUEST_METHOD']
);

echo $result;
```

### .htaccess pour Apache

```apache
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^ index.php [QSA,L]
```

### nginx configuration

```nginx
location / {
    try_files $uri $uri/ /index.php?$query_string;
}
```

## 📖 Concepts de base

### 1. Créer des itinéraires

```php
// HTTP методы
$router->get('/users', 'UserController@index');
$router->post('/users', 'UserController@store');
$router->put('/users/{id}', 'UserController@update');
$router->delete('/users/{id}', 'UserController@destroy');
$router->patch('/users/{id}', 'UserController@patch');

// Множественные методы
$router->match(['GET', 'POST'], '/form', 'FormController@handle');

// Все методы
$router->any('/debug', 'DebugController@all');
```

### 2. Paramètres de l'itinéraire

```php
// Простой параметр
$router->get('/users/{id}', function($id) {
    return "User: {$id}";
});

// Множественные параметры
$router->get('/posts/{year}/{month}/{slug}', function($year, $month, $slug) {
    return "Post: {$year}/{$month}/{$slug}";
});

// С regex constraint
$router->get('/users/{id}', function($id) {
    return "User: {$id}";
})->where('id', '\d+');

// С default значением
$router->get('/page/{num}', function($num) {
    return "Page: {$num}";
})->default('num', 1);
```

### 3. Itinéraires nommés

```php
// Определение
$router->get('/users/{id}', 'UserController@show')
    ->name('users.show');

// Получение маршрута
$route = $router->getRoute('users.show');

// Генерация URL
use CloudCastle\Http\Router\UrlGenerator;

$generator = new UrlGenerator($router);
$url = $generator->generate('users.show', ['id' => 123]);
// /users/123
```

### 4. Groupes de routage

```php
// Группа с префиксом
$router->group(['prefix' => '/api'], function($router) {
    $router->get('/users', 'ApiController@users'); // /api/users
    $router->get('/posts', 'ApiController@posts'); // /api/posts
});

// Группа с middleware
$router->group(['middleware' => 'auth'], function($router) {
    $router->get('/profile', 'ProfileController@show');
    $router->get('/settings', 'SettingsController@index');
});

// Комбинированная группа
$router->group([
    'prefix' => '/admin',
    'middleware' => ['auth', 'admin'],
    'namespace' => 'App\\Admin'
], function($router) {
    $router->get('/dashboard', 'DashboardController@index');
    // URL: /admin/dashboard
    // Controller: App\Admin\DashboardController
    // Middleware: [auth, admin]
});
```

### 5. Middleware

```php
// Глобальный middleware
$router->middleware('cors');

// Middleware маршрута
$router->get('/admin', 'AdminController@index')
    ->middleware(['auth', 'admin']);

// Custom middleware
use CloudCastle\Http\Router\Contracts\MiddlewareInterface;

class LogMiddleware implements MiddlewareInterface
{
    public function handle(mixed $request, callable $next, array $parameters = []): mixed
    {
        error_log("Request: {$request}");
        return $next($request);
    }
}

$router->middleware(new LogMiddleware());
```

## 🎯 Exemples pratiques

### REST API

```php
// CRUD для users
$router->get('/api/users', 'UserController@index')
    ->name('api.users.index')
    ->middleware('cors');

$router->post('/api/users', 'UserController@store')
    ->name('api.users.store')
    ->middleware(['cors', 'auth']);

$router->get('/api/users/{id}', 'UserController@show')
    ->name('api.users.show')
    ->middleware('cors')
    ->where('id', '\d+');

$router->put('/api/users/{id}', 'UserController@update')
    ->name('api.users.update')
    ->middleware(['cors', 'auth'])
    ->where('id', '\d+');

$router->delete('/api/users/{id}', 'UserController@destroy')
    ->name('api.users.destroy')
    ->middleware(['cors', 'auth', 'admin'])
    ->where('id', '\d+');
```

### Admin Panel

```php
$router->group([
    'prefix' => '/admin',
    'middleware' => ['auth', 'admin'],
    'namespace' => 'App\\Admin'
], function($router) {
    $router->get('/dashboard', 'DashboardController@index')
        ->name('admin.dashboard');
    
    $router->get('/users', 'UserController@index')
        ->name('admin.users.index');
    
    $router->get('/settings', 'SettingsController@index')
        ->name('admin.settings');
});
```

### Multi-tenant Application

```php
// Routing по поддоменам
$router->get('/dashboard', 'TenantController@dashboard')
    ->domain('{tenant}.example.com')
    ->middleware('tenant-auth');

// Извлечение tenant из домена
$router->get('/', function() use ($router) {
    $route = $router->current();
    $tenant = $route->getParameter('tenant');
    return "Tenant: {$tenant}";
})->domain('{tenant}.example.com');
```

### Rate Limited API

```php
$router->group(['prefix' => '/api'], function($router) {
    // Public endpoints - строгий лимит
    $router->get('/public', 'ApiController@public')
        ->perMinute(60); // 60 req/min
    
    // Authenticated - больший лимит
    $router->group(['middleware' => 'auth'], function($router) {
        $router->get('/data', 'ApiController@data')
            ->perMinute(1000); // 1000 req/min
    });
});
```

## 🛠️ Fonctionnalités avancées

### YAML Configuration

```php
use CloudCastle\Http\Router\Loader\YamlLoader;

$loader = new YamlLoader($router);
$loader->load(__DIR__ . '/config/routes.yaml');
```

```yaml
# config/routes.yaml
api_users:
  path: /api/users
  methods: GET
  middleware: cors
  throttle: {max: 1000, decay: 60}
```

### Expression Language

```php
$router->get('/premium', 'PremiumController@content')
    ->condition('user.subscription == "premium" and user.age >= 18')
    ->middleware('auth');
```

### Auto-ban System

```php
use CloudCastle\Http\Router\BanManager;

$banManager = new BanManager();
$router->setBanManager($banManager);

$router->enableAutoBan(
    maxAttempts: 100,
    decayMinutes: 60,
    banDuration: 3600
);
```

## 🔍 Debugging

### Route Dumper

```php
use CloudCastle\Http\Router\RouteDumper;

$dumper = new RouteDumper($router);

// CLI table
echo $dumper->dumpTable();

// JSON export
file_put_contents('routes.json', $dumper->dumpJson());
```

### Current Route

```php
// Получить текущий маршрут
$current = $router->current();

echo $current->getName();
echo $current->getUri();
print_r($current->getParameters());
```

## 📚 Prochaines étapes

1. Explorez [liste complète des fonctionnalités] (features.md)
2. Lisez [Bonnes pratiques] (best-practices.md)
3. Découvrez [Système Middleware] (middleware.md)
4. Regardez [exemples](../../exemples/)

## ✅Conclusion

Vous êtes prêt à commencer à utiliser le routeur HTTP CloudCastle ! Commencez par des itinéraires simples et ajoutez progressivement des fonctionnalités supplémentaires si nécessaire.

---

*Dernière mise à jour : 18 octobre 2025*

---

[📚 Table des matières](_table-of-contents.md) | [🏠 Accueil](LISEZMOI.md)

---

[📚 Table of Contents](fr/_table-of-contents.md) | [🏠 Home](fr/README.md)
