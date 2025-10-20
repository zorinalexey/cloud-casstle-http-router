# Индекс всех возможностей CloudCastle HTTP Router

[English](../en/FEATURES_INDEX.md) | **Русский** | [Deutsch](../de/FEATURES_INDEX.md) | [Français](../fr/FEATURES_INDEX.md) | [中文](../zh/FEATURES_INDEX.md)

---







---

## 📚 Навигация по документации

[README](../../README.md) | [USER_GUIDE](USER_GUIDE.md) | [FEATURES_INDEX](FEATURES_INDEX.md) | [API_REFERENCE](API_REFERENCE.md) | [ALL_FEATURES](ALL_FEATURES.md) | [TESTS_SUMMARY](TESTS_SUMMARY.md) | [PERFORMANCE](PERFORMANCE_ANALYSIS.md) | [SECURITY](SECURITY_REPORT.md) | [COMPARISON](COMPARISON.md) | [FAQ](FAQ.md) | [DOC_SUMMARY](DOCUMENTATION_SUMMARY.md)

**Детальная документация:** [Features](features/) (22 файла) | [Tests](tests/) (7 отчетов)

---


**Версия:** 1.1.1  
**Всего возможностей:** 209+  
**Категорий:** 23

---

## 📖 Как пользоваться этим индексом

Этот документ содержит полный список всех 209+ возможностей библиотеки, организованных по категориям. Для каждой категории указаны:
- Количество методов/возможностей
- Ссылка на детальную документацию
- Краткое описание
- Основные методы

---

## 🗂️ Категории возможностей

### 1. Базовая маршрутизация (13 методов)

**Сложность:** ⭐ Начальный  
**Документация:** [01_BASIC_ROUTING.md](features/01_BASIC_ROUTING.md)

Регистрация обработчиков для различных HTTP методов и URI.

**Основные методы:**
- `Route::get()` - GET запросы
- `Route::post()` - POST запросы
- `Route::put()` - PUT запросы (полное обновление)
- `Route::patch()` - PATCH запросы (частичное обновление)
- `Route::delete()` - DELETE запросы
- `Route::view()` - Кастомный метод VIEW
- `Route::custom()` - Любой HTTP метод
- `Route::match()` - Несколько методов
- `Route::any()` - Все HTTP методы
- `Router::getInstance()` - Singleton
- Facade API - Статический интерфейс

---

### 2. Параметры маршрутов (6 способов)

**Сложность:** ⭐⭐ Средний  
**Документация:** [02_ROUTE_PARAMETERS.md](features/02_ROUTE_PARAMETERS.md)

Динамические параметры в URI с валидацией и значениями по умолчанию.

**Основные возможности:**
- `{id}` - Базовые параметры
- `where('id', '[0-9]+')` - Ограничения (regex)
- `{id:[0-9]+}` - Inline параметры
- `{page?}` - Опциональные параметры
- `defaults(['page' => 1])` - Значения по умолчанию
- `getParameters()` - Получение параметров

---

### 3. Группы маршрутов (12 атрибутов)

**Сложность:** ⭐⭐ Средний  
**Документация:** [03_ROUTE_GROUPS.md](features/03_ROUTE_GROUPS.md)

Организация маршрутов с общими атрибутами.

**Атрибуты групп:**
- `prefix` - Префикс URI
- `middleware` - Общий middleware
- `domain` - Привязка к домену
- `port` - Привязка к порту
- `namespace` - Namespace контроллеров
- `https` - Требование HTTPS
- `protocols` - Разрешенные протоколы
- `tags` - Теги для группы
- `throttle` - Rate limiting
- `whitelistIp` - IP whitelist
- `blacklistIp` - IP blacklist
- `name` - Префикс имени

---

### 4. Rate Limiting & Auto-Ban (15 методов)

**Сложность:** ⭐⭐⭐ Продвинутый  
**Документация:** [04_RATE_LIMITING.md](features/04_RATE_LIMITING.md)

Защита от DDoS, брут-форса и злоупотреблений.

**Rate Limiting (8 методов):**
- `throttle(60, 1)` - Базовый лимит
- `TimeUnit` enum - Единицы времени
- Кастомный ключ - По пользователю/API ключу
- `RateLimiter` класс - Программное управление
- `throttleStandard()` - 60 req/min
- `throttleStrict()` - 10 req/min
- `throttleGenerous()` - 1000 req/min

**Auto-Ban (7 методов):**
- `BanManager` - Управление банами
- `enableAutoBan(5)` - Включить автобан
- `ban($ip, $duration)` - Забанить IP
- `unban($ip)` - Разбанить
- `isBanned($ip)` - Проверить бан
- `getBannedIps()` - Список забаненных
- `clearAll()` - Очистить все баны

---

### 5. IP Filtering (4 метода)

**Сложность:** ⭐⭐ Средний  
**Документация:** В разработке

Контроль доступа по IP адресам.

