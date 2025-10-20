# Индекс всех возможностей CloudCastle HTTP Router

---

## 📚 Dokumentationsnavigation

[README](../../README.md) | [USER_GUIDE](USER_GUIDE.md) | [FEATURES_INDEX](FEATURES_INDEX.md) | [API_REFERENCE](API_REFERENCE.md) | [ALL_FEATURES](ALL_FEATURES.md) | [TESTS_SUMMARY](TESTS_SUMMARY.md) | [PERFORMANCE](PERFORMANCE_ANALYSIS.md) | [SECURITY](SECURITY_REPORT.md) | [COMPARISON](COMPARISON.md) | [FAQ](FAQ.md) | [DOC_SUMMARY](DOCUMENTATION_SUMMARY.md)

**Детальная документация:** [Features](features/) (22 Dateiа) | [Tests](tests/) (7 Berichte)

---


**Version:** 1.1.1  
**Всего возможностей:** 209+  
**Категорий:** 23

---

## 📖 Как пользоваться этим индексом

Этот документ содержит полный список всех 209+ возможностей библиотеки, организованных по Kategorieм. Для каждой Kategorien указаны:
- Количество Methodeов/возможностей
- Ссылка на детальную документацию
- Краткое описание
- Основные Methoden

---

## 🗂️ Категории возможностей

### 1. Базовая Routing (13 Methodeов)

**Сложность:** ⭐ Начальный  
**Dokumentation:** [01_BASIC_ROUTING.md](features/01_BASIC_ROUTING.md)

Регистрация обработчиков для различных HTTP Methodeов и URI.

**Основные Methoden:**
- `Route::get()` - GET Anfragen
- `Route::post()` - POST Anfragen
- `Route::put()` - PUT Anfragen (полное обновление)
- `Route::patch()` - PATCH Anfragen (частичное обновление)
- `Route::delete()` - DELETE Anfragen
- `Route::view()` - Кастомный Methode VIEW
- `Route::custom()` - Любой HTTP Methode
- `Route::match()` - Несколько Methodeов
- `Route::any()` - Все HTTP Methoden
- `Router::getInstance()` - Singleton
- Facade API - Статический интерфейс

---

### 2. Параметры Routeов (6 способов)

**Сложность:** ⭐⭐ Средний  
**Dokumentation:** [02_ROUTE_PARAMETERS.md](features/02_ROUTE_PARAMETERS.md)

Динамические Parameter в URI с валидацией и значениями по умолчанию.

**Hauptfunktionen:**
- `{id}` - Базовые Parameter
- `where('id', '[0-9]+')` - Ограничения (regex)
- `{id:[0-9]+}` - Inline Parameter
- `{page?}` - Опциональные Parameter
- `defaults(['page' => 1])` - Значения по умолчанию
- `getParameters()` - Получение Parameterов

---

### 3. Группы Routeов (12 атрибутов)

**Сложность:** ⭐⭐ Средний  
**Dokumentation:** [03_ROUTE_GROUPS.md](features/03_ROUTE_GROUPS.md)

Организация Routeов с общими атрибутами.

**Атрибуты групп:**
- `prefix` - Префикс URI
- `middleware` - Общий Middleware
- `domain` - Привязка к домену
- `port` - Привязка к порту
- `namespace` - Namespace Controllerов
- `https` - Требование HTTPS
- `protocols` - Разрешенные протоколы
- `tags` - Теги для Gruppen
- `throttle` - Rate limiting
- `whitelistIp` - IP whitelist
- `blacklistIp` - IP blacklist
- `name` - Префикс имени

---

### 4. Rate Limiting & Auto-Ban (15 Methodeов)

**Сложность:** ⭐⭐⭐ Продвинутый  
**Dokumentation:** [04_RATE_LIMITING.md](features/04_RATE_LIMITING.md)

Защита от DDoS, брут-форса и злоупотреблений.

**Rate Limiting (8 Methodeов):**
- `throttle(60, 1)` - Базовый лимит
- `TimeUnit` enum - Единицы времени
- Кастомный ключ - По пользователю/API ключу
- `RateLimiter` класс - Программное управление
- `throttleStandard()` - 60 req/min
- `throttleStrict()` - 10 req/min
- `throttleGenerous()` - 1000 req/min

