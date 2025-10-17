# Производительность

**CloudCastle HTTP Router v1.1.0**  
**Язык**: Русский

---

**Переводы**: [English](../../en/documentation/performance.md) | [Deutsch](../../de/documentation/performance.md) | [Français](../../fr/documentation/performance.md)

---

## ⚡ Обзор производительности

CloudCastle HTTP Router оптимизирован для максимальной скорости и минимального потребления ресурсов.

## 📊 Ключевые метрики

| Метрика | Значение |
|---------|----------|
| **Скорость** | 50,000+ запросов/сек |
| **Память** | ~2MB на 1000 маршрутов |
| **Латентность** | <1ms на маршрут |
| **Поиск** | O(1) для прямых маршрутов |
| **Масштабируемость** | Linear scaling |

## Оптимизации

### 1. RouteCollection с индексами

```php
// O(1) поиск для точных URI
Route::get('/exact-path', fn() => 'fast');

// Оптимизированный поиск для параметризованных маршрутов
Route::get('/user/{id}', fn($id) => "User: $id");
```

### 2. Кеширование маршрутов

```php
// Однократная компиляция
Route::enableCache(__DIR__ . '/cache');
Route::compile(true);

// Последующие запросы используют кеш
Route::loadFromCache();
```

### 3. Компиляция регулярных выражений

Регулярные выражения компилируются один раз при создании маршрута:

```php
Route::get('/user/{id:\d+}', fn($id) => "User: $id");
// Regex скомпилирован и закеширован
```

## Бенчмарки

### Простые маршруты (без параметров)

```
Routes: 1,000
Requests: 10,000
Time: 0.18 сек
RPS: 55,555
Memory: 2MB
```

### Параметризованные маршруты

```
Routes: 1,000
Requests: 10,000
Time: 0.25 сек
RPS: 40,000
Memory: 2.5MB
```

### С кешированием

```
Routes: 10,000
Requests: 100,000
Time: 1.8 сек
RPS: 55,555
Memory: 18MB
```

## Сравнение с альтернативами

См. [Отчёт о производительности](../reports/performance.md)

## Рекомендации

### 1. Используйте кеширование в production

```php
if (getenv('APP_ENV') === 'production') {
    Route::enableCache(__DIR__ . '/cache');
    Route::loadFromCache();
}
```

### 2. Регистрируйте маршруты в правильном порядке

```php
// Более специфичные маршруты РАНЬШЕ
Route::get('/users/special', fn() => 'special');
Route::get('/users/{id}', fn($id) => "User: $id");
```

### 3. Используйте группы для организации

```php
Route::group(['prefix' => '/api'], function() {
    // Все API маршруты здесь
});
```

### 4. Избегайте лишних middleware

```php
// Плохо - middleware на каждом маршруте
Route::get('/a', fn() => 'a')->middleware('log');
Route::get('/b', fn() => 'b')->middleware('log');

// Хорошо - middleware на группе
Route::group(['middleware' => 'log'], function() {
    Route::get('/a', fn() => 'a');
    Route::get('/b', fn() => 'b');
});
```

## Профилирование

Используйте встроенную статистику:

```php
$stats = Route::router()->getRouteStats();

print_r($stats);
/*
[
    'total' => 308,
    'by_method' => ['GET' => 180, 'POST' => 100, ...],
    'named' => 250,
    'tagged' => 150,
    'with_middleware' => 120,
    'with_domain' => 30,
    'with_port' => 15,
    'with_throttle' => 80,
]
*/
```

---

**[◀ Безопасность](security.md)** | **[API Reference ▶](api-reference.md)**

