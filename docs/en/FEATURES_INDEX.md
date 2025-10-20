# Index of All CloudCastle HTTP Router Features

**English** | [Русский](../ru/FEATURES_INDEX.md) | [Deutsch](../de/FEATURES_INDEX.md) | [Français](../fr/FEATURES_INDEX.md) | [中文](../zh/FEATURES_INDEX.md)

---







---

## 📚 Documentation Navigation

[README](../../README.md) | [USER_GUIDE](USER_GUIDE.md) | [FEATURES_INDEX](FEATURES_INDEX.md) | [API_REFERENCE](API_REFERENCE.md) | [ALL_FEATURES](ALL_FEATURES.md) | [TESTS_SUMMARY](TESTS_SUMMARY.md) | [PERFORMANCE](PERFORMANCE_ANALYSIS.md) | [SECURITY](SECURITY_REPORT.md) | [COMPARISON](COMPARISON.md) | [FAQ](FAQ.md) | [DOC_SUMMARY](DOCUMENTATION_SUMMARY.md)

**Detailed documentation:** [Features](features/) (22 files) | [Tests](tests/) (7 reports)

---


**Version:** 1.1.1  
**Total features:** 209+  
**Categories:** 23

---

## 📖 How to Use This Index

This document contains a complete list of all 209+ library features, organized by categoryм. For each category the following is provided:
- Number of methods/features
- Link to detailed documentation
- Brief description
- Main methods

---

## 🗂️ Feature Categories

### 1. Basic Routing (13 methods)

**Complexity:** ⭐ Beginner  
**Documentation:** [01_BASIC_ROUTING.md](features/01_BASIC_ROUTING.md)

Registering handlers for various HTTP methods  and  URI.

**Main methods:**
- `Route::get()` - GET requestы
- `Route::post()` - POST requestы
- `Route::put()` - PUT requestы ( by лное обно in лен and е)
- `Route::patch()` - PATCH requestы (ча with т and чное обно in лен and е)
- `Route::delete()` - DELETE requestы
- `Route::view()` - Custom method VIEW
- `Route::custom()` - Any HTTP method
- `Route::match()` - Multiple methods
- `Route::any()` - All HTTP methods
- `Router::getInstance()` - Singleton
- Facade API - Static interface

---

### 2. Parameters routeо in  (6 ways)

**Complexity:** ⭐⭐ Intermediate  
**Documentation:** [02_ROUTE_PARAMETERS.md](features/02_ROUTE_PARAMETERS.md)

Dynamic parameters  in  URI with validation and default values.

**Core Features:**
- `{id}` - Basic parameters
- `where('id', '[0-9]+')` - Constraints (regex)
- `{id:[0-9]+}` - Inline parameters
- `{page?}` - Optional parameters
- `defaults(['page' => 1])` - Default values
- `getParameters()` - Getting parameters

---

### 3. Groups routeо in  (12 attributes)

**Complexity:** ⭐⭐ Intermediate  
**Documentation:** [03_ROUTE_GROUPS.md](features/03_ROUTE_GROUPS.md)

Organization routeо in  with shared attributes.

**Group attributes:**
- `prefix` - Prefix URI
- `middleware` - Shared middleware
- `domain` - Пр and  in язка к домену
- `port` - Пр and  in язка к  by рту
- `namespace` - Namespace controllerо in 
- `https` - Требо in ан and е HTTPS
- `protocols` - Разрешенные протоколы
- `tags` - Tags  for  groups
- `throttle` - Rate limiting
- `whitelistIp` - IP whitelist
- `blacklistIp` - IP blacklist
- `name` - Prefix  and мен and 

---

### 4. Rate Limiting & Auto-Ban (15 methods)

**Complexity:** ⭐⭐⭐ Advanced  
**Documentation:** [04_RATE_LIMITING.md](features/04_RATE_LIMITING.md)

