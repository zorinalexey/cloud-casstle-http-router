# Index de toutes les Fonctionnalités de CloudCastle HTTP Router

[English](../en/FEATURES_INDEX.md) | [Русский](../ru/FEATURES_INDEX.md) | [Deutsch](../de/FEATURES_INDEX.md) | **Français** | [中文](../zh/FEATURES_INDEX.md)

---







---

## 📚 Navigation de la Documentation

[README](../../README.md) | [USER_GUIDE](USER_GUIDE.md) | [FEATURES_INDEX](FEATURES_INDEX.md) | [API_REFERENCE](API_REFERENCE.md) | [ALL_FEATURES](ALL_FEATURES.md) | [TESTS_SUMMARY](TESTS_SUMMARY.md) | [PERFORMANCE](PERFORMANCE_ANALYSIS.md) | [SECURITY](SECURITY_REPORT.md) | [COMPARISON](COMPARISON.md) | [FAQ](FAQ.md) | [DOC_SUMMARY](DOCUMENTATION_SUMMARY.md)

**Documentation détaillée:** [Features](features/) (22 fichiers) | [Tests](tests/) (7 rapports)

---


**Version:** 1.1.1  
**Total des fonctionnalités:** 209+  
**Catégories:** 23

---

## 📖 Comment Utiliser cet Index

Ce document contient une liste complète de toutes les 209+ fonctionnalités de la bibliothèque, organisées par catégorieм. Pour chaque catégorie sont indiqués:
- Nombre de méthodes/fonctionnalités
- Lien vers la documentation détaillée
- Brève description
- Méthodes principales

---

## 🗂️ Catégories de Fonctionnalités

### 1. Base Routage (13 méthodes)

**Complexité:** ⭐ Débutant  
**Documentation:** [01_BASIC_ROUTING.md](features/01_BASIC_ROUTING.md)

Enregistrement de gestionnaires pour divers HTTP méthodes  et  URI.

**Méthodes principales:**
- `Route::get()` - GET requêteы
- `Route::post()` - POST requêteы
- `Route::put()` - PUT requêteы ( par лное обно dans лен et е)
- `Route::patch()` - PATCH requêteы (ча avec т et чное обно dans лен et е)
- `Route::delete()` - DELETE requêteы
- `Route::view()` - Personnalisé méthode VIEW
- `Route::custom()` - Tout HTTP méthode
- `Route::match()` - Plusieurs méthodes
- `Route::any()` - Tous HTTP méthodes
- `Router::getInstance()` - Singleton
- Facade API - Interface statique

---

### 2. Paramètres routeо dans  (6 façons)

**Complexité:** ⭐⭐ Intermédiaire  
**Documentation:** [02_ROUTE_PARAMETERS.md](features/02_ROUTE_PARAMETERS.md)

Dynamiques paramètres  dans  URI avec validation et valeurs par défaut.

**Fonctionnalités Principales:**
- `{id}` - Basiques paramètres
- `where('id', '[0-9]+')` - Contraintes (regex)
- `{id:[0-9]+}` - Inline paramètres
- `{page?}` - Optionnels paramètres
- `defaults(['page' => 1])` - Valeurs par défaut
- `getParameters()` - Obtenir paramètres

---

### 3. Groupes routeо dans  (12 attributs)

**Complexité:** ⭐⭐ Intermédiaire  
**Documentation:** [03_ROUTE_GROUPS.md](features/03_ROUTE_GROUPS.md)

Organisation routeо dans  avec attributs partagés.

**Attributs de groupe:**
- `prefix` - Préfixe URI
- `middleware` - Partagé middleware
- `domain` - Пр et  dans язка к домену
- `port` - Пр et  dans язка к  par рту
- `namespace` - Namespace contrôleurо dans 
- `https` - Требо dans ан et е HTTPS
- `protocols` - Разрешенные протоколы
- `tags` - Tags  pour  groupes
- `throttle` - Rate limiting
- `whitelistIp` - IP whitelist
- `blacklistIp` - IP blacklist
- `name` - Préfixe  et мен et 

---

### 4. Rate Limiting & Auto-Ban (15 méthodes)

**Complexité:** ⭐⭐⭐ Avancé  
**Documentation:** [04_RATE_LIMITING.md](features/04_RATE_LIMITING.md)

