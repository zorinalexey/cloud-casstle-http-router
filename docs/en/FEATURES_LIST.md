# Complete Features List of CloudCastle HTTP Router

[English](**FEATURES_LIST.md**) | [Русский](../../FEATURES_LIST.md) | [Deutsch](../de/FEATURES_LIST.md) | [Français](../fr/FEATURES_LIST.md) | [中文](../zh/FEATURES_LIST.md)

---

## 📋 Quick List (100+ features)

### Basic Routing
1. ✅ GET, POST, PUT, PATCH, DELETE
2. ✅ VIEW (custom method)
3. ✅ custom() - any HTTP method
4. ✅ match() - multiple methods
5. ✅ any() - all methods
6. ✅ Facade API
7. ✅ Instance API

### Helper Functions (9 functions)
8. ✅ route() - get route
9. ✅ current_route() - current route
10. ✅ previous_route() - previous route
11. ✅ route_is() - name check
12. ✅ route_name() - current name
13. ✅ router() - router instance
14. ✅ dispatch_route() - dispatch
15. ✅ route_url() - URL generation
16. ✅ route_has() - existence
17. ✅ route_stats() - statistics
18. ✅ routes_by_tag() - by tag
19. ✅ route_back() - back

### Route Shortcuts (14 methods)
20. ✅ auth() - auth middleware
21. ✅ guest() - for unauthorized
22. ✅ api() - API middleware
23. ✅ web() - Web middleware
24. ✅ cors() - CORS
25. ✅ localhost() - localhost only
26. ✅ secure() - HTTPS
27. ✅ throttleStandard() - 60 req/min
28. ✅ throttleStrict() - 10 req/min
29. ✅ throttleGenerous() - 1000 req/min
30. ✅ public() - public tag
31. ✅ private() - private tag
32. ✅ admin() - admin setup
33. ✅ apiEndpoint() - API endpoint
34. ✅ protected() - protected

### Route Macros (7 macros)
35. ✅ resource() - RESTful CRUD
36. ✅ apiResource() - API CRUD
37. ✅ crud() - simple CRUD
38. ✅ auth() - authentication routes
39. ✅ adminPanel() - admin panel
40. ✅ apiVersion() - API versioning
41. ✅ webhooks() - web hooks

### Route Groups
42. ✅ Prefixes
43. ✅ Middleware in group
44. ✅ Nested groups
45. ✅ Domains
46. ✅ Ports
47. ✅ Namespace
48. ✅ HTTPS requirement
49. ✅ Protocols
50. ✅ Tags in group
51. ✅ Throttle in group
52. ✅ IP filtering in group

### Middleware
53. ✅ Global middleware
54. ✅ Middleware on route
55. ✅ AuthMiddleware
56. ✅ CorsMiddleware
57. ✅ HttpsEnforcement
58. ✅ SecurityLogger
59. ✅ SsrfProtection
60. ✅ MiddlewareDispatcher

### Rate Limiting
61. ✅ Basic throttle
62. ✅ TimeUnit enum (6 units)
63. ✅ Custom key
64. ✅ RateLimiter class
65. ✅ tooManyAttempts()
66. ✅ availableIn()
67. ✅ remaining()
68. ✅ attempt()

### IP Filtering
69. ✅ Whitelist IP
70. ✅ Blacklist IP
71. ✅ CIDR notation
72. ✅ Multiple IPs
73. ✅ IP Spoofing protection

### Auto-Ban System
74. ✅ BanManager
75. ✅ enableAutoBan()
76. ✅ setAutoBanDuration()
77. ✅ ban() - manual ban
78. ✅ unban() - unban
79. ✅ isBanned() - check
80. ✅ getBannedIps()
81. ✅ clearAll()

### Named Routes
82. ✅ name() - assignment
83. ✅ getRouteByName()
84. ✅ currentRouteName()
85. ✅ currentRouteNamed()
86. ✅ Auto-naming
87. ✅ enableAutoNaming()

### Tags
88. ✅ tag() - adding
89. ✅ getRoutesByTag()
90. ✅ hasTag()
91. ✅ getAllTags()
92. ✅ Multiple tags

### Route Parameters
93. ✅ Basic parameters
94. ✅ where() - constraints
95. ✅ Optional parameters
96. ✅ defaults() - default values
97. ✅ Inline patterns
98. ✅ Multiple where

