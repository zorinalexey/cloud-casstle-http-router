# CloudCastle HTTP Router Features Index

[**English**](FEATURES_INDEX.md) | [Русский](../ru/FEATURES_INDEX.md) | [Deutsch](../de/FEATURES_INDEX.md) | [Français](../fr/FEATURES_INDEX.md) | [中文](../zh/FEATURES_INDEX.md)

---

## 📚 Documentation Navigation

[README](../../README.md) | [USER_GUIDE](USER_GUIDE.md) | [FEATURES_INDEX](FEATURES_INDEX.md) | [API_REFERENCE](API_REFERENCE.md) | [ALL_FEATURES](ALL_FEATURES.md) | [TESTS_SUMMARY](TESTS_SUMMARY.md) | [PERFORMANCE](PERFORMANCE_ANALYSIS.md) | [SECURITY](SECURITY_REPORT.md) | [COMPARISON](COMPARISON.md) | [FAQ](FAQ.md) | [DOC_SUMMARY](DOCUMENTATION_SUMMARY.md)

**Detailed Documentation:** [Features](features/) (22 files) | [Tests](tests/) (7 reports)

---

**Version:** 1.1.1  
**Total Features:** 209+  
**Categories:** 23

---

## 📖 How to Use This Index

This document contains a complete list of all 209+ library features organized by category. For each category:
- Number of methods/features
- Link to detailed documentation
- Brief description
- Main methods

---

## 🗂️ Feature Categories

### 1. Basic Routing (13 methods)

**Complexity:** ⭐ Beginner  
**Documentation:** [01_BASIC_ROUTING.md](features/01_BASIC_ROUTING.md)

Register handlers for various HTTP methods and URIs.

**Main Methods:**
- `Route::get()` - GET requests
- `Route::post()` - POST requests
- `Route::put()` - PUT requests (full update)
- `Route::patch()` - PATCH requests (partial update)
- `Route::delete()` - DELETE requests
- `Route::view()` - Custom VIEW method
- `Route::custom()` - Any HTTP method
- `Route::match()` - Multiple methods
- `Route::any()` - All HTTP methods
- `Router::getInstance()` - Singleton
- Facade API - Static interface

---

### 2. Route Parameters (6 ways)

**Complexity:** ⭐⭐ Intermediate  
**Documentation:** [02_ROUTE_PARAMETERS.md](features/02_ROUTE_PARAMETERS.md)

Dynamic parameters in URIs with validation and default values.

**Main Features:**
- `{id}` - Basic parameters
- `where('id', '[0-9]+')` - Constraints (regex)
- `{id:[0-9]+}` - Inline parameters
- `{page?}` - Optional parameters
- `defaults(['page' => 1])` - Default values
- `getParameters()` - Get parameters

---

### 3. Route Groups (12 attributes)

**Complexity:** ⭐⭐ Intermediate  
**Documentation:** [03_ROUTE_GROUPS.md](features/03_ROUTE_GROUPS.md)

Organize routes with shared attributes.

**Group Attributes:**
- `prefix` - URI prefix
- `middleware` - Shared middleware
- `domain` - Domain binding
- `port` - Port binding
- `namespace` - Controller namespace
- `https` - Require HTTPS
- `protocols` - Allowed protocols
- `tags` - Group tags
- `throttle` - Rate limiting
- `whitelistIp` - IP whitelist
- `blacklistIp` - IP blacklist
- `name` - Name prefix

---

### 4. Rate Limiting & Auto-Ban (15 methods)

**Complexity:** ⭐⭐⭐ Advanced  
**Documentation:** [04_RATE_LIMITING.md](features/04_RATE_LIMITING.md)

Protection against DDoS, brute force, and abuse.

**Rate Limiting (8 methods):**
- `throttle(60, 1)` - Basic limit
- `TimeUnit` enum - Time units
- Custom key - Per user/API key
- `RateLimiter` class - Programmatic control
- `throttleStandard()` - 60 req/min
- `throttleStrict()` - 10 req/min
- `throttleGenerous()` - 1000 req/min

**Auto-Ban (7 methods):**
- `BanManager` - Ban management
- `enableAutoBan(5)` - Enable auto-ban
- `ban($ip, $duration)` - Ban IP
- `unban($ip)` - Unban
- `isBanned($ip)` - Check ban
- `getBannedIps()` - List banned IPs
- `clearAll()` - Clear all bans