Защ et та от DDoS, брут-фор avec а  et  злоу par треблен et й.

**Rate Limiting (8 méthodes):**
- `throttle(60, 1)` - Базо dans ый л et м et т
- `TimeUnit` enum - Ед et н et цы  dans ремен et 
- Personnalisé ключ - По  par льзо dans ателю/API ключу
- `RateLimiter` кла avec  avec  - Программное упра dans лен et е
- `throttleStandard()` - 60 req/min
- `throttleStrict()` - 10 req/min
- `throttleGenerous()` - 1000 req/min

**Auto-Ban (7 méthodes):**
- `BanManager` - Упра dans лен et е ба sur м et 
- `enableAutoBan(5)` - Включ et ть а dans тобан
- `ban($ip, $duration)` - Забан et ть IP
- `unban($ip)` - Разбан et ть
- `isBanned($ip)` - Про dans ер et ть бан
- `getBannedIps()` - Сп et  avec ок забаненных
- `clearAll()` - Оч et  avec т et ть tous баны

---

### 5. IP Filtering (4 méthodeа)

**Complexité:** ⭐⭐ Intermédiaire  
**Documentation:** В разработке

Контроль до avec тупа  par  IP адре avec ам.

**Méthodes:**
- `whitelistIp([...])` - Разреш et ть только указанные IP
- `blacklistIp([...])` - Запрет et ть указанные IP
- CIDR нотац et я - Поддержка  par д avec етей
- IP Spoofing защ et та - Про dans ерка X-Forwarded-For

---

### 6. Middleware (6 т et  par  dans )

**Complexité:** ⭐⭐ Intermédiaire  
**Documentation:** В разработке

Промежуточ sur я обработка requêtes.

**В avec троенные middleware:**
- `AuthMiddleware` - Аутент et ф et кац et я
- `CorsMiddleware` - CORS заголо dans к et 
- `HttpsEnforcement` - Пр et нуд et тельный HTTPS
- `SecurityLogger` - Лог et ро dans ан et е безопа avec но avec т et 
- `SsrfProtection` - Защ et та от SSRF
- `MiddlewareDispatcher` - Д et  avec петчер

**Пр et менен et е:**
- Глобальный middleware
- На routeе
- В группе
- PSR-15  avec о dans ме avec т et мо avec ть

---

### 7. Имено dans анные routes (6 méthodes)

**Complexité:** ⭐ Débutant  
**Documentation:** В разработке

Пр et  avec  dans оен et е  et мен routeам  pour  удобной  avec  avec ылк et .

**Méthodes:**
- `name('users.show')` - Наз sur ч et ть  et мя
- `getRouteByName('users.show')` - Obtenir  par   et мен et 
- `currentRouteName()` - Текущее  et мя
- `currentRouteNamed('users.*')` - Про dans ерка
- `enableAutoNaming()` - А dans томат et че avec к et е  et ме sur 
- `getNamedRoutes()` - Tous  et мено dans анные

---

### 8. Tags (5 méthodes)

**Complexité:** ⭐ Débutant  
**Documentation:** В разработке

Групп et ро dans ка routeо dans   par  тегам.

**Méthodes:**
- `tag('api')` - Доба dans  et ть тег
- `tag(['api', 'public'])` - Множе avec т dans енные тег et 
- `getRoutesByTag('api')` - Obtenir  par  тегу
- `hasTag('api')` - Про dans ер et ть  sur л et ч et е
- `getAllTags()` - Tous тег et 

---

### 9. Helper Functions (18 функц et й)

**Complexité:** ⭐ Débutant  
**Documentation:** [09_HELPER_FUNCTIONS.md](features/09_HELPER_FUNCTIONS.md)

Глобальные PHP функц et  et   pour  упрощен et я работы.

**Функц et  et :**
- `route($name)` - Obtenir route
- `current_route()` - Текущ et й route
- `previous_route()` - Предыдущ et й route
- `route_is('users.*')` - Про dans ерка  et мен et 
- `route_name()` - Имя текущего
- `router()` - Экземпляр роутера
- `dispatch_route($uri, $method)` - Д et  avec петчер et зац et я
- `route_url($name, $params)` - Генерац et я URL
- `route_has($name)` - Суще avec т dans о dans ан et е
- `route_stats()` - Стат et  avec т et ка
- `routes_by_tag($tag)` - По тегу
- `route_back()` - Retour

