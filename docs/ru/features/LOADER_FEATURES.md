# Route Loaders - Детальное описание загрузчиков маршрутов

[English](../../en/features/LOADER_FEATURES.md) | **Русский** | [Deutsch](../../de/features/LOADER_FEATURES.md) | [Français](../../fr/features/LOADER_FEATURES.md) | [中文](../../zh/features/LOADER_FEATURES.md)

---

## Содержание

- [JSON Loader](#json-loader)
- [YAML Loader](#yaml-loader)
- [XML Loader](#xml-loader)
- [Attributes Loader](#attributes-loader)
- [Сравнение форматов](#сравнение-форматов)

---

## JSON Loader

### Описание

Загрузка маршрутов из JSON файлов.

### Формат

```json
{
  "routes": [
    {
      "method": "GET",
      "uri": "/users",
      "action": "UserController@index",
      "name": "users.index",
      "middleware": ["auth"]
    },
    {
      "method": "POST",
      "uri": "/users",
      "action": "UserController@store",
      "name": "users.store"
    },
    {
      "method": "GET",
      "uri": "/users/{id}",
      "action": "UserController@show",
      "name": "users.show"
    }
  ],
  "groups": [
    {
      "prefix": "/api",
      "middleware": ["api"],
      "routes": [
        {
          "method": "GET",
          "uri": "/posts",
          "action": "PostController@index"
        }
      ]
    }
  ]
}
```

### Использование

```php
use CloudCastle\Http\Router\Loader\JsonLoader;

$loader = new JsonLoader($router);
$loader->load('/path/to/routes.json');
```

---

## YAML Loader

### Формат

```yaml
routes:
  - method: GET
    uri: /users
    action: UserController@index
    name: users.index
    middleware: [auth]
    
  - method: POST
    uri: /users
    action: UserController@store
    name: users.store

groups:
  - prefix: /api
    middleware: [api]
    routes:
      - method: GET
        uri: /posts
        action: PostController@index
```

### Использование

```php
use CloudCastle\Http\Router\Loader\YamlLoader;

$loader = new YamlLoader($router);
$loader->load('/path/to/routes.yaml');
```

---

## XML Loader

### Формат

```xml
<?xml version="1.0" encoding="UTF-8"?>
<routes>
    <route method="GET" uri="/users" action="UserController@index" name="users.index">
        <middleware>auth</middleware>
    </route>
    
    <route method="POST" uri="/users" action="UserController@store" name="users.store" />
    
    <group prefix="/api">
        <middleware>api</middleware>
        <route method="GET" uri="/posts" action="PostController@index" />
    </group>
</routes>
```

### Использование

```php
use CloudCastle\Http\Router\Loader\XmlLoader;

$loader = new XmlLoader($router);
$loader->load('/path/to/routes.xml');
```

---

## Attributes Loader

### Описание

Загрузка маршрутов из PHP атрибутов (PHP 8+).

### Использование

```php
use CloudCastle\Http\Router\Attributes\Route;
use CloudCastle\Http\Router\Attributes\RouteGroup;
use CloudCastle\Http\Router\Attributes\Middleware;

#[RouteGroup(prefix: '/api', middleware: ['api'])]
class UserController
{
    #[Route('GET', '/users', name: 'users.index')]
    #[Middleware(['auth'])]
    public function index() {
        // ...
    }
    
    #[Route('POST', '/users', name: 'users.store')]
    public function store() {
        // ...
    }
    
    #[Route('GET', '/users/{id}', name: 'users.show')]
    public function show($id) {
        // ...
    }
}
```

### Загрузка

```php
use CloudCastle\Http\Router\Loader\AttributeLoader;

$loader = new AttributeLoader($router);

// Из одного класса
$loader->loadClass(UserController::class);

// Из директории
$loader->loadDirectory('/path/to/controllers');

// Из namespace
$loader->loadNamespace('App\\Controllers');
```

---

## Сравнение форматов

| Формат | Читаемость | Размер | IDE поддержка | Валидация | Оценка |
|--------|-----------|--------|---------------|-----------|--------|
| **PHP** | ⭐⭐⭐ | Средний | ✅ Отличная | ✅ | **⭐⭐⭐⭐⭐** |
| **Attributes** | ⭐⭐⭐⭐ | Малый | ✅ Отличная | ✅ | **⭐⭐⭐⭐⭐** |
| **JSON** | ⭐⭐⭐⭐ | Малый | ✅ Хорошая | ✅ | **⭐⭐⭐⭐** |
| **YAML** | ⭐⭐⭐⭐⭐ | Малый | ⚠️ Средняя | ⚠️ | **⭐⭐⭐⭐** |
| **XML** | ⭐⭐ | Большой | ✅ Хорошая | ✅ | **⭐⭐⭐** |

### Когда использовать

**PHP (стандарт):**
- Для большинства проектов
- Максимальная гибкость
- Лучшая производительность

**Attributes:**
- Современные проекты (PHP 8+)
- Маршруты рядом с контроллерами
- Удобная организация

**JSON:**
- API генерация
- Конфигурация
- Интеграции

**YAML:**
- Простые конфигурации
- Наглядность
- Microservices

**XML:**
- Legacy проекты
- Enterprise стандарты
- Строгая валидация

---

## Сравнение с аналогами

| Роутер | JSON | YAML | XML | Attributes | Оценка |
|--------|------|------|-----|------------|--------|
| **CloudCastle** | ✅ | ✅ | ✅ | ✅ | **⭐⭐⭐⭐⭐** |
| Laravel | ❌ | ❌ | ❌ | ⚠️ Частично | ⭐⭐⭐ |
| Symfony | ✅ | ✅ | ✅ | ✅ | **⭐⭐⭐⭐⭐** |
| FastRoute | ❌ | ❌ | ❌ | ❌ | ⭐ |
| Slim | ❌ | ❌ | ❌ | ❌ | ⭐ |

**CloudCastle = Symfony** по количеству форматов!

---

## Заключение

**CloudCastle поддерживает ВСЕ популярные форматы:**

✅ PHP (стандарт)  
✅ Attributes (PHP 8+)  
✅ JSON  
✅ YAML  
✅ XML  

**Рекомендация:** 
- Используйте Attributes для новых проектов
- PHP для максимальной гибкости
- JSON/YAML для конфигураций

---

[⬆ Наверх](#route-loaders---детальное-описание-загрузчиков-маршрутов) | [📚 Все фичи](../ALL_FEATURES.md) | [🏠 Главная](../../../README.md)

© 2024 CloudCastle HTTP Router
