# FAQ - 常见问题

[English](../en/FAQ.md) | [Русский](../ru/FAQ.md) | [Deutsch](../de/FAQ.md) | [Français](../fr/FAQ.md) | **中文**

---







**版本：** 1.1.1  
**日期：** 十月 2025

---

## 📚 文档导航

### 主要文档
- [README](../../README.md) - 主页
- [USER_GUIDE](USER_GUIDE.md) - 完整用户指南
- [FEATURES_INDEX](FEATURES_INDEX.md) - 所有功能目录
- [API_REFERENCE](API_REFERENCE.md) - API 参考

### 功能
- [Детальная документация по фичам](features/) - 22 
- [ALL_FEATURES](ALL_FEATURES.md) - 完整功能列表

### 测试和报告
- [TESTS_SUMMARY](TESTS_SUMMARY.md) - 所有测试摘要
- [Детальные отчеты по тестам](tests/) - 7 报告
- [PERFORMANCE_ANALYSIS](PERFORMANCE_ANALYSIS.md) - 性能分析
- [SECURITY_REPORT](SECURITY_REPORT.md) - 安全报告

### 附加信息
- **[FAQ](FAQ.md) - 常见问题** ← 您在这里
- [COMPARISON](COMPARISON.md) - 与替代方案比较
- [DOCUMENTATION_SUMMARY](DOCUMENTATION_SUMMARY.md) - 文档摘要

---

## 目录

