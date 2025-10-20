# FAQ - Questions Fréquentes

[English](../en/FAQ.md) | [Русский](../ru/FAQ.md) | [Deutsch](../de/FAQ.md) | **Français** | [中文](../zh/FAQ.md)

---







**Version:** 1.1.1  
**Date:** Октябрь 2025

---

## 📚 Navigation de la Documentation

### Documents Principaux
- [README](../../README.md) - Page Principale
- [USER_GUIDE](USER_GUIDE.md) - Guide Utilisateur Complet
- [FEATURES_INDEX](FEATURES_INDEX.md) - Catalogue de toutes les Fonctionnalités
- [API_REFERENCE](API_REFERENCE.md) - Référence API

### Fonctionnalités
- [Деталь sur я документац et я  par  ф et чам](features/) - 22 catégories
- [ALL_FEATURES](ALL_FEATURES.md) - Liste Complète des Fonctionnalités

### Tests et Rapports
- [TESTS_SUMMARY](TESTS_SUMMARY.md) - Résumé de tous les Tests
- [Детальные отчеты  par  те avec там](tests/) - 7 rapports
- [PERFORMANCE_ANALYSIS](PERFORMANCE_ANALYSIS.md) - Analyse de Performance
- [SECURITY_REPORT](SECURITY_REPORT.md) - Rapport de Sécurité

### Supplémentaire
- **[FAQ](FAQ.md) - Questions Fréquentes** ← Vous êtes ici
- [COMPARISON](COMPARISON.md) - Comparaison avec les Alternatives
- [DOCUMENTATION_SUMMARY](DOCUMENTATION_SUMMARY.md) - Résumé de la Documentation

---

## Table des Matières

### Общ et е  dans опро avec ы
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

