# Введение

**CloudCastle HTTP Router v1.1.1**  
**Язык**: Русский

---

## 🌍 Переводы

- **[Русский](introduction.md)** (текущий)
- [English](../../en/documentation/introduction.md)
- [Deutsch](../../de/documentation/introduction.md)
- [Français](../../fr/documentation/introduction.md)

---

## О проекте

**CloudCastle HTTP Router** - это высокопроизводительный HTTP роутер для PHP 8.2+, разработанный с фокусом на производительность, безопасность и простоту использования.

### Философия проекта

Мы создали роутер, который объединяет:
- **Скорость** - 60,000+ запросов в секунду
- **Масштабируемость** - поддержка 740,000+ маршрутов
- **Безопасность** - встроенная защита от атак
- **Удобство** - интуитивный API и автоматизация

---

## ✨ Ключевые возможности

### Маршрутизация
- ✅ Все HTTP методы (GET, POST, PUT, PATCH, DELETE, OPTIONS, HEAD)
- ✅ Динамические параметры с regex ограничениями
- ✅ Группы маршрутов с префиксами
- ✅ Именованные маршруты
- ✅ Тегированные маршруты
- ✅ **Автоматическое именование маршрутов** 🆕
- ✅ Вложенные группы
- ✅ Кеширование маршрутов

### Безопасность
- 🛡️ **Rate Limiting** с гибкими временными окнами
- 🚫 **Автобан** при превышении лимитов
- 🔒 **IP фильтрация** (белые/черные списки)
- 🌐 **Доменные ограничения**
- 🔐 **Протокольные ограничения** (HTTP/HTTPS/WS/WSS)
- 🛡️ **HTTPS Enforcement** middleware
- 🛡️ **SSRF Protection** middleware
- 📝 **Security Logging** middleware
- ✅ **OWASP Top 10** compliance

### Производительность
- 🚀 **60,000+ req/s** на легкой нагрузке
- 📊 **O(1)** сложность поиска маршрутов
- 💾 **1.47 KB** памяти на маршрут
- ⚡ Компиляция и кеширование
- 🎯 Индексация для быстрого поиска

### Middleware
- ✅ Глобальные middleware
- ✅ Middleware маршрутов
- ✅ Middleware групп
- ✅ Приоритеты middleware
- ✅ Цепочки обработки

---

## 🎯 Для кого этот роутер?

### Идеально подходит для:

✅ **High-load приложений** - когда важна производительность  
✅ **API сервисов** - с встроенным rate limiting и защитой  
✅ **Микросервисов** - легковесный и standalone  
✅ **Enterprise проектов** - с высокими требованиями к качеству  
✅ **Проектов с большим количеством маршрутов** - масштабируемость  

### Отличный выбор если:

- Нужна максимальная производительность
- Требуется встроенная безопасность
- Важна масштабируемость
- Нужен standalone роутер без фреймворка
- Хотите высокое качество кода (PHPStan Level MAX)

---

## 📦 Установка

### Требования

- **PHP**: 8.2, 8.3 или 8.4
- **Composer**: 2.x
- **Расширения**: mbstring, json

### Установка через Composer

```bash
composer require cloudcastle/http-router
```

### Проверка установки

```php
<?php

require 'vendor/autoload.php';

use CloudCastle\Http\Router\Facade\Route;

// Создание простого маршрута
Route::get('/', fn() => 'CloudCastle Router работает!');

// Диспетчеризация
echo Route::dispatch('/', 'GET');
```

---

## 🚀 Быстрый старт

### Пример 1: Базовая маршрутизация

```php
use CloudCastle\Http\Router\Facade\Route;

// GET запрос
Route::get('/users', function() {
    return 'Список пользователей';
});

// POST запрос
Route::post('/users', function() {
    return 'Создание пользователя';
});

// С параметрами
Route::get('/user/{id}', function($id) {
    return "Пользователь #$id";
});
```

### Пример 2: С безопасностью

```php
// Rate limiting
Route::post('/api/data', 'ApiController@store')
    ->perMinute(60);

// С автобаном
Route::post('/login', 'AuthController@login')
    ->throttleWithBan(
        maxAttempts: 5,
        decaySeconds: 60,
        maxViolations: 3,
        banDurationSeconds: 7200
    );
```

