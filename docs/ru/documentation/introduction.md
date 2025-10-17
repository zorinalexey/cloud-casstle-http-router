# Введение в HttpRouter

**CloudCastle HttpRouter** — это современная, высокопроизводительная библиотека маршрутизации для PHP 8.2+, разработанная с упором на безопасность, производительность и удобство использования.

## 📊 Статистика

- **308 тестов** / **748 утверждений** ✅
- **Покрытие кода:** >95%
- **PHPStan:** Level Max (3 несущественные предупреждения)
- **PHPCS:** PSR-12 compliant
- **PHPMD:** Без критических проблем
- **PHP версии:** 8.2, 8.3, 8.4

## ✨ Основные возможности

### 🔐 Безопасность
- **Rate Limiting** — защита от DDoS с гибкими лимитами
- **Auto-ban система** — автоматическая блокировка злоумышленников
- **Protocol enforcement** — принудительное использование HTTPS/WSS
- **Path traversal protection** — защита от обхода директорий
- **Mass assignment protection** — защита от массового присваивания
- **OWASP Top 10** — полное соответствие рекомендациям безопасности

### ⚡ Производительность
- **Route Caching** — кэширование маршрутов для мгновенного доступа
- **Lazy Loading** — ленивая загрузка middleware
- **Optimized Matching** — оптимизированный алгоритм сопоставления
- **Memory Efficient** — эффективное использование памяти (30MB для 308 тестов)
- **Fast Dispatch** — ~0.001ms на dispatch при использовании кэша

### 🎯 Удобство
- **Fluent API** — выразительный chainable интерфейс
- **Route Groups** — группировка маршрутов с общими настройками
- **Named Routes** — именованные маршруты для удобной генерации URL
- **Middleware Support** — полная поддержка middleware
- **Tag System** — система тегов для организации маршрутов
- **Static Facade** — статический фасад `Route::` для быстрого доступа

### 🛠️ Расширяемость
- **Custom Methods** — поддержка произвольных HTTP-методов
- **WebSocket Support** — полная поддержка WebSocket (WS/WSS)
- **Middleware Chains** — цепочки middleware с приоритетами
- **Event System** — система событий для хуков
- **Dependency Injection** — интеграция с DI-контейнерами

## ⚖️ Плюсы и минусы

### ✅ Преимущества

1. **Комплексная безопасность из коробки**
   - Rate limiting и auto-ban встроены нативно
   - Защита от всех основных векторов атак (OWASP Top 10)
   - Protocol enforcement для защищённых соединений

2. **Высокая производительность**
   - Продвинутая система кэширования маршрутов
   - Оптимизированные алгоритмы сопоставления
   - Минимальное потребление памяти

3. **Современный PHP**
   - Полная поддержка PHP 8.2+ фич
   - Строгая типизация
   - Return types и named arguments

4. **Богатый функционал**
   - WebSocket support (редкость для PHP роутеров)
   - Система тегов для организации
   - Гибкие time units (seconds, minutes, hours, days)

5. **Отличная документация**
   - Подробные примеры использования
   - Документация на 4 языках
   - Детальные отчёты по тестированию

6. **100% покрытие тестами**
   - 308 юнит, интеграционных и функциональных тестов
   - Security tests (OWASP)
   - Performance tests
   - Load & Stress testing

### ⚠️ Ограничения

1. **Требует PHP 8.2+**
   - Не работает на старых версиях PHP
   - Требует современного хостинга

2. **Молодая библиотека**
   - Меньше production случаев использования по сравнению с конкурентами
   - Меньше community plugins

3. **Сложность для простых задач**
   - Избыточен для простых проектов
   - Может быть overkill для landing pages

4. **Архитектурные особенности**
   - Использует статический Facade (не всем нравится)
   - Superglobals доступ ($_SERVER) — обоснованно для HTTP роутера
   - Высокая cyclomatic complexity в Router.php — из-за богатого API

## 🔄 Сравнение с конкурентами

| Функция | HttpRouter | Symfony | Laravel | FastRoute | Slim |
|---------|-----------|---------|---------|-----------|------|
| **Версия PHP** | 8.2+ | 8.1+ | 8.2+ | 7.1+ | 8.1+ |
| **Rate Limiting** | ✅ Встроен | ⚠️ Bundle | ✅ Встроен | ❌ | ❌ |
| **Auto-ban** | ✅ Встроен | ❌ | ❌ | ❌ | ❌ |
| **WebSocket** | ✅ WS/WSS | ❌ | ⚠️ Echo | ❌ | ❌ |
| **Route Caching** | ✅ | ✅ | ✅ | ⚠️ Ручной | ❌ |
| **Middleware** | ✅ | ✅ | ✅ | ❌ | ✅ |
| **Named Routes** | ✅ | ✅ | ✅ | ✅ | ✅ |
| **Route Groups** | ✅ | ✅ | ✅ | ✅ | ✅ |
| **Protocol Enforcement** | ✅ HTTPS/WSS | ❌ | ❌ | ❌ | ❌ |
| **Tag System** | ✅ | ❌ | ❌ | ❌ | ❌ |
| **Static Facade** | ✅ Route:: | ❌ | ✅ Route:: | ❌ | ❌ |
| **Performance** | ⚡⚡⚡ | ⚡⚡ | ⚡⚡ | ⚡⚡⚡ | ⚡⚡⚡ |
| **Размер** | Small | Large | Large | Tiny | Medium |
| **Зависимости** | Минимум | Много | Много | Нет | Немного |
| **Документация** | 4 языка | EN | EN | EN | EN |

