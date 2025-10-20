# Changelog

[English](../en/CHANGELOG.md) | [Русский](../../CHANGELOG.md) | [Deutsch](../de/CHANGELOG.md) | **Français** | [中文](../zh/CHANGELOG.md)

---


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

### Corrigé
- Добавлен параметр `protocol` в методы `dispatch` фасада и роутера
- Исправлена пустая строка после statement в JsonLoaderTest
- Обновлена конфигурация Rector для исключения false-positive предупреждений

### Улучшено
- Полная совместимость с PHP 8.4
- Улучшена документация
- Добавлены детальные отчеты по тестированию

## [1.1.0] - 2024-12-01

### Ajouté
- Expression Language для сложных условий маршрутизации
- Plugin система для расширяемости
- Auto-naming для маршрутов
- Port-based routing
- Улучшенная система тегов
- BanManager для автоматической блокировки IP
- TimeUnit enum для удобного указания временных интервалов
- Route dumper для экспорта маршрутов
- UrlMatcher для продвинутого сопоставления URL

### Modifié
- Оптимизирована система индексов для поиска маршрутов
- Улучшена производительность Rate Limiter
- Рефакторинг RouteCompiler для лучшей производительности

### Corrigé
- Проблемы с глубокой вложенностью групп
- Memory leaks при большом количестве маршрутов
- Некорректная работа whitelist/blacklist IP
  
## [1.0.0] - 2024-11-01

### Ajouté
- Базовая функциональность роутера
- Support всех HTTP методов (GET, POST, PUT, PATCH, DELETE, VIEW, ANY, MATCH)
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

### Documentation
- README.md
- Подробная документация API
- Exemples использования
- Руководство пользователя

## [0.9.0] - 2024-10-15

### Ajouté
- Первая beta версия
- Базовая маршрутизация
- Support параметров
- Простые группы

## [0.5.0] - 2024-10-01

### Ajouté
- Alpha версия
- Proof of concept
- Базовые тесты

---

## Типы изменений

- **Ajouté** - для новой функциональности
- **Modifié** - для изменений в существующей функциональности
- **Устарело** - для функциональности, которая скоро будет удалена
- **Supprimé** - для удаленной функциональности
- **Corrigé** - для исправления багов
- **Sécurité** - для исправлений уязвимостей

---

[Unreleased]: https://github.com/zorinalexey/cloud-casstle-http-router/compare/v1.1.1...HEAD
[1.1.1]: https://github.com/zorinalexey/cloud-casstle-http-router/compare/v1.1.0...v1.1.1
[1.1.0]: https://github.com/zorinalexey/cloud-casstle-http-router/compare/v1.0.0...v1.1.0
[1.0.0]: https://github.com/zorinalexey/cloud-casstle-http-router/compare/v0.9.0...v1.0.0
[0.9.0]: https://github.com/zorinalexey/cloud-casstle-http-router/compare/v0.5.0...v0.9.0
[0.5.0]: https://github.com/zorinalexey/cloud-casstle-http-router/releases/tag/v0.5.0

