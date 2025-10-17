# Тесты HTTP Router

Полный набор тестов для библиотеки CloudCastle HTTP Router.

## Типы тестов

### 1. Юнит-тесты (Unit Tests)

Тестирование отдельных компонентов библиотеки.

**Расположение**: `tests/Unit/`

**Запуск**:

```bash
composer test:unit
```

**Покрытие**:

- `RouteTest.php` - тесты класса Route
- `RouterTest.php` - тесты класса Router
- `RouteCacheTest.php` - тесты кеширования
- `ActionResolverTest.php` - тесты резолвера действий

**Что тестируется**:

- Создание и настройка маршрутов
- Сопоставление URI с маршрутами
- Извлечение параметров из URI
- Работа с регулярными выражениями
- Именование и тегирование маршрутов
- Middleware
- Фильтрация по IP, домену, порту
- Группы маршрутов
- Кеширование и компиляция
- Различные типы действий

### 2. Тесты производительности (Performance Tests)

Измерение производительности различных операций.

**Расположение**: `tests/Performance/`

**Запуск**:

```bash
composer test:performance
```

**Что тестируется**:

- Скорость регистрации маршрутов
- Скорость сопоставления маршрутов
- Производительность кеширования
- Использование памяти
- Производительность групп

**Метрики**:

- Запросов в секунду (req/sec)
- Время отклика (ms)
- Использование памяти (MB)
- Маршрутов в секунду

### 3. Нагрузочные тесты (Load Tests)

Проверка работы под различными нагрузками.

**Расположение**: `tests/Load/`

**Запуск**:

```bash
composer test:load
# или
php tests/Load/LoadTest.php
```

**Сценарии**:

- **Легкая нагрузка**: 100 маршрутов, 1,000 запросов
- **Средняя нагрузка**: 500 маршрутов, 5,000 запросов
- **Высокая нагрузка**: 1,000 маршрутов, 10,000 запросов
- **Конкурентные паттерны**: различные типы маршрутов
- **Сравнение кешированных и некешированных маршрутов**

### 4. Стресс-тесты (Stress Tests)

Поиск точек отказа и пределов производительности.

**Расположение**: `tests/Stress/`

**Запуск**:

```bash
composer test:stress
# или
php tests/Stress/StressTest.php
```

**Что тестируется**:

- Максимальное количество маршрутов (до 50,000)
- Глубокая вложенность групп
- Очень длинные URI паттерны
- Экстремальный объем запросов (100,000+)
- Пределы использования памяти

**Цель**: Найти breaking points и убедиться в стабильности при экстремальных условиях.

### 5. Тесты безопасности (Security Tests)

Проверка защиты от распространенных атак.

**Расположение**: `tests/Security/`

**Запуск**:

```bash
composer test:security
```

**Что тестируется**:

- **Path Traversal** - попытки доступа к файловой системе
- **SQL Injection** - внедрение SQL кода в параметры
- **XSS** - межсайтовый скриптинг
- **IP Spoofing** - подмена IP адресов
- **Domain Security** - обход доменных ограничений
- **ReDoS** - атаки на регулярные выражения
- **Method Override** - подмена HTTP методов
- **Mass Assignment** - массовое назначение параметров
- **Cache Injection** - внедрение кода через кеш
- **Resource Exhaustion** - исчерпание ресурсов
- **Unicode Attacks** - атаки с использованием Unicode

## Запуск всех тестов

```bash
# Все автоматизированные тесты
composer test:all

# Только PHPUnit тесты
composer test

# С отчетом о покрытии
composer test:coverage
```

## Требования

- PHP 8.1+
- PHPUnit 10.5+
- Composer

## Интерпретация результатов

### Юнит-тесты

✅ **Успешно**: Все assertions прошли
❌ **Ошибка**: Найдены баги, требуется исправление

### Производительность

**Хорошие показатели**:

- Регистрация: >2,000 маршрутов/сек
- Сопоставление: >5,000 запросов/сек
- Память: <10 KB на маршрут

**Требуется оптимизация**:

- Регистрация: <1,000 маршрутов/сек
- Сопоставление: <1,000 запросов/сек
- Память: >50 KB на маршрут

### Нагрузочные тесты

**Целевые показатели**:

- Легкая нагрузка: >10,000 req/sec
- Средняя нагрузка: >5,000 req/sec
- Высокая нагрузка: >2,000 req/sec
- Кеш улучшение: >50%

### Стресс-тесты

**Минимальные требования**:

- Поддержка >10,000 маршрутов
- Вложенность >20 уровней
- Длина URI >1,000 символов
- Память <50 MB для 10,000 маршрутов

### Безопасность

✅ Все тесты должны проходить
❌ Любая ошибка критична и требует немедленного исправления

## Continuous Integration

Пример конфигурации для GitHub Actions:

```yaml
name: Tests

on: [push, pull_request]

jobs:
  test:
    runs-on: ubuntu-latest
    
    steps:
      - uses: actions/checkout@v2
      
      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: 8.1
          
      - name: Install dependencies
        run: composer install
        
      - name: Run unit tests
        run: composer test:unit
        
      - name: Run security tests
        run: composer test:security
        
      - name: Run performance tests
        run: composer test:performance
        
      - name: Generate coverage
        run: composer test:coverage
```

## Локальная разработка

Для разработки рекомендуется запускать тесты после каждого изменения:

```bash
# Watch mode (с помощью phpunit-watcher)
composer require --dev spatie/phpunit-watcher
vendor/bin/phpunit-watcher watch

# Или вручную после каждого изменения
composer test:unit
```

## Добавление новых тестов

### Юнит-тест

```php
<?php

namespace CloudCastle\Http\Router\Tests\Unit;

use PHPUnit\Framework\TestCase;

class MyNewTest extends TestCase
{
    public function testSomething(): void
    {
        $this->assertTrue(true);
    }
}
```

### Тест производительности

```php
public function testNewFeaturePerformance(): void
{
    $start = microtime(true);
    
    // Ваш код
    
    $duration = microtime(true) - $start;
    
    $this->assertLessThan(1.0, $duration);
}
```

### Тест безопасности

```php
public function testNewSecurityFeature(): void
{
    $this->expectException(SomeSecurityException::class);
    
    // Попытка атаки
}
```

## Troubleshooting

### Тесты падают из-за памяти

Увеличьте лимит памяти в `phpunit.xml`:

```xml
<phpunit bootstrap="vendor/autoload.php">
    <php>
        <ini name="memory_limit" value="512M"/>
    </php>
</phpunit>
```

### Медленные тесты

Запустите только быстрые тесты:

```bash
composer test:unit
```

Или используйте группы:

```bash
vendor/bin/phpunit --group fast
```

### Проблемы с кешем

Очистите кеш перед тестами:

```bash
rm -rf cache/
composer test
```

## Метрики качества

Библиотека должна поддерживать:

- ✅ Покрытие кода: >80%
- ✅ Все юнит-тесты: PASS
- ✅ Все тесты безопасности: PASS
- ✅ Производительность: >5,000 req/sec
- ✅ Стресс-тест: >10,000 маршрутов
- ✅ Использование памяти: <10 KB/маршрут

## Полезные ссылки

- [PHPUnit Documentation](https://phpunit.de/)
- [PHP Best Practices](https://phptherightway.com/)
- [OWASP Testing Guide](https://owasp.org/www-project-web-security-testing-guide/)

