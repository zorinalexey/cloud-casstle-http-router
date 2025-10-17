# CloudCastle HTTP Router

**Высокопроизводительный HTTP роутер для PHP 8.2+**

[![Tests](https://img.shields.io/badge/tests-308%2F308-success)](docs/ru/reports/unit-tests.md)
[![Coverage](https://img.shields.io/badge/coverage-92%25-success)](docs/ru/reports/unit-tests.md)
[![PHPStan](https://img.shields.io/badge/PHPStan-level%20max-success)](docs/ru/reports/static-analysis.md)
[![PHPCS](https://img.shields.io/badge/PHPCS-PSR12-success)](docs/ru/reports/static-analysis.md)
[![PHP](https://img.shields.io/badge/PHP-8.2%2B-blue)](https://www.php.net)
[![License](https://img.shields.io/badge/license-MIT-blue)](LICENSE)

**Версия**: 1.1.0  
**Язык**: Русский

---

**Переводы**: [English](docs/en/documentation/README.md) | [Deutsch](docs/de/documentation/README.md) | [Français](docs/fr/documentation/README.md)

---

## 🚀 Быстрый старт

```php
<?php

use CloudCastle\Http\Router\Facade\Route;

// Простой маршрут
Route::get('/hello', fn() => 'Hello World!');

// С параметрами
Route::get('/user/{id}', fn($id) => "User: $id");

// С rate limiting по секундам
Route::post('/api/data', 'ApiController@store')
    ->perSecond(10);  // 10 запросов в секунду

// С автобаном при злоупотреблении
Route::post('/login', 'AuthController@login')
    ->throttleWithBan(
        maxAttempts: 5,           // 5 попыток
        decaySeconds: 60,          // за минуту
        maxViolations: 3,          // 3 нарушения
        banDurationSeconds: 7200   // бан на 2 часа
    );

// Диспетчеризация
$result = Route::dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
```

## ✨ Возможности

### Базовый функционал
- ✅ Поддержка всех HTTP методов (GET, POST, PUT, PATCH, DELETE, OPTIONS, HEAD)
- ✅ Группы маршрутов с общими атрибутами
- ✅ Middleware с приоритетами
- ✅ Именованные и тегированные маршруты
- ✅ Динамические параметры (`/user/{id}`)
- ✅ Регулярные выражения в маршрутах
- ✅ Кеширование для максимальной производительности
- ✅ Статический фасад для удобного API

### Безопасность 🛡️
- 🚫 **Автоматический бан** - защита от brute-force и DDoS
- ⏱️ **Гибкие временные окна** - от секунд до месяцев
- 🔒 IP фильтрация (белые/черные списки)
- 🌐 Доменные и портовые ограничения
- 🔐 Протокольные ограничения (HTTP/HTTPS/WS/WSS)
- 🛡️ OWASP Top 10 compliance
- 📝 Security logging
- 🚧 SSRF protection

### Производительность ⚡
- RouteCollection с O(1) поиском
- Оптимизированное сопоставление маршрутов
- Компиляция регулярных выражений
- Кеширование скомпилированных маршрутов

## 📦 Установка

```bash
composer require cloudcastle/http-router
```

## 📚 Документация

### Основная документация
- [Введение](docs/ru/documentation/introduction.md)
- [Быстрый старт](docs/ru/documentation/quickstart.md)
- [Маршруты](docs/ru/documentation/routes.md)
- [Группы маршрутов](docs/ru/documentation/route-groups.md)
- [Middleware](docs/ru/documentation/middleware.md)
- [Rate Limiting](docs/ru/documentation/rate-limiting.md)
- [Автобан](docs/ru/documentation/auto-ban.md)
- [Временные окна](docs/ru/documentation/time-units.md)
- [Безопасность](docs/ru/documentation/security.md)
- [Производительность](docs/ru/documentation/performance.md)
- [API Reference](docs/ru/documentation/api-reference.md)

### Отчеты
- [Unit тесты](docs/ru/reports/unit-tests.md) (308 тестов, 748 assertions)
- [Статический анализ](docs/ru/reports/static-analysis.md) (PHPStan, PHPCS, PHPMD)
- [Производительность](docs/ru/reports/performance.md)
- [Безопасность](docs/ru/reports/security.md)
- [Нагрузочное тестирование](docs/ru/reports/load-testing.md)
- [Стресс-тестирование](docs/ru/reports/stress-testing.md)
- [Сравнение с аналогами](docs/ru/reports/comparison.md)
- [Итоговый отчёт](docs/ru/reports/final-report.md)

## 🆕 Что нового в v1.1.0

### Система автобана
```php
Route::post('/login', 'AuthController@login')
    ->throttleWithBan(
        maxAttempts: 5,           // Лимит попыток
        decaySeconds: 60,          // Временное окно
        maxViolations: 3,          // Нарушений до бана
        banDurationSeconds: 7200   // Длительность бана
    );
```

### Временные окна
```php
Route::get('/api/fast', fn() => 'data')->perSecond(10);
Route::post('/api/normal', fn() => 'ok')->perMinute(100);
Route::post('/api/heavy', fn() => 'done')->perHour(50);
Route::post('/api/email', fn() => 'sent')->perDay(100);
Route::post('/newsletter', fn() => 'sent')->perWeek(1);
Route::post('/billing', fn() => 'ok')->perMonth(1);
```

## 💡 Примеры

### Базовые маршруты
```php
// GET запрос
Route::get('/users', 'UserController@index');

// POST запрос
Route::post('/users', 'UserController@store');

// Несколько методов
Route::match(['GET', 'POST'], '/form', 'FormController@handle');

// Любой метод
Route::any('/webhook', 'WebhookController@handle');
```

### Параметры маршрута
```php
// Обязательный параметр
Route::get('/user/{id}', 'UserController@show');

// Необязательный параметр
Route::get('/user/{id?}', 'UserController@show');

// С ограничениями
Route::get('/user/{id}', 'UserController@show')
    ->where('id', '\d+');

// Несколько параметров
Route::get('/post/{year}/{month}/{slug}', 'PostController@show')
    ->where(['year' => '\d{4}', 'month' => '\d{2}']);
```

### Группы маршрутов
```php
Route::group(['prefix' => 'api/v1'], function() {
    Route::get('/users', 'UserController@index');
    Route::post('/users', 'UserController@store');
});

// С middleware
Route::group(['middleware' => 'auth'], function() {
    Route::get('/profile', 'ProfileController@show');
    Route::put('/profile', 'ProfileController@update');
});
```

### Rate Limiting
```php
// По секундам (realtime API)
Route::get('/api/stream', 'StreamController@data')
    ->perSecond(100);

// По минутам (стандартный API)
Route::post('/api/submit', 'ApiController@submit')
    ->perMinute(60);

// По часам (тяжелые операции)
Route::post('/api/process', 'ApiController@process')
    ->perHour(10);

// По дням (email рассылка)
Route::post('/send-email', 'EmailController@send')
    ->perDay(100);
```

### Защита от атак
```php
// Защита от brute-force
Route::post('/login', 'AuthController@login')
    ->throttleWithBan(5, 60, 3, 7200);

// Защита критичных операций
Route::delete('/admin/critical', 'AdminController@critical')
    ->throttleWithBan(1, 60, 1, 86400);
```

## 🧪 Тестирование

```bash
# Все тесты
./vendor/bin/phpunit

# Unit тесты
./vendor/bin/phpunit tests/Unit

# С подробным выводом
./vendor/bin/phpunit --testdox

# С покрытием
./vendor/bin/phpunit --coverage-html coverage
```

**Результаты**: 308/308 тестов ✅ | 748 assertions ✅ | Покрытие ~92% ✅

## 📊 Производительность

- **Скорость**: 50,000+ запросов/сек
- **Память**: ~2MB на 1000 маршрутов
- **Латентность**: <1ms на маршрут
- **Масштабируемость**: Linear O(1) поиск

Подробнее: [Отчет о производительности](docs/ru/reports/performance.md)

## 🔒 Безопасность

- OWASP Top 10 compliance
- Автоматический бан агрессивных IP
- HTTPS enforcement
- SSRF protection
- Security logging
- IP фильтрация

Подробнее: [Отчет по безопасности](docs/ru/reports/security.md)

## 🤝 Участие в разработке

Мы приветствуем ваш вклад! См. [CONTRIBUTING.md](CONTRIBUTING.md)

## 📄 Лицензия

MIT License. См. [LICENSE](LICENSE)

## 📞 Контакты

**Автор**: Зорин Алексей

- **Email**: zorinalexey59292@gmail.com
- **Telegram**: [@CloudCastle85](https://t.me/CloudCastle85)
- **GitHub**: [@zorinalexey](https://github.com/zorinalexey)
- **VK**: [vk.com/leha_zorin](https://vk.com/leha_zorin)

**Канал новостей**: [@cloud_castle_news](https://t.me/cloud_castle_news)

Подробнее: [CONTACTS.md](CONTACTS.md)

## 🌟 Поддержка

Если вам нравится этот проект, поставьте ⭐ на GitHub!

---

**CloudCastle HTTP Router** - максимальная производительность и безопасность для ваших PHP приложений!

---

**Переводы**: [English](docs/en/documentation/README.md) | [Deutsch](docs/de/documentation/README.md) | [Français](docs/fr/documentation/README.md)
