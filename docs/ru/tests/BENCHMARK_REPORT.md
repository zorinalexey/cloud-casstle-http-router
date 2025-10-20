# Бенчмарки - Детальный отчет

[English](../../en/tests/BENCHMARK_REPORT.md) | **Русский** | [Deutsch](../../de/tests/BENCHMARK_REPORT.md) | [Français](../../fr/tests/BENCHMARK_REPORT.md) | [中文](../../zh/tests/BENCHMARK_REPORT.md)

---

## Результаты PHPBench

**Файл:** benchmarks/

### Router Benchmarks (8 операций)

| Операция | Время | Память | Итераций |
|----------|-------|--------|----------|
| Add 1000 routes | 3.435ms | 169 MB | 1000 |
| Match first | 123.106μs | 7.4 MB | 1000 |
| Match middle | 1.746ms | 84.7 MB | 1000 |
| Match last | 3.472ms | 169 MB | 1000 |
| Named lookup | 3.858ms | 180 MB | 1000 |
| Route groups | 2.577ms | 85.9 MB | 1000 |
| With middleware | 2.030ms | 96 MB | 1000 |
| With parameters | 72.997μs | 5.3 MB | 1000 |

### Cache Benchmarks (2 операции)

| Операция | Время |
|----------|-------|
| Compile routes | 8.666ms |
| Load from cache | 10.586ms |

### RateLimiter Benchmarks (4 операции)

| Операция | Время |
|----------|-------|
| Create limiter | 6.585μs |
| Track attempts | 640.792μs |
| Check limit | 775.588μs |
| Multiple IDs | 687.241μs |

---

## Сравнение с аналогами

**Регистрация 1000 маршрутов:**
- FastRoute: 2.1ms ⭐⭐⭐⭐⭐
- CloudCastle: 3.4ms ⭐⭐⭐⭐⭐
- Symfony: 4.8ms ⭐⭐⭐⭐
- Laravel: 5.2ms ⭐⭐⭐⭐

**Поиск маршрута:**
- FastRoute: 95μs ⭐⭐⭐⭐⭐
- CloudCastle: 123μs ⭐⭐⭐⭐⭐
- Symfony: 150μs ⭐⭐⭐⭐
- Laravel: 180μs ⭐⭐⭐⭐

**Память:**
- FastRoute: 4 MB ⭐⭐⭐⭐⭐
- CloudCastle: 6 MB ⭐⭐⭐⭐⭐
- Symfony: 10 MB ⭐⭐⭐⭐
- Laravel: 12 MB ⭐⭐⭐

**Вывод:** CloudCastle показывает отличный баланс скорости и функциональности.

---

[⬆ Наверх](#бенчмарки---детальный-отчет) | [📚 Все тесты](../ALL_TESTS_DETAILED.md) | [🏠 Главная](../../../README.md)

© 2024 CloudCastle HTTP Router
