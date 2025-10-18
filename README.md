# CloudCastle HTTP Router

**Высокопроизводительный HTTP роутер для PHP 8.2+**

[![Tests](https://img.shields.io/badge/tests-263%20passed-success)](docs/ru/reports/tests.md)
[![Coverage](https://img.shields.io/badge/coverage-95%25-success)](docs/ru/reports/tests.md)
[![PHPStan](https://img.shields.io/badge/PHPStan-level%20max%20%7C%200%20errors-success)](docs/ru/reports/static-analysis.md)
[![Performance](https://img.shields.io/badge/performance-60k%20req%2Fs-success)](docs/ru/reports/performance.md)
[![PHP](https://img.shields.io/badge/PHP-8.2%2B%20|%208.3%20|%208.4-blue)](https://www.php.net)
[![License](https://img.shields.io/badge/license-MIT-blue)](LICENSE)

**Версия**: 1.1.1  
**Язык по умолчанию**: Русский

---

## 🌍 Языки документации

- **[Русский](docs/ru/documentation/README.md)** (текущий)
- **[English](docs/en/documentation/README.md)**
- **[Deutsch](docs/de/documentation/README.md)**
- **[Français](docs/fr/documentation/README.md)**

---

## 🚀 Быстрый старт

```php
<?php

use CloudCastle\Http\Router\Facade\Route;

// Простой маршрут
Route::get('/users', fn() => 'User list');

// С параметрами
Route::get('/user/{id}', fn($id) => "User: $id");

// С автоматическим именованием
Route::getInstance()->enableAutoNaming();
Route::get('/api/v1/users/{id}', 'UserController@show');
// Автоматически именуется как: api.v1.users.id.get

// С защитой от перегрузки и автобаном
Route::post('/login', 'AuthController@login')
    ->throttleWithBan(
        maxAttempts: 5,
        decaySeconds: 60,
        maxViolations: 3,
        banDurationSeconds: 7200
    );

// Диспетчеризация
$result = Route::dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
```

## ✨ Основные возможности

### Маршрутизация
- ✅ Все HTTP методы (GET, POST, PUT, PATCH, DELETE, OPTIONS, HEAD)
- ✅ Динамические параметры с ограничениями
- ✅ Группы маршрутов с общими атрибутами
- ✅ Именованные и тегированные маршруты
- ✅ **Автоматическое именование маршрутов** 🆕
- ✅ **Система плагинов** 🔌 - расширяемость без изменения кода
- ✅ Регулярные выражения
- ✅ Кеширование маршрутов

### Безопасность 🛡️
- 🚫 **Автобан** - защита от brute-force и DDoS
- ⏱️ **Гибкие временные окна** - от секунд до месяцев
- 🔒 IP фильтрация (белые/черные списки)
- 🌐 Доменные и портовые ограничения
- 🔐 Протокольные ограничения (HTTP/HTTPS/WS/WSS)
- 🛡️ Middleware для безопасности (HTTPS, SSRF protection, Security Logger)
- ✅ OWASP Top 10 compliance

### Производительность ⚡
- 🚀 **60,000+ запросов/сек** (light load)
- 📊 **740,000+ маршрутов** поддерживается
- 💾 **~1.47 KB** на маршрут
- ⚡ O(1) поиск с индексацией
- 💨 Компиляция и кеширование
- 🎯 Низкое потребление памяти

## 📊 Результаты тестирования

### Модульные тесты
- **263 теста** - все пройдены ✅
- **611 assertions**
- **Покрытие**: ~95%

### Производительность
- **Light Load**: 60,095 req/s (100 маршрутов)
- **Medium Load**: 58,905 req/s (500 маршрутов)
- **Heavy Load**: 59,599 req/s (1,000 маршрутов)
- **Extreme**: 55,609 req/s (200,000 запросов)

### Стресс-тестирование
- **Максимум маршрутов**: 740,000+
- **Память**: 872 MB для 740k маршрутов
- **Скорость**: стабильные 53-55k req/s

### Статический анализ
- **PHPStan**: Level MAX - 0 ошибок ✅
- **PHPCS**: PSR-12 - 0 ошибок ✅
- **PHPMD**: Оптимизированный код
- **Безопасность**: 13 тестов - все пройдены ✅

[📈 Подробные отчеты](docs/ru/reports/)

## 📦 Установка

```bash
composer require cloudcastle/http-router
```

**Требования**:
- PHP 8.2, 8.3 или 8.4
- Composer 2.x

## 💡 Примеры использования

### Базовая маршрутизация

```php
use CloudCastle\Http\Router\Facade\Route;

// GET запрос
Route::get('/users', 'UserController@index');

// POST запрос
Route::post('/users', 'UserController@store');

// С параметрами и ограничениями
Route::get('/user/{id:\d+}', 'UserController@show');

// Несколько методов
Route::match(['GET', 'POST'], '/form', 'FormController@handle');
```

### Автоматическое именование 🤖

```php
use CloudCastle\Http\Router\Router;

$router = Router::getInstance();
$router->enableAutoNaming();

// GET /users -> автоматически: users.get
$router->get('/users', 'UserController@index');

// GET /api/v1/users/{id} -> автоматически: api.v1.users.id.get
$router->get('/api/v1/users/{id}', 'ApiController@show');

// Использование
$route = $router->getRouteByName('api.v1.users.id.get');
```

### Группы маршрутов

```php
Route::group(['prefix' => 'api/v1', 'middleware' => 'auth'], function() {
    Route::get('/users', 'UserController@index');
    Route::get('/posts', 'PostController@index');
});
```

### Rate Limiting и Автобан

```php
// Простой rate limiting
Route::post('/api/data', 'ApiController@store')
    ->perMinute(60);  // 60 запросов в минуту

// С автобаном
Route::post('/login', 'AuthController@login')
    ->throttleWithBan(
        maxAttempts: 5,           // 5 попыток
        decaySeconds: 60,          // за 60 секунд
        maxViolations: 3,          // 3 нарушения
        banDurationSeconds: 7200   // бан на 2 часа
    );

// Разные временные окна
Route::get('/api/fast', fn() => 'data')->perSecond(10);
Route::post('/api/normal', fn() => 'ok')->perMinute(100);
Route::post('/api/heavy', fn() => 'done')->perHour(50);
Route::post('/newsletter', fn() => 'sent')->perWeek(1);
```

### Безопасность

```php
// HTTPS обязателен
Route::post('/login', 'Auth@login')->https();

// IP whitelist
Route::get('/admin', 'Admin@index')
    ->whitelistIp(['192.168.1.0/24', '10.0.0.1']);

// Доменные ограничения
Route::get('/dashboard', 'Dashboard@index')
    ->domain('admin.example.com');

// Комбинирование
Route::get('/secure', 'SecureController@index')
    ->https()
    ->whitelistIp(['10.0.0.0/8'])
    ->middleware('auth');
```

### Макросы

```php
// RESTful resource
Route::resource('posts', 'PostController');
// Создает: index, create, store, show, edit, update, destroy

// API resource
Route::apiResource('articles', 'ArticleController');

// CRUD
Route::crud('products', 'ProductController');

// Auth роуты
Route::auth();
```

### Система плагинов 🔌

```php
use CloudCastle\Http\Router\Router;
use CloudCastle\Http\Router\Plugin\LoggerPlugin;
use CloudCastle\Http\Router\Plugin\AnalyticsPlugin;

$router = Router::getInstance();

// Logger Plugin - логирование всех событий
$logger = new LoggerPlugin('/var/log/router.log');
$router->registerPlugin($logger);

// Analytics Plugin - сбор статистики
$analytics = new AnalyticsPlugin();
$router->registerPlugin($analytics);

// Получение статистики
$stats = $analytics->getStatistics();
echo "Total dispatches: {$stats['total_dispatches']}\n";
echo "Most popular route: {$stats['most_popular_route']}\n";

// Создание кастомного плагина
$customPlugin = new class extends AbstractPlugin {
    public function getName(): string { return 'custom'; }
    
    public function beforeDispatch(Route $route, string $uri, string $method): void {
        // Ваша логика перед диспетчеризацией
    }
};

$router->registerPlugin($customPlugin);
```

**Встроенные плагины:**
- 📝 **LoggerPlugin** - логирование маршрутов, диспетчеризации, исключений
- 📊 **AnalyticsPlugin** - сбор статистики (хиты, методы, время выполнения)
- 💾 **ResponseCachePlugin** - кеширование ответов маршрутов

**Хуки плагинов:**
- `onRouteRegistered()` - при регистрации маршрута
- `beforeDispatch()` - перед диспетчеризацией
- `afterDispatch()` - после диспетчеризации
- `onException()` - при исключении

## 📚 Документация

### Основная документация (Русский)
- [Введение](docs/ru/documentation/introduction.md)
- [Быстрый старт](docs/ru/documentation/quickstart.md)
- [Маршруты](docs/ru/documentation/routes.md)
- [Система плагинов](docs/ru/documentation/plugins.md) 🆕
- [Автоматическое именование](docs/ru/documentation/auto-naming.md)
- [Группы маршрутов](docs/ru/documentation/route-groups.md)
- [Middleware](docs/ru/documentation/middleware.md)
- [Rate Limiting](docs/ru/documentation/rate-limiting.md)
- [Автобан](docs/ru/documentation/auto-ban.md)
- [Безопасность](docs/ru/documentation/security.md)
- [Производительность](docs/ru/documentation/performance.md)
- [API Reference](docs/ru/documentation/api-reference.md)

### Отчеты и анализ
- [📊 Отчет по тестам](docs/ru/reports/tests.md)
- [⚡ Отчет по производительности](docs/ru/reports/performance.md)
- [🔒 Отчет по безопасности](docs/ru/reports/security.md)
- [📈 Статический анализ](docs/ru/reports/static-analysis.md)
- [🔥 Нагрузочное тестирование](docs/ru/reports/load-testing.md)
- [💪 Стресс-тестирование](docs/ru/reports/stress-testing.md)
- [⚖️ Сравнение с аналогами](docs/ru/reports/comparison.md)
- [📋 Итоговый отчет](docs/ru/reports/summary.md)

## 🆚 Сравнение с аналогами

| Характеристика | CloudCastle Router | FastRoute | Symfony Router | Laravel Router |
|----------------|-------------------|-----------|----------------|----------------|
| **Производительность** | **60k req/s** | 50k req/s | 30k req/s | 25k req/s |
| **Макс. маршрутов** | **740k+** | 100k | 50k | 30k |
| **Память/маршрут** | **1.47 KB** | 2.5 KB | 3.8 KB | 4.2 KB |
| **Rate Limiting** | ✅ Встроенный | ❌ | ❌ | ✅ |
| **Автобан** | ✅ | ❌ | ❌ | ❌ |
| **Автонейминг** | ✅ | ❌ | ❌ | ❌ |
| **PHPStan Level** | **MAX (0 errors)** | 6 | 8 | 5 |
| **Покрытие тестами** | **95%** | 85% | 90% | 88% |

[Подробное сравнение](docs/ru/reports/comparison.md)

## 🔧 Примеры кода

В каталоге [`examples/`](examples/) находятся рабочие примеры:

- `instance-usage.php` - Базовое использование
- `static-usage.php` - Статический фасад
- `routes.php` - Различные типы маршрутов
- `navigation-example.php` - Навигация и именованные маршруты
- `auto-naming-example.php` - Автоматическое именование 🆕
- `filtering-example.php` - Фильтрация маршрутов
- `autoban-example.php` - Система автобана
- `throttle-example.php` - Rate limiting
- `rate-limit-timeunits.php` - Временные окна
- `macros-usage.php` - Макросы
- `helpers-usage.php` - Helper функции
- `shortcuts-usage.php` - Shortcuts
- `security-max.php` - Максимальная безопасность

## 🎯 Основные преимущества

1. **Производительность**: 60,000+ запросов в секунду
2. **Масштабируемость**: Поддержка 740,000+ маршрутов
3. **Безопасность**: Встроенная защита от атак
4. **Удобство**: Автоматическое именование маршрутов
5. **Гибкость**: Мощная система middleware и групп
6. **Качество кода**: PHPStan Level MAX, 0 ошибок
7. **Тестирование**: 263 теста, покрытие 95%
8. **Документация**: Полная документация на 4 языках

## 📝 Требования и совместимость

**PHP версии**:
- ✅ PHP 8.2
- ✅ PHP 8.3
- ✅ PHP 8.4

**Зависимости**:
- Нет внешних зависимостей в production
- Dev-зависимости: PHPUnit, PHPStan, PHPCS и др.

## 🤝 Вклад в проект

Мы приветствуем вклад в развитие проекта! См. [CONTRIBUTING.md](CONTRIBUTING.md)

## 📄 Лицензия

MIT License. См. [LICENSE](LICENSE)

## 👥 Авторы

- **Zorin Alexey** - *Разработчик* - [Telegram](https://t.me/CloudCastle85)

## 🔗 Ссылки

- **GitHub**: https://github.com/zorinalexey/cloud-casstle-http-router
- **Packagist**: https://packagist.org/packages/cloud-castle/http-router
- **Документация**: [docs/](docs/)
- **Чат поддержки**: [Telegram](https://t.me/cloud_castle_news)
- **Email**: zorinalexey59292@gmail.com

## 🌟 Поддержите проект

Если вам нравится проект, поставьте ⭐ на GitHub!

---

**CloudCastle HTTP Router** - Производительность. Безопасность. Простота.
Суббота, 18. октября 2025 04:26 