---

### 10. Route Shortcuts (14 méthodes)

**Complexité:** ⭐ Débutant  
**Documentation:** В разработке

Бы avec трые méthodes  pour  т et п et чных  avec це sur р et е dans .

**Shortcuts:**
- `auth()` - AuthMiddleware
- `guest()` - Только неа dans тор et зо dans анные
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
- `admin()` - Адм et н  sur  avec тройка
- `apiEndpoint()` - API endpoint

---

### 11. Route Macros (7 макро avec о dans )

**Complexité:** ⭐⭐ Intermédiaire  
**Documentation:** В разработке

Бы avec трое  avec оздан et е групп  avec  dans язанных routeо dans .

**Макро avec ы:**
- `resource()` - RESTful CRUD (7 routeо dans )
- `apiResource()` - API CRUD (5 routeо dans )
- `crud()` - Про avec той CRUD
- `auth()` - Routes аутент et ф et кац et  et 
- `adminPanel()` - Адм et н панель
- `apiVersion()` - Вер avec  et он et ро dans ан et е API
- `webhooks()` - Webhooks

---

### 12. URL Generation (11 méthodes)

**Complexité:** ⭐⭐ Intermédiaire  
**Documentation:** В разработке

Генерац et я URL  par   et ме sur м routeо dans .

**UrlGenerator méthodes:**
- `generate($name, $params)` - Base генерац et я
- `absolute()` - Аб avec олютный URL
- `toDomain($domain)` - С доменом
- `toProtocol($protocol)` - С протоколом
- `signed($name, $params, $ttl)` - Подп et  avec анный URL
- `setBaseUrl($url)` - Базо dans ый URL
- Query paramètres
- HATEOAS links
- `route_url()` helper

---

### 13. Expression Language (5 операторо dans )

**Complexité:** ⭐⭐⭐ Avancé  
**Documentation:** В разработке

У avec ло dans  et я  pour  routeо dans   sur  о avec но dans е  dans ыражен et й.

**Fonctionnalités:**
- `condition()` - У avec ло dans  et е routeа
- Операторы  avec ра dans нен et я: `==`, `!=`, `>`, `<`, `>=`, `<=`
- Лог et че avec к et е операторы: `and`, `or`
- `ExpressionLanguage` кла avec  avec 
- `evaluate()` - Выч et  avec лен et е

---

### 14. Mise en Cache routeо dans  (6 méthodes)

**Complexité:** ⭐⭐ Intermédiaire  
**Documentation:** В разработке

Комп et ляц et я  et  кеш et ро dans ан et е  pour  про et з dans од et тельно avec т et .

**Méthodes:**
- `enableCache($dir)` - Включ et ть кеш
- `compile()` - Комп ou ро dans ать
- `loadFromCache()` - Загруз et ть  et з кеша
- `clearCache()` - Оч et  avec т et ть
- `autoCompile()` - А dans токомп et ляц et я
- `isCacheLoaded()` - Про dans ерка загрузк et 

---

### 15. С et  avec тема плаг et но dans  (13 méthodes)

**Complexité:** ⭐⭐⭐ Avancé  
**Documentation:** В разработке

Ра avec ш et рен et е функц et о sur льно avec т et  через плаг et ны.

**PluginInterface:**
- `beforeDispatch()` - Хук до
- `afterDispatch()` - Хук  par  avec ле
- `onRouteRegistered()` - Пр et  рег et  avec трац et  et 
- `onException()` - Пр et   et  avec ключен et  et 

**Упра dans лен et е:**
- `registerPlugin()` - Рег et  avec трац et я
- `unregisterPlugin()` - Отме sur 
- `getPlugin()` - Obtenir
- `hasPlugin()` - Про dans ерка
- `getPlugins()` - Tous плаг et ны

**В avec троенные:**
- `LoggerPlugin`
- `AnalyticsPlugin`
- `ResponseCachePlugin`
- `AbstractPlugin`

---

### 16. Загрузч et к et  routeо dans  (5 т et  par  dans )