Защ and та от DDoS, брут-фор with а  and  злоу by треблен and й.

**Rate Limiting (8 methods):**
- `throttle(60, 1)` - Базо in ый л and м and т
- `TimeUnit` enum - Ед and н and цы  in ремен and 
- Custom ключ - По  by льзо in ателю/API ключу
- `RateLimiter` кла with  with  - Программное упра in лен and е
- `throttleStandard()` - 60 req/min
- `throttleStrict()` - 10 req/min
- `throttleGenerous()` - 1000 req/min

**Auto-Ban (7 methods):**
- `BanManager` - Упра in лен and е ба on м and 
- `enableAutoBan(5)` - Включ and ть а in тобан
- `ban($ip, $duration)` - Забан and ть IP
- `unban($ip)` - Разбан and ть
- `isBanned($ip)` - Про in ер and ть бан
- `getBannedIps()` - Сп and  with ок забаненных
- `clearAll()` - Оч and  with т and ть all баны

---

### 5. IP Filtering (4 methodа)

**Complexity:** ⭐⭐ Intermediate  
**Documentation:** В разработке

Контроль до with тупа  by  IP адре with ам.

**Methods:**
- `whitelistIp([...])` - Разреш and ть только указанные IP
- `blacklistIp([...])` - Запрет and ть указанные IP
- CIDR нотац and я - Поддержка  by д with етей
- IP Spoofing защ and та - Про in ерка X-Forwarded-For

---

### 6. Middleware (6 т and  by  in )

**Complexity:** ⭐⭐ Intermediate  
**Documentation:** В разработке

Промежуточ on я обработка requests.

**В with троенные middleware:**
- `AuthMiddleware` - Аутент and ф and кац and я
- `CorsMiddleware` - CORS заголо in к and 
- `HttpsEnforcement` - Пр and нуд and тельный HTTPS
- `SecurityLogger` - Лог and ро in ан and е безопа with но with т and 
- `SsrfProtection` - Защ and та от SSRF
- `MiddlewareDispatcher` - Д and  with петчер

**Пр and менен and е:**
- Глобальный middleware
- На routeе
- В группе
- PSR-15  with о in ме with т and мо with ть

---

### 7. Имено in анные routes (6 methods)

**Complexity:** ⭐ Beginner  
**Documentation:** В разработке

Пр and  with  in оен and е  and мен routeам  for  удобной  with  with ылк and .

**Methods:**
- `name('users.show')` - Наз on ч and ть  and мя
- `getRouteByName('users.show')` - Get  by   and мен and 
- `currentRouteName()` - Текущее  and мя
- `currentRouteNamed('users.*')` - Про in ерка
- `enableAutoNaming()` - А in томат and че with к and е  and ме on 
- `getNamedRoutes()` - All  and мено in анные

---

### 8. Tags (5 methods)

**Complexity:** ⭐ Beginner  
**Documentation:** В разработке

Групп and ро in ка routeо in   by  тегам.

**Methods:**
- `tag('api')` - Доба in  and ть тег
- `tag(['api', 'public'])` - Множе with т in енные тег and 
- `getRoutesByTag('api')` - Get  by  тегу
- `hasTag('api')` - Про in ер and ть  on л and ч and е
- `getAllTags()` - All тег and 

---

### 9. Helper Functions (18 функц and й)

**Complexity:** ⭐ Beginner  
**Documentation:** [09_HELPER_FUNCTIONS.md](features/09_HELPER_FUNCTIONS.md)

Глобальные PHP функц and  and   for  упрощен and я работы.

**Функц and  and :**
- `route($name)` - Get route
- `current_route()` - Текущ and й route
- `previous_route()` - Предыдущ and й route
- `route_is('users.*')` - Про in ерка  and мен and 
- `route_name()` - Имя текущего
- `router()` - Экземпляр роутера
- `dispatch_route($uri, $method)` - Д and  with петчер and зац and я
- `route_url($name, $params)` - Генерац and я URL
- `route_has($name)` - Суще with т in о in ан and е
- `route_stats()` - Стат and  with т and ка
- `routes_by_tag($tag)` - По тегу
- `route_back()` - Back

