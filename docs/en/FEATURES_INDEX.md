# Index of All CloudCastle HTTP Router Features

**English** | [Русский](../ru/FEATURES_INDEX.md) | [Deutsch](../de/FEATURES_INDEX.md) | [Français](../fr/FEATURES_INDEX.md) | [中文](../zh/FEATURES_INDEX.md)

---







---

## 📚 Documentation Navigation

[README](../../README.md) | [USER_GUIDE](USER_GUIDE.md) | [FEATURES_INDEX](FEATURES_INDEX.md) | [API_REFERENCE](API_REFERENCE.md) | [ALL_FEATURES](ALL_FEATURES.md) | [TESTS_SUMMARY](TESTS_SUMMARY.md) | [PERFORMANCE](PERFORMANCE_ANALYSIS.md) | [SECURITY](SECURITY_REPORT.md) | [COMPARISON](COMPARISON.md) | [FAQ](FAQ.md) | [DOC_SUMMARY](DOCUMENTATION_SUMMARY.md)

**Detailed documentation:** [Features](features/) (22 files) | [Tests](tests/) (7 reports)

---


**Version:** 1.1.1  
**Total features:** 209+  
**Categories:** 23

---

## 📖 How to Use This Index

This document contains a complete list of all 209+ library features, organized by categoryм. For each category the following is provided:
- Number of methods/features
- Link to detailed documentation
- Brief description
- Main methods

---

## 🗂️ Feature Categories

### 1. Basic Routing (13 methods)

**Complexity:** ⭐ Beginner  
**Documentation:** [01_BASIC_ROUTING.md](features/01_BASIC_ROUTING.md)

Registering handlers for various HTTP methods и URI.

**Main methods:**
- `Route::get()` - GET requestы
- `Route::post()` - POST requestы
- `Route::put()` - PUT requestы (полное обновление)
- `Route::patch()` - PATCH requestы (частичное обновление)
- `Route::delete()` - DELETE requestы
- `Route::view()` - Custom method VIEW
- `Route::custom()` - Any HTTP method
- `Route::match()` - Multiple methods
- `Route::any()` - All HTTP methods
- `Router::getInstance()` - Singleton
- Facade API - Static interface

---

### 2. Parameters routeов (6 ways)

**Complexity:** ⭐⭐ Intermediate  
**Documentation:** [02_ROUTE_PARAMETERS.md](features/02_ROUTE_PARAMETERS.md)

Dynamic parameters в URI with validation and default values.

**Core Features:**
- `{id}` - Basic parameters
- `where('id', '[0-9]+')` - Constraints (regex)
- `{id:[0-9]+}` - Inline parameters
- `{page?}` - Optional parameters
- `defaults(['page' => 1])` - Default values
- `getParameters()` - Getting parameters

---

### 3. Groups routeов (12 attributes)

**Complexity:** ⭐⭐ Intermediate  
**Documentation:** [03_ROUTE_GROUPS.md](features/03_ROUTE_GROUPS.md)

Organization routeов with shared attributes.

**Group attributes:**
- `prefix` - Prefix URI
- `middleware` - Shared middleware
- `domain` - Привязка к домену
- `port` - Привязка к порту
- `namespace` - Namespace controllerов
- `https` - Требование HTTPS
- `protocols` - Разрешенные протоколы
- `tags` - Теги для groups
- `throttle` - Rate limiting
- `whitelistIp` - IP whitelist
- `blacklistIp` - IP blacklist
- `name` - Prefix имени

---

### 4. Rate Limiting & Auto-Ban (15 methods)

**Complexity:** ⭐⭐⭐ Advanced  
**Documentation:** [04_RATE_LIMITING.md](features/04_RATE_LIMITING.md)

Защита от DDoS, брут-форса и злоупотреблений.

**Rate Limiting (8 methods):**
- `throttle(60, 1)` - Базовый лимит
- `TimeUnit` enum - Единицы времени
- Custom ключ - По пользователю/API ключу
- `RateLimiter` класс - Программное управление
- `throttleStandard()` - 60 req/min
- `throttleStrict()` - 10 req/min
- `throttleGenerous()` - 1000 req/min

**Auto-Ban (7 methods):**
- `BanManager` - Управление банами
- `enableAutoBan(5)` - Включить автобан
- `ban($ip, $duration)` - Забанить IP
- `unban($ip)` - Разбанить
- `isBanned($ip)` - Проверить бан
- `getBannedIps()` - Список забаненных
- `clearAll()` - Очистить all баны

