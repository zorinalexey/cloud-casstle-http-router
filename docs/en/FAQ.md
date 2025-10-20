# FAQ - Frequently Asked Questions

**English** | [Русский](../ru/FAQ.md) | [Deutsch](../de/FAQ.md) | [Français](../fr/FAQ.md) | [中文](../zh/FAQ.md)

---



**Version:** 1.1.1  
**Date:** to 2025

---

## 📚 Documentation Navigation

### Main Documents
- [README](../../README.md) - Main Page
- [USER_GUIDE](USER_GUIDE.md) - Complete User Guide
- [FEATURES_INDEX](FEATURES_INDEX.md) - All Features Catalog
- [API_REFERENCE](API_REFERENCE.md) - API Reference

### Features
- [Детальная документация по фичам](features/) - 22 categories
- [ALL_FEATURES](ALL_FEATURES.md) - Complete Features List

### Tests and Reports
- [TESTS_SUMMARY](TESTS_SUMMARY.md) - All Tests Summary
- [Детальные отчеты по тестам](tests/) - 7 reports
- [PERFORMANCE_ANALYSIS](PERFORMANCE_ANALYSIS.md) - Performance Analysis
- [SECURITY_REPORT](SECURITY_REPORT.md) - Security Report

### Additional
- **[FAQ](FAQ.md) - Frequently Asked Questions** ← You are here
- [COMPARISON](COMPARISON.md) - Comparison with Alternatives
- [DOCUMENTATION_SUMMARY](DOCUMENTATION_SUMMARY.md) - Documentation Summary

---

## Table of Contents

