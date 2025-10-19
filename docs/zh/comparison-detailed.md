# 与流行路由器的详细比较

**语言：** 🇷🇺 俄语 | [🇬🇧 英文](../en/comparison-detailed.md) | [🇩🇪 德语](../de/comparison-detailed.md) | [🇫🇷 法语](../fr/comparison-detailed.md) | [🇨🇳中文](../zh/comparison-detailed.md)

[📚 目录](_table-of-contents.md) | [🏠主页](README.md)

---

## 📋 评论

本文档包含 CloudCastle HTTP Router 与最流行的 PHP 路由器的详细比较：FastRoute、Symfony Router、Laravel Router、Slim Router 和 AltoRouter。

## 🏆 CloudCastle HTTP Router

### 主要特点

|参数|意义|
|:---|:---:|
| **版本** | 1.1.1+ |
| **PHP** | 8.2+ |
| **性能** |平均 50,946 请求/秒 |
| **最大路线** | 1,095,000 | 1,095,000
| **内存/路线** | 1.39 KB |
| **安装** | 10,000+ |
| **GitHub Stars** | - |

### ✅ 优势

1. **卓越的性能**
   - Fastest

 在所有经过测试的解决方案中
   - 真实条件下每秒超过 50,000 个请求
   - 优化路线搜索算法

2. **最大可扩展性**
   - 支持超过100万条路由
   - 每条路线仅占用 1.39 KB 内存
   - 高效的缓存

3. **全面的安全性**
   - SSRF保护（独特功能）
   - 自动禁止系统
   - IP filtering (whitelist/blacklist)
   - 内置速率限制
   - 抵御 13 种以上类型的攻击

4. **丰富的功能**
   - PSR-15 middleware support
   - 条件的表达语言
   - YAML/XML/JSON/属性配置
   - URL Generation
   - Analytics & Plugins
   - 具有继承性的路由组

5. **现代代码**
   - PHP 8.2+ 使用新功能
   - 无处不在的严格类型
   - PHPStan level max
   - 完整的测试覆盖率

### ⚠️ 弱点

1. **项目的新颖性**
   - 社区支持较少
   - 更少的现成示例
   - 不太出名

2. **PHP 要求**
   - 需要 PHP 8.2+（对于遗留项目可能是一个问题）

3. **包装尺寸**
   - 更多功能=更多代码
   - 对于简单的项目来说可能有点矫枉过正

### 🎯 主要特点

- ✅ RESTful routing
- ✅ 具有 URL 生成功能的命名路由
- ✅ 带前缀的路由组
- ✅ 中间件（全局、组、路由）
- ✅ PSR-15 兼容性
- ✅ 速率限制（按时间/请求）
- ✅ 自动禁止系统
- ✅ IP whitelist/blacklist
- ✅ SSRF Protection
- ✅ Domain routing
- ✅ Port routing
- ✅ HTTPS enforcement
- ✅ Protocol filtering (HTTP/HTTPS/WS/WSS)
- ✅ YAML configuration
- ✅ XML configuration
- ✅ PHP Attributes (PHP 8)
- ✅ Expression Language
- ✅ Route caching
- ✅ Analytics plugin
- ✅ Logger plugin
- ✅ Response cache plugin
- ✅ Custom plugins
- ✅ Route macros
- ✅ URL matching & generation
- ✅ Route dumper
- ✅ CORS middleware
- ✅ 具有角色的身份验证中间件

---

## ⚡ FastRoute

### 主要特点

|参数|意义|
|:---|:---:|
| **版本** | 1.3+ |
| **PHP** | 7.2+ |
| **性能** |平均 47,033 请求/秒 |
| **最大路线** | 〜500,000 |
| **内存/路线** | 2.1 KB |
| **安装** | 50M+ |
| **GitHub Stars** | 4.9K+ |

### ✅ 优势

1. **速度**
   - 最快的路由器之一（仅次于 CloudCastle）
   - 基于正则表达式的优化算法

2. **简单**
   - 简约的API
   - 易于集成
   - 清晰的文档

3. **受欢迎程度**
   - 广泛应用于社区
   - 许多示例和教程
   - 经过时间考验的解决方案

### ⚠️ 弱点

1. **最低功能**
   - 无中间件
   - 没有命名路线
   - 没有团体
   - 仅基本路由

2. **没有内置安全性**
   - 没有针对攻击的保护措施
   - 无速率限制
   - 无IP过滤

3. **无配置文件**
   - 仅软件配置
   - 没有 YAML/XML/JSON

### 🎯 主要特点

- ✅ RESTful routing
- ✅ Route parameters
- ✅ Route caching
- ❌ Named routes
- ❌ Route groups
- ❌ Middleware
- ❌ Rate limiting
- ❌ Security features

### 💡 何时使用

- 具有最低要求的微型项目
- 当只需要基本路由时
- PHP 7.2+ 上的遗留项目

---

## 🎼 Symfony Router

### 主要特点

