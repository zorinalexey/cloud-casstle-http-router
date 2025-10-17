# Введение

**CloudCastle HTTP Router v1.1.0**  
**Язык**: Русский

---

**Переводы**: [English](../../en/documentation/introduction.md) | [Deutsch](../../de/documentation/introduction.md) | [Français](../../fr/documentation/introduction.md)

---

## Что такое CloudCastle HTTP Router?

CloudCastle HTTP Router - это высокопроизводительная библиотека маршрутизации HTTP-запросов для PHP 8.2+, разработанная с акцентом на:

- 🚀 **Производительность** - оптимизированный поиск маршрутов O(1)
- 🛡️ **Безопасность** - встроенная защита от атак, OWASP compliance
- 💡 **Удобство** - интуитивный API, статический фасад
- ⚡ **Скорость** - 50,000+ запросов/сек
- 🔒 **Надёжность** - 308 тестов, 748 assertions

## Основные возможности

### Маршрутизация
- Поддержка всех HTTP методов
- Динамические параметры
- Регулярные выражения
- Группы маршрутов
- Именованные маршруты

### Безопасность
- Автоматический бан агрессивных IP
- Rate limiting с гибкими временными окнами
- IP фильтрация (белые/черные списки)
- HTTPS enforcement
- Протокольные ограничения (HTTP/HTTPS/WS/WSS)

### Производительность
- Кеширование маршрутов
- RouteCollection с O(1) поиском
- Компиляция регулярных выражений
- Минимальное потребление памяти (~2MB на 1000 маршрутов)

## Начало работы

См. [Быстрый старт](quickstart.md)

## Документация

- [Маршруты](routes.md)
- [Группы маршрутов](route-groups.md)
- [Middleware](middleware.md)
- [Rate Limiting](rate-limiting.md)
- [Автобан](auto-ban.md)
- [Временные окна](time-units.md)
- [API Reference](api-reference.md)

---

**[◀ Назад к README](../README.md)** | **[Быстрый старт ▶](quickstart.md)**