**Auto-Ban (7 Methodeов):**
- `BanManager` - Управление банами
- `enableAutoBan(5)` - Включить автобан
- `ban($ip, $duration)` - Забанить IP
- `unban($ip)` - Разбанить
- `isBanned($ip)` - Проверить бан
- `getBannedIps()` - Список забаненных
- `clearAll()` - Очистить все баны

---

### 5. IP Filtering (4 Methodeа)

**Сложность:** ⭐⭐ Средний  
**Dokumentation:** В разработке

Контроль доступа по IP адресам.

**Методы:**
- `whitelistIp([...])` - Разрешить только указанные IP
- `blacklistIp([...])` - Запретить указанные IP
- CIDR нотация - Поддержка подсетей
- IP Spoofing защита - Проверка X-Forwarded-For

---

### 6. Middleware (6 типов)

**Сложность:** ⭐⭐ Средний  
**Dokumentation:** В разработке

Промежуточная обработка Anfrageов.

**Встроенные Middleware:**
- `AuthMiddleware` - Аутентификация
- `CorsMiddleware` - CORS заголовки
- `HttpsEnforcement` - Принудительный HTTPS
- `SecurityLogger` - Логирование безопасности
- `SsrfProtection` - Защита от SSRF
- `MiddlewareDispatcher` - Диспетчер

**Применение:**
- Глобальный Middleware
- На Routeе
- В группе
- PSR-15 совместимость

---

### 7. Именованные Routen (6 Methodeов)

**Сложность:** ⭐ Начальный  
**Dokumentation:** В разработке

Присвоение имен Routeам для удобной ссылки.

**Методы:**
- `name('users.show')` - Назначить имя
- `getRouteByName('users.show')` - Получить по имени
- `currentRouteName()` - Текущее имя
- `currentRouteNamed('users.*')` - Проверка
- `enableAutoNaming()` - Автоматические имена
- `getNamedRoutes()` - Все именованные

---

### 8. Теги (5 Methodeов)

**Сложность:** ⭐ Начальный  
**Dokumentation:** В разработке

Группировка Routeов по тегам.

**Методы:**
- `tag('api')` - Добавить тег
- `tag(['api', 'public'])` - Множественные теги
- `getRoutesByTag('api')` - Получить по тегу
- `hasTag('api')` - Проверить наличие
- `getAllTags()` - Все теги

---

### 9. Helper Functions (18 функций)

**Сложность:** ⭐ Начальный  
**Dokumentation:** [09_HELPER_FUNCTIONS.md](features/09_HELPER_FUNCTIONS.md)

Глобальные PHP функции для упрощения работы.

**Функции:**
- `route($name)` - Получить Route
- `current_route()` - Текущий Route
- `previous_route()` - Предыдущий Route
- `route_is('users.*')` - Проверка имени
- `route_name()` - Имя текущего
- `router()` - Экземпляр роутера
- `dispatch_route($uri, $method)` - Диспетчеризация
- `route_url($name, $params)` - Генерация URL
- `route_has($name)` - Существование
- `route_stats()` - Статистика
- `routes_by_tag($tag)` - По тегу
- `route_back()` - Назад

---

### 10. Route Shortcuts (14 Methodeов)

**Сложность:** ⭐ Начальный  
**Dokumentation:** В разработке

Быстрые Methoden для типичных сценариев.

**Shortcuts:**
- `auth()` - AuthMiddleware
- `guest()` - Только неавторизованные
- `api()` - API Middleware
- `web()` - Web Middleware
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

**Сложность:** ⭐⭐ Средний  
**Dokumentation:** В разработке

Быстрое создание групп связанных Routeов.

**Макросы:**
- `resource()` - RESTful CRUD (7 Routeов)
- `apiResource()` - API CRUD (5 Routeов)
- `crud()` - Простой CRUD
- `auth()` - Маршруты аутентификации
- `adminPanel()` - Админ панель
- `apiVersion()` - Версионирование API
- `webhooks()` - Webhooks

---

### 12. URL Generation (11 Methodeов)