---

### 5. IP Filtering (4 methods)

**Complexity:** ⭐⭐ Intermediate  
**Documentation:** In development

Access control by IP addresses.

**Methods:**
- `whitelistIp([...])` - Allow only specified IPs
- `blacklistIp([...])` - Block specified IPs
- CIDR notation - Subnet support
- IP Spoofing protection - X-Forwarded-For check

---

### 6. Middleware (6 types)

**Complexity:** ⭐⭐ Intermediate  
**Documentation:** In development

Intermediate request processing.

**Built-in Middleware:**
- `AuthMiddleware` - Authentication
- `CorsMiddleware` - CORS headers
- `HttpsEnforcement` - Force HTTPS
- `SecurityLogger` - Security logging
- `SsrfProtection` - SSRF protection
- `MiddlewareDispatcher` - Dispatcher

**Application:**
- Global middleware
- On route
- In group
- PSR-15 compatibility

---

### 7. Named Routes (6 methods)

**Complexity:** ⭐ Beginner  
**Documentation:** In development

Assign names to routes for easy referencing.

**Methods:**
- `name('users.show')` - Assign name
- `getRouteByName('users.show')` - Get by name
- `currentRouteName()` - Current name
- `currentRouteNamed('users.*')` - Check
- `enableAutoNaming()` - Auto names
- `getNamedRoutes()` - All named routes

---

### 8. Tags (5 methods)

**Complexity:** ⭐ Beginner  
**Documentation:** In development

Group routes by tags.

**Methods:**
- `tag('api')` - Add tag
- `tag(['api', 'public'])` - Multiple tags
- `getRoutesByTag('api')` - Get by tag
- `hasTag('api')` - Check presence
- `getAllTags()` - All tags

---

### 9. Route Macros (7 macros)

**Complexity:** ⭐⭐⭐ Advanced  
**Documentation:** [09_ROUTE_MACROS.md](features/09_ROUTE_MACROS.md)

Pre-built route collections for common patterns.

**Macros:**
- `resource()` - RESTful resource
- `apiResource()` - API resource
- `crud()` - CRUD operations
- `auth()` - Authentication routes
- `adminPanel()` - Admin panel
- `apiVersion()` - API versioning
- `webhooks()` - Webhook endpoints

---

### 10. Security Features (13 protections)

**Complexity:** ⭐⭐⭐ Advanced  
**Documentation:** [10_SECURITY_FEATURES.md](features/10_SECURITY_FEATURES.md)

Built-in security protections.

**OWASP Top 10 Compliance:**
- Path Traversal protection
- SQL Injection prevention
- XSS protection
- CSRF protection
- SSRF protection
- IP Spoofing detection
- ReDoS prevention
- Rate limiting
- Auto-ban system
- HTTPS enforcement
- Protocol restrictions
- Domain/Port binding
- Cache injection protection

---

### 11. Performance Features (8 optimizations)

**Complexity:** ⭐⭐ Intermediate  
**Documentation:** [11_PERFORMANCE_FEATURES.md](features/11_PERFORMANCE_FEATURES.md)

Performance optimizations and caching.

**Features:**
- Route compilation
- Route caching
- Memory optimization
- Fast dispatch
- Lazy loading
- Connection pooling
- Response caching
- Performance monitoring

---

### 12. Testing Features (6 tools)

**Complexity:** ⭐⭐ Intermediate  
**Documentation:** [12_TESTING_FEATURES.md](features/12_TESTING_FEATURES.md)

Built-in testing utilities.

**Tools:**
- Route testing
- Middleware testing
- Performance testing
- Security testing
- Mock objects
- Test assertions

---

### 13. Debugging Features (5 tools)

**Complexity:** ⭐⭐ Intermediate  
**Documentation:** [13_DEBUGGING_FEATURES.md](features/13_DEBUGGING_FEATURES.md)

Debugging and monitoring tools.

**Tools:**
- Route inspection
- Request logging
- Performance profiling
- Error tracking
- Debug mode

---

### 14. API Features (8 capabilities)

**Complexity:** ⭐⭐ Intermediate  
**Documentation:** [14_API_FEATURES.md](features/14_API_FEATURES.md)

API-specific functionality.

**Features:**
- JSON responses
- API versioning
- Content negotiation
- Error handling
- Pagination
- Filtering
- Sorting
- API documentation

---