### Expression Language
99. ✅ condition() - conditions
100. ✅ Comparison operators (==, !=, >, <, >=, <=)
101. ✅ Logical operators (and, or)
102. ✅ ExpressionLanguage class
103. ✅ evaluate()

### URL Generation
104. ✅ UrlGenerator class
105. ✅ generate() - basic generation
106. ✅ absolute() - absolute URL
107. ✅ toDomain() - with domain
108. ✅ toProtocol() - with protocol
109. ✅ signed() - signed URL
110. ✅ setBaseUrl()
111. ✅ Query parameters

### Caching
112. ✅ enableCache()
113. ✅ compile()
114. ✅ loadFromCache()
115. ✅ clearCache()
116. ✅ autoCompile()
117. ✅ RouteCache class
118. ✅ RouteCompiler

### Plugins
119. ✅ PluginInterface
120. ✅ registerPlugin()
121. ✅ unregisterPlugin()
122. ✅ getPlugin()
123. ✅ hasPlugin()
124. ✅ getPlugins()
125. ✅ beforeDispatch hook
126. ✅ afterDispatch hook
127. ✅ onRouteRegistered hook
128. ✅ onException hook
129. ✅ LoggerPlugin
130. ✅ AnalyticsPlugin
131. ✅ ResponseCachePlugin
132. ✅ AbstractPlugin

### Loaders (5 types)
133. ✅ JsonLoader
134. ✅ YamlLoader
135. ✅ XmlLoader
136. ✅ PhpLoader
137. ✅ AttributeLoader
138. ✅ loadFromDirectory()

### PSR Support
139. ✅ PSR-7 HTTP Message
140. ✅ PSR-15 HTTP Server Handler
141. ✅ Psr15MiddlewareAdapter

### Action Resolver
142. ✅ Closure actions
143. ✅ Array [Controller, method]
144. ✅ String "Controller@method"
145. ✅ String "Controller::method"
146. ✅ Invokable controllers
147. ✅ Dependency injection

### Statistics (20+ methods)
148. ✅ getRouteStats()
149. ✅ getRoutesByMethod()
150. ✅ getRoutesByDomain()
151. ✅ getRoutesByPort()
152. ✅ getRoutesByPrefix()
153. ✅ getRoutesByUriPattern()
154. ✅ getRoutesByMiddleware()
155. ✅ getRoutesByController()
156. ✅ getRoutesWithIpRestrictions()
157. ✅ getThrottledRoutes()
158. ✅ getRoutesWithDomain()
159. ✅ getRoutesWithPort()
160. ✅ searchRoutes()
161. ✅ getRoutesGroupedByMethod()
162. ✅ getRoutesGroupedByPrefix()
163. ✅ getRoutesGroupedByDomain()
164. ✅ getRoutes()
165. ✅ getNamedRoutes()
166. ✅ getAllDomains()
167. ✅ getAllPorts()
168. ✅ getAllTags()
169. ✅ count()
170. ✅ getRoutesAsJson()
171. ✅ getRoutesAsArray()

### Additional Features
172. ✅ RouteDumper
173. ✅ UrlMatcher
174. ✅ current() - current route
175. ✅ previous() - previous
176. ✅ Singleton pattern
177. ✅ reset() - singleton reset
178. ✅ setInstance() - setting
179. ✅ getInstance() - getting

### Security
180. ✅ Path Traversal protection
181. ✅ SQL Injection protection
182. ✅ XSS protection
183. ✅ ReDoS protection
184. ✅ Method Override protection
185. ✅ Cache Injection protection
186. ✅ Resource Exhaustion protection
187. ✅ Unicode Security
188. ✅ HTTPS enforcement
189. ✅ Domain restrictions
190. ✅ Port restrictions
191. ✅ Protocol restrictions

### Exceptions
192. ✅ RouteNotFoundException
193. ✅ MethodNotAllowedException
194. ✅ IpNotAllowedException
195. ✅ TooManyRequestsException
196. ✅ InsecureConnectionException
197. ✅ BannedException
198. ✅ InvalidActionException
199. ✅ RouterException

### Additional
200. ✅ RouteInterface
201. ✅ MiddlewareInterface
202. ✅ RouteCollection
203. ✅ RouteGroup
204. ✅ Fluent API
205. ✅ Method chaining
206. ✅ CLI tools
207. ✅ routes-list command
208. ✅ analyse command
209. ✅ router command

---

## Total

**209+ features and methods!**

For detailed description of each feature, see [ALL_FEATURES.md](ALL_FEATURES.md)

---

© 2024 CloudCastle HTTP Router
