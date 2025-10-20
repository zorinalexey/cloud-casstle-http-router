# Индекс всех возможностей CloudCastle HTTP Router

---

## 📚 Navigation de la Documentation

[README](../../README.md) | [USER_GUIDE](USER_GUIDE.md) | [FEATURES_INDEX](FEATURES_INDEX.md) | [API_REFERENCE](API_REFERENCE.md) | [ALL_FEATURES](ALL_FEATURES.md) | [TESTS_SUMMARY](TESTS_SUMMARY.md) | [PERFORMANCE](PERFORMANCE_ANALYSIS.md) | [SECURITY](SECURITY_REPORT.md) | [COMPARISON](COMPARISON.md) | [FAQ](FAQ.md) | [DOC_SUMMARY](DOCUMENTATION_SUMMARY.md)

**Детальная документация:** [Features](features/) (22 fichierа) | [Tests](tests/) (7 rapports)

---


**Version:** 1.1.1  
**Всего возможностей:** 209+  
**Категорий:** 23

---

## 📖 Как пользоваться этим индексом

Этот документ содержит полный список всех 209+ возможностей библиотеки, организованных по catégorieм. Для каждой catégories указаны:
- Количество méthodeов/возможностей
- Ссылка на детальную документацию
- Краткое описание
- Основные méthodes

---

## 🗂️ Категории возможностей

### 1. Базовая routage (13 méthodeов)

**Сложность:** ⭐ Начальный  
**Documentation:** [01_BASIC_ROUTING.md](features/01_BASIC_ROUTING.md)

Регистрация обработчиков для различных HTTP méthodeов и URI.

**Основные méthodes:**
- `Route::get()` - GET requêtes
- `Route::post()` - POST requêtes
- `Route::put()` - PUT requêtes (полное обновление)
- `Route::patch()` - PATCH requêtes (частичное обновление)
- `Route::delete()` - DELETE requêtes
- `Route::view()` - Кастомный méthode VIEW
- `Route::custom()` - Любой HTTP méthode
- `Route::match()` - Несколько méthodeов
- `Route::any()` - Все HTTP méthodes
- `Router::getInstance()` - Singleton
- Facade API - Статический интерфейс

---

### 2. Параметры routeов (6 способов)

**Сложность:** ⭐⭐ Средний  
**Documentation:** [02_ROUTE_PARAMETERS.md](features/02_ROUTE_PARAMETERS.md)

Динамические paramètres в URI с валидацией и значениями по умолчанию.

**Fonctionnalités Principales:**
- `{id}` - Базовые paramètres
- `where('id', '[0-9]+')` - Ограничения (regex)
- `{id:[0-9]+}` - Inline paramètres
- `{page?}` - Опциональные paramètres
- `defaults(['page' => 1])` - Значения по умолчанию
- `getParameters()` - Получение paramètreов

---

### 3. Группы routeов (12 атрибутов)

**Сложность:** ⭐⭐ Средний  
**Documentation:** [03_ROUTE_GROUPS.md](features/03_ROUTE_GROUPS.md)

Организация routeов с общими атрибутами.

**Атрибуты групп:**
- `prefix` - Префикс URI
- `middleware` - Общий middleware
- `domain` - Привязка к домену
- `port` - Привязка к порту
- `namespace` - Namespace contrôleurов
- `https` - Требование HTTPS
- `protocols` - Разрешенные протоколы
- `tags` - Теги для groupes
- `throttle` - Rate limiting
- `whitelistIp` - IP whitelist
- `blacklistIp` - IP blacklist
- `name` - Префикс имени

---

### 4. Rate Limiting & Auto-Ban (15 méthodeов)

**Сложность:** ⭐⭐⭐ Продвинутый  
**Documentation:** [04_RATE_LIMITING.md](features/04_RATE_LIMITING.md)

Защита от DDoS, брут-форса и злоупотреблений.

**Rate Limiting (8 méthodeов):**
- `throttle(60, 1)` - Базовый лимит
- `TimeUnit` enum - Единицы времени
- Кастомный ключ - По пользователю/API ключу
- `RateLimiter` класс - Программное управление
- `throttleStandard()` - 60 req/min
- `throttleStrict()` - 10 req/min
- `throttleGenerous()` - 1000 req/min

