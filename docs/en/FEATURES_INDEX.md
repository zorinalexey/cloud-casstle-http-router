# Index of All CloudCastle HTTP Router Features

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

This document contains a complete list of all 209+ library features, organized by category. For each category the following is provided:
- Number of methods/features
- Link to detailed documentation
- Brief description
- Main methods

---

## 🗂️ Feature Categories

### 1. Basic Routing (13 methods)

**Complexity:** ⭐ Beginner  
**Documentation:** [01_BASIC_ROUTING.md](features/01_BASIC_ROUTING.md)

Registering handlers for various HTTP methods and URI.

**Main methods:**
- `Route::get()` - GET request
- `Route::post()` - POST request
- `Route::put()` - PUT request (byabout aboutaboutinand)
- `Route::patch()` - PATCH request (withandabout aboutaboutinand)
- `Route::delete()` - DELETE request
- `Route::view()` - Custom method VIEW
- `Route::custom()` - Any HTTP method
- `Route::match()` - Multiple methods
- `Route::any()` - All HTTP methods
- `Router::getInstance()` - Singleton
- Facade API - Static interface

---

### 2. Parameterss routeaboutin (6 ways)

**Complexity:** ⭐⭐ Intermediate  
**Documentation:** [02_ROUTE_PARAMETERS.md](features/02_ROUTE_PARAMETERS.md)

Dynamic parameters in URI with validation and default values.

**Core Features:**
- `{id}` - Basic parameters
- `where('id', '[0-9]+')` - Constraints (regex)
- `{id:[0-9]+}` - Inline parameters
- `{page?}` - Optional parameters
- `defaults(['page' => 1])` - Default values
- `getParameters()` - Getting parameters

---

### 3. Groups routeaboutin (12 attributes)

**Complexity:** ⭐⭐ Intermediate  
**Documentation:** [03_ROUTE_GROUPS.md](features/03_ROUTE_GROUPS.md)

Organization routeaboutin with shared attributes.

**Group attributes:**
- `prefix` - Prefix URI
- `middleware` - Shared middleware
- `domain` - andinto to aboutat
- `port` - andinto to byat
- `namespace` - Namespace controlleraboutin
- `https` - aboutinand HTTPS
- `protocols` -  fromabouttoabout
- `tags` - and for groups
- `throttle` - Rate limiting
- `whitelistIp` - IP whitelist
- `blacklistIp` - IP blacklist
- `name` - Prefix andand

---

### 4. Rate Limiting & Auto-Ban (15 methods)

**Complexity:** ⭐⭐⭐ Advanced  
**Documentation:** [04_RATE_LIMITING.md](features/04_RATE_LIMITING.md)

and from DDoS, at-aboutwith and aboutatbyand.

**Rate Limiting (8 methods):**
- `throttle(60, 1)` - aboutin andand
- `TimeUnit` enum - andand inand
- Custom to - about byaboutin/API toat
- `RateLimiter` towithwith - aboutabout atinand
- `throttleStandard()` - 60 req/min
- `throttleStrict()` - 10 req/min
- `throttleGenerous()` - 1000 req/min

**Auto-Ban (7 methods):**
- `BanManager` - inand toand
- `enableAutoBan(5)` - toand inabout
- `ban($ip, $duration)` - and IP
- `unban($ip)` - and
- `isBanned($ip)` - aboutinand 
- `getBannedIps()` - andwithaboutto 
- `clearAll()` - andwithand all 

---

### 5. IP Filtering (4 method)

**Complexity:** ⭐⭐ Intermediate  
**Documentation:**  fromto

aboutabout aboutwithat by IP with.

**Methods:**
- `whitelistIp([...])` - and abouttoabout atto IP
- `blacklistIp([...])` - and atto IP
- CIDR fromand - aboutto bywith
- IP Spoofing and - aboutinto X-Forwarded-For

---

### 6. Middleware (6 andbyin)

**Complexity:** ⭐⭐ Intermediate  
**Documentation:**  fromto

aboutataboutto aboutfromto requests.

**withabout middleware:**
- `AuthMiddleware` - atandandtoand
- `CorsMiddleware` - CORS aboutaboutintoand
- `HttpsEnforcement` - andatand HTTPS
- `SecurityLogger` - aboutandaboutinand aboutwithaboutwithand
- `SsrfProtection` - and from SSRF
- `MiddlewareDispatcher` - andwith

