[🇷🇺 Русский](ru/loaders.md) | [🇺🇸 English](en/loaders.md) | [🇩🇪 Deutsch](de/loaders.md) | [🇫🇷 Français](fr/loaders.md) | [🇨🇳 中文](zh/loaders.md)

[📚 Table of Contents](zh/_table-of-contents.md) | [🏠 Home](zh/README.md)

---

# Route Loaders - Системы загрузки маршрутов

**Languages:** 🇷🇺 Русский | [🇬🇧 English](../en/loaders.md) | [🇩🇪 Deutsch](../de/loaders.md) | [🇫🇷 Français](../fr/loaders.md) | [🇨🇳 中文](../zh/loaders.md)

[📚 Оглавление](_table-of-contents.md) | [🏠 Главная](README.md)

---

## 📚 Обзор

CloudCastle HTTP Router поддерживает **5 способов** конфигурации маршрутов:
1. PHP (программная конфигурация)
2. YAML файлы
3. XML файлы
4. JSON файлы
5. PHP 8 Attributes

## 🎯 1. PHP Configuration (Программная)

### Преимущества
- ✅ Полный контроль
- ✅ IDE autocomplete
- ✅ Type safety
- ✅ Dynamic routing

### Примеры

```php
use CloudCastle\Http\Router\Router;

$router = new Router();

// Простые маршруты
$router->get('/', 'HomeController@index');
$router->post('/users', 'UserController@store');

// С middleware
$router->get('/admin', 'AdminController@index')
    ->middleware(['auth', 'admin']);

// С группами
$router->group(['prefix' => '/api/v1'], function($router) {
    $router->get('/users', 'UserController@index');
    $router->get('/posts', 'PostController@index');
});

// С условиями
$router->get('/premium', 'PremiumController@index')
    ->condition('user.subscription == "premium"')
    ->middleware('auth');

// Динамические маршруты
foreach ($modules as $module) {
    $router->get("/{$module->slug}", "{$module->controller}@index");
}
```

### Когда использовать
- Small to medium projects
- Когда нужна динамическая маршрутизация
- Когда важен type safety
- Для прототипирования

---

## 📄 2. YAML Configuration

### Преимущества
- ✅ Декларативный стиль
- ✅ Легко читать и редактировать
- ✅ Version control friendly
- ✅ Подходит для больших проектов

### Установка

```bash
# YAML extension required
pecl install yaml
```

### Базовая структура

```yaml
# config/routes.yaml

# Простой маршрут
home:
  path: /
  methods: GET
  controller: HomeController::index

# Маршрут с параметрами
users_show:
  path: /users/{id}
  methods: [GET, POST]
  controller: UserController::show

# С middleware
admin_dashboard:
  path: /admin/dashboard
  methods: GET
  controller: AdminController::dashboard
  middleware: [auth, admin]

# С requirements (regex)
user_profile:
  path: /users/{id}
  methods: GET
  controller: UserController::profile
  requirements:
    id: \d+

# С defaults
blog_page:
  path: /blog/{page}
  methods: GET
  controller: BlogController@index
  defaults:
    page: 1
  requirements:
    page: \d+

# С domain
api_data:
  path: /data
  methods: GET
  controller: ApiController::data
  domain: api.example.com

# С throttle (rate limiting)
api_limited:
  path: /api/limited
  methods: POST
  controller: ApiController::limited
  throttle:
    max: 100
    decay: 60

# Комплексный пример
api_v1_users:
  path: /api/v1/users/{id}
  methods: [GET, POST, PUT, DELETE]
  name: api.v1.users
  controller: Api\V1\UserController::handle
  middleware: [cors, auth, rate-limit]
  domain: api.example.com
  requirements:
    id: \d+
  defaults:
    id: null
  throttle:
    max: 1000
    decay: 60
```

### Загрузка

```php
use CloudCastle\Http\Router\Loader\YamlLoader;

$loader = new YamlLoader($router);
$loader->load(__DIR__ . '/config/routes.yaml');
```

### Модульная загрузка

```php
// Загрузка по модулям
$loader = new YamlLoader($router);

$loader->load(__DIR__ . '/config/routes/web.yaml');
$loader->load(__DIR__ . '/config/routes/api.yaml');
$loader->load(__DIR__ . '/config/routes/admin.yaml');
```

### Условная загрузка

