[🇷🇺 Русский](ru/comparison-detailed.md) | [🇺🇸 English](en/comparison-detailed.md) | [🇩🇪 Deutsch](de/comparison-detailed.md) | [🇫🇷 Français](fr/comparison-detailed.md) | [🇨🇳 中文](zh/comparison-detailed.md)

[📚 Table of Contents](en/_table-of-contents.md) | [🏠 Home](en/README.md)

---

# Detailed comparison with popular routers

**Languages:** 🇷🇺 Russian | [🇬🇧 English](../en/comparison-detailed.md) | [🇩🇪 Deutsch](../de/comparison-detailed.md) | [🇫🇷 Français](../fr/comparison-detailed.md) | [🇨🇳中文](../zh/comparison-detailed.md)

[📚 Table of Contents](_table-of-contents.md) | [🏠 Home](README.md)

---

## 📋 Review

This document contains a detailed comparison of CloudCastle HTTP Router with the most popular PHP routers: FastRoute, Symfony Router, Laravel Router, Slim Router and AltoRouter.

## 🏆 CloudCastle HTTP Router

### Key Features

| Parameter | Meaning |
|:---|:---:|
| **Version** | 1.1.1+ |
| **PHP** | 8.2+ |
| **Performance** | 50,946 req/sec avg |
| **Maximum routes** | 1,095,000 |
| **Memory/route** | 1.39 KB |
| **Installation** | 10,000+ |
| **GitHub Stars** | - |

### ✅ Strengths

1. **Exceptional performance**
   - Fastest

 among all tested solutions
   - 50,000+ requests/sec in real conditions
   - Optimized route search algorithms

2. **Maximum scalability**
   - Supports more than 1 million routes
   - Only 1.39 KB of memory per route
   - Efficient caching

3. **Comprehensive security**
   - SSRF Protection (unique feature)
   - Auto-ban system
   - IP filtering (whitelist/blacklist)
   - Rate limiting built-in
   - Protection from 13+ types of attacks

4. **Rich functionality**
   - PSR-15 middleware support
   - Expression Language for conditions
   - YAML/XML/JSON/Attributes configuration
   - URL Generation
   - Analytics & Plugins
   - Route groups with inheritance

5. **Modern code**
   - PHP 8.2+ using new features
   - Strict types everywhere
   - PHPStan level max
   - Full test coverage

### ⚠️ Weaknesses

1. **Novelty of the project**
   - Less community support
   - Fewer ready-made examples
   - Less famous

2. **PHP Requirements**
   - Requires PHP 8.2+ (may be an issue for legacy projects)

3. **Package size**
   - More features = more code
   - May be overkill for simple projects

### 🎯 Main features

- ✅ RESTful routing
- ✅ Named routes with URL generation
- ✅ Route groups with prefixes
- ✅ Middleware (global, groups, routes)
- ✅ PSR-15 compatibility
- ✅ Rate limiting (by time/requests)
- ✅ Auto-ban system
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
- ✅ Auth middleware with roles

---

## ⚡ FastRoute

### Key Features

| Parameter | Meaning |
|:---|:---:|
| **Version** | 1.3+ |
| **PHP** | 7.2+ |
| **Performance** | 47,033 req/sec avg |
| **Maximum routes** | ~500,000 |
| **Memory/route** | 2.1 KB |
| **Installation** | 50M+ |
| **GitHub Stars** | 4.9K+ |

### ✅ Strengths

1. **Speed**
   - One of the fastest routers (after CloudCastle)
   - Optimized algorithm based on regular expressions

2. **Simplicity**
   - Minimalistic API
   - Easy to integrate
   - Clear documentation

3. **Popularity**
   - Widely used in the community
   - Many examples and tutorials
   - Time-tested solution

### ⚠️ Weaknesses

1. **Minimum functionality**
   - No middleware
   - No named routes
   - No groups
   - Basic routing only

2. **No built-in security**
   - No protection against attacks
   - No rate limiting
   - No IP filtering

3. **No configuration files**
   - Software configuration only
   - No YAML/XML/JSON

### 🎯 Main features

- ✅ RESTful routing
- ✅ Route parameters
- ✅ Route caching
- ❌ Named routes
- ❌ Route groups
- ❌ Middleware
- ❌ Rate limiting
- ❌ Security features

### 💡 When to use

- Micro-projects with minimum requirements
- When only basic routing is needed
- Legacy projects on PHP 7.2+

---

## 🎼 Symfony Router

### Key Features

