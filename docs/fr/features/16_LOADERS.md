# Загрузчики routeов

[English](../../en/features/16_LOADERS.md) | **Русский** | [Deutsch](../../de/features/16_LOADERS.md) | [Français](../../fr/features/16_LOADERS.md) | [中文](../../zh/features/16_LOADERS.md)

---







---

## 📚 Navigation de la Documentation

[README](../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [API_REFERENCE](../API_REFERENCE.md) | [ALL_FEATURES](../ALL_FEATURES.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [PERFORMANCE](../PERFORMANCE_ANALYSIS.md) | [SECURITY](../SECURITY_REPORT.md) | [COMPARISON](../COMPARISON.md) | [FAQ](../FAQ.md)

**Documentation détaillée:** [01](01_BASIC_ROUTING.md) | [02](02_ROUTE_PARAMETERS.md) | [03](03_ROUTE_GROUPS.md) | [04](04_RATE_LIMITING.md) | [05](05_IP_FILTERING.md) | [06](06_MIDDLEWARE.md) | [07](07_NAMED_ROUTES.md) | [08](08_TAGS.md) | [09](09_HELPER_FUNCTIONS.md) | [10](10_ROUTE_SHORTCUTS.md) | [11](11_ROUTE_MACROS.md) | [12](12_URL_GENERATION.md) | [13](13_EXPRESSION_LANGUAGE.md) | [14](14_CACHING.md) | [15](15_PLUGINS.md) | [16](16_LOADERS.md) | [17](17_PSR_SUPPORT.md) | [18](18_ACTION_RESOLVER.md) | [19](19_STATISTICS.md) | [20](20_SECURITY.md) | [21](21_EXCEPTIONS.md) | [22](22_CLI_TOOLS.md)

---


**Catégorie:** Конфигурация  
**Nombre de типов:** 5  
**Complexité:** ⭐⭐ Intermédiaire уровень

---

## Описание

Загрузчики позволяют определять routes в различных форматах (JSON, YAML, XML, PHP Attributes, PHP fichierы) вместо программной регистрации.

## Типы загрузчиков

### 1. JsonLoader

```php
use CloudCastle\Http\Router\Loader\JsonLoader;

$loader = new JsonLoader($router);
$loader->load(__DIR__ . '/routes.json');
```

**routes.json:**
```json
{
    "routes": [
        {
            "method": "GET",
            "uri": "/users",
            "action": "UserController@index",
            "name": "users.index"
        }
    ]
}
```

### 2. YamlLoader

```php
use CloudCastle\Http\Router\Loader\YamlLoader;

$loader = new YamlLoader($router);
$loader->load(__DIR__ . '/routes.yaml');
```

**routes.yaml:**
```yaml
routes:
  - method: GET
    uri: /users
    action: UserController@index
    name: users.index
```

### 3. XmlLoader

```php
use CloudCastle\Http\Router\Loader\XmlLoader;

$loader = new XmlLoader($router);
$loader->load(__DIR__ . '/routes.xml');
```

**routes.xml:**
```xml
<?xml version="1.0"?>
<routes>
    <route method="GET" uri="/users" action="UserController@index" name="users.index"/>
</routes>
```

### 4. AttributeLoader

```php
use CloudCastle\Http\Router\Loader\AttributeLoader;

$loader = new AttributeLoader($router);
$loader->loadFromDirectory(__DIR__ . '/app/Controllers');
```

**UserController.php:**
```php
use CloudCastle\Http\Router\Attributes\Route;

#[Route('/users', 'GET', name: 'users.index')]
class UserController
{
    #[Route('/users/{id}', 'GET', name: 'users.show')]
    public function show(int $id) { }
}
```

### 5. PHP Files

```php
// routes/web.php
Route::get('/users', [UserController::class, 'index']);

// index.php
require 'routes/web.php';
```

---

**Version:** 1.1.1  
**Статус:** ✅ Стабильная функциональность


---

## 📚 Navigation de la Documentation

[README](../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [API_REFERENCE](../API_REFERENCE.md) | [ALL_FEATURES](../ALL_FEATURES.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [FAQ](../FAQ.md)

**Documentation détaillée:** [01](01_BASIC_ROUTING.md) | [02](02_ROUTE_PARAMETERS.md) | [03](03_ROUTE_GROUPS.md) | [04](04_RATE_LIMITING.md) | [05](05_IP_FILTERING.md) | [06](06_MIDDLEWARE.md) | [07](07_NAMED_ROUTES.md) | [08](08_TAGS.md) | [09](09_HELPER_FUNCTIONS.md) | [10](10_ROUTE_SHORTCUTS.md) | [11](11_ROUTE_MACROS.md) | [12](12_URL_GENERATION.md) | [13](13_EXPRESSION_LANGUAGE.md) | [14](14_CACHING.md) | [15](15_PLUGINS.md) | [16](16_LOADERS.md) | [17](17_PSR_SUPPORT.md) | [18](18_ACTION_RESOLVER.md) | [19](19_STATISTICS.md) | [20](20_SECURITY.md) | [21](21_EXCEPTIONS.md) | [22](22_CLI_TOOLS.md)

**© 2024 CloudCastle HTTP Router**