```php
$loader = new YamlLoader($router);

// В зависимости от окружения
if ($env === 'development') {
    $loader->load(__DIR__ . '/config/routes/dev.yaml');
} else {
    $loader->load(__DIR__ . '/config/routes/prod.yaml');
}

// В зависимости от модуля
if ($app->hasModule('blog')) {
    $loader->load(__DIR__ . '/config/routes/blog.yaml');
}
```

### Когда использовать
- Большие проекты (100+ routes)
- Enterprise приложения
- Когда маршруты редактируют не-разработчики
- Multi-environment конфигурация

---

## 📑 3. XML Configuration

### Преимущества
- ✅ Структурированный формат
- ✅ XML validation
- ✅ IDE support с XSD схемами
- ✅ Подходит для enterprise

### Базовая структура

```xml
<?xml version="1.0" encoding="UTF-8"?>
<routes>
    <!-- Простой маршрут -->
    <route path="/" name="home" methods="GET" 
           controller="HomeController::index"/>
    
    <!-- С параметрами -->
    <route path="/users/{id}" name="users.show" methods="GET" 
           controller="UserController::show">
        <requirements>
            <requirement param="id" pattern="\d+"/>
        </requirements>
    </route>
    
    <!-- С middleware -->
    <route path="/admin" name="admin.dashboard" methods="GET"
           controller="AdminController::dashboard">
        <middleware>auth,admin</middleware>
    </route>
    
    <!-- С defaults -->
    <route path="/blog/{page}" name="blog.index" methods="GET"
           controller="BlogController::index">
        <requirements>
            <requirement param="page" pattern="\d+"/>
        </requirements>
        <defaults>
            <default param="page" value="1"/>
        </defaults>
    </route>
    
    <!-- С domain -->
    <route path="/api/data" name="api.data" methods="GET"
           controller="ApiController::data"
           domain="api.example.com"/>
    
    <!-- Комплексный пример -->
    <route path="/api/v1/users/{id}" 
           name="api.v1.users.handle"
           methods="GET,POST,PUT,DELETE"
           controller="Api\V1\UserController::handle"
           domain="api.example.com">
        <middleware>cors,auth,rate-limit</middleware>
        <requirements>
            <requirement param="id" pattern="\d+"/>
        </requirements>
        <defaults>
            <default param="id" value=""/>
        </defaults>
    </route>
</routes>
```

### Загрузка

```php
use CloudCastle\Http\Router\Loader\XmlLoader;

$loader = new XmlLoader($router);
$loader->load(__DIR__ . '/config/routes.xml');
```

### XSD Schema (опционально)

```xml
<?xml version="1.0"?>
<xs:schema xmlns:xs="http://www.w3.org/2001/XMLSchema">
    <xs:element name="routes">
        <xs:complexType>
            <xs:sequence>
                <xs:element name="route" maxOccurs="unbounded">
                    <xs:complexType>
                        <xs:attribute name="path" type="xs:string" required="true"/>
                        <xs:attribute name="name" type="xs:string"/>
                        <xs:attribute name="methods" type="xs:string"/>
                        <xs:attribute name="controller" type="xs:string"/>
                    </xs:complexType>
                </xs:element>
            </xs:sequence>
        </xs:complexType>
    </xs:element>
</xs:schema>
```

### Когда использовать
- Enterprise проекты
- Когда нужна XML validation
- Интеграция с Java/C# системами
- Сложная конфигурация

---

## 🎯 4. JSON Configuration

### Преимущества
- ✅ Универсальный формат данных
- ✅ Легко парсится всеми языками
- ✅ Компактный синтаксис
- ✅ Подходит для API конфигураций
- ✅ Удобен для генерации из других систем

### Базовая структура

```json
{
  "routes": [
    {
      "method": "GET",
      "uri": "/",
      "action": "HomeController@index",
      "name": "home"
    },
    {
      "method": "GET",
      "uri": "/users",
      "action": "UserController@index",
      "name": "users.index",
      "middleware": ["auth"]
    },
    {
      "method": "GET",
      "uri": "/users/{id}",
      "action": "UserController@show",
      "name": "users.show",
      "requirements": {
        "id": "\\d+"
      },
      "defaults": {
        "id": 1
      }
    }
  ]
}
```

### Группы маршрутов

