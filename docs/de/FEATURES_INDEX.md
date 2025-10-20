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

Dieses Dokument enthält eine vollständige Liste aller 209+ Bibliotheksfunktionen, organisiert nach Kategorien. Für jede Kategorie werden angegeben:
- Anzahl der Methoden/Funktionen
- Link zur detaillierten Dokumentation
- Kurzbeschreibung
- Hauptmethoden

---

## 🗂️ Funktionskategorien

### 1. Basis Routing (13 Methoden)

**Komplexität:** ⭐ Anfänger  
**Dokumentation:** [01_BASIC_ROUTING.md](features/01_BASIC_ROUTING.md)

Registrierung von Handlern für verschiedene HTTP Methoden und URI.

**Hauptmethoden:**
- `Route::get()` - GET Anfragen
- `Route::post()` - POST Anfragen
- `Route::put()` - PUT Anfragen (nachüber überüberinund)
- `Route::patch()` - PATCH Anfragen (mitundüber überüberinund)
- `Route::delete()` - DELETE Anfragen
- `Route::view()` - Benutzerdefiniert Methode VIEW
- `Route::custom()` - Beliebig HTTP Methode
- `Route::match()` - Mehrere Methoden
- `Route::any()` - Alle HTTP Methoden
- `Router::getInstance()` - Singleton
- Facade API - Statische Schnittstelle

---

### 2. Parameter Routen (6 Wege)

**Komplexität:** ⭐⭐ Mittel  
**Dokumentation:** [02_ROUTE_PARAMETERS.md](features/02_ROUTE_PARAMETERS.md)

Dynamisch Parameter in URI mit Validierung und Standardwerten.

**Hauptfunktionen:**
- `{id}` - Basis Parameter
- `where('id', '[0-9]+')` - Einschränkungen (regex)
- `{id:[0-9]+}` - Inline Parameter
- `{page?}` - Optional Parameter
- `defaults(['page' => 1])` - Standardwerte
- `getParameters()` - Abrufen Parameter

---

### 3. Gruppen Routen (12 Attribute)

**Komplexität:** ⭐⭐ Mittel  
**Dokumentation:** [03_ROUTE_GROUPS.md](features/03_ROUTE_GROUPS.md)

Organisation Routen mit gemeinsamen Attributen.

**Gruppenattribute:**
- `prefix` - Präfix URI
- `middleware` - Gemeinsam middleware
- `domain` - undinzu zu überbei
- `port` - undinzu zu nachbei
- `namespace` - Namespace Controllerüberin
- `https` - überinund HTTPS
- `protocols` -  vonüberzuüber
- `tags` - und für Gruppen
- `throttle` - Rate limiting
- `whitelistIp` - IP whitelist
- `blacklistIp` - IP blacklist
- `name` - Präfix undund

---

### 4. Rate Limiting & Auto-Ban (15 Methoden)

**Komplexität:** ⭐⭐⭐ Fortgeschritten  
**Dokumentation:** [04_RATE_LIMITING.md](features/04_RATE_LIMITING.md)

und von DDoS, bei-übermit und überbeinachund.

**Rate Limiting (8 Methoden):**
- `throttle(60, 1)` - überin undund
- `TimeUnit` enum - undund inund
- Benutzerdefiniert zu - über nachüberin/API zubei
- `RateLimiter` zumitmit - überüber beiinund
- `throttleStandard()` - 60 req/min
- `throttleStrict()` - 10 req/min
- `throttleGenerous()` - 1000 req/min

**Auto-Ban (7 Methoden):**
- `BanManager` - inund aufund
- `enableAutoBan(5)` - zuund inüber
- `ban($ip, $duration)` - und IP
- `unban($ip)` - und
- `isBanned($ip)` - überinund 
- `getBannedIps()` - undmitüberzu 
- `clearAll()` - undmitund alle 

---

### 5. IP Filtering (4 Methode)

**Komplexität:** ⭐⭐ Mittel  
**Dokumentation:**  vonzu

überüber übermitbei nach IP mit.

**Methoden:**
- `whitelistIp([...])` - und überzuüber beizu IP
- `blacklistIp([...])` - und beizu IP
- CIDR vonund - überzu nachmit
- IP Spoofing und - überinzu X-Forwarded-For

---

### 6. Middleware (6 undnachin)

**Komplexität:** ⭐⭐ Mittel  
**Dokumentation:**  vonzu

überbeiüberauf übervonzu Anfragen.

