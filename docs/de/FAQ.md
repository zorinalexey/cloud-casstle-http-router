# FAQ - Häufig gestellte Fragen

[English](../en/FAQ.md) | [Русский](../ru/FAQ.md) | **Deutsch** | [Français](../fr/FAQ.md) | [中文](../zh/FAQ.md)

---







**Version:** 1.1.1  
**Datum:** Октябрь 2025

---

## 📚 Dokumentationsnavigation

### Hauptdokumente
- [README](../../README.md) - Hauptseite
- [USER_GUIDE](USER_GUIDE.md) - Vollständiges Benutzerhandbuch
- [FEATURES_INDEX](FEATURES_INDEX.md) - Katalog aller Funktionen
- [API_REFERENCE](API_REFERENCE.md) - API-Referenz

### Funktionen
- [Деталь auf я документац und я  nach  ф und чам](features/) - 22 Kategorien
- [ALL_FEATURES](ALL_FEATURES.md) - Vollständige Funktionsliste

### Tests und Berichte
- [TESTS_SUMMARY](TESTS_SUMMARY.md) - Zusammenfassung aller Tests
- [Детальные отчеты  nach  те mit там](tests/) - 7 Berichte
- [PERFORMANCE_ANALYSIS](PERFORMANCE_ANALYSIS.md) - Leistungsanalyse
- [SECURITY_REPORT](SECURITY_REPORT.md) - Sicherheitsbericht

### Zusätzlich
- **[FAQ](FAQ.md) - Häufig gestellte Fragen** ← Sie sind hier
- [COMPARISON](COMPARISON.md) - Vergleich mit Alternativen
- [DOCUMENTATION_SUMMARY](DOCUMENTATION_SUMMARY.md) - Dokumentationszusammenfassung

---

## Inhalt

### Общ und е  in опро mit ы
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