```json
{
  "groups": [
    {
      "prefix": "/api",
      "middleware": ["api"],
      "routes": [
        {
          "method": "GET",
          "uri": "/status",
          "action": "ApiController@status",
          "name": "api.status"
        },
        {
          "method": "GET",
          "uri": "/users",
          "action": "ApiController@users",
          "name": "api.users",
          "throttle": {
            "limit": 100,
            "per_minutes": 1
          }
        }
      ]
    }
  ]
}
```

### Вложенные группы

```json
{
  "groups": [
    {
      "prefix": "/api",
      "routes": [],
      "groups": [
        {
          "prefix": "/v1",
          "middleware": ["api.v1"],
          "routes": [
            {
              "method": "GET",
              "uri": "/users",
              "action": "ApiV1Controller@users",
              "name": "api.v1.users"
            }
          ]
        },
        {
          "prefix": "/v2",
          "middleware": ["api.v2"],
          "routes": [
            {
              "method": "GET",
              "uri": "/users",
              "action": "ApiV2Controller@users",
              "name": "api.v2.users"
            }
          ]
        }
      ]
    }
  ]
}
```

### Продвинутая конфигурация

```json
{
  "routes": [
    {
      "method": "POST",
      "uri": "/api/data",
      "action": "ApiController@data",
      "name": "api.data",
      "middleware": ["auth", "csrf"],
      "domain": "api.example.com",
      "port": 443,
      "protocol": "https",
      "tags": ["api", "public"],
      "throttle": {
        "limit": 60,
        "per_minutes": 1
      },
      "whitelist": ["192.168.1.0/24", "10.0.0.1"],
      "condition": "user.premium == true"
    }
  ],
  "groups": [
    {
      "prefix": "/admin",
      "middleware": ["auth", "admin"],
      "domain": "admin.example.com",
      "port": 443,
      "protocol": "https",
      "routes": [
        {
          "method": "GET",
          "uri": "/dashboard",
          "action": "AdminController@dashboard",
          "name": "admin.dashboard"
        },
        {
          "method": "GET",
          "uri": "/users",
          "action": "AdminController@users",
          "name": "admin.users",
          "whitelist": ["192.168.1.0/24"]
        }
      ]
    }
  ]
}
```

### Использование JsonLoader

```php
use CloudCastle\Http\Router\Router;
use CloudCastle\Http\Router\Loader\JsonLoader;

$router = new Router();
$loader = new JsonLoader($router);

// Загрузить маршруты из JSON файла
$loader->load(__DIR__ . '/config/routes.json');

// Dispatch
$result = $router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
```

### Полный пример JSON конфигурации

```json
{
  "routes": [
    {
      "method": "GET",
      "uri": "/",
      "action": "HomeController@index",
      "name": "home"
    },
    {
      "method": "GET",
      "path": "/about",
      "handler": "PageController@about",
      "name": "about"
    }
  ],
  "groups": [
    {
      "prefix": "/api",
      "middleware": ["api"],
      "routes": [
        {
          "method": "GET",
          "uri": "/status",
          "action": "ApiController@status",
          "name": "api.status"
        }
      ],
      "groups": [
        {
          "prefix": "/v1",
          "middleware": ["api.v1"],
          "routes": [
            {
              "method": "GET",
              "uri": "/users",
              "action": "ApiV1Controller@users",
              "name": "api.v1.users",
              "throttle": {
                "limit": 100,
                "per_minutes": 1
              }
            },
            {
              "method": "POST",
              "uri": "/users",
              "action": "ApiV1Controller@store",
              "name": "api.v1.users.store",
              "middleware": ["csrf"],
              "throttle": {
                "limit": 10,
                "per_minutes": 1
              }
            }
          ]
        }
      ]
    },
    {
      "prefix": "/admin",
      "middleware": ["auth", "admin"],
      "domain": "admin.example.com",
      "routes": [
        {
          "method": "GET",
          "uri": "/dashboard",
          "action": "AdminController@dashboard",
          "name": "admin.dashboard",
          "whitelist": ["192.168.1.0/24", "10.0.0.1"]
        },
        {
          "method": "GET",
          "uri": "/settings",
          "action": "AdminController@settings",
          "name": "admin.settings",
          "condition": "user.role == 'super_admin'"
        }
      ]
    }
  ]
}
```

### Поддерживаемые поля

