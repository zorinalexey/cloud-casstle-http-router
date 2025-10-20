# Index aller CloudCastle HTTP Router Funktionen

[English](../en/FEATURES_INDEX.md) | **Русский** | [Deutsch](../de/FEATURES_INDEX.md) | [Français](../fr/FEATURES_INDEX.md) | [中文](../zh/FEATURES_INDEX.md)

---







---

## 📚 Dokumentationsnavigation

[README](../../README.md) | [USER_GUIDE](USER_GUIDE.md) | [FEATURES_INDEX](FEATURES_INDEX.md) | [API_REFERENCE](API_REFERENCE.md) | [ALL_FEATURES](ALL_FEATURES.md) | [TESTS_SUMMARY](TESTS_SUMMARY.md) | [PERFORMANCE](PERFORMANCE_ANALYSIS.md) | [SECURITY](SECURITY_REPORT.md) | [COMPARISON](COMPARISON.md) | [FAQ](FAQ.md) | [DOC_SUMMARY](DOCUMENTATION_SUMMARY.md)

**Detaillierte Dokumentation:** [Features](features/) (22 Dateien) | [Tests](tests/) (7 Berichte)

---


**Version:** 1.1.1  
**Gesamt Funktionen:** 209+  
**Kategorien:** 23

---

## 📖 Wie man diesen Index verwendet

Dieses Dokument enthält eine vollständige Liste aller 209+ Bibliotheksfunktionen, organisiert nach Kategorieм. Für jede Kategorie werden angegeben:
- Anzahl der Methoden/Funktionen
- Link zur detaillierten Dokumentation
- Kurzbeschreibung
- Hauptmethoden

---

## 🗂️ Funktionskategorien

### 1. Basis Routing (13 Methoden)

**Komplexität:** ⭐ Anfänger  
**Dokumentation:** [01_BASIC_ROUTING.md](features/01_BASIC_ROUTING.md)

Registrierung von Handlern für verschiedene HTTP Methoden и URI.

**Hauptmethoden:**
- `Route::get()` - GET Anfrageы
- `Route::post()` - POST Anfrageы
- `Route::put()` - PUT Anfrageы (полное обновление)
- `Route::patch()` - PATCH Anfrageы (частичное обновление)
- `Route::delete()` - DELETE Anfrageы
- `Route::view()` - Benutzerdefiniert Methode VIEW
- `Route::custom()` - Beliebig HTTP Methode
- `Route::match()` - Mehrere Methoden
- `Route::any()` - Alle HTTP Methoden
- `Router::getInstance()` - Singleton
- Facade API - Statische Schnittstelle

---

### 2. Parameter Routeов (6 Wege)

**Komplexität:** ⭐⭐ Mittel  
**Dokumentation:** [02_ROUTE_PARAMETERS.md](features/02_ROUTE_PARAMETERS.md)

Dynamisch Parameter в URI mit Validierung und Standardwerten.

**Hauptfunktionen:**
- `{id}` - Basis Parameter
- `where('id', '[0-9]+')` - Einschränkungen (regex)
- `{id:[0-9]+}` - Inline Parameter
- `{page?}` - Optional Parameter
- `defaults(['page' => 1])` - Standardwerte
- `getParameters()` - Abrufen Parameter

---

### 3. Gruppen Routeов (12 Attribute)

**Komplexität:** ⭐⭐ Mittel  
**Dokumentation:** [03_ROUTE_GROUPS.md](features/03_ROUTE_GROUPS.md)

Organisation Routeов mit gemeinsamen Attributen.

**Gruppenattribute:**
- `prefix` - Präfix URI
- `middleware` - Gemeinsam middleware
- `domain` - Привязка к домену
- `port` - Привязка к порту
- `namespace` - Namespace Controllerов
- `https` - Требование HTTPS
- `protocols` - Разрешенные протоколы
- `tags` - Теги для Gruppen
- `throttle` - Rate limiting
- `whitelistIp` - IP whitelist
- `blacklistIp` - IP blacklist
- `name` - Präfix имени

---

### 4. Rate Limiting & Auto-Ban (15 Methoden)

**Komplexität:** ⭐⭐⭐ Fortgeschritten  
**Dokumentation:** [04_RATE_LIMITING.md](features/04_RATE_LIMITING.md)

Защита от DDoS, брут-форса и злоупотреблений.