### Пример 3: Группы маршрутов

```php
Route::group(['prefix' => 'api/v1', 'middleware' => 'auth'], function() {
    Route::get('/users', 'UserController@index');
    Route::get('/posts', 'PostController@index');
});
```

---

## 📊 Производительность

### Бенчмарки

| Нагрузка | Req/sec | Память |
|----------|---------|--------|
| Light (100 routes) | 60,095 | 4 MB |
| Medium (500 routes) | 58,905 | 4 MB |
| Heavy (1,000 routes) | 59,599 | 6 MB |
| Extreme (200k req) | 55,609 | 14 MB |

### Сравнение с конкурентами

```
CloudCastle   ████████████████████ 60,095 req/s
FastRoute     ████████████████░░░░ 50,000 req/s
Symfony       ████████████░░░░░░░░ 30,000 req/s
Laravel       ██████████░░░░░░░░░░ 25,000 req/s
```

[Подробнее о производительности →](performance.md)

---

## 🛡️ Безопасность

### Встроенная защита

- **Rate Limiting**: От секунд до месяцев
- **Auto-Ban**: Автоматическая блокировка при нарушениях
- **IP Filtering**: Белые и черные списки
- **HTTPS Enforcement**: Принудительное использование HTTPS
- **SSRF Protection**: Защита от SSRF атак
- **Security Logging**: Логирование событий безопасности

### Тесты безопасности

✅ 13/13 тестов безопасности пройдено  
✅ OWASP Top 10 compliance  
✅ Protection: XSS, SQL Injection, Path Traversal, ReDoS  

[Подробнее о безопасности →](security.md)

---

## 📚 Документация

### Основные темы

1. [Быстрый старт](quickstart.md) - Первые шаги
2. [Маршруты](routes.md) - Создание и настройка
3. [Автонейминг](auto-naming.md) - Автоматическое именование 🆕
4. [Группы](route-groups.md) - Организация маршрутов
5. [Middleware](middleware.md) - Обработчики запросов
6. [Rate Limiting](rate-limiting.md) - Ограничение запросов
7. [Автобан](auto-ban.md) - Защита от злоупотреблений
8. [Безопасность](security.md) - Защита приложения
9. [Производительность](performance.md) - Оптимизация
10. [API Reference](api-reference.md) - Полный справочник

---

## 🆚 Почему CloudCastle?

### vs FastRoute

| Характеристика | CloudCastle | FastRoute |
|----------------|-------------|-----------|
| Производительность | **60k req/s** | 50k req/s |
| Rate Limiting | ✅ | ❌ |
| Middleware | ✅ | ❌ |
| Auto-ban | ✅ | ❌ |
| IP Filtering | ✅ | ❌ |

### vs Symfony Router

| Характеристика | CloudCastle | Symfony |
|----------------|-------------|---------|
| Производительность | **60k req/s** | 30k req/s |
| Standalone | ✅ | ❌ |
| Rate Limiting | ✅ | ❌ |
| Память/route | **1.47 KB** | 3.8 KB |

[Полное сравнение →](../../reports/comparison.md)

---

## 🎓 Примеры

### В каталоге examples/

- `instance-usage.php` - Базовое использование
- `auto-naming-example.php` - Автонейминг 🆕
- `autoban-example.php` - Система автобана
- `throttle-example.php` - Rate limiting
- `macros-usage.php` - Макросы
- `security-max.php` - Максимальная безопасность

---

## 🤝 Сообщество

### Поддержка

- **GitHub Issues**: https://github.com/zorinalexey/cloud-casstle-http-router/issues
- **Telegram**: https://t.me/cloud_castle_news
- **Email**: zorinalexey59292@gmail.com

### Вклад

Мы приветствуем вклад в проект! См. [CONTRIBUTING.md](../../../CONTRIBUTING.md)

---

## 📄 Лицензия

MIT License - используйте свободно в коммерческих и личных проектах.

---

## 🔗 Следующие шаги

1. [Быстрый старт](quickstart.md) - Начните использовать роутер
2. [Маршруты](routes.md) - Изучите возможности маршрутизации
3. [Производительность](performance.md) - Оптимизируйте ваше приложение

---

**CloudCastle HTTP Router** - Ваш выбор для высокопроизводительной маршрутизации! 🚀