**Сложность:** ⭐⭐ Средний  
**Dokumentation:** В разработке

Генерация URL по именам Routeов.

**UrlGenerator Methoden:**
- `generate($name, $params)` - Базовая генерация
- `absolute()` - Абсолютный URL
- `toDomain($domain)` - С доменом
- `toProtocol($protocol)` - С протоколом
- `signed($name, $params, $ttl)` - Подписанный URL
- `setBaseUrl($url)` - Базовый URL
- Query Parameter
- HATEOAS links
- `route_url()` helper

---

### 13. Expression Language (5 операторов)

**Сложность:** ⭐⭐⭐ Продвинутый  
**Dokumentation:** В разработке

Условия для Routeов на основе выражений.

**Funktionen:**
- `condition()` - Условие Routeа
- Операторы сравнения: `==`, `!=`, `>`, `<`, `>=`, `<=`
- Логические операторы: `and`, `or`
- `ExpressionLanguage` класс
- `evaluate()` - Вычисление

---

### 14. Кеширование Routeов (6 Methodeов)

**Сложность:** ⭐⭐ Средний  
**Dokumentation:** В разработке

Компиляция и кеширование для производительности.

**Методы:**
- `enableCache($dir)` - Включить кеш
- `compile()` - Компилировать
- `loadFromCache()` - Загрузить из кеша
- `clearCache()` - Очистить
- `autoCompile()` - Автокомпиляция
- `isCacheLoaded()` - Проверка загрузки

---

### 15. Система плагинов (13 Methodeов)

**Сложность:** ⭐⭐⭐ Продвинутый  
**Dokumentation:** В разработке

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
- `getPlugins()` - Все плагины

**Встроенные:**
- `LoggerPlugin`
- `AnalyticsPlugin`
- `ResponseCachePlugin`
- `AbstractPlugin`

---

### 16. Загрузчики Routeов (5 типов)

**Сложность:** ⭐⭐ Средний  
**Dokumentation:** В разработке

Загрузка Routeов из различных форматов.

**Loaders:**
- `JsonLoader` - JSON Dateiы
- `YamlLoader` - YAML Dateiы
- `XmlLoader` - XML Dateiы
- `AttributeLoader` - PHP Attributes
- PHP Dateiы - Обычный способ

---

### 17. PSR Support (3 стандарта)

**Сложность:** ⭐⭐⭐ Продвинутый  
**Dokumentation:** В разработке

Совместимость с PSR стандартами.

**Поддержка:**
- PSR-7 HTTP Message
- PSR-15 HTTP Server Handler
- `Psr15MiddlewareAdapter`

---

### 18. Action Resolver (6 форматов)

**Сложность:** ⭐⭐ Средний  
**Dokumentation:** В разработке

Различные форматы действий Routeов.

**Форматы:**
- Closure - `function() { }`
- Array - `[Controller::class, 'method']`
- String "Controller@method"
- String "Controller::method"
- Invokable - `Controller::class`
- Dependency Injection

---

### 19. Статистика и Anfragen (24 Methodeа)

**Сложность:** ⭐⭐ Средний  
**Dokumentation:** В разработке

Получение информации о зарегистрированных Routeах.

**Методы:**
- `getRouteStats()` - Общая статистика
- `getRoutesByMethod()` - По Methodeу
- `getRoutesByDomain()` - По домену
- `getRoutesByPort()` - По порту
- `getRoutesByPrefix()` - По префиксу
- `getRoutesByMiddleware()` - По Middleware
- `getRoutesByController()` - По Controllerу
- `getThrottledRoutes()` - С лимитами
- `searchRoutes()` - Поиск
- `getRoutesGroupedByMethod()` - Группировка
- `count()` - Количество
- `getRoutesAsJson()` - В JSON
- `getRoutesAsArray()` - В массив
- И 11 других Methodeов

---

### 20. Sicherheit (12 механизмов)

**Сложность:** ⭐⭐⭐ Продвинутый  
**Dokumentation:** В разработке

Встроенная защита от различных атак.

