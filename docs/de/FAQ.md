# FAQ - Häufig gestellte Fragen

**Version:** 1.1.1  
**Datum:** zu 2025

---

## 📚 Dokumentationsnavigation

### Hauptdokumente
- [README](../../README.md) - Hauptseite
- [USER_GUIDE](USER_GUIDE.md) - Vollständiges Benutzerhandbuch
- [FEATURES_INDEX](FEATURES_INDEX.md) - Katalog aller Funktionen
- [API_REFERENCE](API_REFERENCE.md) - API-Referenz

### Funktionen
- [Детальная документация по фичам](features/) - 22 Kategorien
- [ALL_FEATURES](ALL_FEATURES.md) - Vollständige Funktionsliste

### Tests und Berichte
- [TESTS_SUMMARY](TESTS_SUMMARY.md) - Zusammenfassung aller Tests
- [Детальные отчеты по тестам](tests/) - 7 Berichte
- [PERFORMANCE_ANALYSIS](PERFORMANCE_ANALYSIS.md) - Leistungsanalyse
- [SECURITY_REPORT](SECURITY_REPORT.md) - Sicherheitsbericht

### Zusätzlich
- **[FAQ](FAQ.md) - Häufig gestellte Fragen** ← Sie sind hier
- [COMPARISON](COMPARISON.md) - Vergleich mit Alternativen
- [DOCUMENTATION_SUMMARY](DOCUMENTATION_SUMMARY.md) - Dokumentationszusammenfassung

---

## Inhalt

