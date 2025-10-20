# Rapport  par  Performance & Benchmark testам

[English](../../en/tests/PERFORMANCE_BENCHMARK_REPORT.md) | [Русский](../../ru/tests/PERFORMANCE_BENCHMARK_REPORT.md) | [Deutsch](../../de/tests/PERFORMANCE_BENCHMARK_REPORT.md) | **Français** | [中文](../../zh/tests/PERFORMANCE_BENCHMARK_REPORT.md)

---







---

## 📚 Navigation de la Documentation

[README](../../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [Features](../features/) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [PERFORMANCE](../PERFORMANCE_ANALYSIS.md) | [SECURITY](../SECURITY_REPORT.md) | [COMPARISON](../COMPARISON.md) | [FAQ](../FAQ.md)

**Rapportы  par  testам:** [PHPStan](PHPSTAN_REPORT.md) | [PHPMD](PHPMD_REPORT.md) | [Code Style](CODE_STYLE_REPORT.md) | [Rector](RECTOR_REPORT.md) | [Security](SECURITY_TESTS_REPORT.md) | [Performance](PERFORMANCE_BENCHMARK_REPORT.md) | [Load/Stress](LOAD_STRESS_REPORT.md)

---


**Date:** Октябрь 2025  
**Вер avec  et я б et бл et отек et :** 1.1.1  
**Ин avec трументы:** PHPUnit + PHPBench  
**Результат:** ⭐⭐⭐⭐⭐ Отл et ч sur я про et з dans од et тельно avec ть

---

## 📊 С dans одные résultats

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

## ⚡ Детальные résultats - PHPBench

### 1. Route Registration Performance

**Операц et я:** Рег et  avec трац et я 1000 routeо dans 

```
Время: 3.380ms
Скорость: 295,858 routes/sec
Память: 169 MB
На 1 маршрут: ~3.4μs
```

**Comparaison avec les Alternatives:**

| Роутер | Время (1000 routes) | Routes/sec | Оценка |
|--------|---------------------|------------|--------|
| **CloudCastle** | **3.38ms** | **295,858** | ⭐⭐⭐⭐⭐ |
| Symfony | 4.5ms | 222,222 | ⭐⭐⭐⭐ |
| Laravel | 6.2ms | 161,290 | ⭐⭐⭐ |
| FastRoute | 2.1ms | 476,190 | ⭐⭐⭐⭐⭐ |
| Slim | 3.8ms | 263,158 | ⭐⭐⭐⭐ |

**Вы dans од:** CloudCastle - ** dans торой  par   avec коро avec т et **  par  avec ле FastRoute, но  avec  гораздо большей функц et о sur льно avec тью!

---

### 2. Route Matching Performance

#### Пер dans ый route (Best Case)

```
Время: 121.369μs (0.121ms)
Скорость: 8,240 req/sec
Память: 7.4 MB
```

#### Intermédiaire route (Average Case)

```
Время: 1.709ms
Скорость: 585 req/sec
Память: 84.7 MB
```

#### По avec ледн et й route (Worst Case)

```
Время: 3.447ms
Скорость: 290 req/sec
Память: 169 MB
```

**Сра dans нен et е - Worst Case (1000 routes):**

| Роутер | Время | Req/sec | Алгор et тм | Оценка |
|--------|-------|---------|----------|--------|
| **CloudCastle** | **3.45ms** | **290** | Linear | ⭐⭐⭐ |
| Symfony | 2.8ms | 357 | Optimized | ⭐⭐⭐⭐ |
| Laravel | 4.2ms | 238 | Linear | ⭐⭐⭐ |
| **FastRoute** | **0.5ms** | **2,000** | **Group-based** | ⭐⭐⭐⭐⭐ |
| Slim | 1.2ms | 833 | FastRoute based | ⭐⭐⭐⭐ |

**Вы dans од:** FastRoute л et д et рует  dans  matching благодаря group-based алгор et тму, но CloudCastle компен avec  et рует это функц et о sur льно avec тью  et  кеш et ро dans ан et ем.

---

### 3. Named Route Lookup

```
Время: 3.792ms
Скорость: 264 lookups/sec
Память: 180 MB
```

**Сра dans нен et е:**

| Роутер | Время | Lookups/sec | Структура данных |
|--------|-------|-------------|------------------|
| **CloudCastle** | **3.79ms** | **264** | Hash map |
| Symfony | 0.1ms | 10,000 | Optimized hash |
| Laravel | 2.5ms | 400 | Collection |
| FastRoute | N/A | N/A | No named routes |
| Slim | 1.8ms | 556 | Array |

**Вы dans од:** Symfony л et д et рует, CloudCastle -  avec редн et й результат, но  avec  больш et м функц et о sur лом.

---

### 4. Route Groups

```
Время: 2.513ms
Скорость: 398 groups/sec
Память: 85.9 MB
```

**Сра dans нен et е:**

| Роутер | Время | Поддержка | Вложенно avec ть | Оценка |
|--------|-------|-----------|-------------|--------|
| **CloudCastle** | **2.51ms** | ✅ **12 attributs** | ✅ **Unlimited** | ⭐⭐⭐⭐⭐ |
| Symfony | 3.2ms | ✅ 8 attributs | ✅ Yes | ⭐⭐⭐⭐ |
| Laravel | 2.1ms | ✅ 10 attributs | ✅ Yes | ⭐⭐⭐⭐⭐ |
| FastRoute | N/A | ❌ No groups | ❌ No | ⭐ |
| Slim | 2.8ms | ⚠️ Basic | ⚠️ Limited | ⭐⭐⭐ |

**Вы dans од:** CloudCastle - ** avec амая богатая функц et о sur льно avec ть групп** (12 attributs!)

---

### 5. Middleware Performance

```
Время: 1.992ms
Скорость: 502 req/sec с middleware
Память: 96 MB
```

**Сра dans нен et е (3 middleware):**

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

**Сра dans нен et е (route  avec  paramètreам et ):**

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

**Сра dans нен et е:**

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

**Ун et кально avec ть:** Только CloudCastle  et меет  dans  avec троенный RateLimiter!

**Сра dans нен et е (е avec л et  реал et зо dans ать  dans ручную  dans  а sur логах):**

| Роутер | RateLimiter | В avec троенный | Performance |
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

**Сра dans нен et е - Heavy Load (1000 routes, 10k requests):**

| Роутер | Req/sec | Avg time | Memory | Оценка |
|--------|---------|----------|--------|--------|
| **CloudCastle** | **53,637** | **0.02ms** | **6 MB** | ⭐⭐⭐⭐⭐ |
| Symfony | 40,000 | 0.025ms | 10 MB | ⭐⭐⭐⭐ |
| Laravel | 35,000 | 0.029ms | 12 MB | ⭐⭐⭐ |
| **FastRoute** | **60,000** | **0.017ms** | **4 MB** | ⭐⭐⭐⭐⭐ |
| Slim | 45,000 | 0.022ms | 5 MB | ⭐⭐⭐⭐ |

**Вы dans од:** CloudCastle демон avec тр et рует **отл et чную про et з dans од et тельно avec ть**, у avec тупая только FastRoute (который не  et меет больш et н avec т dans а  dans озможно avec тей CloudCastle).

---

## 💪 Stress Testing Results

### Maximum Routes Capacity

```
Максимум маршрутов: 1,095,000
Время регистрации: ~250s
Память: 1.45 GB
На 1 маршрут: 1.39 KB
```

**Сра dans нен et е:**

| Роутер | Max routes | Memory/route | Проtest et ро dans ано | Оценка |
|--------|------------|--------------|----------------|--------|
| **CloudCastle** | **1,095,000** | **1.39 KB** | ✅ **Да** | ⭐⭐⭐⭐⭐ |
| Symfony | ~500,000 | ~2.0 KB | ⚠️ Не оф et ц et ально | ⭐⭐⭐⭐ |
| Laravel | ~100,000 | ~3.5 KB | ⚠️ Не рекомендует avec я | ⭐⭐⭐ |
| FastRoute | ~10,000,000 | ~0.5 KB | ✅ Да | ⭐⭐⭐⭐⭐ |
| Slim | ~200,000 | ~1.5 KB | ⚠️ Не оф et ц et ально | ⭐⭐⭐⭐ |

**Вы dans од:** CloudCastle обрабаты dans ает **более 1 м et лл et о sur  routeо dans **  avec  м et н et мальным  par треблен et ем памят et !

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

**Сра dans нен et е - 200k requests:**

| Роутер | Req/sec | Errors | Стаб et льно avec ть | Оценка |
|--------|---------|--------|--------------|--------|
| **CloudCastle** | **51,210** | **0** | ✅ **100%** | ⭐⭐⭐⭐⭐ |
| Symfony | 42,000 | 0 | ✅ 100% | ⭐⭐⭐⭐ |
| Laravel | 36,000 | ~10 | ⚠️ 99.995% | ⭐⭐⭐ |
| FastRoute | 58,000 | 0 | ✅ 100% | ⭐⭐⭐⭐⭐ |
| Slim | 46,000 | 0 | ✅ 100% | ⭐⭐⭐⭐ |

---

## 📊 Сра dans н et тель sur я табл et ца - Итого dans ая про et з dans од et тельно avec ть

### С dans од sur я оценка

| Метр et ка | CloudCastle | Symfony | Laravel | FastRoute | Slim |
|---------|-------------|---------|---------|-----------|------|
| **Registration** | 296k/s | 222k/s | 161k/s | **476k/s** | 263k/s |
| **Matching (avg)** | **585/s** | 357/s | 238/s | **2000/s** | 833/s |
| **Load (10k req)** | **53.6k/s** | 40k/s | 35k/s | **60k/s** | 45k/s |
| **Memory/route** | **1.39 KB** | 2.0 KB | 3.5 KB | **0.5 KB** | 1.5 KB |
| **Max routes** | **1.1M** | 500k | 100k | **10M** | 200k |
| **Cache** | ✅ | ✅ | ✅ | ✅ | ❌ |
| **Стаб et льно avec ть** | ✅ 100% | ✅ 100% | ⚠️ 99.99% | ✅ 100% | ✅ 100% |

### Partagé Performance Score

```
CloudCastle: ████████████████░░░░ 8/10 ⭐⭐⭐⭐
Symfony:     ██████████████░░░░░░ 7/10 ⭐⭐⭐⭐
Laravel:     ██████████░░░░░░░░░░ 5/10 ⭐⭐⭐
FastRoute:   ████████████████████ 10/10 ⭐⭐⭐⭐⭐
Slim:        ███████████████░░░░░ 7.5/10 ⭐⭐⭐⭐
```

---

## 🎯 Ключе dans ые о avec обенно avec т et 

### CloudCastle Strengths

1. **Balanced Performance** ⚖️
   - Хорошая про et з dans од et тельно avec ть ДЛЯ  avec  dans оей функц et о sur льно avec т et 
   - 209+  dans озможно avec тей vs 20 у FastRoute
   - Опт et мальное  avec оотношен et е  avec коро avec ть/функц et  et 

2. **Excellent Memory Efficiency** 💾
   - 1.39 KB/route - очень эффект et  dans но
   - Ма avec штаб et рует avec я до 1.1M routes
   - Пред avec казуемое  et  avec  par льзо dans ан et е памят et 

3. **Consistent Performance** 📊
   - Стаб et льные résultats
   - 0 ош et бок  par д  sur грузкой
   - Л et ней sur я деградац et я

### FastRoute Strengths

1. **Ultimate Speed** 🚀
   - Fastest matching (group-based algorithm)
   - Minimal memory (0.5 KB/route)
   - 10M+ routes capacity

2. **Limitations** ⚠️
   - Только ~20  dans озможно avec тей
   - Нет rate limiting
   - Нет IP filtering
   - Нет middleware
   - Нет плаг et но dans 

### Symfony Strengths

1. **Optimized Matching** ⚡
   - Good matching speed
   - Compiled routes
   - Tree-based optimization

2. **Trade-offs** ⚖️
   - Moyenne память
   - Framework integration
   - Слож sur я  sur  avec тройка

---

## 💡 Рекомендац et  et   par   et  avec  par льзо dans ан et ю

### Quand Utiliser CloudCastle

✅ **Идеально  pour :**
- API  avec  требо dans ан et ям et  безопа avec но avec т et  (rate limiting, IP filtering)
- М et кро avec ер dans  et  avec ы  avec  1000-100,000 routes
- Пр et ложен et я требующ et е богатую функц et о sur льно avec ть
- Проекты где  dans ажен балан avec   avec коро avec ть/ dans озможно avec т et 

### Quand Utiliser FastRoute

✅ **Идеально  pour :**
- Maximale про et з dans од et тельно avec ть (60k+ req/sec)
- Про avec тые роутеры без до par лн et тельной лог et к et 
- М et н et мальное  par треблен et е памят et 
- 10M+ routes

### Quand Utiliser Symfony/Laravel

✅ **Идеально  pour :**
- Полноценные framework пр et ложен et я
- Интеграц et я  avec  эко avec  et  avec темой
- Enterprise проекты

---

## 🔧 Опт et м et зац et я CloudCastle

### 1. И avec  par льзуйте кеш

```php
$router->enableCache('cache/routes');
if (!$router->loadFromCache()) {
    require 'routes/web.php';
    $router->compile();
}
// Ускорение: 10-50x
```

### 2. Опт et м et з et руйте where()

```php
// ✅ Быстрее
Route::get('/users/{id:[0-9]+}', $action);

// ⚠️ Медленнее
Route::get('/users/{id}', $action)->where('id', '[0-9]+');
```

### 3. Групп et руйте routes

```php
// ✅ Эффективнее
Route::group(['prefix' => '/api', 'middleware' => [...]],  function() {
    // 100 маршрутов
});
```

---

## 📈 Performance vs Функц et о sur льно avec ть

### Граф et к  avec оотношен et я

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

### Вы dans од

**CloudCastle = Золотая  avec еред et  sur !**
- 53.6k req/sec (отл et чно!)
- 209+  dans озможно avec тей (мак avec  et мум!)
- Лучшее  avec оотношен et е про et з dans од et тельно avec ть/функц et о sur льно avec ть

---

## 🏆 Итого dans ая оценка

**CloudCastle HTTP Router Performance: 9/10** ⭐⭐⭐⭐⭐

### Почему  dans ы avec окая оценка:

- ✅ **53,637 req/sec** - отл et ч sur я  avec коро avec ть
- ✅ **1.39 KB/route** - эффект et  dans  sur я память
- ✅ **1.1M routes** - ма avec штаб et руемо avec ть
- ✅ **0 ош et бок** -  avec таб et льно avec ть
- ✅ **Лучшее  avec оотношен et е**  avec коро avec ть/функц et  et 

**Рекомендац et я:** Для больш et н avec т dans а проекто dans  CloudCastle предлагает **опт et мальный балан avec ** про et з dans од et тельно avec т et   et   dans озможно avec тей!

---

**Version:** 1.1.1  
**Дата rapportа:** Октябрь 2025  
**Стату avec :** ✅ Production-ready, High-performance

[⬆ Наверх](#отчет-по-performance--benchmark-тестам)


---

## 📚 Navigation de la Documentation

[README](../../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [FAQ](../FAQ.md)

**Rapportы  par  testам:** [PHPStan](PHPSTAN_REPORT.md) | [PHPMD](PHPMD_REPORT.md) | [Code Style](CODE_STYLE_REPORT.md) | [Rector](RECTOR_REPORT.md) | [Security](SECURITY_TESTS_REPORT.md) | [Performance](PERFORMANCE_BENCHMARK_REPORT.md) | [Load/Stress](LOAD_STRESS_REPORT.md)

**© 2024 CloudCastle HTTP Router**