**Rate Limiting (8 Methoden):**
- `throttle(60, 1)` - Базовый лимит
- `TimeUnit` enum - Единицы времени
- Benutzerdefiniert ключ - По пользователю/API ключу
- `RateLimiter` класс - Программное управление
- `throttleStandard()` - 60 req/min
- `throttleStrict()` - 10 req/min
- `throttleGenerous()` - 1000 req/min

**Auto-Ban (7 Methoden):**
- `BanManager` - Управление банами
- `enableAutoBan(5)` - Включить автобан
- `ban($ip, $duration)` - Забанить IP
- `unban($ip)` - Разбанить
- `isBanned($ip)` - Проверить бан
- `getBannedIps()` - Список забаненных
- `clearAll()` - Очистить alle баны

---

### 5. IP Filtering (4 Methodeа)

**Komplexität:** ⭐⭐ Mittel  
**Dokumentation:** В разработке

Контроль доступа по IP адресам.

**Methoden:**
- `whitelistIp([...])` - Разрешить только указанные IP
- `blacklistIp([...])` - Запретить указанные IP
- CIDR нотация - Поддержка подсетей
- IP Spoofing защита - Проверка X-Forwarded-For

---

### 6. Middleware (6 типов)

**Komplexität:** ⭐⭐ Mittel  
**Dokumentation:** В разработке

Промежуточная обработка Anfragen.

**Встроенные middleware:**
- `AuthMiddleware` - Аутентификация
- `CorsMiddleware` - CORS заголовки
- `HttpsEnforcement` - Принудительный HTTPS
- `SecurityLogger` - Логирование безопасности
- `SsrfProtection` - Защита от SSRF
- `MiddlewareDispatcher` - Диспетчер

**Применение:**
- Глобальный middleware
- На Routeе
- В группе
- PSR-15 совместимость

---

### 7. Именованные Routen (6 Methoden)

**Komplexität:** ⭐ Anfänger  
**Dokumentation:** В разработке

Присвоение имен Routeам для удобной ссылки.

**Methoden:**
- `name('users.show')` - Назначить имя
- `getRouteByName('users.show')` - Получить по имени
- `currentRouteName()` - Текущее имя
- `currentRouteNamed('users.*')` - Проверка
- `enableAutoNaming()` - Автоматические имена
- `getNamedRoutes()` - Alle именованные

---

### 8. Теги (5 Methoden)

**Komplexität:** ⭐ Anfänger  
**Dokumentation:** В разработке

Группировка Routeов по тегам.

**Methoden:**
- `tag('api')` - Добавить тег
- `tag(['api', 'public'])` - Множественные теги
- `getRoutesByTag('api')` - Получить по тегу
- `hasTag('api')` - Проверить наличие
- `getAllTags()` - Alle теги

---

### 9. Helper Functions (18 функций)

**Komplexität:** ⭐ Anfänger  
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
- `route_back()` - Zurück

---

### 10. Route Shortcuts (14 Methoden)

**Komplexität:** ⭐ Anfänger  
**Dokumentation:** В разработке

Быстрые Methoden для типичных сценариев.

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

**Komplexität:** ⭐⭐ Mittel  
**Dokumentation:** В разработке

Быстрое создание групп связанных Routeов.

**Макросы:**
- `resource()` - RESTful CRUD (7 Routeов)
- `apiResource()` - API CRUD (5 Routeов)
- `crud()` - Простой CRUD
- `auth()` - Routen аутентификации
- `adminPanel()` - Админ панель
- `apiVersion()` - Версионирование API
- `webhooks()` - Webhooks

---

### 12. URL Generation (11 Methoden)

**Komplexität:** ⭐⭐ Mittel  
**Dokumentation:** В разработке

Генерация URL по именам Routeов.

**UrlGenerator Methoden:**
- `generate($name, $params)` - Basis генерация
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

**Komplexität:** ⭐⭐⭐ Fortgeschritten  
**Dokumentation:** В разработке

Условия для Routeов на основе выражений.

**Funktionen:**
- `condition()` - Условие Routeа
- Операторы сравнения: `==`, `!=`, `>`, `<`, `>=`, `<=`
- Логические операторы: `and`, `or`
- `ExpressionLanguage` класс
- `evaluate()` - Вычисление

---

### 14. Кеширование Routeов (6 Methoden)

**Komplexität:** ⭐⭐ Mittel  
**Dokumentation:** В разработке

Компиляция и кеширование для производительности.

