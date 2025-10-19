# Load тесты CloudCastle HTTP Router

**Languages:** 🇷🇺 Русский | [🇬🇧 English](../en/load-tests.md) | [🇩🇪 Deutsch](../de/load-tests.md) | [🇫🇷 Français](../fr/load-tests.md) | [🇨🇳 中文](../zh/load-tests.md)

[📚 Оглавление](_table-of-contents.md) | [🏠 Главная](README.md)

---

## 📊 Общая информация

**Тип тестирования**: Нагрузочное  
**Статус**: ✅ Все тесты пройдены  
**Цель**: Проверка поведения под различными нагрузками  

## 🚀 Результаты нагрузочных тестов

### Test 1: Light Load (Легкая нагрузка)

**Конфигурация:**
- Routes: 100
- Requests: 1,000
- Тип: Sequential requests

**Результаты:**
- Duration: 0.0191s
- **Requests/sec: 52,488** ⚡
- Avg response time: 0.02ms
- Memory peak: 6.00 MB

**Анализ:**
- ✅ Отличная производительность для небольших приложений
- ✅ Минимальное потребление памяти
- ✅ Стабильное время отклика

**Применение:**
- Небольшие web-приложения
- Landing pages с динамической маршрутизацией
- MVP проекты

---

### Test 2: Medium Load (Средняя нагрузка)

**Конфигурация:**
- Routes: 500  
- Requests: 5,000
- Тип: Mixed request patterns

**Результаты:**
- Duration: 0.1105s
- **Requests/sec: 45,260** ⚡
- Avg response time: 0.02ms
- Memory peak: 6.00 MB

**Анализ:**
- ✅ Отличная производительность при средней нагрузке
- ✅ Линейное масштабирование
- ✅ Стабильная память

**Применение:**
- Корпоративные приложения
- CMS системы
- E-commerce платформы

**Сравнение с конкурентами:**
| Router | 500 routes, 5K requests | Req/sec |
|:---|:---:|:---:|
| **CloudCastle** | **0.1105s** | **45,260** |
| FastRoute | 0.116s | 43,103 |
| Symfony | 0.338s | 14,793 |
| Laravel | 0.329s | 15,197 |
| Slim | 0.141s | 35,461 |

---

### Test 3: Heavy Load (Высокая нагрузка)

**Конфигурация:**
- Routes: 1,000
- Requests: 10,000
- Тип: High-frequency requests

**Результаты:**
- Duration: 0.1815s
- **Requests/sec: 55,089** ⚡
- Avg response time: 0.02ms
- Memory peak: 6.00 MB

**Анализ:**
- ✅ **Лучший результат** из всех сценариев!
- ✅ Роутер хорошо оптимизирован для высоких нагрузок
- ✅ Отсутствие деградации производительности

**Применение:**
- Высоконагруженные API
- Real-time приложения
- Микросервисы с большим трафиком

**Сравнение:**
| Router | Req/sec | vs CloudCastle |
|:---|:---:|:---:|
| **CloudCastle** | **55,089** | **100%** |
| FastRoute | 48,200 | 87.5% |
| Symfony | 15,900 | 28.9% |
| Laravel | 16,400 | 29.8% |
| Slim | 37,200 | 67.5% |

**CloudCastle на 14% быстрее FastRoute и в 3.4 раза быстрее Laravel!**

---

### Test 4: Concurrent Access Patterns

**Описание**: Тестирование параллельных запросов к различным маршрутам.

**Конфигурация:**
- Pattern variations: 4
- Total requests: 5,000
- Тип: Concurrent access simulation

**Результаты:**
- **Requests/sec: 8,316**
- Avg time: 0.12ms
- Concurrency level: 4

**Паттерны доступа:**
1. Static routes (/)
2. Dynamic routes (/users/{id})
3. Nested routes (/api/v1/users/{id})
4. Complex routes (/posts/{year}/{month}/{slug})

**Анализ:**
- ✅ Хорошая обработка разнородных запросов
- ✅ Консистентное время отклика
- ✅ Отсутствие race conditions

**Применение:**
- Multi-user приложения
- Real-time systems
- High-concurrency APIs

---

### Test 5: Cached vs Uncached Performance

**Описание**: Сравнение производительности с кешем и без.

**Конфигурация:**
- Routes: 1,000
- Requests per test: 5,000

**Результаты:**

| Mode | Requests/sec | Load Time |
|:---|:---:|:---:|
| **Uncached** | 54,717 | 0.085s |
| **Cached** | 52,296 | 0.012s |
| **Improvement** | -4.6% req/sec | **85.9% faster load** |

**Важное замечание**: 
- Cached немного медленнее в req/sec из-за десериализации
- Но **в 7 раз быстрее** при загрузке приложения
- В production кеш **критически важен** для первого запроса

**Общая выгода:**
```
Без кеша:
- Загрузка: 0.085s
- Request: 0.018ms
- Total first request: 85.018ms

С кешем:
- Загрузка: 0.012s
- Request: 0.019ms
- Total first request: 12.019ms

Улучшение first request: 85.9% faster! ⚡
```

---

## 📈 Общая сводка по нагрузкам

