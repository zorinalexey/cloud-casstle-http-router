# CloudCastle HTTP Router 所有功能索引

[English](../en/FEATURES_INDEX.md) | [Русский](../ru/FEATURES_INDEX.md) | [Deutsch](../de/FEATURES_INDEX.md) | [Français](../fr/FEATURES_INDEX.md) | **中文**

---







---

## 📚 文档导航

[README](../../README.md) | [USER_GUIDE](USER_GUIDE.md) | [FEATURES_INDEX](FEATURES_INDEX.md) | [API_REFERENCE](API_REFERENCE.md) | [ALL_FEATURES](ALL_FEATURES.md) | [TESTS_SUMMARY](TESTS_SUMMARY.md) | [PERFORMANCE](PERFORMANCE_ANALYSIS.md) | [SECURITY](SECURITY_REPORT.md) | [COMPARISON](COMPARISON.md) | [FAQ](FAQ.md) | [DOC_SUMMARY](DOCUMENTATION_SUMMARY.md)

**详细文档：** [Features](features/) (22 文件) | [Tests](tests/) (7 报告)

---


**版本：** 1.1.1  
**总功能数：** 209+  
**类别：** 23

---

## 📖 如何使用此索引

本文档包含所有 209+ 库功能的完整列表，按 . 对于每个 类别指定：
- 数量 方法/功能
- 详细文档链接
- 简要说明
- 主要方法

---

## 🗂️ 功能类别

### 1. 基础 路由 (13 方法)

**复杂度：** ⭐ 初级  
**文档：** [01_BASIC_ROUTING.md](features/01_BASIC_ROUTING.md)

为各种注册处理程序 HTTP 方法  URI.

**主要方法:**
- `Route::get()` - GET 请求
- `Route::post()` - POST 请求
- `Route::put()` - PUT 请求 ( )
- `Route::patch()` - PATCH 请求 ( )
- `Route::delete()` - DELETE 请求
- `Route::view()` - 自定义 方法 VIEW
- `Route::custom()` - 任何 HTTP 方法
- `Route::match()` - 多个 方法
- `Route::any()` - 所有 HTTP 方法
- `Router::getInstance()` - Singleton
- Facade API - 静态接口

---

### 2. 参数 路由 (6 方式)

**复杂度：** ⭐⭐ 中级  
**文档：** [02_ROUTE_PARAMETERS.md](features/02_ROUTE_PARAMETERS.md)

动态 参数  URI 带验证和 默认值.

**核心功能:**
- `{id}` - 基本 参数
- `where('id', '[0-9]+')` - 约束 (regex)
- `{id:[0-9]+}` - Inline 参数
- `{page?}` - 可选 参数
- `defaults(['page' => 1])` - 默认值
- `getParameters()` - 获取 参数

---

### 3. 组 路由 (12 属性)

**复杂度：** ⭐⭐ 中级  
**文档：** [03_ROUTE_GROUPS.md](features/03_ROUTE_GROUPS.md)

组织 路由 具有共享属性.

**组属性：**
- `prefix` - 前缀 URI
- `middleware` - 共享 middleware
- `domain` -   
- `port` -   
- `namespace` - Namespace 控制器
- `https` -  HTTPS
- `protocols` -  
- `tags` -   组
- `throttle` - Rate limiting
- `whitelistIp` - IP whitelist
- `blacklistIp` - IP blacklist
- `name` - 前缀 

---

### 4. Rate Limiting & Auto-Ban (15 方法)

**复杂度：** ⭐⭐⭐ 高级  
**文档：** [04_RATE_LIMITING.md](features/04_RATE_LIMITING.md)

  DDoS, -  .

**Rate Limiting (8 方法):**
- `throttle(60, 1)` -  
- `TimeUnit` enum -  
- 自定义  -  /API 
- `RateLimiter`  -  
- `throttleStandard()` - 60 req/min
- `throttleStrict()` - 10 req/min
- `throttleGenerous()` - 1000 req/min

**Auto-Ban (7 方法):**
- `BanManager` -  
- `enableAutoBan(5)` -  
- `ban($ip, $duration)` -  IP
- `unban($ip)` - 
- `isBanned($ip)` -  
- `getBannedIps()` -  
- `clearAll()` -  所有 

---

### 5. IP Filtering (4 方法)

**复杂度：** ⭐⭐ 中级  
**文档：**  

   IP .

**方法:**
- `whitelistIp([...])` -    IP
- `blacklistIp([...])` -   IP
- CIDR  -  
- IP Spoofing  -  X-Forwarded-For

---

### 6. Middleware (6 )

**复杂度：** ⭐⭐ 中级  
**文档：**  

  请求.

** middleware:**
- `AuthMiddleware` - 
- `CorsMiddleware` - CORS 
- `HttpsEnforcement` -  HTTPS
- `SecurityLogger` -  
- `SsrfProtection` -   SSRF
- `MiddlewareDispatcher` - 

**:**
-  middleware
-  路由
-  
- PSR-15 

---

### 7.  路由 (6 方法)

**复杂度：** ⭐ 初级  
**文档：**  

  路由   .