**Auto-Ban (7 méthodeов):**
- `BanManager` - Управление банами
- `enableAutoBan(5)` - Включить автобан
- `ban($ip, $duration)` - Забанить IP
- `unban($ip)` - Разбанить
- `isBanned($ip)` - Проверить бан
- `getBannedIps()` - Список забаненных
- `clearAll()` - Очистить все баны

---

### 5. IP Filtering (4 méthodeа)

**Сложность:** ⭐⭐ Средний  
**Documentation:** В разработке

Контроль доступа по IP адресам.

**Методы:**
- `whitelistIp([...])` - Разрешить только указанные IP
- `blacklistIp([...])` - Запретить указанные IP
- CIDR нотация - Поддержка подсетей
- IP Spoofing защита - Проверка X-Forwarded-For

---

### 6. Middleware (6 типов)

**Сложность:** ⭐⭐ Средний  
**Documentation:** В разработке

Промежуточная обработка requêteов.

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

### 7. Именованные routes (6 méthodeов)

**Сложность:** ⭐ Начальный  
**Documentation:** В разработке

Присвоение имен routeам для удобной ссылки.

**Методы:**
- `name('users.show')` - Назначить имя
- `getRouteByName('users.show')` - Получить по имени
- `currentRouteName()` - Текущее имя
- `currentRouteNamed('users.*')` - Проверка
- `enableAutoNaming()` - Автоматические имена
- `getNamedRoutes()` - Все именованные

---

### 8. Теги (5 méthodeов)

**Сложность:** ⭐ Начальный  
**Documentation:** В разработке

Группировка routeов по тегам.

**Методы:**
- `tag('api')` - Добавить тег
- `tag(['api', 'public'])` - Множественные теги
- `getRoutesByTag('api')` - Получить по тегу
- `hasTag('api')` - Проверить наличие
- `getAllTags()` - Все теги

---

### 9. Helper Functions (18 функций)

**Сложность:** ⭐ Начальный  
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
- `route_back()` - Назад

---

### 10. Route Shortcuts (14 méthodeов)

**Сложность:** ⭐ Начальный  
**Documentation:** В разработке

Быстрые méthodes для типичных сценариев.

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

**Сложность:** ⭐⭐ Средний  
**Documentation:** В разработке

Быстрое создание групп связанных routeов.

**Макросы:**
- `resource()` - RESTful CRUD (7 routeов)
- `apiResource()` - API CRUD (5 routeов)
- `crud()` - Простой CRUD
- `auth()` - Маршруты аутентификации
- `adminPanel()` - Админ панель
- `apiVersion()` - Версионирование API
- `webhooks()` - Webhooks

---

### 12. URL Generation (11 méthodeов)

**Сложность:** ⭐⭐ Средний  
**Documentation:** В разработке

Генерация URL по именам routeов.

**UrlGenerator méthodes:**
- `generate($name, $params)` - Базовая генерация
- `absolute()` - Абсолютный URL
- `toDomain($domain)` - С доменом
- `toProtocol($protocol)` - С протоколом
- `signed($name, $params, $ttl)` - Подписанный URL
- `setBaseUrl($url)` - Базовый URL
- Query paramètres
- HATEOAS links
- `route_url()` helper

---

### 13. Expression Language (5 операторов)

**Сложность:** ⭐⭐⭐ Продвинутый  
**Documentation:** В разработке

Условия для routeов на основе выражений.

**Fonctionnalités:**
- `condition()` - Условие routeа
- Операторы сравнения: `==`, `!=`, `>`, `<`, `>=`, `<=`
- Логические операторы: `and`, `or`
- `ExpressionLanguage` класс
- `evaluate()` - Вычисление

---

### 14. Кеширование routeов (6 méthodeов)

**Сложность:** ⭐⭐ Средний  
**Documentation:** В разработке

Компиляция и кеширование для производительности.

**Методы:**
- `enableCache($dir)` - Включить кеш
- `compile()` - Компилировать
- `loadFromCache()` - Загрузить из кеша
- `clearCache()` - Очистить
- `autoCompile()` - Автокомпиляция
- `isCacheLoaded()` - Проверка загрузки

---

### 15. Система плагинов (13 méthodeов)

**Сложность:** ⭐⭐⭐ Продвинутый  
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
- `getPlugins()` - Все плагины

**Встроенные:**
- `LoggerPlugin`
- `AnalyticsPlugin`
- `ResponseCachePlugin`
- `AbstractPlugin`

---

### 16. Загрузчики routeов (5 типов)

**Сложность:** ⭐⭐ Средний  
**Documentation:** В разработке