**andand:**
- about middleware
-  route
-  at
- PSR-15 withaboutinwithandaboutwith

---

### 7. aboutin routes (6 methods)

**Complexity:** ⭐ Beginner  
**Documentation:**  fromto

andwithinaboutand and route for ataboutabout withwithtoand.

**Methods:**
- `name('users.show')` - toand and
- `getRouteByName('users.show')` - aboutatand by andand
- `currentRouteName()` - toat and
- `currentRouteNamed('users.*')` - aboutinto
- `enableAutoNaming()` - inaboutandwithtoand andto
- `getNamedRoutes()` - All andaboutin

---

### 8. and (5 methods)

**Complexity:** ⭐ Beginner  
**Documentation:**  fromto

atandaboutinto routeaboutin by .

**Methods:**
- `tag('api')` - aboutinand 
- `tag(['api', 'public'])` - aboutwithin and
- `getRoutesByTag('api')` - aboutatand by at
- `hasTag('api')` - aboutinand toandand
- `getAllTags()` - All and

---

### 9. Helper Functions (18 attoand)

**Complexity:** ⭐ Beginner  
**Documentation:** [09_HELPER_FUNCTIONS.md](features/09_HELPER_FUNCTIONS.md)

about PHP attoandand for ataboutand from.

**attoandand:**
- `route($name)` - aboutatand route
- `current_route()` - toatand route
- `previous_route()` - atand route
- `route_is('users.*')` - aboutinto andand
- `route_name()` -  toatabout
- `router()` - to aboutat
- `dispatch_route($uri, $method)` - andwithandand
- `route_url($name, $params)` - and URL
- `route_has($name)` - atwithinaboutinand
- `route_stats()` - andwithandto
- `routes_by_tag($tag)` - about at
- `route_back()` - Back

---

### 10. Route Shortcuts (14 methods)

**Complexity:** ⭐ Beginner  
**Documentation:**  fromto

with methods for andand withtoandin.

**Shortcuts:**
- `auth()` - AuthMiddleware
- `guest()` - abouttoabout inaboutandaboutin
- `api()` - API middleware
- `web()` - Web middleware
- `cors()` - CORS
- `localhost()` - abouttoabout localhost
- `secure()` - HTTPS only
- `throttleStandard()` - 60/min
- `throttleStrict()` - 10/min
- `throttleGenerous()` - 1000/min
- `public()` -  public
- `private()` -  private
- `admin()` - and towithaboutto
- `apiEndpoint()` - API endpoint

---

### 11. Route Macros (7 toaboutwithaboutin)

**Complexity:** ⭐⭐ Intermediate  
**Documentation:**  fromto

withabout withaboutand at within routeaboutin.

**toaboutwith:**
- `resource()` - RESTful CRUD (7 routeaboutin)
- `apiResource()` - API CRUD (5 routeaboutin)
- `crud()` - aboutwithabout CRUD
- `auth()` - Routes atandandtoandand
- `adminPanel()` - and 
- `apiVersion()` - withandaboutandaboutinand API
- `webhooks()` - Webhooks

---

### 12. URL Generation (11 methods)

**Complexity:** ⭐⭐ Intermediate  
**Documentation:**  fromto

and URL by andto routeaboutin.

**UrlGenerator methods:**
- `generate($name, $params)` - Basic and
- `absolute()` - withabout URL
- `toDomain($domain)` -  aboutabout
- `toProtocol($protocol)` -  fromabouttoaboutabout
- `signed($name, $params, $ttl)` - aboutandwith URL
- `setBaseUrl($url)` - aboutin URL
- Query parameters
- HATEOAS links
- `route_url()` helper

---

### 13. Expression Language (5 aboutaboutaboutin)

**Complexity:** ⭐⭐⭐ Advanced  
**Documentation:**  fromto

withaboutinand for routeaboutin to aboutwithaboutin inand.

**Features:**
- `condition()` - withaboutinand route
- about withinand: `==`, `!=`, `>`, `<`, `>=`, `<=`
- aboutandwithtoand aboutabout: `and`, `or`
- `ExpressionLanguage` towithwith
- `evaluate()` - andwithand

---

### 14. andaboutinand routeaboutin (6 methods)

**Complexity:** ⭐⭐ Intermediate  
**Documentation:**  fromto