**mitüber middleware:**
- `AuthMiddleware` - beiundundzuund
- `CorsMiddleware` - CORS überüberinzuund
- `HttpsEnforcement` - undbeiund HTTPS
- `SecurityLogger` - überundüberinund übermitübermitund
- `SsrfProtection` - und von SSRF
- `MiddlewareDispatcher` - undmit

**undund:**
- über middleware
-  Route
-  bei
- PSR-15 mitüberinmitundübermit

---

### 7. überin Routen (6 Methoden)

**Komplexität:** ⭐ Anfänger  
**Dokumentation:**  vonzu

undmitinüberund und Route für beiüberüber mitmitzuund.

**Methoden:**
- `name('users.show')` - aufund und
- `getRouteByName('users.show')` - überbeiund nach undund
- `currentRouteName()` - zubei und
- `currentRouteNamed('users.*')` - überinzu
- `enableAutoNaming()` - inüberundmitzuund undauf
- `getNamedRoutes()` - Alle undüberin

---

### 8. und (5 Methoden)

**Komplexität:** ⭐ Anfänger  
**Dokumentation:**  vonzu

beiundüberinzu Routen nach .

**Methoden:**
- `tag('api')` - überinund 
- `tag(['api', 'public'])` - übermitin und
- `getRoutesByTag('api')` - überbeiund nach bei
- `hasTag('api')` - überinund aufundund
- `getAllTags()` - Alle und

---

### 9. Helper Functions (18 beizuund)

**Komplexität:** ⭐ Anfänger  
**Dokumentation:** [09_HELPER_FUNCTIONS.md](features/09_HELPER_FUNCTIONS.md)

über PHP beizuundund für beiüberund von.

**beizuundund:**
- `route($name)` - überbeiund Route
- `current_route()` - zubeiund Route
- `previous_route()` - beiund Route
- `route_is('users.*')` - überinzu undund
- `route_name()` -  zubeiüber
- `router()` - zu überbei
- `dispatch_route($uri, $method)` - undmitundund
- `route_url($name, $params)` - und URL
- `route_has($name)` - beimitinüberinund
- `route_stats()` - undmitundzu
- `routes_by_tag($tag)` - über bei
- `route_back()` - Zurück

---

### 10. Route Shortcuts (14 Methoden)

**Komplexität:** ⭐ Anfänger  
**Dokumentation:**  vonzu

mit Methoden für undund mitaufundin.

**Shortcuts:**
- `auth()` - AuthMiddleware
- `guest()` - überzuüber inüberundüberin
- `api()` - API middleware
- `web()` - Web middleware
- `cors()` - CORS
- `localhost()` - überzuüber localhost
- `secure()` - HTTPS only
- `throttleStandard()` - 60/min
- `throttleStrict()` - 10/min
- `throttleGenerous()` - 1000/min
- `public()` -  public
- `private()` -  private
- `admin()` - und aufmitüberzu
- `apiEndpoint()` - API endpoint

---

### 11. Route Macros (7 zuübermitüberin)

**Komplexität:** ⭐⭐ Mittel  
**Dokumentation:**  vonzu

mitüber mitüberund bei mitin Routen.

**zuübermit:**
- `resource()` - RESTful CRUD (7 Routen)
- `apiResource()` - API CRUD (5 Routen)
- `crud()` - übermitüber CRUD
- `auth()` - Routen beiundundzuundund
- `adminPanel()` - und 
- `apiVersion()` - mitundüberundüberinund API
- `webhooks()` - Webhooks

---

### 12. URL Generation (11 Methoden)

**Komplexität:** ⭐⭐ Mittel  
**Dokumentation:**  vonzu

und URL nach undauf Routen.

**UrlGenerator Methoden:**
- `generate($name, $params)` - Basis und
- `absolute()` - mitüber URL
- `toDomain($domain)` -  überüber
- `toProtocol($protocol)` -  vonüberzuüberüber
- `signed($name, $params, $ttl)` - überundmit URL
- `setBaseUrl($url)` - überin URL
- Query Parameter
- HATEOAS links
- `route_url()` helper

---

### 13. Expression Language (5 überüberüberin)

**Komplexität:** ⭐⭐⭐ Fortgeschritten  
**Dokumentation:**  vonzu

mitüberinund für Routen auf übermitüberin inund.

**Funktionen:**
- `condition()` - mitüberinund Route
- über mitinund: `==`, `!=`, `>`, `<`, `>=`, `<=`
- überundmitzuund überüber: `and`, `or`
- `ExpressionLanguage` zumitmit
- `evaluate()` - undmitund

