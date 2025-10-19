# Документация CloudCastle HTTP Router

**Languages:** 🇷🇺 Русский | [🇬🇧 English](../en/README.md) | [🇩🇪 Deutsch](../de/README.md) | [🇫🇷 Français](../fr/README.md) | [🇨🇳 中文](../zh/README.md)

[📚 Оглавление](_table-of-contents.md) | [🏠 Главная](README.md)

---

Добро пожаловать в документацию CloudCastle HTTP Router - современного, быстрого и безопасного роутера для PHP 8.2+.

## 📚 Оглавление

### Начало работы

- [Главная страница](../../README.md) - быстрый старт и основная информация
- [Getting Started](getting-started.md) - руководство для начинающих
- [Best Practices](best-practices.md) - лучшие практики разработки

### Тестирование

- [Сводка по всем тестам](test-summary.md) - результаты всех тестов и бенчмарков
- [Unit тесты](unit-tests.md) - детальные результаты 419 тестов
- [Security тесты](security-tests.md) - анализ 13 security проверок
- [Performance тесты](performance-tests.md) - бенчмарки производительности
- [Load тесты](load-tests.md) - нагрузочное тестирование (50K+ req/sec)
- [Stress тесты](stress-tests.md) - экстремальные условия (1M+ routes)

### Возможности

- [Все возможности](features.md) - полный список 30+ функций
- [Auto-Naming](auto-naming.md) - автоматическое именование маршрутов (уникальная фича!)
- [Route Shortcuts](shortcuts.md) - 13+ сокращений для быстрой настройки
- [Route Macros](macros.md) - 7+ макросов (сокращение кода на 80-97%)
- [Helper Functions](helpers.md) - 15+ глобальных функций
- [ThrottleWithBan](throttle-with-ban.md) - rate limiting + auto-ban (уникальная фича!)
- [Tags System](tags.md) - система тегов для фильтрации маршрутов
- [Route Loaders](loaders.md) - YAML/XML/JSON/Attributes конфигурация
- [Middleware](middleware.md) - система middleware и PSR-15
- [Facade](facade.md) - статическое использование (Laravel-style)
- [Code Quality](code-quality.md) - PHPStan, PHPMD, PHPCS отчёты

### Сравнение

- [Детальное сравнение с конкурентами](comparison-detailed.md) - полный анализ 6 роутеров

## 🎯 О проекте

CloudCastle HTTP Router - это высокопроизводительный роутер с уникальным набором функций безопасности и гибкости конфигурации.

### Ключевые показатели

- **Производительность**: 50,946 requests/sec (среднее)
- **Масштабируемость**: 1,095,000+ маршрутов
- **Безопасность**: 13 защитных механизмов
- **Тесты**: 447 тестов, 1043+ assertions
- **Покрытие**: 100% success rate

## 📊 Результаты тестирования

### Производительность

| Категория | Результат | Статус |
|:---|:---:|:---:|
| Light Load | 52,488 req/sec | ✅ |
| Medium Load | 45,260 req/sec | ✅ |
| Heavy Load | 55,089 req/sec | ✅ |
| Concurrent Access | 8,316 req/sec | ✅ |

### Масштабируемость

| Параметр | Значение |
|:---|:---:|
| Максимум маршрутов | 1,095,000 |
| Память/маршрут | 1.39 KB |
| Глубина вложенности | 50 уровней |
| Длина URI | 1,980 символов |

### Безопасность

✅ Все 13 security тестов пройдены успешно:
- Path Traversal Protection
- SQL Injection Prevention
- XSS Protection
- IP Whitelist/Blacklist
- IP Spoofing Protection
- Domain Security
- ReDoS Protection
- Method Override Protection
- Mass Assignment Protection
- Cache Injection Prevention
- Resource Exhaustion Prevention
- Unicode Security

## 🆚 Сравнение с конкурентами

### Производительность (requests/sec)

1. **CloudCastle** - 50,946 🥇
2. FastRoute - 47,033 🥈
3. AltoRouter - 39,967 🥉
4. Slim - 37,167
5. Laravel - 16,233
6. Symfony - 15,633

