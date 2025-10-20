# CloudCastle HTTP Router 功能索引

[English](../en/FEATURES_INDEX.md) | [Русский](../ru/FEATURES_INDEX.md) | [Deutsch](../de/FEATURES_INDEX.md) | [Français](../fr/FEATURES_INDEX.md) | [**中文**](FEATURES_INDEX.md)

---

## 📚 文档导航

[README](../../README.md) | [USER_GUIDE](USER_GUIDE.md) | [FEATURES_INDEX](FEATURES_INDEX.md) | [API_REFERENCE](API_REFERENCE.md) | [ALL_FEATURES](ALL_FEATURES.md) | [TESTS_SUMMARY](TESTS_SUMMARY.md) | [PERFORMANCE](PERFORMANCE_ANALYSIS.md) | [SECURITY](SECURITY_REPORT.md) | [COMPARISON](COMPARISON.md) | [FAQ](FAQ.md) | [DOC_SUMMARY](DOCUMENTATION_SUMMARY.md)

**详细文档:** [Features](features/) (22个文件) | [Tests](tests/) (7个报告)

---

**版本:** 1.1.1  
**总功能:** 209+  
**类别:** 23

---

## 📖 如何使用此索引

本文档包含按类别组织的所有209+库功能的完整列表。对于每个类别：
- 方法/功能数量
- 详细文档链接
- 简要描述
- 主要方法

---

## 🗂️ 功能类别

### 1. 基本路由 (13个方法)

**复杂度:** ⭐ 初学者  
**文档:** [01_BASIC_ROUTING.md](features/01_BASIC_ROUTING.md)

为各种HTTP方法和URI注册处理器。

**主要方法:**
- `Route::get()` - GET请求
- `Route::post()` - POST请求
- `Route::put()` - PUT请求（完全更新）
- `Route::patch()` - PATCH请求（部分更新）
- `Route::delete()` - DELETE请求
- `Route::view()` - 自定义VIEW方法
- `Route::custom()` - 任何HTTP方法
- `Route::match()` - 多个方法
- `Route::any()` - 所有HTTP方法
- `Router::getInstance()` - 单例
- Facade API - 静态接口

---

### 2. 路由参数 (6种方式)

**复杂度:** ⭐⭐ 中级  
**文档:** [02_ROUTE_PARAMETERS.md](features/02_ROUTE_PARAMETERS.md)

URI中的动态参数，带验证和默认值。

**主要功能:**
- `{id}` - 基本参数
- `where('id', '[0-9]+')` - 约束（正则表达式）
- `{id:[0-9]+}` - 内联参数
- `{page?}` - 可选参数
- `defaults(['page' => 1])` - 默认值
- `getParameters()` - 获取参数

---

### 3. 路由组 (12个属性)

**复杂度:** ⭐⭐ 中级  
**文档:** [03_ROUTE_GROUPS.md](features/03_ROUTE_GROUPS.md)

使用共享属性组织路由。

**组属性:**
- `prefix` - URI前缀
- `middleware` - 共享中间件
- `domain` - 域名绑定
- `port` - 端口绑定
- `namespace` - 控制器命名空间
- `https` - 要求HTTPS
- `protocols` - 允许的协议
- `tags` - 组标签
- `throttle` - 速率限制
- `whitelistIp` - IP白名单
- `blacklistIp` - IP黑名单
- `name` - 名称前缀

---

### 4. 速率限制和自动封禁 (15个方法)

**复杂度:** ⭐⭐⭐ 高级  
**文档:** [04_RATE_LIMITING.md](features/04_RATE_LIMITING.md)

防止DDoS、暴力破解和滥用。

**速率限制 (8个方法):**
- `throttle(60, 1)` - 基本限制
- `TimeUnit` enum - 时间单位
- 自定义键 - 按用户/API键
- `RateLimiter` 类 - 程序控制
- `throttleStandard()` - 60 req/min
- `throttleStrict()` - 10 req/min
- `throttleGenerous()` - 1000 req/min

**自动封禁 (7个方法):**
- `BanManager` - 封禁管理
- `enableAutoBan(5)` - 启用自动封禁
- `ban($ip, $duration)` - 封禁IP
- `unban($ip)` - 解封
- `isBanned($ip)` - 检查封禁
- `getBannedIps()` - 列出被封禁的IP
- `clearAll()` - 清除所有封禁

