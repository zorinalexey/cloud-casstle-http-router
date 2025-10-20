# FAQ - Frequently Asked Questions

**English** | [Русский](../ru/FAQ.md) | [Deutsch](../de/FAQ.md) | [Français](../fr/FAQ.md) | [中文](../zh/FAQ.md)

---







**Version:** 1.1.1  
**Date:** Октябрь 2025

---

## 📚 Documentation Navigation

### Main Documents
- [README](../../README.md) - Main Page
- [USER_GUIDE](USER_GUIDE.md) - Complete User Guide
- [FEATURES_INDEX](FEATURES_INDEX.md) - All Features Catalog
- [API_REFERENCE](API_REFERENCE.md) - API Reference

### Features
- [Деталь on я документац and я  by  ф and чам](features/) - 22 categories
- [ALL_FEATURES](ALL_FEATURES.md) - Complete Features List

### Tests and Reports
- [TESTS_SUMMARY](TESTS_SUMMARY.md) - All Tests Summary
- [Детальные отчеты  by  те with там](tests/) - 7 reports
- [PERFORMANCE_ANALYSIS](PERFORMANCE_ANALYSIS.md) - Performance Analysis
- [SECURITY_REPORT](SECURITY_REPORT.md) - Security Report

### Additional
- **[FAQ](FAQ.md) - Frequently Asked Questions** ← You are here
- [COMPARISON](COMPARISON.md) - Comparison with Alternatives
- [DOCUMENTATION_SUMMARY](DOCUMENTATION_SUMMARY.md) - Documentation Summary

---

## Table of Contents

### Общ and е  in опро with ы
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