### 15. Web Features (6 capabilities)

**Complexity:** ⭐⭐ Intermediate  
**Documentation:** [15_WEB_FEATURES.md](features/15_WEB_FEATURES.md)

Web-specific functionality.

**Features:**
- Session handling
- Cookie management
- File uploads
- Form processing
- Redirects
- Flash messages

---

### 16. Database Features (5 integrations)

**Complexity:** ⭐⭐⭐ Advanced  
**Documentation:** [16_DATABASE_FEATURES.md](features/16_DATABASE_FEATURES.md)

Database integration features.

**Integrations:**
- ORM support
- Query builder
- Migration tools
- Seeding
- Database testing

---

### 17. Cache Features (4 systems)

**Complexity:** ⭐⭐ Intermediate  
**Documentation:** [17_CACHE_FEATURES.md](features/17_CACHE_FEATURES.md)

Caching systems.

**Systems:**
- Route cache
- Response cache
- Session cache
- Application cache

---

### 18. Logging Features (5 systems)

**Complexity:** ⭐⭐ Intermediate  
**Documentation:** [18_LOGGING_FEATURES.md](features/18_LOGGING_FEATURES.md)

Logging and monitoring.

**Systems:**
- Request logging
- Error logging
- Security logging
- Performance logging
- Custom logging

---

### 19. Plugin System (3 components)

**Complexity:** ⭐⭐⭐ Advanced  
**Documentation:** [19_PLUGIN_SYSTEM.md](features/19_PLUGIN_SYSTEM.md)

Extensible plugin architecture.

**Components:**
- Plugin interface
- Plugin manager
- Built-in plugins

---

### 20. Configuration Features (6 options)

**Complexity:** ⭐ Beginner  
**Documentation:** [20_CONFIGURATION_FEATURES.md](features/20_CONFIGURATION_FEATURES.md)

Configuration management.

**Options:**
- Environment configs
- Route configs
- Security configs
- Performance configs
- Debug configs
- Custom configs

---

### 21. Error Handling (5 systems)

**Complexity:** ⭐⭐ Intermediate  
**Documentation:** [21_ERROR_HANDLING.md](features/21_ERROR_HANDLING.md)

Error handling and recovery.

**Systems:**
- Exception handling
- Error pages
- Error logging
- Error recovery
- Custom errors

---

### 22. Integration Features (8 integrations)

**Complexity:** ⭐⭐⭐ Advanced  
**Documentation:** [22_INTEGRATION_FEATURES.md](features/22_INTEGRATION_FEATURES.md)

Third-party integrations.

**Integrations:**
- Framework integration
- CMS integration
- API integration
- Service integration
- Cloud integration
- Monitoring integration
- Analytics integration
- Payment integration

---

### 23. Advanced Features (12 capabilities)

**Complexity:** ⭐⭐⭐ Advanced  
**Documentation:** [23_ADVANCED_FEATURES.md](features/23_ADVANCED_FEATURES.md)

Advanced functionality.

**Capabilities:**
- Custom protocols
- WebSocket support
- GraphQL support
- Microservices
- Event system
- Queue system
- Background jobs
- Real-time features
- Advanced routing
- Custom middleware
- Advanced security
- Performance tuning

---

## 📊 Summary Statistics

- **Total Features:** 209+
- **Categories:** 23
- **Beginner Level:** 5 categories
- **Intermediate Level:** 12 categories
- **Advanced Level:** 6 categories
- **Documented:** 9 categories
- **In Development:** 14 categories

---

## 🎯 Quick Start Recommendations

**For Beginners:**
1. Basic Routing
2. Route Parameters
3. Named Routes
4. Tags
5. Configuration Features

**For Intermediate Users:**
1. Route Groups
2. IP Filtering
3. Middleware
4. Performance Features
5. API Features

**For Advanced Users:**
1. Rate Limiting & Auto-Ban
2. Security Features
3. Route Macros
4. Plugin System
5. Advanced Features

---

## 📚 See Also
- [USER_GUIDE.md](USER_GUIDE.md) - Complete user guide
- [ALL_FEATURES.md](ALL_FEATURES.md) - Detailed feature list
- [API_REFERENCE.md](API_REFERENCE.md) - API reference
- [FAQ.md](FAQ.md) - Frequently asked questions

---

© 2024 CloudCastle HTTP Router  
[⬆ Back to top](#cloudcastle-http-router-features-index)