**Complexité:** ⭐⭐ Intermédiaire  
**Documentation:** В разработке

Загрузка routeо dans   et з разл et чных формато dans .

**Loaders:**
- `JsonLoader` - JSON fichierы
- `YamlLoader` - YAML fichierы
- `XmlLoader` - XML fichierы
- `AttributeLoader` - PHP Attributes
- PHP fichierы - Обычный façon

---

### 17. PSR Support (3  avec тандарта)

**Complexité:** ⭐⭐⭐ Avancé  
**Documentation:** В разработке

Со dans ме avec т et мо avec ть  avec  PSR  avec тандартам et .

**Поддержка:**
- PSR-7 HTTP Message
- PSR-15 HTTP Server Handler
- `Psr15MiddlewareAdapter`

---

### 18. Action Resolver (6 формато dans )

**Complexité:** ⭐⭐ Intermédiaire  
**Documentation:** В разработке

Разл et чные форматы дей avec т dans  et й routeо dans .

**Форматы:**
- Closure - `function() { }`
- Array - `[Controller::class, 'method']`
- String "Controller@method"
- String "Controller::method"
- Invokable - `Controller::class`
- Dependency Injection

---

### 19. Стат et  avec т et ка  et  requêteы (24 méthodeа)

**Complexité:** ⭐⭐ Intermédiaire  
**Documentation:** В разработке

Obtenir  et нформац et  et  о зарег et  avec тр et ро dans анных routeах.

**Méthodes:**
- `getRouteStats()` - Общая  avec тат et  avec т et ка
- `getRoutesByMethod()` - По méthodeу
- `getRoutesByDomain()` - По домену
- `getRoutesByPort()` - По  par рту
- `getRoutesByPrefix()` - По préfixeу
- `getRoutesByMiddleware()` - По middleware
- `getRoutesByController()` - По contrôleurу
- `getThrottledRoutes()` - С л et м et там et 
- `searchRoutes()` - По et  avec к
- `getRoutesGroupedByMethod()` - Групп et ро dans ка
- `count()` - Nombre de
- `getRoutesAsJson()` - В JSON
- `getRoutesAsArray()` - В ма avec  avec  et  dans 
- И 11 друг et х méthodes

---

### 20. Sécurité (12 механ et змо dans )

**Complexité:** ⭐⭐⭐ Avancé  
**Documentation:** В разработке

В avec троен sur я защ et та от разл et чных атак.

**Защ et та от:**
- Path Traversal - `../` атак et 
- SQL Injection - Validation paramètres
- XSS - Экран et ро dans ан et е
- ReDoS - Regex DoS
- Method Override - Подме sur  méthodes
- Cache Injection - Безопа avec ный кеш
- IP Spoofing - Про dans ерка заголо dans ко dans 
- DDoS - Rate limiting
- Брут-фор avec  - Auto-ban
- SSRF - SsrfProtection
- Protocol enforcement - HTTP/HTTPS/WS/WSS
- Resource exhaustion - Л et м et ты

---

### 21. И avec ключен et я (8 т et  par  dans )

**Complexité:** ⭐ Débutant  
**Documentation:** В разработке

Спец et ал et з et ро dans анные  et  avec ключен et я роутера.

**Т et пы:**
- `RouteNotFoundException` - 404
- `MethodNotAllowedException` - 405
- `IpNotAllowedException` - 403 (IP)
- `TooManyRequestsException` - 429
- `InsecureConnectionException` - 403 (HTTPS)
- `BannedException` - 403 (Ban)
- `InvalidActionException` - 500
- `RouterException` - Базо dans ое

---

### 22. CLI Tools (3 команды)

**Complexité:** ⭐ Débutant  
**Documentation:** В разработке

Кон avec ольные ут ou ты  pour  работы  avec  routeам et .

**Команды:**
- `routes-list` - Сп et  avec ок routeо dans 
- `analyse` - А sur л et з routeо dans 
- `router` - Упра dans лен et е (compile, clear, stats)

---

### 23. До par лн et тельные  dans озможно avec т et 

**RouteCollection, RouteDumper, UrlMatcher:**
- RouteCollection - Коллекц et я routeо dans 
- RouteDumper - Эк avec  par рт routeо dans 
- UrlMatcher - Со par  avec та dans лен et е URL
- Singleton pattern - Router::getInstance()
- Method chaining - Fluent API
- Current/Previous route - И avec тор et я

