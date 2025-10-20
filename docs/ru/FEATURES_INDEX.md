# Индекс всех возможностей CloudCastle HTTP Router

[English](../en/FEATURES_INDEX.md) | **Русский** | [Deutsch](../de/FEATURES_INDEX.md) | [Français](../fr/FEATURES_INDEX.md) | [中文](../zh/FEATURES_INDEX.md)

---

## 📋 Категории возможностей

Всего описано: **209+ возможностей**

---

### 1. [Routing Features](features/ROUTING_FEATURES.md) - Маршрутизация

**Фич в категории:** 40+

- HTTP Methods (GET, POST, PUT, PATCH, DELETE, VIEW, ANY, MATCH, CUSTOM)
- Параметры маршрутов
- Базовые параметры
- Параметры с ограничениями (where)
- Опциональные параметры
- Default values
- Inline patterns
- И другие

**Сравнение:** Детальное сравнение каждой фичи с Laravel, Symfony, FastRoute

---

### 2. [Middleware Features](features/MIDDLEWARE_FEATURES.md) - Middleware

**Фич в категории:** 15+

- Глобальный middleware
- Middleware на маршруте
- Middleware в группе
- 5 встроенных middleware (Auth, CORS, HTTPS, SecurityLogger, SsrfProtection)
- PSR-15 поддержка
- MiddlewareDispatcher

**Сравнение:** Сравнение с middleware системами других роутеров

---

### 3. [Security Features](features/SECURITY_FEATURES.md) - Безопасность

**Фич в категории:** 25+

- Path Traversal защита
- SQL Injection защита
- XSS защита
- IP Spoofing защита
- ReDoS защита
- Method Override защита
- Cache Injection защита
- Resource Exhaustion защита
- Unicode Security
- HTTPS Enforcement
- Domain Security
- Port Security

**Сравнение:** Детальное сравнение защиты с каждым аналогом

---

### 4. [Rate Limiting Features](features/RATE_LIMITING_FEATURES.md) - Ограничение частоты

**Фич в категории:** 20+

- Базовый throttle
- TimeUnit enum (6 единиц времени)
- Кастомные ключи
- RateLimiter класс
- tooManyAttempts()
- availableIn()
- remaining()
- attempt()
- Route shortcuts (throttleStandard, throttleStrict, throttleGenerous)

**Сравнение:** Сравнение реализаций rate limiting

---

### 5. [IP Filtering Features](features/IP_FILTERING_FEATURES.md) - IP Фильтрация

**Фич в категории:** 15+

- Whitelist IP
- Blacklist IP
- CIDR нотация
- Множественные IP
- IP Spoofing защита
- localhost() shortcut
- getWhitelistIps()
- getBlacklistIps()

**Уникальность:** Одна из немногих библиотек со встроенной IP фильтрацией

---

### 6. [IP Filtering & Auto-Ban Features](features/IP_FILTERING_FEATURES.md) - IP Фильтрация и Auto-Ban

**Фич в категории:** 10+

- BanManager класс
- enableAutoBan()
- setAutoBanDuration()
- ban() / unban()
- isBanned()
- getBannedIps()
- clearAll()

**Уникальность:** Единственная библиотека роутинга с Auto-Ban системой!

---

### 7. [Caching Features](features/CACHING_FEATURES.md) - Кеширование

**Фич в категории:** 12+

- enableCache()
- compile()
- loadFromCache()
- clearCache()
- autoCompile()
- RouteCache класс
- RouteCompiler

**Сравнение:** Сравнение систем кеширования

---

### 8. [Plugin Features](features/PLUGIN_FEATURES.md) - Система плагинов

**Фич в категории:** 18+

- PluginInterface
- registerPlugin()
- 4 хука (beforeDispatch, afterDispatch, onRouteRegistered, onException)
- 3 встроенных плагина (Logger, Analytics, ResponseCache)
- AbstractPlugin
- Управление плагинами

**Уникальность:** Полноценная plugin система

---

### 9. [URL Generation Features](features/URL_GENERATION_FEATURES.md) - Генерация URL

**Фич в категории:** 12+

- UrlGenerator класс
- generate()
- absolute()
- toDomain()
- toProtocol()
- signed()
- setBaseUrl()
- Query параметры
- route_url() helper

**Сравнение:** Сравнение генераторов URL

---

### 10. [Helper Functions](features/HELPER_FUNCTIONS.md) - Helper функции

**Фич в категории:** 12 функций

- route()
- current_route()
- previous_route()
- route_is()
- route_name()
- router()
- dispatch_route()
- route_url()
- route_has()
- route_stats()
- routes_by_tag()
- route_back()

**Уникальность:** Полный набор helpers как в Laravel

---

### 11. [Route Shortcuts](features/ROUTE_SHORTCUTS.md) - Route Shortcuts

**Фич в категории:** 14 методов