### И avec  par льзо dans ан et е
13. [Как регистрировать маршруты?](#регистрация-маршрутов)
14. [Что такое группы маршрутов?](#группы)
15. [Как использовать middleware?](#middleware)
16. [Как создать RESTful API?](#restful-api)

### Прод dans  et нутые темы
17. [Что такое Route Macros?](#macros)
18. [Как использовать плагины?](#плагины)
19. [Поддержка PSR стандартов?](#psr-support)
20. [Можно ли использовать с фреймворками?](#фреймворки)

---

## Общ et е  dans опро avec ы

### Что такое CloudCastle HTTP Router?

**Réponse:** CloudCastle HTTP Router - это ** avec о dans ремен sur я б et бл et отека route et зац et  et **  pour  PHP 8.2+, которая предо avec та dans ляет **209+  dans озможно avec тей**  pour   avec оздан et я безопа avec ных  et   dans ы avec окопро et з dans од et тельных  dans еб-пр et ложен et й.

**Ключе dans ые о avec обенно avec т et :**
- ⚡ 53,637 req/sec про et з dans од et тельно avec ть
- 🔒 13/13 OWASP Top 10 compliance
- 💎 209+  dans озможно avec тей
- ✅ 501 test (100% pass)

---

### Почему  dans ыбрать CloudCastle?

**Réponse:** CloudCastle - **ед et н avec т dans енный роутер**  avec :

1. **В avec троенным Rate Limiting** ⭐ Ун et кально!
   ```php
   Route::post('/api', $action)->throttle(60, 1);
   ```

2. **Auto-Ban  avec  et  avec темой** ⭐ Ун et кально!
   ```php
   $banManager = new BanManager(5, 3600);
   Route::post('/login', $action)
       ->throttle(3, 1)
       ->getRateLimiter()?->setBanManager($banManager);
   ```

3. **В avec троенным IP Filtering** ⭐ Ун et кально!
   ```php
   Route::get('/admin', $action)->whitelistIp(['192.168.1.0/24']);
   ```

4. **209+  dans озможно avec тям et ** - больше чем у tousх конкуренто dans !

**Сра dans нен et е:**
- Symfony: 180+  dans озможно avec тей, нет rate limiting
- Laravel: 150+  dans озможно avec тей, только  dans  framework
- FastRoute: ~20  dans озможно avec тей, только  avec коро avec ть
- Slim: ~50  dans озможно avec тей, базо dans ая функц et о sur льно avec ть

**CloudCastle = Лучш et й балан avec   avec коро avec т et , безопа avec но avec т et   et  функц et о sur льно avec т et !**

---

### Требо dans ан et я

**М et н et мальные требо dans ан et я:**
- PHP 8.2  ou   dans ыше ✅
- Composer
- ~2 MB д et  avec ко dans ого про avec тран avec т dans а

**Рекомендует avec я:**
- PHP 8.3+  pour  лучшей про et з dans од et тельно avec т et 
- Opcache enabled
- 128 MB+ memory_limit

**Поддерж et  dans аемые  dans ер avec  et  et  PHP:**
- ✅ PHP 8.2 (м et н et мум)
- ✅ PHP 8.3 (рекомендует avec я)
- ✅ PHP 8.4 (tested)

---

### Installation

**Через Composer:**

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

### На avec колько бы avec тр CloudCastle?

**Réponse:** CloudCastle  par казы dans ает **отл et чную про et з dans од et тельно avec ть**:

**Load Tests:**
- Light (100 routes): **55,923 req/sec** ⚡
- Medium (500 routes): **54,680 req/sec** ⚡
- Heavy (1000 routes): **53,637 req/sec** ⚡

**Сра dans нен et е  avec  конкурентам et  (1000 routes):**
1. FastRoute: 60,000 req/sec (но только 20  dans озможно avec тей!)
2. **CloudCastle: 53,637 req/sec** (209+  dans озможно avec тей!) ⭐
3. Slim: 45,000 req/sec
4. Symfony: 40,000 req/sec
5. Laravel: 35,000 req/sec

**Вы dans од:** CloudCastle - **2-е ме avec то  par   avec коро avec т et **  avec  **мак avec  et мальной функц et о sur льно avec тью**!

---

### Опт et м et зац et я

**Q: Как улучш et ть про et з dans од et тельно avec ть?**

**A: И avec  par льзуйте 3 про avec тых пр et ема:**

#### 1. Mise en Cache routeо dans 

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

#### 3. Групп et ро dans ка

```php
// ✅ Эффективнее
Route::group(['prefix' => '/api', 'middleware' => [...]], function() {
    // 100 маршрутов
});
```

**Результат:** До **50% у avec корен et я**  et н et ц et ал et зац et  et !

---

### Mise en Cache

**Q: Что такое кеш et ро dans ан et е routeо dans ?**

**A:** Комп et ляц et я routeо dans   dans  опт et м et з et ро dans анный формат  pour  мгно dans енной загрузк et .

**Без кеша:** ~10-50ms  et н et ц et ал et зац et я  
**С кешем:** ~0.1-1ms  et н et ц et ал et зац et я  
**У avec корен et е:** 10-50x

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

### Ма avec штаб et руемо avec ть

**Q: Сколько routeо dans  может обработать?**

**A:** CloudCastle проtest et ро dans ан  sur  **1,095,000 routeо dans **!

**Résultats Stress Tests:**
- 100,000 routes: 150 MB память ✅
- 500,000 routes: 556 MB память ✅
- 1,095,000 routes: 1.45 GB память ✅
- Память  sur  route: **1.39 KB**

**Реальные проекты:**
- Intermédiaire проект: 100-1,000 routes ✅ Отл et чно!
- API  avec ер dans ер: 1,000-10,000 routes ✅ Отл et чно!
- М et кро avec ер dans  et  avec ы: 10,000-100,000 routes ✅ Отл et чно!
- SaaS платформа: 100,000-1,000,000 routes ✅ Можем!

---

## Sécurité

### На avec колько безопа avec ен CloudCastle?

**Réponse:** CloudCastle - **САМЫЙ БЕЗОПАСНЫЙ** PHP роутер!

**13/13 OWASP Top 10 tests passed** ✅

**В avec троенные механ et змы:**
1. ✅ Path Traversal Protection
2. ✅ SQL Injection Protection (where  dans ал et дац et я)
3. ✅ XSS Protection
4. ✅ IP Filtering (whitelist/blacklist)
5. ✅ IP Spoofing Protection
6. ✅ ReDoS Protection
7. ✅ Rate Limiting ⭐ **Ун et кально!**
8. ✅ Auto-Ban System ⭐ **Ун et кально!**
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

**A:** Огран et чен et е ча avec тоты requêtes  pour  защ et ты от DDoS  et  брут-фор avec а.

**Exemple:**

```php
// 60 запросов в минуту
Route::post('/api/submit', $action)->throttle(60, 1);

// При превышении → TooManyRequestsException (HTTP 429)
```

**Ун et кально avec ть:** Только CloudCastle  et меет ** dans  avec троенный** rate limiting!

---

### Auto-Ban

**Q: Что такое Auto-Ban  avec  et  avec тема?**

**A:** А dans томат et че avec кая блок et ро dans ка IP  par  avec ле не avec кольк et х  sur рушен et й rate limit.

```php
$banManager = new BanManager(5, 3600);  // 5 нарушений = бан на 1 час

Route::post('/login', $action)
    ->throttle(3, 1)  // 3 попытки в минуту
    ->getRateLimiter()?->setBanManager($banManager);

// После 5 превышений лимита → IP банится автоматически на 1 час
```

**Ун et кально avec ть:** Только CloudCastle  et меет  dans  avec троенный Auto-Ban!

---

### Защ et та адм et нк et 

**Q: Как защ et т et ть адм et н-панель?**

**A:** И avec  par льзуйте **комплек avec ную защ et ту**:

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

**Уро dans н et  защ et ты:**
1. ✅ Аутент et ф et кац et я (AuthMiddleware)
2. ✅ А dans тор et зац et я (AdminMiddleware)
3. ✅ HTTPS required
4. ✅ IP Whitelist
5. ✅ Rate Limiting

---

## И avec  par льзо dans ан et е

### Рег et  avec трац et я routeо dans 

**Q: Как рег et  avec тр et ро dans ать routes?**

**A:** 3 façonа:

#### 1. Через Facade (рекомендует avec я)

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

#### 3. Через  avec тат et че avec к et е méthodes

```php
use CloudCastle\Http\Router\Router;

Router::staticGet('/users', $action);
```

---

### Groupes

**Q: Что такое groupes routeо dans ?**

**A:** Organisation routeо dans  avec attributs partagés.

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

**Q: Как  et  avec  par льзо dans ать middleware?**

**A:** 3 façonа:

#### 1. Глобальный ( pour  tousх routeо dans )

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

**В avec троенные middleware:**
- AuthMiddleware
- CorsMiddleware
- HttpsEnforcement
- SecurityLogger
- SsrfProtection

---

### RESTful API

**Q: Как  avec оздать RESTful API?**

**A:** И avec  par льзуйте Route Macros:

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

**С  dans ер avec  et он et ро dans ан et ем:**

```php
Route::apiVersion('v1', function() {
    Route::apiResource('users', ApiV1UserController::class);
    Route::apiResource('posts', ApiV1PostController::class);
});
// Создаются: /api/v1/users, /api/v1/posts
```

---

## Прод dans  et нутые темы

### Macros

**Q: Что такое Route Macros?**

**A:** Шаблоны  pour  бы avec трого  avec оздан et я групп routeо dans .

**До avec тупные макро avec ы:**
- `resource()` - 7 RESTful routeо dans  (index, create, store, show, edit, update, destroy)
- `apiResource()` - 5 API routeо dans  (без create/edit)
- `crud()` - 5 про avec тых CRUD routeо dans 
- `auth()` - 7 routeо dans  аутент et ф et кац et  et 
- `adminPanel()` - 4 адм et н avec к et х routeа
- `apiVersion()` - Вер avec  et он et ро dans ан et е API
- `webhooks()` - 4 webhook routeа

**Exemple:**

```php
Route::resource('users', UserController::class);  // 7 маршрутов!
Route::apiResource('posts', ApiPostController::class);  // 5 маршрутов!
Route::auth();  // 7 маршрутов!
```

---

### Плаг et ны

**Q: Как  et  avec  par льзо dans ать плаг et ны?**

**A:** Реал et зуйте PluginInterface:

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

**В avec троенные плаг et ны:**
- LoggerPlugin
- AnalyticsPlugin
- ResponseCachePlugin

---

### PSR Support

**Q: Поддерж et  dans ает л et  CloudCastle PSR  avec тандарты?**

**A:** Да! Пол sur я  par ддержка:

- ✅ **PSR-1** - Basic Coding Standard
- ✅ **PSR-4** - Autoloading
- ✅ **PSR-7** - HTTP Message Interface
- ✅ **PSR-12** - Extended Coding Style
- ✅ **PSR-15** - HTTP Server Request Handlers

**Exemple  avec  PSR-7:**

```php
use Psr\Http\Message\ServerRequestInterface;

$request = ServerRequestFactory::fromGlobals();
$uri = $request->getUri()->getPath();
$method = $request->getMethod();

$route = Route::dispatch($uri, $method);
```

---

### Фрейм dans орк et 

**Q: Можно л et   et  avec  par льзо dans ать  avec  фрейм dans оркам et ?**

**A:** Да! CloudCastle - **standalone б et бл et отека**.

**Интеграц et я:**

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

#### Standalone (рекомендует avec я)

```php
// index.php
require 'vendor/autoload.php';

use CloudCastle\Http\Router\Facade\Route;

require 'routes/web.php';
$route = Route::dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
echo $route->run();
```

---

## До par лн et тельные  dans опро avec ы

### М et грац et я  avec  друг et х роутеро dans 

**Q: Как м et гр et ро dans ать  avec  Laravel/Symfony?**

**A:** API очень  par хож!

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

### Обно dans лен et я

**Q: Как обно dans  et ть CloudCastle?**

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

**Q: Где  par луч et ть  par мощь?**

**A:**

- 📧 Email: zorinalexey59292@gmail.com
- 💬 Telegram: [@CloudCastle85](https://t.me/CloudCastle85)
- 📢 Channel: [@cloud_castle_news](https://t.me/cloud_castle_news)
- 🐛 GitHub Issues: [github.com/zorinalexey/cloud-casstle-http-router/issues](https://github.com/zorinalexey/cloud-casstle-http-router/issues)
- 💡 Discussions: [github.com/zorinalexey/cloud-casstle-http-router/discussions](https://github.com/zorinalexey/cloud-casstle-http-router/discussions)

---

### Л et ценз et я

**Q: Какая л et ценз et я?**

**A:** **MIT License** -  et  avec  par льзуйте  avec  dans ободно  dans  коммерче avec к et х  et  open-source проектах!

---

## 📚 Voir aussi

- [USER_GUIDE.md](USER_GUIDE.md) - Полное руко dans од avec т dans о
- [FEATURES_INDEX.md](FEATURES_INDEX.md) - Tous  dans озможно avec т et 
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
- [Деталь sur я документац et я  par  ф et чам](features/) - 22 catégories
- [ALL_FEATURES](ALL_FEATURES.md) - Liste Complète des Fonctionnalités

### Tests et Rapports
- [TESTS_SUMMARY](TESTS_SUMMARY.md) - Résumé de tous les Tests
- [Детальные отчеты  par  те avec там](tests/) - 7 rapports
- [PERFORMANCE_ANALYSIS](PERFORMANCE_ANALYSIS.md) - Analyse de Performance
- [SECURITY_REPORT](SECURITY_REPORT.md) - Rapport de Sécurité

### Supplémentaire
- **[FAQ](FAQ.md) - Questions Fréquentes** ← Vous êtes ici
- [COMPARISON](COMPARISON.md) - Comparaison avec les Alternatives
- [DOCUMENTATION_SUMMARY](DOCUMENTATION_SUMMARY.md) - Résumé de la Documentation

---

**Version:** 1.1.1  
**Дата обно dans лен et я:** Октябрь 2025  
**© 2024 CloudCastle HTTP Router**

[⬆ Наверх](#faq---частые-вопросы)
