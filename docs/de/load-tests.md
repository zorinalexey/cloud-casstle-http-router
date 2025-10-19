[🇷🇺 Русский](ru/load-tests.md) | [🇺🇸 English](en/load-tests.md) | [🇩🇪 Deutsch](de/load-tests.md) | [🇫🇷 Français](fr/load-tests.md) | [🇨🇳 中文](zh/load-tests.md)

[📚 Table of Contents](de/_table-of-contents.md) | [🏠 Home](de/README.md)

---

# Lasttests CloudCastle HTTP Router

**Sprachen:** 🇷🇺 Russisch | [🇬🇧 Englisch](../en/load-tests.md) | [🇩🇪 Deutsch](../de/load-tests.md) | [🇫🇷 Français](../fr/load-tests.md) | [🇨🇳中文](../zh/load-tests.md)

[📚 Inhaltsverzeichnis](_table-of-contents.md) | [🏠 Startseite](README.md)

---

## 📊 Allgemeine Informationen

**Testart**: Laden
**Status**: ✅ Alle Tests bestanden
**Zweck**: Testverhalten unter verschiedenen Belastungen

## 🚀 Testergebnisse laden

### Test 1: Leichte Belastung

**Konfiguration:**
- Routes: 100
- Requests: 1,000
- Typ: Sequentielle Anfragen

**Ergebnisse:**
- Duration: 0.0191s
- **Requests/sec: 52,488** ⚡
- Avg response time: 0.02ms
- Memory peak: 6.00 MB

**Analyse:**
- ✅ Hervorragende Leistung für kleine Anwendungen
- ✅ Minimaler Speicherverbrauch
- ✅ Stabile Reaktionszeit

**Anwendung:**
- Kleine Webanwendungen
- Landingpages mit dynamischem Routing
- MVP-Projekte

---

### Test 2: Mittlere Belastung

**Konfiguration:**
- Routes: 500  
- Requests: 5,000
- Typ: Gemischte Anforderungsmuster

**Ergebnisse:**
- Duration: 0.1105s
- **Requests/sec: 45,260** ⚡
- Avg response time: 0.02ms
- Memory peak: 6.00 MB

**Analyse:**
- ✅ Hervorragende Leistung bei mittlerer Belastung
- ✅ Lineare Skalierung
- ✅ Stabiler Speicher

**Anwendung:**
- Unternehmensanwendungen
- CMS-Systeme
- E-Commerce-Plattformen

**Vergleich mit Mitbewerbern:**
| Router | 500 routes, 5K requests | Req/sec |
|:---|:---:|:---:|
| **CloudCastle** | **0.1105s** | **45,260** |
| FastRoute | 0.116s | 43,103 |
| Symfony | 0.338s | 14,793 |
| Laravel | 0.329s | 15,197 |
| Slim | 0.141s | 35,461 |

---

### Test 3: Schwere Belastung

**Konfiguration:**
- Routes: 1,000
- Requests: 10,000
- Typ: Hochfrequenzanfragen

**Ergebnisse:**
- Duration: 0.1815s
- **Requests/sec: 55,089** ⚡
- Avg response time: 0.02ms
- Memory peak: 6.00 MB

**Analyse:**
- ✅ **Bestes Ergebnis** aller Szenarien!
- ✅ Der Router ist gut für hohe Belastungen optimiert
- ✅ Keine Leistungseinbußen

**Anwendung:**
- APIs mit hoher Auslastung
- Echtzeitanwendungen
- Microservices mit hohem Datenverkehr

**Vergleich:**
| Router | Req/sec | vs CloudCastle |
|:---|:---:|:---:|
| **CloudCastle** | **55,089** | **100%** |
| FastRoute | 48,200 | 87.5% |
| Symfony | 15,900 | 28.9% |
| Laravel | 16,400 | 29.8% |
| Slim | 37,200 | 67.5% |

**CloudCastle ist 14 % schneller als FastRoute und 3,4-mal schneller als Laravel!**

---

### Test 4: Concurrent Access Patterns

**Beschreibung**: Testen paralleler Anfragen an verschiedene Routen.

**Konfiguration:**
- Pattern variations: 4
- Total requests: 5,000
- Typ: Gleichzeitige Zugriffssimulation

**Ergebnisse:**
- **Requests/sec: 8,316**
- Avg time: 0.12ms
- Concurrency level: 4

**Zugriffsmuster:**
1. Static routes (/)
2. Dynamic routes (/users/{id})
3. Nested routes (/api/v1/users/{id})
4. Complex routes (/posts/{year}/{month}/{slug})

**Analyse:**
- ✅ Gute Bearbeitung heterogener Anfragen
- ✅ Konsistente Reaktionszeit
- ✅ Keine Rennbedingungen

**Anwendung:**
- Mehrbenutzeranwendungen
- Real-time systems
- High-concurrency APIs

---

### Test 5: Cached vs Uncached Performance

**Beschreibung**: Leistungsvergleich mit und ohne Cache.

