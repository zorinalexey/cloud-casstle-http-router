# 更新日志

[English](../en/CHANGELOG.md) | [Русский](../../CHANGELOG.md) | [Deutsch](../de/CHANGELOG.md) | [Français](../fr/CHANGELOG.md) | [**中文**](CHANGELOG.md)

---

项目中所有重要的更改都记录在此文件中。

格式基于 [Keep a Changelog](https://keepachangelog.com/zh-CN/1.0.0/)，
此项目遵循 [语义化版本](https://semver.org/lang/zh-CN/)。

## [未发布]

### 计划中
- 参数化路由的 Trie 结构
- 编译的正则表达式缓存
- PHP JIT 优化
- WebSocket 支持
- GraphQL 路由支持

## [1.1.1] - 2024-12-20

### 修复
- 在门面和路由器中添加了 `protocol` 参数到 `dispatch` 方法
- 修复了 JsonLoaderTest 中语句后的空字符串
- 更新了 Rector 配置以排除误报警告

### 改进
- 与 PHP 8.4 完全兼容
- 改进了文档
- 添加了详细的测试报告

## [1.1.0] - 2024-12-01

### 新增
- 用于复杂路由条件的表达式语言
- 用于扩展性的插件系统
- 路由的自动命名
- 基于端口的路由
- 增强的标签系统
- 用于自动 IP 阻止的 BanManager
- 用于方便时间间隔指定的 TimeUnit 枚举
- 用于路由导出的路由转储器
- 用于高级 URL 匹配的 UrlMatcher

### 更改
- 优化了路由搜索的索引系统
- 改进了速率限制器性能
- 重构了 RouteCompiler 以获得更好的性能

### 修复
- 深层组嵌套的问题
- 大量路由时的内存泄漏
- 白名单/黑名单 IP 操作不正确
  
## [1.0.0] - 2024-11-01

### 新增
- 基本路由器功能
- 支持所有 HTTP 方法（GET, POST, PUT, PATCH, DELETE, VIEW, ANY, MATCH）
- 路由组系统
- 中间件支持
- 命名路由
- 速率限制
- IP 过滤（白名单/黑名单）
- 域名路由
- HTTPS 强制
- 路由缓存
- URL 生成器
- 多个路由加载器：
  - JsonLoader
  - YamlLoader
  - XmlLoader
  - PhpLoader
  - AttributeLoader
- MiddlewareDispatcher
- 带约束的路由参数
- PSR-7 和 PSR-15 兼容性

### 测试
- 501 个单元测试
- 13 个安全测试
- 5 个性能测试
- 负载测试
- 压力测试
- PHPBench 基准测试

### 文档
- README.md
- 详细的 API 文档
- 使用示例
- 用户指南

## [0.9.0] - 2024-10-15

### 新增
- 第一个 beta 版本
- 基本路由
- 参数支持
- 简单组

## [0.5.0] - 2024-10-01

### 新增
- Alpha 版本
- 概念验证
- 基本测试

---

## 更改类型

- **新增** - 用于新功能
- **更改** - 用于现有功能的更改
- **弃用** - 用于即将删除的功能
- **删除** - 用于已删除的功能
- **修复** - 用于错误修复
- **安全** - 用于漏洞修复

---

[Unreleased]: https://github.com/zorinalexey/cloud-casstle-http-router/compare/v1.1.1...HEAD
[1.1.1]: https://github.com/zorinalexey/cloud-casstle-http-router/compare/v1.1.0...v1.1.1
[1.1.0]: https://github.com/zorinalexey/cloud-casstle-http-router/compare/v1.0.0...v1.1.0
[1.0.0]: https://github.com/zorinalexey/cloud-casstle-http-router/compare/v0.9.0...v1.0.0
[0.9.0]: https://github.com/zorinalexey/cloud-casstle-http-router/compare/v0.5.0...v0.9.0
[0.5.0]: https://github.com/zorinalexey/cloud-casstle-http-router/releases/tag/v0.5.0

