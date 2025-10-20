# CloudCastle HTTP Router

[![PHP Version](https://img.shields.io/badge/PHP-8.2%2B-blue.svg)](https://php.net)
[![License](https://img.shields.io/badge/license-MIT-green.svg)](LICENSE)
[![Tests](https://img.shields.io/badge/tests-501%2F501-success.svg)](docs/ru/TESTS_DETAILED.md)
[![PHPStan](https://img.shields.io/badge/PHPStan-Level%20Max-brightgreen.svg)](reports/phpstan.txt)
[![Performance](https://img.shields.io/badge/performance-54k%20req%2Fsec-brightgreen.svg)](docs/ru/PERFORMANCE_ANALYSIS.md)
[![Features](https://img.shields.io/badge/features-209%2B-blue.svg)](FEATURES_LIST.md)

**Мощная, гибкая и безопасная библиотека HTTP маршрутизации для PHP 8.2+** с фокусом на производительность, безопасность и удобство использования.

[English](docs/en/USER_GUIDE.md) | **Русский** | [Документация](docs/ru/USER_GUIDE.md)

---

## ⚡ Почему CloudCastle HTTP Router?

### 🎯 Ключевые преимущества

- ⚡ **Высочайшая производительность** - **54,891 req/sec**, быстрее большинства конкурентов
- 🔒 **Комплексная безопасность** - 12+ встроенных механизмов защиты (OWASP Top 10)
- 💎 **209+ возможностей** - самая богатая функциональность на рынке
- 💾 **Минимальное потребление памяти** - всего **1.32 KB на маршрут**
- 📊 **Экстремальная масштабируемость** - протестировано на **1,160,000 маршрутов**
- 🔌 **Расширяемость** - система плагинов, middleware, макросов
- 📦 **Полная автономность** - не зависит от фреймворков
- ✅ **100% надежность** - 501 тест, 0 ошибок, 95%+ coverage

---

## 🚀 Быстрый старт

### Установка

```bash
composer require cloud-castle/http-router
```

### Минимальный пример

```php
<?php

use CloudCastle\Http\Router\Facade\Route;

// Простые маршруты
Route::get('/users', fn() => 'Users list');
Route::post('/users', fn() => 'Create user');
Route::get('/users/{id}', fn($id) => "User: $id")
    ->where('id', '[0-9]+');

// Dispatch
$route = Route::dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
echo $route->run();
```

### Продвинутый пример

```php
// API с защитой
Route::group(['prefix' => '/api/v1'], function() {
    Route::get('/users', [UserController::class, 'index'])
        ->name('api.users')
        ->throttle(100, 1)  // 100 запросов в минуту
        ->middleware([AuthMiddleware::class])
        ->tag('api');
    
    Route::post('/users', [UserController::class, 'store'])
        ->throttle(20, 1)
        ->whitelistIp(['192.168.1.0/24'])
        ->middleware([AuthMiddleware::class, AdminMiddleware::class]);
});
```

---

## 💡 Основные возможности

### 1️⃣ HTTP Методы (7 способов)

```php
Route::get('/users', $action);
Route::post('/users', $action);
Route::put('/users/{id}', $action);
Route::patch('/users/{id}', $action);
Route::delete('/users/{id}', $action);
Route::any('/page', $action);              // Любой метод
Route::match(['GET', 'POST'], '/form', $action);  // Несколько методов
Route::custom('VIEW', '/preview', $action);       // Кастомный метод
```

### 2️⃣ Умные параметры

```php
// Базовые параметры
Route::get('/users/{id}', $action);

// С валидацией
Route::get('/users/{id}', $action)->where('id', '[0-9]+');
Route::get('/posts/{slug}', $action)->where('slug', '[a-z0-9-]+');

// Опциональные
Route::get('/blog/{category?}', $action);

// Значения по умолчанию
Route::get('/posts/{page}', $action)->defaults(['page' => 1]);

// Inline patterns
Route::get('/users/{id:[0-9]+}', $action);
```

### 3️⃣ Группы маршрутов

```php
Route::group([
    'prefix' => '/api/v1',
    'middleware' => [AuthMiddleware::class],
    'domain' => 'api.example.com',
    'port' => 8080,
    'namespace' => 'App\\Controllers\\Api',
], function() {
    Route::get('/users', 'UserController@index');
    Route::get('/posts', 'PostController@index');
});
```

### 4️⃣ Rate Limiting & Auto-Ban

```php
// Rate limiting
Route::post('/api/login', $action)
    ->throttle(5, 1);  // 5 попыток в минуту

// С TimeUnit enum
use CloudCastle\Http\Router\TimeUnit;

Route::post('/api/submit', $action)
    ->throttle(100, TimeUnit::HOUR->value);

// Auto-ban система
use CloudCastle\Http\Router\BanManager;

$banManager = new BanManager(
    maxViolations: 5,      // 5 нарушений
    banDuration: 3600      // Бан на 1 час
);

Route::post('/login', $action)
    ->throttle(3, 1)
    ->getRateLimiter()
    ?->setBanManager($banManager);
```

### 5️⃣ IP Filtering

```php
// Whitelist (только разрешенные IP)
Route::get('/admin', $action)
    ->whitelistIp(['192.168.1.1', '10.0.0.0/8']);

// Blacklist (заблокированные IP)
Route::get('/public', $action)
    ->blacklistIp(['1.2.3.4', '5.6.7.0/24']);

// В группе
Route::group(['whitelistIp' => ['192.168.1.0/24']], function() {
    Route::get('/admin/users', $action);
    Route::get('/admin/settings', $action);
});
```

### 6️⃣ Middleware

```php
// Глобальный
Route::middleware([CorsMiddleware::class, LoggerMiddleware::class]);

// На маршруте
Route::get('/admin', $action)
    ->middleware([AuthMiddleware::class, AdminMiddleware::class]);

// Встроенные middleware
Route::get('/api/data', $action)->auth();        // AuthMiddleware
Route::get('/api/public', $action)->cors();      // CorsMiddleware
Route::get('/secure', $action)->secure();        // HTTPS enforcement
```

### 7️⃣ Именованные маршруты и URL Generation

```php
// Именование
Route::get('/users/{id}', $action)->name('users.show');

// Автоименование
Route::enableAutoNaming();

// Генерация URL
$url = route_url('users.show', ['id' => 5]);  // /users/5

// С доменом
use CloudCastle\Http\Router\UrlGenerator;

$generator = new UrlGenerator();
$url = $generator->generate('users.show', ['id' => 5])
    ->toDomain('api.example.com')
    ->toProtocol('https')
    ->absolute();  // https://api.example.com/users/5

// Подписанные URL
$signedUrl = $generator->signed('verify.email', ['user' => 123], 3600);
```

### 8️⃣ Route Shortcuts (14 методов)

```php
Route::get('/api/data', $action)->apiEndpoint();  // API + CORS + JSON
Route::get('/admin', $action)->admin();           // Auth + Admin + Whitelist
Route::get('/page', $action)->public();           // Public tag
Route::get('/dashboard', $action)->protected();   // Auth + HTTPS
Route::get('/localhost', $action)->localhost();   // Only localhost

// Throttle shortcuts
Route::post('/api/submit', $action)->throttleStandard();   // 60/min
Route::post('/api/strict', $action)->throttleStrict();     // 10/min
Route::post('/api/bulk', $action)->throttleGenerous();     // 1000/min
```

### 9️⃣ Route Macros (7 макросов)

```php
// RESTful resource
Route::resource('/users', UserController::class);
// Создает: index, create, store, show, edit, update, destroy

// API resource (без create/edit)
Route::apiResource('/posts', PostController::class);

// CRUD (простой)
Route::crud('/products', ProductController::class);

// Авторизация
Route::auth();
// Создает: login, logout, register, password.request, password.reset

// Админка
Route::adminPanel('/admin');

// API версионирование
Route::apiVersion('v1', function() {
    Route::get('/users', $action);
});

// Webhooks
Route::webhooks('/webhooks', WebhookController::class);
```

### 🔟 Helper Functions (18 функций)

```php
route('users.show');              // Получить маршрут по имени
current_route();                  // Текущий маршрут
previous_route();                 // Предыдущий маршрут
route_is('users.*');              // Проверка имени маршрута
route_name();                     // Имя текущего маршрута
router();                         // Экземпляр роутера
dispatch_route($uri, $method);    // Диспетчеризация
route_url('users.show', ['id' => 5]);  // Генерация URL
route_has('users.show');          // Проверка существования
route_stats();                    // Статистика маршрутов
routes_by_tag('api');             // Маршруты по тегу
route_back();                     // Вернуться назад
```

---

## 📊 Производительность

### Бенчмарки (PHPBench)

| Операция | Время | Производительность |
|----------|-------|-------------------|
| **Добавление 1000 маршрутов** | 3.435ms | 0.0034ms/route |
| **Поиск первого маршрута** | 123μs | 8,130 req/sec |
| **Поиск среднего маршрута** | 1.746ms | 573 req/sec |
| **Поиск последнего маршрута** | 3.472ms | 288 req/sec |
| **Именованный поиск** | 3.858ms | 259 req/sec |
| **Группы маршрутов** | 2.577ms | 388 req/sec |
| **С middleware** | 2.030ms | 493 req/sec |
| **С параметрами** | 73μs | 13,699 req/sec |

### Нагрузочные тесты

| Сценарий | Маршруты | Запросы | Результат | Память |
|----------|----------|---------|-----------|--------|
| **Light Load** | 100 | 1,000 | **53,975 req/sec** | 6 MB |
| **Medium Load** | 500 | 5,000 | **54,135 req/sec** | 6 MB |
| **Heavy Load** | 1,000 | 10,000 | **54,891 req/sec** | 6 MB |

### Стресс-тесты

- ✅ **1,160,000 маршрутов** обработано
- ✅ **1.46 GB память** (1.32 KB/route)
- ✅ **200,000 запросов** за 3.8 сек
- ✅ **0 ошибок** под экстремальной нагрузкой

📖 Подробнее: [Анализ производительности](docs/ru/PERFORMANCE_ANALYSIS.md)

---

## 🔒 Безопасность

### Встроенные механизмы защиты

CloudCastle HTTP Router включает **12+ уровней защиты**:

✅ **Rate Limiting** - предотвращение DDoS  
✅ **Auto-Ban System** - автоматическая блокировка  
✅ **IP Filtering** - whitelist/blacklist с CIDR  
✅ **HTTPS Enforcement** - принудительное использование HTTPS  
✅ **Path Traversal Protection** - защита от ../../../  
✅ **SQL Injection Protection** - валидация параметров  
✅ **XSS Protection** - экранирование  
✅ **ReDoS Protection** - защита от regex DoS  
✅ **Method Override Protection** - защита от подмены методов  
✅ **Cache Injection Protection** - безопасное кеширование  
✅ **IP Spoofing Protection** - проверка X-Forwarded-For  
✅ **Protocol Restrictions** - HTTP/HTTPS/WS/WSS

### Тесты безопасности

**13/13 OWASP Top 10 тестов пройдено** ✅

```
✓ Path Traversal Protection
✓ SQL Injection Protection
✓ XSS Protection
✓ Rate Limiting (A07:2021)
✓ IP Filtering & Spoofing
✓ Method Override Attacks
✓ Cache Injection
✓ ReDoS Protection
✓ Unicode Security
✓ Resource Exhaustion
✓ HTTPS Enforcement
✓ Domain/Port Restrictions
✓ Auto-Ban System
```

📖 Подробнее: [Отчет по безопасности](docs/ru/SECURITY_REPORT.md)

---

## 🧩 Расширенные возможности

### Система плагинов

```php
use CloudCastle\Http\Router\Contracts\PluginInterface;

class LoggerPlugin implements PluginInterface {
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
        log("Error: " . $e->getMessage());
    }
}

Route::registerPlugin(new LoggerPlugin());
```

### Загрузчики маршрутов (5 типов)

```php
use CloudCastle\Http\Router\Loader\*;

// JSON
$loader = new JsonLoader($router);
$loader->load('routes.json');

// YAML
$loader = new YamlLoader($router);
$loader->load('routes.yaml');

// XML
$loader = new XmlLoader($router);
$loader->load('routes.xml');

// PHP Attributes
$loader = new AttributeLoader($router);
$loader->loadFromDirectory('app/Controllers');

// PHP файлы
require 'routes/web.php';
require 'routes/api.php';
```

### Expression Language

```php
Route::get('/admin', $action)
    ->condition('request.ip == "192.168.1.1" and request.time > 9');

Route::get('/api/data', $action)
    ->condition('request.header["X-API-Key"] == "secret"');
```

### Кеширование маршрутов

```php
// Включить кеш
$router->enableCache('var/cache/routes');

// Компиляция
$router->compile();

// Автозагрузка из кеша
if ($router->loadFromCache()) {
    // Кеш загружен - мгновенный старт
} else {
    // Регистрируем маршруты
    require 'routes/web.php';
    $router->compile();
}

// Очистка
$router->clearCache();
```

### PSR Support

```php
// PSR-7
use Psr\Http\Message\ServerRequestInterface;
$request = ServerRequestFactory::fromGlobals();

// PSR-15
use CloudCastle\Http\Router\Psr15\Psr15MiddlewareAdapter;
$psrMiddleware = new Psr15MiddlewareAdapter($router);
```

---

## 📚 Документация

### Основная документация

- 📖 [Руководство пользователя](docs/ru/USER_GUIDE.md) - Полное руководство по всем возможностям
- 🔍 [API Reference](docs/ru/API_REFERENCE.md) - Детальная документация API
- 💡 [Примеры](examples/) - 20+ готовых примеров
- ❓ [FAQ](docs/ru/FAQ.md) - Частые вопросы и ответы
- 🎯 [Список возможностей](FEATURES_LIST.md) - Все 209+ возможностей

### Отчеты и анализ

- 📊 [Сводка тестирования](docs/ru/SUMMARY.md)
- 🧪 [Детальные тесты](docs/ru/TESTS_DETAILED.md)
- ⚡ [Анализ производительности](docs/ru/PERFORMANCE_ANALYSIS.md)
- 🔒 [Отчет по безопасности](docs/ru/SECURITY_REPORT.md)
- ⚖️ [Сравнение с аналогами](docs/ru/COMPARISON.md)

---

## 🧪 Качество кода

### Статистика тестов

```
Всего тестов:     501
Успешно:          501 ✅
Провалено:        0
Покрытие:         ~95%
Assertions:       1,200+
```

### Статический анализ

- **PHPStan:** Level MAX - 0 критических ошибок ✅
- **PHPMD:** 0 проблем ✅
- **PHPCS:** PSR-12 - 0 нарушений ✅
- **PHP-CS-Fixer:** 0 файлов требует исправлений ✅
- **Rector:** 0 изменений требуется ✅

### Запуск тестов

```bash
# Все тесты
composer test

# По категориям
composer test:unit          # Юнит-тесты
composer test:security      # Тесты безопасности
composer test:performance   # Тесты производительности
composer test:load          # Нагрузочные тесты
composer test:stress        # Стресс-тесты

# Статический анализ
composer phpstan            # PHPStan
composer phpcs              # PHP_CodeSniffer
composer phpmd              # PHP Mess Detector
composer analyse            # Все анализаторы

# Бенчмарки
composer benchmark          # PHPBench
```

---

## ⚖️ Сравнение с аналогами

| Характеристика | CloudCastle | Laravel | Symfony | FastRoute | Slim |
|----------------|-------------|---------|---------|-----------|------|
| **Производительность** | **54k req/sec** | 35k | 40k | 60k | 45k |
| **Память (1k routes)** | **6 MB** | 12 MB | 10 MB | 4 MB | 5 MB |
| **Возможности** | **209+** | 150+ | 180+ | 20+ | 50+ |
| **Rate Limiting** | ✅ Встроено | ✅ | ❌ | ❌ | ⚠️ Пакет |
| **Auto-Ban** | ✅ | ❌ | ❌ | ❌ | ❌ |
| **IP Filtering** | ✅ Встроено | ⚠️ Middleware | ❌ | ❌ | ⚠️ Middleware |
| **Expression Lang** | ✅ | ❌ | ⚠️ Ограничено | ❌ | ❌ |
| **Plugins** | ✅ 4 встроенных | ✅ | ⚠️ Events | ❌ | ❌ |
| **Loaders** | ✅ 5 типов | ⚠️ PHP only | ⚠️ XML/YAML | ❌ | ❌ |
| **Macros** | ✅ 7 макросов | ✅ | ❌ | ❌ | ❌ |
| **Shortcuts** | ✅ 14 методов | ⚠️ Некоторые | ❌ | ❌ | ❌ |
| **Helpers** | ✅ 18 функций | ✅ 10+ | ⚠️ Мало | ❌ | ⚠️ Мало |
| **PSR-15** | ✅ | ✅ | ✅ | ❌ | ✅ |
| **Standalone** | ✅ | ❌ Framework | ⚠️ Сложно | ✅ | ✅ |
| **Тесты** | **501** | 300+ | 500+ | 100+ | 200+ |
| **Coverage** | **95%+** | 90%+ | 95%+ | 80%+ | 85%+ |

### Вывод

**CloudCastle HTTP Router** - оптимальный баланс между **производительностью**, **функциональностью** и **безопасностью**. 

✅ **Лучший выбор для:**
- API серверов с высокими требованиями к безопасности
- Микросервисная архитектура
- Высоконагруженные системы (50k+ req/sec)
- Проекты, требующие максимального контроля маршрутизации

📖 Подробнее: [Сравнение с аналогами](docs/ru/COMPARISON.md)

---

## 🤝 Вклад в проект

Мы приветствуем вклад в развитие CloudCastle HTTP Router!

### Как помочь

1. ⭐ Поставьте звезду проекту
2. 🐛 Сообщайте об ошибках
3. 💡 Предлагайте новые возможности
4. 📝 Улучшайте документацию
5. 🔧 Отправляйте Pull Requests

### Процесс

```bash
# 1. Fork проекта
git clone https://github.com/YOUR_USERNAME/cloud-casstle-http-router.git

# 2. Создайте feature branch
git checkout -b feature/AmazingFeature

# 3. Commit изменения
git commit -m 'Add some AmazingFeature'

# 4. Push в branch
git push origin feature/AmazingFeature

# 5. Откройте Pull Request
```

### Требования

- ✅ Следуйте PSR-12
- ✅ Пишите тесты (PHPUnit)
- ✅ Обновляйте документацию
- ✅ Проверьте PHPStan/PHPCS
- ✅ Один PR = одна фича

📖 Подробнее: [CONTRIBUTING.md](CONTRIBUTING.md)

---

## 📄 Лицензия

Этот проект распространяется под лицензией **MIT**. См. [LICENSE](LICENSE) для деталей.

```
MIT License

Copyright (c) 2024 CloudCastle

Permission is hereby granted, free of charge, to any person obtaining a copy...
```

---

## 💬 Поддержка

### Контакты

- 📧 **Email:** zorinalexey59292@gmail.com
- 💬 **Telegram:** [@CloudCastle85](https://t.me/CloudCastle85)
- 📢 **Telegram Channel:** [@cloud_castle_news](https://t.me/cloud_castle_news)
- 🐛 **GitHub Issues:** [Сообщить о проблеме](https://github.com/zorinalexey/cloud-casstle-http-router/issues)
- 💡 **GitHub Discussions:** [Обсуждения](https://github.com/zorinalexey/cloud-casstle-http-router/discussions)

### Полезные ссылки

- [📚 Документация](docs/ru/)
- [💡 Примеры использования](examples/)
- [📋 Changelog](CHANGELOG.md)
- [🗺️ Roadmap](ROADMAP.md)
- [🔒 Security Policy](SECURITY.md)
- [📜 Code of Conduct](CODE_OF_CONDUCT.md)
- [🤝 Контрибьюторы](CONTRIBUTORS.md)

---

## 🌟 Благодарности

Огромное спасибо всем [контрибьюторам](CONTRIBUTORS.md) за вклад в развитие проекта!

### Используемые технологии

- [PHPUnit](https://phpunit.de/) - Тестирование
- [PHPStan](https://phpstan.org/) - Статический анализ
- [PHPCS](https://github.com/squizlabs/PHP_CodeSniffer) - Code Style
- [PHPBench](https://phpbench.readthedocs.io/) - Бенчмарки
- [Rector](https://getrector.org/) - Рефакторинг

---

## 📈 Статистика проекта

![GitHub Stars](https://img.shields.io/github/stars/zorinalexey/cloud-casstle-http-router?style=social)
![GitHub Forks](https://img.shields.io/github/forks/zorinalexey/cloud-casstle-http-router?style=social)
![GitHub Watchers](https://img.shields.io/github/watchers/zorinalexey/cloud-casstle-http-router?style=social)

![GitHub Issues](https://img.shields.io/github/issues/zorinalexey/cloud-casstle-http-router)
![GitHub Pull Requests](https://img.shields.io/github/issues-pr/zorinalexey/cloud-casstle-http-router)
![GitHub Last Commit](https://img.shields.io/github/last-commit/zorinalexey/cloud-casstle-http-router)

---

**Made with ❤️ by [CloudCastle](https://github.com/zorinalexey)**

---

[⬆ Наверх](#cloudcastle-http-router)