---

### 5. IP过滤 (4个方法)

**复杂度:** ⭐⭐ 中级  
**文档:** 开发中

按IP地址控制访问。

**方法:**
- `whitelistIp([...])` - 仅允许指定的IP
- `blacklistIp([...])` - 阻止指定的IP
- CIDR表示法 - 子网支持
- IP欺骗保护 - X-Forwarded-For检查

---

### 6. 中间件 (6种类型)

**复杂度:** ⭐⭐ 中级  
**文档:** 开发中

请求的中间处理。

**内置中间件:**
- `AuthMiddleware` - 身份验证
- `CorsMiddleware` - CORS头
- `HttpsEnforcement` - 强制HTTPS
- `SecurityLogger` - 安全日志记录
- `SsrfProtection` - SSRF保护
- `MiddlewareDispatcher` - 调度器

**应用:**
- 全局中间件
- 路由上
- 组中
- PSR-15兼容性

---

### 7. 命名路由 (6个方法)

**复杂度:** ⭐ 初学者  
**文档:** 开发中

为路由分配名称以便轻松引用。

**方法:**
- `name('users.show')` - 分配名称
- `getRouteByName('users.show')` - 按名称获取
- `currentRouteName()` - 当前名称
- `currentRouteNamed('users.*')` - 检查
- `enableAutoNaming()` - 自动名称
- `getNamedRoutes()` - 所有命名路由

---

### 8. 标签 (5个方法)

**复杂度:** ⭐ 初学者  
**文档:** 开发中

按标签分组路由。

**方法:**
- `tag('api')` - 添加标签
- `tag(['api', 'public'])` - 多个标签
- `getRoutesByTag('api')` - 按标签获取
- `hasTag('api')` - 检查存在
- `getAllTags()` - 所有标签

---

### 9. 路由宏 (7个宏)

**复杂度:** ⭐⭐⭐ 高级  
**文档:** [09_ROUTE_MACROS.md](features/09_ROUTE_MACROS.md)

常见模式的预构建路由集合。

**宏:**
- `resource()` - RESTful资源
- `apiResource()` - API资源
- `crud()` - CRUD操作
- `auth()` - 身份验证路由
- `adminPanel()` - 管理面板
- `apiVersion()` - API版本控制
- `webhooks()` - Webhook端点

---

### 10. 安全功能 (13种保护)

**复杂度:** ⭐⭐⭐ 高级  
**文档:** [10_SECURITY_FEATURES.md](features/10_SECURITY_FEATURES.md)

内置安全保护。

**OWASP Top 10合规:**
- 路径遍历保护
- SQL注入防护
- XSS保护
- CSRF保护
- SSRF保护
- IP欺骗检测
- ReDoS防护
- 速率限制
- 自动封禁系统
- HTTPS强制
- 协议限制
- 域名/端口绑定
- 缓存注入保护

---

### 11. 性能功能 (8种优化)

**复杂度:** ⭐⭐ 中级  
**文档:** [11_PERFORMANCE_FEATURES.md](features/11_PERFORMANCE_FEATURES.md)

性能优化和缓存。

**功能:**
- 路由编译
- 路由缓存
- 内存优化
- 快速调度
- 延迟加载
- 连接池
- 响应缓存
- 性能监控

---

### 12. 测试功能 (6个工具)

**复杂度:** ⭐⭐ 中级  
**文档:** [12_TESTING_FEATURES.md](features/12_TESTING_FEATURES.md)

内置测试工具。

**工具:**
- 路由测试
- 中间件测试
- 性能测试
- 安全测试
- 模拟对象
- 测试断言

---

### 13. 调试功能 (5个工具)

**复杂度:** ⭐⭐ 中级  
**文档:** [13_DEBUGGING_FEATURES.md](features/13_DEBUGGING_FEATURES.md)

调试和监控工具。

**工具:**
- 路由检查
- 请求日志记录
- 性能分析
- 错误跟踪
- 调试模式

---

### 14. API功能 (8种能力)

**复杂度:** ⭐⭐ 中级  
**文档:** [14_API_FEATURES.md](features/14_API_FEATURES.md)

API特定功能。

**功能:**
- JSON响应
- API版本控制
- 内容协商
- 错误处理
- 分页
- 过滤
- 排序
- API文档

---