| Поле | Тип | Описание |
|:---|:---|:---|
| `method` | string | HTTP метод (GET, POST, PUT, DELETE, etc.) |
| `uri` / `path` | string | URI маршрута |
| `action` / `handler` | string | Обработчик маршрута |
| `name` | string | Имя маршрута |
| `middleware` | string\|array | Middleware |
| `defaults` | object | Значения по умолчанию для параметров |
| `requirements` | object | Regex требования для параметров |
| `condition` | string | Expression Language условие |
| `domain` | string | Домен |
| `port` | number | Порт |
| `protocol` | string | Протокол (http/https) |
| `tags` | array | Теги маршрута |
| `throttle` | object | Rate limiting конфигурация |
| `whitelist` | array | IP whitelist |
| `blacklist` | array | IP blacklist |

### Когда использовать
- API-ориентированные проекты
- Когда конфигурация генерируется программно
- Интеграция с JavaScript/Node.js
- REST API сервисы
- Микросервисная архитектура

---

## 🏷️ 5. PHP 8 Attributes

### Преимущества
- ✅ Type-safe
- ✅ IDE autocomplete
- ✅ Маршруты рядом с кодом
- ✅ Рефакторинг-friendly
- ✅ Modern PHP 8+

### Базовое использование

```php
use CloudCastle\Http\Router\Loader\Route;

class UserController
{
    #[Route('/users', methods: 'GET', name: 'users.index')]
    public function index(): array
    {
        return User::all();
    }
    
    #[Route('/users/{id}', methods: 'GET', name: 'users.show')]
    public function show(int $id): User
    {
        return User::find($id);
    }
    
    #[Route('/users', methods: 'POST', name: 'users.store')]
    public function store(): User
    {
        return User::create($request->all());
    }
}
```

### С middleware

```php
class AdminController
{
    #[Route(
        '/admin/dashboard',
        methods: 'GET',
        name: 'admin.dashboard',
        middleware: ['auth', 'admin']
    )]
    public function dashboard(): View
    {
        return view('admin.dashboard');
    }
}
```

### С domain и throttle

```php
class ApiController
{
    #[Route(
        '/api/data',
        methods: 'GET',
        name: 'api.data',
        domain: 'api.example.com',
        throttle: 60,
        middleware: ['cors', 'auth']
    )]
    public function data(): array
    {
        return ['data' => $this->getData()];
    }
}
```

### Множественные маршруты на одном методе

```php
class ProfileController
{
    // Оба маршрута ведут на один метод
    #[Route('/user/{id}', methods: 'GET', name: 'user.profile')]
    #[Route('/profile/{id}', methods: 'GET', name: 'profile.show')]
    public function show(int $id): array
    {
        return User::find($id)->toArray();
    }
}
```

### Загрузка

```php
use CloudCastle\Http\Router\Loader\AttributeLoader;

$loader = new AttributeLoader($router);

// Из одного контроллера
$loader->loadFromController(UserController::class);

// Из нескольких контроллеров
$loader->loadFromControllers([
    UserController::class,
    PostController::class,
    CommentController::class,
]);

// Из директории (auto-discovery)
$loader->loadFromDirectory(
    __DIR__ . '/Controllers',
    'App\\Controllers'
);
```

### Best Practices

```php
// 1. Группируйте логически по контроллерам
class UserApiController
{
    #[Route('/api/users', methods: 'GET')]
    public function index() {}
    
    #[Route('/api/users/{id}', methods: 'GET')]
    public function show(int $id) {}
}

// 2. Используйте named routes
#[Route('/users/{id}', name: 'users.show')]

// 3. Документируйте сложные маршруты
/**
 * Show user profile with statistics.
 */
#[Route(
    '/users/{id}/stats',
    methods: ['GET', 'POST'],
    middleware: ['auth', 'admin']
)]
public function stats(int $id) {}
```

### Когда использовать
- Modern PHP 8+ проекты
- MVC приложения
- Когда важен type safety
- Для лучшей организации кода

---

## 📊 Сравнение Loaders

### Возможности

| Feature | PHP | YAML | XML | Attributes |
|:---|:---:|:---:|:---:|:---:|
| Type Safety | ✅ | ⚠️ | ⚠️ | ✅ |
| IDE Support | ✅ | ⚠️ | ✅ | ✅ |
| Validation | Runtime | ⚠️ | ✅ | Compile |
| Dynamic | ✅ | ❌ | ❌ | ❌ |
| Version Control | ✅ | ✅ | ✅ | ✅ |
| Non-dev Editing | ❌ | ✅ | ✅ | ❌ |
| Complexity | Medium | Low | Medium | Low |

