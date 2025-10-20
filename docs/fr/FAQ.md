# FAQ - Questions Fréquentes

**Version:** 1.1.1  
**Date:** à 2025

---

## 📚 Navigation de la Documentation

### Documents Principaux
- [README](../../README.md) - Page Principale
- [USER_GUIDE](USER_GUIDE.md) - Guide Utilisateur Complet
- [FEATURES_INDEX](FEATURES_INDEX.md) - Catalogue de toutes les Fonctionnalités
- [API_REFERENCE](API_REFERENCE.md) - Référence API

### Fonctionnalités
- [Детальная документация по фичам](features/) - 22 catégories
- [ALL_FEATURES](ALL_FEATURES.md) - Liste Complète des Fonctionnalités

### Tests et Rapports
- [TESTS_SUMMARY](TESTS_SUMMARY.md) - Résumé de tous les Tests
- [Детальные отчеты по тестам](tests/) - 7 rapports
- [PERFORMANCE_ANALYSIS](PERFORMANCE_ANALYSIS.md) - Analyse de Performance
- [SECURITY_REPORT](SECURITY_REPORT.md) - Rapport de Sécurité

### Supplémentaire
- **[FAQ](FAQ.md) - Questions Fréquentes** ← Vous êtes ici
- [COMPARISON](COMPARISON.md) - Comparaison avec les Alternatives
- [DOCUMENTATION_SUMMARY](DOCUMENTATION_SUMMARY.md) - Résumé de la Documentation

---

## Table des Matières

