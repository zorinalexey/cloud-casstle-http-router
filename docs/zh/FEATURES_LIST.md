# CloudCastle HTTP Router 完整功能列表

[English](../en/FEATURES_LIST.md) | [Русский](../../FEATURES_LIST.md) | [Deutsch](../de/FEATURES_LIST.md) | [Français](../fr/FEATURES_LIST.md) | [**中文**](FEATURES_LIST.md)

---

## 📋 快速列表（100+ 功能）

### 基础路由
1. ✅ GET, POST, PUT, PATCH, DELETE
2. ✅ VIEW（自定义方法）
3. ✅ custom() - 任意 HTTP 方法
4. ✅ match() - 多种方法
5. ✅ any() - 所有方法
6. ✅ Facade API
7. ✅ Instance API

### 辅助函数（9 个函数）
8. ✅ route() - 获取路由
9. ✅ current_route() - 当前路由
10. ✅ previous_route() - 上一个路由
11. ✅ route_is() - 名称检查
12. ✅ route_name() - 当前名称
13. ✅ router() - 路由器实例
14. ✅ dispatch_route() - 调度
15. ✅ route_url() - URL 生成
16. ✅ route_has() - 存在性
17. ✅ route_stats() - 统计
18. ✅ routes_by_tag() - 按标签
19. ✅ route_back() - 返回

### 路由快捷方式（14 种方法）
20. ✅ auth() - 认证中间件
21. ✅ guest() - 未授权用户
22. ✅ api() - API 中间件
23. ✅ web() - Web 中间件
24. ✅ cors() - CORS
25. ✅ localhost() - 仅本地主机
26. ✅ secure() - HTTPS
27. ✅ throttleStandard() - 60 请求/分钟
28. ✅ throttleStrict() - 10 请求/分钟
29. ✅ throttleGenerous() - 1000 请求/分钟
30. ✅ public() - 公共标签
31. ✅ private() - 私有标签
32. ✅ admin() - 管理员设置
33. ✅ apiEndpoint() - API 端点
34. ✅ protected() - 受保护

### 路由宏（7 个宏）
35. ✅ resource() - RESTful CRUD
36. ✅ apiResource() - API CRUD
37. ✅ crud() - 简单 CRUD
38. ✅ auth() - 认证路由
39. ✅ adminPanel() - 管理面板
40. ✅ apiVersion() - API 版本控制
41. ✅ webhooks() - Web 钩子

### 路由组
42. ✅ 前缀
43. ✅ 组内中间件
44. ✅ 嵌套组
45. ✅ 域名
46. ✅ 端口
47. ✅ 命名空间
48. ✅ HTTPS 要求
49. ✅ 协议
50. ✅ 组内标签
51. ✅ 组内节流
52. ✅ 组内 IP 过滤

### 中间件
53. ✅ 全局中间件
54. ✅ 路由中间件
55. ✅ AuthMiddleware
56. ✅ CorsMiddleware
57. ✅ HttpsEnforcement
58. ✅ SecurityLogger
59. ✅ SsrfProtection
60. ✅ MiddlewareDispatcher

### 速率限制
61. ✅ 基础节流
62. ✅ TimeUnit 枚举（6 个单位）
63. ✅ 自定义键
64. ✅ RateLimiter 类
65. ✅ tooManyAttempts()
66. ✅ availableIn()
67. ✅ remaining()
68. ✅ attempt()

### IP 过滤
69. ✅ 白名单 IP
70. ✅ 黑名单 IP
71. ✅ CIDR 表示法
72. ✅ 多个 IP
73. ✅ IP 欺骗保护

### 自动封禁系统
74. ✅ BanManager
75. ✅ enableAutoBan()
76. ✅ setAutoBanDuration()
77. ✅ ban() - 手动封禁
78. ✅ unban() - 解封
79. ✅ isBanned() - 检查
80. ✅ getBannedIps()
81. ✅ clearAll()

### 命名路由
82. ✅ name() - 分配
83. ✅ getRouteByName()
84. ✅ currentRouteName()
85. ✅ currentRouteNamed()
86. ✅ 自动命名
87. ✅ enableAutoNaming()