**方法:**
- `name('users.show')` -  
- `getRouteByName('users.show')` -   
- `currentRouteName()` -  
- `currentRouteNamed('users.*')` - 
- `enableAutoNaming()` -  
- `getNamedRoutes()` - 所有 

---

### 8.  (5 方法)

**复杂度：** ⭐ 初级  
**文档：**  

 路由  .

**方法:**
- `tag('api')` -  
- `tag(['api', 'public'])` -  
- `getRoutesByTag('api')` -   
- `hasTag('api')` -  
- `getAllTags()` - 所有 

---

### 9. Helper Functions (18 )

**复杂度：** ⭐ 初级  
**文档：** [09_HELPER_FUNCTIONS.md](features/09_HELPER_FUNCTIONS.md)

 PHP    .

**:**
- `route($name)` -  路由
- `current_route()` -  路由
- `previous_route()` -  路由
- `route_is('users.*')` -  
- `route_name()` -  
- `router()` -  
- `dispatch_route($uri, $method)` - 
- `route_url($name, $params)` -  URL
- `route_has($name)` - 
- `route_stats()` - 
- `routes_by_tag($tag)` -  
- `route_back()` - 

---

### 10. Route Shortcuts (14 方法)

**复杂度：** ⭐ 初级  
**文档：**  

 方法   .

**Shortcuts:**
- `auth()` - AuthMiddleware
- `guest()` -  
- `api()` - API middleware
- `web()` - Web middleware
- `cors()` - CORS
- `localhost()` -  localhost
- `secure()` - HTTPS only
- `throttleStandard()` - 60/min
- `throttleStrict()` - 10/min
- `throttleGenerous()` - 1000/min
- `public()` -  public
- `private()` -  private
- `admin()` -  
- `apiEndpoint()` - API endpoint

---

### 11. Route Macros (7 )

**复杂度：** ⭐⭐ 中级  
**文档：**  

    路由.

**:**
- `resource()` - RESTful CRUD (7 路由)
- `apiResource()` - API CRUD (5 路由)
- `crud()` -  CRUD
- `auth()` - 路由 
- `adminPanel()` -  
- `apiVersion()` -  API
- `webhooks()` - Webhooks

---

### 12. URL Generation (11 方法)

**复杂度：** ⭐⭐ 中级  
**文档：**  

 URL   路由.

**UrlGenerator 方法:**
- `generate($name, $params)` - 基础 
- `absolute()` -  URL
- `toDomain($domain)` -  
- `toProtocol($protocol)` -  
- `signed($name, $params, $ttl)` -  URL
- `setBaseUrl($url)` -  URL
- Query 参数
- HATEOAS links
- `route_url()` helper

---

### 13. Expression Language (5 )

**复杂度：** ⭐⭐⭐ 高级  
**文档：**  

  路由   .

**功能:**
- `condition()` -  路由
-  : `==`, `!=`, `>`, `<`, `>=`, `<=`
-  : `and`, `or`
- `ExpressionLanguage` 
- `evaluate()` - 

---

### 14.  路由 (6 方法)

**复杂度：** ⭐⭐ 中级  
**文档：**  

    .

**方法:**
- `enableCache($dir)` -  
- `compile()` - 
- `loadFromCache()` -   
- `clearCache()` - 
- `autoCompile()` - 
- `isCacheLoaded()` -  

---

### 15.   (13 方法)

**复杂度：** ⭐⭐⭐ 高级  
**文档：**  

   .

**PluginInterface:**
- `beforeDispatch()` -  
- `afterDispatch()` -  
- `onRouteRegistered()` -  
- `onException()` -  

**:**
- `registerPlugin()` - 
- `unregisterPlugin()` - 
- `getPlugin()` - 
- `hasPlugin()` - 
- `getPlugins()` - 所有 

**:**
- `LoggerPlugin`
- `AnalyticsPlugin`
- `ResponseCachePlugin`
- `AbstractPlugin`

---

### 16.  路由 (5 )

**复杂度：** ⭐⭐ 中级  
**文档：**  

 路由   .

**Loaders:**
- `JsonLoader` - JSON 文件
- `YamlLoader` - YAML 文件
- `XmlLoader` - XML 文件
- `AttributeLoader` - PHP Attributes
- PHP 文件 -  方式

---

### 17. PSR Support (3 )

**复杂度：** ⭐⭐⭐ 高级  
**文档：**  

  PSR .

**:**
- PSR-7 HTTP Message
- PSR-15 HTTP Server Handler
- `Psr15MiddlewareAdapter`

---

### 18. Action Resolver (6 )

**复杂度：** ⭐⭐ 中级  
**文档：**  

   路由.

**:**
- Closure - `function() { }`
- Array - `[Controller::class, 'method']`
- String "Controller@method"
- String "Controller::method"
- Invokable - `Controller::class`
- Dependency Injection

---

### 19.   请求 (24 方法)

**复杂度：** ⭐⭐ 中级  
**文档：**  

获取    路由.

