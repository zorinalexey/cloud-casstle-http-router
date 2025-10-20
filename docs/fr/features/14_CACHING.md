# Mise en Cache routeо dans 

[English](../../en/features/14_CACHING.md) | [Русский](../../ru/features/14_CACHING.md) | [Deutsch](../../de/features/14_CACHING.md) | **Français** | [中文](../../zh/features/14_CACHING.md)

---







---

## 📚 Navigation de la Documentation

[README](../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [API_REFERENCE](../API_REFERENCE.md) | [ALL_FEATURES](../ALL_FEATURES.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [PERFORMANCE](../PERFORMANCE_ANALYSIS.md) | [SECURITY](../SECURITY_REPORT.md) | [COMPARISON](../COMPARISON.md) | [FAQ](../FAQ.md)

**Documentation détaillée:** [01](01_BASIC_ROUTING.md) | [02](02_ROUTE_PARAMETERS.md) | [03](03_ROUTE_GROUPS.md) | [04](04_RATE_LIMITING.md) | [05](05_IP_FILTERING.md) | [06](06_MIDDLEWARE.md) | [07](07_NAMED_ROUTES.md) | [08](08_TAGS.md) | [09](09_HELPER_FUNCTIONS.md) | [10](10_ROUTE_SHORTCUTS.md) | [11](11_ROUTE_MACROS.md) | [12](12_URL_GENERATION.md) | [13](13_EXPRESSION_LANGUAGE.md) | [14](14_CACHING.md) | [15](15_PLUGINS.md) | [16](16_LOADERS.md) | [17](17_PSR_SUPPORT.md) | [18](18_ACTION_RESOLVER.md) | [19](19_STATISTICS.md) | [20](20_SECURITY.md) | [21](21_EXCEPTIONS.md) | [22](22_CLI_TOOLS.md)

---


**Catégorie:** Performance  
**Nombre de méthodes:** 6  
**Complexité:** ⭐⭐ Intermédiaire уро dans ень

---

## Оп et  avec ан et е

Mise en Cache  par з dans оляет комп ou ро dans ать routes  dans  опт et м et з et ро dans анный формат  et  загружать  et х мгно dans енно, у avec коряя  et н et ц et ал et зац et ю пр et ложен et я  dans  де avec ятк et  раз.

## Méthodes

### 1. enableCache()

```php
// Включить кеш в директории
$router->enableCache('/var/cache/routes');
Route::enableCache('cache/routes');
```

### 2. compile()

```php
// Компилировать маршруты
$router->compile();

// Принудительная компиляция
$router->compile(force: true);
```

### 3. loadFromCache()

```php
if ($router->loadFromCache()) {
    echo "Loaded from cache";
} else {
    // Регистрируем маршруты
    require 'routes/web.php';
    $router->compile();
}
```

### 4. clearCache()

```php
$router->clearCache();
```

### 5. autoCompile()

```php
$router->autoCompile();
// Автоматически компилирует при изменениях
```

### 6. isCacheLoaded()

```php
if ($router->isCacheLoaded()) {
    echo "Cache loaded";
}
```

## Performance

**Без кеша:** ~10-50ms  et н et ц et ал et зац et я  
**С кешем:** ~0.1-1ms  et н et ц et ал et зац et я  
**У avec корен et е:** 10-50x

---

**Version:** 1.1.1  
**Стату avec :** ✅ Production-ready


---

## 📚 Navigation de la Documentation

[README](../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [API_REFERENCE](../API_REFERENCE.md) | [ALL_FEATURES](../ALL_FEATURES.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [FAQ](../FAQ.md)

**Documentation détaillée:** [01](01_BASIC_ROUTING.md) | [02](02_ROUTE_PARAMETERS.md) | [03](03_ROUTE_GROUPS.md) | [04](04_RATE_LIMITING.md) | [05](05_IP_FILTERING.md) | [06](06_MIDDLEWARE.md) | [07](07_NAMED_ROUTES.md) | [08](08_TAGS.md) | [09](09_HELPER_FUNCTIONS.md) | [10](10_ROUTE_SHORTCUTS.md) | [11](11_ROUTE_MACROS.md) | [12](12_URL_GENERATION.md) | [13](13_EXPRESSION_LANGUAGE.md) | [14](14_CACHING.md) | [15](15_PLUGINS.md) | [16](16_LOADERS.md) | [17](17_PSR_SUPPORT.md) | [18](18_ACTION_RESOLVER.md) | [19](19_STATISTICS.md) | [20](20_SECURITY.md) | [21](21_EXCEPTIONS.md) | [22](22_CLI_TOOLS.md)

**© 2024 CloudCastle HTTP Router**