### И mit  nach льзо in ан und е
13. [Как регистрировать маршруты?](#регистрация-маршрутов)
14. [Что такое группы маршрутов?](#группы)
15. [Как использовать middleware?](#middleware)
16. [Как создать RESTful API?](#restful-api)

### Прод in  und нутые темы
17. [Что такое Route Macros?](#macros)
18. [Как использовать плагины?](#плагины)
19. [Поддержка PSR стандартов?](#psr-support)
20. [Можно ли использовать с фреймворками?](#фреймворки)

---

## Общ und е  in опро mit ы

### Что такое CloudCastle HTTP Router?

**Antwort:** CloudCastle HTTP Router - это ** mit о in ремен auf я б und бл und отека Route und зац und  und **  für  PHP 8.2+, которая предо mit та in ляет **209+  in озможно mit тей**  für   mit оздан und я безопа mit ных  und   in ы mit окопро und з in од und тельных  in еб-пр und ложен und й.

**Ключе in ые о mit обенно mit т und :**
- ⚡ 53,637 req/sec про und з in од und тельно mit ть
- 🔒 13/13 OWASP Top 10 compliance
- 💎 209+  in озможно mit тей
- ✅ 501 Test (100% pass)

---

### Почему  in ыбрать CloudCastle?

**Antwort:** CloudCastle - **ед und н mit т in енный роутер**  mit :

1. **В mit троенным Rate Limiting** ⭐ Ун und кально!
   ```php
   Route::post('/api', $action)->throttle(60, 1);
   ```

2. **Auto-Ban  mit  und  mit темой** ⭐ Ун und кально!
   ```php
   $banManager = new BanManager(5, 3600);
   Route::post('/login', $action)
       ->throttle(3, 1)
       ->getRateLimiter()?->setBanManager($banManager);
   ```

3. **В mit троенным IP Filtering** ⭐ Ун und кально!
   ```php
   Route::get('/admin', $action)->whitelistIp(['192.168.1.0/24']);
   ```

4. **209+  in озможно mit тям und ** - больше чем у alleх конкуренто in !

**Сра in нен und е:**
- Symfony: 180+  in озможно mit тей, нет rate limiting
- Laravel: 150+  in озможно mit тей, только  in  framework
- FastRoute: ~20  in озможно mit тей, только  mit коро mit ть
- Slim: ~50  in озможно mit тей, базо in ая функц und о auf льно mit ть

**CloudCastle = Лучш und й балан mit   mit коро mit т und , безопа mit но mit т und   und  функц und о auf льно mit т und !**

---

### Требо in ан und я

**М und н und мальные требо in ан und я:**
- PHP 8.2  oder   in ыше ✅
- Composer
- ~2 MB д und  mit ко in ого про mit тран mit т in а

**Рекомендует mit я:**
- PHP 8.3+  für  лучшей про und з in од und тельно mit т und 
- Opcache enabled
- 128 MB+ memory_limit

**Поддерж und  in аемые  in ер mit  und  und  PHP:**
- ✅ PHP 8.2 (м und н und мум)
- ✅ PHP 8.3 (рекомендует mit я)
- ✅ PHP 8.4 (tested)

---

### Installation

**Через Composer:**

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

### На mit колько бы mit тр CloudCastle?

**Antwort:** CloudCastle  nach казы in ает **отл und чную про und з in од und тельно mit ть**:

**Load Tests:**
- Light (100 routes): **55,923 req/sec** ⚡
- Medium (500 routes): **54,680 req/sec** ⚡
- Heavy (1000 routes): **53,637 req/sec** ⚡

**Сра in нен und е  mit  конкурентам und  (1000 routes):**
1. FastRoute: 60,000 req/sec (но только 20  in озможно mit тей!)
2. **CloudCastle: 53,637 req/sec** (209+  in озможно mit тей!) ⭐
3. Slim: 45,000 req/sec
4. Symfony: 40,000 req/sec
5. Laravel: 35,000 req/sec

**Вы in од:** CloudCastle - **2-е ме mit то  nach   mit коро mit т und **  mit  **мак mit  und мальной функц und о auf льно mit тью**!

---

### Опт und м und зац und я

**Q: Как улучш und ть про und з in од und тельно mit ть?**

**A: И mit  nach льзуйте 3 про mit тых пр und ема:**

#### 1. Caching Routeо in 

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

#### 3. Групп und ро in ка

```php
// ✅ Эффективнее
Route::group(['prefix' => '/api', 'middleware' => [...]], function() {
    // 100 маршрутов
});
```

**Результат:** До **50% у mit корен und я**  und н und ц und ал und зац und  und !

---

### Caching

**Q: Что такое кеш und ро in ан und е Routeо in ?**

**A:** Комп und ляц und я Routeо in   in  опт und м und з und ро in анный формат  für  мгно in енной загрузк und .

**Без кеша:** ~10-50ms  und н und ц und ал und зац und я  
**С кешем:** ~0.1-1ms  und н und ц und ал und зац und я  
**У mit корен und е:** 10-50x

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

### Ма mit штаб und руемо mit ть

**Q: Сколько Routeо in  может обработать?**

**A:** CloudCastle проTest und ро in ан  auf  **1,095,000 Routeо in **!

**Ergebnisse Stress Tests:**
- 100,000 routes: 150 MB память ✅
- 500,000 routes: 556 MB память ✅
- 1,095,000 routes: 1.45 GB память ✅
- Память  auf  Route: **1.39 KB**

**Реальные проекты:**
- Mittel проект: 100-1,000 routes ✅ Отл und чно!
- API  mit ер in ер: 1,000-10,000 routes ✅ Отл und чно!
- М und кро mit ер in  und  mit ы: 10,000-100,000 routes ✅ Отл und чно!
- SaaS платформа: 100,000-1,000,000 routes ✅ Можем!

---

## Sicherheit

### На mit колько безопа mit ен CloudCastle?

**Antwort:** CloudCastle - **САМЫЙ БЕЗОПАСНЫЙ** PHP роутер!

**13/13 OWASP Top 10 Tests passed** ✅

**В mit троенные механ und змы:**
1. ✅ Path Traversal Protection
2. ✅ SQL Injection Protection (where  in ал und дац und я)
3. ✅ XSS Protection
4. ✅ IP Filtering (whitelist/blacklist)
5. ✅ IP Spoofing Protection
6. ✅ ReDoS Protection
7. ✅ Rate Limiting ⭐ **Ун und кально!**
8. ✅ Auto-Ban System ⭐ **Ун und кально!**
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

**A:** Огран und чен und е ча mit тоты Anfragen  für  защ und ты от DDoS  und  брут-фор mit а.

**Beispiel:**

```php
// 60 запросов в минуту
Route::post('/api/submit', $action)->throttle(60, 1);

// При превышении → TooManyRequestsException (HTTP 429)
```

**Ун und кально mit ть:** Только CloudCastle  und меет ** in  mit троенный** rate limiting!

---

### Auto-Ban

**Q: Что такое Auto-Ban  mit  und  mit тема?**

**A:** А in томат und че mit кая блок und ро in ка IP  nach  mit ле не mit кольк und х  auf рушен und й rate limit.

```php
$banManager = new BanManager(5, 3600);  // 5 нарушений = бан на 1 час

Route::post('/login', $action)
    ->throttle(3, 1)  // 3 попытки в минуту
    ->getRateLimiter()?->setBanManager($banManager);

// После 5 превышений лимита → IP банится автоматически на 1 час
```

**Ун und кально mit ть:** Только CloudCastle  und меет  in  mit троенный Auto-Ban!

---

### Защ und та адм und нк und 

**Q: Как защ und т und ть адм und н-панель?**

**A:** И mit  nach льзуйте **комплек mit ную защ und ту**:

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

**Уро in н und  защ und ты:**
1. ✅ Аутент und ф und кац und я (AuthMiddleware)
2. ✅ А in тор und зац und я (AdminMiddleware)
3. ✅ HTTPS required
4. ✅ IP Whitelist
5. ✅ Rate Limiting

---

## И mit  nach льзо in ан und е

### Рег und  mit трац und я Routeо in 

**Q: Как рег und  mit тр und ро in ать Routen?**

**A:** 3 Wegа:

#### 1. Через Facade (рекомендует mit я)

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

#### 3. Через  mit тат und че mit к und е Methoden

```php
use CloudCastle\Http\Router\Router;

Router::staticGet('/users', $action);
```

---

### Gruppen

**Q: Что такое Gruppen Routeо in ?**

**A:** Organisation Routeо in  mit gemeinsamen Attributen.

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

**Q: Как  und  mit  nach льзо in ать middleware?**

**A:** 3 Wegа:

#### 1. Глобальный ( für  alleх Routeо in )

```php
Route::middleware([CorsMiddleware::class]);
```

#### 2. На Routeе

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

**В mit троенные middleware:**
- AuthMiddleware
- CorsMiddleware
- HttpsEnforcement
- SecurityLogger
- SsrfProtection

---

### RESTful API

**Q: Как  mit оздать RESTful API?**

**A:** И mit  nach льзуйте Route Macros:

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

**С  in ер mit  und он und ро in ан und ем:**

```php
Route::apiVersion('v1', function() {
    Route::apiResource('users', ApiV1UserController::class);
    Route::apiResource('posts', ApiV1PostController::class);
});
// Создаются: /api/v1/users, /api/v1/posts
```

---

## Прод in  und нутые темы

### Macros

**Q: Что такое Route Macros?**

**A:** Шаблоны  für  бы mit трого  mit оздан und я групп Routeо in .

**До mit тупные макро mit ы:**
- `resource()` - 7 RESTful Routeо in  (index, create, store, show, edit, update, destroy)
- `apiResource()` - 5 API Routeо in  (без create/edit)
- `crud()` - 5 про mit тых CRUD Routeо in 
- `auth()` - 7 Routeо in  аутент und ф und кац und  und 
- `adminPanel()` - 4 адм und н mit к und х Routeа
- `apiVersion()` - Вер mit  und он und ро in ан und е API
- `webhooks()` - 4 webhook Routeа

**Beispiel:**

```php
Route::resource('users', UserController::class);  // 7 маршрутов!
Route::apiResource('posts', ApiPostController::class);  // 5 маршрутов!
Route::auth();  // 7 маршрутов!
```

---

### Плаг und ны

**Q: Как  und  mit  nach льзо in ать плаг und ны?**

**A:** Реал und зуйте PluginInterface:

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

**В mit троенные плаг und ны:**
- LoggerPlugin
- AnalyticsPlugin
- ResponseCachePlugin

---

### PSR Support

**Q: Поддерж und  in ает л und  CloudCastle PSR  mit тандарты?**

**A:** Да! Пол auf я  nach ддержка:

- ✅ **PSR-1** - Basic Coding Standard
- ✅ **PSR-4** - Autoloading
- ✅ **PSR-7** - HTTP Message Interface
- ✅ **PSR-12** - Extended Coding Style
- ✅ **PSR-15** - HTTP Server Request Handlers

**Beispiel  mit  PSR-7:**

```php
use Psr\Http\Message\ServerRequestInterface;

$request = ServerRequestFactory::fromGlobals();
$uri = $request->getUri()->getPath();
$method = $request->getMethod();

$route = Route::dispatch($uri, $method);
```

---

### Фрейм in орк und 

**Q: Можно л und   und  mit  nach льзо in ать  mit  фрейм in оркам und ?**

**A:** Да! CloudCastle - **standalone б und бл und отека**.

**Интеграц und я:**

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

#### Standalone (рекомендует mit я)

```php
// index.php
require 'vendor/autoload.php';

use CloudCastle\Http\Router\Facade\Route;

require 'routes/web.php';
$route = Route::dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
echo $route->run();
```

---

## До nach лн und тельные  in опро mit ы

### М und грац und я  mit  друг und х роутеро in 

**Q: Как м und гр und ро in ать  mit  Laravel/Symfony?**

**A:** API очень  nach хож!

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

### Обно in лен und я

**Q: Как обно in  und ть CloudCastle?**

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

**Q: Где  nach луч und ть  nach мощь?**

**A:**

- 📧 Email: zorinalexey59292@gmail.com
- 💬 Telegram: [@CloudCastle85](https://t.me/CloudCastle85)
- 📢 Channel: [@cloud_castle_news](https://t.me/cloud_castle_news)
- 🐛 GitHub Issues: [github.com/zorinalexey/cloud-casstle-http-router/issues](https://github.com/zorinalexey/cloud-casstle-http-router/issues)
- 💡 Discussions: [github.com/zorinalexey/cloud-casstle-http-router/discussions](https://github.com/zorinalexey/cloud-casstle-http-router/discussions)

---

### Л und ценз und я

**Q: Какая л und ценз und я?**

**A:** **MIT License** -  und  mit  nach льзуйте  mit  in ободно  in  коммерче mit к und х  und  open-source проектах!

---

## 📚 Siehe auch

- [USER_GUIDE.md](USER_GUIDE.md) - Полное руко in од mit т in о
- [FEATURES_INDEX.md](FEATURES_INDEX.md) - Alle  in озможно mit т und 
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
- [Деталь auf я документац und я  nach  ф und чам](features/) - 22 Kategorien
- [ALL_FEATURES](ALL_FEATURES.md) - Vollständige Funktionsliste

### Tests und Berichte
- [TESTS_SUMMARY](TESTS_SUMMARY.md) - Zusammenfassung aller Tests
- [Детальные отчеты  nach  те mit там](tests/) - 7 Berichte
- [PERFORMANCE_ANALYSIS](PERFORMANCE_ANALYSIS.md) - Leistungsanalyse
- [SECURITY_REPORT](SECURITY_REPORT.md) - Sicherheitsbericht

### Zusätzlich
- **[FAQ](FAQ.md) - Häufig gestellte Fragen** ← Sie sind hier
- [COMPARISON](COMPARISON.md) - Vergleich mit Alternativen
- [DOCUMENTATION_SUMMARY](DOCUMENTATION_SUMMARY.md) - Dokumentationszusammenfassung

---

**Version:** 1.1.1  
**Дата обно in лен und я:** Октябрь 2025  
**© 2024 CloudCastle HTTP Router**

[⬆ Наверх](#faq---частые-вопросы)