### 标签
88. ✅ tag() - 添加
89. ✅ getRoutesByTag()
90. ✅ hasTag()
91. ✅ getAllTags()
92. ✅ 多个标签

### 路由参数
93. ✅ 基础参数
94. ✅ where() - 约束
95. ✅ 可选参数
96. ✅ defaults() - 默认值
97. ✅ 内联模式
98. ✅ 多个 where

### 表达式语言
99. ✅ condition() - 条件
100. ✅ 比较运算符（==, !=, >, <, >=, <=）
101. ✅ 逻辑运算符（and, or）
102. ✅ ExpressionLanguage 类
103. ✅ evaluate()

### URL 生成
104. ✅ UrlGenerator 类
105. ✅ generate() - 基础生成
106. ✅ absolute() - 绝对 URL
107. ✅ toDomain() - 带域名
108. ✅ toProtocol() - 带协议
109. ✅ signed() - 签名 URL
110. ✅ setBaseUrl()
111. ✅ 查询参数

### 缓存
112. ✅ enableCache()
113. ✅ compile()
114. ✅ loadFromCache()
115. ✅ clearCache()
116. ✅ autoCompile()
117. ✅ RouteCache 类
118. ✅ RouteCompiler

### 插件
119. ✅ PluginInterface
120. ✅ registerPlugin()
121. ✅ unregisterPlugin()
122. ✅ getPlugin()
123. ✅ hasPlugin()
124. ✅ getPlugins()
125. ✅ beforeDispatch 钩子
126. ✅ afterDispatch 钩子
127. ✅ onRouteRegistered 钩子
128. ✅ onException 钩子
129. ✅ LoggerPlugin
130. ✅ AnalyticsPlugin
131. ✅ ResponseCachePlugin
132. ✅ AbstractPlugin

### 加载器（5 种类型）
133. ✅ JsonLoader
134. ✅ YamlLoader
135. ✅ XmlLoader
136. ✅ PhpLoader
137. ✅ AttributeLoader
138. ✅ loadFromDirectory()

### PSR 支持
139. ✅ PSR-7 HTTP Message
140. ✅ PSR-15 HTTP Server Handler
141. ✅ Psr15MiddlewareAdapter

### 动作解析器
142. ✅ Closure 动作
143. ✅ Array [Controller, method]
144. ✅ String "Controller@method"
145. ✅ String "Controller::method"
146. ✅ 可调用控制器
147. ✅ 依赖注入

### 统计（20+ 种方法）
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

### 附加功能
172. ✅ RouteDumper
173. ✅ UrlMatcher
174. ✅ current() - 当前路由
175. ✅ previous() - 上一个
176. ✅ 单例模式
177. ✅ reset() - 单例重置
178. ✅ setInstance() - 设置
179. ✅ getInstance() - 获取

### 安全性
180. ✅ 路径遍历保护
181. ✅ SQL 注入保护
182. ✅ XSS 保护
183. ✅ ReDoS 保护
184. ✅ 方法覆盖保护
185. ✅ 缓存注入保护
186. ✅ 资源耗尽保护
187. ✅ Unicode 安全
188. ✅ HTTPS 强制
189. ✅ 域名限制
190. ✅ 端口限制
191. ✅ 协议限制

### 异常
192. ✅ RouteNotFoundException
193. ✅ MethodNotAllowedException
194. ✅ IpNotAllowedException
195. ✅ TooManyRequestsException
196. ✅ InsecureConnectionException
197. ✅ BannedException
198. ✅ InvalidActionException
199. ✅ RouterException

### 附加
200. ✅ RouteInterface
201. ✅ MiddlewareInterface
202. ✅ RouteCollection
203. ✅ RouteGroup
204. ✅ Fluent API
205. ✅ 方法链
206. ✅ CLI 工具
207. ✅ routes-list 命令
208. ✅ analyse 命令
209. ✅ router 命令

---

## 总计

**209+ 功能和方法！**

每个功能的详细描述请参见 [ALL_FEATURES.md](ALL_FEATURES.md)

---

© 2024 CloudCastle HTTP Router
