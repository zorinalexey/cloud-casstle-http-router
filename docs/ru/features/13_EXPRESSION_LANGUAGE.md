# Expression Language

**Категория:** Продвинутые возможности  
**Количество операторов:** 5  
**Сложность:** ⭐⭐⭐ Продвинутый уровень

---

## Описание

Expression Language позволяет создавать условия для маршрутов на основе вычисляемых выражений (IP, время, заголовки и т.д.).

## Использование

### condition()

```php
// По IP
Route::get('/admin', $action)
    ->condition('request.ip == "192.168.1.1"');

// По времени
Route::get('/api/data', $action)
    ->condition('request.time > 9 and request.time < 18');

// По заголовкам
Route::get('/api/secure', $action)
    ->condition('request.header["X-API-Key"] == "secret"');
```

## Операторы

### Сравнения

- `==` - Равно
- `!=` - Не равно
- `>` - Больше
- `<` - Меньше
- `>=` - Больше или равно
- `<=` - Меньше или равно

### Логические

- `and` - И
- `or` - ИЛИ

## ExpressionLanguage класс

```php
use CloudCastle\Http\Router\ExpressionLanguage\ExpressionLanguage;

$expr = new ExpressionLanguage();

$result = $expr->evaluate('10 > 5 and 20 < 30', []);
// true

$result = $expr->evaluate('user.age >= 18', ['user' => ['age' => 25]]);
// true
```

## Примеры

```php
// Рабочие часы
Route::get('/api/business', $action)
    ->condition('request.time >= 9 and request.time <= 18');

// Только с определенных IP
Route::get('/internal', $action)
    ->condition('request.ip == "192.168.1.1" or request.ip == "10.0.0.1"');

// По User Agent
Route::get('/mobile', $action)
    ->condition('request.header["User-Agent"] contains "Mobile"');
```

---

**Версия:** 1.1.1  
**Статус:** ✅ Экспериментальная функциональность

