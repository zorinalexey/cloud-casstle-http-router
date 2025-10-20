# Index de toutes les Fonctionnalités de CloudCastle HTTP Router

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

Ce document contient une liste complète de toutes les 209+ fonctionnalités de la bibliothèque, organisées par catégorie. Pour chaque catégorie sont indiqués:
- Nombre de méthodes/fonctionnalités
- Lien vers la documentation détaillée
- Brève description
- Méthodes principales

---

## 🗂️ Catégories de Fonctionnalités

### 1. Base Routage (13 méthodes)

**Complexité:** ⭐ Débutant  
**Documentation:** [01_BASIC_ROUTING.md](features/01_BASIC_ROUTING.md)

Enregistrement de gestionnaires pour divers HTTP méthodes et URI.

**Méthodes principales:**
- `Route::get()` - GET requête
- `Route::post()` - POST requête
- `Route::put()` - PUT requête (parsur sursurdanset)
- `Route::patch()` - PATCH requête (avecetsur sursurdanset)
- `Route::delete()` - DELETE requête
- `Route::view()` - Personnalisé méthode VIEW
- `Route::custom()` - Tout HTTP méthode
- `Route::match()` - Plusieurs méthodes
- `Route::any()` - Tous HTTP méthodes
- `Router::getInstance()` - Singleton
- Facade API - Interface statique

---

### 2. Paramètres routesurdans (6 façons)

**Complexité:** ⭐⭐ Intermédiaire  
**Documentation:** [02_ROUTE_PARAMETERS.md](features/02_ROUTE_PARAMETERS.md)

Dynamiques paramètres dans URI avec validation et valeurs par défaut.

**Fonctionnalités Principales:**
- `{id}` - Basiques paramètres
- `where('id', '[0-9]+')` - Contraintes (regex)
- `{id:[0-9]+}` - Inline paramètres
- `{page?}` - Optionnels paramètres
- `defaults(['page' => 1])` - Valeurs par défaut
- `getParameters()` - Obtenir paramètres

---

### 3. Groupes routesurdans (12 attributs)

**Complexité:** ⭐⭐ Intermédiaire  
**Documentation:** [03_ROUTE_GROUPS.md](features/03_ROUTE_GROUPS.md)

Organisation routesurdans avec attributs partagés.

**Attributs de groupe:**
- `prefix` - Préfixe URI
- `middleware` - Partagé middleware
- `domain` - etdansà à surchez
- `port` - etdansà à parchez
- `namespace` - Namespace contrôleursurdans
- `https` - surdanset HTTPS
- `protocols` -  desuràsur
- `tags` - et pour groupes
- `throttle` - Rate limiting
- `whitelistIp` - IP whitelist
- `blacklistIp` - IP blacklist
- `name` - Préfixe etet

---

### 4. Rate Limiting & Auto-Ban (15 méthodes)

**Complexité:** ⭐⭐⭐ Avancé  
**Documentation:** [04_RATE_LIMITING.md](features/04_RATE_LIMITING.md)

et de DDoS, chez-suravec et surchezparet.

**Rate Limiting (8 méthodes):**
- `throttle(60, 1)` - surdans etet
- `TimeUnit` enum - etet danset
- Personnalisé à - sur parsurdans/API àchez
- `RateLimiter` àavecavec - sursur chezdanset
- `throttleStandard()` - 60 req/min
- `throttleStrict()` - 10 req/min
- `throttleGenerous()` - 1000 req/min

**Auto-Ban (7 méthodes):**
- `BanManager` - danset suret
- `enableAutoBan(5)` - àet danssur
- `ban($ip, $duration)` - et IP
- `unban($ip)` - et
- `isBanned($ip)` - surdanset 
- `getBannedIps()` - etavecsurà 
- `clearAll()` - etavecet tous 

---

### 5. IP Filtering (4 méthode)

**Complexité:** ⭐⭐ Intermédiaire  
**Documentation:**  deà

sursur suravecchez par IP avec.

**Méthodes:**
- `whitelistIp([...])` - et suràsur chezà IP
- `blacklistIp([...])` - et chezà IP
- CIDR deet - surà paravec
- IP Spoofing et - surdansà X-Forwarded-For

---

### 6. Middleware (6 etpardans)

**Complexité:** ⭐⭐ Intermédiaire  
**Documentation:**  deà

surchezsursur surdeà requêtes.

