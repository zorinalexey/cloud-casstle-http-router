# Security

[**English**](20_SECURITY.md) | [Русский](../../ru/features/20_SECURITY.md) | [Deutsch](../../de/features/20_SECURITY.md) | [Français](../../fr/features/20_SECURITY.md) | [中文](../../zh/features/20_SECURITY.md)

---

## 📚 Documentation Navigation

[README](../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [API_REFERENCE](../API_REFERENCE.md) | [ALL_FEATURES](../ALL_FEATURES.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [PERFORMANCE](../PERFORMANCE_ANALYSIS.md) | [SECURITY](../SECURITY_REPORT.md) | [COMPARISON](../COMPARISON.md) | [FAQ](../FAQ.md)

**Detailed Documentation:** [01](01_BASIC_ROUTING.md) | [02](02_ROUTE_PARAMETERS.md) | [03](03_ROUTE_GROUPS.md) | [04](04_RATE_LIMITING.md) | [05](05_IP_FILTERING.md) | [06](06_MIDDLEWARE.md) | [07](07_NAMED_ROUTES.md) | [08](08_TAGS.md) | [09](09_HELPER_FUNCTIONS.md) | [10](10_ROUTE_SHORTCUTS.md) | [11](11_ROUTE_MACROS.md) | [12](12_URL_GENERATION.md) | [13](13_EXPRESSION_LANGUAGE.md) | [14](14_CACHING.md) | [15](15_PLUGINS.md) | [16](16_LOADERS.md) | [17](17_PSR_SUPPORT.md) | [18](18_ACTION_RESOLVER.md) | [19](19_STATISTICS.md) | [20](20_SECURITY.md) | [21](21_EXCEPTIONS.md) | [22](22_CLI_TOOLS.md)

---

**Category:** Application Protection  
**Number of Mechanisms:** 12  
**Complexity:** ⭐⭐⭐ Critical

---

## Built-in Security Mechanisms

### 1. Rate Limiting
```php
Route::post('/login', $action)->throttle(5, 1);
```

### 2. Auto-Ban System
```php
Route::post('/login', $action)->autoBan(10, 60);
```

### 3. IP Filtering
```php
Route::get('/admin', $action)->whitelistIp(['192.168.1.0/24']);
```

### 4-12. Other Security Features
- HTTPS enforcement
- CSRF protection
- XSS protection
- SQL injection prevention
- Input validation
- Security headers
- IP spoofing protection
- Request signature verification
- Encryption support

## See Also

- [Rate Limiting](04_RATE_LIMITING.md) - Rate limiting and auto-ban
- [IP Filtering](05_IP_FILTERING.md) - IP-based access control
- [Security Report](../SECURITY_REPORT.md) - Security analysis
- [API Reference](../API_REFERENCE.md) - Complete API reference

---

© 2024 CloudCastle HTTP Router  
[⬆ Back to top](#security)