---

### 5. IP Filtering (4 methodа)

**Complexity:** ⭐⭐ Intermediate  
**Documentation:** В разработке

Контроль доступа по IP адресам.

**Methods:**
- `whitelistIp([...])` - Разрешить только указанные IP
- `blacklistIp([...])` - Запретить указанные IP
- CIDR нотация - Поддержка подсетей
- IP Spoofing защита - Проверка X-Forwarded-For

---

### 6. Middleware (6 типов)

**Complexity:** ⭐⭐ Intermediate  
**Documentation:** В разработке

Промежуточная обработка requests.

**Встроенные middleware:**
- `AuthMiddleware` - Аутентификация
- `CorsMiddleware` - CORS заголовки
- `HttpsEnforcement` - Принудительный HTTPS
- `SecurityLogger` - Логирование безопасности
- `SsrfProtection` - Защита от SSRF
- `MiddlewareDispatcher` - Диспетчер

**Применение:**
- Глобальный middleware
- На routeе
- В группе
- PSR-15 совместимость

---

### 7. Именованные routes (6 methods)

**Complexity:** ⭐ Beginner  
**Documentation:** В разработке

Присвоение имен routeам для удобной ссылки.

**Methods:**
- `name('users.show')` - Назначить имя
- `getRouteByName('users.show')` - Получить по имени
- `currentRouteName()` - Текущее имя
- `currentRouteNamed('users.*')` - Проверка
- `enableAutoNaming()` - Автоматические имена
- `getNamedRoutes()` - All именованные

---

### 8. Теги (5 methods)

**Complexity:** ⭐ Beginner  
**Documentation:** В разработке

Группировка routeов по тегам.

**Methods:**
- `tag('api')` - Добавить тег
- `tag(['api', 'public'])` - Множественные теги
- `getRoutesByTag('api')` - Получить по тегу
- `hasTag('api')` - Проверить наличие
- `getAllTags()` - All теги

---

### 9. Helper Functions (18 функций)

**Complexity:** ⭐ Beginner  
**Documentation:** [09_HELPER_FUNCTIONS.md](features/09_HELPER_FUNCTIONS.md)

Глобальные PHP функции для упрощения работы.

**Функции:**
- `route($name)` - Получить route
- `current_route()` - Текущий route
- `previous_route()` - Предыдущий route
- `route_is('users.*')` - Проверка имени
- `route_name()` - Имя текущего
- `router()` - Экземпляр роутера
- `dispatch_route($uri, $method)` - Диспетчеризация
- `route_url($name, $params)` - Генерация URL
- `route_has($name)` - Существование
- `route_stats()` - Статистика
- `routes_by_tag($tag)` - По тегу
- `route_back()` - Back

---

### 10. Route Shortcuts (14 methods)

**Complexity:** ⭐ Beginner  
**Documentation:** В разработке

Быстрые methods для типичных сценариев.

**Shortcuts:**
- `auth()` - AuthMiddleware
- `guest()` - Только неавторизованные
- `api()` - API middleware
- `web()` - Web middleware
- `cors()` - CORS
- `localhost()` - Только localhost
- `secure()` - HTTPS only
- `throttleStandard()` - 60/min
- `throttleStrict()` - 10/min
- `throttleGenerous()` - 1000/min
- `public()` - Тег public
- `private()` - Тег private
- `admin()` - Админ настройка
- `apiEndpoint()` - API endpoint

---

### 11. Route Macros (7 макросов)

**Complexity:** ⭐⭐ Intermediate  
**Documentation:** В разработке

Быстрое создание групп связанных routeов.

**Макросы:**
- `resource()` - RESTful CRUD (7 routeов)
- `apiResource()` - API CRUD (5 routeов)
- `crud()` - Простой CRUD
- `auth()` - Routes аутентификации
- `adminPanel()` - Админ панель
- `apiVersion()` - Версионирование API
- `webhooks()` - Webhooks

---

### 12. URL Generation (11 methods)

**Complexity:** ⭐⭐ Intermediate  
**Documentation:** В разработке

Генерация URL по именам routeов.

**UrlGenerator methods:**
- `generate($name, $params)` - Basic генерация
- `absolute()` - Абсолютный URL
- `toDomain($domain)` - С доменом
- `toProtocol($protocol)` - С протоколом
- `signed($name, $params, $ttl)` - Подписанный URL
- `setBaseUrl($url)` - Базовый URL
- Query parameters
- HATEOAS links
- `route_url()` helper