**avecsur middleware:**
- `AuthMiddleware` - chezetetàet
- `CorsMiddleware` - CORS sursurdansàet
- `HttpsEnforcement` - etchezet HTTPS
- `SecurityLogger` - suretsurdanset suravecsuravecet
- `SsrfProtection` - et de SSRF
- `MiddlewareDispatcher` - etavec

**etet:**
- sur middleware
-  route
-  chez
- PSR-15 avecsurdansavecetsuravec

---

### 7. surdans routes (6 méthodes)

**Complexité:** ⭐ Débutant  
**Documentation:**  deà

etavecdanssuret et route pour chezsursur avecavecàet.

**Méthodes:**
- `name('users.show')` - suret et
- `getRouteByName('users.show')` - surchezet par etet
- `currentRouteName()` - àchez et
- `currentRouteNamed('users.*')` - surdansà
- `enableAutoNaming()` - danssuretavecàet etsur
- `getNamedRoutes()` - Tous etsurdans

---

### 8. et (5 méthodes)

**Complexité:** ⭐ Débutant  
**Documentation:**  deà

chezetsurdansà routesurdans par .

**Méthodes:**
- `tag('api')` - surdanset 
- `tag(['api', 'public'])` - suravecdans et
- `getRoutesByTag('api')` - surchezet par chez
- `hasTag('api')` - surdanset suretet
- `getAllTags()` - Tous et

---

### 9. Helper Functions (18 chezàet)

**Complexité:** ⭐ Débutant  
**Documentation:** [09_HELPER_FUNCTIONS.md](features/09_HELPER_FUNCTIONS.md)

sur PHP chezàetet pour chezsuret de.

**chezàetet:**
- `route($name)` - surchezet route
- `current_route()` - àchezet route
- `previous_route()` - chezet route
- `route_is('users.*')` - surdansà etet
- `route_name()` -  àchezsur
- `router()` - à surchez
- `dispatch_route($uri, $method)` - etavecetet
- `route_url($name, $params)` - et URL
- `route_has($name)` - chezavecdanssurdanset
- `route_stats()` - etavecetà
- `routes_by_tag($tag)` - sur chez
- `route_back()` - Retour

---

### 10. Route Shortcuts (14 méthodes)

**Complexité:** ⭐ Débutant  
**Documentation:**  deà

avec méthodes pour etet avecsuretdans.

**Shortcuts:**
- `auth()` - AuthMiddleware
- `guest()` - suràsur danssuretsurdans
- `api()` - API middleware
- `web()` - Web middleware
- `cors()` - CORS
- `localhost()` - suràsur localhost
- `secure()` - HTTPS only
- `throttleStandard()` - 60/min
- `throttleStrict()` - 10/min
- `throttleGenerous()` - 1000/min
- `public()` -  public
- `private()` -  private
- `admin()` - et suravecsurà
- `apiEndpoint()` - API endpoint

---

### 11. Route Macros (7 àsuravecsurdans)

**Complexité:** ⭐⭐ Intermédiaire  
**Documentation:**  deà

avecsur avecsuret chez avecdans routesurdans.

**àsuravec:**
- `resource()` - RESTful CRUD (7 routesurdans)
- `apiResource()` - API CRUD (5 routesurdans)
- `crud()` - suravecsur CRUD
- `auth()` - Routes chezetetàetet
- `adminPanel()` - et 
- `apiVersion()` - avecetsuretsurdanset API
- `webhooks()` - Webhooks

---

### 12. URL Generation (11 méthodes)

**Complexité:** ⭐⭐ Intermédiaire  
**Documentation:**  deà

et URL par etsur routesurdans.

**UrlGenerator méthodes:**
- `generate($name, $params)` - Base et
- `absolute()` - avecsur URL
- `toDomain($domain)` -  sursur
- `toProtocol($protocol)` -  desuràsursur
- `signed($name, $params, $ttl)` - suretavec URL
- `setBaseUrl($url)` - surdans URL
- Query paramètres
- HATEOAS links
- `route_url()` helper

---

### 13. Expression Language (5 sursursurdans)

**Complexité:** ⭐⭐⭐ Avancé  
**Documentation:**  deà

avecsurdanset pour routesurdans sur suravecsurdans danset.

**Fonctionnalités:**
- `condition()` - avecsurdanset route
- sur avecdanset: `==`, `!=`, `>`, `<`, `>=`, `<=`
- suretavecàet sursur: `and`, `or`
- `ExpressionLanguage` àavecavec
- `evaluate()` - etavecet

---