**方法:**
- `getRouteStats()` -  
- `getRoutesByMethod()` -  方法
- `getRoutesByDomain()` -  
- `getRoutesByPort()` -  
- `getRoutesByPrefix()` -  前缀
- `getRoutesByMiddleware()` -  middleware
- `getRoutesByController()` -  控制器
- `getThrottledRoutes()` -  
- `searchRoutes()` - 
- `getRoutesGroupedByMethod()` - 
- `count()` - 数量
- `getRoutesAsJson()` -  JSON
- `getRoutesAsArray()` -  
-  11  方法

---

### 20. 安全性 (12 )

**复杂度：** ⭐⭐⭐ 高级  
**文档：**  

    .

** :**
- Path Traversal - `../` 
- SQL Injection - 验证 参数
- XSS - 
- ReDoS - Regex DoS
- Method Override -  方法
- Cache Injection -  
- IP Spoofing -  
- DDoS - Rate limiting
- - - Auto-ban
- SSRF - SsrfProtection
- Protocol enforcement - HTTP/HTTPS/WS/WSS
- Resource exhaustion - 

---

### 21.  (8 )

**复杂度：** ⭐ 初级  
**文档：**  

  .

**:**
- `RouteNotFoundException` - 404
- `MethodNotAllowedException` - 405
- `IpNotAllowedException` - 403 (IP)
- `TooManyRequestsException` - 429
- `InsecureConnectionException` - 403 (HTTPS)
- `BannedException` - 403 (Ban)
- `InvalidActionException` - 500
- `RouterException` - 

---

### 22. CLI Tools (3 )

**复杂度：** ⭐ 初级  
**文档：**  

     路由.

**:**
- `routes-list` -  路由
- `analyse` -  路由
- `router` -  (compile, clear, stats)

---

### 23.  

**RouteCollection, RouteDumper, UrlMatcher:**
- RouteCollection -  路由
- RouteDumper -  路由
- UrlMatcher -  URL
- Singleton pattern - Router::getInstance()
- Method chaining - Fluent API
- Current/Previous route - 

---

## 📊  

| 类别 | 方法/功能 |
|-----------|---------------------|
| 基础 路由 | 13 |
| 参数 路由 | 6 |
| 组 路由 | 12 |
| Rate Limiting & Auto-Ban | 15 |
| IP Filtering | 4 |
| Middleware | 6 |
|  路由 | 6 |
|  | 5 |
| Helper Functions | 18 |
| Route Shortcuts | 14 |
| Route Macros | 7 |
| URL Generation | 11 |
| Expression Language | 5 |
|  | 6 |
|  | 13 |
|  | 5 |
| PSR Support | 3 |
| Action Resolver | 6 |
|  | 24 |
| 安全性 | 12 |
|  | 8 |
| CLI Tools | 3 |
| 附加信息 | 10+ |
| **** | **209+** |

---

## 🔍  

###  

**⭐ 初级 :**
- 基础 路由
-  路由
- 
- Helper Functions
- Route Shortcuts
- 
- CLI Tools

**⭐⭐ 中级 :**
- 参数 路由
- 组 路由
- IP Filtering
- Middleware
- Route Macros
- URL Generation
- 
- 
- Action Resolver
- 

**⭐⭐⭐ 高级 :**
- Rate Limiting & Auto-Ban
- Expression Language
- 
- PSR Support
- 安全性

###   

**路由:**
- 基础 路由
- 参数 路由
- 组 路由
-  路由
- URL Generation

**安全性:**
- Rate Limiting & Auto-Ban
- IP Filtering
- Middleware
- 安全性

**组织 :**
- 组 路由
- 
- Route Macros
- Namespace

**性能:**
- 
- 
- 

**:**
- 
- Middleware
- 
- PSR Support

---

## 📚  

- [USER_GUIDE.md](USER_GUIDE.md) -    
- [API_REFERENCE.md](API_REFERENCE.md) - API 
- [COMPARISON.md](COMPARISON.md) - 与替代方案比较
- [SECURITY_REPORT.md](SECURITY_REPORT.md) - 安全报告
- [PERFORMANCE_ANALYSIS.md](PERFORMANCE_ANALYSIS.md) - 性能分析
- [FAQ.md](FAQ.md) - 常见问题

---

**© 2024 CloudCastle HTTP Router**  
**版本：** 1.1.1  
**:** MIT

[⬆ Наверх](#индекс-всех-возможностей-cloudcastle-http-router)


---

## 📚 文档导航

[README](../../README.md) | [USER_GUIDE](USER_GUIDE.md) | [FEATURES_INDEX](FEATURES_INDEX.md) | [API_REFERENCE](API_REFERENCE.md) | [ALL_FEATURES](ALL_FEATURES.md) | [TESTS_SUMMARY](TESTS_SUMMARY.md) | [PERFORMANCE](PERFORMANCE_ANALYSIS.md) | [SECURITY](SECURITY_REPORT.md) | [COMPARISON](COMPARISON.md) | [FAQ](FAQ.md) | [DOC_SUMMARY](DOCUMENTATION_SUMMARY.md)

**详细文档：** [Features](features/) (22 文件) | [Tests](tests/) (7 报告)

---