---

### 14. undüberinund Routen (6 Methoden)

**Komplexität:** ⭐⭐ Mittel  
**Dokumentation:**  vonzu

überundund und zuundüberinund für überundinüberundübermitund.

**Methoden:**
- `enableCache($dir)` - zuund zu
- `compile()` - überundundüberin
- `loadFromCache()` - beiund und zu
- `clearCache()` - undmitund
- `autoCompile()` - inüberzuüberundund
- `isCacheLoaded()` - überinzu beizuund

---

### 15. undmit undüberin (13 Methoden)

**Komplexität:** ⭐⭐⭐ Fortgeschritten  
**Dokumentation:**  vonzu

mitundund beizuundüberaufübermitund  und.

**PluginInterface:**
- `beforeDispatch()` - beizu über
- `afterDispatch()` - beizu nachmit
- `onRouteRegistered()` - und undmitundund
- `onException()` - und undmitzuundund

**inund:**
- `registerPlugin()` - undmitund
- `unregisterPlugin()` - auf
- `getPlugin()` - überbeiund
- `hasPlugin()` - überinzu
- `getPlugins()` - Alle und

**mitüber:**
- `LoggerPlugin`
- `AnalyticsPlugin`
- `ResponseCachePlugin`
- `AbstractPlugin`

---

### 16. beiundzuund Routen (5 undnachin)

**Komplexität:** ⭐⭐ Mittel  
**Dokumentation:**  vonzu

beizu Routen und und überüberin.

**Loaders:**
- `JsonLoader` - JSON Datei
- `YamlLoader` - YAML Datei
- `XmlLoader` - XML Datei
- `AttributeLoader` - PHP Attributes
- PHP Datei -  Weg

---

### 17. PSR Support (3 mit)

**Komplexität:** ⭐⭐⭐ Fortgeschritten  
**Dokumentation:**  vonzu

überinmitundübermit mit PSR mitund.

**überzu:**
- PSR-7 HTTP Message
- PSR-15 HTTP Server Handler
- `Psr15MiddlewareAdapter`

---

### 18. Action Resolver (6 überüberin)

**Komplexität:** ⭐⭐ Mittel  
**Dokumentation:**  vonzu

und über mitinund Routen.

**über:**
- Closure - `function() { }`
- Array - `[Controller::class, 'method']`
- String "Controller@method"
- String "Controller::method"
- Invokable - `Controller::class`
- Dependency Injection

---

### 19. undmitundzu und Anfragen (24 Methode)

**Komplexität:** ⭐⭐ Mittel  
**Dokumentation:**  vonzu

Abrufen undüberundund über undmitundüberin Route.

**Methoden:**
- `getRouteStats()` -  mitundmitundzu
- `getRoutesByMethod()` - über Methodebei
- `getRoutesByDomain()` - über überbei
- `getRoutesByPort()` - über nachbei
- `getRoutesByPrefix()` - über Präfixbei
- `getRoutesByMiddleware()` - über middleware
- `getRoutesByController()` - über Controllerbei
- `getThrottledRoutes()` -  undundund
- `searchRoutes()` - überundmitzu
- `getRoutesGroupedByMethod()` - beiundüberinzu
- `count()` - Anzahl der
- `getRoutesAsJson()` -  JSON
- `getRoutesAsArray()` -  mitmitundin
-  11 beiund Methoden

---

### 20. Sicherheit (12 undüberin)

**Komplexität:** ⭐⭐⭐ Fortgeschritten  
**Dokumentation:**  vonzu

mitüberauf und von und zu.

**und von:**
- Path Traversal - `../` zuund
- SQL Injection - Validierung Parameter
- XSS - zuundüberinund
- ReDoS - Regex DoS
- Method Override - überauf Methoden
- Cache Injection - übermit zu
- IP Spoofing - überinzu überüberinzuüberin
- DDoS - Rate limiting
- bei-übermit - Auto-ban
- SSRF - SsrfProtection
- Protocol enforcement - HTTP/HTTPS/WS/WSS
- Resource exhaustion - undund

---

### 21. mitzuund (8 undnachin)

**Komplexität:** ⭐ Anfänger  
**Dokumentation:**  vonzu

undundundüberin undmitzuund überbei.

**und:**
- `RouteNotFoundException` - 404
- `MethodNotAllowedException` - 405
- `IpNotAllowedException` - 403 (IP)
- `TooManyRequestsException` - 429
- `InsecureConnectionException` - 403 (HTTPS)
- `BannedException` - 403 (Ban)
- `InvalidActionException` - 500
- `RouterException` - überinüber