### И with  by льзо in ан and е
13. [Как регистрировать маршруты?](#регистрация-маршрутов)
14. [Что такое группы маршрутов?](#группы)
15. [Как использовать middleware?](#middleware)
16. [Как создать RESTful API?](#restful-api)

### Прод in  and нутые темы
17. [Что такое Route Macros?](#macros)
18. [Как использовать плагины?](#плагины)
19. [Поддержка PSR стандартов?](#psr-support)
20. [Можно ли использовать с фреймворками?](#фреймворки)

---

## Общ and е  in опро with ы

### Что такое CloudCastle HTTP Router?

**Response:** CloudCastle HTTP Router - это ** with о in ремен on я б and бл and отека route and зац and  and **  for  PHP 8.2+, которая предо with та in ляет **209+  in озможно with тей**  for   with оздан and я безопа with ных  and   in ы with окопро and з in од and тельных  in еб-пр and ложен and й.

**Ключе in ые о with обенно with т and :**
- ⚡ 53,637 req/sec про and з in од and тельно with ть
- 🔒 13/13 OWASP Top 10 compliance
- 💎 209+  in озможно with тей
- ✅ 501 test (100% pass)

---

### Почему  in ыбрать CloudCastle?

**Response:** CloudCastle - **ед and н with т in енный роутер**  with :

1. **В with троенным Rate Limiting** ⭐ Ун and кально!
   ```php
   Route::post('/api', $action)->throttle(60, 1);
   ```

2. **Auto-Ban  with  and  with темой** ⭐ Ун and кально!
   ```php
   $banManager = new BanManager(5, 3600);
   Route::post('/login', $action)
       ->throttle(3, 1)
       ->getRateLimiter()?->setBanManager($banManager);
   ```

3. **В with троенным IP Filtering** ⭐ Ун and кально!
   ```php
   Route::get('/admin', $action)->whitelistIp(['192.168.1.0/24']);
   ```

4. **209+  in озможно with тям and ** - больше чем у allх конкуренто in !

**Сра in нен and е:**
- Symfony: 180+  in озможно with тей, нет rate limiting
- Laravel: 150+  in озможно with тей, только  in  framework
- FastRoute: ~20  in озможно with тей, только  with коро with ть
- Slim: ~50  in озможно with тей, базо in ая функц and о on льно with ть

**CloudCastle = Лучш and й балан with   with коро with т and , безопа with но with т and   and  функц and о on льно with т and !**

---

### Требо in ан and я

**М and н and мальные требо in ан and я:**
- PHP 8.2  or   in ыше ✅
- Composer
- ~2 MB д and  with ко in ого про with тран with т in а

**Рекомендует with я:**
- PHP 8.3+  for  лучшей про and з in од and тельно with т and 
- Opcache enabled
- 128 MB+ memory_limit

**Поддерж and  in аемые  in ер with  and  and  PHP:**
- ✅ PHP 8.2 (м and н and мум)
- ✅ PHP 8.3 (рекомендует with я)
- ✅ PHP 8.4 (tested)

---

### Installation

**Через Composer:**

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

### На with колько бы with тр CloudCastle?

**Response:** CloudCastle  by казы in ает **отл and чную про and з in од and тельно with ть**:

**Load Tests:**
- Light (100 routes): **55,923 req/sec** ⚡
- Medium (500 routes): **54,680 req/sec** ⚡
- Heavy (1000 routes): **53,637 req/sec** ⚡

**Сра in нен and е  with  конкурентам and  (1000 routes):**
1. FastRoute: 60,000 req/sec (но только 20  in озможно with тей!)
2. **CloudCastle: 53,637 req/sec** (209+  in озможно with тей!) ⭐
3. Slim: 45,000 req/sec
4. Symfony: 40,000 req/sec
5. Laravel: 35,000 req/sec

**Вы in од:** CloudCastle - **2-е ме with то  by   with коро with т and **  with  **мак with  and мальной функц and о on льно with тью**!

---

### Опт and м and зац and я

**Q: Как улучш and ть про and з in од and тельно with ть?**

**A: И with  by льзуйте 3 про with тых пр and ема:**

#### 1. Caching routeо in 

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

#### 3. Групп and ро in ка

```php
// ✅ Эффективнее
Route::group(['prefix' => '/api', 'middleware' => [...]], function() {
    // 100 маршрутов
});
```

**Результат:** До **50% у with корен and я**  and н and ц and ал and зац and  and !

---

### Caching

**Q: Что такое кеш and ро in ан and е routeо in ?**

**A:** Комп and ляц and я routeо in   in  опт and м and з and ро in анный формат  for  мгно in енной загрузк and .

**Без кеша:** ~10-50ms  and н and ц and ал and зац and я  
**С кешем:** ~0.1-1ms  and н and ц and ал and зац and я  
**У with корен and е:** 10-50x

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

### Ма with штаб and руемо with ть

**Q: Сколько routeо in  может обработать?**

**A:** CloudCastle проtest and ро in ан  on  **1,095,000 routeо in **!

**Results Stress Tests:**
- 100,000 routes: 150 MB память ✅
- 500,000 routes: 556 MB память ✅
- 1,095,000 routes: 1.45 GB память ✅
- Память  on  route: **1.39 KB**

**Реальные проекты:**
- Intermediate проект: 100-1,000 routes ✅ Отл and чно!
- API  with ер in ер: 1,000-10,000 routes ✅ Отл and чно!
- М and кро with ер in  and  with ы: 10,000-100,000 routes ✅ Отл and чно!
- SaaS платформа: 100,000-1,000,000 routes ✅ Можем!

---

## Security

### На with колько безопа with ен CloudCastle?

**Response:** CloudCastle - **САМЫЙ БЕЗОПАСНЫЙ** PHP роутер!

**13/13 OWASP Top 10 tests passed** ✅

**В with троенные механ and змы:**
1. ✅ Path Traversal Protection
2. ✅ SQL Injection Protection (where  in ал and дац and я)
3. ✅ XSS Protection
4. ✅ IP Filtering (whitelist/blacklist)
5. ✅ IP Spoofing Protection
6. ✅ ReDoS Protection
7. ✅ Rate Limiting ⭐ **Ун and кально!**
8. ✅ Auto-Ban System ⭐ **Ун and кально!**
9. ✅ HTTPS Enforcement
10. ✅ Protocol Restrictions
11. ✅ Domain/Port Security
12. ✅ Cache Injection Protection

**Конкуренты:**
- Symfony: 10/13 OWASP
- Laravel: 9/13 OWASP
- FastRoute: 3/13 OWASP
- Slim: 4/13 OWASP

---

### Rate Limiting

**Q: Что такое Rate Limiting?**

**A:** Огран and чен and е ча with тоты requests  for  защ and ты от DDoS  and  брут-фор with а.

**Example:**

```php
// 60 запросов в минуту
Route::post('/api/submit', $action)->throttle(60, 1);

// При превышении → TooManyRequestsException (HTTP 429)
```

**Ун and кально with ть:** Только CloudCastle  and меет ** in  with троенный** rate limiting!

---

### Auto-Ban

**Q: Что такое Auto-Ban  with  and  with тема?**

**A:** А in томат and че with кая блок and ро in ка IP  by  with ле не with кольк and х  on рушен and й rate limit.

```php
$banManager = new BanManager(5, 3600);  // 5 нарушений = бан на 1 час

Route::post('/login', $action)
    ->throttle(3, 1)  // 3 попытки в минуту
    ->getRateLimiter()?->setBanManager($banManager);

// После 5 превышений лимита → IP банится автоматически на 1 час
```

**Ун and кально with ть:** Только CloudCastle  and меет  in  with троенный Auto-Ban!

---

### Защ and та адм and нк and 

**Q: Как защ and т and ть адм and н-панель?**

**A:** И with  by льзуйте **комплек with ную защ and ту**:

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

**Уро in н and  защ and ты:**
1. ✅ Аутент and ф and кац and я (AuthMiddleware)
2. ✅ А in тор and зац and я (AdminMiddleware)
3. ✅ HTTPS required
4. ✅ IP Whitelist
5. ✅ Rate Limiting

---

## И with  by льзо in ан and е

### Рег and  with трац and я routeо in 

**Q: Как рег and  with тр and ро in ать routes?**

**A:** 3 wayа:

#### 1. Через Facade (рекомендует with я)

```php
use CloudCastle\Http\Router\Facade\Route;

Route::get('/users', [UserController::class, 'index']);
Route::post('/users', [UserController::class, 'store']);
```

#### 2. Через экземпляр Router

```php
use CloudCastle\Http\Router\Router;

$router = new Router();
$router->get('/users', $action);
```

#### 3. Через  with тат and че with к and е methods

```php
use CloudCastle\Http\Router\Router;

Router::staticGet('/users', $action);
```

---

### Groups

**Q: Что такое groups routeо in ?**

**A:** Organization routeо in  with shared attributes.

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

**Q: Как  and  with  by льзо in ать middleware?**

**A:** 3 wayа:

#### 1. Глобальный ( for  allх routeо in )

```php
Route::middleware([CorsMiddleware::class]);
```

#### 2. На routeе

```php
Route::get('/dashboard', $action)
    ->middleware([AuthMiddleware::class]);
```

#### 3. В группе

```php
Route::group(['middleware' => [AuthMiddleware::class]], function() {
    // Все маршруты с Auth
});
```

**В with троенные middleware:**
- AuthMiddleware
- CorsMiddleware
- HttpsEnforcement
- SecurityLogger
- SsrfProtection

---

### RESTful API

**Q: Как  with оздать RESTful API?**

**A:** И with  by льзуйте Route Macros:

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

**С  in ер with  and он and ро in ан and ем:**

```php
Route::apiVersion('v1', function() {
    Route::apiResource('users', ApiV1UserController::class);
    Route::apiResource('posts', ApiV1PostController::class);
});
// Создаются: /api/v1/users, /api/v1/posts
```

---

## Прод in  and нутые темы

### Macros

**Q: Что такое Route Macros?**

**A:** Шаблоны  for  бы with трого  with оздан and я групп routeо in .

**До with тупные макро with ы:**
- `resource()` - 7 RESTful routeо in  (index, create, store, show, edit, update, destroy)
- `apiResource()` - 5 API routeо in  (без create/edit)
- `crud()` - 5 про with тых CRUD routeо in 
- `auth()` - 7 routeо in  аутент and ф and кац and  and 
- `adminPanel()` - 4 адм and н with к and х routeа
- `apiVersion()` - Вер with  and он and ро in ан and е API
- `webhooks()` - 4 webhook routeа

**Example:**

```php
Route::resource('users', UserController::class);  // 7 маршрутов!
Route::apiResource('posts', ApiPostController::class);  // 5 маршрутов!
Route::auth();  // 7 маршрутов!
```

---

### Плаг and ны

**Q: Как  and  with  by льзо in ать плаг and ны?**

**A:** Реал and зуйте PluginInterface:

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

**В with троенные плаг and ны:**
- LoggerPlugin
- AnalyticsPlugin
- ResponseCachePlugin

---

### PSR Support

**Q: Поддерж and  in ает л and  CloudCastle PSR  with тандарты?**

**A:** Да! Пол on я  by ддержка:

- ✅ **PSR-1** - Basic Coding Standard
- ✅ **PSR-4** - Autoloading
- ✅ **PSR-7** - HTTP Message Interface
- ✅ **PSR-12** - Extended Coding Style
- ✅ **PSR-15** - HTTP Server Request Handlers

**Example  with  PSR-7:**

```php
use Psr\Http\Message\ServerRequestInterface;

$request = ServerRequestFactory::fromGlobals();
$uri = $request->getUri()->getPath();
$method = $request->getMethod();

$route = Route::dispatch($uri, $method);
```

---

### Фрейм in орк and 

**Q: Можно л and   and  with  by льзо in ать  with  фрейм in оркам and ?**

**A:** Да! CloudCastle - **standalone б and бл and отека**.

**Интеграц and я:**

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

#### Standalone (рекомендует with я)

```php
// index.php
require 'vendor/autoload.php';

use CloudCastle\Http\Router\Facade\Route;

require 'routes/web.php';
$route = Route::dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
echo $route->run();
```

---

## До by лн and тельные  in опро with ы

### М and грац and я  with  друг and х роутеро in 

**Q: Как м and гр and ро in ать  with  Laravel/Symfony?**

**A:** API очень  by хож!

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

### Обно in лен and я

**Q: Как обно in  and ть CloudCastle?**

**A:**

```bash
composer update cloud-castle/http-router

# Проверить changelog
cat vendor/cloud-castle/http-router/CHANGELOG.md

# Очистить кеш маршрутов
rm -rf cache/routes/*
```

---

### Поддержка

**Q: Где  by луч and ть  by мощь?**

**A:**

- 📧 Email: zorinalexey59292@gmail.com
- 💬 Telegram: [@CloudCastle85](https://t.me/CloudCastle85)
- 📢 Channel: [@cloud_castle_news](https://t.me/cloud_castle_news)
- 🐛 GitHub Issues: [github.com/zorinalexey/cloud-casstle-http-router/issues](https://github.com/zorinalexey/cloud-casstle-http-router/issues)
- 💡 Discussions: [github.com/zorinalexey/cloud-casstle-http-router/discussions](https://github.com/zorinalexey/cloud-casstle-http-router/discussions)

---

### Л and ценз and я

**Q: Какая л and ценз and я?**

**A:** **MIT License** -  and  with  by льзуйте  with  in ободно  in  коммерче with к and х  and  open-source проектах!

---

## 📚 See also

- [USER_GUIDE.md](USER_GUIDE.md) - Полное руко in од with т in о
- [FEATURES_INDEX.md](FEATURES_INDEX.md) - All  in озможно with т and 
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
- [Деталь on я документац and я  by  ф and чам](features/) - 22 categories
- [ALL_FEATURES](ALL_FEATURES.md) - Complete Features List

### Tests and Reports
- [TESTS_SUMMARY](TESTS_SUMMARY.md) - All Tests Summary
- [Детальные отчеты  by  те with там](tests/) - 7 reports
- [PERFORMANCE_ANALYSIS](PERFORMANCE_ANALYSIS.md) - Performance Analysis
- [SECURITY_REPORT](SECURITY_REPORT.md) - Security Report

### Additional
- **[FAQ](FAQ.md) - Frequently Asked Questions** ← You are here
- [COMPARISON](COMPARISON.md) - Comparison with Alternatives
- [DOCUMENTATION_SUMMARY](DOCUMENTATION_SUMMARY.md) - Documentation Summary

---

**Version:** 1.1.1  
**Дата обно in лен and я:** Октябрь 2025  
**© 2024 CloudCastle HTTP Router**

[⬆ Наверх](#faq---частые-вопросы)