###  
1. [Что такое CloudCastle HTTP Router?](#что-такое-cloudcastle-http-router)
2. [Почему выбрать CloudCastle вместо других роутеров?](#почему-выбрать-cloudcastle)
3. [Какие требования для использования?](#требования)
4. [Как установить CloudCastle?](#установка)

### 性能
5. [Насколько быстр CloudCastle?](#производительность)
6. [Как улучшить производительность?](#оптимизация)
7. [Что такое кеширование маршрутов?](#кеширование)
8. [Сколько маршрутов может обработать?](#масштабируемость)

### 安全性
9. [Насколько безопасен CloudCastle?](#безопасность)
10. [Что такое Rate Limiting?](#rate-limiting)
11. [Что такое Auto-Ban система?](#auto-ban)
12. [Как защитить админку?](#защита-админки)

### 
13. [Как регистрировать маршруты?](#регистрация-маршрутов)
14. [Что такое группы маршрутов?](#группы)
15. [Как использовать middleware?](#middleware)
16. [Как создать RESTful API?](#restful-api)

###  
17. [Что такое Route Macros?](#macros)
18. [Как использовать плагины?](#плагины)
19. [Поддержка PSR стандартов?](#psr-support)
20. [Можно ли использовать с фреймворками?](#фреймворки)

---

##  

###   CloudCastle HTTP Router?

**响应:** CloudCastle HTTP Router -  **  路由**  PHP 8.2+,   **209+ **      -.

** :**
- ⚡ 53,637 req/sec 
- 🔒 13/13 OWASP Top 10 compliance
- 💎 209+ 
- ✅ 501 测试 (100% pass)

---

###   CloudCastle?

**响应:** CloudCastle - ** ** :

1. ** Rate Limiting** ⭐ !
   ```php
   Route::post('/api', $action)->throttle(60, 1);
   ```

2. **Auto-Ban ** ⭐ !
   ```php
   $banManager = new BanManager(5, 3600);
   Route::post('/login', $action)
       ->throttle(3, 1)
       ->getRateLimiter()?->setBanManager($banManager);
   ```

3. ** IP Filtering** ⭐ !
   ```php
   Route::get('/admin', $action)->whitelistIp(['192.168.1.0/24']);
   ```

4. **209+ ** -    所有 !

**:**
- Symfony: 180+ ,  rate limiting
- Laravel: 150+ ,   framework
- FastRoute: ~20 ,  
- Slim: ~50 ,  

**CloudCastle =   ,   !**

---

### 

** :**
- PHP 8.2   ✅
- Composer
- ~2 MB  

**:**
- PHP 8.3+   
- Opcache enabled
- 128 MB+ memory_limit

**  PHP:**
- ✅ PHP 8.2 ()
- ✅ PHP 8.3 ()
- ✅ PHP 8.4 (tested)

---

### 安装

** Composer:**

```bash
composer require cloud-castle/http-router
```

**快速开始:**

```php
<?php

require 'vendor/autoload.php';

use CloudCastle\Http\Router\Facade\Route;

Route::get('/users', fn() => 'Users list');

$route = Route::dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
echo $route->run();
```

---

## 性能

###   CloudCastle?

**响应:** CloudCastle  ** **:

**Load Tests:**
- Light (100 routes): **55,923 req/sec** ⚡
- Medium (500 routes): **54,680 req/sec** ⚡
- Heavy (1000 routes): **53,637 req/sec** ⚡

**   (1000 routes):**
1. FastRoute: 60,000 req/sec (  20 !)
2. **CloudCastle: 53,637 req/sec** (209+ !) ⭐
3. Slim: 45,000 req/sec
4. Symfony: 40,000 req/sec
5. Laravel: 35,000 req/sec

**:** CloudCastle - **2-   **  ** **!

---

### 

**Q:   ?**

**A:  3  :**

#### 1.  路由

```php
$router->enableCache('cache/routes');

if (!$router->loadFromCache()) {
    require 'routes/web.php';
    $router->compile();
}

// Ускорение: 10-50x!
```

#### 2. Inline 参数

```php
// ✅ Быстрее
Route::get('/users/{id:[0-9]+}', $action);

// ⚠️ Медленнее
Route::get('/users/{id}', $action)->where('id', '[0-9]+');
```

#### 3. 

```php
// ✅ Эффективнее
Route::group(['prefix' => '/api', 'middleware' => [...]], function() {
    // 100 маршрутов
});
```

**:**  **50% ** !

---

### 

**Q:    路由?**

**A:**  路由      .

** :** ~10-50ms   
** :** ~0.1-1ms   
**:** 10-50x

**示例:**

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

### 

**Q:  路由  ?**

**A:** CloudCastle 测试  **1,095,000 路由**!

**结果 Stress Tests:**
- 100,000 routes: 150 MB  ✅
- 500,000 routes: 556 MB  ✅
- 1,095,000 routes: 1.45 GB  ✅
-   路由: **1.39 KB**

** :**
- 中级 : 100-1,000 routes ✅ !
- API : 1,000-10,000 routes ✅ !
- : 10,000-100,000 routes ✅ !
- SaaS : 100,000-1,000,000 routes ✅ !

---

## 安全性

###   CloudCastle?

**响应:** CloudCastle - ** ** PHP !

**13/13 OWASP Top 10 测试 passed** ✅

** :**
1. ✅ Path Traversal Protection
2. ✅ SQL Injection Protection (where )
3. ✅ XSS Protection
4. ✅ IP Filtering (whitelist/blacklist)
5. ✅ IP Spoofing Protection
6. ✅ ReDoS Protection
7. ✅ Rate Limiting ⭐ **!**
8. ✅ Auto-Ban System ⭐ **!**
9. ✅ HTTPS Enforcement
10. ✅ Protocol Restrictions
11. ✅ Domain/Port Security
12. ✅ Cache Injection Protection

**:**
- Symfony: 10/13 OWASP
- Laravel: 9/13 OWASP
- FastRoute: 3/13 OWASP
- Slim: 4/13 OWASP

---

### Rate Limiting

**Q:   Rate Limiting?**

**A:**   请求    DDoS  -.

**示例:**

```php
// 60 запросов в минуту
Route::post('/api/submit', $action)->throttle(60, 1);

// При превышении → TooManyRequestsException (HTTP 429)
```

**:**  CloudCastle  **** rate limiting!

---

### Auto-Ban

**Q:   Auto-Ban ?**

**A:**   IP    rate limit.

```php
$banManager = new BanManager(5, 3600);  // 5 нарушений = бан на 1 час

Route::post('/login', $action)
    ->throttle(3, 1)  // 3 попытки в минуту
    ->getRateLimiter()?->setBanManager($banManager);

// После 5 превышений лимита → IP банится автоматически на 1 час
```

**:**  CloudCastle   Auto-Ban!

---

###  

**Q:   -?**

**A:**  ** **:

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

** :**
1. ✅  (AuthMiddleware)
2. ✅  (AdminMiddleware)
3. ✅ HTTPS required
4. ✅ IP Whitelist
5. ✅ Rate Limiting

---

## 

###  路由

**Q:   路由?**

**A:** 3 方式:

#### 1.  Facade ()

```php
use CloudCastle\Http\Router\Facade\Route;

Route::get('/users', [UserController::class, 'index']);
Route::post('/users', [UserController::class, 'store']);
```

#### 2.   Router

```php
use CloudCastle\Http\Router\Router;

$router = new Router();
$router->get('/users', $action);
```

#### 3.   方法

```php
use CloudCastle\Http\Router\Router;

Router::staticGet('/users', $action);
```

---

### 组

**Q:   组 路由?**

**A:** 组织 路由 具有共享属性.

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

**12 属性 组:**
- prefix, middleware, domain, port, namespace
- https, protocols, tags, throttle
- whitelistIp, blacklistIp, name

---

### Middleware

**Q:   middleware?**

**A:** 3 方式:

#### 1.  ( 所有 路由)

```php
Route::middleware([CorsMiddleware::class]);
```

#### 2.  路由

```php
Route::get('/dashboard', $action)
    ->middleware([AuthMiddleware::class]);
```

#### 3.  

```php
Route::group(['middleware' => [AuthMiddleware::class]], function() {
    // Все маршруты с Auth
});
```

** middleware:**
- AuthMiddleware
- CorsMiddleware
- HttpsEnforcement
- SecurityLogger
- SsrfProtection

---

### RESTful API

**Q:   RESTful API?**

**A:**  Route Macros:

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

** :**

```php
Route::apiVersion('v1', function() {
    Route::apiResource('users', ApiV1UserController::class);
    Route::apiResource('posts', ApiV1PostController::class);
});
// Создаются: /api/v1/users, /api/v1/posts
```

---

##  

### Macros

**Q:   Route Macros?**

**A:**      路由.

** :**
- `resource()` - 7 RESTful 路由 (index, create, store, show, edit, update, destroy)
- `apiResource()` - 5 API 路由 ( create/edit)
- `crud()` - 5  CRUD 路由
- `auth()` - 7 路由 
- `adminPanel()` - 4  路由
- `apiVersion()` -  API
- `webhooks()` - 4 webhook 路由

**示例:**

```php
Route::resource('users', UserController::class);  // 7 маршрутов!
Route::apiResource('posts', ApiPostController::class);  // 5 маршрутов!
Route::auth();  // 7 маршрутов!
```

---

### 

**Q:   ?**

**A:**  PluginInterface:

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

** :**
- LoggerPlugin
- AnalyticsPlugin
- ResponseCachePlugin

---

### PSR Support

**Q:   CloudCastle PSR ?**

**A:** !  :

- ✅ **PSR-1** - Basic Coding Standard
- ✅ **PSR-4** - Autoloading
- ✅ **PSR-7** - HTTP Message Interface
- ✅ **PSR-12** - Extended Coding Style
- ✅ **PSR-15** - HTTP Server Request Handlers

**示例  PSR-7:**

```php
use Psr\Http\Message\ServerRequestInterface;

$request = ServerRequestFactory::fromGlobals();
$uri = $request->getUri()->getPath();
$method = $request->getMethod();

$route = Route::dispatch($uri, $method);
```

---

### 

**Q:     ?**

**A:** ! CloudCastle - **standalone **.

**:**

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

#### Standalone ()

```php
// index.php
require 'vendor/autoload.php';

use CloudCastle\Http\Router\Facade\Route;

require 'routes/web.php';
$route = Route::dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
echo $route->run();
```

---

##  

###    

**Q:    Laravel/Symfony?**

**A:** API  !

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

### 

**Q:   CloudCastle?**

**A:**

```bash
composer update cloud-castle/http-router

# Проверить changelog
cat vendor/cloud-castle/http-router/CHANGELOG.md

# Очистить кеш маршрутов
rm -rf cache/routes/*
```

---

### 

**Q:   ?**

**A:**

- 📧 Email: zorinalexey59292@gmail.com
- 💬 Telegram: [@CloudCastle85](https://t.me/CloudCastle85)
- 📢 Channel: [@cloud_castle_news](https://t.me/cloud_castle_news)
- 🐛 GitHub Issues: [github.com/zorinalexey/cloud-casstle-http-router/issues](https://github.com/zorinalexey/cloud-casstle-http-router/issues)
- 💡 Discussions: [github.com/zorinalexey/cloud-casstle-http-router/discussions](https://github.com/zorinalexey/cloud-casstle-http-router/discussions)

---

### 

**Q:  ?**

**A:** **MIT License** -      open-source !

---

## 📚 . 

- [USER_GUIDE.md](USER_GUIDE.md) -  
- [FEATURES_INDEX.md](FEATURES_INDEX.md) - 所有 
- [TESTS_SUMMARY.md](TESTS_SUMMARY.md) - 结果 测试
- [COMPARISON.md](COMPARISON.md) - 与替代方案比较

---

## 📚 文档导航

### 主要文档
- [README](../../README.md) - 主页
- [USER_GUIDE](USER_GUIDE.md) - 完整用户指南
- [FEATURES_INDEX](FEATURES_INDEX.md) - 所有功能目录
- [API_REFERENCE](API_REFERENCE.md) - API 参考

### 功能
- [Детальная документация по фичам](features/) - 22 
- [ALL_FEATURES](ALL_FEATURES.md) - 完整功能列表

### 测试和报告
- [TESTS_SUMMARY](TESTS_SUMMARY.md) - 所有测试摘要
- [Детальные отчеты по тестам](tests/) - 7 报告
- [PERFORMANCE_ANALYSIS](PERFORMANCE_ANALYSIS.md) - 性能分析
- [SECURITY_REPORT](SECURITY_REPORT.md) - 安全报告

### 附加信息
- **[FAQ](FAQ.md) - 常见问题** ← 您在这里
- [COMPARISON](COMPARISON.md) - 与替代方案比较
- [DOCUMENTATION_SUMMARY](DOCUMENTATION_SUMMARY.md) - 文档摘要

---

**版本：** 1.1.1  
** :** 十月 2025  
**© 2024 CloudCastle HTTP Router**

[⬆ Наверх](#faq---частые-вопросы)