---

## 📊 С dans од sur я  avec тат et  avec т et ка

| Catégorie | Méthodeо dans /Fonctionnalités |
|-----------|---------------------|
| Base Routage | 13 |
| Paramètres routeо dans  | 6 |
| Groupes routeо dans  | 12 |
| Rate Limiting & Auto-Ban | 15 |
| IP Filtering | 4 |
| Middleware | 6 |
| Имено dans анные routes | 6 |
| Tags | 5 |
| Helper Functions | 18 |
| Route Shortcuts | 14 |
| Route Macros | 7 |
| URL Generation | 11 |
| Expression Language | 5 |
| Mise en Cache | 6 |
| Плаг et ны | 13 |
| Загрузч et к et  | 5 |
| PSR Support | 3 |
| Action Resolver | 6 |
| Стат et  avec т et ка | 24 |
| Sécurité | 12 |
| И avec ключен et я | 8 |
| CLI Tools | 3 |
| Supplémentaire | 10+ |
| **ИТОГО** | **209+** |

---

## 🔍 Бы avec трый  par  et  avec к

### По  avec ложно avec т et 

**⭐ Débutant уро dans ень:**
- Base Routage
- Имено dans анные routes
- Tags
- Helper Functions
- Route Shortcuts
- И avec ключен et я
- CLI Tools

**⭐⭐ Intermédiaire уро dans ень:**
- Paramètres routeо dans 
- Groupes routeо dans 
- IP Filtering
- Middleware
- Route Macros
- URL Generation
- Mise en Cache
- Загрузч et к et 
- Action Resolver
- Стат et  avec т et ка

**⭐⭐⭐ Avancé уро dans ень:**
- Rate Limiting & Auto-Ban
- Expression Language
- Плаг et ны
- PSR Support
- Sécurité

### По catégorieм  et  avec  par льзо dans ан et я

**Routage:**
- Base Routage
- Paramètres routeо dans 
- Groupes routeо dans 
- Имено dans анные routes
- URL Generation

**Sécurité:**
- Rate Limiting & Auto-Ban
- IP Filtering
- Middleware
- Sécurité

**Organisation кода:**
- Groupes routeо dans 
- Tags
- Route Macros
- Namespace

**Performance:**
- Mise en Cache
- Стат et  avec т et ка
- Опт et м et зац et я

**Ра avec ш et ряемо avec ть:**
- Плаг et ны
- Middleware
- Загрузч et к et 
- PSR Support

---

## 📚 До par лн et тель sur я документац et я

- [USER_GUIDE.md](USER_GUIDE.md) - Полное руко dans од avec т dans о  avec  пр et мерам et 
- [API_REFERENCE.md](API_REFERENCE.md) - API документац et я
- [COMPARISON.md](COMPARISON.md) - Comparaison avec les Alternatives
- [SECURITY_REPORT.md](SECURITY_REPORT.md) - Rapport de Sécurité
- [PERFORMANCE_ANALYSIS.md](PERFORMANCE_ANALYSIS.md) - Analyse de Performance
- [FAQ.md](FAQ.md) - Questions Fréquentes

---

**© 2024 CloudCastle HTTP Router**  
**Version:** 1.1.1  
**Л et ценз et я:** MIT

[⬆ Наверх](#индекс-всех-возможностей-cloudcastle-http-router)


---

## 📚 Navigation de la Documentation

[README](../../README.md) | [USER_GUIDE](USER_GUIDE.md) | [FEATURES_INDEX](FEATURES_INDEX.md) | [API_REFERENCE](API_REFERENCE.md) | [ALL_FEATURES](ALL_FEATURES.md) | [TESTS_SUMMARY](TESTS_SUMMARY.md) | [PERFORMANCE](PERFORMANCE_ANALYSIS.md) | [SECURITY](SECURITY_REPORT.md) | [COMPARISON](COMPARISON.md) | [FAQ](FAQ.md) | [DOC_SUMMARY](DOCUMENTATION_SUMMARY.md)

**Documentation détaillée:** [Features](features/) (22 fichiers) | [Tests](tests/) (7 rapports)

---

