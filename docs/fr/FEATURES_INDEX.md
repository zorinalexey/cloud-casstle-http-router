# Index de toutes les Fonctionnalités de CloudCastle HTTP Router

---

## 📚 Navigation de la Documentation

[README](../../README.md) | [USER_GUIDE](USER_GUIDE.md) | [FEATURES_INDEX](FEATURES_INDEX.md) | [API_REFERENCE](API_REFERENCE.md) | [ALL_FEATURES](ALL_FEATURES.md) | [TESTS_SUMMARY](TESTS_SUMMARY.md) | [PERFORMANCE](PERFORMANCE_ANALYSIS.md) | [SECURITY](SECURITY_REPORT.md) | [COMPARISON](COMPARISON.md) | [FAQ](FAQ.md) | [DOC_SUMMARY](DOCUMENTATION_SUMMARY.md)

**Documentation détaillée:** [Features](features/) (22 fichiers) | [Tests](tests/) (7 rapports)

---


**Version:** 1.1.1  
**Total des fonctionnalités:** 209+  
**Catégories:** 23

---

## 📖 Comment Utiliser cet Index

Ce document contient une liste complète de toutes les 209+ fonctionnalités de la bibliothèque, organisées par catégorieм. Pour chaque catégorie sont indiqués:
- Nombre de méthodes/fonctionnalités
- Lien vers la documentation détaillée
- Brève description
- Méthodes principales

---

## 🗂️ Catégories de Fonctionnalités

### 1. Base Routage (13 méthodes)

**Complexité:** ⭐ Débutant  
**Documentation:** [01_BASIC_ROUTING.md](features/01_BASIC_ROUTING.md)

Enregistrement de gestionnaires pour divers HTTP méthodes и URI.

**Méthodes principales:**
- `Route::get()` - GET requêteы
- `Route::post()` - POST requêteы
- `Route::put()` - PUT requêteы (полное обновление)
- `Route::patch()` - PATCH requêteы (частичное обновление)
- `Route::delete()` - DELETE requêteы
- `Route::view()` - Personnalisé méthode VIEW
- `Route::custom()` - Tout HTTP méthode
- `Route::match()` - Plusieurs méthodes
- `Route::any()` - Tous HTTP méthodes
- `Router::getInstance()` - Singleton
- Facade API - Interface statique

---

### 2. Paramètres routeов (6 façons)

**Complexité:** ⭐⭐ Intermédiaire  
**Documentation:** [02_ROUTE_PARAMETERS.md](features/02_ROUTE_PARAMETERS.md)

Dynamiques paramètres в URI avec validation et valeurs par défaut.

**Fonctionnalités Principales:**
- `{id}` - Basiques paramètres
- `where('id', '[0-9]+')` - Contraintes (regex)
- `{id:[0-9]+}` - Inline paramètres
- `{page?}` - Optionnels paramètres
- `defaults(['page' => 1])` - Valeurs par défaut
- `getParameters()` - Obtenir paramètres

---

### 3. Groupes routeов (12 attributs)

**Complexité:** ⭐⭐ Intermédiaire  
**Documentation:** [03_ROUTE_GROUPS.md](features/03_ROUTE_GROUPS.md)

Organisation routeов avec attributs partagés.

**Attributs de groupe:**
- `prefix` - Préfixe URI
- `middleware` - Partagé middleware
- `domain` - Привязка к домену
- `port` - Привязка к порту
- `namespace` - Namespace contrôleurов
- `https` - Требование HTTPS
- `protocols` - Разрешенные протоколы
- `tags` - Теги для groupes
- `throttle` - Rate limiting
- `whitelistIp` - IP whitelist
- `blacklistIp` - IP blacklist
- `name` - Préfixe имени

---

### 4. Rate Limiting & Auto-Ban (15 méthodes)

**Complexité:** ⭐⭐⭐ Avancé  
**Documentation:** [04_RATE_LIMITING.md](features/04_RATE_LIMITING.md)

