# CLI Tools

---

## 📚 Navigation de la Documentation

[README](../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [API_REFERENCE](../API_REFERENCE.md) | [ALL_FEATURES](../ALL_FEATURES.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [PERFORMANCE](../PERFORMANCE_ANALYSIS.md) | [SECURITY](../SECURITY_REPORT.md) | [COMPARISON](../COMPARISON.md) | [FAQ](../FAQ.md)

**Documentation détaillée:** [01](01_BASIC_ROUTING.md) | [02](02_ROUTE_PARAMETERS.md) | [03](03_ROUTE_GROUPS.md) | [04](04_RATE_LIMITING.md) | [05](05_IP_FILTERING.md) | [06](06_MIDDLEWARE.md) | [07](07_NAMED_ROUTES.md) | [08](08_TAGS.md) | [09](09_HELPER_FUNCTIONS.md) | [10](10_ROUTE_SHORTCUTS.md) | [11](11_ROUTE_MACROS.md) | [12](12_URL_GENERATION.md) | [13](13_EXPRESSION_LANGUAGE.md) | [14](14_CACHING.md) | [15](15_PLUGINS.md) | [16](16_LOADERS.md) | [17](17_PSR_SUPPORT.md) | [18](18_ACTION_RESOLVER.md) | [19](19_STATISTICS.md) | [20](20_SECURITY.md) | [21](21_EXCEPTIONS.md) | [22](22_CLI_TOOLS.md)

---


**Catégorie:** avecchez deàet  
**Nombre de àsur:** 3  
**Complexité:** ⭐ Débutant chezsurdans

---

## etavecet

CLI chezetet pour chezdanset et suret routesurdans et àsursur ligneset.

## sur

### 1. routes-list

**etavecet:** surà avecetavecsurà tous routesurdans.

**avecparsurdanset:**

```bash
# Все маршруты
php bin/routes-list

# Фильтр по методу
php bin/routes-list --method=GET
php bin/routes-list --method=POST

# Фильтр по тегу
php bin/routes-list --tag=api
php bin/routes-list --tag=admin

# Фильтр по имени (wildcard)
php bin/routes-list --name=users.*
php bin/routes-list --name=api.v1.*

# Комбинация фильтров
php bin/routes-list --method=GET --tag=api
```

**danssur:**

```
╔════════════════════════════════════════════════════════════════╗
║                     ROUTES LIST                                 ║
╚════════════════════════════════════════════════════════════════╝

Method | URI                    | Name           | Middleware
-------|------------------------|----------------|------------------
GET    | /users                 | users.index    | Auth
GET    | /users/{id}            | users.show     | Auth
POST   | /users                 | users.store    | Auth, Validate
PUT    | /users/{id}            | users.update   | Auth, Admin
DELETE | /users/{id}            | users.destroy  | Auth, Admin

Total routes: 5
```

---

### 2. analyse

**etavecet:** suret et avecetavecetà routesurdans.

**avecparsurdanset:**

```bash
php bin/analyse
```

**danssur:**

```
╔════════════════════════════════════════════════════════════════╗
║                   ROUTE ANALYSIS                                ║
╚════════════════════════════════════════════════════════════════╝

📊 ОБЩАЯ СТАТИСТИКА:
   Total routes: 150
   Named routes: 120
   With middleware: 60
   With tags: 80

📍 ПО МЕТОДАМ:
   GET: 80
   POST: 40
   PUT: 15
   DELETE: 15

🌐 ПО ДОМЕНАМ:
   api.example.com: 30
   admin.example.com: 10

🔌 ПО ПОРТАМ:
   8080: 20
   443: 100

🏷️  ПО ТЕГАМ:
   api: 50
   admin: 20
   public: 30

⏱️  THROTTLED:
   With rate limits: 40
```

---

### 3. router

**etavecet:** danset surchezsur.

**avecparsurdanset:**

```bash
# Компилировать кеш
php bin/router compile

# Очистить кеш
php bin/router clear

# Статистика
php bin/router stats

# Проверить конфигурацию
php bin/router check
```

---

## avecparsurdanset dans development

```bash
# Посмотреть все API маршруты
php bin/routes-list --tag=api

# Анализ перед deploy
php bin/analyse

# Компиляция для production
php bin/router compile
```

---

**Version:** 1.1.1  
**chezavec:** ✅ etsur chezàetsursursuravec


---

## 📚 Navigation de la Documentation

[README](../../README.md) | [USER_GUIDE](../USER_GUIDE.md) | [FEATURES_INDEX](../FEATURES_INDEX.md) | [API_REFERENCE](../API_REFERENCE.md) | [ALL_FEATURES](../ALL_FEATURES.md) | [TESTS_SUMMARY](../TESTS_SUMMARY.md) | [FAQ](../FAQ.md)

**Documentation détaillée:** [01](01_BASIC_ROUTING.md) | [02](02_ROUTE_PARAMETERS.md) | [03](03_ROUTE_GROUPS.md) | [04](04_RATE_LIMITING.md) | [05](05_IP_FILTERING.md) | [06](06_MIDDLEWARE.md) | [07](07_NAMED_ROUTES.md) | [08](08_TAGS.md) | [09](09_HELPER_FUNCTIONS.md) | [10](10_ROUTE_SHORTCUTS.md) | [11](11_ROUTE_MACROS.md) | [12](12_URL_GENERATION.md) | [13](13_EXPRESSION_LANGUAGE.md) | [14](14_CACHING.md) | [15](15_PLUGINS.md) | [16](16_LOADERS.md) | [17](17_PSR_SUPPORT.md) | [18](18_ACTION_RESOLVER.md) | [19](19_STATISTICS.md) | [20](20_SECURITY.md) | [21](21_EXCEPTIONS.md) | [22](22_CLI_TOOLS.md)

**© 2024 CloudCastle HTTP Router**