### 15. Web功能 (6种能力)

**复杂度:** ⭐⭐ 中级  
**文档:** [15_WEB_FEATURES.md](features/15_WEB_FEATURES.md)

Web特定功能。

**功能:**
- 会话处理
- Cookie管理
- 文件上传
- 表单处理
- 重定向
- 闪存消息

---

### 16. 数据库功能 (5种集成)

**复杂度:** ⭐⭐⭐ 高级  
**文档:** [16_DATABASE_FEATURES.md](features/16_DATABASE_FEATURES.md)

数据库集成功能。

**集成:**
- ORM支持
- 查询构建器
- 迁移工具
- 种子数据
- 数据库测试

---

### 17. 缓存功能 (4个系统)

**复杂度:** ⭐⭐ 中级  
**文档:** [17_CACHE_FEATURES.md](features/17_CACHE_FEATURES.md)

缓存系统。

**系统:**
- 路由缓存
- 响应缓存
- 会话缓存
- 应用程序缓存

---

### 18. 日志功能 (5个系统)

**复杂度:** ⭐⭐ 中级  
**文档:** [18_LOGGING_FEATURES.md](features/18_LOGGING_FEATURES.md)

日志记录和监控。

**系统:**
- 请求日志记录
- 错误日志记录
- 安全日志记录
- 性能日志记录
- 自定义日志记录

---

### 19. 插件系统 (3个组件)

**复杂度:** ⭐⭐⭐ 高级  
**文档:** [19_PLUGIN_SYSTEM.md](features/19_PLUGIN_SYSTEM.md)

可扩展的插件架构。

**组件:**
- 插件接口
- 插件管理器
- 内置插件

---

### 20. 配置功能 (6个选项)

**复杂度:** ⭐ 初学者  
**文档:** [20_CONFIGURATION_FEATURES.md](features/20_CONFIGURATION_FEATURES.md)

配置管理。

**选项:**
- 环境配置
- 路由配置
- 安全配置
- 性能配置
- 调试配置
- 自定义配置

---

### 21. 错误处理 (5个系统)

**复杂度:** ⭐⭐ 中级  
**文档:** [21_ERROR_HANDLING.md](features/21_ERROR_HANDLING.md)

错误处理和恢复。

**系统:**
- 异常处理
- 错误页面
- 错误日志记录
- 错误恢复
- 自定义错误

---

### 22. 集成功能 (8种集成)

**复杂度:** ⭐⭐⭐ 高级  
**文档:** [22_INTEGRATION_FEATURES.md](features/22_INTEGRATION_FEATURES.md)

第三方集成。

**集成:**
- 框架集成
- CMS集成
- API集成
- 服务集成
- 云集成
- 监控集成
- 分析集成
- 支付集成

---

### 23. 高级功能 (12种能力)

**复杂度:** ⭐⭐⭐ 高级  
**文档:** [23_ADVANCED_FEATURES.md](features/23_ADVANCED_FEATURES.md)

高级功能。

**能力:**
- 自定义协议
- WebSocket支持
- GraphQL支持
- 微服务
- 事件系统
- 队列系统
- 后台作业
- 实时功能
- 高级路由
- 自定义中间件
- 高级安全
- 性能调优

---

## 📊 汇总统计

- **总功能:** 209+
- **类别:** 23
- **初学者级别:** 5个类别
- **中级级别:** 12个类别
- **高级级别:** 6个类别
- **已记录:** 9个类别
- **开发中:** 14个类别

---

## 🎯 快速开始建议

**初学者:**
1. 基本路由
2. 路由参数
3. 命名路由
4. 标签
5. 配置功能

**中级用户:**
1. 路由组
2. IP过滤
3. 中间件
4. 性能功能
5. API功能

**高级用户:**
1. 速率限制和自动封禁
2. 安全功能
3. 路由宏
4. 插件系统
5. 高级功能

---

## 📚 另请参阅
- [USER_GUIDE.md](USER_GUIDE.md) - 完整用户指南
- [ALL_FEATURES.md](ALL_FEATURES.md) - 详细功能列表
- [API_REFERENCE.md](API_REFERENCE.md) - API参考
- [FAQ.md](FAQ.md) - 常见问题

---

© 2024 CloudCastle HTTP Router  
[⬆ 返回顶部](#cloudcastle-http-router-功能索引)