---

### 13. Expression Language (5 операторов)

**Complexity:** ⭐⭐⭐ Advanced  
**Documentation:** В разработке

Условия для routeов на основе выражений.

**Features:**
- `condition()` - Условие routeа
- Операторы сравнения: `==`, `!=`, `>`, `<`, `>=`, `<=`
- Логические операторы: `and`, `or`
- `ExpressionLanguage` класс
- `evaluate()` - Вычисление

---

### 14. Кеширование routeов (6 methods)

**Complexity:** ⭐⭐ Intermediate  
**Documentation:** В разработке

Компиляция и кеширование для производительности.

**Methods:**
- `enableCache($dir)` - Включить кеш
- `compile()` - Компилировать
- `loadFromCache()` - Загрузить из кеша
- `clearCache()` - Очистить
- `autoCompile()` - Автокомпиляция
- `isCacheLoaded()` - Проверка загрузки

---

### 15. Система плагинов (13 methods)

**Complexity:** ⭐⭐⭐ Advanced  
**Documentation:** В разработке

Расширение функциональности через плагины.

**PluginInterface:**
- `beforeDispatch()` - Хук до
- `afterDispatch()` - Хук после
- `onRouteRegistered()` - При регистрации
- `onException()` - При исключении

**Управление:**
- `registerPlugin()` - Регистрация
- `unregisterPlugin()` - Отмена
- `getPlugin()` - Получить
- `hasPlugin()` - Проверка
- `getPlugins()` - All плагины

**Встроенные:**
- `LoggerPlugin`
- `AnalyticsPlugin`
- `ResponseCachePlugin`
- `AbstractPlugin`

---

### 16. Загрузчики routeов (5 типов)

**Complexity:** ⭐⭐ Intermediate  
**Documentation:** В разработке

Загрузка routeов из различных форматов.

**Loaders:**
- `JsonLoader` - JSON fileы
- `YamlLoader` - YAML fileы
- `XmlLoader` - XML fileы
- `AttributeLoader` - PHP Attributes
- PHP fileы - Обычный way

---

### 17. PSR Support (3 стандарта)

**Complexity:** ⭐⭐⭐ Advanced  
**Documentation:** В разработке

Совместимость с PSR стандартами.

**Поддержка:**
- PSR-7 HTTP Message
- PSR-15 HTTP Server Handler
- `Psr15MiddlewareAdapter`

---

### 18. Action Resolver (6 форматов)

**Complexity:** ⭐⭐ Intermediate  
**Documentation:** В разработке

Различные форматы действий routeов.

**Форматы:**
- Closure - `function() { }`
- Array - `[Controller::class, 'method']`
- String "Controller@method"
- String "Controller::method"
- Invokable - `Controller::class`
- Dependency Injection

---

### 19. Статистика и requestы (24 methodа)

**Complexity:** ⭐⭐ Intermediate  
**Documentation:** В разработке

Getting информации о зарегистрированных routeах.

**Methods:**
- `getRouteStats()` - Общая статистика
- `getRoutesByMethod()` - По methodу
- `getRoutesByDomain()` - По домену
- `getRoutesByPort()` - По порту
- `getRoutesByPrefix()` - По prefixу
- `getRoutesByMiddleware()` - По middleware
- `getRoutesByController()` - По controllerу
- `getThrottledRoutes()` - С лимитами
- `searchRoutes()` - Поиск
- `getRoutesGroupedByMethod()` - Группировка
- `count()` - Number of
- `getRoutesAsJson()` - В JSON
- `getRoutesAsArray()` - В массив
- И 11 других methods

---

### 20. Security (12 механизмов)

**Complexity:** ⭐⭐⭐ Advanced  
**Documentation:** В разработке

Встроенная защита от различных атак.

**Защита от:**
- Path Traversal - `../` атаки
- SQL Injection - Validation parameters
- XSS - Экранирование
- ReDoS - Regex DoS
- Method Override - Подмена methods
- Cache Injection - Безопасный кеш
- IP Spoofing - Проверка заголовков
- DDoS - Rate limiting
- Брут-форс - Auto-ban
- SSRF - SsrfProtection
- Protocol enforcement - HTTP/HTTPS/WS/WSS
- Resource exhaustion - Лимиты

---

### 21. Исключения (8 типов)

**Complexity:** ⭐ Beginner  
**Documentation:** В разработке

Специализированные исключения роутера.

