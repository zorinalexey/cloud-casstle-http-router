# FAQ - Häufig gestellte Fragen

**Version:** 1.1.1  
**Datum:** Oktober 2025

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

### Общие вопросы
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

### Использование
13. [Как регистрировать маршруты?](#регистрация-маршрутов)
14. [Что такое группы маршрутов?](#группы)
15. [Как использовать middleware?](#middleware)
16. [Как создать RESTful API?](#restful-api)

### Продвинутые темы
17. [Что такое Route Macros?](#macros)
18. [Как использовать плагины?](#плагины)
19. [Поддержка PSR стандартов?](#psr-support)
20. [Можно ли использовать с фреймворками?](#фреймворки)

---

## Общие вопросы

### Что такое CloudCastle HTTP Router?

**Ответ:** CloudCastle HTTP Router - это **современная библиотека Routeизации** для PHP 8.2+, которая предоставляет **209+ возможностей** для создания безопасных и высокопроизводительных веб-приложений.

**Ключевые особенности:**
- ⚡ 53,637 req/sec производительность
- 🔒 13/13 OWASP Top 10 compliance
- 💎 209+ возможностей
- ✅ 501 Test (100% pass)

---

### Почему выбрать CloudCastle?

**Ответ:** CloudCastle - **единственный роутер** с:

1. **Встроенным Rate Limiting** ⭐ Уникально!
   ```php
   Route::post('/api', $action)->throttle(60, 1);
   ```

2. **Auto-Ban системой** ⭐ Уникально!
   ```php
   $banManager = new BanManager(5, 3600);
   Route::post('/login', $action)
       ->throttle(3, 1)
       ->getRateLimiter()?->setBanManager($banManager);
   ```

3. **Встроенным IP Filtering** ⭐ Уникально!
   ```php
   Route::get('/admin', $action)->whitelistIp(['192.168.1.0/24']);
   ```

4. **209+ возможностями** - больше чем у всех конкурентов!

**Сравнение:**
- Symfony: 180+ возможностей, нет rate limiting
- Laravel: 150+ возможностей, только в framework
- FastRoute: ~20 возможностей, только скорость
- Slim: ~50 возможностей, базовая функциональность

**CloudCastle = Лучший баланс скорости, безопасности и функциональности!**

---

### Требования

**Минимальные требования:**
- PHP 8.2 или выше ✅
- Composer
- ~2 MB дискового пространства

**Рекомендуется:**
- PHP 8.3+ для лучшей производительности
- Opcache enabled
- 128 MB+ memory_limit

**Поддерживаемые версии PHP:**
- ✅ PHP 8.2 (минимум)
- ✅ PHP 8.3 (рекомендуется)
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

### Насколько быстр CloudCastle?

**Ответ:** CloudCastle показывает **отличную производительность**:

**Load Tests:**
- Light (100 routes): **55,923 req/sec** ⚡
- Medium (500 routes): **54,680 req/sec** ⚡
- Heavy (1000 routes): **53,637 req/sec** ⚡

**Сравнение с конкурентами (1000 routes):**
1. FastRoute: 60,000 req/sec (но только 20 возможностей!)
2. **CloudCastle: 53,637 req/sec** (209+ возможностей!) ⭐
3. Slim: 45,000 req/sec
4. Symfony: 40,000 req/sec
5. Laravel: 35,000 req/sec

**Вывод:** CloudCastle - **2-е место по скорости** с **максимальной функциональностью**!

---

### Оптимизация

**Q: Как улучшить производительность?**

**A: Используйте 3 простых приема:**

#### 1. Кеширование Routeов

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

#### 3. Группировка

```php
// ✅ Эффективнее
Route::group(['prefix' => '/api', 'middleware' => [...]], function() {
    // 100 маршрутов
});
```

**Результат:** До **50% ускорения** инициализации!

---

### Кеширование

**Q: Что такое кеширование Routeов?**

**A:** Компиляция Routeов в оптимизированный формат для мгновенной загрузки.

**Без кеша:** ~10-50ms инициализация  
**С кешем:** ~0.1-1ms инициализация  
**Ускорение:** 10-50x

**Пример:**

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

### Масштабируемость

**Q: Сколько Routeов может обработать?**

**A:** CloudCastle проTestирован на **1,095,000 Routeов**!

**Ergebnisse Stress Tests:**
- 100,000 routes: 150 MB память ✅
- 500,000 routes: 556 MB память ✅
- 1,095,000 routes: 1.45 GB память ✅
- Память на Route: **1.39 KB**

**Реальные проекты:**
- Средний проект: 100-1,000 routes ✅ Отлично!
- API сервер: 1,000-10,000 routes ✅ Отлично!
- Микросервисы: 10,000-100,000 routes ✅ Отлично!
- SaaS платформа: 100,000-1,000,000 routes ✅ Можем!

---

## Sicherheit

### Насколько безопасен CloudCastle?

**Ответ:** CloudCastle - **САМЫЙ БЕЗОПАСНЫЙ** PHP роутер!

**13/13 OWASP Top 10 Testов passed** ✅

**Встроенные механизмы:**
1. ✅ Path Traversal Protection
2. ✅ SQL Injection Protection (where валидация)
3. ✅ XSS Protection
4. ✅ IP Filtering (whitelist/blacklist)
5. ✅ IP Spoofing Protection
6. ✅ ReDoS Protection
7. ✅ Rate Limiting ⭐ **Уникально!**
8. ✅ Auto-Ban System ⭐ **Уникально!**
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

**A:** Ограничение частоты Anfrageов для защиты от DDoS и брут-форса.

**Пример:**

```php
// 60 запросов в минуту
Route::post('/api/submit', $action)->throttle(60, 1);

// При превышении → TooManyRequestsException (HTTP 429)
```

**Уникальность:** Только CloudCastle имеет **встроенный** rate limiting!

---

### Auto-Ban

**Q: Что такое Auto-Ban система?**

**A:** Автоматическая блокировка IP после нескольких нарушений rate limit.

```php
$banManager = new BanManager(5, 3600);  // 5 нарушений = бан на 1 час

Route::post('/login', $action)
    ->throttle(3, 1)  // 3 попытки в минуту
    ->getRateLimiter()?->setBanManager($banManager);

// После 5 превышений лимита → IP банится автоматически на 1 час
```

**Уникальность:** Только CloudCastle имеет встроенный Auto-Ban!

---

### Защита админки

**Q: Как защитить админ-панель?**

**A:** Используйте **комплексную защиту**:

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

**Уровни защиты:**
1. ✅ Аутентификация (AuthMiddleware)
2. ✅ Авторизация (AdminMiddleware)
3. ✅ HTTPS required
4. ✅ IP Whitelist
5. ✅ Rate Limiting

---

## Использование

### Регистрация Routeов

**Q: Как регистрировать Routen?**

**A:** 3 способа:

#### 1. Через Facade (рекомендуется)

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

#### 3. Через статические Methoden

```php
use CloudCastle\Http\Router\Router;

Router::staticGet('/users', $action);
```

---

### Группы

**Q: Что такое Gruppen Routeов?**

**A:** Организация Routeов с общими атрибутами.

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

**12 атрибутов Gruppen:**
- prefix, Middleware, domain, port, namespace
- https, protocols, tags, throttle
- whitelistIp, blacklistIp, name

---

### Middleware

**Q: Как использовать Middleware?**

**A:** 3 способа:

#### 1. Глобальный (для всех Routeов)

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

**Встроенные Middleware:**
- AuthMiddleware
- CorsMiddleware
- HttpsEnforcement
- SecurityLogger
- SsrfProtection

---

### RESTful API

**Q: Как создать RESTful API?**

**A:** Используйте Route Macros:

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

**С версионированием:**

```php
Route::apiVersion('v1', function() {
    Route::apiResource('users', ApiV1UserController::class);
    Route::apiResource('posts', ApiV1PostController::class);
});
// Создаются: /api/v1/users, /api/v1/posts
```

---

## Продвинутые темы

### Macros

**Q: Что такое Route Macros?**

**A:** Шаблоны для быстрого создания групп Routeов.

**Доступные макросы:**
- `resource()` - 7 RESTful Routeов (index, create, store, show, edit, update, destroy)
- `apiResource()` - 5 API Routeов (без create/edit)
- `crud()` - 5 простых CRUD Routeов
- `auth()` - 7 Routeов аутентификации
- `adminPanel()` - 4 админских Routeа
- `apiVersion()` - Версионирование API
- `webhooks()` - 4 webhook Routeа

**Пример:**

```php
Route::resource('users', UserController::class);  // 7 маршрутов!
Route::apiResource('posts', ApiPostController::class);  // 5 маршрутов!
Route::auth();  // 7 маршрутов!
```

---

### Плагины

**Q: Как использовать плагины?**

**A:** Реализуйте PluginInterface:

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

**Встроенные плагины:**
- LoggerPlugin
- AnalyticsPlugin
- ResponseCachePlugin

---

### PSR Support

**Q: Поддерживает ли CloudCastle PSR стандарты?**

**A:** Да! Полная поддержка:

- ✅ **PSR-1** - Basic Coding Standard
- ✅ **PSR-4** - Autoloading
- ✅ **PSR-7** - HTTP Message Interface
- ✅ **PSR-12** - Extended Coding Style
- ✅ **PSR-15** - HTTP Server Request Handlers

**Пример с PSR-7:**

```php
use Psr\Http\Message\ServerRequestInterface;

$request = ServerRequestFactory::fromGlobals();
$uri = $request->getUri()->getPath();
$method = $request->getMethod();

$route = Route::dispatch($uri, $method);
```

---

### Фреймворки

**Q: Можно ли использовать с фреймворками?**

**A:** Да! CloudCastle - **standalone библиотека**.

**Интеграция:**

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

#### Standalone (рекомендуется)

```php
// index.php
require 'vendor/autoload.php';

use CloudCastle\Http\Router\Facade\Route;

require 'routes/web.php';
$route = Route::dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
echo $route->run();
```

---

## Дополнительные вопросы

### Миграция с других роутеров

**Q: Как мигрировать с Laravel/Symfony?**

**A:** API очень похож!

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

### Обновления

**Q: Как обновить CloudCastle?**

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

**Q: Где получить помощь?**

**A:**

- 📧 Email: zorinalexey59292@gmail.com
- 💬 Telegram: [@CloudCastle85](https://t.me/CloudCastle85)
- 📢 Channel: [@cloud_castle_news](https://t.me/cloud_castle_news)
- 🐛 GitHub Issues: [github.com/zorinalexey/cloud-casstle-http-router/issues](https://github.com/zorinalexey/cloud-casstle-http-router/issues)
- 💡 Discussions: [github.com/zorinalexey/cloud-casstle-http-router/discussions](https://github.com/zorinalexey/cloud-casstle-http-router/discussions)

---

### Лицензия

**Q: Какая лицензия?**

**A:** **MIT License** - используйте свободно в коммерческих и open-source проектах!

---

## 📚 См. также

- [USER_GUIDE.md](USER_GUIDE.md) - Полное руководство
- [FEATURES_INDEX.md](FEATURES_INDEX.md) - Все возможности
- [TESTS_SUMMARY.md](TESTS_SUMMARY.md) - Ergebnisse Testов
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
**Дата обновления:** Oktober 2025  
**© 2024 CloudCastle HTTP Router**

[⬆ Наверх](#faq---частые-вопросы)