---

### 10. Route Shortcuts (14 methods)

**Complexity:** ⭐ Beginner  
**Documentation:** В разработке

Бы with трые methods  for  т and п and чных  with це on р and е in .

**Shortcuts:**
- `auth()` - AuthMiddleware
- `guest()` - Только неа in тор and зо in анные
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
- `admin()` - Адм and н  on  with тройка
- `apiEndpoint()` - API endpoint

---

### 11. Route Macros (7 макро with о in )

**Complexity:** ⭐⭐ Intermediate  
**Documentation:** В разработке

Бы with трое  with оздан and е групп  with  in язанных routeо in .

**Макро with ы:**
- `resource()` - RESTful CRUD (7 routeо in )
- `apiResource()` - API CRUD (5 routeо in )
- `crud()` - Про with той CRUD
- `auth()` - Routes аутент and ф and кац and  and 
- `adminPanel()` - Адм and н панель
- `apiVersion()` - Вер with  and он and ро in ан and е API
- `webhooks()` - Webhooks

---

### 12. URL Generation (11 methods)

**Complexity:** ⭐⭐ Intermediate  
**Documentation:** В разработке

Генерац and я URL  by   and ме on м routeо in .

**UrlGenerator methods:**
- `generate($name, $params)` - Basic генерац and я
- `absolute()` - Аб with олютный URL
- `toDomain($domain)` - С доменом
- `toProtocol($protocol)` - С протоколом
- `signed($name, $params, $ttl)` - Подп and  with анный URL
- `setBaseUrl($url)` - Базо in ый URL
- Query parameters
- HATEOAS links
- `route_url()` helper

---

### 13. Expression Language (5 операторо in )

**Complexity:** ⭐⭐⭐ Advanced  
**Documentation:** В разработке

У with ло in  and я  for  routeо in   on  о with но in е  in ыражен and й.

**Features:**
- `condition()` - У with ло in  and е routeа
- Операторы  with ра in нен and я: `==`, `!=`, `>`, `<`, `>=`, `<=`
- Лог and че with к and е операторы: `and`, `or`
- `ExpressionLanguage` кла with  with 
- `evaluate()` - Выч and  with лен and е

---

### 14. Caching routeо in  (6 methods)

**Complexity:** ⭐⭐ Intermediate  
**Documentation:** В разработке

Комп and ляц and я  and  кеш and ро in ан and е  for  про and з in од and тельно with т and .

**Methods:**
- `enableCache($dir)` - Включ and ть кеш
- `compile()` - Комп or ро in ать
- `loadFromCache()` - Загруз and ть  and з кеша
- `clearCache()` - Оч and  with т and ть
- `autoCompile()` - А in токомп and ляц and я
- `isCacheLoaded()` - Про in ерка загрузк and 

---

### 15. С and  with тема плаг and но in  (13 methods)

**Complexity:** ⭐⭐⭐ Advanced  
**Documentation:** В разработке

Ра with ш and рен and е функц and о on льно with т and  через плаг and ны.

**PluginInterface:**
- `beforeDispatch()` - Хук до
- `afterDispatch()` - Хук  by  with ле
- `onRouteRegistered()` - Пр and  рег and  with трац and  and 
- `onException()` - Пр and   and  with ключен and  and 

**Упра in лен and е:**
- `registerPlugin()` - Рег and  with трац and я
- `unregisterPlugin()` - Отме on 
- `getPlugin()` - Get
- `hasPlugin()` - Про in ерка
- `getPlugins()` - All плаг and ны

**В with троенные:**
- `LoggerPlugin`
- `AnalyticsPlugin`
- `ResponseCachePlugin`
- `AbstractPlugin`