**Типы:**
- `RouteNotFoundException` - 404
- `MethodNotAllowedException` - 405
- `IpNotAllowedException` - 403 (IP)
- `TooManyRequestsException` - 429
- `InsecureConnectionException` - 403 (HTTPS)
- `BannedException` - 403 (Ban)
- `InvalidActionException` - 500
- `RouterException` - Базовое

---

### 22. CLI Tools (3 команды)

**Complexity:** ⭐ Beginner  
**Documentation:** В разработке

Консольные утилиты для работы с routeами.

**Команды:**
- `routes-list` - Список routeов
- `analyse` - Анализ routeов
- `router` - Управление (compile, clear, stats)

---

### 23. Дополнительные возможности

**RouteCollection, RouteDumper, UrlMatcher:**
- RouteCollection - Коллекция routeов
- RouteDumper - Экспорт routeов
- UrlMatcher - Сопоставление URL
- Singleton pattern - Router::getInstance()
- Method chaining - Fluent API
- Current/Previous route - История

---

## 📊 Сводная статистика

| Category | Methodов/Features |
|-----------|---------------------|
| Basic Routing | 13 |
| Parameters routeов | 6 |
| Groups routeов | 12 |
| Rate Limiting & Auto-Ban | 15 |
| IP Filtering | 4 |
| Middleware | 6 |
| Именованные routes | 6 |
| Теги | 5 |
| Helper Functions | 18 |
| Route Shortcuts | 14 |
| Route Macros | 7 |
| URL Generation | 11 |
| Expression Language | 5 |
| Кеширование | 6 |
| Плагины | 13 |
| Загрузчики | 5 |
| PSR Support | 3 |
| Action Resolver | 6 |
| Статистика | 24 |
| Security | 12 |
| Исключения | 8 |
| CLI Tools | 3 |
| Additional | 10+ |
| **ИТОГО** | **209+** |

---

## 🔍 Быстрый поиск

### По сложности

**⭐ Beginner уровень:**
- Basic Routing
- Именованные routes
- Теги
- Helper Functions
- Route Shortcuts
- Исключения
- CLI Tools

**⭐⭐ Intermediate уровень:**
- Parameters routeов
- Groups routeов
- IP Filtering
- Middleware
- Route Macros
- URL Generation
- Кеширование
- Загрузчики
- Action Resolver
- Статистика

**⭐⭐⭐ Advanced уровень:**
- Rate Limiting & Auto-Ban
- Expression Language
- Плагины
- PSR Support
- Security

### По categoryм использования

**Routing:**
- Basic Routing
- Parameters routeов
- Groups routeов
- Именованные routes
- URL Generation

**Security:**
- Rate Limiting & Auto-Ban
- IP Filtering
- Middleware
- Security

**Organization кода:**
- Groups routeов
- Теги
- Route Macros
- Namespace

**Performance:**
- Кеширование
- Статистика
- Оптимизация

**Расширяемость:**
- Плагины
- Middleware
- Загрузчики
- PSR Support

---

## 📚 Дополнительная документация

- [USER_GUIDE.md](USER_GUIDE.md) - Полное руководство с примерами
- [API_REFERENCE.md](API_REFERENCE.md) - API документация
- [COMPARISON.md](COMPARISON.md) - Comparison with Alternatives
- [SECURITY_REPORT.md](SECURITY_REPORT.md) - Security Report
- [PERFORMANCE_ANALYSIS.md](PERFORMANCE_ANALYSIS.md) - Performance Analysis
- [FAQ.md](FAQ.md) - Frequently Asked Questions

---

**© 2024 CloudCastle HTTP Router**  
**Version:** 1.1.1  
**Лицензия:** MIT

[⬆ Наверх](#индекс-всех-возможностей-cloudcastle-http-router)


---

## 📚 Documentation Navigation

[README](../../README.md) | [USER_GUIDE](USER_GUIDE.md) | [FEATURES_INDEX](FEATURES_INDEX.md) | [API_REFERENCE](API_REFERENCE.md) | [ALL_FEATURES](ALL_FEATURES.md) | [TESTS_SUMMARY](TESTS_SUMMARY.md) | [PERFORMANCE](PERFORMANCE_ANALYSIS.md) | [SECURITY](SECURITY_REPORT.md) | [COMPARISON](COMPARISON.md) | [FAQ](FAQ.md) | [DOC_SUMMARY](DOCUMENTATION_SUMMARY.md)

**Detailed documentation:** [Features](features/) (22 files) | [Tests](tests/) (7 reports)

---

