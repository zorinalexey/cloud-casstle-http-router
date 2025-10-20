# Expression Language - Детальное описание языка выражений

[English](../../en/features/EXPRESSION_LANGUAGE_FEATURES.md) | **Русский** | [Deutsch](../../de/features/EXPRESSION_LANGUAGE_FEATURES.md) | [Français](../../fr/features/EXPRESSION_LANGUAGE_FEATURES.md) | [中文](../../zh/features/EXPRESSION_LANGUAGE_FEATURES.md)

---

## Содержание

- [Введение](#введение)
- [Синтаксис](#синтаксис)
- [Операторы сравнения](#операторы-сравнения)
- [Логические операторы](#логические-операторы)
- [Примеры использования](#примеры-использования)
- [Сравнение с аналогами](#сравнение-с-аналогами)

---

## Введение

Expression Language позволяет задавать **динамические условия** для маршрутов.

### Базовый пример

```php
Route::get('/admin', $action)->condition('user.role == "admin"');

// Маршрут сработает только если user.role === "admin"
```

---

## Синтаксис

### Переменные

```php
// Доступ к свойствам
'user.name'
'request.method'
'server.hostname'

// Доступ к массивам
'headers.Accept'
'query.page'
```

### Литералы

```php
// Строки
"admin"
'user'

// Числа
123
45.67

// Boolean
true
false
```

---

## Операторы сравнения

| Оператор | Описание | Пример |
|----------|----------|--------|
| `==` | Равно | `user.role == "admin"` |
| `!=` | Не равно | `user.role != "guest"` |
| `>` | Больше | `user.age > 18` |
| `<` | Меньше | `price < 100` |
| `>=` | Больше или равно | `user.level >= 5` |
| `<=` | Меньше или равно | `quantity <= 10` |

### Примеры

```php
// Равенство
Route::get('/vip', $action)
    ->condition('user.subscription == "premium"');

// Числа
Route::get('/adult', $action)
    ->condition('user.age >= 18');

// Строки
Route::get('/us-only', $action)
    ->condition('request.country == "US"');
```

---

## Логические операторы

| Оператор | Описание | Пример |
|----------|----------|--------|
| `and` | Логическое И | `a == 1 and b == 2` |
| `or` | Логическое ИЛИ | `a == 1 or b == 2` |
| `&&` | Логическое И | `a == 1 && b == 2` |
| `\|\|` | Логическое ИЛИ | `a == 1 \|\| b == 2` |

### Примеры

```php
// AND
Route::get('/premium-adult', $action)
    ->condition('user.age >= 18 and user.subscription == "premium"');

// OR
Route::get('/staff', $action)
    ->condition('user.role == "admin" or user.role == "moderator"');

// Комбинация
Route::get('/special', $action)
    ->condition('(user.level > 10 and user.verified == true) or user.role == "admin"');
```

---

## Примеры использования

### Доступ по ролям

```php
// Только администраторы
Route::get('/admin/dashboard', $action)
    ->condition('user.role == "admin"');

// Администраторы или модераторы
Route::get('/moderate', $action)
    ->condition('user.role == "admin" or user.role == "moderator"');
```

### Возрастные ограничения

```php
// 18+
Route::get('/adult-content', $action)
    ->condition('user.age >= 18');

// Детский контент
Route::get('/kids', $action)
    ->condition('user.age < 13');
```

### Геолокация

```php
// Только для США
Route::get('/us-only', $action)
    ->condition('request.country == "US"');

// Европа
Route::get('/eu-gdpr', $action)
    ->condition('request.region == "EU"');
```

### Subscription Tiers

```php
// Премиум функции
Route::get('/premium-features', $action)
    ->condition('user.subscription == "premium" or user.subscription == "enterprise"');

// Free tier
Route::get('/basic-features', $action)
    ->condition('user.subscription == "free"');
```

### Feature Flags

```php
// Beta функции
Route::get('/beta/new-editor', $action)
    ->condition('user.beta_access == true');

// A/B Testing
Route::get('/experiment', $action)
    ->condition('user.experiment_group == "A"');
```

### Time-based Access

```php
// Рабочие часы
Route::get('/support', $action)
    ->condition('time.hour >= 9 and time.hour < 18');

// Weekdays only
Route::get('/business', $action)
    ->condition('time.weekday == true');
```

---

## Контекст выполнения

### Доступные переменные

```php
// user.*
user.id
user.role
user.name
user.email
user.age
user.subscription
user.verified

// request.*
request.method
request.uri
request.domain
request.country
request.region
request.ip

// time.*
time.hour
time.minute
time.weekday
time.month

// server.*
server.hostname
server.environment

// custom.*
custom.feature_flags
custom.ab_test_group
```

### Установка контекста

```php
$context = [
    'user' => [
        'id' => 123,
        'role' => 'admin',
        'age' => 25,
        'subscription' => 'premium'
    ],
    'request' => [
        'country' => 'US',
        'ip' => '1.2.3.4'
    ]
];

// Передается автоматически при dispatch
$route = $router->dispatch($uri, $method, null, null, null, null, $context);
```

---

## Сравнение с аналогами

| Роутер | Expression Language | Операторов | Переменные | Оценка |
|--------|---------------------|------------|------------|--------|
| **CloudCastle** | ✅ | **10** | ✅ | **⭐⭐⭐⭐⭐** |
| Laravel | ❌ | - | - | ⭐⭐ |
| Symfony | ✅ | Много | ✅ | **⭐⭐⭐⭐⭐** |
| FastRoute | ❌ | - | - | ⭐ |
| Slim | ❌ | - | - | ⭐ |

### Детальное сравнение

**CloudCastle:**
```php
->condition('user.role == "admin" and user.age >= 18')
// Простой синтаксис, 10 операторов
```

**Symfony Expression Language:**
```php
->condition('user.hasRole("ADMIN") and user.getAge() >= 18')
// Более мощный, но сложнее
```

**Laravel:**
```php
// Нет встроенного Expression Language
// Только через middleware или callbacks
```

**FastRoute / Slim:**
```php
// Нет вообще
```

---

## Преимущества CloudCastle

✅ **Простой синтаксис** - похож на JavaScript/Python  
✅ **10 операторов** - достаточно для большинства случаев  
✅ **Переменные из контекста** - гибкость  
✅ **Быстрая оценка** - оптимизированный парсер  

### Производительность

```php
// Парсинг и оценка: ~0.1ms
$result = ExpressionLanguage::evaluate(
    'user.role == "admin" and user.age >= 18',
    $context
);
```

---

## Заключение

**CloudCastle Expression Language:**

✅ Простой и мощный  
✅ 10 операторов  
✅ Динамический контекст  
✅ Быстрая оценка  

**Сравнение:**
- **Symfony**: Более мощный, но сложнее
- **CloudCastle**: Оптимальный баланс простоты и мощности
- **Laravel, FastRoute, Slim**: Нет аналога

**Рекомендация:** Используйте для динамических условий доступа!

---

[⬆ Наверх](#expression-language---детальное-описание-языка-выражений) | [📚 Все фичи](../ALL_FEATURES.md) | [🏠 Главная](../../../README.md)

© 2024 CloudCastle HTTP Router