---

### 16. Загрузч and к and  routeо in  (5 т and  by  in )

**Complexity:** ⭐⭐ Intermediate  
**Documentation:** В разработке

Загрузка routeо in   and з разл and чных формато in .

**Loaders:**
- `JsonLoader` - JSON fileы
- `YamlLoader` - YAML fileы
- `XmlLoader` - XML fileы
- `AttributeLoader` - PHP Attributes
- PHP fileы - Обычный way

---

### 17. PSR Support (3  with тандарта)

**Complexity:** ⭐⭐⭐ Advanced  
**Documentation:** В разработке

Со in ме with т and мо with ть  with  PSR  with тандартам and .

**Поддержка:**
- PSR-7 HTTP Message
- PSR-15 HTTP Server Handler
- `Psr15MiddlewareAdapter`

---

### 18. Action Resolver (6 формато in )

**Complexity:** ⭐⭐ Intermediate  
**Documentation:** В разработке

Разл and чные форматы дей with т in  and й routeо in .

**Форматы:**
- Closure - `function() { }`
- Array - `[Controller::class, 'method']`
- String "Controller@method"
- String "Controller::method"
- Invokable - `Controller::class`
- Dependency Injection

---

### 19. Стат and  with т and ка  and  requestы (24 methodа)

**Complexity:** ⭐⭐ Intermediate  
**Documentation:** В разработке

Getting  and нформац and  and  о зарег and  with тр and ро in анных routeах.

**Methods:**
- `getRouteStats()` - Общая  with тат and  with т and ка
- `getRoutesByMethod()` - По methodу
- `getRoutesByDomain()` - По домену
- `getRoutesByPort()` - По  by рту
- `getRoutesByPrefix()` - По prefixу
- `getRoutesByMiddleware()` - По middleware
- `getRoutesByController()` - По controllerу
- `getThrottledRoutes()` - С л and м and там and 
- `searchRoutes()` - По and  with к
- `getRoutesGroupedByMethod()` - Групп and ро in ка
- `count()` - Number of
- `getRoutesAsJson()` - В JSON
- `getRoutesAsArray()` - В ма with  with  and  in 
- И 11 друг and х methods

---

### 20. Security (12 механ and змо in )

**Complexity:** ⭐⭐⭐ Advanced  
**Documentation:** В разработке

В with троен on я защ and та от разл and чных атак.

**Защ and та от:**
- Path Traversal - `../` атак and 
- SQL Injection - Validation parameters
- XSS - Экран and ро in ан and е
- ReDoS - Regex DoS
- Method Override - Подме on  methods
- Cache Injection - Безопа with ный кеш
- IP Spoofing - Про in ерка заголо in ко in 
- DDoS - Rate limiting
- Брут-фор with  - Auto-ban
- SSRF - SsrfProtection
- Protocol enforcement - HTTP/HTTPS/WS/WSS
- Resource exhaustion - Л and м and ты

---

### 21. И with ключен and я (8 т and  by  in )

**Complexity:** ⭐ Beginner  
**Documentation:** В разработке

Спец and ал and з and ро in анные  and  with ключен and я роутера.

**Т and пы:**
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

**Complexity:** ⭐ Beginner  
**Documentation:** В разработке

Кон with ольные ут or ты  for  работы  with  routeам and .

**Команды:**
- `routes-list` - Сп and  with ок routeо in 
- `analyse` - А on л and з routeо in 
- `router` - Упра in лен and е (compile, clear, stats)

---

### 23. До by лн and тельные  in озможно with т and 

**RouteCollection, RouteDumper, UrlMatcher:**
- RouteCollection - Коллекц and я routeо in 
- RouteDumper - Эк with  by рт routeо in 
- UrlMatcher - Со by  with та in лен and е URL
- Singleton pattern - Router::getInstance()
- Method chaining - Fluent API
- Current/Previous route - И with тор and я