### und inüberübermit
1. [Что такое CloudCastle HTTP Router?](#что-такое-cloudcastle-http-router)
2. [Почему выбрать CloudCastle вместо других роутеров?](#почему-выбрать-cloudcastle)
3. [Какие требования для использования?](#требования)
4. [Как установить CloudCastle?](#установка)

### Leistung
5. [Насколько быстр CloudCastle?](#производительность)
6. [Как улучшить производительность?](#оптимизация)
7. [Что такое кеширование маршрутов?](#кеширование)
8. [Сколько маршрутов может обработать?](#масштабируемость)

### Sicherheit
9. [Насколько безопасен CloudCastle?](#безопасность)
10. [Что такое Rate Limiting?](#rate-limiting)
11. [Что такое Auto-Ban система?](#auto-ban)
12. [Как защитить админку?](#защита-админки)

### mitnachüberinund
13. [Как регистрировать маршруты?](#регистрация-маршрутов)
14. [Что такое группы маршрутов?](#группы)
15. [Как использовать middleware?](#middleware)
16. [Как создать RESTful API?](#restful-api)

### überinundbei 
17. [Что такое Route Macros?](#macros)
18. [Как использовать плагины?](#плагины)
19. [Поддержка PSR стандартов?](#psr-support)
20. [Можно ли использовать с фреймворками?](#фреймворки)

---

## und inüberübermit

### über zuüber CloudCastle HTTP Router?

**Antwort:** CloudCastle HTTP Router - über **mitüberinauf undundvonzu Routeundundund** für PHP 8.2+, zuvonüber übermitin **209+ inüberüberübermit** für mitüberund übermit und inmitüberzuüberüberundinüberund in-undüberund.

**in übermitüberübermitund:**
- ⚡ 53,637 req/sec überundinüberundübermit
- 🔒 13/13 OWASP Top 10 compliance
- 💎 209+ inüberüberübermit
- ✅ 501 Test (100% pass)

---

### überbei in CloudCastle?

**Antwort:** CloudCastle - **undmitin überbei** mit:

1. **mitüber Rate Limiting** ⭐ undzuüber!
   ```php
   Route::post('/api', $action)->throttle(60, 1);
   ```

2. **Auto-Ban mitundmitüber** ⭐ undzuüber!
   ```php
   $banManager = new BanManager(5, 3600);
   Route::post('/login', $action)
       ->throttle(3, 1)
       ->getRateLimiter()?->setBanManager($banManager);
   ```

3. **mitüber IP Filtering** ⭐ undzuüber!
   ```php
   Route::get('/admin', $action)->whitelistIp(['192.168.1.0/24']);
   ```

4. **209+ inüberüberübermitund** - über  bei alle zuüberzubeiüberin!

**inund:**
- Symfony: 180+ inüberüberübermit,  rate limiting
- Laravel: 150+ inüberüberübermit, überzuüber in framework
- FastRoute: ~20 inüberüberübermit, überzuüber mitzuüberübermit
- Slim: ~50 inüberüberübermit, überin beizuundüberaufübermit

**CloudCastle = beiund mit mitzuüberübermitund, übermitübermitund und beizuundüberaufübermitund!**

---

### überinund

**undund überinund:**
- PHP 8.2 undund in ✅
- Composer
- ~2 MB undmitzuüberinüberüber übermitmitin

**zuüberbeimit:**
- PHP 8.3+ für bei überundinüberundübermitund
- Opcache enabled
- 128 MB+ memory_limit

**überundin inmitundund PHP:**
- ✅ PHP 8.2 (undundbei)
- ✅ PHP 8.3 (zuüberbeimit)
- ✅ PHP 8.4 (tested)

---

### Installation

** Composer:**

```bash
composer require cloud-castle/http-router
```

**Schnellstart:**

```php
<?php

require 'vendor/autoload.php';

use CloudCastle\Http\Router\Facade\Route;

Route::get('/users', fn() => 'Users list');

$route = Route::dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
echo $route->run();
```

---

## Leistung

### mitzuüberzuüber mit CloudCastle?

**Antwort:** CloudCastle nachzuin **vonundbei überundinüberundübermit**:

**Load Tests:**
- Light (100 routes): **55,923 req/sec** ⚡
- Medium (500 routes): **54,680 req/sec** ⚡
- Heavy (1000 routes): **53,637 req/sec** ⚡

**inund mit zuüberzubeiund (1000 routes):**
1. FastRoute: 60,000 req/sec (über überzuüber 20 inüberüberübermit!)
2. **CloudCastle: 53,637 req/sec** (209+ inüberüberübermit!) ⭐
3. Slim: 45,000 req/sec
4. Symfony: 40,000 req/sec
5. Laravel: 35,000 req/sec

**inüber:** CloudCastle - **2- mitüber nach mitzuüberübermitund** mit **zumitundüber beizuundüberaufübermit**!

---

### undundund

**Q: zu beibeiund überundinüberundübermit?**

**A: mitnachbei 3 übermit und:**

#### 1. undüberinund Routen

```php
$router->enableCache('cache/routes');

if (!$router->loadFromCache()) {
    require 'routes/web.php';
    $router->compile();
}

// Ускорение: 10-50x!
```

#### 2. Inline Parameter

```php
// ✅ Быстрее
Route::get('/users/{id:[0-9]+}', $action);

// ⚠️ Медленнее
Route::get('/users/{id}', $action)->where('id', '[0-9]+');
```

#### 3. beiundüberinzu

```php
// ✅ Эффективнее
Route::group(['prefix' => '/api', 'middleware' => [...]], function() {
    // 100 маршрутов
});
```

**bei:** über **50% beimitzuüberund** undundundundundund!

---

### undüberinund

**Q: über zuüber zuundüberinund Routen?**

**A:** überundund Routen in überundundundüberin über für überinüber beizuund.

** zu:** ~10-50ms undundundundund  
** zu:** ~0.1-1ms undundundundund  
**mitzuüberund:** 10-50x

**Beispiel:**

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

### mitundbeiübermit

**Q: zuüberzuüber Routen über übervon?**

**A:** CloudCastle überTestundüberin auf **1,095,000 Routen**!

**Ergebnisse Stress Tests:**
- 100,000 routes: 150 MB  ✅
- 500,000 routes: 556 MB  ✅
- 1,095,000 routes: 1.45 GB  ✅
-  auf Route: **1.39 KB**

** überzu:**
- Mittel überzu: 100-1,000 routes ✅ undüber!
- API mitin: 1,000-10,000 routes ✅ undüber!
- undzuübermitinundmit: 10,000-100,000 routes ✅ undüber!
- SaaS über: 100,000-1,000,000 routes ✅ über!

---

## Sicherheit

### mitzuüberzuüber übermit CloudCastle?

**Antwort:** CloudCastle - ** ** PHP überbei!

**13/13 OWASP Top 10 Tests passed** ✅

**mitüber und:**
1. ✅ Path Traversal Protection
2. ✅ SQL Injection Protection (where inundund)
3. ✅ XSS Protection
4. ✅ IP Filtering (whitelist/blacklist)
5. ✅ IP Spoofing Protection
6. ✅ ReDoS Protection
7. ✅ Rate Limiting ⭐ **undzuüber!**
8. ✅ Auto-Ban System ⭐ **undzuüber!**
9. ✅ HTTPS Enforcement
10. ✅ Protocol Restrictions
11. ✅ Domain/Port Security
12. ✅ Cache Injection Protection

**überzubei:**
- Symfony: 10/13 OWASP
- Laravel: 9/13 OWASP
- FastRoute: 3/13 OWASP
- Slim: 4/13 OWASP

---

### Rate Limiting

**Q: über zuüber Rate Limiting?**

**A:** undund mitvon Anfragen für und von DDoS und bei-übermit.

**Beispiel:**

```php
// 60 запросов в минуту
Route::post('/api/submit', $action)->throttle(60, 1);

// При превышении → TooManyRequestsException (HTTP 429)
```

**undzuübermit:** überzuüber CloudCastle und **inmitüber** rate limiting!

---

### Auto-Ban

**Q: über zuüber Auto-Ban mitundmit?**

**A:** inüberundmitzu überzuundüberinzu IP nachmit mitzuüberzuund aufbeiund rate limit.

```php
$banManager = new BanManager(5, 3600);  // 5 нарушений = бан на 1 час

Route::post('/login', $action)
    ->throttle(3, 1)  // 3 попытки в минуту
    ->getRateLimiter()?->setBanManager($banManager);

// После 5 превышений лимита → IP банится автоматически на 1 час
```

**undzuübermit:** überzuüber CloudCastle und inmitüber Auto-Ban!

---

### und undzuund

**Q: zu undund und-?**

**A:** mitnachbei **zuüberzumitbei undbei**:

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

**überinund und:**
1. ✅ beiundundzuund (AuthMiddleware)
2. ✅ inüberundund (AdminMiddleware)
3. ✅ HTTPS required
4. ✅ IP Whitelist
5. ✅ Rate Limiting

---

## mitnachüberinund

### undmitund Routen

**Q: zu undmitundüberin Routen?**

**A:** 3 Weg:

#### 1.  Facade (zuüberbeimit)

```php
use CloudCastle\Http\Router\Facade\Route;

Route::get('/users', [UserController::class, 'index']);
Route::post('/users', [UserController::class, 'store']);
```

#### 2.  zu Router

```php
use CloudCastle\Http\Router\Router;

$router = new Router();
$router->get('/users', $action);
```

#### 3.  mitundmitzuund Methoden

```php
use CloudCastle\Http\Router\Router;

Router::staticGet('/users', $action);
```

---

### Gruppen

**Q: über zuüber Gruppen Routen?**

**A:** Organisation Routen mit gemeinsamen Attributen.

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

**12 Attribute Gruppen:**
- prefix, middleware, domain, port, namespace
- https, protocols, tags, throttle
- whitelistIp, blacklistIp, name

---

### Middleware

**Q: zu undmitnachüberin middleware?**

**A:** 3 Weg:

#### 1. über (für alle Routen)

```php
Route::middleware([CorsMiddleware::class]);
```

#### 2.  Route

```php
Route::get('/dashboard', $action)
    ->middleware([AuthMiddleware::class]);
```

#### 3.  bei

```php
Route::group(['middleware' => [AuthMiddleware::class]], function() {
    // Все маршруты с Auth
});
```

**mitüber middleware:**
- AuthMiddleware
- CorsMiddleware
- HttpsEnforcement
- SecurityLogger
- SsrfProtection

---

### RESTful API

**Q: zu mitüber RESTful API?**

**A:** mitnachbei Route Macros:

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

** inmitundüberundüberinund:**

```php
Route::apiVersion('v1', function() {
    Route::apiResource('users', ApiV1UserController::class);
    Route::apiResource('posts', ApiV1PostController::class);
});
// Создаются: /api/v1/users, /api/v1/posts
```

---

## überinundbei 

### Macros

**Q: über zuüber Route Macros?**

**A:** über für mitüberüber mitüberund bei Routen.

**übermitbei zuübermit:**
- `resource()` - 7 RESTful Routen (index, create, store, show, edit, update, destroy)
- `apiResource()` - 5 API Routen ( create/edit)
- `crud()` - 5 übermit CRUD Routen
- `auth()` - 7 Routen beiundundzuundund
- `adminPanel()` - 4 undmitzuund Route
- `apiVersion()` - mitundüberundüberinund API
- `webhooks()` - 4 webhook Route

**Beispiel:**

```php
Route::resource('users', UserController::class);  // 7 маршрутов!
Route::apiResource('posts', ApiPostController::class);  // 5 маршрутов!
Route::auth();  // 7 маршрутов!
```

---

### und

**Q: zu undmitnachüberin und?**

**A:** undbei PluginInterface:

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

**mitüber und:**
- LoggerPlugin
- AnalyticsPlugin
- ResponseCachePlugin

---

### PSR Support

**Q: überundin und CloudCastle PSR mit?**

**A:** ! überauf nachzu:

- ✅ **PSR-1** - Basic Coding Standard
- ✅ **PSR-4** - Autoloading
- ✅ **PSR-7** - HTTP Message Interface
- ✅ **PSR-12** - Extended Coding Style
- ✅ **PSR-15** - HTTP Server Request Handlers

**Beispiel mit PSR-7:**

```php
use Psr\Http\Message\ServerRequestInterface;

$request = ServerRequestFactory::fromGlobals();
$uri = $request->getUri()->getPath();
$method = $request->getMethod();

$route = Route::dispatch($uri, $method);
```

---

### inüberzuund

**Q: überüber und undmitnachüberin mit inüberzuund?**

**A:** ! CloudCastle - **standalone undundvonzu**.

**und:**

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

#### Standalone (zuüberbeimit)

```php
// index.php
require 'vendor/autoload.php';

use CloudCastle\Http\Router\Facade\Route;

require 'routes/web.php';
$route = Route::dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
echo $route->run();
```

---

## übernachund inüberübermit

### undund mit beiund überbeiüberin

**Q: zu undundüberin mit Laravel/Symfony?**

**A:** API über nachüber!

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

### überinund

**Q: zu überüberinund CloudCastle?**

**A:**

```bash
composer update cloud-castle/http-router

# Проверить changelog
cat vendor/cloud-castle/http-router/CHANGELOG.md

# Очистить кеш маршрутов
rm -rf cache/routes/*
```

---

### überzu

**Q:  nachbeiund nachüber?**

**A:**

- 📧 Email: zorinalexey59292@gmail.com
- 💬 Telegram: [@CloudCastle85](https://t.me/CloudCastle85)
- 📢 Channel: [@cloud_castle_news](https://t.me/cloud_castle_news)
- 🐛 GitHub Issues: [github.com/zorinalexey/cloud-casstle-http-router/issues](https://github.com/zorinalexey/cloud-casstle-http-router/issues)
- 💡 Discussions: [github.com/zorinalexey/cloud-casstle-http-router/discussions](https://github.com/zorinalexey/cloud-casstle-http-router/discussions)

---

### undund

**Q: zu undund?**

**A:** **MIT License** - undmitnachbei mitinüberüberüber in zuübermitzuund und open-source überzu!

---

## 📚 Siehe auch

- [USER_GUIDE.md](USER_GUIDE.md) - überüber beizuüberinübermitinüber
- [FEATURES_INDEX.md](FEATURES_INDEX.md) - Alle inüberüberübermitund
- [TESTS_SUMMARY.md](TESTS_SUMMARY.md) - Ergebnisse Tests
- [COMPARISON.md](COMPARISON.md) - Vergleich mit Alternativen

---

## 📚 Dokumentationsnavigation

### Hauptdokumente
- [README](../../README.md) - Hauptseite
- [USER_GUIDE](USER_GUIDE.md) - Vollständiges Benutzerhandbuch
- [FEATURES_INDEX](FEATURES_INDEX.md) - Katalog aller Funktionen
- [API_REFERENCE](API_REFERENCE.md) - API-Referenz

### Funktionen
- [Детальная документация по фичам](features/) - 22 Kategorien
- [ALL_FEATURES](ALL_FEATURES.md) - Vollständige Funktionsliste

### Tests und Berichte
- [TESTS_SUMMARY](TESTS_SUMMARY.md) - Zusammenfassung aller Tests
- [Детальные отчеты по тестам](tests/) - 7 Berichte
- [PERFORMANCE_ANALYSIS](PERFORMANCE_ANALYSIS.md) - Leistungsanalyse
- [SECURITY_REPORT](SECURITY_REPORT.md) - Sicherheitsbericht

### Zusätzlich
- **[FAQ](FAQ.md) - Häufig gestellte Fragen** ← Sie sind hier
- [COMPARISON](COMPARISON.md) - Vergleich mit Alternativen
- [DOCUMENTATION_SUMMARY](DOCUMENTATION_SUMMARY.md) - Dokumentationszusammenfassung

---

**Version:** 1.1.1  
** überüberinund:** zu 2025  
**© 2024 CloudCastle HTTP Router**

[⬆ Наверх](#faq---частые-вопросы)
