# FAQ - Часто задаваемые вопросы

[English](../en/FAQ.md) | **Русский** | [Deutsch](../de/FAQ.md) | [Français](../fr/FAQ.md) | [中文](../zh/FAQ.md)

---

## Содержание

- [Общие вопросы](#общие-вопросы)
- [Установка и настройка](#установка-и-настройка)
- [Производительность](#производительность)
- [Безопасность](#безопасность)
- [Troubleshooting](#troubleshooting)

---

## Общие вопросы

### Что такое CloudCastle HTTP Router?

CloudCastle HTTP Router - это высокопроизводительная библиотека маршрутизации HTTP-запросов для PHP 8.2+, предоставляющая богатый функционал из коробки включая Rate Limiting, IP фильтрацию, Auto-Ban систему и многое другое.

### Чем отличается от Laravel/Symfony Router?

**Преимущества:**
- Автономность (не требует фреймворка)
- Более высокая производительность (54k+ req/sec)
- Встроенный Rate Limiting и IP фильтрация
- Auto-Ban система из коробки
- Меньшее использование памяти

**Когда использовать:**
- Standalone проекты
- API серверы
- Микросервисы
- Проекты где важна производительность

### Какая минимальная версия PHP?

PHP 8.2 или выше.

### Можно ли использовать с фреймворками?

Да, библиотека автономна и может использоваться с любым фреймворком или без него.

### Есть ли поддержка PSR?

Да, поддерживаются PSR-7 (HTTP Message) и PSR-15 (HTTP Server Handler).

---

## Установка и настройка

### Как установить?

```bash
composer require cloud-castle/http-router
```

### Нужны ли дополнительные зависимости?

Минимальные:
- psr/http-message
- psr/http-server-handler  
- psr/http-server-middleware

Все устанавливаются автоматически через Composer.

### Как настроить автозагрузку маршрутов?

**Вариант 1 - PHP файл:**
```php
require 'routes.php';
```

**Вариант 2 - JSON Loader:**
```php
use CloudCastle\Http\Router\Loader\JsonLoader;
$loader = new JsonLoader($router);
$loader->load('routes.json');
```

**Вариант 3 - Attributes:**
```php
use CloudCastle\Http\Router\Loader\AttributeLoader;
$loader = new AttributeLoader($router);
$loader->loadFromDirectory('app/Controllers');
```

### Как включить кеширование?

```php
$router->enableCache('/path/to/cache');

if (!$router->loadFromCache()) {
    // Регистрация маршрутов
    include 'routes.php';
    $router->compile();
}
```

---

## Производительность

### Какая производительность?

**54,000+ запросов в секунду** в стандартных условиях.

### Как улучшить производительность?

1. Используйте кеширование маршрутов
2. Включите PHP OPcache
3. Используйте PHP 8.2+ с JIT
4. Группируйте маршруты по префиксам
5. Используйте именованные маршруты

### Влияет ли количество маршрутов на скорость?

Минимально благодаря оптимизированным индексам:
- URI index для точных совпадений
- Method index для фильтрации

### Когда использовать кеш, а когда нет?

**Использовать кеш:**
- Более 1,000 маршрутов
- Production окружение
- Маршруты редко меняются

**Не использовать кеш:**
- Менее 1,000 маршрутов
- Development окружение
- Динамические маршруты

---

## Безопасность

### Защищен ли роутер от Path Traversal?

Да, встроенная защита от обхода каталогов.

### Есть ли защита от SQL Injection?

Да, все параметры маршрутов безопасно экранируются.

### Как защитить админку?

```php
Route::group([
    'prefix' => '/admin',
    'middleware' => ['auth', 'admin'],
    'whitelistIp' => ['192.168.1.0/24'],
    'https' => true
], function() {
    // Админ маршруты
});
```

### Как настроить Rate Limiting?

```php
// 60 запросов в минуту
Route::get('/api/data', $action)->throttle(60, 1);

// 10 запросов в минуту (строже)
Route::post('/api/sensitive', $action)->throttle(10, 1);
```

### Как работает Auto-Ban?

```php
use CloudCastle\Http\Router\BanManager;

$banManager = new BanManager();
$banManager->enableAutoBan(5); // Бан после 5 попыток
$banManager->setAutoBanDuration(3600); // На 1 час
```

---

## Troubleshooting

### Маршрут не находится

**Проверьте:**
1. URI совпадает с зарегистрированным
2. HTTP метод корректен
3. Параметры соответствуют ограничениям where()
4. Нет конфликтующих маршрутов

```php
// Debug
$routes = $router->getRoutes();
var_dump($routes);
```

### TooManyRequestsException

Превышен Rate Limit. Проверьте настройки throttle():

```php
// Увеличьте лимит
Route::get('/api/data', $action)->throttle(1000, 1);
```

### IpNotAllowedException

IP не в whitelist или в blacklist:

```php
// Проверьте IP фильтры
$route = $router->getRouteByName('admin.dashboard');
var_dump($route->getWhitelistIps());
var_dump($route->getBlacklistIps());
```

### RouteNotFoundException

**Возможные причины:**
1. Маршрут не зарегистрирован
2. URI не совпадает
3. HTTP метод не поддерживается

**Решение:**
```php
// Посмотрите все маршруты
php bin/routes-list

// Или в коде
$stats = $router->getRouteStats();
print_r($stats);
```

### Memory limit exceeded

**При большом количестве маршрутов:**

1. Используйте кеширование
2. Увеличьте memory_limit в php.ini
3. Разделите маршруты по доменам/портам

```php
// php.ini
memory_limit = 256M
```

### Кеш не работает

**Проверьте:**
1. Директория кеша доступна для записи
2. enableCache() вызван до loadFromCache()
3. compile() вызван после регистрации маршрутов

```php
// Правильный порядок
$router->enableCache('/path/to/cache');

if (!$router->loadFromCache()) {
    include 'routes.php';  // Регистрация
    $router->compile();     // Компиляция
}
```

### Middleware не выполняется

**Проверьте:**
1. Middleware корректно зарегистрирован
2. Middleware реализует MiddlewareInterface
3. Порядок middleware (первый = первым выполняется)

```php
Route::get('/dashboard', $action)
    ->middleware([
        FirstMiddleware::class,
        SecondMiddleware::class  // Выполнится вторым
    ]);
```

### Ошибки при использовании групп

**Частые проблемы:**
1. Забыли use ($router) в closure
2. Неправильная вложенность
3. Конфликтующие атрибуты

```php
// Правильно
$router->group(['prefix' => '/api'], function() use ($router) {
    $router->get('/users', $action);
});
```

---

## Дополнительная помощь

### Где найти документацию?

- [README.md](../../README.md) - Основная информация
- [USER_GUIDE.md](USER_GUIDE.md) - Руководство пользователя
- [ALL_FEATURES.md](ALL_FEATURES.md) - Все возможности
- [Examples](../../examples/) - Примеры кода

### Где сообщить о баге?

[GitHub Issues](https://github.com/zorinalexey/cloud-casstle-http-router/issues)

### Где получить поддержку?

- Email: zorinalexey59292@gmail.com
- Telegram: @CloudCastle85
- Telegram Channel: @cloud_castle_news

---

© 2024 CloudCastle HTTP Router