Загрузка routeов из различных форматов.

**Loaders:**
- `JsonLoader` - JSON fichierы
- `YamlLoader` - YAML fichierы
- `XmlLoader` - XML fichierы
- `AttributeLoader` - PHP Attributes
- PHP fichierы - Обычный способ

---

### 17. PSR Support (3 стандарта)

**Сложность:** ⭐⭐⭐ Продвинутый  
**Documentation:** В разработке

Совместимость с PSR стандартами.

**Поддержка:**
- PSR-7 HTTP Message
- PSR-15 HTTP Server Handler
- `Psr15MiddlewareAdapter`

---

### 18. Action Resolver (6 форматов)

**Сложность:** ⭐⭐ Средний  
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

### 19. Статистика и requêtes (24 méthodeа)

**Сложность:** ⭐⭐ Средний  
**Documentation:** В разработке

Получение информации о зарегистрированных routeах.

**Методы:**
- `getRouteStats()` - Общая статистика
- `getRoutesByMethod()` - По méthodeу
- `getRoutesByDomain()` - По домену
- `getRoutesByPort()` - По порту
- `getRoutesByPrefix()` - По префиксу
- `getRoutesByMiddleware()` - По middleware
- `getRoutesByController()` - По contrôleurу
- `getThrottledRoutes()` - С лимитами
- `searchRoutes()` - Поиск
- `getRoutesGroupedByMethod()` - Группировка
- `count()` - Количество
- `getRoutesAsJson()` - В JSON
- `getRoutesAsArray()` - В массив
- И 11 других méthodeов

---

### 20. Sécurité (12 механизмов)

**Сложность:** ⭐⭐⭐ Продвинутый  
**Documentation:** В разработке

Встроенная защита от различных атак.

**Защита от:**
- Path Traversal - `../` атаки
- SQL Injection - Валидация paramètreов
- XSS - Экранирование
- ReDoS - Regex DoS
- Method Override - Подмена méthodeов
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

**Сложность:** ⭐ Начальный  
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

| Категория | Методов/Возможностей |
|-----------|---------------------|
| Базовая routage | 13 |
| Параметры routeов | 6 |
| Группы routeов | 12 |
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
| Sécurité | 12 |
| Исключения | 8 |
| CLI Tools | 3 |
| Supplémentaire | 10+ |
| **ИТОГО** | **209+** |

---

## 🔍 Быстрый поиск

### По сложности

**⭐ Начальный уровень:**
- Базовая routage
- Именованные routes
- Теги
- Helper Functions
- Route Shortcuts
- Исключения
- CLI Tools

**⭐⭐ Средний уровень:**
- Параметры routeов
- Группы routeов
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
- Sécurité

### По catégorieм использования

**Маршрутизация:**
- Базовая routage
- Параметры routeов
- Группы routeов
- Именованные routes
- URL Generation

**Sécurité:**
- Rate Limiting & Auto-Ban
- IP Filtering
- Middleware
- Sécurité

**Организация кода:**
- Группы routeов
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
- [COMPARISON.md](COMPARISON.md) - Comparaison avec les Alternatives
- [SECURITY_REPORT.md](SECURITY_REPORT.md) - Rapport de Sécurité
- [PERFORMANCE_ANALYSIS.md](PERFORMANCE_ANALYSIS.md) - Analyse de Performance
- [FAQ.md](FAQ.md) - Questions Fréquentes

---

**© 2024 CloudCastle HTTP Router**  
**Version:** 1.1.1  
**Лицензия:** MIT

[⬆ Наверх](#индекс-всех-возможностей-cloudcastle-http-router)


---

## 📚 Navigation de la Documentation

[README](../../README.md) | [USER_GUIDE](USER_GUIDE.md) | [FEATURES_INDEX](FEATURES_INDEX.md) | [API_REFERENCE](API_REFERENCE.md) | [ALL_FEATURES](ALL_FEATURES.md) | [TESTS_SUMMARY](TESTS_SUMMARY.md) | [PERFORMANCE](PERFORMANCE_ANALYSIS.md) | [SECURITY](SECURITY_REPORT.md) | [COMPARISON](COMPARISON.md) | [FAQ](FAQ.md) | [DOC_SUMMARY](DOCUMENTATION_SUMMARY.md)

**Детальная документация:** [Features](features/) (22 fichierа) | [Tests](tests/) (7 rapports)

---

