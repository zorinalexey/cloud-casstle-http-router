# Список всех возможностей CloudCastle HTTP Router

**Русский** | [English](docs/en/FEATURES_LIST.md) | [Deutsch](docs/de/FEATURES_LIST.md) | [Français](docs/fr/FEATURES_LIST.md) | [中文](docs/zh/FEATURES_LIST.md)

---



## 📋 Краткий список (100+ фич)

### Базовая маршрутизация
1. ✅ GET, POST, PUT, PATCH, DELETE
2. ✅ VIEW (кастомный метод)
3. ✅ custom() - любой HTTP метод
4. ✅ match() - множественные методы
5. ✅ any() - все методы
6. ✅ Facade API
7. ✅ Instance API

### Helper Functions (9 функций)
8. ✅ route() - получить маршрут
9. ✅ current_route() - текущий маршрут
10. ✅ previous_route() - предыдущий маршрут
11. ✅ route_is() - проверка имени
12. ✅ route_name() - имя текущего
13. ✅ router() - экземпляр роутера
14. ✅ dispatch_route() - диспетчеризация
15. ✅ route_url() - генерация URL
16. ✅ route_has() - существование
17. ✅ route_stats() - статистика
18. ✅ routes_by_tag() - по тегу
19. ✅ route_back() - назад

### Route Shortcuts (14 методов)
20. ✅ auth() - middleware auth
21. ✅ guest() - для неавторизованных
22. ✅ api() - API middleware
23. ✅ web() - Web middleware
24. ✅ cors() - CORS
25. ✅ localhost() - только localhost
26. ✅ secure() - HTTPS
27. ✅ throttleStandard() - 60 req/min
28. ✅ throttleStrict() - 10 req/min
29. ✅ throttleGenerous() - 1000 req/min
30. ✅ public() - публичный тег
31. ✅ private() - приватный тег
32. ✅ admin() - админ настройка
33. ✅ apiEndpoint() - API endpoint
34. ✅ protected() - защищенный

### Route Macros (7 макросов)
35. ✅ resource() - RESTful CRUD
36. ✅ apiResource() - API CRUD
37. ✅ crud() - простой CRUD
38. ✅ auth() - маршруты аутентификации
39. ✅ adminPanel() - админка
40. ✅ apiVersion() - версионирование API
41. ✅ webhooks() - веб хуки

### Группы маршрутов
42. ✅ Префиксы
43. ✅ Middleware в группе
44. ✅ Вложенные группы
45. ✅ Домены
46. ✅ Порты
47. ✅ Namespace
48. ✅ HTTPS requirement
49. ✅ Протоколы
50. ✅ Теги в группе
51. ✅ Throttle в группе
52. ✅ IP filtering в группе

### Middleware
53. ✅ Глобальный middleware
54. ✅ Middleware на маршруте
55. ✅ AuthMiddleware
56. ✅ CorsMiddleware
57. ✅ HttpsEnforcement
58. ✅ SecurityLogger
59. ✅ SsrfProtection
60. ✅ MiddlewareDispatcher

### Rate Limiting
61. ✅ Базовый throttle
62. ✅ TimeUnit enum (6 единиц)
63. ✅ Кастомный ключ
64. ✅ RateLimiter класс
65. ✅ tooManyAttempts()
66. ✅ availableIn()
67. ✅ remaining()
68. ✅ attempt()

### IP Filtering
69. ✅ Whitelist IP
70. ✅ Blacklist IP
71. ✅ CIDR нотация
72. ✅ Множественные IP
73. ✅ IP Spoofing защита

### Auto-Ban System
74. ✅ BanManager
75. ✅ enableAutoBan()
76. ✅ setAutoBanDuration()
77. ✅ ban() - ручной бан
78. ✅ unban() - разбан
79. ✅ isBanned() - проверка
80. ✅ getBannedIps()
81. ✅ clearAll()

### Именованные маршруты
82. ✅ name() - назначение
83. ✅ getRouteByName()
84. ✅ currentRouteName()
85. ✅ currentRouteNamed()
86. ✅ Auto-naming
87. ✅ enableAutoNaming()

### Теги
88. ✅ tag() - добавление
89. ✅ getRoutesByTag()
90. ✅ hasTag()
91. ✅ getAllTags()
92. ✅ Множественные теги

### Параметры маршрутов
93. ✅ Базовые параметры
94. ✅ where() - ограничения
95. ✅ Опциональные параметры
96. ✅ defaults() - значения по умолчанию
97. ✅ Inline паттерны
98. ✅ Множественные where

### Expression Language
99. ✅ condition() - условия
100. ✅ Операторы сравнения (==, !=, >, <, >=, <=)
101. ✅ Логические операторы (and, or)
102. ✅ ExpressionLanguage класс
103. ✅ evaluate()

### URL Generation
104. ✅ UrlGenerator класс
105. ✅ generate() - базовая генерация
106. ✅ absolute() - абсолютный URL
107. ✅ toDomain() - с доменом
108. ✅ toProtocol() - с протоколом
109. ✅ signed() - подписанный URL
110. ✅ setBaseUrl()
111. ✅ Query параметры

### Кеширование
112. ✅ enableCache()
113. ✅ compile()
114. ✅ loadFromCache()
115. ✅ clearCache()
116. ✅ autoCompile()
117. ✅ RouteCache класс
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

### Loaders (5 типов)
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

### Статистика (20+ методов)
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

### Дополнительные возможности
172. ✅ RouteDumper
173. ✅ UrlMatcher
174. ✅ current() - текущий маршрут
175. ✅ previous() - предыдущий
176. ✅ Singleton pattern
177. ✅ reset() - сброс singleton
178. ✅ setInstance() - установка
179. ✅ getInstance() - получение

### Безопасность
180. ✅ Path Traversal защита
181. ✅ SQL Injection защита
182. ✅ XSS защита
183. ✅ ReDoS защита
184. ✅ Method Override защита
185. ✅ Cache Injection защита
186. ✅ Resource Exhaustion защита
187. ✅ Unicode Security
188. ✅ HTTPS enforcement
189. ✅ Domain restrictions
190. ✅ Port restrictions
191. ✅ Protocol restrictions

### Исключения
192. ✅ RouteNotFoundException
193. ✅ MethodNotAllowedException
194. ✅ IpNotAllowedException
195. ✅ TooManyRequestsException
196. ✅ InsecureConnectionException
197. ✅ BannedException
198. ✅ InvalidActionException
199. ✅ RouterException

### Дополнительно
200. ✅ RouteInterface
201. ✅ MiddlewareInterface
202. ✅ RouteCollection
203. ✅ RouteGroup
204. ✅ Fluent API
205. ✅ Method chaining
206. ✅ CLI tools
207. ✅ routes-list команда
208. ✅ analyse команда
209. ✅ router команда

---

## Итого

**209+ возможностей и методов!**

Подробное описание каждой фичи смотрите в [ALL_FEATURES.md](docs/ru/ALL_FEATURES.md)

---

© 2024 CloudCastle HTTP Router

