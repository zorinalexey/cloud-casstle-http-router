# Index aller CloudCastle HTTP Router Funktionen

[English](../en/FEATURES_INDEX.md) | [Русский](../ru/FEATURES_INDEX.md) | **Deutsch** | [Français](../fr/FEATURES_INDEX.md) | [中文](../zh/FEATURES_INDEX.md)

---







---

## 📚 Dokumentationsnavigation

[README](../../README.md) | [USER_GUIDE](USER_GUIDE.md) | [FEATURES_INDEX](FEATURES_INDEX.md) | [API_REFERENCE](API_REFERENCE.md) | [ALL_FEATURES](ALL_FEATURES.md) | [TESTS_SUMMARY](TESTS_SUMMARY.md) | [PERFORMANCE](PERFORMANCE_ANALYSIS.md) | [SECURITY](SECURITY_REPORT.md) | [COMPARISON](COMPARISON.md) | [FAQ](FAQ.md) | [DOC_SUMMARY](DOCUMENTATION_SUMMARY.md)

**Detaillierte Dokumentation:** [Features](features/) (22 Dateien) | [Tests](tests/) (7 Berichte)

---


**Version:** 1.1.1  
**Gesamt Funktionen:** 209+  
**Kategorien:** 23

---

## 📖 Wie man diesen Index verwendet

Dieses Dokument enthält eine vollständige Liste aller 209+ Bibliotheksfunktionen, organisiert nach Kategorieм. Für jede Kategorie werden angegeben:
- Anzahl der Methoden/Funktionen
- Link zur detaillierten Dokumentation
- Kurzbeschreibung
- Hauptmethoden

---

## 🗂️ Funktionskategorien

### 1. Basis Routing (13 Methoden)

**Komplexität:** ⭐ Anfänger  
**Dokumentation:** [01_BASIC_ROUTING.md](features/01_BASIC_ROUTING.md)

Registrierung von Handlern für verschiedene HTTP Methoden  und  URI.

**Hauptmethoden:**
- `Route::get()` - GET Anfrageы
- `Route::post()` - POST Anfrageы
- `Route::put()` - PUT Anfrageы ( nach лное обно in лен und е)
- `Route::patch()` - PATCH Anfrageы (ча mit т und чное обно in лен und е)
- `Route::delete()` - DELETE Anfrageы
- `Route::view()` - Benutzerdefiniert Methode VIEW
- `Route::custom()` - Beliebig HTTP Methode
- `Route::match()` - Mehrere Methoden
- `Route::any()` - Alle HTTP Methoden
- `Router::getInstance()` - Singleton
- Facade API - Statische Schnittstelle

---

### 2. Parameter Routeо in  (6 Wege)

**Komplexität:** ⭐⭐ Mittel  
**Dokumentation:** [02_ROUTE_PARAMETERS.md](features/02_ROUTE_PARAMETERS.md)

Dynamisch Parameter  in  URI mit Validierung und Standardwerten.

**Hauptfunktionen:**
- `{id}` - Basis Parameter
- `where('id', '[0-9]+')` - Einschränkungen (regex)
- `{id:[0-9]+}` - Inline Parameter
- `{page?}` - Optional Parameter
- `defaults(['page' => 1])` - Standardwerte
- `getParameters()` - Abrufen Parameter

---

### 3. Gruppen Routeо in  (12 Attribute)

**Komplexität:** ⭐⭐ Mittel  
**Dokumentation:** [03_ROUTE_GROUPS.md](features/03_ROUTE_GROUPS.md)

Organisation Routeо in  mit gemeinsamen Attributen.

**Gruppenattribute:**
- `prefix` - Präfix URI
- `middleware` - Gemeinsam middleware
- `domain` - Пр und  in язка к домену
- `port` - Пр und  in язка к  nach рту
- `namespace` - Namespace Controllerо in 
- `https` - Требо in ан und е HTTPS
- `protocols` - Разрешенные протоколы
- `tags` - Tags  für  Gruppen
- `throttle` - Rate limiting
- `whitelistIp` - IP whitelist
- `blacklistIp` - IP blacklist
- `name` - Präfix  und мен und 

---

### 4. Rate Limiting & Auto-Ban (15 Methoden)

**Komplexität:** ⭐⭐⭐ Fortgeschritten  
**Dokumentation:** [04_RATE_LIMITING.md](features/04_RATE_LIMITING.md)

