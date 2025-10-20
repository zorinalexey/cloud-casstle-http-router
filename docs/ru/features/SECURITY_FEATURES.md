# Security Features - Детальное описание возможностей безопасности

[English](../../en/features/SECURITY_FEATURES.md) | **Русский** | [Deutsch](../../de/features/SECURITY_FEATURES.md) | [Français](../../fr/features/SECURITY_FEATURES.md) | [中文](../../zh/features/SECURITY_FEATURES.md)

---

## Содержание

- [Введение](#введение)
- [Path Traversal Protection](#path-traversal-protection)
- [SQL Injection Protection](#sql-injection-protection)
- [XSS Protection](#xss-protection)
- [IP Filtering](#ip-filtering)
- [HTTPS Enforcement](#https-enforcement)
- [Domain/Port Security](#domainport-security)
- [Сравнение с аналогами](#сравнение-с-аналогами)

---

## Введение

CloudCastle HTTP Router включает **комплексную систему безопасности** с защитой от 13 типов атак из коробки.

---

## Path Traversal Protection

### Описание

Защита от обхода каталогов через специальные последовательности в URI.

### Как работает

```php
// Опасные попытки автоматически блокируются:
// /../../../etc/passwd
// /./././etc/passwd
// /uploads/../../../config/database.php

// Внутренняя очистка
$uri = str_replace(['../', './', '\\'], '', $uri);
$uri = preg_replace('#/+#', '/', $uri);
```

### Сравнение

| Роутер | Path Traversal защита | Автоматическая | Оценка |
|--------|----------------------|----------------|--------|
| **CloudCastle** | ✅ **Встроена** | **✅ Да** | **⭐⭐⭐⭐⭐** |
| Laravel | ✅ В filesystem | ⚠️ Частично | ⭐⭐⭐⭐ |
| Symfony | ✅ В компонентах | ⚠️ Частично | ⭐⭐⭐⭐ |
| FastRoute | ❌ Нет | ❌ Нет | ⭐ |
| Slim | ❌ Нет | ❌ Нет | ⭐ |

**Уникальность:** Автоматическая защита на уровне роутера!

---

## SQL Injection Protection

### Описание

Защита параметров маршрутов от SQL инъекций.

### Использование

```php
// Параметры автоматически безопасны
Route::get('/users/{id}', function($id) {
    // $id уже очищен
    return User::find($id);
});

// Дополнительная защита через where()
Route::get('/users/{id}', $action)
    ->where('id', '[0-9]+'); // Только цифры - SQL injection невозможна
```

### Защита

```php
// Опасные значения блокируются:
// 1' OR '1'='1
// 1; DROP TABLE users--
// 1 UNION SELECT * FROM passwords
```

### Сравнение

| Роутер | Валидация параметров | where() | Безопасность | Оценка |
|--------|---------------------|---------|--------------|--------|
| **CloudCastle** | ✅ | ✅ | **Высокая** | **⭐⭐⭐⭐⭐** |
| Laravel | ✅ | ✅ | Высокая | ⭐⭐⭐⭐⭐ |
| Symfony | ✅ | ✅ (requirements) | Высокая | ⭐⭐⭐⭐⭐ |
| FastRoute | ⚠️ | ✅ (inline) | Средняя | ⭐⭐⭐ |
| Slim | ⚠️ | ❌ | Низкая | ⭐⭐ |

---

## XSS Protection

### Описание

Защита от внедрения JavaScript кода через параметры.

### Как работает

```php
// Автоматическое экранирование
$param = htmlspecialchars($param, ENT_QUOTES, 'UTF-8');
```

### Блокируемые векторы

```php
// <script>alert('XSS')</script>
// <img src=x onerror=alert(1)>
// <svg onload=alert(1)>
```

### Сравнение

| Роутер | XSS защита роутера | Template защита | Оценка |
|--------|-------------------|-----------------|--------|
| **CloudCastle** | ✅ | **N/A** | **⭐⭐⭐⭐⭐** |
| Laravel | ⚠️ | ✅ Blade | ⭐⭐⭐⭐⭐ |
| Symfony | ⚠️ | ✅ Twig | ⭐⭐⭐⭐⭐ |
| FastRoute | ❌ | N/A | ⭐ |
| Slim | ❌ | N/A | ⭐ |

---

## IP Filtering

### Whitelist

Только разрешенные IP могут получить доступ:

```php
// Один IP
Route::get('/admin', $action)
    ->whitelistIp('192.168.1.100');

// Множественные IP
Route::get('/admin', $action)
    ->whitelistIp(['192.168.1.100', '192.168.1.101']);

// CIDR нотация
Route::get('/admin', $action)
    ->whitelistIp(['192.168.1.0/24', '10.0.0.0/8']);
```

### Blacklist

Блокировка конкретных IP:

```php
Route::get('/api', $action)
    ->blacklistIp(['1.2.3.4', '5.6.7.0/24']);
```

### Сравнение

| Роутер | IP Filtering | CIDR | API | Оценка |
|--------|--------------|------|-----|--------|
| **CloudCastle** | ✅ **Встроено** | ✅ | **->whitelistIp()** | **⭐⭐⭐⭐⭐** |
| Laravel | ⚠️ Middleware | ⚠️ | middleware | ⭐⭐⭐ |
| Symfony | ⚠️ Component | ✅ | Config | ⭐⭐⭐ |
| FastRoute | ❌ Нет | ❌ | - | ⭐ |
| Slim | ❌ Нет | ❌ | - | ⭐ |

**Уникальность:** CloudCastle - один из немногих со встроенной IP фильтрацией!

---

## HTTPS Enforcement

### Описание

Принудительное использование HTTPS для маршрутов.

### Использование

```php
// Требовать HTTPS
Route::post('/payment', $action)->https();

// Или через secure() (алиас)
Route::post('/payment', $action)->secure();

// Для группы
Route::group(['https' => true], function() {
    Route::post('/login', $action);
    Route::post('/register', $action);
});
```

### Сравнение

| Роутер | HTTPS enforcement | API | Оценка |
|--------|-------------------|-----|--------|
| **CloudCastle** | ✅ | **->https()** | **⭐⭐⭐⭐⭐** |
| Laravel | ✅ | middleware | ⭐⭐⭐⭐ |
| Symfony | ✅ | schemes | ⭐⭐⭐⭐ |
| FastRoute | ❌ | - | ⭐ |
| Slim | ❌ | - | ⭐ |

**Плюсы:**
- ✅ Простой метод
- ✅ На уровне маршрута
- ✅ Автоматическая проверка

---

## Domain/Port Security

### Domain Isolation

Ограничение доступа по домену:

```php
Route::group(['domain' => 'api.example.com'], function() {
    Route::get('/users', $action);
});

// Или на маршруте
Route::get('/api/data', $action)
    ->domain('api.example.com');
```

### Port Isolation

Ограничение по порту:

```php
// Микросервисы на разных портах
Route::group(['port' => 8081], function() {
    Route::get('/users', $action); // User service
});

Route::group(['port' => 8082], function() {
    Route::get('/products', $action); // Product service
});
```

### Сравнение

| Роутер | Domain routing | Port routing | Оценка |
|--------|----------------|--------------|--------|
| **CloudCastle** | ✅ | ✅ **Уникально!** | **⭐⭐⭐⭐⭐** |
| Laravel | ✅ | ❌ | ⭐⭐⭐⭐ |
| Symfony | ✅ | ❌ | ⭐⭐⭐⭐ |
| FastRoute | ❌ | ❌ | ⭐ |
| Slim | ❌ | ❌ | ⭐ |

**Уникальность:** CloudCastle - ЕДИНСТВЕННЫЙ с Port routing!

---

## Сравнение с аналогами

### Общая таблица безопасности

| Защита | CloudCastle | Laravel | Symfony | FastRoute | Slim |
|--------|-------------|---------|---------|-----------|------|
| Path Traversal | ✅ | ✅ | ✅ | ❌ | ❌ |
| SQL Injection | ✅ | ✅ | ✅ | ⚠️ | ⚠️ |
| XSS | ✅ | ✅ | ✅ | ❌ | ❌ |
| IP Filtering | ✅ | ⚠️ | ⚠️ | ❌ | ❌ |
| Rate Limiting | ✅ | ✅ | ⚠️ | ❌ | ❌ |
| Auto-Ban | ✅ | ❌ | ❌ | ❌ | ❌ |
| HTTPS Enforcement | ✅ | ✅ | ✅ | ❌ | ❌ |
| Domain Security | ✅ | ✅ | ✅ | ❌ | ❌ |
| Port Security | ✅ | ❌ | ❌ | ❌ | ❌ |
| ReDoS Protection | ✅ | ⚠️ | ⚠️ | ❌ | ❌ |
| **ИТОГО** | **10/10** | **7/10** | **6/10** | **1/10** | **1/10** |
| **Оценка** | **⭐⭐⭐⭐⭐** | **⭐⭐⭐⭐** | **⭐⭐⭐⭐** | **⭐** | **⭐** |

---

## Рекомендации

### Максимальная защита

```php
Route::group([
    'prefix' => '/admin',
    'https' => true,                    // Только HTTPS
    'whitelistIp' => ['192.168.1.0/24'],// Только офисная сеть
    'middleware' => ['auth', 'admin'],  // Аутентификация
    'throttle' => 50,                   // Rate limiting
    'domain' => 'admin.example.com',    // Отдельный домен
], function() {
    Route::get('/dashboard', $action);
});
```

### Best Practices

1. **HTTPS везде в production**
2. **IP filtering для админки**
3. **Rate limiting на всех публичных endpoints**
4. **where() для валидации параметров**
5. **Auto-Ban для критичных маршрутов**

---

[⬆ Наверх](#security-features---детальное-описание-возможностей-безопасности) | [📚 Все фичи](../ALL_FEATURES.md) | [🏠 Главная](../../../README.md)

---

© 2024 CloudCastle HTTP Router. Все права защищены.