### Производительность загрузки

| Loader | 100 routes | 1000 routes | Overhead |
|:---|:---:|:---:|:---:|
| PHP (direct) | 0.001s | 0.010s | Baseline |
| **JSON** | **0.012s** | **0.120s** | **12x** |
| Attributes | 0.015s | 0.150s | 15x |
| XML | 0.018s | 0.180s | 18x |
| YAML | 0.020s | 0.200s | 20x |

**Примечание**: Overhead только при первой загрузке. С кешированием все работают одинаково быстро.

### Размер конфигурации

| Format | Size for 100 routes | Readability |
|:---|:---:|:---:|
| PHP | ~5 KB | Medium |
| YAML | ~3 KB | High |
| **JSON** | **~4 KB** | **High** |
| XML | ~7 KB | Medium |
| Attributes | ~4 KB | High |

## 💡 Рекомендации по выбору

### Используйте PHP если:
- ✅ Нужна динамическая маршрутизация
- ✅ Проект small/medium (< 100 routes)
- ✅ Важен type safety
- ✅ Нужен полный контроль

### Используйте YAML если:
- ✅ Большой проект (100-1000 routes)
- ✅ Маршруты редактируют не-разработчики
- ✅ Нужна модульная структура
- ✅ Важна читаемость

### Используйте XML если:
- ✅ Enterprise проект
- ✅ Нужна XML validation
- ✅ Интеграция с другими XML системами
- ✅ Требуется XSD схема

### Используйте JSON если:
- ✅ API-ориентированные проекты
- ✅ Интеграция с JavaScript/Node.js
- ✅ Конфигурация генерируется программно
- ✅ REST API сервисы
- ✅ Микросервисная архитектура

### Используйте Attributes если:
- ✅ Modern PHP 8+ проект
- ✅ MVC архитектура
- ✅ Маршруты близко к контроллерам
- ✅ Важен IDE support

## 🔄 Комбинированное использование

Можно комбинировать несколько подходов:

```php
// 1. Базовые маршруты через PHP
$router->get('/', 'HomeController@index');

// 2. API маршруты через JSON
$jsonLoader = new JsonLoader($router);
$jsonLoader->load(__DIR__ . '/config/routes/api.json');

// 3. Admin через YAML
$yamlLoader = new YamlLoader($router);
$yamlLoader->load(__DIR__ . '/config/routes/admin.yaml');

// 4. Module через Attributes
$attrLoader = new AttributeLoader($router);
$attrLoader->loadFromDirectory(__DIR__ . '/Controllers/Module', 'App\\Module');

// 5. Legacy через XML
$xmlLoader = new XmlLoader($router);
$xmlLoader->load(__DIR__ . '/config/legacy-routes.xml');
```

## 📊 Сравнение с конкурентами

| Router | PHP | YAML | XML | JSON | Attributes | Total |
|:---|:---:|:---:|:---:|:---:|:---:|:---:|
| **CloudCastle** | **✅** | **✅** | **✅** | **✅** | **✅** | **5/5** |
| Symfony | ✅ | ✅ | ✅ | ❌ | ✅ | 4/5 |
| Laravel | ✅ | ❌ | ❌ | ❌ | ⚠️ | 1.5/5 |
| FastRoute | ✅ | ❌ | ❌ | ❌ | ❌ | 1/5 |
| Slim | ✅ | ❌ | ❌ | ❌ | ❌ | 1/5 |
| AltoRouter | ✅ | ❌ | ❌ | ❌ | ❌ | 1/5 |

**CloudCastle - единственный роутер с поддержкой всех 5 форматов конфигурации!**

## ✅ Заключение

CloudCastle HTTP Router предоставляет **максимальную гибкость** в конфигурации маршрутов:

- ✅ **5 форматов конфигурации** (PHP, YAML, XML, JSON, Attributes)
- ✅ Возможность комбинирования
- ✅ Auto-discovery для Attributes
- ✅ Модульная загрузка
- ✅ **Единственный роутер с полной поддержкой всех форматов!**

Выбирайте формат в зависимости от размера проекта, команды и требований.

---

*Последнее обновление: 18 октября 2025*

---

[📚 Оглавление](_table-of-contents.md) | [🏠 Главная](README.md)

---

[📚 Table of Contents](zh/_table-of-contents.md) | [🏠 Home](zh/README.md)