- auth(), guest(), api(), web(), cors()
- localhost(), secure()
- throttleStandard(), throttleStrict(), throttleGenerous()
- public(), private(), admin()
- apiEndpoint(), protected()

**Уникальность:** CloudCastle specific shortcuts

---

### 12. [Route Macros](features/ROUTE_MACROS.md) - Route Macros

**Фич в категории:** 7 макросов

- resource() - RESTful CRUD
- apiResource() - API CRUD  
- crud() - Простой CRUD
- auth() - Аутентификация
- adminPanel() - Админка
- apiVersion() - Версионирование
- webhooks() - Веб-хуки

**Уникальность:** Готовые шаблоны для типичных задач

---

### 13. [Groups Features](features/GROUPS_FEATURES.md) - Группы маршрутов

**Фич в категории:** 15+

- Префиксы
- Middleware в группе
- Вложенные группы (до 50 уровней!)
- Домены
- Порты
- Namespace
- HTTPS requirement
- Протоколы
- Теги
- Throttle
- IP filtering

**Сравнение:** Самые гибкие группы среди всех роутеров

---

### 14. [Loader Features](features/LOADER_FEATURES.md) - Загрузчики маршрутов

**Фич в категории:** 5 загрузчиков + методы

- JsonLoader
- YamlLoader
- XmlLoader
- PhpLoader
- AttributeLoader (PHP 8 attributes)

**Уникальность:** Самый широкий выбор загрузчиков

---

### 15. [Expression Language](features/EXPRESSION_LANGUAGE_FEATURES.md) - Язык выражений

**Фич в категории:** 10+

- condition() метод
- Операторы сравнения (==, !=, >, <, >=, <=)
- Логические операторы (and, or)
- ExpressionLanguage класс
- evaluate()
- Контекст

**Уникальность:** Единственный роутер с Expression Language!

---

## 📊 Статистика по категориям

| Категория | Фич | Файл | Уникальность |
|-----------|-----|------|--------------|
| Routing | 40+ | ROUTING_FEATURES.md | ⭐⭐⭐⭐ |
| Middleware | 15+ | MIDDLEWARE_FEATURES.md | ⭐⭐⭐⭐ |
| Security | 25+ | SECURITY_FEATURES.md | ⭐⭐⭐⭐⭐ |
| Rate Limiting | 20+ | RATE_LIMITING_FEATURES.md | ⭐⭐⭐⭐⭐ |
| IP Filtering | 15+ | IP_FILTERING_FEATURES.md | ⭐⭐⭐⭐⭐ |
| Auto-Ban | 10+ | (встроено в IP_FILTERING) | ⭐⭐⭐⭐⭐ |
| Caching | 12+ | CACHING_FEATURES.md | ⭐⭐⭐⭐ |
| Plugins | 18+ | PLUGIN_FEATURES.md | ⭐⭐⭐⭐⭐ |
| URL Generation | 12+ | URL_GENERATION_FEATURES.md | ⭐⭐⭐⭐ |
| Helpers | 12 | HELPER_FUNCTIONS.md | ⭐⭐⭐⭐⭐ |
| Shortcuts | 14 | ROUTE_SHORTCUTS.md | ⭐⭐⭐⭐⭐ |
| Macros | 7 | ROUTE_MACROS.md | ⭐⭐⭐⭐⭐ |
| Groups | 15+ | GROUPS_FEATURES.md | ⭐⭐⭐⭐⭐ |
| Loaders | 10+ | LOADER_FEATURES.md | ⭐⭐⭐⭐⭐ |
| Expression Lang | 10+ | EXPRESSION_LANGUAGE_FEATURES.md | ⭐⭐⭐⭐⭐ |

**ИТОГО: 209+ возможностей описаны!**

---

## 🌟 Уникальные возможности CloudCastle

**Фичи, которых нет в других роутерах:**

1. ⭐ **Auto-Ban System** - автоматическая блокировка IP
2. ⭐ **Expression Language** - условная маршрутизация
3. ⭐ **Port-based routing** - изоляция по портам
4. ⭐ **VIEW HTTP method** - встроенный VIEW метод
5. ⭐ **Route Shortcuts** - 14 удобных методов
6. ⭐ **Route Macros** - 7 готовых шаблонов
7. ⭐ **TimeUnit enum** - удобные единицы времени
8. ⭐ **5 Loaders** - максимальный выбор
9. ⭐ **Plugin System** - расширяемая архитектура
10. ⭐ **20+ методов фильтрации** - мощная фильтрация маршрутов

---

## 📚 Навигация

- [Все возможности](ALL_FEATURES.md) - Полный список с примерами
- [Список фич](../../FEATURES_LIST.md) - Краткий список
- [Сравнение](COMPARISON.md) - Детальное сравнение с аналогами

---

© 2024 CloudCastle HTTP Router. Все права защищены.