Защита от DDoS, брут-форса и злоупотреблений.

**Rate Limiting (8 méthodes):**
- `throttle(60, 1)` - Базовый лимит
- `TimeUnit` enum - Единицы времени
- Personnalisé ключ - По пользователю/API ключу
- `RateLimiter` класс - Программное управление
- `throttleStandard()` - 60 req/min
- `throttleStrict()` - 10 req/min
- `throttleGenerous()` - 1000 req/min

**Auto-Ban (7 méthodes):**
- `BanManager` - Управление банами
- `enableAutoBan(5)` - Включить автобан
- `ban($ip, $duration)` - Забанить IP
- `unban($ip)` - Разбанить
- `isBanned($ip)` - Проверить бан
- `getBannedIps()` - Список забаненных
- `clearAll()` - Очистить tous баны

---

### 5. IP Filtering (4 méthodeа)

**Complexité:** ⭐⭐ Intermédiaire  
**Documentation:** В разработке

Контроль доступа по IP адресам.

**Méthodes:**
- `whitelistIp([...])` - Разрешить только указанные IP
- `blacklistIp([...])` - Запретить указанные IP
- CIDR нотация - Поддержка подсетей
- IP Spoofing защита - Проверка X-Forwarded-For

---

### 6. Middleware (6 типов)

**Complexité:** ⭐⭐ Intermédiaire  
**Documentation:** В разработке

Промежуточная обработка requêtes.

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

### 7. Именованные routes (6 méthodes)

**Complexité:** ⭐ Débutant  
**Documentation:** В разработке

Присвоение имен routeам для удобной ссылки.

**Méthodes:**
- `name('users.show')` - Назначить имя
- `getRouteByName('users.show')` - Получить по имени
- `currentRouteName()` - Текущее имя
- `currentRouteNamed('users.*')` - Проверка
- `enableAutoNaming()` - Автоматические имена
- `getNamedRoutes()` - Tous именованные

---

### 8. Теги (5 méthodes)

**Complexité:** ⭐ Débutant  
**Documentation:** В разработке

Группировка routeов по тегам.

**Méthodes:**
- `tag('api')` - Добавить тег
- `tag(['api', 'public'])` - Множественные теги
- `getRoutesByTag('api')` - Получить по тегу
- `hasTag('api')` - Проверить наличие
- `getAllTags()` - Tous теги

---

### 9. Helper Functions (18 функций)

**Complexité:** ⭐ Débutant  
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
- `route_back()` - Retour

---

### 10. Route Shortcuts (14 méthodes)

**Complexité:** ⭐ Débutant  
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

**Complexité:** ⭐⭐ Intermédiaire  
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

### 12. URL Generation (11 méthodes)

**Complexité:** ⭐⭐ Intermédiaire  
**Documentation:** В разработке

Генерация URL по именам routeов.

**UrlGenerator méthodes:**
- `generate($name, $params)` - Base генерация
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

**Complexité:** ⭐⭐⭐ Avancé  
**Documentation:** В разработке

Условия для routeов на основе выражений.

**Fonctionnalités:**
- `condition()` - Условие routeа
- Операторы сравнения: `==`, `!=`, `>`, `<`, `>=`, `<=`
- Логические операторы: `and`, `or`
- `ExpressionLanguage` класс
- `evaluate()` - Вычисление

---

### 14. Кеширование routeов (6 méthodes)

**Complexité:** ⭐⭐ Intermédiaire  
**Documentation:** В разработке

Компиляция и кеширование для производительности.

**Méthodes:**
- `enableCache($dir)` - Включить кеш
- `compile()` - Компилировать
- `loadFromCache()` - Загрузить из кеша
- `clearCache()` - Очистить
- `autoCompile()` - Автокомпиляция
- `isCacheLoaded()` - Проверка загрузки

---

### 15. Система плагинов (13 méthodes)

**Complexité:** ⭐⭐⭐ Avancé  
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
- `getPlugins()` - Tous плагины

