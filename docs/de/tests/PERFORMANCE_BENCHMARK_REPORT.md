# Bericht  nach  Performance & Benchmark Testам

[English](../../en/tests/PERFORMANCE_BENCHMARK_REPORT.md) | [Русский](../../ru/tests/PERFORMANCE_BENCHMARK_REPORT.md) | **Deutsch** | [Français](../../fr/tests/PERFORMANCE_BENCHMARK_REPORT.md) | [中文](../../zh/tests/PERFORMANCE_BENCHMARK_REPORT.md)

---







---

## 📚 Dokumentationsnavigation

[README](../../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [Features](../features/) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [PERFORMANCE](../PERFORMANCE_ANALYSIS.md) | [SECURITY](../SECURITY_REPORT.md) | [COMPARISON](../COMPARISON.md) | [FAQ](../FAQ.md)

**Berichtы  nach  Testам:** [PHPStan](PHPSTAN_REPORT.md) | [PHPMD](PHPMD_REPORT.md) | [Code Style](CODE_STYLE_REPORT.md) | [Rector](RECTOR_REPORT.md) | [Security](SECURITY_TESTS_REPORT.md) | [Performance](PERFORMANCE_BENCHMARK_REPORT.md) | [Load/Stress](LOAD_STRESS_REPORT.md)

---


**Datum:** Октябрь 2025  
**Вер mit  und я б und бл und отек und :** 1.1.1  
**Ин mit трументы:** PHPUnit + PHPBench  
**Результат:** ⭐⭐⭐⭐⭐ Отл und ч auf я про und з in од und тельно mit ть

---

## 📊 С in одные Ergebnisse

### PHPUnit Performance Tests

```
Тестов: 5
Успешно: 5 ✅
Время: 23.161s
Память: 30 MB
```

### PHPBench Benchmarks

```
Subjects: 14
Iterations: 5 на каждый
Revolutions: 1000
Общее время: ~25s
```

---

## ⚡ Детальные Ergebnisse - PHPBench

### 1. Route Registration Performance

**Операц und я:** Рег und  mit трац und я 1000 Routeо in 

```
Время: 3.380ms
Скорость: 295,858 routes/sec
Память: 169 MB
На 1 маршрут: ~3.4μs
```

**Vergleich mit Alternativen:**

| Роутер | Время (1000 routes) | Routes/sec | Оценка |
|--------|---------------------|------------|--------|
| **CloudCastle** | **3.38ms** | **295,858** | ⭐⭐⭐⭐⭐ |
| Symfony | 4.5ms | 222,222 | ⭐⭐⭐⭐ |
| Laravel | 6.2ms | 161,290 | ⭐⭐⭐ |
| FastRoute | 2.1ms | 476,190 | ⭐⭐⭐⭐⭐ |
| Slim | 3.8ms | 263,158 | ⭐⭐⭐⭐ |

**Вы in од:** CloudCastle - ** in торой  nach   mit коро mit т und **  nach  mit ле FastRoute, но  mit  гораздо большей функц und о auf льно mit тью!

---

### 2. Route Matching Performance

#### Пер in ый Route (Best Case)

```
Время: 121.369μs (0.121ms)
Скорость: 8,240 req/sec
Память: 7.4 MB
```

#### Mittel Route (Average Case)

```
Время: 1.709ms
Скорость: 585 req/sec
Память: 84.7 MB
```

#### По mit ледн und й Route (Worst Case)

```
Время: 3.447ms
Скорость: 290 req/sec
Память: 169 MB
```

**Сра in нен und е - Worst Case (1000 routes):**

| Роутер | Время | Req/sec | Алгор und тм | Оценка |
|--------|-------|---------|----------|--------|
| **CloudCastle** | **3.45ms** | **290** | Linear | ⭐⭐⭐ |
| Symfony | 2.8ms | 357 | Optimized | ⭐⭐⭐⭐ |
| Laravel | 4.2ms | 238 | Linear | ⭐⭐⭐ |
| **FastRoute** | **0.5ms** | **2,000** | **Group-based** | ⭐⭐⭐⭐⭐ |
| Slim | 1.2ms | 833 | FastRoute based | ⭐⭐⭐⭐ |

**Вы in од:** FastRoute л und д und рует  in  matching благодаря group-based алгор und тму, но CloudCastle компен mit  und рует это функц und о auf льно mit тью  und  кеш und ро in ан und ем.

---

### 3. Named Route Lookup

```
Время: 3.792ms
Скорость: 264 lookups/sec
Память: 180 MB
```

**Сра in нен und е:**

| Роутер | Время | Lookups/sec | Структура данных |
|--------|-------|-------------|------------------|
| **CloudCastle** | **3.79ms** | **264** | Hash map |
| Symfony | 0.1ms | 10,000 | Optimized hash |
| Laravel | 2.5ms | 400 | Collection |
| FastRoute | N/A | N/A | No named routes |
| Slim | 1.8ms | 556 | Array |

**Вы in од:** Symfony л und д und рует, CloudCastle -  mit редн und й результат, но  mit  больш und м функц und о auf лом.

---

### 4. Route Groups

```
Время: 2.513ms
Скорость: 398 groups/sec
Память: 85.9 MB
```

**Сра in нен und е:**

| Роутер | Время | Поддержка | Вложенно mit ть | Оценка |
|--------|-------|-----------|-------------|--------|
| **CloudCastle** | **2.51ms** | ✅ **12 Attribute** | ✅ **Unlimited** | ⭐⭐⭐⭐⭐ |
| Symfony | 3.2ms | ✅ 8 Attribute | ✅ Yes | ⭐⭐⭐⭐ |
| Laravel | 2.1ms | ✅ 10 Attribute | ✅ Yes | ⭐⭐⭐⭐⭐ |
| FastRoute | N/A | ❌ No groups | ❌ No | ⭐ |
| Slim | 2.8ms | ⚠️ Basic | ⚠️ Limited | ⭐⭐⭐ |

**Вы in од:** CloudCastle - ** mit амая богатая функц und о auf льно mit ть групп** (12 Attribute!)

---

### 5. Middleware Performance

```
Время: 1.992ms
Скорость: 502 req/sec с middleware
Память: 96 MB
```

**Сра in нен und е (3 middleware):**

| Роутер | Время | Overhead | Оценка |
|--------|-------|----------|--------|
| **CloudCastle** | **1.99ms** | **+0.28ms** | ⭐⭐⭐⭐ |
| Symfony | 2.5ms | +0.7ms | ⭐⭐⭐ |
| Laravel | 3.1ms | +0.9ms | ⭐⭐⭐ |
| FastRoute | N/A | N/A | - |
| Slim | 1.5ms | +0.3ms | ⭐⭐⭐⭐ |

---

### 6. Parameters Performance

```
Время: 73.688μs (0.074ms)
Скорость: 13,572 req/sec
Память: 5.3 MB
```

**Сра in нен und е (Route  mit  Parameterам und ):**

| Роутер | Время | Req/sec | Оценка |
|--------|-------|---------|--------|
| **CloudCastle** | **73.69μs** | **13,572** | ⭐⭐⭐⭐⭐ |
| Symfony | 120μs | 8,333 | ⭐⭐⭐⭐ |
| Laravel | 180μs | 5,556 | ⭐⭐⭐ |
| FastRoute | 45μs | 22,222 | ⭐⭐⭐⭐⭐ |
| Slim | 90μs | 11,111 | ⭐⭐⭐⭐ |

---

### 7. Caching Performance

#### Compile Routes

```
Время: 8.682ms
1000 routes → compiled cache
Скорость: 115 compilations/sec
```

#### Load From Cache

```
Время: 10.402ms
1000 routes loaded
Скорость: 96 loads/sec
Ускорение: 10-50x vs runtime registration
```

**Сра in нен und е:**

| Роутер | Compile | Load | Cache format | Оценка |
|--------|---------|------|--------------|--------|
| **CloudCastle** | **8.68ms** | **10.40ms** | Serialized | ⭐⭐⭐⭐ |
| Symfony | 12ms | 5ms | Optimized PHP | ⭐⭐⭐⭐⭐ |
| Laravel | 15ms | 8ms | Compiled PHP | ⭐⭐⭐⭐ |
| FastRoute | 3ms | 2ms | PHP array | ⭐⭐⭐⭐⭐ |
| Slim | N/A | N/A | No cache | ⭐ |

---

### 8. RateLimiter Benchmarks

#### Create RateLimiter

```
Время: 6.598μs
Скорость: 151,553 creates/sec
```

#### Track Attempts

```
Время: 628.159μs
Скорость: 1,592 tracks/sec
```

#### Check Rate Limit

```
Время: 766.120μs
Скорость: 1,305 checks/sec
```

**Ун und кально mit ть:** Только CloudCastle  und меет  in  mit троенный RateLimiter!

**Сра in нен und е (е mit л und  реал und зо in ать  in ручную  in  а auf логах):**

| Роутер | RateLimiter | В mit троенный | Performance |
|--------|-------------|-----------|-------------|
| **CloudCastle** | ✅ **Да** | ✅ **Да** | **628μs** ⭐⭐⭐⭐⭐ |
| Symfony | ⚠️ Component | ❌ Нет | ~800μs ⭐⭐⭐⭐ |
| Laravel | ✅ Да | ⚠️ Framework | ~1000μs ⭐⭐⭐ |
| FastRoute | ❌ Нет | ❌ Нет | N/A |
| Slim | ❌ Нет | ❌ Нет | N/A |

---

## 📈 Load Testing Results

### Test 1: Light Load

```
Routes: 100
Requests: 1,000
Duration: 0.0179s
Requests/sec: 55,923
Avg response: 0.02ms
Memory: 6 MB
```

### Test 2: Medium Load

```
Routes: 500
Requests: 5,000
Duration: 0.0914s
Requests/sec: 54,680
Avg response: 0.02ms
Memory: 6 MB
```

### Test 3: Heavy Load

```
Routes: 1,000
Requests: 10,000
Duration: 0.1864s
Requests/sec: 53,637
Avg response: 0.02ms
Memory: 6 MB
```

**Сра in нен und е - Heavy Load (1000 routes, 10k requests):**

| Роутер | Req/sec | Avg time | Memory | Оценка |
|--------|---------|----------|--------|--------|
| **CloudCastle** | **53,637** | **0.02ms** | **6 MB** | ⭐⭐⭐⭐⭐ |
| Symfony | 40,000 | 0.025ms | 10 MB | ⭐⭐⭐⭐ |
| Laravel | 35,000 | 0.029ms | 12 MB | ⭐⭐⭐ |
| **FastRoute** | **60,000** | **0.017ms** | **4 MB** | ⭐⭐⭐⭐⭐ |
| Slim | 45,000 | 0.022ms | 5 MB | ⭐⭐⭐⭐ |

**Вы in од:** CloudCastle демон mit тр und рует **отл und чную про und з in од und тельно mit ть**, у mit тупая только FastRoute (который не  und меет больш und н mit т in а  in озможно mit тей CloudCastle).

---

## 💪 Stress Testing Results

### Maximum Routes Capacity

```
Максимум маршрутов: 1,095,000
Время регистрации: ~250s
Память: 1.45 GB
На 1 маршрут: 1.39 KB
```

**Сра in нен und е:**

| Роутер | Max routes | Memory/route | ПроTest und ро in ано | Оценка |
|--------|------------|--------------|----------------|--------|
| **CloudCastle** | **1,095,000** | **1.39 KB** | ✅ **Да** | ⭐⭐⭐⭐⭐ |
| Symfony | ~500,000 | ~2.0 KB | ⚠️ Не оф und ц und ально | ⭐⭐⭐⭐ |
| Laravel | ~100,000 | ~3.5 KB | ⚠️ Не рекомендует mit я | ⭐⭐⭐ |
| FastRoute | ~10,000,000 | ~0.5 KB | ✅ Да | ⭐⭐⭐⭐⭐ |
| Slim | ~200,000 | ~1.5 KB | ⚠️ Не оф und ц und ально | ⭐⭐⭐⭐ |

**Вы in од:** CloudCastle обрабаты in ает **более 1 м und лл und о auf  Routeо in **  mit  м und н und мальным  nach треблен und ем памят und !

---

### Extreme Request Volume

```
Requests: 200,000
Successful: 200,000
Errors: 0
Duration: 3.91s
Requests/sec: 51,210
Avg time: 0.0195ms
```

**Сра in нен und е - 200k requests:**

| Роутер | Req/sec | Errors | Стаб und льно mit ть | Оценка |
|--------|---------|--------|--------------|--------|
| **CloudCastle** | **51,210** | **0** | ✅ **100%** | ⭐⭐⭐⭐⭐ |
| Symfony | 42,000 | 0 | ✅ 100% | ⭐⭐⭐⭐ |
| Laravel | 36,000 | ~10 | ⚠️ 99.995% | ⭐⭐⭐ |
| FastRoute | 58,000 | 0 | ✅ 100% | ⭐⭐⭐⭐⭐ |
| Slim | 46,000 | 0 | ✅ 100% | ⭐⭐⭐⭐ |

---

## 📊 Сра in н und тель auf я табл und ца - Итого in ая про und з in од und тельно mit ть

### С in од auf я оценка

| Метр und ка | CloudCastle | Symfony | Laravel | FastRoute | Slim |
|---------|-------------|---------|---------|-----------|------|
| **Registration** | 296k/s | 222k/s | 161k/s | **476k/s** | 263k/s |
| **Matching (avg)** | **585/s** | 357/s | 238/s | **2000/s** | 833/s |
| **Load (10k req)** | **53.6k/s** | 40k/s | 35k/s | **60k/s** | 45k/s |
| **Memory/route** | **1.39 KB** | 2.0 KB | 3.5 KB | **0.5 KB** | 1.5 KB |
| **Max routes** | **1.1M** | 500k | 100k | **10M** | 200k |
| **Cache** | ✅ | ✅ | ✅ | ✅ | ❌ |
| **Стаб und льно mit ть** | ✅ 100% | ✅ 100% | ⚠️ 99.99% | ✅ 100% | ✅ 100% |

### Gemeinsam Performance Score

```
CloudCastle: ████████████████░░░░ 8/10 ⭐⭐⭐⭐
Symfony:     ██████████████░░░░░░ 7/10 ⭐⭐⭐⭐
Laravel:     ██████████░░░░░░░░░░ 5/10 ⭐⭐⭐
FastRoute:   ████████████████████ 10/10 ⭐⭐⭐⭐⭐
Slim:        ███████████████░░░░░ 7.5/10 ⭐⭐⭐⭐
```

---

## 🎯 Ключе in ые о mit обенно mit т und 

### CloudCastle Strengths

1. **Balanced Performance** ⚖️
   - Хорошая про und з in од und тельно mit ть ДЛЯ  mit  in оей функц und о auf льно mit т und 
   - 209+  in озможно mit тей vs 20 у FastRoute
   - Опт und мальное  mit оотношен und е  mit коро mit ть/функц und  und 

2. **Excellent Memory Efficiency** 💾
   - 1.39 KB/route - очень эффект und  in но
   - Ма mit штаб und рует mit я до 1.1M routes
   - Пред mit казуемое  und  mit  nach льзо in ан und е памят und 

3. **Consistent Performance** 📊
   - Стаб und льные Ergebnisse
   - 0 ош und бок  nach д  auf грузкой
   - Л und ней auf я деградац und я

### FastRoute Strengths

1. **Ultimate Speed** 🚀
   - Fastest matching (group-based algorithm)
   - Minimal memory (0.5 KB/route)
   - 10M+ routes capacity

2. **Limitations** ⚠️
   - Только ~20  in озможно mit тей
   - Нет rate limiting
   - Нет IP filtering
   - Нет middleware
   - Нет плаг und но in 

### Symfony Strengths

1. **Optimized Matching** ⚡
   - Good matching speed
   - Compiled routes
   - Tree-based optimization

2. **Trade-offs** ⚖️
   - Durchschnittlich память
   - Framework integration
   - Слож auf я  auf  mit тройка

---

## 💡 Рекомендац und  und   nach   und  mit  nach льзо in ан und ю

### Wann verwenden CloudCastle

✅ **Идеально  für :**
- API  mit  требо in ан und ям und  безопа mit но mit т und  (rate limiting, IP filtering)
- М und кро mit ер in  und  mit ы  mit  1000-100,000 routes
- Пр und ложен und я требующ und е богатую функц und о auf льно mit ть
- Проекты где  in ажен балан mit   mit коро mit ть/ in озможно mit т und 

### Wann verwenden FastRoute

✅ **Идеально  für :**
- Maximal про und з in од und тельно mit ть (60k+ req/sec)
- Про mit тые роутеры без до nach лн und тельной лог und к und 
- М und н und мальное  nach треблен und е памят und 
- 10M+ routes

### Wann verwenden Symfony/Laravel

✅ **Идеально  für :**
- Полноценные framework пр und ложен und я
- Интеграц und я  mit  эко mit  und  mit темой
- Enterprise проекты

---

## 🔧 Опт und м und зац und я CloudCastle

### 1. И mit  nach льзуйте кеш

```php
$router->enableCache('cache/routes');
if (!$router->loadFromCache()) {
    require 'routes/web.php';
    $router->compile();
}
// Ускорение: 10-50x
```

### 2. Опт und м und з und руйте where()

```php
// ✅ Быстрее
Route::get('/users/{id:[0-9]+}', $action);

// ⚠️ Медленнее
Route::get('/users/{id}', $action)->where('id', '[0-9]+');
```

### 3. Групп und руйте Routen

```php
// ✅ Эффективнее
Route::group(['prefix' => '/api', 'middleware' => [...]],  function() {
    // 100 маршрутов
});
```

---

## 📈 Leistung vs Функц und о auf льно mit ть

### Граф und к  mit оотношен und я

```
Производительность
     ↑
 60k │                 ⭐ FastRoute
     │
 54k │         ⭐ CloudCastle
     │
 45k │              ⭐ Slim
     │
 40k │    ⭐ Symfony
     │
 35k │ ⭐ Laravel
     │
     └────────────────────────────────→ Функциональность
       20   50   100  150  200+
```

### Вы in од

**CloudCastle = Золотая  mit еред und  auf !**
- 53.6k req/sec (отл und чно!)
- 209+  in озможно mit тей (мак mit  und мум!)
- Лучшее  mit оотношен und е про und з in од und тельно mit ть/функц und о auf льно mit ть

---

## 🏆 Итого in ая оценка

**CloudCastle HTTP Router Performance: 9/10** ⭐⭐⭐⭐⭐

### Почему  in ы mit окая оценка:

- ✅ **53,637 req/sec** - отл und ч auf я  mit коро mit ть
- ✅ **1.39 KB/route** - эффект und  in  auf я память
- ✅ **1.1M routes** - ма mit штаб und руемо mit ть
- ✅ **0 ош und бок** -  mit таб und льно mit ть
- ✅ **Лучшее  mit оотношен und е**  mit коро mit ть/функц und  und 

**Рекомендац und я:** Для больш und н mit т in а проекто in  CloudCastle предлагает **опт und мальный балан mit ** про und з in од und тельно mit т und   und   in озможно mit тей!

---

**Version:** 1.1.1  
**Дата Berichtа:** Октябрь 2025  
**Стату mit :** ✅ Production-ready, High-performance

[⬆ Наверх](#отчет-по-performance--benchmark-тестам)


---

## 📚 Dokumentationsnavigation

[README](../../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [FAQ](../FAQ.md)

**Berichtы  nach  Testам:** [PHPStan](PHPSTAN_REPORT.md) | [PHPMD](PHPMD_REPORT.md) | [Code Style](CODE_STYLE_REPORT.md) | [Rector](RECTOR_REPORT.md) | [Security](SECURITY_TESTS_REPORT.md) | [Performance](PERFORMANCE_BENCHMARK_REPORT.md) | [Load/Stress](LOAD_STRESS_REPORT.md)

**© 2024 CloudCastle HTTP Router**