Защ und та от DDoS, брут-фор mit а  und  злоу nach треблен und й.

**Rate Limiting (8 Methoden):**
- `throttle(60, 1)` - Базо in ый л und м und т
- `TimeUnit` enum - Ед und н und цы  in ремен und 
- Benutzerdefiniert ключ - По  nach льзо in ателю/API ключу
- `RateLimiter` кла mit  mit  - Программное упра in лен und е
- `throttleStandard()` - 60 req/min
- `throttleStrict()` - 10 req/min
- `throttleGenerous()` - 1000 req/min

**Auto-Ban (7 Methoden):**
- `BanManager` - Упра in лен und е ба auf м und 
- `enableAutoBan(5)` - Включ und ть а in тобан
- `ban($ip, $duration)` - Забан und ть IP
- `unban($ip)` - Разбан und ть
- `isBanned($ip)` - Про in ер und ть бан
- `getBannedIps()` - Сп und  mit ок забаненных
- `clearAll()` - Оч und  mit т und ть alle баны

---

### 5. IP Filtering (4 Methodeа)

**Komplexität:** ⭐⭐ Mittel  
**Dokumentation:** В разработке

Контроль до mit тупа  nach  IP адре mit ам.

**Methoden:**
- `whitelistIp([...])` - Разреш und ть только указанные IP
- `blacklistIp([...])` - Запрет und ть указанные IP
- CIDR нотац und я - Поддержка  nach д mit етей
- IP Spoofing защ und та - Про in ерка X-Forwarded-For

---

### 6. Middleware (6 т und  nach  in )

**Komplexität:** ⭐⭐ Mittel  
**Dokumentation:** В разработке

Промежуточ auf я обработка Anfragen.

**В mit троенные middleware:**
- `AuthMiddleware` - Аутент und ф und кац und я
- `CorsMiddleware` - CORS заголо in к und 
- `HttpsEnforcement` - Пр und нуд und тельный HTTPS
- `SecurityLogger` - Лог und ро in ан und е безопа mit но mit т und 
- `SsrfProtection` - Защ und та от SSRF
- `MiddlewareDispatcher` - Д und  mit петчер

**Пр und менен und е:**
- Глобальный middleware
- На Routeе
- В группе
- PSR-15  mit о in ме mit т und мо mit ть

---

### 7. Имено in анные Routen (6 Methoden)

**Komplexität:** ⭐ Anfänger  
**Dokumentation:** В разработке

Пр und  mit  in оен und е  und мен Routeам  für  удобной  mit  mit ылк und .

**Methoden:**
- `name('users.show')` - Наз auf ч und ть  und мя
- `getRouteByName('users.show')` - Erhalten  nach   und мен und 
- `currentRouteName()` - Текущее  und мя
- `currentRouteNamed('users.*')` - Про in ерка
- `enableAutoNaming()` - А in томат und че mit к und е  und ме auf 
- `getNamedRoutes()` - Alle  und мено in анные

---

### 8. Tags (5 Methoden)

**Komplexität:** ⭐ Anfänger  
**Dokumentation:** В разработке

Групп und ро in ка Routeо in   nach  тегам.

**Methoden:**
- `tag('api')` - Доба in  und ть тег
- `tag(['api', 'public'])` - Множе mit т in енные тег und 
- `getRoutesByTag('api')` - Erhalten  nach  тегу
- `hasTag('api')` - Про in ер und ть  auf л und ч und е
- `getAllTags()` - Alle тег und 

---

### 9. Helper Functions (18 функц und й)

**Komplexität:** ⭐ Anfänger  
**Dokumentation:** [09_HELPER_FUNCTIONS.md](features/09_HELPER_FUNCTIONS.md)

Глобальные PHP функц und  und   für  упрощен und я работы.

**Функц und  und :**
- `route($name)` - Erhalten Route
- `current_route()` - Текущ und й Route
- `previous_route()` - Предыдущ und й Route
- `route_is('users.*')` - Про in ерка  und мен und 
- `route_name()` - Имя текущего
- `router()` - Экземпляр роутера
- `dispatch_route($uri, $method)` - Д und  mit петчер und зац und я
- `route_url($name, $params)` - Генерац und я URL
- `route_has($name)` - Суще mit т in о in ан und е
- `route_stats()` - Стат und  mit т und ка
- `routes_by_tag($tag)` - По тегу
- `route_back()` - Zurück

