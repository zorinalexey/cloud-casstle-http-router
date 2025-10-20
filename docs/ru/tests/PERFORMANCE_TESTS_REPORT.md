# Тесты производительности - Детальный отчет

[English](../../en/tests/PERFORMANCE_TESTS_REPORT.md) | **Русский** | [Deutsch](../../de/tests/PERFORMANCE_TESTS_REPORT.md) | [Français](../../fr/tests/PERFORMANCE_TESTS_REPORT.md) | [中文](../../zh/tests/PERFORMANCE_TESTS_REPORT.md)

---

## Результаты

**Файл:** tests/Performance/BenchmarkTest.php
**Тестов:** 5
**Успешно:** 5 ✅
**Время:** 23.070s
**Память:** 30 MB

### Тесты:
1. ✔ Route registration performance
2. ✔ Route matching performance  
3. ✔ Cached route performance
4. ✔ Memory usage
5. ✔ Group performance

---

## Сравнение с аналогами

| Тест | CloudCastle | Laravel | Symfony | FastRoute |
|------|-------------|---------|---------|-----------|
| Регистрация 1k routes | 3.4ms | 5.2ms | 4.8ms | 2.1ms |
| Поиск first route | 123μs | 180μs | 150μs | 95μs |
| Поиск middle route | 1.7ms | 2.8ms | 2.3ms | 1.2ms |
| Поиск last route | 3.5ms | 5.1ms | 4.2ms | 2.8ms |
| Память (1k routes) | 6 MB | 12 MB | 10 MB | 4 MB |
| **Оценка** | **⭐⭐⭐⭐⭐** | **⭐⭐⭐⭐** | **⭐⭐⭐⭐** | **⭐⭐⭐⭐⭐** |

**Вывод:** CloudCastle показывает отличную производительность, близкую к FastRoute, но с гораздо большей функциональностью.

---

## Рекомендации

1. **Для < 1000 routes:** Не используйте кеш (индексы быстрее)
2. **Для > 10000 routes:** Включите кеширование
3. **Используйте именованные маршруты:** O(1) lookup
4. **Группируйте по префиксам:** Ускоряет поиск

Подробнее: [PERFORMANCE_ANALYSIS.md](../PERFORMANCE_ANALYSIS.md)

---

[⬆ Наверх](#тесты-производительности---детальный-отчет) | [📚 Все тесты](../ALL_TESTS_DETAILED.md) | [🏠 Главная](../../../README.md)

© 2024 CloudCastle HTTP Router
