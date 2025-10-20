# Expression Language

---

## 📚 Navigation de la Documentation

[README](../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [API_REFERENCE](../API_REFERENCE.md) | [ALL_FEATURES](../ALL_FEATURES.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [PERFORMANCE](../PERFORMANCE_ANALYSIS.md) | [SECURITY](../SECURITY_REPORT.md) | [COMPARISON](../COMPARISON.md) | [FAQ](../FAQ.md)

**Documentation détaillée:** [01](01_BASIC_ROUTING.md) | [02](02_ROUTE_PARAMETERS.md) | [03](03_ROUTE_GROUPS.md) | [04](04_RATE_LIMITING.md) | [05](05_IP_FILTERING.md) | [06](06_MIDDLEWARE.md) | [07](07_NAMED_ROUTES.md) | [08](08_TAGS.md) | [09](09_HELPER_FUNCTIONS.md) | [10](10_ROUTE_SHORTCUTS.md) | [11](11_ROUTE_MACROS.md) | [12](12_URL_GENERATION.md) | [13](13_EXPRESSION_LANGUAGE.md) | [14](14_CACHING.md) | [15](15_PLUGINS.md) | [16](16_LOADERS.md) | [17](17_PSR_SUPPORT.md) | [18](18_ACTION_RESOLVER.md) | [19](19_STATISTICS.md) | [20](20_SECURITY.md) | [21](21_EXCEPTIONS.md) | [22](22_CLI_TOOLS.md)

---


**Catégorie:** surdansetchez danssursursuravecet  
**Nombre de sursursurdans:** 5  
**Complexité:** ⭐⭐⭐ Avancé chezsurdans

---

## etavecet

Expression Language pardanssur avecsurdans chezavecsurdanset pour routesurdans sur suravecsurdans dansetavec danset (IP, dans, sursurdansàet et ..).

## avecparsurdanset

### condition()

```php
// По IP
Route::get('/admin', $action)
    ->condition('request.ip == "192.168.1.1"');

// По времени
Route::get('/api/data', $action)
    ->condition('request.time > 9 and request.time < 18');

// По заголовкам
Route::get('/api/secure', $action)
    ->condition('request.header["X-API-Key"] == "secret"');
```

## sur

### danset

- `==` - danssur
- `!=` -  danssur
- `>` - sur
- `<` - 
- `>=` - sur etet danssur
- `<=` -  etet danssur

### suretavecàet

- `and` - 
- `or` - 

## ExpressionLanguage àavecavec

```php
use CloudCastle\Http\Router\ExpressionLanguage\ExpressionLanguage;

$expr = new ExpressionLanguage();

$result = $expr->evaluate('10 > 5 and 20 < 30', []);
// true

$result = $expr->evaluate('user.age >= 18', ['user' => ['age' => 25]]);
// true
```

## Exemples

```php
// Рабочие часы
Route::get('/api/business', $action)
    ->condition('request.time >= 9 and request.time <= 18');

// Только с определенных IP
Route::get('/internal', $action)
    ->condition('request.ip == "192.168.1.1" or request.ip == "10.0.0.1"');

// По User Agent
Route::get('/mobile', $action)
    ->condition('request.header["User-Agent"] contains "Mobile"');
```

---

**Version:** 1.1.1  
**chezavec:** ✅ àavecetsur chezàetsursursuravec


---

## 📚 Navigation de la Documentation

[README](../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [API_REFERENCE](../API_REFERENCE.md) | [ALL_FEATURES](../ALL_FEATURES.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [FAQ](../FAQ.md)

**Documentation détaillée:** [01](01_BASIC_ROUTING.md) | [02](02_ROUTE_PARAMETERS.md) | [03](03_ROUTE_GROUPS.md) | [04](04_RATE_LIMITING.md) | [05](05_IP_FILTERING.md) | [06](06_MIDDLEWARE.md) | [07](07_NAMED_ROUTES.md) | [08](08_TAGS.md) | [09](09_HELPER_FUNCTIONS.md) | [10](10_ROUTE_SHORTCUTS.md) | [11](11_ROUTE_MACROS.md) | [12](12_URL_GENERATION.md) | [13](13_EXPRESSION_LANGUAGE.md) | [14](14_CACHING.md) | [15](15_PLUGINS.md) | [16](16_LOADERS.md) | [17](17_PSR_SUPPORT.md) | [18](18_ACTION_RESOLVER.md) | [19](19_STATISTICS.md) | [20](20_SECURITY.md) | [21](21_EXCEPTIONS.md) | [22](22_CLI_TOOLS.md)

**© 2024 CloudCastle HTTP Router**