**Методы:**
- `whitelistIp([...])` - Разрешить только указанные IP
- `blacklistIp([...])` - Запретить указанные IP
- CIDR нотация - Поддержка подсетей
- IP Spoofing защита - Проверка X-Forwarded-For

---

### 6. Middleware (6 типов)

**Сложность:** ⭐⭐ Средний  
**Документация:** В разработке

Промежуточная обработка запросов.

**Встроенные middleware:**
- `AuthMiddleware` - Аутентификация
- `CorsMiddleware` - CORS заголовки
- `HttpsEnforcement` - Принудительный HTTPS
- `SecurityLogger` - Логирование безопасности
- `SsrfProtection` - Защита от SSRF
- `MiddlewareDispatcher` - Диспетчер

**Применение:**
- Глобальный middleware
- На маршруте
- В группе
- PSR-15 совместимость

---

### 7. Именованные маршруты (6 методов)

**Сложность:** ⭐ Начальный  
**Документация:** В разработке

Присвоение имен маршрутам для удобной ссылки.

**Методы:**
- `name('users.show')` - Назначить имя
- `getRouteByName('users.show')` - Получить по имени
- `currentRouteName()` - Текущее имя
- `currentRouteNamed('users.*')` - Проверка
- `enableAutoNaming()` - Автоматические имена
- `getNamedRoutes()` - Все именованные

---

### 8. Теги (5 методов)

**Сложность:** ⭐ Начальный  
**Документация:** В разработке

Группировка маршрутов по тегам.

**Методы:**
- `tag('api')` - Добавить тег
- `tag(['api', 'public'])` - Множественные теги
- `getRoutesByTag('api')` - Получить по тегу
- `hasTag('api')` - Проверить наличие
- `getAllTags()` - Все теги

---

### 9. Helper Functions (18 функций)

**Сложность:** ⭐ Начальный  
**Документация:** [09_HELPER_FUNCTIONS.md](features/09_HELPER_FUNCTIONS.md)

Глобальные PHP функции для упрощения работы.

**Функции:**
- `route($name)` - Получить маршрут
- `current_route()` - Текущий маршрут
- `previous_route()` - Предыдущий маршрут
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

### 10. Route Shortcuts (14 методов)

**Сложность:** ⭐ Начальный  
**Документация:** В разработке

Быстрые методы для типичных сценариев.

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
**Документация:** В разработке

Быстрое создание групп связанных маршрутов.

**Макросы:**
- `resource()` - RESTful CRUD (7 маршрутов)
- `apiResource()` - API CRUD (5 маршрутов)
- `crud()` - Простой CRUD
- `auth()` - Маршруты аутентификации
- `adminPanel()` - Админ панель
- `apiVersion()` - Версионирование API
- `webhooks()` - Webhooks

---

### 12. URL Generation (11 методов)

**Сложность:** ⭐⭐ Средний  
**Документация:** В разработке

Генерация URL по именам маршрутов.

**UrlGenerator методы:**
- `generate($name, $params)` - Базовая генерация
- `absolute()` - Абсолютный URL
- `toDomain($domain)` - С доменом
- `toProtocol($protocol)` - С протоколом
- `signed($name, $params, $ttl)` - Подписанный URL
- `setBaseUrl($url)` - Базовый URL
- Query параметры
- HATEOAS links
- `route_url()` helper

---

### 13. Expression Language (5 операторов)

**Сложность:** ⭐⭐⭐ Продвинутый  
**Документация:** В разработке

Условия для маршрутов на основе выражений.

**Возможности:**
- `condition()` - Условие маршрута
- Операторы сравнения: `==`, `!=`, `>`, `<`, `>=`, `<=`
- Логические операторы: `and`, `or`
- `ExpressionLanguage` класс
- `evaluate()` - Вычисление

---

### 14. Кеширование маршрутов (6 методов)

**Сложность:** ⭐⭐ Средний  
**Документация:** В разработке

Компиляция и кеширование для производительности.

**Методы:**
- `enableCache($dir)` - Включить кеш
- `compile()` - Компилировать
- `loadFromCache()` - Загрузить из кеша
- `clearCache()` - Очистить
- `autoCompile()` - Автокомпиляция
- `isCacheLoaded()` - Проверка загрузки

---

### 15. Система плагинов (13 методов)

**Сложность:** ⭐⭐⭐ Продвинутый  
**Документация:** В разработке

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

### 16. Загрузчики маршрутов (5 типов)

**Сложность:** ⭐⭐ Средний  
**Документация:** В разработке

Загрузка маршрутов из различных форматов.

**Loaders:**
- `JsonLoader` - JSON файлы
- `YamlLoader` - YAML файлы
- `XmlLoader` - XML файлы
- `AttributeLoader` - PHP Attributes
- PHP файлы - Обычный способ

---

### 17. PSR Support (3 стандарта)

**Сложность:** ⭐⭐⭐ Продвинутый  
**Документация:** В разработке

Совместимость с PSR стандартами.