|参数|意义|
|:---|:---:|
| **版本** | 6.0+ |
| **PHP** | 8.1+ |
| **性能** |平均 15,633 请求/秒 |
| **最大路线** | 〜100,000 |
| **内存/路线** | 8.5 KB |
| **安装** | 200M+ |
| **GitHub 之星** | 29K+（全部 Symfony）|

### ✅ 优势

1. **Enterprise-grade**
   - 经过验证的大型项目解决方案
   - Symfony 生态系统的一部分
   - 优秀的文档

2. **丰富的功能**
   - Expression Language
   - Attributes support
   - YAML/XML/JSON configuration
   - URL generation

3. **项目成熟度**
   - 超过15年的发展
   - 庞大的社区
   - 许多现成的解决方案

### ⚠️ 弱点

1. **性能不佳**
   - 比CloudCastle慢3.2倍
   - 开销大
   - 资源需求

2. **难度**
   - 陡峭的学习曲线
   - 很多抽象
   - 可能是多余的

3. **大尺寸**
   - 每条路线 8.5 KB 内存
   - 很多依赖项
   - 重包装

### 🎯 主要特点

- ✅ RESTful routing
- ✅ Named routes
- ✅ Route groups
- ✅ URL generation
- ✅ YAML/XML/JSON configuration
- ✅ PHP Attributes
- ✅ Expression Language
- ✅ Route caching
- ❌ 中间件（需要单独的组件）
- ❌ Rate limiting
- ❌ Auto-ban
- ❌ SSRF Protection

### 💡 何时使用

- Symfony 上的企业项目
- 当您需要完整的生态系统时
- 有稳定性要求的项目

---

## 🔴 Laravel Router

### 主要特点

|参数|意义|
|:---|:---:|
| **版本** | 10.0+ |
| **PHP** | 8.1+ |
| **性能** |平均 16,233 请求/秒 |
| **最大路线** | 〜80,000 |
| **内存/路线** | 10.2 KB |
| **安装** | 1.5亿+ |
| **GitHub 之星** | 75K+（全部 Laravel）|

### ✅ 优势

1. **Laravel 集成**
   - Seamless integration
   - Eloquent integration
   - Blade templates
   - 内置授权

2. **Developer Experience**
   - 优秀的DX
   - 简单清晰的API
   - 良好的文档

3. **功能**
   - Named routes
   - Route groups
   - Middleware
   - Rate limiting

### ⚠️ 弱点

1. **性能不佳**
   - 现代中最慢的
   - 框架的大量开销
   - 资源需求

2. **Laravel 依赖**
   - 很难在 Laravel 之外使用
   - 很多依赖项
   - 重包装

3. **可扩展性**
   - 限制约 80K 条路线
   - 每条路线 10+ KB
   - 高内存消耗

### 🎯 主要特点

- ✅ RESTful routing
- ✅ Named routes
- ✅ Route groups
- ✅ Middleware
- ✅ Rate limiting
- ✅ URL generation
- ✅ Route caching
- ❌ PSR-15
- ❌ YAML/XML/JSON config
- ❌ Auto-ban
- ❌ SSRF Protection
- ❌ Expression Language

### 💡 何时使用

- Laravel框架上的项目
- 当 DX 比性能更重要时
- 中小型应用

---

## 🍃 Slim Router

### 主要特点

|参数|意义|
|:---|:---:|
| **版本** | 4.0+ |
| **PHP** | 7.4+ |
| **性能** |平均 37,167 请求/秒 |
| **最大路线** | 〜200,000 |
| **内存/路线** | 4.8 KB |
| **安装** | 20M+ |
| **GitHub Stars** | 11.7K+ |

### ✅ 优势

1. **微框架**
   - 轻的
   - 便于使用
   - 快速启动

2. **PSR兼容**
   - PSR-7 (HTTP messages)
   - PSR-15 (Middleware)
   - PSR-11 (Container)

3. **良好的性能**
   - 更快的 Symfony/Laravel
   - 针对 API 进行了优化

### ⚠️ 弱点

1. **功能有限**
   - 基本功能
   - 没有太多高级功能
   - 无内置安全性

2. **生产力降低**
   - 比 CloudCastle 慢 37%
   - 比 FastRoute 慢 27%

3. **可扩展性**
   - 限制约 20 万条路线
   - 平均内存消耗

### 🎯 主要特点

- ✅ RESTful routing
- ✅ Named routes
- ✅ Route groups
- ✅ Middleware (PSR-15)
- ✅ URL generation
- ❌ Rate limiting
- ❌ Route caching
- ❌ YAML/XML/JSON config
- ❌ Auto-ban
- ❌ SSRF Protection

### 💡 何时使用

- API优先的应用程序
- 微服务
- 当您需要 PSR-15 且无需企业开销时

---

## 🗺️ AltoRouter

### 主要特点

|参数|意义|
|:---|:---:|
| **版本** | 2.0+ |
| **PHP** | 7.2+ |
| **性能** |平均 39,967 请求/秒 |
| **最大路线** | 〜150,000 |
| **内存/路线** | 6.1 KB |
| **安装** | 5M+ |
| **GitHub Stars** | 1.3K+ |