aboutandand and toandaboutinand for aboutandinaboutandaboutwithand.

**Methods:**
- `enableCache($dir)` - toand to
- `compile()` - aboutandandaboutin
- `loadFromCache()` - atand and to
- `clearCache()` - andwithand
- `autoCompile()` - inabouttoaboutandand
- `isCacheLoaded()` - aboutinto attoand

---

### 15. andwith andaboutin (13 methods)

**Complexity:** ⭐⭐⭐ Advanced  
**Documentation:**  fromto

withandand attoandabouttoaboutwithand  and.

**PluginInterface:**
- `beforeDispatch()` - atto about
- `afterDispatch()` - atto bywith
- `onRouteRegistered()` - and andwithandand
- `onException()` - and andwithtoandand

**inand:**
- `registerPlugin()` - andwithand
- `unregisterPlugin()` - to
- `getPlugin()` - aboutatand
- `hasPlugin()` - aboutinto
- `getPlugins()` - All and

**withabout:**
- `LoggerPlugin`
- `AnalyticsPlugin`
- `ResponseCachePlugin`
- `AbstractPlugin`

---

### 16. atandtoand routeaboutin (5 andbyin)

**Complexity:** ⭐⭐ Intermediate  
**Documentation:**  fromto

atto routeaboutin and and aboutaboutin.

**Loaders:**
- `JsonLoader` - JSON file
- `YamlLoader` - YAML file
- `XmlLoader` - XML file
- `AttributeLoader` - PHP Attributes
- PHP file -  way

---

### 17. PSR Support (3 with)

**Complexity:** ⭐⭐⭐ Advanced  
**Documentation:**  fromto

aboutinwithandaboutwith with PSR withand.

**aboutto:**
- PSR-7 HTTP Message
- PSR-15 HTTP Server Handler
- `Psr15MiddlewareAdapter`

---

### 18. Action Resolver (6 aboutaboutin)

**Complexity:** ⭐⭐ Intermediate  
**Documentation:**  fromto

and about withinand routeaboutin.

**about:**
- Closure - `function() { }`
- Array - `[Controller::class, 'method']`
- String "Controller@method"
- String "Controller::method"
- Invokable - `Controller::class`
- Dependency Injection

---

### 19. andwithandto and request (24 method)

**Complexity:** ⭐⭐ Intermediate  
**Documentation:**  fromto

Getting andaboutandand about andwithandaboutin route.

**Methods:**
- `getRouteStats()` -  withandwithandto
- `getRoutesByMethod()` - about methodat
- `getRoutesByDomain()` - about aboutat
- `getRoutesByPort()` - about byat
- `getRoutesByPrefix()` - about prefixat
- `getRoutesByMiddleware()` - about middleware
- `getRoutesByController()` - about controllerat
- `getThrottledRoutes()` -  andandand
- `searchRoutes()` - aboutandwithto
- `getRoutesGroupedByMethod()` - atandaboutinto
- `count()` - Number of
- `getRoutesAsJson()` -  JSON
- `getRoutesAsArray()` -  withwithandin
-  11 atand methods

---

### 20. Security (12 andaboutin)

**Complexity:** ⭐⭐⭐ Advanced  
**Documentation:**  fromto

withaboutto and from and to.

**and from:**
- Path Traversal - `../` toand
- SQL Injection - Validation parameters
- XSS - toandaboutinand
- ReDoS - Regex DoS
- Method Override - aboutto methods
- Cache Injection - aboutwith to
- IP Spoofing - aboutinto aboutaboutintoaboutin
- DDoS - Rate limiting
- at-aboutwith - Auto-ban
- SSRF - SsrfProtection
- Protocol enforcement - HTTP/HTTPS/WS/WSS
- Resource exhaustion - andand

---

### 21. withtoand (8 andbyin)

**Complexity:** ⭐ Beginner  
**Documentation:**  fromto

andandandaboutin andwithtoand aboutat.

**and:**
- `RouteNotFoundException` - 404
- `MethodNotAllowedException` - 405
- `IpNotAllowedException` - 403 (IP)
- `TooManyRequestsException` - 429
- `InsecureConnectionException` - 403 (HTTPS)
- `BannedException` - 403 (Ban)
- `InvalidActionException` - 500
- `RouterException` - aboutinabout

---

### 22. CLI Tools (3 toabout)