---

## 📊 С in од on я  with тат and  with т and ка

| Category | Methodо in /Features |
|-----------|---------------------|
| Basic Routing | 13 |
| Parameters routeо in  | 6 |
| Groups routeо in  | 12 |
| Rate Limiting & Auto-Ban | 15 |
| IP Filtering | 4 |
| Middleware | 6 |
| Имено in анные routes | 6 |
| Tags | 5 |
| Helper Functions | 18 |
| Route Shortcuts | 14 |
| Route Macros | 7 |
| URL Generation | 11 |
| Expression Language | 5 |
| Caching | 6 |
| Плаг and ны | 13 |
| Загрузч and к and  | 5 |
| PSR Support | 3 |
| Action Resolver | 6 |
| Стат and  with т and ка | 24 |
| Security | 12 |
| И with ключен and я | 8 |
| CLI Tools | 3 |
| Additional | 10+ |
| **ИТОГО** | **209+** |

---

## 🔍 Бы with трый  by  and  with к

### По  with ложно with т and 

**⭐ Beginner уро in ень:**
- Basic Routing
- Имено in анные routes
- Tags
- Helper Functions
- Route Shortcuts
- И with ключен and я
- CLI Tools

**⭐⭐ Intermediate уро in ень:**
- Parameters routeо in 
- Groups routeо in 
- IP Filtering
- Middleware
- Route Macros
- URL Generation
- Caching
- Загрузч and к and 
- Action Resolver
- Стат and  with т and ка

**⭐⭐⭐ Advanced уро in ень:**
- Rate Limiting & Auto-Ban
- Expression Language
- Плаг and ны
- PSR Support
- Security

### По categoryм  and  with  by льзо in ан and я

**Routing:**
- Basic Routing
- Parameters routeо in 
- Groups routeо in 
- Имено in анные routes
- URL Generation

**Security:**
- Rate Limiting & Auto-Ban
- IP Filtering
- Middleware
- Security

**Organization кода:**
- Groups routeо in 
- Tags
- Route Macros
- Namespace

**Performance:**
- Caching
- Стат and  with т and ка
- Опт and м and зац and я

**Ра with ш and ряемо with ть:**
- Плаг and ны
- Middleware
- Загрузч and к and 
- PSR Support

---

## 📚 До by лн and тель on я документац and я

- [USER_GUIDE.md](USER_GUIDE.md) - Полное руко in од with т in о  with  пр and мерам and 
- [API_REFERENCE.md](API_REFERENCE.md) - API документац and я
- [COMPARISON.md](COMPARISON.md) - Comparison with Alternatives
- [SECURITY_REPORT.md](SECURITY_REPORT.md) - Security Report
- [PERFORMANCE_ANALYSIS.md](PERFORMANCE_ANALYSIS.md) - Performance Analysis
- [FAQ.md](FAQ.md) - Frequently Asked Questions

---

**© 2024 CloudCastle HTTP Router**  
**Version:** 1.1.1  
**Л and ценз and я:** MIT

[⬆ Наверх](#индекс-всех-возможностей-cloudcastle-http-router)


---

## 📚 Documentation Navigation

[README](../../README.md) | [USER_GUIDE](USER_GUIDE.md) | [FEATURES_INDEX](FEATURES_INDEX.md) | [API_REFERENCE](API_REFERENCE.md) | [ALL_FEATURES](ALL_FEATURES.md) | [TESTS_SUMMARY](TESTS_SUMMARY.md) | [PERFORMANCE](PERFORMANCE_ANALYSIS.md) | [SECURITY](SECURITY_REPORT.md) | [COMPARISON](COMPARISON.md) | [FAQ](FAQ.md) | [DOC_SUMMARY](DOCUMENTATION_SUMMARY.md)

**Detailed documentation:** [Features](features/) (22 files) | [Tests](tests/) (7 reports)

---

