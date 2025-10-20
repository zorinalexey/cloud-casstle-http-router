# CLI Tools - Инструменты командной строки

[English](../../en/features/CLI_TOOLS_FEATURES.md) | **Русский** | [Deutsch](../../de/features/CLI_TOOLS_FEATURES.md) | [Français](../../fr/features/CLI_TOOLS_FEATURES.md) | [中文](../../zh/features/CLI_TOOLS_FEATURES.md)

---

## Содержание

- [routes-list](#routes-list)
- [router](#router)
- [analyse](#analyse)
- [Использование в composer](#использование-в-composer)
- [Сравнение с аналогами](#сравнение-с-аналогами)

---

## routes-list

### Описание

Отображает список всех зарегистрированных маршрутов в табличном формате.

### Использование

```bash
# Через composer
composer routes-list

# Или напрямую
php vendor/bin/router routes-list
```

### Вывод

```
+--------+-------------------------+------------------+---------------------------+
| Method | URI                     | Name             | Action                    |
+--------+-------------------------+------------------+---------------------------+
| GET    | /                       | home             | HomeController@index      |
| GET    | /users                  | users.index      | UserController@index      |
| GET    | /users/{id}             | users.show       | UserController@show       |
| POST   | /users                  | users.store      | UserController@store      |
| PUT    | /users/{id}             | users.update     | UserController@update     |
| DELETE | /users/{id}             | users.destroy    | UserController@destroy    |
| GET    | /api/posts              | api.posts.index  | PostController@index      |
| POST   | /api/posts              | api.posts.store  | PostController@store      |
+--------+-------------------------+------------------+---------------------------+
```

### Фильтрация

```bash
# Только GET маршруты
composer routes-list --method=GET

# По префиксу
composer routes-list --prefix=/api

# По имени
composer routes-list --name=users.*

# Только с middleware
composer routes-list --middleware=auth

# С доменом
composer routes-list --domain=api.example.com
```

### Форматы вывода

```bash
# Таблица (по умолчанию)
composer routes-list

# JSON
composer routes-list --format=json

# CSV
composer routes-list --format=csv

# Markdown
composer routes-list --format=markdown
```

### JSON вывод

```bash
composer routes-list --format=json
```

```json
[
  {
    "method": "GET",
    "uri": "/users",
    "name": "users.index",
    "action": "UserController@index",
    "middleware": ["auth"],
    "domain": null,
    "port": null
  },
  {
    "method": "POST",
    "uri": "/users",
    "name": "users.store",
    "action": "UserController@store",
    "middleware": ["auth", "verified"],
    "domain": null,
    "port": null
  }
]
```

### Расширенная информация

```bash
# С middleware
composer routes-list --show-middleware

# С доменами
composer routes-list --show-domain

# С портами
composer routes-list --show-port

# Всё
composer routes-list --verbose
```

```
+--------+----------+-------------+-------------------+--------------------+--------+------+
| Method | URI      | Name        | Action            | Middleware         | Domain | Port |
+--------+----------+-------------+-------------------+--------------------+--------+------+
| GET    | /admin   | admin.index | AdminController@i | auth,admin,throttle| admin  | 8080 |
| POST   | /api/data| api.data    | DataController@st | api,throttle:60    | api    | null |
+--------+----------+-------------+-------------------+--------------------+--------+------+
```

---

## router

### Описание

Общая команда для работы с роутером.

### Подкоманды

```bash
# Список маршрутов
php vendor/bin/router list

# Информация о маршруте
php vendor/bin/router show users.index

# Проверка маршрута
php vendor/bin/router match GET /users/123

# Очистка кеша
php vendor/bin/router cache:clear

# Создание кеша
php vendor/bin/router cache:create

# Статистика
php vendor/bin/router stats
```

### router show

Детальная информация о конкретном маршруте:

```bash
php vendor/bin/router show users.show
```

```
Route: users.show
Method(s): GET
URI: /users/{id}
Action: UserController@show
Middleware: auth, verified
Domain: -
Port: -
HTTPS Only: No
IP Whitelist: -
IP Blacklist: -
Rate Limit: 60 requests per minute
Tags: public, api
Plugins: Logger, Analytics
```

### router match

Проверка соответствия URI маршруту:

```bash
php vendor/bin/router match GET /users/123
```

```
✓ Match found!

Route: users.show
URI Pattern: /users/{id}
Parameters: {"id": "123"}
Action: UserController@show
```

### router stats

Статистика по маршрутам:

```bash
php vendor/bin/router stats
```

```
Router Statistics
=================

Total Routes: 150

By Method:
  GET:     80 (53%)
  POST:    40 (27%)
  PUT:     15 (10%)
  PATCH:    5 (3%)
  DELETE:  10 (7%)

Named Routes: 120 (80%)
Unnamed Routes: 30 (20%)

With Middleware: 90 (60%)
With Domain: 20 (13%)
With Port: 5 (3%)
With Throttle: 50 (33%)
With IP Restrictions: 10 (7%)

By Tag:
  api:       60
  public:    40
  admin:     20
  protected: 30

Cache: Enabled (/var/cache/routes)
Auto-naming: Disabled
```

---

## analyse

### Описание

Анализ маршрутов на проблемы и рекомендации.

### Использование

```bash
composer analyse

# Или
php vendor/bin/router analyse
```

### Вывод

```
Route Analysis Report
=====================

✓ No critical issues found

Warnings (3):
  ⚠ Route 'users.update' has no authentication middleware
  ⚠ Route 'api.data' has no rate limiting
  ⚠ 15 routes are unnamed

Recommendations (5):
  → Enable route caching for production (10x performance)
  → Consider adding HTTPS enforcement to payment routes
  → Group similar routes under common prefix
  → Add IP whitelisting to admin routes
  → Enable auto-naming for better route management

Statistics:
  Routes analyzed: 150
  Critical issues: 0
  Warnings: 3
  Suggestions: 5

Performance Score: 85/100
Security Score: 90/100
```

### Детальный анализ

```bash
composer analyse --detailed
```

```
Security Analysis:
  ✓ All admin routes have authentication
  ✓ All payment routes use HTTPS
  ⚠ 5 routes missing rate limiting
  ⚠ 2 routes accessible without IP restrictions

Performance Analysis:
  ✓ Route caching enabled
  ✓ 95% of routes are named
  → Consider using route compilation
  → 10 routes with complex regex patterns

Best Practices:
  ✓ RESTful naming conventions
  ✓ Consistent middleware usage
  ⚠ Some routes missing tags
  → Group routes by feature
```

### Проверка конкретных аспектов

```bash
# Только безопасность
composer analyse --security

# Только производительность
composer analyse --performance

# Только best practices
composer analyse --best-practices
```

---

## Использование в composer

### composer.json

```json
{
  "scripts": {
    "routes-list": "router routes-list",
    "router": "router",
    "analyse": "router analyse"
  }
}
```

### Примеры

```bash
# Список маршрутов
composer routes-list

# Статистика
composer router stats

# Анализ
composer analyse

# Очистка кеша
composer router cache:clear
```

---

## Интеграция в CI/CD

### GitHub Actions

```yaml
name: Routes Analysis

on: [push, pull_request]

jobs:
  analyze:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v2
      - uses: php-actions/composer@v6
      
      - name: Analyze routes
        run: composer analyse
        
      - name: Check for critical issues
        run: |
          composer analyse --security --strict
          composer analyse --performance --strict
```

### GitLab CI

```yaml
routes_analysis:
  script:
    - composer install
    - composer analyse
    - composer routes-list --format=json > routes.json
  artifacts:
    paths:
      - routes.json
```

---

## Сравнение с аналогами

| Роутер | routes-list | analyse | Форматы | Фильтры | Оценка |
|--------|-------------|---------|---------|---------|--------|
| **CloudCastle** | ✅ | ✅ | 4 формата | ✅ | **⭐⭐⭐⭐⭐** |
| Laravel | ✅ route:list | ❌ | 2 формата | ⚠️ | ⭐⭐⭐⭐ |
| Symfony | ✅ debug:router | ❌ | 1 формат | ⚠️ | ⭐⭐⭐ |
| FastRoute | ❌ | ❌ | - | - | ⭐ |
| Slim | ❌ | ❌ | - | - | ⭐ |

### Уникальные возможности

✅ **analyse** - анализ и рекомендации (УНИКАЛЬНО!)  
✅ **4 формата вывода** - table, json, csv, markdown  
✅ **router match** - проверка соответствия  
✅ **router stats** - детальная статистика  
✅ **Гибкие фильтры** - по всем критериям  

---

## Примеры использования

### Development

```bash
# Быстрый просмотр всех маршрутов
composer routes-list

# Проверка конкретного маршрута
php vendor/bin/router show users.show

# Тестирование URI
php vendor/bin/router match GET /api/users/123
```

### Testing

```bash
# Экспорт для тестов
composer routes-list --format=json > tests/fixtures/routes.json

# Проверка покрытия
composer analyse --coverage
```

### Production

```bash
# Создание кеша перед deploy
composer router cache:create

# Проверка конфигурации
composer analyse --security --strict
```

### Documentation

```bash
# Экспорт в Markdown для документации
composer routes-list --format=markdown > docs/routes.md

# JSON для API документации
composer routes-list --prefix=/api --format=json > docs/api-routes.json
```

---

## Заключение

**CloudCastle CLI Tools - мощный набор инструментов:**

✅ routes-list - список маршрутов с фильтрацией  
✅ router - универсальная команда  
✅ analyse - анализ и рекомендации (УНИКАЛЬНО!)  
✅ 4 формата вывода  
✅ Гибкие фильтры  
✅ CI/CD интеграция  

**Рекомендация:** Используйте в development, testing и CI/CD!

---

[⬆ Наверх](#cli-tools---инструменты-командной-строки) | [📚 Все фичи](../ALL_FEATURES.md) | [🏠 Главная](../../../README.md)

© 2024 CloudCastle HTTP Router