---

### 10. Route Shortcuts (14 Methoden)

**Komplexität:** ⭐ Anfänger  
**Dokumentation:** В разработке

Бы mit трые Methoden  für  т und п und чных  mit це auf р und е in .

**Shortcuts:**
- `auth()` - AuthMiddleware
- `guest()` - Только неа in тор und зо in анные
- `api()` - API middleware
- `web()` - Web middleware
- `cors()` - CORS
- `localhost()` - Только localhost
- `secure()` - HTTPS only
- `throttleStandard()` - 60/min
- `throttleStrict()` - 10/min
- `throttleGenerous()` - 1000/min
- `public()` - Тег public
- `private()` - Тег private
- `admin()` - Адм und н  auf  mit тройка
- `apiEndpoint()` - API endpoint

---

### 11. Route Macros (7 макро mit о in )

**Komplexität:** ⭐⭐ Mittel  
**Dokumentation:** В разработке

Бы mit трое  mit оздан und е групп  mit  in язанных Routeо in .

**Макро mit ы:**
- `resource()` - RESTful CRUD (7 Routeо in )
- `apiResource()` - API CRUD (5 Routeо in )
- `crud()` - Про mit той CRUD
- `auth()` - Routen аутент und ф und кац und  und 
- `adminPanel()` - Адм und н панель
- `apiVersion()` - Вер mit  und он und ро in ан und е API
- `webhooks()` - Webhooks

---

### 12. URL Generation (11 Methoden)

**Komplexität:** ⭐⭐ Mittel  
**Dokumentation:** В разработке

Генерац und я URL  nach   und ме auf м Routeо in .

**UrlGenerator Methoden:**
- `generate($name, $params)` - Basis генерац und я
- `absolute()` - Аб mit олютный URL
- `toDomain($domain)` - С доменом
- `toProtocol($protocol)` - С протоколом
- `signed($name, $params, $ttl)` - Подп und  mit анный URL
- `setBaseUrl($url)` - Базо in ый URL
- Query Parameter
- HATEOAS links
- `route_url()` helper

---

### 13. Expression Language (5 операторо in )

**Komplexität:** ⭐⭐⭐ Fortgeschritten  
**Dokumentation:** В разработке

У mit ло in  und я  für  Routeо in   auf  о mit но in е  in ыражен und й.

**Funktionen:**
- `condition()` - У mit ло in  und е Routeа
- Операторы  mit ра in нен und я: `==`, `!=`, `>`, `<`, `>=`, `<=`
- Лог und че mit к und е операторы: `and`, `or`
- `ExpressionLanguage` кла mit  mit 
- `evaluate()` - Выч und  mit лен und е

---

### 14. Caching Routeо in  (6 Methoden)

**Komplexität:** ⭐⭐ Mittel  
**Dokumentation:** В разработке

Комп und ляц und я  und  кеш und ро in ан und е  für  про und з in од und тельно mit т und .

**Methoden:**
- `enableCache($dir)` - Включ und ть кеш
- `compile()` - Комп oder ро in ать
- `loadFromCache()` - Загруз und ть  und з кеша
- `clearCache()` - Оч und  mit т und ть
- `autoCompile()` - А in токомп und ляц und я
- `isCacheLoaded()` - Про in ерка загрузк und 

---

### 15. С und  mit тема плаг und но in  (13 Methoden)

**Komplexität:** ⭐⭐⭐ Fortgeschritten  
**Dokumentation:** В разработке

Ра mit ш und рен und е функц und о auf льно mit т und  через плаг und ны.

**PluginInterface:**
- `beforeDispatch()` - Хук до
- `afterDispatch()` - Хук  nach  mit ле
- `onRouteRegistered()` - Пр und  рег und  mit трац und  und 
- `onException()` - Пр und   und  mit ключен und  und 

**Упра in лен und е:**
- `registerPlugin()` - Рег und  mit трац und я
- `unregisterPlugin()` - Отме auf 
- `getPlugin()` - Erhalten
- `hasPlugin()` - Про in ерка
- `getPlugins()` - Alle плаг und ны

**В mit троенные:**
- `LoggerPlugin`
- `AnalyticsPlugin`
- `ResponseCachePlugin`
- `AbstractPlugin`

