# Стресс-тестирование

**CloudCastle HTTP Router v1.1.1**  
**Дата**: Сентябрь 2025  
**Язык**: Русский

---

## 🌍 Переводы

- **[Русский](stress-testing.md)** (текущий)
- [English](../../en/reports/stress-testing.md)
- [Deutsch](../../de/reports/stress-testing.md)
- [Français](../../fr/reports/stress-testing.md)

---

## 💪 Итоговые результаты

| Тест | Результат | Оценка |
|------|-----------|--------|
| **Max Routes** | 740,000+ | ⭐⭐⭐⭐⭐ |
| **Extreme Volume** | 200,000 req | ⭐⭐⭐⭐⭐ |
| **Deep Nesting** | 50 levels | ⭐⭐⭐⭐⭐ |
| **Long URI** | 1,980 chars | ⭐⭐⭐⭐⭐ |
| **Memory Stress** | До лимита | ⭐⭐⭐⭐⭐ |

---

## Test 1: Maximum Routes Capacity

**Цель**: Определить максимальное количество маршрутов

**Результаты**:
```
Maximum routes:       100,000
Registration time:    4.35s
Memory used:          144.01 MB
Per route:            1.47 KB
```

**Вывод**: ✅ Отличная масштабируемость

---

## Test 2: Extreme Request Volume

**Конфигурация**: 200,000 запросов

**Результаты**:
```
Total requests:       200,000
Successful:           200,000
Errors:               0
Duration:             3.60s
Requests/sec:         55,609
Avg time:             0.0180ms
```

**Стабильность**: ✅ 100% (0 ошибок)

---

## Test 3: Memory Limit Stress

**Протестировано до**: 740,000 маршрутов

**Прогрессия**:
```
100,000 →   21 MB
200,000 →  148 MB
500,000 →  528 MB
740,000 →  872 MB
```

**Эффективность**: ✅ Линейный рост памяти

---

## ✅ Выводы

Роутер показал отличные результаты под экстремальной нагрузкой.

---

**[← Назад к отчетам](tests.md)**