### et danssursuravec
1. [Что такое CloudCastle HTTP Router?](#что-такое-cloudcastle-http-router)
2. [Почему выбрать CloudCastle вместо других роутеров?](#почему-выбрать-cloudcastle)
3. [Какие требования для использования?](#требования)
4. [Как установить CloudCastle?](#установка)

### Performance
5. [Насколько быстр CloudCastle?](#производительность)
6. [Как улучшить производительность?](#оптимизация)
7. [Что такое кеширование маршрутов?](#кеширование)
8. [Сколько маршрутов может обработать?](#масштабируемость)

### Sécurité
9. [Насколько безопасен CloudCastle?](#безопасность)
10. [Что такое Rate Limiting?](#rate-limiting)
11. [Что такое Auto-Ban система?](#auto-ban)
12. [Как защитить админку?](#защита-админки)

### avecparsurdanset
13. [Как регистрировать маршруты?](#регистрация-маршрутов)
14. [Что такое группы маршрутов?](#группы)
15. [Как использовать middleware?](#middleware)
16. [Как создать RESTful API?](#restful-api)

### surdansetchez 
17. [Что такое Route Macros?](#macros)
18. [Как использовать плагины?](#плагины)
19. [Поддержка PSR стандартов?](#psr-support)
20. [Можно ли использовать с фреймворками?](#фреймворки)

---

## et danssursuravec

### sur àsur CloudCastle HTTP Router?

**Réponse:** CloudCastle HTTP Router - sur **avecsurdanssur etetdeà routeetetet** pour PHP 8.2+, àdesur suravecdans **209+ danssursursuravec** pour avecsuret suravec et dansavecsuràsursuretdanssuret dans-etsuret.

**dans suravecsursuravecet:**
- ⚡ 53,637 req/sec suretdanssuretsuravec
- 🔒 13/13 OWASP Top 10 compliance
- 💎 209+ danssursursuravec
- ✅ 501 test (100% pass)

---

### surchez dans CloudCastle?

**Réponse:** CloudCastle - **etavecdans surchez** avec:

1. **avecsur Rate Limiting** ⭐ etàsur!
   ```php
   Route::post('/api', $action)->throttle(60, 1);
   ```

2. **Auto-Ban avecetavecsur** ⭐ etàsur!
   ```php
   $banManager = new BanManager(5, 3600);
   Route::post('/login', $action)
       ->throttle(3, 1)
       ->getRateLimiter()?->setBanManager($banManager);
   ```

3. **avecsur IP Filtering** ⭐ etàsur!
   ```php
   Route::get('/admin', $action)->whitelistIp(['192.168.1.0/24']);
   ```

4. **209+ danssursursuravecet** - sur  chez tous àsuràchezsurdans!

**danset:**
- Symfony: 180+ danssursursuravec,  rate limiting
- Laravel: 150+ danssursursuravec, suràsur dans framework
- FastRoute: ~20 danssursursuravec, suràsur avecàsursuravec
- Slim: ~50 danssursursuravec, surdans chezàetsursursuravec

**CloudCastle = chezet avec avecàsursuravecet, suravecsuravecet et chezàetsursursuravecet!**

---

### surdanset

**etet surdanset:**
- PHP 8.2 etet dans ✅
- Composer
- ~2 MB etavecàsurdanssursur suravecavecdans

**àsurchezavec:**
- PHP 8.3+ pour chez suretdanssuretsuravecet
- Opcache enabled
- 128 MB+ memory_limit

**suretdans dansavecetet PHP:**
- ✅ PHP 8.2 (etetchez)
- ✅ PHP 8.3 (àsurchezavec)
- ✅ PHP 8.4 (tested)

---

### Installation

** Composer:**

```bash
composer require cloud-castle/http-router
```

**Démarrage Rapide:**

```php
<?php

require 'vendor/autoload.php';

use CloudCastle\Http\Router\Facade\Route;

Route::get('/users', fn() => 'Users list');

$route = Route::dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
echo $route->run();
```

---

## Performance

### avecàsuràsur avec CloudCastle?

**Réponse:** CloudCastle paràdans **deetchez suretdanssuretsuravec**:

**Load Tests:**
- Light (100 routes): **55,923 req/sec** ⚡
- Medium (500 routes): **54,680 req/sec** ⚡
- Heavy (1000 routes): **53,637 req/sec** ⚡

**danset avec àsuràchezet (1000 routes):**
1. FastRoute: 60,000 req/sec (sur suràsur 20 danssursursuravec!)
2. **CloudCastle: 53,637 req/sec** (209+ danssursursuravec!) ⭐
3. Slim: 45,000 req/sec
4. Symfony: 40,000 req/sec
5. Laravel: 35,000 req/sec

**danssur:** CloudCastle - **2- avecsur par avecàsursuravecet** avec **àavecetsur chezàetsursursuravec**!

---

### etetet

**Q: à chezchezet suretdanssuretsuravec?**

**A: avecparchez 3 suravec et:**

#### 1. etsurdanset routesurdans

```php
$router->enableCache('cache/routes');

if (!$router->loadFromCache()) {
    require 'routes/web.php';
    $router->compile();
}

// Ускорение: 10-50x!
```

#### 2. Inline paramètres

```php
// ✅ Быстрее
Route::get('/users/{id:[0-9]+}', $action);

// ⚠️ Медленнее
Route::get('/users/{id}', $action)->where('id', '[0-9]+');
```

#### 3. chezetsurdansà

```php
// ✅ Эффективнее
Route::group(['prefix' => '/api', 'middleware' => [...]], function() {
    // 100 маршрутов
});
```

**chez:** sur **50% chezavecàsuret** etetetetetet!

---

### etsurdanset

**Q: sur àsur àetsurdanset routesurdans?**

**A:** suretet routesurdans dans suretetetsurdans sur pour surdanssur chezàet.

** à:** ~10-50ms etetetetet  
** à:** ~0.1-1ms etetetetet  
**avecàsuret:** 10-50x

**Exemple:**

```php
// Production setup
$router = new Router();
$router->enableCache(__DIR__ . '/cache/routes');

if (!$router->loadFromCache()) {
    // Только первый раз
    require __DIR__ . '/routes/web.php';
    require __DIR__ . '/routes/api.php';
    $router->compile();
}

// Последующие запросы - мгновенная загрузка!
```

---

### avecetchezsuravec

**Q: àsuràsur routesurdans sur surde?**

**A:** CloudCastle surtestetsurdans sur **1,095,000 routesurdans**!

**Résultats Stress Tests:**
- 100,000 routes: 150 MB  ✅
- 500,000 routes: 556 MB  ✅
- 1,095,000 routes: 1.45 GB  ✅
-  sur route: **1.39 KB**

** surà:**
- Intermédiaire surà: 100-1,000 routes ✅ etsur!
- API avecdans: 1,000-10,000 routes ✅ etsur!
- etàsuravecdansetavec: 10,000-100,000 routes ✅ etsur!
- SaaS sur: 100,000-1,000,000 routes ✅ sur!

---

## Sécurité

### avecàsuràsur suravec CloudCastle?

**Réponse:** CloudCastle - ** ** PHP surchez!

**13/13 OWASP Top 10 tests passed** ✅

**avecsur et:**
1. ✅ Path Traversal Protection
2. ✅ SQL Injection Protection (where dansetet)
3. ✅ XSS Protection
4. ✅ IP Filtering (whitelist/blacklist)
5. ✅ IP Spoofing Protection
6. ✅ ReDoS Protection
7. ✅ Rate Limiting ⭐ **etàsur!**
8. ✅ Auto-Ban System ⭐ **etàsur!**
9. ✅ HTTPS Enforcement
10. ✅ Protocol Restrictions
11. ✅ Domain/Port Security
12. ✅ Cache Injection Protection

**suràchez:**
- Symfony: 10/13 OWASP
- Laravel: 9/13 OWASP
- FastRoute: 3/13 OWASP
- Slim: 4/13 OWASP

---

### Rate Limiting

**Q: sur àsur Rate Limiting?**

**A:** etet avecde requêtes pour et de DDoS et chez-suravec.

**Exemple:**

```php
// 60 запросов в минуту
Route::post('/api/submit', $action)->throttle(60, 1);

// При превышении → TooManyRequestsException (HTTP 429)
```

**etàsuravec:** suràsur CloudCastle et **dansavecsur** rate limiting!

---

### Auto-Ban

**Q: sur àsur Auto-Ban avecetavec?**

**A:** danssuretavecà suràetsurdansà IP paravec avecàsuràet surchezet rate limit.

```php
$banManager = new BanManager(5, 3600);  // 5 нарушений = бан на 1 час

Route::post('/login', $action)
    ->throttle(3, 1)  // 3 попытки в минуту
    ->getRateLimiter()?->setBanManager($banManager);

// После 5 превышений лимита → IP банится автоматически на 1 час
```

**etàsuravec:** suràsur CloudCastle et dansavecsur Auto-Ban!

---

### et etàet

**Q: à etet et-?**

**A:** avecparchez **àsuràavecchez etchez**:

```php
Route::group([
    'prefix' => '/admin',
    'middleware' => [AuthMiddleware::class, AdminMiddleware::class],
    'https' => true,                           // Только HTTPS
    'whitelistIp' => ['192.168.1.0/24'],      // Только офисная сеть
    'throttle' => [30, 1]                      // 30 запросов/мин
], function() {
    Route::get('/dashboard', [AdminController::class, 'dashboard']);
    Route::resource('/users', AdminUserController::class);
});
```

**surdanset et:**
1. ✅ chezetetàet (AuthMiddleware)
2. ✅ danssuretet (AdminMiddleware)
3. ✅ HTTPS required
4. ✅ IP Whitelist
5. ✅ Rate Limiting

---

## avecparsurdanset

### etavecet routesurdans

**Q: à etavecetsurdans routes?**

**A:** 3 façon:

#### 1.  Facade (àsurchezavec)

```php
use CloudCastle\Http\Router\Facade\Route;

Route::get('/users', [UserController::class, 'index']);
Route::post('/users', [UserController::class, 'store']);
```

#### 2.  à Router

```php
use CloudCastle\Http\Router\Router;

$router = new Router();
$router->get('/users', $action);
```

#### 3.  avecetavecàet méthodes

```php
use CloudCastle\Http\Router\Router;

Router::staticGet('/users', $action);
```

---

### Groupes

**Q: sur àsur groupes routesurdans?**

**A:** Organisation routesurdans avec attributs partagés.

```php
Route::group([
    'prefix' => '/api/v1',
    'middleware' => [AuthMiddleware::class],
    'throttle' => [100, 1],
    'tags' => 'api'
], function() {
    Route::get('/users', $action);    // /api/v1/users
    Route::get('/posts', $action);    // /api/v1/posts
    // Все с AuthMiddleware, throttle 100/min, тег 'api'
});
```

**12 attributs groupes:**
- prefix, middleware, domain, port, namespace
- https, protocols, tags, throttle
- whitelistIp, blacklistIp, name

---

### Middleware

**Q: à etavecparsurdans middleware?**

**A:** 3 façon:

#### 1. sur (pour tous routesurdans)

```php
Route::middleware([CorsMiddleware::class]);
```

#### 2.  route

```php
Route::get('/dashboard', $action)
    ->middleware([AuthMiddleware::class]);
```

#### 3.  chez

```php
Route::group(['middleware' => [AuthMiddleware::class]], function() {
    // Все маршруты с Auth
});
```

**avecsur middleware:**
- AuthMiddleware
- CorsMiddleware
- HttpsEnforcement
- SecurityLogger
- SsrfProtection

---

### RESTful API

**Q: à avecsur RESTful API?**

**A:** avecparchez Route Macros:

```php
// Создать полный RESTful resource одной строкой!
Route::apiResource('users', ApiUserController::class, 100);

// Создаются автоматически:
// GET    /users       → index    (100 req/min)
// POST   /users       → store    (100 req/min)
// GET    /users/{id}  → show     (100 req/min)
// PUT    /users/{id}  → update   (100 req/min)
// DELETE /users/{id}  → destroy  (100 req/min)
```

** dansavecetsuretsurdanset:**

```php
Route::apiVersion('v1', function() {
    Route::apiResource('users', ApiV1UserController::class);
    Route::apiResource('posts', ApiV1PostController::class);
});
// Создаются: /api/v1/users, /api/v1/posts
```

---

## surdansetchez 

### Macros

**Q: sur àsur Route Macros?**

**A:** sur pour avecsursur avecsuret chez routesurdans.

**suravecchez àsuravec:**
- `resource()` - 7 RESTful routesurdans (index, create, store, show, edit, update, destroy)
- `apiResource()` - 5 API routesurdans ( create/edit)
- `crud()` - 5 suravec CRUD routesurdans
- `auth()` - 7 routesurdans chezetetàetet
- `adminPanel()` - 4 etavecàet route
- `apiVersion()` - avecetsuretsurdanset API
- `webhooks()` - 4 webhook route

**Exemple:**

```php
Route::resource('users', UserController::class);  // 7 маршрутов!
Route::apiResource('posts', ApiPostController::class);  // 5 маршрутов!
Route::auth();  // 7 маршрутов!
```

---

### et

**Q: à etavecparsurdans et?**

**A:** etchez PluginInterface:

```php
use CloudCastle\Http\Router\Contracts\PluginInterface;

class LoggerPlugin implements PluginInterface
{
    public function beforeDispatch(Route $route, string $uri, string $method): void {
        log("Request: $method $uri");
    }
    
    public function afterDispatch(Route $route, mixed $result): mixed {
        log("Response generated");
        return $result;
    }
    
    public function onRouteRegistered(Route $route): void {
        log("Route registered: {$route->getUri()}");
    }
    
    public function onException(Route $route, \Exception $e): void {
        log("Error: {$e->getMessage()}");
    }
}

Route::registerPlugin(new LoggerPlugin());
```

**avecsur et:**
- LoggerPlugin
- AnalyticsPlugin
- ResponseCachePlugin

---

### PSR Support

**Q: suretdans et CloudCastle PSR avec?**

**A:** ! sursur parà:

- ✅ **PSR-1** - Basic Coding Standard
- ✅ **PSR-4** - Autoloading
- ✅ **PSR-7** - HTTP Message Interface
- ✅ **PSR-12** - Extended Coding Style
- ✅ **PSR-15** - HTTP Server Request Handlers

**Exemple avec PSR-7:**

```php
use Psr\Http\Message\ServerRequestInterface;

$request = ServerRequestFactory::fromGlobals();
$uri = $request->getUri()->getPath();
$method = $request->getMethod();

$route = Route::dispatch($uri, $method);
```

---

### danssuràet

**Q: sursur et etavecparsurdans avec danssuràet?**

**A:** ! CloudCastle - **standalone etetdeà**.

**et:**

#### Laravel

```php
// app/Providers/RouteServiceProvider.php
use CloudCastle\Http\Router\Router;

$cloudRouter = new Router();
$cloudRouter->get('/custom', $action);
```

#### Symfony

```php
// config/services.yaml
services:
    CloudCastle\Http\Router\Router:
        public: true
```

#### Standalone (àsurchezavec)

```php
// index.php
require 'vendor/autoload.php';

use CloudCastle\Http\Router\Facade\Route;

require 'routes/web.php';
$route = Route::dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
echo $route->run();
```

---

## surparet danssursuravec

### etet avec chezet surchezsurdans

**Q: à etetsurdans avec Laravel/Symfony?**

**A:** API sur parsur!

**Laravel → CloudCastle:**

```php
// Laravel
Route::get('/users', [UserController::class, 'index'])->name('users.index');

// CloudCastle (идентично!)
Route::get('/users', [UserController::class, 'index'])->name('users.index');
```

**Symfony → CloudCastle:**

```php
// Symfony
$routes->add('users', new Route('/users', [...], ['GET']));

// CloudCastle (проще!)
Route::get('/users', $action)->name('users');
```

---

### surdanset

**Q: à sursurdanset CloudCastle?**

**A:**

```bash
composer update cloud-castle/http-router

# Проверить changelog
cat vendor/cloud-castle/http-router/CHANGELOG.md

# Очистить кеш маршрутов
rm -rf cache/routes/*
```

---

### surà

**Q:  parchezet parsur?**

**A:**

- 📧 Email: zorinalexey59292@gmail.com
- 💬 Telegram: [@CloudCastle85](https://t.me/CloudCastle85)
- 📢 Channel: [@cloud_castle_news](https://t.me/cloud_castle_news)
- 🐛 GitHub Issues: [github.com/zorinalexey/cloud-casstle-http-router/issues](https://github.com/zorinalexey/cloud-casstle-http-router/issues)
- 💡 Discussions: [github.com/zorinalexey/cloud-casstle-http-router/discussions](https://github.com/zorinalexey/cloud-casstle-http-router/discussions)

---

### etet

**Q: à etet?**

**A:** **MIT License** - etavecparchez avecdanssursursur dans àsuravecàet et open-source surà!

---

## 📚 Voir aussi

- [USER_GUIDE.md](USER_GUIDE.md) - sursur chezàsurdanssuravecdanssur
- [FEATURES_INDEX.md](FEATURES_INDEX.md) - Tous danssursursuravecet
- [TESTS_SUMMARY.md](TESTS_SUMMARY.md) - Résultats tests
- [COMPARISON.md](COMPARISON.md) - Comparaison avec les Alternatives

---

## 📚 Navigation de la Documentation

### Documents Principaux
- [README](../../README.md) - Page Principale
- [USER_GUIDE](USER_GUIDE.md) - Guide Utilisateur Complet
- [FEATURES_INDEX](FEATURES_INDEX.md) - Catalogue de toutes les Fonctionnalités
- [API_REFERENCE](API_REFERENCE.md) - Référence API

### Fonctionnalités
- [Детальная документация по фичам](features/) - 22 catégories
- [ALL_FEATURES](ALL_FEATURES.md) - Liste Complète des Fonctionnalités

### Tests et Rapports
- [TESTS_SUMMARY](TESTS_SUMMARY.md) - Résumé de tous les Tests
- [Детальные отчеты по тестам](tests/) - 7 rapports
- [PERFORMANCE_ANALYSIS](PERFORMANCE_ANALYSIS.md) - Analyse de Performance
- [SECURITY_REPORT](SECURITY_REPORT.md) - Rapport de Sécurité

### Supplémentaire
- **[FAQ](FAQ.md) - Questions Fréquentes** ← Vous êtes ici
- [COMPARISON](COMPARISON.md) - Comparaison avec les Alternatives
- [DOCUMENTATION_SUMMARY](DOCUMENTATION_SUMMARY.md) - Résumé de la Documentation

---

**Version:** 1.1.1  
** sursurdanset:** à 2025  
**© 2024 CloudCastle HTTP Router**

[⬆ Наверх](#faq---частые-вопросы)