### Функциональность (количество фич)

1. **CloudCastle** - 25/25 (100%) 🥇
2. Symfony - 10/25 (40%) 🥈
3. Laravel - 9/25 (36%) 🥉
4. Slim - 7/25 (28%)
5. AltoRouter - 4/25 (16%)
6. FastRoute - 3/25 (12%)

### Масштабируемость (максимум маршрутов)

1. **CloudCastle** - 1,095,000 🥇
2. FastRoute - 500,000 🥈
3. Slim - 200,000 🥉
4. AltoRouter - 150,000
5. Symfony - 100,000
6. Laravel - 80,000

## 🚀 Быстрый старт

### Установка

```bash
composer require cloud-castle/http-router
```

### Базовое использование

```php
use CloudCastle\Http\Router\Router;

$router = new Router();

$router->get('/', function() {
    return 'Hello, World!';
});

$router->get('/users/{id}', function($id) {
    return "User: {$id}";
});

$result = $router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
```

### Продвинутые возможности

```php
// Middleware
$router->get('/admin', 'AdminController@index')
    ->middleware(['auth', 'admin']);

// Rate Limiting
$router->get('/api/data', 'ApiController@data')
    ->perMinute(60);

// Conditions
$router->get('/premium', 'PremiumController@index')
    ->condition('user.subscription == "premium"');

// Groups
$router->group(['prefix' => '/api/v1'], function($router) {
    $router->get('/users', 'UserController@index');
    $router->get('/posts', 'PostController@index');
});
```

## 💡 Рекомендации

### Когда использовать CloudCastle

✅ **Идеально для:**
- Высоконагруженных API сервисов
- Микросервисной архитектуры
- Проектов с требованиями к безопасности
- Enterprise приложений
- Multi-tenant систем

✅ **Преимущества:**
- Максимальная производительность
- Лучшая масштабируемость
- Комплексная безопасность
- Богатый функционал
- Современный код (PHP 8.2+)

### Best Practices

1. **Используйте кеширование** в production
2. **Группируйте маршруты** по функциональности
3. **Применяйте named routes** для URL генерации
4. **Используйте rate limiting** для публичных API
5. **Настройте YAML/XML/JSON** для больших конфигураций

## 📖 Дополнительные ресурсы

### Документация

- [Сводка по тестам](test-summary.md) - детальные результаты всех тестов
- [Сравнение роутеров](comparison-detailed.md) - полный анализ альтернатив

### Примеры

Примеры использования находятся в директории `examples/`:
- `basic-usage.php` - базовая маршрутизация
- `yaml-routes.yaml` - YAML конфигурация
- `xml-routes.xml` - XML конфигурация
- `json-routes.json` - JSON конфигурация ⭐
- `attributes-usage.php` - PHP 8 Attributes
- `middleware-advanced.php` - продвинутый middleware
- `expression-usage.php` - Expression Language

### Отчеты

Результаты тестирования в директории `reports/`:
- `phpunit.txt` - результаты PHPUnit
- `security-tests.txt` - security тесты
- `performance-tests.txt` - бенчмарки
- `load-tests.txt` - нагрузочные тесты
- `stress-tests.txt` - стресс-тесты
- `phpstan.txt` - статический анализ
- `phpcs.txt` - code style
- `phpmd.txt` - code quality

## 🤝 Поддержка

- **Issues**: [GitHub Issues](https://github.com/zorinalexey/cloud-casstle-http-router/issues)
- **Email**: zorinalexey59292@gmail.com
- **Telegram**: [@CloudCastle85](https://t.me/CloudCastle85)

## 📄 Лицензия

MIT License - см. [LICENSE](../../LICENSE) файл.

---

**CloudCastle HTTP Router** - Максимальная производительность. Полная безопасность. Богатейший функционал.

*Последнее обновление: 18 октября 2025*

---

[📚 Оглавление](_table-of-contents.md) | [🏠 Главная](README.md)

