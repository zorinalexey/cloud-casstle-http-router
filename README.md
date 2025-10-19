# CloudCastle HTTP Router

Мощный и гибкий HTTP роутер для PHP 8.2+ с поддержкой middleware, групп маршрутов, именованных маршрутов, фильтрации по IP, системы автобана, rate limiting и множества других функций.

## 🚀 Ключевые особенности

- ✅ **Высокая производительность**: 50,946 запросов/сек (среднее)
- ✅ **Масштабируемость**: поддержка 1,095,000+ маршрутов
- ✅ **Безопасность**: 13 механизмов защиты (SSRF, Auto-ban, IP filtering)
- ✅ **32 возможности**: самый богатый функционал (100% coverage)
- ✅ **10 уникальных фич**: Auto-Naming, ThrottleWithBan, и др.
- ✅ **7 Route Macros**: сокращение кода на 80-97%
- ✅ **13 Shortcuts**: быстрая настройка маршрутов
- ✅ **15 Helper Functions**: удобные глобальные функции
- ✅ **4 способа конфигурации**: PHP, YAML, XML, Attributes
- ✅ **PSR-15**: полная совместимость с PSR-15 middleware
- ✅ **Expression Language**: условная маршрутизация
- ✅ **Типобезопасность**: PHPStan level max (0 errors)

## 📦 Установка

```bash
composer require cloud-castle/http-router
```

## 🎯 Быстрый старт

### Базовое использование

```php
use CloudCastle\Http\Router\Router;

$router = new Router();

// Простой маршрут
$router->get('/', function() {
    return 'Hello, World!';
});

// Маршрут с параметрами
$router->get('/users/{id}', function($id) {
    return "User ID: {$id}";
});

// Dispatch
$result = $router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
```

### Использование с Attributes (PHP 8+)

```php
use CloudCastle\Http\Router\Loader\Route;
use CloudCastle\Http\Router\Loader\AttributeLoader;

class UserController
{
    #[Route('/users', methods: 'GET', name: 'users.index')]
    public function index() {
        return ['users' => []];
    }
    
    #[Route('/users/{id}', methods: 'GET', middleware: 'auth')]
    public function show(int $id) {
        return ['id' => $id];
    }
}

$loader = new AttributeLoader($router);
$loader->loadFromController(UserController::class);
```

### YAML конфигурация

```yaml
home:
  path: /
  methods: GET
  controller: HomeController::index

users:
  path: /users/{id}
  methods: [GET, POST]
  middleware: auth
  requirements:
    id: \d+
  defaults:
    id: 1
```

## 📊 Производительность

| Тест | Результат | Сравнение |
|------|-----------|-----------|
| Light Load | 52,488 req/sec | ⚡ Быстрее FastRoute на 5% |
| Medium Load | 45,260 req/sec | ⚡ Быстрее Symfony на 180% |
| Heavy Load | 55,089 req/sec | ⚡ Быстрее Laravel на 220% |
| Максимум маршрутов | 1,095,000 | 🏆 Лучший результат |
| Память на маршрут | 1.39 KB | 💾 Оптимально |

## 🛡️ Безопасность

- ✅ **13 security тестов** - все пройдены
- ✅ **38 security assertions** - защита от распространенных атак
- ✅ **SSRF Protection** - защита от Server-Side Request Forgery
- ✅ **Auto-ban система** - автоматическая блокировка по IP
- ✅ **Rate Limiting** - ограничение частоты запросов
- ✅ **IP Filtering** - white/black списки IP адресов

## 🎨 Основные возможности

### Middleware

```php
// Глобальный middleware
$router->middleware(AuthMiddleware::class);

// Middleware для маршрута
$router->get('/admin', fn() => 'Admin')
    ->middleware(['auth', 'admin']);

// CORS Middleware
$router->get('/api/data', fn() => ['data'])
    ->middleware(new CorsMiddleware(
        allowedOrigins: ['https://example.com'],
        allowedMethods: ['GET', 'POST'],
        allowCredentials: true
    ));
```

### Groups

```php
$router->group(['prefix' => '/api/v1', 'middleware' => 'auth'], function($router) {
    $router->get('/users', 'UserController@index');
    $router->get('/posts', 'PostController@index');
});
```

### Rate Limiting

```php
// 60 запросов в минуту
$router->get('/api/limited', fn() => 'Limited')
    ->perMinute(60);

// Кастомное время
$router->get('/api/custom', fn() => 'Custom')
    ->throttle(100, 3600); // 100 запросов в час
```

### Expression Language

```php
$router->get('/premium', fn() => 'Premium Content')
    ->condition('user.age > 18 and user.subscription == "premium"');
```

### URL Generation

```php
$generator = new UrlGenerator($router);
$generator->setBaseUrl('https://example.com');

$url = $generator->generate('users.show', ['id' => 123]);
// https://example.com/users/123
```

## 📚 Документация

Полная документация доступна в директории `docs/ru/`:

### Начало работы
- [Оглавление](docs/ru/README.md) - навигация по документации
- [Getting Started](docs/ru/getting-started.md) - быстрый старт
- [Best Practices](docs/ru/best-practices.md) - лучшие практики

### Тестирование
- [Сводка по всем тестам](docs/ru/test-summary.md) - результаты 447 тестов
- [Unit тесты](docs/ru/unit-tests.md) - 419 unit тестов
- [Security тесты](docs/ru/security-tests.md) - 13 security проверок
- [Performance тесты](docs/ru/performance-tests.md) - 5 бенчмарков
- [Load тесты](docs/ru/load-tests.md) - нагрузка 50K+ req/sec
- [Stress тесты](docs/ru/stress-tests.md) - экстрим 1M+ routes

### Возможности
- [Все возможности](docs/ru/features.md) - 32 функции роутера (100% coverage)
- [Auto-Naming](docs/ru/auto-naming.md) - автоматическое именование (уникальная фича!)
- [Route Macros](docs/ru/macros.md) - 7+ макросов, 80-97% сокращения кода
- [Route Shortcuts](docs/ru/shortcuts.md) - 13+ удобных сокращений
- [Helper Functions](docs/ru/helpers.md) - 15+ глобальных функций
- [ThrottleWithBan](docs/ru/throttle-with-ban.md) - rate limiting + auto-ban (уникально!)
- [Tags System](docs/ru/tags.md) - система тегов для фильтрации
- [Route Loaders](docs/ru/loaders.md) - YAML/XML/Attributes/PHP
- [Middleware](docs/ru/middleware.md) - middleware система + PSR-15
- [Facade](docs/ru/facade.md) - статическое использование (Laravel-style)
- [Code Quality](docs/ru/code-quality.md) - PHPStan/PHPMD/PHPCS отчёты

### Сравнение
- [Детальное сравнение](docs/ru/comparison-detailed.md) - анализ 6 роутеров

## 🔧 Требования

- PHP 8.2 или выше
- Composer

## 📖 Примеры

Больше примеров в директории `examples/`:

- [Базовое использование](examples/basic-usage.php)
- [YAML конфигурация](examples/yaml-routes.yaml)
- [XML конфигурация](examples/xml-routes.xml)
- [Attributes](examples/attributes-usage.php)
- [Middleware](examples/middleware-advanced.php)
- [Expression Language](examples/expression-usage.php)

## 🤝 Вклад

Приветствуются pull requests! Для крупных изменений, пожалуйста, сначала откройте issue для обсуждения.

## 📄 Лицензия

MIT

## 🙏 Благодарности

Спасибо всем контрибьюторам и пользователям этого проекта!

---

Сделано с ❤️ командой CloudCastle