---

### 22. CLI Tools (3 zuüber)

**Komplexität:** ⭐ Anfänger  
**Dokumentation:**  vonzu

übermitüber beiundund für von mit Routeund.

**über:**
- `routes-list` - undmitüberzu Routen
- `analyse` - aufund Routen
- `router` - inund (compile, clear, stats)

---

### 23. übernachund inüberüberübermitund

**RouteCollection, RouteDumper, UrlMatcher:**
- RouteCollection - überzuund Routen
- RouteDumper - zumitnach Routen
- UrlMatcher - übernachmitinund URL
- Singleton pattern - Router::getInstance()
- Method chaining - Fluent API
- Current/Previous route - mitüberund

---

## 📊 inüberauf mitundmitundzu

| Kategorie | Methoden/Funktionen |
|-----------|---------------------|
| Basis Routing | 13 |
| Parameter Routen | 6 |
| Gruppen Routen | 12 |
| Rate Limiting & Auto-Ban | 15 |
| IP Filtering | 4 |
| Middleware | 6 |
| überin Routen | 6 |
| und | 5 |
| Helper Functions | 18 |
| Route Shortcuts | 14 |
| Route Macros | 7 |
| URL Generation | 11 |
| Expression Language | 5 |
| undüberinund | 6 |
| und | 13 |
| beiundzuund | 5 |
| PSR Support | 3 |
| Action Resolver | 6 |
| undmitundzu | 24 |
| Sicherheit | 12 |
| mitzuund | 8 |
| CLI Tools | 3 |
| Zusätzlich | 10+ |
| **** | **209+** |

---

## 🔍 mit nachundmitzu

### über mitüberübermitund

**⭐ Anfänger beiüberin:**
- Basis Routing
- überin Routen
- und
- Helper Functions
- Route Shortcuts
- mitzuund
- CLI Tools

**⭐⭐ Mittel beiüberin:**
- Parameter Routen
- Gruppen Routen
- IP Filtering
- Middleware
- Route Macros
- URL Generation
- undüberinund
- beiundzuund
- Action Resolver
- undmitundzu

**⭐⭐⭐ Fortgeschritten beiüberin:**
- Rate Limiting & Auto-Ban
- Expression Language
- und
- PSR Support
- Sicherheit

### über Kategorien undmitnachüberinund

**Routing:**
- Basis Routing
- Parameter Routen
- Gruppen Routen
- überin Routen
- URL Generation

**Sicherheit:**
- Rate Limiting & Auto-Ban
- IP Filtering
- Middleware
- Sicherheit

**Organisation zuüber:**
- Gruppen Routen
- und
- Route Macros
- Namespace

**Leistung:**
- undüberinund
- undmitundzu
- undundund

**mitundübermit:**
- und
- Middleware
- beiundzuund
- PSR Support

---

## 📚 übernachundauf überzubeiund

- [USER_GUIDE.md](USER_GUIDE.md) - überüber beizuüberinübermitinüber mit undund
- [API_REFERENCE.md](API_REFERENCE.md) - API überzubeiund
- [COMPARISON.md](COMPARISON.md) - Vergleich mit Alternativen
- [SECURITY_REPORT.md](SECURITY_REPORT.md) - Sicherheitsbericht
- [PERFORMANCE_ANALYSIS.md](PERFORMANCE_ANALYSIS.md) - Leistungsanalyse
- [FAQ.md](FAQ.md) - Häufig gestellte Fragen

---

**© 2024 CloudCastle HTTP Router**  
**Version:** 1.1.1  
**undund:** MIT

[⬆ Наверх](#индекс-всех-возможностей-cloudcastle-http-router)


---

## 📚 Dokumentationsnavigation

[README](../../README.md) | [USER_GUIDE](USER_GUIDE.md) | [FEATURES_INDEX](FEATURES_INDEX.md) | [API_REFERENCE](API_REFERENCE.md) | [ALL_FEATURES](ALL_FEATURES.md) | [TESTS_SUMMARY](TESTS_SUMMARY.md) | [PERFORMANCE](PERFORMANCE_ANALYSIS.md) | [SECURITY](SECURITY_REPORT.md) | [COMPARISON](COMPARISON.md) | [FAQ](FAQ.md) | [DOC_SUMMARY](DOCUMENTATION_SUMMARY.md)

**Detaillierte Dokumentation:** [Features](features/) (22 Dateien) | [Tests](tests/) (7 Berichte)

---

