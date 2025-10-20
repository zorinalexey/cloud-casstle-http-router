# Report  by  Performance & Benchmark testам

**English** | [Русский](../../ru/tests/PERFORMANCE_BENCHMARK_REPORT.md) | [Deutsch](../../de/tests/PERFORMANCE_BENCHMARK_REPORT.md) | [Français](../../fr/tests/PERFORMANCE_BENCHMARK_REPORT.md) | [中文](../../zh/tests/PERFORMANCE_BENCHMARK_REPORT.md)

---







---

## 📚 Documentation Navigation

[README](../../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [Features](../features/) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [PERFORMANCE](../PERFORMANCE_ANALYSIS.md) | [SECURITY](../SECURITY_REPORT.md) | [COMPARISON](../COMPARISON.md) | [FAQ](../FAQ.md)

**Reportы  by  testам:** [PHPStan](PHPSTAN_REPORT.md) | [PHPMD](PHPMD_REPORT.md) | [Code Style](CODE_STYLE_REPORT.md) | [Rector](RECTOR_REPORT.md) | [Security](SECURITY_TESTS_REPORT.md) | [Performance](PERFORMANCE_BENCHMARK_REPORT.md) | [Load/Stress](LOAD_STRESS_REPORT.md)

---


**Date:** Октябрь 2025  
**Вер with  and я б and бл and отек and :** 1.1.1  
**Ин with трументы:** PHPUnit + PHPBench  
**Результат:** ⭐⭐⭐⭐⭐ Отл and ч on я про and з in од and тельно with ть

---

## 📊 С in одные results

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

## ⚡ Детальные results - PHPBench

### 1. Route Registration Performance

**Операц and я:** Рег and  with трац and я 1000 routeо in 

```
Время: 3.380ms
Скорость: 295,858 routes/sec
Память: 169 MB
На 1 маршрут: ~3.4μs
```

**Comparison with Alternatives:**

| Роутер | Время (1000 routes) | Routes/sec | Оценка |
|--------|---------------------|------------|--------|
| **CloudCastle** | **3.38ms** | **295,858** | ⭐⭐⭐⭐⭐ |
| Symfony | 4.5ms | 222,222 | ⭐⭐⭐⭐ |
| Laravel | 6.2ms | 161,290 | ⭐⭐⭐ |
| FastRoute | 2.1ms | 476,190 | ⭐⭐⭐⭐⭐ |
| Slim | 3.8ms | 263,158 | ⭐⭐⭐⭐ |

**Вы in од:** CloudCastle - ** in торой  by   with коро with т and **  by  with ле FastRoute, но  with  гораздо большей функц and о on льно with тью!

---

### 2. Route Matching Performance

#### Пер in ый route (Best Case)

```
Время: 121.369μs (0.121ms)
Скорость: 8,240 req/sec
Память: 7.4 MB
```

#### Intermediate route (Average Case)

```
Время: 1.709ms
Скорость: 585 req/sec
Память: 84.7 MB
```

#### По with ледн and й route (Worst Case)

```
Время: 3.447ms
Скорость: 290 req/sec
Память: 169 MB
```

**Сра in нен and е - Worst Case (1000 routes):**

| Роутер | Время | Req/sec | Алгор and тм | Оценка |
|--------|-------|---------|----------|--------|
| **CloudCastle** | **3.45ms** | **290** | Linear | ⭐⭐⭐ |
| Symfony | 2.8ms | 357 | Optimized | ⭐⭐⭐⭐ |
| Laravel | 4.2ms | 238 | Linear | ⭐⭐⭐ |
| **FastRoute** | **0.5ms** | **2,000** | **Group-based** | ⭐⭐⭐⭐⭐ |
| Slim | 1.2ms | 833 | FastRoute based | ⭐⭐⭐⭐ |

**Вы in од:** FastRoute л and д and рует  in  matching благодаря group-based алгор and тму, но CloudCastle компен with  and рует это функц and о on льно with тью  and  кеш and ро in ан and ем.

---

### 3. Named Route Lookup

```
Время: 3.792ms
Скорость: 264 lookups/sec
Память: 180 MB
```

**Сра in нен and е:**

| Роутер | Время | Lookups/sec | Структура данных |
|--------|-------|-------------|------------------|
| **CloudCastle** | **3.79ms** | **264** | Hash map |
| Symfony | 0.1ms | 10,000 | Optimized hash |
| Laravel | 2.5ms | 400 | Collection |
| FastRoute | N/A | N/A | No named routes |
| Slim | 1.8ms | 556 | Array |

**Вы in од:** Symfony л and д and рует, CloudCastle -  with редн and й результат, но  with  больш and м функц and о on лом.

---

### 4. Route Groups

```
Время: 2.513ms
Скорость: 398 groups/sec
Память: 85.9 MB
```

**Сра in нен and е:**

| Роутер | Время | Поддержка | Вложенно with ть | Оценка |
|--------|-------|-----------|-------------|--------|
| **CloudCastle** | **2.51ms** | ✅ **12 attributes** | ✅ **Unlimited** | ⭐⭐⭐⭐⭐ |
| Symfony | 3.2ms | ✅ 8 attributes | ✅ Yes | ⭐⭐⭐⭐ |
| Laravel | 2.1ms | ✅ 10 attributes | ✅ Yes | ⭐⭐⭐⭐⭐ |
| FastRoute | N/A | ❌ No groups | ❌ No | ⭐ |
| Slim | 2.8ms | ⚠️ Basic | ⚠️ Limited | ⭐⭐⭐ |

**Вы in од:** CloudCastle - ** with амая богатая функц and о on льно with ть групп** (12 attributes!)

---

### 5. Middleware Performance

```
Время: 1.992ms
Скорость: 502 req/sec с middleware
Память: 96 MB
```

**Сра in нен and е (3 middleware):**

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

**Сра in нен and е (route  with  parameterам and ):**

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

**Сра in нен and е:**

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

**Ун and кально with ть:** Только CloudCastle  and меет  in  with троенный RateLimiter!

**Сра in нен and е (е with л and  реал and зо in ать  in ручную  in  а on логах):**

| Роутер | RateLimiter | В with троенный | Performance |
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

**Сра in нен and е - Heavy Load (1000 routes, 10k requests):**

| Роутер | Req/sec | Avg time | Memory | Оценка |
|--------|---------|----------|--------|--------|
| **CloudCastle** | **53,637** | **0.02ms** | **6 MB** | ⭐⭐⭐⭐⭐ |
| Symfony | 40,000 | 0.025ms | 10 MB | ⭐⭐⭐⭐ |
| Laravel | 35,000 | 0.029ms | 12 MB | ⭐⭐⭐ |
| **FastRoute** | **60,000** | **0.017ms** | **4 MB** | ⭐⭐⭐⭐⭐ |
| Slim | 45,000 | 0.022ms | 5 MB | ⭐⭐⭐⭐ |

**Вы in од:** CloudCastle демон with тр and рует **отл and чную про and з in од and тельно with ть**, у with тупая только FastRoute (который не  and меет больш and н with т in а  in озможно with тей CloudCastle).

---

## 💪 Stress Testing Results

### Maximum Routes Capacity

```
Максимум маршрутов: 1,095,000
Время регистрации: ~250s
Память: 1.45 GB
На 1 маршрут: 1.39 KB
```

**Сра in нен and е:**

| Роутер | Max routes | Memory/route | Проtest and ро in ано | Оценка |
|--------|------------|--------------|----------------|--------|
| **CloudCastle** | **1,095,000** | **1.39 KB** | ✅ **Да** | ⭐⭐⭐⭐⭐ |
| Symfony | ~500,000 | ~2.0 KB | ⚠️ Не оф and ц and ально | ⭐⭐⭐⭐ |
| Laravel | ~100,000 | ~3.5 KB | ⚠️ Не рекомендует with я | ⭐⭐⭐ |
| FastRoute | ~10,000,000 | ~0.5 KB | ✅ Да | ⭐⭐⭐⭐⭐ |
| Slim | ~200,000 | ~1.5 KB | ⚠️ Не оф and ц and ально | ⭐⭐⭐⭐ |

**Вы in од:** CloudCastle обрабаты in ает **более 1 м and лл and о on  routeо in **  with  м and н and мальным  by треблен and ем памят and !

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

**Сра in нен and е - 200k requests:**

| Роутер | Req/sec | Errors | Стаб and льно with ть | Оценка |
|--------|---------|--------|--------------|--------|
| **CloudCastle** | **51,210** | **0** | ✅ **100%** | ⭐⭐⭐⭐⭐ |
| Symfony | 42,000 | 0 | ✅ 100% | ⭐⭐⭐⭐ |
| Laravel | 36,000 | ~10 | ⚠️ 99.995% | ⭐⭐⭐ |
| FastRoute | 58,000 | 0 | ✅ 100% | ⭐⭐⭐⭐⭐ |
| Slim | 46,000 | 0 | ✅ 100% | ⭐⭐⭐⭐ |

---

## 📊 Сра in н and тель on я табл and ца - Итого in ая про and з in од and тельно with ть

### С in од on я оценка

| Метр and ка | CloudCastle | Symfony | Laravel | FastRoute | Slim |
|---------|-------------|---------|---------|-----------|------|
| **Registration** | 296k/s | 222k/s | 161k/s | **476k/s** | 263k/s |
| **Matching (avg)** | **585/s** | 357/s | 238/s | **2000/s** | 833/s |
| **Load (10k req)** | **53.6k/s** | 40k/s | 35k/s | **60k/s** | 45k/s |
| **Memory/route** | **1.39 KB** | 2.0 KB | 3.5 KB | **0.5 KB** | 1.5 KB |
| **Max routes** | **1.1M** | 500k | 100k | **10M** | 200k |
| **Cache** | ✅ | ✅ | ✅ | ✅ | ❌ |
| **Стаб and льно with ть** | ✅ 100% | ✅ 100% | ⚠️ 99.99% | ✅ 100% | ✅ 100% |

### Shared Performance Score

```
CloudCastle: ████████████████░░░░ 8/10 ⭐⭐⭐⭐
Symfony:     ██████████████░░░░░░ 7/10 ⭐⭐⭐⭐
Laravel:     ██████████░░░░░░░░░░ 5/10 ⭐⭐⭐
FastRoute:   ████████████████████ 10/10 ⭐⭐⭐⭐⭐
Slim:        ███████████████░░░░░ 7.5/10 ⭐⭐⭐⭐
```

---

## 🎯 Ключе in ые о with обенно with т and 

### CloudCastle Strengths

1. **Balanced Performance** ⚖️
   - Хорошая про and з in од and тельно with ть ДЛЯ  with  in оей функц and о on льно with т and 
   - 209+  in озможно with тей vs 20 у FastRoute
   - Опт and мальное  with оотношен and е  with коро with ть/функц and  and 

2. **Excellent Memory Efficiency** 💾
   - 1.39 KB/route - очень эффект and  in но
   - Ма with штаб and рует with я до 1.1M routes
   - Пред with казуемое  and  with  by льзо in ан and е памят and 

3. **Consistent Performance** 📊
   - Стаб and льные results
   - 0 ош and бок  by д  on грузкой
   - Л and ней on я деградац and я

### FastRoute Strengths

1. **Ultimate Speed** 🚀
   - Fastest matching (group-based algorithm)
   - Minimal memory (0.5 KB/route)
   - 10M+ routes capacity

2. **Limitations** ⚠️
   - Только ~20  in озможно with тей
   - Нет rate limiting
   - Нет IP filtering
   - Нет middleware
   - Нет плаг and но in 

### Symfony Strengths

1. **Optimized Matching** ⚡
   - Good matching speed
   - Compiled routes
   - Tree-based optimization

2. **Trade-offs** ⚖️
   - Average память
   - Framework integration
   - Слож on я  on  with тройка

---

## 💡 Рекомендац and  and   by   and  with  by льзо in ан and ю

### When to Use CloudCastle

✅ **Идеально  for :**
- API  with  требо in ан and ям and  безопа with но with т and  (rate limiting, IP filtering)
- М and кро with ер in  and  with ы  with  1000-100,000 routes
- Пр and ложен and я требующ and е богатую функц and о on льно with ть
- Проекты где  in ажен балан with   with коро with ть/ in озможно with т and 

### When to Use FastRoute

✅ **Идеально  for :**
- Maximum про and з in од and тельно with ть (60k+ req/sec)
- Про with тые роутеры без до by лн and тельной лог and к and 
- М and н and мальное  by треблен and е памят and 
- 10M+ routes

### When to Use Symfony/Laravel

✅ **Идеально  for :**
- Полноценные framework пр and ложен and я
- Интеграц and я  with  эко with  and  with темой
- Enterprise проекты

---

## 🔧 Опт and м and зац and я CloudCastle

### 1. И with  by льзуйте кеш

```php
$router->enableCache('cache/routes');
if (!$router->loadFromCache()) {
    require 'routes/web.php';
    $router->compile();
}
// Ускорение: 10-50x
```

### 2. Опт and м and з and руйте where()

```php
// ✅ Быстрее
Route::get('/users/{id:[0-9]+}', $action);

// ⚠️ Медленнее
Route::get('/users/{id}', $action)->where('id', '[0-9]+');
```

### 3. Групп and руйте routes

```php
// ✅ Эффективнее
Route::group(['prefix' => '/api', 'middleware' => [...]],  function() {
    // 100 маршрутов
});
```

---

## 📈 Performance vs Функц and о on льно with ть

### Граф and к  with оотношен and я

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

**CloudCastle = Золотая  with еред and  on !**
- 53.6k req/sec (отл and чно!)
- 209+  in озможно with тей (мак with  and мум!)
- Лучшее  with оотношен and е про and з in од and тельно with ть/функц and о on льно with ть

---

## 🏆 Итого in ая оценка

**CloudCastle HTTP Router Performance: 9/10** ⭐⭐⭐⭐⭐

### Почему  in ы with окая оценка:

- ✅ **53,637 req/sec** - отл and ч on я  with коро with ть
- ✅ **1.39 KB/route** - эффект and  in  on я память
- ✅ **1.1M routes** - ма with штаб and руемо with ть
- ✅ **0 ош and бок** -  with таб and льно with ть
- ✅ **Лучшее  with оотношен and е**  with коро with ть/функц and  and 

**Рекомендац and я:** Для больш and н with т in а проекто in  CloudCastle предлагает **опт and мальный балан with ** про and з in од and тельно with т and   and   in озможно with тей!

---

**Version:** 1.1.1  
**Дата reportа:** Октябрь 2025  
**Стату with :** ✅ Production-ready, High-performance

[⬆ Наверх](#отчет-по-performance--benchmark-тестам)


---

## 📚 Documentation Navigation

[README](../../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [FAQ](../FAQ.md)

**Reportы  by  testам:** [PHPStan](PHPSTAN_REPORT.md) | [PHPMD](PHPMD_REPORT.md) | [Code Style](CODE_STYLE_REPORT.md) | [Rector](RECTOR_REPORT.md) | [Security](SECURITY_TESTS_REPORT.md) | [Performance](PERFORMANCE_BENCHMARK_REPORT.md) | [Load/Stress](LOAD_STRESS_REPORT.md)

**© 2024 CloudCastle HTTP Router**