**Защита от:**
- Path Traversal - `../` атаки
- SQL Injection - Валидация Parameterов
- XSS - Экранирование
- ReDoS - Regex DoS
- Method Override - Подмена Methodeов
- Cache Injection - Безопасный кеш
- IP Spoofing - Проверка заголовков
- DDoS - Rate limiting
- Брут-форс - Auto-ban
- SSRF - SsrfProtection
- Protocol enforcement - HTTP/HTTPS/WS/WSS
- Resource exhaustion - Лимиты

---

### 21. Исключения (8 типов)

**Сложность:** ⭐ Начальный  
**Dokumentation:** В разработке

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

**Сложность:** ⭐ Начальный  
**Dokumentation:** В разработке

Консольные утилиты для работы с Routeами.

**Команды:**
- `routes-list` - Список Routeов
- `analyse` - Анализ Routeов
- `router` - Управление (compile, clear, stats)

---

### 23. Дополнительные возможности

**RouteCollection, RouteDumper, UrlMatcher:**
- RouteCollection - Коллекция Routeов
- RouteDumper - Экспорт Routeов
- UrlMatcher - Сопоставление URL
- Singleton pattern - Router::getInstance()
- Method chaining - Fluent API
- Current/Previous route - История

---

## 📊 Сводная статистика

| Категория | Методов/Возможностей |
|-----------|---------------------|
| Базовая Routing | 13 |
| Параметры Routeов | 6 |
| Группы Routeов | 12 |
| Rate Limiting & Auto-Ban | 15 |
| IP Filtering | 4 |
| Middleware | 6 |
| Именованные Routen | 6 |
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
| Sicherheit | 12 |
| Исключения | 8 |
| CLI Tools | 3 |
| Zusätzlich | 10+ |
| **ИТОГО** | **209+** |

---

## 🔍 Быстрый поиск

### По сложности

**⭐ Начальный уровень:**
- Базовая Routing
- Именованные Routen
- Теги
- Helper Functions
- Route Shortcuts
- Исключения
- CLI Tools

**⭐⭐ Средний уровень:**
- Параметры Routeов
- Группы Routeов
- IP Filtering
- Middleware
- Route Macros
- URL Generation
- Кеширование
- Загрузчики
- Action Resolver
- Статистика

**⭐⭐⭐ Продвинутый уровень:**
- Rate Limiting & Auto-Ban
- Expression Language
- Плагины
- PSR Support
- Sicherheit

### По Kategorieм использования

**Маршрутизация:**
- Базовая Routing
- Параметры Routeов
- Группы Routeов
- Именованные Routen
- URL Generation

**Sicherheit:**
- Rate Limiting & Auto-Ban
- IP Filtering
- Middleware
- Sicherheit

**Организация кода:**
- Группы Routeов
- Теги
- Route Macros
- Namespace

**Leistung:**
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
- [COMPARISON.md](COMPARISON.md) - Vergleich mit Alternativen
- [SECURITY_REPORT.md](SECURITY_REPORT.md) - Sicherheitsbericht
- [PERFORMANCE_ANALYSIS.md](PERFORMANCE_ANALYSIS.md) - Leistungsanalyse
- [FAQ.md](FAQ.md) - Häufig gestellte Fragen

---

**© 2024 CloudCastle HTTP Router**  
**Version:** 1.1.1  
**Лицензия:** MIT

[⬆ Наверх](#индекс-всех-возможностей-cloudcastle-http-router)


---

## 📚 Dokumentationsnavigation

[README](../../README.md) | [USER_GUIDE](USER_GUIDE.md) | [FEATURES_INDEX](FEATURES_INDEX.md) | [API_REFERENCE](API_REFERENCE.md) | [ALL_FEATURES](ALL_FEATURES.md) | [TESTS_SUMMARY](TESTS_SUMMARY.md) | [PERFORMANCE](PERFORMANCE_ANALYSIS.md) | [SECURITY](SECURITY_REPORT.md) | [COMPARISON](COMPARISON.md) | [FAQ](FAQ.md) | [DOC_SUMMARY](DOCUMENTATION_SUMMARY.md)

**Детальная документация:** [Features](features/) (22 Dateiа) | [Tests](tests/) (7 Berichte)

---