**Methoden:**
- `enableCache($dir)` - Включить кеш
- `compile()` - Компилировать
- `loadFromCache()` - Загрузить из кеша
- `clearCache()` - Очистить
- `autoCompile()` - Автокомпиляция
- `isCacheLoaded()` - Проверка загрузки

---

### 15. Система плагинов (13 Methoden)

**Komplexität:** ⭐⭐⭐ Fortgeschritten  
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
- `getPlugins()` - Alle плагины

**Встроенные:**
- `LoggerPlugin`
- `AnalyticsPlugin`
- `ResponseCachePlugin`
- `AbstractPlugin`

---

### 16. Загрузчики Routeов (5 типов)

**Komplexität:** ⭐⭐ Mittel  
**Dokumentation:** В разработке

Загрузка Routeов из различных форматов.

**Loaders:**
- `JsonLoader` - JSON Dateiы
- `YamlLoader` - YAML Dateiы
- `XmlLoader` - XML Dateiы
- `AttributeLoader` - PHP Attributes
- PHP Dateiы - Обычный Weg

---

### 17. PSR Support (3 стандарта)

**Komplexität:** ⭐⭐⭐ Fortgeschritten  
**Dokumentation:** В разработке

Совместимость с PSR стандартами.

**Поддержка:**
- PSR-7 HTTP Message
- PSR-15 HTTP Server Handler
- `Psr15MiddlewareAdapter`

---

### 18. Action Resolver (6 форматов)

**Komplexität:** ⭐⭐ Mittel  
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

### 19. Статистика и Anfrageы (24 Methodeа)

**Komplexität:** ⭐⭐ Mittel  
**Dokumentation:** В разработке

Abrufen информации о зарегистрированных Routeах.

**Methoden:**
- `getRouteStats()` - Общая статистика
- `getRoutesByMethod()` - По Methodeу
- `getRoutesByDomain()` - По домену
- `getRoutesByPort()` - По порту
- `getRoutesByPrefix()` - По Präfixу
- `getRoutesByMiddleware()` - По middleware
- `getRoutesByController()` - По Controllerу
- `getThrottledRoutes()` - С лимитами
- `searchRoutes()` - Поиск
- `getRoutesGroupedByMethod()` - Группировка
- `count()` - Anzahl der
- `getRoutesAsJson()` - В JSON
- `getRoutesAsArray()` - В массив
- И 11 других Methoden

---

### 20. Sicherheit (12 механизмов)

**Komplexität:** ⭐⭐⭐ Fortgeschritten  
**Dokumentation:** В разработке

Встроенная защита от различных атак.

**Защита от:**
- Path Traversal - `../` атаки
- SQL Injection - Validierung Parameter
- XSS - Экранирование
- ReDoS - Regex DoS
- Method Override - Подмена Methoden
- Cache Injection - Безопасный кеш
- IP Spoofing - Проверка заголовков
- DDoS - Rate limiting
- Брут-форс - Auto-ban
- SSRF - SsrfProtection
- Protocol enforcement - HTTP/HTTPS/WS/WSS
- Resource exhaustion - Лимиты

---

### 21. Исключения (8 типов)

**Komplexität:** ⭐ Anfänger  
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

**Komplexität:** ⭐ Anfänger  
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

| Kategorie | Methodeов/Funktionen |
|-----------|---------------------|
| Basis Routing | 13 |
| Parameter Routeов | 6 |
| Gruppen Routeов | 12 |
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

**⭐ Anfänger уровень:**
- Basis Routing
- Именованные Routen
- Теги
- Helper Functions
- Route Shortcuts
- Исключения
- CLI Tools

**⭐⭐ Mittel уровень:**
- Parameter Routeов
- Gruppen Routeов
- IP Filtering
- Middleware
- Route Macros
- URL Generation
- Кеширование
- Загрузчики
- Action Resolver
- Статистика

**⭐⭐⭐ Fortgeschritten уровень:**
- Rate Limiting & Auto-Ban
- Expression Language
- Плагины
- PSR Support
- Sicherheit

### По Kategorieм использования

**Routing:**
- Basis Routing
- Parameter Routeов
- Gruppen Routeов
- Именованные Routen
- URL Generation

**Sicherheit:**
- Rate Limiting & Auto-Ban
- IP Filtering
- Middleware
- Sicherheit

**Organisation кода:**
- Gruppen Routeов
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

**Detaillierte Dokumentation:** [Features](features/) (22 Dateien) | [Tests](tests/) (7 Berichte)

---