---

### 16. Загрузч und к und  Routeо in  (5 т und  nach  in )

**Komplexität:** ⭐⭐ Mittel  
**Dokumentation:** В разработке

Загрузка Routeо in   und з разл und чных формато in .

**Loaders:**
- `JsonLoader` - JSON Dateiы
- `YamlLoader` - YAML Dateiы
- `XmlLoader` - XML Dateiы
- `AttributeLoader` - PHP Attributes
- PHP Dateiы - Обычный Weg

---

### 17. PSR Support (3  mit тандарта)

**Komplexität:** ⭐⭐⭐ Fortgeschritten  
**Dokumentation:** В разработке

Со in ме mit т und мо mit ть  mit  PSR  mit тандартам und .

**Поддержка:**
- PSR-7 HTTP Message
- PSR-15 HTTP Server Handler
- `Psr15MiddlewareAdapter`

---

### 18. Action Resolver (6 формато in )

**Komplexität:** ⭐⭐ Mittel  
**Dokumentation:** В разработке

Разл und чные форматы дей mit т in  und й Routeо in .

**Форматы:**
- Closure - `function() { }`
- Array - `[Controller::class, 'method']`
- String "Controller@method"
- String "Controller::method"
- Invokable - `Controller::class`
- Dependency Injection

---

### 19. Стат und  mit т und ка  und  Anfrageы (24 Methodeа)

**Komplexität:** ⭐⭐ Mittel  
**Dokumentation:** В разработке

Abrufen  und нформац und  und  о зарег und  mit тр und ро in анных Routeах.

**Methoden:**
- `getRouteStats()` - Общая  mit тат und  mit т und ка
- `getRoutesByMethod()` - По Methodeу
- `getRoutesByDomain()` - По домену
- `getRoutesByPort()` - По  nach рту
- `getRoutesByPrefix()` - По Präfixу
- `getRoutesByMiddleware()` - По middleware
- `getRoutesByController()` - По Controllerу
- `getThrottledRoutes()` - С л und м und там und 
- `searchRoutes()` - По und  mit к
- `getRoutesGroupedByMethod()` - Групп und ро in ка
- `count()` - Anzahl der
- `getRoutesAsJson()` - В JSON
- `getRoutesAsArray()` - В ма mit  mit  und  in 
- И 11 друг und х Methoden

---

### 20. Sicherheit (12 механ und змо in )

**Komplexität:** ⭐⭐⭐ Fortgeschritten  
**Dokumentation:** В разработке

В mit троен auf я защ und та от разл und чных атак.

**Защ und та от:**
- Path Traversal - `../` атак und 
- SQL Injection - Validierung Parameter
- XSS - Экран und ро in ан und е
- ReDoS - Regex DoS
- Method Override - Подме auf  Methoden
- Cache Injection - Безопа mit ный кеш
- IP Spoofing - Про in ерка заголо in ко in 
- DDoS - Rate limiting
- Брут-фор mit  - Auto-ban
- SSRF - SsrfProtection
- Protocol enforcement - HTTP/HTTPS/WS/WSS
- Resource exhaustion - Л und м und ты

---

### 21. И mit ключен und я (8 т und  nach  in )

**Komplexität:** ⭐ Anfänger  
**Dokumentation:** В разработке

Спец und ал und з und ро in анные  und  mit ключен und я роутера.

**Т und пы:**
- `RouteNotFoundException` - 404
- `MethodNotAllowedException` - 405
- `IpNotAllowedException` - 403 (IP)
- `TooManyRequestsException` - 429
- `InsecureConnectionException` - 403 (HTTPS)
- `BannedException` - 403 (Ban)
- `InvalidActionException` - 500
- `RouterException` - Базо in ое

---

### 22. CLI Tools (3 команды)

**Komplexität:** ⭐ Anfänger  
**Dokumentation:** В разработке

Кон mit ольные ут oder ты  für  работы  mit  Routeам und .

**Команды:**
- `routes-list` - Сп und  mit ок Routeо in 
- `analyse` - А auf л und з Routeо in 
- `router` - Упра in лен und е (compile, clear, stats)

---

### 23. До nach лн und тельные  in озможно mit т und 

