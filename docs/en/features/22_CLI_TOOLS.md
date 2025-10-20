# CLI Tools

[**English**](22_CLI_TOOLS.md) | [Русский](../../ru/features/22_CLI_TOOLS.md) | [Deutsch](../../de/features/22_CLI_TOOLS.md) | [Français](../../fr/features/22_CLI_TOOLS.md) | [中文](../../zh/features/22_CLI_TOOLS.md)

---

## 📚 Documentation Navigation

[README](../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [API_REFERENCE](../API_REFERENCE.md) | [ALL_FEATURES](../ALL_FEATURES.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [PERFORMANCE](../PERFORMANCE_ANALYSIS.md) | [SECURITY](../SECURITY_REPORT.md) | [COMPARISON](../COMPARISON.md) | [FAQ](../FAQ.md)

**Detailed Documentation:** [01](01_BASIC_ROUTING.md) | [02](02_ROUTE_PARAMETERS.md) | [03](03_ROUTE_GROUPS.md) | [04](04_RATE_LIMITING.md) | [05](05_IP_FILTERING.md) | [06](06_MIDDLEWARE.md) | [07](07_NAMED_ROUTES.md) | [08](08_TAGS.md) | [09](09_HELPER_FUNCTIONS.md) | [10](10_ROUTE_SHORTCUTS.md) | [11](11_ROUTE_MACROS.md) | [12](12_URL_GENERATION.md) | [13](13_EXPRESSION_LANGUAGE.md) | [14](14_CACHING.md) | [15](15_PLUGINS.md) | [16](16_LOADERS.md) | [17](17_PSR_SUPPORT.md) | [18](18_ACTION_RESOLVER.md) | [19](19_STATISTICS.md) | [20](20_SECURITY.md) | [21](21_EXCEPTIONS.md) | [22](22_CLI_TOOLS.md)

---

**Category:** Development Tools  
**Number of Commands:** 3  
**Complexity:** ⭐ Beginner Level

---

## Description

CLI utilities for managing and analyzing routes from command line.

## Commands

### 1. routes-list

```bash
# All routes
php bin/routes-list

# Filter by method
php bin/routes-list --method=GET

# Filter by tag
php bin/routes-list --tag=api

# Filter by name
php bin/routes-list --name=users.*
```

### 2. routes-cache

```bash
# Compile routes
php bin/routes-cache

# Clear cache
php bin/routes-cache --clear

# Show cache info
php bin/routes-cache --info
```

### 3. routes-test

```bash
# Test route matching
php bin/routes-test /users/123 GET

# Test with headers
php bin/routes-test /api/data POST --header="X-API-Key: secret"
```

## Examples

```bash
# List all API routes
php bin/routes-list --tag=api

# List admin routes
php bin/routes-list --name=admin.*

# Cache routes for production
php bin/routes-cache

# Test login route
php bin/routes-test /login POST
```

## See Also

- [Statistics](19_STATISTICS.md) - Route statistics
- [Caching](14_CACHING.md) - Route caching
- [API Reference](../API_REFERENCE.md) - Complete API reference

---

© 2024 CloudCastle HTTP Router  
[⬆ Back to top](#cli-tools)