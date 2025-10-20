# Vollständige Funktionsliste von CloudCastle HTTP Router

[English](../en/FEATURES_LIST.md) | [Русский](../../FEATURES_LIST.md) | [**Deutsch**](FEATURES_LIST.md) | [Français](../fr/FEATURES_LIST.md) | [中文](../zh/FEATURES_LIST.md)

---

## 📋 Schnelle Liste (100+ Funktionen)

### Grundlegendes Routing
1. ✅ GET, POST, PUT, PATCH, DELETE
2. ✅ VIEW (benutzerdefinierte Methode)
3. ✅ custom() - beliebige HTTP-Methode
4. ✅ match() - mehrere Methoden
5. ✅ any() - alle Methoden
6. ✅ Facade API
7. ✅ Instance API

### Hilfsfunktionen (9 Funktionen)
8. ✅ route() - Route abrufen
9. ✅ current_route() - aktuelle Route
10. ✅ previous_route() - vorherige Route
11. ✅ route_is() - Namensprüfung
12. ✅ route_name() - aktueller Name
13. ✅ router() - Router-Instanz
14. ✅ dispatch_route() - Dispatch
15. ✅ route_url() - URL-Generierung
16. ✅ route_has() - Existenz
17. ✅ route_stats() - Statistiken
18. ✅ routes_by_tag() - nach Tag
19. ✅ route_back() - zurück

### Route-Shortcuts (14 Methoden)
20. ✅ auth() - Auth-Middleware
21. ✅ guest() - für Unautorisierte
22. ✅ api() - API-Middleware
23. ✅ web() - Web-Middleware
24. ✅ cors() - CORS
25. ✅ localhost() - nur Localhost
26. ✅ secure() - HTTPS
27. ✅ throttleStandard() - 60 Req/Min
28. ✅ throttleStrict() - 10 Req/Min
29. ✅ throttleGenerous() - 1000 Req/Min
30. ✅ public() - öffentlicher Tag
31. ✅ private() - privater Tag
32. ✅ admin() - Admin-Einrichtung
33. ✅ apiEndpoint() - API-Endpunkt
34. ✅ protected() - geschützt

### Route-Makros (7 Makros)
35. ✅ resource() - RESTful CRUD
36. ✅ apiResource() - API CRUD
37. ✅ crud() - einfaches CRUD
38. ✅ auth() - Authentifizierungsrouten
39. ✅ adminPanel() - Admin-Panel
40. ✅ apiVersion() - API-Versionierung
41. ✅ webhooks() - Web-Hooks

### Route-Gruppen
42. ✅ Präfixe
43. ✅ Middleware in Gruppe
44. ✅ Verschachtelte Gruppen
45. ✅ Domains
46. ✅ Ports
47. ✅ Namespace
48. ✅ HTTPS-Anforderung
49. ✅ Protokolle
50. ✅ Tags in Gruppe
51. ✅ Throttle in Gruppe
52. ✅ IP-Filterung in Gruppe

### Middleware
53. ✅ Globale Middleware
54. ✅ Middleware auf Route
55. ✅ AuthMiddleware
56. ✅ CorsMiddleware
57. ✅ HttpsEnforcement
58. ✅ SecurityLogger
59. ✅ SsrfProtection
60. ✅ MiddlewareDispatcher

### Rate Limiting
61. ✅ Grundlegendes Throttle
62. ✅ TimeUnit-Enum (6 Einheiten)
63. ✅ Benutzerdefinierter Schlüssel
64. ✅ RateLimiter-Klasse
65. ✅ tooManyAttempts()
66. ✅ availableIn()
67. ✅ remaining()
68. ✅ attempt()

### IP-Filterung
69. ✅ Whitelist IP
70. ✅ Blacklist IP
71. ✅ CIDR-Notation
72. ✅ Mehrere IPs
73. ✅ IP-Spoofing-Schutz

### Auto-Ban-System
74. ✅ BanManager
75. ✅ enableAutoBan()
76. ✅ setAutoBanDuration()
77. ✅ ban() - manueller Ban
78. ✅ unban() - Entbannung
79. ✅ isBanned() - Prüfung
80. ✅ getBannedIps()
81. ✅ clearAll()

### Benannte Routen
82. ✅ name() - Zuweisung
83. ✅ getRouteByName()
84. ✅ currentRouteName()
85. ✅ currentRouteNamed()
86. ✅ Auto-Naming
87. ✅ enableAutoNaming()

