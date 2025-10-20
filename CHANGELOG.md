# Changelog

Все значимые изменения в проекте документируются в этом файле.

Формат основан на [Keep a Changelog](https://keepachangelog.com/ru/1.0.0/),
и проект придерживается [Semantic Versioning](https://semver.org/lang/ru/).

## [Unreleased]

### Планируется
- Trie-структура для параметризованных маршрутов
- Compiled Regex Cache
- PHP JIT оптимизации
- WebSocket support
- GraphQL routing support

## [1.1.1] - 2024-12-20

### Исправлено
- Добавлен параметр `protocol` в методы `dispatch` фасада и роутера
- Исправлена пустая строка после statement в JsonLoaderTest
- Обновлена конфигурация Rector для исключения false-positive предупреждений

### Улучшено
- Полная совместимость с PHP 8.4
- Улучшена документация
- Добавлены детальные отчеты по тестированию

## [1.1.0] - 2024-12-01

### Добавлено
- Expression Language для сложных условий маршрутизации
- Plugin система для расширяемости
- Auto-naming для маршрутов
- Port-based routing
- Улучшенная система тегов
- BanManager для автоматической блокировки IP
- TimeUnit enum для удобного указания временных интервалов
- Route dumper для экспорта маршрутов
- UrlMatcher для продвинутого сопоставления URL

### Изменено
- Оптимизирована система индексов для поиска маршрутов
- Улучшена производительность Rate Limiter
- Рефакторинг RouteCompiler для лучшей производительности

### Исправлено
- Проблемы с глубокой вложенностью групп
- Memory leaks при большом количестве маршрутов
- Некорректная работа whitelist/blacklist IP
  
## [1.0.0] - 2024-11-01

### Добавлено
- Базовая функциональность роутера
- Поддержка всех HTTP методов (GET, POST, PUT, PATCH, DELETE, VIEW, ANY, MATCH)
- Система групп маршрутов
- Middleware support
- Named routes
- Rate Limiting
- IP фильтрация (whitelist/blacklist)
- Domain routing
- HTTPS enforcement
- Route caching
- URL Generator
- Multiple route loaders:
  - JsonLoader
  - YamlLoader
  - XmlLoader
  - PhpLoader
  - AttributeLoader
- MiddlewareDispatcher
- Route parameters with constraints
- PSR-7 и PSR-15 совместимость

### Тесты
- 501 юнит-тест
- 13 тестов безопасности
- 5 тестов производительности
- Нагрузочные тесты
- Стресс-тесты
- PHPBench бенчмарки

### Документация
- README.md
- Подробная документация API
- Примеры использования
- Руководство пользователя

## [0.9.0] - 2024-10-15

### Добавлено
- Первая beta версия
- Базовая маршрутизация
- Поддержка параметров
- Простые группы

## [0.5.0] - 2024-10-01

### Добавлено
- Alpha версия
- Proof of concept
- Базовые тесты

---

## Типы изменений

- **Добавлено** - для новой функциональности
- **Изменено** - для изменений в существующей функциональности
- **Устарело** - для функциональности, которая скоро будет удалена
- **Удалено** - для удаленной функциональности
- **Исправлено** - для исправления багов
- **Безопасность** - для исправлений уязвимостей

---

[Unreleased]: https://github.com/zorinalexey/cloud-casstle-http-router/compare/v1.1.1...HEAD
[1.1.1]: https://github.com/zorinalexey/cloud-casstle-http-router/compare/v1.1.0...v1.1.1
[1.1.0]: https://github.com/zorinalexey/cloud-casstle-http-router/compare/v1.0.0...v1.1.0
[1.0.0]: https://github.com/zorinalexey/cloud-casstle-http-router/compare/v0.9.0...v1.0.0
[0.9.0]: https://github.com/zorinalexey/cloud-casstle-http-router/compare/v0.5.0...v0.9.0
[0.5.0]: https://github.com/zorinalexey/cloud-casstle-http-router/releases/tag/v0.5.0