| Parameter | Meaning |
|:---|:---:|
| **Version** | 6.0+ |
| **PHP** | 8.1+ |
| **Performance** | 15,633 req/sec avg |
| **Maximum routes** | ~100,000 |
| **Memory/route** | 8.5 KB |
| **Installation** | 200M+ |
| **GitHub Stars** | 29K+ (all Symfony) |

### ✅ Strengths

1. **Enterprise-grade**
   - Proven solution for large projects
   - Part of the Symfony ecosystem
   - Excellent documentation

2. **Rich functionality**
   - Expression Language
   - Attributes support
   - YAML/XML/JSON configuration
   - URL generation

3. **Project maturity**
   - More than 15 years of development
   - Huge community
   - Many ready-made solutions

### ⚠️ Weaknesses

1. **Poor performance**
   - 3.2 times slower than CloudCastle
   - Large overhead
   - Requirement for resources

2. **Difficulty**
   - Steep learning curve
   - Lots of abstractions
   - May be redundant

3. **Large size**
   - 8.5 KB memory per route
   - Lots of dependencies
   - Heavy package

### 🎯 Main features

- ✅ RESTful routing
- ✅ Named routes
- ✅ Route groups
- ✅ URL generation
- ✅ YAML/XML/JSON configuration
- ✅ PHP Attributes
- ✅ Expression Language
- ✅ Route caching
- ❌ Middleware (separate components required)
- ❌ Rate limiting
- ❌ Auto-ban
- ❌ SSRF Protection

### 💡 When to use

- Enterprise projects on Symfony
- When you need a complete ecosystem
- Projects with stability requirements

---

## 🔴 Laravel Router

### Key Features

| Parameter | Meaning |
|:---|:---:|
| **Version** | 10.0+ |
| **PHP** | 8.1+ |
| **Performance** | 16,233 req/sec avg |
| **Maximum routes** | ~80,000 |
| **Memory/route** | 10.2 KB |
| **Installation** | 150M+ |
| **GitHub Stars** | 75K+ (all Laravel) |

### ✅ Strengths

1. **Laravel Integration**
   - Seamless integration
   - Eloquent integration
   - Blade templates
   - Built-in authorization

2. **Developer Experience**
   - Excellent DX
   - Simple and clear API
   - Good documentation

3. **Functionality**
   - Named routes
   - Route groups
   - Middleware
   - Rate limiting

### ⚠️ Weaknesses

1. **Poor performance**
   - The slowest among modern ones
   - Lots of overhead from the framework
   - Requirement for resources

2. **Laravel Dependency**
   - Difficult to use outside of Laravel
   - Lots of dependencies
   - Heavy package

3. **Scalability**
   - Limit ~80K routes
   - 10+ KB per route
   - High memory consumption

### 🎯 Main features

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

### 💡 When to use

- Projects on Laravel framework
- When DX is more important than performance
- Small and medium applications

---

## 🍃 Slim Router

### Key Features

| Parameter | Meaning |
|:---|:---:|
| **Version** | 4.0+ |
| **PHP** | 7.4+ |
| **Performance** | 37,167 req/sec avg |
| **Maximum routes** | ~200,000 |
| **Memory/route** | 4.8 KB |
| **Installation** | 20M+ |
| **GitHub Stars** | 11.7K+ |

### ✅ Strengths

1. **Microframework**
   - Lightweight
   - Easy to use
   - Quick start

2. **PSR compatible**
   - PSR-7 (HTTP messages)
   - PSR-15 (Middleware)
   - PSR-11 (Container)

3. **Good performance**
   - Faster Symfony/Laravel
   - Optimized for API

### ⚠️ Weaknesses

1. **Limited functionality**
   - Basic functionality
   - There are no many advanced features
   - No built-in security

2. **Lower productivity**
   - 37% slower than CloudCastle
   - 27% slower than FastRoute

3. **Scalability**
   - Limit ~200K routes
   - Average memory consumption

### 🎯 Main features

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

### 💡 When to use

- API-first applications
- Microservices
- When you need a PSR-15 without enterprise overhead

---

## 🗺️ AltoRouter

### Key Features

| Parameter | Meaning |
|:---|:---:|
| **Version** | 2.0+ |
| **PHP** | 7.2+ |
| **Performance** | 39,967 req/sec avg |
| **Maximum routes** | ~150,000 |
| **Memory/route** | 6.1 KB |
| **Installation** | 5M+ |
| **GitHub Stars** | 1.3K+ |

### ✅ Strengths

1. **Simplicity**
   - Very simple API
   - Easy to learn
   - Minimum code

2. **Good performance**
   - Faster Laravel/Symfony
   - Optimized