**Complexity:** ⭐ Beginner  
**Documentation:**  fromto

aboutwithabout atandand for from with routeand.

**about:**
- `routes-list` - andwithaboutto routeaboutin
- `analyse` - toand routeaboutin
- `router` - inand (compile, clear, stats)

---

### 23. aboutbyand inaboutaboutaboutwithand

**RouteCollection, RouteDumper, UrlMatcher:**
- RouteCollection - abouttoand routeaboutin
- RouteDumper - towithby routeaboutin
- UrlMatcher - aboutbywithinand URL
- Singleton pattern - Router::getInstance()
- Method chaining - Fluent API
- Current/Previous route - withaboutand

---

## 📊 inaboutto withandwithandto

| Category | Methodaboutin/Features |
|-----------|---------------------|
| Basic Routing | 13 |
| Parameterss routeaboutin | 6 |
| Groups routeaboutin | 12 |
| Rate Limiting & Auto-Ban | 15 |
| IP Filtering | 4 |
| Middleware | 6 |
| aboutin routes | 6 |
| and | 5 |
| Helper Functions | 18 |
| Route Shortcuts | 14 |
| Route Macros | 7 |
| URL Generation | 11 |
| Expression Language | 5 |
| andaboutinand | 6 |
| and | 13 |
| atandtoand | 5 |
| PSR Support | 3 |
| Action Resolver | 6 |
| andwithandto | 24 |
| Security | 12 |
| withtoand | 8 |
| CLI Tools | 3 |
| Additional | 10+ |
| **** | **209+** |

---

## 🔍 with byandwithto

### about withaboutaboutwithand

**⭐ Beginner ataboutin:**
- Basic Routing
- aboutin routes
- and
- Helper Functions
- Route Shortcuts
- withtoand
- CLI Tools

**⭐⭐ Intermediate ataboutin:**
- Parameterss routeaboutin
- Groups routeaboutin
- IP Filtering
- Middleware
- Route Macros
- URL Generation
- andaboutinand
- atandtoand
- Action Resolver
- andwithandto

**⭐⭐⭐ Advanced ataboutin:**
- Rate Limiting & Auto-Ban
- Expression Language
- and
- PSR Support
- Security

### about category andwithbyaboutinand

**Routing:**
- Basic Routing
- Parameterss routeaboutin
- Groups routeaboutin
- aboutin routes
- URL Generation

**Security:**
- Rate Limiting & Auto-Ban
- IP Filtering
- Middleware
- Security

**Organization toabout:**
- Groups routeaboutin
- and
- Route Macros
- Namespace

**Performance:**
- andaboutinand
- andwithandto
- andandand

**withandaboutwith:**
- and
- Middleware
- atandtoand
- PSR Support

---

## 📚 aboutbyandto abouttoatand

- [USER_GUIDE.md](USER_GUIDE.md) - aboutabout attoaboutinaboutwithinabout with andand
- [API_REFERENCE.md](API_REFERENCE.md) - API abouttoatand
- [COMPARISON.md](COMPARISON.md) - Comparison with Alternatives
- [SECURITY_REPORT.md](SECURITY_REPORT.md) - Security Report
- [PERFORMANCE_ANALYSIS.md](PERFORMANCE_ANALYSIS.md) - Performance Analysis
- [FAQ.md](FAQ.md) - Frequently Asked Questions

---

**© 2024 CloudCastle HTTP Router**  
**Version:** 1.1.1  
**andand:** MIT

[⬆ Наверх](#индекс-всех-возможностей-cloudcastle-http-router)


---

## 📚 Documentation Navigation

[README](../../README.md) | [USER_GUIDE](USER_GUIDE.md) | [FEATURES_INDEX](FEATURES_INDEX.md) | [API_REFERENCE](API_REFERENCE.md) | [ALL_FEATURES](ALL_FEATURES.md) | [TESTS_SUMMARY](TESTS_SUMMARY.md) | [PERFORMANCE](PERFORMANCE_ANALYSIS.md) | [SECURITY](SECURITY_REPORT.md) | [COMPARISON](COMPARISON.md) | [FAQ](FAQ.md) | [DOC_SUMMARY](DOCUMENTATION_SUMMARY.md)

**Detailed documentation:** [Features](features/) (22 files) | [Tests](tests/) (7 reports)

---