### Tags
88. ✅ tag() - hinzufügen
89. ✅ getRoutesByTag()
90. ✅ hasTag()
91. ✅ getAllTags()
92. ✅ Mehrere Tags

### Route-Parameter
93. ✅ Grundlegende Parameter
94. ✅ where() - Einschränkungen
95. ✅ Optionale Parameter
96. ✅ defaults() - Standardwerte
97. ✅ Inline-Muster
98. ✅ Mehrere where

### Expression Language
99. ✅ condition() - Bedingungen
100. ✅ Vergleichsoperatoren (==, !=, >, <, >=, <=)
101. ✅ Logische Operatoren (and, or)
102. ✅ ExpressionLanguage-Klasse
103. ✅ evaluate()

### URL-Generierung
104. ✅ UrlGenerator-Klasse
105. ✅ generate() - grundlegende Generierung
106. ✅ absolute() - absolute URL
107. ✅ toDomain() - mit Domain
108. ✅ toProtocol() - mit Protokoll
109. ✅ signed() - signierte URL
110. ✅ setBaseUrl()
111. ✅ Query-Parameter

### Caching
112. ✅ enableCache()
113. ✅ compile()
114. ✅ loadFromCache()
115. ✅ clearCache()
116. ✅ autoCompile()
117. ✅ RouteCache-Klasse
118. ✅ RouteCompiler

### Plugins
119. ✅ PluginInterface
120. ✅ registerPlugin()
121. ✅ unregisterPlugin()
122. ✅ getPlugin()
123. ✅ hasPlugin()
124. ✅ getPlugins()
125. ✅ beforeDispatch Hook
126. ✅ afterDispatch Hook
127. ✅ onRouteRegistered Hook
128. ✅ onException Hook
129. ✅ LoggerPlugin
130. ✅ AnalyticsPlugin
131. ✅ ResponseCachePlugin
132. ✅ AbstractPlugin

### Loader (5 Typen)
133. ✅ JsonLoader
134. ✅ YamlLoader
135. ✅ XmlLoader
136. ✅ PhpLoader
137. ✅ AttributeLoader
138. ✅ loadFromDirectory()

### PSR-Unterstützung
139. ✅ PSR-7 HTTP Message
140. ✅ PSR-15 HTTP Server Handler
141. ✅ Psr15MiddlewareAdapter

### Action Resolver
142. ✅ Closure-Aktionen
143. ✅ Array [Controller, Methode]
144. ✅ String "Controller@Methode"
145. ✅ String "Controller::Methode"
146. ✅ Aufrufbare Controller
147. ✅ Dependency Injection

### Statistiken (20+ Methoden)
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

### Zusätzliche Funktionen
172. ✅ RouteDumper
173. ✅ UrlMatcher
174. ✅ current() - aktuelle Route
175. ✅ previous() - vorherige
176. ✅ Singleton-Muster
177. ✅ reset() - Singleton-Reset
178. ✅ setInstance() - Einstellung
179. ✅ getInstance() - Abrufen

### Sicherheit
180. ✅ Path-Traversal-Schutz
181. ✅ SQL-Injection-Schutz
182. ✅ XSS-Schutz
183. ✅ ReDoS-Schutz
184. ✅ Method-Override-Schutz
185. ✅ Cache-Injection-Schutz
186. ✅ Resource-Exhaustion-Schutz
187. ✅ Unicode-Sicherheit
188. ✅ HTTPS-Erzwingung
189. ✅ Domain-Beschränkungen
190. ✅ Port-Beschränkungen
191. ✅ Protokoll-Beschränkungen

### Ausnahmen
192. ✅ RouteNotFoundException
193. ✅ MethodNotAllowedException
194. ✅ IpNotAllowedException
195. ✅ TooManyRequestsException
196. ✅ InsecureConnectionException
197. ✅ BannedException
198. ✅ InvalidActionException
199. ✅ RouterException

### Zusätzlich
200. ✅ RouteInterface
201. ✅ MiddlewareInterface
202. ✅ RouteCollection
203. ✅ RouteGroup
204. ✅ Fluent API
205. ✅ Method Chaining
206. ✅ CLI-Tools
207. ✅ routes-list Befehl
208. ✅ analyse Befehl
209. ✅ router Befehl

---

## Gesamt

**209+ Funktionen und Methoden!**

Für detaillierte Beschreibung jeder Funktion siehe [ALL_FEATURES.md](ALL_FEATURES.md)

---

© 2024 CloudCastle HTTP Router
