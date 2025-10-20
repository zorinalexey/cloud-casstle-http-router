# Загрузчики маршрутов

**Категория:** Конфигурация  
**Количество типов:** 5  
**Сложность:** ⭐⭐ Средний уровень

---

## Описание

Загрузчики позволяют определять маршруты в различных форматах (JSON, YAML, XML, PHP Attributes, PHP файлы) вместо программной регистрации.

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

**Версия:** 1.1.1  
**Статус:** ✅ Стабильная функциональность