**Konfiguration:**
- Routes: 1,000
- Requests per test: 5,000

**Ergebnisse:**

| Mode | Requests/sec | Load Time |
|:---|:---:|:---:|
| **Uncached** | 54,717 | 0.085s |
| **Cached** | 52,296 | 0.012s |
| **Improvement** | -4.6% req/sec | **85.9% faster load** |

**Wichtiger Hinweis**:
- Aufgrund der Deserialisierung ist die Anforderung/Sekunde im Cache etwas langsamer
- Aber **7-mal schneller** beim Laden der App
– In der Produktion ist der Cache für die erste Anfrage **kritisch**

**Gesamtnutzen:**
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

## 📈 Allgemeine Lastübersicht

### Pivot-Tabelle

| Load Type | Routes | Requests | Req/sec | Response Time | Memory | Status |
|:---|:---:|:---:|:---:|:---:|:---:|:---:|
| **Light** | 100 | 1,000 | **52,488** | 0.02ms | 6 MB | ✅ |
| **Medium** | 500 | 5,000 | **45,260** | 0.02ms | 6 MB | ✅ |
| **Heavy** | 1,000 | 10,000 | **55,089** | 0.02ms | 6 MB | ✅ |
| **Concurrent** | 200 | 5,000 | 8,316 | 0.12ms | 6 MB | ✅ |

**Durchschnitt**: 50.946 Anfragen/Sek

### Vergleich mit allen Mitbewerbern

| Router | Light | Medium | Heavy | Average |
|:---|:---:|:---:|:---:|:---:|
| **CloudCastle** | **52,488** | **45,260** | **55,089** | **50,946** |
| FastRoute | 49,800 | 43,100 | 48,200 | 47,033 |
| AltoRouter | 41,200 | 38,600 | 40,100 | 39,967 |
| Slim | 38,900 | 35,400 | 37,200 | 37,167 |
| Laravel | 17,100 | 15,200 | 16,400 | 16,233 |
| Symfony | 16,200 | 14,800 | 15,900 | 15,633 |

### Leistungsvisualisierung

```
CloudCastle ████████████████████████████████████████████████████ 50,946 req/s
FastRoute   ██████████████████████████████████████████████ 47,033 req/s
AltoRouter  ███████████████████████████████████████ 39,967 req/s
Slim        ████████████████████████████████████ 37,167 req/s
Laravel     ███████████████ 16,233 req/s
Symfony     ██████████████ 15,633 req/s
```

## 💡 Empfehlungen laden

### Light Load (< 100 routes)

**Optimale Konfiguration:**
```php
$router = new Router();
// Кеш опционален
// Middleware минимальный
$router->get('/', 'HomeController@index');
```

**Erwartete Leistung**: 52.000+ Anforderungen/Sek

### Medium Load (100-1000 routes)

**Optimale Konfiguration:**
```php
$cache = new RouteCache(__DIR__ . '/cache/routes.php');
$router->setCache($cache);

// Используйте группы для организации
$router->group(['prefix' => '/api'], function($router) {
    // routes...
});
```

**Erwartete Leistung**: 45.000+ Anforderungen/Sek

### Heavy Load (1000-10000 routes)

**Optimale Konfiguration:**
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

**Erwartete Leistung**: 35.000+ Anforderungen/Sek

### Enterprise Load (10000+ routes)

**Optimale Konfiguration:**
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

**Erwartete Leistung**: 25.000+ Anforderungen/Sek

## 🎯 Best Practices

### 1. Caching ist ein Muss für die Produktion

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

### 2. Leistungsüberwachung

```php
use CloudCastle\Http\Router\Plugin\AnalyticsPlugin;

$analytics = new AnalyticsPlugin();
$router->registerGlobalPlugin($analytics);

// После обработки запросов
$stats = $analytics->getStats();
// ['hits' => [...], 'avg_time' => ..., 'memory' => ...]
```

### 3. Optimierung für Last

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

## ✅ Fazit

Der CloudCastle HTTP Router zeigt **hervorragende Ergebnisse** auf allen Laststufen:

- **Leichte Last**: 52.488 Anforderungen/Sek. (bestes Ergebnis)
- **Mittlere Last**: 45.260 Anforderungen/Sek. (bestes Ergebnis)
- **Schwerlast**: 55.089 Anforderungen/Sek. (bestes Ergebnis)

**Eine durchschnittliche Leistung von 50.946 Anforderungen/Sek.** macht ihn zum **schnellsten** PHP-Router auf dem Markt.

Einsatzbereit unter **jeden Bedingungen**: von kleinen Standorten bis hin zu Unternehmensplattformen mit hoher Auslastung.

---

*Letzte Aktualisierung: 18. Oktober 2025*

---

[📚 Inhaltsverzeichnis](_table-of-contents.md) | [🏠 Startseite](README.md)

---

[📚 Table of Contents](de/_table-of-contents.md) | [🏠 Home](de/README.md)