**Встроенные:**
- `LoggerPlugin`
- `AnalyticsPlugin`
- `ResponseCachePlugin`
- `AbstractPlugin`

---

### 16. Загрузчики routeов (5 типов)

**Complexité:** ⭐⭐ Intermédiaire  
**Documentation:** В разработке

Загрузка routeов из различных форматов.

**Loaders:**
- `JsonLoader` - JSON fichierы
- `YamlLoader` - YAML fichierы
- `XmlLoader` - XML fichierы
- `AttributeLoader` - PHP Attributes
- PHP fichierы - Обычный façon

---

### 17. PSR Support (3 стандарта)

**Complexité:** ⭐⭐⭐ Avancé  
**Documentation:** В разработке

Совместимость с PSR стандартами.

**Поддержка:**
- PSR-7 HTTP Message
- PSR-15 HTTP Server Handler
- `Psr15MiddlewareAdapter`

---

### 18. Action Resolver (6 форматов)

**Complexité:** ⭐⭐ Intermédiaire  
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

### 19. Статистика и requêteы (24 méthodeа)

**Complexité:** ⭐⭐ Intermédiaire  
**Documentation:** В разработке

Obtenir информации о зарегистрированных routeах.

**Méthodes:**
- `getRouteStats()` - Общая статистика
- `getRoutesByMethod()` - По méthodeу
- `getRoutesByDomain()` - По домену
- `getRoutesByPort()` - По порту
- `getRoutesByPrefix()` - По préfixeу
- `getRoutesByMiddleware()` - По middleware
- `getRoutesByController()` - По contrôleurу
- `getThrottledRoutes()` - С лимитами
- `searchRoutes()` - Поиск
- `getRoutesGroupedByMethod()` - Группировка
- `count()` - Nombre de
- `getRoutesAsJson()` - В JSON
- `getRoutesAsArray()` - В массив
- И 11 других méthodes

---

### 20. Sécurité (12 механизмов)

**Complexité:** ⭐⭐⭐ Avancé  
**Documentation:** В разработке

Встроенная защита от различных атак.

**Защита от:**
- Path Traversal - `../` атаки
- SQL Injection - Validation paramètres
- XSS - Экранирование
- ReDoS - Regex DoS
- Method Override - Подмена méthodes
- Cache Injection - Безопасный кеш
- IP Spoofing - Проверка заголовков
- DDoS - Rate limiting
- Брут-форс - Auto-ban
- SSRF - SsrfProtection
- Protocol enforcement - HTTP/HTTPS/WS/WSS
- Resource exhaustion - Лимиты

---

### 21. Исключения (8 типов)

**Complexité:** ⭐ Débutant  
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

**Complexité:** ⭐ Débutant  
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

| Catégorie | Méthodeов/Fonctionnalités |
|-----------|---------------------|
| Base Routage | 13 |
| Paramètres routeов | 6 |
| Groupes routeов | 12 |
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

**⭐ Débutant уровень:**
- Base Routage
- Именованные routes
- Теги
- Helper Functions
- Route Shortcuts
- Исключения
- CLI Tools

**⭐⭐ Intermédiaire уровень:**
- Paramètres routeов
- Groupes routeов
- IP Filtering
- Middleware
- Route Macros
- URL Generation
- Кеширование
- Загрузчики
- Action Resolver
- Статистика

**⭐⭐⭐ Avancé уровень:**
- Rate Limiting & Auto-Ban
- Expression Language
- Плагины
- PSR Support
- Sécurité

### По catégorieм использования

**Routage:**
- Base Routage
- Paramètres routeов
- Groupes routeов
- Именованные routes
- URL Generation

**Sécurité:**
- Rate Limiting & Auto-Ban
- IP Filtering
- Middleware
- Sécurité

**Organisation кода:**
- Groupes routeов
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

**Documentation détaillée:** [Features](features/) (22 fichiers) | [Tests](tests/) (7 rapports)

---

