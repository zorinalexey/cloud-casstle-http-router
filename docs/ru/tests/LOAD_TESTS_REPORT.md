# Нагрузочные тесты - Детальный отчет

[English](../../en/tests/LOAD_TESTS_REPORT.md) | **Русский** | [Deutsch](../../de/tests/LOAD_TESTS_REPORT.md) | [Français](../../fr/tests/LOAD_TESTS_REPORT.md) | [中文](../../zh/tests/LOAD_TESTS_REPORT.md)

---

## Результаты

**Файл:** tests/Load/LoadTest.php

### Test 1: Light Load
- Routes: 100
- Requests: 1,000
- **Result: 53,975 req/sec**
- Memory: 6 MB

### Test 2: Medium Load
- Routes: 500
- Requests: 5,000
- **Result: 54,135 req/sec**
- Memory: 6 MB

### Test 3: Heavy Load
- Routes: 1,000
- Requests: 10,000
- **Result: 54,891 req/sec**
- Memory: 6 MB

### Вывод
✅ Стабильная производительность (~54k req/sec)
✅ Память не растет с нагрузкой
✅ Линейное масштабирование

---

## Сравнение с аналогами

| Роутер | Light | Medium | Heavy | Память | Оценка |
|--------|-------|--------|-------|--------|--------|
| FastRoute | 58k | 59k | 60k | 4 MB | ⭐⭐⭐⭐⭐ |
| **CloudCastle** | **54k** | **54k** | **55k** | **6 MB** | **⭐⭐⭐⭐⭐** |
| Symfony | 38k | 39k | 40k | 10 MB | ⭐⭐⭐⭐ |
| Laravel | 33k | 34k | 35k | 12 MB | ⭐⭐⭐ |

**Анализ:**
- CloudCastle немного медленнее FastRoute, но функциональнее
- Значительно быстрее Laravel и Symfony
- Отличное управление памятью

---

## Рекомендации

### Оптимизация

1. **PHP OPcache:**
   ```ini
   opcache.enable=1
   opcache.memory_consumption=256
   ```

2. **PHP JIT:**
   ```ini
   opcache.jit=1255
   opcache.jit_buffer_size=100M
   ```

3. **Используйте именованные routes:**
   - O(1) lookup vs O(n) scan

4. **Без Xdebug в production:**
   - +30% производительности

---

[⬆ Наверх](#нагрузочные-тесты---детальный-отчет) | [📚 Все тесты](../ALL_TESTS_DETAILED.md) | [🏠 Главная](../../../README.md)

© 2024 CloudCastle HTTP Router