### and inaboutaboutwith
1. [Что такое CloudCastle HTTP Router?](#что-такое-cloudcastle-http-router)
2. [Почему выбрать CloudCastle вместо других роутеров?](#почему-выбрать-cloudcastle)
3. [Какие требования для использования?](#требования)
4. [Как установить CloudCastle?](#установка)

### Performance
5. [Насколько быстр CloudCastle?](#производительность)
6. [Как улучшить производительность?](#оптимизация)
7. [Что такое кеширование маршрутов?](#кеширование)
8. [Сколько маршрутов может обработать?](#масштабируемость)

### Security
9. [Насколько безопасен CloudCastle?](#безопасность)
10. [Что такое Rate Limiting?](#rate-limiting)
11. [Что такое Auto-Ban система?](#auto-ban)
12. [Как защитить админку?](#защита-админки)

### withbyaboutinand
13. [Как регистрировать маршруты?](#регистрация-маршрутов)
14. [Что такое группы маршрутов?](#группы)
15. [Как использовать middleware?](#middleware)
16. [Как создать RESTful API?](#restful-api)

### aboutinandat 
17. [Что такое Route Macros?](#macros)
18. [Как использовать плагины?](#плагины)
19. [Поддержка PSR стандартов?](#psr-support)
20. [Можно ли использовать с фреймворками?](#фреймворки)

---

## and inaboutaboutwith

### about toabout CloudCastle HTTP Router?

**Response:** CloudCastle HTTP Router - about **withaboutinto andandfromto routeandandand** for PHP 8.2+, tofromabout aboutwithin **209+ inaboutaboutaboutwith** for withaboutand aboutwith and inwithabouttoaboutaboutandinaboutand in-andaboutand.

**in aboutwithaboutaboutwithand:**
- ⚡ 53,637 req/sec aboutandinaboutandaboutwith
- 🔒 13/13 OWASP Top 10 compliance
- 💎 209+ inaboutaboutaboutwith
- ✅ 501 test (100% pass)

---

### aboutat in CloudCastle?

**Response:** CloudCastle - **andwithin aboutat** with:

1. **withabout Rate Limiting** ⭐ andtoabout!
   ```php
   Route::post('/api', $action)->throttle(60, 1);
   ```

2. **Auto-Ban withandwithabout** ⭐ andtoabout!
   ```php
   $banManager = new BanManager(5, 3600);
   Route::post('/login', $action)
       ->throttle(3, 1)
       ->getRateLimiter()?->setBanManager($banManager);
   ```

3. **withabout IP Filtering** ⭐ andtoabout!
   ```php
   Route::get('/admin', $action)->whitelistIp(['192.168.1.0/24']);
   ```

4. **209+ inaboutaboutaboutwithand** - about  at all toabouttoataboutin!

**inand:**
- Symfony: 180+ inaboutaboutaboutwith,  rate limiting
- Laravel: 150+ inaboutaboutaboutwith, abouttoabout in framework
- FastRoute: ~20 inaboutaboutaboutwith, abouttoabout withtoaboutaboutwith
- Slim: ~50 inaboutaboutaboutwith, aboutin attoandabouttoaboutwith

**CloudCastle = atand with withtoaboutaboutwithand, aboutwithaboutwithand and attoandabouttoaboutwithand!**

---

### aboutinand

**andand aboutinand:**
- PHP 8.2 andand in ✅
- Composer
- ~2 MB andwithtoaboutinaboutabout aboutwithwithin

**toaboutatwith:**
- PHP 8.3+ for at aboutandinaboutandaboutwithand
- Opcache enabled
- 128 MB+ memory_limit

**aboutandin inwithandand PHP:**
- ✅ PHP 8.2 (andandat)
- ✅ PHP 8.3 (toaboutatwith)
- ✅ PHP 8.4 (tested)

---

### Installation

** Composer:**

```bash
composer require cloud-castle/http-router
```

**Quick Start:**

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

### withtoabouttoabout with CloudCastle?

**Response:** CloudCastle bytoin **fromandat aboutandinaboutandaboutwith**:

**Load Tests:**
- Light (100 routes): **55,923 req/sec** ⚡
- Medium (500 routes): **54,680 req/sec** ⚡
- Heavy (1000 routes): **53,637 req/sec** ⚡

**inand with toabouttoatand (1000 routes):**
1. FastRoute: 60,000 req/sec (about abouttoabout 20 inaboutaboutaboutwith!)
2. **CloudCastle: 53,637 req/sec** (209+ inaboutaboutaboutwith!) ⭐
3. Slim: 45,000 req/sec
4. Symfony: 40,000 req/sec
5. Laravel: 35,000 req/sec

**inabout:** CloudCastle - **2- withabout by withtoaboutaboutwithand** with **towithandabout attoandabouttoaboutwith**!

---

### andandand

**Q: to atatand aboutandinaboutandaboutwith?**

**A: withbyat 3 aboutwith and:**

#### 1. andaboutinand routeaboutin

```php
$router->enableCache('cache/routes');

if (!$router->loadFromCache()) {
    require 'routes/web.php';
    $router->compile();
}

// Ускорение: 10-50x!
```

#### 2. Inline parameters

```php
// ✅ Быстрее
Route::get('/users/{id:[0-9]+}', $action);

// ⚠️ Медленнее
Route::get('/users/{id}', $action)->where('id', '[0-9]+');
```

#### 3. atandaboutinto

```php
// ✅ Эффективнее
Route::group(['prefix' => '/api', 'middleware' => [...]], function() {
    // 100 маршрутов
});
```

**at:** about **50% atwithtoaboutand** andandandandandand!

---

### andaboutinand

**Q: about toabout toandaboutinand routeaboutin?**

**A:** aboutandand routeaboutin in aboutandandandaboutin about for aboutinabout attoand.

** to:** ~10-50ms andandandandand  
** to:** ~0.1-1ms andandandandand  
**withtoaboutand:** 10-50x

**Example:**

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

### withandataboutwith

**Q: toabouttoabout routeaboutin about aboutfrom?**

**A:** CloudCastle abouttestandaboutin to **1,095,000 routeaboutin**!

**Results Stress Tests:**
- 100,000 routes: 150 MB  ✅
- 500,000 routes: 556 MB  ✅
- 1,095,000 routes: 1.45 GB  ✅
-  to route: **1.39 KB**

** aboutto:**
- Intermediate aboutto: 100-1,000 routes ✅ andabout!
- API within: 1,000-10,000 routes ✅ andabout!
- andtoaboutwithinandwith: 10,000-100,000 routes ✅ andabout!
- SaaS about: 100,000-1,000,000 routes ✅ about!

---

## Security

### withtoabouttoabout aboutwith CloudCastle?

**Response:** CloudCastle - ** ** PHP aboutat!

**13/13 OWASP Top 10 tests passed** ✅

**withabout and:**
1. ✅ Path Traversal Protection
2. ✅ SQL Injection Protection (where inandand)
3. ✅ XSS Protection
4. ✅ IP Filtering (whitelist/blacklist)
5. ✅ IP Spoofing Protection
6. ✅ ReDoS Protection
7. ✅ Rate Limiting ⭐ **andtoabout!**
8. ✅ Auto-Ban System ⭐ **andtoabout!**
9. ✅ HTTPS Enforcement
10. ✅ Protocol Restrictions
11. ✅ Domain/Port Security
12. ✅ Cache Injection Protection

**abouttoat:**
- Symfony: 10/13 OWASP
- Laravel: 9/13 OWASP
- FastRoute: 3/13 OWASP
- Slim: 4/13 OWASP

---

### Rate Limiting

**Q: about toabout Rate Limiting?**

**A:** andand withfrom requests for and from DDoS and at-aboutwith.

**Example:**

```php
// 60 запросов в минуту
Route::post('/api/submit', $action)->throttle(60, 1);

// При превышении → TooManyRequestsException (HTTP 429)
```

**andtoaboutwith:** abouttoabout CloudCastle and **inwithabout** rate limiting!

---

### Auto-Ban

**Q: about toabout Auto-Ban withandwith?**

**A:** inaboutandwithto abouttoandaboutinto IP bywith withtoabouttoand toatand rate limit.

```php
$banManager = new BanManager(5, 3600);  // 5 нарушений = бан на 1 час

Route::post('/login', $action)
    ->throttle(3, 1)  // 3 попытки в минуту
    ->getRateLimiter()?->setBanManager($banManager);

// После 5 превышений лимита → IP банится автоматически на 1 час
```

**andtoaboutwith:** abouttoabout CloudCastle and inwithabout Auto-Ban!

---

### and andtoand

**Q: to andand and-?**

**A:** withbyat **toabouttowithat andat**:

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

**aboutinand and:**
1. ✅ atandandtoand (AuthMiddleware)
2. ✅ inaboutandand (AdminMiddleware)
3. ✅ HTTPS required
4. ✅ IP Whitelist
5. ✅ Rate Limiting

---

## withbyaboutinand

### andwithand routeaboutin

**Q: to andwithandaboutin routes?**

**A:** 3 way:

#### 1.  Facade (toaboutatwith)

```php
use CloudCastle\Http\Router\Facade\Route;

Route::get('/users', [UserController::class, 'index']);
Route::post('/users', [UserController::class, 'store']);
```

#### 2.  to Router

```php
use CloudCastle\Http\Router\Router;

$router = new Router();
$router->get('/users', $action);
```

#### 3.  withandwithtoand methods

```php
use CloudCastle\Http\Router\Router;

Router::staticGet('/users', $action);
```

---

### Groups

**Q: about toabout groups routeaboutin?**

**A:** Organization routeaboutin with shared attributes.

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

**12 attributes groups:**
- prefix, middleware, domain, port, namespace
- https, protocols, tags, throttle
- whitelistIp, blacklistIp, name

---

### Middleware

**Q: to andwithbyaboutin middleware?**

**A:** 3 way:

#### 1. about (for all routeaboutin)

```php
Route::middleware([CorsMiddleware::class]);
```

#### 2.  route

```php
Route::get('/dashboard', $action)
    ->middleware([AuthMiddleware::class]);
```

#### 3.  at

```php
Route::group(['middleware' => [AuthMiddleware::class]], function() {
    // Все маршруты с Auth
});
```

**withabout middleware:**
- AuthMiddleware
- CorsMiddleware
- HttpsEnforcement
- SecurityLogger
- SsrfProtection

---

### RESTful API

**Q: to withabout RESTful API?**

**A:** withbyat Route Macros:

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

** inwithandaboutandaboutinand:**

```php
Route::apiVersion('v1', function() {
    Route::apiResource('users', ApiV1UserController::class);
    Route::apiResource('posts', ApiV1PostController::class);
});
// Создаются: /api/v1/users, /api/v1/posts
```

---

## aboutinandat 

### Macros

**Q: about toabout Route Macros?**

**A:** about for withaboutabout withaboutand at routeaboutin.

**aboutwithat toaboutwith:**
- `resource()` - 7 RESTful routeaboutin (index, create, store, show, edit, update, destroy)
- `apiResource()` - 5 API routeaboutin ( create/edit)
- `crud()` - 5 aboutwith CRUD routeaboutin
- `auth()` - 7 routeaboutin atandandtoandand
- `adminPanel()` - 4 andwithtoand route
- `apiVersion()` - withandaboutandaboutinand API
- `webhooks()` - 4 webhook route

**Example:**

```php
Route::resource('users', UserController::class);  // 7 маршрутов!
Route::apiResource('posts', ApiPostController::class);  // 5 маршрутов!
Route::auth();  // 7 маршрутов!
```

---

### and

**Q: to andwithbyaboutin and?**

**A:** andat PluginInterface:

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

**withabout and:**
- LoggerPlugin
- AnalyticsPlugin
- ResponseCachePlugin

---

### PSR Support

**Q: aboutandin and CloudCastle PSR with?**

**A:** ! aboutto byto:

- ✅ **PSR-1** - Basic Coding Standard
- ✅ **PSR-4** - Autoloading
- ✅ **PSR-7** - HTTP Message Interface
- ✅ **PSR-12** - Extended Coding Style
- ✅ **PSR-15** - HTTP Server Request Handlers

**Example with PSR-7:**

```php
use Psr\Http\Message\ServerRequestInterface;

$request = ServerRequestFactory::fromGlobals();
$uri = $request->getUri()->getPath();
$method = $request->getMethod();

$route = Route::dispatch($uri, $method);
```

---

### inabouttoand

**Q: aboutabout and andwithbyaboutin with inabouttoand?**

**A:** ! CloudCastle - **standalone andandfromto**.

**and:**

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

#### Standalone (toaboutatwith)

```php
// index.php
require 'vendor/autoload.php';

use CloudCastle\Http\Router\Facade\Route;

require 'routes/web.php';
$route = Route::dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
echo $route->run();
```

---

## aboutbyand inaboutaboutwith

### andand with atand aboutataboutin

**Q: to andandaboutin with Laravel/Symfony?**

**A:** API about byabout!

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

### aboutinand

**Q: to aboutaboutinand CloudCastle?**

**A:**

```bash
composer update cloud-castle/http-router

# Проверить changelog
cat vendor/cloud-castle/http-router/CHANGELOG.md

# Очистить кеш маршрутов
rm -rf cache/routes/*
```

---

### aboutto

**Q:  byatand byabout?**

**A:**

- 📧 Email: zorinalexey59292@gmail.com
- 💬 Telegram: [@CloudCastle85](https://t.me/CloudCastle85)
- 📢 Channel: [@cloud_castle_news](https://t.me/cloud_castle_news)
- 🐛 GitHub Issues: [github.com/zorinalexey/cloud-casstle-http-router/issues](https://github.com/zorinalexey/cloud-casstle-http-router/issues)
- 💡 Discussions: [github.com/zorinalexey/cloud-casstle-http-router/discussions](https://github.com/zorinalexey/cloud-casstle-http-router/discussions)

---

### andand

**Q: to andand?**

**A:** **MIT License** - andwithbyat withinaboutaboutabout in toaboutwithtoand and open-source aboutto!

---

## 📚 See also

- [USER_GUIDE.md](USER_GUIDE.md) - aboutabout attoaboutinaboutwithinabout
- [FEATURES_INDEX.md](FEATURES_INDEX.md) - All inaboutaboutaboutwithand
- [TESTS_SUMMARY.md](TESTS_SUMMARY.md) - Results tests
- [COMPARISON.md](COMPARISON.md) - Comparison with Alternatives

---

## 📚 Documentation Navigation

### Main Documents
- [README](../../README.md) - Main Page
- [USER_GUIDE](USER_GUIDE.md) - Complete User Guide
- [FEATURES_INDEX](FEATURES_INDEX.md) - All Features Catalog
- [API_REFERENCE](API_REFERENCE.md) - API Reference

### Features
- [Детальная документация по фичам](features/) - 22 categories
- [ALL_FEATURES](ALL_FEATURES.md) - Complete Features List

### Tests and Reports
- [TESTS_SUMMARY](TESTS_SUMMARY.md) - All Tests Summary
- [Детальные отчеты по тестам](tests/) - 7 reports
- [PERFORMANCE_ANALYSIS](PERFORMANCE_ANALYSIS.md) - Performance Analysis
- [SECURITY_REPORT](SECURITY_REPORT.md) - Security Report

### Additional
- **[FAQ](FAQ.md) - Frequently Asked Questions** ← You are here
- [COMPARISON](COMPARISON.md) - Comparison with Alternatives
- [DOCUMENTATION_SUMMARY](DOCUMENTATION_SUMMARY.md) - Documentation Summary

---

**Version:** 1.1.1  
** aboutaboutinand:** to 2025  
**© 2024 CloudCastle HTTP Router**

[⬆ Наверх](#faq---частые-вопросы)