### ✅ 优势

1. **简单**
   - 非常简单的API
   - 简单易学
   - 最低代码

2. **良好的性能**
   - 更快的 Laravel/Symfony
   - 优化

3. **Named routes**
   - 支持命名路由
   - URL generation

### ⚠️ 弱点

1. **功能有限**
   - 无中间件
   - 没有团体
   - 没有配置文件

2. **小社区**
   - 更少的例子
   - 较少的支持
   - 更新较少

3. **无安全功能**
   - 没有针对攻击的保护措施
   - 无速率限制
   - 无IP过滤

### 🎯 主要特点

- ✅ RESTful routing
- ✅ Named routes
- ✅ URL generation
- ✅ Route matching
- ❌ Route groups
- ❌ Middleware
- ❌ Rate limiting
- ❌ Route caching
- ❌ YAML/XML/JSON config

### 💡 何时使用

- 简单的项目
- 当您需要轻型路由器时
- 遗留项目

---

## 📊 比较汇总表

＃＃＃ 表现

|路由器|请求/秒| VS 云堡 |评级 |
|:---|:---:|:---:|:---:|
| **CloudCastle** | **50,946** | **100%** | 🥇 |
| FastRoute | 47,033 | 92.3% | 🥈 |
| AltoRouter | 39,967 | 78.4% | 🥉 |
| Slim | 37,167 | 72.9% | 4 |
| Laravel | 16,233 | 31.9% | 5 |
| Symfony | 15,633 | 30.7% | 6 |

### 功能（共 25 个功能）

|路由器|数量 |百分比 |评级 |
|:---|:---:|:---:|:---:|
| **CloudCastle** | **25** | **100%** | 🥇 |
| Symfony | 10 | 40% | 🥈 |
| Laravel | 9 | 36% | 🥉 |
| Slim | 7 | 28% | 4 |
| AltoRouter | 4 | 16% | 5 |
| FastRoute | 3 | 12% | 6 |

### 可扩展性

|路由器|最大航线 |内存|评级 |
|:---|:---:|:---:|:---:|
| **CloudCastle** | **1,095K** | **1.39 KB** | 🥇 |
| FastRoute | 500K | 2.1 KB | 🥈 |
| Slim | 200K | 4.8 KB | 🥉 |
| AltoRouter | 150K | 6.1 KB | 4 |
| Symfony | 100K | 8.5 KB | 5 |
| Laravel | 80K | 10.2 KB | 6 |

### 总体评分

|地点 |路由器|产品。 |功能性|规模|总计 |
|:---|:---:|:---:|:---:|:---:|:---:|
| 🥇 | **CloudCastle** | 10 | 10 | 10 | **30** |
| 🥈 | FastRoute | 9 | 3 | 9 | **21** |
| 🥉 | Slim | 7 | 7 | 7 | **21** |
| 4 | Symfony | 3 | 9 | 5 | **17** |
| 5 | AltoRouter | 8 | 4 | 6 | **18** |
| 6 | Laravel | 4 | 8 | 4 | **16** |

## 🎯 选择建议

### 如果满足以下条件，请选择 CloudCastle HTTP 路由器：

- ✅ 需要最高性能
- ✅ 需要可扩展性（1000 多个路线）
- ✅ 应用程序安全很重要
- ✅ 需要丰富的开箱即用功能
- ✅ 您使用 PHP 8.2+
- ✅ 构建现代应用程序

### 如果满足以下条件，请选择 FastRoute：

- ✅ 只需要基本的路由
- ✅ 极简主义和速度比功能更重要
- ✅ PHP 7.2+ 中的遗留项目
- ✅ 微型项目

### 选择 Symfony Router 如果：

- ✅ 使用 Symfony 框架
- ✅ 您需要一个经过验证的企业平台
- ✅ 性能并不重要
- ✅ 项目成熟度很重要

### 如果满足以下条件，请选择 Laravel Router：

- ✅ 基于 Laravel 框架构建
- ✅ DX比性能更重要
- ✅ 小型/中型项目

### 如果满足以下条件，请选择 Slim 路由器：

- ✅ 您需要一个 PSR-15 轻型路由器
- ✅ API优先项目
- ✅ 微服务

### 如果满足以下条件，请选择 AltoRouter：

- ✅ 非常简单的项目
- ✅ 所需的最低代码
- ✅ 旧版支持

---

## 📈 结论

**CloudCastle HTTP Router** 是现代 PHP 应用程序的最佳选择，结合了：

1. **最大性能**（50K+ 请求/秒）
2. **卓越的可扩展性**（1M+ 路由）
3. **全面的安全性**（13+保护）
4. **丰富的功能**（25个功能）
5. **现代技术**（PHP 8.2+，PSR-15）

该路由器适用于小型项目和企业应用，提供市场上性能、功能和安全性的最佳平衡。

---

*最后更新：2025 年 10 月 18 日*

---

[📚 目录](_table-of-contents.md) | [🏠主页](README.md)

