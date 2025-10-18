# CloudCastle HTTP Router - Документация

**Версия**: 1.1.1  
**Язык**: Русский

---

## 🌍 Переводы

- **[Русский](README.md)** (текущий)
- [English](../../en/documentation/README.md)
- [Deutsch](../../de/documentation/README.md)
- [Français](../../fr/documentation/README.md)

---

## 📚 Содержание документации

### Основы

1. **[Введение](introduction.md)**
   - О проекте
   - Преимущества
   - Системные требования
   - Установка

2. **[Быстрый старт](quickstart.md)**
   - Первый маршрут
   - Базовая настройка
   - Простые примеры

### Маршрутизация

3. **[Маршруты](routes.md)**
   - Создание маршрутов
   - HTTP методы
   - Параметры маршрутов
   - Ограничения параметров
   - Именованные маршруты
   - Тегированные маршруты

4. **[Автоматическое именование](auto-naming.md)** 🆕
   - Включение автонейминга
   - Правила генерации имен
   - Примеры использования
   - Интеграция с группами

5. **[Группы маршрутов](route-groups.md)**
   - Создание групп
   - Префиксы
   - Middleware групп
   - Вложенные группы
   - Атрибуты групп

### Middleware и безопасность

6. **[Middleware](middleware.md)**
   - Создание middleware
   - Глобальные middleware
   - Middleware маршрутов
   - Цепочки middleware
   - Встроенные middleware

7. **[Rate Limiting](rate-limiting.md)**
   - Базовое ограничение
   - Временные окна
   - Кастомные ключи
   - Методы для разных периодов

8. **[Автобан](auto-ban.md)**
   - Настройка автобана
   - Управление банами
   - Статистика
   - Интеграция с rate limiting

9. **[Безопасность](security.md)**
   - IP фильтрация
   - HTTPS enforcement
   - SSRF protection
   - Security logging
   - Доменные ограничения
   - Протокольные ограничения

### Продвинутые темы

10. **[Производительность](performance.md)**
    - Оптимизация
    - Кеширование
    - Индексация
    - Бенчмарки

11. **[API Reference](api-reference.md)**
    - Классы и методы
    - Интерфейсы
    - Исключения
    - Helper функции

12. **[Макросы](macros.md)**
    - Resource
    - API Resource
    - CRUD
    - Auth
    - Кастомные макросы

13. **[Shortcuts](shortcuts.md)**
    - API shortcuts
    - Auth shortcuts
    - Security shortcuts
    - Throttling shortcuts

## 📊 Отчеты и тестирование

- **[Отчет по тестам](../../reports/tests.md)** - 263 теста, 95% покрытие
- **[Производительность](../../reports/performance.md)** - 60k+ req/s
- **[Безопасность](../../reports/security.md)** - OWASP compliance
- **[Статический анализ](../../reports/static-analysis.md)** - PHPStan Level MAX
- **[Нагрузочное тестирование](../../reports/load-testing.md)** - Масштабирование
- **[Стресс-тестирование](../../reports/stress-testing.md)** - 740k маршрутов
- **[Сравнение с аналогами](../../reports/comparison.md)** - Benchmarks
- **[Итоговый отчет](../../reports/summary.md)** - Общая статистика

## 💡 Примеры кода

Все примеры находятся в каталоге [`examples/`](../../../examples/):

```php
// Базовое использование
require 'examples/instance-usage.php';

// Автоматическое именование
require 'examples/auto-naming-example.php';

// Автобан
require 'examples/autoban-example.php';

// И многое другое...
```

## 🚀 Быстрые ссылки

- [← Назад к главной странице](../../../README.md)
- [📦 Установка](#установка)
- [🎯 Быстрый старт](quickstart.md)
- [📊 Результаты тестирования](../../reports/tests.md)
- [⚡ Производительность](../../reports/performance.md)

---

## 📝 Установка

```bash
composer require cloudcastle/http-router
```

**Требования**:
- PHP 8.2, 8.3 или 8.4
- Composer 2.x

## 🤝 Помощь и поддержка

- **GitHub Issues**: https://github.com/zorinalexey/cloud-casstle-http-router/issues
- **Telegram**: https://t.me/cloud_castle_news
- **Email**: zorinalexey59292@gmail.com

---

**Язык документации**: Русский | [English](../../en/documentation/README.md) | [Deutsch](../../de/documentation/README.md) | [Français](../../fr/documentation/README.md)