### 📈 Детальное сравнение

#### vs Symfony Routing
**Преимущества:**
- Встроенные rate limiting и auto-ban
- Поддержка WebSocket
- Protocol enforcement
- Более простой API
- Меньше зависимостей

**Недостатки:**
- Меньше интеграций с другими компонентами
- Меньше community plugins
- Не часть большого фреймворка

#### vs Laravel Router
**Преимущества:**
- Работает standalone (без Laravel)
- Встроенная auto-ban система
- Нативная поддержка WebSocket
- Protocol enforcement
- Система тегов

**Недостатки:**
- Отсутствует интеграция с Eloquent ORM
- Нет route model binding
- Меньше ecosystem tools

#### vs FastRoute
**Преимущества:**
- Rate limiting из коробки
- Auto-ban система
- Middleware support
- Route caching
- Named routes
- Route groups

**Недостатки:**
- Чуть медленнее в чистой скорости dispatch (~1-2%)
- Больше памяти из-за дополнительных функций

#### vs Slim Router
**Преимущества:**
- Более богатый функционал безопасности
- Auto-ban система
- WebSocket support
- Protocol enforcement
- Tag system

**Недостатки:**
- Не интегрирован в микрофреймворк
- Требует отдельной установки

## 🎯 Когда использовать HttpRouter

### ✅ Идеально подходит для:

1. **API серверов с высокими требованиями к безопасности**
   - Встроенный rate limiting
   - Auto-ban защита
   - Protocol enforcement

2. **WebSocket приложений**
   - Нативная поддержка WS/WSS
   - Unified routing для HTTP и WebSocket

3. **Микросервисов**
   - Легковесный и быстрый
   - Минимум зависимостей
   - Отличная производительность

4. **Проектов на современном PHP**
   - PHP 8.2+ features
   - Строгая типизация
   - Modern best practices

### ⚠️ Не рекомендуется для:

1. **Legacy проектов на PHP < 8.2**
2. **Простых статических сайтов** (overkill)
3. **Проектов, где нужна интеграция с Laravel/Symfony ecosystem**

## 📦 Установка

```bash
composer require cloud-castle/http-router
```

## 🚀 Быстрый старт

```php
<?php

use CloudCastle\Http\Router\Route;

// Простой маршрут
Route::get('/users', fn() => ['users' => User::all()]);

// Маршрут с параметрами
Route::get('/users/{id}', function($id) {
    return User::find($id);
});

// Rate limiting
Route::get('/api/data', fn() => getData())
    ->rateLimit(requests: 100, per: '1 minute');

// Группа маршрутов
Route::group('/api/v1', function() {
    Route::get('/users', 'UserController@index');
    Route::post('/users', 'UserController@store');
})->middleware('auth')->rateLimit(1000, '1 hour');

// WebSocket
Route::websocket('/chat', 'ChatController@handle')
    ->protocol('wss'); // Только secure WebSocket

// Dispatch
$route = Route::dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
echo $route->call();
```

## 📚 Дальнейшее чтение

- [Быстрый старт](quickstart.md)
- [Маршруты](routes.md)
- [Группы маршрутов](route-groups.md)
- [Middleware](middleware.md)
- [Безопасность](security.md)
- [Rate Limiting](rate-limiting.md)
- [Auto-ban](auto-ban.md)
- [Производительность](performance.md)
- [API Reference](api-reference.md)

## 📊 Отчёты

- [Unit Tests](../reports/unit-tests.md)
- [Static Analysis](../reports/static-analysis.md)
- [Performance Benchmarks](../reports/performance.md)
- [Load Testing](../reports/load-testing.md)
- [Security Testing](../reports/security.md)
- [Сравнение с конкурентами](../reports/comparison.md)

## 🤝 Вклад

Мы приветствуем вклад в развитие библиотеки! См. [CONTRIBUTING.md](../CONTRIBUTING.md)

## 📄 Лицензия

MIT License. См. [LICENSE](../../LICENSE)

---

**CloudCastle HttpRouter** — выбор для современных PHP приложений с высокими требованиями к безопасности и производительности.