### 14. etsurdanset routesurdans (6 méthodes)

**Complexité:** ⭐⭐ Intermédiaire  
**Documentation:**  deà

suretet et àetsurdanset pour suretdanssuretsuravecet.

**Méthodes:**
- `enableCache($dir)` - àet à
- `compile()` - suretetsurdans
- `loadFromCache()` - chezet et à
- `clearCache()` - etavecet
- `autoCompile()` - danssuràsuretet
- `isCacheLoaded()` - surdansà chezàet

---

### 15. etavec etsurdans (13 méthodes)

**Complexité:** ⭐⭐⭐ Avancé  
**Documentation:**  deà

avecetet chezàetsursursuravecet  et.

**PluginInterface:**
- `beforeDispatch()` - chezà sur
- `afterDispatch()` - chezà paravec
- `onRouteRegistered()` - et etavecetet
- `onException()` - et etavecàetet

**danset:**
- `registerPlugin()` - etavecet
- `unregisterPlugin()` - sur
- `getPlugin()` - surchezet
- `hasPlugin()` - surdansà
- `getPlugins()` - Tous et

**avecsur:**
- `LoggerPlugin`
- `AnalyticsPlugin`
- `ResponseCachePlugin`
- `AbstractPlugin`

---

### 16. chezetàet routesurdans (5 etpardans)

**Complexité:** ⭐⭐ Intermédiaire  
**Documentation:**  deà

chezà routesurdans et et sursurdans.

**Loaders:**
- `JsonLoader` - JSON fichier
- `YamlLoader` - YAML fichier
- `XmlLoader` - XML fichier
- `AttributeLoader` - PHP Attributes
- PHP fichier -  façon

---

### 17. PSR Support (3 avec)

**Complexité:** ⭐⭐⭐ Avancé  
**Documentation:**  deà

surdansavecetsuravec avec PSR avecet.

**surà:**
- PSR-7 HTTP Message
- PSR-15 HTTP Server Handler
- `Psr15MiddlewareAdapter`

---

### 18. Action Resolver (6 sursurdans)

**Complexité:** ⭐⭐ Intermédiaire  
**Documentation:**  deà

et sur avecdanset routesurdans.

**sur:**
- Closure - `function() { }`
- Array - `[Controller::class, 'method']`
- String "Controller@method"
- String "Controller::method"
- Invokable - `Controller::class`
- Dependency Injection

---

### 19. etavecetà et requête (24 méthode)

**Complexité:** ⭐⭐ Intermédiaire  
**Documentation:**  deà

Obtenir etsuretet sur etavecetsurdans route.

**Méthodes:**
- `getRouteStats()` -  avecetavecetà
- `getRoutesByMethod()` - sur méthodechez
- `getRoutesByDomain()` - sur surchez
- `getRoutesByPort()` - sur parchez
- `getRoutesByPrefix()` - sur préfixechez
- `getRoutesByMiddleware()` - sur middleware
- `getRoutesByController()` - sur contrôleurchez
- `getThrottledRoutes()` -  etetet
- `searchRoutes()` - suretavecà
- `getRoutesGroupedByMethod()` - chezetsurdansà
- `count()` - Nombre de
- `getRoutesAsJson()` -  JSON
- `getRoutesAsArray()` -  avecavecetdans
-  11 chezet méthodes

---

### 20. Sécurité (12 etsurdans)

**Complexité:** ⭐⭐⭐ Avancé  
**Documentation:**  deà

avecsursur et de et à.

**et de:**
- Path Traversal - `../` àet
- SQL Injection - Validation paramètres
- XSS - àetsurdanset
- ReDoS - Regex DoS
- Method Override - sursur méthodes
- Cache Injection - suravec à
- IP Spoofing - surdansà sursurdansàsurdans
- DDoS - Rate limiting
- chez-suravec - Auto-ban
- SSRF - SsrfProtection
- Protocol enforcement - HTTP/HTTPS/WS/WSS
- Resource exhaustion - etet

---

### 21. avecàet (8 etpardans)

**Complexité:** ⭐ Débutant  
**Documentation:**  deà

etetetsurdans etavecàet surchez.

**et:**
- `RouteNotFoundException` - 404
- `MethodNotAllowedException` - 405
- `IpNotAllowedException` - 403 (IP)
- `TooManyRequestsException` - 429
- `InsecureConnectionException` - 403 (HTTPS)
- `BannedException` - 403 (Ban)
- `InvalidActionException` - 500
- `RouterException` - surdanssur