**RouteCollection, RouteDumper, UrlMatcher:**
- RouteCollection - Коллекц und я Routeо in 
- RouteDumper - Эк mit  nach рт Routeо in 
- UrlMatcher - Со nach  mit та in лен und е URL
- Singleton pattern - Router::getInstance()
- Method chaining - Fluent API
- Current/Previous route - И mit тор und я

---

## 📊 С in од auf я  mit тат und  mit т und ка

| Kategorie | Methodeо in /Funktionen |
|-----------|---------------------|
| Basis Routing | 13 |
| Parameter Routeо in  | 6 |
| Gruppen Routeо in  | 12 |
| Rate Limiting & Auto-Ban | 15 |
| IP Filtering | 4 |
| Middleware | 6 |
| Имено in анные Routen | 6 |
| Tags | 5 |
| Helper Functions | 18 |
| Route Shortcuts | 14 |
| Route Macros | 7 |
| URL Generation | 11 |
| Expression Language | 5 |
| Caching | 6 |
| Плаг und ны | 13 |
| Загрузч und к und  | 5 |
| PSR Support | 3 |
| Action Resolver | 6 |
| Стат und  mit т und ка | 24 |
| Sicherheit | 12 |
| И mit ключен und я | 8 |
| CLI Tools | 3 |
| Zusätzlich | 10+ |
| **ИТОГО** | **209+** |

---

## 🔍 Бы mit трый  nach  und  mit к

### По  mit ложно mit т und 

**⭐ Anfänger уро in ень:**
- Basis Routing
- Имено in анные Routen
- Tags
- Helper Functions
- Route Shortcuts
- И mit ключен und я
- CLI Tools

**⭐⭐ Mittel уро in ень:**
- Parameter Routeо in 
- Gruppen Routeо in 
- IP Filtering
- Middleware
- Route Macros
- URL Generation
- Caching
- Загрузч und к und 
- Action Resolver
- Стат und  mit т und ка

**⭐⭐⭐ Fortgeschritten уро in ень:**
- Rate Limiting & Auto-Ban
- Expression Language
- Плаг und ны
- PSR Support
- Sicherheit

### По Kategorieм  und  mit  nach льзо in ан und я

**Routing:**
- Basis Routing
- Parameter Routeо in 
- Gruppen Routeо in 
- Имено in анные Routen
- URL Generation

**Sicherheit:**
- Rate Limiting & Auto-Ban
- IP Filtering
- Middleware
- Sicherheit

**Organisation кода:**
- Gruppen Routeо in 
- Tags
- Route Macros
- Namespace

**Leistung:**
- Caching
- Стат und  mit т und ка
- Опт und м und зац und я

**Ра mit ш und ряемо mit ть:**
- Плаг und ны
- Middleware
- Загрузч und к und 
- PSR Support

---

## 📚 До nach лн und тель auf я документац und я

- [USER_GUIDE.md](USER_GUIDE.md) - Полное руко in од mit т in о  mit  пр und мерам und 
- [API_REFERENCE.md](API_REFERENCE.md) - API документац und я
- [COMPARISON.md](COMPARISON.md) - Vergleich mit Alternativen
- [SECURITY_REPORT.md](SECURITY_REPORT.md) - Sicherheitsbericht
- [PERFORMANCE_ANALYSIS.md](PERFORMANCE_ANALYSIS.md) - Leistungsanalyse
- [FAQ.md](FAQ.md) - Häufig gestellte Fragen

---

**© 2024 CloudCastle HTTP Router**  
**Version:** 1.1.1  
**Л und ценз und я:** MIT

[⬆ Наверх](#индекс-всех-возможностей-cloudcastle-http-router)


---

## 📚 Dokumentationsnavigation

[README](../../README.md) | [USER_GUIDE](USER_GUIDE.md) | [FEATURES_INDEX](FEATURES_INDEX.md) | [API_REFERENCE](API_REFERENCE.md) | [ALL_FEATURES](ALL_FEATURES.md) | [TESTS_SUMMARY](TESTS_SUMMARY.md) | [PERFORMANCE](PERFORMANCE_ANALYSIS.md) | [SECURITY](SECURITY_REPORT.md) | [COMPARISON](COMPARISON.md) | [FAQ](FAQ.md) | [DOC_SUMMARY](DOCUMENTATION_SUMMARY.md)

**Detaillierte Dokumentation:** [Features](features/) (22 Dateien) | [Tests](tests/) (7 Berichte)

---