**Поддержка:**
- PSR-7 HTTP Message
- PSR-15 HTTP Server Handler
- `Psr15MiddlewareAdapter`

---

### 18. Action Resolver (6 форматов)

**Сложность:** ⭐⭐ Средний  
**Документация:** В разработке

Различные форматы действий маршрутов.

**Форматы:**
- Closure - `function() { }`
- Array - `[Controller::class, 'method']`
- String "Controller@method"
- String "Controller::method"
- Invokable - `Controller::class`
- Dependency Injection

---

### 19. Статистика и запросы (24 метода)

**Сложность:** ⭐⭐ Средний  
**Документация:** В разработке

Получение информации о зарегистрированных маршрутах.

**Методы:**
- `getRouteStats()` - Общая статистика
- `getRoutesByMethod()` - По методу
- `getRoutesByDomain()` - По домену
- `getRoutesByPort()` - По порту
- `getRoutesByPrefix()` - По префиксу
- `getRoutesByMiddleware()` - По middleware
- `getRoutesByController()` - По контроллеру
- `getThrottledRoutes()` - С лимитами
- `searchRoutes()` - Поиск
- `getRoutesGroupedByMethod()` - Группировка
- `count()` - Количество
- `getRoutesAsJson()` - В JSON
- `getRoutesAsArray()` - В массив
- И 11 других методов

---

### 20. Безопасность (12 механизмов)

**Сложность:** ⭐⭐⭐ Продвинутый  
**Документация:** В разработке

Встроенная защита от различных атак.

**Защита от:**
- Path Traversal - `../` атаки
- SQL Injection - Валидация параметров
- XSS - Экранирование
- ReDoS - Regex DoS
- Method Override - Подмена методов
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
**Документация:** В разработке

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
**Документация:** В разработке

Консольные утилиты для работы с маршрутами.

**Команды:**
- `routes-list` - Список маршрутов
- `analyse` - Анализ маршрутов
- `router` - Управление (compile, clear, stats)

---

### 23. Дополнительные возможности

**RouteCollection, RouteDumper, UrlMatcher:**
- RouteCollection - Коллекция маршрутов
- RouteDumper - Экспорт маршрутов
- UrlMatcher - Сопоставление URL
- Singleton pattern - Router::getInstance()
- Method chaining - Fluent API
- Current/Previous route - История

---

## 📊 Сводная статистика

| Категория | Методов/Возможностей |
|-----------|---------------------|
| Базовая маршрутизация | 13 |
| Параметры маршрутов | 6 |
| Группы маршрутов | 12 |
| Rate Limiting & Auto-Ban | 15 |
| IP Filtering | 4 |
| Middleware | 6 |
| Именованные маршруты | 6 |
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
| Безопасность | 12 |
| Исключения | 8 |
| CLI Tools | 3 |
| Дополнительно | 10+ |
| **ИТОГО** | **209+** |

---

## 🔍 Быстрый поиск

### По сложности

**⭐ Начальный уровень:**
- Базовая маршрутизация
- Именованные маршруты
- Теги
- Helper Functions
- Route Shortcuts
- Исключения
- CLI Tools

**⭐⭐ Средний уровень:**
- Параметры маршрутов
- Группы маршрутов
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
- Безопасность

### По категориям использования

**Маршрутизация:**
- Базовая маршрутизация
- Параметры маршрутов
- Группы маршрутов
- Именованные маршруты
- URL Generation

**Безопасность:**
- Rate Limiting & Auto-Ban
- IP Filtering
- Middleware
- Безопасность

**Организация кода:**
- Группы маршрутов
- Теги
- Route Macros
- Namespace

**Производительность:**
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
- [COMPARISON.md](COMPARISON.md) - Сравнение с аналогами
- [SECURITY_REPORT.md](SECURITY_REPORT.md) - Отчет по безопасности
- [PERFORMANCE_ANALYSIS.md](PERFORMANCE_ANALYSIS.md) - Анализ производительности
- [FAQ.md](FAQ.md) - Частые вопросы

---

**© 2024 CloudCastle HTTP Router**  
**Версия:** 1.1.1  
**Лицензия:** MIT

[⬆ Наверх](#индекс-всех-возможностей-cloudcastle-http-router)


---

## 📚 Навигация по документации

[README](../../README.md) | [USER_GUIDE](USER_GUIDE.md) | [FEATURES_INDEX](FEATURES_INDEX.md) | [API_REFERENCE](API_REFERENCE.md) | [ALL_FEATURES](ALL_FEATURES.md) | [TESTS_SUMMARY](TESTS_SUMMARY.md) | [PERFORMANCE](PERFORMANCE_ANALYSIS.md) | [SECURITY](SECURITY_REPORT.md) | [COMPARISON](COMPARISON.md) | [FAQ](FAQ.md) | [DOC_SUMMARY](DOCUMENTATION_SUMMARY.md)

**Детальная документация:** [Features](features/) (22 файла) | [Tests](tests/) (7 отчетов)

---