3. **Named routes**
   - Support for named routes
   - URL generation

### ⚠️ Weaknesses

1. **Limited functionality**
   - No middleware
   - No groups
   - No configuration files

2. **Small community**
   - Fewer examples
   - Less support
   - Less updates

3. **No security features**
   - No protection against attacks
   - No rate limiting
   - No IP filtering

### 🎯 Main features

- ✅ RESTful routing
- ✅ Named routes
- ✅ URL generation
- ✅ Route matching
- ❌ Route groups
- ❌ Middleware
- ❌ Rate limiting
- ❌ Route caching
- ❌ YAML/XML/JSON config

### 💡 When to use

- Simple projects
- When you need a lightweight router
- Legacy projects

---

## 📊 Comparison summary table

### Performance

| Router | Req/Sec | vs CloudCastle | Rating |
|:---|:---:|:---:|:---:|
| **CloudCastle** | **50,946** | **100%** | 🥇 |
| FastRoute | 47,033 | 92.3% | 🥈 |
| AltoRouter | 39,967 | 78.4% | 🥉 |
| Slim | 37,167 | 72.9% | 4 |
| Laravel | 16,233 | 31.9% | 5 |
| Symfony | 15,633 | 30.7% | 6 |

### Functionality (out of 25 features)

| Router | Quantity | Percent | Rating |
|:---|:---:|:---:|:---:|
| **CloudCastle** | **25** | **100%** | 🥇 |
| Symfony | 10 | 40% | 🥈 |
| Laravel | 9 | 36% | 🥉 |
| Slim | 7 | 28% | 4 |
| AltoRouter | 4 | 16% | 5 |
| FastRoute | 3 | 12% | 6 |

### Scalability

| Router | Max Routes | Memory | Rating |
|:---|:---:|:---:|:---:|
| **CloudCastle** | **1,095K** | **1.39 KB** | 🥇 |
| FastRoute | 500K | 2.1 KB | 🥈 |
| Slim | 200K | 4.8 KB | 🥉 |
| AltoRouter | 150K | 6.1 KB | 4 |
| Symfony | 100K | 8.5 KB | 5 |
| Laravel | 80K | 10.2 KB | 6 |

### Overall rating

| Place | Router | Prod. | Functional | Scale | Total |
|:---|:---:|:---:|:---:|:---:|:---:|
| 🥇 | **CloudCastle** | 10 | 10 | 10 | **30** |
| 🥈 | FastRoute | 9 | 3 | 9 | **21** |
| 🥉 | Slim | 7 | 7 | 7 | **21** |
| 4 | Symfony | 3 | 9 | 5 | **17** |
| 5 | AltoRouter | 8 | 4 | 6 | **18** |
| 6 | Laravel | 4 | 8 | 4 | **16** |

## 🎯 Recommendations for choosing

### Choose CloudCastle HTTP Router if:

- ✅ Need maximum performance
- ✅ Scalability required (1000+ routes)
- ✅ Application security is important
- ✅ Need rich functionality out of the box
- ✅ You use PHP 8.2+
- ✅ Build a modern application

### Choose FastRoute if:

- ✅ Only basic routing is needed
- ✅ Minimalism and speed are more important than functionality
- ✅ Legacy project in PHP 7.2+
- ✅ Micro project

### Choose Symfony Router if:

- ✅ Using Symfony framework
- ✅ You need a proven enterprise platform
- ✅ Performance is not critical
- ✅ Project maturity is important

### Choose Laravel Router if:

- ✅ Build on Laravel framework
- ✅ DX is more important than performance
- ✅ Small/medium project

### Choose Slim Router if:

- ✅ You need a PSR-15 lightweight router
- ✅ API-first project
- ✅ Microservices

### Choose AltoRouter if:

- ✅ Very simple project
- ✅ Minimum code required
- ✅ Legacy support

---

## 📈 Conclusions

**CloudCastle HTTP Router** is the best choice for modern PHP applications, combining:

1. **Maximum performance** (50K+ req/sec)
2. **Exceptional scalability** (1M+ routes)
3. **Comprehensive security** (13+ protections)
4. **Rich functionality** (25 features)
5. **Modern technologies** (PHP 8.2+, PSR-15)

The router is suitable for both small projects and enterprise applications, providing the best balance of performance, functionality and security on the market.

---

*Last update: October 18, 2025*

---

[📚 Table of Contents](_table-of-contents.md) | [🏠 Home](README.md)

---

[📚 Table of Contents](en/_table-of-contents.md) | [🏠 Home](en/README.md)