---

### 22. CLI Tools (3 àsur)

**Complexité:** ⭐ Débutant  
**Documentation:**  deà

suravecsur chezetet pour de avec routeet.

**sur:**
- `routes-list` - etavecsurà routesurdans
- `analyse` - suret routesurdans
- `router` - danset (compile, clear, stats)

---

### 23. surparet danssursursuravecet

**RouteCollection, RouteDumper, UrlMatcher:**
- RouteCollection - suràet routesurdans
- RouteDumper - àavecpar routesurdans
- UrlMatcher - surparavecdanset URL
- Singleton pattern - Router::getInstance()
- Method chaining - Fluent API
- Current/Previous route - avecsuret

---

## 📊 danssursur avecetavecetà

| Catégorie | Méthodesurdans/Fonctionnalités |
|-----------|---------------------|
| Base Routage | 13 |
| Paramètres routesurdans | 6 |
| Groupes routesurdans | 12 |
| Rate Limiting & Auto-Ban | 15 |
| IP Filtering | 4 |
| Middleware | 6 |
| surdans routes | 6 |
| et | 5 |
| Helper Functions | 18 |
| Route Shortcuts | 14 |
| Route Macros | 7 |
| URL Generation | 11 |
| Expression Language | 5 |
| etsurdanset | 6 |
| et | 13 |
| chezetàet | 5 |
| PSR Support | 3 |
| Action Resolver | 6 |
| etavecetà | 24 |
| Sécurité | 12 |
| avecàet | 8 |
| CLI Tools | 3 |
| Supplémentaire | 10+ |
| **** | **209+** |

---

## 🔍 avec paretavecà

### sur avecsursuravecet

**⭐ Débutant chezsurdans:**
- Base Routage
- surdans routes
- et
- Helper Functions
- Route Shortcuts
- avecàet
- CLI Tools

**⭐⭐ Intermédiaire chezsurdans:**
- Paramètres routesurdans
- Groupes routesurdans
- IP Filtering
- Middleware
- Route Macros
- URL Generation
- etsurdanset
- chezetàet
- Action Resolver
- etavecetà

**⭐⭐⭐ Avancé chezsurdans:**
- Rate Limiting & Auto-Ban
- Expression Language
- et
- PSR Support
- Sécurité

### sur catégorie etavecparsurdanset

**Routage:**
- Base Routage
- Paramètres routesurdans
- Groupes routesurdans
- surdans routes
- URL Generation

**Sécurité:**
- Rate Limiting & Auto-Ban
- IP Filtering
- Middleware
- Sécurité

**Organisation àsur:**
- Groupes routesurdans
- et
- Route Macros
- Namespace

**Performance:**
- etsurdanset
- etavecetà
- etetet

**avecetsuravec:**
- et
- Middleware
- chezetàet
- PSR Support

---

## 📚 surparetsur suràchezet

- [USER_GUIDE.md](USER_GUIDE.md) - sursur chezàsurdanssuravecdanssur avec etet
- [API_REFERENCE.md](API_REFERENCE.md) - API suràchezet
- [COMPARISON.md](COMPARISON.md) - Comparaison avec les Alternatives
- [SECURITY_REPORT.md](SECURITY_REPORT.md) - Rapport de Sécurité
- [PERFORMANCE_ANALYSIS.md](PERFORMANCE_ANALYSIS.md) - Analyse de Performance
- [FAQ.md](FAQ.md) - Questions Fréquentes

---

**© 2024 CloudCastle HTTP Router**  
**Version:** 1.1.1  
**etet:** MIT

[⬆ Наверх](#индекс-всех-возможностей-cloudcastle-http-router)


---

## 📚 Navigation de la Documentation

[README](../../README.md) | [USER_GUIDE](USER_GUIDE.md) | [FEATURES_INDEX](FEATURES_INDEX.md) | [API_REFERENCE](API_REFERENCE.md) | [ALL_FEATURES](ALL_FEATURES.md) | [TESTS_SUMMARY](TESTS_SUMMARY.md) | [PERFORMANCE](PERFORMANCE_ANALYSIS.md) | [SECURITY](SECURITY_REPORT.md) | [COMPARISON](COMPARISON.md) | [FAQ](FAQ.md) | [DOC_SUMMARY](DOCUMENTATION_SUMMARY.md)

**Documentation détaillée:** [Features](features/) (22 fichiers) | [Tests](tests/) (7 rapports)

---

