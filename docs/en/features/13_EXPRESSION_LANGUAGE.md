# Expression Language

**English** | [Русский](../../ru/features/13_EXPRESSION_LANGUAGE.md) | [Deutsch](../../de/features/13_EXPRESSION_LANGUAGE.md) | [Français](../../fr/features/13_EXPRESSION_LANGUAGE.md) | [中文](../../zh/features/13_EXPRESSION_LANGUAGE.md)

---







---

## 📚 Documentation Navigation

[README](../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [API_REFERENCE](../API_REFERENCE.md) | [ALL_FEATURES](../ALL_FEATURES.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [PERFORMANCE](../PERFORMANCE_ANALYSIS.md) | [SECURITY](../SECURITY_REPORT.md) | [COMPARISON](../COMPARISON.md) | [FAQ](../FAQ.md)

**Detailed documentation:** [01](01_BASIC_ROUTING.md) | [02](02_ROUTE_PARAMETERS.md) | [03](03_ROUTE_GROUPS.md) | [04](04_RATE_LIMITING.md) | [05](05_IP_FILTERING.md) | [06](06_MIDDLEWARE.md) | [07](07_NAMED_ROUTES.md) | [08](08_TAGS.md) | [09](09_HELPER_FUNCTIONS.md) | [10](10_ROUTE_SHORTCUTS.md) | [11](11_ROUTE_MACROS.md) | [12](12_URL_GENERATION.md) | [13](13_EXPRESSION_LANGUAGE.md) | [14](14_CACHING.md) | [15](15_PLUGINS.md) | [16](16_LOADERS.md) | [17](17_PSR_SUPPORT.md) | [18](18_ACTION_RESOLVER.md) | [19](19_STATISTICS.md) | [20](20_SECURITY.md) | [21](21_EXCEPTIONS.md) | [22](22_CLI_TOOLS.md)

---


**Category:** Прод in  and нутые  in озможно with т and   
**Number of операторо in :** 5  
**Complexity:** ⭐⭐⭐ Advanced уро in ень

---

## Оп and  with ан and е

Expression Language  by з in оляет  with озда in ать у with ло in  and я  for  routeо in   on  о with но in е  in ыч and  with ляемых  in ыражен and й (IP,  in ремя, заголо in к and   and  т.д.).

## И with  by льзо in ан and е

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

## Операторы

### Сра in нен and я

- `==` - Ра in но
- `!=` - Не ра in но
- `>` - Больше
- `<` - Меньше
- `>=` - Больше  or  ра in но
- `<=` - Меньше  or  ра in но

### Лог and че with к and е

- `and` - И
- `or` - ИЛИ

## ExpressionLanguage кла with  with 

```php
use CloudCastle\Http\Router\ExpressionLanguage\ExpressionLanguage;

$expr = new ExpressionLanguage();

$result = $expr->evaluate('10 > 5 and 20 < 30', []);
// true

$result = $expr->evaluate('user.age >= 18', ['user' => ['age' => 25]]);
// true
```

## Examples

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
**Стату with :** ✅ Эк with пер and менталь on я функц and о on льно with ть


---

## 📚 Documentation Navigation

[README](../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [API_REFERENCE](../API_REFERENCE.md) | [ALL_FEATURES](../ALL_FEATURES.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [FAQ](../FAQ.md)

**Detailed documentation:** [01](01_BASIC_ROUTING.md) | [02](02_ROUTE_PARAMETERS.md) | [03](03_ROUTE_GROUPS.md) | [04](04_RATE_LIMITING.md) | [05](05_IP_FILTERING.md) | [06](06_MIDDLEWARE.md) | [07](07_NAMED_ROUTES.md) | [08](08_TAGS.md) | [09](09_HELPER_FUNCTIONS.md) | [10](10_ROUTE_SHORTCUTS.md) | [11](11_ROUTE_MACROS.md) | [12](12_URL_GENERATION.md) | [13](13_EXPRESSION_LANGUAGE.md) | [14](14_CACHING.md) | [15](15_PLUGINS.md) | [16](16_LOADERS.md) | [17](17_PSR_SUPPORT.md) | [18](18_ACTION_RESOLVER.md) | [19](19_STATISTICS.md) | [20](20_SECURITY.md) | [21](21_EXCEPTIONS.md) | [22](22_CLI_TOOLS.md)

**© 2024 CloudCastle HTTP Router**
