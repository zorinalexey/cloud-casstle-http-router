# CLI Tools

**Категория:** Инструменты разработки  
**Количество команд:** 3  
**Сложность:** ⭐ Начальный уровень

---

## Описание

CLI утилиты для управления и анализа маршрутов из командной строки.

## Команды

### 1. routes-list

**Описание:** Показать список всех маршрутов.

**Использование:**

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

**Вывод:**

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

**Описание:** Анализ и статистика маршрутов.

**Использование:**

```bash
php bin/analyse
```

**Вывод:**

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

**Описание:** Управление роутером.

**Использование:**

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

## Использование в development

```bash
# Посмотреть все API маршруты
php bin/routes-list --tag=api

# Анализ перед deploy
php bin/analyse

# Компиляция для production
php bin/router compile
```

---

**Версия:** 1.1.1  
**Статус:** ✅ Стабильная функциональность