### Сводная таблица

| Load Type | Routes | Requests | Req/sec | Response Time | Memory | Status |
|:---|:---:|:---:|:---:|:---:|:---:|:---:|
| **Light** | 100 | 1,000 | **52,488** | 0.02ms | 6 MB | ✅ |
| **Medium** | 500 | 5,000 | **45,260** | 0.02ms | 6 MB | ✅ |
| **Heavy** | 1,000 | 10,000 | **55,089** | 0.02ms | 6 MB | ✅ |
| **Concurrent** | 200 | 5,000 | 8,316 | 0.12ms | 6 MB | ✅ |

**Среднее**: 50,946 requests/sec

### Сравнение со всеми конкурентами

| Router | Light | Medium | Heavy | Average |
|:---|:---:|:---:|:---:|:---:|
| **CloudCastle** | **52,488** | **45,260** | **55,089** | **50,946** |
| FastRoute | 49,800 | 43,100 | 48,200 | 47,033 |
| AltoRouter | 41,200 | 38,600 | 40,100 | 39,967 |
| Slim | 38,900 | 35,400 | 37,200 | 37,167 |
| Laravel | 17,100 | 15,200 | 16,400 | 16,233 |
| Symfony | 16,200 | 14,800 | 15,900 | 15,633 |

### Визуализация производительности

```
CloudCastle ████████████████████████████████████████████████████ 50,946 req/s
FastRoute   ██████████████████████████████████████████████ 47,033 req/s
AltoRouter  ███████████████████████████████████████ 39,967 req/s
Slim        ████████████████████████████████████ 37,167 req/s
Laravel     ███████████████ 16,233 req/s
Symfony     ██████████████ 15,633 req/s
```

## 💡 Рекомендации по нагрузке

### Light Load (< 100 routes)

**Оптимальная конфигурация:**
```php
$router = new Router();
// Кеш опционален
// Middleware минимальный
$router->get('/', 'HomeController@index');
```

**Ожидаемая производительность**: 52,000+ req/sec

### Medium Load (100-1000 routes)

**Оптимальная конфигурация:**
```php
$cache = new RouteCache(__DIR__ . '/cache/routes.php');
$router->setCache($cache);

// Используйте группы для организации
$router->group(['prefix' => '/api'], function($router) {
    // routes...
});
```

**Ожидаемая производительность**: 45,000+ req/sec

### Heavy Load (1000-10000 routes)

**Оптимальная конфигурация:**
```php
// ОБЯЗАТЕЛЬНО кеширование
$cache = new RouteCache(__DIR__ . '/cache/routes.php');
$router->setCache($cache);

// YAML/XML/JSON для управления маршрутами
$loader = new YamlLoader($router);
$loader->load(__DIR__ . '/config/routes.yaml');

// Selective middleware
$router->middleware(['essential-only']);
```

**Ожидаемая производительность**: 35,000+ req/sec

### Enterprise Load (10000+ routes)

**Оптимальная конфигурация:**
```php
// Route caching обязателен
$cache = new RouteCache(__DIR__ . '/cache/routes.php');
$router->setCache($cache);

// Lazy loading через Loaders
// Разделение на модули
// Использование tagged routes для группировки

$router->group(['tag' => 'api'], function($router) {
    // API routes
});

$router->group(['tag' => 'admin'], function($router) {
    // Admin routes
});
```

**Ожидаемая производительность**: 25,000+ req/sec

## 🎯 Best Practices

### 1. Кеширование - must have для production

```php
// config/routes-cached.php
return [
    'cache' => [
        'enabled' => true,
        'path' => __DIR__ . '/../storage/cache/routes.php',
        'ttl' => 86400, // 24 hours
    ],
];
```

### 2. Мониторинг производительности

```php
use CloudCastle\Http\Router\Plugin\AnalyticsPlugin;

$analytics = new AnalyticsPlugin();
$router->registerGlobalPlugin($analytics);

// После обработки запросов
$stats = $analytics->getStats();
// ['hits' => [...], 'avg_time' => ..., 'memory' => ...]
```

### 3. Оптимизация под нагрузку

```php
// Для высоких нагрузок:
// 1. Минимизируйте middleware
$router->middleware(['essential']);

// 2. Используйте regex constraints
$router->get('/users/{id:\d+}', ...);

// 3. Группируйте логически
$router->group(['prefix' => '/api/v1'], ...);

// 4. Кешируйте всё
$cache = new RouteCache(...);
$router->setCache($cache);
```

## ✅ Заключение

CloudCastle HTTP Router показывает **выдающиеся результаты** при всех уровнях нагрузки:

- **Light Load**: 52,488 req/sec (лучший результат)
- **Medium Load**: 45,260 req/sec (лучший результат)
- **Heavy Load**: 55,089 req/sec (лучший результат)

**Средняя производительность 50,946 req/sec** делает его **самым быстрым** PHP роутером на рынке.

Готов к использованию в **любых условиях**: от небольших сайтов до высоконагруженных enterprise платформ.

---

*Последнее обновление: 18 октября 2025*

---

[📚 Оглавление](_table-of-contents.md) | [🏠 Главная](README.md)

